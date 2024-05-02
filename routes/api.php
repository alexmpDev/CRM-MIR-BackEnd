<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentOverservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('roles', RoleController::class);
Route::post('roles/{id}', [RoleController::class, 'update_workaround']);

Route::apiResource('users', UserController::class);
Route::post('users/{id}', [UserController::class, 'update_workaround']);

// Ruta para crear una obsrvación de un estudiante
Route::post('students/observations/{id}',[StudentOverservationController::class, 'store']);

// Ruta para actualizar una observación de un estudiante
// Route::put('students/observations/{id}/{observation}', [StudentOverservationController::class, 'update']);
Route::post('students/observations/{id}/{observtion}', [StudentOverservationController::class, 'update_workaround']);
// Ruta para eliminar una observación de un estudiante
Route::delete('students/observations/{id}/{observation}', [StudentOverservationController::class, 'destroy']);
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/observations', [StudentOverservationController::class, 'listStudentObservations']);
Route::apiResource("students", StudentController::class);
Route::post('students/{id}', [StudentController::class, 'update_workaround']);


