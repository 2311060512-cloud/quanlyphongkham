# SYSTEM RULES & BUSINESS CONSTRAINTS (QUẢN LÝ PHÒNG KHÁM ĐA KHOA)

## 1. Kiến trúc tổng thể (Architecture)
- Dự án sử dụng **Laravel 12 Modular Monolith** chia theo từng module trong `app/Modules/`:
  - `TaiKhoan`, `ChuyenKhoa`, `BacSi`, `BenhNhan`, `LichHen`, `DichVu`, `HoaDon`.
- Mỗi module tuân thủ kiến trúc 3 tầng: `Controller` -> `Service` -> `Repository` (kế thừa `BaseRepository`).
- Tầng `Controller` chỉ làm nhiệm vụ validate input và return JSON qua `ApiResponseTrait`.
- Toàn bộ logic nghiệp vụ, transaction, và **kiểm tra phân quyền tài nguyên (Authorization)** bắt buộc phải đặt trong tầng `Service`.

## 2. Quy tắc phân quyền & Logic nghiệp vụ y tế (CRITICAL RULES)

### A. Phân quyền Bác sĩ và Ca khám
- Mỗi lịch hẹn (`LichHen`) được gắn với một Bác sĩ (`bac_si_id`).
- **Chỉ có 2 đối tượng được phép Xác nhận (`DA_XAC_NHAN`), Bắt đầu khám (`DANG_KHAM`), hoặc Hoàn thành khám (`HOAN_THANH`):**
  1. **Quản trị viên (`ADMIN`)**: Toàn quyền điều phối.
  2. **Chính Bác sĩ phụ trách ca đó**: So khớp `user->id` với `bac_si->tai_khoan_id` tương ứng `bac_si_id`.
- **Tuyệt đối cấm:** Bác sĩ khác không được phép xác nhận hoặc can thiệp vào ca khám của đồng nghiệp. Không chỉ dựa vào route middleware `middleware('role:ADMIN,BAC_SI')`, Service phải chủ động kiểm tra bằng `kiemTraQuyenBacSiHoacAdmin($lichHen, $user)`.

### B. Phân quyền Bệnh nhân
- Bệnh nhân cần đăng nhập để xem lịch hẹn cá nhân.
- Bệnh nhân chỉ được phép HỦY lịch khám của chính mình và chỉ khi trạng thái là `CHO_XAC_NHAN` hoặc `DA_XAC_NHAN`.

### C. Quy chuẩn dữ liệu
- Mã lịch hẹn: Tăng dần theo thứ tự `LK0001`, `LK0002`...
- Bệnh nhân có Số CCCD (`so_cccd`).
- Bác sĩ có Ảnh chân dung (`hinh_anh`), Học vị, Chuyên khoa, Phòng khám, Giá khám.
- Chống trùng lịch: Không cho phép đặt trùng khung giờ của cùng 1 bác sĩ.
