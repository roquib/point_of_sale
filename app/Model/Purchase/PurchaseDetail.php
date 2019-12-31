<?php

namespace App\Model\Purchase;

use App\Model;

class PurchaseDetail extends Model
{
    // relation with product
    public function product()
    {
        return $this->hasOne('App\Model\Others\Product', 'product_code', 'product_code');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'id');
    }
}
