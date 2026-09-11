<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Phòng Khám Đa Khoa DV</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; }
        .tab-active { border-bottom: 3px solid #0284c7; color: #0284c7; font-weight: 600; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3 cursor-pointer" onclick="switchTab('tab-dat-lich')">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-600 to-teal-500 flex items-center justify-center text-white text-xl shadow-md">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">PHÒNG KHÁM ĐA KHOA DV</h1>
                        <p class="text-xs text-slate-500 font-medium">Hệ Thống Quản Lý Y Tế Chuyên Nghiệp</p>
                    </div>
                </div>

                <!-- Auth Info / Buttons -->
                <div id="authContainer" class="flex items-center space-x-3">
                    <button onclick="openLoginModal()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-medium text-sm transition shadow-sm flex items-center">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng Nhập
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white border-t border-slate-100 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex space-x-8 text-sm text-slate-600 overflow-x-auto">
                <button onclick="switchTab('tab-dat-lich')" id="nav-tab-dat-lich" class="py-3 px-1 hover:text-sky-600 transition flex items-center tab-active">
                    <i class="fa-regular fa-calendar-plus mr-2"></i> Đặt Lịch Khám
                </button>
                <button onclick="switchTab('tab-lich-su')" id="nav-tab-lich-su" class="py-3 px-1 hover:text-sky-600 transition flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Lịch Sử Khám Bệnh
                </button>
                <button onclick="switchTab('tab-bac-si')" id="nav-tab-bac-si" class="py-3 px-1 hover:text-sky-600 transition flex items-center">
                    <i class="fa-solid fa-user-doctor mr-2"></i> Phòng Bác Sĩ
                </button>
                <button onclick="switchTab('tab-thu-ngan')" id="nav-tab-thu-ngan" class="py-3 px-1 hover:text-sky-600 transition flex items-center">
                    <i class="fa-solid fa-receipt mr-2"></i> Quầy Thu Ngân & Viện Phí
                </button>
                <button onclick="switchTab('tab-quan-tri')" id="nav-tab-quan-tri" class="py-3 px-1 hover:text-sky-600 transition flex items-center">
                    <i class="fa-solid fa-chart-pie mr-2"></i> Quản Trị & Báo Cáo
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">

        <!-- TAB 1: ĐẶT LỊCH KHÁM -->
        <div id="tab-dat-lich" class="tab-pane space-y-6">
            <div class="bg-gradient-to-r from-sky-700 to-teal-600 rounded-2xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <span class="bg-sky-500/30 text-sky-100 text-xs uppercase px-3 py-1 rounded-full font-semibold">Đăng ký khám trực tuyến</span>
                    <h2 class="text-2xl sm:text-3xl font-bold mt-2">Đặt Hẹn Bác Sĩ Nhanh Chóng</h2>
                    <p class="text-sky-100 text-sm mt-1 max-w-xl">Chủ động lựa chọn bác sĩ, khung giờ khám và chuyên khoa phù hợp mà không cần chờ đợi tại bệnh viện.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-center border border-white/20 min-w-[200px]">
                    <div class="text-3xl font-extrabold text-white" id="statDoctorCount">0</div>
                    <div class="text-xs text-sky-200 mt-1">Bác sĩ chuyên khoa sẵn sàng</div>
                </div>
            </div>

            <!-- Booking Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mr-2 text-sm font-bold">1</span>
                        Chọn Bác Sĩ & Chuyên Khoa
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Chuyên khoa khám</label>
                            <select id="bookSpecialty" onchange="filterDoctorsBySpecialty()" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                                <option value="">-- Tất cả chuyên khoa --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Bác sĩ phụ trách *</label>
                            <select id="bookDoctor" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none font-medium">
                                <option value="">-- Vui lòng chọn bác sĩ --</option>
                            </select>
                        </div>

                        <div class="p-4 bg-sky-50 rounded-xl border border-sky-100 text-xs text-sky-800 space-y-1">
                            <p><i class="fa-solid fa-circle-info text-sky-600 mr-1"></i> Giá khám và phòng khám của từng bác sĩ sẽ được hiển thị khi lựa chọn.</p>
                            <p><i class="fa-solid fa-shield-halved text-sky-600 mr-1"></i> Hệ thống tự động kiểm tra và ngăn ngừa việc trùng lịch khám.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mr-2 text-sm font-bold">2</span>
                        Chọn Thời Gian & Triệu Chứng
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Ngày khám *</label>
                                <input type="date" id="bookDate" class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Giờ khám *</label>
                                <select id="bookTime" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                                    <option value="08:00">08:00 Sáng</option>
                                    <option value="08:30">08:30 Sáng</option>
                                    <option value="09:00">09:00 Sáng</option>
                                    <option value="09:30">09:30 Sáng</option>
                                    <option value="10:00">10:00 Sáng</option>
                                    <option value="10:30">10:30 Sáng</option>
                                    <option value="13:30">13:30 Chiều</option>
                                    <option value="14:00">14:00 Chiều</option>
                                    <option value="14:30">14:30 Chiều</option>
                                    <option value="15:00">15:00 Chiều</option>
                                    <option value="15:30">15:30 Chiều</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả triệu chứng / Lý do khám</label>
                            <textarea id="bookSymptoms" rows="3" placeholder="Ví dụ: Đau đầu, sốt nhẹ 2 ngày nay..." class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-sky-500 outline-none"></textarea>
                        </div>

                        <button onclick="handleBookAppointment()" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-sm shadow-md transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Xác Nhận Đặt Lịch Khám</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: LỊCH SỬ KHÁM BỆNH -->
        <div id="tab-lich-su" class="tab-pane hidden space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Lịch Sử Khám Của Bệnh Nhân</h2>
                    <p class="text-sm text-slate-500">Xem tiến trình các cuộc hẹn, kết quả chẩn đoán và hóa đơn viện phí</p>
                </div>
                <button onclick="loadMyHistory()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-sm font-semibold flex items-center">
                    <i class="fa-solid fa-arrows-rotate mr-2"></i> Làm Mới
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                <th class="p-4">Mã Lịch Hẹn</th>
                                <th class="p-4">Bác Sĩ Khám</th>
                                <th class="p-4">Thời Gian Khám</th>
                                <th class="p-4">Triệu Chứng</th>
                                <th class="p-4">Chẩn Đoán / Kết Quả</th>
                                <th class="p-4">Trạng Thái</th>
                                <th class="p-4 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody" class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">Vui lòng đăng nhập để xem lịch sử khám của bạn.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: PHÒNG BÁC SĨ (DÀNH CHO BÁC SĨ) -->
        <div id="tab-bac-si" class="tab-pane hidden space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Bàn Làm Việc Bác Sĩ Chuyên Khoa</h2>
                    <p class="text-sm text-slate-500">Tiếp nhận bệnh nhân, chẩn đoán bệnh và chỉ định dịch vụ cận lâm sàng</p>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="date" id="doctorDateFilter" onchange="loadDoctorAppointments()" class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm outline-none">
                    <button onclick="loadDoctorAppointments()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-sm font-semibold">
                        <i class="fa-solid fa-filter mr-1"></i> Lọc Ngày
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                <th class="p-4">Giờ Hẹn</th>
                                <th class="p-4">Bệnh Nhân</th>
                                <th class="p-4">Số Điện Thoại</th>
                                <th class="p-4">Triệu Chứng</th>
                                <th class="p-4">Trạng Thái</th>
                                <th class="p-4 text-center">Chức Năng Bác Sĩ</th>
                            </tr>
                        </thead>
                        <tbody id="doctorTableBody" class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">Cần đăng nhập tài khoản Bác sĩ để thực hiện khám bệnh.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: QUẦY THU NGÂN & VIỆN PHÍ -->
        <div id="tab-thu-ngan" class="tab-pane hidden space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Thu Ngân & Quản Lý Viện Phí</h2>
                    <p class="text-sm text-slate-500">Danh sách hóa đơn, tiền khám bác sĩ và các chi phí cận lâm sàng phát sinh</p>
                </div>
                <button onclick="loadBillingList()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold flex items-center">
                    <i class="fa-solid fa-arrows-rotate mr-2"></i> Tải Lại Hóa Đơn
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                <th class="p-4">Mã Hóa Đơn</th>
                                <th class="p-4">Bệnh Nhân</th>
                                <th class="p-4">Tiền Khám</th>
                                <th class="p-4">Tiền Dịch Vụ Cận Lâm Sàng</th>
                                <th class="p-4 font-bold">Tổng Tiền Viện Phí</th>
                                <th class="p-4">Trạng Thái</th>
                                <th class="p-4 text-center">Thu Viện Phí</th>
                            </tr>
                        </thead>
                        <tbody id="billingTableBody" class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">Đang tải dữ liệu hóa đơn viện phí...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 5: QUẢN TRỊ & BÁO CÁO -->
        <div id="tab-quan-tri" class="tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Tổng Doanh Thu Viện Phí</div>
                        <div class="text-2xl font-black text-emerald-600 mt-1" id="statRevenue">0 đ</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Hóa Đơn Đã Thu</div>
                        <div class="text-2xl font-black text-sky-600 mt-1" id="statPaidInvoices">0</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Hóa Đơn Chờ Thu</div>
                        <div class="text-2xl font-black text-amber-600 mt-1" id="statPendingInvoices">0</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Doctor Management -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Danh Sách Bác Sĩ Phòng Khám</h3>
                    <button onclick="openAddDoctorModal()" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-semibold">
                        <i class="fa-solid fa-plus mr-1"></i> Thêm Bác Sĩ
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-semibold border-b">
                                <th class="p-3">Mã BS</th>
                                <th class="p-3">Họ và Tên</th>
                                <th class="p-3">Chuyên Khoa</th>
                                <th class="p-3">Phòng Khám</th>
                                <th class="p-3">Giá Khám</th>
                                <th class="p-3">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody id="adminDoctorsTable" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- Modal Đăng nhập / Đăng ký -->
    <div id="loginModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
            <button onclick="closeModal('loginModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-xl font-bold text-slate-800 mb-1">Đăng Nhập Hệ Thống</h3>
            <p class="text-xs text-slate-500 mb-6">Sử dụng tài khoản Quản trị viên, Bác sĩ hoặc Bệnh nhân</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tên đăng nhập</label>
                    <input type="text" id="loginUsername" placeholder="admin / bstuan / benhnhan" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mật khẩu</label>
                    <input type="password" id="loginPassword" placeholder="••••••" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                </div>
                <button onclick="handleLogin()" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold text-sm shadow-md transition">
                    Đăng Nhập
                </button>
                <div class="text-center text-xs text-slate-500">
                    Gợi ý tài khoản demo: <br>
                    <strong>admin</strong> / Admin@123 | <strong>bstuan</strong> / 123456 | <strong>benhnhan</strong> / 123456
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chỉ Định Dịch Vụ Của Bác Sĩ -->
    <div id="serviceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative">
            <button onclick="closeModal('serviceModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Chỉ Định Dịch Vụ Cận Lâm Sàng</h3>
            <p class="text-xs text-slate-500 mb-4" id="serviceModalSubtitle">Lịch hẹn: #0</p>

            <input type="hidden" id="currentAppointmentId">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Chọn Dịch Vụ Y Tế</label>
                    <select id="modalSelectService" class="w-full border border-slate-300 rounded-xl p-2.5 text-sm outline-none font-medium">
                        <!-- Loaded via JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ghi chú hoặc yêu cầu lâm sàng</label>
                    <textarea id="modalServiceNotes" rows="2" class="w-full border border-slate-300 rounded-xl p-2.5 text-sm outline-none" placeholder="Kiểm tra chức năng gan thận..."></textarea>
                </div>
                <button onclick="submitAddService()" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-sm">
                    Lưu Chỉ Định
                </button>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api/v1';
        let authToken = localStorage.getItem('token') || '';
        let currentUser = JSON.parse(localStorage.getItem('user') || 'null');
        let allDoctors = [];
        let allSpecialties = [];
        let allServices = [];

        // ==========================================
        // UI & TAB NAVIGATION
        // ==========================================
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('[id^="nav-tab-"]').forEach(el => el.classList.remove('tab-active'));
            const activeNav = document.getElementById('nav-' + tabId);
            if (activeNav) activeNav.classList.add('tab-active');

            if (tabId === 'tab-lich-su') loadMyHistory();
            if (tabId === 'tab-bac-si') loadDoctorAppointments();
            if (tabId === 'tab-thu-ngan') loadBillingList();
            if (tabId === 'tab-quan-tri') loadAdminStats();
        }

        function checkAuthUI() {
            const container = document.getElementById('authContainer');
            if (authToken && currentUser) {
                container.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <div class="text-sm font-bold text-slate-800">${currentUser.ho_ten}</div>
                            <span class="text-xs bg-sky-100 text-sky-700 px-2 py-0.5 rounded font-semibold">${currentUser.ten_vai_tro || currentUser.vai_tro}</span>
                        </div>
                        <button onclick="handleLogout()" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Thoát
                        </button>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <button onclick="openLoginModal()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-medium text-sm transition shadow-sm flex items-center">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng Nhập
                    </button>
                `;
            }
        }

        // ==========================================
        // DATA INITIALIZATION
        // ==========================================
        async function initData() {
            document.getElementById('bookDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('doctorDateFilter').value = new Date().toISOString().split('T')[0];

            try {
                // 1. Load specialties
                const resSpec = await fetch(`${API_BASE}/bac-si/chuyen-khoa`);
                const jsonSpec = await resSpec.json();
                if (jsonSpec.thanh_cong) {
                    allSpecialties = jsonSpec.du_lieu;
                    const sel = document.getElementById('bookSpecialty');
                    sel.innerHTML = '<option value="">-- Tất cả chuyên khoa --</option>';
                    allSpecialties.forEach(s => {
                        sel.innerHTML += `<option value="${s.id}">${s.ten_khoa}</option>`;
                    });
                }

                // 2. Load doctors
                const resDoc = await fetch(`${API_BASE}/bac-si`);
                const jsonDoc = await resDoc.json();
                if (jsonDoc.thanh_cong) {
                    allDoctors = jsonDoc.du_lieu;
                    document.getElementById('statDoctorCount').innerText = allDoctors.length;
                    filterDoctorsBySpecialty();
                    loadAdminDoctors();
                }

                // 3. Load services
                const resSer = await fetch(`${API_BASE}/dich-vu`);
                const jsonSer = await resSer.json();
                if (jsonSer.thanh_cong) {
                    allServices = jsonSer.du_lieu;
                    const selSer = document.getElementById('modalSelectService');
                    selSer.innerHTML = '';
                    allServices.forEach(dv => {
                        selSer.innerHTML += `<option value="${dv.id}">${dv.ten_dich_vu} - ${Number(dv.don_gia).toLocaleString()} đ</option>`;
                    });
                }
            } catch (err) {
                console.error("Lỗi tải dữ liệu khởi tạo:", err);
            }
        }

        function filterDoctorsBySpecialty() {
            const specId = document.getElementById('bookSpecialty').value;
            const docSelect = document.getElementById('bookDoctor');
            docSelect.innerHTML = '<option value="">-- Vui lòng chọn bác sĩ --</option>';

            const filtered = specId ? allDoctors.filter(d => d.chuyen_khoa_id == specId) : allDoctors;
            filtered.forEach(d => {
                docSelect.innerHTML += `
                    <option value="${d.id}">
                        ${d.ho_ten} (${d.chuyen_khoa?.ten_khoa || 'Chuyên khoa'}) - Giá: ${Number(d.gia_kham).toLocaleString()} đ - ${d.phong_kham}
                    </option>
                `;
            });
        }

        // ==========================================
        // BOOKING APPOINTMENT
        // ==========================================
        async function handleBookAppointment() {
            if (!authToken) {
                Swal.fire('Yêu cầu đăng nhập', 'Vui lòng đăng nhập tài khoản bệnh nhân để đặt lịch khám!', 'warning');
                openLoginModal();
                return;
            }

            const docId = document.getElementById('bookDoctor').value;
            const date = document.getElementById('bookDate').value;
            const time = document.getElementById('bookTime').value;
            const symptoms = document.getElementById('bookSymptoms').value;

            if (!docId || !date || !time) {
                Swal.fire('Lỗi', 'Vui lòng chọn đầy đủ Bác sĩ, Ngày khám và Giờ khám!', 'error');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/lich-hen/dat-lich`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({
                        bac_si_id: docId,
                        ngay_kham: date,
                        gio_kham: time,
                        trieu_chung: symptoms || 'Khám sức khỏe'
                    })
                });

                const json = await res.json();
                if (json.thanh_cong) {
                    Swal.fire({
                        title: 'Đặt Lịch Thành Công!',
                        html: `Mã lịch hẹn của bạn là: <strong>${json.du_lieu.ma_lich_hen}</strong><br>Vui lòng đến đúng giờ để được tiếp đón chu đáo!`,
                        icon: 'success'
                    });
                    switchTab('tab-lich-su');
                } else {
                    let errMsg = json.thong_bao;
                    if (json.chi_tiet_loi?.gio_kham) {
                        errMsg = json.chi_tiet_loi.gio_kham[0];
                    }
                    Swal.fire('Đặt Lịch Thất Bại', errMsg, 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối đến máy chủ', 'error');
            }
        }

        // ==========================================
        // HISTORY TAB
        // ==========================================
        async function loadMyHistory() {
            if (!authToken) return;
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-slate-400">Đang tải lịch sử khám...</td></tr>';

            try {
                const res = await fetch(`${API_BASE}/lich-hen/lich-su-cua-toi`, {
                    headers: { 'Authorization': `Bearer ${authToken}` }
                });
                const json = await res.json();
                if (json.thanh_cong) {
                    if (json.du_lieu.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-slate-400">Bạn chưa có lịch hẹn khám nào.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = '';
                    json.du_lieu.forEach(lh => {
                        let statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Chờ xác nhận</span>';
                        if (lh.trang_thai === 'DA_XAC_NHAN') statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">Đã xác nhận</span>';
                        if (lh.trang_thai === 'DANG_KHAM') statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Đang khám</span>';
                        if (lh.trang_thai === 'HOAN_THANH') statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Hoàn thành</span>';
                        if (lh.trang_thai === 'DA_HUY') statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Đã hủy</span>';

                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-mono font-bold text-sky-700">${lh.ma_lich_hen}</td>
                                <td class="p-4">
                                    <div class="font-bold">${lh.bac_si?.ho_ten || 'BS'}</div>
                                    <div class="text-xs text-slate-400">${lh.bac_si?.chuyen_khoa?.ten_khoa || ''}</div>
                                </td>
                                <td class="p-4">${lh.ngay_kham} - <strong>${lh.gio_kham}</strong></td>
                                <td class="p-4 text-slate-600">${lh.trieu_chung || '-'}</td>
                                <td class="p-4 font-medium text-slate-800">${lh.chuan_doan || 'Chưa có kết quả'}</td>
                                <td class="p-4">${statusBadge}</td>
                                <td class="p-4 text-center">
                                    ${lh.trang_thai === 'CHO_XAC_NHAN' ? `
                                        <button onclick="cancelAppointment(${lh.id})" class="px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-xs font-semibold border border-rose-200">
                                            Hủy lịch
                                        </button>
                                    ` : '-'}
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-rose-500">Lỗi tải dữ liệu lịch sử khám.</td></tr>';
            }
        }

        async function cancelAppointment(id) {
            const confirm = await Swal.fire({
                title: 'Hủy lịch khám?',
                text: 'Bạn có chắc chắn muốn hủy cuộc hẹn này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý hủy',
                cancelButtonText: 'Quay lại'
            });

            if (confirm.isConfirmed) {
                const res = await fetch(`${API_BASE}/lich-hen/${id}/huy`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${authToken}` }
                });
                const json = await res.json();
                if (json.thanh_cong) {
                    Swal.fire('Thành công', 'Đã hủy lịch hẹn', 'success');
                    loadMyHistory();
                } else {
                    Swal.fire('Lỗi', json.thong_bao, 'error');
                }
            }
        }

        // ==========================================
        // DOCTOR WORKSPACE TAB
        // ==========================================
        async function loadDoctorAppointments() {
            if (!authToken) return;
            const date = document.getElementById('doctorDateFilter').value;
            const tbody = document.getElementById('doctorTableBody');
            tbody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-slate-400">Đang tải danh sách lịch khám của bác sĩ...</td></tr>';

            try {
                const res = await fetch(`${API_BASE}/lich-hen/lich-kham-bac-si?ngay_kham=${date}`, {
                    headers: { 'Authorization': `Bearer ${authToken}` }
                });
                const json = await res.json();
                if (json.thanh_cong) {
                    if (json.du_lieu.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-slate-400">Không có bệnh nhân đặt lịch trong ngày này.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = '';
                    json.du_lieu.forEach(lh => {
                        let actions = '';
                        if (lh.trang_thai === 'CHO_XAC_NHAN') {
                            actions = `<button onclick="updateAppointmentStatus(${lh.id}, 'xac-nhan')" class="px-3 py-1 bg-sky-600 text-white rounded text-xs font-semibold">Xác nhận</button>`;
                        } else if (lh.trang_thai === 'DA_XAC_NHAN') {
                            actions = `<button onclick="updateAppointmentStatus(${lh.id}, 'bat-dau')" class="px-3 py-1 bg-indigo-600 text-white rounded text-xs font-semibold">Bắt đầu khám</button>`;
                        } else if (lh.trang_thai === 'DANG_KHAM') {
                            actions = `
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openAddServiceModal(${lh.id}, '${lh.benh_nhan?.ho_ten}')" class="px-2.5 py-1 bg-teal-600 text-white rounded text-xs font-semibold">
                                        <i class="fa-solid fa-flask-vial mr-1"></i> Kê Dịch Vụ
                                    </button>
                                    <button onclick="completeAppointment(${lh.id})" class="px-2.5 py-1 bg-emerald-600 text-white rounded text-xs font-semibold">
                                        <i class="fa-solid fa-check mr-1"></i> Kết Luận
                                    </button>
                                </div>
                            `;
                        } else {
                            actions = `<span class="text-xs text-slate-400 font-medium">Đã kết thúc</span>`;
                        }

                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-bold text-sky-700">${lh.gio_kham}</td>
                                <td class="p-4 font-semibold text-slate-800">${lh.benh_nhan?.ho_ten || 'Khách vãng lai'}</td>
                                <td class="p-4">${lh.benh_nhan?.so_dien_thoai || '-'}</td>
                                <td class="p-4 text-slate-600">${lh.trieu_chung || '-'}</td>
                                <td class="p-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">${lh.trang_thai}</span></td>
                                <td class="p-4 text-center">${actions}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-amber-600">${json.thong_bao} (Cần quyền Bác Sĩ)</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-rose-500">Lỗi kết nối máy chủ.</td></tr>';
            }
        }

        async function updateAppointmentStatus(id, action) {
            const res = await fetch(`${API_BASE}/lich-hen/${id}/${action}`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            const json = await res.json();
            if (json.thanh_cong) {
                Swal.fire('Thành công', json.thong_bao, 'success');
                loadDoctorAppointments();
            }
        }

        function openAddServiceModal(id, patientName) {
            document.getElementById('currentAppointmentId').value = id;
            document.getElementById('serviceModalSubtitle').innerText = `Lịch hẹn #${id} - Bệnh nhân: ${patientName}`;
            document.getElementById('serviceModal').classList.remove('hidden');
            document.getElementById('serviceModal').classList.add('flex');
        }

        async function submitAddService() {
            const id = document.getElementById('currentAppointmentId').value;
            const dvId = document.getElementById('modalSelectService').value;
            const notes = document.getElementById('modalServiceNotes').value;

            const res = await fetch(`${API_BASE}/dich-vu/chi-dinh`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify({
                    lich_hen_id: id,
                    dich_vu_id: dvId,
                    so_luong: 1,
                    ghi_chu: notes
                })
            });

            const json = await res.json();
            if (json.thanh_cong) {
                closeModal('serviceModal');
                Swal.fire('Thành công', 'Đã thêm chỉ định dịch vụ cận lâm sàng!', 'success');
            } else {
                Swal.fire('Lỗi', json.thong_bao, 'error');
            }
        }

        async function completeAppointment(id) {
            const { value: formValues } = await Swal.fire({
                title: 'Kết Luận & Chẩn Đoán Khám Bệnh',
                html: `
                    <input id="swalDiagnose" class="swal2-input" placeholder="Chẩn đoán bệnh (ví dụ: Viêm họng cấp)">
                    <textarea id="swalAdvice" class="swal2-textarea" placeholder="Lời dặn bác sĩ / Đơn thuốc"></textarea>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Hoàn Thành & Xuất Viện Phí',
                preConfirm: () => {
                    return [
                        document.getElementById('swalDiagnose').value,
                        document.getElementById('swalAdvice').value
                    ]
                }
            });

            if (formValues) {
                const res = await fetch(`${API_BASE}/lich-hen/${id}/hoan-thanh`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({
                        chuan_doan: formValues[0] || 'Đã khám xong',
                        loi_khuyen: formValues[1] || 'Nghỉ ngơi và tái khám nếu cần'
                    })
                });

                const json = await res.json();
                if (json.thanh_cong) {
                    Swal.fire('Hoàn tất!', 'Hồ sơ đã được lưu và hóa đơn viện phí tự động phát sinh.', 'success');
                    loadDoctorAppointments();
                } else {
                    Swal.fire('Lỗi', json.thong_bao, 'error');
                }
            }
        }

        // ==========================================
        // BILLING & CASHIER TAB
        // ==========================================
        async function loadBillingList() {
            if (!authToken) return;
            const tbody = document.getElementById('billingTableBody');
            tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-slate-400">Đang tải danh sách hóa đơn...</td></tr>';

            try {
                const res = await fetch(`${API_BASE}/hoa-don`, {
                    headers: { 'Authorization': `Bearer ${authToken}` }
                });
                const json = await res.json();
                if (json.thanh_cong) {
                    if (json.du_lieu.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-slate-400">Chưa có hóa đơn nào.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = '';
                    json.du_lieu.forEach(hd => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-mono font-bold text-sky-700">${hd.ma_hoa_don}</td>
                                <td class="p-4 font-semibold text-slate-800">${hd.benh_nhan?.ho_ten || 'Khách hàng'}</td>
                                <td class="p-4">${Number(hd.tien_kham).toLocaleString()} đ</td>
                                <td class="p-4">${Number(hd.tien_dich_vu).toLocaleString()} đ</td>
                                <td class="p-4 font-black text-emerald-700">${Number(hd.tong_tien).toLocaleString()} đ</td>
                                <td class="p-4">
                                    ${hd.trang_thai === 'DA_THANH_TOAN' 
                                        ? '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Đã thanh toán</span>'
                                        : '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Chưa thanh toán</span>'
                                    }
                                </td>
                                <td class="p-4 text-center">
                                    ${hd.trang_thai === 'CHUA_THANH_TOAN' ? `
                                        <button onclick="payInvoice(${hd.id})" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-bold shadow-sm">
                                            <i class="fa-solid fa-money-bill-wave mr-1"></i> Thu Tiền
                                        </button>
                                    ` : `
                                        <span class="text-xs text-slate-400 font-medium">${hd.phuong_thuc_thanh_toan}</span>
                                    `}
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-rose-500">Lỗi tải dữ liệu hóa đơn.</td></tr>';
            }
        }

        async function payInvoice(id) {
            const { value: method } = await Swal.fire({
                title: 'Xác Nhận Thu Viện Phí',
                input: 'select',
                inputOptions: {
                    'TIEN_MAT': 'Tiền mặt tại quầy',
                    'CHUYEN_KHOAN': 'Chuyển khoản Ngân hàng (QR Code)',
                    'VNPAY': 'Ví VNPAY',
                    'THE': 'Thẻ POS / Visa'
                },
                inputPlaceholder: 'Chọn hình thức thanh toán',
                showCancelButton: true,
                confirmButtonText: 'Đã Thu Tiền'
            });

            if (method) {
                const res = await fetch(`${API_BASE}/hoa-don/${id}/thanh-toan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({ phuong_thuc_thanh_toan: method })
                });

                const json = await res.json();
                if (json.thanh_cong) {
                    Swal.fire('Thành công', 'Đã thu tiền và hoàn tất hóa đơn viện phí!', 'success');
                    loadBillingList();
                } else {
                    Swal.fire('Lỗi', json.thong_bao, 'error');
                }
            }
        }

        // ==========================================
        // ADMIN & REPORT TAB
        // ==========================================
        async function loadAdminStats() {
            if (!authToken) return;
            try {
                const res = await fetch(`${API_BASE}/hoa-don/thong-ke`, {
                    headers: { 'Authorization': `Bearer ${authToken}` }
                });
                const json = await res.json();
                if (json.thanh_cong) {
                    document.getElementById('statRevenue').innerText = Number(json.du_lieu.tong_doanh_thu).toLocaleString() + ' đ';
                    document.getElementById('statPaidInvoices').innerText = json.du_lieu.so_hoa_don_da_thanh_toan;
                    document.getElementById('statPendingInvoices').innerText = json.du_lieu.so_hoa_don_chua_thanh_toan;
                }
            } catch (err) {
                console.error("Lỗi tải thống kê doanh thu:", err);
            }
        }

        function loadAdminDoctors() {
            const tbody = document.getElementById('adminDoctorsTable');
            tbody.innerHTML = '';
            allDoctors.forEach(d => {
                tbody.innerHTML += `
                    <tr>
                        <td class="p-3 font-mono font-bold text-sky-700">${d.ma_bac_si}</td>
                        <td class="p-3 font-semibold">${d.ho_ten}</td>
                        <td class="p-3">${d.chuyen_khoa?.ten_khoa || '-'}</td>
                        <td class="p-3">${d.phong_kham}</td>
                        <td class="p-3 font-medium text-emerald-600">${Number(d.gia_kham).toLocaleString()} đ</td>
                        <td class="p-3"><span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">Đang làm việc</span></td>
                    </tr>
                `;
            });
        }

        function openAddDoctorModal() {
            Swal.fire({
                title: 'Thêm Bác Sĩ Mới',
                html: `
                    <input id="swalDocName" class="swal2-input" placeholder="Họ và tên bác sĩ">
                    <input id="swalDocFee" type="number" class="swal2-input" placeholder="Giá khám (VNĐ)" value="200000">
                    <input id="swalDocRoom" class="swal2-input" placeholder="Phòng khám (ví dụ: P205)" value="P205">
                `,
                confirmButtonText: 'Lưu Bác Sĩ',
                preConfirm: () => {
                    return {
                        ho_ten: document.getElementById('swalDocName').value,
                        gia_kham: document.getElementById('swalDocFee').value,
                        phong_kham: document.getElementById('swalDocRoom').value,
                        chuyen_khoa_id: allSpecialties.length > 0 ? allSpecialties[0].id : 1
                    }
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`${API_BASE}/bac-si`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${authToken}`
                            },
                            body: JSON.stringify(result.value)
                        });
                        const json = await res.json();
                        if (json.thanh_cong) {
                            Swal.fire('Thành công', 'Đã thêm bác sĩ mới!', 'success');
                            initData();
                        } else {
                            Swal.fire('Lỗi', json.thong_bao, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Lỗi', 'Cần đăng nhập tài khoản ADMIN để thêm bác sĩ', 'warning');
                    }
                }
            });
        }

        // ==========================================
        // AUTH MODAL & LOGOUT
        // ==========================================
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.getElementById('loginModal').classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        async function handleLogin() {
            const u = document.getElementById('loginUsername').value;
            const p = document.getElementById('loginPassword').value;

            try {
                const res = await fetch(`${API_BASE}/xac-thuc/dang-nhap`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ten_dang_nhap: u, mat_khau: p })
                });

                const json = await res.json();
                if (json.thanh_cong) {
                    authToken = json.du_lieu.token;
                    currentUser = json.du_lieu.tai_khoan;
                    localStorage.setItem('token', authToken);
                    localStorage.setItem('user', JSON.stringify(currentUser));
                    closeModal('loginModal');
                    checkAuthUI();
                    Swal.fire('Đăng nhập thành công', `Chào mừng ${currentUser.ho_ten} (${currentUser.ten_vai_tro})`, 'success');
                } else {
                    Swal.fire('Đăng nhập thất bại', json.thong_bao || 'Sai thông tin tài khoản', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối máy chủ xác thực', 'error');
            }
        }

        function handleLogout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            authToken = '';
            currentUser = null;
            checkAuthUI();
            Swal.fire('Đã đăng xuất', 'Hẹn gặp lại bạn!', 'info');
        }

        // Khởi động khi tải trang
        window.addEventListener('DOMContentLoaded', () => {
            checkAuthUI();
            initData();
        });
    </script>
</body>
</html>
