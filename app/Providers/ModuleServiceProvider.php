<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Module TaiKhoan
        $this->app->bind(
            \App\Modules\TaiKhoan\Repositories\TaiKhoanRepositoryInterface::class,
            \App\Modules\TaiKhoan\Repositories\TaiKhoanRepository::class
        );

        // 2. Module BacSi
        $this->app->bind(
            \App\Modules\BacSi\Repositories\BacSiRepositoryInterface::class,
            \App\Modules\BacSi\Repositories\BacSiRepository::class
        );
        $this->app->bind(
            \App\Modules\BacSi\Repositories\ChuyenKhoaRepositoryInterface::class,
            \App\Modules\BacSi\Repositories\ChuyenKhoaRepository::class
        );

        // 3. Module BenhNhan
        $this->app->bind(
            \App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface::class,
            \App\Modules\BenhNhan\Repositories\BenhNhanRepository::class
        );

        // 4. Module LichHen
        $this->app->bind(
            \App\Modules\LichHen\Repositories\LichHenRepositoryInterface::class,
            \App\Modules\LichHen\Repositories\LichHenRepository::class
        );

        // 5. Module DichVu
        $this->app->bind(
            \App\Modules\DichVu\Repositories\DichVuRepositoryInterface::class,
            \App\Modules\DichVu\Repositories\DichVuRepository::class
        );
        $this->app->bind(
            \App\Modules\DichVu\Repositories\SuDungDichVuRepositoryInterface::class,
            \App\Modules\DichVu\Repositories\SuDungDichVuRepository::class
        );

        // 6. Module HoaDon
        $this->app->bind(
            \App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface::class,
            \App\Modules\HoaDon\Repositories\HoaDonRepository::class
        );
    }

    public function boot(): void
    {
        $modulesPath = app_path('Modules');
        if (is_dir($modulesPath)) {
            $modules = array_filter(glob($modulesPath . '/*'), 'is_dir');
            foreach ($modules as $module) {
                $routeFile = $module . '/routes.php';
                if (file_exists($routeFile)) {
                    Route::prefix('api/v1')
                        ->middleware('api')
                        ->group($routeFile);
                }
            }
        }
    }
}
