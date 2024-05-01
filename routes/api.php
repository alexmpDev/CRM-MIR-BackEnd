<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('roles', RoleController::class);
Route::post('roles/{id}', [RoleController::class, 'update_workaround']);

Route::apiResource('users', UserController::class);
Route::post('users/{id}', [UserController::class, 'update_workaround']);

Route::apiResource("students", StudentController::class);
Route::post('students/{id}', [StudentController::class, 'update_workaround']);

Route::apiResource("books", BookController::class);
Route::post('books/{id}', [BookController::class, 'update_workaround']);

Route::get('/reservations/filter', [ReservationController::class, 'filter']);
Route::apiResource("reservations", ReservationController::class);
Route::post('reservations/{id}', [ReservationController::class, 'update_workaround']);
