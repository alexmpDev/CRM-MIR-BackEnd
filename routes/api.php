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



// Route::apiResource('roles', RoleController::class);
// Route::post('roles/{id}', [RoleController::class, 'update_workaround']);

// Route::post('users/{id}', [UserController::class, 'update_workaround']);
// Route::apiResource('users', UserController::class);
Route::get('users', [UserController::class, 'index']);

Route::get('user', [TokenController::class, 'user'])->middleware('auth:sanctum');
Route::post('register', [TokenController::class, 'register'])->middleware('guest');
Route::post('login', [TokenController::class, 'login'])->middleware('guest');
Route::post('logout', [TokenController::class, 'logout'])->middleware('auth:sanctum');

Route::post('students/phoneinfo',[PhoneInfoController::class, 'store']);
Route::get('students/phones/{id}',[PhoneInfoController::class, 'show']);
Route::post('students/phoneinfo/{id}', [PhoneInfoController::class, 'update_workaround']);
// Ruta para eliminar una observación de un estudiante
Route::delete('students/phoneinfo/{phoneinfo}', [PhoneInfoController::class, 'destroy']);
// Ruta para ver las observaciones de un estudiante
Route::get('students/{studentId}/phones', [PhoneInfoController::class, 'listPhoneInfo']);

// Ruta para crear una obsrvación de un estudiante

Route::post('students/observations',[StudentOverservationController::class, 'store']);

// Ruta para actualizar una observación de un estudiante
// Route::put('students/observations/{id}/{observation}', [StudentOverservationController::class, 'update']);

Route::post('students/observations/{observtion}', [StudentOverservationController::class, 'update_workaround']);
Route::get('students/observations/{id}', [StudentOverservationController::class, 'show']);
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

// Asignar cursos a un evento
Route::post('/events/assign-courses', [EventController::class, 'assignCourses']);

Route::post('/events/generate-tickets', [EventController::class, 'generateTickets']);
Route::delete('/events/{eventId}/unassign-courses', [EventController::class, 'unassignCourses']);

Route::get('/tickets/validate/{ticketId}', [EventController::class, 'validateTicket']);

Route::apiResource("events", EventController::class);
Route::post('events/{id}', [EventController::class, 'update_workaround']);

Route::apiResource('course', CourseController::class);



