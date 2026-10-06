<?php

use App\Http\Controllers\DichVuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service 03: Dich Vu Y Te & Can Lam Sang (Port: 8003)
| Phụ trách: Người 3 (Khải)
|--------------------------------------------------------------------------
*/

$dinhTuyenDichVu = function () {
    // 1. Quản lý Danh mục Dịch vụ Y tế (Bảng giá CLS)
    Route::prefix('dich-vu')->group(function () {
        Route::get('/', [DichVuController::class, 'danhSach']);
        Route::post('/', [DichVuController::class, 'themMoi']);
        Route::put('{id}', [DichVuController::class, 'capNhat'])->whereNumber('id');
        Route::patch('{id}/toggle-status', [DichVuController::class, 'toggleStatus'])->whereNumber('id');
        
        // Chỉ định & kết quả
        Route::post('chi-dinh', [DichVuController::class, 'chiDinh']);
        Route::delete('chi-dinh/{id}', [DichVuController::class, 'huyChiDinh'])->whereNumber('id');
        Route::put('ket-qua/{id}', [DichVuController::class, 'capNhatKetQua'])->whereNumber('id');
        Route::get('lich-hen/{lich_hen_id}', [DichVuController::class, 'danhSachTheoLichHen'])->whereNumber('lich_hen_id');
    });

    // 2. Phân hệ Bác sĩ Khám bệnh & Hồ sơ bệnh án
    Route::prefix('kham-benh')->group(function () {
        Route::post('chi-dinh', [DichVuController::class, 'chiDinh']);
        Route::delete('chi-dinh/{id}', [DichVuController::class, 'huyChiDinh'])->whereNumber('id');
        Route::get('{lichHenId}/dich-vu', [DichVuController::class, 'danhSachTheoLichHen'])->whereNumber('lichHenId');
        Route::post('{lichHenId}/hoan-thanh', [DichVuController::class, 'hoanThanh'])->whereNumber('lichHenId');
        Route::get('{lichHenId}/ho-so', [DichVuController::class, 'hoSo'])->whereNumber('lichHenId');
    });

    // 3. Phân hệ Kỹ thuật viên Cận lâm sàng
    Route::prefix('can-lam-sang')->group(function () {
        Route::get('danh-sach-cho', [DichVuController::class, 'danhSachCho']);
        Route::put('{id}/ket-qua', [DichVuController::class, 'capNhatKetQua'])->whereNumber('id');
    });
};

$dinhTuyenDichVu();
Route::prefix('v1')->group($dinhTuyenDichVu);
