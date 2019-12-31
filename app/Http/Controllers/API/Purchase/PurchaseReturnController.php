<?php

namespace App\Http\Controllers\API\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Supplier;
use App\Model\Others\SupplierDetail;
use App\Model\Purchase\Purchase;
use App\Model\Purchase\PurchaseReturn;
use App\Model\Purchase\PurchaseReturnDetail;
use App\UpdateData;

class PurchaseReturnController extends Controller
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
        $purchases = PurchaseReturn::join('suppliers', 'suppliers.id', '=', 'purchase_returns.supplier_id')
            ->orderBy('suppliers.name', "asc")
            ->with('supplier')
            ->with('purchaseReturnItems')
            ->with('user');

        return response()->JSON($purchases->paginate(3));
    }

    // get returnable purchases product list
    public function returnableProducts($supplier)
    {
        $purchases = Purchase::join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')->with('purchaseItems');
        return response()->JSON($purchases->get());
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
        $supplier_id    = Supplier::where('name', '=', $returnInfo->supplier)->first()->id;
        $grand_total    = $returnInfo->grandTotal - $discount;

        $purchaseReturn    =  PurchaseReturn::create([
            'pur_rtn_no'   => '124',
            'user_id'      => $user_id,
            'supplier_id'  => $supplier_id,
            'totalQty'     => $returnInfo->totalQty,
            'discount'     => $discount,
            'grandTotal'   => $returnInfo->grandTotal,
        ]);

        $invoice_number = "pur-rtn-" . strval($purchaseReturn->id + 100000);
        $purchaseReturn->pur_rtn_no  = $invoice_number;
        $purchaseReturn->save();

        (new UpdateData())->addToCustomerDetails(new SupplierDetail(), $supplier_id, 'debit', $grand_total, 'Purchase Return', $invoice_number);

        foreach ($returnItems as $item) {
            PurchaseReturnDetail::create([
                'pur_rtn_no'   => $invoice_number,
                'quantity'     => $item->productQuantity,
                'product_code' => $item->productCode,
                'price'        => $item->productPrice,
            ]);
            (new UpdateData())->addToStockDetails('debit', $item->productCode, 'Purchase Return', $invoice_number, $item->productQuantity);
            (new UpdateData())->updateStock('-', $item->productCode, $item->productQuantity);
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
    public function destroy($pur_rtn_no)
    {
        $porducts = PurchaseReturnDetail::where('pur_rtn_no', '=', $pur_rtn_no)->get();
        foreach ($porducts as $porduct) {
            (new UpdateData())->updateStock('+', $porduct->product_code, $porduct->quantity);
        }
        (new UpdateData())->deleteAll('purchase_return', $pur_rtn_no);
    }
}
