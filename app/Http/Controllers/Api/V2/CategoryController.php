<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Models\BusinessSetting;
use App\Models\Category;
use Cache;

class CategoryController extends Controller
{

    public function index($parent_id = 0)
    {
        if(request()->has('parent_id') && is_numeric (request()->get('parent_id'))){
          $parent_id = request()->get('parent_id');
        }
        
        return Cache::remember("app.categories-$parent_id", 86400, function() use ($parent_id){
            return new CategoryCollection(Category::where('parent_id', $parent_id)->get());
        });
    }

    public function featured()
    {
        return new CategoryCollection(Category::where('featured', 1)->get());

        // return Cache::remember('app.featured_categories', 86400, function(){
        //     return new CategoryCollection(Category::where('featured', 1)->get());
        // });
    }

    public function home()
    {
        return Cache::remember('app.home_categories', 86400, function(){
            return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->get());
        });
    }

    public function top()
    {   
        return Cache::remember('app.top_categories', 86400, function(){
            return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->limit(20)->get());
        });
    }

    public function allCategory($parent_id = 0)
    {
        if(request()->has('parent_id') && is_numeric (request()->get('parent_id'))){
          $parent_id = request()->get('parent_id');
        }
        
        // return Cache::remember("app.categories-$parent_id", 86400, function() use ($parent_id){
            $response = new CategoryCollection(Category::where('parent_id', $parent_id)->get());
            $logo = get_setting('header_logo');
            $banner_1_imags = [];
            $banner_img = [];
            if(get_setting('home_banner1_images') != null){
                $banner_1_imags = json_decode(get_setting('home_banner1_images'));
                foreach ($banner_1_imags as $key => $value) {
                    $banner_data['link'] = json_decode(get_setting('home_banner1_links'), true)[$key];
                    $banner_data['img'] = uploaded_asset($banner_1_imags[$key]);
                    $banner_img []= $banner_data;
                }
            } 
            
            if($response){
                return response()->json([
                    'status' => true,
                    'logo' => uploaded_asset($logo),
                    'banner_img' => $banner_img,
                    'message' => 'Category Fatch successfully',
                    'data' => $response
                ],200);
            } else {
                return response()->json([
                    'status' => false,
                    'logo' => uploaded_asset($logo),
                    'banner_img' => $banner_img,
                    'message' => 'Category Fatch Fail',
                    'data' => []
                ],200);
            }
            
        // });
    }
    
    public function homePage($parent_id = 0)
    {
        
        if(request()->has('parent_id') && is_numeric (request()->get('parent_id'))){
          $parent_id = request()->get('parent_id');
        }
        
        // return Cache::remember("app.categories-$parent_id", 86400, function() use ($parent_id){
            $response = new CategoryCollection(Category::where('parent_id', $parent_id)->get());
            $logo = get_setting('header_logo');
            // $logo = asset('public/'.get_setting('header_logo'));
            if($response){
                return response()->json([
                    'status' => true,
                    'logo' => uploaded_asset($logo),
                    'message' => 'Category Fatch successfully',
                    'data' => $response
                ],200);
            } else {
                return response()->json([
                    'status' => false,
                    'logo' => uploaded_asset($logo),
                    'message' => 'Category Fatch Fail',
                    'data' => []
                ],200);
            }
            
        // });
    }
}
