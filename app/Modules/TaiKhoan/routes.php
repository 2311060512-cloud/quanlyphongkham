<?php

use Illuminate\Support\Facades\Route;
use App\Modules\TaiKhoan\Controllers\DangNhapController;

Route::prefix('xac-thuc')->group(function () {
    Route::post('/dang-nhap', [DangNhapController::class, 'dangNhap']);
    Route::post('/dang-ky', [DangNhapController::class, 'dangKy']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/thong-tin', [DangNhapController::class, 'thongTinHienTai']);
        Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat']);
    });
});
