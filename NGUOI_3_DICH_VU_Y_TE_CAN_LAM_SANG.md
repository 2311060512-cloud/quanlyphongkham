# BÁO CÁO PHÂN HỆ 03: KHÁM CHUYÊN MÔN & DỊCH VỤ CẬN LÂM SÀNG
**Thành viên:** Người 3(Khải)
**Microservice:** `clinical-service` (Port: `8003`, Database: `db_dich_vu_y_te`)

---

## 1. Cơ sở Dữ liệu Phụ trách
- `dich_vu`: Danh mục các gói xét nghiệm, siêu âm, chụp X-Quang, thủ thuật kèm bảng giá.
- `su_dung_dich_vu`: Các dịch vụ kỹ thuật được chỉ định theo từng ca khám (`lich_hen_id`), trạng thái và kết quả.
- `ho_so_kham_benh`: Chẩn đoán xác định, triệu chứng, đơn thuốc xuất viện và lời dặn của Bác sĩ.

---

## 2. Danh sách RESTful APIs
1. `GET /api/dich-vu`: Lấy danh sách dịch vụ kỹ thuật (hỗ trợ lọc theo loại và tìm kiếm).
2. `POST /api/dich-vu`: Thêm dịch vụ y tế mới.
3. `PUT /api/dich-vu/{id}`: Cập nhật thông tin và đơn giá dịch vụ.
4. `PATCH /api/dich-vu/{id}/toggle-status`: Bật / tắt trạng thái hoạt động của dịch vụ.
5. `POST /api/kham-benh/chi-dinh`: Bác sĩ kê 1 hoặc nhiều xét nghiệm / siêu âm cho ca khám (`lich_hen_id`).
6. `GET /api/kham-benh/{lichHenId}/dich-vu`: Lấy danh sách dịch vụ và tổng tiền CLS của ca khám (cung cấp cho Người 4 tính viện phí).
7. `DELETE /api/kham-benh/chi-dinh/{id}`: Hủy chỉ định dịch vụ nếu chưa có kết quả.
8. `GET /api/can-lam-sang/danh-sach-cho`: Kỹ thuật viên xem danh sách bệnh nhân đang chờ làm CLS.
9. `PUT /api/can-lam-sang/{id}/ket-qua`: Kỹ thuật viên nhập kết quả chỉ số & kết luận siêu âm / xét nghiệm.
10. `POST /api/kham-benh/{lichHenId}/hoan-thanh`: Bác sĩ chẩn đoán, kê đơn thuốc và hoàn tất ca khám (`HOAN_THANH`).
11. `GET /api/kham-benh/{lichHenId}/ho-so`: Xem hồ sơ bệnh án và đơn thuốc ca khám.

---

## 3. Điểm tích hợp với các thành viên khác
- **Người 1 (Xác thực & Bác sĩ):** Xác thực Token Bác sĩ & Kỹ thuật viên.
- **Người 2 (Bệnh nhân & Lịch hẹn):** Nhận `lich_hen_id` của ca khám để tiến hành chỉ định CLS và khám bệnh.
- **Người 4 (Thu ngân & Viện phí):** Cung cấp API `GET /api/kham-benh/{lichHenId}/dich-vu` để Người 4 tự động tính tổng tiền dịch vụ cận lâm sàng vào hóa đơn viện phí.
