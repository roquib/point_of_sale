<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  // public function __construct() {
  //   $this->middleware('auth:api');
  // }

  public function index()
  {
    return User::paginate(10);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'first_name' => 'required|string|max:255',
      'email'      => 'required|string|email|max:191|unique:users',
      'password'   => 'required|string|min:4|max:191',
      'role'       => 'required',
    ]);

    // insert data
    return User::create([
      'first_name'   => $request['first_name'],
      'last_name'    => $request['last_name'],
      'email'        => $request['email'],
      'gender'       => $request['gender'],
      'phone_number' => $request['phone_number'],
      'role'         => $request['role'],
      'address_1'    => $request['address_1'],
      'address_2'    => $request['address_2'],
      'city'         => $request['city'],
      'state'        => $request['state'],
      'zip'          => $request['zip'],
      'country'      => $request['country'],
      'comments'     => $request['comments'],
      'password'     => Hash::make($request['password']),
    ]);
  }


  public function show($id)
  {
    // display single item detail
  }

  public function update(Request $request, User $user)
  {
    // update data

    $this->validate($request, [
      'first_name'  => 'required|string|max:255',
      'email'       => 'required|string|email|max:191|unique:users,email,' . $user->id,
      'password'    => 'sometimes|min:6|max:191',

    ]);

    $user->update($request->all());
    return "Success";
  }

  public function destroy(User $user)
  {
    // select id
    $user->delete();
    return "Success";
  }
}
