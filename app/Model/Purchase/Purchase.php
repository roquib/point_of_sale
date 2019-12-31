<?php

namespace App\Model\Purchase;

use App\Model;
use App\Model\Others\Supplier;
use App\User;

class Purchase extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userDes()
    {
        return $this->belongsTo(User::class)->orderBy('name');
    }

    public function product()
    {
        // return $this->belongsTo('App\Product', 'foreign_key', 'lpcal_key');
        return $this->belongsTo('App\Product', 'product_code', 'product_code');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);

        // return $this->belongsTo(Supplier::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany('App\Model\Purchase\PurchaseDetail', 'pur_inv_no', 'pur_inv_no')->with('product');
    }
}
