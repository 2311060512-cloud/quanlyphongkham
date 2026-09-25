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

## 📌 III. CẤU TRÚC THƯ MỤC DỰ ÁN & HƯỚNG DẪN XÂY DỰNG TỪNG SERVICE

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

### 🌐 1. `api-gateway/` — Cổng Giao Tiếp Tập Trung & Web Portal (Port: 8000)
> **Phụ trách:** 👤 Dương (Leader) | **Database:** *Không dùng DB riêng*

#### 🎯 Chức năng thành viên cần xây dựng & duy trì:
- **Reverse Proxy Dispatcher:** Nhận toàn bộ request từ Client tại port 8000 và chuyển tiếp chuẩn xác sang 4 service con (8001, 8002, 8003, 8004) dựa trên tiền tố đường dẫn:
  - `/api/v1/xac-thuc/*`, `/api/v1/tai-khoan/*`, `/api/v1/bac-si/*`, `/api/v1/chuyen-khoa/*`, `/api/v1/lich-truc/*` ➔ `auth-service:8001`
  - `/api/v1/benh-nhan/*`, `/api/v1/lich-hen/*` ➔ `appointment-service:8002`
  - `/api/v1/dich-vu/*`, `/api/v1/kham-benh/*`, `/api/v1/can-lam-sang/*` ➔ `clinical-service:8003`
  - `/api/v1/hoa-don/*` ➔ `billing-service:8004`
- **Xác thực bảo mật tập trung (JWT Auth):** Giải mã Token JWT của người dùng, kiểm tra tính hợp lệ và tự động đính kèm các Header danh tính: `X-User-Id`, `X-User-Role`, `X-User-Email`, `X-User-Name` sang các service con.
- **Phân quyền tập trung (Role-based Gate):** Chặn các request không đủ quyền hạn (ví dụ: chỉ `ADMIN` mới được thêm bác sĩ/chuyên khoa, chỉ `BAC_SI` mới được kê cận lâm sàng).
- **Hạ tầng Health Check Realtime:** Quét trạng thái liveness (Online/Offline, độ trễ ms) của cả 4 services và trả về báo cáo tổng hợp tại `/api/v1/health`.
- **Giao diện Web Portal:** Hệ thống Blade view tích hợp (`dashboard.blade.php`, `auth.blade.php`) phục vụ trải nghiệm người dùng đầy đủ cho cả Admin, Bác sĩ và Bệnh nhân; tích hợp Modal Cập nhật Hồ sơ cá nhân & Upload Avatar, Modal Quản lý Ca trực Bác sĩ, và cơ chế tự động đối soát ca trực khi Đặt lịch hẹn.

---

### 🔐 2. `auth-service/` — Dịch Vụ Định Danh, Tài Khoản & Bác Sĩ (Port: 8001)
> **Phụ trách:** 👤 Dương | **Database:** `db_xac_thuc_bac_si`

#### 🎯 Chức năng thành viên cần xây dựng & duy trì:
- **Quản lý Tài khoản & Phân quyền RBAC:**
  - Đăng ký, Đăng nhập, Đổi mật khẩu, Khóa/Mở khóa tài khoản, Xóa tài khoản người dùng; cấp phát mã Token JWT chuẩn.
  - Cơ chế bảo vệ đặc quyền: **Nghiêm cấm tuyệt đối việc khóa hoặc xóa tài khoản Quản trị viên tối cao (ADMIN)** ở cả tầng Backend lẫn Frontend.
- **Hồ sơ Cá nhân (Profile) & Upload Avatar:**
  - Cho phép người dùng (Bác sĩ, Bệnh nhân, Admin) cập nhật thông tin cá nhân: Họ tên, Số điện thoại, Email, Ngày sinh, Giới tính, Địa chỉ cư trú.
  - Đối với Bác sĩ: Cập nhật Học vị, Phòng khám, Số năm kinh nghiệm công tác; dữ liệu được đồng bộ tức thời giữa bảng `tai_khoan` và `bac_si`.
  - Tải lên ảnh đại diện (Upload Avatar) định dạng Base64 Data URL, hiển thị đồng bộ trên thẻ bác sĩ, thanh điều hướng và thông tin hồ sơ.
