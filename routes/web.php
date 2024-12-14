<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource('users', UserController::class);
Route::resource('doctors', DoctorController::class);
Route::resource('specialities', SpecialityController::class);
Route::resource('patients', PatientController::class);
Route::resource('appointments', AppointmentController::class);
Route::resource('permissions', PermissionController::class);
Route::resource('roles', RoleController::class);
Route::resource('schedules', ScheduleController::class);
Route::get('my-appointments', [AppointmentController::class, 'myAppointments'])->name('my-appointments')->middleware('auth');
Route::get('my-schedule', [ScheduleController::class, 'mySchedules'])->name('my-schedules')->middleware('auth');
Route::put('/appointments/{id}/edit', [AppointmentController::class, 'editMyAppointment'])->name('editMyAppointment');
Route::delete('/my-appointments/{id}', [AppointmentController::class, 'deleteMyAppointment'])->name('myAppointments.destroy');
Route::get('/specialities/{id}/doctors', [SpecialityController::class, 'doctorsBySpeciality'])->name('doctors.speciality');
Route::get('choose-speciality', [SpecialityController::class, 'chooseSpeciality'])->name('specialities.choose');
Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');


Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payment/{id}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payments/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::delete('/payments/{id}/delete', [PaymentController::class, 'delete'])->name('payment.delete');

Route::resource('reviews', ReviewController::class);