<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\CategoryCollection;
use App\Http\Resources\V2\BrandCollection;
use App\Models\Product;
use App\Models\Color;
use App\Models\SizeCharts;
use Illuminate\Http\Request;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use DB;
use App\Models\Upload;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductTraffic;
use App\Models\Review;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductMiniCollection(Product::latest()->paginate(10));
    }

    public function show($id)
    {
        return new ProductDetailCollection(Product::where('id', $id)->get());
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;

        $products = Product::whereIn('category_id', $category_ids);

        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }


    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return Cache::remember('app.todays_deal', 86400, function(){
            $products = Product::where('todays_deal', 1);
            return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
    {
        return Cache::remember('app.best_selling_products', 86400, function(){
            $products = Product::orderBy('num_of_sale', 'desc');
            return new ProductMiniCollection(filter_products($products)->limit(4)->inRandomOrder()->get());
        });
    }

    public function related($id)
    {
        return Cache::remember("app.related_products-$id", 86400, function() use ($id){
            $product = Product::find($id);
            $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id);
            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }

    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1);

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($query) use ($word){
                        $query->where('name', 'like', '%'.$word.'%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }


        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;


        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }



        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (double)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : api_asset($product_stock->image) 
        ]);
    }

    public function home(Request $request,$category = "all")
    {
        $category = Category::where('name',$request->category)->first();
        if($category){
            $products = Product::with(['stocks','category','image','reviews','brand'])->where('category_id',$category->id)->get();
        }else{
            $products = Product::with(['stocks','category','image','reviews','brand'])->inRandomOrder()->get();
        }
        return new ProductCollection($products);
    }


    public function GetProductDetail(Request $request)
    {
        $product = Product::with(['stocks','category','image','reviews'])->where('slug',$request->slug)->first();
        // echo '<pre>'; print_r($product->id); die;
        // dd($product);
        $userReview = 'no';
        if($product){
            if(isset($request->user_id)){
                $existProduct = ProductTraffic::where(['user_id' => $request->user_id , 'product_id' => $product->id])->first();
                if(!$existProduct){
                    ProductTraffic::create(['user_id' => $request->user_id , 'product_id' => $product->id]);
                } else {
                    $total_visit = 0;
                    $total_visit = $existProduct->total_visit;
                    
                    $existProduct->total_visit = $total_visit + 1;
                    // dd($existProduct);
                    $existProduct->save();
                }
                $orders = DB::table('orders')
                ->select('orders.id', 'orders.user_id', 'order_details.product_id')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('orders.user_id', $request->user_id)
                ->where('order_details.product_id', $product->id)
                ->where('order_details.delivery_status', 'delivered')
                ->first();
                if($orders != null){
                    $check_review = Review::where(['user_id' => $request->user_id , 'product_id' => $product->id])->first();
                    if($check_review == null){
                        $userReview = 'yes';
                    } else {
                        $userReview = 'no';
                    }
                } else {
                    $userReview = 'no';
                }
            }
            $multipleImages = $attribute = [];
            if(isset($product->photos)){
                $images = explode(',',$product->photos);
                foreach ($images as $key => $value) {
                    $upload = Upload::where('id',$value)->first();
                    // if(file_exists(asset('public/'.$upload->file_name))){
                        $multipleImages [$key]= asset('public/'.$upload->file_name);
                    // }
                }
            }
            if($product->attributes){
                $attributes = explode('"',$product->attributes);
                $attribute = AttributeValue::where('attribute_id',$attributes[0])->get();
            }

            $thumImage = '';
            if(isset($product->image->file_name)){
                $thumImage = $product->image->file_name;
            }
            $attributesize = [0];
            $attributesize = json_decode($product->choice_options);

            if ($attributesize) {
              $Msize = $attributesize[0]->values;
            }
            else
            {
                $Msize = [];
            }
            $reviews = [];
            if($product->reviews){
                foreach ($product->reviews as $key => $value) {
                    $re_data = [
                        'id' => $value->id,
                        'product_id' => $value->product_id,
                        'user_id' => $value->user_id,
                        'name' => $value->user->name,
                        'rating' => floatval(number_format($value->rating,1,'.','')),
                        'comment' => $value->comment,
                        'time' => $value->updated_at->diffForHumans()
                    ];
                    $reviews[] = $re_data;
                }

            }
            $catId = $product->category_id;
            $categories = Category::where('id', $catId)->first();
            $sizeChart = SizeCharts::where('name', $categories->name)->first();
            $sizeData = [];
            $sizeImg = '';
            if($sizeChart){
                $sizeData = json_decode($sizeChart->size_values);
                $sizeImg = $sizeChart->image;
            }
            $response = [
                'id' => $product->id,
                'name' => $product->name,
                'rating' => $product->rating,
                'thumbnail_img' => asset('public/'.$thumImage),
                'price' => home_discounted_base_price($product, false),
                'variant' => $product->stocks,
                'oldPrice' => home_base_price($product, false),
                'multipleSize' => $Msize,
                'multipleimage' => $multipleImages,
                'createdate' => \Carbon\Carbon::parse($product->created_at)->format('d-m-Y'),
                'description' => strip_tags($product->description),
                'reviews' => $reviews,
                'contact' => '',
                'ShipOnTime' => '',
                'ChatResponse' => '',
                'offer' => discount_in_percentage($product),
                'return_days' => $product->return_days,
                'replace_days' => $product->replace_days,
                'InStock' => $product->stocks[0]->qty,
                'colors' => json_decode($product->colors),
                'userReview' => $userReview,
                'sizeData' => $sizeData,
                'sizeImg' => api_asset($sizeImg),
            ];
            return response()->json([
                'status' => true,
                'message' => 'product Fatch successfully',
                'data' => $response
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'product Fatch Fail',
                'data' => []
            ],200);
        }
    }

    public function mainAllSearch(Request $request)
    {
        if($request->search){
            $keywords = array();
            $query = $request->search;
            $products = Product::where('published', 1)->where('tags', 'like', '%'.$query.'%')->get();
            foreach ($products as $key => $product) {
                foreach (explode(',',$product->tags) as $key => $tag) {
                    if(stripos($tag, $query) !== false){
                        if(sizeof($keywords) > 5){
                            break;
                        }
                        else{
                            if(!in_array(strtolower($tag), $keywords)){
                                array_push($keywords, strtolower($tag));
                            }
                        }
                    }
                }
            }

            $products = filter_products(Product::query());
            $proData = [];
            $products = $products->where('published', 1)
                            ->where(function ($q) use ($query){
                                foreach (explode(' ', trim($query)) as $word) {
                                    $q->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($q) use ($word){
                                        $q->where('name', 'like', '%'.$word.'%');
                                    });
                                }
                            })
                        ->get();
            // $thumImage = '';
            // if(isset($product->image->file_name)){
            //     $thumImage = $product->image->file_name;
            // }
            foreach ($products as $key => $value) {
                $pro_data = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumbnail_img' => api_asset($value->thumbnail_img),
                ];
                $proData[] = $pro_data;
                
            }
            $categories = Category::where('name', 'like', '%'.$query.'%')->get()->take(3);

            if(sizeof($keywords)>0 || sizeof($categories)>0 || sizeof($products)>0){
                $response = [
                    'products' => $proData,
                    'categories' => $categories,
                    'keywords' => $keywords,
                ];
                if($response){
                    return response()->json([
                        'status' => true,
                        'message' => 'List fatch successfully',
                        'data' => $response
                    ],200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data not found',
                        'data' => []
                    ],200);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                    'data' => []
                ],200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }


    public function productFliter(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        if ($request->size != null && $request->size != "") {
            $size = explode(',', $request->size);
        }

        if ($request->color != null && $request->color != "") {
            $color = explode(',', $request->color);
        }

        $min = $request->min;
        $max = $request->max;

        $products = Product::query();

        $products->where('published', 1);

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }
        
        if($products){
            $response = new ProductCollection($products);
            return response()->json([
                'status' => true,
                'message' => 'List fatch successfully',
                'data' => $response
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }

}
