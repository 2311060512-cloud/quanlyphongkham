<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DichVu\Controllers\DichVuController;

Route::prefix('dich-vu')->group(function () {
    Route::get('/', [DichVuController::class, 'danhSach']);

    Route::middleware(['auth:sanctum', 'phan_quyen:ADMIN,BAC_SI'])->group(function () {
        Route::post('/chi-dinh', [DichVuController::class, 'chiDinh']);
        Route::put('/ket-qua/{id}', [DichVuController::class, 'capNhatKetQua']);
    });
});
