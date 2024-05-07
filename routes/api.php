<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentOverservationController;
use App\Http\Controllers\Api\PhoneInfoController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\WcController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Route::apiResource('roles', RoleController::class);
// Route::post('roles/{id}', [RoleController::class, 'update_workaround']);

// Route::post('users/{id}', [UserController::class, 'update_workaround']);
// Route::apiResource('users', UserController::class);
Route::get('user', [TokenController::class, 'user'])->middleware('auth:sanctum');
Route::post('register', [TokenController::class, 'register'])->middleware('guest');
Route::post('login', [TokenController::class, 'login'])->middleware('guest');
Route::post('logout', [TokenController::class, 'logout'])->middleware('auth:sanctum');

Route::post('students/phoneinfo',[PhoneInfoController::class, 'store']);


Route::post('students/phoneinfo/{phoneinfo}', [PhoneInfoController::class, 'update_workaround']);
// Ruta para eliminar una observación de un estudiante
Route::delete('students/phoneinfo/{phoneinfo}', [PhoneInfoController::class, 'destroy']);
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/phones', [PhoneInfoController::class, 'listPhoneInfo']);

// Ruta para crear una obsrvación de un estudiante

Route::post('students/observations',[StudentOverservationController::class, 'store']);

// Ruta para actualizar una observación de un estudiante
// Route::put('students/observations/{id}/{observation}', [StudentOverservationController::class, 'update']);

Route::post('students/observations/{observtion}', [StudentOverservationController::class, 'update_workaround']);
// Ruta para eliminar una observación de un estudiante
Route::delete('students/observations/{observation}', [StudentOverservationController::class, 'destroy']);
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/observations', [StudentOverservationController::class, 'listStudentObservations']);
Route::apiResource("students", StudentController::class);
Route::post('students/{id}', [StudentController::class, 'update_workaround']);

Route::get('/books/filter', [BookController::class, 'filter']);
Route::apiResource("books", BookController::class);
Route::post('books/{id}', [BookController::class, 'update_workaround']);

Route::get('/reservations/filter', [ReservationController::class, 'filter']);
Route::apiResource("reservations", ReservationController::class);
Route::post('reservations/{id}', [ReservationController::class, 'update_workaround']);

Route::apiResource("wc", WcController::class);
