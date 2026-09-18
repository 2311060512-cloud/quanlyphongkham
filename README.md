# HỆ THỐNG QUẢN LÝ PHÒNG KHÁM ĐA KHOA (LARAVEL MICROSERVICES)

Khung hệ thống Microservices phòng khám đa khoa xây dựng trên môi trường **Laragon (PHP 8.2, Composer, MySQL Port 3307)** 

.\start-he-thong.bat


## 📌 Cấu Trúc Dự Án

Thư mục gốc: `D:\laragon\laragon\www\quanlyphongkham_microservices\` (hoặc `quanlyphongkhamdv`):
```text
├── 00_api_gateway/               (Port: 8000) - API Gateway, Reverse Proxy & JWT Auth (Dương)
├── 01_dich_vu_xac_thuc_bac_si/   (Port: 8001) - DB: db_xac_thuc_bac_si (Dương)
├── 02_dich_vu_benh_nhan_lich_hen/(Port: 8002) - DB: db_benh_nhan_lich_hen (Việt Anh)
├── 03_dich_vu_y_te_can_lam_sang/ (Port: 8003) - DB: db_dich_vu_y_te (Khải)
├── 04_dich_vu_hoa_don_thanh_toan/(Port: 8004) - DB: db_hoa_don_thanh_toan (Toàn)
│
├── khoi-tao-database.php          - Tự động tạo 4 database trên MySQL port 3307
├── chay-migrations.bat / .ps1     - Chạy migration & seeders cho 4 services
├── start-he-thong.bat / .ps1      - Khởi động đồng thời 5 microservices
├── stop-he-thong.bat / .ps1       - Dừng tất cả microservices
├── kiem-tra-he-thong.php          - Test tích hợp End-to-End tự động
├── NGUOI_2_BENH_NHAN_LICH_HEN.md  - Báo cáo chi tiết phân hệ 02 (Người 2)
└── quanlyphongkham_microservices.postman_collection.json - Bộ API Postman
```

---

## 🚀 Hướng Dẫn Cài Đặt & Chạy Hệ Thống

### Bước 1: Khởi tạo Database & Chạy Migrations + Seeders
Đảm bảo Laragon MySQL đang chạy tại cổng **3307** (`DB_HOST=127.0.0.1`, `DB_PORT=3307`, `root`, mật khẩu rỗng).
Mở terminal tại thư mục dự án và chạy:
```powershell
.\chay-migrations.bat
# Hoặc PowerShell:
powershell -ExecutionPolicy Bypass -File chay-migrations.ps1
```

### Bước 2: Khởi động 5 Microservices
Chạy file khởi động:
```powershell
.\start-he-thong.bat
# Hoặc PowerShell:
powershell -ExecutionPolicy Bypass -File start-he-thong.ps1
```
Lệnh sẽ mở 5 cửa sổ console tương ứng với 5 ports:
- **Port 8000:** `00_api_gateway` (Gateway chính & Dashboard)
- **Port 8001:** `01_dich_vu_xac_thuc_bac_si`
- **Port 8002:** `02_dich_vu_benh_nhan_lich_hen`
- **Port 8003:** `03_dich_vu_y_te_can_lam_sang`
- **Port 8004:** `04_dich_vu_hoa_don_thanh_toan`

### Bước 3: Mở Dashboard & Kiểm Thử
- Truy cập trình duyệt: **`http://127.0.0.1:8000`**
- Tại đây bạn có thể xem trạng thái Real-time Health Check của 5 service, bấm các nút test nhanh (Đăng nhập Admin/Bác sĩ/Bệnh nhân, Đặt lịch khám, Thử nghiệm thuật toán chống trùng lịch bác sĩ, Kê cận lâm sàng, Tự động tổng hợp hóa đơn và Báo cáo doanh thu).

### Bước 4: Chạy Kiểm Thử Tự Động Toàn Diện (CLI)
Khi các service đang chạy, mở một terminal và gõ:
```bash
php kiem-tra-he-thong.php
```

### Bước 5: Dừng hệ thống
Khi muốn dừng tất cả service:
```powershell
.\stop-he-thong.bat
```

---

## 🔑 Tài Khoản Mẫu Đã Seed Sẵn

| Vai Trò | Email | Mật Khẩu | Ghi Chú |
|---|---|---|---|
| **ADMIN** | `admin@phongkham.vn` | `admin123` | Quản trị viên hệ thống |
| **BAC_SI** | `bacsian@phongkham.vn` | `bacsi123` | BS.CKI Nguyễn Văn An (Nội khoa - Giá: 200,000đ) |
| **BAC_SI** | `bacsibinh@phongkham.vn` | `bacsi123` | ThS.BS Trần Thị Bình (Tim mạch - Giá: 250,000đ) |
| **BENH_NHAN** | `benhnhancuong@gmail.com` | `benhnhan123` | Bệnh nhân Lê Văn Cường |

---

## 📂 Kiểm Thử Bằng Postman
1. Mở Postman -> Chọn **Import**.
2. Chọn file `quanlyphongkham_microservices.postman_collection.json`.
3. Toàn bộ các request được thiết lập sẵn biến `{{gateway_url}}` và tự động lưu `{{token}}` sau khi đăng nhập.
