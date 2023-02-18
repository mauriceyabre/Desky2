<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $query = Customer::query();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereRaw("TRIM(REPLACE(name,'.', '')) LIKE ?", ["%$term%"]);
        }

        $query->orderByDesc('created_at');
        $customers = $query
            ->select(['id', 'name', 'email', 'type', 'vat_number', 'tax_code', 'created_at', 'phone'])
            ->paginate(10)
            ->withQueryString();
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Customer $customer
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer) {
        //
    }
}
