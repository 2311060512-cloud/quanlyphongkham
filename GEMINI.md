# GEMINI & AI ASSISTANT RULES FOR CLINIC MANAGEMENT PROJECT

Tất cả AI Agent khi làm việc trên repository này phải tuân thủ các quy tắc sau:

1. **Kiến trúc Modular Monolith 3 tầng**:
   - `Controller` $\to$ `Service` $\to$ `Repository`.
   - Các logic nghiệp vụ, tính toán tiền, kiểm tra chống trùng lịch, và phân quyền tài nguyên PHẢI nằm ở tầng `Service`.

2. **Quy tắc phân quyền Bác sĩ (Resource Authorization)**:
   - Khi thực hiện xác nhận lịch hẹn (`xacNhanLichHen`), bắt đầu khám (`batDauKham`), hoặc hoàn tất khám (`hoanThanhKham`):
   - **CHỈ ADMIN** hoặc **ĐÚNG BÁC SĨ ĐƯỢC CHỈ ĐỊNH CHO CA ĐÓ** (`bac_si_id`) mới có quyền thực hiện.
   - Bác sĩ khác không được thao tác trên lịch hẹn của đồng nghiệp.
   - Trên giao diện Frontend: Ẩn các nút hành động của ca khám thuộc về bác sĩ khác và hiển thị nhãn "BS khác phụ trách".

3. **Quy tắc Bệnh nhân**:
   - Bệnh nhân chỉ xem và hủy được lịch hẹn của chính mình khi trạng thái là `CHO_XAC_NHAN` hoặc `DA_XAC_NHAN`.
