<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\EventController;
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
use App\Http\Controllers\Api\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('roles', RoleController::class);
// Route::post('roles/{id}', [RoleController::class, 'update_workaround']);
Route::get('users/dashboard', [UserController::class, 'getDashboardMenu'])->middleware('auth:sanctum');
Route::post('users/{id}', [UserController::class, 'update_workaround']);
Route::apiResource('users', UserController::class);
Route::get('users', [UserController::class, 'index']);



Route::get('user', [TokenController::class, 'user'])->middleware('auth:sanctum');
Route::post('register', [TokenController::class, 'register'])->middleware('guest');
Route::post('login', [TokenController::class, 'login'])->middleware('guest');
Route::post('logout', [TokenController::class, 'logout'])->middleware('auth:sanctum');

Route::post('students/phoneinfo',[PhoneInfoController::class, 'store'])->middleware('auth:sanctum');
Route::get('students/phones/{id}',[PhoneInfoController::class, 'show'])->middleware('auth:sanctum');
Route::post('students/phoneinfo/{id}', [PhoneInfoController::class, 'update_workaround'])->middleware('auth:sanctum');
// Ruta para eliminar una observación de un estudiante
Route::delete('students/phoneinfo/{phoneinfo}', [PhoneInfoController::class, 'destroy'])->middleware('auth:sanctum');
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/phones', [PhoneInfoController::class, 'listPhoneInfo'])->middleware('auth:sanctum');

// Ruta para crear una obsrvación de un estudiante

Route::post('students/observations',[StudentOverservationController::class, 'store'])->middleware('auth:sanctum');

// Ruta para actualizar una observación de un estudiante
// Route::put('students/observations/{id}/{observation}', [StudentOverservationController::class, 'update']);

Route::post('students/observations/{observtion}', [StudentOverservationController::class, 'update_workaround'])->middleware('auth:sanctum');
Route::get('students/observations/{id}', [StudentOverservationController::class, 'show'])->middleware('auth:sanctum');
// Ruta para eliminar una observación de un estudiante
Route::delete('students/observations/{observation}', [StudentOverservationController::class, 'destroy'])->middleware('auth:sanctum');
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/observations', [StudentOverservationController::class, 'listStudentObservations'])->middleware('auth:sanctum');
Route::apiResource("students", StudentController::class)->middleware('auth:sanctum');
Route::post('students/{id}', [StudentController::class, 'update_workaround'])->middleware('auth:sanctum');

Route::get('/books/filter', [BookController::class, 'filter'])->middleware('auth:sanctum');
Route::apiResource("books", BookController::class)->middleware('auth:sanctum');
Route::post('books/{id}', [BookController::class, 'update_workaround'])->middleware('auth:sanctum');

Route::get('/reservations/filter', [ReservationController::class, 'filter'])->middleware('auth:sanctum');
Route::apiResource("reservations", ReservationController::class)->middleware('auth:sanctum');
Route::post('reservations/{id}', [ReservationController::class, 'update_workaround'])->middleware('auth:sanctum');

Route::apiResource("wc", WcController::class)->middleware('auth:sanctum');

// Asignar cursos a un evento
Route::post('/events/assign-courses', [EventController::class, 'assignCourses'])->middleware('auth:sanctum');

Route::post('/events/generate-tickets', [EventController::class, 'generateTickets'])->middleware('auth:sanctum');
Route::get('/events/{eventId}/tickets-generated', [EventController::class, 'ticketsGenerated'])->middleware('auth:sanctum');
Route::delete('/events/{eventId}/unassign-courses', [EventController::class, 'unassignCourses'])->middleware('auth:sanctum');

Route::get('/tickets/validate/{ticketId}', [EventController::class, 'validateTicket'])->middleware('auth:sanctum');

Route::apiResource("events", EventController::class)->middleware('auth:sanctum');
Route::post('events/{id}', [EventController::class, 'update_workaround'])->middleware('auth:sanctum');

Route::apiResource('course', CourseController::class)->middleware('auth:sanctum');
// Rutas para CourseController



