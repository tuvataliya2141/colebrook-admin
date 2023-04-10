<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class AdditionalCategory extends Model
{
    protected $fillable = [
        'category_id', 'product_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
