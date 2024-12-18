<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\JwtAuthMiddleware;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TxnDetailsController;
use App\Http\Controllers\BankDetailsController;
use App\Http\Controllers\CreditScoreController;
use App\Http\Controllers\LoanDetailsController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\EmiRepaymentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ProfileDetailsController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanViewDetailsController;
use App\Http\Middleware\AuthenticateWithRefreshToken;

//Onboarding
Route::post('/register', [RegisterController::class, 'registerprocess'])->name('register');
Route::post('/login', [LoginController::class, 'loginprocess'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('forgetPassword', [ForgetPasswordController::class, 'forgetPasswordProcess'])->name('forget-password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset-password');


//dashboard & UI
Route::middleware([AuthenticateWithRefreshToken::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Other protected routes
});


// Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
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
