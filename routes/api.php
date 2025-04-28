<?php

use App\Http\Controllers\API\CarSwaggerController;
use App\Http\Controllers\API\CategorySwaggerController;
use App\Http\Controllers\API\ReservationSwaggerController;
use App\Http\Controllers\API\UserSwaggerController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class, 'register']);

Route::get( '/users/{id}', [UserController::class, 'getData']);

Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);

Route::put('/users/{id}/update', [UserController::class, 'update']);

Route::post( '/cars', [CarController::class, 'store']);

Route::get('/cars', [CarController::class, 'getAllData']);

Route::delete('/cars/{id}/delete', [CarController::class, 'destroy']);

Route::put('/cars/{id}/update', [CarController::class, 'update']);

Route::post( '/reservations', [ReservationController::class, 'reserves']);

Route::get('/reservations/{id}', [ReservationController::class, 'getReservation']);

Route::put('/reservations/{id}/update-status', [ReservationController::class, 'update']);

Route::delete('/reservations/{id}/delete', [ReservationController::class, 'destroy']);

Route::post( '/categories', [CategoryController::class, 'store']);

Route::get('/categories', [CategoryController::class, 'getAllData']);

Route::delete('/categories/{id}/delete', [CategoryController::class, 'destroy']);

Route::put('/categories/{id}/update', [CategoryController::class, 'update']);

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
