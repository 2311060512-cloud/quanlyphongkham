# 📋 BÁO CÁO CÔNG VIỆC PHÂN HỆ 01: API GATEWAY & DỊCH VỤ XÁC THỰC, TÀI KHOẢN, BÁC SĨ (NGƯỜI 1)
> **Dự án:** Hệ Thống Quản Lý Phòng Khám Đa Khoa (Kiến Trúc Microservices)  
> **Người thực hiện:** Người 1 - Dương (Leader)  
> **Thư mục phụ trách:** `api-gateway/` (Port `8000`) & `auth-service/` (Port `8001`)  
> **Cơ sở dữ liệu:** `db_xac_thuc_bac_si` (MySQL Port 3307 mặc định / 3306)

---

## 📌 I. TỔNG QUAN VAI TRÒ & CÁC TÍNH NĂNG CỐT LÕI

Phân hệ 01 đóng vai trò là **"Trái tim và Cổng phòng tuyến"** của toàn bộ hệ thống, chịu trách nhiệm tiếp nhận toàn bộ lưu lượng truy cập từ người dùng, giải mã danh tính, phân luồng điều phối đến các microservices nội bộ, đồng thời quản lý toàn bộ thực thể nòng cốt: Tài khoản, Phân quyền, Hồ sơ cá nhân, Chuyên khoa, Đội ngũ bác sĩ, Bảng giá khám niêm yết và Ca trực y tế.

### 🌟 Các tính năng cao cấp đã hoàn thành:

1. **Cổng Giao Tiếp Tập Trung (API Gateway Reverse Proxy Dispatcher):**
   - Tiếp nhận mọi tương tác tại duy nhất một cổng **Port 8000** (`api-gateway`).
   - Phân tích tiền tố URL để chuyển tiếp chuẩn xác và thông minh sang 4 microservices chuyên trách (8001, 8002, 8003, 8004).
   - Tự động bắt lỗi và xử lý ngoại lệ mạng (**Graceful Degradation / Fallback 503** khi có service tạm thời ngoại tuyến).

2. **Xác Thực Bảo Mật Tập Trung (JWT Auth & Header Injection):**
   - Giải mã mã thông báo bảo mật (Bearer Token JWT & Laravel Sanctum Token).
   - Tự động kiểm tra tính hợp lệ và inject đồng bộ các Header danh tính: `X-User-Id`, `X-User-Role`, `X-User-Email`, `X-User-Name` sang các service con.
   - **Cơ chế thu hồi quyền tức thì khi khóa tài khoản:** Khi tài khoản bị Admin chuyển sang trạng thái `BI_KHOA`, hệ thống từ chối cấp mới token khi đăng nhập, đồng thời vô hiệu hóa ngay lập tức các token cũ đã cấp trước đó thông qua hàm kiểm tra `dangHoatDong()`.

3. **Hệ Thống Giám Sát Sức Khỏe Microservices Realtime (Health Check Engine):**
   - API `GET /api/v1/health` tự động ping và đo lường độ trễ mạng (`ms`) cùng trạng thái `Online/Offline` của cả 4 services.
   - Trực quan hóa tình trạng hệ thống trên giao diện quản trị Admin với biểu tượng đèn tín hiệu nhấp nháy.

4. **Quản Lý Tài Khoản & Phân Quyền Vai Trò (RBAC):**
   - Đăng ký tài khoản bệnh nhân mới, đăng nhập đa kênh (Email hoặc Tên đăng nhập), đổi mật khẩu bảo mật.
   - Quản trị viên (Admin) quản lý tập trung toàn bộ danh sách tài khoản: lọc theo vai trò (`ADMIN`, `BAC_SI`, `BENH_NHAN`), kích hoạt, khóa hoặc mở khóa tài khoản.
   - **Cơ chế bảo vệ đặc quyền Quản trị viên tối cao:** Chặn tuyệt đối ở cả Backend và Frontend không cho phép khóa hoặc xóa tài khoản `ADMIN`.

