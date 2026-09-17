# 📋 BÁO CÁO CÔNG VIỆC PHÂN HỆ 02: BỆNH NHÂN & ĐẶT LỊCH KHÁM (NGƯỜI 2)
> **Dự án:** Hệ Thống Quản Lý Phòng Khám Đa Khoa (Kiến Trúc Microservices)  
> **Người thực hiện:** Người 2 (Phân hệ Bệnh Nhân & Đặt Lịch Khám)  
> **Thư mục phụ trách:** `02_dich_vu_benh_nhan_lich_hen/` (Port `8002`)  
> **Cơ sở dữ liệu:** `db_benh_nhan_lich_hen` (MySQL Port 3307 mặc định / 3306)

---

## 📌 I. TỔNG QUAN VAI TRÒ & NHIỆM VỤ ĐÃ HOÀN THÀNH

Phân hệ 02 là **cầu nối trực tiếp giữa Bệnh nhân và Phòng khám**, chịu trách nhiệm xử lý toàn bộ luồng nghiệp vụ từ quản lý hồ sơ y tế, điều phối lịch hẹn thông minh, chống trùng lịch khám, dời lịch (reschedule), đến chính sách hủy lịch và đính kèm hồ sơ y tế.

### Các thành tựu chính:
1. **Độc lập dịch vụ:** Microservice 02 hoạt động hoàn toàn độc lập trên Port 8002, có cơ sở dữ liệu riêng `db_benh_nhan_lich_hen`.
2. **Thuật toán cốt lõi chống trùng lịch:** Chặn tuyệt đối không cho 2 bệnh nhân đặt cùng 1 bác sĩ trong cùng 1 khung giờ khám (ca 30 phút); chặn đặt ngày trong quá khứ.
3. **Nghiệp vụ Dời lịch khám (Reschedule):** Cho phép bệnh nhân thay đổi ngày/giờ khám; tự động kiểm tra slot trống và đếm số lần dời lịch.
4. **Chính sách hủy ca khám nghiêm ngặt:** Chặn hủy lịch khi ca khám diễn ra trong vòng 2 tiếng (`KHONG_THE_HUY_SAT_GIO`, HTTP 422).
5. **Bệnh án điện tử & Tệp y tế đính kèm:** Bệnh nhân cung cấp nhóm máu, tiền sử dị ứng thuốc, bệnh lý nền và upload ảnh chụp đơn thuốc cũ/kết quả xét nghiệm để bác sĩ tham khảo.
6. **Kiểm thử tự động 100%:** Bộ test `Nguoi2MicroserviceTest.php` vượt qua 8/8 kịch bản nghiệp vụ.

---

## 🗄️ II. CƠ SỞ DỮ LIỆU & MIGRATIONS (`db_benh_nhan_lich_hen`)

### 1. File Migration: Tạo bảng Hồ Sơ Bệnh Nhân
- **Đường dẫn:** `02_dich_vu_benh_nhan_lich_hen/database/migrations/2026_01_01_000001_create_benh_nhan_table.php`
- **Bảng:** `benh_nhan`
- **Các trường dữ liệu:**
  - `id`: Khóa chính tự tăng (bigIncrements).
  - `ma_benh_nhan`: Mã định danh y tế chuẩn `BNxxxx` (Unique, có đánh Index để truy vấn tức thì).
  - `tai_khoan_id`: ID tài khoản người dùng liên kết từ Service 01 (nếu có).
  - `ho_ten`: Họ và tên bệnh nhân (bắt buộc).
  - `ngay_sinh`: Ngày tháng năm sinh (date).
  - `gioi_tinh`: Giới tính (`NAM`, `NU`, `KHAC`).
  - `so_dien_thoai`: Số điện thoại liên hệ (có đánh Index).
  - `email`: Địa chỉ email.
  - `dia_chi`: Địa chỉ cư trú.
  - `so_cccd`: Số Căn cước công dân / CMND.
  - `nhom_mau`: Nhóm máu (`A`, `B`, `AB`, `O`).
  - `tien_su_di_ung`: Tiền sử dị ứng kháng sinh, thuốc, thức ăn.
  - `tien_su_benh`: Bệnh lý nền mãn tính (tiểu đường, huyết áp, tim mạch...).
  - `nguoi_lien_he_khan_cap`: Tên người thân khi cấp cứu.
  - `sdt_khan_cap`: Số điện thoại người thân.
  - `ghi_chu_y_te`: Ghi chú lưu ý dành cho bác sĩ.
  - `timestamps`: `created_at`, `updated_at`.

