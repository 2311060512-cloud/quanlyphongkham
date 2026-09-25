# 📋 BÁO CÁO CÔNG VIỆC PHÂN HỆ 02: BỆNH NHÂN & ĐẶT LỊCH KHÁM (NGƯỜI 2)
> **Dự án:** Hệ Thống Quản Lý Phòng Khám Đa Khoa (Kiến Trúc Microservices)  
> **Người thực hiện:** Người 2 - Vanh (Phân hệ Bệnh Nhân & Đặt Lịch Khám)  
> **Thư mục phụ trách:** `appointment-service/` (Port `8002`)  
> **Cơ sở dữ liệu:** `db_benh_nhan_lich_hen` (MySQL Port 3307 mặc định / 3306)

---

## 📌 I. TỔNG QUAN VAI TRÒ & TÍNH NĂNG NỔI BẬT

Phân hệ 02 là **cầu nối trực tiếp giữa Bệnh nhân và Phòng khám**, chịu trách nhiệm xử lý toàn bộ luồng nghiệp vụ từ quản lý hồ sơ y tế, điều phối lịch hẹn thông minh, chống trùng lịch khám, dời lịch (reschedule), đến chính sách hủy lịch và đính kèm hồ sơ y tế.

### 🌟 Các tính năng cao cấp đã hoàn thành (Chuẩn BookingCare, Medpro & Zocdoc):
1. **Khung giờ khám thông minh theo thời gian thực (Realtime Slot Engine):**
   - Tự động chia ca khám 30 phút thành 2 buổi: **Sáng (08:00 - 11:30)** và **Chiều (13:30 - 16:30)**.
   - Khi chọn bác sĩ và ngày khám, hệ thống tự động kiểm tra: Slot nào đã kín lịch hoặc đã qua giờ thì hóa xám (`disabled`, badge "Đã kín" / "Qua giờ"); Slot khả dụng hiển thị xanh và tự động chọn slot đầu tiên.
2. **Quản lý Hồ sơ Gia Đình & Đặt lịch cho người thân (Family Multi-Patient Intake):**
   - Cho phép 1 tài khoản đăng nhập quản lý hồ sơ y tế cho cả gia đình.
   - Hỗ trợ chuyển đổi nhanh: `[ Khám cho bản thân ]` hoặc `[ Đặt khám cho người thân ]` (Con cái, Bố/Mẹ, Vợ/Chồng, Người thân).
   - Tự động lưu và gợi ý danh sách người thân đã từng khám để điền nhanh chỉ trong 1 click.
3. **Kết luận chẩn đoán & Toa thuốc điện tử (E-Prescription & Clinical Summary):**
   - Bác sĩ cập nhật chẩn đoán y khoa (`chuan_doan`), lời dặn dò chế độ dinh dưỡng (`loi_khuyen`), danh mục thuốc điều trị (`toa_thuoc`) và ngày hẹn tái khám.
   - Bệnh nhân có thể xem và **In Phiếu Khám & Toa Thuốc Điện Tử** chuẩn bộ y tế trực tiếp từ cổng bệnh nhân.
4. **Thuật toán cốt lõi chống trùng lịch:**
   - Chặn tuyệt đối không cho 2 bệnh nhân đặt cùng 1 bác sĩ trong cùng 1 khung giờ khám (ca 30 phút); chặn đặt ngày trong quá khứ.
5. **Nghiệp vụ Dời lịch khám (Reschedule) & Chặn hủy sát giờ (< 2 tiếng):**
   - Bệnh nhân được chủ động đổi ca khám mới nếu bận việc; tự động đối chiếu slot trống và đếm số lần dời lịch. Chặn hủy ca khám nếu diễn ra trong vòng 2 tiếng (`HTTP 422`).
6. **Kiểm thử tự động 100%:**
   - Bộ test `Nguoi2MicroserviceTest.php` vượt qua **11/11 kịch bản nghiệp vụ** (53 assertions).

---

## 🗄️ II. CƠ SỞ DỮ LIỆU & MIGRATIONS (`db_benh_nhan_lich_hen`)