5. **Hồ Sơ Cá Nhân (Profile) & Upload Avatar Base64:**
   - Người dùng tự cập nhật thông tin cá nhân: Họ tên, Ngày sinh, Giới tính, SĐT, Email, Địa chỉ thường trú.
   - Riêng Bác sĩ: Cập nhật Học vị, Phòng khám, Số năm kinh nghiệm công tác (dữ liệu được đồng bộ realtime giữa bảng `tai_khoan` và `bac_si`).
   - Hỗ trợ tải lên ảnh đại diện Avatar định dạng Base64 Data URL, tự động đồng bộ trên thanh Sidebar, Thẻ bác sĩ và Bảng giá khám.

6. **Quản Lý Chuyên Khoa & Đội Ngũ Bác Sĩ (CRUD Hoàn Chỉnh):**
   - Quản lý danh mục chuyên khoa: Thêm, sửa, xóa, kích hoạt/tắt hoạt động chuyên khoa (Nội, Ngoại, Nhi, Răng Hàm Mặt, Mắt...).
   - Quản lý bác sĩ: Tạo mới tài khoản bác sĩ liên kết hồ sơ chuyên môn, gán phòng làm việc, thiết lập giá khám ban đầu (`gia_kham`).

7. **Bảng Giá Khám Bệnh Niêm Yết Toàn Viện (Consultation Price List):**
   - API `GET /api/v1/bac-si/bang-gia-kham`: Tự động tính toán số liệu thống kê: Giá khám thấp nhất, Giá khám cao nhất, Giá khám bình quân toàn viện.
   - Phân loại biểu phí theo học vị (Giáo sư, Tiến sĩ, CKII, CKI, ThS, Bác sĩ Đa khoa) và phân khúc (Tiêu Chuẩn, Chuyên Gia, VIP).
   - Cho phép Admin cập nhật nhanh giá khám của từng bác sĩ (`PUT /api/v1/bac-si/{id}/gia-kham`).
   - Hỗ trợ chức năng in biểu phí khám bệnh chuẩn y tế trực tiếp từ trình duyệt.

8. **Quản Lý Ca Trực Bác Sĩ & Quy Trình Phê Duyệt Bắt Buộc Bởi Admin:**
   - **Quy định 1 ca/ngày:** Mỗi bác sĩ chỉ được đăng ký tối đa 1 ca trực trong 1 ngày (Ca Sáng, Ca Chiều, Ca Tối, Cả Ngày) để bảo đảm chất lượng chuyên môn y khoa.
   - **Quy trình Admin phê duyệt:** Bác sĩ đăng ký ca trực mới sẽ ở trạng thái `CHO_DUYET`. Chỉ khi Admin bấm duyệt (`PUT /api/v1/bac-si/lich-truc/{id}/duyet`), ca trực mới chuyển sang `HOAT_DONG`.
   - **Liên kết chặt chẽ với Lịch hẹn:** Phân hệ đặt lịch của Bệnh nhân chỉ hiển thị các ca trực đã được Admin phê duyệt (`HOAT_DONG`).

9. **Xây Dựng Giao Diện Web Dashboard Trung Tâm & Tiện Ích In Ấn:**
   - Phát triển giao diện `dashboard.blade.php` hiện đại với Tailwind CSS, FontAwesome 6, SweetAlert2.
   - Menu Sidebar điều hướng động tự động thích ứng theo quyền của từng nhóm người dùng.
   - Tích hợp tính năng **In Phiếu Thu / Hóa Đơn Khám Bệnh & Cận Lâm Sàng** tại bàn khám bác sĩ với chức năng đọc số tiền thành chữ tiếng Việt tự động.

---

## 🗄️ II. CƠ SỞ DỮ LIỆU & MIGRATIONS (`db_xac_thuc_bac_si`)

