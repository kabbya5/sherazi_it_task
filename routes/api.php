<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerifyController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(TransactionController::class)->group(function(){
    Route::get('/transactions','index');
    Route::post('/transactions', 'store');
    Route::get('/ledger/report/{account}','report');
});

// Sanctum Authenticated
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum','verified']);

Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');
});


// User Email Verification Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(EmailVerifyController::class)->group(function(){
        Route::get('/email/verify', 'notice')->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify')->middleware('signed');
        Route::post('/email/resend', 'resend')->middleware('throttle:6,1')->name('verification.resend');
    });
});
