# HỆ THỐNG QUẢN LÝ PHÒNG KHÁM ĐA KHOA (LARAVEL MICROSERVICES)

> Dự án xây dựng hệ thống phần mềm quản lý phòng khám đa khoa áp dụng mô hình kiến trúc **Microservices** hiện đại trên nền tảng **Laravel 12, PHP 8.2 và MySQL (Port 3307 - Laragon)**.

---

## 🏛️ I. CÁC TIÊU CHUẨN KIẾN TRÚC DỰ ÁN HƯỚNG ĐẾN

Hệ thống được thiết kế và chuẩn hóa theo các nguyên lý cốt lõi của kiến trúc Microservices trong môi trường doanh nghiệp:

1. **Chuẩn hóa đặt tên dịch vụ (Standard Naming Convention):**
   - Áp dụng quy ước **kebab-case** chuẩn quốc tế kèm hậu tố `-service` phản ánh chính xác ranh giới nghiệp vụ (**Bounded Context**): `api-gateway`, `auth-service`, `appointment-service`, `clinical-service`, `billing-service`.
   - Loại bỏ các tiền tố cục bộ (`01_`, `dich_vu_`) để sẵn sàng đóng gói Docker container, triển khai Kubernetes hoặc tách thành các Git repository độc lập.

2. **Nguyên tắc Cơ sở dữ liệu độc lập (Database-per-Service Pattern):**
   - Mỗi microservice sở hữu cơ sở dữ liệu riêng biệt (`db_xac_thuc_bac_si`, `db_benh_nhan_lich_hen`, `db_dich_vu_y_te`, `db_hoa_don_thanh_toan`).
   - Tuyệt đối **không truy vấn chéo (cross-database)** và **không dùng khóa ngoại vật lý (physical foreign keys)** giữa các database khác nhau để đảm bảo tính tự chủ và mở rộng (Loose Coupling).

3. **Cổng giao tiếp tập trung (API Gateway Pattern - Single Entry Point):**
   - Mọi tương tác từ Client (Web, Mobile, Postman) đều đi qua duy nhất một cổng **Port 8000** (`api-gateway`).
   - Xử lý định tuyến thông minh (Reverse Proxy Dispatcher), xác thực bảo mật tập trung (JWT Auth), giải mã danh tính người dùng và chuyển tiếp danh tính qua các Header chuẩn: `X-User-Id`, `X-User-Role`, `X-User-Email`.

4. **Giao tiếp liên dịch vụ linh hoạt (Inter-Service Communication & Fault Tolerance):**
   - Giao tiếp giữa các service thông qua HTTP RESTful Client với thời gian chờ (Timeout) và cơ chế xử lý lỗi dự phòng (**Fallback / Graceful Degradation**).
   - Điển hình: `billing-service` tự động tổng hợp viện phí bằng cách gọi đồng thời dữ liệu từ `auth-service` (giá khám), `appointment-service` (thông tin ca khám) và `clinical-service` (danh sách cận lâm sàng).