### 1. Bảng `vai_tro` (Phân quyền người dùng)
- **Migration:** `auth-service/database/migrations/2026_01_01_000001_create_vai_tro_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng (bigIncrements).
  - `ma_vai_tro`: Mã định danh quyền (`ADMIN`, `BAC_SI`, `BENH_NHAN`, `THU_NGAN`, `KY_THUAT_VIEN`) - Unique.
  - `ten_vai_tro`: Tên hiển thị quyền.
  - `mo_ta`: Mô tả phạm vi phân quyền.

### 2. Bảng `tai_khoan` (Người dùng hệ thống)
- **Migration:** `auth-service/database/migrations/2026_01_01_000002_create_tai_khoan_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng.
  - `vai_tro_id`: Khóa ngoại tham chiếu `vai_tro.id`.
  - `ten_dang_nhap`: Tên tài khoản đăng nhập (Unique).
  - `mat_khau`: Mật khẩu mã hóa Bcrypt.
  - `ho_ten`: Họ và tên người dùng.
  - `avatar`: Ảnh đại diện người dùng (Lưu dạng chuỗi Base64 Data URL).
  - `email`: Địa chỉ email (Unique).
  - `so_dien_thoai`: Số điện thoại liên lạc.
  - `ngay_sinh`: Ngày sinh (date).
  - `gioi_tinh`: Giới tính (`NAM`, `NU`, `KHAC`).
  - `dia_chi`: Địa chỉ nơi cư trú.
  - `trang_thai`: Trạng thái hoạt động (`HOAT_DONG`, `BI_KHOA`).

### 3. Bảng `chuyen_khoa` (Danh mục chuyên khoa phòng khám)
- **Migration:** `auth-service/database/migrations/2026_01_01_000003_create_chuyen_khoa_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng.
  - `ma_khoa`: Mã chuyên khoa (vd: `CK_NOI`, `CK_NHI`, `CK_MAT`) - Unique.
  - `ten_khoa`: Tên khoa khám bệnh.
  - `mo_ta`: Mô tả chức năng nhiệm vụ khoa.
  - `trang_thai`: `HOAT_DONG`, `NGUNG_HOAT_DONG`.

### 4. Bảng `bac_si` (Hồ sơ chuyên môn bác sĩ)
- **Migration:** `auth-service/database/migrations/2026_01_01_000004_create_bac_si_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng.
  - `tai_khoan_id`: Khóa ngoại tham chiếu `tai_khoan.id` (liên kết 1-1).
  - `chuyen_khoa_id`: Khóa ngoại tham chiếu `chuyen_khoa.id`.
  - `ma_bac_si`: Mã số định danh bác sĩ (`BSxxxx`) - Unique.
  - `ho_ten`: Họ tên bác sĩ.
  - `avatar`: Ảnh bác sĩ (đồng bộ từ tài khoản).
  - `hoc_vi`: Học hàm học vị (`GS`, `PGS`, `TS`, `ThS`, `CKII`, `CKI`, `BS`).
  - `so_dien_thoai`: Số điện thoại làm việc.
  - `email`: Email làm việc.
  - `gia_kham`: Đơn giá khám niêm yết ban đầu (VNĐ).
  - `phong_kham`: Tên/Số phòng làm việc (vd: `Phòng 102 - Tầng 1`).
  - `kinh_nghiem`: Số năm kinh nghiệm công tác.
  - `trang_thai`: Trạng thái công tác (`DANG_LAM_VIEC`, `NGHI_PHEP`, `DA_NGHI_VIEC`).

### 5. Bảng `lich_truc_bac_si` (Ca làm việc & Lịch trực)
- **Migration:** `auth-service/database/migrations/2026_01_01_000005_create_lich_truc_bac_si_table.php`
- **Các trường:**
  - `id`: Khóa chính tự tăng.
  - `bac_si_id`: Khóa ngoại tham chiếu `bac_si.id`.
  - `thu`: Thứ trong tuần (`THU_HAI`, `THU_BA`, `THU_TU`, `THU_NAM`, `THU_SAU`, `THU_BAY`, `CHU_NHAT`).
  - `ngay_trong_tuan`: Giá trị số tương ứng (`2` -> `8`).
  - `ca_truc`: Loại ca trực (`SANG`, `CHIEU`, `TOI`, `CA_NGAY`).
  - `gio_bat_dau`: Giờ bắt đầu ca trực.
  - `gio_ket_thuc`: Giờ kết thúc ca trực.
  - `so_luong_kham_toi_da`: Giới hạn bệnh nhân tối đa trong ca trực.
  - `phong_kham`: Phòng trực thực tế.
  - `trang_thai`: Trạng thái phê duyệt (`CHO_DUYET`, `HOAT_DONG`, `TU_CHOI`, `TAM_DUNG`).

