<?php

namespace App\Model\Sale;

use App\Model;
use App\Model\Others\Customer;

class SaleReturn extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userDes()
    {
        return $this->belongsTo('App\User')->orderBy('name');
    }

    public function product()
    {
        // return $this->belongsTo('App\Product', 'foreign_key', 'lpcal_key');
        return $this->belongsTo('App\Model\Others\Product', 'product_code', 'product_code');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function SaleReturnItems()
    {
        return $this->hasMany('App\Model\Sale\SaleReturnDetail', 'sale_rtn_no', 'sale_rtn_no')->with('product');
    }
}
