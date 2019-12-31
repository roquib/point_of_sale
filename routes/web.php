<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Auth::routes([
  'register' => false, // Registration Routes dissable
]);


Route::middleware(['auth'])->group(function () {
  Route::get('/',     function () {
    return view('home');
  });
  Route::get('/home', function () {
    return view('home');
  });

  Route::get('/{vue_capture?}', function () {
    return view('home');
  })->where('vue_capture', '[\/\w\.-]*');



  // Route::get('/user', 'AdminController@user')->name('user');
  // Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
  // Route::get('/category', 'AdminController@category')->name('category');
  // Route::get('/customer', 'AdminController@customer')->name('customer');
  // Route::get('/supplier', 'AdminController@supplier')->name('supplier');
  // Route::get('/purchase', 'AdminController@purchase')->name('purchase');
  // Route::get('/purchase-list', 'AdminController@purchase_list')->name('purchase.list');
  // Route::get('/product', 'AdminController@product')->name('product');
  // Route::get('/unit', 'AdminController@unit')->name('unit');
  // Route::get('/point', 'AdminController@point')->name('point');

});

// vue route support after using history mode
// Route::get('/{vue_capture?}', function () {
//     return view('home');
// })->where('vue_capture', '[\/\w\.-]*');
