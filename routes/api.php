<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResources([
    'user'              => 'API\UserController',
    'point'             => 'API\PointController',
    'unit'              => 'API\UnitController',
    'category'          => 'API\CategoryController',
    'brand'             => 'API\BrandController',
    'product'           => 'API\ProductController',
    'supplier'          => 'API\SupplierController',
    'customer'          => 'API\CustomerController',
    'purchase'          => 'API\Purchase\PurchaseController',
    'sale'              => 'API\Sale\SaleController',
    'sale-return'       => 'API\Sale\SaleReturnController',
    'purchase-return'   => 'API\Purchase\PurchaseReturnController',
    'collection'        => 'API\CollectionController',
    'payment'           => 'API\CollectionController',
]);

Route::get('category-list', 'API\CategoryController@category_list');
Route::get('supplier-list', 'API\SupplierController@supplier_list');
Route::get('customer-list', 'API\CustomerController@customer_list');
Route::get('unit-list', 'API\UnitController@unit_list');
Route::get('supplier/previous-dues/{id}', 'API\SupplierController@previousDue');

Route::get('/supplier/search/{search}', 'API\SupplierController@searchSupplier');

// invoice
Route::get('/sale/invoice/{sale_inv_no}', 'API\Sale\SaleController@getInvoice');
Route::get('/purchase/invoice/{pur_inv_no}', 'API\Purchase\PurchaseController@getInvoice');


// product
Route::get('product-list', 'API\ProductController@product_list');
Route::get('/product/single/{product_code}', 'API\ProductController@getProduct');
Route::get('/product/paginate/{item}', 'API\ProductController@paginateProduct');
Route::get('/product/select/{select}', 'API\ProductController@selectedProduct');
Route::get('/product/{perPage}/filter/{order}/order/{orderField}/as', 'API\ProductController@filterProduct');
Route::get('/product/search/{search}', 'API\ProductController@searchProduct');
Route::get('/customer/search/{search}', 'API\CustomerController@customer_search');



Route::get('/customer/search/{search}', 'API\CustomerController@customer_search');

// sales returnable products
Route::get('/sale/returnable/products/{customer}', 'API\Sale\SaleReturnController@returnableProducts');
Route::get('/purchase/returnable/products/{supplier}', 'API\Purchase\PurchaseReturnController@returnableProducts');


// stock
Route::get('/current/stock', 'API\StockController@index');
Route::get('/stock/details/{product_code}', 'API\StockController@show');
