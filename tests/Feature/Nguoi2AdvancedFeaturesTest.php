<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use App\Modules\BacSi\Models\BacSi;
use App\Modules\BenhNhan\Models\BenhNhan;
use App\Modules\LichHen\Models\LichHen;
use App\Notifications\ThongBaoLichHenNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Nguoi2AdvancedFeaturesTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_mo_rong_ho_so_benh_an_dien_tu()
    {
        $user = TaiKhoan::where('ten_dang_nhap', 'benhnhan')->first();
        $token = $user->createToken('test')->plainTextToken;

        // 1. Kiểm tra API lấy hồ sơ
        $res = $this->withHeader('Authorization', "Bearer $token")
                    ->getJson('/api/v1/benh-nhan/ho-so-cua-toi');

        $res->assertStatus(200);
        $res->assertJsonFragment([
            'tien_su_benh' => 'Viêm xoang mãn tính, Huyết áp bình thường',
            'tien_su_di_ung' => 'Dị ứng kháng sinh Penicillin, Dị ứng phấn hoa',
            'nguoi_lien_he_khan_cap' => 'Trần Thị Mai (Vợ)',
            'sdt_khan_cap' => '0912345678',
        ]);
    }

    public function test_kiem_tra_trung_lich_nang_cao_chan_ngay_qua_khu()
    {
        $bs = BacSi::first();
        $user = TaiKhoan::where('ten_dang_nhap', 'benhnhan')->first();
        $token = $user->createToken('test')->plainTextToken;

        // Thử đặt ngày quá khứ
        $res = $this->withHeader('Authorization', "Bearer $token")
                    ->postJson('/api/v1/lich-hen/dat-lich', [
                        'bac_si_id' => $bs->id,
                        'ngay_kham' => '2020-01-01',
                        'gio_kham' => '09:00',
                        'ho_ten' => 'Nguyễn Test',
                        'so_dien_thoai' => '0988776655',
                    ]);

        $res->assertStatus(422);
    }

    public function test_kiem_tra_trung_lich_nang_cao_khoang_cach_30_phut()
    {
        $bs = BacSi::first();
        $user = TaiKhoan::where('ten_dang_nhap', 'benhnhan')->first();
        $token = $user->createToken('test')->plainTextToken;

        $ngayKham = date('Y-m-d', strtotime('+3 days'));

        // Đặt ca 1 lúc 09:00 -> Thành công
        $res1 = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/api/v1/lich-hen/dat-lich', [
                         'bac_si_id' => $bs->id,
                         'ngay_kham' => $ngayKham,
                         'gio_kham' => '09:00',
                         'ho_ten' => 'Bệnh Nhân 1',
                         'so_dien_thoai' => '0988111222',
                     ]);
        $res1->assertStatus(201);

        // Đặt ca 2 lúc 09:15 cùng bác sĩ -> Phải bị chặn vì cách nhau < 30 phút
        $res2 = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/api/v1/lich-hen/dat-lich', [
                         'bac_si_id' => $bs->id,
                         'ngay_kham' => $ngayKham,
                         'gio_kham' => '09:15',
                         'ho_ten' => 'Bệnh Nhân 2',
                         'so_dien_thoai' => '0988333444',
                     ]);
        $res2->assertStatus(422);

        // Đặt ca 3 lúc 09:30 cùng bác sĩ -> Thành công vì cách đúng 30 phút
        $res3 = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/api/v1/lich-hen/dat-lich', [
                         'bac_si_id' => $bs->id,
                         'ngay_kham' => $ngayKham,
                         'gio_kham' => '09:30',
                         'ho_ten' => 'Bệnh Nhân 3',
                         'so_dien_thoai' => '0988555666',
                     ]);
        $res3->assertStatus(201);
    }

    public function test_bo_loc_lich_hen_da_nang()
    {
        $admin = TaiKhoan::where('ten_dang_nhap', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        // Lọc theo trạng thái CHO_XAC_NHAN
        $res = $this->withHeader('Authorization', "Bearer $token")
                    ->getJson('/api/v1/lich-hen?trang_thai=CHO_XAC_NHAN');

        $res->assertStatus(200);
        $duLieu = $res->json('du_lieu');
        foreach ($duLieu as $item) {
            $this->assertEquals('CHO_XAC_NHAN', $item['trang_thai']);
        }
    }

    public function test_sinh_thong_bao_notification_va_sms()
    {
        $lichHen = LichHen::with(['benhNhan', 'bacSi.chuyenKhoa'])->first();
        if ($lichHen) {
            $notif = new ThongBaoLichHenNotification($lichHen, 'DAT_LICH');
            $data = $notif->toArray($lichHen);

            $this->assertEquals($lichHen->ma_lich_hen, $data['ma_lich_hen']);
            $this->assertNotEmpty($data['noi_dung_sms']);
            $this->assertStringContainsString($lichHen->ma_lich_hen, $data['noi_dung_sms']);
        }
    }
}
