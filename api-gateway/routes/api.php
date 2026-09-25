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
        Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/chuyen-khoa/{$id}"))->whereNumber('id');
        Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/chuyen-khoa'))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::put('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/chuyen-khoa/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::delete('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/chuyen-khoa/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
    });

    Route::prefix('bac-si')->group(function () {
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/bac-si'));
        Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}"))->whereNumber('id');
        Route::get('{id}/lich-truc', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}/lich-truc"))->whereNumber('id');
        Route::get('{id}/kiem-tra-truc', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}/kiem-tra-truc"))->whereNumber('id');
        
        // Phân quyền lịch trực
        Route::post('{id}/lich-truc', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}/lich-truc"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);
        Route::put('lich-truc/{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/lich-truc/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);
        Route::delete('lich-truc/{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/lich-truc/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);

        // Phân quyền: Chỉ ADMIN mới được thêm / sửa / xóa bác sĩ
        Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/bac-si'))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::put('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::delete('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
        Route::get('{subpath}', fn(Request $r, $subpath) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$subpath}"));
        Route::post('{subpath}', fn(Request $r, $subpath) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/bac-si/{$subpath}"))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN']);
    });

    // Tuyến đường lịch trực chung
    Route::prefix('lich-truc')->group(function () {
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/lich-truc'));
        Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/lich-truc'))
            ->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);
        Route::put('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/lich-truc/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);
        Route::delete('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', "v1/lich-truc/{$id}"))
            ->whereNumber('id')->middleware(['xac_thuc_gateway', 'phan_quyen:ADMIN,BAC_SI']);
    });

    // 1.3 Thông tin tài khoản & Quản lý tài khoản
    Route::middleware(['xac_thuc_gateway'])->group(function () {
        Route::get('xac-thuc/thong-tin', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/thong-tin'));
        Route::post('xac-thuc/dang-xuat', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/dang-xuat'));
        Route::put('xac-thuc/doi-mat-khau', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/doi-mat-khau'));
        Route::put('xac-thuc/ho-so', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/ho-so'));
        Route::post('xac-thuc/avatar', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'xac_thuc', 'v1/xac-thuc/avatar'));

        // Quản trị toàn bộ danh sách tài khoản & trạng thái tài khoản: BẮT BUỘC ADMIN
        Route::prefix('tai-khoan')->middleware(['phan_quyen:ADMIN'])->group(function () {
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
            // Bệnh nhân xem hồ sơ cá nhân của mình
            Route::get('ho-so-cua-toi', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan/ho-so-cua-toi'));
            // Danh sách hồ sơ thành viên gia đình
            Route::get('ho-so-gia-dinh', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan/ho-so-gia-dinh'));
            // Thêm hồ sơ người thân gia đình
            Route::post('nguoi-than', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan/nguoi-than'));
            // Chi tiết hồ sơ bệnh nhân
            Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/benh-nhan/{$id}"))->whereNumber('id');
            // Xem danh sách toàn bộ bệnh nhân: Chỉ ADMIN và BAC_SI
            Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan'))
                ->middleware('phan_quyen:ADMIN,BAC_SI');
            // Tạo mới hồ sơ bệnh nhân
            Route::post('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/benh-nhan'));
        });

        Route::prefix('lich-hen')->group(function () {
            // Lấy lịch sử ca khám cá nhân của bệnh nhân
            Route::get('lich-su-cua-toi', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/lich-hen/lich-su-cua-toi'));
            // Đặt lịch khám: BENH_NHAN hoặc ADMIN
            Route::post('dat-lich', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/lich-hen/dat-lich'))
                ->middleware('phan_quyen:ADMIN,BENH_NHAN');
            // Bác sĩ kết luận chẩn đoán & kê đơn thuốc
            Route::put('{id}/ket-luan-kham', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/ket-luan-kham"))
                ->whereNumber('id')->middleware('phan_quyen:ADMIN,BAC_SI');
            // Tiếp nhận / Bắt đầu khám: BAC_SI hoặc ADMIN
            Route::put('{id}/bat-dau-kham', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/bat-dau-kham"))
                ->whereNumber('id')->middleware('phan_quyen:ADMIN,BAC_SI');
            // Hoàn thành ca khám: BAC_SI hoặc ADMIN
            Route::put('{id}/hoan-thanh', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/hoan-thanh"))
                ->whereNumber('id')->middleware('phan_quyen:ADMIN,BAC_SI');
            // Xác nhận ca khám: ADMIN hoặc BAC_SI
            Route::put('{id}/xac-nhan', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/xac-nhan"))
                ->whereNumber('id')->middleware('phan_quyen:ADMIN,BAC_SI');
            // Dời lịch hẹn: BENH_NHAN hoặc ADMIN
            Route::put('{id}/doi-lich', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/doi-lich"))
                ->whereNumber('id')->middleware('phan_quyen:ADMIN,BENH_NHAN');
            // Hủy lịch hẹn: Cả 3 vai trò
            Route::put('{id}/huy', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/huy"))->whereNumber('id');
            // Tải tệp đính kèm
            Route::post('{id}/tai-tep', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}/tai-tep"))->whereNumber('id');
            // Chi tiết lịch hẹn
            Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', "v1/lich-hen/{$id}"))->whereNumber('id');
            // Danh sách lịch hẹn
            Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'lich_hen', 'v1/lich-hen'));
        });
    });

    // ========================================================================
    // 3. DỊCH VỤ 3: DỊCH VỤ Y TẾ & CẬN LÂM SÀNG (Port: 8003)
    // ========================================================================
    Route::prefix('dich-vu')->group(function () {
        // Công khai tra cứu danh mục cận lâm sàng
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', 'v1/dich-vu'));

        Route::middleware(['xac_thuc_gateway'])->group(function () {
            // Bác sĩ & Admin chỉ định cận lâm sàng (Bệnh nhân bị chặn)
            Route::post('chi-dinh', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'y_te', 'v1/dich-vu/chi-dinh'))
                ->middleware('phan_quyen:ADMIN,BAC_SI');

            // Cập nhật kết quả xét nghiệm / hình ảnh (Bệnh nhân bị chặn)
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

        // Xem danh sách toàn bộ hóa đơn: Bắt buộc quyền ADMIN
        Route::get('/', fn(Request $r) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', 'v1/hoa-don'))
            ->middleware('phan_quyen:ADMIN');

        // Thanh toán hóa đơn (Bệnh nhân hoặc Admin)
        Route::put('{id}/thanh-toan', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', "v1/hoa-don/{$id}/thanh-toan"));

        // Chi tiết hóa đơn
        Route::get('{id}', fn(Request $r, $id) => app(CongGiaoTiepController::class)->chuyenTiep($r, 'hoa_don', "v1/hoa-don/{$id}"))->whereNumber('id');
    });
};

// Đăng ký cả chuẩn /api/v1/... và /api/... để tương thích tối đa
Route::prefix('v1')->group($dangKyDinhTuyenGateway);
$dangKyDinhTuyenGateway();
