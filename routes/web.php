<?php

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

Route::prefix('products')->group(function () {
    Route::post('/create', [ProductController::class, 'store']);   
    Route::get('/list', [ProductController::class, 'index']);     
    Route::get('/show/{id}', [ProductController::class, 'show']);      
    Route::put('/update/{id}', [ProductController::class, 'update']);    
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
});



