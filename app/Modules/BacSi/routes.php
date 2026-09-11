<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BacSi\Controllers\BacSiController;
use App\Modules\BacSi\Controllers\ChuyenKhoaController;

/**
 * MODULE BAC SI
 * Định tuyến cho chức năng Quản lý Chuyên khoa và Quản lý Bác sĩ
 */

Route::prefix('bac-si')->group(function () {

    // ================= NHÓM 1: QUẢN LÝ CHUYÊN KHOA KHÁM BỆNH =================
    // API 1: Lấy danh sách toàn bộ chuyên khoa khám bệnh (Công khai)
    Route::get('/chuyen-khoa', [ChuyenKhoaController::class, 'danhSach']);

    // API 2: Lấy chi tiết chuyên khoa (Công khai)
    Route::get('/chuyen-khoa/{id}', [ChuyenKhoaController::class, 'chiTiet']);

    // ================= NHÓM 2: TRA CỨU BÁC SĨ (CÔNG KHAI) =================
    // API 3: Xem danh sách bác sĩ (hỗ trợ phân trang ?per_page=10, tìm kiếm ?tu_khoa=..., lọc ?chuyen_khoa_id=..., hoặc lấy tất cả ?all=true)
    Route::get('/', [BacSiController::class, 'danhSach']);

    // API 4: Xem chi tiết bác sĩ kèm thông tin chuyên khoa và lịch sử công tác
    Route::get('/{id}', [BacSiController::class, 'chiTiet']);

    // ================= NHÓM 3: CẬP NHẬT HỒ SƠ BÁC SĨ (ADMIN HOẶC BÁC SĨ TỰ CẬP NHẬT) =================
    Route::middleware(['auth:sanctum', 'phan_quyen:ADMIN,BAC_SI'])->group(function () {
        // API 9: Cập nhật thông tin bác sĩ (chỉnh sửa giá khám, ảnh đại diện, đổi phòng làm việc, ca làm việc, học vị, kinh nghiệm)
        Route::put('/{id}', [BacSiController::class, 'capNhat']);
    });

    // ================= NHÓM 4: DÀNH RIÊNG CHO QUẢN TRỊ VIÊN (ADMIN) =================
    Route::middleware(['auth:sanctum', 'phan_quyen:ADMIN'])->group(function () {
        
        // --- QUẢN TRỊ CHUYÊN KHOA ---
        // API 5: Thêm chuyên khoa mới
        Route::post('/chuyen-khoa', [ChuyenKhoaController::class, 'taoMoi']);

        // API 6: Cập nhật thông tin chuyên khoa
        Route::put('/chuyen-khoa/{id}', [ChuyenKhoaController::class, 'capNhat']);

        // API 7: Xóa chuyên khoa (nếu không có bác sĩ trực thuộc)
        Route::delete('/chuyen-khoa/{id}', [ChuyenKhoaController::class, 'xoa']);

        // --- QUẢN TRỊ BÁC SĨ ---
        // API 8: Thêm bác sĩ mới (Tự động tạo tài khoản đăng nhập với vai trò BAC_SI và mật khẩu mặc định)
        Route::post('/', [BacSiController::class, 'taoMoi']);

        // API 10: Đổi trạng thái bác sĩ (DANG_LAM_VIEC / NGHI_PHEP / NGHI_VIEC)
        Route::post('/{id}/doi-trang-thai', [BacSiController::class, 'doiTrangThai']);
    });
});
