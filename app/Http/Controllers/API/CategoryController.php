<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Category;

class CategoryController extends Controller
{
  public function index($mode = null)
  {
    if ($mode) {
      return "get list";
    }
    return Category::latest()->paginate(10);
  }

  public function category_list()
  {
    return Category::paginate(255);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'         => 'required|string|max:255|min:2|unique:categories',
    ]);

    Category::create(['name' => $request['name']]);
  }


  public function show($id)
  {
    // display single item detail
  }

  public function update(Request $request, Category $category)
  {
    $this->validate($request, [
      'name'      => 'required|string|max:255|min:2|unique:categories,' . $category->id
    ]);
    $category->update($request->all());
    return ['message' => 'Category Updated successful'];
  }

  public function destroy(Category $category)
  {
    $category->delete();
    return ['message' => 'Category deleted successfully'];
  }
}