---

## 🌐 III. DANH SÁCH RESTFUL APIS PHÂN HỆ 01

| Phương thức | Endpoint Gateway (Port 8000) | Endpoint Auth (Port 8001) | Quyền hạn | Chức năng nghiệp vụ |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/health` | `/api/v1/health` | Public | Realtime Health Check trạng thái liveness 4 microservices |
| `POST` | `/api/v1/xac-thuc/dang-ky` | `/api/v1/xac-thuc/dang-ky` | Public | Đăng ký tài khoản bệnh nhân mới |
| `POST` | `/api/v1/xac-thuc/dang-nhap` | `/api/v1/xac-thuc/dang-nhap` | Public | Đăng nhập hệ thống, cấp Bearer Token & JWT |
| `GET` | `/api/v1/xac-thuc/thong-tin` | `/api/v1/xac-thuc/thong-tin` | Bearer Token | Giải mã Token, trả về thông tin user & vai trò |
| `POST` | `/api/v1/xac-thuc/dang-xuat` | `/api/v1/xac-thuc/dang-xuat` | Bearer Token | Đăng xuất, hủy bỏ token phiên làm việc |
| `PUT` | `/api/v1/xac-thuc/doi-mat-khau` | `/api/v1/xac-thuc/doi-mat-khau` | Bearer Token | Người dùng tự đổi mật khẩu cá nhân |
| `PUT` | `/api/v1/xac-thuc/ho-so` | `/api/v1/xac-thuc/ho-so` | Bearer Token | Cập nhật hồ sơ cá nhân & kinh nghiệm bác sĩ |
| `POST` | `/api/v1/xac-thuc/upload-avatar` | `/api/v1/xac-thuc/upload-avatar` | Bearer Token | Tải lên ảnh đại diện Avatar (Base64) |
| `GET` | `/api/v1/tai-khoan` | `/api/v1/tai-khoan` | `ADMIN` | Lấy danh sách toàn bộ tài khoản người dùng |
| `PATCH`| `/api/v1/tai-khoan/{id}/trang-thai`| `/api/v1/tai-khoan/{id}/trang-thai`| `ADMIN` | Khóa / Mở khóa tài khoản (Bảo vệ tài khoản Admin) |
| `DELETE`| `/api/v1/tai-khoan/{id}` | `/api/v1/tai-khoan/{id}` | `ADMIN` | Xóa tài khoản người dùng khỏi hệ thống |
| `GET` | `/api/v1/chuyen-khoa` | `/api/v1/chuyen-khoa` | Public | Tra cứu danh sách các chuyên khoa phòng khám |
| `POST` | `/api/v1/chuyen-khoa` | `/api/v1/chuyen-khoa` | `ADMIN` | Thêm chuyên khoa mới |
| `PUT` | `/api/v1/chuyen-khoa/{id}` | `/api/v1/chuyen-khoa/{id}` | `ADMIN` | Chỉnh sửa tên, mã khoa, trạng thái chuyên khoa |
| `DELETE`| `/api/v1/chuyen-khoa/{id}` | `/api/v1/chuyen-khoa/{id}` | `ADMIN` | Xóa chuyên khoa |
| `GET` | `/api/v1/bac-si` | `/api/v1/bac-si` | Public | Lấy danh sách đội ngũ bác sĩ theo chuyên khoa |
| `GET` | `/api/v1/bac-si/{id}` | `/api/v1/bac-si/{id}` | Public | Chi tiết hồ sơ bác sĩ & đơn giá khám (cho Billing) |
| `POST` | `/api/v1/bac-si` | `/api/v1/bac-si` | `ADMIN` | Thêm bác sĩ mới và khởi tạo tài khoản |
| `PUT` | `/api/v1/bac-si/{id}` | `/api/v1/bac-si/{id}` | `ADMIN` | Cập nhật thông tin bác sĩ |
| `DELETE`| `/api/v1/bac-si/{id}` | `/api/v1/bac-si/{id}` | `ADMIN` | Xóa bác sĩ |
| `GET` | `/api/v1/bac-si/bang-gia-kham` | `/api/v1/bac-si/bang-gia-kham` | Public | Tổng hợp biểu phí niêm yết, min/max/trung bình |
| `PUT` | `/api/v1/bac-si/{id}/gia-kham`| `/api/v1/bac-si/{id}/gia-kham`| `ADMIN` | Cập nhật nhanh đơn giá khám của bác sĩ |
| `GET` | `/api/v1/bac-si/lich-truc/danh-sach`| `/api/v1/bac-si/lich-truc/danh-sach`| Public | Tra cứu lịch trực bác sĩ |
| `POST` | `/api/v1/bac-si/lich-truc` | `/api/v1/bac-si/lich-truc` | `BAC_SI` | Đăng ký ca trực mới (Quy định tối đa 1 ca/ngày) |
| `PUT` | `/api/v1/bac-si/lich-truc/{id}/duyet`| `/api/v1/bac-si/lich-truc/{id}/duyet`| `ADMIN` | Admin duyệt ca trực (`CHO_DUYET` -> `HOAT_DONG`) |
| `GET` | `/api/v1/bac-si/{id}/kiem-tra-truc`| `/api/v1/bac-si/{id}/kiem-tra-truc`| Public | Kiểm tra bác sĩ có trực vào ngày cụ thể hay không |

---

## 🔄 IV. TÍCH HỢP LIÊN DỊCH VỤ (INTER-SERVICE INTEGRATION)

1. **Phối hợp với Người 2 (`appointment-service` - Lịch hẹn & Bệnh nhân):**
   - Cung cấp API `GET /api/v1/bac-si/{id}/kiem-tra-truc?ngay=YYYY-MM-DD` để Người 2 đối soát: Chỉ cho phép bệnh nhân chọn đặt lịch vào những ngày bác sĩ có ca trực **đã được Admin phê duyệt** (`HOAT_DONG`).
   - Cung cấp danh sách Bác sĩ và Chuyên khoa phục vụ bộ lọc liên hoàn 2 bước tại form đặt khám.

2. **Phối hợp với Người 3 (`clinical-service` - Dịch vụ y tế & Cận lâm sàng):**
   - Xác thực tập trung JWT của Bác sĩ và Kỹ thuật viên qua Gateway, inject `X-User-Id` và `X-User-Role` để Người 3 phân quyền kê chỉ định và trả kết quả cận lâm sàng.

3. **Phối hợp với Người 4 (`billing-service` - Hóa đơn & Viện phí):**
   - Cung cấp API `GET /api/v1/bac-si/{id}` trả về thông tin `gia_kham` của bác sĩ để dịch vụ thanh toán tự động cộng tiền khám vào hóa đơn viện phí cùng với các dịch vụ cận lâm sàng.

---

## 🖥️ V. HƯỚNG DẪN KỊCH BẢN DEMO TRÊN GIAO DIỆN WEB

Mở trình duyệt truy cập: **`http://localhost:8000`**

