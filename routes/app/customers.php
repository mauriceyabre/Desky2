<?php
use App\Http\Controllers\CustomerController;

Route::controller(CustomerController::class)->group(function () {
    // CUSTOMERS
    Route::name('customers.')->prefix('customers')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // SINGLE CUSTOMER
    Route::name('customer.')->prefix('customer')->group(function () {
        Route::get('/{id}', function ($id) { return redirect()->route('customer.show', $id); });
        Route::get('/{id}/overview', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
    });

    // FETCH DATA
    Route::name('customers.fetch.')->prefix('customers/fetch')->group(function () {
        Route::get('/types', 'fetchTypes')->name('types');
        Route::get('/categories', 'fetchCategories')->name('categories');
    });
});
