# HƯỚNG DẪN DỰ ÁN MODULAR MONOLITH QUẢN LÝ PHÒNG KHÁM ĐA KHOA (CLINIC MANAGEMENT)
> **Framework:** Laravel 12 | **Môi trường:** Laragon (PHP 8.2+, MySQL) | **Kiến trúc:** Modular Monolith (3-tier: Controller - Service - Repository)

---

## I. TỔNG QUAN HỆ THỐNG & PHÂN CHIA NHIỆM VỤ

Hệ thống Quản lý phòng khám đa khoa được tổ chức theo chuẩn **Modular Monolith**, phân chia trách nhiệm rõ ràng cho các thành viên trong nhóm:

- **NGƯỜI 1: QUẢN TRỊ VIÊN & DANH MỤC (ADMIN)**
  - Quản lý tài khoản, phân quyền (Admin, Bác sĩ, Lễ tân, Bệnh nhân).
  - Quản lý Chuyên khoa, Danh mục Bác sĩ, Bảng giá dịch vụ khám & xét nghiệm.
- **NGƯỜI 2: ĐẶT LỊCH KHÁM BỆNH (BỆNH NHÂN / KHÁCH HÀNG)**
  - Xem danh sách bác sĩ kèm ảnh chân dung, chuyên khoa, phòng khám và giá khám.
  - Tìm kiếm bác sĩ theo tên/từ khóa, lọc theo chuyên khoa.
  - Form đặt lịch khám online (Bác sĩ $\to$ Ngày $\to$ Giờ), nhập Họ tên, SĐT, Số CCCD, Triệu chứng ban đầu.
  - Sinh mã lịch hẹn tăng dần (`LK0001`, `LK0002`...), kiểm tra chống trùng lịch.
  - Tra cứu lịch hẹn cá nhân (bắt buộc đăng nhập), hủy lịch hẹn khi chưa khám.
- **NGƯỜI 3: KHÁM BỆNH & CHỈ ĐỊNH DỊCH VỤ (BÁC SĨ)**
  - Bác sĩ xem danh sách bệnh nhân đặt lịch theo ca của mình.
  - Xác nhận lịch hẹn, bắt đầu khám, ghi nhận chẩn đoán & lời khuyên.
  - Chỉ định dịch vụ cận lâm sàng (Xét nghiệm, Siêu âm, Chụp X-quang...).
- **NGƯỜI 4: THU NGÂN & THANH TOÁN (LỄ TÂN / KẾ TOÁN)**
  - Tự động lập hóa đơn (Tiền khám + Tiền dịch vụ cận lâm sàng).
  - Xác nhận thanh toán hóa đơn viện phí, in phiếu thu/hóa đơn.

---

## II. QUY TẮC NGHIỆP VỤ & PHÂN QUYỀN BẮT BUỘC (CRITICAL BUSINESS RULES)

> [!IMPORTANT]
> **TẤT CẢ THÀNH VIÊN VÀ AI KHI VIẾT CODE CẦN TUÂN THỦ NGHIÊM NGẶT CÁC NGUYÊN TẮC SAU ĐỂ TRÁNH LỖI LOGIC:**

### 1. Phân quyền xử lý Lịch khám (Chống thao tác chéo giữa các Bác sĩ)
- **Quy tắc sở hữu ca khám:** Mỗi lịch hẹn khám được chỉ định cho một Bác sĩ cụ thể (`bac_si_id`).
- **Chỉ có 2 đối tượng được phép Xác nhận / Bắt đầu khám / Hoàn thành ca khám:**
  1. **Quản trị viên (`ADMIN`)**: Có quyền điều phối tổng thể toàn phòng khám.
  2. **Chính Bác sĩ được phân công (`bac_si_id`)**: Phải so khớp tài khoản đang đăng nhập (`user->id`) với hồ sơ bác sĩ (`bac_si->tai_khoan_id`).
- **Nghiêm cấm:**
  - Bác sĩ khác **TUYỆT ĐỐI KHÔNG ĐƯỢC** xác nhận, bắt đầu khám hoặc hoàn tất ca khám của đồng nghiệp.
  - Middleware route `middleware('role:ADMIN,BAC_SI')` **chỉ kiểm tra vai trò chung**. Tầng Service **bắt buộc** phải gọi hàm kiểm tra quyền chi tiết (vd: `kiemTraQuyenBacSiHoacAdmin($lichHen, $user)`).
  - Tại Frontend: Nếu lịch hẹn thuộc về bác sĩ khác, phải ẩn nút thao tác và hiển thị nhãn `BS khác phụ trách`.

