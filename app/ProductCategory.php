<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];
    //
    public function products()
    {
        return $this->hasMany('App\ProductCategory','product_id','id');
    }
}
