<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class,'index']);
Route::get('/cart', [MainController::class,'cart']);
Route::get('/checkout', [MainController::class,'checkout']);
Route::get('/shop', [MainController::class,'shop']);
Route::get('/single/{id}', [MainController::class,'singleProduct']);
Route::get('/register', [MainController::class,'register']);
Route::post('/registerUser', [MainController::class,'registerUser']);
Route::get('/login', [MainController::class,'login']);
Route::post('/loginUser', [MainController::class,'loginUser']);
Route::get('/logout', [MainController::class,'logout']);
Route::post('/addToCart', [MainController::class,'addToCart']);
Route::post('/updateCart', [MainController::class,'updateCart']);
Route::get('/deleteCartItem/{id}', [MainController::class,'deleteCartItem']);


