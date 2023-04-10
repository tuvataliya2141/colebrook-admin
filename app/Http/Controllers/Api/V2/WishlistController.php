<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\WishlistCollection;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index(Request $request,$id)
    {
        if(isset($request->temp_wish_id)){
            $product_ids = Wishlist::where('temp_wish_id', $request->temp_wish_id)->pluck("product_id")->toArray();
            $existing_product_ids = Product::whereIn('id', $product_ids)->pluck("id")->toArray();
            $query = Wishlist::query()->with('product');
            $query->whereIn("product_id", $existing_product_ids);
        }else{
            $product_ids = Wishlist::where('user_id', $id)->pluck("product_id")->toArray();
            $existing_product_ids = Product::whereIn('id', $product_ids)->pluck("id")->toArray();
            $query = Wishlist::query()->with('product.stocks');
            $query->where('user_id', $id)->whereIn("product_id", $existing_product_ids);
        }
        return new WishlistCollection($query->latest()->get());
    }

    public function store(Request $request)
    {
        Wishlist::updateOrCreate(
            ['user_id' => $request->user_id, 'product_id' => $request->product_id]
        );
        return response()->json(['message' => translate('Product is successfully added to your wishlist')], 201);
    }

    public function destroy($id)
    {
        try {
            Wishlist::destroy($id);
            return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your wishlist')], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 200);
        }

    }

    public function add(Request $request)
    {
        $Obj = ['product_id' => $request->product_id, 'user_id' => $request->user_id];
        if($request->temp_wish_id){
            $Obj = ['product_id' => $request->product_id, 'temp_wish_id' => $request->temp_wish_id];
        }
        $product = Wishlist::where($Obj)->count();
        if ($product > 0) {
            if($request->user_id && $request->temp_wish_id){
                $WishlistId = Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first();
                $tempWishlistId = Wishlist::where(['product_id' => $request->product_id, 'temp_wish_id' => $request->temp_wish_id])->first();
                if($WishlistId && $tempWishlistId){
                    $tempWishlistId->delete();
                }else{
                    Wishlist::updateOrCreate([
                        'user_id' => $request->user_id,
                        'product_id' => $request->product_id
                    ],[
                        'user_id' => $request->user_id,
                        'product_id' => $request->product_id,
                        'temp_wish_id' => null
                    ]);
                }
            }
            return response()->json([
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first()->id
            ], 200);
        } else {
            if($request->user_id){
                Wishlist::updateOrCreate([
                    'user_id' => $request->user_id,
                    'product_id' => $request->product_id
                ],[
                    'user_id' => $request->user_id,
                    'product_id' => $request->product_id
                ]);
                $WishlistId = Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first()->id;
            }else{
                if(!Wishlist::where(['temp_wish_id' => $request->temp_wish_id, 'product_id' => $request->product_id])->first()){
                    Wishlist::create(
                        ['temp_wish_id' => $request->temp_wish_id, 'product_id' => $request->product_id]
                    );
                }
                $WishlistId = Wishlist::where(['product_id' => $request->product_id, 'temp_wish_id' => $request->temp_wish_id])->first()->id;
            }

            if($request->user_id && $request->temp_wish_id){
                $WishlistIddata = Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first();
                $tempWishlistId = Wishlist::where(['product_id' => $request->product_id, 'temp_wish_id' => $request->temp_wish_id])->first();
                if($WishlistIddata && $tempWishlistId){
                    $tempWishlistId->delete();
                }else{
                    Wishlist::updateOrCreate([
                        'user_id' => $request->user_id,
                        'product_id' => $request->product_id
                    ],[
                        'user_id' => $request->user_id,
                        'product_id' => $request->product_id,
                        'temp_wish_id' => null
                    ]);
                }
            }
            return response()->json([
                'message' => translate('Product added to wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)$WishlistId
            ], 200);
        }

    }

    public function remove(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->count();
        if ($product == 0) {
            return response()->json([
                'message' => translate('Product in not in wishlist'),
                'is_in_wishlist' => false,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => 0
            ], 200);
        } else {
            Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->delete();

            return response()->json([
                'message' => translate('Product is removed from wishlist'),
                'is_in_wishlist' => false,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => 0
            ], 200);
        }
    }

    public function isProductInWishlist(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->count();
        if ($product > 0)
            return response()->json([
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first()->id
            ], 200);

        return response()->json([
            'message' => translate('Product is not present in wishlist'),
            'is_in_wishlist' => false,
            'product_id' => (integer)$request->product_id,
            'wishlist_id' => 0
        ], 200);
    }
}
