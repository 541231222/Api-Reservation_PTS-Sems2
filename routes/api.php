<?php

use App\Http\Controllers\API\CarSwaggerController;
use App\Http\Controllers\API\ReviewSwaggerController;
use App\Http\Controllers\API\WishlistSwaggerController;
use App\Http\Controllers\API\CategorySwaggerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ReservationSwaggerController;
use App\Http\Controllers\API\UserSwaggerController;

// Public routes
// Category
Route::get('/category', [CategorySwaggerController::class, 'getAllData']);

Route::get('/reservation/all', [ReservationSwaggerController::class, 'getAllReservations']);
Route::get('/reservation/{id}', [ReservationSwaggerController::class, 'getReservation']);
// Car
Route::get('/car', [CarSwaggerController::class, 'getAllData']);
Route::get('/car/{id}', [CarSwaggerController::class, 'show']);
Route::get('/car/category/{categoryId}', [CarSwaggerController::class, 'getCarsByCategory']);

// User
Route::get('/user/{id}', [UserSwaggerController::class, 'getData']);
Route::get('/user/all', [UserSwaggerController::class, 'getAllUsers']);

// Reviews
Route::get('reviews/{id}', [ReviewSwaggerController::class, 'show']);
Route::get('reviews/all', [ReviewSwaggerController::class, 'index']);

// Wishlists
Route::get('wishlists/all', [WishlistSwaggerController::class, 'index']);
Route::get('wishlists/{id}', [WishlistSwaggerController::class, 'show']);

// Authentication
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Category
    Route::post('/category', [CategorySwaggerController::class, 'store']);
    Route::put('/category/update/{id}', [CategorySwaggerController::class, 'update']);
    Route::delete('/category/delete/{id}', [CategorySwaggerController::class, 'destroy']);

    // Car
    Route::put('/car/update/{id}', [CarSwaggerController::class, 'update']);
    Route::delete('/car/delete/{id}', [CarSwaggerController::class, 'destroy']);

    // User
    Route::put('/user/update/{id}', [UserSwaggerController::class, 'update']);
    Route::delete('/user/delete/{id}', [UserSwaggerController::class, 'destroy']);

    // Reservation
    Route::post('/reservation', [ReservationSwaggerController::class, 'reserves']);
    Route::put('/reservation/update/{id}', [ReservationSwaggerController::class, 'update']);
    Route::delete('/reservation/delete/{id}', [ReservationSwaggerController::class, 'destroy']);

    // Reviews
    Route::post('reviews', [ReviewSwaggerController::class, 'store']);
    Route::put('reviews/update/{id}', [ReviewSwaggerController::class, 'update']);
    Route::delete('reviews/delete/{id}', [ReviewSwaggerController::class, 'destroy']);

    // Wishlists
    Route::put('wishlists/update/{id}', [WishlistSwaggerController::class, 'update']);
    Route::delete('wishlists/delete/{id}', [WishlistSwaggerController::class, 'destroy']);

    // Logout
    Route::post('/logout', LogoutController::class);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/car', [CarSwaggerController::class, 'store']);
    });
});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/user/profile', function () {
        return 'Welcome user!';
    });
});

Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
        Route::post('wishlists', [WishlistSwaggerController::class, 'store']);
});
