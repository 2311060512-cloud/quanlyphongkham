<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\TaiKhoanRepositoryInterface::class,
            \App\Repositories\Eloquents\TaiKhoanRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\BacSiRepositoryInterface::class,
            \App\Repositories\Eloquents\BacSiRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ChuyenKhoaRepositoryInterface::class,
            \App\Repositories\Eloquents\ChuyenKhoaRepository::class
        );
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}