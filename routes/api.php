<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AdminController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

  Route::get(
    '/customers',
    [CustomerController::class, 'index']
  );

  Route::put(
    '/customer/{id}/payment-status',
    [CustomerController::class, 'updatePaymentStatus']
  );

  Route::post(
    '/customer/{id}/send-notification',
    [CustomerController::class, 'sendNotification']
  );

  Route::middleware('admin')->group(function () {

    Route::post(
      '/admin/upload-csv',
      [AdminController::class, 'uploadCsv']
    );
  });
});
