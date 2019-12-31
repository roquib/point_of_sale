<?php

namespace App\Http\Controllers\API;

use App\Model\Others\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Customer::latest()->Paginate(5);
    }

    public function customer_list()
    {
        return Customer::pluck('name')->toArray();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // $user_id = auth('api')->user()->id;
        $this->validate($request, [
            'name'         => 'required|string|max:255|min:2',
            'mobile'       => 'required|string|max:16|min:8',
            'address'      => 'required',
        ]);
        // insert data
        Customer::create([
            'name'            => $request['name'],
            'email'           => $request['email'],
            'mobile'          => $request['mobile'],
            'address'         => $request['address'],
            'user_id'         => auth('api')->user()->id,
            'opening_balance' => $request['opening_balance'],
        ]);
        return "Success";
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name'         => 'required|string|max:255|min:2',
            'mobile'       => 'required|string|max:16|min:8',
            'address'      => 'required',
        ]);
        $customer->update($request->all());
        return "Success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return "Success";
    }




    // customer search_customer
    public function customer_search($search)
    {
        return Customer::where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->orWhere('address', 'like', '%' . $search . '%')
            ->paginate(5);
    }
}
