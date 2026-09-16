<?php

use App\Http\Controllers\DichVuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service 03: Dich Vu Y Te Can Lam Sang (Port: 8003)
|--------------------------------------------------------------------------
*/

$dinhTuyenDichVu = function () {
    Route::prefix('dich-vu')->group(function () {
        Route::get('/', [DichVuController::class, 'danhSach']);
        Route::post('/', [DichVuController::class, 'themMoi']);
        Route::post('chi-dinh', [DichVuController::class, 'chiDinh']);
        Route::put('ket-qua/{id}', [DichVuController::class, 'capNhatKetQua'])->whereNumber('id');
        Route::get('lich-hen/{lich_hen_id}', [DichVuController::class, 'danhSachTheoLichHen'])->whereNumber('lich_hen_id');
    });
};

$dinhTuyenDichVu();
Route::prefix('v1')->group($dinhTuyenDichVu);
