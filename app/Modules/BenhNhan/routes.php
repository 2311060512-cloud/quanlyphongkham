<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BenhNhan\Controllers\BenhNhanController;

Route::prefix('benh-nhan')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [BenhNhanController::class, 'danhSach'])->middleware('phan_quyen:ADMIN,BAC_SI');
    Route::get('/{id}', [BenhNhanController::class, 'chiTiet']);
    Route::put('/{id}', [BenhNhanController::class, 'capNhat']);
});
