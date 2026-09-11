# HƯỚNG DẪN DỰ ÁN MODULAR MONOLITH QUẢN LÝ KHÁCH SẠN (HOTEL MANAGEMENT)
> **Framework:** Laravel 12 | **Môi trường:** Laragon (PHP 8.2+, MySQL) | **Kiến trúc:** Modular Monolith (3-tier per Module)

---

## I. TỔNG QUAN KIẾN TRÚC DỰ ÁN

Dự án được tổ chức theo đúng sơ đồ kiến trúc **Modular Monolith**:
1. **Client (Web / Mobile / SPA):** Giao diện Single Page Application hiện đại viết bằng TailwindCSS & Vanilla JS tại `resources/views/client.blade.php`, giao tiếp qua **RESTful API** (HTTP/HTTPS).
2. **Backend Application (Monolith):** Một ứng dụng Laravel duy nhất, một tiến trình duy nhất (1 process, 1 deployment).
3. **Các Module chức năng độc lập (3 tầng Controller - Service - Repository):**
   - **`UserModule` (`app/Modules/User`)**: Xác thực tài khoản, phân quyền Role-based (Admin, Lễ tân, Khách hàng) theo **SOS06**.
   - **`RoomModule` (`app/Modules/Room`)**: Quản lý Loại phòng & Phòng (quan hệ 1-N theo **SOS05**), tìm kiếm - lọc - phân trang - upload ảnh theo **SOS07**.
   - **`CustomerModule` (`app/Modules/Customer`)**: Quản lý hồ sơ khách lưu trú, CCCD/CMND, số điện thoại.
   - **`BookingModule` (`app/Modules/Booking`)**: Xử lý nghiệp vụ đặt phòng, kiểm tra phòng trống, check-in, check-out, hủy phòng.
   - **`HotelServiceModule` (`app/Modules/HotelService`)**: Danh mục dịch vụ khách sạn (Spa, Buffet, Giặt là...) và chi tiết gọi dịch vụ theo phòng.
   - **`BillingModule` (`app/Modules/Billing`)**: Lập hóa đơn tự động (Tiền phòng + Tiền dịch vụ - Đặt cọc), thanh toán hóa đơn.
4. **Database:** CSDL MySQL chung (`quanlykhachsandv`), tương thích chuẩn Laragon.

---

## II. CẤU TRÚC THƯ MỤC CHUẨN

```
quanlykhachsandv/
├── app/
│   ├── Http/
│   │   └── Middleware/
│   │       ├── CheckRole.php          # Middleware phân quyền Role (SOS06)
│   │       └── ApiKeyMiddleware.php   # Middleware bảo mật API Key (SOS10)
│   ├── Modules/
│   │   ├── User/                      # Module Người dùng & Phân quyền
│   │   │   ├── Controllers/ (AuthController, UserController)
│   │   │   ├── Services/    (AuthService, UserService)
│   │   │   ├── Repositories/(UserRepositoryInterface, UserRepository)
│   │   │   ├── Models/      (User, Role)
│   │   │   └── routes.php
│   │   ├── Room/                      # Module Phòng & Loại phòng (1-N, Upload ảnh)
│   │   │   ├── Controllers/ (RoomController, RoomTypeController)
│   │   │   ├── Services/    (RoomService, RoomTypeService)
│   │   │   ├── Repositories/(RoomRepository, RoomTypeRepository)
│   │   │   ├── Models/      (Room, RoomType)
│   │   │   └── routes.php
│   │   ├── Customer/                  # Module Khách hàng
│   │   ├── Booking/                   # Module Đặt phòng & Lưu trú
│   │   ├── HotelService/              # Module Dịch vụ khách sạn
│   │   └── Billing/                   # Module Hóa đơn & Thanh toán
│   ├── Providers/
│   │   └── ModuleServiceProvider.php  # Tự động nạp Routes & DI Repositories
│   ├── Repositories/
│   │   ├── BaseRepositoryInterface.php
│   │   └── BaseRepository.php         # CRUD tổng quát tái sử dụng
│   └── Traits/
│       └── ApiResponseTrait.php       # Chuẩn hóa JSON Response (SOS02, SOS03)
├── database/
│   ├── migrations/                    # 8 bảng CSDL liên kết đầy đủ
│   └── seeders/                       # Dữ liệu mẫu (Admin, Lễ tân, Phòng, Dịch vụ)
└── resources/views/
    └── client.blade.php               # Giao diện Web SPA tương tác trực tiếp
```

