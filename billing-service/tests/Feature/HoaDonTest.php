<?php

namespace Tests\Feature;

use App\Models\HoaDon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HoaDonTest extends TestCase
{
    use DatabaseTransactions;

    protected string $urlAuth;
    protected string $urlAppt;
    protected string $urlClin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlAuth = config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001');
        $this->urlAppt = config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002');
        $this->urlClin = config('services.dich_vu_y_te', 'http://127.0.0.1:8003');
    }

    /**
     * CASE 1: Tạo hóa đơn thành công khi cả 3 service trả về 200 OK hợp lệ
     */
    public function test_tao_hoa_don_thanh_cong_khi_ca_3_service_hoat_dong_tot(): void
    {
        $lichHenId = 8801;

        Http::fake([
            "{$this->urlAppt}/api/lich-hen/{$lichHenId}*" => Http::response([
                'thanh_cong' => true,
                'du_lieu'    => [
                    'id'           => $lichHenId,
                    'benh_nhan_id' => 5,
                    'bac_si_id'    => 2,
                ],
            ], 200),

            "{$this->urlAuth}/api/bac-si/2*" => Http::response([
                'thanh_cong' => true,
                'du_lieu'    => [
                    'id'        => 2,
                    'tai_khoan' => ['ho_ten' => 'ThS.BS Trần Thị Bình'],
                    'gia_kham'  => 250000.00,
                ],
            ], 200),

            "{$this->urlClin}/*" => Http::response([
                'thanh_cong' => true,
                'du_lieu'    => [
                    [
                        'ten_dich_vu' => 'Siêu âm Doppler tim',
                        'so_luong'    => 1,
                        'don_gia'     => 350000.00,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson("/api/v1/hoa-don/tao-tu-dong", [
            'lich_hen_id' => $lichHenId,
            'giam_gia'    => 50000,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'thanh_cong' => true,
                     'du_lieu'    => [
                         'lich_hen_id'  => $lichHenId,
                         'benh_nhan_id' => 5,
                         'tien_kham'    => 250000,
                         'tien_dich_vu' => 350000,
                         'tong_tien'    => 600000,
                         'giam_gia'     => 50000,
                         'thuc_thu'     => 550000,
                         'trang_thai'   => 'CHUA_THANH_TOAN',
                     ],
                 ]);

        $this->assertDatabaseHas('hoa_don', [
            'lich_hen_id' => $lichHenId,
            'thuc_thu'    => 550000.00,
        ]);
    }

    /**
     * CASE 2: Tạo hóa đơn khi clinical-service bị lỗi 500 (Cơ chế chịu lỗi Fallback)
     */
    public function test_tao_hoa_don_khi_clinical_service_loi_500(): void
    {
        $lichHenId = 8802;

        Http::fake([
            "{$this->urlAppt}/api/lich-hen/{$lichHenId}*" => Http::response([
                'thanh_cong' => true,
                'du_lieu'    => [
                    'id'           => $lichHenId,
                    'benh_nhan_id' => 3,
                    'bac_si_id'    => 1,
                ],
            ], 200),

            "{$this->urlAuth}/api/bac-si/1*" => Http::response([
                'thanh_cong' => true,
                'du_lieu'    => [
                    'tai_khoan' => ['ho_ten' => 'BS. Nguyễn Văn An'],
                    'gia_kham'  => 200000.00,
                ],
            ], 200),

            "{$this->urlClin}/*" => Http::response([
                'thanh_cong' => false,
                'thong_diep' => 'Internal Server Error',
            ], 500),
        ]);

        $response = $this->postJson("/api/v1/hoa-don/tao-tu-dong", [
            'lich_hen_id' => $lichHenId,
            'giam_gia'    => 0,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'thanh_cong' => true,
                     'du_lieu'    => [
                         'lich_hen_id'  => $lichHenId,
                         'tien_kham'    => 200000,
                         'tien_dich_vu' => 0,
                         'tong_tien'    => 200000,
                         'thuc_thu'     => 200000,
                     ],
                 ]);

        $this->assertArrayHasKey('canh_bao', $response->json('du_lieu'));

        $this->assertDatabaseHas('hoa_don', [
            'lich_hen_id'  => $lichHenId,
            'tien_dich_vu' => 0.00,
        ]);
    }

    /**
     * CASE 3: Thanh toán 2 lần trên cùng 1 hóa đơn -> Lần 2 phải trả về HTTP 409 Conflict
     */
    public function test_thanh_toan_hai_lan_tra_ve_409_conflict(): void
    {
        $hoaDon = HoaDon::create([
            'ma_hoa_don'             => 'HD-TEST-CONFLICT-409',
            'lich_hen_id'            => 9999,
            'benh_nhan_id'           => 1,
            'tien_kham'              => 200000.00,
            'tien_dich_vu'           => 0.00,
            'tong_tien'              => 200000.00,
            'giam_gia'               => 0.00,
            'thuc_thu'               => 200000.00,
            'phuong_thuc_thanh_toan' => 'TIEN_MAT',
            'trang_thai'             => 'CHUA_THANH_TOAN',
        ]);

        $lan1 = $this->putJson("/api/v1/hoa-don/{$hoaDon->id}/thanh-toan", [
            'phuong_thuc_thanh_toan' => 'VNPAY',
            'ghi_chu'                => 'Thanh toán lần đầu',
        ]);

        $lan1->assertStatus(200)
             ->assertJson([
                 'thanh_cong' => true,
             ]);

        $lan2 = $this->putJson("/api/v1/hoa-don/{$hoaDon->id}/thanh-toan", [
            'phuong_thuc_thanh_toan' => 'TIEN_MAT',
        ]);

        $lan2->assertStatus(409)
             ->assertJson([
                 'thanh_cong' => false,
             ]);
    }
}
