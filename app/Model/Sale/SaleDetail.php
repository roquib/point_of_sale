<?php

namespace App\Model\Sale;

use App\Model\Sale\Sale;
use App\Model;

class SaleDetail extends Model
{
    // relation with product
    public function product()
    {
        return $this->hasOne('App\Model\Others\Product', 'product_code', 'product_code');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_inv_no');
    }
}
