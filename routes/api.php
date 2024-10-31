<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint untuk mendapatkan informasi pengguna saat ini
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Registrasi pengguna baru
Route::post('register', [AuthController::class, 'register']);

// Login pengguna
Route::post('login', [AuthController::class, 'login']);

// Endpoint logout, harus dilindungi oleh middleware auth:sanctum
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Endpoint untuk produk, semua endpoint dilindungi oleh middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});
Route::get('products/search', [ProductController::class, 'search'])->middleware('auth:sanctum');
