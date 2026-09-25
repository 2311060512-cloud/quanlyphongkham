<?php

use App\Http\Controllers\CongGiaoTiepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API GATEWAY ROUTES (Cổng giao tiếp tập trung - Port: 8000)
|--------------------------------------------------------------------------
| Định tuyến thông minh Reverse Proxy chuyển tiếp request đến 4 Microservices:
| - Dịch vụ 1 (Xác thực & Bác sĩ) - Port 8001
| - Dịch vụ 2 (Bệnh nhân & Lịch hẹn) - Port 8002
| - Dịch vụ 3 (Dịch vụ y tế & Cận lâm sàng) - Port 8003
| - Dịch vụ 4 (Hóa đơn & Thanh toán) - Port 8004
*/

// Health Check toàn hệ thống
Route::get('health', [CongGiaoTiepController::class, 'healthCheck']);
Route::get('v1/health', [CongGiaoTiepController::class, 'healthCheck']);

$dangKyDinhTuyenGateway = function () {

    // ========================================================================
    // 1. DỊCH VỤ 1: XÁC THỰC & BÁC SĨ (Port: 8001)
    // ========================================================================
    // 1.1 Tuyến đường công khai (Public - Không cần Token)
    Route::prefix('xac-thuc')->group(function () {
        Route::post('dang-nhap', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/dang-nhap'));
        Route::post('dang-ky', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/dang-ky'));
        Route::post('{subpath}', fn(Request $r, $subpath) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/xac-thuc/{$subpath}"))
            ->where('subpath', '^(?!thong-tin$).*');
    });

    // 1.2 Xem danh sách Bác sĩ & Chuyên khoa công khai
    Route::prefix('chuyen-khoa')->group(function () {
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/chuyen-khoa'));
        Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/chuyen-khoa'))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
    });

    Route::prefix('bac-si')->group(function () {
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/bac-si'));
        Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}"))->whereNumber('id');
        Route::get('{subpath}', fn(Request $r, $subpath) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$subpath}"));
        // Phân quyền: Chỉ ADMIN mới được thêm bác sĩ
        Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/bac-si'))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::post('{subpath}', fn(Request $r, $subpath) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$subpath}"))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
    });

    // 1.3 Thông tin tài khoản & Quản lý tài khoản
    Route::middleware(['xac_thuc_gateway'])->group(function () {
        Route::get('xac-thuc/thong-tin', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/thong-tin'));
        Route::prefix('tai-khoan')->group(function () {
            Route::any('{subpath?}', fn(Request $r, $subpath = '') => 
                app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/tai-khoan' . ($subpath ? "/{$subpath}" : ''))
            )->where('subpath', '.*');
        });
    });

    // ========================================================================
    // 2. DỊCH VỤ 2: BỆNH NHÂN & LỊCH HẸN (Port: 8002)
    // ========================================================================
        // Tra cứu khung giờ khám khả dụng công khai
    Route::get('lich-hen/slots-kha-dung', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/lich-hen/slots-kha-dung'));

    Route::middleware(['xac_thuc_gateway'])->group(function () {
        Route::prefix('benh-nhan')->group(function () {
            Route::any('{subpath?}', fn(Request $r, $subpath = '') => 
                app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan' . ($subpath ? "/{$subpath}" : ''))
            )->where('subpath', '.*');
        });

        Route::prefix('lich-hen')->group(function () {
            Route::any('{subpath?}', fn(Request $r, $subpath = '') => 
                app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/lich-hen' . ($subpath ? "/{$subpath}" : ''))
            )->where('subpath', '.*');
        });
    });

    // ========================================================================
    // 3. DỊCH VỤ 3: DỊCH VỤ Y TẾ & CẬN LÂM SÀNG (Port: 8003)
    // ========================================================================
    Route::prefix('dich-vu')->group(function () {
        // Công khai tra cứu danh mục cận lâm sàng
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', 'v1/dich-vu'));

        Route::middleware(['xac_thuc_gateway'])->group(function () {
            // Bác sĩ & Admin chỉ định cận lâm sàng
            Route::post('chi-dinh', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', 'v1/dich-vu/chi-dinh'))
                ->middleware('phan_quyen:ADMIN,BAC_SI');

            // Cập nhật kết quả xét nghiệm / hình ảnh
            Route::put('ket-qua/{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', "v1/dich-vu/ket-qua/{$id}"))
                ->middleware('phan_quyen:ADMIN,BAC_SI');

            // Lấy danh sách cận lâm sàng theo lịch hẹn
            Route::get('lich-hen/{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', "v1/dich-vu/lich-hen/{$id}"));

            // Thêm danh mục dịch vụ mới: Chỉ ADMIN
            Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', 'v1/dich-vu'))
                ->middleware('phan_quyen:ADMIN');
        });
    });

    // ========================================================================
    // 4. DỊCH VỤ 4: HÓA ĐƠN & THANH TOÁN (Port: 8004)
    // ========================================================================
    Route::middleware(['xac_thuc_gateway'])->prefix('hoa-don')->group(function () {
        // Quản trị thống kê doanh thu: Bắt buộc quyền ADMIN
        Route::get('thong-ke', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', 'v1/hoa-don/thong-ke'))
            ->middleware('phan_quyen:ADMIN');

        // Tạo hóa đơn tự động (Admin hoặc Bác sĩ kết thúc khám)
        Route::post('tao-tu-dong', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', 'v1/hoa-don/tao-tu-dong'))
            ->middleware('phan_quyen:ADMIN,BAC_SI');

        // Thanh toán hóa đơn (Bệnh nhân hoặc Admin)
        Route::put('{id}/thanh-toan', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', "v1/hoa-don/{$id}/thanh-toan"));

        // Chi tiết và danh sách hóa đơn
        Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', "v1/hoa-don/{$id}"))->whereNumber('id');
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', 'v1/hoa-don'));
    });
};

// Đăng ký cả chuẩn /api/v1/... và /api/... để tương thích tối đa
Route::prefix('v1')->group($dangKyDinhTuyenGateway);
$dangKyDinhTuyenGateway();
