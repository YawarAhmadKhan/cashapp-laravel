<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/email', function(){
return response()->json('hello');
});
Route::get('/home',function(){
    return view('testingApi');
})->name('dashboard')->middleware('auth');