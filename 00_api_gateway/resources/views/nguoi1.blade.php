<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Khám Đa Khoa - Phân Hệ Người 1 (Tài Khoản & Bác Sĩ)</title>
    
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
                            600: '#0284c7', // Mau chu dao y te
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .tab-active {
            color: #0284c7;
            border-bottom: 2px solid #0284c7;
            font-weight: 700;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800">

    <!-- ============================================================= -->
    <!-- TOP NAVIGATION BAR -->
    <!-- ============================================================= -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-medical-600 flex items-center justify-center text-white shadow-md shadow-medical-500/30">
                        <i class="fa-solid fa-hospital text-xl"></i>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold tracking-tight text-slate-900 block leading-tight">
                            Phòng Khám Đa Khoa
                        </span>
                        <span class="text-xs font-semibold text-medical-600 uppercase tracking-wider">
                            Phân Hệ Người 1: Xác Thực & Bác Sĩ
                        </span>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="hidden md:flex space-x-1 items-center">
                    <button onclick="switchView('view-doctors')" id="nav-doctors" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg transition-colors tab-active">
                        <i class="fa-solid fa-user-doctor mr-1.5"></i> Danh Mục Bác Sĩ
                    </button>
                    <button onclick="switchView('view-admin-doctors')" id="nav-admin-doctors" class="tab-btn px-4 py-2 text-sm font-semibold text-slate-600 hover:text-medical-600 rounded-lg transition-colors admin-only hidden">
                        <i class="fa-solid fa-user-gear mr-1.5"></i> Quản Trị Bác Sĩ
                    </button>
                    <button onclick="switchView('view-admin-accounts')" id="nav-admin-accounts" class="tab-btn px-4 py-2 text-sm font-semibold text-slate-600 hover:text-medical-600 rounded-lg transition-colors admin-only hidden">
                        <i class="fa-solid fa-users-gear mr-1.5"></i> Quản Lý Tài Khoản
                    </button>
                </div>

                <!-- User Session & Auth Buttons -->
                <div class="flex items-center space-x-3">
                    <!-- Guest View -->
                    <div id="guest-nav-actions" class="flex items-center space-x-2">
                        <a href="/dang-nhap" class="px-3.5 py-1.5 text-sm font-semibold text-medical-700 bg-medical-50 hover:bg-medical-100 rounded-lg transition border border-medical-200 inline-flex items-center">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Đăng Nhập
                        </a>
                        <a href="/dang-ky" class="px-3.5 py-1.5 text-sm font-semibold text-white bg-medical-600 hover:bg-medical-700 rounded-lg shadow-sm transition inline-flex items-center">
                            <i class="fa-solid fa-user-plus mr-1.5"></i> Đăng Ký
                        </a>
                    </div>

                    <!-- Logged-in User Profile Dropdown -->
                    <div id="user-nav-actions" class="hidden flex items-center space-x-3">
                        <div class="flex items-center space-x-2 text-sm bg-slate-100 py-1.5 px-3 rounded-lg border border-slate-200">
                            <div class="w-7 h-7 rounded-full bg-medical-600 text-white flex items-center justify-center font-bold text-xs" id="user-avatar-text">
                                AD
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-xs block" id="user-display-name">Quản Trị Viên</span>
                                <span class="text-[10px] uppercase font-semibold px-1.5 py-0.2 rounded bg-medical-100 text-medical-800" id="user-display-role">ADMIN</span>
                            </div>
                        </div>

                        <button onclick="openModal('modal-change-password')" title="Đổi Mật Khẩu" class="p-2 text-slate-500 hover:text-medical-600 rounded-lg hover:bg-slate-100 transition">
                            <i class="fa-solid fa-key"></i>
                        </button>

                        <button onclick="handleLogout()" title="Đăng Xuất" class="p-2 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================= -->
    <!-- MAIN CONTAINER -->
    <!-- ============================================================= -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- MÀN HÌNH 1: DANH MỤC BÁC SĨ & CHUYÊN KHOA (PUBLIC / BỆNH NHÂN) -->
        <section id="view-doctors" class="app-view">
            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-medical-700 via-medical-600 to-sky-500 rounded-2xl p-6 sm:p-8 text-white shadow-lg shadow-medical-600/20 mb-8">
                <div class="max-w-2xl">
                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-3">
                        Đội Ngũ Chuyên Gia Y Tế
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
                        Đội Ngũ Bác Sĩ Chuyên Khoa Giàu Kinh Nghiệm
                    </h2>
                    <p class="text-medical-100 text-sm sm:text-base leading-relaxed">
                        Tận tâm chăm sóc sức khỏe người bệnh với trang thiết bị y khoa hiện đại và phác đồ điều trị tiên tiến.
                    </p>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="doctor-search-input" oninput="filterDoctors()" placeholder="Tìm theo tên bác sĩ..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition">
                </div>

                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:inline">Chuyên Khoa:</span>
                    <select id="doctor-specialty-filter" onchange="filterDoctors()" class="w-full sm:w-60 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition">
                        <option value="">Tất cả chuyên khoa</option>
                    </select>
                </div>
            </div>

            <!-- Doctor Cards Grid -->
            <div id="doctors-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Skeleton Loader -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm animate-pulse flex flex-col justify-between h-64">
                    <div class="space-y-3">
                        <div class="w-12 h-12 bg-slate-200 rounded-full"></div>
                        <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                        <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MÀN HÌNH 2: TRANG QUẢN TRỊ BÁC SĨ (DÀNH CHO ADMIN) -->
        <section id="view-admin-doctors" class="app-view hidden">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <!-- Table Header & Action -->
                <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Quản Lý Hồ Sơ Bác Sĩ</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Danh sách bác sĩ công tác, phân bổ phòng khám và giá khám niêm yết.</p>
                    </div>
                    <button onclick="openAddDoctorModal()" class="inline-flex items-center px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-user-plus mr-2"></i> Thêm Bác Sĩ Mới
                    </button>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100/75 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3 px-4">Mã BS</th>
                                <th class="py-3 px-4">Họ và Tên</th>
                                <th class="py-3 px-4">Chuyên Khoa</th>
                                <th class="py-3 px-4">Phòng Khám</th>
                                <th class="py-3 px-4">Giá Khám (VNĐ)</th>
                                <th class="py-3 px-4">Trạng Thái</th>
                                <th class="py-3 px-4 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="admin-doctors-tbody" class="divide-y divide-slate-200 text-slate-700">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- MÀN HÌNH 3: QUẢN LÝ TÀI KHOẢN NGƯỜI DÙNG (DÀNH CHO ADMIN) -->
        <section id="view-admin-accounts" class="app-view hidden">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Danh Sách Tài Khoản Toàn Hệ Thống</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kiểm soát danh tính, phân quyền và khóa/mở khóa tài khoản người dùng.</p>
                    </div>
                    <button onclick="loadUserAccounts()" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                        <i class="fa-solid fa-arrows-rotate mr-1.5"></i> Làm Mới Dữ Liệu
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100/75 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Tên Đăng Nhập</th>
                                <th class="py-3 px-4">Họ và Tên</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Vai Trò</th>
                                <th class="py-3 px-4">Trạng Thái</th>
                                <th class="py-3 px-4 text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="admin-accounts-tbody" class="divide-y divide-slate-200 text-slate-700">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <!-- ============================================================= -->
    <!-- FOOTER -->
    <!-- ============================================================= -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
            <p>© 2026 Hệ Thống Phòng Khám Đa Khoa Microservices - Xây dựng với Laravel 12 & Laragon.</p>
            <p class="mt-1 font-mono text-slate-400">Gateway: http://127.0.0.1:8000 | Microservice 1: http://127.0.0.1:8001</p>
        </div>
    </footer>

    <!-- ============================================================= -->
    <!-- MODAL 1: ĐĂNG NHẬP -->
    <!-- ============================================================= -->
    <div id="modal-login" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Đăng Nhập Hệ Thống</h3>
                    <p class="text-xs text-slate-500">Vui lòng đăng nhập để thực hiện các chức năng.</p>
                </div>
                <button onclick="closeModal('modal-login')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="handleLogin(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tên Đăng Nhập / Email</label>
                    <input type="text" id="login-username" required placeholder="admin hoặc email..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Mật Khẩu</label>
                    <input type="password" id="login-password" required placeholder="Nhập mật khẩu..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <!-- Gợi ý tài khoản demo nhanh -->
                <div class="bg-medical-50/70 p-3 rounded-xl border border-medical-100 text-xs">
                    <div class="font-bold text-medical-800 mb-1.5 flex items-center">
                        <i class="fa-solid fa-lightbulb mr-1.5 text-amber-500"></i> Gợi ý tài khoản demo nhanh:
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="fillLoginForm('admin', 'Admin@123')" class="px-2 py-1 bg-white rounded border border-medical-200 text-slate-700 font-mono hover:bg-medical-100">
                            admin (Admin@123)
                        </button>
                        <button type="button" onclick="fillLoginForm('bstuan', '123456')" class="px-2 py-1 bg-white rounded border border-medical-200 text-slate-700 font-mono hover:bg-medical-100">
                            bstuan (123456)
                        </button>
                        <button type="button" onclick="fillLoginForm('benhnhan', '123456')" class="px-2 py-1 bg-white rounded border border-medical-200 text-slate-700 font-mono hover:bg-medical-100">
                            benhnhan (123456)
                        </button>
                    </div>
                </div>

                <button type="submit" id="btn-login-submit" class="w-full py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md shadow-medical-600/20 transition">
                    Đăng Nhập
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 2: ĐĂNG KÝ BỆNH NHÂN -->
    <!-- ============================================================= -->
    <div id="modal-register" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Đăng Ký Tài Khoản Bệnh Nhân</h3>
                    <p class="text-xs text-slate-500">Tạo tài khoản để theo dõi lịch khám bệnh cá nhân.</p>
                </div>
                <button onclick="closeModal('modal-register')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="handleRegister(event)" class="p-6 space-y-3.5 max-h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tên Đăng Nhập</label>
                        <input type="text" id="reg-username" required placeholder="bn_cuong..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Email</label>
                        <input type="email" id="reg-email" required placeholder="cuong@gmail.com..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Mật Khẩu (Ít nhất 6 ký tự)</label>
                    <input type="password" id="reg-password" minlength="6" required placeholder="Nhập mật khẩu..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Họ và Tên</label>
                        <input type="text" id="reg-fullname" required placeholder="Nguyễn Văn A..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Số Điện Thoại</label>
                        <input type="tel" id="reg-phone" placeholder="0901234567..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Giới Tính</label>
                        <select id="reg-gender" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Ngày Sinh</label>
                        <input type="date" id="reg-birthday" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Địa Chỉ</label>
                    <input type="text" id="reg-address" placeholder="Quận Ninh Kiều, Cần Thơ..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <button type="submit" id="btn-register-submit" class="w-full mt-2 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md transition">
                    Đăng Ký Tài Khoản
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 3: ĐỔI MẬT KHẨU -->
    <!-- ============================================================= -->
    <div id="modal-change-password" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-900">Thay Đổi Mật Khẩu</h3>
                <button onclick="closeModal('modal-change-password')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="handleChangePassword(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Mật Khẩu Cũ</label>
                    <input type="password" id="cp-old-password" required placeholder="Nhập mật khẩu hiện tại..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Mật Khẩu Mới</label>
                    <input type="password" id="cp-new-password" minlength="6" required placeholder="Ít nhất 6 ký tự..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Xác Nhận Mật Khẩu Mới</label>
                    <input type="password" id="cp-confirm-password" minlength="6" required placeholder="Nhập lại mật khẩu mới..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <button type="submit" class="w-full py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md transition">
                    Xác Nhận Đổi Mật Khẩu
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 4: THÊM BÁC SĨ MỚI (ADMIN ONLY) -->
    <!-- ============================================================= -->
    <div id="modal-add-doctor" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Thêm Bác Sĩ Mới</h3>
                    <p class="text-xs text-slate-500">Tự động tạo tài khoản đăng nhập với vai trò BAC_SI.</p>
                </div>
                <button onclick="closeModal('modal-add-doctor')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="submitAddDoctor(event)" class="p-6 space-y-3.5">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Họ và Tên Bác Sĩ</label>
                    <input type="text" id="doc-add-name" required placeholder="BS. CKII Trần Minh..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Chuyên Khoa</label>
                        <select id="doc-add-specialty" required class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                            <!-- Dynamic -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Học Vị</label>
                        <input type="text" id="doc-add-degree" placeholder="BS CKII, ThS.BS..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Giá Khám (VNĐ)</label>
                        <input type="number" id="doc-add-price" required value="200000" min="0" step="10000" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Số Phòng Khám</label>
                        <input type="text" id="doc-add-room" required placeholder="P201..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Số Điện Thoại</label>
                        <input type="tel" id="doc-add-phone" placeholder="0901112233..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Email</label>
                        <input type="email" id="doc-add-email" placeholder="bstuan@phongkham.vn..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-xs text-slate-500">
                    <i class="fa-solid fa-circle-info mr-1 text-medical-600"></i>
                    Tài khoản đăng nhập sẽ tự động tạo với mật khẩu mặc định là: <strong class="font-mono text-slate-700">123456</strong>
                </div>

                <button type="submit" class="w-full py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md transition">
                    Lưu Bác Sĩ & Tạo Tài Khoản
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL 5: CHỈNH SỬA HỒ SƠ BÁC SĨ (ADMIN ONLY) -->
    <!-- ============================================================= -->
    <div id="modal-edit-doctor" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Cập Nhật Hồ Sơ Bác Sĩ</h3>
                    <p class="text-xs text-slate-500" id="edit-doc-subtitle">Mã Bác Sĩ: #--</p>
                </div>
                <button onclick="closeModal('modal-edit-doctor')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form onsubmit="submitEditDoctor(event)" class="p-6 space-y-3.5">
                <input type="hidden" id="edit-doc-id">

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Họ và Tên Bác Sĩ</label>
                    <input type="text" id="edit-doc-name" required class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Chuyên Khoa</label>
                        <select id="edit-doc-specialty" required class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                            <!-- Dynamic -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Học Vị</label>
                        <input type="text" id="edit-doc-degree" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Giá Khám (VNĐ)</label>
                        <input type="number" id="edit-doc-price" required min="0" step="10000" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Số Phòng Khám</label>
                        <input type="text" id="edit-doc-room" required class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kinh Nghiệm Chuyên Môn</label>
                    <input type="text" id="edit-doc-exp" placeholder="Ví dụ: 15 năm kinh nghiệm nội tổng quát..." class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500">
                </div>

                <button type="submit" class="w-full py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-lg shadow-md transition">
                    Cập Nhật Thông Tin Bác Sĩ
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- JAVASCRIPT LOGIC CLIENT SIDE -->
    <!-- ============================================================= -->
    <script>
        // CẤU HÌNH KẾT NỐI API (Qua API Gateway hoặc Service 1)
        const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

        // TRẠNG THÁI TOÀN CỤC TRÊN BROWSER
        let AppData = {
            doctors: [],
            specialties: [],
            accounts: [],
            currentUser: null,
            token: localStorage.getItem('token') || ''
        };

        // KHỞI ĐỘNG KHI TẢI TRANG
        document.addEventListener('DOMContentLoaded', async () => {
            initAuthSession();
            await loadSpecialties();
            await loadDoctors();

            // Nếu người dùng đang là Admin thì load tài khoản
            if (isAdmin()) {
                loadUserAccounts();
            }
        });

        // 1. QUẢN LÝ PHIÊN ĐĂNG NHẬP & LOCALSTORAGE
        function initAuthSession() {
            const savedUser = sessionStorage.getItem('user_info') || localStorage.getItem('user_info');
            const savedToken = sessionStorage.getItem('token') || localStorage.getItem('token');
            const savedRole = sessionStorage.getItem('role') || localStorage.getItem('role');

            if (savedToken && savedUser) {
                try {
                    AppData.currentUser = JSON.parse(savedUser);
                    AppData.token = savedToken;
                    renderUserHeader(AppData.currentUser);
                } catch (e) {
                    handleLogout();
                }
            } else {
                renderGuestHeader();
            }
        }

        function isAdmin() {
            return AppData.currentUser && (AppData.currentUser.vai_tro === 'ADMIN' || localStorage.getItem('role') === 'ADMIN');
        }

        function renderUserHeader(user) {
            document.getElementById('guest-nav-actions').classList.add('hidden');
            document.getElementById('user-nav-actions').classList.remove('hidden');

            document.getElementById('user-display-name').textContent = user.ho_ten || user.ten_dang_nhap;
            document.getElementById('user-display-role').textContent = user.vai_tro;

            const initials = (user.ho_ten || user.ten_dang_nhap || 'AD').substring(0, 2).toUpperCase();
            document.getElementById('user-avatar-text').textContent = initials;

            // Hiển thị các tab dành riêng cho Admin
            if (user.vai_tro === 'ADMIN') {
                document.querySelectorAll('.admin-only').forEach(el => el.classList.remove('hidden'));
            } else {
                document.querySelectorAll('.admin-only').forEach(el => el.classList.add('hidden'));
            }
        }

        function renderGuestHeader() {
            document.getElementById('guest-nav-actions').classList.remove('hidden');
            document.getElementById('user-nav-actions').classList.add('hidden');
            document.querySelectorAll('.admin-only').forEach(el => el.classList.add('hidden'));
        }

        function fillLoginForm(u, p) {
            document.getElementById('login-username').value = u;
            document.getElementById('login-password').value = p;
        }

        // 2. XỬ LÝ ĐĂNG NHẬP (handleLogin)
        async function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('login-username').value.trim();
            const password = document.getElementById('login-password').value;

            showLoading('Đang xác thực tài khoản...');

            try {
                const res = await fetch(`${API_BASE_URL}/xac-thuc/dang-nhap`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ten_dang_nhap: username, mat_khau: password })
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    const data = json.du_lieu;
                    AppData.token = data.token;
                    AppData.currentUser = data.nguoi_dung;

                    // Lưu vào localStorage
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user_info', JSON.stringify(data.nguoi_dung));
                    localStorage.setItem('role', data.vai_tro);

                    renderUserHeader(AppData.currentUser);
                    closeModal('modal-login');

                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng nhập thành công!',
                        text: `Xin chào ${data.nguoi_dung.ho_ten} (${data.vai_tro})`,
                        timer: 1800,
                        showConfirmButton: false
                    });

                    if (data.vai_tro === 'ADMIN') {
                        loadUserAccounts();
                    }
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

        // 3. XỬ LÝ ĐĂNG KÝ BỆNH NHÂN (handleRegister)
        async function handleRegister(e) {
            e.preventDefault();
            const payload = {
                ten_dang_nhap: document.getElementById('reg-username').value.trim(),
                email: document.getElementById('reg-email').value.trim(),
                mat_khau: document.getElementById('reg-password').value,
                ho_ten: document.getElementById('reg-fullname').value.trim(),
                so_dien_thoai: document.getElementById('reg-phone').value.trim()
            };

            showLoading('Đang tạo hồ sơ bệnh nhân...');

            try {
                const res = await fetch(`${API_BASE_URL}/xac-thuc/dang-ky`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    closeModal('modal-register');
                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng ký thành công!',
                        text: 'Tài khoản bệnh nhân đã sẵn sàng. Vui lòng đăng nhập.',
                        confirmButtonColor: '#0284c7'
                    });
                    openModal('modal-login');
                    fillLoginForm(payload.ten_dang_nhap, payload.mat_khau);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng ký thất bại',
                        text: json.thong_diep || 'Thông tin tài khoản đã tồn tại hoặc không hợp lệ.'
                    });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối máy chủ.' });
            }
        }

        // 4. XỬ LÝ ĐĂNG XUẤT (handleLogout)
        async function handleLogout() {
            if (AppData.token) {
                try {
                    await fetch(`${API_BASE_URL}/xac-thuc/dang-xuat`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${AppData.token}`,
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {}
            }

            localStorage.removeItem('token');
            localStorage.removeItem('user_info');
            localStorage.removeItem('role');

            AppData.currentUser = null;
            AppData.token = '';

            renderGuestHeader();
            switchView('view-doctors');

            Swal.fire({
                icon: 'success',
                title: 'Đã đăng xuất thành công',
                text: 'Đang chuyển về trang đăng nhập...',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/dang-nhap';
            });
        }

        // 5. ĐỔI MẬT KHẨU (handleChangePassword)
        async function handleChangePassword(e) {
            e.preventDefault();
            const oldPass = document.getElementById('cp-old-password').value;
            const newPass = document.getElementById('cp-new-password').value;
            const confirmPass = document.getElementById('cp-confirm-password').value;

            if (newPass !== confirmPass) {
                Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: 'Mật khẩu mới và xác nhận mật khẩu không khớp.' });
                return;
            }

            showLoading('Đang đổi mật khẩu...');

            try {
                const res = await fetch(`${API_BASE_URL}/xac-thuc/doi-mat-khau`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ mat_khau_cu: oldPass, mat_khau_moi: newPass })
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    closeModal('modal-change-password');
                    Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Mật khẩu đã được cập nhật.' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Thất bại', text: json.thong_diep || 'Mật khẩu cũ không chính xác.' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối máy chủ.' });
            }
        }

        // 6. TẢI DANH MỤC CHUYÊN KHOA (loadSpecialties)
        async function loadSpecialties() {
            try {
                const res = await fetch(`${API_BASE_URL}/bac-si/chuyen-khoa`);
                const json = await res.json();

                if (res.ok && json.thanh_cong) {
                    AppData.specialties = json.du_lieu || [];

                    // Điền vào Filter Dropdown
                    const filterSelect = document.getElementById('doctor-specialty-filter');
                    const addDocSelect = document.getElementById('doc-add-specialty');
                    const editDocSelect = document.getElementById('edit-doc-specialty');

                    let opts = '<option value="">Tất cả chuyên khoa</option>';
                    let formOpts = '';

                    AppData.specialties.forEach(sp => {
                        const name = sp.ten_khoa || sp.ten_chuyen_khoa;
                        opts += `<option value="${sp.id}">${name}</option>`;
                        formOpts += `<option value="${sp.id}">${name}</option>`;
                    });

                    if (filterSelect) filterSelect.innerHTML = opts;
                    if (addDocSelect) addDocSelect.innerHTML = formOpts;
                    if (editDocSelect) editDocSelect.innerHTML = formOpts;
                }
            } catch (err) {
                console.error('Loi load chuyen khoa:', err);
            }
        }

        // 7. TẢI DANH SÁCH BÁC SĨ (loadDoctors)
        async function loadDoctors() {
            try {
                const res = await fetch(`${API_BASE_URL}/bac-si`);
                const json = await res.json();

                if (res.ok && json.thanh_cong) {
                    AppData.doctors = json.du_lieu || [];
                    renderDoctorsCards(AppData.doctors);
                    renderAdminDoctorsTable(AppData.doctors);
                }
            } catch (err) {
                console.error('Loi load danh sach bac si:', err);
            }
        }

        // Render Doctor Cards Grid
        function renderDoctorsCards(doctors) {
            const container = document.getElementById('doctors-grid');
            if (!container) return;

            if (doctors.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="fa-solid fa-user-doctor text-4xl mb-3 text-slate-300"></i>
                        <p class="text-base font-semibold">Không tìm thấy bác sĩ nào phù hợp.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = doctors.map(doc => {
                const specName = doc.chuyen_khoa ? (doc.chuyen_khoa.ten_khoa || doc.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const isWorking = (doc.trang_thai === 'DANG_LAM_VIEC' || doc.trang_thai == 1);
                const statusBadge = isWorking 
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Đang làm việc</span>'
                    : '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>Nghỉ phép</span>';

                const price = Number(doc.gia_kham || 200000).toLocaleString('vi-VN');

                return `
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-medical-300 transition flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-medical-500 to-sky-600 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-medical-500/20">
                                    <i class="fa-solid fa-user-doctor"></i>
                                </div>
                                ${statusBadge}
                            </div>

                            <h4 class="font-extrabold text-slate-900 text-base leading-tight mb-1">${doc.ho_ten}</h4>
                            <p class="text-xs font-semibold text-medical-700 bg-medical-50 px-2 py-0.5 rounded inline-block mb-3">${specName}</p>

                            <div class="space-y-1.5 text-xs text-slate-600 mb-4 border-t border-slate-100 pt-3">
                                <div class="flex items-center"><i class="fa-solid fa-graduation-cap w-5 text-slate-400"></i> ${doc.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                                <div class="flex items-center"><i class="fa-solid fa-door-open w-5 text-slate-400"></i> Phòng khám: <strong class="ml-1 text-slate-800">${doc.phong_kham || 'P201'}</strong></div>
                                <div class="flex items-center"><i class="fa-solid fa-briefcase w-5 text-slate-400"></i> ${doc.kinh_nghiem || '10 năm kinh nghiệm'}</div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-3 flex items-center justify-between mt-2">
                            <div>
                                <span class="text-[11px] text-slate-400 uppercase font-semibold block">Giá khám:</span>
                                <span class="text-base font-extrabold text-medical-600">${price} đ</span>
                            </div>
                            <button onclick="bookAppointmentPrompt('${doc.ho_ten}', ${doc.id})" class="px-3.5 py-1.5 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                Đặt Khám
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // 8. TÌM KIẾM & LỌC BÁC SĨ (filterDoctors)
        function filterDoctors() {
            const keyword = (document.getElementById('doctor-search-input').value || '').toLowerCase();
            const specId = document.getElementById('doctor-specialty-filter').value;

            const filtered = AppData.doctors.filter(doc => {
                const matchName = doc.ho_ten.toLowerCase().includes(keyword) || (doc.ma_bac_si && doc.ma_bac_si.toLowerCase().includes(keyword));
                const matchSpec = !specId || (doc.chuyen_khoa_id == specId);
                return matchName && matchSpec;
            });

            renderDoctorsCards(filtered);
        }

        // 9. RENDER BẢNG QUẢN TRỊ BÁC SĨ (Admin Table)
        function renderAdminDoctorsTable(doctors) {
            const tbody = document.getElementById('admin-doctors-tbody');
            if (!tbody) return;

            tbody.innerHTML = doctors.map(doc => {
                const specName = doc.chuyen_khoa ? (doc.chuyen_khoa.ten_khoa || doc.chuyen_khoa.ten_chuyen_khoa) : 'Khoa Nội';
                const isWorking = (doc.trang_thai === 'DANG_LAM_VIEC' || doc.trang_thai == 1);
                const statusBadge = isWorking
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">Đang làm việc</span>'
                    : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600">Nghỉ phép</span>';

                const price = Number(doc.gia_kham || 200000).toLocaleString('vi-VN');

                return `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-xs text-medical-700">${doc.ma_bac_si || ('BS' + doc.id)}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">${doc.ho_ten}</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 bg-slate-100 rounded text-xs">${specName}</span></td>
                        <td class="py-3 px-4 font-semibold text-slate-700">${doc.phong_kham || 'P201'}</td>
                        <td class="py-3 px-4 font-bold text-medical-600">${price} đ</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center space-x-1.5 whitespace-nowrap">
                            <button onclick="openEditDoctorModal(${doc.id})" class="px-2.5 py-1 text-xs bg-sky-50 text-sky-700 hover:bg-sky-100 rounded-lg font-semibold transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Sửa
                            </button>
                            <button onclick="toggleDoctorStatus(${doc.id}, '${isWorking ? 'NGHI_VIEC' : 'DANG_LAM_VIEC'}')" class="px-2.5 py-1 text-xs ${isWorking ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'} rounded-lg font-semibold transition">
                                ${isWorking ? '<i class="fa-solid fa-user-slash mr-1"></i> Nghỉ Phép' : '<i class="fa-solid fa-user-check mr-1"></i> Làm Việc'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // 10. THÊM BÁC SĨ MỚI (openAddDoctorModal / submitAddDoctor)
        function openAddDoctorModal() {
            if (!isAdmin()) {
                Swal.fire({ icon: 'error', title: 'Từ chối', text: 'Chỉ Quản trị viên (ADMIN) mới có quyền thêm bác sĩ.' });
                return;
            }
            openModal('modal-add-doctor');
        }

        async function submitAddDoctor(e) {
            e.preventDefault();
            const payload = {
                ho_ten: document.getElementById('doc-add-name').value.trim(),
                chuyen_khoa_id: Number(document.getElementById('doc-add-specialty').value),
                hoc_vi: document.getElementById('doc-add-degree').value.trim(),
                gia_kham: Number(document.getElementById('doc-add-price').value),
                phong_kham: document.getElementById('doc-add-room').value.trim(),
                so_dien_thoai: document.getElementById('doc-add-phone').value.trim(),
                email: document.getElementById('doc-add-email').value.trim()
            };

            showLoading('Đang tạo hồ sơ bác sĩ...');

            try {
                const res = await fetch(`${API_BASE_URL}/bac-si`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    closeModal('modal-add-doctor');
                    Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Bác sĩ mới đã được thêm và cấp tài khoản.' });
                    await loadDoctors();
                    loadUserAccounts();
                } else {
                    Swal.fire({ icon: 'error', title: 'Thất bại', text: json.thong_diep || 'Không thể tạo bác sĩ mới.' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối máy chủ.' });
            }
        }

        // 11. CHỈNH SỬA BÁC SĨ (openEditDoctorModal / submitEditDoctor)
        function openEditDoctorModal(doctorId) {
            const doc = AppData.doctors.find(d => d.id === doctorId);
            if (!doc) return;

            document.getElementById('edit-doc-id').value = doc.id;
            document.getElementById('edit-doc-subtitle').textContent = `Mã: ${doc.ma_bac_si || ('BS' + doc.id)}`;
            document.getElementById('edit-doc-name').value = doc.ho_ten;
            document.getElementById('edit-doc-specialty').value = doc.chuyen_khoa_id;
            document.getElementById('edit-doc-degree').value = doc.hoc_vi || '';
            document.getElementById('edit-doc-price').value = doc.gia_kham || 200000;
            document.getElementById('edit-doc-room').value = doc.phong_kham || 'P201';
            document.getElementById('edit-doc-exp').value = doc.kinh_nghiem || '';

            openModal('modal-edit-doctor');
        }

        async function submitEditDoctor(e) {
            e.preventDefault();
            const id = document.getElementById('edit-doc-id').value;
            const payload = {
                ho_ten: document.getElementById('edit-doc-name').value.trim(),
                chuyen_khoa_id: Number(document.getElementById('edit-doc-specialty').value),
                hoc_vi: document.getElementById('edit-doc-degree').value.trim(),
                gia_kham: Number(document.getElementById('edit-doc-price').value),
                phong_kham: document.getElementById('edit-doc-room').value.trim(),
                kinh_nghiem: document.getElementById('edit-doc-exp').value.trim()
            };

            showLoading('Đang cập nhật...');

            try {
                const res = await fetch(`${API_BASE_URL}/bac-si/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    closeModal('modal-edit-doctor');
                    Swal.fire({ icon: 'success', title: 'Cập nhật thành công!', text: 'Hồ sơ bác sĩ đã được lưu lại.' });
                    await loadDoctors();
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: json.thong_diep || 'Không thể cập nhật hồ sơ.' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Lỗi kết nối máy chủ.' });
            }
        }

        // Đổi trạng thái Bác Sĩ (Đang làm việc / Nghỉ việc)
        async function toggleDoctorStatus(docId, newStatus) {
            showLoading('Đang cập nhật trạng thái...');

            try {
                const res = await fetch(`${API_BASE_URL}/bac-si/${docId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ trang_thai: newStatus })
                });

                Swal.close();
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Thành công', text: 'Trạng thái công tác của bác sĩ đã thay đổi.', timer: 1200, showConfirmButton: false });
                    await loadDoctors();
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể cập nhật trạng thái bác sĩ.' });
            }
        }

        // 12. QUẢN LÝ TÀI KHOẢN NGƯỜI DÙNG (loadUserAccounts / toggleAccountStatus)
        async function loadUserAccounts() {
            if (!isAdmin()) return;

            try {
                const res = await fetch(`${API_BASE_URL}/tai-khoan`, {
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();
                if (res.ok && json.thanh_cong) {
                    AppData.accounts = json.du_lieu || [];
                    renderAdminAccountsTable(AppData.accounts);
                }
            } catch (err) {
                console.error('Loi load danh sach tai khoan:', err);
            }
        }

        function renderAdminAccountsTable(accounts) {
            const tbody = document.getElementById('admin-accounts-tbody');
            if (!tbody) return;

            tbody.innerHTML = accounts.map(acc => {
                const role = acc.vai_tro ? (acc.vai_tro.ma_vai_tro || 'BENH_NHAN') : 'BENH_NHAN';
                const roleBadge = role === 'ADMIN' 
                    ? '<span class="px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800">ADMIN</span>'
                    : (role === 'BAC_SI' 
                        ? '<span class="px-2 py-0.5 rounded text-xs font-bold bg-sky-100 text-sky-800">BÁC SĨ</span>'
                        : '<span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">BỆNH NHÂN</span>');

                const isActive = (acc.trang_thai === 'HOAT_DONG' || acc.trang_thai == 1);
                const statusBadge = isActive
                    ? '<span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1"></span>Hoạt Động</span>'
                    : '<span class="px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block mr-1"></span>Bị Khóa</span>';

                return `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono text-xs text-slate-500">#${acc.id}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">${acc.ten_dang_nhap}</td>
                        <td class="py-3 px-4 font-semibold text-slate-700">${acc.ho_ten}</td>
                        <td class="py-3 px-4 text-slate-500 text-xs">${acc.email}</td>
                        <td class="py-3 px-4">${roleBadge}</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <button onclick="toggleAccountStatus(${acc.id}, '${isActive ? 'BI_KHOA' : 'HOAT_DONG'}')" class="px-2.5 py-1 text-xs font-semibold rounded-lg transition ${isActive ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'}">
                                ${isActive ? '<i class="fa-solid fa-lock mr-1"></i> Khóa Tài Khoản' : '<i class="fa-solid fa-lock-open mr-1"></i> Mở Khóa'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function toggleAccountStatus(accountId, newStatus) {
            const actionText = newStatus === 'BI_KHOA' ? 'khóa' : 'mở khóa';
            const confirm = await Swal.fire({
                title: 'Xác nhận thao tác?',
                text: `Bạn có chắc chắn muốn ${actionText} tài khoản này không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            });

            if (!confirm.isConfirmed) return;

            showLoading('Đang xử lý...');

            try {
                const res = await fetch(`${API_BASE_URL}/tai-khoan/${accountId}/trang-thai`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${AppData.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ trang_thai: newStatus })
                });

                const json = await res.json();
                Swal.close();

                if (res.ok && json.thanh_cong) {
                    Swal.fire({ icon: 'success', title: 'Thành công', text: `Tài khoản đã được ${actionText}.`, timer: 1500, showConfirmButton: false });
                    await loadUserAccounts();
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: json.thong_diep || 'Không thể cập nhật trạng thái.' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối máy chủ.' });
            }
        }

        // Đặt lịch hẹn prompt nhanh
        function bookAppointmentPrompt(docName, docId) {
            if (!AppData.token) {
                Swal.fire({
                    title: 'Yêu cầu đăng nhập',
                    text: 'Bạn cần đăng ký hoặc đăng nhập tài khoản trước khi đặt lịch khám với ' + docName,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0284c7',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Đến màn hình Đăng Nhập / Đăng Ký',
                    cancelButtonText: 'Để sau'
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = '/dang-nhap';
                    }
                });
                return;
            }

            // Đã đăng nhập -> Chuyển đến Cổng Đặt Lịch với Bác sĩ này
            window.location.href = `/dashboard?bac_si_id=${docId}`;
        }

        // 13. TIỆN ÍCH MODAL & UI SWITCHER
        function switchView(viewId) {
            document.querySelectorAll('.app-view').forEach(v => v.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-active'));

            const activeView = document.getElementById(viewId);
            if (activeView) activeView.classList.remove('hidden');

            if (viewId === 'view-doctors') document.getElementById('nav-doctors').classList.add('tab-active');
            if (viewId === 'view-admin-doctors') document.getElementById('nav-admin-doctors').classList.add('tab-active');
            if (viewId === 'view-admin-accounts') document.getElementById('nav-admin-accounts').classList.add('tab-active');
        }

        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('hidden');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('hidden');
        }

        function showLoading(msg = 'Đang xử lý...') {
            Swal.fire({
                title: msg,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }
    </script>
</body>
</html>
