<?php

use App\Http\Controllers\BenhNhanController;
use App\Http\Controllers\LichHenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service 02: Dich Vu Benh Nhan & Lich Hen (Port: 8002)
|--------------------------------------------------------------------------
*/

$dinhTuyenDichVu = function () {
    Route::prefix('benh-nhan')->group(function () {
        Route::get('/', [BenhNhanController::class, 'danhSach']);
        Route::post('/', [BenhNhanController::class, 'taoMoi']);
        Route::get('{id}', [BenhNhanController::class, 'chiTiet'])->whereNumber('id');
    });

    Route::prefix('lich-hen')->group(function () {
        Route::get('/', [LichHenController::class, 'danhSach']);
        Route::get('{id}', [LichHenController::class, 'chiTiet'])->whereNumber('id');
        Route::post('dat-lich', [LichHenController::class, 'datLich']);
        Route::put('{id}/hoan-thanh', [LichHenController::class, 'hoanThanh'])->whereNumber('id');
        Route::put('{id}/huy', [LichHenController::class, 'huy'])->whereNumber('id');
    });
};

$dinhTuyenDichVu();
Route::prefix('v1')->group($dinhTuyenDichVu);
