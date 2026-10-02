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

    <!-- Chart.js 4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        .sidebar-item.active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        }
        .sidebar-item.active i {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex bg-slate-100 text-slate-800 font-sans">

    <!-- ================================================================= -->
    <!-- LEFT SIDEBAR: THANH MENU BÊN TRÁI ĐẦY ĐỦ TẤT CẢ CHỨC NĂNG       -->
    <!-- ================================================================= -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 h-screen border-r border-slate-800 z-50 select-none shadow-2xl">
        <!-- Logo & Brand Header -->
        <div class="p-4 border-b border-slate-800 flex items-center space-x-3 bg-slate-950/60">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-medical-500 to-sky-600 flex items-center justify-center text-white text-lg shadow-lg shadow-medical-500/25 flex-shrink-0">
                <i class="fa-solid fa-hospital"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="font-extrabold text-sm text-white tracking-tight truncate">Phòng Khám Đa Khoa</h1>
                <p class="text-[10px] font-bold text-sky-400 tracking-wider uppercase flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Cổng Quản Trị Hệ Thống</span>
                </p>
            </div>
        </div>

        <!-- Menu Navigation (Scrollable) -->
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-5 sidebar-scroll">
            <!-- Nhóm 1: TỔNG QUAN (Chỉ ADMIN) -->
            <div id="nav-group-admin-giam-sat" class="role-nav-group" data-roles="ADMIN">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tổng Quan</p>
                <div class="space-y-1">
                    <button class="sidebar-item active w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-giam-sat" onclick="chuyenTab('tab-admin-giam-sat', this)">
                        <i class="fa-solid fa-chart-pie text-sm text-purple-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Thống Kê & Giám Sát</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 2: ĐẶT LỊCH & BỆNH NHÂN (BENH_NHAN, BAC_SI & ADMIN) -->
            <div id="nav-group-benh-nhan" class="role-nav-group" data-roles="BENH_NHAN,ADMIN,BAC_SI">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lịch Hẹn & Bệnh Nhân</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-benh-nhan-lich" onclick="chuyenTab('tab-benh-nhan-lich', this)">
                        <i class="fa-solid fa-calendar-days text-sm text-sky-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span id="nav-label-benh-nhan-lich">Quản Lý Lịch Khám</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-benh-nhan" onclick="chuyenTab('tab-benh-nhan', this)">
                        <i class="fa-regular fa-calendar-plus text-sm text-medical-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Tra Cứu & Đặt Lịch</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-bang-gia-kham" onclick="chuyenTab('tab-bang-gia-kham', this)">
                        <i class="fa-solid fa-receipt text-sm text-amber-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Bảng Giá Khám Bệnh</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-ho-so-gia-dinh" onclick="chuyenTab('tab-ho-so-gia-dinh', this)">
                        <i class="fa-solid fa-people-roof text-sm text-emerald-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Hồ Sơ Gia Đình</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-benh-nhan" onclick="chuyenTab('tab-admin-benh-nhan', this)">
                        <i class="fa-solid fa-hospital-user text-sm text-rose-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Quản Lý Bệnh Nhân & EHR</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 3: BÀN KHÁM BÁC SĨ (BAC_SI & ADMIN) -->
            <div id="nav-group-bac-si" class="role-nav-group" data-roles="BAC_SI,ADMIN">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Bàn Khám Bác Sĩ</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-bac-si" onclick="chuyenTab('tab-bac-si', this)">
                        <i class="fa-solid fa-stethoscope text-sm text-teal-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Khám & Kê Cận Lâm Sàng</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-bac-si-lich-su" onclick="chuyenTab('tab-bac-si-lich-su', this)">
                        <i class="fa-solid fa-clipboard-user text-sm text-emerald-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Danh Sách Ca Khám Hôm Nay</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" onclick="moModalLichTrucBacSiHienTai()">
                        <i class="fa-solid fa-calendar-week text-sm text-sky-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Đăng Ký & Lịch Trực Của Tôi</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 4: THU NGÂN & VIỆN PHÍ (Chỉ ADMIN) -->
            <div id="nav-group-thu-ngan" class="role-nav-group" data-roles="ADMIN">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Viện Phí & Tài Chính</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-thu-ngan" onclick="chuyenTab('tab-admin-thu-ngan', this)">
                        <i class="fa-solid fa-file-invoice-dollar text-sm text-amber-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Thu Ngân & Hóa Đơn</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 5: QUẢN TRỊ HỆ THỐNG (Chỉ ADMIN) -->
            <div id="nav-group-admin-he-thong" class="role-nav-group" data-roles="ADMIN">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Quản Trị Hệ Thống</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-bac-si" onclick="chuyenTab('tab-admin-bac-si', this)">
                        <i class="fa-solid fa-user-doctor text-sm text-blue-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Bác Sĩ & Chuyên Khoa</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-tai-khoan" onclick="chuyenTab('tab-admin-tai-khoan', this)">
                        <i class="fa-solid fa-users-gear text-sm text-indigo-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Quản Trị Tài Khoản</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer: Profile & Logout -->
        <div class="p-3.5 border-t border-slate-800 bg-slate-950/70 space-y-2 flex-shrink-0">
            <div onclick="moModalHoSoCaNhan()" class="flex items-center space-x-2.5 px-2 py-1.5 rounded-xl hover:bg-slate-800/80 cursor-pointer transition group" title="Nhấn để xem & chỉnh sửa hồ sơ">
                <div class="relative flex-shrink-0">
                    <img id="sidebar-user-avatar-img" src="" alt="Avatar" class="w-9 h-9 rounded-xl object-cover border border-slate-700 shadow-md hidden">
                    <div id="sidebar-user-avatar-icon" class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-slate-900"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="header-user-name" class="text-xs font-bold text-white truncate group-hover:text-sky-300 transition">Đang tải...</p>
                    <span id="header-user-role" class="px-2 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-sky-400 border border-slate-700 inline-block">ADMIN</span>
                </div>
                <i class="fa-solid fa-gear text-slate-500 group-hover:text-white text-xs transition"></i>
            </div>
            <div class="grid grid-cols-2 gap-1.5">
                <button onclick="moModalHoSoCaNhan()" class="flex items-center justify-center space-x-1.5 py-1.5 px-2 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl text-[11px] font-semibold border border-slate-700 transition" title="Hồ sơ cá nhân">
                    <i class="fa-solid fa-id-card text-sky-400"></i>
                    <span>Hồ Sơ</span>
                </button>
                <button onclick="xuLyDangXuatGateway()" class="flex items-center justify-center space-x-1.5 py-1.5 px-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 rounded-xl text-[11px] font-semibold border border-rose-500/20 transition" title="Đăng xuất">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Đăng Xuất</span>
                </button>
            </div>
        </div>
    </aside>

    <!-- ============================================================= -->
    <!-- RIGHT MAIN WORKSPACE CONTENT                                  -->
    <!-- ============================================================= -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
        <!-- Top Sticky Sub-Header -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-6 flex items-center justify-between z-30 flex-shrink-0 shadow-xs">
            <div class="flex items-center space-x-3 min-w-0">
                <h2 id="current-page-title" class="text-base font-extrabold text-slate-800 flex items-center gap-2 truncate">
                    <i class="fa-solid fa-chart-pie text-purple-600"></i>
                    <span>Tổng Quan & Giám Sát Hệ Thống</span>
                </h2>
                <span class="text-slate-300 hidden sm:inline">|</span>
                <span id="brand-subtitle" class="text-xs font-medium text-slate-500 hidden md:inline truncate">Phòng Khám Đa Khoa</span>
            </div>

            <div class="flex items-center space-x-2 flex-shrink-0">
                <!-- Nút Liên Kết Nhanh Hồ Sơ Gia Đình Trên Header -->
                <button onclick="chuyenTab('tab-ho-so-gia-dinh')" class="flex items-center space-x-2 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold transition shadow-2xs hover:scale-105 active:scale-95" title="Mở danh sách Hồ Sơ Gia Đình & Người Thân">
                    <i class="fa-solid fa-people-roof text-emerald-600"></i>
                    <span class="hidden sm:inline">Hồ Sơ Gia Đình</span>
                </button>

                <button onclick="moModalHoSoCaNhan()" class="flex items-center space-x-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition" title="Hồ sơ tài khoản cá nhân">
                    <img id="top-user-avatar-img" src="" alt="Avatar" class="w-6 h-6 rounded-lg object-cover hidden">
                    <i id="top-user-avatar-icon" class="fa-solid fa-user-circle text-sky-600 text-sm"></i>
                    <span id="top-user-name" class="hidden sm:inline">Hồ Sơ & Avatar</span>
                </button>
            </div>
        </header>

        <!-- Main Body Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">

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
                <div class="flex items-center gap-3 z-10 flex-wrap">
                    <button onclick="moModalDatLich(null)" class="px-6 py-3 bg-white hover:bg-sky-50 text-medical-700 font-extrabold text-sm rounded-2xl shadow-lg shadow-slate-900/10 transition transform hover:-translate-y-0.5 flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-plus text-medical-600"></i>
                        <span>Đặt Lịch Khám Ngay</span>
                    </button>
                    <button onclick="chuyenTab('tab-ho-so-gia-dinh')" class="px-5 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/30 text-white font-extrabold text-sm rounded-2xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center space-x-2">
                        <i class="fa-solid fa-people-roof text-emerald-300"></i>
                        <span>Hồ Sơ Gia Đình</span>
                    </button>
                </div>
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
                        <select id="filter-doctor-ca" onchange="locDanhSachBacSi()" class="px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-700 font-medium">
                            <option value="">Tất cả ca khám</option>
                            <option value="CA_SANG">☀️ Ca Sáng (07:30 - 11:30)</option>
                            <option value="CA_CHIEU">⛅ Ca Chiều (13:30 - 17:00)</option>
                            <option value="CA_TOI">🌙 Ca Tối (17:30 - 20:30)</option>
                            <option value="CA_NGAY">⭐ Cả Ngày (07:30 - 17:00)</option>
                        </select>
                    </div>
                </div>

                <!-- Doctor Cards Grid -->
                <div id="doctors-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <p class="text-xs text-slate-400 italic">Đang tải dữ liệu bác sĩ...</p>
                </div>
            </div>
        </section>

        <!-- TAB 1.2: LỊCH KHÁM (NGƯỜI 2) -->
        <section id="tab-benh-nhan-lich" class="portal-section space-y-6">
            <!-- Widget Liên Kết Hồ Sơ Nhanh (Giao Diện Chính) -->
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-sky-600 rounded-3xl p-6 text-white shadow-xl shadow-emerald-600/10 flex flex-col md:flex-row items-center justify-between gap-5 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                <div class="flex items-center space-x-4 z-10">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-2xl text-white shadow-inner flex-shrink-0">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-white/20 text-emerald-100 border border-white/20">Hồ Sơ Y Tế</span>
                            <span class="text-xs text-emerald-100 font-semibold" id="quick-banner-so-thanh-vien">Quản lý sức khỏe cả gia đình</span>
                        </div>
                        <h3 class="text-lg font-black text-white mt-1">Hồ Sơ Sức Khỏe Gia Đình & Người Thân</h3>
                        <p class="text-xs text-emerald-100/90 mt-0.5">Lưu trữ thông tin nhóm máu, tiền sử dị ứng thuốc, bệnh án điện tử và đặt lịch khám 1-click cho người thân.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap z-10 flex-shrink-0">
                    <button onclick="chuyenTab('tab-ho-so-gia-dinh')" class="px-4 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 rounded-2xl text-xs font-extrabold shadow-sm transition hover:scale-105 active:scale-95 flex items-center space-x-2">
                        <i class="fa-solid fa-address-card text-emerald-600"></i>
                        <span>Xem Hồ Sơ Gia Đình</span>
                    </button>
                    <button onclick="moModalThemNguoiThan()" class="px-4 py-2.5 bg-emerald-500/40 hover:bg-emerald-500/60 border border-white/20 text-white rounded-2xl text-xs font-bold transition hover:scale-105 active:scale-95 flex items-center space-x-2">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>+ Thêm Người Thân</span>
                    </button>
                    <button onclick="moModalHoSoCaNhan()" class="px-3.5 py-2.5 bg-slate-900/30 hover:bg-slate-900/50 border border-white/10 text-white rounded-2xl text-xs font-bold transition flex items-center space-x-1.5" title="Hồ sơ tài khoản cá nhân">
                        <i class="fa-solid fa-id-badge"></i>
                        <span>Hồ Sơ Cá Nhân</span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-list-check text-sky-600"></i>
                            <span id="title-danh-sach-lich-kham">Quản Lý Lịch Khám Bệnh</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Theo dõi thời gian, bệnh nhân, bác sĩ phụ trách, dời lịch và tình trạng ca khám</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="chuyenTab('tab-ho-so-gia-dinh')" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-2 rounded-xl border border-emerald-200 transition flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-people-roof text-emerald-600"></i>
                            <span>Hồ Sơ Gia Đình</span>
                        </button>
                    </div>
                </div>

                <!-- Table Appointments -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">Mã Lịch</th>
                                <th class="py-3.5 px-4">Bệnh Nhân</th>
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
                            <tr><td colspan="9" class="text-center py-8 text-slate-400">Đang tải lịch hẹn...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 1.3: QUẢN LÝ HỒ SƠ GIA ĐÌNH & NGƯỜI THÂN (FAMILY EHR)     -->
        <!-- ============================================================= -->
        <section id="tab-ho-so-gia-dinh" class="portal-section space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-50 via-white to-teal-50 p-6 sm:p-8 rounded-3xl border border-emerald-100 shadow-xs">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-people-roof"></i> Hồ Sơ Y Tế Gia Đình (Family EHR)
                    </div>
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                        <i class="fa-solid fa-house-chimney-medical text-emerald-600"></i>
                        <span>Quản Lý Hồ Sơ Bệnh Nhân Gia Đình</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                        Tài khoản duy nhất quản lý hồ sơ khám cho cả gia đình (Con cái, Cha/Mẹ, Vợ/Chồng). Đặt lịch khám và theo dõi lịch sử y tế chỉ với 1 cú click!
                    </p>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-emerald-500/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Thêm Người Thân Mới</span>
                    </button>
                    <button type="button" onclick="taiVaRenderHoSoGiaDinh(true)" class="px-3.5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-2xl border border-slate-200 transition shadow-xs flex items-center gap-1.5" title="Làm mới">
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>Làm Mới</span>
                    </button>
                </div>
            </div>

            <!-- KPI Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-3xl border border-emerald-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tổng Thành Viên</p>
                        <h4 id="stat-gd-tong-thanh-vien" class="text-2xl font-black text-slate-900 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                            <i class="fa-solid fa-address-book"></i> Hồ sơ quản lý
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-sky-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Người Thân Bảo Hộ</p>
                        <h4 id="stat-gd-nguoi-than" class="text-2xl font-black text-sky-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-600 mt-1">
                            <i class="fa-solid fa-children"></i> Con cái, Bố/Mẹ, Vợ/Chồng
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-purple-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lượt Khám Gia Đình</p>
                        <h4 id="stat-gd-luot-kham" class="text-2xl font-black text-purple-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-purple-600 mt-1">
                            <i class="fa-solid fa-calendar-check"></i> Ca khám đã đặt
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </div>

            <!-- Family Cards Container -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-id-card-clip text-emerald-600"></i>
                            <span>Danh Sách Thành Viên Trong Gia Đình</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bấm nút "Đặt Lịch Khám" để lập tức đặt ca khám cho người thân</p>
                    </div>
                    <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm Người Thân</span>
                    </button>
                </div>

                <div id="container-the-gia-dinh" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <p class="text-xs text-slate-400 italic">Đang nạp hồ sơ gia đình...</p>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 1.4: QUẢN LÝ BỆNH NHÂN & HỒ SƠ BỆNH ÁN ĐIỆN TỬ (EHR/EMR)  -->
        <!-- ============================================================= -->
        <section id="tab-admin-benh-nhan" class="portal-section space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-rose-50 via-white to-sky-50 p-6 rounded-3xl border border-rose-100/80 shadow-xs">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-heart-pulse animate-pulse"></i> Phân Hệ Quản Lý Hồ Sơ Bệnh Án Điện Tử (EHR / EMR)
                    </div>
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                        <i class="fa-solid fa-hospital-user text-rose-600"></i>
                        <span>Quản Lý Bệnh Nhân & Lịch Sử Thăm Khám</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                        Theo dõi hồ sơ bệnh nhân trọn đời, thông tin bác sĩ phụ trách, khung giờ khám 30 phút, chẩn đoán bệnh lý, đơn thuốc điện tử và chỉ định tái khám theo chuẩn y khoa.
                    </p>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <button type="button" onclick="moModalThemSuaBenhNhan()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-rose-500/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Thêm Bệnh Nhân Mới</span>
                    </button>
                    <button type="button" onclick="taiDanhSachBenhNhan(true)" class="px-3.5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-2xl border border-slate-200 transition shadow-xs flex items-center gap-1.5" title="Làm mới dữ liệu">
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>Làm Mới</span>
                    </button>
                </div>
            </div>

            <!-- KPI Stats Cards (4 cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Tổng số BN -->
                <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tổng Hồ Sơ Bệnh Nhân</p>
                        <h4 id="qlbn-stat-tong-bn" class="text-2xl font-black text-slate-900 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 mt-1">
                            <i class="fa-solid fa-address-book"></i> Hồ sơ quản lý
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <!-- Card 2: Ca Khám Hôm Nay -->
                <div class="bg-white p-5 rounded-3xl border border-sky-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lượt Khám Hôm Nay</p>
                        <h4 id="qlbn-stat-kham-hom-nay" class="text-2xl font-black text-sky-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-600 mt-1">
                            <i class="fa-regular fa-calendar-check"></i> Ca khám trong ngày
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>

                <!-- Card 3: Đang Chờ Khám -->
                <div class="bg-white p-5 rounded-3xl border border-amber-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bệnh Nhân Chờ Khám</p>
                        <h4 id="qlbn-stat-dang-cho" class="text-2xl font-black text-amber-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 mt-1">
                            <i class="fa-solid fa-hourglass-half"></i> Tiếp nhận / Chờ phòng
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                </div>

                <!-- Card 4: Tái Khám -->
                <div class="bg-white p-5 rounded-3xl border border-emerald-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Có Lịch Tái Khám</p>
                        <h4 id="qlbn-stat-tai-kham" class="text-2xl font-black text-emerald-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                            <i class="fa-solid fa-notes-medical"></i> Kê đơn & Hẹn tái khám
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </div>

            <!-- Smart Filter Bar -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                    <!-- Search input (4 cols) -->
                    <div class="lg:col-span-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" id="qlbn-tim-kiem" oninput="locDanhSachBenhNhan()" placeholder="Tìm theo Mã BN, Họ tên, SĐT, CCCD, Bệnh..." class="w-full pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        <button type="button" onclick="document.getElementById('qlbn-tim-kiem').value=''; locDanhSachBenhNhan();" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-slate-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>

                    <!-- Bác sĩ phụ trách (3 cols) -->
                    <div class="lg:col-span-3">
                        <select id="qlbn-loc-bac-si" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            <option value="">Tất cả Bác Sĩ Thăm Khám</option>
                        </select>
                    </div>

                    <!-- Khung giờ khám (2 cols) -->
                    <div class="lg:col-span-2">
                        <select id="qlbn-loc-khung-gio" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            <option value="">Tất cả Khung Giờ</option>
                            <option value="SANG">Buổi Sáng (07:30 - 11:30)</option>
                            <option value="CHIEU">Buổi Chiều (13:30 - 17:30)</option>
                        </select>
                    </div>

                    <!-- Trạng thái (3 cols) -->
                    <div class="lg:col-span-3">
                        <select id="qlbn-loc-trang-thai" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            <option value="">Tất cả Trạng Thái Khám</option>
                            <option value="HOAN_THANH">Đã Khám (Hoàn Thành)</option>
                            <option value="DANG_KHAM">Đang Trong Phòng Khám</option>
                            <option value="DA_XAC_NHAN">Đã Xác Nhận / Chờ Khám</option>
                            <option value="CHO_XAC_NHAN">Chờ Xác Nhận Check-in</option>
                            <option value="DA_HUY">Đã Hủy Ca Khám</option>
                            <option value="CO_TAI_KHAM">Có Lịch Hẹn Tái Khám</option>
                        </select>
                    </div>
                </div>

                <!-- Chips bộ lọc nhanh -->
                <div class="flex items-center gap-2 flex-wrap text-xs pt-1 border-t border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">Bộ lọc nhanh:</span>
                    <button type="button" onclick="datBoLocNhanh('ALL')" class="btn-quick-filter px-3 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">Tất cả</button>
                    <button type="button" onclick="datBoLocNhanh('HOM_NAY')" class="btn-quick-filter px-3 py-1 rounded-xl text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition">Khám hôm nay</button>
                    <button type="button" onclick="datBoLocNhanh('CHO_KHAM')" class="btn-quick-filter px-3 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Đang chờ khám</button>
                    <button type="button" onclick="datBoLocNhanh('DA_KHAM')" class="btn-quick-filter px-3 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">Đã khám xong</button>
                    <button type="button" onclick="datBoLocNhanh('CANH_BAO')" class="btn-quick-filter px-3 py-1 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 transition">Có cảnh báo dị ứng</button>
                </div>
            </div>

            <!-- Patient EHR Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                                <th class="py-3.5 px-4">Bệnh Nhân</th>
                                <th class="py-3.5 px-4">Liên Hệ & Y Tế</th>
                                <th class="py-3.5 px-4">Bác Sĩ Khám</th>
                                <th class="py-3.5 px-4">Khung Giờ & Ngày</th>
                                <th class="py-3.5 px-4">Chẩn Đoán / Bị Bệnh Gì</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                                <th class="py-3.5 px-4 text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-danh-sach-benh-nhan" class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-rose-500 mb-2"></i>
                                    <div>Đang đồng bộ dữ liệu hồ sơ bệnh nhân & bệnh án điện tử...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary Bar -->
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Hiển thị <span id="qlbn-count-hien-thi" class="font-bold text-slate-800">0</span> / <span id="qlbn-count-tong" class="font-bold text-slate-800">0</span> hồ sơ bệnh nhân
                    </div>
                    <div class="text-[11px] text-slate-400">
                        <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> Dữ liệu hồ sơ bệnh án được bảo mật theo tiêu chuẩn y tế
                    </div>
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
                <div class="flex flex-wrap items-center gap-2">
                    <button onclick="moModalLichTrucBacSiHienTai()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white flex items-center space-x-1.5 transition shadow-sm" title="Quản lý ca trực trong tuần của bạn">
                        <i class="fa-solid fa-calendar-days text-sky-200"></i>
                        <span>Lịch Trực Của Tôi</span>
                    </button>
                    <button onclick="moModalHoSoCaNhan()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white flex items-center space-x-1.5 transition shadow-sm" title="Cập nhật thông tin cá nhân và ảnh đại diện">
                        <i class="fa-solid fa-user-pen text-sky-200"></i>
                        <span>Hồ Sơ & Avatar</span>
                    </button>
                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/30 backdrop-blur-sm border border-emerald-400/40 text-emerald-100 flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>ĐANG TIẾP NHẬN</span>
                    </span>
                </div>
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

                    <!-- Subtotal & Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-slate-500 uppercase">TỔNG PHÍ CẬN LÂM SÀNG:</span>
                            <div id="subtotal-cls" class="text-xl font-extrabold text-emerald-600">0 đ</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="inHoaDonKhamBenhCaHienTai()" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md shadow-sky-600/20 transition flex items-center space-x-1.5" title="In phiếu thu / Hóa đơn viện phí khám bệnh">
                                <i class="fa-solid fa-print"></i>
                                <span>In Hóa Đơn Khám</span>
                            </button>
                            <button type="button" onclick="luuChiDinhCanLamSang()" class="px-5 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-medical-600/25 transition flex items-center space-x-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Lưu Chỉ Định & Gửi Thu Ngân</span>
                            </button>
                        </div>
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
                                    <th class="py-2.5 px-3 text-center">Thao Tác</th>
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
                    </div>
                    <div class="overflow-y-auto max-h-[380px] rounded-2xl border border-slate-200/80">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Tên Khoa</th>
                                    <th class="py-2.5 px-3">Mô Tả</th>
                                    <th class="py-2.5 px-3 text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin-chuyen-khoa" class="divide-y divide-slate-100 text-slate-700">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BẢNG PHÊ DUYỆT LỊCH TRỰC BÁC SĨ (YÊU CẦU ĐĂNG KÝ MỚI & ĐỔI CA) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                                <span>Phê Duyệt Lịch Trực Bác Sĩ (Đăng Ký & Đổi Ca)</span>
                                <span id="badge-admin-count-cho-duyet" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white shadow-xs">0 chờ duyệt</span>
                            </h3>
                            <p class="text-[11px] text-slate-500">Mỗi ngày bác sĩ chỉ trực 1 ca (Sáng, Chiều, Tối hoặc Cả Ngày). Yêu cầu đăng ký mới hoặc đổi ca cần Admin duyệt để kích hoạt.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <select id="filter-admin-duyet-trang-thai" onchange="taiVaRenderDanhSachDuyetCaTruc()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                            <option value="CHO_DUYET">Chờ Admin Duyệt</option>
                            <option value="HOAT_DONG">Đã Duyệt (Hoạt Động)</option>
                            <option value="TU_CHOI">Bị Từ Chối</option>
                            <option value="">Tất cả trạng thái</option>
                        </select>
                        <button type="button" onclick="taiVaRenderDanhSachDuyetCaTruc()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
                            <i class="fa-solid fa-arrows-rotate"></i> Làm mới
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                <th class="py-2.5 px-3">Bác Sĩ</th>
                                <th class="py-2.5 px-3">Thứ / Ngày</th>
                                <th class="py-2.5 px-3">Ca Trực</th>
                                <th class="py-2.5 px-3">Khung Giờ</th>
                                <th class="py-2.5 px-3">Phòng Khám</th>
                                <th class="py-2.5 px-3">Khám Tối Đa</th>
                                <th class="py-2.5 px-3">Trạng Thái</th>
                                <th class="py-2.5 px-3 text-center">Thao Tác Phê Duyệt</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-admin-duyet-lich-truc" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="8" class="text-center py-6 text-slate-400">Đang tải danh sách ca trực chờ duyệt...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- TAB: BẢNG GIÁ KHÁM BỆNH & DỊCH VỤ KHÁM CHUYÊN KHOA (MEMBER 1) -->
        <section id="tab-bang-gia-kham" class="portal-section space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-medical-900 rounded-3xl p-6 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-xl text-amber-400 shadow-md">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-amber-300 tracking-wider uppercase">Biểu Phí Khám Bệnh Niêm Yết</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Công Khai & Minh Bạch</span>
                        </div>
                        <h2 class="text-xl font-extrabold tracking-tight">Bảng Giá Khám Bệnh & Danh Mục Phí Khám Chuyên Khoa</h2>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="taiBangGiaKham()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl border border-white/20 transition flex items-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Làm Mới</span>
                    </button>
                    <button onclick="inBangGiaKham()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs rounded-xl shadow transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-print"></i>
                        <span>In / Xuất Biểu Phí</span>
                    </button>
                </div>
            </div>

            <!-- 4 Thẻ Thống Kê Nhanh -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tổng Bác Sĩ Tiếp Nhận</p>
                        <h4 id="stat-bgk-tong-bs" class="text-lg font-black text-slate-900 mt-0.5">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-arrow-down text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Khám Tiêu Chuẩn Từ</p>
                        <h4 id="stat-bgk-min" class="text-lg font-black text-emerald-600 mt-0.5 font-mono">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-crown text-amber-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Khám Chuyên Gia / GS</p>
                        <h4 id="stat-bgk-max" class="text-lg font-black text-purple-700 mt-0.5 font-mono">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-scale-balanced text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mức Phí Bình Quân</p>
                        <h4 id="stat-bgk-avg" class="text-lg font-black text-amber-600 mt-0.5 font-mono">--</h4>
                    </div>
                </div>
            </div>

            <!-- Bảng Dữ Liệu Biểu Phí & Bộ Lọc -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-medical-50 text-medical-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-table-list"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Chi Tiết Biểu Phí Khám Bệnh Từng Bác Sĩ</h3>
                            <p class="text-[11px] text-slate-500">Giá niêm yết áp dụng cho mỗi lượt khám chuyên khoa ban đầu (chưa bao gồm chỉ định cận lâm sàng)</p>
                        </div>
                    </div>

                    <!-- Bộ lọc đa tiêu chí -->
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="relative min-w-[200px]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="filter-bgk-tu-khoa" oninput="locBangGiaKham()" placeholder="Tìm bác sĩ, học vị, phòng..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600">
                        </div>

                        <select id="filter-bgk-chuyen-khoa" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả chuyên khoa</option>
                        </select>

                        <select id="filter-bgk-phan-khuc" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả phân khúc</option>
                            <option value="Khám Tiêu Chuẩn">Khám Tiêu Chuẩn</option>
                            <option value="Khám Thạc Sĩ / CKI">Khám Thạc Sĩ / CKI</option>
                            <option value="Khám Chuyên Gia / CKII">Khám Chuyên Gia / CKII</option>
                            <option value="Khám Phó Giáo Sư">Khám Phó Giáo Sư</option>
                            <option value="Khám Giáo Sư / Chuyên Gia Đầu Ngành">Khám Giáo Sư</option>
                            <option value="Khám Dịch Vụ VIP">Khám Dịch Vụ VIP</option>
                        </select>

                        <select id="filter-bgk-muc-gia" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả mức giá</option>
                            <option value="DUOI_200">Dưới 200.000 VNĐ</option>
                            <option value="200_300">200.000đ - 300.000 VNĐ</option>
                            <option value="TREN_300">Trên 300.000 VNĐ</option>
                        </select>
                    </div>
                </div>

                <!-- Bảng Hiển Thị Biểu Phí -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                <th class="py-2.5 px-3.5 text-center w-12">STT</th>
                                <th class="py-2.5 px-3.5">Bác Sĩ Khám Bệnh</th>
                                <th class="py-2.5 px-3.5">Chuyên Khoa</th>
                                <th class="py-2.5 px-3.5">Học Vị & Kinh Nghiệm</th>
                                <th class="py-2.5 px-3.5">Phòng Khám</th>
                                <th class="py-2.5 px-3.5">Hạng / Loại Khám</th>
                                <th class="py-2.5 px-3.5">Đơn Giá Niêm Yết</th>
                                <th class="py-2.5 px-3.5 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-bang-gia-kham" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="8" class="text-center py-8 text-slate-400">Đang tải bảng giá khám bệnh...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Chân bảng lưu ý chuẩn y tế -->
                <div class="p-3 bg-amber-50/60 rounded-2xl border border-amber-200/60 flex items-start space-x-2 text-[11px] text-amber-800">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <div>
                        <strong>Quy định niêm yết viện phí:</strong> Giá khám bệnh trên đã bao gồm tiền công khám lâm sàng, tư vấn chẩn đoán ban đầu. Đối với bệnh nhân đặt lịch hẹn trước trực tuyến, mức giá được bảo đảm giữ nguyên không phát sinh phụ thu giờ cao điểm.
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
            <!-- 5 Metric Cards (Bác Sĩ, Lịch Hẹn, Dịch Vụ, Doanh Thu, Hồ Sơ Bệnh Nhân) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5 hover:border-medical-400 hover:shadow-md transition cursor-pointer group" onclick="chuyenTab('tab-admin-bac-si')" title="Xem Danh Sách Bác Sĩ">
                    <div class="w-12 h-12 rounded-xl bg-medical-50 text-medical-600 border border-medical-200 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bác Sĩ Công Tác</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-bac-si">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5 hover:border-sky-400 hover:shadow-md transition cursor-pointer group" onclick="chuyenTab('tab-benh-nhan-lich')" title="Xem Lịch Khám Toàn Viện">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lịch Hẹn Hôm Nay</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-lich-hen">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5 hover:border-rose-400 hover:shadow-md transition cursor-pointer group" onclick="chuyenTab('tab-admin-benh-nhan')" title="Xem Quản Lý Bệnh Nhân & EHR">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Quản Lý Bệnh Nhân</div>
                        <div class="text-xl sm:text-2xl font-black text-rose-600" id="stat-so-ho-so-bn">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5 hover:border-amber-400 hover:shadow-md transition cursor-pointer group" onclick="chuyenTab('tab-bac-si')" title="Xem Danh Mục Cận Lâm Sàng">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dịch Vụ CLS</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-dich-vu">5</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5 hover:border-emerald-400 hover:shadow-md transition cursor-pointer group" onclick="chuyenTab('tab-admin-thu-ngan')" title="Xem Thu Ngân & Viện Phí">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tổng Thu Viện Phí</div>
                        <div class="text-xl sm:text-2xl font-black text-emerald-600 truncate" id="stat-tong-doanh-thu">--</div>
                    </div>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- KHU VỰC BIỂU ĐỒ THỐNG KÊ & PHÂN TÍCH CHUYÊN SÂU (CHART.JS)   -->
            <!-- ============================================================= -->
            <div class="space-y-6">
                <!-- Header Thống Kê & Bộ Lọc Thời Gian -->
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4 border border-slate-800">
                    <div>
                        <div class="flex items-center space-x-2 text-medical-400 text-xs font-bold uppercase tracking-wider mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Trực Quan Hóa Dữ Liệu Thời Gian Thực</span>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-white tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-sky-400"></i>
                            <span>Trung Tâm Biểu Đồ Thống Kê & Giám Sát</span>
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Phân tích chuyên sâu doanh thu viện phí, cơ cấu thanh toán, nhu cầu khám theo khoa và tiến độ tiếp đón bệnh nhân.</p>
                    </div>

                    <div class="flex items-center flex-wrap gap-2.5">
                        <!-- Bộ chọn khoảng thời gian -->
                        <div class="inline-flex p-1 bg-slate-800/90 rounded-2xl border border-slate-700/80 text-xs">
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('7_NGAY', this)" class="btn-chart-filter px-3.5 py-1.5 rounded-xl font-bold transition text-white bg-medical-600 shadow-sm" id="btn-chart-7ngay">7 Ngày</button>
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('30_NGAY', this)" class="btn-chart-filter px-3.5 py-1.5 rounded-xl font-bold transition text-slate-400 hover:text-white" id="btn-chart-30ngay">30 Ngày</button>
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('TAT_CA', this)" class="btn-chart-filter px-3.5 py-1.5 rounded-xl font-bold transition text-slate-400 hover:text-white" id="btn-chart-tatca">Toàn Bộ</button>
                        </div>

                        <!-- Nút làm mới biểu đồ -->
                        <button onclick="lamMoiTatCaBieuDo()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold border border-white/10 flex items-center space-x-2 transition hover:scale-105 active:scale-95 shadow-sm">
                            <i class="fa-solid fa-arrows-rotate text-sky-400" id="icon-refresh-chart"></i>
                            <span>Làm Mới Dữ Liệu</span>
                        </button>
                    </div>
                </div>

                <!-- Hàng 1: 2 Biểu đồ chính (Doanh Thu Viện Phí & Cơ Cấu Thanh Toán) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Biểu đồ 1: Doanh Thu & Lượt Khám (Line / Area Chart) -->
                    <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                    <i class="fa-solid fa-chart-area text-medical-600"></i>
                                    <span>Xu Hướng Doanh Thu Viện Phí & Tiếp Đón Bệnh Nhân</span>
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Biến động số tiền thanh toán thực thu (VNĐ) và số lượt khám qua các ngày</p>
                            </div>
                            <div class="flex items-center space-x-3 text-xs font-semibold">
                                <span class="inline-flex items-center text-sky-600"><span class="w-3 h-3 rounded-full bg-sky-500 mr-1.5 inline-block"></span> Doanh thu (VNĐ)</span>
                                <span class="inline-flex items-center text-purple-600"><span class="w-3 h-3 rounded-full bg-purple-500 mr-1.5 inline-block"></span> Lượt khám</span>
                            </div>
                        </div>
                        
                        <div class="relative w-full h-72 sm:h-80 pt-4">
                            <canvas id="chart-doanh-thu-lich-hen"></canvas>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 mt-2 text-center">
                            <div class="p-2.5 bg-sky-50/60 rounded-2xl border border-sky-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Doanh Thu TB/Ngày</span>
                                <span class="text-xs sm:text-sm font-extrabold text-sky-700" id="stat-chart-dt-tb">--</span>
                            </div>
                            <div class="p-2.5 bg-purple-50/60 rounded-2xl border border-purple-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Lượt Khám Cao Nhất</span>
                                <span class="text-xs sm:text-sm font-extrabold text-purple-700" id="stat-chart-kham-max">--</span>
                            </div>
                            <div class="p-2.5 bg-emerald-50/60 rounded-2xl border border-emerald-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Tỷ Lệ Thu Thành Công</span>
                                <span class="text-xs sm:text-sm font-extrabold text-emerald-700" id="stat-chart-ty-le-thu">100%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ 2: Cơ Cấu Phương Thức Thanh Toán (Doughnut Chart) -->
                    <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-4 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-chart-pie text-emerald-600"></i>
                                <span>Cơ Cấu Phương Thức Thanh Toán</span>
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tỷ lệ thanh toán viện phí qua các kênh</p>
                        </div>

                        <div class="relative w-full h-64 flex items-center justify-center pt-2">
                            <canvas id="chart-phuong-thuc-thanh-toan"></canvas>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-2 space-y-2 text-xs" id="legend-phuong-thuc-container">
                            <!-- Dynamic legend rendered by JS -->
                        </div>
                    </div>
                </div>

                <!-- Hàng 2: 3 Biểu đồ chuyên sâu (Chuyên Khoa, Trạng Thái Khám, Giám Sát Lưu Lượng Services) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Biểu đồ 3: Lượt Khám Theo Chuyên Khoa (Bar Chart) -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-hospital text-blue-600"></i>
                                <span>Nhu Cầu Khám Theo Chuyên Khoa</span>
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Số lượt đặt khám phân bổ theo các chuyên khoa</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-chuyen-khoa"></canvas>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/70 text-[11px] text-slate-500 mt-3 flex items-center justify-between">
                            <span>Khoa có lượt khám cao nhất:</span>
                            <span class="font-extrabold text-slate-800" id="badge-top-khoa">Đang tải...</span>
                        </div>
                    </div>

                    <!-- Biểu đồ 4: Trạng Thái Lịch Khám & Tiến Độ (Polar / Doughnut Chart) -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-list-check text-amber-500"></i>
                                <span>Tình Trạng Lịch Hẹn Khám Bệnh</span>
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tiến độ tiếp đón và hoàn thành ca khám</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-trang-thai-lich"></canvas>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/70 text-[11px] text-slate-500 mt-3 flex items-center justify-between">
                            <span>Tỷ lệ hoàn thành khám bệnh:</span>
                            <span class="font-extrabold text-emerald-600" id="badge-ty-le-hoan-thanh">--%</span>
                        </div>
                    </div>

                    <!-- Biểu đồ 5: Phân Bổ Tải & Request Của 4 Microservices (Giám Sát Hạ Tầng) -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-server text-teal-600"></i>
                                <span>Phân Phối Lưu Lượng 4 Microservices</span>
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Giám sát tải trọng và điều phối qua API Gateway</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-traffic-microservices"></canvas>
                        </div>

                        <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200/70 text-[11px] text-emerald-800 mt-3 flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> 4 Services hoạt động ổn định</span>
                            <span class="font-mono font-black text-emerald-700">100% ONLINE</span>
                        </div>
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
    </div>

    <!-- ============================================================= -->
    <!-- MODALS HỆ THỐNG                                               -->
    <!-- ============================================================= -->

    <!-- MODAL: HỒ SƠ TÀI KHOẢN CÁ NHÂN & CẬP NHẬT THÔNG TIN          -->
    <div class="modal-backdrop" id="modal-ho-so-ca-nhan">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <form id="form-ho-so-ca-nhan" onsubmit="event.preventDefault(); xacNhanCapNhatHoSo();" autocomplete="off" class="flex flex-col h-full overflow-hidden">
                <!-- Header Modal -->
                <div class="bg-gradient-to-r from-sky-50 via-white to-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-medical-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-medical-500/20">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <span>Hồ Sơ Thông Tin Tài Khoản</span>
                            </h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Quản lý thông tin định danh cá nhân, ảnh đại diện và liên kết y tế</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-ho-so-ca-nhan')" class="text-slate-400 hover:text-slate-600 text-base p-1 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Body Modal (Scrollable) -->
                <div class="p-6 space-y-5 overflow-y-auto flex-1 text-xs">
                    <!-- Avatar & User Headline Card -->
                    <div class="p-4 bg-slate-50/90 rounded-2xl border border-slate-200/80 flex items-center justify-between gap-4">
                        <div class="flex items-center space-x-3.5">
                            <div class="relative group">
                                <img id="profile-modal-avatar-preview" src="" alt="Avatar" class="w-16 h-16 rounded-2xl object-cover border-2 border-medical-500 shadow-md hidden">
                                <div id="profile-modal-avatar-placeholder" class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 text-white flex items-center justify-center text-xl font-black shadow-md">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <label for="profile-modal-avatar-input" class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-medical-600 hover:bg-medical-700 text-white flex items-center justify-center text-[10px] shadow cursor-pointer transition">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input type="file" id="profile-modal-avatar-input" accept="image/*" class="hidden" onchange="xuLyChonAvatar(this)">
                            </div>
                            <div>
                                <h4 id="profile-modal-display-name" class="text-base font-extrabold text-slate-900">Người Dùng</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span id="profile-modal-display-role" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-medical-100 text-medical-800 border border-medical-200 inline-block">VAI TRÒ</span>
                                    <span class="text-[11px] text-slate-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đang hoạt động</span>
                                </div>
                            </div>
                        </div>

                        <label for="profile-modal-avatar-input" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold cursor-pointer transition shadow-2xs flex items-center gap-1.5">
                            <i class="fa-solid fa-upload text-medical-600"></i>
                            <span>Đổi Avatar</span>
                        </label>
                    </div>



                    <!-- Personal Information Form Fields -->
                    <div class="space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Tên đăng nhập (Username):</label>
                                <input type="text" id="profile-username" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-mono font-bold cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Vai trò hệ thống:</label>
                                <input type="text" id="profile-role" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-bold uppercase cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Họ và tên: <span class="text-rose-500">*</span></label>
                                <input type="text" id="profile-ho-ten" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            </div>
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Số điện thoại:</label>
                                <input type="text" id="profile-sdt" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Địa chỉ Email: <span class="text-rose-500">*</span></label>
                                <input type="email" id="profile-email" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            </div>
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Ngày sinh:</label>
                                <input type="date" id="profile-ngay-sinh" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Giới tính:</label>
                                <select id="profile-gioi-tinh" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                                    <option value="NAM">Nam</option>
                                    <option value="NU">Nữ</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 text-[10px] mb-1">Địa chỉ liên hệ:</label>
                                <input type="text" id="profile-dia-chi" placeholder="Số nhà, đường, quận/huyện..." class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            </div>
                        </div>

                        <!-- Extra fields for Doctor -->
                        <div id="profile-doctor-extra-fields" class="p-3.5 bg-sky-50/60 rounded-2xl border border-sky-100 space-y-3 hidden">
                            <h5 class="font-extrabold text-sky-900 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-user-doctor text-medical-600"></i>
                                <span>Thông Tin Chuyên Môn Bác Sĩ</span>
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Học vị:</label>
                                    <input type="text" id="profile-doctor-hoc-vi" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Phòng khám phụ trách:</label>
                                    <input type="text" id="profile-doctor-phong" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                            </div>
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Kinh nghiệm công tác:</label>
                                <textarea id="profile-doctor-kinh-nghiem" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-slate-800 text-xs"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2 flex-shrink-0">
                    <button type="button" onclick="dongModal('modal-ho-so-ca-nhan')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                        Đóng
                    </button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu Thay Đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 1: ĐẶT LỊCH HẸN (BỆNH NHÂN) -->
    <div class="modal-backdrop" id="modal-dat-lich">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <form id="form-dat-lich" onsubmit="event.preventDefault(); xacNhanDatLich();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-plus text-medical-600"></i>
                        <span>Đặt Lịch Hẹn Khám Bệnh Trực Tuyến</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-dat-lich')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <!-- BƯỚC 1 & 2: CHỌN CHUYÊN KHOA TRƯỚC, RỒI MỚI CHỌN BÁC SĨ THUỘC KHOA ĐÓ -->
                    <div class="p-3.5 bg-slate-50/90 border border-slate-200 rounded-2xl space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1 text-[11px] flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-medical-600 text-white text-[10px] inline-flex items-center justify-center font-bold">1</span>
                                    <span>Chọn Chuyên Khoa:</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <select id="modal-dl-chuyen-khoa" onchange="locBacSiTheoChuyenKhoaModal()" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-semibold text-xs transition shadow-xs">
                                    <option value="">-- Tất Cả Chuyên Khoa --</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1 text-[11px] flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-medical-600 text-white text-[10px] inline-flex items-center justify-center font-bold">2</span>
                                    <span>Chọn Bác Sĩ Điều Trị:</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <select id="modal-dl-bac-si" onchange="capNhatGiaKhamModal(); taiSlotsKhaDungDatLich();" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-semibold text-xs transition shadow-xs">
                                    <!-- Dynamic lọc theo chuyên khoa đã chọn -->
                                </select>
                            </div>
                        </div>

                        <!-- Doctor summary & price badge -->
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200/70 text-xs">
                            <div class="flex items-center space-x-2 text-slate-600">
                                <i class="fa-solid fa-user-doctor text-medical-600"></i>
                                <span id="modal-dl-bac-si-info" class="font-medium text-[11px]">Chuyên khoa: Đang chọn</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <span class="text-slate-500 text-[11px]">Giá niêm yết:</span>
                                <span id="modal-dl-gia-kham" class="font-black text-medical-700 text-xs bg-medical-100/70 px-2 py-0.5 rounded-lg border border-medical-200">200,000 đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- TÍNH NĂNG ĐẶT LỊCH CHO NGƯỜI THÂN (Medpro & YouMed Style) -->
                    <div class="p-3 bg-sky-50/60 border border-sky-200/80 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-900 uppercase tracking-wider">
                                <i class="fa-solid fa-people-roof text-sky-600 mr-1"></i> Đối Tượng Khám Bệnh:
                            </span>
                            <div class="inline-flex p-0.5 bg-sky-100 rounded-xl text-[11px] font-bold">
                                <button type="button" id="btn-tab-ban-than" onclick="chuyenDoiTuongKham('BAN_THAN')" class="px-2.5 py-1 rounded-lg bg-white text-sky-700 shadow-sm transition">
                                    Bản Thân
                                </button>
                                <button type="button" id="btn-tab-nguoi-than" onclick="chuyenDoiTuongKham('NGUOI_THAN')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition">
                                    Người Thân
                                </button>
                            </div>
                        </div>

                        <!-- Dropdown chọn hồ sơ gia đình -->
                        <div id="khu-vuc-nguoi-than" class="hidden pt-1.5 border-t border-sky-200/60 space-y-2">
                            <div class="flex items-center gap-2">
                                <select id="modal-dl-chon-ho-so" onchange="chonHoSoGiaDinhDropdown(this.value)" class="w-full px-3 py-1.5 text-xs bg-white border border-sky-300 rounded-xl font-medium text-slate-800 focus:ring-2 focus:ring-sky-500">
                                    <option value="MOI">+ Tạo hồ sơ người thân mới (Con cái, Bố/Mẹ...)</option>
                                </select>
                            </div>
                            <div id="form-nguoi-than-moi" class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-0.5">Mối Quan Hệ:</label>
                                    <select id="modal-dl-quan-he" onchange="capNhatNhanNguoiThan()" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-800 font-semibold">
                                        <option value="CON">Con cái (Bé nhỏ / Thanh thiếu niên)</option>
                                        <option value="CHA_ME">Bố / Mẹ</option>
                                        <option value="VO_CHONG">Vợ / Chồng</option>
                                        <option value="NGUOI_THAN">Người thân khác</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-0.5">Ngày Sinh Người Thân:</label>
                                    <input type="date" id="modal-dl-ngay-sinh-nt" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1" id="lbl-ho-ten-bn">Họ tên Bệnh nhân:</label>
                            <input type="text" id="modal-dl-ho-ten" autocomplete="off" placeholder="Nhập họ tên..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="text" id="modal-dl-sdt" autocomplete="off" placeholder="Nhập số điện thoại..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày khám:</label>
                        <input type="date" id="modal-dl-ngay" onchange="taiSlotsKhaDungDatLich(); kiemTraCaTrucDatLich();" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                    </div>

                    <!-- THÔNG TIN CA TRỰC BÁC SĨ (LIÊN KẾT PHÂN HỆ ĐẶT LỊCH) -->
                    <div id="modal-dl-ca-truc-box" class="p-3.5 rounded-2xl border text-xs transition-all hidden">
                        <!-- Dynamic rendered shift info -->
                    </div>

                    <!-- BỘ CHỌN KHUNG GIỜ KHÁM THỜI GIAN THỰC (Realtime Slots - BookingCare Style) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="font-bold uppercase tracking-wider text-slate-700 text-xs">Khung Giờ Khám (Ca 30 phút):</label>
                            <span id="modal-dl-slots-status" class="text-[11px] font-bold text-sky-600 flex items-center gap-1">
                                <i class="fa-solid fa-bolt text-amber-500"></i> <span id="modal-dl-slots-count">Đang kiểm tra slot...</span>
                            </span>
                        </div>

                        <div id="modal-dl-slots" class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2.5">
                            <div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-sun text-amber-500"></i> Buổi Sáng (08:00 - 11:30)
                                </div>
                                <div class="grid grid-cols-4 gap-2" id="modal-dl-slots-sang">
                                    <!-- Render động từ API slots-kha-dung -->
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-200/70">
                                <div class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-cloud-sun text-sky-500"></i> Buổi Chiều (13:30 - 16:30)
                                </div>
                                <div class="grid grid-cols-4 gap-2" id="modal-dl-slots-chieu">
                                    <!-- Render động từ API slots-kha-dung -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOX CAM KẾT PHIÊN KHÁM & QUY CHẾ TIẾP NHẬN TRÁNH DELAY DOMINO -->
                    <div id="modal-dl-cam-ket-box" class="p-3.5 bg-gradient-to-r from-sky-50 via-indigo-50/50 to-emerald-50/60 border border-sky-200/80 rounded-2xl space-y-2.5 text-xs shadow-xs">
                        <div class="flex items-center justify-between pb-2 border-b border-sky-100">
                            <span class="font-extrabold text-sky-950 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-heart text-sky-600 text-sm"></i>
                                <span>Cam Kết Phiên Khám & Tiếp Nhận Ưu Tiên</span>
                            </span>
                            <span id="badge-stt-du-kien" class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-sky-200 text-sky-900 border border-sky-300">
                                STT dự kiến: #01 (Ca Sáng)
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-700 space-y-1.5">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-emerald-800">Cam kết khám xong trong buổi:</strong> Quý khách đăng ký <span id="lbl-phien-kham" class="font-bold text-sky-700">Buổi Sáng (08:00 - 11:30)</span> được phòng khám <strong>chắc chắn hoàn thành khám 100% trong buổi này</strong> (trước giờ nghỉ của ca).
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-amber-500 text-xs mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-amber-800">Quy chế tiếp nhận linh hoạt (Giải tỏa trễ dây chuyền):</strong> Khung giờ <span id="lbl-khung-gio-chon" class="font-bold text-slate-900">08:00 - 08:30</span> là khung giờ có mặt để tiếp đón và đo sinh hiệu. Vì lý do chuyên môn (ca khám trước có thể cần hội chẩn kỹ hơn), giờ vào khám thực tế có thể dao động linh hoạt ±10-15 phút.
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-bell text-rose-500 text-xs mt-0.5 flex-shrink-0"></i>
                                <div class="text-slate-500">
                                    Quý khách vui lòng có mặt trước giờ hẹn <strong>10 - 15 phút</strong> tại quầy tiếp đón để được lấy số ưu tiên.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Lý do khám / Triệu chứng:</label>
                        <input type="text" id="modal-dl-ly-do" autocomplete="off" placeholder="Khám tổng quát, nhức đầu, sốt..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>

                    <!-- ACCORDION HỒ SƠ BỆNH ÁN ĐIỆN TỬ (BỆNH NHÂN ĐIỀN ĐỂ BÁC SĨ CHẨN ĐOÁN) -->
                    <div class="border border-sky-200 rounded-2xl bg-sky-50/50 overflow-hidden">
                        <button type="button" onclick="toggleDashboardEhrAccordion()" class="w-full px-4 py-2.5 text-left font-bold text-xs text-sky-900 flex justify-between items-center hover:bg-sky-100/60 transition">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-notes-medical text-sky-600"></i>
                                <span>Bệnh Án Điện Tử & Tiền Sử Bệnh (Bệnh nhân điền để Bác sĩ xem)</span>
                            </span>
                            <i id="dashboard-ehr-arrow" class="fa-solid fa-chevron-down text-sky-600 transition-transform"></i>
                        </button>

                        <div id="dashboard-ehr-accordion" class="p-3.5 pt-2 space-y-3 bg-white border-t border-sky-100 text-xs">
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Nhóm Máu:</label>
                                    <select id="modal-dl-nhom-mau" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 font-semibold focus:bg-white">
                                        <option value="">-- Chưa rõ --</option>
                                        <option value="A">Nhóm máu A</option>
                                        <option value="B">Nhóm máu B</option>
                                        <option value="AB">Nhóm máu AB</option>
                                        <option value="O">Nhóm máu O</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Số CCCD / CMND:</label>
                                    <input type="text" id="modal-dl-cccd" autocomplete="off" placeholder="079xxxxxxxx" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i>
                                    Tiền Sử Dị Ứng Thuốc / Kháng Sinh / Thức Ăn:
                                </label>
                                <textarea id="modal-dl-tien-su-di-ung" rows="2" placeholder="Ghi rõ: dị ứng Penicillin, Paracetamol, tôm cua, kháng sinh..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                    <i class="fa-solid fa-heart-pulse text-sky-500 mr-1"></i>
                                    Bệnh Lý Nền Mãn Tính (Tim mạch, tiểu đường, huyết áp...):
                                </label>
                                <textarea id="modal-dl-tien-su-benh" rows="2" placeholder="Ví dụ: Cao huyết áp 2 năm, viêm loét dạ dày, tiểu đường tuýp 2..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 pt-1 border-t border-slate-100">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Người Thân Khẩn Cấp:</label>
                                    <input type="text" id="modal-dl-nguoi-than" autocomplete="off" placeholder="Họ tên người thân" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">SĐT Khẩn Cấp:</label>
                                    <input type="tel" id="modal-dl-sdt-khan-cap" autocomplete="off" placeholder="09xxxxxxxx" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TỆP / ẢNH Y TẾ ĐÍNH KÈM (ĐƠN THUỐC CŨ, KẾT QUẢ XÉT NGHIỆM) -->
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <label class="block font-bold uppercase tracking-wider text-slate-700 text-[11px] flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-paperclip text-medical-600"></i>
                                <span>Đính Kèm Ảnh Y Tế (Đơn thuốc cũ, xét nghiệm, ảnh triệu chứng)</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-normal">Tùy chọn</span>
                        </label>
                        <input type="file" id="modal-dl-files" multiple accept="image/*,.pdf" onchange="xuLyChonTepYTe(this)" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-medical-50 file:text-medical-700 hover:file:bg-medical-100 cursor-pointer">
                        <div id="modal-dl-file-preview" class="flex flex-wrap gap-2 pt-1 empty:hidden"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-dat-lich')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác Nhận Đặt Lịch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DỜI LỊCH HẸN KHÁM (RESCHEDULE) -->
    <div class="modal-backdrop" id="modal-doi-lich">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <form id="form-doi-lich" onsubmit="event.preventDefault(); xacNhanDoiLich();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-clock-rotate-left text-sky-600"></i>
                        <span>Dời Lịch Hẹn Khám Bệnh (Reschedule)</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-doi-lich')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <input type="hidden" id="modal-doi-lich-id">
                    
                    <div class="p-3.5 bg-sky-50/70 border border-sky-100 rounded-2xl space-y-1">
                        <div class="text-[11px] font-bold text-sky-800 uppercase tracking-wider">Thông Tin Ca Khám Hiện Tại</div>
                        <div class="text-slate-700">Mã ca: <strong id="modal-doi-lich-ma" class="font-mono text-sky-700 font-bold">#--</strong></div>
                        <div class="text-slate-700">Bác sĩ: <strong id="modal-doi-lich-bs">--</strong></div>
                        <div class="text-slate-700">Thời gian hẹn cũ: <strong id="modal-doi-lich-tg-cu" class="text-slate-900">--</strong></div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Chọn Ngày Khám Mới:</label>
                        <input type="date" id="modal-doi-lich-ngay" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1.5">Chọn Khung Giờ Mới (Ca 30 phút):</label>
                        <div class="grid grid-cols-4 gap-2" id="modal-doi-lich-slots">
                            <button type="button" class="slot-btn-reschedule selected py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '08:00', '08:30')">08:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '08:30', '09:00')">08:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '09:00', '09:30')">09:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '09:30', '10:00')">09:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '10:00', '10:30')">10:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '14:00', '14:30')">14:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '14:30', '15:00')">14:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '15:00', '15:30')">15:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '15:30', '16:00')">15:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono text-[11px] transition" onclick="chonSlotDoiLich(this, '16:00', '16:30')">16:00</button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Lý do xin dời lịch:</label>
                        <input type="text" id="modal-doi-lich-ly-do" autocomplete="off" placeholder="Bận công việc, trùng lịch cá nhân, kẹt xe..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-[11px] flex items-start gap-2">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                        <span>Lịch dời sẽ được thuật toán tự động đối chiếu để đảm bảo không bị trùng slot của bác sĩ.</span>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-doi-lich')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác Nhận Dời Giờ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL XEM TỆP / ẢNH Y TẾ ĐÍNH KÈM -->
    <div class="modal-backdrop" id="modal-xem-tep-y-te">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-paperclip text-medical-600"></i>
                    <span>Hồ Sơ Y Tế & Đơn Thuốc Đính Kèm</span>
                </h3>
                <button onclick="dongModal('modal-xem-tep-y-te')" class="text-slate-400 hover:text-slate-600 text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                <div id="modal-tep-y-te-content" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dynamic images/files -->
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end">
                <button onclick="dongModal('modal-xem-tep-y-te')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TẠO HÓA ĐƠN TỰ ĐỘNG (ADMIN) -->
    <div class="modal-backdrop" id="modal-tao-hoa-don">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <form id="form-tao-hoa-don" onsubmit="event.preventDefault(); xacNhanTaoHoaDonTuDong();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        <span>Tự Động Tổng Hợp Hóa Đơn Liên Dịch Vụ</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-tao-hoa-don')" class="text-slate-400 hover:text-slate-600 text-base">
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
                    <button type="button" onclick="dongModal('modal-tao-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Tự Động Tính & Tạo Hóa Đơn</span>
                    </button>
                </div>
            </form>
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
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="inHoaDonTuChiTietModal()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow transition flex items-center space-x-1.5" title="In phiếu thu / Hóa đơn viện phí">
                    <i class="fa-solid fa-print"></i>
                    <span>In Hóa Đơn Này</span>
                </button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="dongModal('modal-chi-tiet-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
                    <button id="btn-xac-nhan-thanh-toan" onclick="xacNhanThanhToanHoaDonHienTai()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác Nhận Thu Tiền</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 4: THÊM BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-bac-si">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <form id="form-them-bac-si" onsubmit="event.preventDefault(); xacNhanThemBacSi();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-user-doctor text-medical-600"></i>
                        <span>Thêm Bác Sĩ Mới (Admin)</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-them-bac-si')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ và tên Bác sĩ:</label>
                        <input type="text" id="modal-tbs-ho-ten" autocomplete="off" placeholder="Ví dụ: BS. CKII Hoàng Minh Tuấn" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
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
                            <input type="text" id="modal-tbs-hoc-vi" autocomplete="off" placeholder="Bác sĩ Chuyên khoa II" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giá khám (VND):</label>
                            <input type="number" id="modal-tbs-gia-kham" value="250000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số phòng khám:</label>
                            <input type="text" id="modal-tbs-phong" autocomplete="off" placeholder="P302" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tên đăng nhập:</label>
                            <input type="text" id="modal-tbs-username" autocomplete="off" placeholder="bshminh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mật khẩu ban đầu:</label>
                            <input type="password" id="modal-tbs-pass" autocomplete="new-password" placeholder="Mặc định: 123456" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-bac-si')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-xl shadow-md shadow-medical-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Tạo Hồ Sơ Bác Sĩ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: THÊM CHUYÊN KHOA (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-chuyen-khoa">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-md w-full overflow-hidden">
            <form id="form-them-chuyen-khoa" onsubmit="event.preventDefault(); xacNhanThemChuyenKhoa();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-folder-plus text-indigo-600"></i>
                        <span>Thêm Chuyên Khoa Mới</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-them-chuyen-khoa')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mã chuyên khoa:</label>
                        <input type="text" id="modal-tck-ma" autocomplete="off" placeholder="Ví dụ: UNG_BUOU, DA_LIEU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 uppercase">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tên chuyên khoa:</label>
                        <input type="text" id="modal-tck-ten" autocomplete="off" placeholder="Ví dụ: Khoa Ung Bướu" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mô tả nhiệm vụ:</label>
                        <input type="text" id="modal-tck-mo-ta" autocomplete="off" placeholder="Khám, tầm soát và điều trị..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-chuyen-khoa')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Thêm Chuyên Khoa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL BÁC SĨ KÊ TOA THUỐC & KẾT LUẬN KHÁM                     -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-ke-toa-thuoc">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <form id="form-ke-toa-thuoc" onsubmit="event.preventDefault(); xacNhanKeToaThuoc();" autocomplete="off">
                <div class="bg-gradient-to-r from-purple-700 to-indigo-700 px-6 py-4 text-white flex items-center justify-between">
                    <h3 class="text-sm font-extrabold flex items-center space-x-2">
                        <i class="fa-solid fa-file-prescription text-amber-300 text-base"></i>
                        <span>Kê Toa Thuốc & Kết Luận Chẩn Đoán Khám Bệnh</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-ke-toa-thuoc')" class="text-white/70 hover:text-white text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <input type="hidden" id="modal-kt-id">

                    <!-- Thông tin ca khám -->
                    <div class="grid grid-cols-3 gap-2 bg-purple-50/70 p-3.5 rounded-2xl border border-purple-100">
                        <div>
                            <div class="text-[10px] text-purple-700 font-bold uppercase">Mã Ca Khám</div>
                            <div class="font-extrabold text-slate-800 text-sm mt-0.5" id="modal-kt-ma-ca">#--</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-purple-700 font-bold uppercase">Bệnh Nhân</div>
                            <div class="font-bold text-slate-800 text-xs mt-0.5" id="modal-kt-ten-bn">--</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-purple-700 font-bold uppercase">Lý Do Khám</div>
                            <div class="text-slate-600 text-[11px] mt-0.5 truncate" id="modal-kt-ly-do">--</div>
                        </div>
                    </div>

                    <!-- Chẩn đoán bệnh -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1 flex items-center justify-between">
                            <span>Chẩn Đoán Bệnh / Kết Luận Y Khoa:</span>
                            <span class="text-rose-500 font-normal">* Bắt buộc</span>
                        </label>
                        <input type="text" id="modal-kt-chuan-doan" required placeholder="Ví dụ: Viêm phế quản cấp, Cảm cúm siêu vi..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800 font-semibold">
                    </div>

                    <!-- Danh sách thuốc kê đơn -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="font-bold uppercase tracking-wider text-slate-700">Đơn Thuốc (Toa Thuốc Điều Trị):</label>
                            <button type="button" onclick="themDongThuoc()" class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg border border-purple-200 transition text-[11px] flex items-center gap-1">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Thêm Thuốc</span>
                            </button>
                        </div>
                        <div class="border border-slate-200 rounded-2xl overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="py-2 px-3">Tên Thuốc & Biệt Dược</th>
                                        <th class="py-2 px-2 w-28">Hàm Lượng</th>
                                        <th class="py-2 px-2 w-20 text-center">Số Lượng</th>
                                        <th class="py-2 px-2">Cách Dùng & Liều Dùng</th>
                                        <th class="py-2 px-2 w-8 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-ke-toa-thuoc" class="divide-y divide-slate-100 bg-white">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Lời khuyên và hẹn tái khám -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Lời Dặn Bác Sĩ:</label>
                            <textarea id="modal-kt-loi-khuyen" rows="2" placeholder="Uống nhiều nước, kiêng đồ chua cay..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800"></textarea>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày Hẹn Tái Khám (nếu có):</label>
                            <input type="date" id="modal-kt-ngay-tai-kham" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800">
                            <div class="mt-2 flex items-center gap-2">
                                <input type="checkbox" id="modal-kt-hoan-thanh-ngay" checked class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4">
                                <label for="modal-kt-hoan-thanh-ngay" class="text-[11px] font-semibold text-slate-600">Đánh dấu ca khám hoàn thành luôn</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-ke-toa-thuoc')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu Toa Thuốc & Hoàn Tất</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL XEM PHIẾU KHÁM & TOA THUỐC ĐIỆN TỬ (BỆNH NHÂN XEM)      -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-xem-toa-thuoc">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <h3 class="text-sm font-extrabold flex items-center space-x-2">
                    <i class="fa-solid fa-notes-medical text-amber-300"></i>
                    <span>Phiếu Khám Bệnh & Đơn Thuốc Điện Tử</span>
                </h3>
                <button type="button" onclick="dongModal('modal-xem-toa-thuoc')" class="text-white/70 hover:text-white text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs" id="in-phieu-kham-area">
                <!-- Tiêu đề phòng khám chuẩn y tế -->
                <div class="border-b-2 border-slate-800 pb-3 flex justify-between items-start">
                    <div>
                        <div class="text-xs font-black uppercase text-medical-700 tracking-wider">HỆ THỐNG PHÒNG KHÁM ĐA KHOA QUỐC TẾ</div>
                        <div class="text-[11px] text-slate-500">Địa chỉ: 123 Đường Sức Khỏe, Quận Y Tế, TP.HCM | Hotline: 1900 6868</div>
                        <div class="text-[11px] text-slate-500">Giấy phép hoạt động số: 2026/BYT-GPHĐ</div>
                    </div>
                    <div class="text-right">
                        <div class="font-mono font-black text-slate-900 text-sm" id="view-toa-ma-ca">LK#--</div>
                        <div class="text-[10px] text-slate-400" id="view-toa-ngay-kham">Ngày: --/--/----</div>
                    </div>
                </div>

                <div class="text-center py-1">
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">PHIẾU KHÁM BỆNH & TOA THUỐC ĐIỆN TỬ</h2>
                </div>

                <!-- Thông tin Bệnh nhân -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 grid grid-cols-2 gap-2 text-xs">
                    <div>Họ và tên: <strong class="text-slate-900 font-extrabold" id="view-toa-ten-bn">--</strong></div>
                    <div>Số điện thoại: <span class="font-mono text-slate-700 font-bold" id="view-toa-sdt-bn">--</span></div>
                    <div>Nhóm máu: <span class="font-bold text-rose-600" id="view-toa-nhom-mau">--</span></div>
                    <div>Bác sĩ khám: <strong class="text-slate-900 font-bold" id="view-toa-bac-si">--</strong></div>
                    <div class="col-span-2 text-amber-800 font-medium">Tiền sử dị ứng: <span id="view-toa-di-ung">Không ghi nhận</span></div>
                </div>

                <!-- Chẩn đoán & Lời khuyên -->
                <div class="space-y-2">
                    <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-xl">
                        <div class="text-[10px] font-bold text-emerald-800 uppercase">Chẩn Đoán Bệnh:</div>
                        <div class="text-xs font-bold text-slate-900 mt-0.5" id="view-toa-chuan-doan">Chưa có kết luận</div>
                    </div>
                    <div class="p-3 bg-sky-50/70 border border-sky-200 rounded-xl">
                        <div class="text-[10px] font-bold text-sky-800 uppercase">Lời Dặn Của Bác Sĩ:</div>
                        <div class="text-xs text-slate-800 mt-0.5 whitespace-pre-line" id="view-toa-loi-khuyen">Theo dõi sức khỏe và tái khám theo chỉ định.</div>
                    </div>
                </div>

                <!-- Danh mục thuốc kê đơn -->
                <div class="space-y-1.5">
                    <div class="font-bold uppercase tracking-wider text-slate-800 text-[11px] flex items-center justify-between">
                        <span>Đơn Thuốc Điều Trị:</span>
                        <span class="text-slate-400 font-normal italic">(Uống theo đúng chỉ định)</span>
                    </div>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px]">
                                <tr>
                                    <th class="py-2 px-3">STT</th>
                                    <th class="py-2 px-3">Tên Thuốc & Biệt Dược</th>
                                    <th class="py-2 px-2">Hàm Lượng</th>
                                    <th class="py-2 px-2 text-center">Số Lượng</th>
                                    <th class="py-2 px-3">Cách Dùng</th>
                                </tr>
                            </thead>
                            <tbody id="view-toa-tbody-thuoc" class="divide-y divide-slate-100 bg-white">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-between items-end pt-4 border-t border-slate-100">
                    <div class="text-slate-600 text-xs">
                        Hẹn ngày tái khám: <strong class="text-rose-600 font-bold" id="view-toa-tai-kham">Không có hẹn</strong>
                    </div>
                    <div class="text-center">
                        <div class="text-[11px] text-slate-400">Bác sĩ khám bệnh</div>
                        <div class="font-script text-base text-medical-800 font-bold italic mt-2" id="view-toa-chu-ky">BS. Phụ Trách</div>
                        <div class="text-xs font-bold text-slate-800 mt-1" id="view-toa-bs-ten">--</div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="inPhieuKhamToaThuoc()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>In Phiếu Khám / Lưu PDF</span>
                </button>
                <button type="button" onclick="dongModal('modal-xem-toa-thuoc')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: HỒ SƠ BỆNH ÁN ĐIỆN TỬ TOÀN DIỆN (EHR / EMR TIMELINE)   -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-chi-tiet-benh-nhan-ehr">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-rose-50 via-white to-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-rose-500/20">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <span>Hồ Sơ Bệnh Án Điện Tử Toàn Diện (EHR)</span>
                            <span id="ehr-badge-ma-bn" class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">BN-XXXX</span>
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Lịch sử khám chữa bệnh, bác sĩ phụ trách, chẩn đoán y khoa & toa thuốc điện tử</p>
                    </div>
                </div>
                <button type="button" onclick="dongModal('modal-chi-tiet-benh-nhan-ehr')" class="text-slate-400 hover:text-slate-600 text-base p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body Modal (Scrollable) -->
            <div class="p-6 space-y-6 overflow-y-auto flex-1 text-xs">
                <!-- Patient Demographic & Health Banner -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/80">
                        <div class="flex items-center space-x-3">
                            <div id="ehr-patient-avatar" class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-500 to-amber-500 text-white flex items-center justify-center text-lg font-black shadow-sm">
                                BN
                            </div>
                            <div>
                                <h4 id="ehr-patient-name" class="text-base font-extrabold text-slate-900">--</h4>
                                <div class="flex items-center gap-2 text-slate-500 text-[11px] mt-0.5">
                                    <span id="ehr-patient-gender">--</span>
                                    <span>•</span>
                                    <span id="ehr-patient-dob">--</span>
                                    <span>•</span>
                                    <span id="ehr-patient-age">-- tuổi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="ehr-badge-blood" class="px-3 py-1 bg-rose-100 text-rose-700 font-extrabold rounded-xl text-xs border border-rose-200 flex items-center gap-1">
                                <i class="fa-solid fa-droplet"></i> Nhóm máu: --
                            </span>
                        </div>
                    </div>

                    <!-- Contact & Identity Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-slate-600 text-[11px]">
                        <div><strong>SĐT:</strong> <span id="ehr-patient-phone">--</span></div>
                        <div><strong>CCCD / CMND:</strong> <span id="ehr-patient-cccd">--</span></div>
                        <div><strong>Địa chỉ:</strong> <span id="ehr-patient-address">--</span></div>
                    </div>

                    <!-- Medical Alerts: Allergy & Chronic Diseases -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-rose-50/80 border border-rose-200 rounded-xl space-y-1">
                            <span class="font-extrabold text-rose-900 flex items-center gap-1.5 text-[11px]">
                                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Tiền Sử Dị Ứng:
                            </span>
                            <p id="ehr-patient-allergy" class="text-rose-800 text-[11px] font-medium">Chưa ghi nhận dị ứng</p>
                        </div>
                        <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-xl space-y-1">
                            <span class="font-extrabold text-amber-900 flex items-center gap-1.5 text-[11px]">
                                <i class="fa-solid fa-heart-pulse text-amber-600"></i> Bệnh Lý Nền & Mãn Tính:
                            </span>
                            <p id="ehr-patient-history" class="text-amber-800 text-[11px] font-medium">Chưa ghi nhận bệnh nền</p>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="p-2.5 bg-sky-50/70 border border-sky-200 rounded-xl text-[11px] text-sky-900 flex items-center justify-between">
                        <div>
                            <i class="fa-solid fa-phone-volume text-sky-600 mr-1.5"></i>
                            <strong>Liên hệ khẩn cấp:</strong> <span id="ehr-patient-emergency-contact">--</span>
                        </div>
                        <div>
                            <strong>SĐT:</strong> <span id="ehr-patient-emergency-phone" class="font-bold font-mono">--</span>
                        </div>
                    </div>
                </div>

                <!-- Medical History Timeline Section -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-slate-800 text-xs flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-rose-600"></i>
                            <span>Dòng Thời Gian Các Ca Khám & Điều Trị (EMR Timeline)</span>
                        </h4>
                        <span id="ehr-total-visits" class="text-[11px] font-bold text-slate-500">0 lượt khám</span>
                    </div>

                    <div id="ehr-timeline-container" class="space-y-4">
                        <!-- Dynamic EHR Visit Cards rendered via JS -->
                        <div class="p-8 text-center text-slate-400">Đang tải hồ sơ bệnh án...</div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between flex-shrink-0">
                <span class="text-[11px] text-slate-500">Hệ thống Hồ sơ Bệnh án Điện tử liên thông nội bộ Phòng khám</span>
                <button type="button" onclick="dongModal('modal-chi-tiet-benh-nhan-ehr')" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: THÊM / CẬP NHẬT HỒ SƠ BỆNH NHÂN (ADMIN / LỄ TÂN)       -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-them-sua-benh-nhan">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <form id="form-them-sua-benh-nhan" onsubmit="event.preventDefault(); luuThongTinBenhNhan();" autocomplete="off">
                <input type="hidden" id="form-bn-id">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 font-bold">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div>
                            <h3 id="modal-bn-title" class="text-sm font-extrabold text-slate-900">Thêm Hồ Sơ Bệnh Nhân Mới</h3>
                            <p class="text-[11px] text-slate-500">Quản lý hồ sơ định danh và tiền sử y tế bệnh nhân</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-sua-benh-nhan')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ và Tên Bệnh Nhân: <span class="text-rose-500">*</span></label>
                            <input type="text" id="form-bn-hoten" required placeholder="Nguyễn Văn A" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số Điện Thoại: <span class="text-rose-500">*</span></label>
                            <input type="tel" id="form-bn-sdt" required placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày Sinh:</label>
                            <input type="date" id="form-bn-ngaysinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giới Tính:</label>
                            <select id="form-bn-gioitinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                                <option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Nhóm Máu:</label>
                            <select id="form-bn-nhommau" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                                <option value="">-- Chưa rõ --</option>
                                <option value="A">Nhóm máu A</option>
                                <option value="B">Nhóm máu B</option>
                                <option value="AB">Nhóm máu AB</option>
                                <option value="O">Nhóm máu O</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số CCCD / CMND:</label>
                            <input type="text" id="form-bn-cccd" placeholder="001200000001" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Địa Chỉ Thường Trú:</label>
                            <input type="text" id="form-bn-diachi" placeholder="Quận 1, TP. Hồ Chí Minh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 mr-1"></i> Tiền Sử Dị Ứng Thuốc / Thức Ăn:
                        </label>
                        <textarea id="form-bn-diung" rows="2" placeholder="Ví dụ: Dị ứng Penicillin, Paracetamol, Hải sản..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            <i class="fa-solid fa-heart-pulse text-amber-500 mr-1"></i> Bệnh Lý Nền & Tiền Sử Bệnh Mãn Tính:
                        </label>
                        <textarea id="form-bn-tiensubenh" rows="2" placeholder="Ví dụ: Cao huyết áp, Đái tháo đường type 2, Hen suyễn..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ Tên Người Thân Khẩn Cấp:</label>
                            <input type="text" id="form-bn-nguoithan" placeholder="Người bảo hộ / Vợ / Chồng" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SĐT Người Thân Khẩn Cấp:</label>
                            <input type="tel" id="form-bn-sdtnguoithan" placeholder="0987654321" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-sua-benh-nhan')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy Bỏ</button>
                    <button type="submit" id="btn-submit-bn" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu Hồ Sơ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: THÊM / CẬP NHẬT HỒ SƠ NGƯỜI THÂN GIA ĐÌNH              -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-them-sua-nguoi-than">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <form id="form-them-sua-nguoi-than" onsubmit="event.preventDefault(); luuHoSoNguoiThan();" autocomplete="off">
                <input type="hidden" id="modal-nt-id">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 font-bold">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>
                        <div>
                            <h3 id="modal-nt-title" class="text-sm font-extrabold text-slate-900">Thêm Hồ Sơ Người Thân Mới</h3>
                            <p class="text-[11px] text-slate-500">Quản lý hồ sơ khám bệnh cho con cái, cha mẹ, người trong nhà</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-sua-nguoi-than')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mối Quan Hệ Với Bạn: <span class="text-rose-500">*</span></label>
                            <select id="modal-nt-quanhe" required onchange="capNhatNhanModalNguoiThan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-bold">
                                <option value="CON">Con cái (Trẻ em / Hậu bối)</option>
                                <option value="CHA_ME">Bố / Mẹ (Phụ mẫu)</option>
                                <option value="VO_CHONG">Vợ / Chồng</option>
                                <option value="NGUOI_THAN">Người thân khác</option>
                            </select>
                        </div>
                        <div>
                            <label id="lbl-modal-nt-hoten" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ và Tên Người Thân: <span class="text-rose-500">*</span></label>
                            <input type="text" id="modal-nt-hoten" required placeholder="Ví dụ: Nguyễn Gia Bảo" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày Sinh:</label>
                            <input type="date" id="modal-nt-ngaysinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giới Tính:</label>
                            <select id="modal-nt-gioitinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                                <option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Nhóm Máu:</label>
                            <select id="modal-nt-nhommau" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                                <option value="">-- Chưa rõ --</option>
                                <option value="A">Nhóm máu A</option>
                                <option value="B">Nhóm máu B</option>
                                <option value="AB">Nhóm máu AB</option>
                                <option value="O">Nhóm máu O</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label id="lbl-modal-nt-sdt" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số Điện Thoại:</label>
                            <input type="tel" id="modal-nt-sdt" placeholder="Để trống nếu là trẻ em" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium font-mono">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số CCCD / Mã Định Danh:</label>
                            <input type="text" id="modal-nt-cccd" placeholder="Mã định danh cá nhân" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Địa Chỉ Thường Trú:</label>
                        <input type="text" id="modal-nt-diachi" placeholder="Nơi ở hiện tại" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 mr-1"></i> Tiền Sử Dị Ứng Thuốc / Thực Phẩm:
                        </label>
                        <textarea id="modal-nt-diung" rows="2" placeholder="Ví dụ: Dị ứng Amoxicillin, thức ăn có vỏ..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            <i class="fa-solid fa-heart-pulse text-amber-500 mr-1"></i> Tiền Sử Bệnh & Bệnh Mãn Tính:
                        </label>
                        <textarea id="modal-nt-tiensubenh" rows="2" placeholder="Ví dụ: Hen phế quản, viêm tai giữa tái phát..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Người Liên Hệ Khẩn Cấp:</label>
                            <input type="text" id="modal-nt-nguoithan" placeholder="Họ tên người giám hộ" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SĐT Khẩn Cấp:</label>
                            <input type="tel" id="modal-nt-sdtnguoithan" placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-sua-nguoi-than')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy Bỏ</button>
                    <button type="submit" id="btn-submit-nt" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu Hồ Sơ Người Thân</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-bac-si">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-lg w-full overflow-hidden">
            <form id="form-sua-bac-si" onsubmit="event.preventDefault(); xacNhanSuaBacSi();" autocomplete="off">
                <input type="hidden" id="modal-sbs-id">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-user-doctor text-amber-600"></i>
                        <span>Cập Nhật Thông Tin Bác Sĩ</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-sua-bac-si')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ và tên bác sĩ: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sbs-ho-ten" required autocomplete="off" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Học hàm / Học vị:</label>
                            <select id="modal-sbs-hoc-vi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                                <option value="Bác sĩ CKI">Bác sĩ CKI</option>
                                <option value="Bác sĩ CKII">Bác sĩ CKII</option>
                                <option value="Thạc sĩ">Thạc sĩ</option>
                                <option value="Tiến sĩ">Tiến sĩ</option>
                                <option value="Phó Giáo sư">Phó Giáo sư</option>
                                <option value="Giáo sư">Giáo sư</option>
                                <option value="Bác sĩ Đa khoa">Bác sĩ Đa khoa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Chuyên khoa: <span class="text-rose-500">*</span></label>
                            <select id="modal-sbs-chuyen-khoa" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                                <!-- Dynamic -->
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giá khám niêm yết (VNĐ):</label>
                            <input type="number" id="modal-sbs-gia-kham" min="0" step="10000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-bold text-amber-600">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số phòng khám:</label>
                            <input type="text" id="modal-sbs-phong" placeholder="P101, P202..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="text" id="modal-sbs-sdt" autocomplete="off" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Trạng thái làm việc:</label>
                            <select id="modal-sbs-trang-thai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                                <option value="DANG_LAM_VIEC">Đang làm việc</option>
                                <option value="NGHI_VIEC">Nghỉ việc / Tạm dừng</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Kinh nghiệm / Giới thiệu:</label>
                        <textarea id="modal-sbs-kinh-nghiem" rows="2" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800" placeholder="Số năm kinh nghiệm hoặc quá trình công tác..."></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-sua-bac-si')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md shadow-amber-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu Thay Đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA CHUYÊN KHOA (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-chuyen-khoa">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-md w-full overflow-hidden">
            <form id="form-sua-chuyen-khoa" onsubmit="event.preventDefault(); xacNhanSuaChuyenKhoa();" autocomplete="off">
                <input type="hidden" id="modal-sck-id">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-amber-600"></i>
                        <span>Sửa Thông Tin Chuyên Khoa</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-sua-chuyen-khoa')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mã chuyên khoa: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sck-ma" required autocomplete="off" placeholder="Ví dụ: NOI, DA_LIEU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-mono font-bold uppercase">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tên chuyên khoa: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sck-ten" required autocomplete="off" placeholder="Ví dụ: Khoa Nội Tổng Quát" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Trạng thái hoạt động:</label>
                        <select id="modal-sck-trang-thai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-bold">
                            <option value="HOAT_DONG">Đang hoạt động</option>
                            <option value="TAM_DUNG">Tạm ngưng tiếp nhận</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Mô tả tóm tắt:</label>
                        <textarea id="modal-sck-mo-ta" rows="3" placeholder="Mô tả chức năng nhiệm vụ khoa..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-sua-chuyen-khoa')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md shadow-amber-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Cập Nhật Khoa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA ĐƠN GIÁ KHÁM BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-gia-kham">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-md w-full overflow-hidden">
            <form id="form-sua-gia-kham" onsubmit="event.preventDefault(); xacNhanSuaGiaKham();" autocomplete="off">
                <input type="hidden" id="modal-sgk-id">
                <div class="bg-gradient-to-r from-slate-900 to-sky-950 px-6 py-4 text-white flex items-center justify-between">
                    <h3 class="text-sm font-extrabold flex items-center space-x-2">
                        <i class="fa-solid fa-hand-holding-dollar text-amber-400"></i>
                        <span>Điều Chỉnh Đơn Giá Khám Bác Sĩ</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-sua-gia-kham')" class="text-slate-400 hover:text-white text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-medical-500/10 text-medical-600 font-bold text-base flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p id="modal-sgk-ho-ten" class="font-extrabold text-slate-900 text-sm truncate">Bác sĩ...</p>
                            <p id="modal-sgk-khoa-hocvi" class="text-slate-500 text-[11px] truncate">Chuyên khoa • Học vị</p>
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giá khám hiện tại:</label>
                        <div id="modal-sgk-gia-hien-tai" class="px-3.5 py-2.5 bg-slate-100 rounded-xl font-mono font-extrabold text-slate-600 text-sm">
                            0 VNĐ
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Đơn giá khám mới (VNĐ): <span class="text-rose-500">*</span></label>
                        <input type="number" id="modal-sgk-gia-moi" required min="0" step="10000" placeholder="Ví dụ: 250000" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-900 font-mono font-extrabold text-base">
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <span class="text-[10px] text-slate-400 font-bold self-center">Gợi ý:</span>
                            <button type="button" onclick="datGiaKhamGoiY(150000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition">150.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(200000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition">200.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(250000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition">250.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(300000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition">300.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(500000)" class="px-2 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-[10px] font-bold transition">500.000đ (VIP)</button>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-sua-gia-kham')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu Thay Đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: LỊCH TRỰC / CA LÀM VIỆC CỦA BÁC SĨ -->
    <div class="modal-backdrop" id="modal-lich-truc-bac-si">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-sky-950 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-400">
                        <i class="fa-solid fa-calendar-week"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold flex items-center space-x-2">
                            <span>Lịch Trực / Ca Làm Việc Của Bác Sĩ</span>
                        </h3>
                        <p id="modal-lt-bac-si-name" class="text-xs text-sky-300 font-bold">BS. Nguyễn Anh Tuấn</p>
                    </div>
                </div>
                <button type="button" onclick="dongModal('modal-lich-truc-bac-si')" class="text-slate-400 hover:text-white text-base">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto text-xs">
                <!-- Add/Edit Shift Form -->
                <form id="form-them-ca-truc" onsubmit="event.preventDefault(); xacNhanLuuCaTruc();" class="p-4 bg-sky-50/70 rounded-2xl border border-sky-200/80 space-y-3">
                    <input type="hidden" id="modal-lt-bac-si-id">
                    <input type="hidden" id="modal-lt-ca-id">
                    <div class="font-extrabold text-slate-800 flex items-center justify-between pb-1 border-b border-sky-100">
                        <span id="modal-lt-form-title" class="flex items-center gap-1.5"><i class="fa-solid fa-plus-circle text-sky-600"></i> Đăng Ký / Cập Nhật Ca Trực</span>
                        <span class="text-[11px] text-slate-500 font-medium">Bấm <i class="fa-solid fa-pen-to-square text-sky-600"></i> bên dưới để sửa ca</span>
                    </div>

                    <!-- Banner quy tắc 1 ca/ngày & Admin duyệt -->
                    <div class="bg-amber-50/90 border border-amber-200/90 rounded-xl p-3 text-[11px] text-amber-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-amber-600 mt-0.5 text-xs"></i>
                        <div>
                            <span class="font-extrabold">Quy định ca trực:</span> Mỗi bác sĩ chỉ được đăng ký tối đa <strong>1 ca trực trong 1 ngày</strong> (Sáng, Chiều, Tối hoặc Cả Ngày). Khi Bác Sĩ đăng ký mới hoặc đổi ca, yêu cầu sẽ ở trạng thái <strong class="text-amber-700">Chờ Admin Duyệt</strong> và chỉ có hiệu lực sau khi Quản trị viên (Admin) phê duyệt.
                        </div>
                    </div>

                    <!-- Alert cảnh báo động khi chọn thứ đã có ca -->
                    <div id="modal-lt-cung-ngay-alert" class="hidden p-2.5 rounded-xl border text-[11px] font-bold"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Thứ trong tuần:</label>
                            <select id="modal-lt-thu" onchange="tuDongDienGioCaTruc(); kiemTraCaCungNgayModal();" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                                <option value="2">Thứ Hai</option>
                                <option value="3">Thứ Ba</option>
                                <option value="4">Thứ Tư</option>
                                <option value="5">Thứ Năm</option>
                                <option value="6">Thứ Sáu</option>
                                <option value="7">Thứ Bảy</option>
                                <option value="8">Chủ Nhật</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Ca trực:</label>
                            <select id="modal-lt-ca" onchange="tuDongDienGioCaTruc(); kiemTraCaCungNgayModal();" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                                <option value="CA_SANG">Ca Sáng (07:30 - 11:30)</option>
                                <option value="CA_CHIEU">Ca Chiều (13:30 - 17:00)</option>
                                <option value="CA_TOI">Ca Tối (17:30 - 20:30)</option>
                                <option value="CA_NGAY">Cả Ngày (07:30 - 17:00)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Số khám tối đa:</label>
                            <input type="number" id="modal-lt-so-luong" value="20" min="1" max="100" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Giờ bắt đầu:</label>
                            <input type="time" id="modal-lt-gio-bd" value="07:30" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Giờ kết thúc:</label>
                            <input type="time" id="modal-lt-gio-kt" value="11:30" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-600 text-[10px] mb-1">Phòng khám:</label>
                            <input type="text" id="modal-lt-phong" placeholder="P201" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" id="btn-huy-sua-ca-truc" onclick="datLaiFormCaTruc()" class="hidden px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                            Hủy Sửa
                        </button>
                        <button type="submit" id="btn-submit-ca-truc" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span id="lbl-submit-ca-truc">Lưu Ca Trực</span>
                        </button>
                    </div>
                </form>

                <!-- Current Shifts Table -->
                <div class="space-y-2">
                    <h4 class="font-extrabold text-slate-800 flex items-center space-x-2">
                        <i class="fa-solid fa-list-check text-medical-600"></i>
                        <span>Danh Sách Ca Trực Đang Đăng Ký Trong Tuần</span>
                    </h4>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                    <th class="py-2.5 px-3">Ngày</th>
                                    <th class="py-2.5 px-3">Ca</th>
                                    <th class="py-2.5 px-3">Khung Giờ</th>
                                    <th class="py-2.5 px-3">Phòng</th>
                                    <th class="py-2.5 px-3">Khám Tối Đa</th>
                                    <th class="py-2.5 px-3">Trạng Thái</th>
                                    <th class="py-2.5 px-3 text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-modal-lich-truc" class="divide-y divide-slate-100 text-slate-700">
                                <tr><td colspan="7" class="text-center py-6 text-slate-400">Đang tải lịch trực...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end">
                <button type="button" onclick="dongModal('modal-lich-truc-bac-si')" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">Đóng</button>
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
            bangGiaKham: [],
            thongKeBangGia: null,
            slotDaChon: { batDau: '08:00', ketThuc: '08:30' },
            slotDoiLichDaChon: { batDau: '08:00:00', ketThuc: '08:30:00' },
            tepYTeUploads: [],
            caKhamDangChon: null,
            phuongThucThanhToan: 'TIEN_MAT',
            hoaDonDangXemId: null,
            danhSachBenhNhan: [],
            benhNhanDangXem: null,
            benhNhanDangSuaId: null
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
            await taiBangGiaKham();

            const role = AppState.currentUser ? AppState.currentUser.vai_tro : '';
            if (role === 'BENH_NHAN' || role === 'ADMIN' || role === 'BAC_SI') {
                await taiVaRenderHoSoGiaDinh();
            }
            if (role === 'BAC_SI' || role === 'ADMIN') {
                await taiDanhSachBenhNhan();
                await taiDanhSachDichVuCLS();
            }
            if (role === 'ADMIN') {
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
                await taiDanhSachTaiKhoan();
                await kiemTraHealthToanHeThong();
                setTimeout(() => khoiTaoBieuDoThongKe(), 200);
            }

            // Xử lý tham số ?bac_si_id nếu có
            const urlParams = new URLSearchParams(window.location.search);
            const preselectDoctorId = urlParams.get('bac_si_id');
            if (preselectDoctorId && role === 'BENH_NHAN') {
                setTimeout(() => moModalDatLich(preselectDoctorId), 300);
            }
        });


        // BẢNG QUY ĐỊNH PHÂN QUYỀN TRUY CẬP CÁC TAB
        const ROLE_PERMISSIONS = {
            'ADMIN': [
                'tab-admin-giam-sat',
                'tab-benh-nhan-lich',
                'tab-benh-nhan',
                'tab-bang-gia-kham',
                'tab-ho-so-gia-dinh',
                'tab-admin-benh-nhan',
                'tab-bac-si',
                'tab-bac-si-lich-su',
                'tab-admin-thu-ngan',
                'tab-admin-bac-si',
                'tab-admin-tai-khoan'
            ],
            'BAC_SI': [
                'tab-bac-si',
                'tab-bac-si-lich-su',
                'tab-benh-nhan-lich',
                'tab-bang-gia-kham',
                'tab-ho-so-gia-dinh',
                'tab-admin-benh-nhan'
            ],
            'BENH_NHAN': [
                'tab-benh-nhan-lich',
                'tab-benh-nhan',
                'tab-bang-gia-kham',
                'tab-ho-so-gia-dinh'
            ]
        };

        const DEFAULT_TAB_BY_ROLE = {
            'ADMIN': 'tab-admin-giam-sat',
            'BAC_SI': 'tab-bac-si',
            'BENH_NHAN': 'tab-benh-nhan-lich'
        };

        // HÀM CHUẨN HÓA VAI TRÒ (ĐẢM BẢO LUÔN LÀ STRING 'ADMIN' | 'BAC_SI' | 'BENH_NHAN')
        function chuanHoaVaiTroCurrentUser() {
            if (!AppState.currentUser) return 'BENH_NHAN';
            let role = 'BENH_NHAN';
            if (typeof AppState.currentUser.vai_tro === 'string') {
                role = AppState.currentUser.vai_tro;
            } else if (AppState.currentUser.vai_tro && typeof AppState.currentUser.vai_tro === 'object') {
                role = AppState.currentUser.vai_tro.ma_vai_tro || AppState.currentUser.vai_tro.ten_vai_tro || 'BENH_NHAN';
            } else if (AppState.currentUser.vaiTro && typeof AppState.currentUser.vaiTro === 'object') {
                role = AppState.currentUser.vaiTro.ma_vai_tro || 'BENH_NHAN';
            } else if (AppState.currentUser.vai_tro_id) {
                const mapRoles = { 1: 'ADMIN', 2: 'BAC_SI', 3: 'BENH_NHAN' };
                role = mapRoles[AppState.currentUser.vai_tro_id] || 'BENH_NHAN';
            }
            AppState.currentUser.vai_tro = role;
            try {
                localStorage.setItem('role', role);
            } catch (e) {}
            return role;
        }

        // HÀM ĐIỀU CHỈNH GIAO DIỆN CHUẨN XÁC THEO TỪNG VAI TRÒ
        function capNhatGiaoDienTheoVaiTro() {
            const role = chuanHoaVaiTroCurrentUser();
            const hoTen = AppState.currentUser ? (AppState.currentUser.ho_ten || AppState.currentUser.ten_dang_nhap) : 'Người Dùng';

            // Cập nhật Header & Sidebar User
            const nameEl = document.getElementById('header-user-name');
            if (nameEl) nameEl.textContent = hoTen;
            
            const roleEl = document.getElementById('header-user-role');
            if (roleEl) {
                roleEl.textContent = role;
                if (role === 'ADMIN') {
                    roleEl.className = 'px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-indigo-950 text-indigo-300 border border-indigo-700/60 inline-block';
                } else if (role === 'BAC_SI') {
                    roleEl.className = 'px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-sky-950 text-sky-300 border border-sky-700/60 inline-block';
                } else {
                    roleEl.className = 'px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-emerald-950 text-emerald-300 border border-emerald-700/60 inline-block';
                }
            }

            // Cập nhật Avatar người dùng đồng bộ
            const userAvatar = AppState.currentUser ? AppState.currentUser.avatar : null;
            const sidebarAvatarImg = document.getElementById('sidebar-user-avatar-img');
            const sidebarAvatarIcon = document.getElementById('sidebar-user-avatar-icon');
            const topAvatarImg = document.getElementById('top-user-avatar-img');
            const topAvatarIcon = document.getElementById('top-user-avatar-icon');
            const topUserName = document.getElementById('top-user-name');

            if (topUserName) topUserName.textContent = hoTen;

            if (userAvatar) {
                if (sidebarAvatarImg) {
                    sidebarAvatarImg.src = userAvatar;
                    sidebarAvatarImg.classList.remove('hidden');
                }
                if (sidebarAvatarIcon) sidebarAvatarIcon.classList.add('hidden');

                if (topAvatarImg) {
                    topAvatarImg.src = userAvatar;
                    topAvatarImg.classList.remove('hidden');
                }
                if (topAvatarIcon) topAvatarIcon.classList.add('hidden');
            } else {
                if (sidebarAvatarImg) sidebarAvatarImg.classList.add('hidden');
                if (sidebarAvatarIcon) sidebarAvatarIcon.classList.remove('hidden');

                if (topAvatarImg) topAvatarImg.classList.add('hidden');
                if (topAvatarIcon) topAvatarIcon.classList.remove('hidden');
            }

            // Cập nhật Brand Title / Subtitle
            const brandSub = document.getElementById('brand-subtitle');
            if (brandSub) {
                if (role === 'ADMIN') brandSub.textContent = 'Cổng Quản Trị Hệ Thống Toàn Viện';
                else if (role === 'BAC_SI') brandSub.textContent = 'Bàn Khám Chuyên Môn Bác Sĩ';
                else brandSub.textContent = 'Cổng Dịch Vụ Dành Cho Bệnh Nhân';
            }

            // Ẩn / Hiện các nhóm menu sidebar theo vai trò
            document.querySelectorAll('.role-nav-group').forEach(group => {
                const allowedRoles = (group.getAttribute('data-roles') || '').split(',').map(r => r.trim());
                if (allowedRoles.includes(role)) {
                    group.classList.remove('hidden');
                    group.style.display = '';
                } else {
                    group.classList.add('hidden');
                    group.style.display = 'none';
                }
            });

            // Ẩn / Hiện từng mục menu con cụ thể theo allowedTabs của vai trò
            const menuAllowedTabs = ROLE_PERMISSIONS[role] || ROLE_PERMISSIONS['BENH_NHAN'];
            document.querySelectorAll('.sidebar-item').forEach(item => {
                const itemTab = item.getAttribute('data-tab');
                if (itemTab) {
                    if (menuAllowedTabs.includes(itemTab)) {
                        item.classList.remove('hidden');
                        item.style.display = '';
                    } else {
                        item.classList.add('hidden');
                        item.style.display = 'none';
                    }
                }
            });

            // Tùy chỉnh nhãn điều hướng và tiêu đề bảng cho từng vai trò
            const navLabelLich = document.getElementById('nav-label-benh-nhan-lich');
            const titleLichKham = document.getElementById('title-danh-sach-lich-kham');
            if (role === 'BENH_NHAN') {
                if (navLabelLich) navLabelLich.textContent = 'Lịch Khám Của Tôi';
                if (titleLichKham) titleLichKham.textContent = 'Lịch Khám Của Tôi';
            } else if (role === 'BAC_SI') {
                if (navLabelLich) navLabelLich.textContent = 'Lịch Hẹn Phòng Khám';
                if (titleLichKham) titleLichKham.textContent = 'Lịch Hẹn Phòng Khám';
            } else {
                if (navLabelLich) navLabelLich.textContent = 'Quản Lý Lịch Khám';
                if (titleLichKham) titleLichKham.textContent = 'Quản Lý Toàn Bộ Lịch Khám';
            }

            if (document.getElementById('label-active-token')) {
                document.getElementById('label-active-token').textContent = AppState.token ? (AppState.token.substring(0, 36) + '...') : 'Chưa có Token';
            }

            // Khôi phục tab đang đứng nếu có và đảm bảo hợp lệ về quyền hạn
            const hashTab = window.location.hash ? window.location.hash.replace('#', '') : null;
            const savedTab = hashTab || sessionStorage.getItem('current_active_tab');
            const allowedTabs = ROLE_PERMISSIONS[role] || ROLE_PERMISSIONS['BENH_NHAN'];

            if (savedTab && allowedTabs.includes(savedTab) && document.getElementById(savedTab)) {
                chuyenTab(savedTab);
            } else {
                const defaultTab = DEFAULT_TAB_BY_ROLE[role] || 'tab-benh-nhan-lich';
                chuyenTab(defaultTab);
            }
        }

        // HÀM CHUYỂN TAB TRÊN THANH MENU SIDEBAR BÊN TRÁI (CÓ BẢO VỆ PHÂN QUYỀN)
        function chuyenTab(tabId, clickedBtn = null) {
            const role = AppState.currentUser ? AppState.currentUser.vai_tro : (localStorage.getItem('role') || 'BENH_NHAN');
            const allowedTabs = ROLE_PERMISSIONS[role] || ROLE_PERMISSIONS['BENH_NHAN'];

            // Chặn tuyệt đối nếu vai trò hiện tại không có quyền truy cập tab
            if (!allowedTabs.includes(tabId)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Từ Chối Truy Cập!',
                        text: `Tài khoản vai trò [${role}] không có quyền truy cập vào chức năng này. Vui lòng liên hệ Quản trị viên (ADMIN).`,
                        confirmButtonColor: '#0284c7'
                    });
                }
                const fallbackTab = DEFAULT_TAB_BY_ROLE[role] || 'tab-benh-nhan-lich';
                if (tabId !== fallbackTab) {
                    chuyenTab(fallbackTab);
                }
                return;
            }

            const targetSection = document.getElementById(tabId);
            if (!targetSection) return;

            // Lưu trạng thái tab hiện tại vào sessionStorage & URL Hash để khi F5 / Reload vẫn giữ nguyên tab
            try {
                sessionStorage.setItem('current_active_tab', tabId);
                if (window.location.hash !== '#' + tabId) {
                    history.replaceState(null, null, '#' + tabId);
                }
            } catch (e) {}

            // Cập nhật active cho menu bên trái
            document.querySelectorAll('.sidebar-item').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.portal-section').forEach(sec => sec.classList.remove('active'));

            targetSection.classList.add('active');

            if (clickedBtn) {
                clickedBtn.classList.add('active');
            } else {
                const btn = document.querySelector(`.sidebar-item[data-tab="${tabId}"]`) || document.querySelector(`button[onclick*="${tabId}"]`);
                if (btn) btn.classList.add('active');
            }

            // Cập nhật tiêu đề trên thanh điều hướng đầu trang
            const titles = {
                'tab-admin-giam-sat': { icon: 'fa-solid fa-chart-pie text-purple-600', text: 'Tổng Quan & Giám Sát Hệ Thống' },
                'tab-benh-nhan-lich': { icon: 'fa-solid fa-calendar-days text-sky-600', text: 'Quản Lý Lịch Khám Bệnh' },
                'tab-benh-nhan': { icon: 'fa-regular fa-calendar-plus text-medical-600', text: 'Tra Cứu Bác Sĩ & Đặt Lịch Khám' },
                'tab-bang-gia-kham': { icon: 'fa-solid fa-receipt text-amber-500', text: 'Bảng Giá Khám Bệnh Niêm Yết' },
                'tab-ho-so-gia-dinh': { icon: 'fa-solid fa-people-roof text-emerald-600', text: 'Quản Lý Hồ Sơ Sức Khỏe Gia Đình' },
                'tab-admin-benh-nhan': { icon: 'fa-solid fa-hospital-user text-rose-500', text: 'Quản Lý Bệnh Nhân & Hồ Sơ Bệnh Án (EHR)' },
                'tab-bac-si': { icon: 'fa-solid fa-stethoscope text-teal-600', text: 'Bàn Khám Bác Sĩ & Cận Lâm Sàng' },
                'tab-bac-si-lich-su': { icon: 'fa-solid fa-clipboard-user text-emerald-600', text: 'Danh Sách Ca Khám Hôm Nay' },
                'tab-admin-thu-ngan': { icon: 'fa-solid fa-file-invoice-dollar text-amber-600', text: 'Thu Ngân & Quản Lý Viện Phí' },
                'tab-admin-bac-si': { icon: 'fa-solid fa-user-doctor text-blue-600', text: 'Quản Trị Bác Sĩ & Chuyên Khoa' },
                'tab-admin-tai-khoan': { icon: 'fa-solid fa-users-gear text-indigo-600', text: 'Quản Trị Tài Khoản Người Dùng' },
            };

            const pageTitle = document.getElementById('current-page-title');
            if (pageTitle && titles[tabId]) {
                pageTitle.innerHTML = `<i class="${titles[tabId].icon}"></i><span>${titles[tabId].text}</span>`;
            }

            if (tabId === 'tab-bang-gia-kham') {
                taiBangGiaKham();
            }
            if (tabId === 'tab-ho-so-gia-dinh') {
                taiVaRenderHoSoGiaDinh();
            }
            if (tabId === 'tab-admin-bac-si') {
                taiVaRenderDanhSachDuyetCaTruc();
            }
            if (tabId === 'tab-admin-giam-sat') {
                setTimeout(() => khoiTaoBieuDoThongKe(), 150);
            }
        }

        // Lắng nghe sự kiện hashchange để hỗ trợ nút Back/Forward trên trình duyệt
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById(hash)) {
                chuyenTab(hash);
            }
        });

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
                const modalSbsSelect = document.getElementById('modal-sbs-chuyen-khoa');
                const filterBgk = document.getElementById('filter-bgk-chuyen-khoa');
                
                let opts = '<option value="">Tất cả chuyên khoa</option>';
                let modalOpts = '';

                AppState.danhSachChuyenKhoa.forEach(ck => {
                    const ten = ck.ten_khoa || ck.ten_chuyen_khoa;
                    opts += `<option value="${ck.id}">${ten}</option>`;
                    modalOpts += `<option value="${ck.id}">${ten}</option>`;
                });

                if (filterSelect) filterSelect.innerHTML = opts;
                if (modalSelect) modalSelect.innerHTML = modalOpts;
                if (modalSbsSelect) modalSbsSelect.innerHTML = modalOpts;
                if (filterBgk) filterBgk.innerHTML = opts;

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
                const hoTenEsc = (bs.ho_ten || '').replace(/'/g, "\\'");
                const avatarHtml = bs.avatar 
                    ? `<img src="${bs.avatar}" alt="${bs.ho_ten}" class="w-14 h-14 rounded-2xl object-cover border-2 border-sky-300 shadow-md">`
                    : `<div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-50 to-medical-100 text-medical-700 border border-medical-200 flex items-center justify-center font-extrabold text-base shadow-sm">
                        ${bs.ho_ten.substring(0, 2).toUpperCase()}
                       </div>`;

                const shifts = bs.lich_truc || [];
                const coCaSang = shifts.some(s => s.ca_truc === 'CA_SANG' && s.trang_thai === 'HOAT_DONG');
                const coCaChieu = shifts.some(s => s.ca_truc === 'CA_CHIEU' && s.trang_thai === 'HOAT_DONG');
                const coCaToi = shifts.some(s => s.ca_truc === 'CA_TOI' && s.trang_thai === 'HOAT_DONG');
                const coCaNgay = shifts.some(s => s.ca_truc === 'CA_NGAY' && s.trang_thai === 'HOAT_DONG');

                let caBadgesHtml = '';
                if (coCaSang || coCaChieu || coCaToi || coCaNgay) {
                    caBadgesHtml = `
                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                            ${coCaSang ? '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 flex items-center gap-1"><i class="fa-solid fa-sun text-amber-500"></i> Ca Sáng</span>' : ''}
                            ${coCaChieu ? '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200/80 flex items-center gap-1"><i class="fa-solid fa-cloud-sun text-sky-500"></i> Ca Chiều</span>' : ''}
                            ${coCaToi ? '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200/80 flex items-center gap-1"><i class="fa-solid fa-moon text-purple-500"></i> Ca Tối</span>' : ''}
                            ${coCaNgay ? '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center gap-1"><i class="fa-solid fa-star text-emerald-500"></i> Cả Ngày</span>' : ''}
                        </div>
                    `;
                }

                return `
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 hover:border-medical-500 hover:shadow-xl hover:shadow-medical-600/10 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    ${avatarHtml}
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm leading-tight">${bs.ho_ten}</h4>
                                        <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            ${tenKhoa}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1.5 text-xs text-slate-500">
                                <div><i class="fa-solid fa-graduation-cap text-medical-500 w-4"></i> ${bs.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                                <div><i class="fa-solid fa-door-open text-slate-400 w-4"></i> Phòng khám: <strong class="text-slate-700">${bs.phong_kham || 'P201'}</strong></div>
                                <div><i class="fa-solid fa-phone text-emerald-500 w-4"></i> ${bs.so_dien_thoai || '0901234567'}</div>
                                ${caBadgesHtml}
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
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
            const tuKhoa = (document.getElementById('filter-doctor-search')?.value || '').toLowerCase().trim();
            const chuyenKhoaId = document.getElementById('filter-doctor-chuyen-khoa')?.value;
            const caTruc = document.getElementById('filter-doctor-ca')?.value;

            const filtered = AppState.danhSachBacSi.filter(bs => {
                const matchName = (bs.ho_ten || '').toLowerCase().includes(tuKhoa) || (bs.ma_bac_si && bs.ma_bac_si.toLowerCase().includes(tuKhoa));
                const matchKhoa = !chuyenKhoaId || (bs.chuyen_khoa_id == chuyenKhoaId);
                
                let matchCa = true;
                if (caTruc) {
                    const shifts = bs.lich_truc || [];
                    matchCa = shifts.some(s => s.ca_truc === caTruc && s.trang_thai === 'HOAT_DONG');
                }

                return matchName && matchKhoa && matchCa;
            });

            renderDoctorsCards(filtered);
        }

        // =============================================================
        // PHÂN HỆ ĐẶT LỊCH HẸN & LỊCH CỦA BỆNH NHÂN
        // =============================================================
        function toggleDashboardEhrAccordion() {
            const acc = document.getElementById('dashboard-ehr-accordion');
            const arrow = document.getElementById('dashboard-ehr-arrow');
            if (acc) {
                if (acc.style.display === 'none') {
                    acc.style.display = 'block';
                    if (arrow) arrow.classList.add('rotate-180');
                } else {
                    acc.style.display = 'none';
                    if (arrow) arrow.classList.remove('rotate-180');
                }
            }
        }

        function xuLyChonTepYTe(input) {
            const preview = document.getElementById('modal-dl-file-preview');
            if (!preview) return;
            preview.innerHTML = '';
            AppState.tepYTeUploads = [];

            if (!input.files || input.files.length === 0) return;

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const base64Data = e.target.result;
                    AppState.tepYTeUploads.push(base64Data);

                    const isImage = file.type.startsWith('image/');
                    const item = document.createElement('div');
                    item.className = 'flex items-center gap-1.5 px-2.5 py-1 bg-white border border-slate-200 rounded-xl text-slate-700 shadow-xs';
                    item.innerHTML = `
                        <i class="fa-solid ${isImage ? 'fa-image text-medical-600' : 'fa-file-lines text-amber-500'}"></i>
                        <span class="max-w-[120px] truncate font-medium">${file.name}</span>
                        <span class="text-[10px] text-slate-400 font-mono">(${(file.size / 1024).toFixed(0)}KB)</span>
                    `;
                    preview.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        }

        async function moModalDatLich(bacSiId = null) {
            if (!AppState.token) {
                Swal.fire({ icon: 'warning', title: 'Yêu cầu đăng nhập', text: 'Bạn cần đăng nhập tài khoản trước khi đặt lịch!' });
                window.location.href = '/dang-nhap';
                return;
            }

            // Fix #2: Reset selected slot state when modal opens
            AppState.slotDaChon = null;

            // Reset tệp đính kèm
            AppState.tepYTeUploads = [];
            const fileInput = document.getElementById('modal-dl-files');
            if (fileInput) fileInput.value = '';
            const preview = document.getElementById('modal-dl-file-preview');
            if (preview) preview.innerHTML = '';

            // Fix #7: Chặn chọn ngày quá khứ & Reset ngày khám mặc định
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('modal-dl-ngay');
            if (dateInput) {
                dateInput.min = today;
                if (!dateInput.value || dateInput.value < today) {
                    dateInput.value = today;
                }
            }

            // Reset lý do khám
            const lyDoInput = document.getElementById('modal-dl-ly-do');
            if (lyDoInput) lyDoInput.value = '';

            if (AppState.currentUser) {
                if (document.getElementById('modal-dl-ho-ten')) {
                    document.getElementById('modal-dl-ho-ten').value = AppState.currentUser.ho_ten || '';
                }
                if (document.getElementById('modal-dl-sdt')) {
                    document.getElementById('modal-dl-sdt').value = AppState.currentUser.so_dien_thoai || '0901234567';
                }
                if (document.getElementById('modal-dl-cccd') && AppState.currentUser.so_cccd) {
                    document.getElementById('modal-dl-cccd').value = AppState.currentUser.so_cccd;
                }
            }

            // Tự động nạp hồ sơ bệnh án điện tử đã lưu để điền sẵn
            try {
                const resBn = await goiApi('GET', '/api/v1/benh-nhan/ho-so-cua-toi');
                if (resBn.ok && resBn.data && resBn.data.du_lieu) {
                    const bn = resBn.data.du_lieu;
                    if (bn.nhom_mau && document.getElementById('modal-dl-nhom-mau')) document.getElementById('modal-dl-nhom-mau').value = bn.nhom_mau;
                    if (bn.so_cccd && document.getElementById('modal-dl-cccd')) document.getElementById('modal-dl-cccd').value = bn.so_cccd;
                    if (bn.tien_su_di_ung && document.getElementById('modal-dl-tien-su-di-ung')) document.getElementById('modal-dl-tien-su-di-ung').value = bn.tien_su_di_ung;
                    if (bn.tien_su_benh && document.getElementById('modal-dl-tien-su-benh')) document.getElementById('modal-dl-tien-su-benh').value = bn.tien_su_benh;
                    if (bn.nguoi_lien_he_khan_cap && document.getElementById('modal-dl-nguoi-than')) document.getElementById('modal-dl-nguoi-than').value = bn.nguoi_lien_he_khan_cap;
                    if (bn.sdt_khan_cap && document.getElementById('modal-dl-sdt-khan-cap')) document.getElementById('modal-dl-sdt-khan-cap').value = bn.sdt_khan_cap;
                }
            } catch (e) {
                console.log("Không có hồ sơ bệnh án mở rộng sẵn.");
            }

            // BƯỚC 1: Nạp danh sách chuyên khoa vào Bước 1
            const ckSelect = document.getElementById('modal-dl-chuyen-khoa');
            if (ckSelect) {
                let ckOpts = '<option value="">-- Tất Cả Chuyên Khoa --</option>';
                (AppState.danhSachChuyenKhoa || []).forEach(ck => {
                    const ten = ck.ten_khoa || ck.ten_chuyen_khoa;
                    ckOpts += `<option value="${ck.id}">${ten}</option>`;
                });
                ckSelect.innerHTML = ckOpts;

                // Nếu có bác sĩ truyền vào, tự động chọn đúng chuyên khoa của bác sĩ đó
                if (bacSiId) {
                    const bs = (AppState.danhSachBacSi || []).find(b => b.id == bacSiId);
                    if (bs) {
                        const khoaId = bs.chuyen_khoa_id || (bs.chuyen_khoa ? bs.chuyen_khoa.id : '');
                        if (khoaId) ckSelect.value = khoaId;
                    }
                }
            }

            // BƯỚC 2: Lọc và nạp danh sách bác sĩ thuộc chuyên khoa đã chọn
            locBacSiTheoChuyenKhoaModal(bacSiId);

            capNhatGiaKhamModal();
            if (typeof kiemTraCaTrucDatLich === 'function') {
                await kiemTraCaTrucDatLich();
            }
            moModal('modal-dat-lich');
        }

        // HÀM LỌC BÁC SĨ THEO CHUYÊN KHOA TRONG MODAL ĐẶT LỊCH
        function locBacSiTheoChuyenKhoaModal(preselectDoctorId = null) {
            const chuyenKhoaId = document.getElementById('modal-dl-chuyen-khoa')?.value;
            const selectBs = document.getElementById('modal-dl-bac-si');
            const infoBs = document.getElementById('modal-dl-bac-si-info');
            if (!selectBs) return;

            let bacSiList = AppState.danhSachBacSi || [];

            if (chuyenKhoaId) {
                bacSiList = bacSiList.filter(b => {
                    return (b.chuyen_khoa_id == chuyenKhoaId) || 
                           (b.chuyen_khoa && b.chuyen_khoa.id == chuyenKhoaId);
                });
            }

            if (bacSiList.length === 0) {
                selectBs.innerHTML = '<option value="">-- Chưa có bác sĩ thuộc khoa này --</option>';
                if (infoBs) infoBs.textContent = 'Chưa có bác sĩ';
                const giaEl = document.getElementById('modal-dl-gia-kham');
                if (giaEl) giaEl.textContent = '-- đ';
                const sangContainer = document.getElementById('modal-dl-slots-sang');
                const chieuContainer = document.getElementById('modal-dl-slots-chieu');
                if (sangContainer) sangContainer.innerHTML = '<div class="col-span-4 text-center py-4 text-slate-400 text-xs">Vui lòng chọn chuyên khoa khác có bác sĩ trực.</div>';
                if (chieuContainer) chieuContainer.innerHTML = '';
                return;
            }

            selectBs.innerHTML = bacSiList.map(b => {
                const tenKhoa = b.chuyen_khoa ? (b.chuyen_khoa.ten_khoa || b.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const phong = b.phong_kham ? ` (${b.phong_kham})` : '';
                return `<option value="${b.id}">${b.ho_ten} - ${tenKhoa}${phong}</option>`;
            }).join('');

            if (preselectDoctorId && bacSiList.some(b => b.id == preselectDoctorId)) {
                selectBs.value = preselectDoctorId;
            } else {
                selectBs.value = bacSiList[0].id;
            }

            capNhatGiaKhamModal();
            taiSlotsKhaDungDatLich();
        }

        function capNhatGiaKhamModal() {
            const bId = document.getElementById('modal-dl-bac-si')?.value;
            const bs = (AppState.danhSachBacSi || []).find(b => b.id == bId);
            const infoBs = document.getElementById('modal-dl-bac-si-info');
            if (bs) {
                const gia = Number(bs.gia_kham || 200000).toLocaleString('vi-VN') + ' đ';
                const giaEl = document.getElementById('modal-dl-gia-kham');
                if (giaEl) giaEl.textContent = gia;

                const tenKhoa = bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const phong = bs.phong_kham || 'Phòng khám đa khoa';
                if (infoBs) infoBs.textContent = `${tenKhoa} • ${phong}`;
            }
        }

        // HÀM CẬP NHẬT CAM KẾT CA KHÁM VÀ QUY CHẾ TIẾP NHẬN TRÁNH TRỄ DOMINO
        function capNhatThongBaoCamKetCaKham(start, end) {
            const startHour = parseInt(start.split(':')[0], 10);
            const startMin = parseInt(start.split(':')[1] || '0', 10);
            const isSang = startHour < 12;

            const lblPhienKham = document.getElementById('lbl-phien-kham');
            const lblKhungGio = document.getElementById('lbl-khung-gio-chon');
            const badgeStt = document.getElementById('badge-stt-du-kien');

            if (lblKhungGio) lblKhungGio.textContent = `${start} - ${end}`;

            let stt = 1;
            let tenBuoi = 'Buổi Sáng (08:00 - 11:30)';

            if (isSang) {
                tenBuoi = 'Buổi Sáng (08:00 - 11:30)';
                // 08:00 -> #1, 08:30 -> #2, 09:00 -> #3, ...
                const totalMinutesFrom8 = Math.max(0, (startHour - 8) * 60 + startMin);
                stt = Math.floor(totalMinutesFrom8 / 30) + 1;
            } else {
                tenBuoi = 'Buổi Chiều (13:30 - 16:30)';
                // 13:30 -> #1, 14:00 -> #2, 14:30 -> #3, ...
                const totalMinutesFrom1330 = Math.max(0, (startHour - 13) * 60 + startMin - 30);
                stt = Math.floor(totalMinutesFrom1330 / 30) + 1;
            }

            if (lblPhienKham) lblPhienKham.textContent = tenBuoi;
            if (badgeStt) {
                const padStt = String(stt).padStart(2, '0');
                badgeStt.textContent = `STT dự kiến: #${padStt} (${isSang ? 'Ca Sáng' : 'Ca Chiều'})`;
            }
        }

        function chonSlot(el, start, end) {
            // 1. Bỏ chọn tất cả các nút slot trong cả ca sáng và ca chiều
            const container = document.getElementById('modal-dl-slots') || document;
            container.querySelectorAll('.slot-btn').forEach(b => {
                b.classList.remove('selected', 'bg-medical-600', 'text-white', 'font-bold', 'ring-2', 'ring-medical-500');
                b.classList.add('bg-white', 'text-slate-800');
                const subTxt = b.querySelector('.slot-sub-text') || b.querySelector('.font-sans');
                if (subTxt && !b.disabled) subTxt.textContent = 'Trống';
            });

            // 2. Kích hoạt duy nhất 1 nút slot được người dùng bấm
            el.classList.remove('bg-white', 'text-slate-800');
            el.classList.add('selected', 'bg-medical-600', 'text-white', 'font-bold', 'ring-2', 'ring-medical-500');
            const activeSub = el.querySelector('.slot-sub-text') || el.querySelector('.font-sans');
            if (activeSub) activeSub.textContent = 'Đã chọn';

            // 3. Cập nhật state slot đã chọn (chuẩn hóa định dạng HH:MM:00)
            const startFormatted = start.length === 5 ? (start + ':00') : start;
            const endFormatted = end.length === 5 ? (end + ':00') : end;
            AppState.slotDaChon = { batDau: startFormatted, ketThuc: endFormatted };

            // 4. Đồng bộ bảng quy chế & STT dự kiến
            capNhatThongBaoCamKetCaKham(start.substring(0, 5), end.substring(0, 5));
        }

        async function xacNhanDatLich() {
            const bacSiId = document.getElementById('modal-dl-bac-si')?.value;
            const hoTen = document.getElementById('modal-dl-ho-ten')?.value;
            const sdt = document.getElementById('modal-dl-sdt')?.value;
            const ngayKham = document.getElementById('modal-dl-ngay')?.value;
            const lyDo = document.getElementById('modal-dl-ly-do')?.value;

            if (!hoTen || !ngayKham) {
                showToast('error', 'Thiếu thông tin', 'Vui lòng nhập họ tên bệnh nhân và ngày khám.');
                return;
            }

            if (!bacSiId) {
                showToast('error', 'Chưa chọn bác sĩ', 'Vui lòng chọn bác sĩ khám bệnh.');
                return;
            }

            // Kiểm tra bắt buộc đã chọn duy nhất 1 slot khám còn trống
            if (!AppState.slotDaChon || !AppState.slotDaChon.batDau) {
                showToast('error', 'Chưa chọn khung giờ', 'Vui lòng chọn một khung giờ khám còn trống.');
                return;
            }

            const btnSubmit = document.querySelector('#form-dat-lich button[type="submit"]');
            const originalBtnHtml = btnSubmit ? btnSubmit.innerHTML : '';
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> <span>Đang Đặt Lịch...</span>';
            }

            try {
                const payload = {
                    bac_si_id: Number(bacSiId),
                    ho_ten_benh_nhan: hoTen,
                    so_dien_thoai: sdt,
                    ngay_kham: ngayKham,
                    gio_bat_dau: AppState.slotDaChon.batDau,
                    gio_ket_thuc: AppState.slotDaChon.ketThuc,
                    ly_do_kham: lyDo || 'Khám sức khỏe tổng quát',
                    tep_dinh_kem: AppState.tepYTeUploads.length > 0 ? AppState.tepYTeUploads : null,
                    so_cccd: document.getElementById('modal-dl-cccd') ? document.getElementById('modal-dl-cccd').value : null,
                    nhom_mau: document.getElementById('modal-dl-nhom-mau') ? document.getElementById('modal-dl-nhom-mau').value : null,
                    tien_su_di_ung: document.getElementById('modal-dl-tien-su-di-ung') ? document.getElementById('modal-dl-tien-su-di-ung').value : null,
                    tien_su_benh: document.getElementById('modal-dl-tien-su-benh') ? document.getElementById('modal-dl-tien-su-benh').value : null,
                    nguoi_lien_he_khan_cap: document.getElementById('modal-dl-nguoi-than') ? document.getElementById('modal-dl-nguoi-than').value : null,
                    sdt_khan_cap: document.getElementById('modal-dl-sdt-khan-cap') ? document.getElementById('modal-dl-sdt-khan-cap').value : null,
                };

                if (typeof AppStateDoiTuongKham !== 'undefined' && AppStateDoiTuongKham === 'NGUOI_THAN') {
                    const chonHoSo = document.getElementById('modal-dl-chon-ho-so')?.value;
                    if (chonHoSo && chonHoSo !== 'MOI') {
                        payload.benh_nhan_id = Number(chonHoSo);
                    } else {
                        payload.quan_he_chu_tai_khoan = document.getElementById('modal-dl-quan-he')?.value || 'NGUOI_THAN';
                        payload.ngay_sinh = document.getElementById('modal-dl-ngay-sinh-nt')?.value || null;
                    }
                } else {
                    payload.quan_he_chu_tai_khoan = 'BAN_THAN';
                }

                const res = await goiApi('POST', '/api/v1/lich-hen/dat-lich', payload);

                if (res.ok) {
                    const lh = res.data.du_lieu;
                    const bs = (AppState.danhSachBacSi || []).find(b => b.id == lh.bac_si_id);
                    const tenBs = bs ? bs.ho_ten : `Bác sĩ #${lh.bac_si_id}`;
                    const tenKhoa = bs && bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Khám';
                    const gioBatDau = (lh.gio_bat_dau || '').substring(0, 5);
                    const isSang = parseInt(gioBatDau.split(':')[0] || '8', 10) < 12;
                    const tenBuoi = isSang ? 'Buổi Sáng (trước 11:30)' : 'Buổi Chiều (trước 17:00)';
                    const ngayFormatted = lh.ngay_kham ? lh.ngay_kham.split('-').reverse().join('/') : '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt Lịch Khám Thành Công!',
                        html: `
                            <div class="text-left text-xs space-y-2.5 mt-2 p-3.5 bg-slate-50 rounded-2xl border border-slate-200">
                                <div><strong>Bác sĩ khám:</strong> <span class="text-slate-900 font-bold">${tenBs}</span></div>
                                <div><strong>Chuyên khoa:</strong> <span class="text-sky-700 font-semibold">${tenKhoa}</span></div>
                                <div><strong>Ngày khám:</strong> <span class="font-bold text-slate-800">${ngayFormatted}</span></div>
                                <div><strong>Khung giờ hẹn tiếp nhận:</strong> <span class="text-rose-600 font-extrabold text-sm">${gioBatDau} - ${(lh.gio_ket_thuc || '').substring(0, 5)}</span></div>
                                <div class="p-2.5 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 text-[11px] leading-relaxed">
                                    <strong><i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Cam kết ca khám:</strong> Phòng khám cam kết Quý khách <strong>chắc chắn được hoàn thành khám 100% trong ${tenBuoi}</strong>.
                                </div>
                                <div class="text-[11px] text-slate-500 italic">
                                    * Giờ vào phòng khám có thể dao động linh hoạt ±10-15 phút do tính chất chuyên môn của các ca khám trước. Vui lòng đến trước 10-15 phút để lấy số tiếp nhận ưu tiên.
                                </div>
                            </div>
                        `,
                        confirmButtonColor: '#0284c7',
                        confirmButtonText: 'Đã Hiểu & Xem Lịch Khám'
                    });
                    dongModal('modal-dat-lich');
                    await taiDanhSachLichHen();
                    if (typeof taiDanhSachBenhNhan === 'function') await taiDanhSachBenhNhan();
                    if (typeof taiHoSoGiaDinhVaThanhVien === 'function') await taiHoSoGiaDinhVaThanhVien();
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
            } finally {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalBtnHtml;
                }
            }
        }

        // =============================================================
        // CHỨC NĂNG DỜI LỊCH (RESCHEDULE) & XEM TỆP Y TẾ
        // =============================================================
        function chonSlotDoiLich(el, start, end) {
            document.querySelectorAll('#modal-doi-lich-slots .slot-btn-reschedule').forEach(b => b.classList.remove('selected'));
            el.classList.add('selected');
            AppState.slotDoiLichDaChon = { batDau: start + ':00', ketThuc: end + ':00' };
        }

        function moModalDoiLich(id) {
            const lh = AppState.danhSachLichHen.find(l => l.id == id);
            if (!lh) return;

            document.getElementById('modal-doi-lich-id').value = lh.id;
            document.getElementById('modal-doi-lich-ma').textContent = `#${lh.id} (${lh.ma_lich_hen || ''})`;
            
            const bs = AppState.danhSachBacSi.find(b => b.id == lh.bac_si_id);
            document.getElementById('modal-doi-lich-bs').textContent = bs ? bs.ho_ten : `Bác sĩ #${lh.bac_si_id}`;
            document.getElementById('modal-doi-lich-tg-cu').textContent = `${lh.gio_bat_dau} ngày ${lh.ngay_kham}`;

            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('modal-doi-lich-ngay');
            dateInput.min = today;
            dateInput.value = lh.ngay_kham >= today ? lh.ngay_kham : today;

            document.getElementById('modal-doi-lich-ly-do').value = '';
            moModal('modal-doi-lich');
        }

        async function xacNhanDoiLich() {
            const id = document.getElementById('modal-doi-lich-id').value;
            const ngayKham = document.getElementById('modal-doi-lich-ngay').value;
            const lyDo = document.getElementById('modal-doi-lich-ly-do').value;

            if (!ngayKham) {
                showToast('warning', 'Thiếu ngày khám', 'Vui lòng chọn ngày khám mới.');
                return;
            }

            const payload = {
                ngay_kham: ngayKham,
                gio_bat_dau: AppState.slotDoiLichDaChon.batDau,
                gio_ket_thuc: AppState.slotDoiLichDaChon.ketThuc,
                ly_do_doi_lich: lyDo || 'Bệnh nhân đề nghị dời giờ khám'
            };

            const res = await goiApi('PUT', `/api/v1/lich-hen/${id}/doi-lich`, payload);

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dời Lịch Thành Công!',
                    text: res.data.thong_diep || 'Lịch hẹn đã được cập nhật sang thời gian mới.',
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-doi-lich');
                await taiDanhSachLichHen();
            } else if (res.status === 409) {
                Swal.fire({
                    icon: 'error',
                    title: 'Trùng Lịch Bác Sĩ (409)',
                    text: res.data.thong_diep || 'Khung giờ này đã có bệnh nhân khác đặt. Vui lòng chọn khung giờ khác!',
                    confirmButtonColor: '#0284c7'
                });
            } else {
                showToast('error', 'Lỗi dời lịch', res.data.thong_diep || 'Không thể dời lịch hẹn.');
            }
        }

        function moModalXemTepYTe(id) {
            const lh = AppState.danhSachLichHen.find(l => l.id == id);
            if (!lh || !lh.tep_dinh_kem || lh.tep_dinh_kem.length === 0) {
                showToast('info', 'Thông báo', 'Ca khám này không có tệp hoặc đơn thuốc đính kèm.');
                return;
            }

            const container = document.getElementById('modal-tep-y-te-content');
            let files = lh.tep_dinh_kem;
            if (typeof files === 'string') {
                try { files = JSON.parse(files); } catch(e) { files = [files]; }
            }
            if (!Array.isArray(files)) files = [files];

            container.innerHTML = files.map((fileUrl, idx) => {
                const isImage = typeof fileUrl === 'string' && (fileUrl.startsWith('data:image') || fileUrl.match(/\.(jpeg|jpg|gif|png|webp)/i));
                if (isImage) {
                    return `
                        <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2 shadow-xs">
                            <div class="text-[11px] font-bold text-slate-700 truncate flex items-center justify-between">
                                <span>Ảnh đính kèm #${idx + 1}</span>
                                <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Đã nạp</span>
                            </div>
                            <img src="${fileUrl}" alt="Hồ sơ y tế #${idx + 1}" class="w-full h-48 object-contain bg-slate-900/5 rounded-xl border border-slate-100 cursor-pointer hover:opacity-90 transition" onclick="window.open('${fileUrl}', '_blank')">
                            <a href="${fileUrl}" target="_blank" class="block text-center text-xs font-bold text-medical-600 hover:text-medical-700 pt-1"><i class="fa-solid fa-up-right-from-square mr-1"></i> Mở xem toàn màn hình</a>
                        </div>
                    `;
                } else {
                    return `
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col items-center justify-center space-y-2">
                            <i class="fa-solid fa-file-pdf text-rose-500 text-3xl"></i>
                            <span class="text-xs font-bold text-slate-800">Tệp hồ sơ #${idx + 1}</span>
                            <a href="${fileUrl}" target="_blank" class="px-3 py-1.5 bg-medical-600 text-white rounded-xl text-xs font-bold hover:bg-medical-700 transition"><i class="fa-solid fa-download mr-1"></i> Tải / Mở tệp</a>
                        </div>
                    `;
                }
            }).join('');

            moModal('modal-xem-tep-y-te');
        }

        async function taiDanhSachLichHen() {
            const res = await goiApi('GET', '/api/v1/lich-hen?per_page=50');
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
                    return (lh.benh_nhan && (lh.benh_nhan.tai_khoan_id == u.id || lh.benh_nhan.id == u.id)) ||
                           (lh.tai_khoan_id == u.id) ||
                           (lh.benh_nhan_id == u.id) ||
                           (lh.ho_ten_benh_nhan && u.ho_ten && lh.ho_ten_benh_nhan.toLowerCase() === u.ho_ten.toLowerCase()) ||
                           (lh.so_dien_thoai && u.so_dien_thoai && lh.so_dien_thoai === u.so_dien_thoai);
                });
            } else if (u && u.vai_tro === 'BAC_SI') {
                const bs = AppState.danhSachBacSi.find(b => 
                    b.tai_khoan_id == u.id || 
                    b.id == u.id || 
                    (b.ho_ten && u.ho_ten && b.ho_ten.toLowerCase().includes(u.ho_ten.toLowerCase()))
                );
                if (bs) {
                    displayList = list.filter(lh => lh.bac_si_id == bs.id);
                }
            }

            if (!displayList || displayList.length === 0) {
                const emptyMsg = (u && u.vai_tro === 'BENH_NHAN') 
                    ? 'Bạn chưa có lịch hẹn nào. Hãy đặt lịch khám mới!' 
                    : (u && u.vai_tro === 'BAC_SI') 
                        ? 'Chưa có lịch hẹn khám nào được phân công cho bác sĩ.' 
                        : 'Không có dữ liệu lịch hẹn nào trong hệ thống.';
                tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8 text-slate-400 font-medium"><i class="fa-regular fa-calendar-xmark mr-1.5 text-slate-400"></i> ${emptyMsg}</td></tr>`;
                return;
            }

            tbody.innerHTML = displayList.map(lh => {
                const bs = AppState.danhSachBacSi.find(b => b.id == lh.bac_si_id);
                const tenBs = bs ? bs.ho_ten : (`Bác sĩ ID #${lh.bac_si_id}`);
                const tenKhoa = (bs && bs.chuyen_khoa) ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                
                const tenBn = lh.benh_nhan ? (lh.benh_nhan.ho_ten || '') : (lh.ho_ten_benh_nhan || (`Bệnh nhân #${lh.benh_nhan_id}`));
                const sdtBn = lh.benh_nhan ? (lh.benh_nhan.so_dien_thoai || '') : (lh.so_dien_thoai || '');

                // Fix #5: Only valid appointment statuses can be rescheduled/cancelled by patient
                const canManage = ['CHO_XAC_NHAN', 'DA_XAC_NHAN'].includes(lh.trang_thai);
                
                let actionBtns = [];
                if (canManage) {
                    actionBtns.push(`<button onclick="moModalDoiLich(${lh.id})" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Dời Giờ / Ngày Khám"><i class="fa-solid fa-clock-rotate-left"></i> Dời Lịch</button>`);
                    actionBtns.push(`<button onclick="huyLichHenBenhNhan(${lh.id})" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Hủy Ca Khám"><i class="fa-solid fa-ban"></i> Hủy</button>`);
                }
                if (lh.tep_dinh_kem && (Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length > 0 : true)) {
                    const count = Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length : 1;
                    actionBtns.push(`<button onclick="moModalXemTepYTe(${lh.id})" class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Xem Hồ Sơ Đính Kèm"><i class="fa-solid fa-paperclip"></i> ${count} tệp</button>`);
                }
                const actionHtml = actionBtns.length > 0 ? `<div class="flex items-center justify-center gap-1.5 flex-wrap">${actionBtns.join('')}</div>` : `<span class="text-slate-400 text-xs">--</span>`;

                const soLanDoi = lh.so_lan_doi_lich ? `<span class="text-[10px] text-sky-600 block">(Đã dời ${lh.so_lan_doi_lich} lần)</span>` : '';

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${lh.id}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <button type="button" onclick="moModalXemEhrHoacGiaDinh(${lh.benh_nhan_id || (lh.benh_nhan ? lh.benh_nhan.id : 0)})" class="font-bold text-slate-900 hover:text-medical-600 transition flex items-center gap-1 text-left group" title="Nhấp để xem hồ sơ bệnh án">
                                    <span>${tenBn}</span>
                                    <i class="fa-solid fa-address-card text-[11px] text-slate-400 group-hover:text-medical-600 transition"></i>
                                </button>
                                ${lh.benh_nhan && lh.benh_nhan.quan_he_chu_tai_khoan && lh.benh_nhan.quan_he_chu_tai_khoan !== 'BAN_THAN' ? 
                                    `<button type="button" onclick="chuyenTab('tab-ho-so-gia-dinh')" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-100 hover:bg-purple-200 text-purple-700 border border-purple-200 transition" title="Xem trong Hồ Sơ Gia Đình">${lh.benh_nhan.quan_he_chu_tai_khoan === 'CON' ? 'Con cái' : (lh.benh_nhan.quan_he_chu_tai_khoan === 'CHA_ME' ? 'Bố/Mẹ' : 'Người thân')}</button>` : ''}
                            </div>
                            ${sdtBn ? `<span class="text-[11px] text-slate-400 font-mono block">${sdtBn}</span>` : ''}
                        </td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${tenBs}</td>
                        <td class="py-3 px-4 text-slate-600">${tenKhoa}</td>
                        <td class="py-3 px-4 text-slate-600">${lh.ngay_kham}</td>
                        <td class="py-3 px-4 font-mono font-semibold text-medical-600">
                            ${lh.gio_bat_dau} - ${lh.gio_ket_thuc}
                            ${soLanDoi}
                        </td>
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
            } else if (res.status === 422 && res.data.ma_loi === 'KHONG_THE_HUY_SAT_GIO') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chặn Hủy Sát Giờ (< 2 tiếng)',
                    html: `<div class="text-left text-xs space-y-2">
                        <p class="text-rose-600 font-bold">${res.data.thong_diep}</p>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-slate-700">
                            <strong>Quy định phòng khám:</strong> Để đảm bảo lịch trực của y bác sĩ và công bằng cho các bệnh nhân chờ khám, hệ thống không cho phép tự hủy ca khám dưới 2 tiếng trước giờ hẹn.
                        </div>
                        <p class="font-bold text-sky-700 text-center text-sm pt-1">📞 Hotline tiếp đón: 1900 6868 (Trực 24/7)</p>
                    </div>`,
                    confirmButtonColor: '#0284c7',
                    confirmButtonText: 'Đã hiểu'
                });
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
            const bn = lh.benh_nhan || {};
            const bnName = bn.ho_ten || (lh.ho_ten_benh_nhan || 'Bệnh nhân');
            const nhomMau = bn.nhom_mau ? `<span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 font-bold">Nhóm máu: ${bn.nhom_mau}</span>` : '';
            const diUng = bn.tien_su_di_ung ? `<div class="text-amber-700 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Dị ứng: <strong>${bn.tien_su_di_ung}</strong></div>` : '';
            const benhNen = bn.tien_su_benh ? `<div class="text-sky-700 font-medium"><i class="fa-solid fa-heart-pulse mr-1"></i> Bệnh nền: <strong>${bn.tien_su_benh}</strong></div>` : '';
            const nguoiThan = bn.nguoi_lien_he_khan_cap ? `<div class="text-slate-500"><i class="fa-solid fa-phone mr-1"></i> Liên hệ khẩn cấp: ${bn.nguoi_lien_he_khan_cap} (${bn.sdt_khan_cap || ''})</div>` : '';
            const tepBtn = (lh.tep_dinh_kem && (Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length > 0 : true))
                ? `<div class="pt-2"><button type="button" onclick="moModalXemTepYTe(${lh.id})" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl font-bold text-xs inline-flex items-center gap-1.5 shadow-xs transition hover:scale-102"><i class="fa-solid fa-paperclip text-indigo-600"></i> Xem Hồ Sơ / Đơn Thuốc Cũ Đính Kèm</button></div>`
                : '';

            document.getElementById('box-chon-ca-kham-thong-tin').innerHTML = `
                <div class="flex items-center justify-between border-b border-sky-100 pb-2 mb-2">
                    <div class="font-extrabold text-medical-700 text-sm">TIẾP NHẬN CA KHÁM #${lh.id} - ${bnName}</div>
                    ${nhomMau}
                </div>
                <div class="text-slate-600 space-y-1 text-xs">
                    <div>Khám ngày: <strong>${lh.ngay_kham} (${lh.gio_bat_dau})</strong> | Triệu chứng: <strong class="text-slate-900">${lh.ly_do_kham || 'Khám bệnh'}</strong></div>
                    ${diUng}
                    ${benhNen}
                    ${nguoiThan}
                    <div class="pt-2 flex flex-wrap items-center gap-2">
                        ${tepBtn}
                        <button type="button" onclick="inHoaDonKhamBenhCaHienTai()" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-xl font-bold text-xs inline-flex items-center gap-1.5 shadow-xs transition hover:scale-102">
                            <i class="fa-solid fa-file-invoice-dollar text-sky-600"></i> Xem / In Hóa Đơn Khám
                        </button>
                    </div>
                </div>
            `;

            showToast('info', 'Tiếp Nhận Ca Khám', `Đã nạp hồ sơ bệnh án của bệnh nhân: ${bnName}.`);
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

            const chanDoan = (document.getElementById('input-chan-doan')?.value || '').trim() || 'Theo dõi lâm sàng';

            const payload = {
                lich_hen_id: Number(AppState.caKhamDangChon.id),
                benh_nhan_id: Number(AppState.caKhamDangChon.benh_nhan_id || AppState.caKhamDangChon.benh_nhan?.id || 1),
                bac_si_id: Number(AppState.caKhamDangChon.bac_si_id || AppState.currentUser?.bac_si?.id || 1),
                danh_sach_dich_vu_id: checked,
                chan_doan_so_bo: chanDoan
            };

            const res = await goiApi('POST', '/api/v1/dich-vu/chi-dinh', payload);

            if (res.ok) {
                // Tự động chuyển chỉ định sang Thu Ngân lập / đồng bộ hóa đơn
                try {
                    await goiApi('POST', '/api/v1/hoa-don/tao-tu-dong', {
                        lich_hen_id: Number(AppState.caKhamDangChon.id),
                        giam_gia: 0,
                        ghi_chu: `Chỉ định cận lâm sàng (${chanDoan})`
                    });
                } catch (e) {
                    console.warn('Lỗi tự động gửi sang thu ngân:', e);
                }

                // Cập nhật trạng thái lịch hẹn sang Đang Khám nếu ca mới tiếp nhận
                try {
                    if (AppState.caKhamDangChon.trang_thai !== 'DANG_KHAM' && AppState.caKhamDangChon.trang_thai !== 'DA_HOAN_THANH') {
                        await goiApi('PUT', `/api/v1/lich-hen/${AppState.caKhamDangChon.id}/trang-thai`, {
                            trang_thai: 'DANG_KHAM'
                        });
                        AppState.caKhamDangChon.trang_thai = 'DANG_KHAM';
                    }
                } catch (e) {
                    console.warn('Lỗi cập nhật trạng thái lịch hẹn:', e);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Lưu & Gửi Thu Ngân thành công!',
                    text: `Đã lưu ${checked.length} dịch vụ cận lâm sàng cho ca #${AppState.caKhamDangChon.id} và tự động chuyển viện phí sang bàn Thu Ngân.`,
                    confirmButtonColor: '#0284c7'
                });
                document.querySelectorAll('.cb-dich-vu-cls').forEach(c => c.checked = false);
                tinhTongTienCLS();
                await taiDanhSachLichHen();
                if (typeof taiDanhSachHoaDon === 'function') {
                    await taiDanhSachHoaDon();
                }
            } else {
                const errMsg = res.data?.thong_diep || res.data?.message || (res.data?.errors ? Object.values(res.data.errors).flat().join(', ') : 'Không thể lưu chỉ định.');
                showToast('error', 'Lỗi', errMsg);
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

            if (!list || list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-6 text-slate-400">Chưa có danh sách bác sĩ.</td></tr>';
                return;
            }

            tbody.innerHTML = list.map(b => {
                const khoa = b.chuyen_khoa ? (b.chuyen_khoa.ten_khoa || b.chuyen_khoa.ten_chuyen_khoa) : 'N/A';
                const hoTenEsc = (b.ho_ten || '').replace(/'/g, "\\'");
                const avatarThumb = b.avatar 
                    ? `<img src="${b.avatar}" class="w-7 h-7 rounded-lg object-cover border border-sky-300 shadow-xs">` 
                    : `<div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-[10px]">${(b.ho_ten || 'BS').substring(0, 2).toUpperCase()}</div>`;

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">${b.ma_bac_si || ('BS' + b.id)}</td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center space-x-2">
                                ${avatarThumb}
                                <span class="font-extrabold text-slate-900">${b.ho_ten}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-3 text-slate-600">${khoa}</td>
                        <td class="py-2.5 px-3">
                            <button type="button" onclick="moModalSuaGiaKham(${b.id})" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline inline-flex items-center gap-1 group/p" title="Nhấn để đổi giá khám">
                                <span>${Number(b.gia_kham || 0).toLocaleString('vi-VN')} đ</span>
                                <i class="fa-solid fa-pen text-[9px] text-slate-400 group-hover/p:text-amber-600 transition"></i>
                            </button>
                        </td>
                        <td class="py-2.5 px-3 font-medium text-slate-700">${b.phong_kham || 'P201'}</td>
                        <td class="py-2.5 px-3 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <button onclick="moModalSuaGiaKham(${b.id})" class="px-2 py-1 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Sửa đơn giá khám">
                                    <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i>
                                    <span>Giá</span>
                                </button>
                                <button onclick="moModalLichTrucBacSi(${b.id}, '${hoTenEsc}')" class="px-2 py-1 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Quản lý ca trực / lịch trực">
                                    <i class="fa-solid fa-calendar-days text-[10px]"></i>
                                    <span>Lịch trực</span>
                                </button>
                                <button onclick="moModalSuaBacSi(${b.id})" class="px-2 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Sửa thông tin bác sĩ">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    <span>Sửa</span>
                                </button>
                                <button onclick="xacNhanXoaBacSi(${b.id}, '${hoTenEsc}')" class="px-2 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa bác sĩ">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    <span>Xóa</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderAdminChuyenKhoaTable() {
            const tbody = document.getElementById('tbody-admin-chuyen-khoa');
            if (!tbody) return;

            if (!AppState.danhSachChuyenKhoa || AppState.danhSachChuyenKhoa.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-slate-400">Chưa có danh mục chuyên khoa.</td></tr>';
                return;
            }

            tbody.innerHTML = AppState.danhSachChuyenKhoa.map(ck => {
                const tenKhoa = ck.ten_khoa || ck.ten_chuyen_khoa || '';
                const tenKhoaEsc = tenKhoa.replace(/'/g, "\\'");
                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">${ck.ma_khoa || ck.ma_chuyen_khoa}</td>
                        <td class="py-2.5 px-3 font-extrabold text-slate-900">${tenKhoa}</td>
                        <td class="py-2.5 px-3 text-slate-500 max-w-[160px] truncate" title="${ck.mo_ta || ''}">${ck.mo_ta || '--'}</td>
                        <td class="py-2.5 px-3 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <button onclick="moModalSuaChuyenKhoa(${ck.id})" class="px-2 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Sửa chuyên khoa">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    <span>Sửa</span>
                                </button>
                                <button onclick="xacNhanXoaChuyenKhoa(${ck.id}, '${tenKhoaEsc}')" class="px-2 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa chuyên khoa">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    <span>Xóa</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
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
                const isLocked = tk.trang_thai === 'BI_KHOA' || tk.trang_thai === 'KHOA';
                const statusBadge = isLocked
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ KHÓA</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">HOẠT ĐỘNG</span>';

                const lockBtnText = isLocked ? '🔓 Mở Khóa' : '🔒 Khóa';
                const lockBtnClass = isLocked ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100';
                const newStatus = isLocked ? 'HOAT_DONG' : 'BI_KHOA';
                const usernameEsc = (tk.ten_dang_nhap || '').replace(/'/g, "\\'");
                const fullnameEsc = (tk.ho_ten || '').replace(/'/g, "\\'");

                // Bắt buộc: Tài khoản ADMIN tuyệt đối KHÔNG có nút xóa tài khoản
                const deleteBtnHtml = vaiTro !== 'ADMIN' ? `
                    <button onclick="xacNhanXoaTaiKhoan(${tk.id}, '${usernameEsc}')" class="px-2.5 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa tài khoản người dùng">
                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                        <span>Xóa</span>
                    </button>
                ` : '';

                // Bắt buộc: Tài khoản ADMIN tuyệt đối KHÔNG thể bị khóa (không có nút khóa tài khoản)
                const lockBtnHtml = vaiTro !== 'ADMIN' ? `
                    <button onclick="doiTrangThaiTaiKhoan(${tk.id}, '${newStatus}')" class="px-2.5 py-1 border rounded-lg text-xs font-bold transition inline-flex items-center space-x-1 ${lockBtnClass}">
                        <span>${lockBtnText}</span>
                    </button>
                ` : (isLocked ? `
                    <button onclick="doiTrangThaiTaiKhoan(${tk.id}, 'HOAT_DONG')" class="px-2.5 py-1 border rounded-lg text-xs font-bold transition inline-flex items-center space-x-1 bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100" title="Mở khóa tài khoản Quản trị">
                        <span>🔓 Mở Khóa</span>
                    </button>
                ` : '');

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
                                <button onclick="moModalDoiMatKhauAdmin(${tk.id}, '${usernameEsc}', '${fullnameEsc}')" class="px-2.5 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Đổi / Đặt lại mật khẩu">
                                    <i class="fa-solid fa-key text-[10px]"></i>
                                    <span>Đổi MK</span>
                                </button>
                                ${lockBtnHtml}
                                ${deleteBtnHtml}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function xacNhanXoaTaiKhoan(id, username) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền xóa tài khoản.');
                return;
            }

            const confirm = await Swal.fire({
                title: 'Xóa Tài Khoản?',
                html: `Bạn có chắc chắn muốn xóa vĩnh viễn tài khoản <strong class="text-rose-600 font-mono">${username}</strong>?<br><span class="text-xs text-rose-500 font-bold">Lưu ý: Hành động này sẽ gỡ bỏ dữ liệu liên kết và không thể hoàn tác!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash-can mr-1"></i> Xác nhận xóa',
                cancelButtonText: 'Hủy bỏ'
            });

            if (confirm.isConfirmed) {
                const res = await goiApi('DELETE', `/api/v1/tai-khoan/${id}`);
                if (res.ok) {
                    showToast('success', 'Đã Xóa', `Đã xóa tài khoản "${username}".`);
                    await taiDanhSachTaiKhoan();
                    await taiDanhSachBacSi();
                } else {
                    showToast('error', 'Lỗi xóa tài khoản', res.data.thong_diep || 'Không thể xóa tài khoản này.');
                }
            }
        }

        // =============================================================
        // QUẢN TRỊ BÁC SĨ (SỬA & XÓA)
        // =============================================================
        function moModalSuaBacSi(id) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa bác sĩ.');
                return;
            }
            const bs = AppState.danhSachBacSi.find(b => b.id == id);
            if (!bs) {
                showToast('error', 'Lỗi', 'Không tìm thấy thông tin bác sĩ.');
                return;
            }

            document.getElementById('modal-sbs-id').value = bs.id;
            document.getElementById('modal-sbs-ho-ten').value = bs.ho_ten || '';
            document.getElementById('modal-sbs-hoc-vi').value = bs.hoc_vi || '';
            document.getElementById('modal-sbs-gia-kham').value = bs.gia_kham || 200000;
            document.getElementById('modal-sbs-phong').value = bs.phong_kham || '';
            document.getElementById('modal-sbs-sdt').value = bs.so_dien_thoai || '';
            document.getElementById('modal-sbs-kinh-nghiem').value = bs.kinh_nghiem || '';
            document.getElementById('modal-sbs-trang-thai').value = bs.trang_thai || 'DANG_LAM_VIEC';

            const select = document.getElementById('modal-sbs-chuyen-khoa');
            if (select) {
                select.innerHTML = AppState.danhSachChuyenKhoa.map(ck => `
                    <option value="${ck.id}" ${ck.id == bs.chuyen_khoa_id ? 'selected' : ''}>${ck.ten_khoa || ck.ten_chuyen_khoa}</option>
                `).join('');
            }

            moModal('modal-sua-bac-si');
        }

        async function xacNhanSuaBacSi() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa bác sĩ.');
                return;
            }
            const id = document.getElementById('modal-sbs-id').value;
            const hoTen = document.getElementById('modal-sbs-ho-ten').value.trim();
            const chuyenKhoaId = document.getElementById('modal-sbs-chuyen-khoa').value;
            const hocVi = document.getElementById('modal-sbs-hoc-vi').value.trim();
            const giaKham = document.getElementById('modal-sbs-gia-kham').value;
            const phong = document.getElementById('modal-sbs-phong').value.trim();
            const soDienThoai = document.getElementById('modal-sbs-sdt').value.trim();
            const kinhNghiem = document.getElementById('modal-sbs-kinh-nghiem').value.trim();
            const trangThai = document.getElementById('modal-sbs-trang-thai').value;

            if (!hoTen || !chuyenKhoaId) {
                showToast('error', 'Thiếu dữ liệu', 'Vui lòng nhập họ tên bác sĩ và chọn chuyên khoa.');
                return;
            }

            const payload = {
                ho_ten: hoTen,
                chuyen_khoa_id: parseInt(chuyenKhoaId),
                hoc_vi: hocVi,
                gia_kham: parseFloat(giaKham),
                phong_kham: phong,
                so_dien_thoai: soDienThoai,
                kinh_nghiem: kinhNghiem,
                trang_thai: trangThai
            };

            const res = await goiApi('PUT', `/api/v1/bac-si/${id}`, payload);
            if (res.ok) {
                dongModal('modal-sua-bac-si');
                showToast('success', 'Thành Công', `Đã cập nhật thông tin bác sĩ ${hoTen}!`);
                await taiDanhSachBacSi();
                await taiDanhSachTaiKhoan();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể cập nhật bác sĩ.');
            }
        }

        async function xacNhanXoaBacSi(id, hoTen) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền xóa bác sĩ.');
                return;
            }

            const confirm = await Swal.fire({
                title: 'Xóa Bác Sĩ?',
                html: `Bạn có chắc chắn muốn xóa hồ sơ bác sĩ <strong class="text-rose-600">${hoTen}</strong>?<br><span class="text-xs text-slate-500">Tài khoản đăng nhập của bác sĩ cũng sẽ được gỡ bỏ khỏi hệ thống.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash-can mr-1"></i> Đồng ý xóa',
                cancelButtonText: 'Hủy bỏ'
            });

            if (confirm.isConfirmed) {
                const res = await goiApi('DELETE', `/api/v1/bac-si/${id}`);
                if (res.ok) {
                    showToast('success', 'Đã Xóa', `Đã xóa thành công bác sĩ ${hoTen}.`);
                    await taiDanhSachBacSi();
                    await taiDanhSachTaiKhoan();
                } else {
                    showToast('error', 'Lỗi xóa', res.data.thong_diep || 'Không thể xóa bác sĩ này.');
                }
            }
        }

        // =============================================================
        // QUẢN TRỊ CHUYÊN KHOA (SỬA & XÓA)
        // =============================================================
        function moModalSuaChuyenKhoa(id) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa chuyên khoa.');
                return;
            }
            const ck = AppState.danhSachChuyenKhoa ? AppState.danhSachChuyenKhoa.find(c => c.id == id) : null;
            if (!ck) {
                showToast('error', 'Lỗi', 'Không tìm thấy thông tin chuyên khoa.');
                return;
            }

            const elId = document.getElementById('modal-sck-id');
            const elMa = document.getElementById('modal-sck-ma');
            const elTen = document.getElementById('modal-sck-ten');
            const elMoTa = document.getElementById('modal-sck-mo-ta');
            const elTrangThai = document.getElementById('modal-sck-trang-thai');

            if (elId) elId.value = ck.id;
            if (elMa) elMa.value = ck.ma_khoa || ck.ma_chuyen_khoa || '';
            if (elTen) elTen.value = ck.ten_khoa || ck.ten_chuyen_khoa || '';
            if (elMoTa) elMoTa.value = ck.mo_ta || '';
            if (elTrangThai) elTrangThai.value = ck.trang_thai || 'HOAT_DONG';

            moModal('modal-sua-chuyen-khoa');
        }

        async function xacNhanSuaChuyenKhoa() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa chuyên khoa.');
                return;
            }
            const id = document.getElementById('modal-sck-id')?.value;
            const ma = (document.getElementById('modal-sck-ma')?.value || '').trim();
            const ten = (document.getElementById('modal-sck-ten')?.value || '').trim();
            const moTa = (document.getElementById('modal-sck-mo-ta')?.value || '').trim();
            const trangThai = document.getElementById('modal-sck-trang-thai')?.value || 'HOAT_DONG';

            if (!id || !ma || !ten) {
                showToast('error', 'Thiếu dữ liệu', 'Vui lòng nhập đầy đủ mã và tên chuyên khoa.');
                return;
            }

            const payload = {
                ma_khoa: ma.toUpperCase(),
                ten_khoa: ten,
                mo_ta: moTa,
                trang_thai: trangThai
            };

            const res = await goiApi('PUT', `/api/v1/chuyen-khoa/${id}`, payload);
            if (res.ok) {
                dongModal('modal-sua-chuyen-khoa');
                showToast('success', 'Thành Công', `Đã cập nhật chuyên khoa ${ten}!`);
                await taiDanhSachChuyenKhoa();
                await taiDanhSachBacSi();
                if (typeof taiBangGiaKham === 'function') {
                    await taiBangGiaKham();
                }
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể cập nhật chuyên khoa.');
            }
        }

        async function xacNhanXoaChuyenKhoa(id, tenKhoa) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền xóa chuyên khoa.');
                return;
            }

            const confirm = await Swal.fire({
                title: 'Xóa Chuyên Khoa?',
                html: `Bạn có chắc muốn xóa chuyên khoa <strong class="text-rose-600">${tenKhoa}</strong>?<br><span class="text-xs text-slate-500">Lưu ý: Chỉ có thể xóa khi chuyên khoa này chưa được phân bổ bác sĩ nào.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash-can mr-1"></i> Đồng ý xóa',
                cancelButtonText: 'Hủy bỏ'
            });

            if (confirm.isConfirmed) {
                const res = await goiApi('DELETE', `/api/v1/chuyen-khoa/${id}`);
                if (res.ok) {
                    showToast('success', 'Đã Xóa', `Đã xóa chuyên khoa ${tenKhoa}.`);
                    await taiDanhSachChuyenKhoa();
                    if (typeof taiBangGiaKham === 'function') {
                        await taiBangGiaKham();
                    }
                } else {
                    showToast('error', 'Không thể xóa', res.data.thong_diep || 'Lỗi khi xóa chuyên khoa.');
                }
            }
        }

        // =============================================================
        // PHÂN HỆ: BẢNG GIÁ KHÁM BỆNH NIÊM YẾT (MEMBER 1)
        // =============================================================
        async function taiBangGiaKham() {
            try {
                const res = await goiApi('GET', '/api/v1/bac-si/bang-gia-kham');
                if (res.ok && res.data && res.data.du_lieu) {
                    AppState.bangGiaKham = res.data.du_lieu;
                    AppState.thongKeBangGia = res.data.thong_ke || null;

                    // Cập nhật các thẻ thống kê nhanh
                    if (AppState.thongKeBangGia) {
                        const elTong = document.getElementById('stat-bgk-tong-bs');
                        if (elTong) elTong.innerText = `${AppState.thongKeBangGia.tong_so_bac_si || 0} Bác sĩ`;
                        const elMin = document.getElementById('stat-bgk-min');
                        if (elMin) elMin.innerText = `${Number(AppState.thongKeBangGia.gia_thap_nhat || 0).toLocaleString('vi-VN')} đ`;
                        const elMax = document.getElementById('stat-bgk-max');
                        if (elMax) elMax.innerText = `${Number(AppState.thongKeBangGia.gia_cao_nhat || 0).toLocaleString('vi-VN')} đ`;
                        const elAvg = document.getElementById('stat-bgk-avg');
                        if (elAvg) elAvg.innerText = `${Number(AppState.thongKeBangGia.gia_trung_binh || 0).toLocaleString('vi-VN')} đ`;
                    }

                    // Cập nhật bộ lọc chuyên khoa
                    const filterCK = document.getElementById('filter-bgk-chuyen-khoa');
                    if (filterCK && AppState.danhSachChuyenKhoa) {
                        const curVal = filterCK.value;
                        filterCK.innerHTML = '<option value="">Tất cả chuyên khoa</option>' +
                            AppState.danhSachChuyenKhoa.map(ck => `<option value="${ck.id}">${ck.ten_khoa || ck.ten_chuyen_khoa}</option>`).join('');
                        filterCK.value = curVal;
                    }

                    renderBangGiaKhamTable(AppState.bangGiaKham);
                }
            } catch (e) {
                console.error('Lỗi tải bảng giá khám:', e);
            }
        }

        function locBangGiaKham() {
            if (!AppState.bangGiaKham) return;
            const tuKhoa = (document.getElementById('filter-bgk-tu-khoa')?.value || '').toLowerCase().trim();
            const chuyenKhoaId = document.getElementById('filter-bgk-chuyen-khoa')?.value || '';
            const phanKhuc = document.getElementById('filter-bgk-phan-khuc')?.value || '';
            const mucGia = document.getElementById('filter-bgk-muc-gia')?.value || '';

            const filtered = AppState.bangGiaKham.filter(item => {
                if (chuyenKhoaId && String(item.chuyen_khoa_id) !== String(chuyenKhoaId)) return false;
                if (phanKhuc && item.phan_khuc !== phanKhuc) return false;
                if (mucGia === 'DUOI_200' && item.gia_kham >= 200000) return false;
                if (mucGia === '200_300' && (item.gia_kham < 200000 || item.gia_kham > 300000)) return false;
                if (mucGia === 'TREN_300' && item.gia_kham <= 300000) return false;

                if (tuKhoa) {
                    const matchName = (item.ho_ten || '').toLowerCase().includes(tuKhoa);
                    const matchCode = (item.ma_bac_si || '').toLowerCase().includes(tuKhoa);
                    const matchKhoa = (item.ten_chuyen_khoa || '').toLowerCase().includes(tuKhoa);
                    const matchHocVi = (item.hoc_vi || '').toLowerCase().includes(tuKhoa);
                    const matchPhong = (item.phong_kham || '').toLowerCase().includes(tuKhoa);
                    if (!matchName && !matchCode && !matchKhoa && !matchHocVi && !matchPhong) return false;
                }
                return true;
            });

            renderBangGiaKhamTable(filtered);
        }

        function renderBangGiaKhamTable(danhSach) {
            const tbody = document.getElementById('tbody-bang-gia-kham');
            if (!tbody) return;

            if (!danhSach || danhSach.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-slate-400 font-medium">Không tìm thấy bác sĩ hoặc biểu phí phù hợp với bộ lọc.</td></tr>';
                return;
            }

            const isAdmin = AppState.currentUser && AppState.currentUser.vai_tro === 'ADMIN';

            tbody.innerHTML = danhSach.map((bs, index) => {
                const avatar = bs.avatar
                    ? `<img src="${bs.avatar}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-xs">`
                    : `<div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-xs"><i class="fa-solid fa-user-doctor"></i></div>`;

                let badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">${bs.phan_khuc}</span>`;
                if (bs.phan_khuc.includes('Giáo Sư') || bs.phan_khuc.includes('Đầu Ngành')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1 w-max"><i class="fa-solid fa-crown text-amber-500 text-[10px]"></i> ${bs.phan_khuc}</span>`;
                } else if (bs.phan_khuc.includes('Chuyên Gia') || bs.phan_khuc.includes('CKII')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200 flex items-center gap-1 w-max"><i class="fa-solid fa-star text-amber-400 text-[10px]"></i> ${bs.phan_khuc}</span>`;
                } else if (bs.phan_khuc.includes('VIP')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1 w-max"><i class="fa-solid fa-gem text-amber-600 text-[10px]"></i> ${bs.phan_khuc}</span>`;
                }

                const hoTenEsc = (bs.ho_ten || '').replace(/'/g, "\\'");

                let actionHtml = '';
                if (isAdmin) {
                    actionHtml = `
                        <div class="inline-flex items-center space-x-1.5">
                            <button onclick="moModalSuaGiaKham(${bs.bac_si_id})" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition flex items-center space-x-1 shadow-xs" title="Cập nhật biểu phí khám">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                <span>Sửa giá</span>
                            </button>
                            <button onclick="datLichTuBangGia(${bs.bac_si_id})" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1" title="Đặt lịch thử">
                                <i class="fa-regular fa-calendar-check text-[11px]"></i>
                                <span>Đặt lịch</span>
                            </button>
                        </div>
                    `;
                } else {
                    actionHtml = `
                        <button onclick="datLichTuBangGia(${bs.bac_si_id})" class="px-3 py-1.5 bg-medical-600 hover:bg-medical-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-sm shadow-medical-600/20" title="Đặt lịch khám ngay với bác sĩ này">
                            <i class="fa-regular fa-calendar-check text-[11px]"></i>
                            <span>Đặt Lịch Ngay</span>
                        </button>
                    `;
                }

                return `
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-3.5 text-center text-slate-400 font-mono text-[11px]">${index + 1}</td>
                        <td class="py-3 px-3.5">
                            <div class="flex items-center space-x-3">
                                ${avatar}
                                <div>
                                    <p class="font-extrabold text-slate-900 text-xs">${bs.ho_ten}</p>
                                    <span class="font-mono text-[10px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">${bs.ma_bac_si}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1.5 w-max">
                                <i class="fa-solid fa-stethoscope text-[11px]"></i>
                                <span>${bs.ten_chuyen_khoa}</span>
                            </span>
                        </td>
                        <td class="py-3 px-3.5">
                            <p class="font-semibold text-slate-800 text-xs">${bs.hoc_vi}</p>
                            <p class="text-[11px] text-slate-500">${bs.kinh_nghiem ? bs.kinh_nghiem : '--'}</p>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-semibold text-slate-700 text-xs bg-slate-100 px-2 py-1 rounded-lg border border-slate-200/80">
                                <i class="fa-solid fa-door-open text-slate-400 mr-1"></i>${bs.phong_kham || 'P201'}
                            </span>
                        </td>
                        <td class="py-3 px-3.5">${badgePhanKhuc}</td>
                        <td class="py-3 px-3.5 font-mono font-black text-emerald-600 text-sm">
                            ${bs.formatted_gia_kham}
                        </td>
                        <td class="py-3 px-3.5 text-center">
                            ${actionHtml}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function moModalSuaGiaKham(bacSiId) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa biểu phí khám bệnh.');
                return;
            }
            const bs = (AppState.danhSachBacSi ? AppState.danhSachBacSi.find(b => b.id == bacSiId) : null) ||
                       (AppState.bangGiaKham ? AppState.bangGiaKham.find(b => b.bac_si_id == bacSiId) : null);
            if (!bs) {
                showToast('error', 'Lỗi', 'Không tìm thấy thông tin bác sĩ.');
                return;
            }

            document.getElementById('modal-sgk-id').value = bs.id || bs.bac_si_id;
            document.getElementById('modal-sgk-ho-ten').innerText = bs.ho_ten;
            const tenKhoa = bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : (bs.ten_chuyen_khoa || 'Chuyên khoa');
            const hocVi = bs.hoc_vi || 'Bác sĩ';
            const phong = bs.phong_kham || 'P201';
            document.getElementById('modal-sgk-khoa-hocvi').innerText = `${hocVi} • Khoa ${tenKhoa} • ${phong}`;

            const giaHienTai = Number(bs.gia_kham || 0);
            document.getElementById('modal-sgk-gia-hien-tai').innerText = `${giaHienTai.toLocaleString('vi-VN')} VNĐ`;
            document.getElementById('modal-sgk-gia-moi').value = giaHienTai;

            moModal('modal-sua-gia-kham');
        }

        function datGiaKhamGoiY(soTien) {
            const input = document.getElementById('modal-sgk-gia-moi');
            if (input) input.value = soTien;
        }

        async function xacNhanSuaGiaKham() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền chỉnh sửa biểu phí khám bệnh.');
                return;
            }
            const id = document.getElementById('modal-sgk-id').value;
            const giaMoi = Number(document.getElementById('modal-sgk-gia-moi').value);

            if (isNaN(giaMoi) || giaMoi < 0) {
                showToast('error', 'Dữ liệu không hợp lệ', 'Vui lòng nhập đơn giá khám hợp lệ.');
                return;
            }

            const res = await goiApi('PUT', `/api/v1/bac-si/${id}/gia-kham`, { gia_kham: giaMoi });
            if (res.ok) {
                dongModal('modal-sua-gia-kham');
                showToast('success', 'Thành Công', res.data.thong_diep || `Đã cập nhật đơn giá khám thành công!`);
                await taiDanhSachBacSi();
                await taiBangGiaKham();
            } else {
                showToast('error', 'Lỗi cập nhật', res.data.thong_diep || 'Không thể cập nhật giá khám.');
            }
        }

        function datLichTuBangGia(bacSiId) {
            chuyenTab('tab-benh-nhan');
            setTimeout(() => {
                moModalDatLich(bacSiId);
            }, 150);
        }

        function inBangGiaKham() {
            window.print();
        }

        async function doiTrangThaiTaiKhoan(id, trangThaiMoi) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền khóa/mở khóa tài khoản.');
                return;
            }
            const tk = AppState.danhSachTaiKhoan ? AppState.danhSachTaiKhoan.find(t => t.id == id) : null;
            const vaiTro = tk && tk.vai_tro ? (tk.vai_tro.ma_vai_tro || tk.vai_tro.ten_vai_tro) : '';
            if (vaiTro === 'ADMIN' && trangThaiMoi === 'BI_KHOA') {
                showToast('warning', 'Không thể khóa', 'Tài khoản Quản trị viên (ADMIN) không được phép khóa.');
                return;
            }
            const res = await goiApi('PATCH', `/api/v1/tai-khoan/${id}/trang-thai`, { trang_thai: trangThaiMoi });
            if (res.ok) {
                const nhanTrangThai = trangThaiMoi === 'BI_KHOA' ? 'Đã khóa' : 'Đang hoạt động';
                showToast('success', 'Thành Công', `Đã cập nhật tài khoản #${id} sang trạng thái "${nhanTrangThai}".`);
                await taiDanhSachTaiKhoan();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể đổi trạng thái.');
            }
        }

        async function moModalDoiMatKhauAdmin(id, username, fullname) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền đặt lại mật khẩu.');
                return;
            }
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
            if (!AppState.currentUser || (AppState.currentUser.vai_tro !== 'ADMIN' && AppState.currentUser.vai_tro !== 'BAC_SI')) {
                showToast('error', 'Từ chối', 'Bạn không có quyền lập hóa đơn viện phí.');
                return;
            }
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
                AppState.hoaDonDangXem = hd;
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
                    }
                    );
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
        // PHÂN HỆ IN HÓA ĐƠN VIỆN PHÍ & PHIẾU THU KHÁM BỆNH (IN ẤN CHUYÊN NGHIỆP)
        // =============================================================
        function docSoThanhChu(so) {
            if (isNaN(so) || so === null || so === undefined) return '';
            so = Math.round(Math.abs(Number(so)));
            if (so === 0) return 'Không đồng chẵn';

            const chuSo = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
            const donVi = ['', 'nghìn', 'triệu', 'tỷ', 'nghìn tỷ', 'triệu tỷ'];

            function docBlock3(b, coHangTruoc) {
                let tram = Math.floor(b / 100);
                let chuc = Math.floor((b % 100) / 10);
                let donvi = b % 10;
                let str = '';

                if (tram > 0 || coHangTruoc) {
                    str += chuSo[tram] + ' trăm ';
                }

                if (chuc > 1) {
                    str += chuSo[chuc] + ' mươi ';
                    if (donvi === 1) str += 'mốt ';
                    else if (donvi === 5) str += 'lăm ';
                    else if (donvi > 0) str += chuSo[donvi] + ' ';
                } else if (chuc === 1) {
                    str += 'mười ';
                    if (donvi === 5) str += 'lăm ';
                    else if (donvi > 0) str += chuSo[donvi] + ' ';
                } else {
                    if (donvi > 0) {
                        if (tram > 0 || coHangTruoc) str += 'lẻ ' + chuSo[donvi] + ' ';
                        else str += chuSo[donvi] + ' ';
                    }
                }
                return str.trim();
            }

            let groups = [];
            let temp = so;
            while (temp > 0) {
                groups.push(temp % 1000);
                temp = Math.floor(temp / 1000);
            }

            let result = '';
            for (let i = groups.length - 1; i >= 0; i--) {
                let grp = groups[i];
                if (grp > 0) {
                    let doc = docBlock3(grp, i < groups.length - 1);
                    result += doc + ' ' + donVi[i] + ' ';
                }
            }

            result = result.trim();
            if (!result) return 'Không đồng chẵn';
            return result.charAt(0).toUpperCase() + result.slice(1) + ' đồng chẵn.';
        }

        async function inHoaDonKhamBenhCaHienTai() {
            if (!AppState.caKhamDangChon) {
                showToast('error', 'Chưa chọn ca khám', 'Vui lòng bấm "Tiếp Nhận" 1 ca khám từ danh sách chờ bên trái trước khi in hóa đơn.');
                return;
            }

            const caKham = AppState.caKhamDangChon;
            showToast('info', 'Đang tải hóa đơn...', 'Hệ thống đang chuẩn bị bản in hóa đơn viện phí...');

            let hd = null;

            // 1. Thử gọi API tạo/đồng bộ tự động từ Service 04
            try {
                const resTao = await goiApi('POST', '/api/v1/hoa-don/tao-tu-dong', {
                    lich_hen_id: Number(caKham.id),
                    giam_gia: 0,
                    ghi_chu: 'Hóa đơn khám bệnh'
                });
                if (resTao.ok && resTao.data && resTao.data.du_lieu) {
                    hd = resTao.data.du_lieu;
                }
            } catch (e) {
                console.warn('Lỗi gọi tao-tu-dong:', e);
            }

            // 2. Nếu chưa có, tìm trong danh sách hoặc gọi GET danh sách
            if (!hd) {
                if (AppState.danhSachHoaDon && AppState.danhSachHoaDon.length > 0) {
                    hd = AppState.danhSachHoaDon.find(h => h.lich_hen_id == caKham.id);
                }
            }

            if (!hd) {
                const resList = await goiApi('GET', '/api/v1/hoa-don');
                if (resList.ok && resList.data && resList.data.du_lieu) {
                    AppState.danhSachHoaDon = resList.data.du_lieu;
                    hd = AppState.danhSachHoaDon.find(h => h.lich_hen_id == caKham.id);
                }
            }

            // 3. Fallback: Nếu hệ thống chưa lưu hóa đơn, tổng hợp trực tiếp từ form khám hiện tại
            if (!hd) {
                const bsObj = (AppState.danhSachBacSi || []).find(b => b.id == caKham.bac_si_id) || AppState.currentUser?.bac_si || {};
                const giaKham = Number(bsObj.gia_kham || 200000);

                const checkedDichVu = Array.from(document.querySelectorAll('.cb-dich-vu-cls:checked')).map(c => {
                    const dvId = Number(c.value);
                    const dvObj = (AppState.danhSachDichVu || []).find(d => d.id == dvId) || {};
                    return {
                        ten_khoan_thu: dvObj.ten_dich_vu || 'Dịch vụ cận lâm sàng',
                        loai_khoan_thu: 'CAN_LAM_SANG',
                        so_luong: 1,
                        don_gia: Number(dvObj.don_gia || 0),
                        thanh_tien: Number(dvObj.don_gia || 0)
                    };
                });

                const tienCLS = checkedDichVu.reduce((s, it) => s + it.thanh_tien, 0);

                hd = {
                    ma_hoa_don: `HD-KB-${String(caKham.id).padStart(4, '0')}`,
                    lich_hen_id: caKham.id,
                    created_at: new Date().toISOString(),
                    trang_thai: 'CHUA_THANH_TOAN',
                    tien_kham: giaKham,
                    tien_dich_vu: tienCLS,
                    tong_tien: giaKham + tienCLS,
                    giam_gia: 0,
                    thuc_thu: giaKham + tienCLS,
                    phuong_thuc_thanh_toan: 'TIEN_MAT',
                    chi_tiet: [
                        {
                            ten_khoan_thu: `Công khám chuyên khoa (${bsObj.ho_ten || 'Bác sĩ phụ trách'})`,
                            loai_khoan_thu: 'TIEN_KHAM',
                            so_luong: 1,
                            don_gia: giaKham,
                            thanh_tien: giaKham
                        },
                        ...checkedDichVu
                    ]
                };
            }

            thucHienInHoaDon(hd, caKham);
        }

        function inHoaDonTuChiTietModal() {
            if (!AppState.hoaDonDangXem) {
                showToast('error', 'Lỗi', 'Không tìm thấy dữ liệu hóa đơn đang xem.');
                return;
            }
            thucHienInHoaDon(AppState.hoaDonDangXem, null);
        }

        function thucHienInHoaDon(hd, caKhamInput) {
            if (!hd) return;

            let caKham = caKhamInput;
            if (!caKham && hd.lich_hen_id) {
                caKham = (AppState.danhSachLichHen || []).find(l => l.id == hd.lich_hen_id);
            }
            if (!caKham && AppState.caKhamDangChon && AppState.caKhamDangChon.id == hd.lich_hen_id) {
                caKham = AppState.caKhamDangChon;
            }

            const bn = caKham?.benh_nhan || {};
            const tenBn = hd.ten_benh_nhan || bn.ho_ten || caKham?.ho_ten_benh_nhan || 'Lê Văn Cường';
            const sdtBn = bn.so_dien_thoai || caKham?.so_dien_thoai || '--';
            const diaChiBn = bn.dia_chi || 'TP. Hồ Chí Minh';
            const gioiTinh = bn.gioi_tinh === 'NU' ? 'Nữ' : (bn.gioi_tinh === 'NAM' ? 'Nam' : 'Khác');
            let dobStr = '--';
            if (bn.ngay_sinh) {
                const birthYear = new Date(bn.ngay_sinh).getFullYear();
                const age = new Date().getFullYear() - birthYear;
                dobStr = `${bn.ngay_sinh.split('-').reverse().join('/')} (${age} tuổi)`;
            }

            let tenBacSi = 'BS. CKII Nguyễn Anh Tuấn';
            let tenKhoa = 'Khoa Khám Bệnh Đa Khoa';
            if (caKham) {
                const bsObj = (AppState.danhSachBacSi || []).find(b => b.id == caKham.bac_si_id) || AppState.currentUser?.bac_si;
                if (bsObj) {
                    tenBacSi = bsObj.ho_ten || tenBacSi;
                    if (bsObj.chuyen_khoa) {
                        tenKhoa = bsObj.chuyen_khoa.ten_khoa || bsObj.chuyen_khoa.ten_chuyen_khoa || tenKhoa;
                    }
                }
            }
            const chanDoan = (document.getElementById('input-chan-doan')?.value || '').trim() || caKham?.ly_do_kham || 'Khám nội tổng quát & Cận lâm sàng';

            const items = hd.chi_tiet || hd.chi_tiet_hoa_don || [];
            let itemsRows = '';
            let stt = 1;

            if (items.length > 0) {
                items.forEach(it => {
                    const tenKhoan = it.ten_khoan_thu || it.ten_khoan_muc || 'Dịch vụ y tế';
                    const loai = (it.loai_khoan_thu === 'TIEN_KHAM' || it.loai_khoan_thu === 'KHAM_BENH') ? 'Khám chuyên khoa' : 'Cận lâm sàng';
                    const sl = Number(it.so_luong || 1);
                    const donGia = Number(it.don_gia || 0);
                    const thanhTien = Number(it.thanh_tien || (sl * donGia));
                    itemsRows += `
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px; text-align: center;">${stt++}</td>
                            <td style="padding: 8px; font-weight: 600;">${tenKhoan}</td>
                            <td style="padding: 8px; color: #64748b; font-size: 11px;">${loai}</td>
                            <td style="padding: 8px; text-align: center;">${sl}</td>
                            <td style="padding: 8px; text-align: right;">${donGia.toLocaleString('vi-VN')} đ</td>
                            <td style="padding: 8px; text-align: right; font-weight: 700;">${thanhTien.toLocaleString('vi-VN')} đ</td>
                        </tr>
                    `;
                });
            } else {
                const tk = Number(hd.tien_kham || 200000);
                itemsRows += `
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px; text-align: center;">1</td>
                        <td style="padding: 8px; font-weight: 600;">Công khám chuyên khoa (${tenBacSi})</td>
                        <td style="padding: 8px; color: #64748b; font-size: 11px;">Khám chuyên khoa</td>
                        <td style="padding: 8px; text-align: center;">1</td>
                        <td style="padding: 8px; text-align: right;">${tk.toLocaleString('vi-VN')} đ</td>
                        <td style="padding: 8px; text-align: right; font-weight: 700;">${tk.toLocaleString('vi-VN')} đ</td>
                    </tr>
                `;
                if (Number(hd.tien_dich_vu) > 0) {
                    const td = Number(hd.tien_dich_vu);
                    itemsRows += `
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 8px; text-align: center;">2</td>
                            <td style="padding: 8px; font-weight: 600;">Các dịch vụ xét nghiệm & Cận lâm sàng</td>
                            <td style="padding: 8px; color: #64748b; font-size: 11px;">Cận lâm sàng</td>
                            <td style="padding: 8px; text-align: center;">1</td>
                            <td style="padding: 8px; text-align: right;">${td.toLocaleString('vi-VN')} đ</td>
                            <td style="padding: 8px; text-align: right; font-weight: 700;">${td.toLocaleString('vi-VN')} đ</td>
                        </tr>
                    `;
                }
            }

            const tienKham = Number(hd.tien_kham || 0);
            const tienCLS = Number(hd.tien_dich_vu || 0);
            const tongTien = Number(hd.tong_tien || (tienKham + tienCLS));
            const giamGia = Number(hd.giam_gia || 0);
            const thucThu = Number(hd.thuc_thu || Math.max(0, tongTien - giamGia));
            const soTienChu = docSoThanhChu(thucThu);

            const isPaid = (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID');
            const statusBadge = isPaid 
                ? '<span style="display:inline-block; padding: 4px 12px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 9999px; font-weight: 800; font-size: 11px;">ĐÃ THANH TOÁN ĐỦ</span>'
                : '<span style="display:inline-block; padding: 4px 12px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; border-radius: 9999px; font-weight: 800; font-size: 11px;">CHỜ THU NGÂN THANH TOÁN</span>';

            const ngayIn = new Date().toLocaleString('vi-VN');
            const maHdStr = hd.ma_hoa_don || `HD-${String(hd.id || '8').padStart(6, '0')}`;

            const printHtml = `
                <!DOCTYPE html>
                <html lang="vi">
                <head>
                    <meta charset="UTF-8">
                    <title>Hóa Đơn Viện Phí #${maHdStr} - ${tenBn}</title>
                    <style>
                        * { box-sizing: border-box; margin: 0; padding: 0; }
                        body { font-family: "Segoe UI", Roboto, Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; padding: 25px; line-height: 1.45; }
                        .header-table { width: 100%; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 15px; }
                        .title-section { text-align: center; margin: 15px 0 20px 0; }
                        .title-section h1 { font-size: 18px; font-weight: 900; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; }
                        .title-section p { font-size: 11px; color: #64748b; font-style: italic; margin-top: 3px; }
                        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px; }
                        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
                        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                        .items-table th { background: #f1f5f9; padding: 8px; text-align: left; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; border-bottom: 2px solid #cbd5e1; }
                        .summary-box { float: right; width: 340px; margin-bottom: 20px; }
                        .summary-table { width: 100%; border-collapse: collapse; }
                        .summary-table td { padding: 4px 8px; }
                        .sign-section { clear: both; width: 100%; margin-top: 30px; display: table; }
                        .sign-col { display: table-cell; width: 33.33%; text-align: center; vertical-align: top; }
                        .sign-title { font-weight: 800; font-size: 12px; text-transform: uppercase; color: #334155; }
                        .sign-sub { font-size: 10px; color: #64748b; font-style: italic; margin-top: 2px; }
                        .sign-space { height: 75px; }
                        .sign-name { font-weight: 700; font-size: 12px; color: #0f172a; }
                        .footer-note { clear: both; margin-top: 40px; padding-top: 10px; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 10px; color: #64748b; }
                        @media print {
                            body { padding: 15px; }
                            .no-print { display: none !important; }
                        }
                    </style>
                </head>
                <body>
                    <table class="header-table">
                        <tr>
                            <td style="width: 60%; vertical-align: top;">
                                <div style="font-size: 15px; font-weight: 900; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px;">PHÒNG KHÁM ĐA KHOA QUỐC TẾ DV</div>
                                <div style="font-size: 11px; color: #475569; margin-top: 3px;">Địa chỉ: 123 Đường Y Học, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</div>
                                <div style="font-size: 11px; color: #475569;">Hotline cấp cứu: <strong>1900 8888</strong> | Bàn trực: (028) 3822 9999</div>
                                <div style="font-size: 11px; color: #475569;">Website: www.phongkhamdv.vn | Email: lienhe@phongkhamdv.vn</div>
                            </td>
                            <td style="width: 40%; text-align: right; vertical-align: top;">
                                <div style="font-size: 11px; font-weight: 700; color: #334155;">MÃ HÓA ĐƠN: <span style="font-family: monospace; font-size: 13px; color: #0284c7;">${maHdStr}</span></div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Mã ca khám: <strong>#${caKham?.id || '--'}</strong></div>
                                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">Ngày lập: ${ngayIn}</div>
                                <div style="margin-top: 6px;">${statusBadge}</div>
                            </td>
                        </tr>
                    </table>

                    <div class="title-section">
                        <h1>HÓA ĐƠN VIỆN PHÍ & PHIẾU THU KHÁM BỆNH</h1>
                        <p>(Bản thể hiện hóa đơn điện tử liên dịch vụ - Kèm phiếu chỉ định cận lâm sàng)</p>
                    </div>

                    <div class="info-box">
                        <div class="info-grid">
                            <div><strong>Họ và tên người bệnh:</strong> <span style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase;">${tenBn}</span></div>
                            <div><strong>Giới tính / Tuổi:</strong> ${gioiTinh} | ${dobStr}</div>
                            <div><strong>Số điện thoại:</strong> ${sdtBn}</div>
                            <div><strong>Địa chỉ:</strong> ${diaChiBn}</div>
                            <div><strong>Bác sĩ phụ trách:</strong> ${tenBacSi} (${tenKhoa})</div>
                            <div><strong>Phương thức thanh toán:</strong> ${hd.phuong_thuc_thanh_toan || 'TIEN_MAT'}</div>
                            <div style="grid-column: span 2;"><strong>Chẩn đoán lâm sàng:</strong> <em>${chanDoan}</em></div>
                        </div>
                    </div>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">STT</th>
                                <th style="width: 45%;">Hạng Mục Dịch Vụ / Khám Chữa Bệnh</th>
                                <th style="width: 20%;">Phân Loại</th>
                                <th style="width: 8%; text-align: center;">SL</th>
                                <th style="width: 11%; text-align: right;">Đơn Giá</th>
                                <th style="width: 11%; text-align: right;">Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsRows}
                        </tbody>
                    </table>

                    <div class="summary-box">
                        <table class="summary-table">
                            <tr>
                                <td style="color: #64748b;">Tiền công khám bác sĩ:</td>
                                <td style="text-align: right; font-weight: 600;">${tienKham.toLocaleString('vi-VN')} đ</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b;">Tổng chi phí cận lâm sàng:</td>
                                <td style="text-align: right; font-weight: 600;">${tienCLS.toLocaleString('vi-VN')} đ</td>
                            </tr>
                            ${giamGia > 0 ? `
                            <tr>
                                <td style="color: #059669;">Miễn giảm / Ưu đãi BHYT:</td>
                                <td style="text-align: right; font-weight: 600; color: #059669;">-${giamGia.toLocaleString('vi-VN')} đ</td>
                            </tr>
                            ` : ''}
                            <tr style="border-top: 1px solid #cbd5e1; border-bottom: 2px solid #0284c7;">
                                <td style="font-weight: 800; font-size: 13px; color: #0f172a; padding: 6px 8px;">TỔNG THỰC THU:</td>
                                <td style="text-align: right; font-weight: 900; font-size: 15px; color: #0284c7; padding: 6px 8px;">${thucThu.toLocaleString('vi-VN')} đ</td>
                            </tr>
                        </table>
                    </div>

                    <div style="clear: both; margin-top: 8px; font-size: 11px; font-style: italic;">
                        <strong>Số tiền viết bằng chữ:</strong> <span style="font-weight: 700; color: #0f172a;">${soTienChu}</span>
                    </div>

                    <div class="sign-section">
                        <div class="sign-col">
                            <div class="sign-title">Bệnh Nhân / Thân Nhân</div>
                            <div class="sign-sub">(Ký & ghi rõ họ tên)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">${tenBn}</div>
                        </div>
                        <div class="sign-col">
                            <div class="sign-title">Bác Sĩ Khám Bệnh</div>
                            <div class="sign-sub">(Ký, đóng dấu chức danh)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">${tenBacSi}</div>
                        </div>
                        <div class="sign-col">
                            <div class="sign-title">Người Thu Tiền / Thu Ngân</div>
                            <div class="sign-sub">(Ký & đóng dấu biên lai)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">Bộ Phận Thu Ngân DV</div>
                        </div>
                    </div>

                    <div class="footer-note">
                        <p>Cảm ơn Quý khách đã tin tưởng khám chữa bệnh tại Phòng Khám Đa Khoa DV.</p>
                        <p>Quý khách vui lòng lưu giữ hóa đơn để tái khám, lấy kết quả xét nghiệm hoặc thanh toán với công ty bảo hiểm.</p>
                        <p style="margin-top: 3px; font-family: monospace;">Mã tra cứu biên lai điện tử: <strong>PKDV-${hd.id || '8'}-${Date.now().toString().slice(-4)}</strong></p>
                    </div>
                </body>
                </html>
            `;

            const printWindow = window.open('', '', 'height=800,width=950');
            if (printWindow) {
                printWindow.document.write(printHtml);
                printWindow.document.close();
                setTimeout(() => {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                }, 500);
            } else {
                showToast('error', 'Trình duyệt chặn Pop-up', 'Vui lòng cho phép Pop-up để mở cửa sổ in hóa đơn.');
            }
        }

        // =============================================================
        // MODALS ADMIN THÊM BÁC SĨ & CHUYÊN KHOA
        // =============================================================
        function moModalThemBacSi() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền thêm hồ sơ bác sĩ.');
                return;
            }
            moModal('modal-them-bac-si');
        }

        async function xacNhanThemBacSi() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền thêm hồ sơ bác sĩ.');
                return;
            }
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
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền thêm chuyên khoa.');
                return;
            }
            moModal('modal-them-chuyen-khoa');
        }

        async function xacNhanThemChuyenKhoa() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền thêm chuyên khoa.');
                return;
            }
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
        // BIỂU ĐỒ THỐNG KÊ & GIÁM SÁT HỆ THỐNG (CHART.JS)
        // =============================================================
        window.ClinicChartInstances = {
            doanhThu: null,
            phuongThuc: null,
            chuyenKhoa: null,
            trangThai: null,
            traffic: null,
            currentRange: '7_NGAY'
        };

        function thayDoiKhungThoiGianBieuDo(mode, btn) {
            window.ClinicChartInstances.currentRange = mode;
            document.querySelectorAll('.btn-chart-filter').forEach(b => {
                b.classList.remove('bg-medical-600', 'text-white', 'shadow-sm');
                b.classList.add('text-slate-400', 'hover:text-white');
            });
            if (btn) {
                btn.classList.remove('text-slate-400', 'hover:text-white');
                btn.classList.add('bg-medical-600', 'text-white', 'shadow-sm');
            }
            khoiTaoBieuDoThongKe(mode);
        }

        async function lamMoiTatCaBieuDo() {
            const icon = document.getElementById('icon-refresh-chart');
            if (icon) icon.classList.add('fa-spin');

            try {
                await Promise.all([
                    taiDanhSachHoaDon(),
                    taiBaoCaoDoanhThu(),
                    taiDanhSachLichHen(),
                    taiDanhSachBacSi(),
                    taiDanhSachChuyenKhoa(),
                    kiemTraHealthToanHeThong()
                ]);
                khoiTaoBieuDoThongKe(window.ClinicChartInstances.currentRange);
                showToast('success', 'Thành công', 'Đã cập nhật toàn bộ số liệu và làm mới biểu đồ!');
            } catch (err) {
                console.error('Lỗi làm mới biểu đồ:', err);
            } finally {
                if (icon) {
                    setTimeout(() => icon.classList.remove('fa-spin'), 600);
                }
            }
        }

        function khoiTaoBieuDoThongKe(khungThoiGian = null) {
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js chưa sẵn sàng.');
                return;
            }

            const cDoanhThu = document.getElementById('chart-doanh-thu-lich-hen');
            if (!cDoanhThu) return;

            const mode = khungThoiGian || window.ClinicChartInstances.currentRange || '7_NGAY';

            // Hủy biểu đồ cũ nếu đã tồn tại để tránh xung đột canvas
            if (window.ClinicChartInstances.doanhThu) { window.ClinicChartInstances.doanhThu.destroy(); window.ClinicChartInstances.doanhThu = null; }
            if (window.ClinicChartInstances.phuongThuc) { window.ClinicChartInstances.phuongThuc.destroy(); window.ClinicChartInstances.phuongThuc = null; }
            if (window.ClinicChartInstances.chuyenKhoa) { window.ClinicChartInstances.chuyenKhoa.destroy(); window.ClinicChartInstances.chuyenKhoa = null; }
            if (window.ClinicChartInstances.trangThai) { window.ClinicChartInstances.trangThai.destroy(); window.ClinicChartInstances.trangThai = null; }
            if (window.ClinicChartInstances.traffic) { window.ClinicChartInstances.traffic.destroy(); window.ClinicChartInstances.traffic = null; }

            // 1. TẠO TRỤC THỜI GIAN & DỮ LIỆU DOANH THU + LƯỢT KHÁM
            const numDays = (mode === '30_NGAY') ? 30 : (mode === 'TAT_CA' ? 14 : 7);
            const labels = [];
            const revenueMap = {};
            const appointmentsMap = {};

            const today = new Date();
            for (let i = numDays - 1; i >= 0; i--) {
                const d = new Date(today);
                d.setDate(today.getDate() - i);
                const isoDate = d.toISOString().split('T')[0];
                const dayOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][d.getDay()];
                const dayLabel = `${dayOfWeek} (${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')})`;
                
                labels.push(dayLabel);
                revenueMap[isoDate] = 0;
                appointmentsMap[isoDate] = 0;
            }

            // Tổng hợp hóa đơn thực thu
            const hoaDons = AppState.danhSachHoaDon || [];
            let tongTienThucThu = 0;
            let soHdDaThu = 0;
            hoaDons.forEach(hd => {
                if (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID') {
                    soHdDaThu++;
                    const tien = Number(hd.thuc_thu || hd.tong_tien || 0);
                    tongTienThucThu += tien;
                    const dateStr = (hd.ngay_thanh_toan || hd.created_at || '').substring(0, 10);
                    if (revenueMap.hasOwnProperty(dateStr)) {
                        revenueMap[dateStr] += tien;
                    }
                }
            });

            // Tổng hợp lượt khám theo ngày
            const lichHens = AppState.danhSachLichHen || [];
            lichHens.forEach(lh => {
                const dateStr = (lh.ngay_kham || lh.created_at || '').substring(0, 10);
                if (appointmentsMap.hasOwnProperty(dateStr)) {
                    appointmentsMap[dateStr] += 1;
                }
            });

            // Dữ liệu cho chart 1
            const datesList = Object.keys(revenueMap);
            let revenueData = datesList.map(k => revenueMap[k]);
            let appointmentsData = datesList.map(k => appointmentsMap[k]);

            // Nếu trong những ngày vừa qua phòng khám có ít dữ liệu mẫu, bổ sung điểm nhấn mô phỏng chân thực
            const hasAnyRev = revenueData.some(v => v > 0);
            if (!hasAnyRev && hoaDons.length > 0) {
                hoaDons.forEach((hd, idx) => {
                    const targetIdx = Math.max(0, labels.length - 1 - (idx % labels.length));
                    const tien = Number(hd.thuc_thu || hd.tong_tien || 0);
                    revenueData[targetIdx] += tien;
                    appointmentsData[targetIdx] += 1;
                });
            } else if (!hasAnyRev) {
                revenueData = [450000, 750000, 600000, 920000, 1250000, 850000, 1100000].slice(-numDays);
                appointmentsData = [2, 3, 2, 4, 5, 3, 4].slice(-numDays);
            }

            // Cập nhật 3 mini KPIs bên dưới biểu đồ Doanh thu
            const sumFilteredRev = revenueData.reduce((a, b) => a + b, 0);
            const avgRev = Math.round(sumFilteredRev / labels.length);
            const maxKham = Math.max(...appointmentsData, 0);
            const tyLeThu = hoaDons.length > 0 ? Math.round((soHdDaThu / hoaDons.length) * 100) : 100;

            const elDtTb = document.getElementById('stat-chart-dt-tb');
            if (elDtTb) elDtTb.textContent = avgRev.toLocaleString('vi-VN') + ' đ';

            const elKhamMax = document.getElementById('stat-chart-kham-max');
            if (elKhamMax) elKhamMax.textContent = maxKham + ' lượt';

            const elTyLeThu = document.getElementById('stat-chart-ty-le-thu');
            if (elTyLeThu) elTyLeThu.textContent = tyLeThu + '%';

            // --- VẼ BIỂU ĐỒ 1: DOANH THU & TIẾP ĐÓN (LINE/AREA) ---
            const ctxDoanhThu = cDoanhThu.getContext('2d');
            const gradientBlue = ctxDoanhThu.createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(14, 165, 233, 0.35)');
            gradientBlue.addColorStop(1, 'rgba(14, 165, 233, 0.01)');

            window.ClinicChartInstances.doanhThu = new Chart(ctxDoanhThu, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doanh Thu Viện Phí (VNĐ)',
                            data: revenueData,
                            borderColor: '#0ea5e9',
                            backgroundColor: gradientBlue,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointBackgroundColor: '#0284c7',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Lượt Khám Tiếp Đón',
                            data: appointmentsData,
                            borderColor: '#a855f7',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.35,
                            pointBackgroundColor: '#9333ea',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                            padding: 10,
                            boxPadding: 4,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return ` Doanh thu: ${Number(context.raw).toLocaleString('vi-VN')} đ`;
                                    }
                                    return ` Lượt khám: ${context.raw} bệnh nhân`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', size: 10 },
                                color: '#0284c7',
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                    return value;
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', size: 10 },
                                color: '#9333ea',
                                stepSize: 1,
                                callback: value => value + ' ca'
                            }
                        }
                    }
                }
            });

            // --- VẼ BIỂU ĐỒ 2: CƠ CẤU PHƯƠNG THỨC THANH TOÁN (DOUGHNUT) ---
            const cPhuongThuc = document.getElementById('chart-phuong-thuc-thanh-toan');
            if (cPhuongThuc) {
                const methodStats = {
                    'VNPAY': { ten: 'VNPAY QR', tong: 0, count: 0, color: '#0284c7' },
                    'CHUYEN_KHOAN': { ten: 'Chuyển Khoản', tong: 0, count: 0, color: '#10b981' },
                    'TIEN_MAT': { ten: 'Tiền Mặt', tong: 0, count: 0, color: '#f59e0b' },
                    'MOMO': { ten: 'Ví MoMo', tong: 0, count: 0, color: '#ec4899' }
                };

                hoaDons.forEach(hd => {
                    const pt = hd.phuong_thuc_thanh_toan || 'TIEN_MAT';
                    const key = methodStats[pt] ? pt : 'TIEN_MAT';
                    const amount = Number(hd.thuc_thu || hd.tong_tien || 0);
                    methodStats[key].tong += amount;
                    methodStats[key].count += 1;
                });

                let ptLabels = [];
                let ptData = [];
                let ptColors = [];
                let ptTotalAmount = Object.values(methodStats).reduce((acc, curr) => acc + curr.tong, 0);

                if (ptTotalAmount === 0) {
                    ptLabels = ['VNPAY QR', 'Chuyển Khoản', 'Tiền Mặt', 'Ví MoMo'];
                    ptData = [3850000, 300000, 200000, 0];
                    ptColors = ['#0284c7', '#10b981', '#f59e0b', '#ec4899'];
                    ptTotalAmount = 4350000;
                } else {
                    Object.values(methodStats).forEach(item => {
                        ptLabels.push(item.ten);
                        ptData.push(item.tong);
                        ptColors.push(item.color);
                    });
                }

                window.ClinicChartInstances.phuongThuc = new Chart(cPhuongThuc.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ptLabels,
                        datasets: [{
                            data: ptData,
                            backgroundColor: ptColors,
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                                bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        const val = context.raw || 0;
                                        const pct = ptTotalAmount > 0 ? Math.round((val / ptTotalAmount) * 100) : 0;
                                        return ` ${context.label}: ${val.toLocaleString('vi-VN')} đ (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Render dynamic legend vào container
                const legendContainer = document.getElementById('legend-phuong-thuc-container');
                if (legendContainer) {
                    legendContainer.innerHTML = ptLabels.map((lbl, idx) => {
                        const val = ptData[idx] || 0;
                        const pct = ptTotalAmount > 0 ? Math.round((val / ptTotalAmount) * 100) : 0;
                        return `
                            <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0">
                                <span class="flex items-center gap-2 text-slate-600 font-semibold">
                                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background-color: ${ptColors[idx]}"></span>
                                    <span>${lbl}</span>
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-slate-800">${val.toLocaleString('vi-VN')} đ</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600">${pct}%</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            }

            // --- VẼ BIỂU ĐỒ 3: PHÂN BỔ NHU CẦU THEO CHUYÊN KHOA (BAR CHART) ---
            const cChuyenKhoa = document.getElementById('chart-chuyen-khoa');
            if (cChuyenKhoa) {
                const khoaCount = {};
                const ckList = AppState.danhSachChuyenKhoa || [];
                ckList.forEach(ck => {
                    const ten = ck.ten_khoa || ck.ten_chuyen_khoa || 'Khoa Nội';
                    khoaCount[ten] = 0;
                });

                if (Object.keys(khoaCount).length === 0) {
                    ['Khoa Nội Tổng Quát', 'Khoa Nhi', 'Răng Hàm Mặt', 'Khoa Mắt', 'Khoa Da Liễu'].forEach(k => khoaCount[k] = 0);
                }

                lichHens.forEach(lh => {
                    let tenKhoa = 'Khoa Nội Tổng Quát';
                    if (lh.chuyen_khoa) {
                        tenKhoa = lh.chuyen_khoa.ten_khoa || lh.chuyen_khoa.ten_chuyen_khoa || tenKhoa;
                    } else if (lh.bac_si_id) {
                        const bs = (AppState.danhSachBacSi || []).find(b => b.id == lh.bac_si_id);
                        if (bs && bs.chuyen_khoa) {
                            tenKhoa = bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa || tenKhoa;
                        }
                    }
                    khoaCount[tenKhoa] = (khoaCount[tenKhoa] || 0) + 1;
                });

                const totalKhoaCounts = Object.values(khoaCount).reduce((a, b) => a + b, 0);
                if (totalKhoaCounts === 0) {
                    khoaCount['Khoa Nội Tổng Quát'] = 4;
                    khoaCount['Khoa Nhi'] = 3;
                    khoaCount['Răng Hàm Mặt'] = 2;
                    khoaCount['Khoa Mắt'] = 1;
                }

                const ckLabels = Object.keys(khoaCount);
                const ckValues = Object.values(khoaCount);

                let topKhoa = ckLabels[0] || 'Khoa Nội Tổng Quát';
                let maxCount = -1;
                ckLabels.forEach((k, idx) => {
                    if (ckValues[idx] > maxCount) {
                        maxCount = ckValues[idx];
                        topKhoa = k;
                    }
                });
                const elTopKhoa = document.getElementById('badge-top-khoa');
                if (elTopKhoa) elTopKhoa.textContent = `${topKhoa} (${maxCount} ca)`;

                window.ClinicChartInstances.chuyenKhoa = new Chart(cChuyenKhoa.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ckLabels.map(l => l.replace('Khoa ', '')),
                        datasets: [{
                            label: 'Số Lượt Đặt Khám',
                            data: ckValues,
                            backgroundColor: [
                                '#0284c7',
                                '#0ea5e9',
                                '#06b6d4',
                                '#6366f1',
                                '#8b5cf6',
                                '#ec4899'
                            ],
                            borderRadius: 8,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                cornerRadius: 8,
                                callbacks: {
                                    label: ctx => ` Lượt khám: ${ctx.raw} ca bệnh`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 10 } },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            }
                        }
                    }
                });
            }

            // --- VẼ BIỂU ĐỒ 4: TRẠNG THÁI LỊCH KHÁM & TIẾN ĐỘ TIẾP NHẬN (DOUGHNUT) ---
            const cTrangThai = document.getElementById('chart-trang-thai-lich');
            if (cTrangThai) {
                const ttCounts = {
                    'DA_HOAN_THANH': 0,
                    'DANG_KHAM': 0,
                    'DA_XAC_NHAN': 0,
                    'CHO_XAC_NHAN': 0,
                    'DA_HUY': 0
                };

                lichHens.forEach(lh => {
                    const st = lh.trang_thai || 'CHO_XAC_NHAN';
                    if (ttCounts.hasOwnProperty(st)) {
                        ttCounts[st]++;
                    } else {
                        ttCounts['CHO_XAC_NHAN']++;
                    }
                });

                const totalAppointments = Object.values(ttCounts).reduce((a, b) => a + b, 0);
                if (totalAppointments === 0) {
                    ttCounts['DA_HOAN_THANH'] = 5;
                    ttCounts['DANG_KHAM'] = 1;
                    ttCounts['DA_XAC_NHAN'] = 2;
                    ttCounts['DA_HUY'] = 1;
                }

                const totalAfter = Object.values(ttCounts).reduce((a, b) => a + b, 0);
                const completeRate = totalAfter > 0 ? Math.round((ttCounts['DA_HOAN_THANH'] / totalAfter) * 100) : 0;
                const elTyLeHt = document.getElementById('badge-ty-le-hoan-thanh');
                if (elTyLeHt) elTyLeHt.textContent = `${completeRate}%`;

                window.ClinicChartInstances.trangThai = new Chart(cTrangThai.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Hoàn Thành', 'Đang Khám', 'Đã Xác Nhận', 'Chờ Duyệt', 'Đã Hủy'],
                        datasets: [{
                            data: [
                                ttCounts['DA_HOAN_THANH'],
                                ttCounts['DANG_KHAM'],
                                ttCounts['DA_XAC_NHAN'],
                                ttCounts['CHO_XAC_NHAN'],
                                ttCounts['DA_HUY']
                            ],
                            backgroundColor: [
                                '#10b981',
                                '#0ea5e9',
                                '#6366f1',
                                '#f59e0b',
                                '#f43f5e'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' }
                                }
                            },
                            tooltip: {
                                cornerRadius: 8,
                                callbacks: {
                                    label: ctx => ` ${ctx.label}: ${ctx.raw} ca`
                                }
                            }
                        }
                    }
                });
            }

            // --- VẼ BIỂU ĐỒ 5: PHÂN BỔ LƯU LƯỢNG 4 MICROSERVICES (BAR CHART) ---
            const cTraffic = document.getElementById('chart-traffic-microservices');
            if (cTraffic) {
                window.ClinicChartInstances.traffic = new Chart(cTraffic.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['01. Auth/Bác sĩ', '02. BN & Lịch', '03. Y Tế/CLS', '04. Viện Phí'],
                        datasets: [{
                            label: 'Tỷ lệ tải request Gateway (%)',
                            data: [22, 41, 18, 19],
                            backgroundColor: [
                                '#0d9488',
                                '#0284c7',
                                '#8b5cf6',
                                '#10b981'
                            ],
                            borderRadius: 6,
                            barPercentage: 0.55
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                cornerRadius: 8,
                                callbacks: {
                                    label: ctx => ` Tải điều phối: ${ctx.raw}% | Phản hồi: < 25ms`
                                }
                            }
                        },
                        scales: {
                            x: {
                                max: 100,
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, callback: v => v + '%' },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' } }
                            }
                        }
                    }
                });
            }
        }

        // =============================================================
        // HẠ TẦNG & HEALTH CHECK 4 SERVICES (ADMIN)
        // =============================================================
        async function kiemTraHealthToanHeThong() {
            const res = await goiApi('GET', '/api/v1/health');
            if (res.ok && res.data && res.data.danh_sach_dich_vu) {
                const list = res.data.danh_sach_dich_vu;
                capNhatBadgeService('badge-svc-1', list['auth-service']);
                capNhatBadgeService('badge-svc-2', list['appointment-service']);
                capNhatBadgeService('badge-svc-3', list['clinical-service']);
                capNhatBadgeService('badge-svc-4', list['billing-service']);

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

        // =============================================================
        // PHÂN HỆ: QUẢN LÝ HỒ SƠ CÁ NHÂN & UPLOAD AVATAR
        // =============================================================
        function moModalHoSoCaNhan() {
            if (!AppState.currentUser) return;
            const u = AppState.currentUser;
            const role = u.vai_tro || 'BENH_NHAN';

            document.getElementById('profile-username').value = u.ten_dang_nhap || '';
            document.getElementById('profile-role').value = u.ten_vai_tro || role;
            document.getElementById('profile-ho-ten').value = u.ho_ten || '';
            document.getElementById('profile-sdt').value = u.so_dien_thoai || '';
            document.getElementById('profile-email').value = u.email || '';
            document.getElementById('profile-ngay-sinh').value = u.ngay_sinh || '';
            document.getElementById('profile-gioi-tinh').value = u.gioi_tinh || '';
            document.getElementById('profile-dia-chi').value = u.dia_chi || '';

            document.getElementById('profile-modal-display-name').textContent = u.ho_ten || u.ten_dang_nhap;
            document.getElementById('profile-modal-display-role').textContent = u.ten_vai_tro || role;

            const previewImg = document.getElementById('profile-modal-avatar-preview');
            const placeholder = document.getElementById('profile-modal-avatar-placeholder');
            if (u.avatar) {
                previewImg.src = u.avatar;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                previewImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            const doctorExtra = document.getElementById('profile-doctor-extra-fields');
            if (role === 'BAC_SI') {
                doctorExtra.classList.remove('hidden');
                const bsInfo = u.bac_si || AppState.danhSachBacSi.find(b => b.tai_khoan_id == u.id || b.email == u.email);
                if (bsInfo) {
                    document.getElementById('profile-doctor-hoc-vi').value = bsInfo.hoc_vi || '';
                    document.getElementById('profile-doctor-phong').value = bsInfo.phong_kham || '';
                    document.getElementById('profile-doctor-kinh-nghiem').value = bsInfo.kinh_nghiem || '';
                }
            } else {
                doctorExtra.classList.add('hidden');
            }

            moModal('modal-ho-so-ca-nhan');
        }

        async function xuLyChonAvatar(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];

            if (!file.type.startsWith('image/')) {
                showToast('error', 'Lỗi định dạng', 'Vui lòng chọn tệp hình ảnh hợp lệ (PNG, JPG, JPEG, WEBP).');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                showToast('error', 'Tệp quá lớn', 'Kích thước ảnh đại diện không được vượt quá 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = async (e) => {
                const base64Data = e.target.result;
                const previewImg = document.getElementById('profile-modal-avatar-preview');
                const placeholder = document.getElementById('profile-modal-avatar-placeholder');
                previewImg.src = base64Data;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');

                // Tự động lưu avatar lên server
                const res = await goiApi('POST', '/api/v1/xac-thuc/avatar', { avatar: base64Data });
                if (res.ok) {
                    if (AppState.currentUser) {
                        AppState.currentUser.avatar = base64Data;
                        sessionStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                        localStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                    }
                    capNhatGiaoDienTheoVaiTro();
                    await taiDanhSachBacSi();
                    showToast('success', 'Thành công', 'Đã cập nhật ảnh đại diện mới!');
                } else {
                    showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể lưu ảnh đại diện.');
                }
            };
            reader.readAsDataURL(file);
        }

        async function xacNhanCapNhatHoSo() {
            const hoTen = document.getElementById('profile-ho-ten').value.trim();
            const sdt = document.getElementById('profile-sdt').value.trim();
            const email = document.getElementById('profile-email').value.trim();
            const ngaySinh = document.getElementById('profile-ngay-sinh').value;
            const gioiTinh = document.getElementById('profile-gioi-tinh').value;
            const diaChi = document.getElementById('profile-dia-chi').value.trim();

            if (!hoTen || !email) {
                showToast('error', 'Thiếu thông tin', 'Họ tên và Email là thông tin bắt buộc.');
                return;
            }

            const payload = {
                ho_ten: hoTen,
                so_dien_thoai: sdt,
                email: email,
                ngay_sinh: ngaySinh || null,
                gioi_tinh: gioiTinh || null,
                dia_chi: diaChi || null,
            };

            const role = AppState.currentUser ? AppState.currentUser.vai_tro : '';
            if (role === 'BAC_SI') {
                payload.hoc_vi = document.getElementById('profile-doctor-hoc-vi').value.trim();
                payload.phong_kham = document.getElementById('profile-doctor-phong').value.trim();
                payload.kinh_nghiem = document.getElementById('profile-doctor-kinh-nghiem').value.trim();
            }

            const res = await goiApi('PUT', '/api/v1/xac-thuc/ho-so', payload);
            if (res.ok && res.data) {
                if (AppState.currentUser) {
                    const currentRole = chuanHoaVaiTroCurrentUser();
                    Object.assign(AppState.currentUser, payload);
                    if (res.data.du_lieu) {
                        AppState.currentUser = Object.assign({}, AppState.currentUser, res.data.du_lieu);
                    }
                    if (AppState.currentUser.vai_tro && typeof AppState.currentUser.vai_tro === 'object') {
                        AppState.currentUser.vai_tro = AppState.currentUser.vai_tro.ma_vai_tro || currentRole;
                    } else if (!AppState.currentUser.vai_tro) {
                        AppState.currentUser.vai_tro = currentRole;
                    }
                    chuanHoaVaiTroCurrentUser();
                    sessionStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                    localStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                }

                capNhatGiaoDienTheoVaiTro();
                await taiDanhSachBacSi();

                Swal.fire({
                    icon: 'success',
                    title: 'Cập nhật thành công!',
                    text: 'Hồ sơ cá nhân của bạn đã được cập nhật thành công.',
                    confirmButtonColor: '#0284c7'
                });
                dongModal('modal-ho-so-ca-nhan');
            } else {
                showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể cập nhật hồ sơ.');
            }
        }

        // =============================================================
        // PHÂN HỆ: QUẢN LÝ LỊCH TRỰC / CA LÀM VIỆC BÁC SĨ
        // =============================================================
        const MAP_TEN_THU_JS = {
            2: 'Thứ Hai',
            3: 'Thứ Ba',
            4: 'Thứ Tư',
            5: 'Thứ Năm',
            6: 'Thứ Sáu',
            7: 'Thứ Bảy',
            8: 'Chủ Nhật'
        };

        const MAP_TEN_CA_JS = {
            'CA_SANG': 'Ca Sáng',
            'CA_CHIEU': 'Ca Chiều',
            'CA_TOI': 'Ca Tối',
            'CA_NGAY': 'Cả Ngày'
        };

        function tuDongDienGioCaTruc() {
            const ca = document.getElementById('modal-lt-ca').value;
            const gBd = document.getElementById('modal-lt-gio-bd');
            const gKt = document.getElementById('modal-lt-gio-kt');
            if (ca === 'CA_SANG') {
                gBd.value = '07:30';
                gKt.value = '11:30';
            } else if (ca === 'CA_CHIEU') {
                gBd.value = '13:30';
                gKt.value = '17:00';
            } else if (ca === 'CA_TOI') {
                gBd.value = '17:30';
                gKt.value = '20:30';
            } else if (ca === 'CA_NGAY') {
                gBd.value = '07:30';
                gKt.value = '17:00';
            }
        }

        async function moModalLichTrucBacSi(bacSiId, hoTen = '') {
            document.getElementById('modal-lt-bac-si-id').value = bacSiId;
            document.getElementById('modal-lt-bac-si-name').textContent = hoTen ? ('BS. ' + hoTen) : 'Bác sĩ';
            
            const bs = AppState.danhSachBacSi.find(b => b.id == bacSiId);
            if (bs) {
                document.getElementById('modal-lt-phong').value = bs.phong_kham || 'P201';
            }

            tuDongDienGioCaTruc();
            moModal('modal-lich-truc-bac-si');
            const scrollBox = document.querySelector('#modal-lich-truc-bac-si .overflow-y-auto');
            if (scrollBox) scrollBox.scrollTop = 0;
            await taiLichTrucBacSi(bacSiId);
        }

        async function moModalLichTrucBacSiHienTai() {
            if (!AppState.currentUser) return;
            let bsId = AppState.currentUser.bac_si ? AppState.currentUser.bac_si.id : null;
            let hoTen = AppState.currentUser.ho_ten || 'Tôi';

            if (!bsId) {
                const match = AppState.danhSachBacSi.find(b => b.tai_khoan_id == AppState.currentUser.id || b.email == AppState.currentUser.email);
                if (match) {
                    bsId = match.id;
                    hoTen = match.ho_ten;
                }
            }

            if (!bsId && AppState.danhSachBacSi.length > 0) {
                bsId = AppState.danhSachBacSi[0].id;
                hoTen = AppState.danhSachBacSi[0].ho_ten;
            }

            if (!bsId) {
                showToast('error', 'Chưa tìm thấy', 'Không tìm thấy hồ sơ bác sĩ liên kết.');
                return;
            }

            await moModalLichTrucBacSi(bsId, hoTen);
        }

        async function taiLichTrucBacSi(bacSiId) {
            const tbody = document.getElementById('tbody-modal-lich-truc');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang tải danh sách ca trực...</td></tr>';

            const res = await goiApi('GET', `/api/v1/bac-si/${bacSiId}/lich-truc`);
            if (res.ok && res.data && res.data.du_lieu) {
                const shifts = res.data.du_lieu.danh_sach_ca_truc || [];
                AppState.currentModalShifts = shifts;
                kiemTraCaCungNgayModal();

                if (shifts.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-6 text-slate-400">Chưa có ca trực nào được thiết lập. Hãy đăng ký ca trực ở form phía trên.</td></tr>';
                    return;
                }

                const isAdmin = Boolean(AppState.currentUser && AppState.currentUser.vai_tro === 'ADMIN');

                tbody.innerHTML = shifts.map(s => {
                    const tenThu = MAP_TEN_THU_JS[s.ngay_trong_tuan] || ('Thứ ' + s.ngay_trong_tuan);
                    const tenCa = MAP_TEN_CA_JS[s.ca_truc] || s.ca_truc;
                    let caBadge = 'bg-sky-50 text-sky-700 border-sky-300';
                    if (s.ca_truc === 'CA_CHIEU') caBadge = 'bg-amber-50 text-amber-700 border-amber-300';
                    if (s.ca_truc === 'CA_TOI') caBadge = 'bg-purple-50 text-purple-700 border-purple-300';
                    if (s.ca_truc === 'CA_NGAY') caBadge = 'bg-emerald-50 text-emerald-700 border-emerald-300';

                    let trangThaiHtml = '';
                    const tt = s.trang_thai || 'CHO_DUYET';
                    if (tt === 'HOAT_DONG') {
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-emerald-50 text-emerald-800 border-emerald-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-600"></i> Đã Duyệt (Hoạt Động)</span>';
                    } else if (tt === 'CHO_DUYET') {
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-amber-50 text-amber-800 border-amber-300 inline-flex items-center gap-1.5 animate-pulse"><i class="fa-solid fa-clock text-amber-500"></i> Chờ Admin Duyệt</span>';
                    } else if (tt === 'TU_CHOI') {
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-rose-50 text-rose-800 border-rose-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-xmark text-rose-600"></i> Bị Từ Chối</span>';
                    } else {
                        trangThaiHtml = `<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold border bg-slate-50 text-slate-600 border-slate-200">${tt}</span>`;
                    }

                    const sJson = JSON.stringify(s).replace(/"/g, '&quot;');

                    let adminBtns = '';
                    if (isAdmin) {
                        if (tt === 'CHO_DUYET' || tt === 'TU_CHOI') {
                            adminBtns += `
                                <button type="button" onclick="duyetCaTruc(${s.id}, ${bacSiId})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-extrabold transition shadow-xs flex items-center gap-1" title="Duyệt kích hoạt ca trực này">
                                    <i class="fa-solid fa-check"></i> Duyệt
                                </button>
                            `;
                        }
                        if (tt === 'CHO_DUYET' || tt === 'HOAT_DONG') {
                            adminBtns += `
                                <button type="button" onclick="tuChoiCaTruc(${s.id}, ${bacSiId})" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[10px] font-extrabold transition shadow-xs flex items-center gap-1" title="Từ chối ca trực này">
                                    <i class="fa-solid fa-ban"></i> Từ chối
                                </button>
                            `;
                        }
                    }

                    return `
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-3 font-bold text-slate-800">${tenThu}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border ${caBadge}">${tenCa}</span>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-semibold text-slate-700">${s.gio_bat_dau} - ${s.gio_ket_thuc}</td>
                            <td class="py-2.5 px-3 text-slate-600">${s.phong_kham || 'P201'}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-600">${s.so_luong_kham_toi_da || 20} BN</td>
                            <td class="py-2.5 px-3">${trangThaiHtml}</td>
                            <td class="py-2.5 px-3 text-center">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    ${adminBtns}
                                    <button type="button" onclick="chuanBiSuaCaTruc(${sJson})" class="p-1.5 text-sky-600 hover:text-sky-800 hover:bg-sky-50 rounded-lg transition" title="Đổi lịch / Chỉnh sửa ca trực này">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" onclick="xoaCaTruc(${s.id}, ${bacSiId})" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Xóa bỏ ca trực này">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-rose-500">Lỗi tải danh sách ca trực.</td></tr>';
            }
        }

        function kiemTraCaCungNgayModal() {
            const thu = parseInt(document.getElementById('modal-lt-thu')?.value || '2');
            const alertBox = document.getElementById('modal-lt-cung-ngay-alert');
            const lblSubmit = document.getElementById('lbl-submit-ca-truc');
            const caId = document.getElementById('modal-lt-ca-id')?.value;
            if (!alertBox) return;

            const shifts = AppState.currentModalShifts || [];
            const caHienTai = shifts.find(s => s.ngay_trong_tuan === thu);
            const isAdmin = Boolean(AppState.currentUser && AppState.currentUser.vai_tro === 'ADMIN');
            const tenThu = MAP_TEN_THU_JS[thu] || ('Thứ ' + thu);

            if (caHienTai && (!caId || caHienTai.id != caId)) {
                const tenCaCu = MAP_TEN_CA_JS[caHienTai.ca_truc] || caHienTai.ca_truc;
                let ttBadge = caHienTai.trang_thai === 'HOAT_DONG' 
                    ? '<span class="text-emerald-700 font-bold">Đã Duyệt</span>' 
                    : (caHienTai.trang_thai === 'CHO_DUYET' ? '<span class="text-amber-700 font-bold">Chờ Admin Duyệt</span>' : '<span class="text-rose-700 font-bold">Bị Từ Chối</span>');

                alertBox.className = 'p-3 rounded-xl border border-amber-300 bg-amber-50 text-amber-900 text-[11px] block animate-fadeIn';
                alertBox.innerHTML = `
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-amber-600 mt-0.5 text-xs"></i>
                        <div>
                            <span class="font-extrabold text-amber-800">${tenThu} hiện đã có:</span> <strong>${tenCaCu}</strong> (${caHienTai.gio_bat_dau} - ${caHienTai.gio_ket_thuc} | Trạng thái: ${ttBadge}).
                            <div class="text-[10px] text-amber-700 mt-1">Mỗi ngày chỉ trực tối đa 1 ca. Khi bấm lưu, hệ thống sẽ tự động thực hiện <strong>ĐỔI CA TRỰC</strong> sang ca bạn đang chọn ${isAdmin ? 'ngay lập tức' : 'và chuyển sang trạng thái <strong>Chờ Admin Phê Duyệt</strong>'}.</div>
                        </div>
                    </div>
                `;
                if (lblSubmit && !caId) {
                    lblSubmit.textContent = isAdmin ? 'Đổi Ca Trực Ngay' : 'Gửi Yêu Cầu Đổi Ca Trực (Chờ Admin Duyệt)';
                }
            } else {
                alertBox.className = 'p-2.5 rounded-xl border border-sky-200 bg-sky-50 text-sky-800 text-[11px] block';
                alertBox.innerHTML = `
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-sky-600"></i>
                        <span>${tenThu} chưa có ca trực nào. ${isAdmin ? 'Đang tạo ca trực mới.' : 'Đăng ký ca mới sẽ gửi tới <strong>Admin phê duyệt</strong> trước khi kích hoạt.'}</span>
                    </div>
                `;
                if (lblSubmit && !caId) {
                    lblSubmit.textContent = isAdmin ? 'Lưu Ca Trực' : 'Gửi Yêu Cầu Đăng Ký Ca Trực';
                }
            }
        }

        async function duyetCaTruc(caId, bacSiId) {
            const res = await goiApi('PUT', `/api/v1/bac-si/lich-truc/${caId}/duyet`);
            if (res.ok) {
                showToast('success', 'Đã Phê Duyệt', res.data?.thong_diep || 'Ca trực đã được phê duyệt và kích hoạt thành công.');
                if (bacSiId) await taiLichTrucBacSi(bacSiId);
                await taiDanhSachBacSi();
                await taiVaRenderDanhSachDuyetCaTruc();
            } else {
                showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể phê duyệt ca trực.');
            }
        }

        async function tuChoiCaTruc(caId, bacSiId) {
            const confirm = await Swal.fire({
                title: 'Từ chối ca trực?',
                text: 'Bạn có chắc chắn muốn từ chối ca trực này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Đồng ý từ chối',
                cancelButtonText: 'Hủy'
            });

            if (!confirm.isConfirmed) return;

            const res = await goiApi('PUT', `/api/v1/bac-si/lich-truc/${caId}/tu-choi`);
            if (res.ok) {
                showToast('info', 'Đã Từ Chối', res.data?.thong_diep || 'Đã chuyển ca trực sang trạng thái từ chối.');
                if (bacSiId) await taiLichTrucBacSi(bacSiId);
                await taiDanhSachBacSi();
                await taiVaRenderDanhSachDuyetCaTruc();
            } else {
                showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể từ chối ca trực.');
            }
        }

        function chuanBiSuaCaTruc(s) {
            document.getElementById('modal-lt-ca-id').value = s.id;
            document.getElementById('modal-lt-thu').value = s.ngay_trong_tuan;
            document.getElementById('modal-lt-ca').value = s.ca_truc;
            document.getElementById('modal-lt-gio-bd').value = (s.gio_bat_dau || '').substring(0, 5);
            document.getElementById('modal-lt-gio-kt').value = (s.gio_ket_thuc || '').substring(0, 5);
            document.getElementById('modal-lt-phong').value = s.phong_kham || 'P201';
            document.getElementById('modal-lt-so-luong').value = s.so_luong_kham_toi_da || 20;

            const isAdmin = Boolean(AppState.currentUser && AppState.currentUser.vai_tro === 'ADMIN');
            const titleEl = document.getElementById('modal-lt-form-title');
            if (titleEl) {
                const tenThu = MAP_TEN_THU_JS[s.ngay_trong_tuan] || ('Thứ ' + s.ngay_trong_tuan);
                titleEl.innerHTML = `<i class="fa-solid fa-pen-to-square text-amber-500"></i> Đang Đổi Lịch / Chỉnh Sửa Ca Trực: <strong class="text-sky-700">${tenThu}</strong>`;
            }
            const lblSubmit = document.getElementById('lbl-submit-ca-truc');
            if (lblSubmit) lblSubmit.textContent = isAdmin ? 'Lưu Thay Đổi (Cập Nhật)' : 'Gửi Yêu Cầu Đổi Ca (Chờ Admin Duyệt)';
            const btnHuy = document.getElementById('btn-huy-sua-ca-truc');
            if (btnHuy) btnHuy.classList.remove('hidden');

            kiemTraCaCungNgayModal();

            // Cuộn mượt lên đầu form
            const scrollBox = document.querySelector('#modal-lich-truc-bac-si .overflow-y-auto');
            if (scrollBox) scrollBox.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function datLaiFormCaTruc() {
            const caIdInput = document.getElementById('modal-lt-ca-id');
            if (caIdInput) caIdInput.value = '';

            const titleEl = document.getElementById('modal-lt-form-title');
            if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-plus-circle text-sky-600"></i> Đăng Ký / Cập Nhật Ca Trực';
            const lblSubmit = document.getElementById('lbl-submit-ca-truc');
            if (lblSubmit) lblSubmit.textContent = 'Lưu Ca Trực';
            const btnHuy = document.getElementById('btn-huy-sua-ca-truc');
            if (btnHuy) btnHuy.classList.add('hidden');

            document.getElementById('modal-lt-thu').value = '2';
            document.getElementById('modal-lt-ca').value = 'CA_SANG';
            tuDongDienGioCaTruc();
            kiemTraCaCungNgayModal();
        }

        async function xacNhanLuuCaTruc() {
            const bacSiId = document.getElementById('modal-lt-bac-si-id').value;
            const caId = document.getElementById('modal-lt-ca-id')?.value;
            if (!bacSiId) return;

            const payload = {
                ngay_trong_tuan: parseInt(document.getElementById('modal-lt-thu').value),
                ca_truc: document.getElementById('modal-lt-ca').value,
                gio_bat_dau: document.getElementById('modal-lt-gio-bd').value,
                gio_ket_thuc: document.getElementById('modal-lt-gio-kt').value,
                phong_kham: document.getElementById('modal-lt-phong').value.trim() || 'P201',
                so_luong_kham_toi_da: parseInt(document.getElementById('modal-lt-so-luong').value) || 20,
            };

            let res;
            if (caId) {
                // Đang cập nhật ca trực đã có
                res = await goiApi('PUT', `/api/v1/bac-si/lich-truc/${caId}`, payload);
            } else {
                // Thêm ca trực mới / Đổi ca trực
                res = await goiApi('POST', `/api/v1/bac-si/${bacSiId}/lich-truc`, payload);
            }

            if (res.ok) {
                showToast('success', 'Thành công', res.data?.thong_diep || (caId ? 'Đã gửi yêu cầu đổi ca trực thành công.' : 'Đã gửi yêu cầu đăng ký ca trực thành công.'));
                datLaiFormCaTruc();
                await taiLichTrucBacSi(bacSiId);
                await taiDanhSachBacSi(); // Làm mới lại bộ nhớ và danh sách hiển thị toàn hệ thống
                await taiVaRenderDanhSachDuyetCaTruc();
            } else {
                showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể lưu ca trực.');
            }
        }

        async function xoaCaTruc(caId, bacSiId) {
            const confirm = await Swal.fire({
                title: 'Xác nhận xóa ca trực?',
                text: 'Bạn có chắc chắn muốn xóa ca trực này khỏi lịch làm việc của bác sĩ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Đồng ý xóa',
                cancelButtonText: 'Hủy'
            });

            if (!confirm.isConfirmed) return;

            const res = await goiApi('DELETE', `/api/v1/bac-si/lich-truc/${caId}`);
            if (res.ok) {
                showToast('success', 'Đã xóa', 'Ca trực đã được xóa thành công.');
                if (bacSiId) await taiLichTrucBacSi(bacSiId);
                await taiDanhSachBacSi(); // Đồng bộ lại thẻ bác sĩ
                await taiVaRenderDanhSachDuyetCaTruc();
            } else {
                showToast('error', 'Lỗi', res.data?.thong_diep || 'Không thể xóa ca trực.');
            }
        }

        // =============================================================
        // PHÂN HỆ: QUẢN TRỊ VIÊN DUYỆT LỊCH TRỰC BÁC SĨ (1-CLICK APPROVAL)
        // =============================================================
        async function taiVaRenderDanhSachDuyetCaTruc() {
            const tbody = document.getElementById('tbody-admin-duyet-lich-truc');
            const badgeCount = document.getElementById('badge-admin-count-cho-duyet');
            if (!tbody) return;

            const filterStatus = document.getElementById('filter-admin-duyet-trang-thai')?.value ?? 'CHO_DUYET';
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-6 text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2 text-medical-600"></i>Đang tải danh sách ca trực...</td></tr>';

            let url = '/api/v1/lich-truc';
            if (filterStatus) {
                url += `?trang_thai=${filterStatus}`;
            }

            const res = await goiApi('GET', url);
            if (res.ok && res.data && res.data.du_lieu) {
                const list = res.data.du_lieu || [];
                const choDuyetCount = list.filter(c => c.trang_thai === 'CHO_DUYET').length;
                if (badgeCount) {
                    badgeCount.textContent = `${choDuyetCount} chờ duyệt`;
                    badgeCount.className = choDuyetCount > 0 
                        ? 'px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white shadow-xs animate-bounce' 
                        : 'px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-400 text-white shadow-xs';
                }

                if (list.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-clipboard-check text-2xl text-emerald-500"></i>
                                    <span class="text-xs font-bold text-slate-600">Không có ca trực nào ở trạng thái này.</span>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = list.map(c => {
                    const bs = c.bac_si || {};
                    const tenThu = MAP_TEN_THU_JS[c.ngay_trong_tuan] || ('Thứ ' + c.ngay_trong_tuan);
                    const tenCa = MAP_TEN_CA_JS[c.ca_truc] || c.ca_truc;

                    let caBadge = 'bg-sky-50 text-sky-700 border-sky-300';
                    if (c.ca_truc === 'CA_CHIEU') caBadge = 'bg-amber-50 text-amber-700 border-amber-300';
                    if (c.ca_truc === 'CA_TOI') caBadge = 'bg-purple-50 text-purple-700 border-purple-300';
                    if (c.ca_truc === 'CA_NGAY') caBadge = 'bg-emerald-50 text-emerald-700 border-emerald-300';

                    const tt = c.trang_thai || 'CHO_DUYET';
                    let ttBadge = '';
                    if (tt === 'HOAT_DONG') {
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-emerald-50 text-emerald-800 border-emerald-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-600"></i> Đã Duyệt (Hoạt Động)</span>';
                    } else if (tt === 'CHO_DUYET') {
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-amber-50 text-amber-800 border-amber-300 inline-flex items-center gap-1.5 animate-pulse"><i class="fa-solid fa-clock text-amber-500"></i> Chờ Phê Duyệt</span>';
                    } else if (tt === 'TU_CHOI') {
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border bg-rose-50 text-rose-800 border-rose-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-xmark text-rose-600"></i> Bị Từ Chối</span>';
                    }

                    let actionBtns = '';
                    if (tt === 'CHO_DUYET' || tt === 'TU_CHOI') {
                        actionBtns += `
                            <button type="button" onclick="adminDuyetNhanhCaTruc(${c.id}, ${c.bac_si_id})" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs flex items-center gap-1" title="Duyệt ca trực này">
                                <i class="fa-solid fa-check"></i> Duyệt
                            </button>
                        `;
                    }
                    if (tt === 'CHO_DUYET' || tt === 'HOAT_DONG') {
                        actionBtns += `
                            <button type="button" onclick="adminTuChoiNhanhCaTruc(${c.id}, ${c.bac_si_id})" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition shadow-xs flex items-center gap-1" title="Từ chối ca trực này">
                                <i class="fa-solid fa-ban"></i> Từ chối
                            </button>
                        `;
                    }

                    return `
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-3">
                                <div class="font-extrabold text-slate-900">${bs.ho_ten || ('Bác sĩ #' + c.bac_si_id)}</div>
                                <div class="text-[10px] text-slate-500">${bs.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                            </td>
                            <td class="py-2.5 px-3 font-bold text-slate-800">${tenThu}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border ${caBadge}">${tenCa}</span>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-semibold text-slate-700">${c.gio_bat_dau} - ${c.gio_ket_thuc}</td>
                            <td class="py-2.5 px-3 text-slate-600 font-medium">${c.phong_kham || bs.phong_kham || 'P201'}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-600">${c.so_luong_kham_toi_da || 20} BN</td>
                            <td class="py-2.5 px-3">${ttBadge}</td>
                            <td class="py-2.5 px-3 text-center">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    ${actionBtns}
                                    <button type="button" onclick="xoaCaTruc(${c.id}, ${c.bac_si_id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa bỏ ca">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-6 text-rose-500">Lỗi khi tải dữ liệu ca trực.</td></tr>';
            }
        }

        async function adminDuyetNhanhCaTruc(caId, bacSiId) {
            await duyetCaTruc(caId, bacSiId);
        }

        async function adminTuChoiNhanhCaTruc(caId, bacSiId) {
            await tuChoiCaTruc(caId, bacSiId);
        }

        function xemNhanhLichTruc(bacSiId, hoTen) {
            moModalLichTrucBacSi(bacSiId, hoTen);
        }

        // =============================================================
        // LIÊN KẾT CA TRỰC BÁC SĨ VÀO PHÂN HỆ ĐẶT LỊCH HẸN
        // =============================================================
        async function kiemTraCaTrucDatLich() {
            const bacSiSelect = document.getElementById('modal-dl-bac-si');
            const ngayInput = document.getElementById('modal-dl-ngay');
            const box = document.getElementById('modal-dl-ca-truc-box');
            if (!bacSiSelect || !ngayInput || !box) return;

            const bacSiId = bacSiSelect.value;
            const ngayKham = ngayInput.value;

            if (!bacSiId || !ngayKham) {
                box.classList.add('hidden');
                return;
            }

            box.classList.remove('hidden');
            box.className = 'p-3.5 rounded-2xl border text-xs transition-all bg-slate-50 border-slate-200 text-slate-600';
            box.innerHTML = '<div class="flex items-center space-x-2"><i class="fa-solid fa-spinner fa-spin text-medical-600"></i><span>Đang kiểm tra ca trực bác sĩ...</span></div>';

            const res = await goiApi('GET', `/api/v1/bac-si/${bacSiId}/kiem-tra-truc?ngay=${ngayKham}`);
            if (res.ok && res.data && res.data.du_lieu) {
                const d = res.data.du_lieu;
                if (d.co_truc && d.ca_truc_trong_ngay && d.ca_truc_trong_ngay.length > 0) {
                    const shiftsHtml = d.ca_truc_trong_ngay.map(c => {
                        const tenCa = MAP_TEN_CA_JS[c.ca_truc] || c.ca_truc;
                        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100/70 border border-emerald-300 text-emerald-900 font-bold"><i class="fa-solid fa-clock text-emerald-600"></i> ${tenCa}: ${c.gio_bat_dau} - ${c.gio_ket_thuc} (${c.phong_kham || 'P201'})</span>`;
                    }).join(' ');

                    box.className = 'p-3.5 rounded-2xl border text-xs transition-all bg-emerald-50 border-emerald-200 text-emerald-900';
                    box.innerHTML = `
                        <div class="space-y-1.5">
                            <div class="font-extrabold flex items-center space-x-1.5 text-emerald-800">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                <span>Bác sĩ có lịch trực vào ${d.thu} (${ngayKham}):</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5 pt-0.5">
                                ${shiftsHtml}
                            </div>
                        </div>
                    `;

                    // Lọc và làm sáng các slot phù hợp trong ngày
                    capNhatTrangThaiKhungGioTheoCaTruc(d.ca_truc_trong_ngay);
                } else {
                    box.className = 'p-3.5 rounded-2xl border text-xs transition-all bg-amber-50 border-amber-200 text-amber-900';
                    box.innerHTML = `
                        <div class="space-y-1">
                            <div class="font-extrabold flex items-center space-x-1.5 text-amber-800">
                                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                                <span>Chú ý: Bác sĩ không có ca trực cố định vào ${d.thu || 'ngày này'}!</span>
                            </div>
                            <p class="text-amber-700 text-[11px]">
                                Quý khách vui lòng chọn các ngày trong tuần (T2 - T7) để được bác sĩ khám đúng ca trực.
                            </p>
                        </div>
                    `;
                }
            } else {
                box.classList.add('hidden');
            }
        }

        function capNhatTrangThaiKhungGioTheoCaTruc(shifts) {
            const slotBtns = document.querySelectorAll('#modal-dl-slots .slot-btn');
            if (!slotBtns || slotBtns.length === 0) return;

            let firstValidInShiftBtn = null;
            let currentSelectedBtn = null;

            slotBtns.forEach(btn => {
                const timeEl = btn.querySelector('.font-extrabold');
                const timeStr = timeEl ? timeEl.textContent.trim() : btn.textContent.trim().substring(0, 5);
                
                let isInShift = false;
                for (let s of shifts) {
                    const bd = (s.gio_bat_dau || '').substring(0, 5);
                    const kt = (s.gio_ket_thuc || '').substring(0, 5);
                    if (timeStr >= bd && timeStr < kt) {
                        isInShift = true;
                        break;
                    }
                }

                const subText = btn.querySelector('.slot-sub-text') || btn.querySelector('.font-sans');
                const isSelected = btn.classList.contains('selected');

                if (isInShift) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-30', 'line-through', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-400');
                    btn.title = 'Khung giờ trong ca trực của bác sĩ';
                    if (!isSelected && subText) subText.textContent = 'Trống';
                    if (!firstValidInShiftBtn) {
                        firstValidInShiftBtn = btn;
                    }
                    if (isSelected) {
                        currentSelectedBtn = btn;
                    }
                } else {
                    btn.disabled = true;
                    btn.classList.add('opacity-30', 'line-through', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-400');
                    btn.classList.remove('selected', 'bg-medical-600', 'text-white', 'font-bold', 'ring-2', 'ring-medical-500');
                    btn.title = 'Bác sĩ không trực ca này';
                    if (subText) subText.textContent = 'Ngoài ca';
                }
            });

            // Nếu slot hiện tại đang chọn bị rơi vào ngoài ca trực, tự động kích hoạt slot hợp lệ đầu tiên trong ca trực của bác sĩ
            if (!currentSelectedBtn && firstValidInShiftBtn) {
                firstValidInShiftBtn.click();
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
    
        // =============================================================
        // PHÂN HỆ 02: TÍNH NĂNG MỞ RỘNG (SLOT THỜI GIAN THỰC, HỒ SƠ GIA ĐÌNH, TOA THUỐC)
        // =============================================================

        let AppStateDoiTuongKham = 'BAN_THAN';

        function capNhatNhanNguoiThan() {
            const qh = document.getElementById('modal-dl-quan-he')?.value || 'CON';
            const lbl = document.getElementById('lbl-ho-ten-bn');
            const inp = document.getElementById('modal-dl-ho-ten');
            if (!lbl || !inp) return;

            if (AppStateDoiTuongKham === 'BAN_THAN') {
                lbl.innerHTML = 'Họ tên Bệnh nhân: <span class="text-rose-500">*</span>';
                inp.placeholder = 'Nhập họ tên bệnh nhân...';
                return;
            }

            if (qh === 'CON') {
                lbl.innerHTML = '<span class="text-sky-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-child text-sky-600"></i> Họ Tên Bé / Con Cái: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Bé Bo, Nguyễn Gia Hân...';
            } else if (qh === 'CHA_ME') {
                lbl.innerHTML = '<span class="text-amber-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-person-cane text-amber-600"></i> Họ Tên Bố / Mẹ: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Nguyễn Văn Nam, Trần Thị Mai...';
            } else if (qh === 'VO_CHONG') {
                lbl.innerHTML = '<span class="text-rose-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-heart text-rose-600"></i> Họ Tên Vợ / Chồng: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Lê Thị Hoa...';
            } else {
                lbl.innerHTML = 'Họ Tên Người Thân: <span class="text-rose-500">*</span>';
                inp.placeholder = 'Nhập họ tên người thân...';
            }
        }
        let DanhSachHoSoGiaDinh = [];

        // 1. CHUYỂN ĐỔI ĐỐI TƯỢNG ĐẶT KHÁM (BẢN THÂN vs NGƯỜI THÂN)
        function chuyenDoiTuongKham(loai) {
            AppStateDoiTuongKham = loai;
            const btnBt = document.getElementById('btn-tab-ban-than');
            const btnNt = document.getElementById('btn-tab-nguoi-than');
            const khuVucNt = document.getElementById('khu-vuc-nguoi-than');
            const lblHoTen = document.getElementById('lbl-ho-ten-bn');

            if (loai === 'BAN_THAN') {
                btnBt.className = 'px-2.5 py-1 rounded-lg bg-white text-sky-700 shadow-sm transition';
                btnNt.className = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition';
                khuVucNt.classList.add('hidden');

                if (AppState.currentUser) {
                    document.getElementById('modal-dl-ho-ten').value = AppState.currentUser.ho_ten || '';
                    document.getElementById('modal-dl-sdt').value = AppState.currentUser.so_dien_thoai || '';
                }
                capNhatNhanNguoiThan();
            } else {
                btnNt.className = 'px-2.5 py-1 rounded-lg bg-white text-sky-700 shadow-sm transition';
                btnBt.className = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition';
                khuVucNt.classList.remove('hidden');

                document.getElementById('modal-dl-ho-ten').value = '';
                capNhatNhanNguoiThan();
                taiDanhSachHoSoGiaDinh();
            }
        }

        async function taiDanhSachHoSoGiaDinh() {
            const selectEl = document.getElementById('modal-dl-chon-ho-so');
            if (!selectEl) return;

            try {
                const res = await goiApi('GET', '/api/v1/benh-nhan/ho-so-gia-dinh');
                if (res.ok && res.data && res.data.du_lieu) {
                    DanhSachHoSoGiaDinh = res.data.du_lieu;
                    const nguoiThanList = DanhSachHoSoGiaDinh.filter(h => h.quan_he_chu_tai_khoan !== 'BAN_THAN');
                    
                    let options = '<option value="MOI">+ Tạo hồ sơ người thân mới (Con cái, Bố/Mẹ...)</option>';
                    options += nguoiThanList.map(h => {
                        const qh = h.quan_he_chu_tai_khoan === 'CON' ? 'Con cái' : 
                                  (h.quan_he_chu_tai_khoan === 'CHA_ME' ? 'Bố/Mẹ' : 
                                  (h.quan_he_chu_tai_khoan === 'VO_CHONG' ? 'Vợ/Chồng' : 'Người thân'));
                        return `<option value="${h.id}">${h.ho_ten} (${qh}) - NS: ${h.ngay_sinh || 'Chưa rõ'}</option>`;
                    }).join('');
                    selectEl.innerHTML = options;
                }
            } catch (e) {
                console.log('Chưa có hồ sơ người thân');
            }
        }

        function chonHoSoGiaDinhDropdown(val) {
            const formMoi = document.getElementById('form-nguoi-than-moi');
            if (val === 'MOI') {
                if (formMoi) formMoi.classList.remove('hidden');
                document.getElementById('modal-dl-ho-ten').value = '';
                document.getElementById('modal-dl-nhom-mau').value = '';
                document.getElementById('modal-dl-tien-su-di-ung').value = '';
                document.getElementById('modal-dl-tien-su-benh').value = '';
            } else {
                if (formMoi) formMoi.classList.add('hidden');
                const hoSo = DanhSachHoSoGiaDinh.find(h => h.id == val);
                if (hoSo) {
                    document.getElementById('modal-dl-ho-ten').value = hoSo.ho_ten || '';
                    if (hoSo.so_dien_thoai) document.getElementById('modal-dl-sdt').value = hoSo.so_dien_thoai;
                    if (hoSo.nhom_mau) document.getElementById('modal-dl-nhom-mau').value = hoSo.nhom_mau;
                    if (hoSo.tien_su_di_ung) document.getElementById('modal-dl-tien-su-di-ung').value = hoSo.tien_su_di_ung;
                    if (hoSo.tien_su_benh) document.getElementById('modal-dl-tien-su-benh').value = hoSo.tien_su_benh;
                }
            }
        }

        // 2. TRA CỨU SLOT KHÁM THỜI GIAN THỰC (REALTIME SLOT AVAILABILITY)
        async function taiSlotsKhaDungDatLich() {
            const bacSiId = document.getElementById('modal-dl-bac-si')?.value;
            const ngayKham = document.getElementById('modal-dl-ngay')?.value;
            const sangContainer = document.getElementById('modal-dl-slots-sang');
            const chieuContainer = document.getElementById('modal-dl-slots-chieu');
            const countLabel = document.getElementById('modal-dl-slots-count');

            if (!bacSiId || !ngayKham || !sangContainer || !chieuContainer) return;

            if (countLabel) countLabel.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang tải...';

            try {
                const res = await goiApi('GET', `/api/v1/lich-hen/slots-kha-dung?bac_si_id=${bacSiId}&ngay_kham=${ngayKham}`);
                if (res.ok && res.data && res.data.du_lieu) {
                    const data = res.data.du_lieu;
                    const slots = data.slots || [];

                    if (countLabel) {
                        countLabel.textContent = `${data.so_slot_kha_dung}/${data.tong_so_slot} ca còn trống`;
                        countLabel.className = data.so_slot_kha_dung > 0 ? 'text-emerald-600 font-bold' : 'text-rose-600 font-bold';
                    }

                    const sangSlots = slots.filter(s => s.buoi === 'SANG');
                    const chieuSlots = slots.filter(s => s.buoi === 'CHIEU');

                    // Xác định duy nhất 1 slot được kích hoạt
                    let targetSlot = null;
                    if (AppState.slotDaChon && AppState.slotDaChon.batDau) {
                        const curStart = AppState.slotDaChon.batDau.substring(0, 5);
                        targetSlot = slots.find(s => s.kha_dung && s.bat_dau.substring(0, 5) === curStart);
                    }
                    if (!targetSlot) {
                        targetSlot = slots.find(s => s.kha_dung);
                    }

                    if (targetSlot) {
                        AppState.slotDaChon = { batDau: targetSlot.bat_dau, ketThuc: targetSlot.ket_thuc };
                        capNhatThongBaoCamKetCaKham(targetSlot.bat_dau.substring(0, 5), targetSlot.ket_thuc.substring(0, 5));
                    } else {
                        AppState.slotDaChon = null;
                        const lblPhienKham = document.getElementById('lbl-phien-kham');
                        if (lblPhienKham) lblPhienKham.textContent = 'Hết ca khám';
                        const badgeStt = document.getElementById('badge-stt-du-kien');
                        if (badgeStt) badgeStt.textContent = 'Hết slot trống';
                    }

                    const renderSlotItem = (s) => {
                        const isAvail = s.kha_dung;
                        const isSelect = Boolean(isAvail && targetSlot && (s.bat_dau.substring(0, 5) === targetSlot.bat_dau.substring(0, 5)));

                        if (isAvail) {
                            const activeClass = isSelect ? 'selected bg-medical-600 text-white font-bold ring-2 ring-medical-500' : 'bg-white hover:bg-sky-50 text-slate-800 border-slate-200 hover:border-sky-400';
                            return `
                                <button type="button" class="slot-btn py-2 px-1 rounded-xl border text-[11px] font-mono transition text-center shadow-xs ${activeClass}" onclick="chonSlot(this, '${s.bat_dau.substring(0, 5)}', '${s.ket_thuc.substring(0, 5)}')">
                                    <div class="font-extrabold">${s.bat_dau.substring(0, 5)}</div>
                                    <div class="text-[9px] opacity-75 font-sans slot-sub-text">${isSelect ? 'Đã chọn' : 'Trống'}</div>
                                </button>
                            `;
                        } else {
                            const badge = s.trang_thai === 'DA_DAT' ? 'Đã kín' : 'Qua giờ';
                            return `
                                <button type="button" disabled class="py-2 px-1 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-[11px] font-mono text-center cursor-not-allowed opacity-60">
                                    <div class="font-bold line-through">${s.bat_dau.substring(0, 5)}</div>
                                    <div class="text-[9px] font-sans text-slate-400">${badge}</div>
                                </button>
                            `;
                        }
                    };

                    sangContainer.innerHTML = sangSlots.map(renderSlotItem).join('');
                    chieuContainer.innerHTML = chieuSlots.map(renderSlotItem).join('');

                    // Tự động đồng bộ ca trực thực tế của bác sĩ với các slot khám
                    if (typeof kiemTraCaTrucDatLich === 'function') {
                        await kiemTraCaTrucDatLich();
                    }
                }
            } catch (e) {
                console.log('Lỗi tải slots', e);
            }
        }

        // 3. TOA THUỐC ĐIỆN TỬ & KẾT LUẬN KHÁM BỆNH
        function themDongThuoc(ten = '', hamLuong = '', soLuong = '', cachDung = '') {
            const tbody = document.getElementById('tbody-ke-toa-thuoc');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-100';
            tr.innerHTML = `
                <td class="p-2">
                    <input type="text" class="kt-ten-thuoc w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Tên thuốc..." value="${ten}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-ham-luong w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="500mg, 1g..." value="${hamLuong}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-so-luong w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="10 viên..." value="${soLuong}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-cach-dung w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Sáng 1v, tối 1v sau ăn..." value="${cachDung}">
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-rose-500 hover:text-rose-700 text-sm">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function moModalKeToa(lichHenId) {
            const lh = AppState.danhSachLichHen.find(l => l.id == lichHenId);
            if (!lh) return;

            document.getElementById('modal-kt-id').value = lh.id;
            document.getElementById('modal-kt-ma-ca').textContent = lh.ma_lich_hen || `#${lh.id}`;
            document.getElementById('modal-kt-ten-bn').textContent = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
            document.getElementById('modal-kt-ly-do').textContent = lh.ly_do_kham || 'Khám tổng quát';

            document.getElementById('modal-kt-chuan-doan').value = lh.chuan_doan || '';
            document.getElementById('modal-kt-loi-khuyen').value = lh.loi_khuyen || '';
            document.getElementById('modal-kt-ngay-tai-kham').value = lh.ngay_tai_kham || '';

            const tbody = document.getElementById('tbody-ke-toa-thuoc');
            tbody.innerHTML = '';

            const toaThuocHienTai = lh.toa_thuoc;
            if (Array.isArray(toaThuocHienTai) && toaThuocHienTai.length > 0) {
                toaThuocHienTai.forEach(t => themDongThuoc(t.ten_thuoc, t.ham_luong, t.so_luong, t.cach_dung));
            } else {
                // Thêm sẵn 2 dòng mẫu tiện dụng
                themDongThuoc('Augmentin (Amoxicillin/Clavulanate)', '1000mg', '14 viên', 'Sáng 1v, tối 1v sau ăn no');
                themDongThuoc('Paracetamol', '500mg', '10 viên', 'Uống 1 viên khi sốt trên 38.5 độ C');
            }

            moModal('modal-ke-toa-thuoc');
        }

        async function xacNhanKeToaThuoc() {
            const id = document.getElementById('modal-kt-id').value;
            const chuanDoan = document.getElementById('modal-kt-chuan-doan').value.trim();
            const loiKhuyen = document.getElementById('modal-kt-loi-khuyen').value.trim();
            const ngayTaiKham = document.getElementById('modal-kt-ngay-tai-kham').value;
            const hoanThanhNgay = document.getElementById('modal-kt-hoan-thanh-ngay').checked;

            if (!chuanDoan) {
                showToast('warning', 'Thiếu thông tin', 'Vui lòng nhập chẩn đoán bệnh y khoa.');
                return;
            }

            // Thu thập toa thuốc
            const danhSachThuoc = [];
            document.querySelectorAll('#tbody-ke-toa-thuoc tr').forEach(tr => {
                const ten = tr.querySelector('.kt-ten-thuoc')?.value.trim();
                const hamLuong = tr.querySelector('.kt-ham-luong')?.value.trim();
                const soLuong = tr.querySelector('.kt-so-luong')?.value.trim();
                const cachDung = tr.querySelector('.kt-cach-dung')?.value.trim();
                if (ten) {
                    danhSachThuoc.push({ ten_thuoc: ten, ham_luong: hamLuong, so_luong: soLuong, cach_dung: cachDung });
                }
            });

            const payload = {
                chuan_doan: chuanDoan,
                loi_khuyen: loiKhuyen,
                toa_thuoc: danhSachThuoc,
                ngay_tai_kham: ngayTaiKham || null,
                chuyen_hoan_thanh: hoanThanhNgay,
            };

            const res = await goiApi('PUT', `/api/v1/lich-hen/${id}/ket-luan-kham`, payload);
            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Đã Lưu Toa Thuốc!',
                    text: 'Kết luận chẩn đoán và đơn thuốc đã được cập nhật thành công vào hồ sơ bệnh án.',
                    confirmButtonColor: '#7c3aed'
                });
                dongModal('modal-ke-toa-thuoc');
                await taiDanhSachLichHen();
            } else {
                showToast('error', 'Thất bại', res.data.thong_diep || 'Không thể lưu đơn thuốc.');
            }
        }

        // 4. XEM TOA THUỐC VÀ IN PHIẾU KHÁM (BỆNH NHÂN XEM)
        function xemToaThuocVaKetLuan(lichHenId) {
            const lh = AppState.danhSachLichHen.find(l => l.id == lichHenId);
            if (!lh) return;

            const bs = AppState.danhSachBacSi.find(b => b.id == lh.bac_si_id);
            const tenBs = bs ? bs.ho_ten : (`BS. Phụ Trách #${lh.bac_si_id}`);
            const bn = lh.benh_nhan || {};
            const tenBn = bn.ho_ten || (lh.ho_ten_benh_nhan || 'Bệnh nhân');

            document.getElementById('view-toa-ma-ca').textContent = lh.ma_lich_hen || `LK#${lh.id}`;
            document.getElementById('view-toa-ngay-kham').textContent = `Ngày: ${lh.ngay_kham}`;
            document.getElementById('view-toa-ten-bn').textContent = tenBn;
            document.getElementById('view-toa-sdt-bn').textContent = bn.so_dien_thoai || lh.so_dien_thoai || '--';
            document.getElementById('view-toa-nhom-mau').textContent = bn.nhom_mau ? `Nhóm ${bn.nhom_mau}` : 'Chưa ghi nhận';
            document.getElementById('view-toa-bac-si').textContent = tenBs;
            document.getElementById('view-toa-di-ung').textContent = bn.tien_su_di_ung || 'Không ghi nhận tiền sử dị ứng thuốc';

            document.getElementById('view-toa-chuan-doan').textContent = lh.chuan_doan || 'Khám sức khỏe tổng quát định kỳ';
            document.getElementById('view-toa-loi-khuyen').textContent = lh.loi_khuyen || 'Uống thuốc đúng liều lượng, tái khám khi có dấu hiệu bất thường.';
            document.getElementById('view-toa-tai-kham').textContent = lh.ngay_tai_kham || 'Tái khám theo hẹn hoặc khi hết thuốc';
            document.getElementById('view-toa-bs-ten').textContent = tenBs;
            document.getElementById('view-toa-chu-ky').textContent = tenBs;

            const tbody = document.getElementById('view-toa-tbody-thuoc');
            let danhSachThuoc = lh.toa_thuoc;
            if (typeof danhSachThuoc === 'string') {
                try { danhSachThuoc = JSON.parse(danhSachThuoc); } catch(e) {}
            }

            if (Array.isArray(danhSachThuoc) && danhSachThuoc.length > 0) {
                tbody.innerHTML = danhSachThuoc.map((t, idx) => `
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2.5 px-3 text-slate-500 font-mono font-bold text-center">${idx + 1}</td>
                        <td class="py-2.5 px-3 font-extrabold text-slate-800">${t.ten_thuoc}</td>
                        <td class="py-2.5 px-2 text-slate-600">${t.ham_luong || '--'}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-purple-700">${t.so_luong || '--'}</td>
                        <td class="py-2.5 px-3 text-slate-700 italic">${t.cach_dung || 'Theo chỉ định'}</td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400 italic">
                            Chưa có đơn thuốc kê trong ca khám này. Vui lòng liên hệ bác sĩ phụ trách.
                        </td>
                    </tr>
                `;
            }

            moModal('modal-xem-toa-thuoc');
        }

        function inPhieuKhamToaThuoc() {
            const printContent = document.getElementById('in-phieu-kham-area').innerHTML;
            const printWindow = window.open('', '', 'height=700,width=900');
            printWindow.document.write('<!DOCTYPE html><html><head><title>Phiếu Khám Bệnh & Toa Thuốc</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">');
            printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }</style>');
            printWindow.document.write('</head><body class="p-8 bg-white text-slate-800 font-sans text-xs">');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            setTimeout(() => {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }, 600);
        }

    
        // =========================================================================
        // PHÂN HỆ QUẢN LÝ BỆNH NHÂN & HỒ SƠ BỆNH ÁN ĐIỆN TỬ (EHR/EMR)
        // =========================================================================

        async function taiDanhSachBenhNhan(hienThongBao = false) {
            try {
                const [resBn, resLh] = await Promise.all([
                    goiApi('GET', '/api/v1/benh-nhan'),
                    goiApi('GET', '/api/v1/lich-hen')
                ]);

                if (resBn.ok && resBn.data) {
                    AppState.danhSachBenhNhan = Array.isArray(resBn.data) ? resBn.data : (resBn.data.du_lieu || []);
                }
                if (resLh.ok && resLh.data) {
                    AppState.danhSachLichHen = Array.isArray(resLh.data) ? resLh.data : (resLh.data.du_lieu || []);
                }

                // Cập nhật dropdown lọc bác sĩ
                capNhatDropdownBacSiLocBN();

                // Render dữ liệu và thống kê
                renderDanhSachBenhNhan(AppState.danhSachBenhNhan);
                capNhatThongKeBenhNhan();

                if (hienThongBao) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Đã làm mới dữ liệu bệnh nhân & bệnh án!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            } catch (err) {
                console.error('Lỗi tải danh sách bệnh nhân:', err);
            }
        }

        function capNhatDropdownBacSiLocBN() {
            const selectBs = document.getElementById('qlbn-loc-bac-si');
            if (!selectBs) return;

            const curVal = selectBs.value;
            selectBs.innerHTML = '<option value="">Tất cả Bác Sĩ Thăm Khám</option>';

            (AppState.danhSachBacSi || []).forEach(bs => {
                const opt = document.createElement('option');
                opt.value = bs.id;
                const tenKhoa = bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                opt.textContent = `${bs.ho_ten} (${tenKhoa})`;
                selectBs.appendChild(opt);
            });

            selectBs.value = curVal;
        }

        function capNhatThongKeBenhNhan() {
            const totalBn = (AppState.danhSachBenhNhan || []).length;
            const todayStr = new Date().toISOString().split('T')[0];

            let countHomNay = 0;
            let countDangCho = 0;
            let countTaiKham = 0;

            (AppState.danhSachLichHen || []).forEach(lh => {
                if (lh.ngay_kham === todayStr) {
                    countHomNay++;
                }
                if (['CHO_XAC_NHAN', 'DA_XAC_NHAN'].includes(lh.trang_thai)) {
                    countDangCho++;
                }
                if (lh.ngay_tai_kham) {
                    countTaiKham++;
                }
            });

            const elTotal = document.getElementById('qlbn-stat-tong-bn');
            if (elTotal) elTotal.textContent = totalBn;

            const elHomNay = document.getElementById('qlbn-stat-kham-hom-nay');
            if (elHomNay) elHomNay.textContent = countHomNay;

            const elDangCho = document.getElementById('qlbn-stat-dang-cho');
            if (elDangCho) elDangCho.textContent = countDangCho;

            const elTaiKham = document.getElementById('qlbn-stat-tai-kham');
            if (elTaiKham) elTaiKham.textContent = countTaiKham;

            const elCountTong = document.getElementById('qlbn-count-tong');
            if (elCountTong) elCountTong.textContent = totalBn;

            const elStatAdmin = document.getElementById('stat-so-ho-so-bn');
            if (elStatAdmin) elStatAdmin.textContent = totalBn;
        }

        function renderDanhSachBenhNhan(list = null) {
            const tbody = document.getElementById('tbody-danh-sach-benh-nhan');
            if (!tbody) return;

            const displayList = list !== null ? list : (AppState.danhSachBenhNhan || []);
            const countHienThi = document.getElementById('qlbn-count-hien-thi');
            if (countHienThi) countHienThi.textContent = displayList.length;

            if (!displayList || displayList.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <div class="font-medium text-xs">Không tìm thấy hồ sơ bệnh nhân nào phù hợp.</div>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            displayList.forEach(bn => {
                // Tìm lịch khám gần nhất của bệnh nhân này
                const lichHensCuaBn = (AppState.danhSachLichHen || []).filter(lh => 
                    lh.benh_nhan_id == bn.id || 
                    (lh.benh_nhan && lh.benh_nhan.id == bn.id) ||
                    (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)
                );

                // Sắp xếp ngày khám mới nhất
                lichHensCuaBn.sort((a, b) => new Date(b.ngay_kham + ' ' + (b.gio_bat_dau || '00:00')) - new Date(a.ngay_kham + ' ' + (a.gio_bat_dau || '00:00')));
                const latestVisit = lichHensCuaBn[0] || null;

                // Bác sĩ khám
                let tenBs = '<span class="text-slate-400 italic">Chưa có ca khám</span>';
                let chuyenKhoa = '';
                if (latestVisit) {
                    const bs = (AppState.danhSachBacSi || []).find(b => b.id == latestVisit.bac_si_id);
                    tenBs = bs ? `<span class="font-bold text-slate-900">${bs.ho_ten}</span>` : `<span class="font-semibold text-slate-700">BS. #${latestVisit.bac_si_id}</span>`;
                    const khoa = bs && bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                    chuyenKhoa = `<div class="text-[11px] text-sky-600 font-medium">${khoa}</div>`;
                }

                // Khung giờ khám & Ngày khám
                let thoiGianKham = '<span class="text-slate-400 italic">--</span>';
                if (latestVisit) {
                    const gioBatDau = (latestVisit.gio_bat_dau || '').substring(0, 5);
                    const gioKetThuc = (latestVisit.gio_ket_thuc || '').substring(0, 5);
                    const khungGio = (gioBatDau && gioKetThuc) ? `${gioBatDau} - ${gioKetThuc}` : (gioBatDau || 'Ca trong ngày');
                    const ngay = latestVisit.ngay_kham ? latestVisit.ngay_kham.split('-').reverse().join('/') : '--';
                    thoiGianKham = `
                        <div class="font-extrabold text-slate-800">${khungGio}</div>
                        <div class="text-[11px] text-slate-400 font-medium"><i class="fa-regular fa-calendar text-[10px] mr-1"></i>${ngay}</div>
                    `;
                }

                // Bị bệnh gì (Chẩn đoán lâm sàng) & Lý do khám
                let benhLy = '<span class="text-slate-400 italic">Chưa có chẩn đoán</span>';
                if (latestVisit) {
                    const chuanDoan = latestVisit.chuan_doan;
                    const lyDo = latestVisit.ly_do_kham || latestVisit.trieu_chung || '';
                    if (chuanDoan) {
                        benhLy = `
                            <div class="font-extrabold text-rose-700 flex items-center gap-1">
                                <i class="fa-solid fa-stethoscope text-[10px] text-rose-500"></i>
                                <span class="truncate max-w-[190px]" title="${chuanDoan}">${chuanDoan}</span>
                            </div>
                            ${lyDo ? `<div class="text-[11px] text-slate-400 truncate max-w-[190px]" title="${lyDo}">Lý do: ${lyDo}</div>` : ''}
                        `;
                    } else {
                        benhLy = `
                            <div class="text-slate-600 font-medium truncate max-w-[190px]" title="${lyDo || 'Chờ bác sĩ kết luận'}">
                                ${lyDo ? `Khám: ${lyDo}` : 'Chờ bác sĩ khám'}
                            </div>
                            <span class="text-[10px] px-1.5 py-0.2 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200">Đang theo dõi</span>
                        `;
                    }
                }

                // Nhóm máu & Cảnh báo
                let nhomMauBadge = bn.nhom_mau ? `<span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">${bn.nhom_mau}</span>` : '';
                let diUngBadge = bn.tien_su_di_ung ? `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1" title="Dị ứng: ${bn.tien_su_di_ung}"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i>Dị ứng</span>` : '';
                let benhNenBadge = bn.tien_su_benh ? `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200" title="Bệnh nền: ${bn.tien_su_benh}">Bệnh nền</span>` : '';

                // Tuổi
                let tuoi = '';
                if (bn.ngay_sinh) {
                    const birthYear = new Date(bn.ngay_sinh).getFullYear();
                    const age = new Date().getFullYear() - birthYear;
                    tuoi = age > 0 ? `${age} tuổi` : '';
                }

                // Trạng thái badge
                let statusBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Mới tạo</span>';
                if (latestVisit) {
                    const st = latestVisit.trang_thai;
                    if (st === 'HOAN_THANH') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-200">Đã Khám</span>';
                    } else if (st === 'DANG_KHAM') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-700 border border-sky-200 animate-pulse">Đang Khám</span>';
                    } else if (st === 'DA_XAC_NHAN') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700 border border-amber-200">Chờ Khám</span>';
                    } else if (st === 'DA_HUY') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Đã Hủy</span>';
                    } else {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700 border border-indigo-200">Chờ Duyệt</span>';
                    }
                }

                const maBn = bn.ma_benh_nhan || `BN-${String(bn.id).padStart(4, '0')}`;

                html += `
                    <tr class="hover:bg-rose-50/30 transition group">
                        <!-- Cột 1: Bệnh nhân -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-500 to-amber-500 text-white flex items-center justify-center font-black text-xs shadow-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                                    ${(bn.ho_ten || 'BN').substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span>${bn.ho_ten}</span>
                                        <span class="text-[9px] px-1.5 py-0.2 rounded font-black uppercase bg-slate-100 text-slate-600 border border-slate-200">${bn.gioi_tinh || 'NAM'}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mt-0.5">
                                        <span class="font-mono text-rose-600 font-bold">${maBn}</span>
                                        ${tuoi ? `<span>• ${tuoi}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Cột 2: Liên hệ & Y tế -->
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">${bn.so_dien_thoai || '--'}</div>
                            <div class="flex items-center gap-1 flex-wrap mt-1">
                                ${nhomMauBadge}
                                ${diUngBadge}
                                ${benhNenBadge}
                            </div>
                        </td>

                        <!-- Cột 3: Bác sĩ khám -->
                        <td class="py-3.5 px-4">
                            ${tenBs}
                            ${chuyenKhoa}
                        </td>

                        <!-- Cột 4: Khung giờ & Ngày khám -->
                        <td class="py-3.5 px-4">
                            ${thoiGianKham}
                        </td>

                        <!-- Cột 5: Chẩn đoán / Bị bệnh gì -->
                        <td class="py-3.5 px-4">
                            ${benhLy}
                        </td>

                        <!-- Cột 6: Trạng thái -->
                        <td class="py-3.5 px-4">
                            ${statusBadge}
                            ${latestVisit && latestVisit.ngay_tai_kham ? `<div class="text-[10px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-calendar-check mr-0.5"></i>Tái khám: ${latestVisit.ngay_tai_kham.split('-').reverse().join('/')}</div>` : ''}
                        </td>

                        <!-- Cột 7: Thao tác -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <button type="button" onclick="moModalChiTietBenhNhanEHR(${bn.id})" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition" title="Xem Toàn Bộ Bệnh Án Điện Tử (EHR)">
                                    <i class="fa-solid fa-notes-medical"></i>
                                </button>
                                <button type="button" onclick="datLichChoBenhNhan(${bn.id})" class="p-1.5 bg-sky-50 hover:bg-sky-100 text-sky-600 rounded-lg transition" title="Đặt Lịch Khám / Tái Khám Nhanh">
                                    <i class="fa-regular fa-calendar-plus"></i>
                                </button>
                                <button type="button" onclick="moModalThemSuaBenhNhan(${bn.id})" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition" title="Cập Nhật Hồ Sơ Bệnh Nhân">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function locDanhSachBenhNhan() {
            const tuKhoa = (document.getElementById('qlbn-tim-kiem')?.value || '').toLowerCase().trim();
            const bacSiId = document.getElementById('qlbn-loc-bac-si')?.value || '';
            const khungGioLoc = document.getElementById('qlbn-loc-khung-gio')?.value || '';
            const trangThaiLoc = document.getElementById('qlbn-loc-trang-thai')?.value || '';

            const filtered = (AppState.danhSachBenhNhan || []).filter(bn => {
                // 1. Tìm kiếm theo từ khóa
                const maBn = (bn.ma_benh_nhan || '').toLowerCase();
                const hoTen = (bn.ho_ten || '').toLowerCase();
                const sdt = (bn.so_dien_thoai || '').toLowerCase();
                const cccd = (bn.so_cccd || '').toLowerCase();

                // Lịch khám của bệnh nhân
                const lichHens = (AppState.danhSachLichHen || []).filter(lh => 
                    lh.benh_nhan_id == bn.id || 
                    (lh.benh_nhan && lh.benh_nhan.id == bn.id) ||
                    (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)
                );
                lichHens.sort((a, b) => new Date(b.ngay_kham + ' ' + (b.gio_bat_dau || '00:00')) - new Date(a.ngay_kham + ' ' + (a.gio_bat_dau || '00:00')));
                const latestVisit = lichHens[0] || null;

                const benhChuanDoan = latestVisit && latestVisit.chuan_doan ? latestVisit.chuan_doan.toLowerCase() : '';
                const lyDoKham = latestVisit && (latestVisit.ly_do_kham || latestVisit.trieu_chung) ? (latestVisit.ly_do_kham || latestVisit.trieu_chung).toLowerCase() : '';

                const matchTuKhoa = !tuKhoa || 
                    maBn.includes(tuKhoa) || 
                    hoTen.includes(tuKhoa) || 
                    sdt.includes(tuKhoa) || 
                    cccd.includes(tuKhoa) ||
                    benhChuanDoan.includes(tuKhoa) ||
                    lyDoKham.includes(tuKhoa);

                if (!matchTuKhoa) return false;

                // 2. Lọc theo bác sĩ
                if (bacSiId) {
                    const hasBs = lichHens.some(lh => lh.bac_si_id == bacSiId);
                    if (!hasBs) return false;
                }

                // 3. Lọc theo khung giờ
                if (khungGioLoc && latestVisit) {
                    const gio = latestVisit.gio_bat_dau ? parseInt(latestVisit.gio_bat_dau.split(':')[0]) : 8;
                    if (khungGioLoc === 'SANG' && gio >= 12) return false;
                    if (khungGioLoc === 'CHIEU' && gio < 12) return false;
                }

                // 4. Lọc theo trạng thái
                if (trangThaiLoc) {
                    if (trangThaiLoc === 'CO_TAI_KHAM') {
                        if (!lichHens.some(lh => !!lh.ngay_tai_kham)) return false;
                    } else {
                        if (!latestVisit || latestVisit.trang_thai !== trangThaiLoc) return false;
                    }
                }

                return true;
            });

            renderDanhSachBenhNhan(filtered);
        }

        function datBoLocNhanh(kieu) {
            document.querySelectorAll('.btn-quick-filter').forEach(b => {
                b.classList.remove('ring-2', 'ring-rose-500', 'font-extrabold');
            });
            event.target.classList.add('ring-2', 'ring-rose-500', 'font-extrabold');

            const searchInput = document.getElementById('qlbn-tim-kiem');
            const selectTt = document.getElementById('qlbn-loc-trang-thai');

            if (kieu === 'ALL') {
                if (searchInput) searchInput.value = '';
                if (selectTt) selectTt.value = '';
                renderDanhSachBenhNhan(AppState.danhSachBenhNhan);
                return;
            }

            if (kieu === 'HOM_NAY') {
                const todayStr = new Date().toISOString().split('T')[0];
                const filtered = (AppState.danhSachBenhNhan || []).filter(bn => {
                    return (AppState.danhSachLichHen || []).some(lh => 
                        (lh.benh_nhan_id == bn.id || (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)) &&
                        lh.ngay_kham === todayStr
                    );
                });
                renderDanhSachBenhNhan(filtered);
                return;
            }

            if (kieu === 'CHO_KHAM') {
                if (selectTt) selectTt.value = 'DA_XAC_NHAN';
                locDanhSachBenhNhan();
                return;
            }

            if (kieu === 'DA_KHAM') {
                if (selectTt) selectTt.value = 'HOAN_THANH';
                locDanhSachBenhNhan();
                return;
            }

            if (kieu === 'CANH_BAO') {
                const filtered = (AppState.danhSachBenhNhan || []).filter(bn => !!bn.tien_su_di_ung);
                renderDanhSachBenhNhan(filtered);
                return;
            }
        }

        // =========================================================================
        // MODAL EHR TIMELINE CHI TIẾT
        // =========================================================================
        async function moModalChiTietBenhNhanEHR(benhNhanId) {
            let bn = (AppState.danhSachBenhNhan || []).find(b => b.id == benhNhanId);
            if (!bn) {
                const res = await goiApi('GET', `/api/v1/benh-nhan/${benhNhanId}`);
                if (res.ok && res.data) {
                    bn = res.data.du_lieu || res.data;
                }
            }

            if (!bn) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không tìm thấy hồ sơ bệnh nhân!' });
                return;
            }

            AppState.benhNhanDangXem = bn;

            // Populate Banner
            const maBn = bn.ma_benh_nhan || `BN-${String(bn.id).padStart(4, '0')}`;
            const elBadgeMaBn = document.getElementById('ehr-badge-ma-bn');
            if (elBadgeMaBn) elBadgeMaBn.textContent = maBn;

            const elAvatar = document.getElementById('ehr-patient-avatar');
            if (elAvatar) elAvatar.textContent = (bn.ho_ten || 'BN').substring(0, 2).toUpperCase();

            const elName = document.getElementById('ehr-patient-name');
            if (elName) elName.textContent = bn.ho_ten || '--';

            const elGender = document.getElementById('ehr-patient-gender');
            if (elGender) elGender.textContent = bn.gioi_tinh === 'NU' ? 'Nữ' : (bn.gioi_tinh === 'NAM' ? 'Nam' : 'Khác');

            let dobStr = '--';
            let ageStr = '--';
            if (bn.ngay_sinh) {
                const birthYear = new Date(bn.ngay_sinh).getFullYear();
                const age = new Date().getFullYear() - birthYear;
                dobStr = `${bn.ngay_sinh.split('-').reverse().join('/')}`;
                ageStr = `${age} tuổi`;
            }
            const elDob = document.getElementById('ehr-patient-dob');
            if (elDob) elDob.textContent = dobStr;

            const elAge = document.getElementById('ehr-patient-age');
            if (elAge) elAge.textContent = ageStr;

            const elPhone = document.getElementById('ehr-patient-phone');
            if (elPhone) elPhone.innerHTML = `<i class="fa-solid fa-phone text-slate-400 mr-1"></i>${bn.so_dien_thoai || '--'}`;

            const elCccd = document.getElementById('ehr-patient-cccd');
            if (elCccd) elCccd.textContent = bn.so_cccd || bn.cccd || '--';

            const elAddress = document.getElementById('ehr-patient-address');
            if (elAddress) elAddress.textContent = bn.dia_chi || '--';

            const elBadgeBlood = document.getElementById('ehr-badge-blood');
            if (elBadgeBlood) {
                elBadgeBlood.innerHTML = `<i class="fa-solid fa-droplet text-rose-500 mr-1"></i>Nhóm Máu: ${bn.nhom_mau || 'Chưa rõ'}`;
            }

            // Baseline & Emergency
            const elAllergy = document.getElementById('ehr-patient-allergy');
            if (elAllergy) {
                elAllergy.textContent = bn.tien_su_di_ung ? bn.tien_su_di_ung : 'Chưa ghi nhận dị ứng thuốc';
                elAllergy.className = bn.tien_su_di_ung ? 'font-bold text-rose-600 mt-1' : 'font-semibold text-slate-600 mt-1';
            }

            const elHistory = document.getElementById('ehr-patient-history');
            if (elHistory) {
                elHistory.textContent = bn.tien_su_benh ? bn.tien_su_benh : 'Chưa ghi nhận bệnh nền';
            }

            const elEmergencyContact = document.getElementById('ehr-patient-emergency-contact');
            if (elEmergencyContact) {
                elEmergencyContact.textContent = bn.nguoi_lien_he_khan_cap || 'Thân nhân (Chưa cập nhật)';
            }
            const elEmergencyPhone = document.getElementById('ehr-patient-emergency-phone');
            if (elEmergencyPhone) {
                elEmergencyPhone.textContent = bn.sdt_khan_cap || bn.so_dien_thoai || '--';
            }

            // Timeline Items
            const timelineContainer = document.getElementById('ehr-timeline-container');
            const lichHens = (AppState.danhSachLichHen || []).filter(lh => 
                lh.benh_nhan_id == bn.id || 
                (lh.benh_nhan && lh.benh_nhan.id == bn.id) ||
                (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)
            );

            lichHens.sort((a, b) => new Date(b.ngay_kham + ' ' + (b.gio_bat_dau || '00:00')) - new Date(a.ngay_kham + ' ' + (a.gio_bat_dau || '00:00')));
            const elTimelineCount = document.getElementById('ehr-total-visits');
            if (elTimelineCount) {
                elTimelineCount.textContent = `${lichHens.length} lượt khám`;
            }

            if (lichHens.length === 0) {
                timelineContainer.innerHTML = `
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200/60 text-slate-400">
                        <i class="fa-solid fa-file-circle-question text-3xl mb-2 text-slate-300"></i>
                        <p class="font-medium text-xs">Bệnh nhân chưa có lịch sử ca khám nào trong hệ thống.</p>
                        <button type="button" onclick="datLichChoBenhNhanHienTai()" class="mt-3 px-3.5 py-1.5 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition">
                            Tạo Ca Khám Đầu Tiên
                        </button>
                    </div>
                `;
            } else {
                let timelineHtml = '';
                lichHens.forEach((lh, index) => {
                    const bs = (AppState.danhSachBacSi || []).find(b => b.id == lh.bac_si_id);
                    const tenBs = bs ? bs.ho_ten : (`Bác sĩ #${lh.bac_si_id}`);
                    const chuyenKhoa = bs && bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội Tổng Quát';

                    const ngayKham = lh.ngay_kham ? lh.ngay_kham.split('-').reverse().join('/') : '--';
                    const gioBatDau = (lh.gio_bat_dau || '').substring(0, 5);
                    const gioKetThuc = (lh.gio_ket_thuc || '').substring(0, 5);
                    const khungGio = (gioBatDau && gioKetThuc) ? `${gioBatDau} - ${gioKetThuc}` : (gioBatDau || 'Ca khám');

                    // Status
                    let stBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Đã Hoàn Thành</span>';
                    if (lh.trang_thai === 'DANG_KHAM') stBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-700">Đang Khám</span>';
                    if (lh.trang_thai === 'DA_XAC_NHAN') stBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Chờ Khám</span>';
                    if (lh.trang_thai === 'DA_HUY') stBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Đã Hủy</span>';

                    // Toa thuốc
                    let toaThuocHtml = '';
                    if (lh.toa_thuoc && Array.isArray(lh.toa_thuoc) && lh.toa_thuoc.length > 0) {
                        toaThuocHtml = `
                            <div class="mt-3 p-3 bg-white rounded-xl border border-slate-200 space-y-2">
                                <div class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-pills text-rose-500"></i>
                                    <span>Toa Thuốc Điện Tử Điều Trị (${lh.toa_thuoc.length} loại thuốc):</span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-[11px]">
                                        <thead>
                                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                                                <th class="py-1.5 px-2">STT</th>
                                                <th class="py-1.5 px-2">Tên Thuốc</th>
                                                <th class="py-1.5 px-2">Hàm Lượng / Liều Dùng</th>
                                                <th class="py-1.5 px-2">Cách Uống</th>
                                                <th class="py-1.5 px-2 text-center">Số Ngày</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-slate-700">
                                            ${lh.toa_thuoc.map((t, idx) => `
                                                <tr>
                                                    <td class="py-1 px-2 font-bold text-slate-400">${idx + 1}</td>
                                                    <td class="py-1 px-2 font-bold text-slate-800">${t.ten_thuoc || t.ten || '--'}</td>
                                                    <td class="py-1 px-2 font-medium text-slate-600">${t.lieu_dung || t.ham_luong || '--'}</td>
                                                    <td class="py-1 px-2 text-slate-600">${t.cach_dung || t.huong_dan || '--'}</td>
                                                    <td class="py-1 px-2 text-center font-bold text-rose-600">${t.so_ngay ? `${t.so_ngay} ngày` : '--'}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Tệp đính kèm
                    let tepDinhKemHtml = '';
                    if (lh.tep_dinh_kem && Array.isArray(lh.tep_dinh_kem) && lh.tep_dinh_kem.length > 0) {
                        tepDinhKemHtml = `
                            <div class="mt-2 flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-bold text-slate-400">Kết quả đính kèm:</span>
                                ${lh.tep_dinh_kem.map(f => `
                                    <a href="${f.url || '#'}" target="_blank" class="px-2 py-0.5 rounded-lg bg-sky-50 text-sky-700 border border-sky-200 text-[10px] font-semibold hover:bg-sky-100 transition inline-flex items-center gap-1">
                                        <i class="fa-solid fa-file-medical text-sky-500"></i>
                                        <span>${f.ten_tep || 'Kết quả xét nghiệm / X-Quang'}</span>
                                    </a>
                                `).join('')}
                            </div>
                        `;
                    }

                    timelineHtml += `
                        <div class="relative pl-6 pb-6 border-l-2 ${index === 0 ? 'border-rose-500' : 'border-slate-200'} last:pb-0">
                            <!-- Bullet -->
                            <div class="absolute -left-2 top-0 w-4 h-4 rounded-full ${index === 0 ? 'bg-rose-500 ring-4 ring-rose-100' : 'bg-slate-300'} flex items-center justify-center text-white text-[8px]">
                                ${index + 1}
                            </div>

                            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 hover:bg-white hover:shadow-xs transition space-y-3">
                                <!-- Top: Thời gian & Bác sĩ -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-slate-200/60">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-slate-900 text-white flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[10px] text-rose-400"></i> ${khungGio}
                                        </span>
                                        <span class="text-xs font-extrabold text-slate-800">${ngayKham}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-xs font-bold text-sky-700"><i class="fa-solid fa-user-doctor text-sky-500 mr-1"></i>${tenBs}</span>
                                        <span class="text-[11px] text-slate-500">(${chuyenKhoa})</span>
                                    </div>
                                    <div>${stBadge}</div>
                                </div>

                                <!-- Triệu chứng & Chẩn đoán -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="p-2.5 bg-white rounded-xl border border-slate-200">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lý do / Triệu chứng khi đến khám:</div>
                                        <div class="font-semibold text-slate-800 mt-0.5">${lh.ly_do_kham || lh.trieu_chung || 'Khám sức khỏe tổng quát định kỳ'}</div>
                                    </div>

                                    <div class="p-2.5 bg-rose-50/70 rounded-xl border border-rose-100">
                                        <div class="text-[10px] font-bold text-rose-600 uppercase tracking-wider flex items-center gap-1">
                                            <i class="fa-solid fa-heart-pulse"></i> Chẩn đoán bệnh lý (Bị bệnh gì):
                                        </div>
                                        <div class="font-black text-rose-800 mt-0.5 text-xs">
                                            ${lh.chuan_doan || '<span class="text-slate-400 font-normal italic">Chưa có kết luận chẩn đoán</span>'}
                                        </div>
                                    </div>
                                </div>

                                <!-- Toa thuốc -->
                                ${toaThuocHtml}

                                <!-- Lời dặn & Hẹn tái khám -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-2 text-[11px] border-t border-slate-200/60">
                                    <div class="text-slate-600">
                                        <strong class="text-slate-700">Lời dặn bác sĩ:</strong> ${lh.loi_khuyen || lh.ghi_chu_bac_si || 'Uống thuốc đúng giờ, tái khám khi có dấu hiệu bất thường.'}
                                    </div>
                                    ${lh.ngay_tai_kham ? `
                                        <div class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold flex items-center gap-1 flex-shrink-0">
                                            <i class="fa-regular fa-calendar-check text-emerald-600"></i> Hẹn tái khám: ${lh.ngay_tai_kham.split('-').reverse().join('/')}
                                        </div>
                                    ` : ''}
                                </div>

                                ${tepDinhKemHtml}
                            </div>
                        </div>
                    `;
                });
                timelineContainer.innerHTML = timelineHtml;
            }

            moModal('modal-chi-tiet-benh-nhan-ehr');
        }

        function moModalXemEhrHoacGiaDinh(benhNhanId) {
            const role = AppState.currentUser ? AppState.currentUser.vai_tro : 'BENH_NHAN';
            if (role === 'BENH_NHAN') {
                chuyenTab('tab-ho-so-gia-dinh');
            } else if (benhNhanId) {
                moModalChiTietBenhNhanEHR(benhNhanId);
            } else {
                chuyenTab('tab-admin-benh-nhan');
            }
        }

        function datLichChoBenhNhan(benhNhanId) {
            const bn = (AppState.danhSachBenhNhan || []).find(b => b.id == benhNhanId);
            if (!bn) return;
            moModalDatLich();
            // Pre-fill fields
            const tenBn = document.getElementById('modal-dl-ho-ten');
            if (tenBn) tenBn.value = bn.ho_ten || '';
            const sdtBn = document.getElementById('modal-dl-sdt');
            if (sdtBn) sdtBn.value = bn.so_dien_thoai || '';
            const cccdBn = document.getElementById('modal-dl-cccd');
            if (cccdBn) cccdBn.value = bn.so_cccd || bn.cccd || '';
            const nsBn = document.getElementById('modal-dl-ngay-sinh-nt');
            if (nsBn && bn.ngay_sinh) nsBn.value = bn.ngay_sinh;
            chuyenDoiTuongKham('NGUOI_THAN');
        }

        function datLichChoBenhNhanHienTai() {
            if (!AppState.benhNhanDangXem) return;
            dongModal('modal-chi-tiet-benh-nhan-ehr');
            datLichChoBenhNhan(AppState.benhNhanDangXem.id);
        }

        function inBenhAnDienTuHienTai() {
            if (!AppState.benhNhanDangXem) return;
            inBenhAnDienTu(AppState.benhNhanDangXem.id);
        }

        function inBenhAnDienTu(benhNhanId) {
            const bn = (AppState.danhSachBenhNhan || []).find(b => b.id == benhNhanId) || AppState.benhNhanDangXem;
            if (!bn) return;

            const lichHens = (AppState.danhSachLichHen || []).filter(lh => 
                lh.benh_nhan_id == bn.id || 
                (lh.benh_nhan && lh.benh_nhan.id == bn.id) ||
                (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)
            );
            lichHens.sort((a, b) => new Date(b.ngay_kham + ' ' + (b.gio_bat_dau || '00:00')) - new Date(a.ngay_kham + ' ' + (a.gio_bat_dau || '00:00')));

            const maBn = bn.ma_benh_nhan || `BN-${String(bn.id).padStart(4, '0')}`;
            const ngayIn = new Date().toLocaleDateString('vi-VN');

            let visitsHtml = '';
            lichHens.forEach((lh, idx) => {
                const bs = (AppState.danhSachBacSi || []).find(b => b.id == lh.bac_si_id);
                const tenBs = bs ? bs.ho_ten : (`Bác sĩ #${lh.bac_si_id}`);
                const khoa = bs && bs.chuyen_khoa ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const ngayKham = lh.ngay_kham ? lh.ngay_kham.split('-').reverse().join('/') : '--';
                const gio = lh.gio_bat_dau ? `${lh.gio_bat_dau.substring(0, 5)} - ${(lh.gio_ket_thuc || '').substring(0, 5)}` : '';

                visitsHtml += `
                    <div style="margin-bottom: 20px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; font-weight: bold; color: #0f172a; margin-bottom: 6px;">
                            <span>Lần khám ${lichHens.length - idx}: Ngày ${ngayKham} (${gio})</span>
                            <span style="color: #0284c7;">BS. ${tenBs} - ${khoa}</span>
                        </div>
                        <div style="margin-bottom: 4px;"><strong>Lý do khám:</strong> ${lh.ly_do_kham || lh.trieu_chung || 'Khám định kỳ'}</div>
                        <div style="margin-bottom: 4px; color: #b91c1c;"><strong>Chẩn đoán bệnh lý:</strong> ${lh.chuan_doan || 'Đang theo dõi'}</div>
                        ${lh.toa_thuoc && lh.toa_thuoc.length > 0 ? `
                            <div style="margin-top: 6px;">
                                <strong>Toa thuốc:</strong>
                                <ul style="margin: 4px 0 0 16px; padding: 0;">
                                    ${lh.toa_thuoc.map(t => `<li>${t.ten_thuoc || t.ten} (${t.lieu_dung || ''}) - ${t.cach_dung || ''} [${t.so_ngay || ''} ngày]</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                        <div style="margin-top: 4px; font-style: italic; color: #475569;"><strong>Lời dặn:</strong> ${lh.loi_khuyen || lh.ghi_chu_bac_si || 'Uống thuốc và tái khám theo chỉ dẫn.'}</div>
                        ${lh.ngay_tai_kham ? `<div style="margin-top: 4px; color: #059669; font-weight: bold;">Hẹn tái khám: ${lh.ngay_tai_kham.split('-').reverse().join('/')}</div>` : ''}
                    </div>
                `;
            });

            const printHtml = `
                <html>
                    <head>
                        <title>Tóm Tắt Bệnh Án Điện Tử - ${bn.ho_ten}</title>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <style>
                            @media print {
                                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; font-family: sans-serif; font-size: 12px; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body class="p-8 bg-white text-slate-800 font-sans text-xs">
                        <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 20px;">
                            <div>
                                <h1 style="font-size: 16px; font-weight: 900; color: #0369a1; text-transform: uppercase;">Phòng Khám Đa Khoa Quốc Tế</h1>
                                <p style="font-size: 11px; color: #64748b;">Địa chỉ: 123 Đường Sức Khỏe, Quận 1, TP. Hồ Chí Minh - Hotline: 1900 6868</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 13px; font-weight: bold; color: #0f172a;">PHIẾU TÓM TẮT BỆNH ÁN ĐIỆN TỬ</div>
                                <div style="font-size: 11px; color: #b91c1c; font-weight: bold;">Mã BN: ${maBn}</div>
                                <div style="font-size: 10px; color: #64748b;">Ngày in: ${ngayIn}</div>
                            </div>
                        </div>

                        <!-- Thông tin hành chính bệnh nhân -->
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                                <div><strong>Họ và tên:</strong> ${bn.ho_ten}</div>
                                <div><strong>Giới tính:</strong> ${bn.gioi_tinh || 'Nam'}</div>
                                <div><strong>Ngày sinh:</strong> ${bn.ngay_sinh ? bn.ngay_sinh.split('-').reverse().join('/') : '--'}</div>
                                <div><strong>Số điện thoại:</strong> ${bn.so_dien_thoai || '--'}</div>
                                <div><strong>CCCD/CMND:</strong> ${bn.so_cccd || '--'}</div>
                                <div><strong>Nhóm máu:</strong> <span style="color: #b91c1c; font-weight: bold;">${bn.nhom_mau || 'Chưa rõ'}</span></div>
                            </div>
                            <div style="margin-top: 8px;"><strong>Địa chỉ:</strong> ${bn.dia_chi || 'Chưa cập nhật'}</div>
                            <div style="margin-top: 8px; color: #b91c1c;"><strong>Tiền sử dị ứng:</strong> ${bn.tien_su_di_ung || 'Không ghi nhận'}</div>
                            <div style="margin-top: 4px;"><strong>Bệnh lý nền:</strong> ${bn.tien_su_benh || 'Không'}</div>
                        </div>

                        <!-- Lịch sử các ca khám -->
                        <h3 style="font-size: 13px; font-weight: bold; text-transform: uppercase; margin-bottom: 12px; color: #0f172a; border-left: 4px solid #0284c7; padding-left: 8px;">
                            Lịch Sử Các Lần Thăm Khám & Điều Trị (${lichHens.length} ca khám)
                        </h3>
                        <div>
                            ${visitsHtml || '<p style="text-align: center; color: #94a3b8;">Chưa có dữ liệu lần khám nào.</p>'}
                        </div>

                        <!-- Chữ ký -->
                        <div style="display: flex; justify-content: space-between; margin-top: 40px; text-align: center;">
                            <div style="width: 200px;">
                                <div>Người Lập Bệnh Án</div>
                                <div style="height: 60px;"></div>
                                <div style="font-weight: bold;">Hệ thống EHR Phòng Khám</div>
                            </div>
                            <div style="width: 250px;">
                                <div>Bác Sĩ Trưởng Khoa / Phụ Trách</div>
                                <div style="height: 60px;"></div>
                                <div style="font-weight: bold; color: #0284c7;">BS. CKII. Nguyễn Văn Trưởng</div>
                            </div>
                        </div>
                    </body>
                </html>
            `;

            const printWindow = window.open('', '', 'height=750,width=950');
            printWindow.document.write(printHtml);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }, 600);
        }

        // =========================================================================
        // MODAL THÊM / CẬP NHẬT BỆNH NHÂN
        // =========================================================================
        function moModalThemSuaBenhNhan(benhNhanId = null) {
            AppState.benhNhanDangSuaId = benhNhanId;
            const titleEl = document.getElementById('modal-bn-title');
            const form = document.getElementById('form-them-sua-benh-nhan');
            if (form) form.reset();

            if (benhNhanId) {
                if (titleEl) titleEl.textContent = 'Cập Nhật Hồ Sơ Bệnh Nhân';
                const bn = (AppState.danhSachBenhNhan || []).find(b => b.id == benhNhanId);
                if (bn) {
                    document.getElementById('form-bn-id').value = bn.id;
                    document.getElementById('form-bn-hoten').value = bn.ho_ten || '';
                    document.getElementById('form-bn-sdt').value = bn.so_dien_thoai || '';
                    document.getElementById('form-bn-gioitinh').value = bn.gioi_tinh || 'NAM';
                    document.getElementById('form-bn-ngaysinh').value = bn.ngay_sinh || '';
                    document.getElementById('form-bn-nhommau').value = bn.nhom_mau || '';
                    document.getElementById('form-bn-cccd').value = bn.so_cccd || '';
                    document.getElementById('form-bn-diachi').value = bn.dia_chi || '';
                    document.getElementById('form-bn-diung').value = bn.tien_su_di_ung || '';
                    document.getElementById('form-bn-tiensubenh').value = bn.tien_su_benh || '';
                    document.getElementById('form-bn-nguoithan').value = bn.nguoi_lien_he_khan_cap || '';
                    document.getElementById('form-bn-sdtnguoithan').value = bn.sdt_khan_cap || '';
                }
            } else {
                if (titleEl) titleEl.textContent = 'Thêm Hồ Sơ Bệnh Nhân Mới';
                document.getElementById('form-bn-id').value = '';
            }

            moModal('modal-them-sua-benh-nhan');
        }

        async function luuThongTinBenhNhan() {
            const id = document.getElementById('form-bn-id').value;
            const hoTen = document.getElementById('form-bn-hoten').value.trim();
            const sdt = document.getElementById('form-bn-sdt').value.trim();

            if (!hoTen || !sdt) {
                Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập Họ tên và Số điện thoại!' });
                return;
            }

            const payload = {
                ho_ten: hoTen,
                so_dien_thoai: sdt,
                gioi_tinh: document.getElementById('form-bn-gioitinh').value,
                ngay_sinh: document.getElementById('form-bn-ngaysinh').value || null,
                nhom_mau: document.getElementById('form-bn-nhommau').value || null,
                so_cccd: document.getElementById('form-bn-cccd').value.trim() || null,
                dia_chi: document.getElementById('form-bn-diachi').value.trim() || null,
                tien_su_di_ung: document.getElementById('form-bn-diung').value.trim() || null,
                tien_su_benh: document.getElementById('form-bn-tiensubenh').value.trim() || null,
                nguoi_lien_he_khan_cap: document.getElementById('form-bn-nguoithan').value.trim() || null,
                sdt_khan_cap: document.getElementById('form-bn-sdtnguoithan').value.trim() || null
            };

            const btn = document.getElementById('btn-submit-bn');
            if (btn) btn.disabled = true;

            try {
                let res;
                if (id) {
                    res = await goiApi('PUT', `/api/v1/benh-nhan/${id}`, payload);
                } else {
                    res = await goiApi('POST', '/api/v1/benh-nhan', payload);
                }

                if (btn) btn.disabled = false;

                if (res.ok) {
                    dongModal('modal-them-sua-benh-nhan');
                    Swal.fire({
                        icon: 'success',
                        title: id ? 'Cập nhật thành công!' : 'Thêm mới thành công!',
                        text: id ? 'Thông tin bệnh nhân đã được cập nhật.' : 'Hồ sơ bệnh nhân mới đã được tạo thành công.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    await taiDanhSachBenhNhan();
                } else {
                    const msg = (res.data && res.data.thong_diep) || 'Không thể lưu hồ sơ bệnh nhân. Vui lòng kiểm tra lại.';
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
                }
            } catch (err) {
                if (btn) btn.disabled = false;
                console.error('Lỗi lưu bệnh nhân:', err);
                Swal.fire({ icon: 'error', title: 'Lỗi máy chủ', text: 'Có lỗi xảy ra khi lưu dữ liệu bệnh nhân.' });
            }
        }

        const xacNhanLuuBenhNhan = luuThongTinBenhNhan;

        // =========================================================================
        // HỆ THỐNG QUẢN LÝ HỒ SƠ GIA ĐÌNH & NGƯỜI THÂN (FAMILY EHR)
        // =========================================================================
        let CacheHoSoGiaDinh = [];

        async function taiVaRenderHoSoGiaDinh(hienThongBao = false) {
            const container = document.getElementById('container-the-gia-dinh');
            if (!container) return;

            try {
                const res = await goiApi('GET', '/api/v1/benh-nhan/ho-so-gia-dinh');
                if (res.ok && res.data && res.data.du_lieu) {
                    CacheHoSoGiaDinh = res.data.du_lieu;
                    renderTheGiaDinh(CacheHoSoGiaDinh);
                    capNhatThongKeGiaDinh(CacheHoSoGiaDinh);

                    if (hienThongBao && typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Đã cập nhật danh sách hồ sơ gia đình!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                } else {
                    container.innerHTML = `
                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-people-roof text-4xl text-slate-300 mb-3 block"></i>
                            <p class="text-sm font-bold text-slate-600">Chưa có hồ sơ người thân nào</p>
                            <p class="text-xs text-slate-400 mt-1 mb-4">Hãy thêm hồ sơ con cái, cha mẹ hoặc vợ chồng để đặt lịch khám nhanh chóng</p>
                            <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Thêm Người Thân Đầu Tiên
                            </button>
                        </div>
                    `;
                }
            } catch (err) {
                console.error('Lỗi tải hồ sơ gia đình:', err);
                container.innerHTML = '<div class="col-span-full text-center py-8 text-rose-500 text-xs">Không thể tải dữ liệu hồ sơ gia đình. Vui lòng thử lại.</div>';
            }
        }

        function capNhatThongKeGiaDinh(list) {
            const elTong = document.getElementById('stat-gd-tong-thanh-vien');
            const elNguoiThan = document.getElementById('stat-gd-nguoi-than');
            const elLuotKham = document.getElementById('stat-gd-luot-kham');

            const tongTv = (list || []).length;
            const nguoiThanCount = (list || []).filter(h => h.quan_he_chu_tai_khoan !== 'BAN_THAN').length;
            const tongKham = (list || []).reduce((acc, h) => acc + (Number(h.danh_sach_lich_hen_count) || 0), 0);

            if (elTong) elTong.textContent = tongTv;
            if (elNguoiThan) elNguoiThan.textContent = nguoiThanCount;
            if (elLuotKham) elLuotKham.textContent = tongKham;

            const elQuickBanner = document.getElementById('quick-banner-so-thanh-vien');
            if (elQuickBanner) {
                elQuickBanner.textContent = `${tongTv} thành viên gia đình`;
            }
        }

        function renderTheGiaDinh(list) {
            const container = document.getElementById('container-the-gia-dinh');
            if (!container) return;

            if (!list || list.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <i class="fa-solid fa-people-roof text-4xl text-slate-300 mb-3 block"></i>
                        <p class="text-sm font-bold text-slate-600">Chưa có hồ sơ gia đình nào</p>
                        <p class="text-xs text-slate-400 mt-1 mb-4">Thêm hồ sơ con cái, cha mẹ, vợ chồng để dễ dàng đặt khám</p>
                        <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Thêm Người Thân Đầu Tiên
                        </button>
                    </div>
                `;
                return;
            }

            let html = '';
            list.forEach(h => {
                const isBanThan = h.quan_he_chu_tai_khoan === 'BAN_THAN';
                let qhBadge = '';
                let avatarIcon = 'fa-solid fa-user';
                let avatarBg = 'from-sky-500 to-indigo-600';

                switch (h.quan_he_chu_tai_khoan) {
                    case 'BAN_THAN':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-100 text-sky-700 border border-sky-200"><i class="fa-solid fa-user-check mr-1"></i>Bản Thân (Chủ TK)</span>';
                        avatarIcon = h.gioi_tinh === 'NU' ? 'fa-solid fa-user-nurse' : 'fa-solid fa-user-tie';
                        avatarBg = 'from-sky-500 to-blue-600';
                        break;
                    case 'CON':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200"><i class="fa-solid fa-child mr-1"></i>Con Cái</span>';
                        avatarIcon = 'fa-solid fa-child-reaching';
                        avatarBg = 'from-amber-400 to-orange-500';
                        break;
                    case 'CHA_ME':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-person-cane mr-1"></i>Bố / Mẹ</span>';
                        avatarIcon = 'fa-solid fa-person-cane';
                        avatarBg = 'from-emerald-500 to-teal-600';
                        break;
                    case 'VO_CHONG':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 border border-purple-200"><i class="fa-solid fa-heart mr-1"></i>Vợ / Chồng</span>';
                        avatarIcon = 'fa-solid fa-user-group';
                        avatarBg = 'from-purple-500 to-pink-600';
                        break;
                    default:
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200"><i class="fa-solid fa-user-group mr-1"></i>Người Thân</span>';
                        avatarIcon = 'fa-solid fa-user-shield';
                        avatarBg = 'from-rose-500 to-red-600';
                        break;
                }

                // Tính tuổi nếu có ngày sinh
                let tuoiStr = '';
                if (h.ngay_sinh) {
                    const birthYear = new Date(h.ngay_sinh).getFullYear();
                    const nowYear = new Date().getFullYear();
                    if (nowYear >= birthYear) tuoiStr = ` (${nowYear - birthYear} tuổi)`;
                }

                const gioiTinhStr = h.gioi_tinh === 'NU' ? 'Nữ' : (h.gioi_tinh === 'NAM' ? 'Nam' : 'Khác');
                const nhomMauBadge = h.nhom_mau ? `<span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">Máu: ${h.nhom_mau}</span>` : '';
                const soKham = Number(h.danh_sach_lich_hen_count) || 0;

                html += `
                    <div class="bg-gradient-to-b from-white to-slate-50/70 p-6 rounded-3xl border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header Card: Avatar + Tên + Badge -->
                            <div class="flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr ${avatarBg} text-white flex items-center justify-center text-xl shadow-md flex-shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="${avatarIcon}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-extrabold text-slate-900 truncate flex items-center gap-1.5">
                                            <span>${h.ho_ten}</span>
                                        </h4>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[11px] font-mono text-slate-400 font-bold">${h.ma_benh_nhan || 'BN---'}</span>
                                            ${nhomMauBadge}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    ${qhBadge}
                                </div>
                            </div>

                            <!-- Body Info -->
                            <div class="py-4 space-y-2.5 text-xs text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-cake-candles text-slate-400 text-[11px]"></i> Ngày sinh:</span>
                                    <span class="font-semibold text-slate-700 font-mono">${h.ngay_sinh || 'Chưa cập nhật'}${tuoiStr}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-venus-mars text-slate-400 text-[11px]"></i> Giới tính:</span>
                                    <span class="font-semibold text-slate-700">${gioiTinhStr}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-phone text-slate-400 text-[11px]"></i> Điện thoại:</span>
                                    <span class="font-semibold text-slate-700 font-mono">${h.so_dien_thoai || '--'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-notes-medical text-slate-400 text-[11px]"></i> Lượt khám:</span>
                                    <span class="font-bold text-emerald-600 font-mono">${soKham} lượt</span>
                                </div>
                                
                                ${h.tien_su_di_ung ? `
                                <div class="bg-rose-50/70 p-2.5 rounded-xl border border-rose-200/60 mt-1">
                                    <p class="text-[10px] font-bold uppercase text-rose-700 flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Dị Ứng Thuốc:
                                    </p>
                                    <p class="text-xs text-rose-800 font-medium mt-0.5 line-clamp-2">${h.tien_su_di_ung}</p>
                                </div>` : ''}

                                ${h.tien_su_benh ? `
                                <div class="bg-amber-50/70 p-2.5 rounded-xl border border-amber-200/60 mt-1">
                                    <p class="text-[10px] font-bold uppercase text-amber-700 flex items-center gap-1">
                                        <i class="fa-solid fa-heart-pulse text-amber-500"></i> Bệnh Lý Nền:
                                    </p>
                                    <p class="text-xs text-amber-800 font-medium mt-0.5 line-clamp-2">${h.tien_su_benh}</p>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Footer Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex items-center gap-2">
                            <button type="button" onclick="datLichNhanhChoNguoiThan(${h.id})" class="flex-1 py-2.5 px-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-calendar-plus text-xs"></i>
                                <span>Đặt Lịch Khám</span>
                            </button>
                            <button type="button" onclick="moModalThemNguoiThan(${h.id})" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 rounded-xl transition" title="Chỉnh sửa hồ sơ">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function capNhatNhanModalNguoiThan() {
            const qh = document.getElementById('modal-nt-quanhe')?.value || 'CON';
            const lbl = document.getElementById('lbl-modal-nt-hoten');
            const inp = document.getElementById('modal-nt-hoten');
            const lblSdt = document.getElementById('lbl-modal-nt-sdt');
            const inpSdt = document.getElementById('modal-nt-sdt');
            if (!lbl || !inp) return;

            if (qh === 'CON') {
                lbl.innerHTML = '<span class="text-sky-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-child text-sky-600"></i> Họ Tên Bé / Con Cái: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Bé Bo, Nguyễn Gia Hân...';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại: <span class="text-[10px] text-slate-400 font-normal lowercase">(không bắt buộc đối với trẻ em)</span>';
                if (inpSdt) inpSdt.placeholder = 'Để trống nếu là trẻ em';
            } else if (qh === 'CHA_ME') {
                lbl.innerHTML = '<span class="text-amber-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-person-cane text-amber-600"></i> Họ Tên Bố / Mẹ: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Nguyễn Văn Nam, Trần Thị Mai...';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại: <span class="text-[10px] text-slate-400 font-normal lowercase">(hoặc SĐT người giám hộ)</span>';
                if (inpSdt) inpSdt.placeholder = 'Nhập SĐT của bố/mẹ...';
            } else if (qh === 'VO_CHONG') {
                lbl.innerHTML = '<span class="text-rose-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-heart text-rose-600"></i> Họ Tên Vợ / Chồng: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Lê Thị Hoa...';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại:';
                if (inpSdt) inpSdt.placeholder = 'Nhập số điện thoại...';
            } else {
                lbl.innerHTML = 'Họ và Tên Người Thân: <span class="text-rose-500">*</span>';
                inp.placeholder = 'Ví dụ: Nguyễn Gia Bảo';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại:';
                if (inpSdt) inpSdt.placeholder = 'Để trống nếu là trẻ em';
            }
        }

        function moModalThemNguoiThan(hoSoId = null) {
            const form = document.getElementById('form-them-sua-nguoi-than');
            if (form) form.reset();

            const titleEl = document.getElementById('modal-nt-title');
            const idInput = document.getElementById('modal-nt-id');

            if (hoSoId) {
                const hoSo = (CacheHoSoGiaDinh || []).find(h => h.id == hoSoId);
                if (hoSo) {
                    if (titleEl) titleEl.textContent = 'Cập Nhật Hồ Sơ Thành Viên';
                    if (idInput) idInput.value = hoSo.id;
                    document.getElementById('modal-nt-quanhe').value = hoSo.quan_he_chu_tai_khoan || 'CON';
                    document.getElementById('modal-nt-hoten').value = hoSo.ho_ten || '';
                    document.getElementById('modal-nt-ngaysinh').value = hoSo.ngay_sinh || '';
                    document.getElementById('modal-nt-gioitinh').value = hoSo.gioi_tinh || 'NAM';
                    document.getElementById('modal-nt-nhommau').value = hoSo.nhom_mau || '';
                    document.getElementById('modal-nt-sdt').value = hoSo.so_dien_thoai || '';
                    document.getElementById('modal-nt-cccd').value = hoSo.so_cccd || '';
                    document.getElementById('modal-nt-diachi').value = hoSo.dia_chi || '';
                    document.getElementById('modal-nt-diung').value = hoSo.tien_su_di_ung || '';
                    document.getElementById('modal-nt-tiensubenh').value = hoSo.tien_su_benh || '';
                    document.getElementById('modal-nt-nguoithan').value = hoSo.nguoi_lien_he_khan_cap || '';
                    document.getElementById('modal-nt-sdtnguoithan').value = hoSo.sdt_khan_cap || '';
                }
            } else {
                if (titleEl) titleEl.textContent = 'Thêm Hồ Sơ Người Thân Mới';
                if (idInput) idInput.value = '';
                document.getElementById('modal-nt-quanhe').value = 'CON';
            }

            capNhatNhanModalNguoiThan();
            moModal('modal-them-sua-nguoi-than');
        }

        async function luuHoSoNguoiThan() {
            const id = document.getElementById('modal-nt-id')?.value;
            const hoTen = document.getElementById('modal-nt-hoten')?.value.trim();
            const quanHe = document.getElementById('modal-nt-quanhe')?.value || 'CON';

            if (!hoTen) {
                Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập họ và tên của người thân!' });
                return;
            }

            const rawSdt = document.getElementById('modal-nt-sdt')?.value.trim();

            const payload = {
                ho_ten: hoTen,
                quan_he_chu_tai_khoan: quanHe,
                ngay_sinh: document.getElementById('modal-nt-ngaysinh')?.value || null,
                gioi_tinh: document.getElementById('modal-nt-gioitinh')?.value || 'NAM',
                nhom_mau: document.getElementById('modal-nt-nhommau')?.value || null,
                so_dien_thoai: rawSdt ? rawSdt : null,
                so_cccd: document.getElementById('modal-nt-cccd')?.value.trim() || null,
                dia_chi: document.getElementById('modal-nt-diachi')?.value.trim() || null,
                tien_su_di_ung: document.getElementById('modal-nt-diung')?.value.trim() || null,
                tien_su_benh: document.getElementById('modal-nt-tiensubenh')?.value.trim() || null,
                nguoi_lien_he_khan_cap: document.getElementById('modal-nt-nguoithan')?.value.trim() || null,
                sdt_khan_cap: document.getElementById('modal-nt-sdtnguoithan')?.value.trim() || null
            };

            const btn = document.getElementById('btn-submit-nt');
            if (btn) btn.disabled = true;

            try {
                let res;
                if (id) {
                    res = await goiApi('PUT', `/api/v1/benh-nhan/${id}`, payload);
                } else {
                    res = await goiApi('POST', '/api/v1/benh-nhan/nguoi-than', payload);
                }

                if (btn) btn.disabled = false;

                if (res.ok) {
                    dongModal('modal-them-sua-nguoi-than');
                    Swal.fire({
                        icon: 'success',
                        title: id ? 'Cập nhật thành công!' : 'Tạo hồ sơ thành công!',
                        text: id ? `Đã cập nhật thông tin hồ sơ ${hoTen}.` : `Hồ sơ người thân ${hoTen} đã được lưu vào gia đình.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    await taiVaRenderHoSoGiaDinh();
                } else {
                    const msg = (res.data && res.data.thong_diep) || (res.data && res.data.message) || 'Không thể lưu hồ sơ. Vui lòng kiểm tra lại.';
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
                }
            } catch (err) {
                if (btn) btn.disabled = false;
                console.error('Lỗi lưu hồ sơ người thân:', err);
                Swal.fire({ icon: 'error', title: 'Lỗi hệ thống', text: 'Có lỗi xảy ra khi lưu dữ liệu người thân.' });
            }
        }

        async function datLichNhanhChoNguoiThan(benhNhanId) {
            await moModalDatLich(null);
            if (typeof chonDoiTuongKham === 'function') {
                chonDoiTuongKham('NGUOI_THAN');
            }
            await taiDanhSachHoSoGiaDinh();
            const selectEl = document.getElementById('modal-dl-chon-ho-so');
            if (selectEl) {
                selectEl.value = benhNhanId;
                if (typeof chonHoSoGiaDinhDropdown === 'function') {
                    chonHoSoGiaDinhDropdown(benhNhanId);
                }
            }
        }

    </script>
