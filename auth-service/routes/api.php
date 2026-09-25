<?php

use App\Http\Controllers\BacSiController;
use App\Http\Controllers\ChuyenKhoaController;
use App\Http\Controllers\LichTrucController;
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
        Route::put('ho-so', [XacThucController::class, 'capNhatHoSo']);
        Route::post('avatar', [XacThucController::class, 'capNhatAvatar']);
    });

    Route::prefix('tai-khoan')->group(function () {
        Route::get('/', [TaiKhoanController::class, 'danhSach']);
        Route::patch('{id}/trang-thai', [TaiKhoanController::class, 'capNhatTrangThai'])->whereNumber('id');
        Route::put('{id}/doi-mat-khau', [TaiKhoanController::class, 'doiMatKhau'])->whereNumber('id');
        Route::delete('{id}', [TaiKhoanController::class, 'xoa'])->whereNumber('id');
    });

    // Phân hệ B: Chuyên Khoa & Bác Sĩ (Module BacSi)
    Route::prefix('chuyen-khoa')->group(function () {
        Route::get('/', [ChuyenKhoaController::class, 'danhSach']);
        Route::post('/', [ChuyenKhoaController::class, 'themMoi']);
        Route::put('{id}', [ChuyenKhoaController::class, 'capNhat'])->whereNumber('id');
        Route::delete('{id}', [ChuyenKhoaController::class, 'xoa'])->whereNumber('id');
    });

    Route::prefix('bac-si')->group(function () {
        // Alias tương thích ngược cho bac-si/chuyen-khoa
        Route::get('chuyen-khoa', [ChuyenKhoaController::class, 'danhSach']);
        Route::post('chuyen-khoa', [ChuyenKhoaController::class, 'themMoi']);

        // Lịch trực bác sĩ
        Route::get('{id}/lich-truc', [LichTrucController::class, 'layTheoBacSi'])->whereNumber('id');
        Route::post('{id}/lich-truc', [LichTrucController::class, 'themMoi'])->whereNumber('id');
        Route::put('lich-truc/{id}', [LichTrucController::class, 'capNhat'])->whereNumber('id');
        Route::delete('lich-truc/{id}', [LichTrucController::class, 'xoa'])->whereNumber('id');
        Route::get('{id}/kiem-tra-truc', [LichTrucController::class, 'kiemTraTruc'])->whereNumber('id');

        // CRUD Bác sĩ
        Route::get('/', [BacSiController::class, 'danhSach']);
        Route::get('{id}', [BacSiController::class, 'chiTiet'])->whereNumber('id');
        Route::post('/', [BacSiController::class, 'themMoi']);
        Route::put('{id}', [BacSiController::class, 'capNhat'])->whereNumber('id');
        Route::delete('{id}', [BacSiController::class, 'xoa'])->whereNumber('id');
    });

    // Lịch trực chung
    Route::prefix('lich-truc')->group(function () {
        Route::get('/', [LichTrucController::class, 'danhSach']);
        Route::post('/', [LichTrucController::class, 'themMoi']);
        Route::put('{id}', [LichTrucController::class, 'capNhat'])->whereNumber('id');
        Route::delete('{id}', [LichTrucController::class, 'xoa'])->whereNumber('id');
    });
};

// Đăng ký cả chuẩn /api/v1/... và /api/...
Route::prefix('v1')->group($dinhTuyenMicroservice1);
$dinhTuyenMicroservice1();
