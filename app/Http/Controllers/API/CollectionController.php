<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;

class CollectionController extends Controller
{
  public function index()
  {
      // load page with initial data
      return Collection::latest()->paginate(5);
  }
  public function collection_list()
  {
      // load page with initial data
      return DB::table('collections')->paginate(255);
  }

  public function store(Request $request)
  {
    // dd($request->all());
    // validation
    $this->validate($request, [
      'user_id' => 'required',
      'company_id' => 'required',
      'point_id' => 'required',
      'remarks' => 'required',
      'amount' => 'required',
    ]);
    // insert data
    return Collection::create([
      'user_id'      => $request['user_id'],
      'company_id'      => $request['company_id'],
      'point_id'      => $request['point_id'],
      'remarks'      => $request['remarks'],
      'amount'      => $request['amount'],
    ]);
  }


  public function show($id)
  {
      // display single item detail
  }

  public function update(Request $request, $id)
  {
      // update data
      $collection = Collection::findOrFAil($id);
      $this->validate($request, [
        'user_id' => 'required',
        'company_id' => 'required',
        'point_id' => 'required',
        'remarks' => 'required',
        'amount' => 'required',
        ]);
      $collection->update($request->all());
      return ['message'=>'Getting id is: ' . $id];
  }

  public function destroy($id)
  {
      //  delete
      // select id
      $collection = Collection::findOrFail($id);
      // delete user
      $collection->delete();
      // redirect back
      return ['message' => 'Collection deleted successfully'];
  }


}
