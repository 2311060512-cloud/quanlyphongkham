<?php

namespace Tests\Feature;

use App\Models\BenhNhan;
use App\Models\LichHen;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Nguoi2MicroserviceTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * TEST 1: Bệnh nhân bắt buộc phải đăng nhập trước khi đặt lịch khám (Auth Gate)
     */
    public function test_benh_nhan_bat_buoc_dang_nhap_khi_dat_lich(): void
    {
        // Gửi request đặt lịch mà không truyền Header X-User-Id / X-Nguoi-Dung-Id
        $response = $this->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => Carbon::tomorrow()->toDateString(),
            'gio_bat_dau' => '08:00:00',
            'ho_ten' => 'Khách Vãng Lai',
            'so_dien_thoai' => '0909999888',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'thanh_cong' => false,
                     'ma_loi' => 'CHUA_DANG_NHAP',
                 ]);
    }

    /**
     * TEST 2: Mở rộng hồ sơ bệnh án điện tử (nhóm máu, dị ứng, bệnh nền, liên hệ khẩn cấp)
     */
    public function test_mo_rong_ho_so_benh_an_dien_tu(): void
    {
        $payload = [
            'ho_ten' => 'Vũ Thị Minh Hạnh',
            'so_dien_thoai' => '0912345678',
            'so_cccd' => '079195009999',
            'ngay_sinh' => '1995-08-20',
            'gioi_tinh' => 'NU',
            'dia_chi' => 'Quận Tân Bình, TP. HCM',
            'nhom_mau' => 'AB',
            'tien_su_di_ung' => 'Dị ứng kháng sinh Penicillin và tôm biển',
            'tien_su_benh' => 'Tăng huyết áp nhẹ, thiếu máu cục bộ',
            'nguoi_lien_he_khan_cap' => 'Vũ Văn Bình (Bố ruột)',
            'sdt_khan_cap' => '0912345999',
            'tai_khoan_id' => 10,
        ];

        $response = $this->withHeaders([
            'X-User-Id' => '10',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/benh-nhan', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'thanh_cong' => true,
                     'du_lieu' => [
                         'ho_ten' => 'Vũ Thị Minh Hạnh',
                         'nhom_mau' => 'AB',
                         'so_cccd' => '079195009999',
                         'tien_su_di_ung' => 'Dị ứng kháng sinh Penicillin và tôm biển',
                     ]
                 ]);

        $this->assertDatabaseHas('benh_nhan', [
            'so_cccd' => '079195009999',
            'nhom_mau' => 'AB',
            'sdt_khan_cap' => '0912345999',
        ]);
    }

    /**
     * TEST 3: Thuật toán chống trùng lịch khám 30 phút giao thoa (Trả về HTTP 409 Conflict)
     */
    public function test_thuat_toan_chong_trung_lich_kham_30_phut(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(10, 500))->toDateString();

        // 1. Đặt lịch ca đầu tiên: 09:00 - 09:30
        $caDau = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '09:00:00',
            'gio_ket_thuc' => '09:30:00',
            'ho_ten' => 'Bệnh Nhân Ca 1',
            'so_dien_thoai' => '0933111222',
            'ly_do_kham' => 'Khám tổng quát',
        ]);

        $caDau->assertStatus(201)
              ->assertJson([
                  'thanh_cong' => true,
              ]);

        // 2. Đặt lịch ca thứ hai giao thoa: 09:15 - 09:45 cùng bác sĩ 1
        $caTrung = $this->withHeaders([
            'X-User-Id' => '5',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '09:15:00',
            'gio_ket_thuc' => '09:45:00',
            'ho_ten' => 'Bệnh Nhân Ca 2',
            'so_dien_thoai' => '0933333444',
            'ly_do_kham' => 'Thử nghiệm trùng lịch',
        ]);

        $caTrung->assertStatus(409)
                ->assertJson([
                    'thanh_cong' => false,
                    'ma_loi' => 'TRUNG_LICH_KHAM',
                ]);
    }

    /**
     * TEST 4: Chặn đặt lịch khám ngày trong quá khứ (Trả về HTTP 422)
     */
    public function test_chan_dat_lich_ngay_trong_qua_khu(): void
    {
        $ngayQuaKhu = Carbon::yesterday()->toDateString();

        $response = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayQuaKhu,
            'gio_bat_dau' => '10:00:00',
            'ho_ten' => 'Bệnh Nhân Đặt Ngày Cũ',
            'so_dien_thoai' => '0911222333',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'thanh_cong' => false,
                     'ma_loi' => 'NGAY_KHAM_KHONG_HOP_LE',
                 ]);
    }

    /**
     * TEST 5: Phân quyền bác sĩ điều phối ca khám và bộ lọc đa năng
     */
    public function test_phan_quyen_bac_si_va_bo_loc_da_nang(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(501, 1000))->toDateString();

        // 1. Tạo lịch hẹn cho Bác sĩ ID 1
        $datLich = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '14:00:00',
            'ho_ten' => 'Nguyễn Minh Quân',
            'so_dien_thoai' => '0908888777',
        ]);
        $lichHenId = $datLich->json('du_lieu.id');

        // 2. Bác sĩ ID 2 cố tình duyệt ca khám của Bác sĩ ID 1 -> Phải bị từ chối 403 Forbidden
        $duyetThatBai = $this->withHeaders([
            'X-User-Id' => '2',
            'X-User-Role' => 'BAC_SI',
        ])->putJson("/api/v1/lich-hen/{$lichHenId}/xac-nhan");

        $duyetThatBai->assertStatus(403)
                     ->assertJson([
                         'thanh_cong' => false,
                         'ma_loi' => 'KHONG_CO_QUYEN',
                     ]);

        // 3. Đúng Bác sĩ ID 1 duyệt ca khám -> Thành công 200
        $duyetThanhCong = $this->withHeaders([
            'X-User-Id' => '1',
            'X-User-Role' => 'BAC_SI',
        ])->putJson("/api/v1/lich-hen/{$lichHenId}/xac-nhan");

        $duyetThanhCong->assertStatus(200)
                       ->assertJson([
                           'thanh_cong' => true,
                           'du_lieu' => [
                               'trang_thai' => 'DA_XAC_NHAN'
                           ]
                       ]);

        // 4. Kiểm tra bộ lọc đa năng theo trạng thái DA_XAC_NHAN
        $boLoc = $this->getJson('/api/v1/lich-hen?trang_thai=DA_XAC_NHAN');
        $boLoc->assertStatus(200)
              ->assertJson([
                  'thanh_cong' => true,
              ]);

        $danhSach = $boLoc->json('du_lieu');
        $this->assertNotEmpty($danhSach);
        foreach ($danhSach as $item) {
            $this->assertEquals('DA_XAC_NHAN', $item['trang_thai']);
        }
    }

    /**
     * TEST 6: Nghiệp vụ Dời Lịch (Reschedule) & Chống trùng lịch khi dời
     */
    public function test_doi_lich_kham_thanh_cong_va_chong_trung_lich_khi_doi(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(1001, 1500))->toDateString();

        // 1. Tạo lịch hẹn gốc: 14:00 - 14:30
        $datLich1 = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '14:00:00',
            'ho_ten' => 'Bệnh Nhân Dời Lịch',
            'so_dien_thoai' => '0901234567',
        ]);
        $lichHen1Id = $datLich1->json('du_lieu.id');

        // 2. Bệnh nhân thực hiện dời lịch sang 15:00:00 -> Thành công 200
        $doiLich = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->putJson("/api/v1/lich-hen/{$lichHen1Id}/doi-lich", [
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '15:00:00',
            'ly_do_doi_lich' => 'Kẹt xe, xin dời sau 1 tiếng',
        ]);

        $doiLich->assertStatus(200)
                ->assertJson([
                    'thanh_cong' => true,
                    'du_lieu' => [
                        'gio_bat_dau' => '15:00:00',
                        'so_lan_doi_lich' => 1,
                        'ly_do_doi_lich' => 'Kẹt xe, xin dời sau 1 tiếng',
                    ]
                ]);

        // 3. Tạo một lịch hẹn khác của bệnh nhân thứ hai: 16:00 - 16:30
        $this->withHeaders([
            'X-User-Id' => '5',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '16:00:00',
            'ho_ten' => 'Bệnh Nhân Thứ Hai',
            'so_dien_thoai' => '0909999111',
        ]);

        // 4. Bệnh nhân 1 cố tình dời vào 16:00:00 (đã có người đặt) -> Phải bị chặn 409 Conflict
        $doiLichTrung = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->putJson("/api/v1/lich-hen/{$lichHen1Id}/doi-lich", [
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '16:00:00',
        ]);

        $doiLichTrung->assertStatus(409)
                     ->assertJson([
                         'thanh_cong' => false,
                         'ma_loi' => 'TRUNG_LICH_KHAM',
                     ]);
    }

    /**
     * TEST 7: Chặn Bệnh Nhân Hủy Lịch Sát Giờ (< 2 tiếng / 120 phút trước giờ hẹn)
     */
    public function test_chan_huy_lich_kham_sat_gio_duoi_2_tieng(): void
    {
        // Tạo lịch hẹn chỉ còn 30 phút nữa là diễn ra
        $thoiGianBatDau = Carbon::now()->addMinutes(30);
        $ngayKham = $thoiGianBatDau->toDateString();
        $gioBatDau = $thoiGianBatDau->toTimeString();

        $lichHen = LichHen::create([
            'ma_lich_hen' => 'LK_TEST_SAT_GIO_' . uniqid(),
            'benh_nhan_id' => 1,
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => Carbon::parse($gioBatDau)->addMinutes(30)->toTimeString(),
            'ly_do_kham' => 'Khám đau dạ dày cấp',
            'trang_thai' => 'DA_XAC_NHAN',
        ]);

        // 1. Bệnh nhân tự hủy ca khám sát giờ (30 phút < 120 phút) -> Phải bị chặn 422
        $huyThatBai = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->putJson("/api/v1/lich-hen/{$lichHen->id}/huy", [
            'ly_do_huy' => 'Tôi bận đột xuất',
        ]);

        $huyThatBai->assertStatus(422)
                   ->assertJson([
                       'thanh_cong' => false,
                       'ma_loi' => 'KHONG_THE_HUY_SAT_GIO',
                   ]);

        // 2. Quản trị viên (ADMIN) can thiệp khẩn cấp hủy ca -> Thành công 200
        $huyBoiAdmin = $this->withHeaders([
            'X-User-Id' => '1',
            'X-User-Role' => 'ADMIN',
        ])->putJson("/api/v1/lich-hen/{$lichHen->id}/huy", [
            'ly_do_huy' => 'Admin hủy do trường hợp y tế bất khả kháng',
        ]);

        $huyBoiAdmin->assertStatus(200)
                    ->assertJson([
                        'thanh_cong' => true,
                        'du_lieu' => [
                            'trang_thai' => 'DA_HUY',
                        ]
                    ]);
    }

    /**
     * TEST 8: Đính kèm tệp/ảnh y tế (đơn thuốc, xét nghiệm) khi đặt lịch và tải bổ sung
     */
    public function test_dinh_kem_tep_va_anh_y_te_khi_dat_lich(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(1501, 2000))->toDateString();

        $payload = [
            'bac_si_id' => 2,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '10:00:00',
            'ho_ten' => 'Bệnh Nhân Có Hồ Sơ Ảnh',
            'so_dien_thoai' => '0988776655',
            'ly_do_kham' => 'Xem kết quả chụp X-Quang phổi và đơn thuốc cũ',
            'tep_dinh_kem' => [
                '/uploads/tep_y_te/x_quang_phoi_bn.jpg',
                '/uploads/tep_y_te/don_thuoc_bv_cho_ray.jpg'
            ]
        ];

        // 1. Đặt lịch có đính kèm ảnh
        $response = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'thanh_cong' => true,
                     'du_lieu' => [
                         'tep_dinh_kem' => [
                             '/uploads/tep_y_te/x_quang_phoi_bn.jpg',
                             '/uploads/tep_y_te/don_thuoc_bv_cho_ray.jpg'
                         ]
                     ]
                 ]);

        $lichHenId = $response->json('du_lieu.id');

        // 2. Tải thêm tệp y tế bổ sung vào lịch hẹn đã tạo
        $taiThem = $this->postJson("/api/v1/lich-hen/{$lichHenId}/tai-tep", [
            'tep_dinh_kem' => ['/uploads/tep_y_te/xet_nghiem_mau_moi.pdf']
        ]);

        $taiThem->assertStatus(200)
                ->assertJson([
                    'thanh_cong' => true,
                ]);

        $danhSachTep = $taiThem->json('du_lieu.tep_dinh_kem');
        $this->assertCount(3, $danhSachTep);
        $this->assertContains('/uploads/tep_y_te/xet_nghiem_mau_moi.pdf', $danhSachTep);
    }

    /**
     * TEST 9: Tra cứu khung giờ khám khả dụng theo thời gian thực (BookingCare & Zocdoc style)
     */
    public function test_tra_cuu_slots_kha_dung_theo_thoi_gian_thuc(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(2001, 2500))->toDateString();
        $bacSiId = 1;

        // 1. Kiểm tra ban đầu chưa có ca khám nào: tất cả 13 ca đều khả dụng
        $res1 = $this->getJson("/api/v1/lich-hen/slots-kha-dung?bac_si_id={$bacSiId}&ngay_kham={$ngayKham}");
        $res1->assertStatus(200)
             ->assertJson([
                 'thanh_cong' => true,
                 'du_lieu' => [
                     'tong_so_slot' => 13,
                     'so_slot_kha_dung' => 13,
                 ]
             ]);

        // 2. Đặt 1 ca khám lúc 08:00 - 08:30
        $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => $bacSiId,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '08:00:00',
            'ho_ten' => 'Bệnh Nhân Kiểm Tra Slot',
            'so_dien_thoai' => '0912345678',
            'ly_do_kham' => 'Thử nghiệm slot',
        ])->assertStatus(201);

        // 3. Tra cứu lại: Slot 08:00 phải bị đánh dấu DA_DAT và không khả dụng
        $res2 = $this->getJson("/api/v1/lich-hen/slots-kha-dung?bac_si_id={$bacSiId}&ngay_kham={$ngayKham}");
        $res2->assertStatus(200)
             ->assertJson([
                 'thanh_cong' => true,
                 'du_lieu' => [
                     'so_slot_kha_dung' => 12,
                 ]
             ]);

        $slots = $res2->json('du_lieu.slots');
        $slot8h = collect($slots)->firstWhere('bat_dau', '08:00:00');
        $this->assertNotNull($slot8h);
        $this->assertFalse($slot8h['kha_dung']);
        $this->assertEquals('DA_DAT', $slot8h['trang_thai']);
    }

    /**
     * TEST 10: Quản lý hồ sơ gia đình và đặt khám cho người thân (Con cái, Cha mẹ...)
     */
    public function test_ho_so_gia_dinh_va_dat_lich_cho_nguoi_than(): void
    {
        $userId = '88';

        // 1. Tạo hồ sơ người thân (con gái 5 tuổi)
        $resTao = $this->withHeaders([
            'X-User-Id' => $userId,
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/benh-nhan/nguoi-than', [
            'ho_ten' => 'Bé Nguyễn Mai Anh (Con gái)',
            'quan_he_chu_tai_khoan' => 'CON',
            'ngay_sinh' => '2021-06-15',
            'gioi_tinh' => 'NU',
            'nhom_mau' => 'O',
        ]);

        $resTao->assertStatus(201)
               ->assertJson([
                   'thanh_cong' => true,
                   'du_lieu' => [
                       'ho_ten' => 'Bé Nguyễn Mai Anh (Con gái)',
                       'quan_he_chu_tai_khoan' => 'CON',
                   ]
               ]);

        $conGaiId = $resTao->json('du_lieu.id');

        // 2. Lấy danh sách hồ sơ gia đình của tài khoản này
        $resGiaDinh = $this->withHeaders([
            'X-User-Id' => $userId,
            'X-User-Role' => 'BENH_NHAN',
        ])->getJson('/api/v1/benh-nhan/ho-so-gia-dinh');

        $resGiaDinh->assertStatus(200)
                   ->assertJson([
                       'thanh_cong' => true,
                   ]);

        $danhSach = $resGiaDinh->json('du_lieu');
        $this->assertTrue(collect($danhSach)->contains('id', $conGaiId));

        // 3. Đặt lịch khám cho hồ sơ người thân vừa tạo
        $ngayKham = Carbon::tomorrow()->addDays(rand(2501, 3000))->toDateString();
        $resDat = $this->withHeaders([
            'X-User-Id' => $userId,
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'benh_nhan_id' => $conGaiId,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '09:00:00',
            'ly_do_kham' => 'Khám nhi: Sốt nhẹ và ho đêm',
        ]);

        $resDat->assertStatus(201)
               ->assertJson([
                   'thanh_cong' => true,
                   'du_lieu' => [
                       'benh_nhan_id' => $conGaiId,
                   ]
               ]);
    }

    /**
     * TEST 11: Bác sĩ kê toa thuốc điện tử và hoàn thành kết luận khám
     */
    public function test_bac_si_ke_toa_thuoc_va_ket_luan_kham(): void
    {
        $ngayKham = Carbon::tomorrow()->addDays(rand(3001, 3500))->toDateString();

        // 1. Tạo 1 ca khám
        $resDat = $this->withHeaders([
            'X-User-Id' => '4',
            'X-User-Role' => 'BENH_NHAN',
        ])->postJson('/api/v1/lich-hen/dat-lich', [
            'bac_si_id' => 1,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => '14:00:00',
            'ho_ten' => 'Bệnh Nhân Kê Đơn Thuốc',
            'so_dien_thoai' => '0933445566',
            'ly_do_kham' => 'Viêm amidan hốc mủ',
        ]);

        $resDat->assertStatus(201);
        $lichHenId = $resDat->json('du_lieu.id');

        // 2. Bác sĩ cập nhật kết luận và toa thuốc điện tử, chuyển HOAN_THANH
        $toaThuoc = [
            [
                'ten_thuoc' => 'Augmentin 1g (Amoxicillin/Clavulanate)',
                'ham_luong' => '1000mg',
                'so_luong' => '14 viên',
                'cach_dung' => 'Uống 1 viên sau ăn sáng, 1 viên sau ăn tối (cách 12 giờ)'
            ],
            [
                'ten_thuoc' => 'Paracetamol 500mg',
                'ham_luong' => '500mg',
                'so_luong' => '10 viên',
                'cach_dung' => 'Uống 1 viên khi sốt trên 38.5 độ C'
            ]
        ];

        $resKetLuan = $this->withHeaders([
            'X-User-Id' => '1',
            'X-User-Role' => 'BAC_SI',
        ])->putJson("/api/v1/lich-hen/{$lichHenId}/ket-luan-kham", [
            'chuan_doan' => 'Viêm họng hạt cấp tính kèm sốt nhẹ',
            'loi_khuyen' => 'Súc họng nước muối sinh lý ấm, kiêng nước đá, tái khám sau 5 ngày',
            'toa_thuoc' => $toaThuoc,
            'ngay_tai_kham' => Carbon::parse($ngayKham)->addDays(5)->toDateString(),
            'chuyen_hoan_thanh' => true,
        ]);

        $resKetLuan->assertStatus(200)
                   ->assertJson([
                       'thanh_cong' => true,
                       'du_lieu' => [
                           'id' => $lichHenId,
                           'trang_thai' => 'HOAN_THANH',
                           'chuan_doan' => 'Viêm họng hạt cấp tính kèm sốt nhẹ',
                       ]
                   ]);

        $duLieuToa = $resKetLuan->json('du_lieu.toa_thuoc');
        $this->assertCount(2, $duLieuToa);
        $this->assertEquals('Augmentin 1g (Amoxicillin/Clavulanate)', $duLieuToa[0]['ten_thuoc']);
    }


    /**
     * TEST 12: Cập nhật thông tin hồ sơ bệnh nhân (EHR Baseline & Dị ứng)
     */
    public function test_cap_nhat_ho_so_benh_nhan_ehr(): void
    {
        // 1. Tạo bệnh nhân
        $resTao = $this->postJson('/api/v1/benh-nhan', [
            'ho_ten' => 'Bệnh Nhân Test EHR',
            'so_dien_thoai' => '0988776655',
            'gioi_tinh' => 'NAM',
            'nhom_mau' => 'O',
        ]);
        $resTao->assertStatus(201);
        $bnId = $resTao->json('du_lieu.id');

        // 2. Cập nhật thông tin dị ứng, bệnh nền, số CCCD kèm kiểm tra whitelist bảo vệ (Fix #4)
        $resCapNhat = $this->putJson("/api/v1/benh-nhan/{$bnId}", [
            'ho_ten' => 'Bệnh Nhân Test EHR (Đã Cập Nhật)',
            'nhom_mau' => 'A',
            'tien_su_di_ung' => 'Dị ứng Penicillin và hải sản',
            'tien_su_benh' => 'Tăng huyết áp vô căn',
            'so_cccd' => '079201000123',
            'ma_benh_nhan' => 'HACKED_MA_BN', // Should be filtered out by whitelist
        ]);

        $resCapNhat->assertStatus(200)
                   ->assertJson([
                       'thanh_cong' => true,
                       'du_lieu' => [
                           'id' => $bnId,
                           'nhom_mau' => 'A',
                           'tien_su_di_ung' => 'Dị ứng Penicillin và hải sản',
                           'tien_su_benh' => 'Tăng huyết áp vô căn',
                       ]
                   ]);

        // 3. Kiểm tra lại qua chi tiết bệnh nhân
        $resDetail = $this->getJson("/api/v1/benh-nhan/{$bnId}");
        $resDetail->assertStatus(200);
        $this->assertEquals('Dị ứng Penicillin và hải sản', $resDetail->json('du_lieu.tien_su_di_ung'));
        $this->assertNotEquals('HACKED_MA_BN', $resDetail->json('du_lieu.ma_benh_nhan'));
    }

}
