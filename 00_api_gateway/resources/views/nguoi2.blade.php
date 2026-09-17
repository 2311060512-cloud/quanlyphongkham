<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Khám Đa Khoa - Phân Hệ Người 2 (Bệnh Nhân & Đặt Lịch Khám)</title>

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
                            500: '#0ea5e9',
                            600: '#0284c7', // Màu xanh y tế chủ đạo
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Be Vietnam Pro', 'Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts: Be Vietnam Pro & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .tab-active {
            color: #0284c7 !important;
            border-bottom: 3px solid #0284c7 !important;
            font-weight: 700 !important;
            background-color: #f0f9ff;
        }
        .slot-btn.active {
            background-color: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }
        .table-nowrap th, .table-nowrap td {
            white-space: nowrap;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased">

    <!-- ============================================================= -->
    <!-- TOP NAVIGATION BAR -->
    <!-- ============================================================= -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="/dashboard" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-medical-700 to-sky-500 flex items-center justify-center text-white shadow-md shadow-medical-500/30 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold tracking-tight text-slate-900 block leading-tight">
                                Phòng Khám Đa Khoa
                            </span>
                            <span class="text-xs font-bold text-medical-600 uppercase tracking-wider flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Phân Hệ Bệnh Nhân & Đặt Lịch Khám
                            </span>
                        </div>
                    </a>

                    <!-- Return to Dashboard -->
                    <div class="hidden lg:flex items-center pl-6 border-l border-slate-200 space-x-2 text-xs">
                        <a href="/dashboard" class="px-3 py-1.5 text-slate-600 hover:text-medical-700 hover:bg-slate-100 rounded-xl transition font-semibold border border-slate-200 shadow-xs">
                            <i class="fa-solid fa-house mr-1 text-medical-600"></i> Trang Chủ Phòng Khám
                        </a>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div id="nav-tabs-container" class="hidden md:flex space-x-1 items-center">
                    <button onclick="switchView('view-booking')" id="nav-booking" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg transition-colors tab-active">
                        <i class="fa-solid fa-calendar-plus mr-1.5"></i> Đặt Lịch Khám
                    </button>
                    <button onclick="switchView('view-appointments')" id="nav-appointments" class="tab-btn px-4 py-2 text-sm font-semibold text-slate-600 hover:text-medical-600 rounded-lg transition-colors">
                        <i class="fa-solid fa-list-check mr-1.5"></i> Quản Lý & Điều Phối
                    </button>
                    <button onclick="switchView('view-my-history')" id="nav-my-history" class="tab-btn px-4 py-2 text-sm font-semibold text-slate-600 hover:text-medical-600 rounded-lg transition-colors">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Lịch Sử Khám
                    </button>
                </div>

                <!-- User Session & Auth Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Guest View -->
                    <div id="guest-nav-actions" class="flex items-center space-x-2">
                        <button onclick="openAdminLoginModal()" class="px-3.5 py-1.5 text-sm font-semibold text-medical-700 bg-medical-50 hover:bg-medical-100 rounded-lg transition border border-medical-200 inline-flex items-center">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Đăng Nhập ADMIN
                        </button>
                    </div>

                    <!-- Logged-in User Profile -->
                    <div id="user-nav-actions" class="hidden flex items-center space-x-3">
                        <div class="flex items-center space-x-2 text-sm bg-slate-100 py-1.5 px-3 rounded-lg border border-slate-200">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-medical-600 to-sky-400 text-white flex items-center justify-center font-bold text-xs shadow" id="user-avatar-text">
                                AD
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-xs block leading-tight" id="user-display-name">Quản Trị Viên</span>
                                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-medical-100 text-medical-800" id="user-display-role">ADMIN</span>
                            </div>
                        </div>

                        <button onclick="handleLogout()" title="Đăng Xuất" class="p-2 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Tabs -->
        <div id="nav-m-tabs-container" class="flex md:hidden border-t border-slate-200 bg-slate-50 px-2 py-1 justify-around">
            <button onclick="switchView('view-booking')" id="nav-m-booking" class="px-3 py-1.5 text-xs font-bold text-medical-600">
                <i class="fa-solid fa-calendar-plus block text-center text-sm mb-0.5"></i> Đặt Lịch
            </button>
            <button onclick="switchView('view-appointments')" id="nav-m-appointments" class="px-3 py-1.5 text-xs font-semibold text-slate-600">
                <i class="fa-solid fa-list-check block text-center text-sm mb-0.5"></i> Điều Phối
            </button>
            <button onclick="switchView('view-my-history')" id="nav-m-my-history" class="px-3 py-1.5 text-xs font-semibold text-slate-600">
                <i class="fa-solid fa-clock-rotate-left block text-center text-sm mb-0.5"></i> Lịch Sử
            </button>
        </div>
    </nav>

    <!-- ============================================================= -->
    <!-- MAIN CONTAINER -->
    <!-- ============================================================= -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- CẢNH BÁO YÊU CẦU ĐĂNG NHẬP ADMIN -->
        <div id="admin-required-gate" class="hidden my-12 flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-3xl border border-amber-200 shadow-xl p-8 text-center relative overflow-hidden">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h2 class="text-xl font-black text-slate-900 mb-2">Yêu Cầu Quyền Quản Trị Viên (ADMIN)</h2>
                <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                    Phân hệ này yêu cầu đăng nhập bằng tài khoản <strong class="text-amber-600 font-bold">ADMIN</strong> để xem và thao tác dữ liệu. Vui lòng đăng nhập tài khoản ADMIN để tiếp tục.
                </p>
                <div class="bg-slate-50 rounded-2xl p-4 mb-6 text-xs text-slate-600 border border-slate-200 text-left space-y-2">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5 pb-1 border-b border-slate-200">
                        <i class="fa-solid fa-key text-amber-500"></i> Tài khoản ADMIN mặc định:
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Tài khoản:</span>
                        <code class="font-mono font-bold text-slate-800 bg-white px-2 py-0.5 rounded border border-slate-200">admin</code>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Mật khẩu:</span>
                        <code class="font-mono font-bold text-slate-800 bg-white px-2 py-0.5 rounded border border-slate-200">Admin@123</code>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="openAdminLoginModal()" class="flex-1 py-3 px-4 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-medical-600/30 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập ADMIN
                    </button>
                    <a href="/dashboard" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-house"></i> Về Trang Chủ
                    </a>
                </div>
            </div>
        </div>

        <!-- VÙNG NỘI DUNG PHÂN HỆ (CHỈ HIỂN THỊ KHI ĐÃ ĐĂNG NHẬP ADMIN) -->
        <div id="nguoi2-content">
            <!-- ============================================================= -->
            <!-- TAB 1: FORM ĐẶT LỊCH KHÁM BỆNH TRỰC TUYẾN -->
            <!-- ============================================================= -->
            <section id="view-booking" class="app-view">
            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-medical-800 via-medical-700 to-sky-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-medical-900/15 mb-8 relative overflow-hidden">
                <div class="absolute -right-8 -bottom-10 opacity-10 text-9xl pointer-events-none">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <div class="max-w-3xl relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider mb-3 border border-white/20">
                        <i class="fa-solid fa-shield-heart text-sky-300"></i> Phân Hệ 02 • Đặt Lịch Y Tế Thông Minh
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight mb-2">
                        Đặt Lịch Khám Chuyên Khoa Trực Tuyến
                    </h2>
                    <p class="text-sky-100 text-sm sm:text-base leading-relaxed">
                        Chủ động chọn Bác sĩ, Ngày khám và Khung giờ phù hợp. Tích hợp thuật toán chống trùng ca khám 30 phút và hồ sơ bệnh án điện tử đồng bộ tức thì.
                    </p>
                </div>
            </div>

            <!-- Auth Warning Alert (if not logged in) -->
            <div id="booking-guest-alert" class="hidden mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start space-x-3 text-amber-800">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xl mt-0.5"></i>
                <div class="flex-1 text-sm">
                    <span class="font-bold">Yêu cầu xác thực tài khoản (Auth Gate):</span>
                    Bệnh nhân bắt buộc phải đăng nhập tài khoản trước khi gửi yêu cầu đặt lịch khám theo quy chuẩn bảo mật y tế.
                    <a href="/dang-nhap" class="ml-2 font-bold underline hover:text-amber-900">Đăng nhập ngay</a> hoặc đăng ký tài khoản mới.
                </div>
            </div>

            <!-- Main Booking Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form id="form-dat-lich" onsubmit="handleDatLich(event)">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            <!-- CỘT TRÁI: THÔNG TIN BÁC SĨ & LỊCH KHÁM (7 Cột) -->
                            <div class="lg:col-span-7 space-y-6">
                                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <span class="w-7 h-7 rounded-lg bg-medical-50 text-medical-600 flex items-center justify-center text-sm">1</span>
                                    Thông Tin Bác Sĩ & Thời Gian Khám
                                </h3>

                                <!-- Chọn Bác Sĩ -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">
                                        Bác Sĩ Phụ Trách <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="booking-bac-si-id" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                                            <option value="">-- Đang tải danh sách bác sĩ... --</option>
                                        </select>
                                        <i class="fa-solid fa-user-doctor absolute left-3.5 top-3.5 text-slate-400"></i>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1" id="booking-bac-si-info">
                                        Chọn bác sĩ để hiển thị chuyên khoa và giá khám niêm yết.
                                    </p>
                                </div>

                                <!-- Chọn Ngày Khám -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">
                                        Ngày Khám Bệnh <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="date" id="booking-ngay-kham" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                                        <i class="fa-solid fa-calendar-day absolute left-3.5 top-3.5 text-slate-400"></i>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">Chỉ được chọn từ ngày hôm nay trở đi (chống đặt ngày trong quá khứ).</p>
                                </div>

                                <!-- Khung Giờ Khám Trực Quan (Ca 30 phút) -->
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-xs font-bold uppercase text-slate-600">
                                            Khung Giờ Khám (Ca 30 Phút) <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-xs text-medical-600 font-semibold" id="selected-slot-display">Chưa chọn khung giờ</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5" id="time-slots-grid">
                                        <!-- Sáng -->
                                        <button type="button" onclick="selectSlot('08:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="08:00:00">
                                            08:00 - 08:30
                                        </button>
                                        <button type="button" onclick="selectSlot('08:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="08:30:00">
                                            08:30 - 09:00
                                        </button>
                                        <button type="button" onclick="selectSlot('09:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="09:00:00">
                                            09:00 - 09:30
                                        </button>
                                        <button type="button" onclick="selectSlot('09:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="09:30:00">
                                            09:30 - 10:00
                                        </button>
                                        <button type="button" onclick="selectSlot('10:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="10:00:00">
                                            10:00 - 10:30
                                        </button>
                                        <button type="button" onclick="selectSlot('10:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="10:30:00">
                                            10:30 - 11:00
                                        </button>

                                        <!-- Chiều -->
                                        <button type="button" onclick="selectSlot('13:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="13:30:00">
                                            13:30 - 14:00
                                        </button>
                                        <button type="button" onclick="selectSlot('14:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="14:00:00">
                                            14:00 - 14:30
                                        </button>
                                        <button type="button" onclick="selectSlot('14:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="14:30:00">
                                            14:30 - 15:00
                                        </button>
                                        <button type="button" onclick="selectSlot('15:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="15:00:00">
                                            15:00 - 15:30
                                        </button>
                                        <button type="button" onclick="selectSlot('15:30:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="15:30:00">
                                            15:30 - 16:00
                                        </button>
                                        <button type="button" onclick="selectSlot('16:00:00')" class="slot-btn py-2 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-medical-50 hover:border-medical-300 transition text-center" data-time="16:00:00">
                                            16:00 - 16:30
                                        </button>
                                    </div>
                                    <input type="hidden" id="booking-gio-bat-dau" required>
                                </div>

                                <!-- Triệu Chứng / Lý Do Khám -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">
                                        Triệu Chứng & Lý Do Khám Ban Đầu
                                    </label>
                                    <textarea id="booking-ly-do-kham" rows="3" placeholder="Mô tả triệu chứng bệnh, khó chịu vùng nào, thời gian xuất hiện (ví dụ: sốt nhẹ 2 ngày, ho có đờm, đau họng...)" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition"></textarea>
                                </div>
                            </div>

                            <!-- CỘT PHẢI: THÔNG TIN BỆNH NHÂN & HỒ SƠ BỆNH ÁN ĐIỆN TỬ (5 Cột) -->
                            <div class="lg:col-span-5 space-y-5 bg-slate-50/70 p-5 sm:p-6 rounded-2xl border border-slate-200">
                                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-3">
                                    <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-sm">2</span>
                                    Hồ Sơ Y Tế Bệnh Nhân
                                </h3>

                                <!-- Họ Tên Bệnh Nhân -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1.5">
                                        Họ Và Tên <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="booking-ho-ten" required placeholder="Nguyễn Văn A" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                                </div>

                                <!-- Số Điện Thoại & CCCD -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1.5">
                                            Số Điện Thoại <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="tel" id="booking-sdt" required placeholder="09xxxxxxxx" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1.5">
                                            Số CCCD / CMND
                                        </label>
                                        <input type="text" id="booking-cccd" placeholder="079xxxxxxxx" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500 transition">
                                    </div>
                                </div>

                                <!-- Ngày Sinh & Giới Tính -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1.5">
                                            Ngày Sinh
                                        </label>
                                        <input type="date" id="booking-ngay-sinh" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1.5">
                                            Giới Tính
                                        </label>
                                        <select id="booking-gioi-tinh" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-medical-500 transition">
                                            <option value="NAM">Nam</option>
                                            <option value="NU">Nữ</option>
                                            <option value="KHAC">Khác</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ACCORDION: HỒ SƠ BỆNH ÁN ĐIỆN TỬ MỞ RỘNG -->
                                <div class="border border-sky-200 rounded-xl bg-sky-50/50 overflow-hidden transition">
                                    <button type="button" onclick="toggleAccordion('ehr-accordion')" class="w-full px-4 py-3 text-left font-bold text-xs text-sky-900 flex justify-between items-center hover:bg-sky-100/60 transition">
                                        <span class="flex items-center gap-2">
                                            <i class="fa-solid fa-notes-medical text-sky-600"></i>
                                            Bệnh Án Điện Tử Mở Rộng (Khuyến Nghị)
                                        </span>
                                        <i id="ehr-arrow" class="fa-solid fa-chevron-down text-sky-600 transition-transform"></i>
                                    </button>

                                    <div id="ehr-accordion" class="hidden p-4 pt-1 space-y-3 bg-white border-t border-sky-100">
                                        <div>
                                            <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                                Nhóm Máu
                                            </label>
                                            <select id="booking-nhom-mau" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:bg-white">
                                                <option value="">-- Chọn nhóm máu --</option>
                                                <option value="A">Nhóm máu A</option>
                                                <option value="B">Nhóm máu B</option>
                                                <option value="AB">Nhóm máu AB</option>
                                                <option value="O">Nhóm máu O</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                                Tiền Sử Dị Ứng Thuốc / Thức Ăn
                                            </label>
                                            <textarea id="booking-tien-su-di-ung" rows="2" placeholder="Ghi rõ: dị ứng Penicillin, Paracetamol, hải sản..." class="w-full p-2 text-xs bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:bg-white"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                                Tiền Sử Bệnh Lý Nền
                                            </label>
                                            <textarea id="booking-tien-su-benh" rows="2" placeholder="Ví dụ: Tim mạch, tiểu đường tuýp 2, huyết áp cao, viêm gan..." class="w-full p-2 text-xs bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:bg-white"></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                                            <div>
                                                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                                    Người Thân Khẩn Cấp
                                                </label>
                                                <input type="text" id="booking-nguoi-than" placeholder="Họ tên người thân" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">
                                                    SĐT Khẩn Cấp
                                                </label>
                                                <input type="tel" id="booking-sdt-khan-cap" placeholder="09xxxxxxxx" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nút Submit -->
                                <button type="submit" id="btn-submit-booking" class="w-full py-3.5 px-6 bg-gradient-to-r from-medical-600 to-sky-600 hover:from-medical-700 hover:to-sky-700 text-white font-extrabold rounded-xl shadow-md shadow-medical-600/30 flex items-center justify-center gap-2 text-sm tracking-wide transition transform active:scale-95">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <span>Xác Nhận Đặt Lịch Khám</span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 2: QUẢN LÝ & ĐIỀU PHỐI LỊCH HẸN (BÁC SĨ & QUẢN TRỊ) -->
        <!-- ============================================================= -->
        <section id="view-appointments" class="app-view hidden">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <i class="fa-solid fa-list-check text-medical-600"></i>
                        Điều Phối & Quản Lý Lịch Hẹn Khám
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Theo dõi danh sách ca khám, xác nhận lịch hẹn, chuyển trạng thái và cập nhật hồ sơ chẩn đoán bệnh án.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="taiDanhSachLichHen()" class="px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition">
                        <i class="fa-solid fa-rotate-right text-slate-400"></i> Làm Mới
                    </button>
                    <button onclick="switchView('view-booking')" class="px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition">
                        <i class="fa-solid fa-plus"></i> Đặt Lịch Mới
                    </button>
                </div>
            </div>

            <!-- THANH BỘ LỌC 4 Ô ĐỒNG BỘ CHIỀU CAO (py-2) -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    
                    <!-- Ô 1: Tìm kiếm từ khóa -->
                    <div class="relative">
                        <input type="text" id="filter-keyword" oninput="debounceFilter()" placeholder="Tìm mã LK, tên, SĐT, CCCD..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Ô 2: Lọc theo Trạng thái -->
                    <div class="relative">
                        <select id="filter-status" onchange="taiDanhSachLichHen()" class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="CHO_XAC_NHAN">⏳ Chờ xác nhận</option>
                            <option value="DA_XAC_NHAN">🩺 Đã xác nhận</option>
                            <option value="DANG_KHAM">🟣 Đang khám</option>
                            <option value="HOAN_THANH">🏆 Hoàn thành</option>
                            <option value="DA_HUY">❌ Đã hủy</option>
                        </select>
                        <i class="fa-solid fa-filter absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Ô 3: Lọc theo Mốc thời gian -->
                    <div class="relative">
                        <select id="filter-timeline" onchange="handleTimelineChange()" class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                            <option value="">-- Tất cả thời gian --</option>
                            <option value="hom_nay">Hôm nay</option>
                            <option value="tuan_nay">Tuần này</option>
                            <option value="thang_nay">Tháng này</option>
                        </select>
                        <i class="fa-solid fa-clock absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Ô 4: Chọn ngày cụ thể -->
                    <div class="relative">
                        <input type="date" id="filter-date" onchange="handleDateChange()" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition">
                        <i class="fa-solid fa-calendar-days absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                </div>
            </div>

            <!-- BẢNG DANH SÁCH LỊCH HẸN (TABLE NO-WRAP) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                                <th class="py-3.5 px-4">Mã Lịch Hẹn</th>
                                <th class="py-3.5 px-4">Bệnh Nhân</th>
                                <th class="py-3.5 px-4">Bác Sĩ</th>
                                <th class="py-3.5 px-4">Thời Gian Khám</th>
                                <th class="py-3.5 px-4">Trạng Thái</th>
                                <th class="py-3.5 px-4 text-center">Thao Tác Điều Phối</th>
                            </tr>
                        </thead>
                        <tbody id="table-lich-hen-body" class="divide-y divide-slate-100 text-xs">
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-medical-500 mb-2"></i>
                                    <p>Đang tải danh sách lịch khám...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary -->
                <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
                    <div>
                        Tổng cộng: <strong id="total-appointments-count" class="text-slate-800">0</strong> lịch hẹn
                    </div>
                    <div class="flex items-center gap-4 text-[11px]">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Chờ xác nhận</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Đã duyệt</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Đang khám</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Hoàn thành</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 3: LỊCH SỬ KHÁM CỦA TÔI (BỆNH NHÂN) -->
        <!-- ============================================================= -->
        <section id="view-my-history" class="app-view hidden">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fa-solid fa-clock-rotate-left text-medical-600"></i>
                    Lịch Sử Khám Bệnh Của Tôi
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Theo dõi toàn bộ lịch hẹn khám và kết luận chẩn đoán y khoa của bạn.
                </p>
            </div>

            <div id="my-history-container" class="space-y-4">
                <!-- Sẽ render động qua Javascript -->
            </div>
        </section>
        </div> <!-- Kết thúc #nguoi2-content -->

    </main>

    <!-- ============================================================= -->
    <!-- MODAL 1: XEM HỒ SƠ BỆNH ÁN ĐIỆN TỬ CHI TIẾT -->
    <!-- ============================================================= -->
    <div id="modal-patient-record" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-base">
                        <i class="fa-solid fa-file-medical"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Hồ Sơ Bệnh Án Điện Tử</h4>
                        <p class="text-xs text-slate-400" id="modal-ehr-patient-name">Mã BN: BN0001</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-patient-record')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs" id="modal-ehr-content">
                <!-- Dynamic Content -->
            </div>

            <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-patient-record')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 2: XEM TRƯỚC THÔNG BÁO KÉP (EMAIL HTML + SMS BRANDNAME) -->
    <!-- ============================================================= -->
    <div id="modal-preview-notification" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Xem Trước Thông Báo Lịch Hẹn</h4>
                        <p class="text-xs text-slate-400">Mô phỏng Email HTML tự động & tin nhắn SMS Brandname</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-preview-notification')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Tab Switcher -->
            <div class="flex border-b border-slate-200 mb-4">
                <button onclick="switchNotificationTab('tab-email')" id="btn-tab-email" class="px-4 py-2 text-xs font-bold text-medical-600 border-b-2 border-medical-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-envelope"></i> Thư Điện Tử (Email)
                </button>
                <button onclick="switchNotificationTab('tab-sms')" id="btn-tab-sms" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-comment-sms"></i> Tin Nhắn SMS Brandname
                </button>
            </div>

            <!-- Content Container -->
            <div class="flex-1 overflow-y-auto pr-1">
                <!-- Tab Email -->
                <div id="tab-email" class="space-y-2">
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200 text-xs text-slate-600">
                        Tiêu đề: <strong id="preview-email-title" class="text-slate-900">...</strong>
                    </div>
                    <div id="preview-email-body" class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <!-- HTML Email -->
                    </div>
                </div>

                <!-- Tab SMS -->
                <div id="tab-sms" class="hidden flex flex-col items-center justify-center py-6">
                    <div class="w-72 bg-slate-900 rounded-3xl p-4 shadow-2xl border-4 border-slate-800 text-white">
                        <div class="w-16 h-1 bg-slate-700 rounded-full mx-auto mb-3"></div>
                        <div class="text-[10px] text-center text-slate-400 mb-2">Tin nhắn từ <strong>PHONGKHAM</strong></div>
                        <div class="bg-sky-700 text-white p-3.5 rounded-2xl rounded-tr-none text-xs leading-relaxed shadow" id="preview-sms-text">
                            Đang tải tin nhắn...
                        </div>
                        <div class="text-[9px] text-right text-slate-400 mt-1">Hôm nay</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-preview-notification')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 3: BÁC SĨ HOÀN THÀNH CA KHÁM & GHI CHẨN ĐOÁN -->
    <!-- ============================================================= -->
    <div id="modal-complete-exam" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Hoàn Thành Ca Khám Bệnh</h4>
                        <p class="text-xs text-slate-400" id="complete-modal-subtitle">Mã LK: LK0001</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-complete-exam')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="form-complete-exam" onsubmit="handleHoanThanhKham(event)" class="space-y-4 text-xs">
                <input type="hidden" id="complete-appointment-id">

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">
                        Kết Luận Chẩn Đoán <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="complete-chuan-doan" required rows="3" placeholder="Nhập kết luận chẩn đoán y khoa (ví dụ: Viêm mũi họng cấp tính, trào ngược dạ dày thực quản...)" class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">
                        Đơn Thuốc & Lời Dặn Bác Sĩ
                    </label>
                    <textarea id="complete-loi-khuyen" rows="3" placeholder="Hướng dẫn dùng thuốc, chế độ ăn uống, hẹn ngày tái khám..." class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-medical-500 transition"></textarea>
                </div>

                <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-complete-exam')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                        Hủy
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition">
                        <i class="fa-solid fa-check mr-1"></i> Lưu & Hoàn Tất Khám
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 4: HỦY LỊCH HẸN -->
    <!-- ============================================================= -->
    <div id="modal-cancel-appointment" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-base">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Hủy Lịch Hẹn Khám</h4>
                        <p class="text-xs text-slate-400" id="cancel-modal-subtitle">Mã LK: LK0001</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-cancel-appointment')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="form-cancel-appointment" onsubmit="handleXacNhanHuyLich(event)" class="space-y-4 text-xs">
                <input type="hidden" id="cancel-appointment-id">

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">
                        Lý Do Hủy Lịch Khám <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="cancel-ly-do" required rows="3" placeholder="Nhập lý do hủy (ví dụ: Bệnh nhân bận đột xuất, chuyển sang ngày khác...)" class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-rose-500 transition"></textarea>
                </div>

                <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-cancel-appointment')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                        Bỏ qua
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-rose-600/20 transition">
                        <i class="fa-solid fa-xmark mr-1"></i> Xác Nhận Hủy Lịch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 5: ĐĂNG NHẬP ADMIN -->
    <!-- ============================================================= -->
    <div id="modal-login" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Đăng Nhập ADMIN</h3>
                    <p class="text-xs text-slate-500">Đăng nhập tài khoản Quản trị viên để truy cập phân hệ.</p>
                </div>
                <button onclick="closeModal('modal-login')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="handleLogin(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tên Đăng Nhập / Email</label>
                    <input type="text" id="login-username" required placeholder="admin..." value="admin" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Mật Khẩu</label>
                    <input type="password" id="login-password" required placeholder="Nhập mật khẩu..." value="Admin@123" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <!-- Gợi ý tài khoản demo nhanh -->
                <div class="bg-medical-50/70 p-3 rounded-xl border border-medical-100 text-xs">
                    <div class="font-bold text-medical-800 mb-1 flex items-center">
                        <i class="fa-solid fa-lightbulb mr-1.5 text-amber-500"></i> Tài khoản Quản trị viên mẫu:
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Tài khoản: <strong class="text-slate-800 font-mono">admin</strong></span>
                        <span>Mật khẩu: <strong class="text-slate-800 font-mono">Admin@123</strong></span>
                    </div>
                </div>

                <button type="submit" id="btn-login-submit" class="w-full py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md shadow-medical-600/20 transition">
                    Đăng Nhập ADMIN
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- FOOTER -->
    <!-- ============================================================= -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p class="font-bold text-slate-700">Hệ Thống Quản Lý Phòng Khám Đa Khoa • Microservice 02: Bệnh Nhân & Đặt Lịch Khám</p>
            <p class="mt-1 text-slate-400">Kiến trúc Microservices chuẩn Laravel 12 • Port 8002 • API Gateway 8000</p>
        </div>
    </footer>

    <!-- ============================================================= -->
    <!-- JAVASCRIPT XỬ LÝ TOÀN DIỆN FRONTEND -->
    <!-- ============================================================= -->
    <script>
        // Cấu hình URL Gateway
        const GATEWAY_URL = 'http://127.0.0.1:8000';
        let danhSachBacSi = [];
        let danhSachLichHen = [];
        let currentUser = null;
        let debounceTimer = null;

        // Khởi tạo trang
        document.addEventListener('DOMContentLoaded', async () => {
            // Thiết lập ngày tối thiểu cho input chọn ngày (min = today)
            const today = new Date().toISOString().split('T')[0];
            const bookingDateEl = document.getElementById('booking-ngay-kham');
            if (bookingDateEl) {
                bookingDateEl.min = today;
                bookingDateEl.value = today;
            }

            // Kiểm tra trạng thái đăng nhập & kiểm soát Gate
            kiemTraTrangThaiDangNhap();
            updateAccessGate();

            // Chỉ tải dữ liệu nếu là ADMIN
            if (isUserAdmin()) {
                await taiDanhSachBacSi();
                await taiDanhSachLichHen();
            }
        });

        function isUserAdmin() {
            const role = (currentUser && currentUser.vai_tro) 
                || sessionStorage.getItem('role') 
                || localStorage.getItem('role');
            const token = sessionStorage.getItem('token') 
                || localStorage.getItem('token') 
                || localStorage.getItem('jwt_token');
            return !!(token && role && role.toUpperCase() === 'ADMIN');
        }

        function updateAccessGate() {
            const isAdm = isUserAdmin();
            const gate = document.getElementById('admin-required-gate');
            const content = document.getElementById('nguoi2-content');
            const navTabs = document.getElementById('nav-tabs-container');
            const navMTabs = document.getElementById('nav-m-tabs-container');

            if (isAdm) {
                if (gate) gate.classList.add('hidden');
                if (content) content.classList.remove('hidden');
                if (navTabs) navTabs.classList.remove('opacity-30', 'pointer-events-none');
                if (navMTabs) navMTabs.classList.remove('opacity-30', 'pointer-events-none');
            } else {
                if (gate) gate.classList.remove('hidden');
                if (content) content.classList.add('hidden');
                if (navTabs) navTabs.classList.add('opacity-30', 'pointer-events-none');
                if (navMTabs) navMTabs.classList.add('opacity-30', 'pointer-events-none');
            }
        }

        function openAdminLoginModal() {
            const u = document.getElementById('login-username');
            const p = document.getElementById('login-password');
            if (u) u.value = 'admin';
            if (p) p.value = 'Admin@123';
            openModal('modal-login');
        }

        // 1. Quản lý trạng thái xác thực (Auth Gate)
        function kiemTraTrangThaiDangNhap() {
            const token = sessionStorage.getItem('token') || localStorage.getItem('token') || localStorage.getItem('jwt_token');
            const userData = sessionStorage.getItem('user_info') || localStorage.getItem('user_info') || localStorage.getItem('nguoi_dung');

            if (token && userData) {
                try {
                    currentUser = JSON.parse(userData);
                    document.getElementById('guest-nav-actions').classList.add('hidden');
                    document.getElementById('user-nav-actions').classList.remove('hidden');

                    document.getElementById('user-display-name').textContent = currentUser.ho_ten || 'Quản Trị Viên';
                    document.getElementById('user-display-role').textContent = currentUser.vai_tro || 'ADMIN';
                    
                    const initials = (currentUser.ho_ten || 'AD').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    document.getElementById('user-avatar-text').textContent = initials;

                    // Tự động điền thông tin vào form đặt lịch
                    if (document.getElementById('booking-ho-ten')) document.getElementById('booking-ho-ten').value = currentUser.ho_ten || '';
                    if (document.getElementById('booking-sdt')) document.getElementById('booking-sdt').value = currentUser.so_dien_thoai || '';
                    if (currentUser.so_cccd && document.getElementById('booking-cccd')) document.getElementById('booking-cccd').value = currentUser.so_cccd;
                    if (currentUser.ngay_sinh && document.getElementById('booking-ngay-sinh')) document.getElementById('booking-ngay-sinh').value = currentUser.ngay_sinh;
                    if (currentUser.gioi_tinh && document.getElementById('booking-gioi-tinh')) document.getElementById('booking-gioi-tinh').value = currentUser.gioi_tinh;

                    taiHoSoBenhAnDienTu();
                } catch (e) {
                    console.error("Lỗi đọc dữ liệu người dùng:", e);
                }
            } else {
                document.getElementById('guest-nav-actions').classList.remove('hidden');
                document.getElementById('user-nav-actions').classList.add('hidden');
            }
        }

        async function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('login-username').value.trim();
            const password = document.getElementById('login-password').value;

            Swal.fire({
                title: 'Đang xác thực...',
                text: 'Vui lòng chờ giây lát',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch(`${GATEWAY_URL}/api/xac-thuc/dang-nhap`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ten_dang_nhap: username, mat_khau: password })
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    const data = json.du_lieu;

                    if (data.vai_tro !== 'ADMIN') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Yêu Cầu Quyền ADMIN',
                            text: `Tài khoản ${data.nguoi_dung.ho_ten} (${data.vai_tro}) không phải là Quản Trị Viên (ADMIN). Vui lòng đăng nhập bằng tài khoản Quản Trị Viên để truy cập phân hệ này!`
                        });
                        return;
                    }

                    currentUser = data.nguoi_dung;
                    sessionStorage.setItem('token', data.token);
                    sessionStorage.setItem('user_info', JSON.stringify(data.nguoi_dung));
                    sessionStorage.setItem('role', data.vai_tro);
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('jwt_token', data.token);
                    localStorage.setItem('user_info', JSON.stringify(data.nguoi_dung));
                    localStorage.setItem('nguoi_dung', JSON.stringify(data.nguoi_dung));
                    localStorage.setItem('role', data.vai_tro);

                    kiemTraTrangThaiDangNhap();
                    updateAccessGate();
                    closeModal('modal-login');

                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng nhập ADMIN thành công!',
                        text: `Xin chào Quản Trị Viên: ${data.nguoi_dung.ho_ten}`,
                        timer: 1600,
                        showConfirmButton: false
                    });

                    await taiDanhSachBacSi();
                    await taiDanhSachLichHen();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng nhập thất bại',
                        text: json.thong_diep || 'Tên đăng nhập hoặc mật khẩu không chính xác.'
                    });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi máy chủ', text: 'Không thể kết nối đến API Gateway.' });
            }
        }

        async function taiHoSoBenhAnDienTu() {
            const token = sessionStorage.getItem('token') || localStorage.getItem('token') || localStorage.getItem('jwt_token');
            if (!token) return;

            try {
                const res = await fetch(`${GATEWAY_URL}/api/benh-nhan/ho-so-cua-toi`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const data = await res.json();
                if (data.thanh_cong && data.du_lieu) {
                    const bn = data.du_lieu;
                    if (bn.nhom_mau && document.getElementById('booking-nhom-mau')) document.getElementById('booking-nhom-mau').value = bn.nhom_mau;
                    if (bn.tien_su_di_ung && document.getElementById('booking-tien-su-di-ung')) document.getElementById('booking-tien-su-di-ung').value = bn.tien_su_di_ung;
                    if (bn.tien_su_benh && document.getElementById('booking-tien-su-benh')) document.getElementById('booking-tien-su-benh').value = bn.tien_su_benh;
                    if (bn.nguoi_lien_he_khan_cap && document.getElementById('booking-nguoi-than')) document.getElementById('booking-nguoi-than').value = bn.nguoi_lien_he_khan_cap;
                    if (bn.sdt_khan_cap && document.getElementById('booking-sdt-khan-cap')) document.getElementById('booking-sdt-khan-cap').value = bn.sdt_khan_cap;
                }
            } catch (e) {
                console.log("Chưa có hồ sơ bệnh án mở rộng sẵn.");
            }
        }

        function handleLogout() {
            Swal.fire({
                title: 'Đăng xuất tài khoản?',
                text: 'Bạn sẽ cần đăng nhập lại tài khoản ADMIN để truy cập phân hệ.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#e11d48',
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.clear();
                    localStorage.clear();
                    window.location.reload();
                }
            });
        }

        // 2. Chuyển đổi màn hình Tab
        function switchView(viewId) {
            document.querySelectorAll('.app-view').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('tab-active'));

            const targetView = document.getElementById(viewId);
            if (targetView) targetView.classList.remove('hidden');

            const tabMap = {
                'view-booking': 'nav-booking',
                'view-appointments': 'nav-appointments',
                'view-my-history': 'nav-my-history'
            };
            const activeTabBtn = document.getElementById(tabMap[viewId]);
            if (activeTabBtn) activeTabBtn.classList.add('tab-active');

            if (viewId === 'view-appointments') {
                taiDanhSachLichHen();
            } else if (viewId === 'view-my-history') {
                taiLichSuCuaToi();
            }
        }

        // 3. Tải danh mục Bác Sĩ từ Service 01 qua Gateway
        async function taiDanhSachBacSi() {
            const selectEl = document.getElementById('booking-bac-si-id');
            try {
                const res = await fetch(`${GATEWAY_URL}/api/bac-si`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.thanh_cong && Array.isArray(data.du_lieu)) {
                    danhSachBacSi = data.du_lieu;
                    selectEl.innerHTML = '<option value="">-- Chọn bác sĩ phụ trách khám --</option>';
                    danhSachBacSi.forEach(b => {
                        const tenBs = b.tai_khoan ? b.tai_khoan.ho_ten : ('Bác sĩ #' + b.id);
                        const khoa = b.chuyen_khoa ? b.chuyen_khoa.ten_chuyen_khoa : 'Đa khoa';
                        const gia = b.gia_kham ? (new Intl.NumberFormat('vi-VN').format(b.gia_kham) + 'đ') : '200,000đ';
                        selectEl.innerHTML += `<option value="${b.id}">${tenBs} • ${khoa} (${gia})</option>`;
                    });
                } else {
                    selectEl.innerHTML = '<option value="1">BS. Nguyễn Văn An • Tai Mũi Họng (200,000đ)</option><option value="2">ThS.BS. Trần Thị Bình • Tim Mạch (300,000đ)</option>';
                }
            } catch (e) {
                console.warn("Không thể tải bác sĩ từ API, dùng danh sách dự phòng:", e);
                selectEl.innerHTML = '<option value="1">BS. Nguyễn Văn An • Tai Mũi Họng (200,000đ)</option><option value="2">ThS.BS. Trần Thị Bình • Tim Mạch (300,000đ)</option>';
            }
        }

        // 4. Chọn khung giờ khám (Slot 30 phút)
        function selectSlot(timeStr) {
            document.querySelectorAll('.slot-btn').forEach(btn => btn.classList.remove('active'));
            const targetBtn = document.querySelector(`.slot-btn[data-time="${timeStr}"]`);
            if (targetBtn) {
                targetBtn.classList.add('active');
                document.getElementById('booking-gio-bat-dau').value = timeStr;
                document.getElementById('selected-slot-display').textContent = 'Đã chọn: ' + timeStr.substring(0, 5);
            }
        }

        function toggleAccordion(id) {
            const acc = document.getElementById(id);
            const arrow = document.getElementById('ehr-arrow');
            if (acc.classList.contains('hidden')) {
                acc.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                acc.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // 5. Xử lý gửi Form Đặt Lịch Khám (API Service 02)
        async function handleDatLich(e) {
            e.preventDefault();

            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Yêu cầu đăng nhập!',
                    text: 'Bệnh nhân bắt buộc phải đăng nhập tài khoản trước khi thực hiện đặt lịch khám bệnh.',
                    confirmButtonText: 'Đến trang đăng nhập',
                    confirmButtonColor: '#0284c7',
                }).then(() => {
                    window.location.href = '/dang-nhap';
                });
                return;
            }

            const bacSiId = document.getElementById('booking-bac-si-id').value;
            const ngayKham = document.getElementById('booking-ngay-kham').value;
            const gioBatDau = document.getElementById('booking-gio-bat-dau').value;

            if (!gioBatDau) {
                Swal.fire('Chưa chọn giờ khám', 'Vui lòng nhấn chọn một khung giờ khám 30 phút phù hợp.', 'info');
                return;
            }

            const payload = {
                bac_si_id: parseInt(bacSiId),
                ngay_kham: ngayKham,
                gio_bat_dau: gioBatDau,
                ly_do_kham: document.getElementById('booking-ly-do-kham').value,
                ho_ten: document.getElementById('booking-ho-ten').value,
                so_dien_thoai: document.getElementById('booking-sdt').value,
                so_cccd: document.getElementById('booking-cccd').value,
                ngay_sinh: document.getElementById('booking-ngay-sinh').value || null,
                gioi_tinh: document.getElementById('booking-gioi-tinh').value,
                nhom_mau: document.getElementById('booking-nhom-mau').value || null,
                tien_su_di_ung: document.getElementById('booking-tien-su-di-ung').value || null,
                tien_su_benh: document.getElementById('booking-tien-su-benh').value || null,
                nguoi_lien_he_khan_cap: document.getElementById('booking-nguoi-than').value || null,
                sdt_khan_cap: document.getElementById('booking-sdt-khan-cap').value || null,
            };

            const submitBtn = document.getElementById('btn-submit-booking');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý đặt lịch...';

            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/dat-lich`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (res.status === 201 && data.thanh_cong) {
                    const lh = data.du_lieu;
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt lịch khám thành công!',
                        html: `
                            <div class="text-left text-sm space-y-2 mt-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div>Mã lịch hẹn: <strong class="text-medical-600 font-mono text-base">${lh.ma_lich_hen}</strong></div>
                                <div>Bệnh nhân: <strong>${payload.ho_ten}</strong> (${payload.so_dien_thoai})</div>
                                <div>Thời gian: <strong>${lh.gio_bat_dau.substring(0, 5)} - ${lh.gio_ket_thuc.substring(0, 5)}</strong> ngày <strong>${lh.ngay_kham}</strong></div>
                                <div class="text-xs text-slate-500 mt-2">Hệ thống đã tự động gửi email và tạo tin nhắn xác nhận cho bạn.</div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa-solid fa-bell mr-1"></i> Xem Email / SMS xác nhận',
                        cancelButtonText: 'Quản lý lịch hẹn',
                        confirmButtonColor: '#0284c7',
                        cancelButtonColor: '#64748b',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            xemTruocThongBao(lh.id);
                        } else {
                            switchView('view-appointments');
                        }
                    });

                    // Reset form
                    document.getElementById('booking-ly-do-kham').value = '';
                } else if (res.status === 409) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Trùng Lịch Khám (HTTP 409)',
                        text: data.thong_bao || data.thong_diep || 'Bác sĩ đã có lịch khám trong khung giờ này. Vui lòng chọn khung giờ khác.',
                        confirmButtonColor: '#e11d48'
                    });
                } else if (res.status === 422) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ngày khám không hợp lệ',
                        text: data.thong_bao || data.thong_diep || 'Không thể đặt lịch khám trong quá khứ.',
                        confirmButtonColor: '#f59e0b'
                    });
                } else {
                    Swal.fire('Lỗi đặt lịch', data.thong_diep || 'Không thể hoàn tất đặt lịch. Vui lòng thử lại.', 'error');
                }
            } catch (err) {
                console.error("Lỗi đặt lịch:", err);
                Swal.fire('Lỗi kết nối', 'Không thể gửi yêu cầu tới API Gateway. Vui lòng kiểm tra hệ thống.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-calendar-check"></i> Xác Nhận Đặt Lịch Khám';
            }
        }

        // 6. Tải danh sách lịch hẹn & Áp dụng bộ lọc 4 ô
        async function taiDanhSachLichHen() {
            const tbody = document.getElementById('table-lich-hen-body');
            tbody.innerHTML = `<tr><td colspan="6" class="py-12 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl text-medical-500 mb-2"></i><p>Đang tải danh sách lịch khám...</p></td></tr>`;

            const keyword = document.getElementById('filter-keyword').value.trim();
            const status = document.getElementById('filter-status').value;
            const timeline = document.getElementById('filter-timeline').value;
            const date = document.getElementById('filter-date').value;

            const params = new URLSearchParams();
            if (keyword) params.append('tu_khoa', keyword);
            if (status) params.append('trang_thai', status);
            if (timeline) params.append('moc_thoi_gian', timeline);
            if (date) params.append('ngay_kham', date);

            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');

            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    }
                });
                const data = await res.json();

                if (data.thanh_cong && Array.isArray(data.du_lieu)) {
                    danhSachLichHen = data.du_lieu;
                    document.getElementById('total-appointments-count').textContent = danhSachLichHen.length;
                    renderBangLichHen(danhSachLichHen);
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-slate-400">Không có dữ liệu lịch hẹn nào.</td></tr>`;
                }
            } catch (e) {
                console.error("Lỗi tải lịch hẹn:", e);
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-rose-500">Lỗi kết nối khi tải danh sách lịch hẹn.</td></tr>`;
            }
        }

        function debounceFilter() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                taiDanhSachLichHen();
            }, 300);
        }

        function handleTimelineChange() {
            document.getElementById('filter-date').value = '';
            taiDanhSachLichHen();
        }

        function handleDateChange() {
            document.getElementById('filter-timeline').value = '';
            taiDanhSachLichHen();
        }

        // Render bảng dữ liệu chuẩn No-Wrap với Badge viên thuốc quy chuẩn
        function renderBangLichHen(danhSach) {
            const tbody = document.getElementById('table-lich-hen-body');
            if (danhSach.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-slate-400">Không tìm thấy ca khám nào khớp bộ lọc.</td></tr>`;
                return;
            }

            let html = '';
            danhSach.forEach(lh => {
                const bn = lh.benh_nhan || {};
                const tenBn = bn.ho_ten || 'Chưa cập nhật';
                const sdtBn = bn.so_dien_thoai || 'N/A';
                const cccdBn = bn.so_cccd ? ` • CCCD: ${bn.so_cccd}` : '';
                const gioKham = `${lh.gio_bat_dau ? lh.gio_bat_dau.substring(0, 5) : '08:00'} - ${lh.gio_ket_thuc ? lh.gio_ket_thuc.substring(0, 5) : '08:30'}`;
                const ngayKham = formatDateVN(lh.ngay_kham);

                // Badge viên thuốc bo tròn màu sắc quy chuẩn
                let badgeClass = '';
                let badgeText = '';
                let iconClass = '';

                switch (lh.trang_thai) {
                    case 'CHO_XAC_NHAN':
                    case 'CHO_KHAM':
                        badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
                        badgeText = 'Chờ xác nhận';
                        iconClass = 'fa-solid fa-hourglass-half';
                        break;
                    case 'DA_XAC_NHAN':
                        badgeClass = 'bg-sky-100 text-sky-800 border-sky-300';
                        badgeText = 'Đã xác nhận';
                        iconClass = 'fa-solid fa-user-doctor';
                        break;
                    case 'DANG_KHAM':
                        badgeClass = 'bg-purple-100 text-purple-800 border-purple-300';
                        badgeText = 'Đang khám';
                        iconClass = 'fa-solid fa-stethoscope';
                        break;
                    case 'HOAN_THANH':
                        badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                        badgeText = 'Hoàn thành';
                        iconClass = 'fa-solid fa-circle-check';
                        break;
                    case 'DA_HUY':
                        badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
                        badgeText = 'Đã hủy';
                        iconClass = 'fa-solid fa-ban';
                        break;
                    default:
                        badgeClass = 'bg-slate-100 text-slate-800 border-slate-300';
                        badgeText = lh.trang_thai;
                        iconClass = 'fa-solid fa-circle-info';
                }

                html += `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <!-- Cột Mã LK -->
                    <td class="py-3 px-4">
                        <span class="font-mono font-bold text-xs bg-slate-100 text-medical-700 px-2.5 py-1 rounded-lg border border-slate-200">
                            ${lh.ma_lich_hen || ('LK' + String(lh.id).padStart(4, '0'))}
                        </span>
                    </td>

                    <!-- Cột Bệnh Nhân (No-wrap) -->
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-900">${tenBn}</div>
                        <div class="text-[11px] text-slate-500 font-mono">${sdtBn}${cccdBn}</div>
                    </td>

                    <!-- Cột Bác Sĩ -->
                    <td class="py-3 px-4">
                        <span class="font-semibold text-slate-700">Bác sĩ #${lh.bac_si_id}</span>
                    </td>

                    <!-- Cột Thời Gian -->
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800">${gioKham}</div>
                        <div class="text-[11px] text-slate-400">${ngayKham}</div>
                    </td>

                    <!-- Cột Trạng Thái (Badge viên thuốc) -->
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border ${badgeClass}">
                            <i class="${iconClass} text-[10px]"></i>
                            ${badgeText}
                        </span>
                    </td>

                    <!-- Cột Thao Tác Điều Phối -->
                    <td class="py-3 px-4 text-center">
                        <div class="inline-flex items-center gap-1.5">
                            <!-- Nút xem Bệnh Án -->
                            <button onclick="xemBenhAn(${lh.id})" title="Xem Hồ sơ Bệnh án Điện tử" class="p-1.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition">
                                <i class="fa-solid fa-file-medical text-xs"></i>
                            </button>

                            <!-- Nút xem Thông Báo Kép -->
                            <button onclick="xemTruocThongBao(${lh.id})" title="Xem trước Email & SMS" class="p-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition">
                                <i class="fa-solid fa-bell text-xs"></i>
                            </button>

                            <!-- Nút Duyệt ca khám (Chờ xác nhận -> Đã xác nhận) -->
                            ${(lh.trang_thai === 'CHO_XAC_NHAN' || lh.trang_thai === 'CHO_KHAM') ? `
                                <button onclick="handleDuyetLich(${lh.id})" title="Bác sĩ duyệt ca khám" class="px-2 py-1 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-[11px] transition inline-flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Duyệt
                                </button>
                            ` : ''}

                            <!-- Nút Gọi khám (Đã xác nhận -> Đang khám) -->
                            ${lh.trang_thai === 'DA_XAC_NHAN' ? `
                                <button onclick="handleGoiKham(${lh.id})" title="Gọi bệnh nhân vào khám" class="px-2 py-1 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] transition inline-flex items-center gap-1">
                                    <i class="fa-solid fa-stethoscope"></i> Gọi khám
                                </button>
                            ` : ''}

                            <!-- Nút Hoàn thành khám (Đang khám -> Hoàn thành) -->
                            ${lh.trang_thai === 'DANG_KHAM' ? `
                                <button onclick="openModalHoanThanh(${lh.id}, '${lh.ma_lich_hen}')" title="Ghi kết luận và hoàn thành khám" class="px-2 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Kết luận
                                </button>
                            ` : ''}

                            <!-- Nút Hủy lịch (Chỉ khi Chờ duyệt hoặc Đã duyệt) -->
                            ${(lh.trang_thai === 'CHO_XAC_NHAN' || lh.trang_thai === 'CHO_KHAM' || lh.trang_thai === 'DA_XAC_NHAN') ? `
                                <button onclick="openModalHuy(${lh.id}, '${lh.ma_lich_hen}')" title="Hủy lịch hẹn" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // 7. Bác sĩ duyệt lịch hẹn (XAC_NHAN)
        async function handleDuyetLich(id) {
            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');
            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/${id}/xac-nhan`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    }
                });
                const data = await res.json();
                if (res.status === 200 && data.thanh_cong) {
                    Swal.fire('Thành công', 'Đã duyệt xác nhận ca khám.', 'success');
                    taiDanhSachLichHen();
                } else if (res.status === 403) {
                    Swal.fire('Từ chối truy cập (403)', data.thong_diep || 'Bác sĩ không có quyền duyệt ca khám của bác sĩ khác.', 'error');
                } else {
                    Swal.fire('Lỗi', data.thong_diep || 'Không thể duyệt ca khám.', 'error');
                }
            } catch (e) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối API duyệt ca khám.', 'error');
            }
        }

        // 8. Bác sĩ gọi khám (BAT_DAU_KHAM)
        async function handleGoiKham(id) {
            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');
            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/${id}/bat-dau-kham`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    }
                });
                const data = await res.json();
                if (res.status === 200 && data.thanh_cong) {
                    Swal.fire('Đã bắt đầu', 'Ca khám đã chuyển sang trạng thái Đang khám.', 'success');
                    taiDanhSachLichHen();
                } else {
                    Swal.fire('Lỗi', data.thong_diep || 'Không thể bắt đầu ca khám.', 'error');
                }
            } catch (e) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối API.', 'error');
            }
        }

        // 9. Bác sĩ hoàn tất khám bệnh
        function openModalHoanThanh(id, maLh) {
            document.getElementById('complete-appointment-id').value = id;
            document.getElementById('complete-modal-subtitle').textContent = `Mã lịch hẹn: ${maLh}`;
            document.getElementById('complete-chuan-doan').value = '';
            document.getElementById('complete-loi-khuyen').value = '';
            openModal('modal-complete-exam');
        }

        async function handleHoanThanhKham(e) {
            e.preventDefault();
            const id = document.getElementById('complete-appointment-id').value;
            const chuanDoan = document.getElementById('complete-chuan-doan').value;
            const loiKhuyen = document.getElementById('complete-loi-khuyen').value;
            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');

            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/${id}/hoan-thanh`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    },
                    body: JSON.stringify({ chuan_doan: chuanDoan, loi_khuyen: loiKhuyen })
                });
                const data = await res.json();
                if (res.status === 200 && data.thanh_cong) {
                    closeModal('modal-complete-exam');
                    Swal.fire('Hoàn tất ca khám', 'Đã lưu chẩn đoán và hoàn thành ca khám y tế.', 'success');
                    taiDanhSachLichHen();
                } else {
                    Swal.fire('Lỗi', data.thong_diep || 'Không thể hoàn tất ca khám.', 'error');
                }
            } catch (e) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối API.', 'error');
            }
        }

        // 10. Hủy lịch hẹn
        function openModalHuy(id, maLh) {
            document.getElementById('cancel-appointment-id').value = id;
            document.getElementById('cancel-modal-subtitle').textContent = `Mã lịch hẹn: ${maLh}`;
            document.getElementById('cancel-ly-do').value = '';
            openModal('modal-cancel-appointment');
        }

        async function handleXacNhanHuyLich(e) {
            e.preventDefault();
            const id = document.getElementById('cancel-appointment-id').value;
            const lyDo = document.getElementById('cancel-ly-do').value;
            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');

            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/${id}/huy`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    },
                    body: JSON.stringify({ ly_do_huy: lyDo })
                });
                const data = await res.json();
                if (res.status === 200 && data.thanh_cong) {
                    closeModal('modal-cancel-appointment');
                    Swal.fire('Đã hủy lịch hẹn', 'Ca khám đã được hủy thành công.', 'info');
                    taiDanhSachLichHen();
                } else if (res.status === 422 && data.ma_loi === 'KHONG_THE_HUY_SAT_GIO') {
                    closeModal('modal-cancel-appointment');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chặn Hủy Lịch Sát Giờ (< 2 tiếng)',
                        html: `<div class="text-left text-xs space-y-2">
                            <p class="text-rose-600 font-bold">${data.thong_diep}</p>
                            <p class="text-slate-600">Theo quy định phòng khám, không thể tự hủy lịch khám khi thời gian hẹn còn dưới 2 tiếng. Quý khách vui lòng liên hệ trực tiếp Hotline <strong>1900 6868</strong> để được hỗ trợ dời lịch khẩn cấp.</p>
                        </div>`
                    });
                } else {
                    Swal.fire('Lỗi hủy lịch', data.thong_diep || 'Không thể hủy ca khám này.', 'error');
                }
            } catch (e) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối API.', 'error');
            }
        }

        // 11. Xem Hồ sơ Bệnh Án Điện Tử
        function xemBenhAn(id) {
            const lh = danhSachLichHen.find(item => item.id === id);
            if (!lh || !lh.benh_nhan) {
                Swal.fire('Không có dữ liệu', 'Không tìm thấy hồ sơ bệnh án của ca khám này.', 'info');
                return;
            }

            const bn = lh.benh_nhan;
            document.getElementById('modal-ehr-patient-name').textContent = `${bn.ho_ten} • Mã BN: ${bn.ma_benh_nhan || 'BN000' + bn.id}`;

            document.getElementById('modal-ehr-content').innerHTML = `
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                    <div><span class="text-slate-500">Họ và tên:</span> <strong class="text-slate-900 block">${bn.ho_ten}</strong></div>
                    <div><span class="text-slate-500">Số điện thoại:</span> <strong class="text-slate-900 block font-mono">${bn.so_dien_thoai}</strong></div>
                    <div><span class="text-slate-500">Số CCCD / CMND:</span> <strong class="text-slate-900 block font-mono">${bn.so_cccd || 'Chưa cập nhật'}</strong></div>
                    <div><span class="text-slate-500">Nhóm máu:</span> <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-bold">${bn.nhom_mau || 'Chưa rõ'}</span></div>
                    <div><span class="text-slate-500">Ngày sinh:</span> <span class="text-slate-800 font-semibold">${bn.ngay_sinh ? formatDateVN(bn.ngay_sinh) : 'N/A'}</span></div>
                    <div><span class="text-slate-500">Giới tính:</span> <span class="text-slate-800 font-semibold">${bn.gioi_tinh === 'NAM' ? 'Nam' : (bn.gioi_tinh === 'NU' ? 'Nữ' : 'Khác')}</span></div>
                </div>

                <div class="space-y-2">
                    <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl">
                        <span class="font-bold text-amber-800 block mb-0.5"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Tiền Sử Dị Ứng Thuốc:</span>
                        <p class="text-slate-700">${bn.tien_su_di_ung || 'Chưa ghi nhận tiền sử dị ứng thuốc hay kháng sinh.'}</p>
                    </div>

                    <div class="p-3 bg-sky-50/70 border border-sky-200 rounded-xl">
                        <span class="font-bold text-sky-800 block mb-0.5"><i class="fa-solid fa-heart-pulse mr-1"></i> Bệnh Lý Nền Mãn Tính:</span>
                        <p class="text-slate-700">${bn.tien_su_benh || 'Không có ghi nhận bệnh lý nền nguy hiểm.'}</p>
                    </div>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="font-bold text-slate-700 block mb-0.5"><i class="fa-solid fa-phone-volume mr-1"></i> Liên Hệ Khẩn Cấp:</span>
                        <p class="text-slate-700">Người liên hệ: <strong>${bn.nguoi_lien_he_khan_cap || 'Chưa cập nhật'}</strong> • SĐT: <strong class="font-mono">${bn.sdt_khan_cap || 'N/A'}</strong></p>
                    </div>
                </div>
            `;

            openModal('modal-patient-record');
        }

        // 12. Xem trước thông báo Kép (Email & SMS)
        async function xemTruocThongBao(id) {
            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/${id}/xem-truoc-thong-bao`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.thanh_cong) {
                    document.getElementById('preview-email-title').textContent = data.email.tieu_de;
                    document.getElementById('preview-email-body').innerHTML = data.email.noi_dung_html;
                    document.getElementById('preview-sms-text').textContent = data.sms.noi_dung;

                    switchNotificationTab('tab-email');
                    openModal('modal-preview-notification');
                } else {
                    Swal.fire('Lỗi', 'Không thể tạo bản xem trước thông báo.', 'error');
                }
            } catch (e) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối API xem thông báo.', 'error');
            }
        }

        function switchNotificationTab(tabId) {
            document.getElementById('tab-email').classList.add('hidden');
            document.getElementById('tab-sms').classList.add('hidden');
            document.getElementById('btn-tab-email').className = 'px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5';
            document.getElementById('btn-tab-sms').className = 'px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5';

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = tabId === 'tab-email' ? 'btn-tab-email' : 'btn-tab-sms';
            document.getElementById(activeBtn).className = 'px-4 py-2 text-xs font-bold text-medical-600 border-b-2 border-medical-600 flex items-center gap-1.5';
        }

        // 13. Xem Lịch Sử Của Tôi
        async function taiLichSuCuaToi() {
            const container = document.getElementById('my-history-container');
            const token = localStorage.getItem('token') || localStorage.getItem('jwt_token');

            if (!token) {
                container.innerHTML = `
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center text-amber-800">
                        <i class="fa-solid fa-lock text-3xl mb-2 text-amber-500"></i>
                        <h4 class="font-bold text-base mb-1">Vui lòng đăng nhập để xem lịch sử khám</h4>
                        <p class="text-xs mb-4">Lịch sử khám bệnh được bảo mật tuyệt đối theo tài khoản bệnh nhân.</p>
                        <a href="/dang-nhap" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs transition inline-block">
                            Đăng nhập ngay
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = `<div class="py-12 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl text-medical-500 mb-2"></i><p>Đang tải lịch sử khám...</p></div>`;

            try {
                const res = await fetch(`${GATEWAY_URL}/api/lich-hen/lich-su-cua-toi`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const data = await res.json();

                if (data.thanh_cong && Array.isArray(data.du_lieu) && data.du_lieu.length > 0) {
                    let html = '';
                    data.du_lieu.forEach(lh => {
                        const ngayKham = formatDateVN(lh.ngay_kham);
                        const gioKham = `${lh.gio_bat_dau.substring(0, 5)} - ${lh.gio_ket_thuc.substring(0, 5)}`;
                        html += `
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-medical-300 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-xs bg-medical-50 text-medical-700 px-2.5 py-1 rounded-lg border border-medical-200">
                                        ${lh.ma_lich_hen || ('LK' + String(lh.id).padStart(4, '0'))}
                                    </span>
                                    <span class="text-sm font-bold text-slate-800">Khám cùng Bác sĩ #${lh.bac_si_id}</span>
                                </div>
                                <div class="text-xs text-slate-500">
                                    <i class="fa-solid fa-clock mr-1 text-slate-400"></i> ${gioKham} ngày <strong>${ngayKham}</strong>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-slate-500 block mb-0.5">Lý do / Triệu chứng ban đầu:</span>
                                    <p class="font-semibold text-slate-800">${lh.ly_do_kham || 'Khám sức khỏe'}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500 block mb-0.5">Kết luận chẩn đoán:</span>
                                    <p class="font-semibold text-slate-800">${lh.chuan_doan ? `<span class="text-emerald-700">${lh.chuan_doan}</span>` : '<span class="text-slate-400 italic">Chưa có kết luận</span>'}</p>
                                </div>
                            </div>
                            ${lh.loi_khuyen ? `
                                <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                                    <span class="font-bold text-medical-800 block mb-0.5"><i class="fa-solid fa-pills mr-1"></i> Đơn thuốc & Dặn dò:</span>
                                    <p class="text-slate-700">${lh.loi_khuyen}</p>
                                </div>
                            ` : ''}
                        </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300"></i>
                            <p class="font-bold text-slate-600">Bạn chưa có lịch hẹn khám nào.</p>
                            <button onclick="switchView('view-booking')" class="mt-3 px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl text-xs transition">
                                Đặt lịch ngay
                            </button>
                        </div>
                    `;
                }
            } catch (e) {
                container.innerHTML = `<div class="py-8 text-center text-rose-500">Lỗi kết nối khi tải lịch sử khám.</div>`;
            }
        }

        // Tiện ích
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function formatDateVN(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
            return dateStr;
        }
    </script>
</body>
</html>
