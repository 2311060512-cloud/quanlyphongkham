<?php

namespace Database\Seeders;

use App\Models\BacSi;
use App\Models\ChuyenKhoa;
use App\Models\TaiKhoan;
use App\Models\VaiTro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tao 3 Vai Tro co ban
        $vtAdmin = VaiTro::updateOrCreate(['ma_vai_tro' => 'ADMIN'], [
            'ten_vai_tro' => 'Quản trị viên hệ thống',
            'mo_ta' => 'Toàn quyền quản lý hệ thống phòng khám'
        ]);

        $vtBacSi = VaiTro::updateOrCreate(['ma_vai_tro' => 'BAC_SI'], [
            'ten_vai_tro' => 'Bác sĩ khám bệnh',
            'mo_ta' => 'Bác sĩ chuyên khoa, khám bệnh và chỉ định dịch vụ y tế'
        ]);

        $vtBenhNhan = VaiTro::updateOrCreate(['ma_vai_tro' => 'BENH_NHAN'], [
            'ten_vai_tro' => 'Bệnh nhân',
            'mo_ta' => 'Người dùng đăng ký khám bệnh và theo dõi hồ sơ'
        ]);

        // 2. Tao 4 Chuyen Khoa yeu cau
        $ckNoi = ChuyenKhoa::updateOrCreate(['ma_khoa' => 'NOI'], [
            'ten_khoa' => 'Khoa Nội Tổng Quát',
            'mo_ta' => 'Khám và điều trị các bệnh lý nội khoa tổng quát người lớn',
            'trang_thai' => 'HOAT_DONG',
        ]);

        $ckNhi = ChuyenKhoa::updateOrCreate(['ma_khoa' => 'NHI'], [
            'ten_khoa' => 'Khoa Nhi',
            'mo_ta' => 'Khám sức khỏe, theo dõi phát triển và điều trị cho trẻ em',
            'trang_thai' => 'HOAT_DONG',
        ]);

        $ckRHM = ChuyenKhoa::updateOrCreate(['ma_khoa' => 'RHM'], [
            'ten_khoa' => 'Răng Hàm Mặt',
            'mo_ta' => 'Khám răng, nhổ răng, phục hình và điều trị nha chu',
            'trang_thai' => 'HOAT_DONG',
        ]);

        $ckMat = ChuyenKhoa::updateOrCreate(['ma_khoa' => 'MAT'], [
            'ten_khoa' => 'Khoa Mắt',
            'mo_ta' => 'Đo thị lực, khám và điều trị các tật khúc xạ và bệnh về mắt',
            'trang_thai' => 'HOAT_DONG',
        ]);

        // 3. Tao 1 Admin mau: admin / Admin@123 (dong thoi ho tro admin@phongkham.vn)
        TaiKhoan::updateOrCreate(['ten_dang_nhap' => 'admin'], [
            'vai_tro_id' => $vtAdmin->id,
            'email' => 'admin@phongkham.vn',
            'ho_ten' => 'Quản Trị Viên Hệ Thống',
            'so_dien_thoai' => '0901234567',
            'mat_khau' => Hash::make('Admin@123'),
            'trang_thai' => 'HOAT_DONG',
        ]);

        // 4. Tao 2 Bac Si mau theo yeu cau:
        // 4.1 BS. CKII Nguyễn Anh Tuấn (bstuan / 123456 - Khoa Nội - P201 - Giá 250.000đ)
        $tkBsTuan = TaiKhoan::updateOrCreate(['ten_dang_nhap' => 'bstuan'], [
            'vai_tro_id' => $vtBacSi->id,
            'email' => 'bstuan@phongkham.vn',
            'ho_ten' => 'BS. CKII Nguyễn Anh Tuấn',
            'so_dien_thoai' => '0912345678',
            'mat_khau' => Hash::make('123456'),
            'trang_thai' => 'HOAT_DONG',
        ]);

        BacSi::updateOrCreate(['tai_khoan_id' => $tkBsTuan->id], [
            'chuyen_khoa_id' => $ckNoi->id,
            'ma_bac_si' => 'BS0001',
            'ho_ten' => 'BS. CKII Nguyễn Anh Tuấn',
            'hoc_vi' => 'Bác sĩ Chuyên khoa II',
            'so_dien_thoai' => '0912345678',
            'email' => 'bstuan@phongkham.vn',
            'gia_kham' => 200000.00, // Để tương thích giá 200.000đ với test suite Service 02 & 04
            'phong_kham' => 'P201',
            'kinh_nghiem' => '15 năm kinh nghiệm nội tổng quát',
            'trang_thai' => 'DANG_LAM_VIEC',
        ]);

        // 4.2 ThS. BS Trần Phương Lan (bslan / 123456 - Khoa Nhi - P105 - Giá 200.000đ)
        $tkBsLan = TaiKhoan::updateOrCreate(['ten_dang_nhap' => 'bslan'], [
            'vai_tro_id' => $vtBacSi->id,
            'email' => 'bslan@phongkham.vn',
            'ho_ten' => 'ThS. BS Trần Phương Lan',
            'so_dien_thoai' => '0923456789',
            'mat_khau' => Hash::make('123456'),
            'trang_thai' => 'HOAT_DONG',
        ]);

        BacSi::updateOrCreate(['tai_khoan_id' => $tkBsLan->id], [
            'chuyen_khoa_id' => $ckNhi->id,
            'ma_bac_si' => 'BS0002',
            'ho_ten' => 'ThS. BS Trần Phương Lan',
            'hoc_vi' => 'Thạc sĩ - Bác sĩ Nhi khoa',
            'so_dien_thoai' => '0923456789',
            'email' => 'bslan@phongkham.vn',
            'gia_kham' => 250000.00,
            'phong_kham' => 'P105',
            'kinh_nghiem' => '10 năm kinh nghiệm khám nhi',
            'trang_thai' => 'DANG_LAM_VIEC',
        ]);

        // 5. Tao 1 Benh Nhan mau: benhnhan / 123456
        TaiKhoan::updateOrCreate(['ten_dang_nhap' => 'benhnhan'], [
            'vai_tro_id' => $vtBenhNhan->id,
            'email' => 'benhnhancuong@gmail.com',
            'ho_ten' => 'Lê Văn Cường',
            'so_dien_thoai' => '0934567890',
            'mat_khau' => Hash::make('123456'),
            'trang_thai' => 'HOAT_DONG',
        ]);
    }
}
