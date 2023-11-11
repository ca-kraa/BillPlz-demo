<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillplzController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/billplz/collection/{id}', [BillplzController::class, 'getCollection']);
Route::get('/billplz/billing/{id}', [BillplzController::class, 'getBill']);
Route::post('/billplz/createBill', [BillplzController::class, 'createBill']);
Route::get('/billplz/transactions/{BILL_ID}', [BillplzController::class, 'getTransactions']);
Route::get('/billplz/getpayment', [BillplzController::class, 'getPayment']);
