<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::apiResource("students", StudentController::class);
Route::post('students/{id}', [StudentController::class, 'update_workaround']);

