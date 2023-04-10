<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Auth;
use DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reviews = Review::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.product.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        // Code cange by Tarun on 02-fab-22 CR#2 - start
        $product = Product::all();
        $customer = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc')->get();
        return view('backend.product.reviews.create', compact('product', 'customer'));
        // Code cange by Tarun on 02-fab-22 CR#2 - end
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $review = new Review;
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->viewed = '0';
        $review->save();
        $product = Product::findOrFail($request->product_id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        flash(translate('Review has been submitted successfully'))->success();
        return back();
    }

    // Code cange by Tarun on 02-fab-22 CR#2 - start
    public function adminStore(Request $request){
        $review = new Review;
        $review->product_id = $request->product_id;
        $review->user_id = $request->user_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->viewed = '0';
        // echo '<pre>'; print_r($review); die;
        $review->save();
        $product = Product::findOrFail($request->product_id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        flash(translate('Review has been submitted successfully'))->success();
        return redirect()->route('reviews.index');
    }
    // Code cange by Tarun on 02-fab-22 CR#2 - end

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        // Code cange by Tarun on 02-fab-22 CR#2 - start
        $reviews = Review::find($id);
        $product = Product::all();
        $customer = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc')->get();
        return view('backend.product.reviews.edit', compact('reviews','product', 'customer'));
        // Code cange by Tarun on 02-fab-22 CR#2 - end
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        // Code cange by Tarun on 02-fab-22 CR#2 - start
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
        ]);

        $review = Review::find($id);
        $review->product_id = $request->product_id;
        $review->user_id = $request->user_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        $product = Product::findOrFail($request->product_id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        flash(translate('Review has been updated successfully'))->success();
        return redirect()->route('reviews.index');
        // Code cange by Tarun on 02-fab-22 CR#2 - end
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        // Code cange by Tarun on 02-fab-22 CR#2 - start
        Review::find($id)->delete();
        return redirect('reviews');
        // Code cange by Tarun on 02-fab-22 CR#2 - end
    }

    public function updatePublished(Request $request){
        $review = Review::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        $product = Product::findOrFail($review->product->id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        return 1;
    }
}
