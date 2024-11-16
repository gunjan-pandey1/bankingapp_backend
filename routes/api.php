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

 
Route::post('/register', [RegisterController::class, 'registerprocess']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/user-details', [UserDetailsController::class, 'getAllUsers']);
Route::delete('/user-details/{id}', [UserDetailsController::class, 'deleteUser']);
Route::post('/user-form', [UserFormController::class, 'userFormProcess']);
Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');