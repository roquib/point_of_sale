<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Others\Product;
use App\Model\Stocks\Stock;
use App\Model\Stocks\StockDetail;
use App\UpdateData;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::latest()
            ->with('category')
            ->with('unit')
            ->paginate(5);
    }

    public function paginateProduct($item = null)
    {
        $perPage = 5;
        if ($item) {
            $perPage = $item;
        }
        return Product::latest()
            ->with('category')
            ->with('unit')
            ->paginate($perPage);
    }

    public function product_list()
    {
        return DB::table('products')
            ->get(['name', 'purchase_price', 'sale_price', 'product_code']);
        // return Supplier::pluck('name')->toArray();
        // return Product::latest()->paginate(500000);
    }

    public function selectedProduct($select)
    {
        return Product::where('name', '=', $select)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|string|max:255|min:2|unique:products',
            'category_id' => 'required',
            'unit_id'     => 'required',
        ]);
        $category_id = $request->category_id < 100 ? $request->category_id + 100 : $request->category_id;

        $product = Product::create([
            'name'           => $request->name,
            'category_id'    => $category_id,
            'unit_id'        => $request->unit_id,
            'purchase_price' => $request->purchase_price,
            'sale_price'     => $request->sale_price,
            'opening_stock'  => $request->opening_stock,
            'product_code'   => '123',
        ]);
        $product_code = "pro-code-" . strval($category_id) . '-' . strval($product->id + 10000);
        $product->product_code = $product_code;
        $product->save();

        Stock::create([
            'product_code'   => $product_code,
            'price'          => $request->purchase_price,
            'quantity'       => $request->opening_stock,
        ]);
        (new UpdateData())->addToStockDetails('credit', $product_code, 'Opening Stock', "nothing", $request->opening_stock);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProduct($product_code)
    {
        return Product::where('product_code', '=', $product_code)->first();
    }

    public function update(Request $request, $product_code)
    {
        $this->validate($request, [
            'name'            => 'required',
            'category_id'     => 'required',
            'unit_id'         => 'required',
        ]);

        $product = Product::where('product_code', '=', $product_code)->first();
        $product->update($request->all());
        $stock = Stock::where('product_code', '=', $product_code)->first()->quantity;
        (new UpdateData())->updateStock('+', $product_code, $request->opening_stock - $stock);

        StockDetail::where('description', '=', "Opening Stock")
            ->where('product_code', '=', $product_code)->update([
                'credit'       => $request->opening_stock,
            ]);
        return "success";
    }

    public function searchProduct($search)
    {
        return Product::where('name', 'like', '%' . $search . '%')
            ->orWhere('product_code', 'like', '%' . $search . '%')
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('unit', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with('category')
            ->with('unit')
            ->paginate(5);
    }

    public function filterProduct($perPage, $order, $orderField)
    {
        if ($orderField == 'category') {
            return Product::select('products.*')->join('categories', 'categories.id', '=', 'products.category_id')
                ->orderBy('categories.name', $order)
                ->with('category')
                ->with('unit')
                ->paginate($perPage);
        } else if ($orderField == 'unit') {
            return Product::select('products.*')->join('units', 'units.id', '=', 'products.unit_id')
                ->orderBy('units.name', $order)
                ->with('category')
                ->with('unit')
                ->paginate($perPage);
        } else {
            return Product::with('category')
                ->with('unit')
                ->orderBy($orderField, $order)
                ->paginate($perPage);
        }
    }

    /*
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_code)
    {
        $product = Product::where('product_code', '=', $product_code)->first();
        $stock = Stock::where('product_code', '=', $product_code);
        $stockDetail = StockDetail::where('product_code', '=', $product_code);

        $stock->delete();
        $stockDetail->delete();
        $product->delete();
        return "success";
    }
}