### 1. Tài khoản thử nghiệm có sẵn:
- **Quản trị viên (Admin):** `admin@phongkham.vn` / `123456` (hoặc `Admin@123`)
- **Bác sĩ Chuyên khoa:** `bacsi@phongkham.vn` / `123456`
- **Bệnh nhân:** `benhnhan@gmail.com` / `123456`

### 2. Các bước Demo cụ thể:

#### 📍 Bước 1: Màn hình Đăng nhập & Xác thực (`/dang-nhap`)
- Đăng nhập với tài khoản `admin@phongkham.vn`.
- Trình bày cơ chế cấp mã Bearer Token JWT, giải mã danh tính và điều hướng vào giao diện tổng thể.

#### 📍 Bước 2: Tab "Thống Kê & Giám Sát" (`tab-admin-giam-sat`)
- Menu bên trái ➔ **Tổng Quan** ➔ **Thống Kê & Giám Sát**.
- Trình bày bảng kiểm tra sức khỏe 4 microservices theo thời gian thực (Liveness, độ trễ `ms`, trạng thái `Online`).

#### 📍 Bước 3: Tab "Bác Sĩ & Chuyên Khoa" (`tab-admin-bac-si`)
- Menu bên trái ➔ **Quản Trị Hệ Thống** ➔ **Bác Sĩ & Chuyên Khoa**.
- **Chuyên khoa:** Bấm nút *"Thêm Chuyên Khoa"* mới ➔ Bấm nút *"Sửa"* để cập nhật tên hoặc bật/tắt hoạt động.
- **Bác sĩ:** Thêm bác sĩ mới, gán chuyên khoa, học vị, phòng làm việc và thiết lập giá khám ban đầu.
- **Phê duyệt ca trực:** Kéo xuống mục *"Danh sách ca trực chờ duyệt"*, bấm nút **"Duyệt ca trực"** để kích hoạt ca trực từ `CHO_DUYET` sang `HOAT_DONG`.

