<?php

namespace App\Providers;

use App\Repository\LoginRepository;
use App\Repository\UserDetailsRepository;
use App\Repository\UserFormRepository;
use App\Repository\RegisterRepository;
use App\Repository\ResetPasswordRepository;
use App\Repository\ForgetPasswordEmailRepository;


use App\Repository\Mysql\UserDetailsRepositoryImpl;
use App\Repository\Mysql\UserFormRepositoryImpl;
use App\Repository\Mysql\ResetPasswordRepositoryImpl;
use App\Repository\Mysql\ForgetPasswordEmailRepositoryImpl;
use App\Repository\Mysql\LoginRepositoryImpl;
use App\Repository\Mysql\RegisterRepositoryImpl;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepository::class, RegisterRepositoryImpl::class);
        $this->app->bind(LoginRepository::class, LoginRepositoryImpl::class);
        // $this->app->bind(UserDetailsRepository::class, UserDetailsRepositoryImpl::class);
        $this->app->bind(UserFormRepository::class, UserFormRepositoryImpl::class);
        $this->app->bind(ForgetPasswordEmailRepository::class, ForgetPasswordEmailRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
