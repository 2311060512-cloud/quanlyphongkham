<?php

use App\Http\Controllers\BenhNhanController;
use App\Http\Controllers\LichHenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service 02: Dịch Vụ Bệnh Nhân & Lịch Hẹn (Port: 8002)
|--------------------------------------------------------------------------
*/

$dinhTuyenDichVu = function () {
    Route::prefix('benh-nhan')->group(function () {
        Route::get('ho-so-cua-toi', [BenhNhanController::class, 'hoSoCuaToi']);
        Route::get('ho-so-gia-dinh', [BenhNhanController::class, 'hoSoGiaDinh']);
        Route::post('nguoi-than', [BenhNhanController::class, 'taoHoSoNguoiThan']);
        Route::get('/', [BenhNhanController::class, 'danhSach']);
        Route::post('/', [BenhNhanController::class, 'taoMoi']);
        Route::get('{id}', [BenhNhanController::class, 'chiTiet'])->whereNumber('id');
        Route::put('{id}', [BenhNhanController::class, 'capNhat'])->whereNumber('id');
    });

    Route::prefix('lich-hen')->group(function () {
        Route::get('lich-su-cua-toi', [LichHenController::class, 'lichSuCuaToi']);
        Route::get('/', [LichHenController::class, 'danhSach']);
        Route::get('{id}', [LichHenController::class, 'chiTiet'])->whereNumber('id');
        Route::get('{id}/xem-truoc-thong-bao', [LichHenController::class, 'xemTruocThongBao'])->whereNumber('id');
        Route::get('slots-kha-dung', [LichHenController::class, 'slotsKhaDung']);
        Route::post('dat-lich', [LichHenController::class, 'datLich']);
        Route::put('{id}/ket-luan-kham', [LichHenController::class, 'ketLuanKham'])->whereNumber('id');
        Route::put('{id}/xac-nhan', [LichHenController::class, 'xacNhan'])->whereNumber('id');
        Route::put('{id}/bat-dau-kham', [LichHenController::class, 'batDauKham'])->whereNumber('id');
        Route::put('{id}/hoan-thanh', [LichHenController::class, 'hoanThanh'])->whereNumber('id');
        Route::put('{id}/doi-lich', [LichHenController::class, 'doiLich'])->whereNumber('id');
        Route::post('{id}/tai-tep', [LichHenController::class, 'taiTepDinhKem'])->whereNumber('id');
        Route::put('{id}/huy', [LichHenController::class, 'huy'])->whereNumber('id');
    });
};

$dinhTuyenDichVu();
Route::prefix('v1')->group($dinhTuyenDichVu);