### 1. Bảng `benh_nhan` (Hồ Sơ Bệnh Nhân & Gia Đình)
- **Migration:** `appointment-service/database/migrations/2026_01_01_000001_create_benh_nhan_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng (bigIncrements).
  - `tai_khoan_id`: ID tài khoản người dùng liên kết từ Service 01 (cho phép 1 tài khoản có nhiều hồ sơ người thân).
  - `quan_he_chu_tai_khoan`: Mối quan hệ (`BAN_THAN`, `CON`, `CHA_ME`, `VO_CHONG`, `NGUOI_THAN`).
  - `ma_benh_nhan`: Mã định danh y tế chuẩn `BNxxxx` (Unique, Indexed).
  - `ho_ten`: Họ và tên bệnh nhân.
  - `ngay_sinh`: Ngày tháng năm sinh (date).
  - `gioi_tinh`: Giới tính (`NAM`, `NU`, `KHAC`).
  - `so_dien_thoai`: Số điện thoại liên hệ (Indexed).
  - `so_cccd`: Số Căn cước công dân / CMND.
  - `nhom_mau`: Nhóm máu (`A`, `B`, `AB`, `O`).
  - `tien_su_di_ung`: Tiền sử dị ứng kháng sinh, thuốc, thức ăn.
  - `tien_su_benh`: Bệnh lý nền mãn tính (tiểu đường, huyết áp, tim mạch...).
  - `nguoi_lien_he_khan_cap`: Tên người thân khi cấp cứu.
  - `sdt_khan_cap`: Số điện thoại người thân.

### 2. Bảng `lich_hen` (Ca Khám & Toa Thuốc)
- **Migration:** `appointment-service/database/migrations/2026_01_01_000002_create_lich_hen_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng.
  - `ma_lich_hen`: Mã ca khám chuẩn `LKxxxx` (Unique, Indexed).
  - `benh_nhan_id`: Khóa ngoại tham chiếu `benh_nhan.id` (`onDelete('cascade')`).
  - `bac_si_id`: ID bác sĩ phụ trách (Service 01).
  - `ngay_kham`: Ngày hẹn khám (`YYYY-MM-DD`, Indexed).
  - `gio_bat_dau`: Giờ bắt đầu ca khám 30 phút.
  - `gio_ket_thuc`: Giờ kết thúc ca khám.
  - `ly_do_kham`: Triệu chứng ban đầu của bệnh nhân.
  - `trang_thai`: `CHO_XAC_NHAN`, `DA_XAC_NHAN`, `DANG_KHAM`, `HOAN_THANH`, `DA_HUY`.
  - `chuan_doan`: Kết luận chẩn đoán y khoa của bác sĩ.
  - `loi_khuyen`: Lời dặn dò, hướng dẫn chăm sóc của bác sĩ.
  - `toa_thuoc`: Dữ liệu JSON danh mục thuốc kê đơn (`[{"ten_thuoc": "...", "ham_luong": "...", "so_luong": "...", "cach_dung": "..."}]`).
  - `ngay_tai_kham`: Ngày hẹn tái khám (date).
  - `tep_dinh_kem`: JSON danh sách ảnh/file y tế bệnh nhân gửi lên.
  - `so_lan_doi_lich`: Số lần dời lịch (mặc định: `0`).
  - `ly_do_doi_lich`: Lý do xin đổi ngày/giờ khám.

---

## 🌐 III. DANH SÁCH RESTFUL API ENDPOINTS PHÂN HỆ 02

| Phương thức | Endpoint Gateway (Port 8000) | Endpoint Nội bộ (Port 8002) | Quyền hạn | Chức năng nghiệp vụ |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/lich-hen/slots-kha-dung` | `/api/v1/lich-hen/slots-kha-dung` | Public | Tra cứu các ca 30p khả dụng theo ngày và bác sĩ |
| `POST` | `/api/v1/lich-hen/dat-lich` | `/api/v1/lich-hen/dat-lich` | Bệnh nhân | Đặt lịch khám (cho bản thân hoặc người thân) |
| `PUT` | `/api/v1/lich-hen/{id}/doi-lich` | `/api/v1/lich-hen/{id}/doi-lich` | Bệnh nhân/BS | Dời lịch khám sang ca mới (chống trùng lịch) |
| `PUT` | `/api/v1/lich-hen/{id}/huy` | `/api/v1/lich-hen/{id}/huy` | Bệnh nhân | Hủy ca khám (chặn nếu còn dưới 2 tiếng) |
| `PUT` | `/api/v1/lich-hen/{id}/ket-luan-kham` | `/api/v1/lich-hen/{id}/ket-luan-kham` | Bác sĩ / Admin | Kê đơn thuốc điện tử, dặn dò & hoàn tất ca khám |
| `GET` | `/api/v1/benh-nhan/ho-so-gia-dinh` | `/api/v1/benh-nhan/ho-so-gia-dinh` | Bệnh nhân | Lấy danh sách hồ sơ bản thân & người thân |
| `POST` | `/api/v1/benh-nhan/nguoi-than` | `/api/v1/benh-nhan/nguoi-than` | Bệnh nhân | Tạo hồ sơ người thân (con cái, bố mẹ...) |
| `GET` | `/api/v1/lich-hen/lich-su-cua-toi` | `/api/v1/lich-hen/lich-su-cua-toi` | Bệnh nhân | Xem lịch sử toàn bộ ca khám đã đặt |

---

## 🧪 IV. KẾT QUẢ KIỂM THỬ TỰ ĐỘNG (AUTOMATED TESTS)

Chạy lệnh kiểm thử độc lập cho phân hệ:
```bash
php appointment-service/artisan test
```
**Kết quả: `11 passed (53 assertions) - 100% OK`**
- `test_benh_nhan_bat_buoc_dang_nhap_khi_dat_lich`
- `test_mo_rong_ho_so_benh_an_dien_tu`
- `test_thuat_toan_chong_trung_lich_kham_30_phut` (409 Conflict)
- `test_chan_dat_lich_ngay_trong_qua_khu` (422 Unprocessable)
- `test_phan_quyen_bac_si_va_bo_loc_da_nang`
- `test_doi_lich_kham_thanh_cong_va_chong_trung_lich_khi_doi`
- `test_chan_huy_lich_kham_sat_gio_duoi_2_tieng` (422)
- `test_dinh_kem_tep_va_anh_y_te_khi_dat_lich`
- `test_tra_cuu_slots_kha_dung_theo_thoi_gian_thuc` (BookingCare style)
- `test_ho_so_gia_dinh_va_dat_lich_cho_nguoi_than` (Family Intake)
- `test_bac_si_ke_toa_thuoc_va_ket_luan_kham` (E-Prescription)
