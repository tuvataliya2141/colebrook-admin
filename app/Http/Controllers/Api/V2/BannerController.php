<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\BannerCollection;
use App\Models\Banner;
use App\Models\HomeCard;

class BannerController extends Controller
{

    public function index()
    {
        $banner =  Banner::all();
        $bannerData = [];
        foreach ($banner as $key => $value) {
            $allData = [
                'title' => $value->title,
                'sub_title' => $value->sub_title,
                'url' => $value->url,
                'photo' => api_asset($value->photo),
            ];
            $bannerData []  = $allData;
        }
        return response()->json([
            'status' => true,
            'message' => "banner fatch successfully",
            'data' => $bannerData
        ],200);
        // return new BannerCollection(json_decode($banner, true));
        // return new BannerCollection(json_decode(get_setting('home_banner1_images'), true));
    }
    
    
    public function homeCard()
    {
        $card =  HomeCard::all();
        $cardData = [];
        foreach ($card as $key => $value) {
            $allData = [
                'title' => $value->title,
                'url' => $value->url,
                'image' => api_asset($value->image),
            ];
            $cardData []  = $allData;
        }
        return response()->json([
            'status' => true,
            'message' => "Card fatch successfully",
            'data' => $cardData
        ],200);
    }
}