#### 📍 Bước 4: Tab "Bảng Giá Khám Bệnh" (`tab-bang-gia-kham`)
- Menu bên trái ➔ **Lịch Hẹn & Bệnh Nhân** ➔ **Bảng Giá Khám Bệnh**.
- Giới thiệu 3 thẻ KPI: Giá khám thấp nhất, Giá khám cao nhất, Giá khám bình quân toàn viện.
- Sử dụng bộ lọc theo chuyên khoa và phân khúc (Tiêu Chuẩn, Chuyên Gia, VIP).
- Thử bấm nút *"Chỉnh sửa giá"* của 1 bác sĩ ➔ Đổi giá ➔ Hệ thống tự động cập nhật ngay trên giao diện và tính lại KPI.
- Bấm nút *"In Bảng Giá"* để xuất phiếu biểu phí niêm yết chuẩn y tế.

#### 📍 Bước 5: Tab "Quản Trị Tài Khoản" (`tab-admin-tai-khoan`)
- Menu bên trái ➔ **Quản Trị Hệ Thống** ➔ **Quản Trị Tài Khoản**.
- Xem danh sách người dùng, thử khóa hoặc mở khóa 1 tài khoản bệnh nhân/bác sĩ.
- **Điểm cộng bảo mật:** Thử bấm khóa tài khoản Admin ➔ Hệ thống lập tức chặn lại và báo *"Không thể khóa tài khoản Quản trị viên tối cao!"*.

#### 📍 Bước 6: Modal "Hồ Sơ Cá Nhân & Upload Avatar" (Góc dưới Sidebar)
- Nhấn trực tiếp vào tên hoặc Avatar của người dùng ở góc dưới cùng bên trái thanh Sidebar.
- Bấm *"Chọn ảnh mới"* để tải lên avatar (Base64 Data URL).
- Cập nhật số điện thoại, địa chỉ (với bác sĩ: cập nhật học vị, kinh nghiệm) ➔ Bấm *"Lưu Thay Đổi"*.
- Quan sát thông tin và avatar ở thanh Sidebar cập nhật tức thì mà không bị chớp hay mất menu.

#### 📍 Bước 7: Luồng nghiệp vụ Quy định Ca trực Bác sĩ
- **Bác sĩ:** Đăng nhập `bacsi@phongkham.vn`, bấm nút *"Đăng Ký & Lịch Trực Của Tôi"*, đăng ký ca sáng Thứ Ba. Thử đăng ký tiếp ca chiều Thứ Ba ➔ Hệ thống chặn với thông báo *"Bác sĩ chỉ được đăng ký tối đa 1 ca trực trong 1 ngày"*. Ca trực ở trạng thái `Chờ duyệt`.
- **Admin:** Đăng nhập `admin@phongkham.vn` ➔ Tab *Bác Sĩ & Chuyên Khoa* ➔ Bấm nút **"Duyệt ca trực"**.
- **Bệnh nhân:** Đăng nhập `benhnhan@gmail.com` ➔ Vào *Tra Cứu & Đặt Lịch* ➔ Chọn bác sĩ đó vào Thứ Ba ➔ Khung giờ trực đã được duyệt hiển thị để đặt hẹn khám bệnh thành công.
