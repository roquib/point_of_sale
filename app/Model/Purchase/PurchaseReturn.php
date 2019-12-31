<?php

namespace App\Model\Purchase;

use App\Model;
use App\Model\Others\Supplier;

class PurchaseReturn extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userDes()
    {
        return $this->belongsTo(User::class)->orderBy('name');
    }

    public function product()
    {
        // return $this->belongsTo('App\Product', 'foreign_key', 'lpcal_key');
        return $this->belongsTo('App\Model\Others\Product', 'product_code', 'product_code');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);

        // return $this->belongsTo(Supplier::class);
    }

    public function purchaseReturnItems()
    {
        return $this->hasMany('App\Model\Purchase\PurchaseReturnDetail', 'pur_rtn_no', 'pur_rtn_no')->with('product');
    }
}