### 2. File Migration: Tạo bảng Lịch Hẹn Khám Bệnh
- **Đường dẫn:** `02_dich_vu_benh_nhan_lich_hen/database/migrations/2026_01_01_000002_create_lich_hen_table.php`
- **Bảng:** `lich_hen`
- **Các trường dữ liệu:**
  - `id`: Khóa chính tự tăng (bigIncrements).
  - `ma_lich_hen`: Mã định danh ca khám chuẩn `LKxxxx` (Unique, Indexed).
  - `benh_nhan_id`: Khóa ngoại tham chiếu `benh_nhan.id` (`onDelete('cascade')`).
  - `bac_si_id`: ID bác sĩ phụ trách (đối soát sang Service 01).
  - `ngay_kham`: Ngày hẹn khám (`YYYY-MM-DD`, Indexed).
  - `gio_bat_dau`: Giờ bắt đầu ca khám 30 phút (ví dụ: `08:00:00`).
  - `gio_ket_thuc`: Giờ kết thúc ca khám (ví dụ: `08:30:00`).
  - `ly_do_kham`: Triệu chứng hoặc lý do thăm khám.
  - `trang_thai`: Enum trạng thái ca khám:
    - `CHO_XAC_NHAN`: Chờ phòng khám tiếp nhận.
    - `DA_XAC_NHAN`: Bác sĩ đã nhận lịch.
    - `DANG_KHAM`: Bệnh nhân đang trong phòng khám.
    - `HOAN_THANH`: Đã hoàn thành khám và chuyển viện phí sang Service 04.
    - `DA_HUY`: Ca khám đã bị hủy.
  - `ghi_chu_bac_si`: Ghi chú dặn dò sau khám.
  - `tep_dinh_kem`: Kiểu JSON lưu danh sách ảnh/file y tế bệnh nhân gửi lên.
  - `so_lan_doi_lich`: Số lần bệnh nhân đã yêu cầu dời lịch (mặc định: `0`).
  - `ly_do_doi_lich`: Lý do xin đổi ngày/giờ khám.
  - `thoi_gian_doi_lich_gan_nhat`: Mốc thời gian thực hiện dời lịch.
  - `timestamps`: `created_at`, `updated_at`.

### 3. File Seeder: Khởi tạo dữ liệu mẫu
- **Đường dẫn:** `02_dich_vu_benh_nhan_lich_hen/database/seeders/DatabaseSeeder.php`
- Cung cấp sẵn hồ sơ bệnh nhân chuẩn (`BN20260001`, `BN20260002`...) và các ca lịch hẹn mẫu giúp nhóm trưởng và các thành viên test chạy hệ thống ngay lập tức mà không cần tự nhập liệu.

---

## 💻 III. DANH SÁCH FILE SOURCE CODE BACKEND ĐÃ TẠO & CẬP NHẬT

### 1. Lớp Model (Eloquent ORM)
- `02_dich_vu_benh_nhan_lich_hen/app/Models/BenhNhan.php`:
  - Quan hệ 1-N: `hasMany(LichHen::class, 'benh_nhan_id')`.
  - Khai báo đầy đủ `$fillable` bảo vệ dữ liệu hồ sơ y tế.
