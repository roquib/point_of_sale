<?php

namespace App\Http\Controllers\API\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Supplier;
use App\Model\Others\SupplierDetail;
use App\Model\Purchase\Purchase;
use App\Model\Purchase\PurchaseDetail;
use App\Model\Stocks\StockDetail;
use App\UpdateData;

class PurchaseController extends Controller
{

    // check authentication
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
        $purchases = Purchase::join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->orderBy('suppliers.name', "asc")
            ->with('user');
        return response()->JSON($purchases->paginate(5));
    }
    // populate invoice
    public function getInvoice($pur_inv_no)
    {

        $invoice = Purchase::join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->orderBy('suppliers.name', "asc")
            ->with('supplier')
            ->with('purchaseItems')
            ->with('user')
            ->where('pur_inv_no', '=', $pur_inv_no)
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
        $supplierInfo = json_decode($request->supplierInfo);

        $user_id       = auth('api')->user()->id;
        $supplier_id   = Supplier::where('name', '=', $supplierInfo->name)->first()->id;
        $discount      = 0;
        $grand_total   = $supplierInfo->grandTotal - $discount;


        $purchase = Purchase::create([
            'pur_inv_no'   => '124',
            'user_id'      => $user_id,
            'supplier_id'  => 1,
            'totalQty'     => $supplierInfo->totalQuantity,
            'subTotal'     => $supplierInfo->grandTotal,
            'discount'     => $discount,
            'grandTotal'   => $grand_total,
        ]);

        $invoice_number = "pur-inv-" . strval($purchase->id + 100000);

        if ($supplierInfo->cashPurchase) {
            $purchase->name         = $supplierInfo->name;
            $purchase->mobile       = $supplierInfo->mobile;
            $purchase->address      = $supplierInfo->address;
            $purchase->pur_inv_no   = $invoice_number;
        } else {
            $purchase->supplier_id  = $supplier_id;
            $purchase->pur_inv_no   = $invoice_number;
        }
        $purchase->save();

        (new UpdateData())->addToCustomerDetails(new SupplierDetail(), $supplier_id, 'credit', $grand_total, 'Purchase', $invoice_number);

        foreach (json_decode($request->shopItems) as $item) {
            PurchaseDetail::create([
                'pur_inv_no'    => $invoice_number,
                'quantity'      => $item->quantity,
                'product_code'  => $item->product_code,
                'price'         => $item->price,
            ]);

            (new UpdateData())->addToStockDetails('credit', $item->product_code, 'Purchase', $invoice_number, $item->quantity);
            // call from StockContorller
            (new UpdateData())->updateStock('+', $item->product_code, $item->quantity);
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
    public function update(Request $request, $pur_inv_no)
    {
        $oldPurchase   = json_decode($request->oldPurchase);
        $supplierInfo  = json_decode($request->supplierInfo);
        $shopItems     = json_decode($request->shopItems);
        $supplier_id   = Supplier::where('name', '=', $supplierInfo->name)->first()->id;


        Purchase::where('pur_inv_no', $pur_inv_no)
            ->update(
                [
                    'supplier_id'  => $supplier_id,
                    'totalQty'     => $supplierInfo->totalQuantity,
                    'subTotal'     => $supplierInfo->grandTotal,
                    'grandTotal'   => $supplierInfo->grandTotal,
                ]
            );

        SupplierDetail::where('supplier_id', '=', $oldPurchase->id)
            ->update([
                'supplier_id'   => $supplier_id,
                'credit'        => $supplierInfo->grandTotal,
            ]);

        foreach ($shopItems as $item) {
            $purchase = PurchaseDetail::where('pur_inv_no', '=', $pur_inv_no)
                ->where('product_code', '=', $item->product_code)
                ->update([
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                ]);
            if (!$purchase) {
                PurchaseDetail::create([
                    'pur_inv_no'    => $pur_inv_no,
                    'quantity'      => $item->quantity,
                    'product_code'  => $item->product_code,
                    'price'         => $item->price,
                ]);
                StockDetail::create([
                    'product_code' => $item->product_code,
                    'description'  => "Purchase",
                    'source_id'    => $pur_inv_no,
                    'credit'       => $item->quantity,
                ]);
                (new UpdateData())->updateStock('+', $item->product_code, $item->quantity);
                // $stock = Stock::where('product_code', '=', $item->product_code)->first();
                // $stock->quantity += $item->quantity;
                // $stock->save();
            } else {
                StockDetail::where('source_id', '=', $pur_inv_no)
                    ->where('product_code', '=', $item->product_code)->update([
                        'credit'       => $item->quantity,
                    ]);
            }
            $purchase = null;
        }

        foreach ($oldPurchase->purchase_items as $item) {
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
                (new UpdateData())->updateStock('-', $item->product_code, $item->quantity);

                $purchase = PurchaseDetail::where('pur_inv_no', '=', $pur_inv_no)
                    ->where('product_code', '=', $item->product_code)->first();
                $purchase->delete();
            } else {
                (new UpdateData())->updateStock('+', $item->product_code, ($shop_quantity - $item->quantity));
            }
        }
        return "Update " . $oldPurchase->pur_inv_no . "Successfull";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pur_inv_no)
    {
        $porducts = PurchaseDetail::where('pur_inv_no', '=', $pur_inv_no)->get();
        foreach ($porducts as $porduct) {
            (new UpdateData())->updateStock('-', $porduct->product_code, $porduct->quantity);
        }
        (new UpdateData())->deleteAll('purchase', $pur_inv_no);
    }
}
