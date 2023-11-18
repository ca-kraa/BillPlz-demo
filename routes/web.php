<?php

use App\Http\Controllers\BillplzController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/payment/create', [BillplzController::class, 'createForm'])->name('payment.createForm');
Route::post('/payment/create', [BillplzController::class, 'createBill'])->name('payment.create');

// Route::post('/createBill-produk/{id}', [BillplzController::class, 'createBill']);
// Route::post('/handleBillplzCallback', [BillplzController::class, 'handleBillplzCallback']);

// Route::post('/create-bill/{barangId}', [BillplzController::class, 'createBill']);
Route::post('/handleBillplzCallback', [BillplzController::class, 'handleBillplzCallback'])->name('handle-billplz-callback');
Route::get('/create-bill/{barangId}', [BillplzController::class, 'createBill'])->name('create.bill');

Route::post('/create-produk', [BillplzController::class, 'createBarang']);

Route::get('/product', [BillplzController::class, 'showDataOriginal']);

Route::get('/produk/create', [BillplzController::class, 'create'])->name('produk.create');
Route::post('/product', [BillplzController::class, 'store'])->name('produk.store');
