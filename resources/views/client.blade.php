<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Khám Đa Khoa Đại Việt - Hệ Thống Quản Lý Y Tế</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        clinic: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-medical { background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); }
        .gradient-auth { background: radial-gradient(circle at top left, #0d9488 0%, #0f172a 70%); }
        .pulse-dot { animation: pulse-animation 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse-animation {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(1.1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased">

    <!-- ==================================================================== -->
    <!-- 1. MÀN HÌNH XÁC THỰC: ĐĂNG NHẬP / ĐĂNG KÝ (BẮT BUỘC TRƯỚC KHI VÀO) -->
    <!-- ==================================================================== -->
    <div id="authScreen" class="min-h-screen w-full flex items-center justify-center p-4 gradient-auth relative overflow-hidden">
        
        <!-- Decorative background elements -->
        <div class="absolute top-10 left-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 border border-white/20 relative z-10 animate-fade-in">
            
            <!-- Branding Header -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-tr from-teal-600 to-sky-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-teal-600/30 text-white text-3xl">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">PHÒNG KHÁM ĐA KHOA ĐẠI VIỆT</h1>
                <p class="text-xs text-slate-500 mt-1">Hệ Thống Quản Lý & Khám Chữa Bệnh Toàn Diện</p>
                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                    <i class="fa-solid fa-lock text-[10px]"></i> Yêu cầu xác thực tài khoản
                </div>
            </div>

            <!-- Tabs: Đăng Nhập vs Đăng Ký -->
            <div class="flex rounded-xl bg-slate-100 p-1 mb-6 text-xs font-bold">
                <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')" class="flex-1 py-2 rounded-lg bg-white text-teal-800 shadow-sm transition">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Đăng Nhập
                </button>
                <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')" class="flex-1 py-2 rounded-lg text-slate-500 hover:text-slate-800 transition">
                    <i class="fa-solid fa-user-plus mr-1"></i> Đăng Ký Bệnh Nhân
                </button>
            </div>

            <!-- Form 1: Đăng Nhập -->
            <form id="formLogin" onsubmit="handleLoginSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tên đăng nhập</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="text" id="loginUsername" required placeholder="admin / bstuan / benhnhan" value="admin"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="password" id="loginPassword" required placeholder="Nhập mật khẩu..." value="Admin@123"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                </div>

                <!-- Tài khoản gợi ý (Demo Quick-Pick) -->
                <div class="bg-slate-50 border border-slate-200/80 p-3 rounded-2xl text-xs space-y-2">
                    <span class="font-bold text-slate-700 block">Tài khoản mẫu thử nghiệm (Bấm 1 chạm để điền):</span>
                    <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                        <button type="button" onclick="fillDemo('admin', 'Admin@123')" class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-teal-800 hover:bg-teal-50 font-semibold text-left truncate">
                            👑 Admin (Toàn quyền)
                        </button>
                        <button type="button" onclick="fillDemo('bstuan', '123456')" class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-sky-800 hover:bg-sky-50 font-semibold text-left truncate">
                            👨‍⚕️ BS. Tuấn (Nội)
                        </button>
                        <button type="button" onclick="fillDemo('bslan', '123456')" class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-purple-800 hover:bg-purple-50 font-semibold text-left truncate">
                            👩‍⚕️ BS. Lan (Nhi)
                        </button>
                        <button type="button" onclick="fillDemo('benhnhan', '123456')" class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-emerald-800 hover:bg-emerald-50 font-semibold text-left truncate">
                            🧑 Bệnh nhân Nam
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmitLogin" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Đăng Nhập Vào Hệ Thống</span>
                </button>
            </form>

            <!-- Form 2: Đăng Ký Tài Khoản Mới -->
            <form id="formRegister" onsubmit="handleRegisterSubmit(event)" class="space-y-3 hidden text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Họ và tên <span class="text-rose-500">*</span></label>
                    <input type="text" id="regHoTen" required placeholder="Nguyễn Văn A" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tên đăng nhập <span class="text-rose-500">*</span></label>
                        <input type="text" id="regTenDangNhap" required placeholder="nguyenvana" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Số điện thoại</label>
                        <input type="tel" id="regSoDienThoai" placeholder="0987xxxxxx" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="regEmail" required placeholder="email@domain.com" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Mật khẩu (tối thiểu 6 ký tự) <span class="text-rose-500">*</span></label>
                    <input type="password" id="regMatKhau" required minlength="6" placeholder="******" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>

                <button type="submit" id="btnSubmitRegister" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2 mt-4 text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tạo Tài Khoản & Đăng Nhập Ngay</span>
                </button>
            </form>

        </div>
    </div>


    <!-- ==================================================================== -->
    <!-- 2. MÀN HÌNH CHÍNH CỦA HỆ THỐNG (CHỈ HIỂN THỊ KHI ĐÃ ĐĂNG NHẬP) -->
    <!-- ==================================================================== -->
    <div id="appScreen" class="hidden min-h-screen flex-col flex-1 w-full">

        <!-- Top Navigation Bar -->
        <header class="gradient-medical text-white shadow-lg sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    
                    <!-- Brand Logo & Name -->
                    <div class="flex items-center space-x-3 cursor-pointer" onclick="switchTab('bacsi')">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 shadow-inner backdrop-blur-md">
                            <i class="fa-solid fa-hospital-user text-2xl text-emerald-300"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-xl tracking-tight text-white">ĐẠI VIỆT CLINIC</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">Modular Monolith</span>
                            </div>
                            <span class="block text-xs text-teal-100 font-medium">Hệ Thống Quản Lý Phòng Khám Đa Khoa Toàn Diện</span>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <nav class="hidden md:flex items-center space-x-1 bg-black/15 p-1.5 rounded-2xl backdrop-blur-sm border border-white/10">
                        <button onclick="switchTab('bacsi')" id="nav-bacsi" class="tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 bg-white text-teal-900 shadow-sm">
                            <i class="fa-solid fa-user-doctor mr-1.5"></i> Bác Sĩ & Khoa
                        </button>
                        <button onclick="switchTab('dichvu')" id="nav-dichvu" class="tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 text-teal-100 hover:text-white hover:bg-white/10">
                            <i class="fa-solid fa-flask-vial mr-1.5"></i> Dịch Vụ
                        </button>
                        <button onclick="switchTab('lichhen')" id="nav-lichhen" class="tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 text-teal-100 hover:text-white hover:bg-white/10">
                            <i class="fa-solid fa-calendar-check mr-1.5"></i> Lịch Hẹn Khám
                        </button>
                        <button onclick="switchTab('hoadon')" id="nav-hoadon" class="tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 text-teal-100 hover:text-white hover:bg-white/10">
                            <i class="fa-solid fa-receipt mr-1.5"></i> Viện Phí
                        </button>
                        <!-- Tab dành riêng cho ADMIN: Quản Lý Tài Khoản -->
                        <button onclick="switchTab('taikhoan')" id="nav-taikhoan" class="tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 text-teal-100 hover:text-white hover:bg-white/10 hidden">
                            <i class="fa-solid fa-users-gear mr-1.5"></i> Quản Lý Tài Khoản
                        </button>
                    </nav>

                    <!-- User Profile & Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <button onclick="openBookingModal()" class="hidden sm:flex items-center gap-2 bg-emerald-400 hover:bg-emerald-300 text-teal-950 font-bold text-sm px-4 py-2.5 rounded-xl shadow-md transition duration-200 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-calendar-plus text-base"></i>
                            <span>Đặt Lịch Khám</span>
                        </button>

                        <!-- User Profile Badge with Dropdown Actions -->
                        <div class="flex items-center gap-2 bg-white/10 border border-white/20 px-3 py-1.5 rounded-xl backdrop-blur-sm">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-emerald-200 font-bold text-sm">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="text-left hidden lg:block">
                                <div class="text-xs font-bold leading-tight" id="profileName">Admin</div>
                                <span class="text-[10px] font-semibold text-emerald-200 uppercase tracking-wide" id="profileRole">Quản trị viên</span>
                            </div>
                            
                            <!-- Đổi mật khẩu button -->
                            <button onclick="openChangePasswordModal()" title="Đổi mật khẩu tài khoản" class="text-teal-200 hover:text-white ml-2 p-1.5 rounded-lg hover:bg-white/10 transition">
                                <i class="fa-solid fa-key"></i>
                            </button>

                            <!-- Đăng xuất button -->
                            <button onclick="handleLogout()" title="Đăng xuất khỏi hệ thống" class="text-teal-200 hover:text-rose-300 p-1.5 rounded-lg hover:bg-white/10 transition">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- Sub-Navbar for Mobile -->
        <div class="md:hidden bg-teal-800 text-white px-3 py-2 flex items-center justify-around text-xs font-semibold overflow-x-auto border-b border-teal-700">
            <button onclick="switchTab('bacsi')" class="py-1 px-2.5 rounded-lg whitespace-nowrap">Bác Sĩ</button>
            <button onclick="switchTab('dichvu')" class="py-1 px-2.5 rounded-lg whitespace-nowrap">Dịch Vụ</button>
            <button onclick="switchTab('lichhen')" class="py-1 px-2.5 rounded-lg whitespace-nowrap">Lịch Hẹn</button>
            <button onclick="switchTab('hoadon')" class="py-1 px-2.5 rounded-lg whitespace-nowrap">Viện Phí</button>
            <button onclick="switchTab('taikhoan')" id="mob-nav-taikhoan" class="py-1 px-2.5 rounded-lg whitespace-nowrap hidden">Tài Khoản</button>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
            
            <!-- Hero Overview Stats -->
            <section class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Đội Ngũ Bác Sĩ</span>
                            <h3 class="text-3xl font-extrabold text-teal-700 mt-1" id="statDoctorCount">0</h3>
                            <p class="text-xs text-slate-500 mt-0.5"><span class="text-emerald-600 font-semibold">100%</span> Chuyên khoa cao</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 text-2xl border border-teal-100">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Chuyên Khoa</span>
                            <h3 class="text-3xl font-extrabold text-sky-700 mt-1" id="statSpecialtyCount">0</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Nội, Nhi, RHM, Mắt...</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600 text-2xl border border-sky-100">
                            <i class="fa-solid fa-stethoscope"></i>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cận Lâm Sàng</span>
                            <h3 class="text-3xl font-extrabold text-indigo-700 mt-1" id="statServiceCount">0</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Xét nghiệm, Siêu âm, X-Quang</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl border border-indigo-100">
                            <i class="fa-solid fa-microscope"></i>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lịch Khám Hệ Thống</span>
                            <h3 class="text-3xl font-extrabold text-emerald-700 mt-1" id="statAppointmentCount">0</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Cập nhật theo thời gian thực</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl border border-emerald-100">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>

                </div>
            </section>

            <!-- ================= TAB 1: BÁC SĨ & CHUYÊN KHOA ================= -->
            <section id="tab-content-bacsi" class="space-y-6">
                
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Specialty buttons -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0" id="specialtyFilterList">
                        <button onclick="filterDoctorsBySpecialty(null)" class="spec-btn active-spec px-3.5 py-1.5 rounded-xl text-xs font-bold bg-teal-700 text-white transition shadow-sm">
                            Tất Cả Khoa
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Search input -->
                        <div class="relative w-full md:w-72">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                            <input type="text" id="doctorSearchInput" oninput="filterDoctors()" placeholder="Tìm bác sĩ, học vị..." 
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                        </div>

                        <!-- Admin buttons: Thêm bác sĩ & Thêm khoa -->
                        <button onclick="openCreateDoctorModal()" id="btnAdminAddDoctor" class="hidden bg-teal-700 hover:bg-teal-800 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                            <i class="fa-solid fa-user-plus mr-1"></i> Thêm Bác Sĩ
                        </button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-user-doctor text-teal-600"></i>
                            <span>Danh Sách Bác Sĩ Chuyên Khoa</span>
                        </h2>
                        <span class="text-xs text-slate-500" id="doctorResultCount">Đang tải dữ liệu...</span>
                    </div>

                    <div id="doctorGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Loaded via JS -->
                    </div>
                </div>
            </section>

            <!-- ================= TAB 2: DỊCH VỤ CẬN LÂM SÀNG ================= -->
            <section id="tab-content-dichvu" class="hidden space-y-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-flask-vial text-teal-600"></i>
                            <span>Bảng Danh Mục Dịch Vụ & Viện Phí Cận Lâm Sàng</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Đơn giá niêm yết minh bạch theo quy chuẩn của Bộ Y Tế</p>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <i class="fa-solid fa-search absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="text" id="serviceSearchInput" oninput="filterServices()" placeholder="Tìm tên dịch vụ..." 
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>

                <div id="servicesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Loaded via JS -->
                </div>
            </section>

            <!-- ================= TAB 3: QUẢN LÝ LỊCH HẸN ================= -->
            <section id="tab-content-lichhen" class="hidden space-y-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg border border-teal-100">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Tra Cứu & Điều Phối Lịch Hẹn</h2>
                            <p class="text-xs text-slate-500">Xem trạng thái, xác nhận khám và cập nhật kết quả điều trị</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select id="appointmentStatusFilter" onchange="filterAppointments()" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-teal-600 focus:outline-none">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="CHO_XAC_NHAN">Chờ xác nhận</option>
                            <option value="DA_XAC_NHAN">Đã xác nhận</option>
                            <option value="DANG_KHAM">Đang khám</option>
                            <option value="HOAN_THANH">Hoàn thành</option>
                            <option value="DA_HUY">Đã hủy</option>
                        </select>

                        <button onclick="openBookingModal()" class="bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm px-4 py-2 rounded-xl flex items-center gap-2 shadow-sm transition">
                            <i class="fa-solid fa-plus"></i>
                            <span>Thêm Lịch Khám</span>
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                    <th class="py-3.5 px-4">Mã Lịch</th>
                                    <th class="py-3.5 px-4">Bệnh Nhân</th>
                                    <th class="py-3.5 px-4">Bác Sĩ Phụ Trách</th>
                                    <th class="py-3.5 px-4">Thời Gian Khám</th>
                                    <th class="py-3.5 px-4">Triệu Chứng</th>
                                    <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                                    <th class="py-3.5 px-4 text-right">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentTableBody" class="divide-y divide-slate-100">
                                <!-- Loaded via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div id="appointmentEmptyMessage" class="hidden p-8 text-center text-slate-400">
                        <i class="fa-regular fa-folder-open text-4xl mb-2 text-slate-300"></i>
                        <p class="text-sm">Không tìm thấy lịch hẹn phù hợp</p>
                    </div>
                </div>
            </section>

            <!-- ================= TAB 4: VIỆN PHÍ & HÓA ĐƠN ================= -->
            <section id="tab-content-hoadon" class="hidden space-y-6">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-200">Báo Cáo Tài Chính Viện Phí</span>
                        <h3 class="text-2xl font-bold mt-1">Tổng Doanh Thu Khám & Xét Nghiệm</h3>
                        <p class="text-xs text-emerald-100 mt-1">Hóa đơn phát sinh tự động sau khi Bác sĩ hoàn thành khám bệnh</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs uppercase font-semibold text-emerald-200">Đã thu thực tế</span>
                        <div class="text-3xl font-extrabold text-white" id="totalRevenueDisplay">0 đ</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-teal-600"></i>
                            <span>Danh Sách Hóa Đơn Khám Bệnh & Thu Ngân</span>
                        </h3>
                        <button onclick="loadAppointments()" class="text-xs text-teal-700 hover:text-teal-900 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-rotate"></i> Làm mới
                        </button>
                    </div>

                    <div id="invoiceGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Loaded via JS -->
                    </div>
                </div>
            </section>

            <!-- ================= TAB 5: QUẢN LÝ TÀI KHOẢN (CHO ADMIN) ================= -->
            <section id="tab-content-taikhoan" class="hidden space-y-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg border border-purple-100">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Quản Lý Người Dùng & Phân Quyền</h2>
                            <p class="text-xs text-slate-500">Danh sách tài khoản hệ thống, phân quyền vai trò và khóa tài khoản</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <select id="userRoleFilter" onchange="loadAccounts()" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-teal-600 focus:outline-none">
                            <option value="">-- Tất cả vai trò --</option>
                            <option value="1">Quản trị viên (ADMIN)</option>
                            <option value="2">Bác sĩ (BAC_SI)</option>
                            <option value="3">Bệnh nhân (BENH_NHAN)</option>
                        </select>
                        <button onclick="loadAccounts()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                            <i class="fa-solid fa-rotate"></i> Làm mới
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                    <th class="py-3.5 px-4">Tên Đăng Nhập</th>
                                    <th class="py-3.5 px-4">Họ Và Tên</th>
                                    <th class="py-3.5 px-4">Email</th>
                                    <th class="py-3.5 px-4">Số Điện Thoại</th>
                                    <th class="py-3.5 px-4 text-center">Vai Trò</th>
                                    <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                                    <th class="py-3.5 px-4 text-right">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="accountTableBody" class="divide-y divide-slate-100">
                                <!-- Loaded via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-slate-500 text-xs">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-hospital text-teal-600 text-base"></i>
                    <span class="font-semibold text-slate-700">Phòng Khám Đa Khoa Đại Việt</span>
                    <span>• Địa chỉ: Số 12 Chùa Bộc, Đống Đa, Hà Nội</span>
                </div>
                <div>
                    <span>Kiến trúc: Modular Monolith (Laravel 12 + MySQL 3307)</span>
                </div>
            </div>
        </footer>

    </div>


    <!-- ==================================================================== -->
    <!-- MODALS & POPUPS -->
    <!-- ==================================================================== -->

    <!-- Modal Đổi Mật Khẩu -->
    <div id="modalChangePassword" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-key text-teal-600 text-lg"></i>
                    <h3 class="text-base font-bold text-slate-800">Đổi Mật Khẩu Tài Khoản</h3>
                </div>
                <button onclick="closeModal('modalChangePassword')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="formChangePassword" onsubmit="handleChangePasswordSubmit(event)" class="space-y-4 mt-4 text-sm">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu hiện tại <span class="text-rose-500">*</span></label>
                    <input type="password" id="pwOld" required placeholder="Nhập mật khẩu đang dùng..." class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu mới (tối thiểu 6 ký tự) <span class="text-rose-500">*</span></label>
                    <input type="password" id="pwNew" required minlength="6" placeholder="Nhập mật khẩu mới..." class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Xác nhận mật khẩu mới <span class="text-rose-500">*</span></label>
                    <input type="password" id="pwConfirm" required minlength="6" placeholder="Nhập lại mật khẩu mới..." class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmitChangePw" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        <span>Cập Nhật Mật Khẩu Mới</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Đặt Lịch Khám -->
    <div id="modalBooking" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl overflow-y-auto max-h-[92vh] border border-slate-100">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-base">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Đăng Ký Đặt Lịch Khám</h3>
                        <p class="text-[11px] text-slate-500">Đặt hẹn trực tiếp với Bác sĩ chuyên khoa</p>
                    </div>
                </div>
                <button onclick="closeModal('modalBooking')" class="text-slate-400 hover:text-slate-600 p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="bookingForm" onsubmit="handleBookingSubmit(event)" class="space-y-4 mt-4 text-sm">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Chọn Bác Sĩ Chuyên Khoa <span class="text-rose-500">*</span></label>
                    <select id="bookingDoctorSelect" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50/50 focus:ring-2 focus:ring-teal-600 focus:bg-white focus:outline-none"></select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ngày Khám <span class="text-rose-500">*</span></label>
                        <input type="date" id="bookingDate" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Khung Giờ <span class="text-rose-500">*</span></label>
                        <select id="bookingTime" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm bg-white focus:ring-2 focus:ring-teal-600 focus:outline-none">
                            <option value="08:00">08:00 - Sáng</option>
                            <option value="08:30">08:30 - Sáng</option>
                            <option value="09:00" selected>09:00 - Sáng</option>
                            <option value="09:30">09:30 - Sáng</option>
                            <option value="10:00">10:00 - Sáng</option>
                            <option value="10:30">10:30 - Sáng</option>
                            <option value="14:00">14:00 - Chiều</option>
                            <option value="14:30">14:30 - Chiều</option>
                            <option value="15:00">15:00 - Chiều</option>
                            <option value="15:30">15:30 - Chiều</option>
                            <option value="16:00">16:00 - Chiều</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <span class="text-xs font-bold text-teal-800 uppercase tracking-wide block mb-2">Thông Tin Người Khám</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Họ và Tên <span class="text-rose-500">*</span></label>
                            <input type="text" id="patientName" required placeholder="Nguyễn Văn A" class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Số Điện Thoại <span class="text-rose-500">*</span></label>
                            <input type="tel" id="patientPhone" required placeholder="0987xxxxxx" class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Mô tả triệu chứng / Lý do khám</label>
                    <textarea id="patientSymptoms" rows="2" placeholder="Ví dụ: Đau đầu, sốt nhẹ 2 ngày, khó tiêu..." class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmitBooking" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Xác Nhận Đăng Ký Lịch Khám</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Thêm Bác Sĩ Mới (ADMIN) -->
    <div id="modalCreateDoctor" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-teal-600 text-lg"></i>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Thêm Bác Sĩ & Tạo Tài Khoản Tự Động</h3>
                        <p class="text-xs text-slate-500">Hệ thống sẽ cấp tài khoản vai trò BAC_SI với mật khẩu mặc định</p>
                    </div>
                </div>
                <button onclick="closeModal('modalCreateDoctor')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="formCreateDoctor" onsubmit="handleCreateDoctorSubmit(event)" class="space-y-3 mt-4 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Họ tên Bác sĩ <span class="text-rose-500">*</span></label>
                        <input type="text" id="docHoTen" required placeholder="BS. CKII..." class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Chuyên khoa <span class="text-rose-500">*</span></label>
                        <select id="docChuyenKhoaSelect" required class="w-full px-3.5 py-2 border rounded-xl bg-white focus:ring-2 focus:ring-teal-600 focus:outline-none"></select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Học vị / Chức danh</label>
                        <input type="text" id="docHocVi" placeholder="ThS. Bác sĩ CKII" class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Giá khám (VNĐ) <span class="text-rose-500">*</span></label>
                        <input type="number" id="docGiaKham" required value="200000" step="10000" class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Phòng khám <span class="text-rose-500">*</span></label>
                        <input type="text" id="docPhongKham" required value="Phòng 201 - Tầng 2" class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Số điện thoại</label>
                        <input type="tel" id="docSoDienThoai" placeholder="0912xxxxxx" class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kinh nghiệm làm việc & Lịch sử công tác</label>
                    <textarea id="docKinhNghiem" rows="2" placeholder="Ví dụ: 15 năm công tác tại Bệnh viện Bạch Mai..." class="w-full px-3.5 py-2 border rounded-xl focus:ring-2 focus:ring-teal-600 focus:outline-none"></textarea>
                </div>

                <div class="bg-teal-50 border border-teal-200 p-3 rounded-xl text-[11px] text-teal-800">
                    <i class="fa-solid fa-info-circle mr-1"></i> Tài khoản đăng nhập cho Bác sĩ sẽ được tự động tạo với tên đăng nhập theo họ tên và mật khẩu mặc định: <strong>123456</strong>.
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmitDoc" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-plus-circle"></i>
                        <span>Thêm Bác Sĩ & Tạo Tài Khoản</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hoàn Thành Khám Bệnh -->
    <div id="modalExam" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Hoàn Thành Khám Bệnh & Kết Luận</h3>
                    <p class="text-xs text-slate-500" id="examAppointmentInfo">Lịch hẹn: LH...</p>
                </div>
                <button onclick="closeModal('modalExam')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="examForm" onsubmit="handleExamSubmit(event)" class="space-y-4 mt-4 text-sm">
                <input type="hidden" id="examAppointmentId">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Chẩn Đoán Của Bác Sĩ <span class="text-rose-500">*</span></label>
                    <textarea id="examDiagnosis" required rows="2" placeholder="Ví dụ: Viêm mũi họng cấp tính, theo dõi trào ngược dạ dày..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lời Khuyên / Hướng Dẫn Điều Trị</label>
                    <textarea id="examAdvice" rows="2" placeholder="Uống nhiều nước, dùng thuốc theo đơn, tái khám sau 5 ngày..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-teal-600 focus:outline-none"></textarea>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <label class="block text-xs font-bold text-teal-800 uppercase mb-2">Chỉ định thêm dịch vụ cận lâm sàng (nếu có):</label>
                    <div class="space-y-2" id="examServicesSelection"></div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Lưu Hồ Sơ & Phát Sinh Hóa Đơn</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none flex items-center gap-3 bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-xl border border-slate-800 text-sm">
        <i id="toastIcon" class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
        <span id="toastMessage">Thông báo</span>
    </div>


    <!-- ==================================================================== -->
    <!-- 3. JAVASCRIPT LOGIC & API CONSUMPTION -->
    <!-- ==================================================================== -->
    <script>
        // App State
        let state = {
            doctors: [],
            specialties: [],
            services: [],
            appointments: [],
            accounts: [],
            selectedSpecialtyId: null,
            currentUser: null,
            token: localStorage.getItem('clinic_token') || null,
        };

        // Format currency VNĐ
        function formatVND(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }

        // Show Toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMsg = document.getElementById('toastMessage');

            toastMsg.textContent = message;
            if (type === 'success') {
                toastIcon.className = 'fa-solid fa-circle-check text-emerald-400 text-lg';
            } else if (type === 'error') {
                toastIcon.className = 'fa-solid fa-circle-xmark text-rose-400 text-lg';
            } else {
                toastIcon.className = 'fa-solid fa-circle-info text-sky-400 text-lg';
            }

            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3500);
        }

        // ================= AUTH GATEWAY LOGIC =================
        function checkAuth() {
            const savedToken = localStorage.getItem('clinic_token');
            const savedUser = localStorage.getItem('clinic_user');

            if (savedToken && savedUser) {
                try {
                    state.token = savedToken;
                    state.currentUser = JSON.parse(savedUser);
                    showAppScreen();
                    return;
                } catch (e) {}
            }

            showAuthScreen();
        }

        function showAuthScreen() {
            document.getElementById('authScreen').classList.remove('hidden');
            document.getElementById('authScreen').classList.add('flex');
            document.getElementById('appScreen').classList.add('hidden');
            document.getElementById('appScreen').classList.remove('flex');
        }

        function showAppScreen() {
            document.getElementById('authScreen').classList.add('hidden');
            document.getElementById('authScreen').classList.remove('flex');
            document.getElementById('appScreen').classList.remove('hidden');
            document.getElementById('appScreen').classList.add('flex');

            updateProfileHeader();
            loadAllAppData();
        }

        function switchAuthTab(tab) {
            const btnLogin = document.getElementById('tabBtnLogin');
            const btnReg = document.getElementById('tabBtnRegister');
            const formLogin = document.getElementById('formLogin');
            const formReg = document.getElementById('formRegister');

            if (tab === 'login') {
                btnLogin.className = 'flex-1 py-2 rounded-lg bg-white text-teal-800 shadow-sm transition';
                btnReg.className = 'flex-1 py-2 rounded-lg text-slate-500 hover:text-slate-800 transition';
                formLogin.classList.remove('hidden');
                formReg.classList.add('hidden');
            } else {
                btnReg.className = 'flex-1 py-2 rounded-lg bg-white text-teal-800 shadow-sm transition';
                btnLogin.className = 'flex-1 py-2 rounded-lg text-slate-500 hover:text-slate-800 transition';
                formReg.classList.remove('hidden');
                formLogin.classList.add('hidden');
            }
        }

        function fillDemo(username, password) {
            document.getElementById('loginUsername').value = username;
            document.getElementById('loginPassword').value = password;
        }

        // Handle Login Submit
        async function handleLoginSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitLogin');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xác thực tài khoản...`;

            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value;

            try {
                const res = await fetch('/api/v1/tai-khoan/dang-nhap', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ten_dang_nhap: username, mat_khau: password })
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    state.token = data.du_lieu.token;
                    state.currentUser = data.du_lieu.tai_khoan;
                    localStorage.setItem('clinic_token', state.token);
                    localStorage.setItem('clinic_user', JSON.stringify(state.currentUser));

                    showToast(`Chào mừng ${state.currentUser.ho_ten} (${state.currentUser.ten_vai_tro})!`, 'success');
                    showAppScreen();
                } else {
                    const err = data.chi_tiet_loi ? Object.values(data.chi_tiet_loi).flat().join(', ') : data.thong_bao;
                    showToast(err || 'Đăng nhập không thành công', 'error');
                }
            } catch (err) {
                showToast('Lỗi kết nối tới máy chủ', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-arrow-right-to-bracket"></i> <span>Đăng Nhập Vào Hệ Thống</span>`;
            }
        }

        // Handle Register Submit
        async function handleRegisterSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitRegister');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Đang tạo tài khoản...`;

            const payload = {
                ho_ten: document.getElementById('regHoTen').value.trim(),
                ten_dang_nhap: document.getElementById('regTenDangNhap').value.trim(),
                email: document.getElementById('regEmail').value.trim(),
                so_dien_thoai: document.getElementById('regSoDienThoai').value.trim(),
                mat_khau: document.getElementById('regMatKhau').value,
            };

            try {
                const res = await fetch('/api/v1/tai-khoan/dang-ky', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    state.token = data.du_lieu.token;
                    state.currentUser = data.du_lieu.tai_khoan;
                    localStorage.setItem('clinic_token', state.token);
                    localStorage.setItem('clinic_user', JSON.stringify(state.currentUser));

                    showToast('Đăng ký tài khoản thành công! Tự động đăng nhập.', 'success');
                    showAppScreen();
                } else {
                    const err = data.chi_tiet_loi ? Object.values(data.chi_tiet_loi).flat().join(', ') : data.thong_bao;
                    showToast(err || 'Đăng ký không thành công', 'error');
                }
            } catch (err) {
                showToast('Lỗi kết nối máy chủ', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-user-plus"></i> <span>Tạo Tài Khoản & Đăng Nhập Ngay</span>`;
            }
        }

        // Handle Logout
        async function handleLogout() {
            if (state.token) {
                try {
                    await fetch('/api/v1/tai-khoan/dang-xuat', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${state.token}` }
                    });
                } catch (e) {}
            }

            state.token = null;
            state.currentUser = null;
            localStorage.removeItem('clinic_token');
            localStorage.removeItem('clinic_user');

            showToast('Đã đăng xuất khỏi hệ thống', 'info');
            showAuthScreen();
        }

        // Update Profile in Header & Roles
        function updateProfileHeader() {
            if (!state.currentUser) return;

            document.getElementById('profileName').textContent = state.currentUser.ho_ten || state.currentUser.ten_dang_nhap;
            document.getElementById('profileRole').textContent = state.currentUser.ten_vai_tro || state.currentUser.vai_tro;

            const isAdmin = state.currentUser.vai_tro === 'ADMIN';
            const navTaiKhoan = document.getElementById('nav-taikhoan');
            const mobNavTaiKhoan = document.getElementById('mob-nav-taikhoan');
            const btnAdminAddDoctor = document.getElementById('btnAdminAddDoctor');

            if (isAdmin) {
                navTaiKhoan?.classList.remove('hidden');
                mobNavTaiKhoan?.classList.remove('hidden');
                btnAdminAddDoctor?.classList.remove('hidden');
            } else {
                navTaiKhoan?.classList.add('hidden');
                mobNavTaiKhoan?.classList.add('hidden');
                btnAdminAddDoctor?.classList.add('hidden');
            }
        }

        // ================= CHANGE PASSWORD =================
        function openChangePasswordModal() {
            document.getElementById('formChangePassword').reset();
            openModal('modalChangePassword');
        }

        async function handleChangePasswordSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitChangePw');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Đang đổi...`;

            const payload = {
                mat_khau_cu: document.getElementById('pwOld').value,
                mat_khau_moi: document.getElementById('pwNew').value,
                mat_khau_moi_confirmation: document.getElementById('pwConfirm').value,
            };

            try {
                const res = await fetch('/api/v1/tai-khoan/doi-mat-khau', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    showToast('Đổi mật khẩu thành công!', 'success');
                    closeModal('modalChangePassword');
                } else {
                    const err = data.chi_tiet_loi ? Object.values(data.chi_tiet_loi).flat().join(', ') : data.thong_bao;
                    showToast(err || 'Đổi mật khẩu thất bại', 'error');
                }
            } catch (err) {
                showToast('Lỗi gửi yêu cầu', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-check"></i> <span>Cập Nhật Mật Khẩu Mới</span>`;
            }
        }

        // ================= TAB NAVIGATION =================
        function switchTab(tabId) {
            ['bacsi', 'dichvu', 'lichhen', 'hoadon', 'taikhoan'].forEach(t => {
                const section = document.getElementById(`tab-content-${t}`);
                const navBtn = document.getElementById(`nav-${t}`);
                if (!section) return;

                if (t === tabId) {
                    section.classList.remove('hidden');
                    if (navBtn) {
                        navBtn.className = 'tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 bg-white text-teal-900 shadow-sm';
                    }
                    if (t === 'taikhoan') loadAccounts();
                } else {
                    section.classList.add('hidden');
                    if (navBtn) {
                        navBtn.className = 'tab-btn px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 text-teal-100 hover:text-white hover:bg-white/10';
                    }
                }
            });
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // ================= DATA LOADERS =================
        async function loadAllAppData() {
            await Promise.all([
                loadDoctors(),
                loadServices(),
                loadAppointments()
            ]);
        }

        // 1. Load Doctors & Specialties
        async function loadDoctors() {
            try {
                const [docRes, specRes] = await Promise.all([
                    fetch('/api/v1/bac-si?all=true', { headers: { 'Accept': 'application/json' } }),
                    fetch('/api/v1/bac-si/chuyen-khoa', { headers: { 'Accept': 'application/json' } })
                ]);

                const docData = await docRes.json();
                const specData = await specRes.json();

                if (docData.thanh_cong) {
                    state.doctors = docData.du_lieu;
                    document.getElementById('statDoctorCount').textContent = state.doctors.length;
                    renderDoctors();
                }

                if (specData.thanh_cong) {
                    state.specialties = specData.du_lieu;
                    document.getElementById('statSpecialtyCount').textContent = state.specialties.length;
                    renderSpecialtyFilters();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderSpecialtyFilters() {
            const container = document.getElementById('specialtyFilterList');
            const items = [
                `<button onclick="filterDoctorsBySpecialty(null)" class="spec-btn ${state.selectedSpecialtyId === null ? 'bg-teal-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'} px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                    Tất Cả Khoa
                </button>`
            ];

            state.specialties.forEach(sp => {
                const isActive = state.selectedSpecialtyId === sp.id;
                items.push(`
                    <button onclick="filterDoctorsBySpecialty(${sp.id})" class="spec-btn ${isActive ? 'bg-teal-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'} px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                        ${sp.ten_khoa}
                    </button>
                `);
            });

            container.innerHTML = items.join('');
        }

        function filterDoctorsBySpecialty(specId) {
            state.selectedSpecialtyId = specId;
            renderSpecialtyFilters();
            renderDoctors();
        }

        function filterDoctors() {
            renderDoctors();
        }

        function renderDoctors() {
            const container = document.getElementById('doctorGrid');
            const keyword = (document.getElementById('doctorSearchInput')?.value || '').toLowerCase().trim();

            const filtered = state.doctors.filter(d => {
                const matchSpec = state.selectedSpecialtyId === null || d.chuyen_khoa_id === state.selectedSpecialtyId;
                const matchKey = !keyword || d.ho_ten.toLowerCase().includes(keyword) || 
                                 (d.chuyen_khoa?.ten_khoa || '').toLowerCase().includes(keyword) ||
                                 (d.hoc_vi || '').toLowerCase().includes(keyword);
                return matchSpec && matchKey;
            });

            document.getElementById('doctorResultCount').textContent = `Hiển thị ${filtered.length} / ${state.doctors.length} bác sĩ`;

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="fa-solid fa-user-slash text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm">Không tìm thấy bác sĩ phù hợp với tiêu chí tìm kiếm</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(d => {
                const isWorking = d.trang_thai === 'DANG_LAM_VIEC';
                return `
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-teal-600 to-emerald-400 text-white flex items-center justify-center text-xl font-extrabold shadow-sm">
                                        ${d.ho_ten.split(' ').pop().charAt(0)}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-800 leading-tight">${d.ho_ten}</h4>
                                        <span class="text-xs text-slate-500 font-medium">${d.hoc_vi || 'Bác sĩ chuyên khoa'}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold ${isWorking ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600'}">
                                    <span class="w-1.5 h-1.5 rounded-full ${isWorking ? 'bg-emerald-500 pulse-dot' : 'bg-slate-400'}"></span>
                                    ${d.trang_thai === 'DANG_LAM_VIEC' ? 'Trực khám' : (d.trang_thai === 'NGHI_PHEP' ? 'Nghỉ phép' : 'Nghỉ việc')}
                                </span>
                            </div>

                            <div class="mb-3">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-100">
                                    <i class="fa-solid fa-stethoscope mr-1 text-teal-600"></i> ${d.chuyen_khoa ? d.chuyen_khoa.ten_khoa : 'Đa khoa'}
                                </span>
                            </div>

                            <div class="space-y-1.5 text-xs text-slate-600 mb-4 bg-slate-50/70 p-3 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-door-open text-teal-600 w-4"></i>
                                    <span>Phòng: <strong class="text-slate-800">${d.phong_kham || 'P101'}</strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-award text-amber-500 w-4"></i>
                                    <span class="line-clamp-1">${d.kinh_nghiem || 'Nhiều năm kinh nghiệm công tác'}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-slate-400 w-4"></i>
                                    <span>${d.so_dien_thoai || '0912xxxxxx'}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Giá khám ban đầu</span>
                                <span class="text-base font-extrabold text-teal-700">${formatVND(d.gia_kham)}</span>
                            </div>
                            <button onclick="openBookingModal(${d.id})" class="bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-check"></i>
                                <span>Đặt Khám</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // 2. Load Services
        async function loadServices() {
            try {
                const res = await fetch('/api/v1/dich-vu', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (data.thanh_cong) {
                    state.services = data.du_lieu;
                    document.getElementById('statServiceCount').textContent = state.services.length;
                    renderServices();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function filterServices() {
            renderServices();
        }

        function renderServices() {
            const container = document.getElementById('servicesGrid');
            const keyword = (document.getElementById('serviceSearchInput')?.value || '').toLowerCase().trim();

            const filtered = state.services.filter(s => {
                return !keyword || s.ten_dich_vu.toLowerCase().includes(keyword) || (s.ma_dich_vu || '').toLowerCase().includes(keyword);
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm">Không tìm thấy dịch vụ y tế phù hợp</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(s => {
                const isBloodTest = s.loai_dich_vu === 'XET_NGHIEM';
                return `
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="w-12 h-12 rounded-2xl ${isBloodTest ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-600'} flex items-center justify-center text-xl font-bold border ${isBloodTest ? 'border-rose-100' : 'border-sky-100'}">
                                    <i class="fa-solid ${isBloodTest ? 'fa-vial' : 'fa-x-ray'}"></i>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider ${isBloodTest ? 'bg-rose-50 text-rose-700' : 'bg-sky-50 text-sky-700'}">
                                    ${s.loai_dich_vu || 'Cận lâm sàng'}
                                </span>
                            </div>

                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">${s.ma_dich_vu}</span>
                            <h4 class="font-bold text-base text-slate-800 mt-1 mb-2 leading-snug">${s.ten_dich_vu}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">${s.mo_ta || 'Dịch vụ cận lâm sàng hiện đại đạt chuẩn Bộ Y Tế'}</p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Đơn giá niêm yết:</span>
                            <span class="text-base font-extrabold text-teal-700">${formatVND(s.don_gia)}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // 3. Load Appointments
        async function loadAppointments() {
            try {
                const res = await fetch('/api/v1/lich-hen', { 
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${state.token}` } 
                });
                const data = await res.json();
                if (data.thanh_cong) {
                    state.appointments = data.du_lieu;
                    document.getElementById('statAppointmentCount').textContent = state.appointments.length;
                    renderAppointments();
                    renderInvoices();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function filterAppointments() {
            renderAppointments();
        }

        function getStatusBadge(status) {
            switch (status) {
                case 'CHO_XAC_NHAN':
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fa-solid fa-clock text-[10px]"></i> Chờ xác nhận
                            </span>`;
                case 'DA_XAC_NHAN':
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Đã xác nhận
                            </span>`;
                case 'DANG_KHAM':
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500 pulse-dot"></span> Đang khám
                            </span>`;
                case 'HOAN_THANH':
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-check-double text-[10px]"></i> Hoàn thành
                            </span>`;
                case 'DA_HUY':
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <i class="fa-solid fa-ban text-[10px]"></i> Đã hủy
                            </span>`;
                default:
                    return `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">${status}</span>`;
            }
        }

        function renderAppointments() {
            const tbody = document.getElementById('appointmentTableBody');
            const emptyMsg = document.getElementById('appointmentEmptyMessage');
            const statusFilter = document.getElementById('appointmentStatusFilter')?.value;

            const filtered = state.appointments.filter(a => !statusFilter || a.trang_thai === statusFilter);

            if (filtered.length === 0) {
                tbody.innerHTML = '';
                emptyMsg.classList.remove('hidden');
                return;
            }

            emptyMsg.classList.add('hidden');
            tbody.innerHTML = filtered.map(a => {
                const benhNhanName = a.benh_nhan?.ho_ten || 'Khách vãng lai';
                const benhNhanPhone = a.benh_nhan?.so_dien_thoai || 'Chưa cập nhật';
                const bacSiName = a.bac_si?.ho_ten || 'Chưa phân công';
                const chuyenKhoa = a.bac_si?.chuyen_khoa?.ten_khoa || '';

                let actionButtons = '';
                if (a.trang_thai === 'CHO_XAC_NHAN') {
                    actionButtons = `
                        <button onclick="updateAppointmentStatus(${a.id}, 'xac-nhan')" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition shadow-sm" title="Xác nhận lịch khám">
                            Xác Nhận
                        </button>
                        <button onclick="updateAppointmentStatus(${a.id}, 'huy')" class="px-2.5 py-1.5 bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 rounded-lg text-xs font-bold transition" title="Hủy lịch">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                } else if (a.trang_thai === 'DA_XAC_NHAN') {
                    actionButtons = `
                        <button onclick="updateAppointmentStatus(${a.id}, 'bat-dau')" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition shadow-sm" title="Bắt đầu vào khám">
                            <i class="fa-solid fa-stethoscope mr-1"></i> Khám
                        </button>
                    `;
                } else if (a.trang_thai === 'DANG_KHAM') {
                    actionButtons = `
                        <button onclick="openExamModal(${a.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm" title="Kết luận & Hoàn thành">
                            <i class="fa-solid fa-check mr-1"></i> Hoàn Thành
                        </button>
                    `;
                } else if (a.trang_thai === 'HOAN_THANH') {
                    actionButtons = `<span class="text-xs text-emerald-600 font-bold"><i class="fa-solid fa-check"></i> Đã khám</span>`;
                }

                return `
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-xs text-teal-800">${a.ma_lich_hen}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">${benhNhanName}</div>
                            <div class="text-xs text-slate-500"><i class="fa-solid fa-phone text-[10px] mr-1"></i>${benhNhanPhone}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-800">${bacSiName}</div>
                            <div class="text-xs text-teal-600">${chuyenKhoa}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-700">${a.ngay_kham}</div>
                            <div class="text-xs text-slate-500 font-medium">${a.gio_kham}</div>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600 max-w-xs truncate" title="${a.trieu_chung || ''}">
                            ${a.trieu_chung || 'Khám tổng quát'}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            ${getStatusBadge(a.trang_thai)}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                ${actionButtons}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // 4. Render Invoices
        function renderInvoices() {
            const container = document.getElementById('invoiceGrid');
            const revenueDisplay = document.getElementById('totalRevenueDisplay');

            const invoices = [];
            let totalRevenue = 0;

            state.appointments.forEach(a => {
                if (a.hoa_don) {
                    invoices.push({
                        ...a.hoa_don,
                        benh_nhan_name: a.benh_nhan?.ho_ten || 'Bệnh nhân',
                        bac_si_name: a.bac_si?.ho_ten || '',
                        ngay_kham: a.ngay_kham
                    });
                    if (a.hoa_don.trang_thai === 'DA_THANH_TOAN') {
                        totalRevenue += parseFloat(a.hoa_don.tong_tien || 0);
                    }
                }
            });

            revenueDisplay.textContent = formatVND(totalRevenue);

            if (invoices.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="fa-solid fa-file-invoice text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm">Chưa có hóa đơn viện phí nào phát sinh</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = invoices.map(inv => {
                const isPaid = inv.trang_thai === 'DA_THANH_TOAN';
                return `
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-mono text-xs font-bold text-teal-800 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-100">
                                    ${inv.ma_hoa_don}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${isPaid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                                    ${isPaid ? '✓ Đã Thanh Toán' : 'Chưa Thanh Toán'}
                                </span>
                            </div>

                            <h4 class="font-bold text-slate-800 text-sm">${inv.benh_nhan_name}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Bác sĩ: ${inv.bac_si_name}</p>

                            <div class="my-3 space-y-1 bg-slate-50 p-2.5 rounded-xl text-xs text-slate-600">
                                <div class="flex justify-between">
                                    <span>Tiền khám bác sĩ:</span>
                                    <strong>${formatVND(inv.tien_kham)}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span>Dịch vụ cận lâm sàng:</span>
                                    <strong>${formatVND(inv.tien_dich_vu)}</strong>
                                </div>
                                <div class="flex justify-between pt-1 border-t border-slate-200 font-bold text-slate-800">
                                    <span>Tổng viện phí:</span>
                                    <span class="text-teal-700 text-sm">${formatVND(inv.tong_tien)}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            ${!isPaid ? `
                                <button onclick="handlePayInvoice(${inv.id})" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <span>Thu Viện Phí & Hoàn Tất</span>
                                </button>
                            ` : `
                                <div class="text-center text-xs text-emerald-700 font-medium py-1">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Thanh toán: ${inv.phuong_thuc_thanh_toan || 'TIEN_MAT'}
                                </div>
                            `}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // 5. Load Accounts (ADMIN)
        async function loadAccounts() {
            if (!state.token || state.currentUser?.vai_tro !== 'ADMIN') return;

            const roleFilter = document.getElementById('userRoleFilter')?.value;
            let url = '/api/v1/tai-khoan?per_page=20';
            if (roleFilter) url += `&vai_tro_id=${roleFilter}`;

            try {
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${state.token}` }
                });
                const data = await res.json();
                if (data.thanh_cong) {
                    state.accounts = data.du_lieu.data || data.du_lieu;
                    renderAccounts();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderAccounts() {
            const tbody = document.getElementById('accountTableBody');
            if (!tbody) return;

            tbody.innerHTML = state.accounts.map(u => {
                const isCurrent = state.currentUser?.id === u.id;
                const isLocked = u.trang_thai === 'TAM_KHOA';

                return `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">${u.ten_dang_nhap}</td>
                        <td class="py-3 px-4 font-semibold text-slate-700">${u.ho_ten}</td>
                        <td class="py-3 px-4 text-xs text-slate-500">${u.email}</td>
                        <td class="py-3 px-4 text-xs text-slate-500">${u.so_dien_thoai || '-'}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold ${u.vai_tro?.ma_vai_tro === 'ADMIN' ? 'bg-rose-50 text-rose-700 border border-rose-200' : (u.vai_tro?.ma_vai_tro === 'BAC_SI' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200')}">
                                ${u.vai_tro?.ten_vai_tro || u.vai_tro?.ma_vai_tro || 'Bệnh nhân'}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold ${!isLocked ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
                                ${!isLocked ? '✓ Hoạt động' : 'Tạm khóa'}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            ${!isCurrent ? `
                                <button onclick="toggleLockAccount(${u.id})" class="px-3 py-1.5 rounded-lg text-xs font-bold transition ${!isLocked ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'}">
                                    <i class="fa-solid ${!isLocked ? 'fa-lock' : 'fa-lock-open'} mr-1"></i>
                                    ${!isLocked ? 'Khóa' : 'Mở khóa'}
                                </button>
                            ` : `<span class="text-xs text-slate-400 italic">Đang dùng</span>`}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function toggleLockAccount(id) {
            try {
                const res = await fetch(`/api/v1/tai-khoan/${id}/khoa-mo-khoa`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${state.token}` }
                });
                const data = await res.json();
                if (data.thanh_cong) {
                    showToast(data.thong_bao, 'success');
                    await loadAccounts();
                } else {
                    showToast(data.thong_bao || 'Không thể thao tác', 'error');
                }
            } catch (err) {
                showToast('Lỗi gửi yêu cầu', 'error');
            }
        }

        // ================= BOOKING MODAL =================
        function openBookingModal(doctorId = null) {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('bookingDate').min = today;
            if (!document.getElementById('bookingDate').value) {
                document.getElementById('bookingDate').value = today;
            }

            const select = document.getElementById('bookingDoctorSelect');
            select.innerHTML = state.doctors.map(d => `
                <option value="${d.id}" ${doctorId === d.id ? 'selected' : ''}>
                    ${d.ho_ten} (${d.chuyen_khoa ? d.chuyen_khoa.ten_khoa : 'Đa khoa'}) - Giá: ${formatVND(d.gia_kham)}
                </option>
            `).join('');

            if (state.currentUser) {
                document.getElementById('patientName').value = state.currentUser.ho_ten || '';
            }

            openModal('modalBooking');
        }

        async function handleBookingSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitBooking');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...`;

            const payload = {
                bac_si_id: document.getElementById('bookingDoctorSelect').value,
                ngay_kham: document.getElementById('bookingDate').value,
                gio_kham: document.getElementById('bookingTime').value,
                ho_ten: document.getElementById('patientName').value,
                so_dien_thoai: document.getElementById('patientPhone').value,
                trieu_chung: document.getElementById('patientSymptoms').value || 'Khám sức khỏe tổng quát',
            };

            try {
                const res = await fetch('/api/v1/lich-hen/dat-lich', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    showToast('Đăng ký lịch khám thành công! Mã: ' + data.du_lieu.ma_lich_hen, 'success');
                    closeModal('modalBooking');
                    document.getElementById('bookingForm').reset();
                    await loadAppointments();
                    switchTab('lichhen');
                } else {
                    const err = data.chi_tiet_loi ? Object.values(data.chi_tiet_loi).flat().join(', ') : data.thong_bao;
                    showToast(err || 'Đặt lịch thất bại', 'error');
                }
            } catch (err) {
                showToast('Lỗi kết nối máy chủ', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-paper-plane"></i> <span>Xác Nhận Đăng Ký Lịch Khám</span>`;
            }
        }

        // ================= APPOINTMENT ACTIONS =================
        async function updateAppointmentStatus(id, action) {
            try {
                const res = await fetch(`/api/v1/lich-hen/${id}/${action}`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${state.token}` }
                });
                const data = await res.json();
                if (data.thanh_cong) {
                    showToast('Thao tác thành công!', 'success');
                    await loadAppointments();
                } else {
                    showToast(data.thong_bao || 'Không thể thực hiện', 'error');
                }
            } catch (err) {
                showToast('Lỗi khi gửi yêu cầu', 'error');
            }
        }

        function openExamModal(appointmentId) {
            const app = state.appointments.find(a => a.id === appointmentId);
            if (!app) return;

            document.getElementById('examAppointmentId').value = appointmentId;
            document.getElementById('examAppointmentInfo').textContent = `Lịch hẹn: ${app.ma_lich_hen} - Bệnh nhân: ${app.benh_nhan?.ho_ten || ''}`;
            document.getElementById('examDiagnosis').value = '';
            document.getElementById('examAdvice').value = '';

            const container = document.getElementById('examServicesSelection');
            container.innerHTML = state.services.map(s => `
                <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="examServices" value="${s.id}" class="rounded text-teal-600 focus:ring-teal-500">
                        <span class="font-semibold text-slate-800">${s.ten_dich_vu}</span>
                    </div>
                    <span class="font-bold text-teal-700">${formatVND(s.don_gia)}</span>
                </label>
            `).join('');

            openModal('modalExam');
        }

        async function handleExamSubmit(e) {
            e.preventDefault();
            const appointmentId = document.getElementById('examAppointmentId').value;
            const diagnosis = document.getElementById('examDiagnosis').value;
            const advice = document.getElementById('examAdvice').value;

            const checkboxes = document.querySelectorAll('input[name="examServices"]:checked');
            const selectedServices = Array.from(checkboxes).map(cb => parseInt(cb.value));

            try {
                for (const dvId of selectedServices) {
                    await fetch('/api/v1/dich-vu/chi-dinh', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${state.token}`
                        },
                        body: JSON.stringify({ lich_hen_id: appointmentId, dich_vu_id: dvId, so_luong: 1 })
                    });
                }

                const res = await fetch(`/api/v1/lich-hen/${appointmentId}/hoan-thanh`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.token}`
                    },
                    body: JSON.stringify({ chuan_doan: diagnosis, loi_khuyen: advice })
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    showToast('Đã hoàn thành khám và tự động phát sinh hóa đơn!', 'success');
                    closeModal('modalExam');
                    await loadAppointments();
                    switchTab('hoadon');
                } else {
                    showToast(data.thong_bao || 'Không thể hoàn thành khám', 'error');
                }
            } catch (err) {
                showToast('Lỗi khi lưu kết quả khám', 'error');
            }
        }

        async function handlePayInvoice(invoiceId) {
            if (!confirm('Xác nhận thu tiền cho hóa đơn này?')) return;

            try {
                const res = await fetch(`/api/v1/hoa-don/${invoiceId}/thanh-toan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.token}`
                    },
                    body: JSON.stringify({ phuong_thuc_thanh_toan: 'TIEN_MAT' })
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    showToast('Thanh toán viện phí thành công!', 'success');
                    await loadAppointments();
                } else {
                    showToast(data.thong_bao || 'Yêu cầu quyền Quản trị viên (ADMIN)', 'error');
                }
            } catch (err) {
                showToast('Lỗi kết nối', 'error');
            }
        }

        // ================= ADMIN: CREATE DOCTOR =================
        function openCreateDoctorModal() {
            const select = document.getElementById('docChuyenKhoaSelect');
            select.innerHTML = state.specialties.map(sp => `
                <option value="${sp.id}">${sp.ten_khoa}</option>
            `).join('');
            openModal('modalCreateDoctor');
        }

        async function handleCreateDoctorSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitDoc');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Đang thêm bác sĩ...`;

            const payload = {
                ho_ten: document.getElementById('docHoTen').value.trim(),
                chuyen_khoa_id: document.getElementById('docChuyenKhoaSelect').value,
                hoc_vi: document.getElementById('docHocVi').value.trim() || 'Bác sĩ chuyên khoa',
                gia_kham: document.getElementById('docGiaKham').value,
                phong_kham: document.getElementById('docPhongKham').value.trim(),
                so_dien_thoai: document.getElementById('docSoDienThoai').value.trim(),
                kinh_nghiem: document.getElementById('docKinhNghiem').value.trim(),
            };

            try {
                const res = await fetch('/api/v1/bac-si', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.thanh_cong) {
                    showToast(`Đã thêm bác sĩ ${data.du_lieu.ho_ten} & tạo tài khoản tự động thành công!`, 'success');
                    closeModal('modalCreateDoctor');
                    document.getElementById('formCreateDoctor').reset();
                    await loadDoctors();
                } else {
                    const err = data.chi_tiet_loi ? Object.values(data.chi_tiet_loi).flat().join(', ') : data.thong_bao;
                    showToast(err || 'Không thể thêm bác sĩ', 'error');
                }
            } catch (err) {
                showToast('Lỗi khi gửi yêu cầu', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-plus-circle"></i> <span>Thêm Bác Sĩ & Tạo Tài Khoản</span>`;
            }
        }

        // Initialize Application on DOM Ready
        document.addEventListener('DOMContentLoaded', () => {
            checkAuth();
        });
    </script>
</body>
</html>
