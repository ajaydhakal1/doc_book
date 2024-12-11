<?php

use App\Http\Controllers\api\v1\AppointmentController;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\v1\DoctorController;
use App\Http\Controllers\api\v1\PatientController;
use App\Http\Controllers\api\v1\PaymentController;
use App\Http\Controllers\api\v1\PermissionController;
use App\Http\Controllers\api\v1\ReviewController;
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
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('/schedules', ScheduleController::class);
        Route::apiResource('/appointments', AppointmentController::class);
        Route::apiResource('/patients', PatientController::class)->except('store');
        Route::apiResource('/specialities', SpecialityController::class);
        Route::apiResource('/doctors', DoctorController::class);
        Route::apiResource('/payments', PaymentController::class);
        Route::apiResource('/reviews', ReviewController::class);
        Route::put('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');

        Route::middleware(AdminMiddleware::class)->group(function () {
            Route::apiResource('/users', UserController::class);
            Route::apiResource('/permissions', PermissionController::class);
            Route::apiResource('/roles', RoleController::class);
        });
    });

    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});