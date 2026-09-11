<?php

use Illuminate\Support\Facades\Route;
use App\Modules\LichHen\Controllers\LichHenController;

Route::prefix('lich-hen')->group(function () {
    Route::get('/', [LichHenController::class, 'danhSach']);
    Route::post('/dat-lich', [LichHenController::class, 'datLich']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/lich-su-cua-toi', [LichHenController::class, 'lichSuCuaToi']);
        Route::get('/lich-kham-bac-si', [LichHenController::class, 'lichKhamBacSi']);
        
        Route::post('/{id}/xac-nhan', [LichHenController::class, 'xacNhan']);
        Route::post('/{id}/bat-dau', [LichHenController::class, 'batDau']);
        Route::post('/{id}/hoan-thanh', [LichHenController::class, 'hoanThanh']);
        Route::post('/{id}/huy', [LichHenController::class, 'huy']);
    });
});