- **Quản lý Danh mục Chuyên khoa & Bác sĩ (CRUD hoàn chỉnh):**
  - Thêm, Sửa, Xóa chuyên khoa phòng khám (Nội, Nhi, Răng Hàm Mặt, Mắt, Tai Mũi Họng...).
  - Thêm, Sửa, Xóa bác sĩ chuyên khoa; thiết lập phòng khám và giá khám ban đầu (`gia_kham`).
- **Quản lý Lịch trực / Ca làm việc của Bác sĩ (Doctor Shifts):**
  - Quản lý ca trực cố định theo các ngày trong tuần (Thứ Hai đến Chủ Nhật): Ca Sáng (07:30 - 11:30), Ca Chiều (13:30 - 17:00), Ca Tối, Cả Ngày.
  - Thiết lập phòng khám và số lượng bệnh nhân khám tối đa trên mỗi ca.
  - Cung cấp API kiểm tra lịch trực theo ngày `GET /api/v1/bac-si/{id}/kiem-tra-truc?ngay=YYYY-MM-DD` để liên kết chặt chẽ với phân hệ Đặt lịch hẹn tại API Gateway.
- **Điểm cung cấp liên dịch vụ:** Cung cấp API `GET /api/v1/bac-si/{id}` trả về `gia_kham` để `billing-service` tự động kéo đơn giá khám vào hóa đơn viện phí.

#### 🗄️ Cấu trúc dữ liệu (Models/Tables):
- `VaiTro` (`vai_tro`): `id`, `ma_vai_tro`, `ten_vai_tro`, `mo_ta`
- `TaiKhoan` (`tai_khoan`): `id`, `vai_tro_id`, `ho_ten`, `avatar`, `email`, `so_dien_thoai`, `ngay_sinh`, `gioi_tinh`, `dia_chi`, `mat_khau`, `trang_thai`
- `ChuyenKhoa` (`chuyen_khoa`): `id`, `ma_khoa`, `ten_khoa`, `mo_ta`, `trang_thai`
- `BacSi` (`bac_si`): `id`, `tai_khoan_id`, `chuyen_khoa_id`, `ma_bac_si`, `ho_ten`, `avatar`, `hoc_vi`, `so_dien_thoai`, `email`, `gia_kham`, `phong_kham`, `kinh_nghiem`, `trang_thai`
- `LichTrucBacSi` (`lich_truc_bac_si`): `id`, `bac_si_id`, `thu`, `ngay_trong_tuan`, `ca_truc`, `gio_bat_dau`, `gio_ket_thuc`, `so_luong_kham_toi_da`, `phong_kham`, `trang_thai`

---

### 📅 3. `appointment-service/` — Dịch Vụ Bệnh Nhân & Lịch Hẹn Khám (Port: 8002)
> **Phụ trách:** 👤 Việt Anh | **Database:** `db_benh_nhan_lich_hen`

#### 🎯 Chức năng thành viên cần xây dựng & duy trì:
- **Quản lý Hồ sơ Bệnh nhân (Bệnh án điện tử ban đầu):**
  - Tự động sinh mã y tế `BNxxxx` độc nhất.
  - Lưu trữ thông tin cá nhân: Họ tên, ngày sinh, giới tính, CCCD/CMND, số điện thoại, địa chỉ cư trú.
  - Quản lý tiền sử y tế: Nhóm máu (`A`, `B`, `AB`, `O`), tiền sử dị ứng thuốc, bệnh lý mãn tính, thông tin người liên hệ khẩn cấp.
- **Quy trình Đặt lịch khám thông minh:**
  - Bệnh nhân lựa chọn bác sĩ, ngày hẹn và khung giờ khám (theo slot 30 phút).
  - Tự động liên kết hoặc khởi tạo mới hồ sơ bệnh nhân nếu là lượt khám đầu.
- **Thuật toán cốt lõi Chống trùng lịch Bác sĩ (Conflict Prevention):**
  - Kiểm tra giao thoa thời gian ca khám: chặn tuyệt đối 2 bệnh nhân đặt cùng 1 bác sĩ trong cùng 1 khung giờ.
  - Bắt buộc trả về HTTP Status `409 Conflict` kèm mã lỗi `TRUNG_LICH_KHAM` khi phát hiện trùng slot.
  - Chặn đặt lịch vào các ngày trong quá khứ (`HTTP 422`).
