<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Brand;

class BrandController extends Controller
{
  public function index($mode = null)
  {
    if ($mode) {
      return "get list";
    }
    return Brand::latest()->paginate(10);
  }

  // public function brand_list()
  // {
  //   return Brand::paginate(255);
  // }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'         => 'required|string|max:255|min:2|unique:brands',
    ]);

    Brand::create(['name' => $request['name']]);
  }


  public function show($id)
  {
    // display single item detail
  }

  public function update(Request $request, Brand $brand)
  {
    $this->validate($request, [
      'name'      => 'required|string|max:255|min:2|unique:brands,' . $brand->id
    ]);
    $brand->update($request->all());
    return ['message' => 'Brand Updated successful'];
  }

  public function destroy(Brand $brand)
  {
    $brand->delete();
    return ['message' => 'Brand deleted successfully'];
  }
}
