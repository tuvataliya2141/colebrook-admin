<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Product extends Model {
    // Code cange by Brijesh on 17-fab-22 CR#2 - start
    protected $fillable = [
        'name', 'added_by', 'user_id', 'category_id', 'brand_id', 'video_provider', 'video_link', 'unit_price',
        'purchase_price', 'unit', 'slug', 'colors', 'choice_options', 'variations', 'thumbnail_img', 'meta_title', 'meta_description',
        'bullet_points_description', 'is_text_box', 'is_file_upload', 'is_file_type'
    ];
    // Code cange by Brijesh on 17-fab-22 CR#2 - end

    protected $with = ['product_translations', 'taxes'];

    public function getTranslation($field = '', $lang = false) {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations() {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks() {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes() {
        return $this->hasMany(ProductTax::class);
    }

    public function image() {
        return $this->hasOne(Upload::class,'id','thumbnail_img');
    }

    public function photos()
    {
        return implode(", ", $this->image()->pluck('id')->implode(','));
    }

}
