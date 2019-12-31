<?php

namespace App\Model\Others;

use App\Model;

class Supplier extends Model
{
    public function product()
    {
        $this->hasOne('App\Product');
    }
    public function purchases()
    {
        return $this->belongsTo(Purchase::class);
    }
}
