<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AutoNumberController;
use App\Http\Controllers\ResellerController;

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
    return view('pages.auth.login');
});
Route::get('autonumbers', [AutoNumberController::class, 'get']);

Route::middleware(['auth'])->group(function () {
    // Route::get('home', function () {
    //     return view('pages.dashboard');
    // })->name('home');
    Route::get('home', [DashboardController::class, 'index'])->name('home');
    Route::post('home', [DashboardController::class, 'index'])->name('home.post');
    Route::resource('user', UserController::class);
    Route::resource('reseller', ResellerController::class);
    Route::post('/reseller/bayar', [ResellerController::class, 'bayar'])->name('reseller.bayar');
    Route::resource('product', ProductController::class);
    Route::resource('order', OrderController::class);
});
Route::get('konfirmasi/{confirmation_code}', [UserController::class, 'konfirmasi'])->name('konfirmasi');
Route::get('register-success', [UserController::class, 'registerSuccess'])->name('register.success');
