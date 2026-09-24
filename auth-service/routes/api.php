<?php

use App\Http\Controllers\BacSiController;
use App\Http\Controllers\ChuyenKhoaController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\XacThucController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MICROSERVICE 1: XÁC THỰC, TÀI KHOẢN & BÁC SĨ (Port: 8001)
|--------------------------------------------------------------------------
*/

$dinhTuyenMicroservice1 = function () {

    // Phân hệ A: Xác Thực & Tài Khoản (Module TaiKhoan)
    Route::prefix('xac-thuc')->group(function () {
        Route::post('dang-nhap', [XacThucController::class, 'dangNhap']);
        Route::post('dang-ky', [XacThucController::class, 'dangKy']);
        Route::get('thong-tin', [XacThucController::class, 'thongTin']);
        Route::post('dang-xuat', [XacThucController::class, 'dangXuat']);
        Route::put('doi-mat-khau', [XacThucController::class, 'doiMatKhau']);
    });

    Route::prefix('tai-khoan')->group(function () {
        Route::get('/', [TaiKhoanController::class, 'danhSach']);
        Route::patch('{id}/trang-thai', [TaiKhoanController::class, 'capNhatTrangThai'])->whereNumber('id');
        Route::put('{id}/doi-mat-khau', [TaiKhoanController::class, 'doiMatKhau'])->whereNumber('id');
    });

    // Phân hệ B: Chuyên Khoa & Bác Sĩ (Module BacSi)
    Route::prefix('chuyen-khoa')->group(function () {
        Route::get('/', [ChuyenKhoaController::class, 'danhSach']);
        Route::post('/', [ChuyenKhoaController::class, 'themMoi']);
    });

    Route::prefix('bac-si')->group(function () {
        // Alias tương thích ngược cho bac-si/chuyen-khoa
        Route::get('chuyen-khoa', [ChuyenKhoaController::class, 'danhSach']);
        Route::post('chuyen-khoa', [ChuyenKhoaController::class, 'themMoi']);

        // CRUD Bác sĩ
        Route::get('/', [BacSiController::class, 'danhSach']);
        Route::get('{id}', [BacSiController::class, 'chiTiet'])->whereNumber('id');
        Route::post('/', [BacSiController::class, 'themMoi']);
        Route::put('{id}', [BacSiController::class, 'capNhat'])->whereNumber('id');
    });
};

// Đăng ký cả chuẩn /api/v1/... và /api/...
Route::prefix('v1')->group($dinhTuyenMicroservice1);
$dinhTuyenMicroservice1();
