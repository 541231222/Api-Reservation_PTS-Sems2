<?php

use App\Http\Controllers\API\ReviewSwaggerController;
use App\Http\Controllers\API\WishlistSwaggerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CarSwaggerController;
use App\Http\Controllers\API\CategorySwaggerController;
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

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


Route::post('/users', [UserController::class, 'register']);

Route::get( '/users/{id}', [UserController::class, 'getData']);

Route::get( '/users', [UserController::class, 'getAllUsers']);

Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);

Route::put('/users/{id}/update', [UserController::class, 'update']);

Route::post( '/cars', [CarController::class, 'store']);

Route::get('/cars', [CarController::class, 'getAllData']);

Route::get('/cars/category/{categoryId}', [CarController::class, 'getCarsByCategory']);

Route::delete('/cars/{id}/delete', [CarController::class, 'destroy']);

Route::put('/cars/{id}/update', [CarController::class, 'update']);

Route::post( '/reservations', [ReservationController::class, 'reserves']);

Route::get('/reservations/{id}', [ReservationController::class, 'getReservation']);

Route::get('/reservations/filter', [ReservationController::class, 'getByStatus']);

Route::get('/reservations', [ReservationController::class, 'getAllReservations']);

Route::put('/reservations/{id}/update-status', [ReservationController::class, 'update']);

Route::delete('/reservations/{id}/delete', [ReservationController::class, 'destroy']);

Route::post( '/categories', [CategoryController::class, 'store']);

Route::get('/categories', [CategoryController::class, 'getAllData']);

Route::delete('/categories/{id}/delete', [CategoryController::class, 'destroy']);

Route::put('/categories/{id}/update', [CategoryController::class, 'update']);

Route::apiResource('reviews', ReviewController::class);

Route::apiResource('wishlists', WishlistController::class);



//MODEL CATEGORY YA
Route::group([], function () {
    // Route untuk menambahkan kategori baru
    Route::post('category', [CategorySwaggerController::class, 'store']);

    // Route untuk mengambil semua kategori
    Route::get('category', [CategorySwaggerController::class, 'getAllData']);

    // Route untuk menghapus kategori berdasarkan ID
    Route::delete('category/{id}', [CategorySwaggerController::class, 'destroy']);

    // Route untuk memperbarui data kategori berdasarkan ID
    Route::put('category/{id}', [CategorySwaggerController::class, 'update']);
});

//MODEL CAR
Route::group([], function () {
    // Route untuk menambahkan mobil baru
    Route::post('car', [CarSwaggerController::class, 'store']);

    // Route untuk mengambil semua data mobil
    Route::get('car', [CarSwaggerController::class, 'getAllData']);

    // Route untuk menghapus mobil berdasarkan ID
    Route::delete('car/{id}', [CarSwaggerController::class, 'destroy']);

    // Route untuk memperbarui data mobil berdasarkan ID
    Route::put('car/{id}', [CarSwaggerController::class, 'update']);
});

//MODEL USER
Route::group([], function () {
    Route::post('register', [UserSwaggerController::class, 'register']);
    Route::get('user/{id}', [UserSwaggerController::class, 'getData']);
    Route::put('user/{id}', [UserSwaggerController::class, 'update']);
    Route::delete('user/{id}', [UserSwaggerController::class, 'destroy']);
});


//MODEL RESERVATION
Route::group([], function () {
    Route::post('reservation', [ReservationSwaggerController::class, 'reserves']);
    Route::get('reservation/{id}', [ReservationSwaggerController::class, 'getReservation']);
    Route::put('reservation/{id}', [ReservationSwaggerController::class, 'update']);
    Route::delete('reservation/{id}', [ReservationSwaggerController::class, 'destroy']);
});

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