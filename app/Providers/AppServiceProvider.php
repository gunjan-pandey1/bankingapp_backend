<?php

namespace App\Providers;

use App\Repository\LoanRepository;
use App\Repository\LoginRepository;
use Illuminate\Support\Facades\URL;
use App\Repository\RegisterRepository;
use App\Repository\UserFormRepository;
use App\Repository\DashboardRepository;
use App\Repository\LoanApplyRepository;
use App\Repository\Mysql\LoanApplyImpl;
use Illuminate\Support\ServiceProvider;
use App\Repository\TxnDetailsRepository;
use Illuminate\Support\Facades\Password;
use App\Repository\BankDetailsRepository;
use App\Repository\LoanHistoryRepository;
use App\Repository\UserDetailsRepository;
use App\Repository\UpdateProfileRepository;
use App\Repository\Mysql\LoanRepositoryImpl;
use App\Repository\LoanViewDetailsRepository;
use App\Repository\Mysql\LoginRepositoryImpl;
use App\Repository\Mysql\RegisterRepositoryImpl;
use App\Repository\Mysql\UserFormRepositoryImpl;
use App\Repository\ForgetPasswordEmailRepository;
use App\Repository\Mysql\DashboardRepositoryImpl;
use App\Repository\Mysql\TxnDetailsRepositoryImpl;
use App\Repository\Mysql\BankDetailsRepositoryImpl;
use App\Repository\Mysql\LoanHistoryRepositoryImpl;
use App\Repository\Mysql\UserDetailsRepositoryImpl;
use App\Repository\Mysql\UpdateProfileRepositoryImpl;
use App\Repository\Mysql\LoanViewDetailsRepositoryImpl;
use App\Repository\Mysql\ForgetPasswordEmailRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepository::class, RegisterRepositoryImpl::class);
        $this->app->bind(LoanRepository::class, LoanRepositoryImpl::class);
        $this->app->bind(DashboardRepository::class, DashboardRepositoryImpl::class);
        $this->app->bind(BankDetailsRepository::class, BankDetailsRepositoryImpl::class);
        $this->app->bind(LoanViewDetailsRepository::class, LoanViewDetailsRepositoryImpl::class);
        $this->app->bind(TxnDetailsRepository::class, TxnDetailsRepositoryImpl::class);
        $this->app->bind(LoanHistoryRepository::class, LoanHistoryRepositoryImpl::class);
        $this->app->bind(UpdateProfileRepository::class, UpdateProfileRepositoryImpl::class);
        



        

        // $this->app->bind(UserDetailsRepository::class, UserDetailsRepositoryImpl::class);
        $this->app->bind(ForgetPasswordEmailRepository::class, ForgetPasswordEmailRepositoryImpl::class);
        $this->app->bind(LoanRepository::class, LoanRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }
}
