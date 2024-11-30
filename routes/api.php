<?php

use App\Http\Controllers\api\v1\AppointmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\api\v1\DoctorController;
use App\Http\Controllers\api\v1\PatientController;
use App\Http\Controllers\api\v1\PermissionController;
use App\Http\Controllers\api\v1\RoleController;
use App\Http\Controllers\api\v1\ScheduleController;
use App\Http\Controllers\api\v1\SpecialityController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::apiResource('/users', UserController::class)->middleware(['auth:sanctum', AdminMiddleware::class]);
    Route::apiResource('/doctors', DoctorController::class);
    Route::apiResource('/patients', PatientController::class);
    Route::apiResource('/specialities', SpecialityController::class);
    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/permissions', PermissionController::class);
    Route::apiResource('/schedules', ScheduleController::class);
    Route::apiResource('/appointments', AppointmentController::class);
});