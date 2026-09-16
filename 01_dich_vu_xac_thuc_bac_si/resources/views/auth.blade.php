<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Xác Thực & Đăng Ký - Phòng Khám Đa Khoa</title>

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
        .active-tab-btn {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }
        .inactive-tab-btn {
            background-color: transparent;
            color: #64748b;
            font-weight: 600;
        }
        .inactive-tab-btn:hover {
            color: #0f172a;
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-slate-900/5 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:16px_16px]">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- CỘT TRÁI: BANNER THƯƠNG HIỆU & GIỚI THIỆU PHÒNG KHÁM -->
        <div class="lg:col-span-5 bg-gradient-to-br from-medical-700 via-medical-600 to-sky-500 p-8 sm:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Ornaments -->
            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full bg-medical-900/30 blur-2xl pointer-events-none"></div>

            <!-- Header Brand -->
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center space-x-3 mb-8 group">
                    <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-xl shadow-lg border border-white/30 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight text-white block leading-none">Phòng Khám Đa Khoa</span>
                        <span class="text-[11px] text-sky-200 tracking-wider font-medium uppercase mt-0.5 block">Hệ Thống Microservices 2026</span>
                    </div>
                </a>

                <div class="space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/15 backdrop-blur-sm border border-white/20 text-sky-100 uppercase tracking-wider">
                        Cổng Xác Thực & Quản Lý
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                        Chăm Sóc Sức Khỏe Toàn Diện Cho Bạn & Gia Đình
                    </h2>
                    <p class="text-medical-100 text-sm leading-relaxed">
                        Đặt lịch khám trực tuyến, liên kết hồ sơ bệnh án điện tử và nhận kết quả cận lâm sàng nhanh chóng, chính xác.
                    </p>
                </div>
            </div>

            <!-- Features Badges -->
            <div class="relative z-10 space-y-3 my-8">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/15">
                    <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white">Bác Sĩ Chuyên Khoa Hàng Đầu</div>
                        <div class="text-[11px] text-sky-200">Đội ngũ bác sĩ giàu kinh nghiệm công tác các viện lớn</div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/15">
                    <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white">Thuật Toán Chống Trùng Lịch</div>
                        <div class="text-[11px] text-sky-200">Bảo đảm chính xác khung giờ khám, không chờ đợi</div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/15">
                    <div class="w-8 h-8 rounded-lg bg-sky-400/20 text-sky-200 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white">Bảo Mật Thông Tin Y Tế</div>
                        <div class="text-[11px] text-sky-200">Xác thực tập trung Sanctum Token tại API Gateway</div>
                    </div>
                </div>
            </div>


        </div>

        <!-- CỘT PHẢI: FORM ĐĂNG NHẬP / ĐĂNG KÝ (TAB SWITCHER) -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-center bg-white">
            
            <!-- Tab Buttons Switcher -->
            <div class="flex p-1.5 bg-slate-100 rounded-2xl mb-6 max-w-sm mx-auto w-full">
                <button id="tab-btn-login" onclick="chuyenTabAuth('login')" class="flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center active-tab-btn">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Đăng Nhập
                </button>
                <button id="tab-btn-register" onclick="chuyenTabAuth('register')" class="flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center inactive-tab-btn">
                    <i class="fa-solid fa-user-plus mr-2"></i> Đăng Ký Bệnh Nhân
                </button>
            </div>


            <!-- ========================================================= -->
            <!-- PHẦN 1: FORM ĐĂNG NHẬP -->
            <!-- ========================================================= -->
            <div id="section-login" class="space-y-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Đăng Nhập Tài Khoản</h3>
                    <p class="text-xs text-slate-500 mt-1">Sử dụng tài khoản Quản trị viên, Bác sĩ hoặc Bệnh nhân để tiếp tục.</p>
                </div>

                <form onsubmit="xuLyDangNhap(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tên Đăng Nhập hoặc Email
                        </label>
                        <div class="relative">
                            <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="input-login-username" required placeholder="Nhập tên đăng nhập hoặc email..." class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Mật Khẩu</label>
                            <button type="button" onclick="quenMatKhauPrompt()" class="text-xs text-medical-600 hover:text-medical-700 font-semibold">Quên mật khẩu?</button>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" id="input-login-password" required placeholder="Nhập mật khẩu..." class="w-full pl-10 pr-11 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                            <button type="button" onclick="togglePasswordVisibility('input-login-password', 'eye-login-icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm">
                                <i class="fa-solid fa-eye" id="eye-login-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" class="w-4 h-4 text-medical-600 border-slate-300 rounded focus:ring-medical-500">
                        <label for="remember-me" class="ml-2 text-xs text-slate-600 select-none cursor-pointer">Ghi nhớ đăng nhập trên thiết bị này</label>
                    </div>

                    <button type="submit" id="btn-submit-login" class="w-full py-3 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-medical-600/25 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Đăng Nhập Ngay
                    </button>
                </form>

                <!-- KHỐI CHỌN NHANH TÀI KHOẢN DEMO (1-CLICK) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 mt-4">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center">
                            <i class="fa-solid fa-bolt text-amber-500 mr-1.5"></i> Đăng Nhập Nhanh (Demo Accounts)
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono">1-click fill & login</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="nhapNhanhTaiKhoan('admin', 'Admin@123', true)" class="p-2 bg-white hover:bg-medical-50 border border-slate-200 rounded-xl text-left transition flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">🛡️</span>
                            <div class="truncate">
                                <div class="text-xs font-bold text-slate-800">Admin</div>
                                <div class="text-[10px] text-slate-500 font-mono">admin / Admin@123</div>
                            </div>
                        </button>

                        <button type="button" onclick="nhapNhanhTaiKhoan('bstuan', '123456', true)" class="p-2 bg-white hover:bg-medical-50 border border-slate-200 rounded-xl text-left transition flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs font-bold">👨‍⚕️</span>
                            <div class="truncate">
                                <div class="text-xs font-bold text-slate-800">BS. Tuấn (Nội)</div>
                                <div class="text-[10px] text-slate-500 font-mono">bstuan / 123456</div>
                            </div>
                        </button>

                        <button type="button" onclick="nhapNhanhTaiKhoan('bslan', '123456', true)" class="p-2 bg-white hover:bg-medical-50 border border-slate-200 rounded-xl text-left transition flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-bold">👩‍⚕️</span>
                            <div class="truncate">
                                <div class="text-xs font-bold text-slate-800">BS. Lan (Nhi)</div>
                                <div class="text-[10px] text-slate-500 font-mono">bslan / 123456</div>
                            </div>
                        </button>

                        <button type="button" onclick="nhapNhanhTaiKhoan('benhnhan', '123456', true)" class="p-2 bg-white hover:bg-medical-50 border border-slate-200 rounded-xl text-left transition flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">🧑</span>
                            <div class="truncate">
                                <div class="text-xs font-bold text-slate-800">Bệnh Nhân</div>
                                <div class="text-[10px] text-slate-500 font-mono">benhnhan / 123456</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- PHẦN 2: FORM ĐĂNG KÝ BỆNH NHÂN -->
            <!-- ========================================================= -->
            <div id="section-register" class="space-y-4 hidden">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Đăng Ký Tài Khoản Bệnh Nhân</h3>
                    <p class="text-xs text-slate-500 mt-1">Đăng ký thành viên để tự do tra cứu bác sĩ và đặt lịch khám tiện lợi.</p>
                </div>

                <form onsubmit="xuLyDangKy(event)" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Họ và Tên <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="reg-fullname" required placeholder="Ví dụ: Nguyễn Văn An" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Tên Đăng Nhập <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="reg-username" required placeholder="bn_vanan" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="reg-email" required placeholder="vanan@gmail.com" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Số Điện Thoại
                            </label>
                            <input type="tel" id="reg-phone" placeholder="0901234567" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Mật Khẩu (Ít nhất 6 ký tự) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="reg-password" minlength="6" required placeholder="Nhập mật khẩu..." oninput="kiemTraDoManhMatKhau(this.value)" class="w-full pl-3.5 pr-10 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                                <button type="button" onclick="togglePasswordVisibility('reg-password', 'eye-reg-icon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                    <i class="fa-solid fa-eye" id="eye-reg-icon"></i>
                                </button>
                            </div>
                            <!-- Thanh đo độ mạnh mật khẩu -->
                            <div class="mt-1 h-1 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div id="pwd-strength-bar" class="h-full bg-rose-500 transition-all" style="width: 0%"></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nhập Lại Mật Khẩu <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="reg-confirm-password" minlength="6" required placeholder="Xác nhận mật khẩu..." class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-600 transition">
                        </div>
                    </div>

                    <div class="flex items-start mt-2">
                        <input type="checkbox" id="reg-terms" required class="mt-0.5 w-4 h-4 text-medical-600 border-slate-300 rounded focus:ring-medical-500">
                        <label for="reg-terms" class="ml-2 text-xs text-slate-600 leading-relaxed cursor-pointer select-none">
                            Tôi cam kết thông tin khai báo là chính xác và đồng ý với <a href="#" class="text-medical-600 font-semibold underline">Chính sách bảo mật y tế</a> của phòng khám.
                        </label>
                    </div>

                    <button type="submit" id="btn-submit-register" class="w-full py-3 bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-medical-600/25 transition-all flex items-center justify-center mt-3">
                        <i class="fa-solid fa-user-plus mr-2"></i> Hoàn Tất Đăng Ký Tài Khoản
                    </button>
                </form>
            </div>

        </div>

    </div>

    <!-- JAVASCRIPT LOGIC CLIENT SIDE -->
    <script>
        const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

        document.addEventListener('DOMContentLoaded', () => {
            // Kiểm tra query parameter ?tab=register để chuyển tab tự động nếu cần
            const params = new URLSearchParams(window.location.search);
            if (params.get('tab') === 'register' || window.location.pathname.includes('dang-ky')) {
                chuyenTabAuth('register');
            }
        });

        // HÀM CHUYỂN TAB ĐĂNG NHẬP / ĐĂNG KÝ
        function chuyenTabAuth(tab) {
            const btnLogin = document.getElementById('tab-btn-login');
            const btnRegister = document.getElementById('tab-btn-register');
            const secLogin = document.getElementById('section-login');
            const secRegister = document.getElementById('section-register');

            if (tab === 'register') {
                btnLogin.className = 'flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center inactive-tab-btn';
                btnRegister.className = 'flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center active-tab-btn';
                secLogin.classList.add('hidden');
                secRegister.classList.remove('hidden');
            } else {
                btnLogin.className = 'flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center active-tab-btn';
                btnRegister.className = 'flex-1 py-2 text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center inactive-tab-btn';
                secLogin.classList.remove('hidden');
                secRegister.classList.add('hidden');
            }
        }

        // HÀM ẨN / HIỆN MẬT KHẨU
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }

        // HÀM NHẬP NHANH TÀI KHOẢN DEMO
        function nhapNhanhTaiKhoan(username, password, autoSubmit = false) {
            document.getElementById('input-login-username').value = username;
            document.getElementById('input-login-password').value = password;

            if (autoSubmit) {
                xuLyDangNhap(new Event('submit'));
            }
        }

        // HÀM XỬ LÝ ĐĂNG NHẬP
        async function xuLyDangNhap(e) {
            if (e && e.preventDefault) e.preventDefault();

            const username = document.getElementById('input-login-username').value.trim();
            const password = document.getElementById('input-login-password').value;
            const btnSubmit = document.getElementById('btn-submit-login');

            if (!username || !password) {
                Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.' });
                return;
            }

            // Hiển thị trạng thái đang xử lý
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang xác thực...';

            try {
                const res = await fetch(`${API_BASE_URL}/xac-thuc/dang-nhap`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ten_dang_nhap: username,
                        mat_khau: password
                    })
                });

                const json = await res.json();
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Đăng Nhập Ngay';

                if (res.ok && json.thanh_cong) {
                    const data = json.du_lieu;
                    
                    // Lưu session vào sessionStorage theo phiên làm việc trình duyệt
                    sessionStorage.setItem('token', data.token);
                    sessionStorage.setItem('user_info', JSON.stringify(data.nguoi_dung));
                    sessionStorage.setItem('role', data.vai_tro);

                    // Xóa sạch localStorage cũ nếu có
                    localStorage.clear();

                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng nhập thành công!',
                        text: `Xin chào ${data.nguoi_dung.ho_ten} (${data.vai_tro})`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Điều hướng thông minh theo vai trò
                        if (data.vai_tro === 'ADMIN') {
                            window.location.href = '/dashboard';
                        } else if (data.vai_tro === 'BAC_SI') {
                            window.location.href = '/dashboard';
                        } else {
                            // BỆNH NHÂN -> Đến ngay Cổng Đặt Lịch Khám
                            window.location.href = '/dashboard';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng nhập không thành công',
                        text: json.thong_diep || 'Tên đăng nhập hoặc mật khẩu không chính xác.'
                    });
                }
            } catch (err) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Đăng Nhập Ngay';
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối',
                    text: 'Không thể kết nối đến máy chủ API Gateway (Port 8000).'
                });
            }
        }

        // HÀM XỬ LÝ ĐĂNG KÝ
        async function xuLyDangKy(e) {
            e.preventDefault();

            const fullname = document.getElementById('reg-fullname').value.trim();
            const username = document.getElementById('reg-username').value.trim();
            const email = document.getElementById('reg-email').value.trim();
            const phone = document.getElementById('reg-phone').value.trim();
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;
            const btnSubmit = document.getElementById('btn-submit-register');

            if (password !== confirmPassword) {
                Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: 'Mật khẩu và xác nhận mật khẩu không trùng khớp.' });
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang tạo tài khoản...';

            try {
                const res = await fetch(`${API_BASE_URL}/xac-thuc/dang-ky`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ho_ten: fullname,
                        ten_dang_nhap: username,
                        email: email,
                        so_dien_thoai: phone,
                        mat_khau: password
                    })
                });

                const json = await res.json();
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-user-plus mr-2"></i> Hoàn Tất Đăng Ký Tài Khoản';

                if (res.ok && json.thanh_cong) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng ký thành công!',
                        text: 'Tài khoản bệnh nhân của bạn đã được kích hoạt. Hãy đăng nhập ngay.',
                        confirmButtonColor: '#0284c7'
                    }).then(() => {
                        chuyenTabAuth('login');
                        nhapNhanhTaiKhoan(username, password, false);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng ký thất bại',
                        text: json.thong_diep || 'Tên đăng nhập hoặc Email đã tồn tại trong hệ thống.'
                    });
                }
            } catch (err) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-user-plus mr-2"></i> Hoàn Tất Đăng Ký Tài Khoản';
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi máy chủ',
                    text: 'Không thể kết nối đến máy chủ đăng ký.'
                });
            }
        }

        // HÀM KIỂM TRA ĐỘ MẠNH MẬT KHẨU
        function kiemTraDoManhMatKhau(pwd) {
            const bar = document.getElementById('pwd-strength-bar');
            if (!bar) return;

            let score = 0;
            if (pwd.length >= 6) score += 25;
            if (pwd.length >= 8) score += 25;
            if (/[A-Z]/.test(pwd)) score += 25;
            if (/[0-9]/.test(pwd) || /[^A-Za-z0-9]/.test(pwd)) score += 25;

            bar.style.width = score + '%';
            if (score <= 25) {
                bar.className = 'h-full bg-rose-500 transition-all';
            } else if (score <= 50) {
                bar.className = 'h-full bg-amber-500 transition-all';
            } else if (score <= 75) {
                bar.className = 'h-full bg-sky-500 transition-all';
            } else {
                bar.className = 'h-full bg-emerald-500 transition-all';
            }
        }

        function quenMatKhauPrompt() {
            Swal.fire({
                icon: 'info',
                title: 'Hỗ trợ đặt lại mật khẩu',
                text: 'Vui lòng liên hệ Hotline phòng khám: 1900 6868 hoặc liên hệ trực tiếp Quản trị viên hệ thống để được cấp lại mật khẩu.',
                confirmButtonColor: '#0284c7'
            });
        }


    </script>
</body>
</html>
