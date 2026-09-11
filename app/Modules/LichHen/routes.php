<?php

use Illuminate\Support\Facades\Route;
use App\Modules\LichHen\Controllers\LichHenController;

Route::prefix('lich-hen')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [LichHenController::class, 'danhSach']);
    Route::post('/dat-lich', [LichHenController::class, 'datLich']);
    Route::get('/lich-su-cua-toi', [LichHenController::class, 'lichSuCuaToi']);
    Route::get('/lich-kham-bac-si', [LichHenController::class, 'lichKhamBacSi'])->middleware('phan_quyen:ADMIN,BAC_SI');
    
    Route::post('/{id}/xac-nhan', [LichHenController::class, 'xacNhan'])->middleware('phan_quyen:ADMIN,BAC_SI');
    Route::post('/{id}/bat-dau', [LichHenController::class, 'batDau'])->middleware('phan_quyen:ADMIN,BAC_SI');
    Route::post('/{id}/hoan-thanh', [LichHenController::class, 'hoanThanh'])->middleware('phan_quyen:ADMIN,BAC_SI');
    Route::post('/{id}/huy', [LichHenController::class, 'huy']);
});
