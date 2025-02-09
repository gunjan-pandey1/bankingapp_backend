<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RabbitMQController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanApplyController;
use App\Http\Controllers\TxnDetailsController;
use App\Http\Controllers\BankDetailsController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\EmiRepaymentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgetPasswordController;

use App\Http\Controllers\ProfileDetailsController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanViewDetailsController;

//Onboarding
Route::post('/register', [RegisterController::class, 'registerprocess'])->name('register');
Route::post('/login', [LoginController::class, 'loginprocess'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('forgetPassword', [ForgetPasswordController::class, 'forgetPasswordProcess'])->name('forget-password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset-password');


// Route::prefix('profile')->group(function () {
    Route::post('/bank-details', [BankDetailsController::class, 'bankDetailsProcess'])->name('bank-details-process');
    Route::get('/profileDetails', [ProfileDetailsController::class, 'profileDetails'])->name('profileDetails');
    // Route::middleware('auth:sanctum')->group(function () {
        Route::get('/referral/generate', [ReferralController::class, 'generateReferral']);
        Route::post('/referral/validate', [ReferralController::class, 'validateReferral']);
    // });
// });


//dashboard & UI
// Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
// });



Log::channel('info')->info("enter in the route");
Route::get('/loans', [LoanApplicationController::class, 'getLoans'])->name('loans.index'); 

Route::post('/loanViewDetails', [LoanViewDetailsController::class, 'loanViewDetails'])->name('loanViewDetails');
Route::get('/txnDetails', [TxnDetailsController::class, 'txnDetailsProcess'])->name('txnDetails');

//Loan Application Process
Route::post('/emiRepayment', [EmiRepaymentController::class, 'emiRepayment'])->name('emiRepayment');

//ADMIN LEVEL
Route::get('/user-details', [UserDetailsController::class, 'getAllUsers']);
Route::delete('/user-details/{id}', [UserDetailsController::class, 'deleteUser']);

Route::get('/send-message', [RabbitMQController::class, 'sendMessage']);
