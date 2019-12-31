<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Product;
use App\Model\Stocks\Stock;
use App\Model\Stocks\StockDetail;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return "Akash";
        return Product::with('currentStock')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_code)
    {
        $stocks = StockDetail::where('product_code', '=', $product_code)->paginate(10);
        return response()->JSON($stocks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function updateStock($addMode, $product_code, $quantity)
    {
        $stock = Stock::where('product_code', '=', $product_code)->first();
        if ($addMode == '+') {
            $stock->quantity += $quantity;
        } else {
            $stock->quantity -= $quantity;
        }
        $stock->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