- `02_dich_vu_benh_nhan_lich_hen/app/Models/LichHen.php`:
  - Quan hệ N-1: `belongsTo(BenhNhan::class, 'benh_nhan_id')`.
  - `$casts`: `tep_dinh_kem => 'array'`, `ngay_kham => 'date:Y-m-d'`, `thoi_gian_doi_lich_gan_nhat => 'datetime'`.

### 2. Lớp Nghiệp Vụ Chuyên Sâu (Service Layer)
- `02_dich_vu_benh_nhan_lich_hen/app/Services/LichHenService.php`:
  - `kiemTraTrungLich($bacSiId, $ngayKham, $gioBatDau, $gioKetThuc, $boQuaLichHenId = null)`: Thuật toán kiểm tra giao thoa khoảng thời gian chống trùng ca khám.
  - `datLichKham($duLieu)`: Quy trình tạo lịch hẹn khép kín, tự động tạo mới hồ sơ bệnh nhân nếu chưa có, sinh mã `LKxxxx` độc nhất.
  - `doiLichKham($id, $ngayKhamMoi, $gioBatDauMoi, $gioKetThucMoi, $lyDoDoi)`: Nghiệp vụ dời lịch, kiểm tra xung đột slot của bác sĩ, tăng `so_lan_doi_lich`.
  - `huyLichHen($id, $lyDoHuy, $vaiTro, $userHienTai)`: Áp dụng quy tắc chặn hủy sát giờ nếu ca khám diễn ra trong vòng 2 tiếng.
  - `dieuPhoiTrangThai($id, $trangThaiMoi, $ghiChu)`: Chuyển đổi trạng thái khám (`DA_XAC_NHAN`, `DANG_KHAM`, `HOAN_THANH`).
  - `layLichSuKhamBenhNhan($benhNhanId, $soDienThoai)`: Truy xuất toàn bộ lịch sử ca khám.
- `02_dich_vu_benh_nhan_lich_hen/app/Services/BenhNhanService.php`:
  - Tự động sinh mã `BN` theo năm và số thứ tự tự tăng.
  - Cập nhật thông tin bệnh án điện tử, liên hệ khẩn cấp.

### 3. Lớp Controller (REST API Handler)
- `02_dich_vu_benh_nhan_lich_hen/app/Http/Controllers/Controller.php`:
  - Lớp Base Controller chuẩn hóa cho Laravel 12.
- `02_dich_vu_benh_nhan_lich_hen/app/Http/Controllers/LichHenController.php`:
  - Xử lý các request đặt lịch, dời lịch, điều phối trạng thái, xem lịch hẹn.
- `02_dich_vu_benh_nhan_lich_hen/app/Http/Controllers/BenhNhanController.php`:
  - Xử lý tra cứu và cập nhật hồ sơ bệnh nhân.

### 4. Middleware, Notification & Route
- `02_dich_vu_benh_nhan_lich_hen/app/Http/Middleware/XacThucService02Middleware.php`:
  - Xác thực bảo mật nội bộ và chuyển đổi thông tin từ Gateway Header.
- `02_dich_vu_benh_nhan_lich_hen/app/Notifications/ThongBaoLichHenNotification.php`:
  - Định nghĩa sự kiện thông báo trạng thái ca khám.
- `02_dich_vu_benh_nhan_lich_hen/routes/api.php`:
  - Đăng ký đầy đủ danh mục RESTful API v1 của Microservice 02.

---

## 🌐 IV. DANH SÁCH API ENDPOINTS PHÂN HỆ 02

