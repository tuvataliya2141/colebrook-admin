<?php

namespace App\Http\Resources\V2;

use App\Models\AttributeValue;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $imagePath = $categoryName = '';
                $attributes_ids = $attributes = [];
                if($data->thumbnail_img){
                    $imagePath = api_asset($data->thumbnail_img);
                }
                if(isset($data->category)){
                    $categoryName = $data->category->name;
                }
                if($data->attributes){
                    foreach(json_decode($data->choice_options) as $key => $value){
                        foreach($value->values as $valuekey => $item){
                            $attributes_ids [] = $item;
                        }
                    }
                    $attributes = AttributeValue::whereIn('value',$attributes_ids)->get();
                }
                $attributesize = [0];
                $attributesize = json_decode($data->choice_options);
                if ($attributesize) {
                    $Msize = $attributesize[0]->values;
                } else {
                    $Msize = [];
                }
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'photos' => explode(',', $data->photos),
                    'brand' => isset($data->brand->name) ? $data->brand->name : '',
                    'multipleSize' => $Msize,
                    'variants' => isset($data->stocks[0]) ? $data->stocks[0] : '',
                    'thumbnail_image' => $imagePath,
                    'reviews' => $data->reviews,
                    'base_price' => (double) home_base_price($data, false),
                    'base_discounted_price' => (double) home_discounted_base_price($data, false),
                    'todays_deal' => (integer) $data->todays_deal,
                    'featured' =>(integer) $data->featured,
                    'unit' => $data->unit,
                    'description' => $data->description,
                    'category' => $categoryName,
                    'colors' => json_decode($data->colors),
                    'discount' => (double) discount_in_percentage($data),
                    'discount_type' => $data->discount_type,
                    'rating' => (double) $data->rating,
                    'sales' => (integer) $data->num_of_sale,
                    'links' => [
                        'details' => route('products.show', $data->id),
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id)
                    ]
                ];
                // if(isset($data->stocks[0])) {
                // }
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