- **Nghiệp vụ Dời lịch & Hủy ca khám:**
  - Dời lịch sang khung giờ mới, tự động kiểm tra slot trống và đếm số lần dời lịch.
  - Chính sách hủy ca khám nghiêm ngặt: Chặn hủy ca khám khi thời gian diễn ra còn dưới 2 tiếng (`KHONG_THE_HUY_SAT_GIO`).
- **Điểm cung cấp liên dịch vụ:** Cung cấp API `GET /api/v1/lich-hen/{id}` trả về `benh_nhan_id`, `bac_si_id`, `ngay_kham` cho `billing-service` tổng hợp hóa đơn.

#### 🗄️ Cấu trúc dữ liệu (Models/Tables):
- `BenhNhan` (`benh_nhan`): `id`, `ma_benh_nhan`, `tai_khoan_id`, `ho_ten`, `ngay_sinh`, `gioi_tinh`, `so_dien_thoai`, `email`, `dia_chi`, `so_cccd`, `nhom_mau`, `tien_su_di_ung`, `tien_su_benh`, `nguoi_lien_he_khan_cap`, `sdt_khan_cap`
- `LichHen` (`lich_hen`): `id`, `ma_lich_hen`, `benh_nhan_id`, `bac_si_id`, `ngay_kham`, `gio_bat_dau`, `gio_ket_thuc`, `ly_do_kham`, `trang_thai`, `ghi_chu_bac_si`, `so_lan_doi_lich`

---

### 🔬 4. `clinical-service/` — Dịch Vụ Khám Chuyên Môn & Cận Lâm Sàng (Port: 8003)
> **Phụ trách:** 👤 Khải | **Database:** `db_dich_vu_y_te`

#### 🎯 Chức năng thành viên cần xây dựng & duy trì:
- **Quản lý Danh mục Kỹ thuật Y tế (Bảng giá CLS):**
  - Tạo lập và quản lý danh mục các gói xét nghiệm (Công thức máu, Nước tiểu, Sinh hóa...), Chẩn đoán hình ảnh (Chụp X-Quang, Siêu âm ổ bụng, Nội soi tai mũi họng...), Thủ thuật y tế kèm theo đơn giá niêm yết.
- **Kê chỉ định Cận lâm sàng theo ca khám:**
  - Bác sĩ phụ trách ca khám (`lich_hen_id`) chỉ định 1 hoặc nhiều dịch vụ cận lâm sàng cho bệnh nhân.
  - Hỗ trợ hủy chỉ định dịch vụ nếu kỹ thuật viên chưa thực hiện.
- **Quy trình Thực hiện & Trả kết quả Cận lâm sàng:**
  - Hàng đợi chờ xét nghiệm/siêu âm cho kỹ thuật viên phòng cận lâm sàng.
  - Nhập kết quả chỉ số đo lường, kết luận chẩn đoán hình ảnh và upload tệp/ảnh kết quả (`file_ket_qua`).
- **Khám bệnh, Chẩn đoán & Kê đơn thuốc:**
  - Bác sĩ nhập triệu chứng lâm sàng, chẩn đoán xác định bệnh, kê đơn thuốc điều trị và dặn dò tái khám.
  - Đóng và hoàn tất ca khám chuyển sang bước quyết toán viện phí.
- **Điểm cung cấp liên dịch vụ:** Cung cấp API `GET /api/v1/dich-vu/lich-hen/{lich_hen_id}` trả về danh sách cận lâm sàng kèm `don_gia * so_luong` để `billing-service` tự động cộng tiền cận lâm sàng vào viện phí.

#### 🗄️ Cấu trúc dữ liệu (Models/Tables):
- `DichVu` (`dich_vu`): `id`, `ma_dich_vu`, `ten_dich_vu`, `loai_dich_vu`, `don_gia`, `mo_ta`, `trang_thai`
- `SuDungDichVu` (`su_dung_dich_vu`): `id`, `lich_hen_id`, `benh_nhan_id`, `bac_si_id`, `dich_vu_id`, `so_luong`, `don_gia`, `ket_qua`, `ghi_chu`, `file_ket_qua`, `trang_thai`
- `HoSoKhamBenh` (`ho_so_kham_benh`): `id`, `lich_hen_id`, `benh_nhan_id`, `bac_si_id`, `trieu_chung`, `chan_doan`, `don_thuoc`, `loi_dan_bac_si`, `ngay_tai_kham`

---

### 💳 5. `billing-service/` — Dịch Vụ Viện Phí, Hóa Đơn & Thanh Toán (Port: 8004)
> **Phụ trách:** 👤 Toàn | **Database:** `db_hoa_don_thanh_toan`

