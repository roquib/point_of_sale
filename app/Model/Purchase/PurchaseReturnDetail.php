<?php

namespace App\Model\Purchase;

use App\Model;

class PurchaseReturnDetail extends Model
{
    public function product()
    {
        return $this->hasOne('App\Model\Others\Product', 'product_code', 'product_code');
    }
}
