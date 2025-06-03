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

// Car
Route::get('/car', [CarSwaggerController::class, 'getAllData']);
Route::get('/car/{id}', [CarSwaggerController::class, 'show']);
Route::get('/car/category/{categoryId}', [CarSwaggerController::class, 'getCarsByCategory']);

// User
Route::get('/user/{id}', [UserSwaggerController::class, 'getData']);
Route::get('/user', [UserSwaggerController::class, 'getAllUsers']);

// Reviews
Route::get('reviews', [ReviewSwaggerController::class, 'index']);

// Wishlists
Route::get('wishlists/{id}', [WishlistSwaggerController::class, 'show']);

// Authentication
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Category
    Route::post('/category', [CategorySwaggerController::class, 'store']);
    Route::put('/category/{id}', [CategorySwaggerController::class, 'update']);
    Route::delete('/category/{id}', [CategorySwaggerController::class, 'destroy']);

    // Car
    Route::post('/car', [CarSwaggerController::class, 'store']);
    Route::put('/car/{id}', [CarSwaggerController::class, 'update']);
    Route::delete('/car/{id}', [CarSwaggerController::class, 'destroy']);

    // User
    Route::put('/user/{id}', [UserSwaggerController::class, 'update']);
    Route::delete('/user/{id}', [UserSwaggerController::class, 'destroy']);

    // Reservation
    Route::post('/reservation', [ReservationSwaggerController::class, 'reserves']);
    Route::put('/reservation/{id}', [ReservationSwaggerController::class, 'update']);
    Route::delete('/reservation/{id}', [ReservationSwaggerController::class, 'destroy']);

    // Reviews
    Route::post('reviews', [ReviewSwaggerController::class, 'store']);
    Route::put('reviews/{id}', [ReviewSwaggerController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewSwaggerController::class, 'destroy']);

    // Wishlists
    Route::post('wishlists', [WishlistSwaggerController::class, 'store']);
    Route::put('wishlists/{id}', [WishlistSwaggerController::class, 'update']);
    Route::delete('wishlists/{id}', [WishlistSwaggerController::class, 'destroy']);

    // Logout
    Route::post('/logout', LogoutController::class);
});
