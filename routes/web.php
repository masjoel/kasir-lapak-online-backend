<?php

use App\Http\Controllers\ActivationCodeController;
use App\Http\Controllers\AutoNumberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\UserController;
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
    Route::resource('activation-code', ActivationCodeController::class);
});
Route::get('upgrade-starter/{confirmation_code}', [UserController::class, 'upgradeStarter'])->name('upgrade-starter');
Route::get('upgrade-basic/{confirmation_code}', [UserController::class, 'upgradeBasic'])->name('upgrade-basic');
Route::get('upgrade-pro/{confirmation_code}', [UserController::class, 'upgradePro'])->name('upgrade-pro');
Route::get('starter/{confirmation_code}', [UserController::class, 'starter'])->name('starter');
Route::get('basic/{confirmation_code}', [UserController::class, 'basic'])->name('basic');
Route::get('konfirmasi/{confirmation_code}', [UserController::class, 'konfirmasi'])->name('konfirmasi');
Route::get('register-success', [UserController::class, 'registerSuccess'])->name('register.success');