5. **Chuẩn hóa phản hồi API (RESTful Response Standard):**
   - Định dạng JSON thống nhất toàn hệ thống: `thanh_cong` (boolean), `thong_diep` (string), `du_lieu` (object/array), `ma_loi` (string khi thất bại).
   - Sử dụng chuẩn mã HTTP Status: `200 OK`, `201 Created`, `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `409 Conflict` (trùng lịch), `422 Unprocessable Entity`, `503 Service Unavailable`.

---

## 👥 II. PHÂN CÔNG CÔNG VIỆC TỪNG THÀNH VIÊN

Dự án được phân chia theo 4 phân hệ dịch vụ độc lập tương ứng với 4 thành viên trong nhóm:

| Thành viên | Phân hệ phụ trách | Thư mục & Port | Database | Trách nhiệm cốt lõi |
|---|---|:---:|---|---|
| **👤 Dương** *(Leader)* | **API Gateway & Dịch vụ Xác thực** | `api-gateway` (8000)<br>`auth-service` (8001) | `db_xac_thuc_bac_si` | • Xây dựng Reverse Proxy, JWT Auth, CORS, Health Check toàn hệ thống.<br>• Quản lý tài khoản, vai trò (`ADMIN`, `BAC_SI`, `BENH_NHAN`).<br>• Quản lý hồ sơ bác sĩ, bảng giá khám, chuyên khoa.<br>• Xây dựng giao diện Dashboard tổng thể phòng khám. |
| **👤 Việt Anh** | **Dịch vụ Bệnh Nhân & Đặt Lịch Khám** | `appointment-service` (8002) | `db_benh_nhan_lich_hen` | • Quản lý hồ sơ bệnh nhân, bệnh án điện tử, tiền sử bệnh.<br>• **Thuật toán chống trùng lịch khám** cho bác sĩ (HTTP 409 Conflict).<br>• Nghiệp vụ đặt lịch, dời lịch (reschedule) và hủy lịch khám.<br>• Viết bộ Automated Feature Tests cho quy trình đặt lịch. |
| **👤 Khải** | **Dịch vụ Y Tế & Cận Lâm Sàng** | `clinical-service` (8003) | `db_dich_vu_y_te` | • Quản lý danh mục kỹ thuật y tế (Xét nghiệm, Siêu âm, X-Quang).<br>• Tiếp nhận chỉ định cận lâm sàng theo ca khám (`lich_hen_id`).<br>• Kỹ thuật viên nhập chỉ số, kết quả cận lâm sàng, file đính kèm.<br>• Cung cấp API tổng tiền dịch vụ phục vụ quyết toán viện phí. |
| **👤 Toàn** | **Dịch vụ Hóa Đơn & Thanh Toán** | `billing-service` (8004) | `db_hoa_don_thanh_toan` | • **Cơ chế tự động tổng hợp viện phí liên dịch vụ** (Service 01 + 02 + 03).<br>• Tạo và quản lý hóa đơn khám bệnh, chi tiết từng khoản thu.<br>• Xử lý thanh toán đa kênh (Tiền mặt, Chuyển khoản, VNPay, MoMo).<br>• Báo cáo thống kê doanh thu và phân tích tỷ lệ thanh toán. |

---

## 📌 III. CẤU TRÚC THƯ MỤC DỰ ÁN

```text
quanlyphongkham_microservices/
├── api-gateway/                  (Port: 8000) - API Gateway, Reverse Proxy, JWT Auth & Web Dashboard
├── auth-service/                 (Port: 8001) - DB: db_xac_thuc_bac_si (Phụ trách: Dương)
├── appointment-service/          (Port: 8002) - DB: db_benh_nhan_lich_hen (Phụ trách: Việt Anh)
├── clinical-service/             (Port: 8003) - DB: db_dich_vu_y_te (Phụ trách: Khải)
├── billing-service/              (Port: 8004) - DB: db_hoa_don_thanh_toan (Phụ trách: Toàn)
│
├── khoi-tao-database.php          - Tự động tạo 4 database trên MySQL port 3307
├── chay-migrations.bat / .ps1     - Chạy migration & seeders tự động cho cả 4 services
├── start-he-thong.bat / .ps1      - Khởi động đồng thời cả 5 microservices trên các cửa sổ riêng
├── stop-he-thong.bat / .ps1       - Dừng toàn bộ tiến trình lắng nghe trên port 8000-8004
├── kiem-tra-he-thong.php          - Bộ test tích hợp tự động End-to-End toàn diện (CLI)
├── ARCHITECTURE.md                - Bản vẽ thiết kế kiến trúc kỹ thuật chi tiết
├── NGUOI_2_BENH_NHAN_LICH_HEN.md  - Báo cáo phân hệ 02 chi tiết
├── NGUOI_3_DICH_VU_Y_TE_CAN_LAM_SANG.md - Báo cáo phân hệ 03 chi tiết
└── quanlyphongkham_microservices.postman_collection.json - Bộ API Postman kiểm thử đầy đủ
```

---

## 🚀 IV. HƯỚNG DẪN CÀI ĐẶT & VẬN HÀNH HỆ THỐNG

### Bước 1: Khởi tạo Database & Chạy Migrations + Seeders
Đảm bảo máy chủ MySQL Laragon đang hoạt động tại cổng **3307** (`DB_HOST=127.0.0.1`, `DB_PORT=3307`, user: `root`, password: rỗng).

Chạy lệnh tự động:
```powershell
.\chay-migrations.bat
# Hoặc bằng PowerShell:
powershell -ExecutionPolicy Bypass -File chay-migrations.ps1
```

### Bước 2: Khởi động đồng thời 5 Microservices
Chạy file kích hoạt hệ thống:
```powershell
.\start-he-thong.bat
# Hoặc bằng PowerShell:
powershell -ExecutionPolicy Bypass -File start-he-thong.ps1
```
Hệ thống sẽ mở 5 console tương ứng với 5 ports độc lập:
- **Port 8000:** `api-gateway` (Cổng giao tiếp chính & Dashboard)
- **Port 8001:** `auth-service` (Dịch vụ Xác thực & Bác sĩ)
- **Port 8002:** `appointment-service` (Dịch vụ Bệnh nhân & Lịch hẹn)
- **Port 8003:** `clinical-service` (Dịch vụ Cận lâm sàng)
- **Port 8004:** `billing-service` (Dịch vụ Hóa đơn & Viện phí)

### Bước 3: Mở Dashboard & Kiểm thử trên trình duyệt
Truy cập trình duyệt tại địa chỉ: **`http://127.0.0.1:8000`**
- Xem trạng thái **Real-time Health Check** liveness của 4 microservices.
- Đăng nhập thử nghiệm các vai trò: Quản trị viên (Admin), Bác sĩ điều trị, Bệnh nhân.
- Thử nghiệm đặt lịch khám, kiểm chứng **thuật toán chặn trùng lịch ca khám**, chỉ định xét nghiệm và tự động kết xuất hóa đơn thanh toán.

