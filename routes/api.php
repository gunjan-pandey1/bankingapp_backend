<?php

use App\Http\Controllers\BankDetailsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmiRepaymentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanDetailsController;
use App\Http\Controllers\LoanViewDetailsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileDetailsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TxnDetailsController;
use App\Http\Controllers\UserDetailsController;
use Illuminate\Support\Facades\Route;

//Onboarding
Route::post('/register', [RegisterController::class, 'registerprocess'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('password/forgot', [ForgotPasswordController::class, 'forgetPassword'])->name('forgot-password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset-password');

//dashboard & UI
Route::get('/dashboard', [DashboardController::class, 'dashboardDetails'])->name('dashboard');
Route::get('/loansDetails', [LoanDetailsController::class, 'loansDetails'])->name('loansDetails');
Route::get('/loanViewDetails', [LoanViewDetailsController::class, 'loanViewDetails'])->name('loanViewDetails');
Route::get('/txnDetails', [TxnDetailsController::class, 'txnDetails'])->name('txnDetails');
Route::get('/profileDetails', [ProfileDetailsController::class, 'profileDetails'])->name('profileDetails');

//Loan Application Process
Route::post('/bankDetails', [BankDetailsController::class, 'bankDetails'])->name('bankDetails');
Route::post('/loanApplication', [LoanApplicationController::class, 'loanApplicationProcess'])->name('loanApplication');
Route::post('/emiRepayment', [EmiRepaymentController::class, 'emiRepayment'])->name('emiRepayment');

//ADMIN LEVEL
Route::get('/user-details', [UserDetailsController::class, 'getAllUsers']);
Route::delete('/user-details/{id}', [UserDetailsController::class, 'deleteUser']);
