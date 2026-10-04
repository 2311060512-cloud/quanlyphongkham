# BÁO CÁO PHÂN HỆ 03: KHÁM CHUYÊN MÔN & DỊCH VỤ CẬN LÂM SÀNG
**Thành viên phụ trách:** 👤 Người 3 (Khải)  
**Microservice:** `clinical-service` (Port: `8003`, Database: `db_dich_vu_y_te`)  
**Gateway Proxy:** `api-gateway` (Port: `8000`)

---

## 1. Cơ sở Dữ liệu Phụ trách (`db_dich_vu_y_te`)
1. **`dich_vu`**: Danh mục các gói xét nghiệm, chụp X-Quang, siêu âm, nội soi, thủ thuật kèm đơn giá niêm yết và trạng thái hoạt động.
2. **`su_dung_dich_vu`**: Các dịch vụ cận lâm sàng được bác sĩ kê chỉ định theo ca khám (`lich_hen_id`), ghi nhận trạng thái thực hiện (`CHO_THUC_HIEN`, `DA_CO_KET_QUA`, `DA_HUY`), chỉ số kết quả, kết luận hình ảnh và file đính kèm.
3. **`ho_so_kham_benh`**: Hồ sơ bệnh án điện tử, lưu trữ triệu chứng lâm sàng, chẩn đoán xác định, đơn thuốc xuất viện (JSON mảng tên thuốc, liều lượng, cách dùng), lời dặn bác sĩ và ngày hẹn tái khám.

---

## 2. Danh sách 11 RESTful APIs Hoàn Thiện

| STT | Phương thức | Endpoint Gateway (`Port 8000`) | Phân quyền | Mô tả chức năng |
|:---:|:---:|:---|:---:|:---|
| 1 | `GET` | `/api/v1/dich-vu` | Công khai | Tra cứu danh mục dịch vụ cận lâm sàng (hỗ trợ lọc `loai_dich_vu` và tìm kiếm `tu_khoa`). |
| 2 | `POST` | `/api/v1/dich-vu` | `ADMIN` | Thêm mới dịch vụ y tế / xét nghiệm / chẩn đoán hình ảnh. |
| 3 | `PUT` | `/api/v1/dich-vu/{id}` | `ADMIN` | Cập nhật thông tin và điều chỉnh đơn giá dịch vụ. |
| 4 | `PATCH` | `/api/v1/dich-vu/{id}/toggle-status` | `ADMIN` | Bật / tắt trạng thái hoạt động của dịch vụ kỹ thuật. |
| 5 | `POST` | `/api/v1/kham-benh/chi-dinh` | `BAC_SI`, `ADMIN` | Bác sĩ kê chỉ định 1 hoặc nhiều xét nghiệm / siêu âm cho ca khám (`lich_hen_id`). |
| 6 | `GET` | `/api/v1/kham-benh/{lichHenId}/dich-vu` | Tất cả | Lấy danh sách dịch vụ và tổng tiền CLS của ca khám (cung cấp cho Người 4 tính viện phí). |
| 7 | `DELETE` | `/api/v1/kham-benh/chi-dinh/{id}` | `BAC_SI`, `ADMIN` | Hủy chỉ định dịch vụ nếu chưa có kết quả (`CHO_THUC_HIEN`). |
| 8 | `GET` | `/api/v1/can-lam-sang/danh-sach-cho` | `BAC_SI`, `ADMIN` | Kỹ thuật viên xem danh sách bệnh nhân đang trong hàng đợi chờ làm CLS. |
| 9 | `PUT` | `/api/v1/can-lam-sang/{id}/ket-qua` | `BAC_SI`, `ADMIN` | Kỹ thuật viên nhập kết quả chỉ số đo lường & kết luận siêu âm / xét nghiệm / tệp kết quả. |
| 10 | `POST` | `/api/v1/kham-benh/{lichHenId}/hoan-thanh` | `BAC_SI`, `ADMIN` | Bác sĩ chẩn đoán, kê đơn thuốc và hoàn tất ca khám (`HOAN_THANH`). |
| 11 | `GET` | `/api/v1/kham-benh/{lichHenId}/ho-so` | Tất cả | Xem hồ sơ bệnh án và đơn thuốc điện tử của ca khám. |

---

## 3. Điểm Tích Hợp Liên Dịch Vụ
- **Người 1 (Xác thực & Bác sĩ):** Xác thực JWT Token Bác sĩ & Quản trị viên khi thực hiện kê chỉ định và chẩn đoán.
- **Người 2 (Bệnh nhân & Lịch hẹn):** Nhận `lich_hen_id` của ca khám để tiến hành chỉ định CLS và tự động đồng bộ trạng thái ca khám sang `HOAN_THANH`.
- **Người 4 (Thu ngân & Viện phí):** Cung cấp API `GET /api/v1/dich-vu/lich-hen/{lichHenId}` trả về danh sách dịch vụ kèm `don_gia * so_luong` để `billing-service` tự động tổng hợp tiền cận lâm sàng vào hóa đơn viện phí.

---

## 4. Hướng Dẫn Vận Hành & Kiểm Thử
1. Nạp Database & Migration:
   ```powershell
   .\chay-migrations.bat
   ```
2. Khởi động hệ thống:
   ```powershell
   .\start-he-thong.bat
   ```
3. Đăng nhập kiểm thử chức năng lâm sàng tại Dashboard: `http://127.0.0.1:8000` (Tài khoản Bác sĩ: `bstuan` / `123456`).

