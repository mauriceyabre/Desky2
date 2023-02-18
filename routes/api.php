<?php

use App\Http\Controllers\Api\V1\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return response()->json(['roba']);
//    return $request->user();
//});
//
//Route::post('login', [AuthController::class, 'login']);
//
Route::middleware('api')->prefix('v1')->namespace('App\Http\Controllers\Api\V1')->name('api.v1.')->group(function () {
    Route::name('customers.')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('index');
    });
});
