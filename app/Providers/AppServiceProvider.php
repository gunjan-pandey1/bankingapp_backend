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
use App\Repository\UserDetailsRepository;
use App\Repository\Mysql\LoanRepositoryImpl;
use App\Repository\Mysql\LoginRepositoryImpl;
use App\Repository\Mysql\RegisterRepositoryImpl;
use App\Repository\Mysql\UserFormRepositoryImpl;
use App\Repository\ForgetPasswordEmailRepository;
use App\Repository\Mysql\DashboardRepositoryImpl;
use App\Repository\Mysql\UserDetailsRepositoryImpl;
use App\Repository\Mysql\ForgetPasswordEmailRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepository::class, RegisterRepositoryImpl::class);
        $this->app->bind(LoginRepository::class, LoginRepositoryImpl::class);
        $this->app->bind(LoanRepository::class, LoanRepositoryImpl::class);
        $this->app->bind(LoanApplyRepository::class, LoanApplyImpl::class);
        $this->app->bind(DashboardRepository::class, DashboardRepositoryImpl::class);


        

        // $this->app->bind(UserDetailsRepository::class, UserDetailsRepositoryImpl::class);
        $this->app->bind(UserFormRepository::class, UserFormRepositoryImpl::class);
        $this->app->bind(ForgetPasswordEmailRepository::class, ForgetPasswordEmailRepositoryImpl::class);
        $this->app->bind(LoanRepository::class, LoanRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (env('APP_ENV') !== 'local') {
        //     URL::forceScheme('https');
        // }
    }
}
