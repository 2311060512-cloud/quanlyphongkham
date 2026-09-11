<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BacSi\Controllers\BacSiController;

Route::prefix('bac-si')->group(function () {
    Route::get('/', [BacSiController::class, 'danhSach']);
    Route::get('/chuyen-khoa', [BacSiController::class, 'danhSachChuyenKhoa']);
    Route::get('/{id}', [BacSiController::class, 'chiTiet']);

    Route::middleware(['auth:sanctum', 'phan_quyen:ADMIN'])->group(function () {
        Route::post('/', [BacSiController::class, 'taoMoi']);
    });
});
