<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserFormController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController; 

//USER LEVEL
Route::post('/register', [RegisterController::class, 'registerprocess'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('password/forgot', [ForgotPasswordController::class, 'forgetPassword'])->name('forgot-password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset-password');
Route::get('/dashboard', [DashboardController::class, 'dashboardDetails'])->name('dashboard');



//ADMIN LEVEL
Route::get('/user-details', [UserDetailsController::class, 'getAllUsers']);
Route::delete('/user-details/{id}', [UserDetailsController::class, 'deleteUser']);
Route::post('/user-form', [UserFormController::class, 'userFormProcess']);