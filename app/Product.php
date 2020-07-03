<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    //
    public function category()
    {
        return $this->belongsTo('App\ProductCategory','category_id','id')->withDefault();
    }

    public function files()
    {
        return $this->hasMany('App\Files','product_id','id');
    }
}
