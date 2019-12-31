<?php

namespace App;

use App\Model\Others\CustomerDetail;
use App\Model\Others\SupplierDetail;
use App\Model\Purchase\Purchase;
use App\Model\Purchase\PurchaseDetail;
use App\Model\Purchase\PurchaseReturn;
use App\Model\Purchase\PurchaseReturnDetail;
use App\Model\Sale\Sale;
use App\Model\Sale\SaleDetail;
use App\Model\Sale\SaleReturn;
use App\Model\Sale\SaleReturnDetail;
use App\Model\Stocks\Stock;
use App\Model\Stocks\StockDetail;

class UpdateData
{
    // update stock for purchase, sale, purchase return and sale return product
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

    // delete data for purchase, sale, purchase return and sale return
    public function deleteAll($deleteMode, $delete_code)
    {
        StockDetail::where('source_id', '=', $delete_code)->delete();

        if ($deleteMode == 'purchase' || 'purchase_return') {
            SupplierDetail::where('source_id', '=', $delete_code)->delete();
            if ($deleteMode == 'purchase') {
                PurchaseDetail::where('pur_inv_no', '=', $delete_code)->delete();
                Purchase::where('pur_inv_no', '=', $delete_code)->delete();
                return "purchase delete " . $deleteMode;
            } else {
                PurchaseReturnDetail::where('pur_rtn_no', '=', $delete_code)->delete();
                PurchaseReturn::where('pur_rtn_no', '=', $delete_code)->delete();
                return "purchase return delete " . $deleteMode;
            }
        } else {
            CustomerDetail::where('source_id', '=', $delete_code)->delete();
            if ($deleteMode == 'sale') {
                SaleDetail::where('sale_inv_no', '=', $delete_code)->delete();
                Sale::where('sale_inv_no', '=', $delete_code)->delete();
                return "sale delete " . $deleteMode;
            } else {
                SaleReturnDetail::where('sale_rtn_no', '=', $delete_code)->delete();
                SaleReturn::where('sale_rtn_no', '=', $delete_code)->delete();
                return "sale return delete " . $deleteMode;
            }
        }
    }

    // stock details
    public function addToStockDetails($debit_credit, $product_code, $mode, $invoice_number, $quantity)
    {
        StockDetail::create([
            'product_code' => $product_code,
            'description'  => $mode,
            'source_id'    => $invoice_number,
            $debit_credit  => $quantity,
        ]);
    }

    // Supplier details
    public function addToCustomerDetails($model, $supplier_id, $debit_credit, $grand_total, $mode, $invoice_number)
    {
        if ($mode == 'Purchase' || $mode == 'Purchase Return') {
            $customer_id = 'supplier_id';
        } else {
            $customer_id = 'customer_id';
        }
        $model::create([
            $customer_id   => $supplier_id,
            $debit_credit  => $grand_total,
            'description'  => $mode,
            'source_id'    => $invoice_number,
        ]);
    }
}