| Phương thức | Endpoint Gateway (Port 8000) | Endpoint Nội bộ (Port 8002) | Mô tả nghiệp vụ |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/v1/lich-hen/dat-lich` | `/api/v1/lich-hen` | Đặt lịch khám (kiểm tra chống trùng lịch) |
| `PUT` | `/api/v1/lich-hen/{id}/doi-lich` | `/api/v1/lich-hen/{id}/doi-lich` | Dời lịch hẹn sang ngày/giờ mới |
| `PUT` | `/api/v1/lich-hen/{id}/huy` | `/api/v1/lich-hen/{id}/huy` | Hủy lịch hẹn (chặn nếu dưới 2 tiếng) |
| `PATCH` | `/api/v1/lich-hen/{id}/trang-thai` | `/api/v1/lich-hen/{id}/trang-thai` | Điều phối trạng thái ca khám |
| `GET` | `/api/v1/lich-hen/benh-nhan` | `/api/v1/lich-hen/benh-nhan` | Lấy lịch sử khám của bệnh nhân |
| `GET` | `/api/v1/benh-nhan/{id}` | `/api/v1/benh-nhan/{id}` | Lấy chi tiết hồ sơ bệnh nhân & bệnh án |
| `PUT` | `/api/v1/benh-nhan/{id}` | `/api/v1/benh-nhan/{id}` | Cập nhật tiền sử bệnh, nhóm máu, dị ứng |

---

## 🎨 V. GIAO DIỆN & TÍCH HỢP GATEWAY (FRONTEND)

1. **Giao diện Dashboard tổng thể (`00_api_gateway/resources/views/dashboard.blade.php`):**
   - **Tab Cổng Bệnh Nhân:** Cho phép tra cứu danh sách bác sĩ của Service 01, xem giá niêm yết và ấn Đặt lịch trực tiếp.
   - **Form Đặt Lịch Thông Minh:** Tích hợp bộ chọn khung giờ 30 phút, form điền bệnh án điện tử (nhóm máu, dị ứng, bệnh nền) và upload ảnh y tế.
   - **Bọc Form cách ly (`autocomplete="off"`):** Khắc phục triệt để hiện tượng trình duyệt Chrome nhận nhầm ô "Lý do khám" thành tính năng lưu mật khẩu tài khoản.
   - **Modal Dời Lịch Hẹn (Reschedule):** Cho phép bệnh nhân chủ động chọn ngày và ca khám mới trực tiếp trên bảng lịch khám.
2. **Trang Chuyên Biệt Người 2 (`00_api_gateway/resources/views/nguoi2.blade.php`):**
   - Cung cấp cổng demo riêng biệt cho phân hệ Đặt lịch & Bệnh nhân, độc lập và dễ dàng kiểm thử nghiệm vụ.
3. **Bộ điều hướng Gateway (`00_api_gateway/app/Http/Controllers/CongGiaoTiepController.php`):**
   - Định tuyến an toàn các API `/api/v1/lich-hen/*` sang `http://127.0.0.1:8002`.

---

## 🧪 VI. KẾT QUẢ KIỂM THỬ (AUTOMATED TESTING)

1. **Bộ Test Chuyên Biệt Microservice 02 (`02_dich_vu_benh_nhan_lich_hen/tests/Feature/Nguoi2MicroserviceTest.php`):**
   - `test_dat_lich_kham_thanh_cong`: Đạt.
   - `test_chan_trung_lich_kham_cung_bac_si_cung_gio` (Mã lỗi 409 Conflict): Đạt.
   - `test_chan_dat_lich_ngay_trong_qua_khu` (Mã lỗi 422): Đạt.
   - `test_doi_lich_kham_reschedule_thanh_cong`: Đạt.
   - `test_chan_huy_lich_sat_gio_duoi_2_tieng` (Mã lỗi 422): Đạt.
   - `test_lay_lich_su_kham_benh_nhan`: Đạt.
   - `test_benh_an_dien_tu_va_tep_dinh_kem`: Đạt.
   - `test_dieu_phoi_trang_thai_ca_kham`: Đạt.
   - **Kết quả:** `Tests: 8 passed (24 assertions) - 100% OK`.

2. **Kịch Bản Tích Hợp Toàn Hệ Thống (`php kiem-tra-he-thong.php`):**
   - Đạt 12/12 bước tích hợp liên dịch vụ (Service 01 -> Service 02 -> Service 03 -> Service 04).
