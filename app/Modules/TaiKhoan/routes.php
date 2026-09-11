<?php

use Illuminate\Support\Facades\Route;
use App\Modules\TaiKhoan\Controllers\DangNhapController;
use App\Modules\TaiKhoan\Controllers\TaiKhoanController;

/**
 * MODULE TAI KHOAN
 * Định tuyến cho chức năng Xác thực, Phân quyền và Quản lý tài khoản
 */

// ================= NHÓM 1: XÁC THỰC CƠ BẢN (ĐĂNG NHẬP / ĐĂNG KÝ) =================
Route::prefix('tai-khoan')->group(function () {
    // API 1: Đăng nhập hệ thống (trả về Sanctum token, thông tin tài khoản và vai trò)
    Route::post('/dang-nhap', [DangNhapController::class, 'dangNhap']);

    // API 2: Đăng ký tài khoản bệnh nhân mới
    Route::post('/dang-ky', [DangNhapController::class, 'dangKy']);

    // API 2.1: Quên mật khẩu (Khôi phục mật khẩu qua email cho bác sĩ, bệnh nhân, admin)
    Route::post('/quen-mat-khau', [DangNhapController::class, 'quenMatKhau']);

    // Các API yêu cầu đã đăng nhập (Token Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        // API 3: Lấy thông tin tài khoản hiện tại kèm vai trò và hồ sơ
        Route::get('/thong-tin', [DangNhapController::class, 'thongTinHienTai']);

        // API 4: Đổi mật khẩu tài khoản hiện tại (kiểm tra mật khẩu cũ, mã hóa mật khẩu mới)
        Route::post('/doi-mat-khau', [DangNhapController::class, 'doiMatKhau']);

        // API 5: Đăng xuất (thu hồi Access Token)
        Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat']);

        // ================= NHÓM 2: DÀNH RIÊNG CHO QUẢN TRỊ VIÊN (ADMIN) =================
        Route::middleware('phan_quyen:ADMIN')->group(function () {
            // API 6: Xem danh sách tài khoản (có phân trang, tìm kiếm từ khóa, lọc theo vai_tro_id)
            Route::get('/', [TaiKhoanController::class, 'danhSach']);

            // API 7: Lấy danh sách các vai trò trong hệ thống
            Route::get('/vai-tro', [TaiKhoanController::class, 'danhSachVaiTro']);

            // API 8: Khóa / Mở khóa tài khoản (chuyển đổi HOAT_DONG <-> KHOA)
            Route::post('/{id}/khoa-mo-khoa', [TaiKhoanController::class, 'khoaMoKhoa']);
        });
    });
});

// Alias tương thích ngược cho route /xac-thuc/...
Route::prefix('xac-thuc')->group(function () {
    Route::post('/dang-nhap', [DangNhapController::class, 'dangNhap']);
    Route::post('/dang-ky', [DangNhapController::class, 'dangKy']);
    Route::post('/quen-mat-khau', [DangNhapController::class, 'quenMatKhau']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/thong-tin', [DangNhapController::class, 'thongTinHienTai']);
        Route::post('/doi-mat-khau', [DangNhapController::class, 'doiMatKhau']);
        Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat']);
    });
});
