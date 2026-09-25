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
            <!-- Nhóm 1: TỔNG QUAN -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tổng Quan</p>
                <div class="space-y-1">
                    <button class="sidebar-item active w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-giam-sat" onclick="chuyenTab('tab-admin-giam-sat', this)">
                        <i class="fa-solid fa-chart-pie text-sm text-purple-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Thống Kê & Giám Sát</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 2: ĐẶT LỊCH & BỆNH NHÂN -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lịch Hẹn & Bệnh Nhân</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-benh-nhan-lich" onclick="chuyenTab('tab-benh-nhan-lich', this)">
                        <i class="fa-solid fa-calendar-days text-sm text-sky-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Quản Lý Lịch Khám</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-benh-nhan" onclick="chuyenTab('tab-benh-nhan', this)">
                        <i class="fa-regular fa-calendar-plus text-sm text-medical-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Tra Cứu & Đặt Lịch</span>
                    </button>
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-benh-nhan" onclick="chuyenTab('tab-admin-benh-nhan', this)">
                        <i class="fa-solid fa-hospital-user text-sm text-rose-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Quản Lý Bệnh Nhân & EHR</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 3: BÀN KHÁM BÁC SĨ -->
            <div>
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
                </div>
            </div>

            <!-- Nhóm 4: THU NGÂN & VIỆN PHÍ -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Viện Phí & Tài Chính</p>
                <div class="space-y-1">
                    <button class="sidebar-item w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition group text-left" data-tab="tab-admin-thu-ngan" onclick="chuyenTab('tab-admin-thu-ngan', this)">
                        <i class="fa-solid fa-file-invoice-dollar text-sm text-amber-400 w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Thu Ngân & Hóa Đơn</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 5: QUẢN TRỊ HỆ THỐNG -->
            <div>
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
            <div class="flex items-center space-x-2.5 px-1.5 py-1">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md flex-shrink-0">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="header-user-name" class="text-xs font-bold text-white truncate">Đang tải...</p>
                    <span id="header-user-role" class="px-2 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-sky-400 border border-slate-700 inline-block">ADMIN</span>
                </div>
            </div>
            <button onclick="xuLyDangXuatGateway()" class="w-full flex items-center justify-center space-x-2 py-2 px-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 rounded-xl text-xs font-bold border border-rose-500/20 transition">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Đăng Xuất</span>
            </button>
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

            <!-- Quick Actions -->
            <div class="flex items-center space-x-2 flex-shrink-0">
                <button onclick="location.reload()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition border border-slate-200 flex items-center gap-1.5 text-xs font-semibold px-3" title="Làm mới dữ liệu">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span class="hidden sm:inline">Làm Mới</span>
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

        <!-- TAB 1.2: LỊCH KHÁM (NGƯỜI 2) -->
        <section id="tab-benh-nhan-lich" class="portal-section space-y-6">
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
                        <button onclick="taiDanhSachLichHen()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Làm Mới</span>
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
        <!-- TAB 1.3: QUẢN LÝ BỆNH NHÂN & HỒ SƠ BỆNH ÁN ĐIỆN TỬ (EHR/EMR)  -->
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
    </div>

    <!-- ============================================================= -->
    <!-- MODALS HỆ THỐNG                                               -->
    <!-- ============================================================= -->

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
                                    <select id="modal-dl-quan-he" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-800 font-semibold">
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
                        <input type="date" id="modal-dl-ngay" onchange="taiSlotsKhaDungDatLich();" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 text-slate-800 font-medium">
                    </div>

                    <!-- BỘ CHỌN KHUNG GIỜ KHÁM THỜI GIAN THỰC (Realtime Slots - BookingCare Style) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="font-bold uppercase tracking-wider text-slate-700 text-xs">Khung Giờ Khám (Ca 30 phút):</label>
                            <span id="modal-dl-slots-status" class="text-[11px] font-bold text-sky-600 flex items-center gap-1">
                                <i class="fa-solid fa-bolt text-amber-500"></i> <span id="modal-dl-slots-count">Đang kiểm tra slot...</span>
                            </span>
                        </div>

                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2.5">
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
                        <input type="text" id="modal-kt-chuan-doan" required placeholder="Ví dụ: Viêm phế quản cấp tính, Viêm họng hạt, Rối loạn tiêu hóa..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800 font-medium">
                    </div>

                    <!-- Lời khuyên & dặn dò -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Lời Dặn Dò & Chế Độ Nghỉ Ngơi / Dinh Dưỡng:</label>
                        <textarea id="modal-kt-loi-khuyen" rows="2" placeholder="Ví dụ: Uống nhiều nước ấm, súc họng nước muối sinh lý, kiêng đồ uống lạnh, nghỉ ngơi 3 ngày..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"></textarea>
                    </div>

                    <!-- Bảng Kê Toa Thuốc Điện Tử -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="font-bold uppercase tracking-wider text-slate-700 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-pills text-purple-600"></i>
                                <span>Danh Mục Thuốc Kê Đơn (Toa Thuốc):</span>
                            </label>
                            <button type="button" onclick="themDongThuoc()" class="px-2.5 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                <i class="fa-solid fa-plus"></i> Thêm Thuốc
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="py-2 px-3">Tên Thuốc & Biệt Dược</th>
                                        <th class="py-2 px-2 w-24">Hàm Lượng</th>
                                        <th class="py-2 px-2 w-20">Số Lượng</th>
                                        <th class="py-2 px-3">Hướng Dẫn Sử Dụng</th>
                                        <th class="py-2 px-2 text-center w-10">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-ke-toa-thuoc" class="divide-y divide-slate-100 bg-white">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày Hẹn Tái Khám (Nếu có):</label>
                            <input type="date" id="modal-kt-ngay-tai-kham" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-medium">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" id="modal-kt-hoan-thanh-ngay" checked class="w-4 h-4 text-purple-600 rounded">
                                <span>Chuyển trạng thái sang "HOÀN THÀNH"</span>
                            </label>
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
                                    <span id="ehr-patient-phone"><i class="fa-solid fa-phone text-slate-400 mr-1"></i>--</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span id="ehr-chip-nhom-mau" class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                Nhóm Máu: --
                            </span>
                            <span id="ehr-chip-quan-he" class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-sky-100 text-sky-700 border border-sky-200">
                                Bản Thân
                            </span>
                        </div>
                    </div>

                    <!-- Medical Baseline & Emergency -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                        <div class="p-2.5 bg-white rounded-xl border border-slate-200/80">
                            <div class="text-slate-400 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Cảnh Báo Dị Ứng:
                            </div>
                            <div id="ehr-patient-allergy" class="font-semibold text-rose-600 mt-1">Không ghi nhận dị ứng</div>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-slate-200/80">
                            <div class="text-slate-400 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-heart-pulse text-amber-500"></i> Bệnh Lý Nền:
                            </div>
                            <div id="ehr-patient-history" class="font-semibold text-amber-700 mt-1">Không có tiền sử bệnh</div>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-slate-200/80">
                            <div class="text-slate-400 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-phone-volume text-sky-500"></i> Liên Hệ Khẩn Cấp:
                            </div>
                            <div id="ehr-patient-emergency" class="font-semibold text-slate-700 mt-1">Chưa cập nhật</div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Section Header -->
                <div class="flex items-center justify-between pt-2">
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-timeline text-rose-500"></i>
                        <span>Dòng Thời Gian Lịch Sử Khám Chữa Bệnh (<span id="ehr-timeline-count">0</span> ca khám)</span>
                    </h4>
                    <span class="text-[11px] text-slate-400">Sắp xếp từ lần khám mới nhất</span>
                </div>

                <!-- Timeline Container -->
                <div id="ehr-timeline-container" class="space-y-4">
                    <!-- Dynamic timeline items -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between flex-shrink-0">
                <button type="button" onclick="inBenhAnDienTuHienTai()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-print text-xs text-rose-400"></i>
                    <span>In Tóm Tắt Bệnh Án</span>
                </button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="datLichChoBenhNhanHienTai()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-plus"></i>
                        <span>Đặt Lịch Khám Mới</span>
                    </button>
                    <button type="button" onclick="dongModal('modal-chi-tiet-benh-nhan-ehr')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: THÊM / CẬP NHẬT HỒ SƠ BỆNH NHÂN                        -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-them-sua-benh-nhan">
        <div class="modal-box bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-2xl w-full overflow-hidden">
            <form id="form-them-sua-benh-nhan" onsubmit="event.preventDefault(); luuThongTinBenhNhan();" autocomplete="off">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-user-pen text-rose-600"></i>
                        <span id="modal-bn-title">Thêm Hồ Sơ Bệnh Nhân Mới</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-them-sua-benh-nhan')" class="text-slate-400 hover:text-slate-600 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <input type="hidden" id="form-bn-id" value="">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Họ Và Tên Bệnh Nhân: <span class="text-rose-500">*</span></label>
                            <input type="text" id="form-bn-hoten" required placeholder="Nguyễn Văn A" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Số Điện Thoại: <span class="text-rose-500">*</span></label>
                            <input type="tel" id="form-bn-sdt" required placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Giới Tính:</label>
                            <select id="form-bn-gioitinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                                <option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Ngày Sinh:</label>
                            <input type="date" id="form-bn-ngaysinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Nhóm Máu:</label>
                            <select id="form-bn-nhommau" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                                <option value="">Chưa rõ</option>
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
            await taiDanhSachBenhNhan();

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
            const role = AppState.currentUser ? AppState.currentUser.vai_tro : (localStorage.getItem('role') || 'ADMIN');
            const hoTen = AppState.currentUser ? (AppState.currentUser.ho_ten || AppState.currentUser.ten_dang_nhap) : 'Quản Trị Viên';

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

            if (document.getElementById('label-active-token')) {
                document.getElementById('label-active-token').textContent = AppState.token ? (AppState.token.substring(0, 36) + '...') : 'Chưa có Token';
            }

            // Khôi phục tab đang đứng nếu có (từ URL Hash hoặc SessionStorage khi reload trang)
            const hashTab = window.location.hash ? window.location.hash.replace('#', '') : null;
            const savedTab = hashTab || sessionStorage.getItem('current_active_tab');
            const targetSection = savedTab ? document.getElementById(savedTab) : null;

            if (targetSection) {
                chuyenTab(savedTab);
            } else {
                // Mở tab mặc định theo vai trò người đăng nhập
                if (role === 'BENH_NHAN') {
                    chuyenTab('tab-benh-nhan-lich');
                } else if (role === 'BAC_SI') {
                    chuyenTab('tab-bac-si');
                } else {
                    chuyenTab('tab-admin-giam-sat');
                }
            }
        }

        // HÀM CHUYỂN TAB TRÊN THANH MENU SIDEBAR BÊN TRÁI
        function chuyenTab(tabId, clickedBtn = null) {
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

            // Reset tệp đính kèm
            AppState.tepYTeUploads = [];
            const fileInput = document.getElementById('modal-dl-files');
            if (fileInput) fileInput.value = '';
            const preview = document.getElementById('modal-dl-file-preview');
            if (preview) preview.innerHTML = '';

            // Chặn chọn ngày quá khứ
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('modal-dl-ngay');
            if (dateInput) {
                dateInput.min = today;
                if (!dateInput.value || dateInput.value < today) {
                    dateInput.value = today;
                }
            }

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
            document.querySelectorAll('#modal-dl-slots .slot-btn').forEach(b => {
                b.classList.remove('selected', 'bg-medical-600', 'text-white', 'font-bold', 'ring-2', 'ring-medical-500');
                b.classList.add('bg-white', 'text-slate-800');
            });
            el.classList.remove('bg-white', 'text-slate-800');
            el.classList.add('selected', 'bg-medical-600', 'text-white', 'font-bold', 'ring-2', 'ring-medical-500');
            AppState.slotDaChon = { batDau: start + ':00', ketThuc: end + ':00' };

            capNhatThongBaoCamKetCaKham(start, end);
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
                           (lh.benh_nhan && lh.benh_nhan.tai_khoan_id == u.id) ||
                           (lh.ho_ten_benh_nhan && lh.ho_ten_benh_nhan.toLowerCase() === (u.ho_ten || '').toLowerCase()) ||
                           (lh.so_dien_thoai && lh.so_dien_thoai === u.so_dien_thoai);
                });
                if (displayList.length === 0) displayList = list;
            }

            if (!displayList || displayList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-slate-400">Bạn chưa có lịch hẹn nào. Hãy đặt lịch khám mới!</td></tr>';
                return;
            }

            tbody.innerHTML = displayList.map(lh => {
                const bs = AppState.danhSachBacSi.find(b => b.id == lh.bac_si_id);
                const tenBs = bs ? bs.ho_ten : (`Bác sĩ ID #${lh.bac_si_id}`);
                const tenKhoa = (bs && bs.chuyen_khoa) ? (bs.chuyen_khoa.ten_khoa || bs.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                
                const tenBn = lh.benh_nhan ? (lh.benh_nhan.ho_ten || '') : (lh.ho_ten_benh_nhan || (`Bệnh nhân #${lh.benh_nhan_id}`));
                const sdtBn = lh.benh_nhan ? (lh.benh_nhan.so_dien_thoai || '') : (lh.so_dien_thoai || '');

                const canManage = (['CHO_KHAM', 'CHO_XAC_NHAN', 'DA_XAC_NHAN', 'DA_DAT'].includes(lh.trang_thai) || !lh.trang_thai);
                
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
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-slate-900">${tenBn}</span>
                                ${lh.benh_nhan && lh.benh_nhan.quan_he_chu_tai_khoan && lh.benh_nhan.quan_he_chu_tai_khoan !== 'BAN_THAN' ? 
                                    `<span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-purple-100 text-purple-700 border border-purple-200">${lh.benh_nhan.quan_he_chu_tai_khoan === 'CON' ? 'Con cái' : (lh.benh_nhan.quan_he_chu_tai_khoan === 'CHA_ME' ? 'Bố/Mẹ' : 'Người thân')}</span>` : ''}
                            </div>
                            ${sdtBn ? `<span class="text-[11px] text-slate-400 font-mono">${sdtBn}</span>` : ''}
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
                    ${tepBtn}
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
                if (lblHoTen) lblHoTen.textContent = 'Họ tên Bệnh nhân:';

                if (AppState.currentUser) {
                    document.getElementById('modal-dl-ho-ten').value = AppState.currentUser.ho_ten || '';
                    document.getElementById('modal-dl-sdt').value = AppState.currentUser.so_dien_thoai || '';
                }
            } else {
                btnNt.className = 'px-2.5 py-1 rounded-lg bg-white text-sky-700 shadow-sm transition';
                btnBt.className = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition';
                khuVucNt.classList.remove('hidden');
                if (lblHoTen) lblHoTen.textContent = 'Họ tên Người Thân:';

                document.getElementById('modal-dl-ho-ten').value = '';
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

                    let selectedSet = false;

                    const renderSlotItem = (s) => {
                        const isAvail = s.kha_dung;
                        const isSelect = isAvail && !selectedSet;
                        if (isSelect) {
                            selectedSet = true;
                            AppState.slotDaChon = { batDau: s.bat_dau, ketThuc: s.ket_thuc };
                            capNhatThongBaoCamKetCaKham(s.bat_dau.substring(0, 5), s.ket_thuc.substring(0, 5));
                        }

                        if (isAvail) {
                            const activeClass = isSelect ? 'selected bg-medical-600 text-white font-bold ring-2 ring-medical-500' : 'bg-white hover:bg-sky-50 text-slate-800 border-slate-200 hover:border-sky-400';
                            return `
                                <button type="button" class="slot-btn py-2 px-1 rounded-xl border text-[11px] font-mono transition text-center shadow-xs ${activeClass}" onclick="chonSlot(this, '${s.bat_dau.substring(0, 5)}', '${s.ket_thuc.substring(0, 5)}')">
                                    <div class="font-extrabold">${s.bat_dau.substring(0, 5)}</div>
                                    <div class="text-[9px] opacity-75 font-sans">Trống</div>
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
            const originalContent = document.body.innerHTML;

            const printWindow = window.open('', '', 'height=700,width=900');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Phiếu Khám Bệnh & Toa Thuốc</title>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <style>
                            @media print {
                                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                            }
                        </style>
                    </head>
                    <body class="p-8 bg-white text-slate-800 font-sans text-xs">
                        ${printContent}
                    </body>
                </html>
            `);
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
            document.getElementById('ehr-badge-ma-bn').textContent = maBn;
            document.getElementById('ehr-patient-avatar').textContent = (bn.ho_ten || 'BN').substring(0, 2).toUpperCase();
            document.getElementById('ehr-patient-name').textContent = bn.ho_ten || '--';
            document.getElementById('ehr-patient-gender').textContent = bn.gioi_tinh === 'NU' ? 'Nữ' : (bn.gioi_tinh === 'NAM' ? 'Nam' : 'Khác');

            let dobStr = '--';
            if (bn.ngay_sinh) {
                const birthYear = new Date(bn.ngay_sinh).getFullYear();
                const age = new Date().getFullYear() - birthYear;
                dobStr = `${bn.ngay_sinh.split('-').reverse().join('/')} (${age} tuổi)`;
            }
            document.getElementById('ehr-patient-dob').textContent = dobStr;
            document.getElementById('ehr-patient-phone').innerHTML = `<i class="fa-solid fa-phone text-slate-400 mr-1"></i>${bn.so_dien_thoai || '--'}`;

            document.getElementById('ehr-chip-nhom-mau').textContent = bn.nhom_mau ? `Nhóm Máu: ${bn.nhom_mau}` : 'Nhóm Máu: Chưa rõ';
            document.getElementById('ehr-chip-quan-he').textContent = bn.quan_he_chu_tai_khoan === 'NGUOI_THAN' ? 'Hồ Sơ Người Thân' : 'Chủ Tài Khoản';

            // Baseline & Emergency
            const elAllergy = document.getElementById('ehr-patient-allergy');
            if (elAllergy) {
                elAllergy.textContent = bn.tien_su_di_ung ? bn.tien_su_di_ung : 'Không ghi nhận tiền sử dị ứng thuốc';
                elAllergy.className = bn.tien_su_di_ung ? 'font-bold text-rose-600 mt-1' : 'font-semibold text-slate-600 mt-1';
            }

            const elHistory = document.getElementById('ehr-patient-history');
            if (elHistory) {
                elHistory.textContent = bn.tien_su_benh ? bn.tien_su_benh : 'Không có tiền sử bệnh lý nền';
            }

            const elEmergency = document.getElementById('ehr-patient-emergency');
            if (elEmergency) {
                if (bn.nguoi_lien_he_khan_cap || bn.sdt_khan_cap) {
                    elEmergency.textContent = `${bn.nguoi_lien_he_khan_cap || 'Thân nhân'} (${bn.sdt_khan_cap || bn.so_dien_thoai})`;
                } else {
                    elEmergency.textContent = 'Chưa thiết lập người liên hệ khẩn cấp';
                }
            }

            // Timeline Items
            const timelineContainer = document.getElementById('ehr-timeline-container');
            const lichHens = (AppState.danhSachLichHen || []).filter(lh => 
                lh.benh_nhan_id == bn.id || 
                (lh.benh_nhan && lh.benh_nhan.id == bn.id) ||
                (lh.so_dien_thoai && lh.so_dien_thoai === bn.so_dien_thoai)
            );

            lichHens.sort((a, b) => new Date(b.ngay_kham + ' ' + (b.gio_bat_dau || '00:00')) - new Date(a.ngay_kham + ' ' + (a.gio_bat_dau || '00:00')));
            document.getElementById('ehr-timeline-count').textContent = lichHens.length;

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

        function datLichChoBenhNhan(benhNhanId) {
            const bn = (AppState.danhSachBenhNhan || []).find(b => b.id == benhNhanId);
            if (!bn) return;
            moModalDatLich();
            // Pre-fill fields
            const tenBn = document.getElementById('modal-dl-ten-nguoi-than');
            if (tenBn) tenBn.value = bn.ho_ten || '';
            const sdtBn = document.getElementById('modal-dl-sdt-nguoi-than');
            if (sdtBn) sdtBn.value = bn.so_dien_thoai || '';
            const cccdBn = document.getElementById('modal-dl-cccd-nguoi-than');
            if (cccdBn) cccdBn.value = bn.so_cccd || '';
            const nsBn = document.getElementById('modal-dl-ngaysinh-nguoi-than');
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

    </script>