### Bước 4: Chạy kiểm thử tự động toàn diện (CLI End-to-End Test)
Khi 5 service đang chạy, mở một terminal mới và chạy:
```bash
php kiem-tra-he-thong.php
```

### Bước 5: Dừng toàn bộ hệ thống
Khi muốn giải phóng tài nguyên và đóng 5 microservices:
```powershell
.\stop-he-thong.bat
# Hoặc bằng PowerShell:
powershell -ExecutionPolicy Bypass -File stop-he-thong.ps1
```

---

## 🔑 V. TÀI KHOẢN MẪU ĐÃ SEED SẴN

| Vai trò (`vai_tro`) | Email đăng nhập | Mật khẩu | Thông tin chi tiết |
|---|---|---|---|
| **ADMIN** | `admin@phongkham.vn` | `admin123` | Quản trị viên hệ thống phòng khám |
| **BAC_SI** | `bacsian@phongkham.vn` | `bacsi123` | BS.CKI Nguyễn Văn An (Khoa Nội tổng quát - Giá khám: 200,000đ) |
| **BAC_SI** | `bacsibinh@phongkham.vn` | `bacsi123` | ThS.BS Trần Thị Bình (Khoa Tim mạch - Giá khám: 250,000đ) |
| **BENH_NHAN** | `benhnhancuong@gmail.com` | `benhnhan123` | Bệnh nhân Lê Văn Cường (Mã hồ sơ: `BN20260001`) |

---

## 📂 VI. KIỂM THỬ BẰNG POSTMAN

1. Mở ứng dụng **Postman** -> Nhấn nút **Import**.
2. Chọn file `quanlyphongkham_microservices.postman_collection.json` tại thư mục gốc.
3. Bộ collection đã thiết lập sẵn:
   - Biến môi trường tự động `{{gateway_url}}` trỏ về `http://127.0.0.1:8000`.
   - Script tự động trích xuất và gán Token JWT `{{token}}` sau mỗi lần đăng nhập thành công.
