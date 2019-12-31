<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Point;

class PointController extends Controller
{
  public function index()
  {
    return Point::latest()->paginate(5);
  }
  public function point_list()
  {
    return Point::paginate(255);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'           => 'required|string|max:255|min:2',
      'contact_person' => 'required|string|max:255|min:2',
      'phone_number'   => 'required|string|max:16|min:2',
    ]);
    // insert data
    return Point::create([
      'name'           => $request['name'],
      'description'    => $request['description'],
      'location'       => $request['location'],
      'phone_number'   => $request['phone_number'],
      'contact_person' => $request['contact_person'],
    ]);
  }


  public function show($id)
  {
    // display single item detail
  }

  public function update(Request $request, Point $point)
  {
    $this->validate($request, [
      'name'             => 'required|string|max:255|min:2',
      'contact_person'   => 'required|string|max:255|min:2',
      'phone_number'     => 'required|string|max:16|min:2',
    ]);
    $point->update($request->all());
    return "success";
  }

  public function destroy(Point $point)
  {
    $point->delete();
    return "deleted";
  }
}