### 2. Phân quyền Bệnh nhân & Hủy lịch hẹn
- Bệnh nhân cần **đăng nhập** để xem danh sách lịch hẹn của bản thân (bảo mật thông tin y tế).
- Bệnh nhân chỉ được phép **HỦY** lịch hẹn của chính mình khi trạng thái là `CHO_XAC_NHAN` hoặc `DA_XAC_NHAN`.
- Khi ca khám đã ở trạng thái `DANG_KHAM` hoặc `HOAN_THANH`, hệ thống **phải chặn** không cho phép hủy.

### 3. Kiểm tra chống trùng lịch (Anti-conflict Booking)
- Khi bệnh nhân đặt lịch, hệ thống phải kiểm tra xem Bác sĩ đó đã có lịch hẹn ở cùng `ngay_kham` và `gio_kham` ở trạng thái khác `DA_HUY` hay chưa. Nếu đã có thì phải báo lỗi và yêu cầu chọn giờ/bác sĩ khác.

### 4. Quy chuẩn dữ liệu & Mã định danh
- **Mã lịch hẹn:** Sinh tự động theo thứ tự tăng dần chuẩn format `LK0001`, `LK0002`, `LK0003`...
- **Hồ sơ Bệnh nhân:** Bắt buộc lưu trữ Số CCCD (`so_cccd`) để đối chiếu danh tính khi tiếp đón.
- **Hồ sơ Bác sĩ:** Chứa ảnh chân dung (`hinh_anh`), học vị, chuyên khoa, phòng làm việc và giá khám niêm yết.

---

## III. CẤU TRÚC THƯ MỤC CHUẨN (MODULAR MONOLITH)

```
app/
├── Http/
│   └── Middleware/
│       ├── CheckRole.php              # Middleware phân quyền Role (ADMIN, BAC_SI, LE_TAN, BENH_NHAN)
│       └── ApiKeyMiddleware.php       # Middleware bảo vệ API nội bộ
├── Modules/
│   ├── TaiKhoan/                      # Xác thực, Đăng ký, Đăng nhập, Token Sanctum
│   ├── ChuyenKhoa/                    # Quản lý danh mục chuyên khoa
│   ├── BacSi/                         # Quản lý thông tin bác sĩ, giá khám, tìm kiếm & lọc
│   ├── BenhNhan/                      # Quản lý hồ sơ bệnh nhân, CCCD, lịch sử khám
│   ├── LichHen/                       # Nghiệp vụ đặt lịch, phân quyền bác sĩ, hủy lịch
│   ├── DichVu/                        # Danh mục dịch vụ cận lâm sàng (X-quang, Xét nghiệm...)
│   └── HoaDon/                        # Quản lý hóa đơn viện phí, thanh toán
├── Providers/
│   └── ModuleServiceProvider.php      # Auto-discovery Routes & DI Repositories
└── Traits/
    └── ApiResponseTrait.php           # Chuẩn hóa JSON Response (successResponse, errorResponse)
```

---

## IV. HƯỚNG DẪN KHỞI CHẠY TRÊN LARAGON

1. Khởi động **Laragon** (Start All: Apache + MySQL).
2. Kiểm tra file `.env` (Database: `quanlyphongkham`, port: `3306`).
3. Chạy lệnh migrate và nạp dữ liệu mẫu:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
4. Khởi chạy server:
   ```bash
   php artisan serve
   ```
5. Tài khoản mẫu kiểm thử:
   - **Admin:** `admin` / `Admin@123`
   - **Bác sĩ Khoa Nội:** `bstuan` / `123456`
   - **Bác sĩ Khoa Nhi:** `bslan` / `123456`
   - **Bệnh nhân:** `benhnhan` / `123456` (CCCD: `001201012345`)

---

## V. GỢI Ý PROMPT CHO CÁC THÀNH VIÊN KHI DÙNG AI (ANTIGRAVITY / CURSOR / COPILOT)

Khi thành viên khác dùng AI để phát triển module của mình, hãy copy đoạn này vào prompt đầu tiên:

```text
Dự án là Hệ thống Quản lý phòng khám đa khoa theo kiến trúc Modular Monolith (Laravel 12).
Tuân thủ nghiêm ngặt các quy tắc:
1. Kiến trúc 3 tầng độc lập theo từng Module: Controller -> Service -> Repository (kế thừa BaseRepository).
2. Phân quyền chặt chẽ tại tầng Service: Bác sĩ chỉ được thao tác (xác nhận, khám, hoàn thành) trên các ca khám được chỉ định cho mình (bac_si_id trùng với bac_si->id của tài khoản đăng nhập). Admin có toàn quyền. Bác sĩ khác không được can thiệp vào ca của đồng nghiệp.
3. Bệnh nhân chỉ xem và hủy lịch hẹn của chính mình khi trạng thái là CHO_XAC_NHAN hoặc DA_XAC_NHAN.
4. Mọi response trả về dùng ApiResponseTrait.
```
