<?php

namespace App\Http\Controllers\API\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Customer;
use App\Model\Others\CustomerDetail;
use App\Model\Sale\Sale;
use App\Model\Sale\SaleReturn;
use App\Model\Sale\SaleReturnDetail;
use App\UpdateData;

class SaleReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sale = SaleReturn::join('customers', 'customers.id', '=', 'sale_returns.customer_id')
            ->orderBy('customers.name', 'asc')
            ->with('customer')
            ->with('SaleReturnItems')
            ->with('user');

        return response()->JSON($sale->paginate(3));
    }

    public function returnableProducts($customer)
    {
        $products = Sale::join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('customers.name', '=', $customer)
            ->with('saleItems');

        return response()->JSON($products->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $returnInfo     = json_decode($request->returnInfo);
        $returnItems    = json_decode($request->returnItems);

        $discount       = 0;
        $user_id        = auth('api')->user()->id;
        $customer_id    = Customer::where('name', '=', $returnInfo->customer)->first()->id;
        $grand_total    = $returnInfo->grandTotal - $discount;

        $saleReturn        = SaleReturn::create([
            'sale_rtn_no'  => '124',
            'user_id'      => $user_id,
            'customer_id'  => $customer_id,
            'totalQty'     => $returnInfo->totalQty,
            'discount'     => $discount,
            'grandTotal'   => $grand_total,
        ]);

        $invoice_number = "sale-rtn-" . strval($saleReturn->id + 100000);
        $saleReturn->sale_rtn_no  = $invoice_number;
        $saleReturn->save();

        (new UpdateData())->addToCustomerDetails(new CustomerDetail(), $customer_id, 'credit', $grand_total, 'Sale Return', $invoice_number);

        foreach ($returnItems as $item) {
            SaleReturnDetail::create([
                'sale_rtn_no'  => $invoice_number,
                'quantity'     => $item->productQuantity,
                'product_code' => $item->productCode,
                'price'        => $item->productPrice,
            ]);

            (new UpdateData())->addToStockDetails('credit', $item->productCode, 'Sale Return', $invoice_number, $item->productQuantity);
            (new UpdateData())->updateStock('+', $item->productCode, $item->productQuantity);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($sale_rtn_no)
    {
        $porducts = SaleReturnDetail::where('sale_rtn_no', '=', $sale_rtn_no)->get();
        foreach ($porducts as $porduct) {
            (new UpdateData())->updateStock('-', $porduct->product_code, $porduct->quantity);
        }
        (new UpdateData())->deleteAll('sale_return', $sale_rtn_no);
    }
}
