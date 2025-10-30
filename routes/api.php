<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(TransactionController::class)->group(function(){
    Route::get('/transactions','index');
    Route::post('/transactions', 'store');
    Route::get('/ledger/report/{account}','report');
});
