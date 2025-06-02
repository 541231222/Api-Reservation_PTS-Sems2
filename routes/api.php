<?php

use App\Http\Controllers\API\CarSwaggerController;
use App\Http\Controllers\API\ReviewSwaggerController;
use App\Http\Controllers\API\WishlistSwaggerController;
use App\Http\Controllers\API\CategorySwaggerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ReservationSwaggerController;
use App\Http\Controllers\API\UserSwaggerController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

//ini swagger

//MODEL CATEGORY YA
Route::post('/category', [CategorySwaggerController::class, 'store']);
Route::get('/category', [CategorySwaggerController::class, 'getAllData']);
Route::put('/category/{id}', [CategorySwaggerController::class, 'update']);
Route::delete('/category/{id}', [CategorySwaggerController::class, 'destroy']);

//MODEL CAR
Route::post('/car', [CarSwaggerController::class, 'store']);                         // Tambah mobil
Route::get('/car', [CarSwaggerController::class, 'getAllData']);                     // Ambil semua mobil
Route::get('/car/{id}', [CarSwaggerController::class, 'show']);                      // Ambil detail mobil
Route::put('/car/{id}', [CarSwaggerController::class, 'update']);                    // Update data mobil
Route::delete('/car/{id}', [CarSwaggerController::class, 'destroy']);               // Hapus mobil
Route::get('/car/category/{categoryId}', [CarSwaggerController::class, 'getCarsByCategory']); //get by category dw

//MODEL USER
Route::post('/user/register', [UserSwaggerController::class, 'register']);
Route::get('/user/{id}', [UserSwaggerController::class, 'getData']);
Route::get('/user', [UserSwaggerController::class, 'getAllUsers']);
Route::put('/user/{id}', [UserSwaggerController::class, 'update']);
Route::delete('/user/{id}', [UserSwaggerController::class, 'destroy']);

//MODEL RESERVATION
Route::post('/reservation', [ReservationSwaggerController::class, 'reserves']);
Route::get('/reservation', [ReservationSwaggerController::class, 'getAllReservations']);
Route::get('/reservation/{id}', [ReservationSwaggerController::class, 'getReservation']);
Route::get('/reservation/filter', [ReservationSwaggerController::class, 'getByStatus']);
Route::put('/reservation/{id}', [ReservationSwaggerController::class, 'update']);
Route::delete('/reservation/{id}', [ReservationSwaggerController::class, 'destroy']);


Route::group([], function () {
    // Route untuk menambahkan review baru
    Route::post('reviews', [ReviewSwaggerController::class, 'store']);

    // Route untuk mengambil semua review
    Route::get('reviews', [reviewSwaggerController::class, 'getAllData']);

    // Route untuk menghapus review berdasarkan ID
    Route::delete('reviews/{id}', [reviewSwaggerController::class, 'destroy']);

    // Route untuk memperbarui data review berdasarkan ID
    Route::put('reviews/{id}', [reviewSwaggerController::class, 'update']);
});

Route::group([], function () {
    Route::post('wishlists', [WishlistSwaggerController::class, 'store']);
    Route::get('wishlists/{id}', [WishlistSwaggerController::class, 'getAllData']);
    Route::put('wishlists/{id}', [WishlistSwaggerController::class, 'update']);
    Route::delete('wishlists/{id}', [WishlistSwaggerController::class, 'destroy']);
});
