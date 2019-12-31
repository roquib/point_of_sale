<?php

namespace App\Model\Others;

use App\Model;
use App\Model\Sale\SaleDetail;
use App\Model\Stocks\Stock;
use App\Model\Stocks\StockDetail;

class Product extends Model
{
  public function category()
  {
    return $this->belongsTo(Category::class);
  }
  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }
  // relation with purchase detail
  public function purchases()
  {
    return $this->belongsTo(PurchaseDetail::class);
  }
  public function sales()
  {
    return $this->belongsTo(SaleDetail::class);
  }
  public function currentStock()
  {
    return $this->hasOne(Stock::class, 'product_code', 'product_code');
  }
  // public function stock_details()
  // {
  //   return $this->hasMany(StockDetail::class, 'product_code', 'product_code');
  // }
}
