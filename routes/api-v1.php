<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\PaymentApiController;

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

Route::controller(PaymentApiController::class)->prefix('payments')->name('payments.')->group(function () {
    Route::post('/', 'create');
    Route::put('/', 'update');
    Route::get('/{stripeChargeId}', 'show');
});