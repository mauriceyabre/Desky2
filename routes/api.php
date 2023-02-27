<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PasswordUpdateController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\ProjectController;
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


Route::middleware('api')->prefix('v1')->namespace('App\Http\Controllers\Api\V1')->name('api.v1.')->group(function () {
    Route::name('customers.')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('index');
    });

    // GUEST ROUTES
    Route::group(['prefix' => 'auth'], function (){
        Route::post('/login', LoginController::class);
        Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
        Route::get('/user', [ProfileController::class, 'user'])->middleware('auth:sanctum');
    });

    // AUTH ROUTES
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('profile', [ProfileController::class, 'show']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::put('password', PasswordUpdateController::class);


        // MEMBERS ROUTES
        Route::prefix('members')->controller(MemberController::class)->group(function () {
            Route::get('/{id}', [MemberController::class, 'show']);
            Route::put('/{id}', [MemberController::class, 'update']);
        });

        // ATTENDANCES ROUTES
        Route::apiResource('attendances', AttendanceController::class)->except('update');
        Route::put('attendances/{id?}', [AttendanceController::class, 'update']);

        // PROJECTS ROUTES
        Route::get('projects/dropdown', [ProjectController::class, 'dropdown']);
        Route::apiResource('projects', ProjectController::class);
    });
});
