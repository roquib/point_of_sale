<?php

namespace App\Http\Controllers\API\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Customer;
use App\Model\Others\CustomerDetail;
use App\Model\Sale\Sale;
use App\Model\Sale\SaleDetail;
use App\Model\Stocks\StockDetail;
use App\UpdateData;

class SaleController extends Controller
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
        $sales = Sale::select('sales.*')->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->orderBy('customers.name', "asc")
            ->with('customer')
            ->with('saleItems')
            ->with('user');

        return response()->JSON($sales->paginate(10));
    }
    public function getInvoice($sale_inv_no)
    {
        $invoice = Sale::select('sales.*')->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->orderBy('customers.name', "asc")
            ->with('customer')
            ->with('saleItems')
            ->with('user')
            ->where('pur_inv_no', '=', $sale_inv_no)
            ->first();

        return response()->JSON($invoice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customerInfo   = json_decode($request->customerInfo);
        $discount       = 0;

        $user_id        = auth('api')->user()->id;
        $customer_id    = Customer::where('name', '=', $customerInfo->name)->first()->id;
        $grand_total    = $customerInfo->grandTotal - $discount;

        $sale = Sale::create([
            'sale_inv_no'  => '124',
            'user_id'      => $user_id,
            'totalQty'     => $customerInfo->totalQuantity,
            'subTotal'     => $customerInfo->grandTotal,
            'discount'     => $discount,
            'grandTotal'   => $grand_total,
        ]);

        $invoice_number = "sale-inv-" . strval($sale->id + 100000);

        if ($customerInfo->cashSale) {
            $sale->sale_inv_no  = $invoice_number;
            $sale->name         = $customerInfo->name;
            $sale->mobile       = $customerInfo->mobile;
            $sale->address      = $customerInfo->address;
        } else {
            $sale->customer_id  = $customer_id;
            $sale->sale_inv_no  = $invoice_number;
        }
        $sale->save();

        (new UpdateData())->addToCustomerDetails(new CustomerDetail(), $customer_id, 'debit', $grand_total, 'Sale', $invoice_number);

        foreach (json_decode($request->shopItems) as $item) {
            SaleDetail::create([
                'sale_inv_no'  => $invoice_number,
                'quantity'     => $item->quantity,
                'product_code' => $item->product_code,
                'price'        => $item->price,
            ]);

            (new UpdateData())->addToStockDetails('debit', $item->product_code, 'Sale', $invoice_number, $item->quantity);
            (new UpdateData())->updateStock('-', $item->product_code, $item->quantity);
        }
        return "Success";
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
    public function update(Request $request, $sale_inv_no)
    {
        $oldSale       = json_decode($request->oldSale);
        $customerInfo  = json_decode($request->customerInfo);
        $shopItems     = json_decode($request->shopItems);
        $customer_id   = Customer::where('name', '=', $customerInfo->name)->first()->id;


        Sale::where('sale_inv_no', $sale_inv_no)
            ->update(
                [
                    'customer_id'  => $customer_id,
                    'totalQty'     => $customerInfo->totalQuantity,
                    'subTotal'     => $customerInfo->grandTotal,
                    'grandTotal'   => $customerInfo->grandTotal,
                ]
            );

        CustomerDetail::where('customer_id', '=', $oldSale->id)
            ->update([
                'customer_id'   => $customer_id,
                'debit'         => $customerInfo->grandTotal,
            ]);

        foreach ($shopItems as $item) {
            $sale = SaleDetail::where('sale_inv_no', '=', $sale_inv_no)
                ->where('product_code', '=', $item->product_code)
                ->update([
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                ]);
            if (!$sale) {
                SaleDetail::create([
                    'sale_inv_no'   => $sale_inv_no,
                    'quantity'      => $item->quantity,
                    'product_code'  => $item->product_code,
                    'price'         => $item->price,
                ]);
                StockDetail::create([
                    'product_code' => $item->product_code,
                    'description'  => "Sale",
                    'source_id'    => $sale_inv_no,
                    'debit'        => $item->quantity,
                ]);
                (new UpdateData())->updateStock('-', $item->product_code, $item->quantity);
            } else {
                StockDetail::where('source_id', '=', $sale_inv_no)
                    ->where('product_code', '=', $item->product_code)->update([
                        'debit'       => $item->quantity,
                    ]);
            }
            $sale = null;
        }

        foreach ($oldSale->sale_items as $item) {
            $exist = false;
            $shop_quantity = 0;

            foreach ($shopItems as $shopItem) {
                if ($shopItem->product_code == $item->product_code) {
                    $shop_quantity = $shopItem->quantity;
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                (new UpdateData())->updateStock('+', $item->product_code, $item->quantity);

                $sale = SaleDetail::where('sale_inv_no', '=', $sale_inv_no)
                    ->where('product_code', '=', $item->product_code)->first();
                $sale->delete();
            } else {
                (new UpdateData())->updateStock('-', $item->product_code, ($shop_quantity - $item->quantity));
            }
        }
        return "Update " . $oldSale->sale_inv_no . "Successfull";
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($sale_inv_no)
    {
        $porducts = SaleDetail::where('sale_inv_no', '=', $sale_inv_no)->get();
        foreach ($porducts as $porduct) {
            (new UpdateData())->updateStock('+', $porduct->product_code, $porduct->quantity);
        }
        (new UpdateData())->deleteAll('sale', $sale_inv_no);
    }
}
