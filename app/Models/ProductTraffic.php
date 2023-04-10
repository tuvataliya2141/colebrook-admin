<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTraffic extends Model
{
    use HasFactory;
    protected $table = "product_traffic";

    protected $fillable = [
        'user_id','product_id'
    ];

    public function getTranslation($field = '', $lang = false) {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product()
    {
        return $this->hasOne(User::class,'id','product_id');
    }
}
