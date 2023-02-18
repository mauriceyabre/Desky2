<?php

namespace App\Http\Controllers;

use App\Enums\CustomerCategory;
use App\Enums\CustomerType;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller {
    public function index(Request $request) {
        return Inertia::render('App/Customers/CustomersIndex', [
            'page' => [
                'title' => 'Lista Clienti'
            ]
        ]);
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        //
    }

    public function show(Request $request, int $id) {
        $customer = Customer::with('projects', 'creator')->findOrFail($id);
        return Inertia::render('App/Customers/CustomerShow', [
            'customer' => $customer,
            'page' => [
                'title' => $customer->name
            ]
        ]);
    }

    public function edit(int $id) {
        //
    }

    public function update(UpdateCustomerRequest $request, $id) {
        $customer = Customer::findOrFail($id);
        $filteredData = $request->except('address');
        if (!empty($filteredData)) {
            $data = $filteredData;
            if ($request->has('e_invoice') && !$request->input('e_invoice')) {
                $data['sdi_code'] = null;
            }
            $customer->updateOrFail($data);
        }

        if ($request->has('address')) {
            $customer->address()->updateOrCreate(
                ['id' => $request->input('address')['id']],
                $request->input('address')
            );
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'Cliente Aggiornato.']);
    }

    public function destroy(int $id) {
        //
    }

    public function fetchTypes() {
        return collect(CustomerType::array())->toJson();
    }

    public function fetchCategories() {
        return collect(CustomerCategory::array())->toJson();
    }

    public function getAllIds() {
        return Customer::get(['id', 'name']);
    }

    public function getAll() {
        $request = request()->all();
        $only = ($request['only']) ?? ['*'];
        // dd(\request()->all());
        return response()->json(['customers' => Customer::all($only), 'request' => $request]);
    }
}