---

## III. HƯỚNG DẪN KHỞI CHẠY TRÊN LARAGON

1. Mở ứng dụng **Laragon** -> Nhấn **Start All** (khởi động Apache và MySQL).
2. Mở trình duyệt truy cập phpMyAdmin hoặc HeidiSQL trong Laragon:
   - Tạo database tên: `quanlykhachsandv`
3. Mở Terminal tại thư mục dự án (`D:\laragon\laragon\www\quanlykhachsandv`) và chạy lệnh:
   ```bash
   php artisan migrate --seed
   ```
4. Truy cập giao diện ứng dụng:
   - Nếu dùng Virtual Host Laragon: `http://quanlykhachsandv.test`
   - Hoặc chạy lệnh `php artisan serve` rồi truy cập: `http://127.0.0.1:8000`
5. Tài khoản đăng nhập mẫu:
   - **Admin:** `admin` / `Admin@123`
   - **Lễ tân:** `letan` / `123456`
   - **Khách hàng:** `khachhang` / `123456`

---

## IV. CÁC BỘ PROMPT ĐỂ COPY VÀO ANTY IDE (ANTIGRAVITY IDE)

Dưới đây là các câu Prompt được tối ưu để bạn copy trực tiếp vào Anty IDE khi muốn mở rộng hoặc hoàn thiện các bài thực hành:

### Prompt 1: Thêm một Module mới theo đúng chuẩn Modular Monolith
```text
Tôi đang phát triển dự án Quản lý khách sạn theo kiến trúc Modular Monolith (Laravel) tại thư mục D:\laragon\laragon\www\quanlykhachsandv. Hãy tạo thêm cho tôi Module Promotion (Khuyến mãi / Giảm giá voucher) tuân thủ đúng cấu trúc 3 tầng hiện tại:
- Model: Promotion (mã code, phần trăm giảm, ngày bắt đầu, ngày kết thúc, số lượng)
- Repository: PromotionRepositoryInterface và PromotionRepository kế thừa BaseRepository
- Service: PromotionService xử lý kiểm tra mã hợp lệ và tính số tiền giảm
- Controller: PromotionController trả về JSON theo ApiResponseTrait
- routes.php: Đăng ký các route CRUD và route áp dụng mã voucher cho đơn đặt phòng.
Đăng ký Repository binding vào ModuleServiceProvider.
```

### Prompt 2: Viết Feature Test cho API Đặt phòng (Booking Module)
```text
Hãy viết cho tôi bộ Test tự động (PHPUnit / Pest Feature Test) cho Module Booking tại tests/Feature/BookingApiTest.php trong dự án D:\laragon\laragon\www\quanlykhachsandv. Test các trường hợp:
1. Đặt phòng thành công khi phòng còn trống (HTTP Status 201).
2. Báo lỗi 422 khi đặt phòng bị trùng lịch với khách khác đang ở hoặc ngày không hợp lệ.
3. Kiểm tra quy trình Check-in chuyển trạng thái phòng thành 'dang_o'.
4. Kiểm tra quy trình Check-out chuyển trạng thái phòng thành 'dang_don'.
```

### Prompt 3: Xuất file Export dữ liệu và Báo cáo thống kê
```text
Dựa vào dự án quản lý khách sạn hiện tại tại D:\laragon\laragon\www\quanlykhachsandv, hãy bổ sung thêm tính năng Báo cáo thống kê doanh thu theo ngày/tháng và xuất danh sách đơn đặt phòng ra file Excel/CSV. Đặt trong BillingModule hoặc tạo một module mới ReportModule, viết Service và Controller tương ứng theo đúng kiến trúc 3 tầng.
```
