<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Purchase;


class AdminController extends Controller
{
  // populate dashboard
  public function dashboard()
  {
    return view('master.admin');
  }

  public function user()
  {
    return view('admin.user');
  }

  public function point()
  {
    return view('admin.point');
  }


  public function unit()
  {
    return view('admin.unit');
  }
  public function category()
  {
    return view('admin.category');
  }
  public function product()
  {
    return view('admin.product')->with('products', Product::all());
  }




  public function customer()
  {
    return view('admin.customer');
  }
  public function supplier()
  {
    return view('admin.supplier');
  }

  public function purchase()
  {
    return view('admin.purchase');
  }
}
