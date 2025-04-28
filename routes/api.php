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

Route::group( [], function () {
    Route::get('category', [CategorySwaggerController::class, 'listCategory']);
});

Route::group( [], function () {
    Route::get('car', [CarSwaggerController::class, 'listCar']);
});

Route::group( [], function () {
    Route::get('user', [UserSwaggerController::class, 'listUser']);
});

Route::group( [], function () {
    Route::get('reservation', [ReservationSwaggerController::class, 'listReservation']);
});
