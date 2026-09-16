<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Phòng Khám Đa Khoa</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7', // Mau chu dao y te
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        .portal-section {
            display: none;
            animation: fadeIn 0.25s ease-in-out;
        }

        .portal-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-btn.active {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .slot-btn.selected {
            background-color: #0284c7 !important;
            color: #ffffff !important;
            border-color: #0284c7 !important;
            font-weight: 700;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-box {
            animation: modalScale 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalScale {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:16px_16px]">

    <!-- ================================================================= -->
    <!-- HEADER: ĐỒNG MÀU CHUẨN FORM ĐĂNG NHẬP / ĐĂNG KÝ                  -->
    <!-- ================================================================= -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 px-6 py-3 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-medical-600 via-sky-600 to-sky-500 flex items-center justify-center text-white text-lg shadow-md shadow-medical-600/25 border border-white/40">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <div>
                    <h1 id="brand-title" class="font-extrabold text-lg text-slate-900 leading-none tracking-tight">Phòng Khám Đa Khoa</h1>
                    <p id="brand-subtitle" class="text-[11px] font-bold text-medical-600 tracking-wider uppercase mt-1">CỔNG DỊCH VỤ Y TẾ</p>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center space-x-3">
                <!-- User Info Badge -->
                <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200/90 rounded-full px-3.5 py-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="header-user-name" class="text-xs font-bold text-slate-700">Đang tải...</span>
                    <span id="header-user-role" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-700">...</span>
                </div>


                <!-- Logout Button -->
                <button onclick="xuLyDangXuatGateway()" class="flex items-center space-x-1.5 px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl text-xs font-bold transition" title="Đăng Xuất Khỏi Hệ Thống">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Đăng Xuất</span>
                </button>
            </div>
        </div>
    </header>

    <!-- ================================================================= -->
    <!-- THANH ĐIỀU HƯỚNG TABS: CHỈ HIỂN THỊ THEO VAI TRÒ                 -->
    <!-- ================================================================= -->
    <nav class="bg-white border-b border-slate-200/80 px-6 py-2 shadow-xs" id="main-nav-tabs">
        <div class="max-w-7xl mx-auto flex items-center space-x-2 overflow-x-auto">
            <!-- 1. TABS CHO BỆNH NHÂN -->
            <button class="tab-btn role-tab-benh-nhan hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-benh-nhan" onclick="chuyenTab('tab-benh-nhan', this)">
                <i class="fa-regular fa-calendar-check text-medical-600"></i>
                <span>Tra Cứu Bác Sĩ & Đặt Lịch</span>
            </button>
            <button class="tab-btn role-tab-benh-nhan hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-benh-nhan-lich" onclick="chuyenTab('tab-benh-nhan-lich', this)">
                <i class="fa-solid fa-list-check text-sky-600"></i>
                <span>Lịch Khám Của Tôi</span>
            </button>

            <!-- 2. TABS CHO BÁC SĨ -->
            <button class="tab-btn role-tab-bac-si hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-bac-si" onclick="chuyenTab('tab-bac-si', this)">
                <i class="fa-solid fa-stethoscope text-medical-600"></i>
                <span>Bàn Khám & Kê Cận Lâm Sàng</span>
            </button>
            <button class="tab-btn role-tab-bac-si hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-bac-si-lich-su" onclick="chuyenTab('tab-bac-si-lich-su', this)">
                <i class="fa-solid fa-clipboard-user text-emerald-600"></i>
                <span>Danh Sách Ca Khám Hôm Nay</span>
            </button>

            <!-- 3. TABS CHO ADMIN -->
            <button class="tab-btn role-tab-admin hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-admin-bac-si" onclick="chuyenTab('tab-admin-bac-si', this)">
                <i class="fa-solid fa-user-doctor text-medical-600"></i>
                <span>Quản Trị Bác Sĩ & Khoa</span>
            </button>
            <button class="tab-btn role-tab-admin hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-admin-tai-khoan" onclick="chuyenTab('tab-admin-tai-khoan', this)">
                <i class="fa-solid fa-users-gear text-indigo-600"></i>
                <span>Quản Trị Tài Khoản</span>
            </button>
            <button class="tab-btn role-tab-admin hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-admin-thu-ngan" onclick="chuyenTab('tab-admin-thu-ngan', this)">
                <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                <span>Thu Ngân & Viện Phí</span>
            </button>
            <button class="tab-btn role-tab-admin hidden items-center space-x-2 px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition border border-transparent" id="nav-btn-admin-giam-sat" onclick="chuyenTab('tab-admin-giam-sat', this)">
                <i class="fa-solid fa-chart-pie text-purple-600"></i>
                <span>Thống Kê & Trạng Thái Hệ Thống</span>
            </button>
        </div>
    </nav>

    <!-- ================================================================= -->
    <!-- MAIN WORKSPACE                                                    -->
    <!-- ================================================================= -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-6 space-y-6">

        <!-- ============================================================= -->
        <!-- PHÂN HỆ 1: DÀNH CHO BỆNH NHÂN                                -->
        <!-- ============================================================= -->

        <!-- TAB 1.1: TRA CỨU & ĐẶT LỊCH KHÁM -->
        <section id="tab-benh-nhan" class="portal-section space-y-6">
            <!-- Patient Hero Banner -->
            <div class="bg-gradient-to-r from-medical-700 via-medical-600 to-sky-500 rounded-3xl p-8 text-white shadow-xl shadow-medical-600/15 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                <div class="space-y-2 z-10">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-sm border border-white/30 text-sky-100 uppercase tracking-wider">
                        Cổng Đặt Lịch Trực Tuyến
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Xin chào, <span id="banner-patient-name" class="underline decoration-sky-300">Quý Khách</span>!
                    </h2>
                    <p class="text-sky-100 text-sm max-w-xl">
                        Tra cứu danh sách bác sĩ chuyên khoa, xem phòng khám, giá niêm yết và đăng ký khám bệnh theo khung giờ 30 phút thuận tiện.
                    </p>
                </div>
                <button onclick="moModalDatLich(null)" class="z-10 px-6 py-3 bg-white hover:bg-sky-50 text-medical-700 font-extrabold text-sm rounded-2xl shadow-lg shadow-slate-900/10 transition transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-calendar-plus text-medical-600"></i>
                    <span>Đặt Lịch Khám Ngay</span>
                </button>
            </div>

            <!-- Doctor List Panel -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-user-doctor text-medical-600"></i>
                            <span>Đội Ngũ Bác Sĩ Chuyên Khoa</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Chọn bác sĩ phù hợp để đặt lịch khám trực tiếp</p>
                    </div>

                    <!-- Search and Speciality Filters -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="filter-doctor-search" oninput="locDanhSachBacSi()" placeholder="Tìm tên bác sĩ..." class="pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 w-56">
                        </div>
                        <select id="filter-doctor-chuyen-khoa" onchange="locDanhSachBacSi()" class="px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-700 font-medium">
                            <option value="">Tất cả chuyên khoa</option>
                        </select>
                    </div>
                </div>

                <!-- Doctor Cards Grid -->
                <div id="doctors-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <p class="text-xs text-slate-400 italic">Đang tải dữ liệu bác sĩ...</p>
                </div>
            </div>
        </section>

        <!-- TAB 1.2: LỊCH KHÁM CỦA TÔI -->
        <section id="tab-benh-nhan-lich" class="portal-section space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-list-check text-sky-600"></i>
                            <span>Danh Sách Lịch Khám Của Tôi</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Theo dõi thời gian, bác sĩ phụ trách và tình trạng ca khám</p>
                    </div>
                    <button onclick="taiDanhSachLichHen()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Làm Mới</span>
                    </button>
                </div>

                <!-- Table Appointments -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">Mã Lịch</th>
                                <th class="py-3.5 px-4">Bác Sĩ Khám</th>
                                <th class="py-3.5 px-4">Chuyên Khoa</th>
                                <th class="py-3.5 px-4">Ngày Khám</th>
                                <th class="py-3.5 px-4">Khung Giờ</th>
                                <th class="py-3.5 px-4">Lý Do Khám</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                                <th class="py-3.5 px-4 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-benh-nhan-lich" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="8" class="text-center py-8 text-slate-400">Đang tải lịch hẹn...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ============================================================= -->
        <!-- PHÂN HỆ 2: DÀNH CHO BÁC SĨ                                   -->
        <!-- ============================================================= -->

        <!-- TAB 2.1: BÀN KHÁM TIẾP NHẬN & CHỈ ĐỊNH CẬN LÂM SÀNG -->
        <section id="tab-bac-si" class="portal-section space-y-6">
            <!-- Doctor Banner -->
            <div class="bg-gradient-to-r from-sky-700 via-medical-600 to-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-sky-700/15 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl text-white border border-white/30">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold text-sky-200 tracking-wider uppercase">Bàn Khám Bác Sĩ Đang Trực</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                            BS. <span id="banner-doctor-name">Khám Bệnh</span>
                        </h2>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/20 backdrop-blur-sm border border-white/30 text-white flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>ĐANG TIẾP NHẬN</span>
                </span>
            </div>

            <!-- 2-Column Doctor Workspace -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Column 1: Waiting Patient Queue (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-user-clock text-amber-500"></i>
                            <span>1. Hàng Đợi Bệnh Nhân Đang Chờ</span>
                        </h3>
                        <button onclick="taiDanhSachLichHen()" class="text-xs text-medical-600 hover:text-medical-700 font-bold">
                            <i class="fa-solid fa-arrows-rotate mr-1"></i> Tải lại
                        </button>
                    </div>

                    <div class="overflow-y-auto max-h-[420px] rounded-2xl border border-slate-200/80">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Bệnh Nhân</th>
                                    <th class="py-2.5 px-3">Giờ Hẹn</th>
                                    <th class="py-2.5 px-3 text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-ca-kham-bac-si" class="divide-y divide-slate-100 text-slate-700">
                                <tr><td colspan="4" class="text-center py-6 text-slate-400">Đang tải ca khám...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Column 2: Clinical Form & Paraclinical Test Prescription (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-medical-700 flex items-center space-x-2">
                            <i class="fa-solid fa-notes-medical"></i>
                            <span>2. Phiếu Khám & Chỉ Định Cận Lâm Sàng</span>
                        </h3>
                    </div>

                    <!-- Selected Patient Info Card -->
                    <div id="box-chon-ca-kham-thong-tin" class="bg-sky-50/70 border border-sky-100 p-3.5 rounded-2xl text-xs text-slate-600">
                        <em>👉 Hãy bấm nút <strong>"Tiếp Nhận"</strong> trên danh sách hàng đợi bên trái để nạp thông tin bệnh nhân.</em>
                    </div>

                    <!-- Preliminary Diagnosis Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Chẩn đoán sơ bộ / Triệu chứng lâm sàng:
                        </label>
                        <input type="text" id="input-chan-doan" placeholder="Ví dụ: Đau đầu kéo dài, nghi rối loạn tiền đình..." class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>

                    <!-- Paraclinical Tests Checkboxes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tích chọn các dịch vụ cận lâm sàng chỉ định (Service 03):
                        </label>
                        <div id="list-dich-vu-checkboxes" class="space-y-2 max-h-[190px] overflow-y-auto pr-1">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                    <!-- Subtotal & Save Button -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-[11px] font-bold text-slate-500 uppercase">TỔNG PHÍ CẬN LÂM SÀNG:</span>
                            <div id="subtotal-cls" class="text-xl font-extrabold text-emerald-600">0 đ</div>
                        </div>
                        <button onclick="luuChiDinhCanLamSang()" class="px-5 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-medical-600/25 transition flex items-center space-x-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Lưu Chỉ Định & Gửi Thu Ngân</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 2.2: DANH SÁCH CA KHÁM HÔM NAY -->
        <section id="tab-bac-si-lich-su" class="portal-section space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-clipboard-user text-emerald-600"></i>
                            <span>Danh Sách Ca Bệnh Nhân Bác Sĩ Đã Tiếp Nhận</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Lịch sử và tiến trình các ca khám trong ngày</p>
                    </div>
                    <button onclick="taiDanhSachLichHen()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Làm Mới</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">Mã Lịch</th>
                                <th class="py-3.5 px-4">Bệnh Nhân</th>
                                <th class="py-3.5 px-4">Số Điện Thoại</th>
                                <th class="py-3.5 px-4">Ngày Khám</th>
                                <th class="py-3.5 px-4">Khung Giờ</th>
                                <th class="py-3.5 px-4">Lý Do Khám</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-bac-si-lich-su" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="7" class="text-center py-8 text-slate-400">Đang tải dữ liệu...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ============================================================= -->
        <!-- PHÂN HỆ 3: DÀNH CHO ADMIN                                    -->
        <!-- ============================================================= -->

        <!-- TAB 3.1: QUẢN TRỊ BÁC SĨ & CHUYÊN KHOA -->
        <section id="tab-admin-bac-si" class="portal-section space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-medical-900 rounded-3xl p-6 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-xl text-sky-400 border border-white/20">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold text-indigo-300 tracking-wider uppercase">Quản Trị Hệ Thống</span>
                        <h2 class="text-xl font-extrabold tracking-tight">Danh Mục Bác Sĩ & Chuyên Khoa</h2>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="moModalThemBacSi()" class="px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm Bác Sĩ</span>
                    </button>
                    <button onclick="moModalThemChuyenKhoa()" class="px-4 py-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl border border-white/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-folder-plus"></i>
                        <span>Thêm Khoa</span>
                    </button>
                </div>
            </div>

            <!-- Two Columns for Doctors and Specialties -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-user-doctor text-medical-600"></i>
                            <span>Danh Sách Bác Sĩ Phòng Khám</span>
                        </h3>
                        <button onclick="taiDanhSachBacSi()" class="text-xs text-medical-600 font-bold">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                    </div>
                    <div class="overflow-y-auto max-h-[380px] rounded-2xl border border-slate-200/80">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Họ Tên</th>
                                    <th class="py-2.5 px-3">Chuyên Khoa</th>
                                    <th class="py-2.5 px-3">Giá Khám</th>
                                    <th class="py-2.5 px-3">Phòng</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin-bac-si" class="divide-y divide-slate-100 text-slate-700">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-building-user text-indigo-600"></i>
                            <span>Danh Mục Chuyên Khoa</span>
                        </h3>
                        <button onclick="taiDanhSachChuyenKhoa()" class="text-xs text-indigo-600 font-bold">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                    </div>
                    <div class="overflow-y-auto max-h-[380px] rounded-2xl border border-slate-200/80">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Tên Khoa</th>
                                    <th class="py-2.5 px-3">Mô Tả</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin-chuyen-khoa" class="divide-y divide-slate-100 text-slate-700">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 3.2: QUẢN TRỊ TÀI KHOẢN -->
        <section id="tab-admin-tai-khoan" class="portal-section space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-users-gear text-indigo-600"></i>
                            <span>Quản Trị Danh Sách Tài Khoản Toàn Hệ Thống</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Phân quyền, kiểm tra hoạt động và khóa/mở khóa tài khoản (Service 01)</p>
                    </div>
                    <button onclick="taiDanhSachTaiKhoan()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Làm Mới</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">ID</th>
                                <th class="py-3.5 px-4">Tên Đăng Nhập</th>
                                <th class="py-3.5 px-4">Họ Và Tên</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Số Điện Thoại</th>
                                <th class="py-3.5 px-4">Vai Trò</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                                <th class="py-3.5 px-4 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-admin-tai-khoan" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="8" class="text-center py-8 text-slate-400">Đang tải danh sách tài khoản...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- TAB 3.3: THU NGÂN & VIỆN PHÍ -->
        <section id="tab-admin-thu-ngan" class="portal-section space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                            <span>Danh Sách Hóa Đơn & Thu Viện Phí Tự Động</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tổng hợp chi phí khám + cận lâm sàng tự động (Service 04)</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="moModalTaoHoaDonTuDong()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-bolt"></i>
                            <span>Tạo Hóa Đơn Tự Động</span>
                        </button>
                        <button onclick="taiDanhSachHoaDon()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Làm Mới</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">Mã Hóa Đơn</th>
                                <th class="py-3.5 px-4">Lịch Hẹn ID</th>
                                <th class="py-3.5 px-4">Bệnh Nhân</th>
                                <th class="py-3.5 px-4">Tiền Khám</th>
                                <th class="py-3.5 px-4">Tiền CLS</th>
                                <th class="py-3.5 px-4">Tổng Tiền</th>
                                <th class="py-3.5 px-4">Thực Thu</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                                <th class="py-3.5 px-4 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-hoa-don" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="9" class="text-center py-8 text-slate-400">Đang tải hóa đơn...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- TAB 3.4: GIÁM SÁT 4 MICROSERVICES & CONSOLE (ĐÂY LÀ "CÁI NÀY" DÀNH RIÊNG CHO ADMIN) -->
        <section id="tab-admin-giam-sat" class="portal-section space-y-6">
            <!-- 4 Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-medical-50 text-medical-600 border border-medical-200 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bác Sĩ Công Tác</div>
                        <div class="text-2xl font-black text-slate-800" id="stat-so-bac-si">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lịch Hẹn Hôm Nay</div>
                        <div class="text-2xl font-black text-slate-800" id="stat-so-lich-hen">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dịch Vụ Cận Lâm Sàng</div>
                        <div class="text-2xl font-black text-slate-800" id="stat-so-dich-vu">5</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tổng Thu Viện Phí</div>
                        <div class="text-2xl font-black text-emerald-600" id="stat-tong-doanh-thu">--</div>
                    </div>
                </div>
            </div>

            <!-- Trạng Thái 4 Microservices & Kiến Trúc Hệ Thống -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Services Status (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-network-wired text-medical-600"></i>
                            <span>Trạng Thái 4 Microservices Độc Lập</span>
                        </h3>
                        <button onclick="kiemTraHealthToanHeThong()" class="text-xs text-medical-600 hover:text-medical-700 font-bold flex items-center space-x-1">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Kiểm tra lại</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">01. Xác Thực & Bác Sĩ</h4>
                                <p class="text-[11px] text-slate-400 font-mono">Port: 8001 | DB: db_xac_thuc_bac_si</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-1">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">02. Bệnh Nhân & Lịch Hẹn</h4>
                                <p class="text-[11px] text-slate-400 font-mono">Port: 8002 | DB: db_benh_nhan_lich_hen</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-2">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">03. Y Tế & Cận Lâm Sàng</h4>
                                <p class="text-[11px] text-slate-400 font-mono">Port: 8003 | DB: db_dich_vu_y_te</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-3">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">04. Hóa Đơn & Thanh Toán</h4>
                                <p class="text-[11px] text-slate-400 font-mono">Port: 8004 | DB: db_hoa_don_thanh_toan</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-4">ONLINE</span>
                        </div>
                    </div>
                </div>

                <!-- System Info (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-info text-indigo-600"></i>
                            <span>Thông Tin Hạ Tầng & Kết Nối</span>
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="status-gateway-badge">
                            GATEWAY: 8000
                        </span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-1">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Cổng Giao Tiếp Tập Trung (API Gateway)</div>
                            <div class="font-mono font-bold text-medical-700">http://127.0.0.1:8000</div>
                            <p class="text-[11px] text-slate-500">Reverse Proxy trung tâm, xử lý phân quyền RBAC và điều hướng liên dịch vụ.</p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-1">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Máy Chủ Cơ Sở Dữ Liệu</div>
                            <div class="font-mono font-bold text-slate-800">MySQL Laragon (Port 3307)</div>
                            <p class="text-[11px] text-slate-500">4 Database độc lập cho từng Microservice, bảo toàn tính toàn vẹn dữ liệu.</p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-1">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Giao Thức Xác Thực & An Toàn</div>
                            <div class="font-mono font-bold text-emerald-700">Laravel Sanctum Bearer Token</div>
                            <p class="text-[11px] text-slate-500">Mã hóa phiên làm việc, phân quyền nghiêm ngặt giữa Bệnh nhân, Bác sĩ và Quản trị viên.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ============================================================= -->
    <!-- MODALS HỆ THỐNG                                               -->
    <!-- ============================================================= -->

    <!-- MODAL 1: ĐẶT LỊCH HẸN (BỆNH NHÂN) -->
    <div class="modal-backdrop" id="modal-dat-lich">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-calendar-plus text-medical-600"></i>
                    <span>Đặt Lịch Hẹn Khám Bệnh Trực Tuyến</span>
                </h3>
                <button onclick="dongModal('modal-dat-lich')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Chọn Bác Sĩ:</label>
                    <select id="modal-dl-bac-si" onchange="capNhatGiaKhamModal()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                        <!-- Dynamic -->
                    </select>
                </div>

                <div class="bg-medical-50/70 border border-medical-100 p-3 rounded-xl flex items-center justify-between">
                    <span class="text-slate-600 font-medium">Giá khám chuyên khoa niêm yết:</span>
                    <span id="modal-dl-gia-kham" class="font-extrabold text-medical-700 text-sm">200,000 đ</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ tên Bệnh nhân:</label>
                        <input type="text" id="modal-dl-ho-ten" placeholder="Nhập họ tên..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số điện thoại:</label>
                        <input type="text" id="modal-dl-sdt" placeholder="Nhập số điện thoại..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày khám:</label>
                    <input type="date" id="modal-dl-ngay" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1.5">Chọn Khung Giờ (Ca khám 30 phút):</label>
                    <div class="grid grid-cols-4 gap-2" id="modal-dl-slots">
                        <button type="button" class="slot-btn selected py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '08:00', '08:30')">08:00</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '08:30', '09:00')">08:30</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '09:00', '09:30')">09:00</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '09:30', '10:00')">09:30</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '10:00', '10:30')">10:00</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '14:00', '14:30')">14:00</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '14:30', '15:00')">14:30</button>
                        <button type="button" class="slot-btn py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlot(this, '15:00', '15:30')">15:00</button>
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Lý do khám / Triệu chứng:</label>
                    <input type="text" id="modal-dl-ly-do" placeholder="Khám tổng quát, nhức đầu, sốt..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button onclick="dongModal('modal-dat-lich')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                <button onclick="xacNhanDatLich()" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Xác Nhận Đặt Lịch</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TẠO HÓA ĐƠN TỰ ĐỘNG (ADMIN) -->
    <div class="modal-backdrop" id="modal-tao-hoa-don">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-bolt text-amber-500"></i>
                    <span>Tự Động Tổng Hợp Hóa Đơn Liên Dịch Vụ</span>
                </h3>
                <button onclick="dongModal('modal-tao-hoa-don')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-xs">
                <p class="text-slate-500">
                    Service 04 sẽ tự động liên lạc sang Service 01 (tiền khám) và Service 03 (tất cả cận lâm sàng bác sĩ đã chỉ định) để lập hóa đơn viện phí:
                </p>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Chọn Lịch Hẹn Cần Tính Phí:</label>
                    <select id="modal-hd-lich-hen-id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                        <!-- Dynamic -->
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giảm giá / Ưu đãi (VND):</label>
                    <input type="number" id="modal-hd-giam-gia" value="0" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ghi chú hóa đơn:</label>
                    <input type="text" id="modal-hd-ghi-chu" value="Thanh toán viện phí khám bệnh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button onclick="dongModal('modal-tao-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                <button onclick="xacNhanTaoHoaDonTuDong()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Tự Động Tính & Tạo Hóa Đơn</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: CHI TIẾT & THANH TOÁN HÓA ĐƠN (ADMIN) -->
    <div class="modal-backdrop" id="modal-chi-tiet-hoa-don">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-xl w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900" id="modal-cthd-title">Chi Tiết Hóa Đơn Viện Phí</h3>
                <button onclick="dongModal('modal-chi-tiet-hoa-don')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Bệnh Nhân</div>
                        <div class="font-extrabold text-slate-800 mt-0.5" id="modal-cthd-benh-nhan">--</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Ngày Lập</div>
                        <div class="font-bold text-slate-800 mt-0.5" id="modal-cthd-ngay">--</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Trạng Thái</div>
                        <div class="mt-0.5" id="modal-cthd-trang-thai">--</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-700 uppercase tracking-wider mb-2">Bảng Kê Viện Phí Liên Dịch Vụ:</h4>
                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2 px-3">Hạng Mục</th>
                                    <th class="py-2 px-3 text-center">SL</th>
                                    <th class="py-2 px-3">Đơn Giá</th>
                                    <th class="py-2 px-3 text-right">Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody id="modal-cthd-tbody-items" class="divide-y divide-slate-100 text-slate-700">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-1.5 text-right font-medium">
                    <div class="text-slate-500">Phí khám bác sĩ: <strong id="modal-cthd-tien-kham" class="text-slate-800">0 đ</strong></div>
                    <div class="text-slate-500">Phí cận lâm sàng: <strong id="modal-cthd-tien-cls" class="text-slate-800">0 đ</strong></div>
                    <div class="text-rose-500">Giảm trừ: <span id="modal-cthd-giam-tru">-0 đ</span></div>
                    <div class="text-base font-black text-emerald-600 pt-1 border-t border-slate-100">
                        TỔNG THỰC THU: <span id="modal-cthd-thuc-thu">0 đ</span>
                    </div>
                </div>

                <div id="box-thanh-toan-actions" class="pt-3 border-t border-slate-100 space-y-2">
                    <label class="block font-bold uppercase tracking-wider text-slate-700">Chọn Phương Thức Thanh Toán:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="chonPhuongThuc(this, 'TIEN_MAT')" class="py-2 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 font-bold rounded-xl text-center transition">💵 Tiền Mặt</button>
                        <button type="button" onclick="chonPhuongThuc(this, 'CHUYEN_KHOAN')" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold rounded-xl text-center transition">🏦 Chuyển Khoản</button>
                        <button type="button" onclick="chonPhuongThuc(this, 'VNPAY')" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold rounded-xl text-center transition">💳 VNPAY</button>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button onclick="dongModal('modal-chi-tiet-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
                <button id="btn-xac-nhan-thanh-toan" onclick="xacNhanThanhToanHoaDonHienTai()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Xác Nhận Thu Tiền</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 4: THÊM BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-bac-si">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-user-doctor text-medical-600"></i>
                    <span>Thêm Bác Sĩ Mới (Admin)</span>
                </h3>
                <button onclick="dongModal('modal-them-bac-si')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ và tên Bác sĩ:</label>
                    <input type="text" id="modal-tbs-ho-ten" placeholder="Ví dụ: BS. CKII Hoàng Minh Tuấn" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Chuyên khoa:</label>
                        <select id="modal-tbs-chuyen-khoa" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                            <!-- Dynamic -->
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Học vị:</label>
                        <input type="text" id="modal-tbs-hoc-vi" placeholder="Bác sĩ Chuyên khoa II" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giá khám (VND):</label>
                        <input type="number" id="modal-tbs-gia-kham" value="250000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số phòng khám:</label>
                        <input type="text" id="modal-tbs-phong" placeholder="P302" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tên đăng nhập:</label>
                        <input type="text" id="modal-tbs-username" placeholder="bshminh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mật khẩu ban đầu:</label>
                        <input type="password" id="modal-tbs-pass" value="123456" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button onclick="dongModal('modal-them-bac-si')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                <button onclick="xacNhanThemBacSi()" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Tạo Hồ Sơ Bác Sĩ</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 5: THÊM CHUYÊN KHOA (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-chuyen-khoa">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-folder-plus text-indigo-600"></i>
                    <span>Thêm Chuyên Khoa Mới</span>
                </h3>
                <button onclick="dongModal('modal-them-chuyen-khoa')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mã chuyên khoa:</label>
                    <input type="text" id="modal-tck-ma" placeholder="Ví dụ: UNG_BUOU, DA_LIEU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 uppercase">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tên chuyên khoa:</label>
                    <input type="text" id="modal-tck-ten" placeholder="Ví dụ: Khoa Ung Bướu" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mô tả nhiệm vụ:</label>
                    <input type="text" id="modal-tck-mo-ta" placeholder="Khám, tầm soát và điều trị..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button onclick="dongModal('modal-them-chuyen-khoa')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                <button onclick="xacNhanThemChuyenKhoa()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Thêm Chuyên Khoa</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- JAVASCRIPT APPLICATION CORE                                       -->
    <!-- ============================================================= -->
    <script>
        const AppState = {
            token: '',
            currentUser: null,
            danhSachBacSi: [],
            danhSachChuyenKhoa: [],
            danhSachDichVuCLS: [],
            danhSachLichHen: [],
            danhSachHoaDon: [],
            danhSachTaiKhoan: [],
            slotDaChon: { batDau: '08:00', ketThuc: '08:30' },
            caKhamDangChon: null,
            phuongThucThanhToan: 'TIEN_MAT',
            hoaDonDangXemId: null
        };

        // KHỞI ĐỘNG KHI TẢI TRANG (AUTH GUARD)
        document.addEventListener('DOMContentLoaded', async () => {
            // Ngày khám mặc định: ngày mai
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const dateStr = tomorrow.toISOString().split('T')[0];
            const dateInput = document.getElementById('modal-dl-ngay');
            if (dateInput) dateInput.value = dateStr;

            const savedToken = sessionStorage.getItem('token') || localStorage.getItem('token');
            const savedUserStr = sessionStorage.getItem('user_info') || localStorage.getItem('user_info');

            if (!savedToken || !savedUserStr) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Yêu cầu đăng nhập',
                    text: 'Bạn cần đăng nhập để truy cập vào hệ thống phòng khám!',
                    confirmButtonColor: '#0284c7'
                }).then(() => {
                    window.location.href = '/dang-nhap';
                });
                return;
            }

            try {
                AppState.currentUser = JSON.parse(savedUserStr);
                AppState.token = savedToken;
            } catch (e) {
                sessionStorage.clear();
                localStorage.clear();
                window.location.href = '/dang-nhap';
                return;
            }

            // Đồng bộ giao diện theo vai trò
            capNhatGiaoDienTheoVaiTro();

            // Tải dữ liệu ban đầu
            await taiDanhSachChuyenKhoa();
            await taiDanhSachBacSi();
            await taiDanhSachLichHen();

            const role = AppState.currentUser ? AppState.currentUser.vai_tro : '';
            if (role === 'BAC_SI' || role === 'ADMIN') {
                await taiDanhSachDichVuCLS();
            }
            if (role === 'ADMIN') {
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
                await taiDanhSachTaiKhoan();
                await kiemTraHealthToanHeThong();
            }

            // Xử lý tham số ?bac_si_id nếu có
            const urlParams = new URLSearchParams(window.location.search);
            const preselectDoctorId = urlParams.get('bac_si_id');
            if (preselectDoctorId && role === 'BENH_NHAN') {
                setTimeout(() => moModalDatLich(preselectDoctorId), 300);
            }
        });

        // HÀM ĐIỀU CHỈNH GIAO DIỆN CHUẨN XÁC THEO TỪNG VAI TRÒ
        function capNhatGiaoDienTheoVaiTro() {
            const role = AppState.currentUser ? AppState.currentUser.vai_tro : (localStorage.getItem('role') || 'BENH_NHAN');
            const hoTen = AppState.currentUser ? (AppState.currentUser.ho_ten || AppState.currentUser.ten_dang_nhap) : 'Người dùng';

            // Cập nhật Header
            document.getElementById('header-user-name').textContent = hoTen;
            const roleEl = document.getElementById('header-user-role');
            roleEl.textContent = role;

            if (role === 'ADMIN') {
                roleEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200';
            } else if (role === 'BAC_SI') {
                roleEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-700 border border-sky-200';
            } else {
                roleEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200';
            }

            if (document.getElementById('label-active-token')) {
                document.getElementById('label-active-token').textContent = AppState.token ? (AppState.token.substring(0, 36) + '...') : 'Chưa có Token';
            }

            // 1. TRƯỜNG HỢP: BỆNH NHÂN (BENH_NHAN)
            if (role === 'BENH_NHAN') {
                document.getElementById('brand-title').textContent = 'Phòng Khám Đa Khoa';
                document.getElementById('brand-subtitle').textContent = 'CỔNG BỆNH NHÂN - ĐẶT LỊCH TRỰC TUYẾN';
                
                const pName = document.getElementById('banner-patient-name');
                if (pName) pName.textContent = hoTen;

                document.querySelectorAll('.admin-only').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.role-tab-bac-si').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.role-tab-admin').forEach(el => el.classList.add('hidden'));
                
                document.querySelectorAll('.role-tab-benh-nhan').forEach(el => el.classList.remove('hidden'));
                chuyenTab('tab-benh-nhan');

            // 2. TRƯỜNG HỢP: BÁC SĨ (BAC_SI)
            } else if (role === 'BAC_SI') {
                document.getElementById('brand-title').textContent = 'Phòng Khám Đa Khoa';
                document.getElementById('brand-subtitle').textContent = 'BÀN KHÁM BÁC SĨ & CHỈ ĐỊNH CẬN LÂM SÀNG';

                const docName = document.getElementById('banner-doctor-name');
                if (docName) docName.textContent = hoTen;

                document.querySelectorAll('.admin-only').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.role-tab-benh-nhan').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.role-tab-admin').forEach(el => el.classList.add('hidden'));

                document.querySelectorAll('.role-tab-bac-si').forEach(el => el.classList.remove('hidden'));
                chuyenTab('tab-bac-si');

            // 3. TRƯỜNG HỢP: ADMIN (QUẢN TRỊ VIÊN)
            } else {
                document.getElementById('brand-title').textContent = 'Phòng Khám Đa Khoa';
                document.getElementById('brand-subtitle').textContent = 'TRUNG TÂM QUẢN TRỊ & ĐIỀU HÀNH (ADMIN)';

                document.querySelectorAll('.admin-only').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.admin-only').forEach(el => el.style.display = 'inline-flex');

                document.querySelectorAll('.role-tab-benh-nhan').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.role-tab-bac-si').forEach(el => el.classList.add('hidden'));

                document.querySelectorAll('.role-tab-admin').forEach(el => el.classList.remove('hidden'));
                chuyenTab('tab-admin-bac-si');
            }
        }

        // HÀM CHUYỂN TAB CÓ PHÂN QUYỀN RBAC
        function chuyenTab(tabId, clickedBtn = null) {
            const role = AppState.currentUser ? AppState.currentUser.vai_tro : (localStorage.getItem('role') || '');

            if (role === 'BENH_NHAN' && (tabId.startsWith('tab-bac-si') || tabId.startsWith('tab-admin'))) {
                showToast('error', 'Phân Quyền RBAC (403 Forbidden)', 'Tài khoản Bệnh nhân chỉ có quyền đặt lịch và xem lịch của mình.');
                return;
            }

            if (role === 'BAC_SI' && tabId.startsWith('tab-admin')) {
                showToast('error', 'Phân Quyền RBAC (403 Forbidden)', 'Khu vực quản trị chỉ dành riêng cho Quản Trị Viên (ADMIN).');
                return;
            }

            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.portal-section').forEach(sec => sec.classList.remove('active'));

            const targetSection = document.getElementById(tabId);
            if (targetSection) targetSection.classList.add('active');

            if (clickedBtn) {
                clickedBtn.classList.add('active');
            } else {
                const btn = document.querySelector(`button[onclick*="${tabId}"]`);
                if (btn) btn.classList.add('active');
            }
        }

        // HÀM GỌI API QUA GATEWAY (PORT 8000)
        async function goiApi(method, path, body = null) {
            const cleanPath = path.startsWith('/') ? path : '/' + path;
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };

            if (AppState.token) {
                headers['Authorization'] = 'Bearer ' + AppState.token;
            }

            const options = {
                method: method.toUpperCase(),
                headers: headers
            };

            if (body && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(options.method)) {
                options.body = JSON.stringify(body);
            }

            try {
                const res = await fetch(cleanPath, options);
                const data = await res.json().catch(() => ({}));

                return {
                    ok: res.ok,
                    status: res.status,
                    data: data
                };
            } catch (err) {
                return {
                    ok: false,
                    status: 503,
                    data: { thong_diep: 'Không thể kết nối đến API Gateway hoặc Service ngoại tuyến.' }
                };
            }
        }

        // ĐĂNG XUẤT HỆ THỐNG
        function xuLyDangXuatGateway() {
            sessionStorage.clear();
            localStorage.clear();
            AppState.token = '';
            AppState.currentUser = null;
            window.location.href = '/dang-nhap';
        }

        // =============================================================
        // PHÂN HỆ BÁC SĨ & CHUYÊN KHOA
        // =============================================================
        async function taiDanhSachChuyenKhoa() {
            const res = await goiApi('GET', '/api/v1/bac-si/chuyen-khoa');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachChuyenKhoa = res.data.du_lieu;
                
                const filterSelect = document.getElementById('filter-doctor-chuyen-khoa');
                const modalSelect = document.getElementById('modal-tbs-chuyen-khoa');
                
                let opts = '<option value="">Tất cả chuyên khoa</option>';
                let modalOpts = '';

                AppState.danhSachChuyenKhoa.forEach(ck => {
                    const ten = ck.ten_khoa || ck.ten_chuyen_khoa;
                    opts += `<option value="${ck.id}">${ten}</option>`;
                    modalOpts += `<option value="${ck.id}">${ten}</option>`;
                });

                if (filterSelect) filterSelect.innerHTML = opts;
                if (modalSelect) modalSelect.innerHTML = modalOpts;

                renderAdminChuyenKhoaTable();
            }
        }

        async function taiDanhSachBacSi() {
            const res = await goiApi('GET', '/api/v1/bac-si');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachBacSi = res.data.du_lieu;
                renderDoctorsCards(AppState.danhSachBacSi);
                renderAdminBacSiTable(AppState.danhSachBacSi);
                
                const statBs = document.getElementById('stat-so-bac-si');
                if (statBs) statBs.textContent = AppState.danhSachBacSi.length;
            }
        }

        function renderDoctorsCards(doctors) {
            const container = document.getElementById('doctors-cards-container');
            if (!container) return;

            if (!doctors || doctors.length === 0) {
                container.innerHTML = '<p class="text-xs text-slate-400 col-span-full py-4 text-center italic">Không tìm thấy bác sĩ phù hợp.</p>';
                return;
            }

            container.innerHTML = doctors.map(bs => {
                const tenKhoa = bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const gia = Number(bs.gia_kham || 200000).toLocaleString('vi-VN');
                return `
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 hover:border-medical-500 hover:shadow-xl hover:shadow-medical-600/10 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-50 to-medical-100 text-medical-700 border border-medical-200 flex items-center justify-center font-extrabold text-base shadow-sm">
                                    ${bs.ho_ten.substring(0, 2).toUpperCase()}
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    ${tenKhoa}
                                </span>
                            </div>

                            <div class="mt-4 space-y-1.5">
                                <h4 class="font-extrabold text-slate-900 text-sm">${bs.ho_ten}</h4>
                                <div class="text-xs text-slate-500 space-y-1">
                                    <div><i class="fa-solid fa-graduation-cap text-medical-500 w-4"></i> ${bs.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                                    <div><i class="fa-solid fa-door-open text-slate-400 w-4"></i> Phòng: <strong class="text-slate-700">${bs.phong_kham || 'P201'}</strong></div>
                                    <div><i class="fa-solid fa-phone text-emerald-500 w-4"></i> ${bs.so_dien_thoai || '0901234567'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Giá khám</div>
                                <div class="text-base font-black text-medical-600">${gia} đ</div>
                            </div>
                            <button onclick="moModalDatLich(${bs.id})" class="px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                                <i class="fa-regular fa-calendar-check"></i>
                                <span>Đặt Lịch</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function locDanhSachBacSi() {
            const tuKhoa = (document.getElementById('filter-doctor-search').value || '').toLowerCase();
            const chuyenKhoaId = document.getElementById('filter-doctor-chuyen-khoa').value;

            const filtered = AppState.danhSachBacSi.filter(bs => {
                const matchName = bs.ho_ten.toLowerCase().includes(tuKhoa) || (bs.ma_bac_si && bs.ma_bac_si.toLowerCase().includes(tuKhoa));
                const matchKhoa = !chuyenKhoaId || (bs.chuyen_khoa_id == chuyenKhoaId);
                return matchName && matchKhoa;
            });

            renderDoctorsCards(filtered);
        }

        // =============================================================
        // PHÂN HỆ ĐẶT LỊCH HẸN & LỊCH CỦA BỆNH NHÂN
        // =============================================================
        function moModalDatLich(bacSiId = null) {
            if (!AppState.token) {
                Swal.fire({ icon: 'warning', title: 'Yêu cầu đăng nhập', text: 'Bạn cần đăng nhập tài khoản trước khi đặt lịch!' });
                window.location.href = '/dang-nhap';
                return;
            }

            if (AppState.currentUser) {
                if (document.getElementById('modal-dl-ho-ten')) {
                    document.getElementById('modal-dl-ho-ten').value = AppState.currentUser.ho_ten || '';
                }
                if (document.getElementById('modal-dl-sdt')) {
                    document.getElementById('modal-dl-sdt').value = AppState.currentUser.so_dien_thoai || '0901234567';
                }
            }

            const selectEl = document.getElementById('modal-dl-bac-si');
            selectEl.innerHTML = AppState.danhSachBacSi.map(b => `<option value="${b.id}">${b.ho_ten} - ${b.chuyen_khoa ? (b.chuyen_khoa.ten_khoa || b.chuyen_khoa.ten_chuyen_khoa) : ''}</option>`).join('');

            if (bacSiId) selectEl.value = bacSiId;

            capNhatGiaKhamModal();
            moModal('modal-dat-lich');
        }

        function capNhatGiaKhamModal() {
            const bId = document.getElementById('modal-dl-bac-si').value;
            const bs = AppState.danhSachBacSi.find(b => b.id == bId);
            if (bs) {
                const gia = Number(bs.gia_kham || 200000).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-dl-gia-kham').textContent = gia;
            }
        }

        function chonSlot(el, start, end) {
            document.querySelectorAll('#modal-dl-slots .slot-btn').forEach(b => b.classList.remove('selected'));
            el.classList.add('selected');
            AppState.slotDaChon = { batDau: start + ':00', ketThuc: end + ':00' };
        }

        async function xacNhanDatLich() {
            const bacSiId = document.getElementById('modal-dl-bac-si').value;
            const hoTen = document.getElementById('modal-dl-ho-ten').value;
            const sdt = document.getElementById('modal-dl-sdt').value;
            const ngayKham = document.getElementById('modal-dl-ngay').value;
            const lyDo = document.getElementById('modal-dl-ly-do').value;

            if (!hoTen || !ngayKham) {
                showToast('error', 'Thiếu thông tin', 'Vui lòng nhập họ tên bệnh nhân và ngày khám.');
                return;
            }

            const payload = {
                bac_si_id: Number(bacSiId),
                ho_ten_benh_nhan: hoTen,
                so_dien_thoai: sdt,
                ngay_kham: ngayKham,
                gio_bat_dau: AppState.slotDaChon.batDau,
                gio_ket_thuc: AppState.slotDaChon.ketThuc,
                ly_do_kham: lyDo || 'Khám sức khỏe tổng quát'
            };

            const res = await goiApi('POST', '/api/v1/lich-hen/dat-lich', payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt lịch thành công!',
                    text: `Lịch hẹn mã #${res.data.du_lieu.id} đã được xác nhận.`,
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-dat-lich');
                await taiDanhSachLichHen();
                chuyenTab('tab-benh-nhan-lich');
            } else if (res.status === 409) {
                Swal.fire({
                    icon: 'error',
                    title: 'Trùng lịch khám (409 Conflict)',
                    text: res.data.thong_diep || 'Bác sĩ đã có ca khám trong khung giờ này, vui lòng chọn giờ khác!',
                    confirmButtonColor: '#0284c7'
                });
            } else {
                showToast('error', 'Thất bại', res.data.thong_diep || 'Không thể tạo lịch hẹn.');
            }
        }

        async function taiDanhSachLichHen() {
            const res = await goiApi('GET', '/api/v1/lich-hen');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachLichHen = res.data.du_lieu;
                renderLichHenBenhNhan(AppState.danhSachLichHen);
                renderCaKhamBacSi(AppState.danhSachLichHen);
                renderBacSiLichSu(AppState.danhSachLichHen);

                const statLh = document.getElementById('stat-so-lich-hen');
                if (statLh) statLh.textContent = AppState.danhSachLichHen.length;
            }
        }

        function renderLichHenBenhNhan(list) {
            const tbody = document.getElementById('tbody-benh-nhan-lich');
            if (!tbody) return;

            const u = AppState.currentUser;
            let displayList = list;
            if (u && u.vai_tro === 'BENH_NHAN') {
                displayList = list.filter(lh => {
                    return (lh.benh_nhan_id == u.id) || 
                           (lh.ho_ten_benh_nhan && lh.ho_ten_benh_nhan.toLowerCase() === (u.ho_ten || '').toLowerCase()) ||
                           (lh.so_dien_thoai && lh.so_dien_thoai === u.so_dien_thoai);
                });
                if (displayList.length === 0) displayList = list;
            }

            if (!displayList || displayList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-slate-400">Bạn chưa có lịch hẹn nào. Hãy đặt lịch khám mới!</td></tr>';
                return;
            }

            tbody.innerHTML = displayList.map(lh => {
                const bs = AppState.danhSachBacSi.find(b => b.id == lh.bac_si_id);
                const tenBs = bs ? bs.ho_ten : (`Bác sĩ ID #${lh.bac_si_id}`);
                const tenKhoa = (bs && bs.chuyen_khoa) ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                
                const canCancel = (lh.trang_thai === 'CHO_KHAM' || lh.trang_thai === 'DA_DAT' || !lh.trang_thai);
                const actionHtml = canCancel
                    ? `<button onclick="huyLichHenBenhNhan(${lh.id})" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition">Hủy Lịch</button>`
                    : `<span class="text-slate-400 text-xs">--</span>`;

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${lh.id}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${tenBs}</td>
                        <td class="py-3 px-4 text-slate-600">${tenKhoa}</td>
                        <td class="py-3 px-4 text-slate-600">${lh.ngay_kham}</td>
                        <td class="py-3 px-4 font-mono font-semibold text-medical-600">${lh.gio_bat_dau} - ${lh.gio_ket_thuc}</td>
                        <td class="py-3 px-4 text-slate-500">${lh.ly_do_kham || 'Khám tổng quát'}</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">${lh.trang_thai || 'CHO_KHAM'}</span>
                        </td>
                        <td class="py-3 px-4 text-center">${actionHtml}</td>
                    </tr>
                `;
            }).join('');
        }

        async function huyLichHenBenhNhan(id) {
            const confirm = await Swal.fire({
                title: 'Hủy lịch hẹn?',
                text: 'Bạn có chắc chắn muốn hủy lịch khám bệnh này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Đồng ý hủy',
                cancelButtonText: 'Không'
            });

            if (!confirm.isConfirmed) return;

            const res = await goiApi('PUT', `/api/v1/lich-hen/${id}/huy`, { ly_do: 'Bệnh nhân chủ động hủy' });
            if (res.ok) {
                showToast('success', 'Đã Hủy Lịch', 'Lịch hẹn đã được hủy thành công.');
                await taiDanhSachLichHen();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể hủy lịch.');
            }
        }

        // =============================================================
        // PHÂN HỆ BÁC SĨ KHÁM & CHỈ ĐỊNH CẬN LÂM SÀNG
        // =============================================================
        async function taiDanhSachDichVuCLS() {
            const res = await goiApi('GET', '/api/v1/dich-vu');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachDichVuCLS = res.data.du_lieu;
                renderDichVuCheckboxes();
                const statDv = document.getElementById('stat-so-dich-vu');
                if (statDv) statDv.textContent = AppState.danhSachDichVuCLS.length;
            }
        }

        function renderDichVuCheckboxes() {
            const container = document.getElementById('list-dich-vu-checkboxes');
            if (!container) return;

            container.innerHTML = AppState.danhSachDichVuCLS.map(dv => {
                const gia = Number(dv.don_gia || dv.gia_dich_vu || 100000).toLocaleString('vi-VN');
                return `
                    <label class="flex items-center justify-between p-2.5 bg-slate-50 hover:bg-sky-50/50 border border-slate-200 rounded-xl cursor-pointer transition">
                        <span class="flex items-center space-x-2 text-xs text-slate-700 font-medium">
                            <input type="checkbox" class="cb-dich-vu-cls w-4 h-4 text-medical-600 rounded focus:ring-medical-500 border-slate-300" value="${dv.id}" data-gia="${dv.don_gia || dv.gia_dich_vu || 100000}" onchange="tinhTongTienCLS()">
                            <span>${dv.ten_dich_vu}</span>
                        </span>
                        <span class="text-xs font-bold text-medical-700">${gia} đ</span>
                    </label>
                `;
            }).join('');
        }

        function tinhTongTienCLS() {
            let total = 0;
            document.querySelectorAll('.cb-dich-vu-cls:checked').forEach(cb => {
                total += Number(cb.dataset.gia || 0);
            });
            document.getElementById('subtotal-cls').textContent = total.toLocaleString('vi-VN') + ' đ';
        }

        function renderCaKhamBacSi(list) {
            const tbody = document.getElementById('tbody-ca-kham-bac-si');
            if (!tbody) return;

            let docList = list;
            const u = AppState.currentUser;
            if (u && u.vai_tro === 'BAC_SI') {
                const bs = AppState.danhSachBacSi.find(b => 
                    b.id == u.id || 
                    (b.ho_ten && u.ho_ten && b.ho_ten.toLowerCase().includes(u.ho_ten.toLowerCase())) ||
                    (u.ten_dang_nhap && b.ma_bac_si && b.ma_bac_si.toLowerCase().includes(u.ten_dang_nhap.toLowerCase()))
                );
                if (bs) docList = list.filter(lh => lh.bac_si_id == bs.id);
            }

            const waitingList = docList.filter(l => l.trang_thai !== 'HOAN_THANH' && l.trang_thai !== 'DA_HUY');

            if (waitingList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-slate-400 italic">Không có ca khám nào đang chờ.</td></tr>';
                return;
            }

            tbody.innerHTML = waitingList.map(lh => {
                const bnName = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3 font-bold text-slate-700">#${lh.id}</td>
                        <td class="py-2.5 px-3 font-extrabold text-slate-900">${bnName}</td>
                        <td class="py-2.5 px-3 font-mono text-medical-600 font-semibold">${lh.gio_bat_dau}</td>
                        <td class="py-2.5 px-3 text-center">
                            <button onclick="chuyenSangKhamCaNay(${lh.id})" class="px-3 py-1 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                                Tiếp Nhận
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function chuyenSangKhamCaNay(lichHenId) {
            const lh = AppState.danhSachLichHen.find(l => l.id == lichHenId);
            if (!lh) return;

            AppState.caKhamDangChon = lh;
            const bnName = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
            
            document.getElementById('box-chon-ca-kham-thong-tin').innerHTML = `
                <div class="font-extrabold text-medical-700">ĐANG TIẾP NHẬN CA KHÁM #${lh.id} - ${bnName}</div>
                <div class="text-slate-500 mt-1">Khám ngày: <strong>${lh.ngay_kham} (${lh.gio_bat_dau})</strong> | Triệu chứng: <strong>${lh.ly_do_kham || 'Khám bệnh'}</strong></div>
            `;

            showToast('info', 'Tiếp Nhận Ca Khám', `Đã nạp hồ sơ bệnh nhân: ${bnName}.`);
        }

        async function luuChiDinhCanLamSang() {
            if (!AppState.caKhamDangChon) {
                showToast('error', 'Chưa chọn ca khám', 'Vui lòng bấm "Tiếp Nhận" 1 ca khám từ danh sách chờ bên trái.');
                return;
            }

            const checked = Array.from(document.querySelectorAll('.cb-dich-vu-cls:checked')).map(c => Number(c.value));
            if (checked.length === 0) {
                showToast('warning', 'Chưa chọn dịch vụ', 'Hãy tích chọn ít nhất 1 dịch vụ cận lâm sàng.');
                return;
            }

            const payload = {
                lich_hen_id: AppState.caKhamDangChon.id,
                danh_sach_dich_vu_id: checked,
                chan_doan_so_bo: document.getElementById('input-chan-doan').value || 'Theo dõi lâm sàng'
            };

            const res = await goiApi('POST', '/api/v1/dich-vu/chi-dinh', payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Lưu chỉ định thành công!',
                    text: `Đã chỉ định ${checked.length} dịch vụ cho ca #${AppState.caKhamDangChon.id}. Đã chuyển sang Thu Ngân lập hóa đơn.`,
                    confirmButtonColor: '#0284c7'
                });
                document.querySelectorAll('.cb-dich-vu-cls').forEach(c => c.checked = false);
                tinhTongTienCLS();
                await taiDanhSachLichHen();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể lưu chỉ định.');
            }
        }

        function renderBacSiLichSu(list) {
            const tbody = document.getElementById('tbody-bac-si-lich-su');
            if (!tbody) return;

            let docList = list;
            const u = AppState.currentUser;
            if (u && u.vai_tro === 'BAC_SI') {
                const bs = AppState.danhSachBacSi.find(b => 
                    b.id == u.id || 
                    (b.ho_ten && u.ho_ten && b.ho_ten.toLowerCase().includes(u.ho_ten.toLowerCase()))
                );
                if (bs) docList = list.filter(lh => lh.bac_si_id == bs.id);
            }

            if (docList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-slate-400">Chưa có ca khám nào được ghi nhận hôm nay.</td></tr>';
                return;
            }

            tbody.innerHTML = docList.map(lh => {
                const bnName = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${lh.id}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${bnName}</td>
                        <td class="py-3 px-4 text-slate-600">${lh.so_dien_thoai || '--'}</td>
                        <td class="py-3 px-4 text-slate-600">${lh.ngay_kham}</td>
                        <td class="py-3 px-4 font-mono font-semibold text-medical-600">${lh.gio_bat_dau} - ${lh.gio_ket_thuc}</td>
                        <td class="py-3 px-4 text-slate-500">${lh.ly_do_kham || 'Khám bệnh'}</td>
                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-50 text-sky-700 border border-sky-200">${lh.trang_thai || 'CHO_KHAM'}</span></td>
                    </tr>
                `;
            }).join('');
        }

        // =============================================================
        // PHÂN HỆ ADMIN: BÁC SĨ, TÀI KHOẢN, THU NGÂN & GIÁM SÁT
        // =============================================================
        function renderAdminBacSiTable(list) {
            const tbody = document.getElementById('tbody-admin-bac-si');
            if (!tbody) return;

            tbody.innerHTML = list.map(b => {
                const khoa = b.chuyen_khoa ? (b.chuyen_khoa.ten_khoa || b.chuyen_khoa.ten_chuyen_khoa) : 'N/A';
                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">${b.ma_bac_si || ('BS' + b.id)}</td>
                        <td class="py-2.5 px-3 font-extrabold text-slate-900">${b.ho_ten}</td>
                        <td class="py-2.5 px-3 text-slate-600">${khoa}</td>
                        <td class="py-2.5 px-3 font-bold text-emerald-600">${Number(b.gia_kham || 0).toLocaleString('vi-VN')} đ</td>
                        <td class="py-2.5 px-3 font-medium text-slate-700">${b.phong_kham || 'P201'}</td>
                    </tr>
                `;
            }).join('');
        }

        function renderAdminChuyenKhoaTable() {
            const tbody = document.getElementById('tbody-admin-chuyen-khoa');
            if (!tbody) return;

            tbody.innerHTML = AppState.danhSachChuyenKhoa.map(ck => `
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">${ck.ma_khoa || ck.ma_chuyen_khoa}</td>
                    <td class="py-2.5 px-3 font-extrabold text-slate-900">${ck.ten_khoa || ck.ten_chuyen_khoa}</td>
                    <td class="py-2.5 px-3 text-slate-500">${ck.mo_ta || '--'}</td>
                </tr>
            `).join('');
        }

        async function taiDanhSachTaiKhoan() {
            const res = await goiApi('GET', '/api/v1/tai-khoan');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachTaiKhoan = res.data.du_lieu;
                renderAdminTaiKhoanTable(AppState.danhSachTaiKhoan);
            }
        }

        function renderAdminTaiKhoanTable(list) {
            const tbody = document.getElementById('tbody-admin-tai-khoan');
            if (!tbody) return;

            if (!list || list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-slate-400">Chưa có dữ liệu tài khoản.</td></tr>';
                return;
            }

            tbody.innerHTML = list.map(tk => {
                const vaiTro = tk.vai_tro ? (tk.vai_tro.ma_vai_tro || tk.vai_tro.ten_vai_tro) : 'BENH_NHAN';
                const isLocked = tk.trang_thai === 'KHOA';
                const statusBadge = isLocked
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ KHÓA</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">HOẠT ĐỘNG</span>';

                const lockBtnText = isLocked ? '🔓 Mở Khóa' : '🔒 Khóa';
                const lockBtnClass = isLocked ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100';
                const newStatus = isLocked ? 'HOAT_DONG' : 'KHOA';

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${tk.id}</td>
                        <td class="py-3 px-4 font-mono font-bold text-medical-700">${tk.ten_dang_nhap}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">${tk.ho_ten || '--'}</td>
                        <td class="py-3 px-4 text-slate-500">${tk.email || '--'}</td>
                        <td class="py-3 px-4 text-slate-600">${tk.so_dien_thoai || '--'}</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">${vaiTro}</span></td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <button onclick="moModalDoiMatKhauAdmin(${tk.id}, '${tk.ten_dang_nhap}', '${tk.ho_ten || ''}')" class="px-2.5 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Đổi / Đặt lại mật khẩu">
                                    <i class="fa-solid fa-key text-[10px]"></i>
                                    <span>Đổi MK</span>
                                </button>
                                <button onclick="doiTrangThaiTaiKhoan(${tk.id}, '${newStatus}')" class="px-2.5 py-1 border rounded-lg text-xs font-bold transition inline-flex items-center space-x-1 ${lockBtnClass}">
                                    <span>${lockBtnText}</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function doiTrangThaiTaiKhoan(id, trangThaiMoi) {
            const res = await goiApi('PATCH', `/api/v1/tai-khoan/${id}/trang-thai`, { trang_thai: trangThaiMoi });
            if (res.ok) {
                showToast('success', 'Thành Công', `Đã cập nhật trạng thái tài khoản #${id} thành ${trangThaiMoi}.`);
                await taiDanhSachTaiKhoan();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể đổi trạng thái.');
            }
        }

        async function moModalDoiMatKhauAdmin(id, username, fullname) {
            const { value: matKhauMoi } = await Swal.fire({
                title: 'Đặt Lại Mật Khẩu',
                html: `
                    <div class="text-left text-xs text-slate-500 mb-3 space-y-1">
                        <div>Tài khoản: <strong class="text-slate-800 font-mono">${username}</strong></div>
                        <div>Họ tên: <strong class="text-slate-800">${fullname || 'Người dùng'}</strong></div>
                    </div>
                    <div class="text-left">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu mới</label>
                        <input id="swal-input-mk-moi" type="password" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600" placeholder="Tối thiểu 6 ký tự...">
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check mr-1.5"></i> Lưu Mật Khẩu Mới',
                cancelButtonText: 'Hủy Bỏ',
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#94a3b8',
                preConfirm: () => {
                    const val = document.getElementById('swal-input-mk-moi').value;
                    if (!val || val.length < 6) {
                        Swal.showValidationMessage('Vui lòng nhập mật khẩu mới tối thiểu 6 ký tự.');
                        return false;
                    }
                    return val;
                }
            });

            if (matKhauMoi) {
                const res = await goiApi('PUT', `/api/v1/tai-khoan/${id}/doi-mat-khau`, { mat_khau_moi: matKhauMoi });
                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đổi mật khẩu thành công!',
                        text: `Mật khẩu mới cho tài khoản "${username}" đã được cập nhật thành công.`,
                        confirmButtonColor: '#0284c7'
                    });
                } else {
                    showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể đổi mật khẩu.');
                }
            }
        }

        // =============================================================
        // PHÂN HỆ THU NGÂN & HÓA ĐƠN (SERVICE 04)
        // =============================================================
        async function taiDanhSachHoaDon() {
            const res = await goiApi('GET', '/api/v1/hoa-don');
            if (res.ok && res.data && res.data.du_lieu) {
                AppState.danhSachHoaDon = res.data.du_lieu;
                renderHoaDonTable(AppState.danhSachHoaDon);
            }
        }

        function renderHoaDonTable(list) {
            const tbody = document.getElementById('tbody-hoa-don');
            if (!tbody) return;

            if (!list || list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-slate-400">Chưa có hóa đơn nào. Hãy tạo hóa đơn tự động!</td></tr>';
                return;
            }

            tbody.innerHTML = list.map(hd => {
                const daThu = (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID');
                const statusBadge = daThu
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ĐÃ THU</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">CHỜ THU</span>';

                const tongTien = Number(hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const thucThu = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const tienKham = Number(hd.tien_kham || 200000).toLocaleString('vi-VN') + ' đ';
                const tienCls = Number(hd.tien_dich_vu_cls || (hd.tong_tien - (hd.tien_kham || 200000)) || 0).toLocaleString('vi-VN') + ' đ';

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">${hd.ma_hoa_don || ('HD-' + hd.id)}</td>
                        <td class="py-3 px-4 text-slate-600 font-medium">#${hd.lich_hen_id}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${hd.ten_benh_nhan || 'Bệnh nhân'}</td>
                        <td class="py-3 px-4 text-slate-600">${tienKham}</td>
                        <td class="py-3 px-4 text-slate-600">${tienCls}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">${tongTien}</td>
                        <td class="py-3 px-4 font-extrabold text-emerald-600">${thucThu}</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="xemChiTietHoaDon(${hd.id})" class="px-3 py-1 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 rounded-lg text-xs font-bold transition">
                                Chi Tiết
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function moModalTaoHoaDonTuDong() {
            const selectEl = document.getElementById('modal-hd-lich-hen-id');
            selectEl.innerHTML = AppState.danhSachLichHen.map(l => {
                const bnName = l.benh_nhan ? l.benh_nhan.ho_ten : (l.ho_ten_benh_nhan || 'Bệnh nhân');
                return `<option value="${l.id}">Ca #${l.id} - ${bnName} (${l.ngay_kham})</option>`;
            }).join('');

            moModal('modal-tao-hoa-don');
        }

        async function xacNhanTaoHoaDonTuDong() {
            const lichHenId = document.getElementById('modal-hd-lich-hen-id').value;
            const giamGia = document.getElementById('modal-hd-giam-gia').value || 0;
            const ghiChu = document.getElementById('modal-hd-ghi-chu').value;

            if (!lichHenId) {
                showToast('error', 'Lỗi', 'Vui lòng chọn 1 lịch hẹn.');
                return;
            }

            const payload = {
                lich_hen_id: Number(lichHenId),
                giam_gia: Number(giamGia),
                ghi_chu: ghiChu
            };

            const res = await goiApi('POST', '/api/v1/hoa-don/tao-tu-dong', payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tổng hợp hóa đơn thành công!',
                    text: 'Hóa đơn đã được lập tự động từ Service 01 và Service 03.',
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-tao-hoa-don');
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể tạo hóa đơn.');
            }
        }

        async function xemChiTietHoaDon(hoaDonId) {
            AppState.hoaDonDangXemId = hoaDonId;
            const res = await goiApi('GET', `/api/v1/hoa-don/${hoaDonId}`);

            if (res.ok && res.data && res.data.du_lieu) {
                const hd = res.data.du_lieu;
                document.getElementById('modal-cthd-title').textContent = `Chi Tiết Hóa Đơn #${hd.ma_hoa_don || hd.id}`;
                document.getElementById('modal-cthd-benh-nhan').textContent = hd.ten_benh_nhan || 'Lê Văn Cường';
                document.getElementById('modal-cthd-ngay').textContent = hd.created_at ? hd.created_at.substring(0, 10) : 'Hôm nay';

                const daThu = (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID');
                document.getElementById('modal-cthd-trang-thai').innerHTML = daThu
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ĐÃ THU</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">CHỜ THU</span>';

                const itemsTbody = document.getElementById('modal-cthd-tbody-items');
                let rowsHtml = `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2 px-3 font-bold text-slate-800">Phí khám bác sĩ chuyên khoa</td>
                        <td class="py-2 px-3 text-center">1</td>
                        <td class="py-2 px-3 text-slate-600">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                        <td class="py-2 px-3 text-right font-bold text-slate-900">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                    </tr>
                `;

                if (hd.chi_tiet_hoa_don && hd.chi_tiet_hoa_don.length > 0) {
                    hd.chi_tiet_hoa_don.forEach(it => {
                        rowsHtml += `
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-2 px-3 text-slate-700">${it.ten_khoan_muc || 'Dịch vụ cận lâm sàng'}</td>
                                <td class="py-2 px-3 text-center">${it.so_luong || 1}</td>
                                <td class="py-2 px-3 text-slate-600">${Number(it.don_gia || 0).toLocaleString('vi-VN')} đ</td>
                                <td class="py-2 px-3 text-right font-bold text-slate-900">${Number(it.thanh_tien || 0).toLocaleString('vi-VN')} đ</td>
                            </tr>
                        `;
                    });
                }

                itemsTbody.innerHTML = rowsHtml;

                document.getElementById('modal-cthd-tien-kham').textContent = Number(hd.tien_kham || 200000).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-tien-cls').textContent = Number(hd.tien_dich_vu_cls || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-giam-tru').textContent = '-' + Number(hd.giam_gia || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-thuc-thu').textContent = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';

                const btnPay = document.getElementById('btn-xac-nhan-thanh-toan');
                const boxPay = document.getElementById('box-thanh-toan-actions');
                if (daThu) {
                    btnPay.style.display = 'none';
                    boxPay.style.display = 'none';
                } else {
                    btnPay.style.display = 'inline-flex';
                    boxPay.style.display = 'block';
                }

                moModal('modal-chi-tiet-hoa-don');
            }
        }

        function chonPhuongThuc(btn, pt) {
            document.querySelectorAll('#box-thanh-toan-actions button').forEach(b => {
                b.className = 'py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold rounded-xl text-center transition';
            });
            btn.className = 'py-2 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 font-bold rounded-xl text-center transition';
            AppState.phuongThucThanhToan = pt;
        }

        async function xacNhanThanhToanHoaDonHienTai() {
            if (!AppState.hoaDonDangXemId) return;

            const res = await goiApi('PUT', `/api/v1/hoa-don/${AppState.hoaDonDangXemId}/thanh-toan`, {
                phuong_thuc_thanh_toan: AppState.phuongThucThanhToan
            });

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thu tiền thành công!',
                    text: `Hóa đơn #${AppState.hoaDonDangXemId} đã hoàn tất thanh toán.`,
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-chi-tiet-hoa-don');
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể cập nhật thanh toán.');
            }
        }

        async function taiBaoCaoDoanhThu() {
            const res = await goiApi('GET', '/api/v1/hoa-don/thong-ke');
            if (res.ok && res.data && res.data.du_lieu) {
                const dt = res.data.du_lieu;
                const tong = Number(dt.tong_doanh_thu || 0).toLocaleString('vi-VN') + ' đ';
                const statDt = document.getElementById('stat-tong-doanh-thu');
                if (statDt) statDt.textContent = tong;
            }
        }

        // =============================================================
        // MODALS ADMIN THÊM BÁC SĨ & CHUYÊN KHOA
        // =============================================================
        function moModalThemBacSi() {
            moModal('modal-them-bac-si');
        }

        async function xacNhanThemBacSi() {
            const hoTen = document.getElementById('modal-tbs-ho-ten').value;
            const chuyenKhoaId = document.getElementById('modal-tbs-chuyen-khoa').value;
            const hocVi = document.getElementById('modal-tbs-hoc-vi').value;
            const giaKham = document.getElementById('modal-tbs-gia-kham').value;
            const phong = document.getElementById('modal-tbs-phong').value;
            const username = document.getElementById('modal-tbs-username').value;
            const matKhau = document.getElementById('modal-tbs-pass').value;

            if (!hoTen || !chuyenKhoaId) {
                showToast('error', 'Thiếu dữ liệu', 'Vui lòng nhập họ tên bác sĩ và chọn chuyên khoa.');
                return;
            }

            const payload = {
                ho_ten: hoTen,
                chuyen_khoa_id: Number(chuyenKhoaId),
                hoc_vi: hocVi,
                gia_kham: Number(giaKham || 200000),
                phong_kham: phong,
                ten_dang_nhap: username,
                mat_khau: matKhau
            };

            const res = await goiApi('POST', '/api/v1/bac-si', payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thêm bác sĩ thành công!',
                    text: `Đã tạo hồ sơ và cấp tài khoản cho BS. ${hoTen}`,
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-them-bac-si');
                await taiDanhSachBacSi();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể tạo bác sĩ mới.');
            }
        }

        function moModalThemChuyenKhoa() {
            moModal('modal-them-chuyen-khoa');
        }

        async function xacNhanThemChuyenKhoa() {
            const ma = document.getElementById('modal-tck-ma').value;
            const ten = document.getElementById('modal-tck-ten').value;
            const moTa = document.getElementById('modal-tck-mo-ta').value;

            if (!ma || !ten) {
                showToast('error', 'Thiếu dữ liệu', 'Vui lòng nhập mã và tên chuyên khoa.');
                return;
            }

            const payload = {
                ma_khoa: ma.toUpperCase(),
                ten_khoa: ten,
                mo_ta: moTa
            };

            const res = await goiApi('POST', '/api/v1/bac-si/chuyen-khoa', payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thêm chuyên khoa thành công!',
                    text: `Khoa ${ten} đã được thêm vào hệ thống.`,
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-them-chuyen-khoa');
                await taiDanhSachChuyenKhoa();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể tạo chuyên khoa.');
            }
        }

        // =============================================================
        // HẠ TẦNG & HEALTH CHECK 4 SERVICES (ADMIN)
        // =============================================================
        async function kiemTraHealthToanHeThong() {
            const res = await goiApi('GET', '/api/v1/health');
            if (res.ok && res.data && res.data.danh_sach_dich_vu) {
                const list = res.data.danh_sach_dich_vu;
                capNhatBadgeService('badge-svc-1', list['01_dich_vu_xac_thuc_bac_si']);
                capNhatBadgeService('badge-svc-2', list['02_dich_vu_benh_nhan_lich_hen']);
                capNhatBadgeService('badge-svc-3', list['03_dich_vu_y_te_can_lam_sang']);
                capNhatBadgeService('badge-svc-4', list['04_dich_vu_hoa_don_thanh_toan']);

                showToast('success', 'Hệ Thống Trực Tuyến', 'Cả 4 Microservices đều đang ONLINE!');
            }
        }

        function capNhatBadgeService(elementId, svcData) {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (svcData && svcData.trang_thai === 'ONLINE') {
                el.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                el.textContent = `ONLINE (${svcData.do_tre_ms || 0}ms)`;
            } else {
                el.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200';
                el.textContent = 'OFFLINE';
            }
        }

        // TIỆN ÍCH MODAL & TOAST SWEETALERT2
        function moModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('show');
        }

        function dongModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('show');
        }

        function showToast(type, title, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: type,
                title: `${title} - ${message}`
            });
        }
    </script>
</body>
</html>
