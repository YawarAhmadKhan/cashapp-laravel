<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/testing1', [App\Http\Controllers\HomeController::class, 'fetchingdata'])->name('saving');
Route::get('/token-php1', [App\Http\Controllers\HomeController::class, 'accesstoken'])->name('accesstoken');




// Route::get('/home_savng_1234', [App\Http\Controllers\HomeController::class, 'geto'])->name('saving');
Route::get('/home', \App\Livewire\AdminDash::class)->name('dashboard')->middleware('auth');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/home', \App\Livewire\AdminDash::class)->name('dashboard');
    Route::get('/emails', \App\Livewire\Transactions::class)->name('emailtransactions');
    Route::get('/receive-amount', \App\Livewire\ReceiveAmount::class)->name('receive-amount');
    Route::get('/sent-amount', \App\Livewire\SentAmount::class)->name('sent-amount');
    Route::get('/bticion-purchase', \App\Livewire\BitcionPurchase::class)->name('bitcionpurchase');
    Route::get('/bticion-sell', \App\Livewire\BitcionSell::class)->name('bitcionsell');
    Route::get('/Refunds', \App\Livewire\Refunds::class)->name('refunds');
});