#### 🎯 Chức năng thành viên cần xây dựng & duy trì:
- **Cơ chế Tổng hợp Viện phí Tự động Liên dịch vụ (Core Feature):**
  - Tự động gọi HTTP song song sang 3 service con để tổng hợp đầy đủ chi phí ca khám:
    1. Gọi `appointment-service` ➔ Lấy `lich_hen_id`, `benh_nhan_id`, `bac_si_id`.
    2. Gọi `auth-service` ➔ Lấy `gia_kham` ban đầu của bác sĩ phụ trách.
    3. Gọi `clinical-service` ➔ Lấy toàn bộ danh sách dịch vụ cận lâm sàng đã dùng: `sum(don_gia * so_luong)`.
  - Tự động lập Hóa đơn tổng hợp và từng dòng Chi tiết hóa đơn:
    $$\text{Thực thu} = \text{Tiền khám} + \text{Tiền cận lâm sàng} - \text{Giảm giá}$$
- **Quản lý Hóa đơn & Biên lai Viện phí:**
  - Sinh mã hóa đơn chuẩn `HDxxxx`, lưu trữ trạng thái thanh toán (`CHUA_THANH_TOAN`, `DA_THANH_TOAN`, `DA_HOAN_TIEN`).
  - Hỗ trợ in biên lai viện phí chi tiết từng hạng mục cho bệnh nhân.
- **Xử lý Giao dịch Thanh toán Đa phương thức:**
  - Tiếp nhận xác nhận thanh toán qua các hình thức: `TIEN_MAT` (tại quầy thu ngân), `CHUYEN_KHOAN`, cổng thanh toán trực tuyến (`VNPAY`, `MOMO`).
- **Báo cáo Thống kê Doanh thu & Dòng tiền:**
  - Thống kê doanh thu phòng khám theo ngày, tuần, tháng.
  - Phân tích cơ cấu nguồn thu (tỷ trọng tiền khám bác sĩ so với tiền dịch vụ xét nghiệm/siêu âm).
  - Thống kê tỷ lệ ca khám đã thanh toán / nợ viện phí.

#### 🗄️ Cấu trúc dữ liệu (Models/Tables):
- `HoaDon` (`hoa_don`): `id`, `ma_hoa_don`, `lich_hen_id`, `benh_nhan_id`, `tien_kham`, `tien_dich_vu`, `tong_tien`, `giam_gia`, `thuc_thu`, `phuong_thuc_thanh_toan`, `trang_thai`, `ngay_thanh_toan`, `ghi_chu`
- `ChiTietHoaDon` (`chi_tiet_hoa_don`): `id`, `hoa_don_id`, `loai_khoan_thu` (`TIEN_KHAM`, `CAN_LAM_SANG`, `THUOC`), `ten_khoan_thu`, `so_luong`, `don_gia`, `thanh_tien`

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

| Vai trò (`vai_tro`) | Email / Tên đăng nhập | Mật khẩu | Thông tin chi tiết |
|---|---|---|---|
| **ADMIN** | `admin@phongkham.vn` / `admin` | `Admin@123` | Quản trị viên hệ thống phòng khám (toàn quyền) |
| **BAC_SI** | `bstuan@phongkham.vn` / `bstuan` | `123456` | BS. CKII Nguyễn Anh Tuấn (Khoa Nội tổng quát - Giá khám: 200,000đ) |
| **BAC_SI** | `bslan@phongkham.vn` / `bslan` | `123456` | ThS. BS Trần Phương Lan (Khoa Nhi - Giá khám: 250,000đ) |
| **BENH_NHAN** | `benhnhancuong@gmail.com` / `benhnhan` | `123456` | Bệnh nhân Lê Văn Cường (Mã hồ sơ: `BN20260001`) |

---

## 📂 VI. KIỂM THỬ BẰNG POSTMAN

1. Mở ứng dụng **Postman** -> Nhấn nút **Import**.
2. Chọn file `quanlyphongkham_microservices.postman_collection.json` tại thư mục gốc.
3. Bộ collection đã thiết lập sẵn:
   - Biến môi trường tự động `{{gateway_url}}` trỏ về `http://127.0.0.1:8000`.
   - Script tự động trích xuất và gán Token JWT `{{token}}` sau mỗi lần đăng nhập thành công.
