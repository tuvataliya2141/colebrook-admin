<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'title' => $data,
                    'sub_title' => $data,
                    'photo' => api_asset($data),
                    'url' => route('home'),
                    'position' => 1
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => "banner fatch successfully",
            'status' => 200
        ];
    }
}
