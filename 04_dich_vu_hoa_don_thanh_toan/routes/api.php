<?php

use App\Http\Controllers\HoaDonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service 04: Dich Vu Hoa Don & Thanh Toan (Port: 8004)
|--------------------------------------------------------------------------
*/

$dinhTuyenDichVu = function () {
    Route::prefix('hoa-don')->group(function () {
        Route::get('/', [HoaDonController::class, 'danhSach']);
        Route::get('thong-ke', [HoaDonController::class, 'thongKe']);
        Route::post('tao-tu-dong', [HoaDonController::class, 'taoTuDong']);
        Route::get('{id}', [HoaDonController::class, 'chiTiet'])->whereNumber('id');
        Route::put('{id}/thanh-toan', [HoaDonController::class, 'thanhToan'])->whereNumber('id');
    });
};

$dinhTuyenDichVu();
Route::prefix('v1')->group($dinhTuyenDichVu);
