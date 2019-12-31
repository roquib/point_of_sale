<?php

namespace App\Model\Sale;

use App\Model;

class SaleReturnDetail extends Model
{
    public function product()
    {
        return $this->hasOne('App\Model\Others\Product', 'product_code', 'product_code');
    }
}
