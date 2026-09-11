<?php

use Illuminate\Support\Facades\Route;
use App\Modules\HoaDon\Controllers\HoaDonController;

Route::prefix('hoa-don')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [HoaDonController::class, 'danhSach']);
    Route::get('/thong-ke', [HoaDonController::class, 'thongKe'])->middleware('phan_quyen:ADMIN');
    Route::get('/{id}', [HoaDonController::class, 'chiTiet']);
    Route::post('/{id}/thanh-toan', [HoaDonController::class, 'thanhToan'])->middleware('phan_quyen:ADMIN');
});
