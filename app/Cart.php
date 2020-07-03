<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    //
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function products()
    {
        return $this->hasMany('App\Product','product_id','id');
    }
}
