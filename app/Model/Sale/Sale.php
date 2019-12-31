<?php

namespace App\Model\Sale;

use App\Model;
use App\Model\Others\Customer;

class Sale extends Model
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
        return $this->belongsTo('App\Model\Others\Product', 'product_code', 'product_code');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems()
    {
        return $this->hasMany('App\Model\Sale\SaleDetail', 'sale_inv_no', 'sale_inv_no')->with('product');
    }
}
