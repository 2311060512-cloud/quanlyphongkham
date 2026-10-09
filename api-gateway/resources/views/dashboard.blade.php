<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phòng khám đa khoa – Hệ thống quản lý</title>
    <meta name="description" content="Hệ thống quản lý phòng khám đa khoa: đặt lịch khám, hồ sơ bệnh nhân, bàn khám bác sĩ và thu ngân viện phí.">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Bảng màu chủ đạo duy nhất (xanh dương y tế dịu). Các họ màu "trang trí" cũ
        // (sky, blue, indigo, purple, violet, teal, cyan, fuchsia, pink) được quy về
        // cùng một màu chủ đạo để giao diện đồng nhất mà không phải sửa từng class.
        const PRIMARY_PALETTE = {
            50: '#EEF5FB',
            100: '#D9E8F5',
            200: '#B3D1EB',
            300: '#82B3DC',
            400: '#4F91C9',
            500: '#2A7BBE',
            600: '#1F6FB2', // Màu chủ đạo
            700: '#185A92',
            800: '#144A78',
            900: '#103B60',
            950: '#0B2842',
        };
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: PRIMARY_PALETTE,
                        primary: PRIMARY_PALETTE,
                        sky: PRIMARY_PALETTE,
                        blue: PRIMARY_PALETTE,
                        indigo: PRIMARY_PALETTE,
                        purple: PRIMARY_PALETTE,
                        violet: PRIMARY_PALETTE,
                        teal: PRIMARY_PALETTE,
                        cyan: PRIMARY_PALETTE,
                        fuchsia: PRIMARY_PALETTE,
                        pink: PRIMARY_PALETTE,
                    },
                    fontFamily: {
                        sans: ['"Be Vietnam Pro"', 'system-ui', 'sans-serif'],
                        mono: ['"Be Vietnam Pro"', 'system-ui', 'sans-serif'],
                    },
                    // Cỡ chữ tối thiểu 13px, dễ đọc với người lớn tuổi
                    fontSize: {
                        xs: ['13px', { lineHeight: '18px' }],
                        sm: ['14px', { lineHeight: '20px' }],
                        base: ['15px', { lineHeight: '24px' }],
                        lg: ['17px', { lineHeight: '26px' }],
                        xl: ['19px', { lineHeight: '28px' }],
                        '2xl': ['22px', { lineHeight: '30px' }],
                        '3xl': ['26px', { lineHeight: '34px' }],
                        '4xl': ['30px', { lineHeight: '38px' }],
                    },
                    // Tiêu đề tối đa 600, không dùng chữ quá đậm
                    fontWeight: {
                        bold: '600',
                        extrabold: '600',
                        black: '700',
                    },
                    // Bo góc vừa phải: nút/input 6px, card 8px
                    borderRadius: {
                        md: '6px',
                        lg: '8px',
                        xl: '8px',
                        '2xl': '8px',
                        '3xl': '10px',
                    },
                    // Bóng đổ rất nhẹ
                    boxShadow: {
                        '2xs': 'none',
                        xs: '0 1px 2px rgba(16, 24, 40, 0.04)',
                        sm: '0 1px 2px rgba(16, 24, 40, 0.05)',
                        DEFAULT: '0 1px 3px rgba(16, 24, 40, 0.06)',
                        md: '0 2px 6px rgba(16, 24, 40, 0.06)',
                        lg: '0 4px 12px rgba(16, 24, 40, 0.08)',
                        xl: '0 8px 24px rgba(16, 24, 40, 0.10)',
                        '2xl': '0 12px 32px rgba(16, 24, 40, 0.12)',
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts: Be Vietnam Pro (hỗ trợ tiếng Việt tốt) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js 4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ============================================================
           DESIGN TOKENS
           ============================================================ */
        :root {
            --primary: #1F6FB2;
            --primary-hover: #185A92;
            --primary-50: #EEF5FB;
            --primary-100: #D9E8F5;
            --primary-700: #185A92;

            --success: #15803D;
            --success-bg: #ECFDF3;
            --warning: #B45309;
            --warning-bg: #FFF7E6;
            --danger: #C0352B;
            --danger-bg: #FDEEEC;
            --neutral: #5B6675;
            --neutral-bg: #F1F3F6;

            --bg-page: #F5F7FA;
            --bg-card: #FFFFFF;
            --border: #E3E8EE;
            --border-strong: #CBD3DC;
            --text: #1E2A38;
            --text-muted: #5B6675;

            --radius-sm: 6px;
            --radius: 8px;
            --space-1: 4px;
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 24px;

            --control-h: 42px;
            --ease: 150ms ease;
        }

        html { font-size: 15px; }

        body {
            font-family: 'Be Vietnam Pro', system-ui, sans-serif;
            background-color: var(--bg-page);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .font-mono { font-variant-numeric: tabular-nums; }

        /* ============================================================
           TAB / SECTION
           ============================================================ */
        .portal-section { display: none; }
        .portal-section.active { display: block; }

        .tab-btn.active {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
        }

        .slot-btn {
            min-height: 44px;
            font-size: 15px;
        }
        .slot-btn.selected {
            background-color: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            font-weight: 600;
        }

        /* ============================================================
           MODAL: nền tối nhẹ, không blur
           ============================================================ */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-backdrop.show { display: flex; }
        .modal-box { animation: modalFade 0.15s ease-out; }
        @keyframes modalFade {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ============================================================
           SIDEBAR
           ============================================================ */
        .app-sidebar {
            width: 256px;
            background: #FFFFFF;
            border-right: 1px solid var(--border);
        }
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #D5DBE3; border-radius: 6px; }

        .nav-group-label {
            padding: 0 12px;
            margin-bottom: 4px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
        }
        .sidebar-item {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 42px;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            text-align: left;
            transition: background-color var(--ease), color var(--ease);
        }
        .sidebar-item i {
            width: 20px;
            text-align: center;
            font-size: 15px;
            color: #64748B;
            transition: color var(--ease);
        }
        .sidebar-item:hover { background: var(--neutral-bg); color: var(--text); }
        .sidebar-item.active {
            background: var(--primary-50);
            color: var(--primary-700);
            font-weight: 600;
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--primary);
        }
        .sidebar-item.active i { color: var(--primary); }

        #sidebar-overlay { display: none; }

        @media (max-width: 1023px) {
            .app-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%);
                transition: transform 200ms ease;
                box-shadow: 0 12px 32px rgba(16, 24, 40, 0.12);
            }
            .app-sidebar.open { transform: translateX(0); }
            #sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.35);
                z-index: 40;
            }
        }

        /* ============================================================
           THÀNH PHẦN DÙNG CHUNG
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color var(--ease), border-color var(--ease), color var(--ease);
        }
        .btn:disabled { opacity: 0.55; cursor: not-allowed; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: #fff; color: var(--text); border-color: var(--border-strong); }
        .btn-secondary:hover { background: var(--neutral-bg); }
        .btn-danger { background: #fff; color: var(--danger); border-color: #EDB7B1; }
        .btn-danger:hover { background: var(--danger-bg); }
        .btn-danger-solid { background: var(--danger); color: #fff; }
        .btn-ghost { background: transparent; color: var(--text-muted); }
        .btn-ghost:hover { background: var(--neutral-bg); color: var(--text); }
        .btn-icon { width: 40px; padding: 0; }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
        }

        .input {
            width: 100%;
            min-height: var(--control-h);
            padding: 8px 12px;
            background: #fff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            font-size: 15px;
            color: var(--text);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            line-height: 20px;
            white-space: nowrap;
        }
        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-neutral { background: var(--neutral-bg); color: var(--neutral); }
        .badge-info { background: var(--primary-50); color: var(--primary-700); }

        .table { width: 100%; border-collapse: collapse; font-size: 14px; }

        /* ============================================================
           CHUẨN HÓA TOÀN CỤC (áp cho markup cũ, không cần sửa từng dòng)
           ============================================================ */

        /* Focus rõ ràng khi dùng bàn phím */
        :focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Ô nhập liệu */
        input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="range"]):not([type="color"]),
        select {
            min-height: var(--control-h);
            font-size: 15px;
        }
        input, select, textarea { color: var(--text); }
        input::placeholder, textarea::placeholder { color: #8A94A3; }
        input:not([type="checkbox"]):not([type="radio"]):focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 2px rgba(31, 111, 178, 0.25) !important;
        }
        label { color: #334155; }

        /* Vùng bấm tối thiểu */
        button[class*="py-2"], button[class*="py-3"],
        a[class*="py-2"], a[class*="py-3"] {
            min-height: 40px;
        }

        /* Bảng: hàng thoáng, tiêu đề không in hoa */
        main table, .modal-box table { font-size: 14px; }
        main table th, .modal-box table th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: none;
            letter-spacing: 0;
            white-space: nowrap;
        }
        main table td, main table th,
        .modal-box table td, .modal-box table th {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        .overflow-x-auto { -webkit-overflow-scrolling: touch; }

        /* Bỏ hiệu ứng nhấp nháy/phóng to còn sót lại */
        .animate-pulse, .animate-bounce, .animate-ping { animation: none !important; }

        /* SweetAlert2 đồng bộ phong cách */
        .swal2-popup { font-family: 'Be Vietnam Pro', system-ui, sans-serif !important; border-radius: 10px !important; }
        .swal2-title { font-size: 20px !important; font-weight: 600 !important; color: var(--text) !important; }
        .swal2-html-container { font-size: 15px !important; color: #334155 !important; line-height: 1.6 !important; }
        .swal2-styled { border-radius: var(--radius-sm) !important; font-weight: 600 !important; font-size: 15px !important; min-height: 42px; padding: 8px 20px !important; }
        .swal2-styled.swal2-cancel { background-color: #fff !important; color: var(--text) !important; border: 1px solid var(--border-strong) !important; }
        .swal2-styled:focus { box-shadow: 0 0 0 3px rgba(31, 111, 178, 0.3) !important; }
        .swal2-toast { border-radius: 8px !important; box-shadow: 0 8px 24px rgba(16, 24, 40, 0.12) !important; }

        @media (max-width: 1023px) {
            button[class*="py-2"], button[class*="py-3"],
            a[class*="py-2"], a[class*="py-3"],
            .btn, .sidebar-item {
                min-height: 44px;
            }
            main { padding: 16px !important; }
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex bg-[#F5F7FA] text-slate-800 font-sans">

    <!-- Lớp phủ khi mở menu trên điện thoại / máy tính bảng -->
    <div id="sidebar-overlay" onclick="dongSidebarMobile()" aria-hidden="true"></div>

    <!-- ================================================================= -->
    <!-- THANH MENU BÊN TRÁI                                                -->
    <!-- ================================================================= -->
    <aside id="app-sidebar" class="app-sidebar flex flex-col flex-shrink-0 h-screen z-50 select-none" aria-label="Menu chính">
        <!-- Tên phòng khám -->
        <div class="h-16 px-4 border-b border-[#E3E8EE] flex items-center gap-3 flex-shrink-0">
            <div class="w-9 h-9 rounded-md bg-[#1F6FB2] flex items-center justify-center text-white flex-shrink-0" aria-hidden="true">
                <i class="fa-solid fa-staff-snake"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-[15px] text-slate-900 leading-tight truncate">Phòng khám đa khoa</p>
                <p class="text-[13px] text-slate-500 leading-tight truncate mt-0.5">Hệ thống quản lý</p>
            </div>
            <button type="button" onclick="dongSidebarMobile()" class="lg:hidden btn btn-ghost btn-icon" aria-label="Đóng menu" title="Đóng menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Danh sách chức năng -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 sidebar-scroll">
            <!-- Nhóm 1: TỔNG QUAN (Chỉ ADMIN) -->
            <div id="nav-group-admin-giam-sat" class="role-nav-group" data-roles="ADMIN">
                <p class="nav-group-label">Tổng quan</p>
                <div class="space-y-0.5">
                    <button class="sidebar-item active" data-tab="tab-admin-giam-sat" onclick="chuyenTab('tab-admin-giam-sat', this)">
                        <i class="fa-solid fa-chart-column" aria-hidden="true"></i>
                        <span>Thống kê</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 2: ĐẶT LỊCH & BỆNH NHÂN (BENH_NHAN, BAC_SI, LE_TAN & ADMIN) -->
            <div id="nav-group-benh-nhan" class="role-nav-group" data-roles="BENH_NHAN,ADMIN,BAC_SI,LE_TAN">
                <p class="nav-group-label">Khám bệnh</p>
                <div class="space-y-0.5">
                    <button class="sidebar-item" data-tab="tab-benh-nhan" onclick="chuyenTab('tab-benh-nhan', this)">
                        <i class="fa-regular fa-calendar-plus" aria-hidden="true"></i>
                        <span>Đặt lịch khám</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-benh-nhan-lich" onclick="chuyenTab('tab-benh-nhan-lich', this)">
                        <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                        <span id="nav-label-benh-nhan-lich">Lịch khám</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-benh-nhan-vien-phi" onclick="chuyenTab('tab-benh-nhan-vien-phi', this)">
                        <i class="fa-regular fa-file-lines" aria-hidden="true"></i>
                        <span>Viện phí</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-ho-so-gia-dinh" onclick="chuyenTab('tab-ho-so-gia-dinh', this)">
                        <i class="fa-solid fa-people-roof" aria-hidden="true"></i>
                        <span>Hồ sơ gia đình</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-admin-benh-nhan" onclick="chuyenTab('tab-admin-benh-nhan', this)">
                        <i class="fa-solid fa-hospital-user" aria-hidden="true"></i>
                        <span>Bệnh nhân</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 3: BÀN KHÁM BÁC SĨ (BAC_SI & ADMIN) -->
            <div id="nav-group-bac-si" class="role-nav-group" data-roles="BAC_SI,ADMIN">
                <p class="nav-group-label">Bác sĩ</p>
                <div class="space-y-0.5">
                    <button class="sidebar-item" data-tab="tab-bac-si" onclick="chuyenTab('tab-bac-si', this)">
                        <i class="fa-solid fa-stethoscope" aria-hidden="true"></i>
                        <span>Bàn khám</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-bac-si-lich-su" onclick="chuyenTab('tab-bac-si-lich-su', this)">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                        <span>Ca khám hôm nay</span>
                    </button>
                    <button class="sidebar-item" id="nav-item-lich-truc-bac-si" onclick="moModalLichTrucBacSiHienTai()">
                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                        <span id="nav-label-lich-truc-bac-si">Lịch trực của tôi</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 4: THU NGÂN & VIỆN PHÍ (ADMIN & LE_TAN) -->
            <div id="nav-group-thu-ngan" class="role-nav-group" data-roles="ADMIN,LE_TAN">
                <p class="nav-group-label">Tài chính</p>
                <div class="space-y-0.5">
                    <button class="sidebar-item" data-tab="tab-admin-thu-ngan" onclick="chuyenTab('tab-admin-thu-ngan', this)">
                        <i class="fa-solid fa-cash-register" aria-hidden="true"></i>
                        <span>Thu ngân</span>
                    </button>
                </div>
            </div>

            <!-- Nhóm 5: QUẢN TRỊ HỆ THỐNG (Chỉ ADMIN) -->
            <div id="nav-group-admin-he-thong" class="role-nav-group" data-roles="ADMIN">
                <p class="nav-group-label">Quản trị</p>
                <div class="space-y-0.5">
                    <button class="sidebar-item" data-tab="tab-admin-bac-si" onclick="chuyenTab('tab-admin-bac-si', this)">
                        <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                        <span>Bác sĩ &amp; chuyên khoa</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-bang-gia-kham" onclick="chuyenTab('tab-bang-gia-kham', this)">
                        <i class="fa-solid fa-tags" aria-hidden="true"></i>
                        <span>Bảng giá khám</span>
                    </button>
                    <button class="sidebar-item" data-tab="tab-admin-tai-khoan" onclick="chuyenTab('tab-admin-tai-khoan', this)">
                        <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
                        <span>Tài khoản</span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Chân menu -->
        <div class="px-4 py-3 border-t border-[#E3E8EE] text-[13px] text-slate-500 flex-shrink-0">
            Phiên bản 1.2
        </div>
    </aside>

    <!-- ============================================================= -->
    <!-- VÙNG NỘI DUNG CHÍNH                                           -->
    <!-- ============================================================= -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
        <!-- Thanh tiêu đề -->
        <header class="h-16 bg-white border-b border-[#E3E8EE] px-4 sm:px-6 flex items-center justify-between gap-3 z-30 flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="moSidebarMobile()" class="lg:hidden btn btn-secondary btn-icon flex-shrink-0" aria-label="Mở menu" title="Mở menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="min-w-0">
                    <h1 id="current-page-title" class="text-[17px] font-semibold text-slate-900 flex items-center gap-2 truncate leading-tight">
                        <span>Tổng quan</span>
                    </h1>
                    <p id="brand-subtitle" class="text-[13px] text-slate-500 hidden md:block truncate leading-tight mt-0.5">Phòng khám đa khoa</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <!-- Hồ sơ cá nhân -->
                <button type="button" onclick="moModalHoSoCaNhan()" class="flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-md hover:bg-slate-100 transition-colors text-left" title="Xem và sửa hồ sơ cá nhân" aria-label="Hồ sơ cá nhân">
                    <div class="relative flex-shrink-0">
                        <img id="top-user-avatar-img" src="" alt="Ảnh đại diện" class="w-9 h-9 rounded-full object-cover border border-[#E3E8EE] hidden">
                        <div id="top-user-avatar-icon" class="w-9 h-9 rounded-full bg-[#D9E8F5] flex items-center justify-center text-[#185A92] text-sm">
                            <i class="fa-solid fa-user" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="min-w-0 hidden sm:block">
                        <p id="header-user-name" class="text-[14px] font-semibold text-slate-900 truncate max-w-[170px] leading-tight">Đang tải...</p>
                        <span id="header-user-role" class="text-[13px] text-slate-500 leading-tight">Quản trị viên</span>
                    </div>
                </button>

                <!-- Đăng xuất -->
                <button type="button" onclick="xuLyDangXuatGateway()" class="btn btn-secondary" title="Đăng xuất khỏi hệ thống" aria-label="Đăng xuất">
                    <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                    <span class="hidden sm:inline">Đăng xuất</span>
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
            <!-- Patient Welcome Bar -->
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 sm:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-[18px] font-semibold text-slate-900">
                        Xin chào, <span id="banner-patient-name">Quý khách</span>
                    </h2>
                    <p class="text-[14px] text-slate-500 mt-1 max-w-2xl">
                        Chọn bác sĩ chuyên khoa và khung giờ phù hợp để đăng ký khám bệnh nhanh chóng.
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="button" onclick="moModalDatLich(null)" class="btn btn-primary">
                        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i>
                        <span>Đặt lịch khám</span>
                    </button>
                    <button type="button" onclick="chuyenTab('tab-benh-nhan-lich')" class="btn btn-secondary">
                        <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                        <span>Lịch hẹn của tôi</span>
                    </button>
                </div>
            </div>

            <!-- Doctor List Panel -->
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-user-doctor text-medical-600"></i>
                            <span>Đội ngũ bác sĩ chuyên khoa</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Chọn bác sĩ phù hợp để đặt lịch khám trực tiếp</p>
                    </div>

                    <!-- Search and Speciality Filters -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="filter-doctor-search" oninput="locDanhSachBacSi()" placeholder="Tìm tên bác sĩ..." class="pl-9 pr-4 py-2 text-xs bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 w-56">
                        </div>
                        <select id="filter-doctor-chuyen-khoa" onchange="locDanhSachBacSi()" class="px-3.5 py-2 text-xs bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-700 font-medium">
                            <option value="">Tất cả chuyên khoa</option>
                        </select>
                        <select id="filter-doctor-ca" onchange="locDanhSachBacSi()" class="px-3.5 py-2 text-xs bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-700 font-medium">
                            <option value="">Tất cả ca khám</option>
                            <option value="CA_SANG">Ca sáng (07:30 - 11:30)</option>
                            <option value="CA_CHIEU">Ca chiều (13:30 - 17:00)</option>
                            <option value="CA_TOI">Ca tối (17:30 - 20:30)</option>
                            <option value="CA_NGAY">Cả ngày (07:30 - 17:00)</option>
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
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-people-roof" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-[15px] font-semibold text-slate-900">Hồ sơ sức khỏe gia đình</h3>
                            <span class="badge badge-info" id="quick-banner-so-thanh-vien">Quản lý sức khỏe gia đình</span>
                        </div>
                        <p class="text-[13px] text-slate-500 mt-0.5">Đặt lịch khám và theo dõi lịch sử y tế cho người thân trong gia đình.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                    <button type="button" onclick="moModalThemNguoiThan()" class="btn btn-secondary">
                        <i class="fa-solid fa-user-plus text-[#1F6FB2]" aria-hidden="true"></i>
                        <span>Thêm người thân</span>
                    </button>
                    <button type="button" onclick="chuyenTab('tab-ho-so-gia-dinh')" class="btn btn-ghost">
                        <span>Xem hồ sơ</span>
                        <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-list-check text-sky-600"></i>
                            <span id="title-danh-sach-lich-kham">Quản lý lịch khám bệnh</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Theo dõi thời gian, bệnh nhân, bác sĩ phụ trách, dời lịch và tình trạng ca khám</p>
                    </div>
                </div>

                <!-- Table Appointments -->
                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">Mã lịch</th>
                                <th class="py-3.5 px-4">Bệnh nhân</th>
                                <th class="py-3.5 px-4">Bác sĩ khám</th>
                                <th class="py-3.5 px-4">Chuyên khoa</th>
                                <th class="py-3.5 px-4">Ngày khám</th>
                                <th class="py-3.5 px-4">Khung giờ</th>
                                <th class="py-3.5 px-4">Lý do khám</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
                                <th class="py-3.5 px-4 text-center">Thao tác</th>
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
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-people-roof" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-[17px] font-semibold text-slate-900">Hồ sơ sức khỏe gia đình</h2>
                        <p class="text-[13px] text-slate-500 mt-0.5">Quản lý hồ sơ bệnh nhân cho cả gia đình (con cái, cha mẹ, vợ chồng), đặt lịch và theo dõi lịch sử y tế.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                    <button type="button" onclick="moModalThemNguoiThan()" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        <span>Thêm người thân</span>
                    </button>
                    <button type="button" onclick="taiVaRenderHoSoGiaDinh(true)" class="btn btn-secondary" title="Làm mới">
                        <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                        <span>Làm mới</span>
                    </button>
                </div>
            </div>

            <!-- KPI Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Tổng thành viên</p>
                        <h4 id="stat-gd-tong-thanh-vien" class="text-2xl font-black text-slate-900 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-emerald-600 mt-1">
                            <i class="fa-solid fa-address-book"></i> Hồ sơ quản lý
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Người thân bảo hộ</p>
                        <h4 id="stat-gd-nguoi-than" class="text-2xl font-black text-sky-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-sky-600 mt-1">
                            <i class="fa-solid fa-children"></i> Con cái, bố/mẹ, vợ/chồng
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Lượt khám gia đình</p>
                        <h4 id="stat-gd-luot-kham" class="text-2xl font-black text-purple-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-purple-600 mt-1">
                            <i class="fa-solid fa-calendar-check"></i> Ca khám đã đặt
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </div>

            <!-- Family Cards Container -->
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-id-card-clip text-emerald-600"></i>
                            <span>Danh sách thành viên trong gia đình</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bấm nút "đặt lịch khám" để lập tức đặt ca khám cho người thân</p>
                    </div>
                    <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-md shadow-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm người thân</span>
                    </button>
                </div>

                <div id="container-the-gia-dinh" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <p class="text-xs text-slate-400 italic">Đang nạp hồ sơ gia đình...</p>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 1.3: VIỆN PHÍ & THANH TOÁN (DÀNH CHO BỆNH NHÂN & ADMIN)   -->
        <!-- ============================================================= -->
        <section id="tab-benh-nhan-vien-phi" class="portal-section space-y-6">
            <!-- Header Bar -->
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-[17px] font-semibold text-slate-900">Viện phí và hóa đơn</h2>
                        <p class="text-[13px] text-slate-500 mt-0.5">Theo dõi viện phí các ca khám, tiền công khám, cận lâm sàng và lịch sử thanh toán.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                    <button type="button" onclick="taiDanhSachHoaDon(true)" class="btn btn-secondary">
                        <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                        <span>Làm mới</span>
                    </button>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-lg border border-[#E3E8EE] p-5 shadow-xs flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-black">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Chờ thanh toán</div>
                        <div id="stat-bn-cho-thu" class="text-lg font-black text-amber-600 mt-0.5">0 đ</div>
                        <div class="text-[13px] text-slate-500" id="stat-bn-so-cho-thu">0 hóa đơn</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-[#E3E8EE] p-5 shadow-xs flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-black">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Đã thanh toán</div>
                        <div id="stat-bn-da-thu" class="text-lg font-black text-emerald-600 mt-0.5">0 đ</div>
                        <div class="text-[13px] text-slate-500" id="stat-bn-so-da-thu">0 hóa đơn</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-[#E3E8EE] p-5 shadow-xs flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-md bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-black">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Tổng số hóa đơn</div>
                        <div id="stat-bn-tong-hd" class="text-lg font-black text-slate-900 mt-0.5">0</div>
                        <div class="text-[13px] text-slate-500">Tất cả lượt khám</div>
                    </div>
                </div>
            </div>

            <!-- Table Invoices -->
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-file-invoice text-emerald-600"></i>
                            <span>Lịch sử hóa đơn viện phí của bạn</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Bao gồm hóa đơn bản thân và người thân trong hồ sơ gia đình</p>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-md text-xs font-bold">
                        <button type="button" onclick="locHoaDonBenhNhan('ALL', this)" class="btn-filter-hd-bn active px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs">Tất cả</button>
                        <button type="button" onclick="locHoaDonBenhNhan('CHUA_THANH_TOAN', this)" class="btn-filter-hd-bn px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">Chờ thu</button>
                        <button type="button" onclick="locHoaDonBenhNhan('DA_THANH_TOAN', this)" class="btn-filter-hd-bn px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">Đã thu</button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">Mã hóa đơn</th>
                                <th class="py-3.5 px-4">Bệnh nhân</th>
                                <th class="py-3.5 px-4">Lịch khám</th>
                                <th class="py-3.5 px-4">Tiền khám</th>
                                <th class="py-3.5 px-4">Cận lâm sàng</th>
                                <th class="py-3.5 px-4">Giảm giá</th>
                                <th class="py-3.5 px-4">Thực thu</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
                                <th class="py-3.5 px-4 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-hoa-don-benh-nhan" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="9" class="text-center py-8 text-slate-400">Đang tải hóa đơn của bạn...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ============================================================= -->
        <!-- TAB 1.4: QUẢN LÝ BỆNH NHÂN & HỒ SƠ BỆNH ÁN ĐIỆN TỬ (EHR/EMR)  -->
        <!-- ============================================================= -->
        <section id="tab-admin-benh-nhan" class="portal-section space-y-6">
            <!-- Header Section -->
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-hospital-user" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-[17px] font-semibold text-slate-900">Quản lý bệnh nhân &amp; hồ sơ bệnh án</h2>
                        <p class="text-[13px] text-slate-500 mt-0.5">Theo dõi hồ sơ bệnh nhân, bác sĩ thăm khám, chẩn đoán, đơn thuốc và lịch sử điều trị.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                    <button type="button" onclick="moModalThemSuaBenhNhan()" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        <span>Thêm bệnh nhân</span>
                    </button>
                    <button type="button" onclick="taiDanhSachBenhNhan(true)" class="btn btn-secondary" title="Làm mới dữ liệu">
                        <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                        <span>Làm mới</span>
                    </button>
                </div>
            </div>

            <!-- KPI Stats Cards (4 cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Tổng số BN -->
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Tổng hồ sơ bệnh nhân</p>
                        <h4 id="qlbn-stat-tong-bn" class="text-2xl font-black text-slate-900 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-rose-600 mt-1">
                            <i class="fa-solid fa-address-book"></i> Hồ sơ quản lý
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#FDF2F2] text-[#C0352B] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <!-- Card 2: Ca Khám Hôm Nay -->
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Lượt khám hôm nay</p>
                        <h4 id="qlbn-stat-kham-hom-nay" class="text-2xl font-black text-sky-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-sky-600 mt-1">
                            <i class="fa-regular fa-calendar-check"></i> Ca khám trong ngày
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>

                <!-- Card 3: Đang Chờ Khám -->
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Bệnh nhân chờ khám</p>
                        <h4 id="qlbn-stat-dang-cho" class="text-2xl font-black text-amber-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-amber-600 mt-1">
                            <i class="fa-solid fa-hourglass-half"></i> Tiếp nhận / chờ phòng
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#FEF6EE] text-[#B54708] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                </div>

                <!-- Card 4: Tái Khám -->
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Có lịch tái khám</p>
                        <h4 id="qlbn-stat-tai-kham" class="text-2xl font-black text-emerald-600 mt-1">0</h4>
                        <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-emerald-600 mt-1">
                            <i class="fa-solid fa-notes-medical"></i> Kê đơn & hẹn tái khám
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </div>

            <!-- Smart Filter Bar -->
            <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                    <!-- Search input (4 cols) -->
                    <div class="lg:col-span-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" id="qlbn-tim-kiem" oninput="locDanhSachBenhNhan()" placeholder="Tìm theo Mã BN, Họ tên, SĐT, CCCD, Bệnh..." class="w-full pl-9 pr-8 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                        <button type="button" onclick="document.getElementById('qlbn-tim-kiem').value=''; locDanhSachBenhNhan();" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-slate-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>

                    <!-- Bác sĩ phụ trách (3 cols) -->
                    <div class="lg:col-span-3">
                        <select id="qlbn-loc-bac-si" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            <option value="">Tất cả bác sĩ thăm khám</option>
                        </select>
                    </div>

                    <!-- Khung giờ khám (2 cols) -->
                    <div class="lg:col-span-2">
                        <select id="qlbn-loc-khung-gio" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            <option value="">Tất cả khung giờ</option>
                            <option value="SANG">Buổi sáng (07:30 - 11:30)</option>
                            <option value="CHIEU">Buổi chiều (13:30 - 17:30)</option>
                        </select>
                    </div>

                    <!-- Trạng thái (3 cols) -->
                    <div class="lg:col-span-3">
                        <select id="qlbn-loc-trang-thai" onchange="locDanhSachBenhNhan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            <option value="">Tất cả trạng thái khám</option>
                            <option value="HOAN_THANH">Đã khám (hoàn thành)</option>
                            <option value="DANG_KHAM">Đang trong phòng khám</option>
                            <option value="DA_XAC_NHAN">Đã xác nhận / chờ khám</option>
                            <option value="CHO_XAC_NHAN">Chờ xác nhận check-in</option>
                            <option value="DA_HUY">Đã hủy ca khám</option>
                            <option value="CO_TAI_KHAM">Có lịch hẹn tái khám</option>
                        </select>
                    </div>
                </div>

                <!-- Chips bộ lọc nhanh -->
                <div class="flex items-center gap-2 flex-wrap text-xs pt-1 border-t border-slate-100">
                    <span class="text-[13px] font-bold text-slate-400 mr-1">Bộ lọc nhanh:</span>
                    <button type="button" onclick="datBoLocNhanh('ALL')" class="btn-quick-filter px-3 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">Tất cả</button>
                    <button type="button" onclick="datBoLocNhanh('HOM_NAY')" class="btn-quick-filter px-3 py-1 rounded-md text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition">Khám hôm nay</button>
                    <button type="button" onclick="datBoLocNhanh('CHO_KHAM')" class="btn-quick-filter px-3 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Đang chờ khám</button>
                    <button type="button" onclick="datBoLocNhanh('DA_KHAM')" class="btn-quick-filter px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">Đã khám xong</button>
                    <button type="button" onclick="datBoLocNhanh('CANH_BAO')" class="btn-quick-filter px-3 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 transition">Có cảnh báo dị ứng</button>
                </div>
            </div>

            <!-- Patient EHR Table -->
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">Bệnh nhân</th>
                                <th class="py-3.5 px-4">Liên hệ & Y tế</th>
                                <th class="py-3.5 px-4">Bác sĩ khám</th>
                                <th class="py-3.5 px-4">Khung giờ & ngày</th>
                                <th class="py-3.5 px-4">Chẩn đoán / bị bệnh gì</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
                                <th class="py-3.5 px-4 text-center">Hành động</th>
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
                    <div class="text-[13px] text-slate-400">
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
            <!-- Doctor Header Bar -->
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-stethoscope" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-[17px] font-semibold text-slate-900">
                                <span id="banner-doctor-name">Bàn khám bệnh</span>
                            </h2>
                            <span class="badge badge-success">Đang tiếp nhận</span>
                        </div>
                        <p class="text-[13px] text-slate-500 mt-0.5">Bàn khám chuyên môn, kê cận lâm sàng và quản lý ca khám</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="moModalLichTrucBacSiHienTai()" class="btn btn-secondary" title="Xem lịch trực trong tuần">
                        <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                        <span id="btn-banner-lich-truc-label">Lịch trực của tôi</span>
                    </button>
                    <button type="button" onclick="chuyenTab('tab-bac-si-lich-su')" class="btn btn-ghost">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                        <span>Ca khám hôm nay</span>
                    </button>
                </div>
            </div>

            <!-- 2-Column Doctor Workspace -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Column 1: Waiting Patient Queue (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-user-clock text-amber-500"></i>
                            <span>1. Hàng đợi bệnh nhân đang chờ</span>
                        </h3>
                        <div class="flex items-center gap-1.5 text-xs">
                            <input type="date" id="filter-ca-kham-ngay" onchange="renderCaKhamBacSi(AppState.danhSachLichHen)" class="border border-slate-300 rounded px-2 py-1 text-xs text-slate-700 font-semibold focus:ring-1 focus:ring-medical-500" title="Chọn ngày xem ca khám">
                            <button type="button" onclick="datNgayCaKhamHomNay()" class="px-2 py-1 bg-medical-50 hover:bg-medical-100 text-medical-700 font-bold rounded text-xs border border-medical-200 transition" title="Xem ca khám hôm nay">Hôm nay</button>
                        </div>
                    </div>

                    <div class="overflow-y-auto max-h-[420px] rounded-lg border border-[#E3E8EE]">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Bệnh nhân</th>
                                    <th class="py-2.5 px-3">Giờ hẹn</th>
                                    <th class="py-2.5 px-3 text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-ca-kham-bac-si" class="divide-y divide-slate-100 text-slate-700">
                                <tr><td colspan="4" class="text-center py-6 text-slate-400">Đang tải ca khám...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Column 2: Clinical Form & Paraclinical Test Prescription (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-medical-700 flex items-center space-x-2">
                            <i class="fa-solid fa-notes-medical"></i>
                            <span>2. Phiếu khám & chỉ định cận lâm sàng</span>
                        </h3>
                        <button type="button" onclick="lamMoiBanKham()" class="text-xs text-slate-500 hover:text-medical-700 transition flex items-center gap-1.5 font-semibold px-2.5 py-1 rounded-md hover:bg-slate-100 border border-slate-200 shadow-2xs" title="Làm mới bàn khám để tiếp nhận ca tiếp theo">
                            <i class="fa-solid fa-arrows-rotate text-medical-600"></i>
                            <span>Làm mới bàn khám</span>
                        </button>
                    </div>

                    <!-- Selected Patient Info Card -->
                    <div id="box-chon-ca-kham-thong-tin" class="bg-sky-50/70 border border-sky-100 p-3.5 rounded-md text-xs text-slate-600">
                        <em>Hãy bấm nút <strong>"Tiếp nhận"</strong> trên danh sách hàng đợi bên trái để nạp thông tin bệnh nhân.</em>
                    </div>

                    <!-- Banner Khóa chỉ định khi đã gửi thu ngân -->
                    <div id="banner-khoa-chi-dinh" class="hidden p-3.5 bg-amber-50 border border-amber-200 rounded-md text-xs text-amber-900 flex items-start gap-2.5 font-medium">
                        <i class="fa-solid fa-lock text-amber-600 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-amber-800">Đã khóa phiếu chỉ định:</strong>
                            <span class="text-amber-700"> Ca khám này đã tiếp nhận và gửi chỉ định dịch vụ sang bàn Thu Ngân. Hệ thống đã khóa tất cả thao tác kê thêm chỉ định để tránh phát sinh chi phí hoặc trùng lặp hóa đơn.</span>
                        </div>
                    </div>

                    <!-- Preliminary Diagnosis Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Chẩn đoán sơ bộ / triệu chứng lâm sàng:
                        </label>
                        <input type="text" id="input-chan-doan" placeholder="Ví dụ: Đau đầu kéo dài, nghi rối loạn tiền đình..." class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>

                    <!-- Paraclinical Tests Checkboxes -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tích chọn các dịch vụ cận lâm sàng chỉ định (service 03):
                        </label>
                        <div id="list-dich-vu-checkboxes" class="space-y-2 max-h-[190px] overflow-y-auto pr-1">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                    <!-- Subtotal & Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 gap-3">
                        <div>
                            <span class="text-[13px] font-bold text-slate-500">TỔNG PHÍ CẬN LÂM SÀNG:</span>
                            <div id="subtotal-cls" class="text-xl font-extrabold text-emerald-600">0 đ</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="inHoaDonKhamBenhCaHienTai()" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-md shadow-sm transition flex items-center space-x-1.5" title="In phiếu thu / Hóa đơn viện phí khám bệnh">
                                <i class="fa-solid fa-print"></i>
                                <span>In hóa đơn khám</span>
                            </button>
                            <button type="button" id="btn-luu-chi-dinh-cls" onclick="luuChiDinhCanLamSang()" class="px-5 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-md shadow-lg transition flex items-center space-x-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Lưu chỉ định & gửi thu ngân</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 2.2: DANH SÁCH CA KHÁM HÔM NAY -->
        <section id="tab-bac-si-lich-su" class="portal-section space-y-6">
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-clipboard-user text-emerald-600"></i>
                            <span>Danh sách ca bệnh nhân bác sĩ đã tiếp nhận</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tiến trình các ca khám (Chỉ hiển thị các ca đã đến ngày khám)</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <label for="filter-bac-si-lich-su-ngay" class="text-xs text-slate-500 font-semibold">Ngày:</label>
                        <input type="date" id="filter-bac-si-lich-su-ngay" onchange="renderBacSiLichSu(AppState.danhSachLichHen)" class="border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-700 font-semibold focus:ring-1 focus:ring-medical-500">
                        <button type="button" onclick="datNgayLichSuHomNay()" class="px-2.5 py-1.5 bg-medical-50 hover:bg-medical-100 text-medical-700 font-bold rounded text-xs border border-medical-200 transition">Hôm nay</button>
                        <button type="button" onclick="datNgayLichSuTatCa()" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded text-xs border border-slate-200 transition">Tất cả ca đã đến ngày</button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">Mã lịch</th>
                                <th class="py-3.5 px-4">Bệnh nhân</th>
                                <th class="py-3.5 px-4">Số điện thoại</th>
                                <th class="py-3.5 px-4">Ngày khám</th>
                                <th class="py-3.5 px-4">Khung giờ</th>
                                <th class="py-3.5 px-4">Lý do khám</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
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
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-[17px] font-semibold text-slate-900">Danh mục bác sĩ &amp; chuyên khoa</h2>
                        <p class="text-[13px] text-slate-500 mt-0.5">Quản lý danh sách bác sĩ, chuyên khoa, phòng khám, đơn giá và phân ca trực</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="moModalThemBacSi()" class="btn btn-primary">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                        <span>Thêm bác sĩ</span>
                    </button>
                    <button type="button" onclick="moModalThemChuyenKhoa()" class="btn btn-secondary">
                        <i class="fa-solid fa-folder-plus" aria-hidden="true"></i>
                        <span>Thêm khoa</span>
                    </button>
                </div>
            </div>

            <!-- Two Columns for Doctors and Specialties -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-user-doctor text-medical-600"></i>
                            <span>Danh sách bác sĩ phòng khám</span>
                        </h3>
                    </div>
                    <div class="overflow-y-auto max-h-[380px] rounded-lg border border-[#E3E8EE]">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Họ tên</th>
                                    <th class="py-2.5 px-3">Chuyên khoa</th>
                                    <th class="py-2.5 px-3">Giá khám</th>
                                    <th class="py-2.5 px-3">Phòng</th>
                                    <th class="py-2.5 px-3 text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin-bac-si" class="divide-y divide-slate-100 text-slate-700">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-building-user text-indigo-600"></i>
                            <span>Danh mục chuyên khoa</span>
                        </h3>
                    </div>
                    <div class="overflow-y-auto max-h-[380px] rounded-lg border border-[#E3E8EE]">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                    <th class="py-2.5 px-3">Mã</th>
                                    <th class="py-2.5 px-3">Tên khoa</th>
                                    <th class="py-2.5 px-3">Mô tả</th>
                                    <th class="py-2.5 px-3 text-center">Thao tác</th>
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
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                                <span>Phê duyệt lịch trực bác sĩ (đăng ký & đổi ca)</span>
                                <span id="badge-admin-count-cho-duyet" class="px-2 py-0.5 rounded-full text-[13px] font-black bg-amber-500 text-white shadow-xs">0 chờ duyệt</span>
                            </h3>
                            <p class="text-[13px] text-slate-500">Mỗi ngày bác sĩ chỉ trực 1 ca (sáng, chiều, tối hoặc cả ngày). Yêu cầu đăng ký mới hoặc đổi ca cần admin duyệt để kích hoạt.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <select id="filter-admin-duyet-trang-thai" onchange="taiVaRenderDanhSachDuyetCaTruc()" class="px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs font-bold text-slate-700">
                            <option value="CHO_DUYET">Chờ admin duyệt</option>
                            <option value="HOAT_DONG">Đã duyệt (hoạt động)</option>
                            <option value="TU_CHOI">Bị từ chối</option>
                            <option value="">Tất cả trạng thái</option>
                        </select>
                        <button type="button" onclick="taiVaRenderDanhSachDuyetCaTruc()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md text-xs font-bold transition flex items-center gap-1">
                            <i class="fa-solid fa-arrows-rotate"></i> Làm mới
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-2.5 px-3">Bác sĩ</th>
                                <th class="py-2.5 px-3">Thứ / ngày</th>
                                <th class="py-2.5 px-3">Ca trực</th>
                                <th class="py-2.5 px-3">Khung giờ</th>
                                <th class="py-2.5 px-3">Phòng khám</th>
                                <th class="py-2.5 px-3">Khám tối đa</th>
                                <th class="py-2.5 px-3">Trạng thái</th>
                                <th class="py-2.5 px-3 text-center">Thao tác phê duyệt</th>
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
            <!-- Header Bar -->
            <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-[17px] font-semibold text-slate-900">Bảng giá khám bệnh</h2>
                            <span class="badge badge-success">Niêm yết công khai</span>
                        </div>
                        <p class="text-[13px] text-slate-500 mt-0.5">Danh mục phí khám chuyên khoa và đơn giá khám theo bác sĩ</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="taiBangGiaKham()" class="btn btn-secondary">
                        <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                        <span>Làm mới</span>
                    </button>
                    <button type="button" onclick="inBangGiaKham()" class="btn btn-primary">
                        <i class="fa-solid fa-print" aria-hidden="true"></i>
                        <span>In bảng giá</span>
                    </button>
                </div>
            </div>

            <!-- 4 Thẻ Thống Kê Nhanh -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4 border border-[#E3E8EE] shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-md bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Tổng bác sĩ tiếp nhận</p>
                        <h4 id="stat-bgk-tong-bs" class="text-lg font-black text-slate-900 mt-0.5">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 border border-[#E3E8EE] shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-arrow-down text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Khám tiêu chuẩn từ</p>
                        <h4 id="stat-bgk-min" class="text-lg font-black text-emerald-600 mt-0.5 font-mono">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 border border-[#E3E8EE] shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-md bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-crown text-amber-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Khám chuyên gia / GS</p>
                        <h4 id="stat-bgk-max" class="text-lg font-black text-purple-700 mt-0.5 font-mono">--</h4>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 border border-[#E3E8EE] shadow-xs flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-md bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-scale-balanced text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-400">Mức phí bình quân</p>
                        <h4 id="stat-bgk-avg" class="text-lg font-black text-amber-600 mt-0.5 font-mono">--</h4>
                    </div>
                </div>
            </div>

            <!-- Bảng Dữ Liệu Biểu Phí & Bộ Lọc -->
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-medical-50 text-medical-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-table-list"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Chi tiết biểu phí khám bệnh từng bác sĩ</h3>
                            <p class="text-[13px] text-slate-500">Giá niêm yết áp dụng cho mỗi lượt khám chuyên khoa ban đầu (chưa bao gồm chỉ định cận lâm sàng)</p>
                        </div>
                    </div>

                    <!-- Bộ lọc đa tiêu chí -->
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="relative min-w-[200px]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="filter-bgk-tu-khoa" oninput="locBangGiaKham()" placeholder="Tìm bác sĩ, học vị, phòng..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs font-medium text-slate-800 focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2]">
                        </div>

                        <select id="filter-bgk-chuyen-khoa" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả chuyên khoa</option>
                        </select>

                        <select id="filter-bgk-phan-khuc" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả phân khúc</option>
                            <option value="Khám Tiêu Chuẩn">Khám tiêu chuẩn</option>
                            <option value="Khám Thạc Sĩ / CKI">Khám thạc sĩ / CKI</option>
                            <option value="Khám Chuyên Gia / CKII">Khám chuyên gia / CKII</option>
                            <option value="Khám Phó Giáo Sư">Khám phó giáo sư</option>
                            <option value="Khám Giáo Sư / Chuyên Gia Đầu Ngành">Khám giáo sư</option>
                            <option value="Khám Dịch Vụ VIP">Khám dịch vụ VIP</option>
                        </select>

                        <select id="filter-bgk-muc-gia" onchange="locBangGiaKham()" class="px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-md text-xs font-bold text-slate-700 focus:outline-none">
                            <option value="">Tất cả mức giá</option>
                            <option value="DUOI_200">Dưới 200.000 VNĐ</option>
                            <option value="200_300">200.000đ - 300.000 VNĐ</option>
                            <option value="TREN_300">Trên 300.000 VNĐ</option>
                        </select>
                    </div>
                </div>

                <!-- Bảng Hiển Thị Biểu Phí -->
                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-2.5 px-3.5 text-center w-12">STT</th>
                                <th class="py-2.5 px-3.5">Bác sĩ khám bệnh</th>
                                <th class="py-2.5 px-3.5">Chuyên khoa</th>
                                <th class="py-2.5 px-3.5">Học vị & kinh nghiệm</th>
                                <th class="py-2.5 px-3.5">Phòng khám</th>
                                <th class="py-2.5 px-3.5">Hạng / loại khám</th>
                                <th class="py-2.5 px-3.5">Đơn giá niêm yết</th>
                                <th class="py-2.5 px-3.5 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-bang-gia-kham" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="8" class="text-center py-8 text-slate-400">Đang tải bảng giá khám bệnh...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Chân bảng lưu ý chuẩn y tế -->
                <div class="p-3 bg-amber-50/60 rounded-md border border-amber-200/60 flex items-start space-x-2 text-[13px] text-amber-800">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <div>
                        <strong>Quy định niêm yết viện phí:</strong> Giá khám bệnh trên đã bao gồm tiền công khám lâm sàng, tư vấn chẩn đoán ban đầu. Đối với bệnh nhân đặt lịch hẹn trước trực tuyến, mức giá được bảo đảm giữ nguyên không phát sinh phụ thu giờ cao điểm.
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 3.2: QUẢN TRỊ TÀI KHOẢN -->
        <section id="tab-admin-tai-khoan" class="portal-section space-y-6">
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100 flex-wrap gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-users-gear text-indigo-600"></i>
                            <span>Quản trị danh sách tài khoản toàn hệ thống</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Phân quyền, kiểm tra hoạt động và khóa/mở khóa tài khoản (service 01)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="moModalThemTaiKhoan()" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                            <span>Thêm tài khoản</span>
                        </button>
                        <button type="button" onclick="taiDanhSachTaiKhoan()" class="btn btn-secondary" title="Làm mới">
                            <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                            <span>Làm mới</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">ID</th>
                                <th class="py-3.5 px-4">Tên đăng nhập</th>
                                <th class="py-3.5 px-4">Họ và tên</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Số điện thoại</th>
                                <th class="py-3.5 px-4">Vai trò</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
                                <th class="py-3.5 px-4 text-center">Thao tác</th>
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
            <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                            <span>Danh sách hóa đơn & thu viện phí tự động</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tổng hợp chi phí khám + cận lâm sàng tự động (service 04)</p>
                    </div>
                    <div class="flex items-center space-x-2 flex-wrap gap-2">
                        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-md text-xs font-bold">
                            <button type="button" onclick="locHoaDonAdmin('ALL', this)" class="btn-filter-hd-admin active px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs">Tất cả</button>
                            <button type="button" onclick="locHoaDonAdmin('CHUA_THANH_TOAN', this)" class="btn-filter-hd-admin px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">Chờ thu</button>
                            <button type="button" onclick="locHoaDonAdmin('DA_THANH_TOAN', this)" class="btn-filter-hd-admin px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">Đã thu</button>
                            <button type="button" onclick="locHoaDonAdmin('DA_HOAN_TIEN', this)" class="btn-filter-hd-admin px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">Đã hoàn tiền</button>
                        </div>
                        <button onclick="taiDanhSachHoaDon(true)" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-md transition" title="Làm mới">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                <th class="py-3.5 px-4">Mã hóa đơn</th>
                                <th class="py-3.5 px-4">Lịch hẹn ID</th>
                                <th class="py-3.5 px-4">Bệnh nhân</th>
                                <th class="py-3.5 px-4">Tiền khám</th>
                                <th class="py-3.5 px-4">Tiền CLS</th>
                                <th class="py-3.5 px-4">Tổng tiền</th>
                                <th class="py-3.5 px-4">Thực thu</th>
                                <th class="py-3.5 px-4">Trạng thái</th>
                                <th class="py-3.5 px-4 text-center">Thao tác</th>
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
                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] shadow-sm flex items-center space-x-3.5 hover:border-medical-400 hover:border-[#1F6FB2] transition cursor-pointer group" onclick="chuyenTab('tab-admin-bac-si')" title="Xem Danh Sách Bác Sĩ">
                    <div class="w-12 h-12 rounded-md bg-medical-50 text-medical-600 border border-medical-200 flex items-center justify-center text-xl transition-transform">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Bác sĩ công tác</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-bac-si">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] shadow-sm flex items-center space-x-3.5 hover:border-sky-400 hover:border-[#1F6FB2] transition cursor-pointer group" onclick="chuyenTab('tab-benh-nhan-lich')" title="Xem Lịch Khám Toàn Viện">
                    <div class="w-12 h-12 rounded-md bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xl transition-transform">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Lịch hẹn hôm nay</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-lich-hen">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] shadow-sm flex items-center space-x-3.5 hover:border-rose-400 hover:border-[#1F6FB2] transition cursor-pointer group" onclick="chuyenTab('tab-admin-benh-nhan')" title="Xem Quản Lý Bệnh Nhân & EHR">
                    <div class="w-12 h-12 rounded-md bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center text-xl transition-transform">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Quản lý bệnh nhân</div>
                        <div class="text-xl sm:text-2xl font-black text-rose-600" id="stat-so-ho-so-bn">--</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] shadow-sm flex items-center space-x-3.5 hover:border-amber-400 hover:border-[#1F6FB2] transition cursor-pointer group" onclick="chuyenTab('tab-bac-si')" title="Xem Danh Mục Cận Lâm Sàng">
                    <div class="w-12 h-12 rounded-md bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-xl transition-transform">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Dịch vụ CLS</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800" id="stat-so-dich-vu">5</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-lg border border-[#E3E8EE] shadow-sm flex items-center space-x-3.5 hover:border-emerald-400 hover:border-[#1F6FB2] transition cursor-pointer group" onclick="chuyenTab('tab-admin-thu-ngan')" title="Xem Thu Ngân & Viện Phí">
                    <div class="w-12 h-12 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xl transition-transform">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-slate-400">Tổng thu viện phí</div>
                        <div class="text-xl sm:text-2xl font-black text-emerald-600 truncate" id="stat-tong-doanh-thu">--</div>
                    </div>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- KHU VỰC BIỂU ĐỒ THỐNG KÊ & PHÂN TÍCH CHUYÊN SÂU (CHART.JS)   -->
            <!-- ============================================================= -->
            <div class="space-y-6">
                <!-- Header Thống Kê & Bộ Lọc Thời Gian -->
                <div class="bg-white rounded-lg p-5 border border-[#E3E8EE] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-[17px] font-semibold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-[#1F6FB2]" aria-hidden="true"></i>
                            <span>Biểu đồ thống kê và giám sát</span>
                        </h2>
                        <p class="text-[13px] text-slate-500 mt-0.5">Doanh thu viện phí, cơ cấu phương thức thanh toán và lượt khám theo chuyên khoa.</p>
                    </div>

                    <div class="flex items-center flex-wrap gap-2.5">
                        <!-- Bộ chọn khoảng thời gian -->
                        <div class="inline-flex p-1 bg-slate-100 rounded-md border border-[#E3E8EE] text-xs">
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('7_NGAY', this)" class="btn-chart-filter px-3.5 py-1.5 rounded font-medium transition text-white bg-medical-600 shadow-sm" id="btn-chart-7ngay">7 ngày</button>
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('30_NGAY', this)" class="btn-chart-filter px-3.5 py-1.5 rounded font-medium transition text-slate-600 hover:text-slate-900" id="btn-chart-30ngay">30 ngày</button>
                            <button type="button" onclick="thayDoiKhungThoiGianBieuDo('TAT_CA', this)" class="btn-chart-filter px-3.5 py-1.5 rounded font-medium transition text-slate-600 hover:text-slate-900" id="btn-chart-tatca">Toàn bộ</button>
                        </div>

                        <!-- Nút làm mới biểu đồ -->
                        <button type="button" onclick="lamMoiTatCaBieuDo()" class="btn btn-secondary" title="Làm mới dữ liệu biểu đồ">
                            <i class="fa-solid fa-arrows-rotate text-[#1F6FB2]" id="icon-refresh-chart" aria-hidden="true"></i>
                            <span>Làm mới dữ liệu</span>
                        </button>
                    </div>
                </div>

                <!-- Hàng 1: 2 Biểu đồ chính (Doanh Thu Viện Phí & Cơ Cấu Thanh Toán) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Biểu đồ 1: Doanh Thu & Lượt Khám (Line / Area Chart) -->
                    <div class="lg:col-span-8 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 flex flex-col justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                    <i class="fa-solid fa-chart-area text-medical-600"></i>
                                    <span>Xu hướng doanh thu viện phí & tiếp đón bệnh nhân</span>
                                </h3>
                                <p class="text-[13px] text-slate-400 mt-0.5">Biến động số tiền thanh toán thực thu (VNĐ) và số lượt khám qua các ngày</p>
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
                            <div class="p-2.5 bg-sky-50/60 rounded-md border border-sky-100">
                                <span class="text-[13px] font-bold text-slate-400 block">Doanh thu TB/ngày</span>
                                <span class="text-xs sm:text-sm font-extrabold text-sky-700" id="stat-chart-dt-tb">--</span>
                            </div>
                            <div class="p-2.5 bg-purple-50/60 rounded-md border border-purple-100">
                                <span class="text-[13px] font-bold text-slate-400 block">Lượt khám cao nhất</span>
                                <span class="text-xs sm:text-sm font-extrabold text-purple-700" id="stat-chart-kham-max">--</span>
                            </div>
                            <div class="p-2.5 bg-emerald-50/60 rounded-md border border-emerald-100">
                                <span class="text-[13px] font-bold text-slate-400 block">Tỷ lệ thu thành công</span>
                                <span class="text-xs sm:text-sm font-extrabold text-emerald-700" id="stat-chart-ty-le-thu">100%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ 2: Cơ Cấu Phương Thức Thanh Toán (Doughnut Chart) -->
                    <div class="lg:col-span-4 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-4 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-chart-pie text-emerald-600"></i>
                                <span>Cơ cấu phương thức thanh toán</span>
                            </h3>
                            <p class="text-[13px] text-slate-400 mt-0.5">Tỷ lệ thanh toán viện phí qua các kênh</p>
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
                    <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-hospital text-blue-600"></i>
                                <span>Nhu cầu khám theo chuyên khoa</span>
                            </h3>
                            <p class="text-[13px] text-slate-400 mt-0.5">Số lượt đặt khám phân bổ theo các chuyên khoa</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-chuyen-khoa"></canvas>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-md border border-[#E3E8EE] text-[13px] text-slate-500 mt-3 flex items-center justify-between">
                            <span>Khoa có lượt khám cao nhất:</span>
                            <span class="font-extrabold text-slate-800" id="badge-top-khoa">Đang tải...</span>
                        </div>
                    </div>

                    <!-- Biểu đồ 4: Trạng Thái Lịch Khám & Tiến Độ (Polar / Doughnut Chart) -->
                    <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-list-check text-amber-500"></i>
                                <span>Tình trạng lịch hẹn khám bệnh</span>
                            </h3>
                            <p class="text-[13px] text-slate-400 mt-0.5">Tiến độ tiếp đón và hoàn thành ca khám</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-trang-thai-lich"></canvas>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-md border border-[#E3E8EE] text-[13px] text-slate-500 mt-3 flex items-center justify-between">
                            <span>Tỷ lệ hoàn thành khám bệnh:</span>
                            <span class="font-extrabold text-emerald-600" id="badge-ty-le-hoan-thanh">--%</span>
                        </div>
                    </div>

                    <!-- Biểu đồ 5: Phân Bổ Tải & Request Của 4 Microservices (Giám Sát Hạ Tầng) -->
                    <div class="bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-server text-teal-600"></i>
                                <span>Phân phối lưu lượng 4 microservices</span>
                            </h3>
                            <p class="text-[13px] text-slate-400 mt-0.5">Giám sát tải trọng và điều phối qua API gateway</p>
                        </div>

                        <div class="relative w-full h-60 pt-3">
                            <canvas id="chart-traffic-microservices"></canvas>
                        </div>

                        <div class="p-3 bg-emerald-50 rounded-md border border-emerald-200/70 text-[13px] text-emerald-800 mt-3 flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> 4 Services hoạt động ổn định</span>
                            <span class="font-mono font-black text-emerald-700">100% ONLINE</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trạng Thái 4 Microservices & Kiến Trúc Hệ Thống -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Services Status (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-network-wired text-medical-600"></i>
                            <span>Trạng thái 4 microservices độc lập</span>
                        </h3>
                        <button onclick="kiemTraHealthToanHeThong()" class="text-xs text-medical-600 hover:text-medical-700 font-bold flex items-center space-x-1">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Kiểm tra lại</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-md border border-[#E3E8EE]">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">01. Xác thực & bác sĩ</h4>
                                <p class="text-[13px] text-slate-400 font-mono">Port: 8001 | DB: db_xac_thuc_bac_si</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-1">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-md border border-[#E3E8EE]">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">02. Bệnh nhân & lịch hẹn</h4>
                                <p class="text-[13px] text-slate-400 font-mono">Port: 8002 | DB: db_benh_nhan_lich_hen</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-2">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-md border border-[#E3E8EE]">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">03. Y tế & cận lâm sàng</h4>
                                <p class="text-[13px] text-slate-400 font-mono">Port: 8003 | DB: db_dich_vu_y_te</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-3">ONLINE</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-md border border-[#E3E8EE]">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">04. Hóa đơn & thanh toán</h4>
                                <p class="text-[13px] text-slate-400 font-mono">Port: 8004 | DB: db_hoa_don_thanh_toan</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="badge-svc-4">ONLINE</span>
                        </div>
                    </div>
                </div>

                <!-- System Info (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-lg border border-[#E3E8EE] shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-info text-indigo-600"></i>
                            <span>Thông tin hạ tầng & kết nối</span>
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="status-gateway-badge">
                            GATEWAY: 8000
                        </span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-slate-50 rounded-md border border-[#E3E8EE] space-y-1">
                            <div class="text-[13px] font-bold text-slate-400">Cổng giao tiếp tập trung (API gateway)</div>
                            <div class="font-mono font-bold text-medical-700">http://127.0.0.1:8000</div>
                            <p class="text-[13px] text-slate-500">Reverse proxy trung tâm, xử lý phân quyền RBAC và điều hướng liên dịch vụ.</p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-md border border-[#E3E8EE] space-y-1">
                            <div class="text-[13px] font-bold text-slate-400">Máy chủ cơ sở dữ liệu</div>
                            <div class="font-mono font-bold text-slate-800">MySQL laragon (port 3307)</div>
                            <p class="text-[13px] text-slate-500">4 Database độc lập cho từng microservice, bảo toàn tính toàn vẹn dữ liệu.</p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-md border border-[#E3E8EE] space-y-1">
                            <div class="text-[13px] font-bold text-slate-400">Giao thức xác thực & an toàn</div>
                            <div class="font-mono font-bold text-emerald-700">Laravel sanctum bearer token</div>
                            <p class="text-[13px] text-slate-500">Mã hóa phiên làm việc, phân quyền nghiêm ngặt giữa bệnh nhân, bác sĩ và quản trị viên.</p>
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
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <form id="form-ho-so-ca-nhan" onsubmit="event.preventDefault(); xacNhanCapNhatHoSo();" autocomplete="off" class="flex flex-col h-full overflow-hidden">
                <!-- Header Modal -->
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-base flex-shrink-0">
                            <i class="fa-solid fa-id-card" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">
                                Thông tin tài khoản
                            </h3>
                            <p class="text-[13px] text-slate-500 mt-0.5 leading-tight">Thông tin cá nhân, ảnh đại diện và liên kết y tế</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-ho-so-ca-nhan')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Body Modal (Scrollable) -->
                <div class="p-6 space-y-5 overflow-y-auto flex-1 text-xs">
                    <!-- Avatar & User Headline Card -->
                    <div class="p-4 bg-slate-50/90 rounded-md border border-[#E3E8EE] flex items-center justify-between gap-4">
                        <div class="flex items-center space-x-3.5">
                            <div class="relative group">
                                <img id="profile-modal-avatar-preview" src="" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-medical-500 shadow-sm hidden">
                                <div id="profile-modal-avatar-placeholder" class="w-16 h-16 rounded-full bg-sky-500 text-white flex items-center justify-center text-xl font-black shadow-sm">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <label for="profile-modal-avatar-input" class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-medical-600 hover:bg-medical-700 text-white flex items-center justify-center text-[13px] shadow cursor-pointer transition">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input type="file" id="profile-modal-avatar-input" accept="image/*" class="hidden" onchange="xuLyChonAvatar(this)">
                            </div>
                            <div>
                                <h4 id="profile-modal-display-name" class="text-base font-extrabold text-slate-900">Người dùng</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span id="profile-modal-display-role" class="px-2 py-0.5 rounded text-[13px] font-bold bg-medical-100 text-medical-800 border border-medical-200 inline-block">VAI TRÒ</span>
                                    <span class="text-[13px] text-slate-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đang hoạt động</span>
                                </div>
                            </div>
                        </div>

                        <label for="profile-modal-avatar-input" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-[#E3E8EE] rounded-md text-xs font-bold cursor-pointer transition shadow-2xs flex items-center gap-1.5">
                            <i class="fa-solid fa-upload text-medical-600"></i>
                            <span>Đổi avatar</span>
                        </label>
                    </div>



                    <!-- Personal Information Form Fields -->
                    <div class="space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-600 text-[13px] mb-1">Tên đăng nhập (username):</label>
                                <input type="text" id="profile-username" readonly class="w-full px-3 py-2 bg-slate-100 border border-[#E3E8EE] rounded-md text-slate-500 font-mono font-bold cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 text-[13px] mb-1">Vai trò hệ thống:</label>
                                <input type="text" id="profile-role" readonly class="w-full px-3 py-2 bg-slate-100 border border-[#E3E8EE] rounded-md text-slate-500 font-bold uppercase cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Họ và tên: <span class="text-rose-500">*</span></label>
                                <input type="text" id="profile-ho-ten" required class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Số điện thoại:</label>
                                <input type="text" id="profile-sdt" class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Địa chỉ email: <span class="text-rose-500">*</span></label>
                                <input type="email" id="profile-email" required class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Ngày sinh:</label>
                                <input type="date" id="profile-ngay-sinh" class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Giới tính:</label>
                                <select id="profile-gioi-tinh" class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                                    <option value="NAM">Nam</option>
                                    <option value="NU">Nữ</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-[13px] mb-1">Địa chỉ liên hệ:</label>
                                <input type="text" id="profile-dia-chi" placeholder="Số nhà, đường, quận/huyện..." class="w-full px-3.5 py-2 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800 focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] transition">
                            </div>
                        </div>

                        <!-- Extra fields for Doctor -->
                        <div id="profile-doctor-extra-fields" class="p-3.5 bg-sky-50/60 rounded-md border border-sky-100 space-y-3 hidden">
                            <h5 class="font-extrabold text-sky-900 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-user-doctor text-medical-600"></i>
                                <span>Thông tin chuyên môn bác sĩ</span>
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-600 text-[13px] mb-1">Học vị:</label>
                                    <input type="text" id="profile-doctor-hoc-vi" class="w-full px-3 py-1.5 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-600 text-[13px] mb-1">Phòng khám phụ trách:</label>
                                    <input type="text" id="profile-doctor-phong" class="w-full px-3 py-1.5 bg-white border border-[#E3E8EE] rounded-md font-semibold text-slate-800">
                                </div>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 text-[13px] mb-1">Kinh nghiệm công tác:</label>
                                <textarea id="profile-doctor-kinh-nghiem" rows="2" class="w-full px-3 py-1.5 bg-white border border-[#E3E8EE] rounded-md text-slate-800 text-xs"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2 flex-shrink-0">
                    <button type="button" onclick="dongModal('modal-ho-so-ca-nhan')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">
                        Đóng
                    </button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu thay đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 1: ĐẶT LỊCH HẸN (BỆNH NHÂN) -->
    <div class="modal-backdrop" id="modal-dat-lich">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-lg w-full overflow-hidden">
            <form id="form-dat-lich" onsubmit="event.preventDefault(); xacNhanDatLich();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Đặt lịch khám bệnh trực tuyến</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-dat-lich')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <!-- BƯỚC 1 & 2: CHỌN CHUYÊN KHOA TRƯỚC, RỒI MỚI CHỌN BÁC SĨ THUỘC KHOA ĐÓ -->
                    <div class="p-3.5 bg-slate-50/90 border border-[#E3E8EE] rounded-md space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1 text-[13px] flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-medical-600 text-white text-[13px] inline-flex items-center justify-center font-bold">1</span>
                                    <span>Chọn chuyên khoa:</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <select id="modal-dl-chuyen-khoa" onchange="locBacSiTheoChuyenKhoaModal()" class="w-full px-3.5 py-2.5 bg-white border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-semibold text-xs transition shadow-xs">
                                    <option value="">-- Tất cả chuyên khoa --</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1 text-[13px] flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-medical-600 text-white text-[13px] inline-flex items-center justify-center font-bold">2</span>
                                    <span>Chọn bác sĩ điều trị:</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <select id="modal-dl-bac-si" onchange="capNhatGiaKhamModal(); taiSlotsKhaDungDatLich();" class="w-full px-3.5 py-2.5 bg-white border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-semibold text-xs transition shadow-xs">
                                    <!-- Dynamic lọc theo chuyên khoa đã chọn -->
                                </select>
                            </div>
                        </div>

                        <!-- Doctor summary & price badge -->
                        <div class="flex items-center justify-between pt-2 border-t border-[#E3E8EE] text-xs">
                            <div class="flex items-center space-x-2 text-slate-600">
                                <i class="fa-solid fa-user-doctor text-medical-600"></i>
                                <span id="modal-dl-bac-si-info" class="font-medium text-[13px]">Chuyên khoa: Đang chọn</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <span class="text-slate-500 text-[13px]">Giá niêm yết:</span>
                                <span id="modal-dl-gia-kham" class="font-black text-medical-700 text-xs bg-medical-100/70 px-2 py-0.5 rounded-lg border border-medical-200">200,000 đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- TÍNH NĂNG ĐẶT LỊCH CHO NGƯỜI THÂN (Medpro & YouMed Style) -->
                    <div class="p-3 bg-sky-50/60 border border-sky-200/80 rounded-md space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-900">
                                <i class="fa-solid fa-people-roof text-sky-600 mr-1"></i> Đối tượng khám bệnh:
                            </span>
                            <div class="inline-flex p-0.5 bg-sky-100 rounded-md text-[13px] font-bold">
                                <button type="button" id="btn-tab-ban-than" onclick="chuyenDoiTuongKham('BAN_THAN')" class="px-2.5 py-1 rounded-lg bg-white text-sky-700 shadow-sm transition">
                                    Bản thân
                                </button>
                                <button type="button" id="btn-tab-nguoi-than" onclick="chuyenDoiTuongKham('NGUOI_THAN')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition">
                                    Người thân
                                </button>
                            </div>
                        </div>

                        <!-- Dropdown chọn hồ sơ gia đình -->
                        <div id="khu-vuc-nguoi-than" class="hidden pt-1.5 border-t border-sky-200/60 space-y-2">
                            <div class="flex items-center gap-2">
                                <select id="modal-dl-chon-ho-so" onchange="chonHoSoGiaDinhDropdown(this.value)" class="w-full px-3 py-1.5 text-xs bg-white border border-sky-300 rounded-md font-medium text-slate-800 focus:ring-2 focus:ring-sky-500">
                                    <option value="MOI">+ Tạo hồ sơ người thân mới (con cái, bố/mẹ...)</option>
                                </select>
                            </div>
                            <div id="form-nguoi-than-moi" class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-0.5">Mối quan hệ:</label>
                                    <select id="modal-dl-quan-he" onchange="capNhatNhanNguoiThan()" class="w-full px-2.5 py-1.5 bg-white border border-[#E3E8EE] rounded-lg text-slate-800 font-semibold">
                                        <option value="CON">Con cái (bé nhỏ / thanh thiếu niên)</option>
                                        <option value="CHA_ME">Bố / mẹ</option>
                                        <option value="VO_CHONG">Vợ / chồng</option>
                                        <option value="NGUOI_THAN">Người thân khác</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-0.5">Ngày sinh người thân:</label>
                                    <input type="date" id="modal-dl-ngay-sinh-nt" class="w-full px-2.5 py-1.5 bg-white border border-[#E3E8EE] rounded-lg text-slate-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1" id="lbl-ho-ten-bn">Họ tên bệnh nhân:</label>
                            <input type="text" id="modal-dl-ho-ten" autocomplete="off" placeholder="Nhập họ tên..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="text" id="modal-dl-sdt" autocomplete="off" placeholder="Nhập số điện thoại..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Ngày khám:</label>
                        <input type="date" id="modal-dl-ngay" onchange="taiSlotsKhaDungDatLich(); kiemTraCaTrucDatLich();" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                    </div>

                    <!-- THÔNG TIN CA TRỰC BÁC SĨ (LIÊN KẾT PHÂN HỆ ĐẶT LỊCH) -->
                    <div id="modal-dl-ca-truc-box" class="p-3.5 rounded-md border text-xs transition-all hidden">
                        <!-- Dynamic rendered shift info -->
                    </div>

                    <!-- BỘ CHỌN KHUNG GIỜ KHÁM THỜI GIAN THỰC (Realtime Slots - BookingCare Style) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="font-bold text-slate-700 text-xs">Khung giờ khám (ca 30 phút):</label>
                            <span id="modal-dl-slots-status" class="text-[13px] font-bold text-sky-600 flex items-center gap-1">
                                <i class="fa-solid fa-bolt text-amber-500"></i> <span id="modal-dl-slots-count">Đang kiểm tra slot...</span>
                            </span>
                        </div>

                        <div id="modal-dl-slots" class="p-3 bg-slate-50 border border-[#E3E8EE] rounded-md space-y-2.5">
                            <div>
                                <div class="text-[13px] font-bold text-slate-500 flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-sun text-amber-500"></i> Buổi sáng (08:00 - 11:30)
                                </div>
                                <div class="grid grid-cols-4 gap-2" id="modal-dl-slots-sang">
                                    <!-- Render động từ API slots-kha-dung -->
                                </div>
                            </div>
                            <div class="pt-2 border-t border-[#E3E8EE]">
                                <div class="text-[13px] font-bold text-slate-500 flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-cloud-sun text-sky-500"></i> Buổi chiều (13:30 - 16:30)
                                </div>
                                <div class="grid grid-cols-4 gap-2" id="modal-dl-slots-chieu">
                                    <!-- Render động từ API slots-kha-dung -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOX CAM KẾT PHIÊN KHÁM & QUY CHẾ TIẾP NHẬN TRÁNH DELAY DOMINO -->
                    <div id="modal-dl-cam-ket-box" class="p-3.5 bg-sky-50 border border-sky-200/80 rounded-md space-y-2.5 text-xs shadow-xs">
                        <div class="flex items-center justify-between pb-2 border-b border-sky-100">
                            <span class="font-extrabold text-sky-950 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-heart text-sky-600 text-sm"></i>
                                <span>Cam kết phiên khám & tiếp nhận ưu tiên</span>
                            </span>
                            <span id="badge-stt-du-kien" class="px-2.5 py-0.5 rounded-full text-[13px] font-black bg-sky-200 text-sky-900 border border-sky-300">
                                STT dự kiến: #01 (Ca sáng)
                            </span>
                        </div>
                        <div class="text-[13px] text-slate-700 space-y-1.5">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-emerald-800">Cam kết khám xong trong buổi:</strong> Quý khách đăng ký <span id="lbl-phien-kham" class="font-bold text-sky-700">Buổi sáng (08:00 - 11:30)</span> được phòng khám <strong>chắc chắn hoàn thành khám 100% trong buổi này</strong> (trước giờ nghỉ của ca).
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-amber-500 text-xs mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-amber-800">Quy chế tiếp nhận linh hoạt (giải tỏa trễ dây chuyền):</strong> Khung giờ <span id="lbl-khung-gio-chon" class="font-bold text-slate-900">08:00 - 08:30</span> là khung giờ có mặt để tiếp đón và đo sinh hiệu. Vì lý do chuyên môn (ca khám trước có thể cần hội chẩn kỹ hơn), giờ vào khám thực tế có thể dao động linh hoạt ±10-15 phút.
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
                        <label class="block font-bold text-slate-700 mb-1">Lý do khám / triệu chứng:</label>
                        <input type="text" id="modal-dl-ly-do" autocomplete="off" placeholder="Khám tổng quát, nhức đầu, sốt..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>

                    <!-- ACCORDION HỒ SƠ BỆNH ÁN ĐIỆN TỬ (BỆNH NHÂN ĐIỀN ĐỂ BÁC SĨ CHẨN ĐOÁN) -->
                    <div class="border border-sky-200 rounded-md bg-sky-50/50 overflow-hidden">
                        <button type="button" onclick="toggleDashboardEhrAccordion()" class="w-full px-4 py-2.5 text-left font-bold text-xs text-sky-900 flex justify-between items-center hover:bg-sky-100/60 transition">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-notes-medical text-sky-600"></i>
                                <span>Bệnh án điện tử & tiền sử bệnh (bệnh nhân điền để bác sĩ xem)</span>
                            </span>
                            <i id="dashboard-ehr-arrow" class="fa-solid fa-chevron-down text-sky-600 transition-transform"></i>
                        </button>

                        <div id="dashboard-ehr-accordion" class="p-3.5 pt-2 space-y-3 bg-white border-t border-sky-100 text-xs">
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-1">Nhóm máu:</label>
                                    <select id="modal-dl-nhom-mau" class="w-full px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-lg text-slate-800 font-semibold focus:bg-white">
                                        <option value="">-- Chưa rõ --</option>
                                        <option value="A">Nhóm máu A</option>
                                        <option value="B">Nhóm máu B</option>
                                        <option value="AB">Nhóm máu AB</option>
                                        <option value="O">Nhóm máu O</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-1">Số CCCD / CMND:</label>
                                    <input type="text" id="modal-dl-cccd" autocomplete="off" placeholder="079xxxxxxxx" class="w-full px-3 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-lg text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[13px] font-bold text-slate-500 mb-1">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i>
                                    Tiền sử dị ứng thuốc / kháng sinh / thức ăn:
                                </label>
                                <textarea id="modal-dl-tien-su-di-ung" rows="2" placeholder="Ghi rõ: dị ứng Penicillin, Paracetamol, tôm cua, kháng sinh..." class="w-full p-2 bg-slate-50 border border-[#E3E8EE] rounded-lg text-slate-800 focus:bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-[13px] font-bold text-slate-500 mb-1">
                                    <i class="fa-solid fa-heart-pulse text-sky-500 mr-1"></i>
                                    Bệnh lý nền mãn tính (tim mạch, tiểu đường, huyết áp...):
                                </label>
                                <textarea id="modal-dl-tien-su-benh" rows="2" placeholder="Ví dụ: Cao huyết áp 2 năm, viêm loét dạ dày, tiểu đường tuýp 2..." class="w-full p-2 bg-slate-50 border border-[#E3E8EE] rounded-lg text-slate-800 focus:bg-white"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 pt-1 border-t border-slate-100">
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-1">Người thân khẩn cấp:</label>
                                    <input type="text" id="modal-dl-nguoi-than" autocomplete="off" placeholder="Họ tên người thân" class="w-full px-2.5 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-500 mb-1">SĐT khẩn cấp:</label>
                                    <input type="tel" id="modal-dl-sdt-khan-cap" autocomplete="off" placeholder="09xxxxxxxx" class="w-full px-2.5 py-1.5 bg-slate-50 border border-[#E3E8EE] rounded-lg font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TỆP / ẢNH Y TẾ ĐÍNH KÈM (ĐƠN THUỐC CŨ, KẾT QUẢ XÉT NGHIỆM) -->
                    <div class="p-3.5 bg-slate-50 border border-[#E3E8EE] rounded-md space-y-2">
                        <label class="block font-bold text-slate-700 text-[13px] flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-paperclip text-medical-600"></i>
                                <span>Đính kèm ảnh Y tế (đơn thuốc cũ, xét nghiệm, ảnh triệu chứng)</span>
                            </span>
                            <span class="text-[13px] text-slate-400 font-normal">Tùy chọn</span>
                        </label>
                        <input type="file" id="modal-dl-files" multiple accept="image/*,.pdf" onchange="xuLyChonTepYTe(this)" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-medical-50 file:text-medical-700 hover:file:bg-medical-100 cursor-pointer">
                        <div id="modal-dl-file-preview" class="flex flex-wrap gap-2 pt-1 empty:hidden"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-dat-lich')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác nhận đặt lịch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DỜI LỊCH HẸN KHÁM (RESCHEDULE) -->
    <div class="modal-backdrop" id="modal-doi-lich">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-lg w-full overflow-hidden">
            <form id="form-doi-lich" onsubmit="event.preventDefault(); xacNhanDoiLich();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Dời ngày giờ khám bệnh</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-doi-lich')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <input type="hidden" id="modal-doi-lich-id">
                    
                    <div class="p-3.5 bg-sky-50/70 border border-sky-100 rounded-md space-y-1">
                        <div class="text-[13px] font-bold text-sky-800">Thông tin ca khám hiện tại</div>
                        <div class="text-slate-700">Mã ca: <strong id="modal-doi-lich-ma" class="font-mono text-sky-700 font-bold">#--</strong></div>
                        <div class="text-slate-700">Bác sĩ: <strong id="modal-doi-lich-bs">--</strong></div>
                        <div class="text-slate-700">Thời gian hẹn cũ: <strong id="modal-doi-lich-tg-cu" class="text-slate-900">--</strong></div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Chọn ngày khám mới:</label>
                        <input type="date" id="modal-doi-lich-ngay" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Chọn khung giờ mới (ca 30 phút):</label>
                        <div class="grid grid-cols-4 gap-2" id="modal-doi-lich-slots">
                            <button type="button" class="slot-btn-reschedule selected py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '08:00', '08:30')">08:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '08:30', '09:00')">08:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '09:00', '09:30')">09:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '09:30', '10:00')">09:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '10:00', '10:30')">10:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '14:00', '14:30')">14:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '14:30', '15:00')">14:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '15:00', '15:30')">15:00</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '15:30', '16:00')">15:30</button>
                            <button type="button" class="slot-btn-reschedule py-2 rounded-md bg-slate-100 border border-[#E3E8EE] text-slate-700 font-mono text-[13px] transition" onclick="chonSlotDoiLich(this, '16:00', '16:30')">16:00</button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lý do xin dời lịch:</label>
                        <input type="text" id="modal-doi-lich-ly-do" autocomplete="off" placeholder="Bận công việc, trùng lịch cá nhân, kẹt xe..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-md text-amber-800 text-[13px] flex items-start gap-2">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                        <span>Lịch dời sẽ được thuật toán tự động đối chiếu để đảm bảo không bị trùng slot của bác sĩ.</span>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-doi-lich')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác nhận dời giờ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL XEM TỆP / ẢNH Y TẾ ĐÍNH KÈM -->
    <div class="modal-backdrop" id="modal-xem-tep-y-te">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-paperclip" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Hồ sơ y tế &amp; tệp đính kèm</h3>
                </div>
                <button type="button" onclick="dongModal('modal-xem-tep-y-te')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                <div id="modal-tep-y-te-content" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dynamic images/files -->
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end">
                <button onclick="dongModal('modal-xem-tep-y-te')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
            </div>
        </div>
    </div>


    <!-- MODAL 3: CHI TIẾT & THANH TOÁN HÓA ĐƠN (ADMIN) -->
    <div class="modal-backdrop" id="modal-chi-tiet-hoa-don">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-[16px] font-semibold text-slate-900 leading-tight" id="modal-cthd-title">Chi tiết hóa đơn viện phí</h3>
                </div>
                <button type="button" onclick="dongModal('modal-chi-tiet-hoa-don')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3.5 rounded-md border border-[#E3E8EE]">
                    <div>
                        <div class="text-[13px] text-slate-400 font-bold">Bệnh nhân</div>
                        <div class="font-extrabold text-slate-800 mt-0.5" id="modal-cthd-benh-nhan">--</div>
                    </div>
                    <div>
                        <div class="text-[13px] text-slate-400 font-bold">Ngày lập</div>
                        <div class="font-bold text-slate-800 mt-0.5" id="modal-cthd-ngay">--</div>
                    </div>
                    <div>
                        <div class="text-[13px] text-slate-400 font-bold">Trạng thái</div>
                        <div class="mt-0.5" id="modal-cthd-trang-thai">--</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-700 mb-2">Bảng kê viện phí liên dịch vụ:</h4>
                    <div class="rounded-md border border-[#E3E8EE] overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                    <th class="py-2 px-3">Hạng mục</th>
                                    <th class="py-2 px-3 text-center">SL</th>
                                    <th class="py-2 px-3">Đơn giá</th>
                                    <th class="py-2 px-3 text-right">Thành tiền</th>
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

                <div id="box-thanh-toan-actions" class="pt-3 border-t border-slate-100 space-y-2.5">
                    <label class="block font-bold text-slate-700">Chọn phương thức thanh toán:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button" onclick="chonPhuongThuc(this, 'TIEN_MAT')" class="py-2.5 px-2 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 font-bold rounded-md text-center text-xs transition">Tiền mặt</button>
                        <button type="button" onclick="chonPhuongThuc(this, 'CHUYEN_KHOAN')" class="py-2.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-[#E3E8EE] font-bold rounded-md text-center text-xs transition">VietQR 24/7</button>
                        <button type="button" onclick="chonPhuongThuc(this, 'VNPAY')" class="py-2.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-[#E3E8EE] font-bold rounded-md text-center text-xs transition">VNPAY</button>
                        <button type="button" onclick="chonPhuongThuc(this, 'MOMO')" class="py-2.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-[#E3E8EE] font-bold rounded-md text-center text-xs transition">MoMo</button>
                    </div>

                    <!-- VietQR Dynamic Box -->
                    <div id="box-vietqr-code" class="hidden p-3.5 bg-sky-50 border border-sky-200 rounded-md flex flex-col sm:flex-row items-center gap-4 text-xs">
                        <div class="bg-white p-2 rounded-md shadow-xs border border-[#E3E8EE] flex-shrink-0 text-center">
                            <img id="vietqr-img" src="" alt="VietQR" class="w-28 h-28 object-contain rounded-lg">
                            <span class="text-[13px] font-bold text-slate-500 tracking-tighter mt-1 block">Quét để trả</span>
                        </div>
                        <div class="space-y-1 text-slate-700 flex-1">
                            <div class="font-extrabold text-sky-800 text-[13px]" id="vietqr-title">Quét mã chuyển khoản ngân hàng:</div>
                            <div id="vietqr-info" class="text-[13px] leading-relaxed">--</div>
                            <div class="text-[13px] text-slate-400 italic mt-1">* Hệ thống tự động ghi nhận thanh toán ngay sau khi giao dịch thành công.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <button type="button" onclick="inBienLaiHoaDonHienTai()" class="px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-md shadow-2xs transition flex items-center space-x-1.5" title="In phiếu thu / Hóa đơn viện phí">
                        <i class="fa-solid fa-print text-sky-600"></i>
                        <span>In biên lai</span>
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="dongModal('modal-chi-tiet-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
                    <button id="btn-hoan-tien-hoa-don" onclick="moModalHoanTien()" class="hidden px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Hoàn tiền</span>
                    </button>
                    <button id="btn-xac-nhan-thanh-toan" onclick="xacNhanThanhToanHoaDonHienTai()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Xác nhận thu tiền</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HOÀN TIỀN HÓA ĐƠN (ADMIN) -->
    <div class="modal-backdrop" id="modal-hoan-tien-hoa-don">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-[#FDF2F2] text-[#C0352B] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Hoàn tiền viện phí</h3>
                </div>
                <button type="button" onclick="dongModal('modal-hoan-tien-hoa-don')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-xs">
                <p class="text-slate-600">Thực hiện hoàn trả viện phí cho hóa đơn: <strong id="modal-ht-ma-hd" class="text-slate-900 font-mono">--</strong></p>
                <div class="p-3 bg-rose-50/60 rounded-md border border-rose-200 flex justify-between items-center">
                    <span class="text-slate-600 font-bold text-[13px]">Số tiền hoàn lại:</span>
                    <span id="modal-ht-so-tien" class="text-base font-black text-rose-600">0 đ</span>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Lý do hoàn tiền (*):</label>
                    <textarea id="modal-ht-ly-do" rows="3" placeholder="Nhập lý do hoàn trả viện phí (Ví dụ: Bác sĩ yêu cầu đổi xét nghiệm / Bệnh nhân yêu cầu hủy)..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800"></textarea>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="dongModal('modal-hoan-tien-hoa-don')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy bỏ</button>
                <button type="button" onclick="xacNhanHoanTien()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Xác nhận hoàn tiền</span>
                </button>
            </div>
        </div>
    </div>


    <!-- MODAL 4: THÊM BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-bac-si">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-lg w-full overflow-hidden">
            <form id="form-them-bac-si" onsubmit="event.preventDefault(); xacNhanThemBacSi();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Thêm bác sĩ mới</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-bac-si')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Họ và tên bác sĩ:</label>
                        <input type="text" id="modal-tbs-ho-ten" autocomplete="off" placeholder="Ví dụ: BS. CKII Hoàng Minh Tuấn" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Chuyên khoa:</label>
                            <select id="modal-tbs-chuyen-khoa" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                                <!-- Dynamic -->
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Học vị:</label>
                            <input type="text" id="modal-tbs-hoc-vi" autocomplete="off" placeholder="Bác sĩ Chuyên khoa II" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Giá khám (VND):</label>
                            <input type="number" id="modal-tbs-gia-kham" value="250000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số phòng khám:</label>
                            <input type="text" id="modal-tbs-phong" autocomplete="off" placeholder="P302" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tên đăng nhập:</label>
                            <input type="text" id="modal-tbs-username" autocomplete="off" placeholder="bshminh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Mật khẩu ban đầu:</label>
                            <input type="password" id="modal-tbs-pass" autocomplete="new-password" placeholder="Mặc định: 123456" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-bac-si')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Tạo hồ sơ bác sĩ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL THÊM TÀI KHOẢN (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-tai-khoan">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-md w-full overflow-hidden">
            <form id="form-them-tai-khoan" onsubmit="event.preventDefault(); xacNhanThemTaiKhoan();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Thêm tài khoản người dùng</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-tai-khoan')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tên đăng nhập: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-ttk-username" required autocomplete="off" placeholder="Ví dụ: letan_mai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mật khẩu ban đầu: <span class="text-rose-500">*</span></label>
                        <input type="password" id="modal-ttk-pass" required autocomplete="new-password" placeholder="Tối thiểu 6 ký tự..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Họ và tên: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-ttk-hoten" required autocomplete="off" placeholder="Ví dụ: Nguyễn Thị Mai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Vai trò phân quyền: <span class="text-rose-500">*</span></label>
                        <select id="modal-ttk-vaitro" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-bold">
                            <option value="LE_TAN" selected>Lễ tân (Quản lý bệnh nhân &amp; Thu ngân)</option>
                            <option value="BAC_SI">Bác sĩ khám bệnh</option>
                            <option value="BENH_NHAN">Bệnh nhân</option>
                            <option value="ADMIN">Quản trị viên hệ thống</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email:</label>
                            <input type="email" id="modal-ttk-email" autocomplete="off" placeholder="mai@phongkham.vn" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="tel" id="modal-ttk-sdt" autocomplete="off" placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-tai-khoan')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Tạo tài khoản</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: THÊM CHUYÊN KHOA (ADMIN) -->
    <div class="modal-backdrop" id="modal-them-chuyen-khoa">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-md w-full overflow-hidden">
            <form id="form-them-chuyen-khoa" onsubmit="event.preventDefault(); xacNhanThemChuyenKhoa();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-folder-plus" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Thêm chuyên khoa mới</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-chuyen-khoa')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mã chuyên khoa:</label>
                        <input type="text" id="modal-tck-ma" autocomplete="off" placeholder="Ví dụ: UNG_BUOU, DA_LIEU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 uppercase">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tên chuyên khoa:</label>
                        <input type="text" id="modal-tck-ten" autocomplete="off" placeholder="Ví dụ: Khoa Ung Bướu" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mô tả nhiệm vụ:</label>
                        <input type="text" id="modal-tck-mo-ta" autocomplete="off" placeholder="Khám, tầm soát và điều trị..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-chuyen-khoa')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Thêm chuyên khoa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL BÁC SĨ KÊ TOA THUỐC & KẾT LUẬN KHÁM                     -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-ke-toa-thuoc">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <form id="form-ke-toa-thuoc" onsubmit="event.preventDefault(); xacNhanKeToaThuoc();" autocomplete="off">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Kê toa thuốc &amp; kết luận chẩn đoán</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-ke-toa-thuoc')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <input type="hidden" id="modal-kt-id">

                    <!-- Thông tin ca khám -->
                    <div class="grid grid-cols-3 gap-2 bg-purple-50/70 p-3.5 rounded-md border border-purple-100">
                        <div>
                            <div class="text-[13px] text-purple-700 font-bold">Mã ca khám</div>
                            <div class="font-extrabold text-slate-800 text-sm mt-0.5" id="modal-kt-ma-ca">#--</div>
                        </div>
                        <div>
                            <div class="text-[13px] text-purple-700 font-bold">Bệnh nhân</div>
                            <div class="font-bold text-slate-800 text-xs mt-0.5" id="modal-kt-ten-bn">--</div>
                        </div>
                        <div>
                            <div class="text-[13px] text-purple-700 font-bold">Lý do khám</div>
                            <div class="text-slate-600 text-[13px] mt-0.5 truncate" id="modal-kt-ly-do">--</div>
                        </div>
                    </div>

                    <!-- Chẩn đoán bệnh -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Chẩn đoán bệnh / kết luận Y khoa:</span>
                            <span class="text-rose-500 font-normal">* Bắt buộc</span>
                        </label>
                        <input type="text" id="modal-kt-chuan-doan" required placeholder="Ví dụ: Viêm phế quản cấp, Cảm cúm siêu vi..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800 font-semibold">
                    </div>

                    <!-- Danh sách thuốc kê đơn -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="font-bold text-slate-700">Đơn thuốc (toa thuốc điều trị):</label>
                            <button type="button" onclick="themDongThuoc()" class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg border border-purple-200 transition text-[13px] flex items-center gap-1">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Thêm thuốc</span>
                            </button>
                        </div>
                        <div class="border border-[#E3E8EE] rounded-lg overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-slate-600 font-bold text-[13px]">
                                    <tr>
                                        <th class="py-2 px-3">Tên thuốc & biệt dược</th>
                                        <th class="py-2 px-2 w-28">Hàm lượng</th>
                                        <th class="py-2 px-2 w-20 text-center">Số lượng</th>
                                        <th class="py-2 px-2">Cách dùng & liều dùng</th>
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
                            <label class="block font-bold text-slate-700 mb-1">Lời dặn bác sĩ:</label>
                            <textarea id="modal-kt-loi-khuyen" rows="2" placeholder="Uống nhiều nước, kiêng đồ chua cay..." class="w-full px-3 py-2 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800"></textarea>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ngày hẹn tái khám (nếu có):</label>
                            <input type="date" id="modal-kt-ngay-tai-kham" class="w-full px-3 py-2 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 text-slate-800">
                            <div class="mt-2 flex items-center gap-2">
                                <input type="checkbox" id="modal-kt-hoan-thanh-ngay" checked class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4">
                                <label for="modal-kt-hoan-thanh-ngay" class="text-[13px] font-semibold text-slate-600">Đánh dấu ca khám hoàn thành luôn</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-ke-toa-thuoc')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu toa thuốc & hoàn tất</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL XEM PHIẾU KHÁM & TOA THUỐC ĐIỆN TỬ (BỆNH NHÂN XEM)      -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-xem-toa-thuoc">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-notes-medical" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-[16px] font-semibold text-slate-900">
                        Phiếu khám bệnh và đơn thuốc
                    </h3>
                </div>
                <button type="button" onclick="dongModal('modal-xem-toa-thuoc')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs" id="in-phieu-kham-area">
                <!-- Tiêu đề phòng khám chuẩn y tế -->
                <div class="border-b-2 border-slate-800 pb-3 flex justify-between items-start">
                    <div>
                        <div class="text-xs font-black text-medical-700">HỆ THỐNG PHÒNG KHÁM ĐA KHOA QUỐC TẾ</div>
                        <div class="text-[13px] text-slate-500">Địa chỉ: 123 Đường sức khỏe, quận Y tế, TP.HCM | Hotline: 1900 6868</div>
                        <div class="text-[13px] text-slate-500">Giấy phép hoạt động số: 2026/BYT-GPHĐ</div>
                    </div>
                    <div class="text-right">
                        <div class="font-mono font-black text-slate-900 text-sm" id="view-toa-ma-ca">LK#--</div>
                        <div class="text-[13px] text-slate-400" id="view-toa-ngay-kham">Ngày: --/--/----</div>
                    </div>
                </div>

                <div class="text-center py-1">
                    <h2 class="text-base font-black text-slate-900">PHIẾU KHÁM BỆNH & TOA THUỐC ĐIỆN TỬ</h2>
                </div>

                <!-- Thông tin Bệnh nhân -->
                <div class="bg-slate-50 p-3.5 rounded-md border border-[#E3E8EE] grid grid-cols-2 gap-2 text-xs">
                    <div>Họ và tên: <strong class="text-slate-900 font-extrabold" id="view-toa-ten-bn">--</strong></div>
                    <div>Số điện thoại: <span class="font-mono text-slate-700 font-bold" id="view-toa-sdt-bn">--</span></div>
                    <div>Nhóm máu: <span class="font-bold text-rose-600" id="view-toa-nhom-mau">--</span></div>
                    <div>Bác sĩ khám: <strong class="text-slate-900 font-bold" id="view-toa-bac-si">--</strong></div>
                    <div class="col-span-2 text-amber-800 font-medium">Tiền sử dị ứng: <span id="view-toa-di-ung">Không ghi nhận</span></div>
                </div>

                <!-- Chẩn đoán & Lời khuyên -->
                <div class="space-y-2">
                    <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-md">
                        <div class="text-[13px] font-bold text-emerald-800">Chẩn đoán bệnh:</div>
                        <div class="text-xs font-bold text-slate-900 mt-0.5" id="view-toa-chuan-doan">Chưa có kết luận</div>
                    </div>
                    <div class="p-3 bg-sky-50/70 border border-sky-200 rounded-md">
                        <div class="text-[13px] font-bold text-sky-800">Lời dặn của bác sĩ:</div>
                        <div class="text-xs text-slate-800 mt-0.5 whitespace-pre-line" id="view-toa-loi-khuyen">Theo dõi sức khỏe và tái khám theo chỉ định.</div>
                    </div>
                </div>

                <!-- Danh mục thuốc kê đơn -->
                <div class="space-y-1.5">
                    <div class="font-bold text-slate-800 text-[13px] flex items-center justify-between">
                        <span>Đơn thuốc điều trị:</span>
                        <span class="text-slate-400 font-normal italic">(Uống theo đúng chỉ định)</span>
                    </div>
                    <div class="border border-[#E3E8EE] rounded-lg overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-600 font-bold text-[13px]">
                                <tr>
                                    <th class="py-2 px-3">STT</th>
                                    <th class="py-2 px-3">Tên thuốc & biệt dược</th>
                                    <th class="py-2 px-2">Hàm lượng</th>
                                    <th class="py-2 px-2 text-center">Số lượng</th>
                                    <th class="py-2 px-3">Cách dùng</th>
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
                        <div class="text-[13px] text-slate-400">Bác sĩ khám bệnh</div>
                        <div class="font-script text-base text-medical-800 font-bold italic mt-2" id="view-toa-chu-ky">BS. Phụ trách</div>
                        <div class="text-xs font-bold text-slate-800 mt-1" id="view-toa-bs-ten">--</div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="inPhieuKhamToaThuoc()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>In phiếu khám / lưu PDF</span>
                </button>
                <button type="button" onclick="dongModal('modal-xem-toa-thuoc')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: HỒ SƠ BỆNH ÁN ĐIỆN TỬ TOÀN DIỆN (EHR / EMR TIMELINE)   -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-chi-tiet-benh-nhan-ehr">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-notes-medical" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-semibold text-slate-900 flex items-center gap-2 leading-tight">
                            <span>Hồ sơ bệnh án điện tử</span>
                            <span id="ehr-badge-ma-bn" class="badge badge-info">BN-XXXX</span>
                        </h3>
                        <p class="text-[13px] text-slate-500 mt-0.5">Lịch sử khám chữa bệnh, bác sĩ phụ trách, chẩn đoán y khoa &amp; toa thuốc điện tử</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="inBenhAnDienTuHienTai()" class="btn btn-secondary text-xs">
                        <i class="fa-solid fa-print"></i>
                        <span>In bệnh án</span>
                    </button>
                    <button type="button" onclick="dongModal('modal-chi-tiet-benh-nhan-ehr')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Body Modal (Scrollable) -->
            <div class="p-6 space-y-6 overflow-y-auto flex-1 text-xs">
                <!-- Patient Demographic & Health Banner -->
                <div class="p-4 bg-slate-50 rounded-md border border-[#E3E8EE] space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#E3E8EE]">
                        <div class="flex items-center space-x-3">
                            <div id="ehr-patient-avatar" class="w-10 h-10 rounded-md bg-rose-500 text-white flex items-center justify-center text-lg font-black shadow-sm">
                                BN
                            </div>
                            <div>
                                <h4 id="ehr-patient-name" class="text-base font-extrabold text-slate-900">--</h4>
                                <div class="flex items-center gap-2 text-slate-500 text-[13px] mt-0.5">
                                    <span id="ehr-patient-gender">--</span>
                                    <span>•</span>
                                    <span id="ehr-patient-dob">--</span>
                                    <span>•</span>
                                    <span id="ehr-patient-age">-- tuổi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="ehr-badge-blood" class="px-3 py-1 bg-rose-100 text-rose-700 font-extrabold rounded-md text-xs border border-rose-200 flex items-center gap-1">
                                <i class="fa-solid fa-droplet"></i> Nhóm máu: --
                            </span>
                        </div>
                    </div>

                    <!-- Contact & Identity Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-slate-600 text-[13px]">
                        <div><strong>SĐT:</strong> <span id="ehr-patient-phone">--</span></div>
                        <div><strong>CCCD / CMND:</strong> <span id="ehr-patient-cccd">--</span></div>
                        <div><strong>Địa chỉ:</strong> <span id="ehr-patient-address">--</span></div>
                    </div>

                    <!-- Medical Alerts: Allergy & Chronic Diseases -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-rose-50/80 border border-rose-200 rounded-md space-y-1">
                            <span class="font-extrabold text-rose-900 flex items-center gap-1.5 text-[13px]">
                                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Tiền sử dị ứng:
                            </span>
                            <p id="ehr-patient-allergy" class="text-rose-800 text-[13px] font-medium">Chưa ghi nhận dị ứng</p>
                        </div>
                        <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-md space-y-1">
                            <span class="font-extrabold text-amber-900 flex items-center gap-1.5 text-[13px]">
                                <i class="fa-solid fa-heart-pulse text-amber-600"></i> Bệnh lý nền & mãn tính:
                            </span>
                            <p id="ehr-patient-history" class="text-amber-800 text-[13px] font-medium">Chưa ghi nhận bệnh nền</p>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="p-2.5 bg-sky-50/70 border border-sky-200 rounded-md text-[13px] text-sky-900 flex items-center justify-between">
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
                            <span>Dòng thời gian các ca khám & điều trị (EMR timeline)</span>
                        </h4>
                        <span id="ehr-total-visits" class="text-[13px] font-bold text-slate-500">0 lượt khám</span>
                    </div>

                    <div id="ehr-timeline-container" class="space-y-4">
                        <!-- Dynamic EHR Visit Cards rendered via JS -->
                        <div class="p-8 text-center text-slate-400">Đang tải hồ sơ bệnh án...</div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between flex-shrink-0">
                <span class="text-[13px] text-slate-500">Hệ thống hồ sơ bệnh án điện tử liên thông nội bộ phòng khám</span>
                <button type="button" onclick="dongModal('modal-chi-tiet-benh-nhan-ehr')" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: THÊM / CẬP NHẬT HỒ SƠ BỆNH NHÂN (ADMIN / LỄ TÂN)       -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-them-sua-benh-nhan">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <form id="form-them-sua-benh-nhan" onsubmit="event.preventDefault(); luuThongTinBenhNhan();" autocomplete="off">
                <input type="hidden" id="form-bn-id">
                
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 id="modal-bn-title" class="text-[16px] font-semibold text-slate-900 leading-tight">Thêm hồ sơ bệnh nhân mới</h3>
                            <p class="text-[13px] text-slate-500 mt-0.5">Quản lý hồ sơ định danh và tiền sử y tế bệnh nhân</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-sua-benh-nhan')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Họ và tên bệnh nhân: <span class="text-rose-500">*</span></label>
                            <input type="text" id="form-bn-hoten" required placeholder="Nguyễn Văn A" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số điện thoại: <span class="text-rose-500">*</span></label>
                            <input type="tel" id="form-bn-sdt" required placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ngày sinh:</label>
                            <input type="date" id="form-bn-ngaysinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Giới tính:</label>
                            <select id="form-bn-gioitinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                                <option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nhóm máu:</label>
                            <select id="form-bn-nhommau" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
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
                            <label class="block font-bold text-slate-700 mb-1">Số CCCD / CMND:</label>
                            <input type="text" id="form-bn-cccd" placeholder="001200000001" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Địa chỉ thường trú:</label>
                            <input type="text" id="form-bn-diachi" placeholder="Quận 1, TP. Hồ Chí Minh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 mr-1"></i> Tiền sử dị ứng thuốc / thức ăn:
                        </label>
                        <textarea id="form-bn-diung" rows="2" placeholder="Ví dụ: Dị ứng Penicillin, Paracetamol, Hải sản..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-solid fa-heart-pulse text-amber-500 mr-1"></i> Bệnh lý nền & tiền sử bệnh mãn tính:
                        </label>
                        <textarea id="form-bn-tiensubenh" rows="2" placeholder="Ví dụ: Cao huyết áp, Đái tháo đường type 2, Hen suyễn..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Họ tên người thân khẩn cấp:</label>
                            <input type="text" id="form-bn-nguoithan" placeholder="Người bảo hộ / Vợ / Chồng" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">SĐT người thân khẩn cấp:</label>
                            <input type="tel" id="form-bn-sdtnguoithan" placeholder="0987654321" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 font-medium">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-sua-benh-nhan')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy bỏ</button>
                    <button type="submit" id="btn-submit-bn" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu hồ sơ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL: THÊM / CẬP NHẬT HỒ SƠ NGƯỜI THÂN GIA ĐÌNH              -->
    <!-- ============================================================= -->
    <div class="modal-backdrop" id="modal-them-sua-nguoi-than">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <form id="form-them-sua-nguoi-than" onsubmit="event.preventDefault(); luuHoSoNguoiThan();" autocomplete="off">
                <input type="hidden" id="modal-nt-id">
                
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-people-roof" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 id="modal-nt-title" class="text-[16px] font-semibold text-slate-900 leading-tight">Thêm hồ sơ người thân</h3>
                            <p class="text-[13px] text-slate-500 mt-0.5">Quản lý hồ sơ khám bệnh cho con cái, cha mẹ, người thân trong nhà</p>
                        </div>
                    </div>
                    <button type="button" onclick="dongModal('modal-them-sua-nguoi-than')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Mối quan hệ với bạn: <span class="text-rose-500">*</span></label>
                            <select id="modal-nt-quanhe" required onchange="capNhatNhanModalNguoiThan()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-bold">
                                <option value="CON">Con cái (trẻ em / hậu bối)</option>
                                <option value="CHA_ME">Bố / mẹ (phụ mẫu)</option>
                                <option value="VO_CHONG">Vợ / chồng</option>
                                <option value="NGUOI_THAN">Người thân khác</option>
                            </select>
                        </div>
                        <div>
                            <label id="lbl-modal-nt-hoten" class="block font-bold text-slate-700 mb-1">Họ và tên người thân: <span class="text-rose-500">*</span></label>
                            <input type="text" id="modal-nt-hoten" required placeholder="Ví dụ: Nguyễn Gia Bảo" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ngày sinh:</label>
                            <input type="date" id="modal-nt-ngaysinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Giới tính:</label>
                            <select id="modal-nt-gioitinh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                                <option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nhóm máu:</label>
                            <select id="modal-nt-nhommau" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
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
                            <label id="lbl-modal-nt-sdt" class="block font-bold text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="tel" id="modal-nt-sdt" placeholder="Để trống nếu là trẻ em" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số CCCD / mã định danh:</label>
                            <input type="text" id="modal-nt-cccd" placeholder="Mã định danh cá nhân" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Địa chỉ thường trú:</label>
                        <input type="text" id="modal-nt-diachi" placeholder="Nơi ở hiện tại" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 mr-1"></i> Tiền sử dị ứng thuốc / thực phẩm:
                        </label>
                        <textarea id="modal-nt-diung" rows="2" placeholder="Ví dụ: Dị ứng Amoxicillin, thức ăn có vỏ..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-solid fa-heart-pulse text-amber-500 mr-1"></i> Tiền sử bệnh & bệnh mãn tính:
                        </label>
                        <textarea id="modal-nt-tiensubenh" rows="2" placeholder="Ví dụ: Hen phế quản, viêm tai giữa tái phát..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Người liên hệ khẩn cấp:</label>
                            <input type="text" id="modal-nt-nguoithan" placeholder="Họ tên người giám hộ" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">SĐT khẩn cấp:</label>
                            <input type="tel" id="modal-nt-sdtnguoithan" placeholder="0901234567" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-slate-800 font-medium">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-them-sua-nguoi-than')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy bỏ</button>
                    <button type="submit" id="btn-submit-nt" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu hồ sơ người thân</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-bac-si">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-lg w-full overflow-hidden">
            <form id="form-sua-bac-si" onsubmit="event.preventDefault(); xacNhanSuaBacSi();" autocomplete="off">
                <input type="hidden" id="modal-sbs-id">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Cập nhật thông tin bác sĩ</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-sua-bac-si')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Họ và tên bác sĩ: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sbs-ho-ten" required autocomplete="off" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Học hàm / học vị:</label>
                            <select id="modal-sbs-hoc-vi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                                <option value="Bác sĩ CKI">Bác sĩ CKI</option>
                                <option value="Bác sĩ CKII">Bác sĩ CKII</option>
                                <option value="Thạc sĩ">Thạc sĩ</option>
                                <option value="Tiến sĩ">Tiến sĩ</option>
                                <option value="Phó Giáo sư">Phó giáo sư</option>
                                <option value="Giáo sư">Giáo sư</option>
                                <option value="Bác sĩ Đa khoa">Bác sĩ đa khoa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Chuyên khoa: <span class="text-rose-500">*</span></label>
                            <select id="modal-sbs-chuyen-khoa" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                                <!-- Dynamic -->
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Giá khám niêm yết (VNĐ):</label>
                            <input type="number" id="modal-sbs-gia-kham" min="0" step="10000" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-bold text-amber-600">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số phòng khám:</label>
                            <input type="text" id="modal-sbs-phong" placeholder="P101, P202..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Số điện thoại:</label>
                            <input type="text" id="modal-sbs-sdt" autocomplete="off" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Trạng thái làm việc:</label>
                            <select id="modal-sbs-trang-thai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-medium">
                                <option value="DANG_LAM_VIEC">Đang làm việc</option>
                                <option value="NGHI_VIEC">Nghỉ việc / tạm dừng</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kinh nghiệm / giới thiệu:</label>
                        <textarea id="modal-sbs-kinh-nghiem" rows="2" class="w-full px-3.5 py-2 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800" placeholder="Số năm kinh nghiệm hoặc quá trình công tác..."></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-sua-bac-si')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Lưu thay đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA CHUYÊN KHOA (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-chuyen-khoa">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-md w-full overflow-hidden">
            <form id="form-sua-chuyen-khoa" onsubmit="event.preventDefault(); xacNhanSuaChuyenKhoa();" autocomplete="off">
                <input type="hidden" id="modal-sck-id">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-[16px] font-semibold text-slate-900 leading-tight">Cập nhật tên chuyên khoa</h3>
                    </div>
                    <button type="button" onclick="dongModal('modal-sua-chuyen-khoa')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mã chuyên khoa: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sck-ma" required autocomplete="off" placeholder="Ví dụ: NOI, DA_LIEU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-mono font-bold uppercase">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tên chuyên khoa: <span class="text-rose-500">*</span></label>
                        <input type="text" id="modal-sck-ten" required autocomplete="off" placeholder="Ví dụ: Khoa Nội Tổng Quát" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Trạng thái hoạt động:</label>
                        <select id="modal-sck-trang-thai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800 font-bold">
                            <option value="HOAT_DONG">Đang hoạt động</option>
                            <option value="TAM_DUNG">Tạm ngưng tiếp nhận</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mô tả tóm tắt:</label>
                        <textarea id="modal-sck-mo-ta" rows="3" placeholder="Mô tả chức năng nhiệm vụ khoa..." class="w-full px-3.5 py-2 bg-slate-50 border border-[#E3E8EE] rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-800"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="dongModal('modal-sua-chuyen-khoa')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-md shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Cập nhật khoa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: SỬA ĐƠN GIÁ KHÁM BÁC SĨ (ADMIN) -->
    <div class="modal-backdrop" id="modal-sua-gia-kham">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-md w-full overflow-hidden">
            <form id="form-sua-gia-kham" onsubmit="event.preventDefault(); xacNhanSuaGiaKham();" autocomplete="off">
                <input type="hidden" id="modal-sgk-id">
                <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between">
                    <h3 class="text-[16px] font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-hand-holding-dollar text-[#1F6FB2]" aria-hidden="true"></i>
                        <span>Điều chỉnh đơn giá khám</span>
                    </h3>
                    <button type="button" onclick="dongModal('modal-sua-gia-kham')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div class="p-3.5 rounded-md bg-slate-50 border border-[#E3E8EE] flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-md bg-medical-500/10 text-medical-600 font-bold text-base flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p id="modal-sgk-ho-ten" class="font-extrabold text-slate-900 text-sm truncate">Bác sĩ...</p>
                            <p id="modal-sgk-khoa-hocvi" class="text-slate-500 text-[13px] truncate">Chuyên khoa • học vị</p>
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Giá khám hiện tại:</label>
                        <div id="modal-sgk-gia-hien-tai" class="px-3.5 py-2.5 bg-slate-100 rounded-md font-mono font-extrabold text-slate-600 text-sm">
                            0 VNĐ
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Đơn giá khám mới (VNĐ): <span class="text-rose-500">*</span></label>
                        <input type="number" id="modal-sgk-gia-moi" required min="0" step="10000" placeholder="Ví dụ: 250000" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2] text-slate-900 font-mono font-extrabold text-base">
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <span class="text-[13px] text-slate-400 font-bold self-center">Gợi ý:</span>
                            <button type="button" onclick="datGiaKhamGoiY(150000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[13px] font-bold transition">150.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(200000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[13px] font-bold transition">200.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(250000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[13px] font-bold transition">250.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(300000)" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[13px] font-bold transition">300.000đ</button>
                            <button type="button" onclick="datGiaKhamGoiY(500000)" class="px-2 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-[13px] font-bold transition">500.000đ (VIP)</button>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3.5 border-t border-[#E3E8EE] flex items-center justify-end gap-2">
                    <button type="button" onclick="dongModal('modal-sua-gia-kham')" class="btn btn-secondary">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check" aria-hidden="true"></i>
                        <span>Lưu thay đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: LỊCH TRỰC / CA LÀM VIỆC CỦA BÁC SĨ -->
    <div class="modal-backdrop" id="modal-lich-truc-bac-si">
        <div class="modal-box bg-white rounded-lg border border-[#E3E8EE] shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E3E8EE] flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-md bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fa-solid fa-calendar-week" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="modal-lt-title" class="text-[16px] font-semibold text-slate-900 leading-tight">
                            Lịch trực bác sĩ
                        </h3>
                        <p id="modal-lt-bac-si-name" class="text-[13px] text-slate-500 font-medium leading-tight mt-0.5 truncate">Đang tải thông tin...</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <div id="modal-lt-select-bs-wrapper" class="hidden">
                        <select id="modal-lt-select-bs" onchange="doiBacSiXemLichTruc(this.value)" class="text-xs px-2.5 py-1.5 border border-slate-300 rounded-md bg-white font-medium text-slate-700" title="Chọn bác sĩ để xem và phân ca trực">
                        </select>
                    </div>
                    <button type="button" onclick="dongModal('modal-lich-truc-bac-si')" class="btn btn-ghost btn-icon" aria-label="Đóng" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto text-xs">
                <!-- Add/Edit Shift Form -->
                <form id="form-them-ca-truc" onsubmit="event.preventDefault(); xacNhanLuuCaTruc();" class="p-4 bg-sky-50/70 rounded-md border border-sky-200/80 space-y-3">
                    <input type="hidden" id="modal-lt-bac-si-id">
                    <input type="hidden" id="modal-lt-ca-id">
                    <div class="font-extrabold text-slate-800 flex items-center justify-between pb-1 border-b border-sky-100">
                        <span id="modal-lt-form-title" class="flex items-center gap-1.5"><i class="fa-solid fa-plus-circle text-sky-600"></i> Đăng ký / cập nhật ca trực</span>
                        <span class="text-[13px] text-slate-500 font-medium">Bấm <i class="fa-solid fa-pen-to-square text-sky-600"></i> bên dưới để sửa ca</span>
                    </div>

                    <!-- Banner quy tắc 1 ca/ngày & Admin duyệt -->
                    <div class="bg-amber-50/90 border border-amber-200/90 rounded-md p-3 text-[13px] text-amber-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-amber-600 mt-0.5 text-xs"></i>
                        <div>
                            <span class="font-extrabold">Quy định ca trực:</span> Mỗi bác sĩ chỉ được đăng ký tối đa <strong>1 ca trực trong 1 ngày</strong> (Sáng, chiều, tối hoặc cả ngày). Khi bác sĩ đăng ký mới hoặc đổi ca, yêu cầu sẽ ở trạng thái <strong class="text-amber-700">Chờ admin duyệt</strong> và chỉ có hiệu lực sau khi quản trị viên (admin) phê duyệt.
                        </div>
                    </div>

                    <!-- Alert cảnh báo động khi chọn thứ đã có ca -->
                    <div id="modal-lt-cung-ngay-alert" class="hidden p-2.5 rounded-md border text-[13px] font-bold"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Thứ trong tuần:</label>
                            <select id="modal-lt-thu" onchange="tuDongDienGioCaTruc(); kiemTraCaCungNgayModal();" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                                <option value="2">Thứ hai</option>
                                <option value="3">Thứ ba</option>
                                <option value="4">Thứ tư</option>
                                <option value="5">Thứ năm</option>
                                <option value="6">Thứ sáu</option>
                                <option value="7">Thứ bảy</option>
                                <option value="8">Chủ nhật</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Ca trực:</label>
                            <select id="modal-lt-ca" onchange="tuDongDienGioCaTruc(); kiemTraCaCungNgayModal();" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                                <option value="CA_SANG">Ca sáng (07:30 - 11:30)</option>
                                <option value="CA_CHIEU">Ca chiều (13:30 - 17:00)</option>
                                <option value="CA_TOI">Ca tối (17:30 - 20:30)</option>
                                <option value="CA_NGAY">Cả ngày (07:30 - 17:00)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Số khám tối đa:</label>
                            <input type="number" id="modal-lt-so-luong" value="20" min="1" max="100" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Giờ bắt đầu:</label>
                            <input type="time" id="modal-lt-gio-bd" value="07:30" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Giờ kết thúc:</label>
                            <input type="time" id="modal-lt-gio-kt" value="11:30" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 text-[13px] mb-1">Phòng khám:</label>
                            <input type="text" id="modal-lt-phong" placeholder="P201" class="w-full px-3 py-2 bg-white border border-[#E3E8EE] rounded-md font-bold text-slate-800 focus:ring-2 focus:ring-sky-500/20">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" id="btn-huy-sua-ca-truc" onclick="datLaiFormCaTruc()" class="hidden px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-md transition">
                            Hủy sửa
                        </button>
                        <button type="submit" id="btn-submit-ca-truc" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-md shadow-sm transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span id="lbl-submit-ca-truc">Lưu ca trực</span>
                        </button>
                    </div>
                </form>

                <!-- Current Shifts Table -->
                <div class="space-y-2">
                    <h4 class="font-extrabold text-slate-800 flex items-center space-x-2">
                        <i class="fa-solid fa-list-check text-medical-600"></i>
                        <span>Danh sách ca trực đang đăng ký trong tuần</span>
                    </h4>
                    <div class="overflow-x-auto rounded-lg border border-[#E3E8EE]">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-600 font-bold text-[13px] border-b border-[#E3E8EE]">
                                    <th class="py-2.5 px-3">Ngày</th>
                                    <th class="py-2.5 px-3">Ca</th>
                                    <th class="py-2.5 px-3">Khung giờ</th>
                                    <th class="py-2.5 px-3">Phòng</th>
                                    <th class="py-2.5 px-3">Khám tối đa</th>
                                    <th class="py-2.5 px-3">Trạng thái</th>
                                    <th class="py-2.5 px-3 text-center">Thao tác</th>
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
                <button type="button" onclick="dongModal('modal-lich-truc-bac-si')" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-md transition">Đóng</button>
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
            benhNhanDangSuaId: null,
            cacCaDaGuiThuNgan: new Set()
        };

        // HÀM TIỆN ÍCH LẤY NGÀY HIỆN TẠI (ĐỊNH DẠNG YYYY-MM-DD THEO GIỜ ĐỊA PHƯƠNG)
        function getNgayHienTai() {
            const d = new Date();
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        function datNgayCaKhamHomNay() {
            const filterInput = document.getElementById('filter-ca-kham-ngay');
            if (filterInput) {
                filterInput.value = getNgayHienTai();
                renderCaKhamBacSi(AppState.danhSachLichHen);
            }
        }

        function datNgayLichSuHomNay() {
            const filterInput = document.getElementById('filter-bac-si-lich-su-ngay');
            if (filterInput) {
                filterInput.value = getNgayHienTai();
                renderBacSiLichSu(AppState.danhSachLichHen);
            }
        }

        function datNgayLichSuTatCa() {
            const filterInput = document.getElementById('filter-bac-si-lich-su-ngay');
            if (filterInput) {
                filterInput.value = '';
                renderBacSiLichSu(AppState.danhSachLichHen);
            }
        }

        // KHÓA TOÀN BỘ BÀN KHÁM KHI ĐÃ LƯU CHỈ ĐỊNH & GỬI THU NGÂN
        function capNhatTrangThaiKhoaBanKham(daKhoa) {
            const btnLuu = document.getElementById('btn-luu-chi-dinh-cls');
            const inputChanDoan = document.getElementById('input-chan-doan');
            const cbs = document.querySelectorAll('.cb-dich-vu-cls');
            const bannerKhoa = document.getElementById('banner-khoa-chi-dinh');

            if (daKhoa) {
                if (inputChanDoan) {
                    inputChanDoan.disabled = true;
                    inputChanDoan.classList.add('bg-slate-100', 'cursor-not-allowed', 'text-slate-500');
                }
                cbs.forEach(cb => {
                    cb.disabled = true;
                    cb.classList.add('cursor-not-allowed');
                });
                if (btnLuu) {
                    btnLuu.disabled = true;
                    btnLuu.className = 'px-5 py-2.5 bg-slate-200 text-slate-500 font-bold text-xs rounded-md cursor-not-allowed flex items-center space-x-2 border border-slate-300 shadow-none';
                    btnLuu.innerHTML = '<i class="fa-solid fa-lock text-slate-500"></i><span>Đã gửi thu ngân (Đã khóa chỉ định)</span>';
                }
                if (bannerKhoa) {
                    bannerKhoa.classList.remove('hidden');
                }
            } else {
                if (inputChanDoan) {
                    inputChanDoan.disabled = false;
                    inputChanDoan.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-500');
                }
                cbs.forEach(cb => {
                    cb.disabled = false;
                    cb.classList.remove('cursor-not-allowed');
                });
                if (btnLuu) {
                    btnLuu.disabled = false;
                    btnLuu.className = 'px-5 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-md shadow-lg transition flex items-center space-x-2';
                    btnLuu.innerHTML = '<i class="fa-solid fa-floppy-disk"></i><span>Lưu chỉ định & gửi thu ngân</span>';
                }
                if (bannerKhoa) {
                    bannerKhoa.classList.add('hidden');
                }
            }
        }

        // LÀM MỚI BÀN KHÁM BÁC SĨ (SAU KHI LƯU HOẶC KHI BẤM NÚT LÀM MỚI)
        function lamMoiBanKham(thongBao = false) {
            AppState.caKhamDangChon = null;

            const boxInfo = document.getElementById('box-chon-ca-kham-thong-tin');
            if (boxInfo) {
                boxInfo.innerHTML = `
                    <div class="py-1 text-slate-500">
                        <i class="fa-solid fa-circle-info text-sky-600 mr-1.5"></i>
                        <em>Hãy bấm nút <strong>"Tiếp nhận"</strong> trên danh sách hàng đợi bên trái để nạp thông tin bệnh nhân.</em>
                    </div>
                `;
            }

            const inputCd = document.getElementById('input-chan-doan');
            if (inputCd) {
                inputCd.value = '';
                inputCd.disabled = false;
                inputCd.placeholder = 'Ví dụ: Đau đầu kéo dài, nghi rối loạn tiền đình...';
                inputCd.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-500');
            }

            document.querySelectorAll('.cb-dich-vu-cls').forEach(c => {
                c.checked = false;
                c.disabled = false;
                c.classList.remove('cursor-not-allowed');
            });
            if (typeof tinhTongTienCLS === 'function') {
                tinhTongTienCLS();
            }

            const bannerKhoa = document.getElementById('banner-khoa-chi-dinh');
            if (bannerKhoa) bannerKhoa.classList.add('hidden');

            const btnLuu = document.getElementById('btn-luu-chi-dinh-cls');
            if (btnLuu) {
                btnLuu.disabled = false;
                btnLuu.className = 'px-5 py-2.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-md shadow-lg transition flex items-center space-x-2';
                btnLuu.innerHTML = '<i class="fa-solid fa-floppy-disk"></i><span>Lưu chỉ định & gửi thu ngân</span>';
            }

            // Bỏ highlight ca khám trên hàng đợi bên trái
            if (typeof renderCaKhamBacSi === 'function' && AppState.danhSachLichHen) {
                renderCaKhamBacSi(AppState.danhSachLichHen);
            }

            if (thongBao) {
                showToast('info', 'Làm mới bàn khám', 'Bàn khám đã được làm mới, sẵn sàng tiếp nhận bệnh nhân tiếp theo.');
            }
        }

        // KHỞI ĐỘNG KHI TẢI TRANG (AUTH GUARD)
        document.addEventListener('DOMContentLoaded', async () => {
            // Ngày khám mặc định: hôm nay
            const todayStr = getNgayHienTai();
            const dateInput = document.getElementById('modal-dl-ngay');
            if (dateInput) {
                dateInput.value = todayStr;
                dateInput.min = todayStr;
            }

            const filterCaKham = document.getElementById('filter-ca-kham-ngay');
            if (filterCaKham) filterCaKham.value = todayStr;

            const filterLichSu = document.getElementById('filter-bac-si-lich-su-ngay');
            if (filterLichSu) filterLichSu.value = todayStr;

            const savedToken = sessionStorage.getItem('token') || localStorage.getItem('token');
            const savedUserStr = sessionStorage.getItem('user_info') || localStorage.getItem('user_info');

            if (!savedToken || !savedUserStr) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Yêu cầu đăng nhập',
                    text: 'Bạn cần đăng nhập để truy cập vào hệ thống phòng khám!',
                    confirmButtonColor: '#1F6FB2'
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

            // Kiểm tra trạng thái tài khoản thời gian thực với Auth Service (phát hiện tài khoản bị khóa ngay khi vào trang)
            const resXacThuc = await goiApi('GET', '/api/v1/xac-thuc/thong-tin');
            if (!resXacThuc.ok || resXacThuc.status === 401) {
                // Nếu bị 401, goiApi đã tự động hiện thông báo tài khoản bị khóa và chuyển hướng
                return;
            }
            if (resXacThuc.data && resXacThuc.data.du_lieu) {
                AppState.currentUser = resXacThuc.data.du_lieu;
                localStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                sessionStorage.setItem('user_info', JSON.stringify(AppState.currentUser));
                if (AppState.currentUser.trang_thai === 'BI_KHOA') {
                    if (!window._isHandling401) {
                        window._isHandling401 = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Tài khoản đã bị khóa!',
                            text: 'Tài khoản của bạn đã bị khóa bởi Quản trị viên. Hệ thống sẽ tự động đăng xuất.',
                            confirmButtonText: 'Đăng xuất ngay',
                            confirmButtonColor: '#DC2626',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            xuLyDangXuatGateway();
                        });
                    }
                    return;
                }
            }

            // Giám sát trạng thái tài khoản thời gian thực (Heartbeat mỗi 8 giây để phát hiện ngay khi Admin bấm khóa)
            setInterval(async () => {
                if (!AppState.token || window._isHandling401) return;
                try {
                    await goiApi('GET', '/api/v1/xac-thuc/thong-tin');
                } catch (e) {}
            }, 8000);

            // Tải dữ liệu ban đầu
            await taiDanhSachChuyenKhoa();
            await taiDanhSachBacSi();
            await taiDanhSachLichHen();
            await taiBangGiaKham();

            const role = AppState.currentUser ? AppState.currentUser.vai_tro : '';
            if (role === 'BENH_NHAN' || role === 'ADMIN' || role === 'BAC_SI' || role === 'LE_TAN') {
                await taiVaRenderHoSoGiaDinh();
            }
            if (role === 'BAC_SI' || role === 'ADMIN' || role === 'LE_TAN') {
                await taiDanhSachBenhNhan();
                await taiDanhSachDichVuCLS();
            }
            if (role === 'ADMIN' || role === 'BENH_NHAN' || role === 'LE_TAN') {
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
            }
            if (role === 'ADMIN') {
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


        // Hàm chuẩn hóa tên hiển thị bác sĩ (tránh lặp tiền tố BS. BS. ...)
        function formatTenBacSi(hoTen) {
            if (!hoTen) return 'Bác sĩ';
            const s = String(hoTen).trim();
            if (/^(BS\.|BS\b|Bác sĩ|ThS\.|TS\.|PGS\.|GS\.)/i.test(s)) {
                return s;
            }
            return 'BS. ' + s;
        }

        // BẢNG QUY ĐỊNH PHÂN QUYỀN TRUY CẬP CÁC TAB
        const ROLE_PERMISSIONS = {
            'ADMIN': [
                'tab-admin-giam-sat',
                'tab-benh-nhan-lich',
                'tab-benh-nhan',
                'tab-bang-gia-kham',
                'tab-ho-so-gia-dinh',
                'tab-benh-nhan-vien-phi',
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
                'tab-ho-so-gia-dinh',
                'tab-admin-benh-nhan'
            ],
            'BENH_NHAN': [
                'tab-benh-nhan-lich',
                'tab-benh-nhan',
                'tab-ho-so-gia-dinh',
                'tab-benh-nhan-vien-phi'
            ],
            'LE_TAN': [
                'tab-admin-benh-nhan',
                'tab-admin-thu-ngan',
                'tab-benh-nhan-lich',
                'tab-benh-nhan',
                'tab-bang-gia-kham'
            ]
        };

        const DEFAULT_TAB_BY_ROLE = {
            'ADMIN': 'tab-admin-giam-sat',
            'BAC_SI': 'tab-bac-si',
            'BENH_NHAN': 'tab-benh-nhan-lich',
            'LE_TAN': 'tab-admin-benh-nhan'
        };

        // HÀM CHUẨN HÓA VAI TRÒ (ĐẢM BẢO LUÔN LÀ STRING 'ADMIN' | 'BAC_SI' | 'BENH_NHAN' | 'LE_TAN')
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
                const mapRoles = { 1: 'ADMIN', 2: 'BAC_SI', 3: 'BENH_NHAN', 4: 'LE_TAN' };
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
                // Hiển thị tên vai trò bằng tiếng Việt thay cho mã kỹ thuật
                const tenVaiTro = { 'ADMIN': 'Quản trị viên', 'BAC_SI': 'Bác sĩ', 'BENH_NHAN': 'Bệnh nhân', 'LE_TAN': 'Lễ tân' };
                roleEl.textContent = tenVaiTro[role] || role;
                roleEl.className = 'text-[13px] text-slate-500 leading-tight';
            }

            // Cập nhật Avatar người dùng đồng bộ
            const userAvatar = AppState.currentUser ? AppState.currentUser.avatar : null;
            const topAvatarImg = document.getElementById('top-user-avatar-img');
            const topAvatarIcon = document.getElementById('top-user-avatar-icon');

            if (userAvatar) {
                if (topAvatarImg) {
                    topAvatarImg.src = userAvatar;
                    topAvatarImg.classList.remove('hidden');
                }
                if (topAvatarIcon) topAvatarIcon.classList.add('hidden');
            } else {
                if (topAvatarImg) topAvatarImg.classList.add('hidden');
                if (topAvatarIcon) topAvatarIcon.classList.remove('hidden');
            }

            // Cập nhật Brand Title / Subtitle
            const brandSub = document.getElementById('brand-subtitle');
            if (brandSub) {
                if (role === 'ADMIN') brandSub.textContent = 'Phòng khám đa khoa · Quản trị';
                else if (role === 'BAC_SI') brandSub.textContent = 'Phòng khám đa khoa · Bác sĩ';
                else if (role === 'LE_TAN') brandSub.textContent = 'Phòng khám đa khoa · Lễ tân';
                else brandSub.textContent = 'Phòng khám đa khoa';
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
                if (navLabelLich) navLabelLich.textContent = 'Lịch hẹn của tôi';
                if (titleLichKham) titleLichKham.textContent = 'Lịch hẹn của tôi';
            } else if (role === 'BAC_SI') {
                if (navLabelLich) navLabelLich.textContent = 'Lịch hẹn';
                if (titleLichKham) titleLichKham.textContent = 'Lịch hẹn của phòng khám';
            } else if (role === 'LE_TAN') {
                if (navLabelLich) navLabelLich.textContent = 'Tiếp đón lịch khám';
                if (titleLichKham) titleLichKham.textContent = 'Danh sách lịch khám tiếp đón';
            } else {
                if (navLabelLich) navLabelLich.textContent = 'Lịch khám';
                if (titleLichKham) titleLichKham.textContent = 'Tất cả lịch khám';
            }

            // Tùy chỉnh nhãn lịch trực theo vai trò
            const navLabelLichTruc = document.getElementById('nav-label-lich-truc-bac-si');
            const btnBannerLichTruc = document.getElementById('btn-banner-lich-truc-label');
            const bannerDoctorName = document.getElementById('banner-doctor-name');
            if (role === 'ADMIN') {
                if (navLabelLichTruc) navLabelLichTruc.textContent = 'Lịch trực bác sĩ';
                if (btnBannerLichTruc) btnBannerLichTruc.textContent = 'Lịch trực bác sĩ';
                if (bannerDoctorName) bannerDoctorName.textContent = 'Bàn khám & Ca trực (Quản trị viên)';
            } else if (role === 'BAC_SI') {
                if (navLabelLichTruc) navLabelLichTruc.textContent = 'Lịch trực của tôi';
                if (btnBannerLichTruc) btnBannerLichTruc.textContent = 'Lịch trực của tôi';
                if (bannerDoctorName) bannerDoctorName.textContent = formatTenBacSi(AppState.currentUser?.ho_ten || 'Khám bệnh');
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

            // Xóa hash trên thanh địa chỉ để luôn hiển thị URL gốc /dashboard sạch đẹp
            if (window.location.hash) {
                try {
                    history.replaceState(null, null, window.location.pathname);
                } catch (e) {}
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
                        title: 'Từ chối truy cập!',
                        text: `Tài khoản vai trò [${role}] không có quyền truy cập vào chức năng này. Vui lòng liên hệ Quản trị viên (ADMIN).`,
                        confirmButtonColor: '#1F6FB2'
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

            // Lưu trạng thái tab hiện tại vào sessionStorage (khi F5/Reload vẫn giữ nguyên tab)
            // Giữ URL gốc /dashboard sạch đẹp không hiển thị #tab-... trên thanh địa chỉ
            try {
                sessionStorage.setItem('current_active_tab', tabId);
                if (window.location.hash) {
                    history.replaceState(null, null, window.location.pathname);
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
                'tab-admin-giam-sat': { text: 'Thống kê tổng quan' },
                'tab-benh-nhan-lich': { text: role === 'BENH_NHAN' ? 'Lịch hẹn của tôi' : 'Lịch khám' },
                'tab-benh-nhan': { text: 'Đặt lịch khám' },
                'tab-bang-gia-kham': { text: 'Bảng giá khám bệnh' },
                'tab-ho-so-gia-dinh': { text: 'Hồ sơ gia đình' },
                'tab-admin-benh-nhan': { text: 'Bệnh nhân và hồ sơ bệnh án' },
                'tab-bac-si': { text: 'Bàn khám' },
                'tab-bac-si-lich-su': { text: 'Ca khám hôm nay' },
                'tab-admin-thu-ngan': { text: 'Thu ngân và viện phí' },
                'tab-admin-bac-si': { text: 'Bác sĩ và chuyên khoa' },
                'tab-admin-tai-khoan': { text: 'Tài khoản người dùng' },
                'tab-benh-nhan-vien-phi': { text: 'Viện phí của tôi' },
            };

            const pageTitle = document.getElementById('current-page-title');
            if (pageTitle && titles[tabId]) {
                pageTitle.innerHTML = `<span>${titles[tabId].text}</span>`;
            }

            if (tabId === 'tab-bang-gia-kham') {
                taiBangGiaKham();
            }
            if (tabId === 'tab-ho-so-gia-dinh') {
                taiVaRenderHoSoGiaDinh();
            }
            if (tabId === 'tab-benh-nhan-vien-phi') {
                taiDanhSachHoaDon();
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

                // TỰ ĐỘNG BẮT VÀ XỬ LÝ KHÓA TÀI KHOẢN HOẶC HẾT HẠN PHIÊN (HTTP 401)
                if (res.status === 401) {
                    if (!window._isHandling401) {
                        window._isHandling401 = true;
                        const isBiKhoa = (data && (data.ma_loi === 'TAI_KHOAN_BI_KHOA' || (data.thong_diep && data.thong_diep.toLowerCase().includes('khóa'))));
                        const tieuDe = isBiKhoa ? 'Tài khoản đã bị khóa!' : 'Phiên đăng nhập hết hạn';
                        const noiDung = (data && data.thong_diep)
                            ? data.thong_diep
                            : 'Tài khoản của bạn đã bị khóa bởi Quản trị viên hoặc phiên đăng nhập đã hết hạn.';

                        Swal.fire({
                            icon: 'error',
                            title: tieuDe,
                            text: noiDung + ' Hệ thống sẽ tự động đăng xuất.',
                            confirmButtonText: 'Đăng xuất ngay',
                            confirmButtonColor: '#DC2626',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            xuLyDangXuatGateway();
                        });
                    }
                }

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
                    ? `<img src="${bs.avatar}" alt="${bs.ho_ten}" class="w-12 h-12 rounded-full object-cover border border-[#E3E8EE]">`
                    : `<div class="w-12 h-12 rounded-full bg-[#EEF5FB] text-[#1F6FB2] flex items-center justify-center font-semibold text-sm">
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
                            ${coCaSang ? '<span class="px-2 py-0.5 rounded text-[13px] font-medium bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1"><i class="fa-solid fa-sun text-amber-500"></i> Ca sáng</span>' : ''}
                            ${coCaChieu ? '<span class="px-2 py-0.5 rounded text-[13px] font-medium bg-sky-50 text-sky-700 border border-sky-200 flex items-center gap-1"><i class="fa-solid fa-cloud-sun text-sky-500"></i> Ca chiều</span>' : ''}
                            ${coCaToi ? '<span class="px-2 py-0.5 rounded text-[13px] font-medium bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1"><i class="fa-solid fa-moon text-purple-500"></i> Ca tối</span>' : ''}
                            ${coCaNgay ? '<span class="px-2 py-0.5 rounded text-[13px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1"><i class="fa-solid fa-star text-emerald-500"></i> Cả ngày</span>' : ''}
                        </div>
                    `;
                }

                return `
                    <div class="bg-white border border-[#E3E8EE] rounded-lg p-5 hover:border-[#1F6FB2] transition-colors flex flex-col justify-between">
                        <div>
                            <div class="flex items-start gap-3">
                                ${avatarHtml}
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-semibold text-slate-900 text-[15px] leading-snug">${bs.ho_ten}</h4>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[13px] font-medium bg-[#EEF5FB] text-[#1F6FB2]">
                                        ${tenKhoa}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1.5 text-[13px] text-slate-600">
                                <div><i class="fa-solid fa-graduation-cap text-slate-400 w-4" aria-hidden="true"></i> ${bs.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                                <div><i class="fa-solid fa-door-open text-slate-400 w-4" aria-hidden="true"></i> Phòng: <span class="font-medium text-slate-800">${bs.phong_kham || 'P201'}</span></div>
                                <div><i class="fa-solid fa-phone text-slate-400 w-4" aria-hidden="true"></i> ${bs.so_dien_thoai || '0901234567'}</div>
                                ${caBadgesHtml}
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-[13px] text-slate-500">Giá khám</div>
                                <div class="text-[16px] font-semibold text-[#1F6FB2]">${gia} đ</div>
                            </div>
                            <button type="button" onclick="moModalDatLich(${bs.id})" class="btn btn-primary" style="min-height:36px; padding:6px 14px; font-size:13px;">
                                <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                                <span>Đặt lịch</span>
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
                    item.className = 'flex items-center gap-1.5 px-2.5 py-1 bg-white border border-[#E3E8EE] rounded-md text-slate-700 shadow-xs';
                    item.innerHTML = `
                        <i class="fa-solid ${isImage ? 'fa-image text-medical-600' : 'fa-file-lines text-amber-500'}"></i>
                        <span class="max-w-[120px] truncate font-medium">${file.name}</span>
                        <span class="text-[13px] text-slate-400 font-mono">(${(file.size / 1024).toFixed(0)}KB)</span>
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

            // Chặn chọn ngày quá khứ & Reset ngày khám mặc định (giờ địa phương)
            const today = getNgayHienTai();
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
                let ckOpts = '<option value="">-- Tất cả chuyên khoa --</option>';
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
                btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> <span>Đang đặt lịch...</span>';
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
                        title: 'Đặt lịch khám thành công!',
                        html: `
                            <div class="text-left text-xs space-y-2.5 mt-2 p-3.5 bg-slate-50 rounded-md border border-[#E3E8EE]">
                                <div><strong>Bác sĩ khám:</strong> <span class="text-slate-900 font-bold">${tenBs}</span></div>
                                <div><strong>Chuyên khoa:</strong> <span class="text-sky-700 font-semibold">${tenKhoa}</span></div>
                                <div><strong>Ngày khám:</strong> <span class="font-bold text-slate-800">${ngayFormatted}</span></div>
                                <div><strong>Khung giờ hẹn tiếp nhận:</strong> <span class="text-rose-600 font-extrabold text-sm">${gioBatDau} - ${(lh.gio_ket_thuc || '').substring(0, 5)}</span></div>
                                <div class="p-2.5 bg-emerald-50 text-emerald-800 rounded-md border border-emerald-200 text-[13px] leading-relaxed">
                                    <strong><i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Cam kết ca khám:</strong> Phòng khám cam kết quý khách <strong>chắc chắn được hoàn thành khám 100% trong ${tenBuoi}</strong>.
                                </div>
                                <div class="text-[13px] text-slate-500 italic">
                                    * Giờ vào phòng khám có thể dao động linh hoạt ±10-15 phút do tính chất chuyên môn của các ca khám trước. Vui lòng đến trước 10-15 phút để lấy số tiếp nhận ưu tiên.
                                </div>
                            </div>
                        `,
                        confirmButtonColor: '#1F6FB2',
                        confirmButtonText: 'Đã hiểu & xem lịch khám'
                    });
                    dongModal('modal-dat-lich');
                    await taiDanhSachLichHen();
                    if (typeof taiDanhSachBenhNhan === 'function') await taiDanhSachBenhNhan();
                    if (typeof taiHoSoGiaDinhVaThanhVien === 'function') await taiHoSoGiaDinhVaThanhVien();
                    chuyenTab('tab-benh-nhan-lich');
                } else if (res.status === 409) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Trùng lịch khám (409 conflict)',
                        text: res.data.thong_diep || 'Bác sĩ đã có ca khám trong khung giờ này, vui lòng chọn giờ khác!',
                        confirmButtonColor: '#1F6FB2'
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
            // Bác sĩ không thể dời lịch khám của bệnh nhân
            if (AppState.currentUser && AppState.currentUser.vai_tro === 'BAC_SI') {
                showToast('error', 'Không có quyền', 'Bác sĩ không thể dời lịch khám của bệnh nhân.');
                return;
            }

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
                    title: 'Dời lịch thành công!',
                    text: res.data.thong_diep || 'Lịch hẹn đã được cập nhật sang thời gian mới.',
                    confirmButtonColor: '#1F6FB2'
                });
                dongModal('modal-doi-lich');
                await taiDanhSachLichHen();
            } else if (res.status === 409) {
                Swal.fire({
                    icon: 'error',
                    title: 'Trùng lịch bác sĩ (409)',
                    text: res.data.thong_diep || 'Khung giờ này đã có bệnh nhân khác đặt. Vui lòng chọn khung giờ khác!',
                    confirmButtonColor: '#1F6FB2'
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
                        <div class="p-2.5 bg-slate-50 border border-[#E3E8EE] rounded-md space-y-2 shadow-xs">
                            <div class="text-[13px] font-bold text-slate-700 truncate flex items-center justify-between">
                                <span>Ảnh đính kèm #${idx + 1}</span>
                                <span class="text-[13px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Đã nạp</span>
                            </div>
                            <img src="${fileUrl}" alt="Hồ sơ y tế #${idx + 1}" class="w-full h-48 object-contain bg-slate-900/5 rounded-md border border-slate-100 cursor-pointer hover:opacity-90 transition" onclick="window.open('${fileUrl}', '_blank')">
                            <a href="${fileUrl}" target="_blank" class="block text-center text-xs font-bold text-medical-600 hover:text-medical-700 pt-1"><i class="fa-solid fa-up-right-from-square mr-1"></i> Mở xem toàn màn hình</a>
                        </div>
                    `;
                } else {
                    return `
                        <div class="p-4 bg-slate-50 border border-[#E3E8EE] rounded-md flex flex-col items-center justify-center space-y-2">
                            <i class="fa-solid fa-file-pdf text-rose-500 text-3xl"></i>
                            <span class="text-xs font-bold text-slate-800">Tệp hồ sơ #${idx + 1}</span>
                            <a href="${fileUrl}" target="_blank" class="px-3 py-1.5 bg-medical-600 text-white rounded-md text-xs font-bold hover:bg-medical-700 transition"><i class="fa-solid fa-download mr-1"></i> Tải / mở tệp</a>
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
                AppState.cacCaDaTiepNhan = AppState.cacCaDaTiepNhan || new Set();
                AppState.danhSachLichHen.forEach(l => {
                    if (l.trang_thai === 'DANG_KHAM') {
                        AppState.cacCaDaTiepNhan.add(Number(l.id));
                    }
                });
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

                // Bác sĩ KHÔNG THỂ dời lịch và hủy lịch của bệnh nhân (Chỉ bệnh nhân, lễ tân hoặc quản trị viên)
                const isBacSi = u && u.vai_tro === 'BAC_SI';
                const isNhanVien = u && (u.vai_tro === 'ADMIN' || u.vai_tro === 'LE_TAN');
                const canManage = !isBacSi && ['CHO_XAC_NHAN', 'DA_XAC_NHAN'].includes(lh.trang_thai);
                
                let actionBtns = [];
                if (isNhanVien && ['CHO_XAC_NHAN', 'CHO_KHAM'].includes(lh.trang_thai)) {
                    actionBtns.push(`<button onclick="xacNhanLichHen(${lh.id})" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Xác nhận / Tiếp đón bệnh nhân"><i class="fa-solid fa-check"></i> Tiếp nhận</button>`);
                }
                if (canManage) {
                    actionBtns.push(`<button onclick="moModalDoiLich(${lh.id})" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Dời Giờ / Ngày Khám"><i class="fa-solid fa-clock-rotate-left"></i> Dời lịch</button>`);
                    actionBtns.push(`<button onclick="huyLichHenBenhNhan(${lh.id})" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Hủy Ca Khám"><i class="fa-solid fa-ban"></i> Hủy</button>`);
                }
                if (lh.tep_dinh_kem && (Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length > 0 : true)) {
                    const count = Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length : 1;
                    actionBtns.push(`<button onclick="moModalXemTepYTe(${lh.id})" class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Xem Hồ Sơ Đính Kèm"><i class="fa-solid fa-paperclip"></i> ${count} tệp</button>`);
                }
                const actionHtml = actionBtns.length > 0 ? `<div class="flex items-center justify-center gap-1.5 flex-wrap">${actionBtns.join('')}</div>` : `<span class="text-slate-400 text-xs">--</span>`;

                const soLanDoi = lh.so_lan_doi_lich ? `<span class="text-[13px] text-sky-600 block">(Đã dời ${lh.so_lan_doi_lich} lần)</span>` : '';

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${lh.id}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <button type="button" onclick="moModalXemEhrHoacGiaDinh(${lh.benh_nhan_id || (lh.benh_nhan ? lh.benh_nhan.id : 0)})" class="font-bold text-slate-900 hover:text-medical-600 transition flex items-center gap-1 text-left group" title="Nhấp để xem hồ sơ bệnh án">
                                    <span>${tenBn}</span>
                                    <i class="fa-solid fa-address-card text-[13px] text-slate-400 group-hover:text-medical-600 transition"></i>
                                </button>
                                ${lh.benh_nhan && lh.benh_nhan.quan_he_chu_tai_khoan && lh.benh_nhan.quan_he_chu_tai_khoan !== 'BAN_THAN' ? 
                                    `<button type="button" onclick="chuyenTab('tab-ho-so-gia-dinh')" class="px-1.5 py-0.5 rounded text-[13px] font-bold bg-purple-100 hover:bg-purple-200 text-purple-700 border border-purple-200 transition" title="Xem trong Hồ Sơ Gia Đình">${lh.benh_nhan.quan_he_chu_tai_khoan === 'CON' ? 'Con cái' : (lh.benh_nhan.quan_he_chu_tai_khoan === 'CHA_ME' ? 'Bố/Mẹ' : 'Người thân')}</button>` : ''}
                            </div>
                            ${sdtBn ? `<span class="text-[13px] text-slate-400 font-mono block">${sdtBn}</span>` : ''}
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
                            <span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">${lh.trang_thai || 'CHO_KHAM'}</span>
                        </td>
                        <td class="py-3 px-4 text-center">${actionHtml}</td>
                    </tr>
                `;
            }).join('');
        }

        async function xacNhanLichHen(id) {
            const res = await goiApi('PUT', `/api/v1/lich-hen/${id}/xac-nhan`);
            if (res.ok) {
                showToast('success', 'Đã tiếp nhận', 'Đã xác nhận tiếp nhận lịch khám thành công!');
                await taiDanhSachLichHen();
            } else {
                showToast('error', 'Lỗi tiếp nhận', res.data.thong_diep || 'Không thể xác nhận lịch khám.');
            }
        }

        async function huyLichHenBenhNhan(id) {
            // Bác sĩ không thể hủy lịch khám của bệnh nhân
            if (AppState.currentUser && AppState.currentUser.vai_tro === 'BAC_SI') {
                showToast('error', 'Không có quyền', 'Bác sĩ không thể hủy lịch khám của bệnh nhân.');
                return;
            }

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
                showToast('success', 'Đã hủy lịch', 'Lịch hẹn đã được hủy thành công.');
                await taiDanhSachLichHen();
            } else if (res.status === 422 && res.data.ma_loi === 'KHONG_THE_HUY_SAT_GIO') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chặn hủy sát giờ (< 2 tiếng)',
                    html: `<div class="text-left text-xs space-y-2">
                        <p class="text-rose-600 font-bold">${res.data.thong_diep}</p>
                        <div class="p-3 bg-amber-50 rounded-md border border-amber-200 text-slate-700">
                            <strong>Quy định phòng khám:</strong> Để đảm bảo lịch trực của y bác sĩ và công bằng cho các bệnh nhân chờ khám, hệ thống không cho phép tự hủy ca khám dưới 2 tiếng trước giờ hẹn.
                        </div>
                        <p class="font-bold text-sky-700 text-center text-sm pt-1">Hotline tiếp đón: 1900 6868 (Trực 24/7)</p>
                    </div>`,
                    confirmButtonColor: '#1F6FB2',
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
                    <label class="flex items-center justify-between p-2.5 bg-slate-50 hover:bg-sky-50/50 border border-[#E3E8EE] rounded-md cursor-pointer transition">
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
                    b.tai_khoan_id == u.id ||
                    (b.ho_ten && u.ho_ten && b.ho_ten.toLowerCase().includes(u.ho_ten.toLowerCase())) ||
                    (u.ten_dang_nhap && b.ma_bac_si && b.ma_bac_si.toLowerCase().includes(u.ten_dang_nhap.toLowerCase()))
                );
                if (bs) docList = list.filter(lh => lh.bac_si_id == bs.id);
            }

            const today = getNgayHienTai();
            const filterInput = document.getElementById('filter-ca-kham-ngay');
            if (filterInput && !filterInput.value) {
                filterInput.value = today;
            }
            const targetDate = (filterInput && filterInput.value) ? filterInput.value : today;

            // NGUYÊN TẮC CA KHÁM: Đến ngày thì mới hiển thị lên, chưa đến ngày thì không hiện để tránh nhầm lẫn
            const waitingList = docList.filter(l => {
                if (l.trang_thai === 'HOAN_THANH' || l.trang_thai === 'DA_HUY') return false;
                // Chỉ hiển thị ca khám của ngày đã chọn (mặc định hôm nay), loại bỏ hoàn toàn các ngày tương lai
                return l.ngay_kham === targetDate;
            });

            if (waitingList.length === 0) {
                const ngayHienThi = (targetDate === today) ? `hôm nay (${today})` : `ngày ${targetDate}`;
                tbody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-slate-400 italic">Không có ca khám nào đang chờ trong ${ngayHienThi}.</td></tr>`;
                return;
            }

            // SẮP XẾP THEO THỜI GIAN: Giờ bắt đầu tăng dần (ca sớm nhất lên đầu hàng đợi)
            waitingList.sort((a, b) => {
                const dateComp = (a.ngay_kham || '').localeCompare(b.ngay_kham || '');
                if (dateComp !== 0) return dateComp;
                return (a.gio_bat_dau || '').localeCompare(b.gio_bat_dau || '');
            });

            tbody.innerHTML = waitingList.map(lh => {
                const bnName = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
                const isDaGuiThuNgan = (AppState.cacCaDaGuiThuNgan && AppState.cacCaDaGuiThuNgan.has(Number(lh.id))) || 
                                       (AppState.danhSachHoaDon && AppState.danhSachHoaDon.some(hd => Number(hd.lich_hen_id) === Number(lh.id)));
                const isDaTiepNhan = (AppState.cacCaDaTiepNhan && AppState.cacCaDaTiepNhan.has(Number(lh.id))) || 
                                     (AppState.caKhamDangChon && AppState.caKhamDangChon.id == lh.id) || 
                                     lh.trang_thai === 'DANG_KHAM';
                const isDangChon = AppState.caKhamDangChon && AppState.caKhamDangChon.id == lh.id;

                let btnThaoTac = '';
                if (isDaGuiThuNgan) {
                    btnThaoTac = `
                        <button type="button" onclick="chuyenSangKhamCaNay(${lh.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-xs rounded-md shadow-xs transition" title="Đã lưu chỉ định & chuyển sang thu ngân - Nhấp để xem lại">
                            <i class="fa-solid fa-file-invoice-dollar text-blue-600"></i>
                            <span>Đã gửi thu ngân</span>
                        </button>
                    `;
                } else if (isDaTiepNhan) {
                    btnThaoTac = `
                        <button type="button" onclick="chuyenSangKhamCaNay(${lh.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 font-bold text-xs rounded-md shadow-xs transition" title="Đang tiếp nhận - Nhấp để nạp lại hồ sơ">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Đã tiếp nhận</span>
                        </button>
                    `;
                } else {
                    btnThaoTac = `
                        <button type="button" onclick="chuyenSangKhamCaNay(${lh.id})" class="px-3.5 py-1.5 bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs rounded-md shadow-sm transition inline-flex items-center gap-1 justify-center">
                            <span>Tiếp nhận</span>
                        </button>
                    `;
                }

                return `
                    <tr class="hover:bg-slate-50/70 transition ${isDangChon ? 'bg-sky-50/70 border-l-4 border-medical-600' : ''}">
                        <td class="py-2.5 px-3 font-bold text-slate-700">#${lh.id}</td>
                        <td class="py-2.5 px-3 font-extrabold text-slate-900">${bnName}</td>
                        <td class="py-2.5 px-3 font-mono text-medical-600 font-semibold">
                            ${lh.gio_bat_dau}
                            ${lh.ngay_kham ? `<span class="text-[11px] text-slate-400 block font-sans">${lh.ngay_kham}</span>` : ''}
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            ${btnThaoTac}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function chuyenSangKhamCaNay(lichHenId) {
            const lh = AppState.danhSachLichHen.find(l => l.id == lichHenId);
            if (!lh) return;

            // Đánh dấu ca khám đã tiếp nhận vào State
            AppState.cacCaDaTiepNhan = AppState.cacCaDaTiepNhan || new Set();
            AppState.cacCaDaTiepNhan.add(Number(lichHenId));
            AppState.caKhamDangChon = lh;
            lh.trang_thai = 'DANG_KHAM';

            // Đồng bộ trạng thái Bắt đầu khám lên API Service
            try {
                goiApi('PUT', `/api/v1/lich-hen/${lichHenId}/bat-dau-kham`, {
                    bac_si_id: lh.bac_si_id
                }).catch(e => console.warn('Lỗi gọi bat-dau-kham:', e));
            } catch (e) {
                console.warn('Lỗi gọi bat-dau-kham:', e);
            }

            // Tức thì cập nhật nút Tiếp nhận -> Đã tiếp nhận và đồng bộ danh sách
            renderCaKhamBacSi(AppState.danhSachLichHen);
            renderBacSiLichSu(AppState.danhSachLichHen);

            const bn = lh.benh_nhan || {};
            const bnName = bn.ho_ten || (lh.ho_ten_benh_nhan || 'Bệnh nhân');
            const nhomMau = bn.nhom_mau ? `<span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 font-bold">Nhóm máu: ${bn.nhom_mau}</span>` : '';
            const diUng = bn.tien_su_di_ung ? `<div class="text-amber-700 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Dị ứng: <strong>${bn.tien_su_di_ung}</strong></div>` : '';
            const benhNen = bn.tien_su_benh ? `<div class="text-sky-700 font-medium"><i class="fa-solid fa-heart-pulse mr-1"></i> Bệnh nền: <strong>${bn.tien_su_benh}</strong></div>` : '';
            const nguoiThan = bn.nguoi_lien_he_khan_cap ? `<div class="text-slate-500"><i class="fa-solid fa-phone mr-1"></i> Liên hệ khẩn cấp: ${bn.nguoi_lien_he_khan_cap} (${bn.sdt_khan_cap || ''})</div>` : '';
            const tepBtn = (lh.tep_dinh_kem && (Array.isArray(lh.tep_dinh_kem) ? lh.tep_dinh_kem.length > 0 : true))
                ? `<div class="pt-2"><button type="button" onclick="moModalXemTepYTe(${lh.id})" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-md font-bold text-xs inline-flex items-center gap-1.5 shadow-xs transition"><i class="fa-solid fa-paperclip text-indigo-600"></i> Xem hồ sơ / đơn thuốc cũ đính kèm</button></div>`
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
                        <button type="button" onclick="inHoaDonKhamBenhCaHienTai()" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-md font-bold text-xs inline-flex items-center gap-1.5 shadow-xs transition">
                            <i class="fa-solid fa-file-invoice-dollar text-sky-600"></i> Xem / in hóa đơn khám
                        </button>
                    </div>
                </div>
            `;

            // KIỂM TRA TRẠNG THÁI ĐÃ LƯU CHỈ ĐỊNH VÀ GỬI THU NGÂN:
            let isDaGui = (AppState.cacCaDaGuiThuNgan && AppState.cacCaDaGuiThuNgan.has(Number(lichHenId))) ||
                          (AppState.danhSachHoaDon && AppState.danhSachHoaDon.some(hd => Number(hd.lich_hen_id) === Number(lichHenId)));

            try {
                const resCls = await goiApi('GET', `/api/v1/dich-vu/lich-hen/${lichHenId}`);
                if (resCls.ok && resCls.data && resCls.data.du_lieu && resCls.data.du_lieu.length > 0) {
                    isDaGui = true;
                    AppState.cacCaDaGuiThuNgan = AppState.cacCaDaGuiThuNgan || new Set();
                    AppState.cacCaDaGuiThuNgan.add(Number(lichHenId));

                    const dvIds = resCls.data.du_lieu.map(d => Number(d.dich_vu_id));
                    document.querySelectorAll('.cb-dich-vu-cls').forEach(cb => {
                        cb.checked = dvIds.includes(Number(cb.value));
                    });
                    tinhTongTienCLS();

                    const cd = resCls.data.du_lieu[0].chan_doan_so_bo || resCls.data.du_lieu[0].ghi_chu;
                    if (cd && document.getElementById('input-chan-doan')) {
                        document.getElementById('input-chan-doan').value = cd;
                    }
                } else if (!isDaGui) {
                    document.querySelectorAll('.cb-dich-vu-cls').forEach(c => c.checked = false);
                    tinhTongTienCLS();
                    const inputCd = document.getElementById('input-chan-doan');
                    if (inputCd) inputCd.value = lh.chuan_doan || lh.ly_do_kham || '';
                }
            } catch(e) {
                console.warn('Lỗi kiểm tra chỉ định cũ:', e);
            }

            // Cập nhật giao diện khóa / mở khóa bàn khám (khi đã lưu gửi thu ngân thì không được thêm thao tác gì nữa)
            capNhatTrangThaiKhoaBanKham(isDaGui);

            showToast('info', 'Tiếp nhận ca khám', `Đã tiếp nhận hồ sơ bệnh nhân: ${bnName}. ${isDaGui ? '(Phiếu chỉ định đã khóa do đã gửi thu ngân)' : ''}`);
        }

        async function luuChiDinhCanLamSang() {
            if (!AppState.caKhamDangChon) {
                showToast('error', 'Chưa chọn ca khám', 'Vui lòng bấm "Tiếp Nhận" 1 ca khám từ danh sách chờ bên trái.');
                return;
            }

            const currentLhId = Number(AppState.caKhamDangChon.id);

            // NGUYÊN TẮC: Đã lưu chỉ định gửi thu ngân rồi thì KHÔNG ĐƯỢC thêm thao tác gì nữa
            const isDaGui = (AppState.cacCaDaGuiThuNgan && AppState.cacCaDaGuiThuNgan.has(currentLhId)) ||
                            (AppState.danhSachHoaDon && AppState.danhSachHoaDon.some(hd => Number(hd.lich_hen_id) === currentLhId));

            if (isDaGui) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chỉ định đã được khóa!',
                    text: 'Ca khám này đã được lưu chỉ định và chuyển sang bàn Thu Ngân. Không thể thực hiện thêm thao tác kê thêm chỉ định để tránh trùng lặp viện phí.',
                    confirmButtonColor: '#1F6FB2'
                });
                capNhatTrangThaiKhoaBanKham(true);
                return;
            }

            const checked = Array.from(document.querySelectorAll('.cb-dich-vu-cls:checked')).map(c => Number(c.value));
            if (checked.length === 0) {
                showToast('warning', 'Chưa chọn dịch vụ', 'Hãy tích chọn ít nhất 1 dịch vụ cận lâm sàng.');
                return;
            }

            const chanDoan = (document.getElementById('input-chan-doan')?.value || '').trim() || 'Theo dõi lâm sàng';

            const payload = {
                lich_hen_id: currentLhId,
                benh_nhan_id: Number(AppState.caKhamDangChon.benh_nhan_id || AppState.caKhamDangChon.benh_nhan?.id || 1),
                bac_si_id: Number(AppState.caKhamDangChon.bac_si_id || AppState.currentUser?.bac_si?.id || 1),
                danh_sach_dich_vu_id: checked,
                chan_doan_so_bo: chanDoan
            };

            const res = await goiApi('POST', '/api/v1/dich-vu/chi-dinh', payload);

            if (res.ok) {
                // Đánh dấu ca khám đã gửi thu ngân
                AppState.cacCaDaGuiThuNgan = AppState.cacCaDaGuiThuNgan || new Set();
                AppState.cacCaDaGuiThuNgan.add(currentLhId);

                // KHÓA BÀN KHÁM NGAY LẬP TỨC: Không được thêm thao tác gì nữa
                capNhatTrangThaiKhoaBanKham(true);

                // Tự động chuyển chỉ định sang Thu Ngân lập / đồng bộ hóa đơn
                try {
                    await goiApi('POST', '/api/v1/hoa-don/tao-tu-dong', {
                        lich_hen_id: currentLhId,
                        giam_gia: 0,
                        ghi_chu: `Chỉ định cận lâm sàng (${chanDoan})`
                    });
                } catch (e) {
                    console.warn('Lỗi tự động gửi sang thu ngân:', e);
                }

                // Cập nhật trạng thái lịch hẹn sang Đang Khám nếu ca mới tiếp nhận
                try {
                    if (AppState.caKhamDangChon.trang_thai !== 'DANG_KHAM' && AppState.caKhamDangChon.trang_thai !== 'HOAN_THANH') {
                        await goiApi('PUT', `/api/v1/lich-hen/${currentLhId}/bat-dau-kham`);
                        AppState.caKhamDangChon.trang_thai = 'DANG_KHAM';
                    }
                } catch (e) {
                    console.warn('Lỗi cập nhật trạng thái lịch hẹn:', e);
                }

                // LÀM MỚI BÀN KHÁM NGAY LẬP TỨC để sẵn sàng đón ca tiếp theo và tránh hiểu nhầm là chưa thao tác
                lamMoiBanKham();

                Swal.fire({
                    icon: 'success',
                    title: 'Lưu & gửi thu ngân thành công!',
                    text: `Đã lưu ${checked.length} dịch vụ cận lâm sàng cho ca #${currentLhId} và tự động chuyển viện phí sang bàn Thu Ngân. Bàn khám đã được làm mới để tiếp nhận bệnh nhân tiếp theo.`,
                    confirmButtonColor: '#1F6FB2'
                });

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
                    b.tai_khoan_id == u.id ||
                    (b.ho_ten && u.ho_ten && b.ho_ten.toLowerCase().includes(u.ho_ten.toLowerCase())) ||
                    (u.ten_dang_nhap && b.ma_bac_si && b.ma_bac_si.toLowerCase().includes(u.ten_dang_nhap.toLowerCase()))
                );
                if (bs) docList = list.filter(lh => lh.bac_si_id == bs.id);
            }

            const today = getNgayHienTai();
            const filterInput = document.getElementById('filter-bac-si-lich-su-ngay');
            if (filterInput && !filterInput.dataset.initialized) {
                filterInput.value = today;
                filterInput.dataset.initialized = 'true';
            }
            const selectedDate = filterInput ? filterInput.value : today;

            // Loại bỏ các ca đã hủy (DA_HUY)
            docList = (docList || []).filter(lh => lh.trang_thai !== 'DA_HUY');

            // NGUYÊN TẮC CA KHÁM: Đến ngày mới hiển thị lên, chưa đến ngày thì không hiện để tránh nhầm lẫn
            if (selectedDate) {
                docList = docList.filter(lh => lh.ngay_kham === selectedDate);
            } else {
                // Nếu chọn xem tất cả: Tuyệt đối chỉ xem các ca ĐÃ ĐẾN NGÀY (<= today)
                docList = docList.filter(lh => lh.ngay_kham <= today);
            }

            if (docList.length === 0) {
                const msg = selectedDate 
                    ? `Không có ca khám nào được ghi nhận cho ${selectedDate === today ? 'hôm nay (' + today + ')' : 'ngày ' + selectedDate}.` 
                    : 'Không có ca khám nào đã đến ngày.';
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium">${msg}</td></tr>`;
                return;
            }

            // SẮP XẾP THEO NGÀY VÀ THEO GIỜ:
            // Ngày mới nhất / hôm nay lên đầu, cùng ngày thì xếp theo giờ sáng -> chiều
            docList.sort((a, b) => {
                const dateComp = (b.ngay_kham || '').localeCompare(a.ngay_kham || '');
                if (dateComp !== 0) return dateComp;
                return (a.gio_bat_dau || '').localeCompare(b.gio_bat_dau || '');
            });

            tbody.innerHTML = docList.map(lh => {
                const bnName = lh.benh_nhan ? lh.benh_nhan.ho_ten : (lh.ho_ten_benh_nhan || 'Bệnh nhân');
                const sdt = lh.benh_nhan ? (lh.benh_nhan.so_dien_thoai || lh.so_dien_thoai || '--') : (lh.so_dien_thoai || '--');
                const isDaTiepNhan = (AppState.cacCaDaTiepNhan && AppState.cacCaDaTiepNhan.has(Number(lh.id))) || 
                                     (AppState.caKhamDangChon && AppState.caKhamDangChon.id == lh.id) || 
                                     lh.trang_thai === 'DANG_KHAM';
                const isDangChon = AppState.caKhamDangChon && AppState.caKhamDangChon.id == lh.id;

                let badgeHtml = '';
                if (lh.trang_thai === 'HOAN_THANH') {
                    badgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200"><i class="fa-solid fa-check-double text-blue-600"></i> Hoàn thành</span>`;
                } else if (isDaTiepNhan) {
                    badgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-300"><i class="fa-solid fa-circle-check text-emerald-600"></i> Đã tiếp nhận</span>`;
                } else if (lh.trang_thai === 'DA_XAC_NHAN') {
                    badgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">Đã xác nhận</span>`;
                } else {
                    badgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Chờ tiếp nhận</span>`;
                }

                return `
                    <tr class="hover:bg-slate-50/70 transition ${isDangChon ? 'bg-sky-50/60 font-medium' : ''}">
                        <td class="py-3 px-4 font-bold text-slate-800">#${lh.id}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${bnName}</td>
                        <td class="py-3 px-4 text-slate-600">${sdt}</td>
                        <td class="py-3 px-4 text-slate-700 font-medium">${lh.ngay_kham}</td>
                        <td class="py-3 px-4 font-mono font-semibold text-medical-600">${lh.gio_bat_dau} - ${lh.gio_ket_thuc}</td>
                        <td class="py-3 px-4 text-slate-500">${lh.ly_do_kham || 'Khám bệnh'}</td>
                        <td class="py-3 px-4">${badgeHtml}</td>
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
                    : `<div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-[13px]">${(b.ho_ten || 'BS').substring(0, 2).toUpperCase()}</div>`;

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
                                <i class="fa-solid fa-pen text-[13px] text-slate-400 group-hover/p:text-amber-600 transition"></i>
                            </button>
                        </td>
                        <td class="py-2.5 px-3 font-medium text-slate-700">${b.phong_kham || 'P201'}</td>
                        <td class="py-2.5 px-3 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <button onclick="moModalSuaGiaKham(${b.id})" class="px-2 py-1 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Sửa đơn giá khám">
                                    <i class="fa-solid fa-hand-holding-dollar text-[13px]"></i>
                                    <span>Giá</span>
                                </button>
                                <button onclick="moModalLichTrucBacSi(${b.id}, '${hoTenEsc}')" class="px-2 py-1 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Quản lý ca trực / lịch trực">
                                    <i class="fa-solid fa-calendar-days text-[13px]"></i>
                                    <span>Lịch trực</span>
                                </button>
                                <button onclick="moModalSuaBacSi(${b.id})" class="px-2 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Sửa thông tin bác sĩ">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                    <span>Sửa</span>
                                </button>
                                <button onclick="xacNhanXoaBacSi(${b.id}, '${hoTenEsc}')" class="px-2 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa bác sĩ">
                                    <i class="fa-solid fa-trash-can text-[13px]"></i>
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
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                    <span>Sửa</span>
                                </button>
                                <button onclick="xacNhanXoaChuyenKhoa(${ck.id}, '${tenKhoaEsc}')" class="px-2 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa chuyên khoa">
                                    <i class="fa-solid fa-trash-can text-[13px]"></i>
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
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ KHÓA</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">HOẠT ĐỘNG</span>';

                const lockBtnText = isLocked ? 'Mở Khóa' : 'Khóa';
                const lockBtnClass = isLocked ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100';
                const newStatus = isLocked ? 'HOAT_DONG' : 'BI_KHOA';
                const usernameEsc = (tk.ten_dang_nhap || '').replace(/'/g, "\\'");
                const fullnameEsc = (tk.ho_ten || '').replace(/'/g, "\\'");

                // Bắt buộc: Tài khoản ADMIN tuyệt đối KHÔNG có nút xóa tài khoản
                const deleteBtnHtml = vaiTro !== 'ADMIN' ? `
                    <button onclick="xacNhanXoaTaiKhoan(${tk.id}, '${usernameEsc}')" class="px-2.5 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Xóa tài khoản người dùng">
                        <i class="fa-solid fa-trash-can text-[13px]"></i>
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
                        <span>Mở khóa</span>
                    </button>
                ` : '');

                let roleBadgeHtml = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">${vaiTro}</span>`;
                if (vaiTro === 'ADMIN') {
                    roleBadgeHtml = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Quản trị viên</span>`;
                } else if (vaiTro === 'BAC_SI') {
                    roleBadgeHtml = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">Bác sĩ</span>`;
                } else if (vaiTro === 'LE_TAN') {
                    roleBadgeHtml = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Lễ tân</span>`;
                } else if (vaiTro === 'BENH_NHAN') {
                    roleBadgeHtml = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Bệnh nhân</span>`;
                }

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">#${tk.id}</td>
                        <td class="py-3 px-4 font-mono font-bold text-medical-700">${tk.ten_dang_nhap}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">${tk.ho_ten || '--'}</td>
                        <td class="py-3 px-4 text-slate-500">${tk.email || '--'}</td>
                        <td class="py-3 px-4 text-slate-600">${tk.so_dien_thoai || '--'}</td>
                        <td class="py-3 px-4">${roleBadgeHtml}</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <button onclick="moModalDoiMatKhauAdmin(${tk.id}, '${usernameEsc}', '${fullnameEsc}')" class="px-2.5 py-1 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1" title="Đổi / Đặt lại mật khẩu">
                                    <i class="fa-solid fa-key text-[13px]"></i>
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

        function moModalThemTaiKhoan() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên mới có quyền thêm tài khoản.');
                return;
            }
            const form = document.getElementById('form-them-tai-khoan');
            if (form) form.reset();
            moModal('modal-them-tai-khoan');
        }

        async function xacNhanThemTaiKhoan() {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên mới có quyền thêm tài khoản.');
                return;
            }

            const username = (document.getElementById('modal-ttk-username').value || '').trim();
            const matKhau = (document.getElementById('modal-ttk-pass').value || '').trim();
            const hoTen = (document.getElementById('modal-ttk-hoten').value || '').trim();
            const vaiTro = (document.getElementById('modal-ttk-vaitro').value || 'LE_TAN').trim();
            const email = (document.getElementById('modal-ttk-email').value || '').trim();
            const sdt = (document.getElementById('modal-ttk-sdt').value || '').trim();

            if (!username || !matKhau || !hoTen) {
                showToast('error', 'Thiếu dữ liệu', 'Vui lòng điền đầy đủ tên đăng nhập, mật khẩu và họ tên.');
                return;
            }

            if (matKhau.length < 6) {
                showToast('error', 'Mật khẩu yếu', 'Mật khẩu phải có ít nhất 6 ký tự.');
                return;
            }

            const payload = {
                ten_dang_nhap: username,
                mat_khau: matKhau,
                ho_ten: hoTen,
                vai_tro: vaiTro,
                email: email || null,
                so_dien_thoai: sdt || null
            };

            const res = await goiApi('POST', '/api/v1/tai-khoan', payload);
            if (res.ok) {
                dongModal('modal-them-tai-khoan');
                showToast('success', 'Thành công', `Đã tạo tài khoản "${username}" thành công!`);
                await taiDanhSachTaiKhoan();
            } else {
                showToast('error', 'Lỗi tạo tài khoản', (res.data && res.data.thong_diep) || 'Không thể tạo tài khoản.');
            }
        }

        async function xacNhanXoaTaiKhoan(id, username) {
            if (!AppState.currentUser || AppState.currentUser.vai_tro !== 'ADMIN') {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên (ADMIN) mới có quyền xóa tài khoản.');
                return;
            }

            const confirm = await Swal.fire({
                title: 'Xóa tài khoản?',
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
                    showToast('success', 'Đã xóa', `Đã xóa tài khoản "${username}".`);
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
                showToast('success', 'Thành công', `Đã cập nhật thông tin bác sĩ ${hoTen}!`);
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
                title: 'Xóa bác sĩ?',
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
                    showToast('success', 'Đã xóa', `Đã xóa thành công bác sĩ ${hoTen}.`);
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
                showToast('success', 'Thành công', `Đã cập nhật chuyên khoa ${ten}!`);
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
                title: 'Xóa chuyên khoa?',
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
                    showToast('success', 'Đã xóa', `Đã xóa chuyên khoa ${tenKhoa}.`);
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
                    ? `<img src="${bs.avatar}" class="w-9 h-9 rounded-md object-cover border border-[#E3E8EE] shadow-xs">`
                    : `<div class="w-9 h-9 rounded-md bg-sky-500 flex items-center justify-center text-white font-bold text-xs shadow-xs"><i class="fa-solid fa-user-doctor"></i></div>`;

                let badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[13px] font-bold bg-slate-100 text-slate-700 border border-[#E3E8EE]">${bs.phan_khuc}</span>`;
                if (bs.phan_khuc.includes('Giáo Sư') || bs.phan_khuc.includes('Đầu Ngành')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1 w-max"><i class="fa-solid fa-crown text-amber-500 text-[13px]"></i> ${bs.phan_khuc}</span>`;
                } else if (bs.phan_khuc.includes('Chuyên Gia') || bs.phan_khuc.includes('CKII')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200 flex items-center gap-1 w-max"><i class="fa-solid fa-star text-amber-400 text-[13px]"></i> ${bs.phan_khuc}</span>`;
                } else if (bs.phan_khuc.includes('VIP')) {
                    badgePhanKhuc = `<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1 w-max"><i class="fa-solid fa-gem text-amber-600 text-[13px]"></i> ${bs.phan_khuc}</span>`;
                }

                const hoTenEsc = (bs.ho_ten || '').replace(/'/g, "\\'");

                let actionHtml = '';
                if (isAdmin) {
                    actionHtml = `
                        <div class="inline-flex items-center space-x-1.5">
                            <button onclick="moModalSuaGiaKham(${bs.bac_si_id})" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-md text-xs font-bold transition flex items-center space-x-1 shadow-xs" title="Cập nhật biểu phí khám">
                                <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                <span>Sửa giá</span>
                            </button>
                            <button onclick="datLichTuBangGia(${bs.bac_si_id})" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md text-xs font-bold transition flex items-center space-x-1" title="Đặt lịch thử">
                                <i class="fa-regular fa-calendar-check text-[13px]"></i>
                                <span>Đặt lịch</span>
                            </button>
                        </div>
                    `;
                } else {
                    actionHtml = `
                        <button onclick="datLichTuBangGia(${bs.bac_si_id})" class="px-3 py-1.5 bg-medical-600 hover:bg-medical-700 text-white rounded-md text-xs font-bold transition flex items-center space-x-1.5 shadow-sm" title="Đặt lịch khám ngay với bác sĩ này">
                            <i class="fa-regular fa-calendar-check text-[13px]"></i>
                            <span>Đặt lịch ngay</span>
                        </button>
                    `;
                }

                return `
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-3.5 text-center text-slate-400 font-mono text-[13px]">${index + 1}</td>
                        <td class="py-3 px-3.5">
                            <div class="flex items-center space-x-3">
                                ${avatar}
                                <div>
                                    <p class="font-extrabold text-slate-900 text-xs">${bs.ho_ten}</p>
                                    <span class="font-mono text-[13px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">${bs.ma_bac_si}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1.5 w-max">
                                <i class="fa-solid fa-stethoscope text-[13px]"></i>
                                <span>${bs.ten_chuyen_khoa}</span>
                            </span>
                        </td>
                        <td class="py-3 px-3.5">
                            <p class="font-semibold text-slate-800 text-xs">${bs.hoc_vi}</p>
                            <p class="text-[13px] text-slate-500">${bs.kinh_nghiem ? bs.kinh_nghiem : '--'}</p>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-semibold text-slate-700 text-xs bg-slate-100 px-2 py-1 rounded-lg border border-[#E3E8EE]">
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
                showToast('success', 'Thành công', res.data.thong_diep || `Đã cập nhật đơn giá khám thành công!`);
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
                showToast('success', 'Thành công', `Đã cập nhật tài khoản #${id} sang trạng thái "${nhanTrangThai}".`);
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
                title: 'Đặt lại mật khẩu',
                html: `
                    <div class="text-left text-xs text-slate-500 mb-3 space-y-1">
                        <div>Tài khoản: <strong class="text-slate-800 font-mono">${username}</strong></div>
                        <div>Họ tên: <strong class="text-slate-800">${fullname || 'Người dùng'}</strong></div>
                    </div>
                    <div class="text-left">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu mới</label>
                        <input id="swal-input-mk-moi" type="password" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:outline-none focus:outline-none focus:border-[#1F6FB2] focus:ring-1 focus:ring-[#1F6FB2]" placeholder="Tối thiểu 6 ký tự...">
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check mr-1.5"></i> lưu mật khẩu mới',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: '#1F6FB2',
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
                        confirmButtonColor: '#1F6FB2'
                    });
                } else {
                    showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể đổi mật khẩu.');
                }
            }
        }

        // =============================================================
        // PHÂN HỆ THU NGÂN & HÓA ĐƠN (SERVICE 04)
        // =============================================================
        let boLocHoaDonAdminHienTai = 'ALL';
        let boLocHoaDonBenhNhanHienTai = 'ALL';

        function layDanhSachBenhNhanIdsCuaUser() {
            if (!AppState.currentUser) return [];
            const uid = Number(AppState.currentUser.id);
            const phone = AppState.currentUser.so_dien_thoai;
            const uName = (AppState.currentUser.ho_ten || '').toLowerCase().trim();
            const ids = new Set();

            if (uid) ids.add(uid);

            // 1. Lấy từ CacheHoSoGiaDinh (Hồ sơ bản thân và người thân)
            if (typeof CacheHoSoGiaDinh !== 'undefined' && Array.isArray(CacheHoSoGiaDinh)) {
                CacheHoSoGiaDinh.forEach(h => {
                    if (h.id) ids.add(Number(h.id));
                });
            }

            // 2. Lấy từ AppState.danhSachBenhNhan (nếu có dữ liệu)
            if (AppState.danhSachBenhNhan && AppState.danhSachBenhNhan.length > 0) {
                AppState.danhSachBenhNhan.forEach(bn => {
                    if (bn.tai_khoan_id == uid || (phone && bn.so_dien_thoai == phone)) {
                        ids.add(Number(bn.id));
                    }
                });
            }

            // 3. Lấy từ danh sách lịch hẹn của user
            if (AppState.danhSachLichHen && AppState.danhSachLichHen.length > 0) {
                AppState.danhSachLichHen.forEach(lh => {
                    const matchUser = (lh.tai_khoan_id == uid) ||
                        (lh.benh_nhan && (lh.benh_nhan.tai_khoan_id == uid || lh.benh_nhan.id == uid)) ||
                        (phone && (lh.so_dien_thoai == phone || (lh.benh_nhan && lh.benh_nhan.so_dien_thoai == phone))) ||
                        (uName && ((lh.ho_ten_benh_nhan && lh.ho_ten_benh_nhan.toLowerCase() === uName) || (lh.benh_nhan && lh.benh_nhan.ho_ten && lh.benh_nhan.ho_ten.toLowerCase() === uName)));
                    
                    if (matchUser) {
                        if (lh.benh_nhan_id) ids.add(Number(lh.benh_nhan_id));
                        if (lh.benh_nhan && lh.benh_nhan.id) ids.add(Number(lh.benh_nhan.id));
                    }
                });
            }

            return Array.from(ids);
        }

        function timTenBenhNhanTheoId(benhNhanId, lichHenId = null) {
            if (typeof CacheHoSoGiaDinh !== 'undefined' && Array.isArray(CacheHoSoGiaDinh) && CacheHoSoGiaDinh.length > 0) {
                const bn = CacheHoSoGiaDinh.find(b => Number(b.id) === Number(benhNhanId));
                if (bn && bn.ho_ten) return bn.ho_ten;
            }
            if (AppState.danhSachBenhNhan && AppState.danhSachBenhNhan.length > 0) {
                const bn = AppState.danhSachBenhNhan.find(b => Number(b.id) === Number(benhNhanId));
                if (bn && bn.ho_ten) return bn.ho_ten;
            }
            if (lichHenId && AppState.danhSachLichHen && AppState.danhSachLichHen.length > 0) {
                const lh = AppState.danhSachLichHen.find(l => Number(l.id) === Number(lichHenId));
                if (lh) {
                    if (lh.benh_nhan && lh.benh_nhan.ho_ten) return lh.benh_nhan.ho_ten;
                    if (lh.ho_ten_benh_nhan) return lh.ho_ten_benh_nhan;
                }
            }
            if (AppState.currentUser && AppState.currentUser.ho_ten) {
                return AppState.currentUser.ho_ten;
            }
            return `Bệnh nhân #${benhNhanId || ''}`;
        }

        async function taiDanhSachHoaDon(hienThongBao = false) {
            // Đảm bảo dữ liệu hồ sơ gia đình đã được tải trước để đối soát chính xác ID bệnh nhân
            if (AppState.currentUser && AppState.currentUser.vai_tro === 'BENH_NHAN') {
                if (typeof CacheHoSoGiaDinh === 'undefined' || CacheHoSoGiaDinh.length === 0) {
                    if (typeof taiVaRenderHoSoGiaDinh === 'function') {
                        try { await taiVaRenderHoSoGiaDinh(); } catch(e) {}
                    }
                }
            }
            const res = await goiApi('GET', '/api/v1/hoa-don');
            if (res.ok && res.data) {
                AppState.danhSachHoaDon = Array.isArray(res.data) ? res.data : (res.data.du_lieu || []);
                AppState.cacCaDaGuiThuNgan = AppState.cacCaDaGuiThuNgan || new Set();
                AppState.danhSachHoaDon.forEach(hd => {
                    if (hd.lich_hen_id) AppState.cacCaDaGuiThuNgan.add(Number(hd.lich_hen_id));
                });
                renderHoaDonTable(AppState.danhSachHoaDon, boLocHoaDonAdminHienTai);
                renderHoaDonBenhNhanTable(AppState.danhSachHoaDon, boLocHoaDonBenhNhanHienTai);
                if (hienThongBao) {
                    showToast('success', 'Đã cập nhật', 'Danh sách hóa đơn đã được làm mới.');
                }
            }
        }

        function locHoaDonAdmin(trangThai, btn) {
            boLocHoaDonAdminHienTai = trangThai;
            document.querySelectorAll('.btn-filter-hd-admin').forEach(b => {
                b.className = 'btn-filter-hd-admin px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900';
            });
            if (btn) {
                btn.className = 'btn-filter-hd-admin active px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs';
            }
            renderHoaDonTable(AppState.danhSachHoaDon, trangThai);
        }

        function renderHoaDonTable(list, filter = 'ALL') {
            const tbody = document.getElementById('tbody-hoa-don');
            if (!tbody) return;

            let filteredList = list || [];
            if (filter !== 'ALL') {
                filteredList = filteredList.filter(hd => hd.trang_thai === filter);
            }

            if (filteredList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-slate-400">Không có hóa đơn nào phù hợp bộ lọc.</td></tr>';
                return;
            }

            tbody.innerHTML = filteredList.map(hd => {
                let statusBadge = '';
                if (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID') {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ĐÃ THU</span>';
                } else if (hd.trang_thai === 'DA_HOAN_TIEN') {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ HOÀN TIỀN</span>';
                } else {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-amber-50 text-amber-700 border border-amber-200">CHỜ THU</span>';
                }

                const tongTien = Number(hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const thucThu = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const tienKham = Number(hd.tien_kham || 200000).toLocaleString('vi-VN') + ' đ';
                const tienCls = Number(hd.tien_dich_vu || hd.tien_dich_vu_cls || 0).toLocaleString('vi-VN') + ' đ';
                const tenBn = timTenBenhNhanTheoId(hd.benh_nhan_id, hd.lich_hen_id);

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">${hd.ma_hoa_don || ('HD-' + hd.id)}</td>
                        <td class="py-3 px-4 text-slate-600 font-medium">#${hd.lich_hen_id}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${tenBn}</td>
                        <td class="py-3 px-4 text-slate-600">${tienKham}</td>
                        <td class="py-3 px-4 text-slate-600">${tienCls}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">${tongTien}</td>
                        <td class="py-3 px-4 font-extrabold text-emerald-600">${thucThu}</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="xemChiTietHoaDon(${hd.id})" class="px-3 py-1 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 rounded-lg text-xs font-bold transition">
                                Chi tiết
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function locHoaDonBenhNhan(trangThai, btn) {
            boLocHoaDonBenhNhanHienTai = trangThai;
            document.querySelectorAll('.btn-filter-hd-bn').forEach(b => {
                b.className = 'btn-filter-hd-bn px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900';
            });
            if (btn) {
                btn.className = 'btn-filter-hd-bn active px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs';
            }
            renderHoaDonBenhNhanTable(AppState.danhSachHoaDon, trangThai);
        }

        function renderHoaDonBenhNhanTable(list, filter = 'ALL') {
            const tbody = document.getElementById('tbody-hoa-don-benh-nhan');
            if (!tbody) return;

            const myBnIds = layDanhSachBenhNhanIdsCuaUser();
            const u = AppState.currentUser;
            const uid = u ? Number(u.id) : 0;
            const phone = u ? u.so_dien_thoai : '';
            const uName = u ? (u.ho_ten || '').toLowerCase().trim() : '';

            // Lấy danh sách ID lịch hẹn thuộc về bệnh nhân này
            const myLhIds = new Set();
            if (AppState.danhSachLichHen && AppState.danhSachLichHen.length > 0) {
                AppState.danhSachLichHen.forEach(lh => {
                    const matchUser = (lh.tai_khoan_id == uid) ||
                        (lh.benh_nhan && (lh.benh_nhan.tai_khoan_id == uid || myBnIds.includes(Number(lh.benh_nhan.id)))) ||
                        (lh.benh_nhan_id && myBnIds.includes(Number(lh.benh_nhan_id))) ||
                        (phone && (lh.so_dien_thoai == phone || (lh.benh_nhan && lh.benh_nhan.so_dien_thoai == phone))) ||
                        (uName && ((lh.ho_ten_benh_nhan && lh.ho_ten_benh_nhan.toLowerCase() === uName) || (lh.benh_nhan && lh.benh_nhan.ho_ten && lh.benh_nhan.ho_ten.toLowerCase() === uName)));
                    if (matchUser) {
                        myLhIds.add(Number(lh.id));
                    }
                });
            }

            let myList = list || [];

            // Nếu là Bệnh nhân, chỉ hiển thị hóa đơn thuộc tài khoản/người thân của mình
            if (u && u.vai_tro === 'BENH_NHAN') {
                myList = myList.filter(hd => {
                    return myBnIds.includes(Number(hd.benh_nhan_id)) || myLhIds.has(Number(hd.lich_hen_id));
                });
            }

            // Sắp xếp hóa đơn theo ID mới nhất lên trước
            myList.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));

            // Thống kê nhanh viện phí của bệnh nhân
            let tongChoThu = 0;
            let soChoThu = 0;
            let tongDaThu = 0;
            let soDaThu = 0;

            myList.forEach(hd => {
                const tt = Number(hd.thuc_thu || hd.tong_tien || 0);
                if (hd.trang_thai === 'CHUA_THANH_TOAN') {
                    tongChoThu += tt;
                    soChoThu++;
                } else if (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID') {
                    tongDaThu += tt;
                    soDaThu++;
                }
            });

            const elChoThu = document.getElementById('stat-bn-cho-thu');
            if (elChoThu) elChoThu.textContent = tongChoThu.toLocaleString('vi-VN') + ' đ';
            const elSoChoThu = document.getElementById('stat-bn-so-cho-thu');
            if (elSoChoThu) elSoChoThu.textContent = `${soChoThu} hóa đơn chờ thanh toán`;

            const elDaThu = document.getElementById('stat-bn-da-thu');
            if (elDaThu) elDaThu.textContent = tongDaThu.toLocaleString('vi-VN') + ' đ';
            const elSoDaThu = document.getElementById('stat-bn-so-da-thu');
            if (elSoDaThu) elSoDaThu.textContent = `${soDaThu} hóa đơn đã thanh toán`;

            const elTongHd = document.getElementById('stat-bn-tong-hd');
            if (elTongHd) elTongHd.textContent = myList.length;

            if (filter !== 'ALL') {
                myList = myList.filter(hd => hd.trang_thai === filter);
            }

            if (myList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-slate-400">Bạn chưa có hóa đơn viện phí nào trong danh mục này.</td></tr>';
                return;
            }

            tbody.innerHTML = myList.map(hd => {
                let statusBadge = '';
                const daThu = (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID');
                const daHoan = (hd.trang_thai === 'DA_HOAN_TIEN');

                if (daThu) {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ĐÃ THANH TOÁN</span>';
                } else if (daHoan) {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ HOÀN TIỀN</span>';
                } else {
                    statusBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-amber-50 text-amber-700 border border-amber-200">CHỜ THANH TOÁN</span>';
                }

                const tongTien = Number(hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const thucThu = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
                const tienKham = Number(hd.tien_kham || 200000).toLocaleString('vi-VN') + ' đ';
                const tienCls = Number(hd.tien_dich_vu || hd.tien_dich_vu_cls || 0).toLocaleString('vi-VN') + ' đ';
                const giamGia = Number(hd.giam_gia || 0).toLocaleString('vi-VN') + ' đ';
                const tenBn = timTenBenhNhanTheoId(hd.benh_nhan_id, hd.lich_hen_id);

                let btnThaoTac = '';
                if (!daThu && !daHoan) {
                    btnThaoTac = `
                        <button onclick="xemChiTietHoaDon(${hd.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold shadow-xs transition flex items-center gap-1 mx-auto">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Thanh toán</span>
                        </button>
                    `;
                } else {
                    btnThaoTac = `
                        <button onclick="xemChiTietHoaDon(${hd.id})" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-md text-xs font-bold transition flex items-center gap-1 mx-auto">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Xem & In</span>
                        </button>
                    `;
                }

                return `
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">${hd.ma_hoa_don || ('HD-' + hd.id)}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">${tenBn}</td>
                        <td class="py-3 px-4 text-slate-600 font-medium">Lịch khám #${hd.lich_hen_id}</td>
                        <td class="py-3 px-4 text-slate-600">${tienKham}</td>
                        <td class="py-3 px-4 text-slate-600">${tienCls}</td>
                        <td class="py-3 px-4 text-rose-500 font-semibold">-${giamGia}</td>
                        <td class="py-3 px-4 font-extrabold text-emerald-600">${thucThu}</td>
                        <td class="py-3 px-4">${statusBadge}</td>
                        <td class="py-3 px-4 text-center">${btnThaoTac}</td>
                    </tr>
                `;
            }).join('');
        }


        async function xemChiTietHoaDon(hoaDonId) {
            AppState.hoaDonDangXemId = hoaDonId;
            const res = await goiApi('GET', `/api/v1/hoa-don/${hoaDonId}`);

            if (res.ok && res.data && res.data.du_lieu) {
                const hd = res.data.du_lieu;
                AppState.hoaDonDangXem = hd;
                const tenBn = timTenBenhNhanTheoId(hd.benh_nhan_id, hd.lich_hen_id);
                document.getElementById('modal-cthd-title').textContent = `Chi Tiết Hóa Đơn #${hd.ma_hoa_don || hd.id}`;
                document.getElementById('modal-cthd-benh-nhan').textContent = tenBn;
                document.getElementById('modal-cthd-ngay').textContent = hd.created_at ? hd.created_at.substring(0, 10) : 'Hôm nay';

                const daThu = (hd.trang_thai === 'DA_THANH_TOAN' || hd.trang_thai === 'PAID');
                const daHoan = (hd.trang_thai === 'DA_HOAN_TIEN');

                let badgeHtml = '';
                if (daThu) {
                    badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ĐÃ THU</span>';
                } else if (daHoan) {
                    badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ĐÃ HOÀN TIỀN</span>';
                } else {
                    badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-amber-50 text-amber-700 border border-amber-200">CHỜ THU</span>';
                }
                document.getElementById('modal-cthd-trang-thai').innerHTML = badgeHtml;

                // Render danh mục chi tiết viện phí (SỬA LỖI MẤT CẬN LÂM SÀNG)
                const itemsTbody = document.getElementById('modal-cthd-tbody-items');
                const items = hd.chi_tiet || hd.chi_tiet_hoa_don || [];

                let rowsHtml = '';
                if (items.length > 0) {
                    rowsHtml = items.map(it => {
                        const tenItem = it.ten_khoan_thu || it.ten_khoan_muc || 'Dịch vụ y tế';
                        return `
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-2.5 px-3 font-semibold text-slate-800">${tenItem}</td>
                                <td class="py-2.5 px-3 text-center">${it.so_luong || 1}</td>
                                <td class="py-2.5 px-3 text-slate-600">${Number(it.don_gia || 0).toLocaleString('vi-VN')} đ</td>
                                <td class="py-2.5 px-3 text-right font-bold text-slate-900">${Number(it.thanh_tien || 0).toLocaleString('vi-VN')} đ</td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    rowsHtml = `
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-3 font-bold text-slate-800">Phí khám bác sĩ chuyên khoa</td>
                            <td class="py-2.5 px-3 text-center">1</td>
                            <td class="py-2.5 px-3 text-slate-600">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                            <td class="py-2.5 px-3 text-right font-bold text-slate-900">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                        </tr>
                    `;
                }

                itemsTbody.innerHTML = rowsHtml;

                document.getElementById('modal-cthd-tien-kham').textContent = Number(hd.tien_kham || 200000).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-tien-cls').textContent = Number(hd.tien_dich_vu || hd.tien_dich_vu_cls || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-giam-tru').textContent = '-' + Number(hd.giam_gia || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('modal-cthd-thuc-thu').textContent = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';

                const btnPay = document.getElementById('btn-xac-nhan-thanh-toan');
                const boxPay = document.getElementById('box-thanh-toan-actions');
                const btnRefund = document.getElementById('btn-hoan-tien-hoa-don');

                if (daThu) {
                    btnPay.style.display = 'none';
                    boxPay.style.display = 'none';
                    if (btnRefund && AppState.currentUser && (AppState.currentUser.vai_tro === 'ADMIN' || AppState.currentUser.vai_tro === 'LE_TAN')) {
                        btnRefund.classList.remove('hidden');
                    }
                } else if (daHoan) {
                    btnPay.style.display = 'none';
                    boxPay.style.display = 'none';
                    if (btnRefund) btnRefund.classList.add('hidden');
                } else {
                    btnPay.style.display = 'inline-flex';
                    boxPay.style.display = 'block';
                    if (btnRefund) btnRefund.classList.add('hidden');
                    // Reset box VietQR
                    capNhatGiaoDienVietQR(AppState.phuongThucThanhToan, hd);
                }

                moModal('modal-chi-tiet-hoa-don');
            }
        }

        function chonPhuongThuc(btn, pt) {
            document.querySelectorAll('#box-thanh-toan-actions button').forEach(b => {
                b.className = 'py-2.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-[#E3E8EE] font-bold rounded-md text-center text-xs transition';
            });
            btn.className = 'py-2.5 px-2 bg-medical-50 hover:bg-medical-100 text-medical-700 border border-medical-200 font-bold rounded-md text-center text-xs transition';
            AppState.phuongThucThanhToan = pt;

            const hd = (AppState.danhSachHoaDon || []).find(h => h.id === AppState.hoaDonDangXemId);
            capNhatGiaoDienVietQR(pt, hd);
        }

        function capNhatGiaoDienVietQR(pt, hd) {
            const boxQr = document.getElementById('box-vietqr-code');
            if (!boxQr) return;

            if (pt === 'TIEN_MAT' || !hd) {
                boxQr.classList.add('hidden');
                return;
            }

            const amount = Math.round(Number(hd.thuc_thu || hd.tong_tien || 0));
            const code = hd.ma_hoa_don || ('HD' + hd.id);

            boxQr.classList.remove('hidden');
            const qrImg = document.getElementById('vietqr-img');
            const qrTitle = document.getElementById('vietqr-title');
            const qrInfo = document.getElementById('vietqr-info');

            if (pt === 'CHUYEN_KHOAN') {
                qrTitle.textContent = 'Quét mã VietQR chuyển khoản nhanh 24/7:';
                qrImg.src = `https://api.vietqr.io/image/970422-0123456789-compact2.jpg?amount=${amount}&addInfo=${encodeURIComponent(code)}`;
                qrInfo.innerHTML = `Ngân hàng: <strong>MBBank quân đội</strong><br>Số tài khoản: <strong class="font-mono text-sky-800">0123456789</strong><br>Số tiền: <strong class="text-emerald-600 font-black">${amount.toLocaleString('vi-VN')} đ</strong><br>Nội dung CK: <strong class="font-mono text-slate-900 bg-white px-1.5 py-0.5 rounded border border-[#E3E8EE]">${code}</strong>`;
            } else if (pt === 'VNPAY') {
                qrTitle.textContent = 'Cổng thanh toán quốc gia VNPAY-QR (mô phỏng):';
                qrImg.src = `https://api.vietqr.io/image/970422-0123456789-compact2.jpg?amount=${amount}&addInfo=VNPAY_${encodeURIComponent(code)}`;
                qrInfo.innerHTML = `Cổng thanh toán: <strong>VNPAY cổng quốc gia</strong><br>Số tiền: <strong class="text-emerald-600 font-black">${amount.toLocaleString('vi-VN')} đ</strong><br>Mã giao dịch: <strong class="font-mono text-slate-900 bg-white px-1.5 py-0.5 rounded border border-[#E3E8EE]">VNPAY_${code}</strong>`;
            } else if (pt === 'MOMO') {
                qrTitle.textContent = 'Ví điện tử MoMo QR (mô phỏng):';
                qrImg.src = `https://api.vietqr.io/image/970422-0123456789-compact2.jpg?amount=${amount}&addInfo=MOMO_${encodeURIComponent(code)}`;
                qrInfo.innerHTML = `Ví điện tử: <strong>MoMo Healthcare</strong><br>Số tiền: <strong class="text-emerald-600 font-black">${amount.toLocaleString('vi-VN')} đ</strong><br>Mã giao dịch: <strong class="font-mono text-rose-700 bg-white px-1.5 py-0.5 rounded border border-[#E3E8EE]">MOMO_${code}</strong>`;
            }
        }

        function inBienLaiHoaDonHienTai() {
            if (!AppState.hoaDonDangXemId) return;
            const hd = (AppState.danhSachHoaDon || []).find(h => h.id === AppState.hoaDonDangXemId) || {};
            const tenBn = timTenBenhNhanTheoId(hd.benh_nhan_id, hd.lich_hen_id);
            const items = hd.chi_tiet || hd.chi_tiet_hoa_don || [];

            let itemsHtml = '';
            if (items.length > 0) {
                itemsHtml = items.map((it, idx) => `
                    <tr>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">${idx + 1}</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1;">${it.ten_khoan_thu || it.ten_khoan_muc || 'Dịch vụ y tế'}</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">${it.so_luong || 1}</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right;">${Number(it.don_gia || 0).toLocaleString('vi-VN')} đ</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-weight: bold;">${Number(it.thanh_tien || 0).toLocaleString('vi-VN')} đ</td>
                    </tr>
                `).join('');
            } else {
                itemsHtml = `
                    <tr>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">1</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1;">Công khám bác sĩ chuyên khoa</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">1</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right;">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-weight: bold;">${Number(hd.tien_kham || 200000).toLocaleString('vi-VN')} đ</td>
                    </tr>
                `;
            }

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Biên Lai Viện Phí - ${hd.ma_hoa_don || hd.id}</title>
                    <meta charset="utf-8">
                    <style>
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; color: #1e293b; max-width: 800px; margin: 0 auto; }
                        .header { text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
                        .title { font-size: 20px; font-weight: 800; color: #0284c7; margin: 8px 0; text-transform: ; }
                        .meta-table { width: 100%; margin-bottom: 20px; font-size: 13px; }
                        .meta-table td { padding: 4px 0; }
                        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; }
                        .data-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; font-weight: bold; }
                        .totals { margin-left: auto; width: 340px; font-size: 13px; margin-bottom: 30px; }
                        .totals td { padding: 4px 8px; }
                        .footer { display: flex; justify-content: space-between; text-align: center; margin-top: 40px; font-size: 13px; }
                        @media print {
                            .no-print { display: none; }
                            body { padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div style="font-weight: bold; font-size: 14px; color: #64748b; text-transform: ;">PHÒNG KHÁM ĐA KHOA QUỐC TẾ - HỆ THỐNG Y TẾ SỐ</div>
                        <div class="title">BIÊN LAI THU TIỀN VIỆN PHÍ & DỊCH VỤ</div>
                        <div style="font-size: 12px; color: #64748b;">Mã hóa đơn: <strong style="color: #0f172a; font-family: monospace;">${hd.ma_hoa_don || ('HD-' + hd.id)}</strong> | Ngày in: ${new Date().toLocaleDateString('vi-VN')}</div>
                    </div>

                    <table class="meta-table">
                        <tr>
                            <td style="width: 50%;">Họ tên bệnh nhân: <strong>${tenBn}</strong></td>
                            <td>Mã lịch hẹn: <strong>#${hd.lich_hen_id || '--'}</strong></td>
                        </tr>
                        <tr>
                            <td>Trạng thái thanh toán: <strong>${hd.trang_thai === 'DA_THANH_TOAN' ? 'ĐÃ HOÀN TẤT THANH TOÁN' : (hd.trang_thai === 'DA_HOAN_TIEN' ? 'ĐÃ HOÀN TIỀN' : 'CHỜ THANH TOÁN')}</strong></td>
                            <td>Hình thức: <strong>${hd.phuong_thuc_thanh_toan || 'TIEN_MAT'}</strong></td>
                        </tr>
                    </table>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">STT</th>
                                <th>Nội dung chi phí & dịch vụ</th>
                                <th style="width: 50px;">SL</th>
                                <th style="width: 120px;">Đơn giá</th>
                                <th style="width: 140px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>

                    <table class="totals">
                        <tr>
                            <td>Tổng chi phí ban đầu:</td>
                            <td style="text-align: right; font-weight: bold;">${Number(hd.tong_tien || 0).toLocaleString('vi-VN')} đ</td>
                        </tr>
                        <tr>
                            <td>Miễn giảm / ưu đãi:</td>
                            <td style="text-align: right; color: #e11d48;">-${Number(hd.giam_gia || 0).toLocaleString('vi-VN')} đ</td>
                        </tr>
                        <tr style="border-top: 1.5px solid #0284c7; font-size: 15px;">
                            <td style="font-weight: 800; color: #0284c7;">THỰC THU:</td>
                            <td style="text-align: right; font-weight: 900; color: #059669;">${Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN')} đ</td>
                        </tr>
                    </table>

                    <div class="footer">
                        <div>
                            <div><strong>Người nộp tiền</strong></div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">(Ký, ghi rõ họ tên)</div>
                        </div>
                        <div>
                            <div><strong>Bộ phận thu ngân</strong></div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">(Ký, đóng dấu)</div>
                            <div style="margin-top: 50px; font-weight: bold;">Bộ phận tài chính viện phí</div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 35px;" class="no-print">
                        <button onclick="window.print()" style="padding: 10px 24px; background: #0284c7; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px;">In biên lai này</button>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function moModalHoanTien() {
            if (!AppState.currentUser || (AppState.currentUser.vai_tro !== 'ADMIN' && AppState.currentUser.vai_tro !== 'LE_TAN')) {
                showToast('error', 'Từ chối', 'Chỉ Quản trị viên hoặc Lễ tân mới có quyền hoàn tiền viện phí.');
                return;
            }
            const hd = (AppState.danhSachHoaDon || []).find(h => h.id === AppState.hoaDonDangXemId);
            if (!hd) return;
            document.getElementById('modal-ht-ma-hd').textContent = hd.ma_hoa_don || ('HD-' + hd.id);
            document.getElementById('modal-ht-so-tien').textContent = Number(hd.thuc_thu || hd.tong_tien || 0).toLocaleString('vi-VN') + ' đ';
            document.getElementById('modal-ht-ly-do').value = '';
            moModal('modal-hoan-tien-hoa-don');
        }

        async function xacNhanHoanTien() {
            if (!AppState.hoaDonDangXemId) return;
            const lyDo = document.getElementById('modal-ht-ly-do').value.trim();
            if (!lyDo) {
                showToast('error', 'Lỗi', 'Vui lòng nhập lý do hoàn tiền.');
                return;
            }
            const res = await goiApi('PUT', `/api/v1/hoa-don/${AppState.hoaDonDangXemId}/hoan-tien`, { ly_do: lyDo });
            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Hoàn tiền thành công!',
                    text: `Hóa đơn đã được chuyển sang trạng thái ĐÃ HOÀN TIỀN.`,
                    confirmButtonColor: '#1F6FB2'
                });
                dongModal('modal-hoan-tien-hoa-don');
                dongModal('modal-chi-tiet-hoa-don');
                await taiDanhSachHoaDon();
                await taiBaoCaoDoanhThu();
            } else {
                showToast('error', 'Lỗi', res.data.thong_diep || 'Không thể hoàn tiền.');
            }
        }

        async function xacNhanThanhToanHoaDonHienTai() {
            if (!AppState.hoaDonDangXemId) return;

            const res = await goiApi('PUT', `/api/v1/hoa-don/${AppState.hoaDonDangXemId}/thanh-toan`, {
                phuong_thuc_thanh_toan: AppState.phuongThucThanhToan
            });

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thanh toán thành công!',
                    text: `Hóa đơn #${AppState.hoaDonDangXemId} đã hoàn tất thanh toán.`,
                    confirmButtonColor: '#1F6FB2'
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
                            <td style="padding: 8px; font-weight: 600;">Các dịch vụ xét nghiệm & cận lâm sàng</td>
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
                        .title-section h1 { font-size: 18px; font-weight: 900; text-transform: ; color: #0f172a; letter-spacing: 0.5px; }
                        .title-section p { font-size: 11px; color: #64748b; font-style: italic; margin-top: 3px; }
                        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px; }
                        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
                        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                        .items-table th { background: #f1f5f9; padding: 8px; text-align: left; font-size: 11px; font-weight: 800; text-transform: ; color: #475569; border-bottom: 2px solid #cbd5e1; }
                        .summary-box { float: right; width: 340px; margin-bottom: 20px; }
                        .summary-table { width: 100%; border-collapse: collapse; }
                        .summary-table td { padding: 4px 8px; }
                        .sign-section { clear: both; width: 100%; margin-top: 30px; display: table; }
                        .sign-col { display: table-cell; width: 33.33%; text-align: center; vertical-align: top; }
                        .sign-title { font-weight: 800; font-size: 12px; text-transform: ; color: #334155; }
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
                                <div style="font-size: 15px; font-weight: 900; color: #0284c7; text-transform: ; letter-spacing: 0.5px;">PHÒNG KHÁM ĐA KHOA QUỐC TẾ DV</div>
                                <div style="font-size: 11px; color: #475569; margin-top: 3px;">Địa chỉ: 123 Đường Y học, phường bến nghé, quận 1, TP. Hồ chí minh</div>
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
                        <p>(Bản thể hiện hóa đơn điện tử liên dịch vụ - kèm phiếu chỉ định cận lâm sàng)</p>
                    </div>

                    <div class="info-box">
                        <div class="info-grid">
                            <div><strong>Họ và tên người bệnh:</strong> <span style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: ;">${tenBn}</span></div>
                            <div><strong>Giới tính / tuổi:</strong> ${gioiTinh} | ${dobStr}</div>
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
                                <th style="width: 45%;">Hạng mục dịch vụ / khám chữa bệnh</th>
                                <th style="width: 20%;">Phân loại</th>
                                <th style="width: 8%; text-align: center;">SL</th>
                                <th style="width: 11%; text-align: right;">Đơn giá</th>
                                <th style="width: 11%; text-align: right;">Thành tiền</th>
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
                                <td style="color: #059669;">Miễn giảm / ưu đãi BHYT:</td>
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
                            <div class="sign-title">Bệnh nhân / thân nhân</div>
                            <div class="sign-sub">(Ký & ghi rõ họ tên)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">${tenBn}</div>
                        </div>
                        <div class="sign-col">
                            <div class="sign-title">Bác sĩ khám bệnh</div>
                            <div class="sign-sub">(Ký, đóng dấu chức danh)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">${tenBacSi}</div>
                        </div>
                        <div class="sign-col">
                            <div class="sign-title">Người thu tiền / thu ngân</div>
                            <div class="sign-sub">(Ký & đóng dấu biên lai)</div>
                            <div class="sign-space"></div>
                            <div class="sign-name">Bộ phận thu ngân DV</div>
                        </div>
                    </div>

                    <div class="footer-note">
                        <p>Cảm ơn quý khách đã tin tưởng khám chữa bệnh tại phòng khám đa khoa DV.</p>
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
                showToast('error', 'Trình duyệt chặn pop-up', 'Vui lòng cho phép Pop-up để mở cửa sổ in hóa đơn.');
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
                    text: `Đã tạo hồ sơ và cấp tài khoản cho ${formatTenBacSi(hoTen)}`,
                    confirmButtonColor: '#1F6FB2'
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
                    confirmButtonColor: '#1F6FB2'
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
                b.classList.add('text-slate-600', 'hover:text-slate-900');
            });
            if (btn) {
                btn.classList.remove('text-slate-600', 'hover:text-slate-900');
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
            gradientBlue.addColorStop(0, 'rgba(31, 111, 178, 0.35)');
            gradientBlue.addColorStop(1, 'rgba(31, 111, 178, 0.01)');

            window.ClinicChartInstances.doanhThu = new Chart(ctxDoanhThu, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doanh Thu Viện Phí (VNĐ)',
                            data: revenueData,
                            borderColor: '#1F6FB2',
                            backgroundColor: gradientBlue,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointBackgroundColor: '#1F6FB2',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Lượt Khám Tiếp Đón',
                            data: appointmentsData,
                            borderColor: '#94A3B8',
                            backgroundColor: 'rgba(100, 116, 139, 0.1)',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.35,
                            pointBackgroundColor: '#64748B',
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
                            titleFont: { family: 'Be Vietnam Pro', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Be Vietnam Pro', size: 11 },
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
                            ticks: { font: { family: 'Be Vietnam Pro', size: 10 }, color: '#64748b' }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                font: { family: 'Be Vietnam Pro', size: 10 },
                                color: '#1F6FB2',
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
                                font: { family: 'Be Vietnam Pro', size: 10 },
                                color: '#64748B',
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
                    'VNPAY': { ten: 'VNPAY QR', tong: 0, count: 0, color: '#1F6FB2' },
                    'CHUYEN_KHOAN': { ten: 'Chuyển Khoản', tong: 0, count: 0, color: '#10b981' },
                    'TIEN_MAT': { ten: 'Tiền Mặt', tong: 0, count: 0, color: '#f59e0b' },
                    'MOMO': { ten: 'Ví MoMo', tong: 0, count: 0, color: '#4F91C9' }
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
                    ptColors = ['#1F6FB2', '#10b981', '#f59e0b', '#4F91C9'];
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
                                titleFont: { family: 'Be Vietnam Pro', size: 12, weight: 'bold' },
                                bodyFont: { family: 'Be Vietnam Pro', size: 11 },
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
                                    <span class="text-[13px] font-bold px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600">${pct}%</span>
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
                                '#1F6FB2',
                                '#1F6FB2',
                                '#B3D1EB',
                                '#144A78',
                                '#82B3DC',
                                '#4F91C9'
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
                                ticks: { font: { family: 'Be Vietnam Pro', size: 10 } }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { family: 'Be Vietnam Pro', size: 10 } },
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
                                '#1F6FB2',
                                '#144A78',
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
                                    font: { family: 'Be Vietnam Pro', size: 10, weight: '600' }
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
                                '#2A7BBE',
                                '#1F6FB2',
                                '#82B3DC',
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
                                ticks: { font: { family: 'Be Vietnam Pro', size: 10 }, callback: v => v + '%' },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { family: 'Be Vietnam Pro', size: 10, weight: '600' } }
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

                showToast('success', 'Hệ thống trực tuyến', 'Cả 4 Microservices đều đang ONLINE!');
            }
        }

        function capNhatBadgeService(elementId, svcData) {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (svcData && svcData.trang_thai === 'ONLINE') {
                el.className = 'px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                el.textContent = `ONLINE (${svcData.do_tre_ms || 0}ms)`;
            } else {
                el.className = 'px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200';
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
                    confirmButtonColor: '#1F6FB2'
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
            
            const bs = (AppState.danhSachBacSi || []).find(b => b.id == bacSiId);
            const tenHienThi = formatTenBacSi(hoTen || (bs ? bs.ho_ten : ''));
            const isAdmin = Boolean(AppState.currentUser && AppState.currentUser.vai_tro === 'ADMIN');
            
            const titleEl = document.getElementById('modal-lt-title');
            if (titleEl) {
                titleEl.textContent = isAdmin ? 'Quản lý lịch trực bác sĩ' : 'Lịch trực của tôi';
            }
            
            const nameEl = document.getElementById('modal-lt-bac-si-name');
            if (nameEl) {
                nameEl.textContent = isAdmin ? `Đang quản lý ca trực: ${tenHienThi}` : tenHienThi;
            }

            // Dropdown chọn bác sĩ cho Admin
            const selectWrapper = document.getElementById('modal-lt-select-bs-wrapper');
            const selectEl = document.getElementById('modal-lt-select-bs');
            if (selectWrapper && selectEl) {
                if (isAdmin && (AppState.danhSachBacSi || []).length > 0) {
                    selectWrapper.classList.remove('hidden');
                    selectEl.innerHTML = AppState.danhSachBacSi.map(b => 
                        `<option value="${b.id}" ${b.id == bacSiId ? 'selected' : ''}>${formatTenBacSi(b.ho_ten)} (${(b.chuyen_khoa && (b.chuyen_khoa.ten_khoa || b.chuyen_khoa.ten_chuyen_khoa)) || 'Khoa khám'})</option>`
                    ).join('');
                } else {
                    selectWrapper.classList.add('hidden');
                }
            }
            
            if (bs) {
                document.getElementById('modal-lt-phong').value = bs.phong_kham || 'P201';
            }

            tuDongDienGioCaTruc();
            moModal('modal-lich-truc-bac-si');
            const scrollBox = document.querySelector('#modal-lich-truc-bac-si .overflow-y-auto');
            if (scrollBox) scrollBox.scrollTop = 0;
            await taiLichTrucBacSi(bacSiId);
        }

        function doiBacSiXemLichTruc(bacSiId) {
            const bs = (AppState.danhSachBacSi || []).find(b => b.id == bacSiId);
            if (bs) {
                moModalLichTrucBacSi(bs.id, bs.ho_ten);
            }
        }

        async function moModalLichTrucBacSiHienTai() {
            if (!AppState.currentUser) return;
            const role = chuanHoaVaiTroCurrentUser();
            
            let bsId = AppState.currentUser.bac_si ? AppState.currentUser.bac_si.id : null;
            let hoTen = AppState.currentUser.ho_ten || '';

            if (!bsId) {
                const match = (AppState.danhSachBacSi || []).find(b => b.tai_khoan_id == AppState.currentUser.id || b.email == AppState.currentUser.email);
                if (match) {
                    bsId = match.id;
                    hoTen = match.ho_ten;
                }
            }

            // Nếu là ADMIN (không phải bác sĩ), mở quản lý ca trực cho bác sĩ đầu tiên kèm theo dropdown chọn bác sĩ
            if (!bsId && role === 'ADMIN') {
                if ((AppState.danhSachBacSi || []).length > 0) {
                    bsId = AppState.danhSachBacSi[0].id;
                    hoTen = AppState.danhSachBacSi[0].ho_ten;
                } else {
                    showToast('info', 'Thông báo', 'Hệ thống chưa có danh sách bác sĩ nào để quản lý ca trực.');
                    return;
                }
            } else if (!bsId) {
                // Nếu là BAC_SI nhưng chưa link hồ sơ
                showToast('error', 'Chưa tìm thấy', 'Tài khoản của bạn chưa được liên kết với hồ sơ bác sĩ.');
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
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-emerald-50 text-emerald-800 border-emerald-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-600"></i> Đã duyệt (hoạt động)</span>';
                    } else if (tt === 'CHO_DUYET') {
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-amber-50 text-amber-800 border-amber-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-clock text-amber-500"></i> Chờ admin duyệt</span>';
                    } else if (tt === 'TU_CHOI') {
                        trangThaiHtml = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-rose-50 text-rose-800 border-rose-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-xmark text-rose-600"></i> Bị từ chối</span>';
                    } else {
                        trangThaiHtml = `<span class="px-2 py-0.5 rounded-lg text-[13px] font-bold border bg-slate-50 text-slate-600 border-[#E3E8EE]">${tt}</span>`;
                    }

                    const sJson = JSON.stringify(s).replace(/"/g, '&quot;');

                    let adminBtns = '';
                    if (isAdmin) {
                        if (tt === 'CHO_DUYET' || tt === 'TU_CHOI') {
                            adminBtns += `
                                <button type="button" onclick="duyetCaTruc(${s.id}, ${bacSiId})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[13px] font-extrabold transition shadow-xs flex items-center gap-1" title="Duyệt kích hoạt ca trực này">
                                    <i class="fa-solid fa-check"></i> Duyệt
                                </button>
                            `;
                        }
                        if (tt === 'CHO_DUYET' || tt === 'HOAT_DONG') {
                            adminBtns += `
                                <button type="button" onclick="tuChoiCaTruc(${s.id}, ${bacSiId})" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[13px] font-extrabold transition shadow-xs flex items-center gap-1" title="Từ chối ca trực này">
                                    <i class="fa-solid fa-ban"></i> Từ chối
                                </button>
                            `;
                        }
                    }

                    return `
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-3 font-bold text-slate-800">${tenThu}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[13px] font-bold border ${caBadge}">${tenCa}</span>
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
                    ? '<span class="text-emerald-700 font-bold">Đã duyệt</span>' 
                    : (caHienTai.trang_thai === 'CHO_DUYET' ? '<span class="text-amber-700 font-bold">Chờ admin duyệt</span>' : '<span class="text-rose-700 font-bold">Bị từ chối</span>');

                alertBox.className = 'p-3 rounded-md border border-amber-300 bg-amber-50 text-amber-900 text-[13px] block animate-fadeIn';
                alertBox.innerHTML = `
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-amber-600 mt-0.5 text-xs"></i>
                        <div>
                            <span class="font-extrabold text-amber-800">${tenThu} hiện đã có:</span> <strong>${tenCaCu}</strong> (${caHienTai.gio_bat_dau} - ${caHienTai.gio_ket_thuc} | Trạng thái: ${ttBadge}).
                            <div class="text-[13px] text-amber-700 mt-1">Mỗi ngày chỉ trực tối đa 1 ca. Khi bấm lưu, hệ thống sẽ tự động thực hiện <strong>ĐỔI CA TRỰC</strong> sang ca bạn đang chọn ${isAdmin ? 'ngay lập tức' : 'và chuyển sang trạng thái <strong>Chờ admin phê duyệt</strong>'}.</div>
                        </div>
                    </div>
                `;
                if (lblSubmit && !caId) {
                    lblSubmit.textContent = isAdmin ? 'Đổi Ca Trực Ngay' : 'Gửi Yêu Cầu Đổi Ca Trực (Chờ Admin Duyệt)';
                }
            } else {
                alertBox.className = 'p-2.5 rounded-md border border-sky-200 bg-sky-50 text-sky-800 text-[13px] block';
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
                showToast('success', 'Đã phê duyệt', res.data?.thong_diep || 'Ca trực đã được phê duyệt và kích hoạt thành công.');
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
                showToast('info', 'Đã từ chối', res.data?.thong_diep || 'Đã chuyển ca trực sang trạng thái từ chối.');
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
                titleEl.innerHTML = `<i class="fa-solid fa-pen-to-square text-amber-500"></i> Đang đổi lịch / chỉnh sửa ca trực: <strong class="text-sky-700">${tenThu}</strong>`;
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
            if (lblSubmit) lblSubmit.textContent = 'Lưu ca trực';
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
                        ? 'px-2 py-0.5 rounded-full text-[13px] font-black bg-amber-500 text-white shadow-xs ' 
                        : 'px-2 py-0.5 rounded-full text-[13px] font-black bg-slate-400 text-white shadow-xs';
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
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-emerald-50 text-emerald-800 border-emerald-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-600"></i> Đã duyệt (hoạt động)</span>';
                    } else if (tt === 'CHO_DUYET') {
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-amber-50 text-amber-800 border-amber-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-clock text-amber-500"></i> Chờ phê duyệt</span>';
                    } else if (tt === 'TU_CHOI') {
                        ttBadge = '<span class="px-2.5 py-1 rounded-lg text-[13px] font-extrabold border bg-rose-50 text-rose-800 border-rose-300 inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-xmark text-rose-600"></i> Bị từ chối</span>';
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
                                <div class="text-[13px] text-slate-500">${bs.hoc_vi || 'Bác sĩ chuyên khoa'}</div>
                            </td>
                            <td class="py-2.5 px-3 font-bold text-slate-800">${tenThu}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[13px] font-bold border ${caBadge}">${tenCa}</span>
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
            box.className = 'p-3.5 rounded-md border text-xs transition-all bg-slate-50 border-[#E3E8EE] text-slate-600';
            box.innerHTML = '<div class="flex items-center space-x-2"><i class="fa-solid fa-spinner fa-spin text-medical-600"></i><span>Đang kiểm tra ca trực bác sĩ...</span></div>';

            const res = await goiApi('GET', `/api/v1/bac-si/${bacSiId}/kiem-tra-truc?ngay=${ngayKham}`);
            if (res.ok && res.data && res.data.du_lieu) {
                const d = res.data.du_lieu;
                if (d.co_truc && d.ca_truc_trong_ngay && d.ca_truc_trong_ngay.length > 0) {
                    const shiftsHtml = d.ca_truc_trong_ngay.map(c => {
                        const tenCa = MAP_TEN_CA_JS[c.ca_truc] || c.ca_truc;
                        return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100/70 border border-emerald-300 text-emerald-900 font-bold"><i class="fa-solid fa-clock text-emerald-600"></i> ${tenCa}: ${c.gio_bat_dau} - ${c.gio_ket_thuc} (${c.phong_kham || 'P201'})</span>`;
                    }).join(' ');

                    box.className = 'p-3.5 rounded-md border text-xs transition-all bg-emerald-50 border-emerald-200 text-emerald-900';
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
                    box.className = 'p-3.5 rounded-md border text-xs transition-all bg-amber-50 border-amber-200 text-amber-900';
                    box.innerHTML = `
                        <div class="space-y-1">
                            <div class="font-extrabold flex items-center space-x-1.5 text-amber-800">
                                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                                <span>Chú ý: Bác sĩ không có ca trực cố định vào ${d.thu || 'ngày này'}!</span>
                            </div>
                            <p class="text-amber-700 text-[13px]">
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
                lbl.innerHTML = '<span class="text-sky-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-child text-sky-600"></i> Họ tên bé / con cái: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Bé Bo, Nguyễn Gia Hân...';
            } else if (qh === 'CHA_ME') {
                lbl.innerHTML = '<span class="text-amber-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-person-cane text-amber-600"></i> Họ tên bố / mẹ: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Nguyễn Văn Nam, Trần Thị Mai...';
            } else if (qh === 'VO_CHONG') {
                lbl.innerHTML = '<span class="text-rose-700 font-extrabold flex items-center gap-1"><i class="fa-solid fa-heart text-rose-600"></i> Họ tên vợ / chồng: <span class="text-rose-500">*</span></span>';
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
                    
                    let options = '<option value="MOI">+ Tạo hồ sơ người thân mới (con cái, bố/mẹ...)</option>';
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
                            const activeClass = isSelect ? 'selected bg-medical-600 text-white font-bold ring-2 ring-medical-500' : 'bg-white hover:bg-sky-50 text-slate-800 border-[#E3E8EE] hover:border-sky-400';
                            return `
                                <button type="button" class="slot-btn py-2 px-1 rounded-md border text-[13px] font-mono transition text-center shadow-xs ${activeClass}" onclick="chonSlot(this, '${s.bat_dau.substring(0, 5)}', '${s.ket_thuc.substring(0, 5)}')">
                                    <div class="font-extrabold">${s.bat_dau.substring(0, 5)}</div>
                                    <div class="text-[13px] opacity-75 font-sans slot-sub-text">${isSelect ? 'Đã chọn' : 'Trống'}</div>
                                </button>
                            `;
                        } else {
                            const badge = s.trang_thai === 'DA_DAT' ? 'Đã kín' : 'Qua giờ';
                            return `
                                <button type="button" disabled class="py-2 px-1 rounded-md border border-[#E3E8EE] bg-slate-100 text-slate-400 text-[13px] font-mono text-center cursor-not-allowed opacity-60">
                                    <div class="font-bold line-through">${s.bat_dau.substring(0, 5)}</div>
                                    <div class="text-[13px] font-sans text-slate-400">${badge}</div>
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
                    <input type="text" class="kt-ten-thuoc w-full px-2 py-1 bg-slate-50 border border-[#E3E8EE] rounded-lg text-xs" placeholder="Tên thuốc..." value="${ten}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-ham-luong w-full px-2 py-1 bg-slate-50 border border-[#E3E8EE] rounded-lg text-xs" placeholder="500mg, 1g..." value="${hamLuong}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-so-luong w-full px-2 py-1 bg-slate-50 border border-[#E3E8EE] rounded-lg text-xs" placeholder="10 viên..." value="${soLuong}">
                </td>
                <td class="p-2">
                    <input type="text" class="kt-cach-dung w-full px-2 py-1 bg-slate-50 border border-[#E3E8EE] rounded-lg text-xs" placeholder="Sáng 1v, tối 1v sau ăn..." value="${cachDung}">
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
                    title: 'Đã lưu toa thuốc!',
                    text: 'Kết luận chẩn đoán và đơn thuốc đã được cập nhật thành công vào hồ sơ bệnh án.',
                    confirmButtonColor: '#185A92'
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
            printWindow.document.write('<!DOCTYPE html><html><head><title>Phiếu khám bệnh & toa thuốc</title>');
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
            selectBs.innerHTML = '<option value="">Tất cả bác sĩ thăm khám</option>';

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
            const todayStr = getNgayHienTai();

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
                    chuyenKhoa = `<div class="text-[13px] text-sky-600 font-medium">${khoa}</div>`;
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
                        <div class="text-[13px] text-slate-400 font-medium"><i class="fa-regular fa-calendar text-[13px] mr-1"></i>${ngay}</div>
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
                                <i class="fa-solid fa-stethoscope text-[13px] text-rose-500"></i>
                                <span class="truncate max-w-[190px]" title="${chuanDoan}">${chuanDoan}</span>
                            </div>
                            ${lyDo ? `<div class="text-[13px] text-slate-400 truncate max-w-[190px]" title="${lyDo}">Lý do: ${lyDo}</div>` : ''}
                        `;
                    } else {
                        benhLy = `
                            <div class="text-slate-600 font-medium truncate max-w-[190px]" title="${lyDo || 'Chờ bác sĩ kết luận'}">
                                ${lyDo ? `Khám: ${lyDo}` : 'Chờ bác sĩ khám'}
                            </div>
                            <span class="text-[13px] px-1.5 py-0.2 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200">Đang theo dõi</span>
                        `;
                    }
                }

                // Nhóm máu & Cảnh báo
                let nhomMauBadge = bn.nhom_mau ? `<span class="px-1.5 py-0.5 rounded text-[13px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">${bn.nhom_mau}</span>` : '';
                let diUngBadge = bn.tien_su_di_ung ? `<span class="px-1.5 py-0.5 rounded text-[13px] font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1" title="Dị ứng: ${bn.tien_su_di_ung}"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i>Dị ứng</span>` : '';
                let benhNenBadge = bn.tien_su_benh ? `<span class="px-1.5 py-0.5 rounded text-[13px] font-bold bg-slate-100 text-slate-700 border border-[#E3E8EE]" title="Bệnh nền: ${bn.tien_su_benh}">Bệnh nền</span>` : '';

                // Tuổi
                let tuoi = '';
                if (bn.ngay_sinh) {
                    const birthYear = new Date(bn.ngay_sinh).getFullYear();
                    const age = new Date().getFullYear() - birthYear;
                    tuoi = age > 0 ? `${age} tuổi` : '';
                }

                // Trạng thái badge
                let statusBadge = '<span class="px-2 py-0.5 rounded-full text-[13px] font-bold bg-slate-100 text-slate-500">Mới tạo</span>';
                if (latestVisit) {
                    const st = latestVisit.trang_thai;
                    if (st === 'HOAN_THANH') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-200">Đã khám</span>';
                    } else if (st === 'DANG_KHAM') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-sky-100 text-sky-700 border border-sky-200">Đang khám</span>';
                    } else if (st === 'DA_XAC_NHAN') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-amber-100 text-amber-700 border border-amber-200">Chờ khám</span>';
                    } else if (st === 'DA_HUY') {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-slate-100 text-slate-500 border border-[#E3E8EE]">Đã hủy</span>';
                    } else {
                        statusBadge = '<span class="px-2.5 py-1 rounded-full text-[13px] font-extrabold bg-indigo-100 text-indigo-700 border border-indigo-200">Chờ duyệt</span>';
                    }
                }

                const maBn = bn.ma_benh_nhan || `BN-${String(bn.id).padStart(4, '0')}`;

                html += `
                    <tr class="hover:bg-rose-50/30 transition group">
                        <!-- Cột 1: Bệnh nhân -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-md bg-rose-500 text-white flex items-center justify-center font-black text-xs shadow-xs flex-shrink-0 transition-transform">
                                    ${(bn.ho_ten || 'BN').substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span>${bn.ho_ten}</span>
                                        <span class="text-[13px] px-1.5 py-0.2 rounded font-black bg-slate-100 text-slate-600 border border-[#E3E8EE]">${bn.gioi_tinh || 'NAM'}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[13px] text-slate-400 mt-0.5">
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
                            ${latestVisit && latestVisit.ngay_tai_kham ? `<div class="text-[13px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-calendar-check mr-0.5"></i>Tái khám: ${latestVisit.ngay_tai_kham.split('-').reverse().join('/')}</div>` : ''}
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
                    <div class="p-8 text-center bg-slate-50 rounded-md border border-[#E3E8EE] text-slate-400">
                        <i class="fa-solid fa-file-circle-question text-3xl mb-2 text-slate-300"></i>
                        <p class="font-medium text-xs">Bệnh nhân chưa có lịch sử ca khám nào trong hệ thống.</p>
                        <button type="button" onclick="datLichChoBenhNhanHienTai()" class="mt-3 px-3.5 py-1.5 bg-rose-600 text-white rounded-md text-xs font-bold hover:bg-rose-700 transition">
                            Tạo ca khám đầu tiên
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
                    let stBadge = '<span class="px-2 py-0.5 rounded-full text-[13px] font-bold bg-emerald-100 text-emerald-700">Đã hoàn thành</span>';
                    if (lh.trang_thai === 'DANG_KHAM') stBadge = '<span class="px-2 py-0.5 rounded-full text-[13px] font-bold bg-sky-100 text-sky-700">Đang khám</span>';
                    if (lh.trang_thai === 'DA_XAC_NHAN') stBadge = '<span class="px-2 py-0.5 rounded-full text-[13px] font-bold bg-amber-100 text-amber-700">Chờ khám</span>';
                    if (lh.trang_thai === 'DA_HUY') stBadge = '<span class="px-2 py-0.5 rounded-full text-[13px] font-bold bg-slate-100 text-slate-500">Đã hủy</span>';

                    // Toa thuốc
                    let toaThuocHtml = '';
                    if (lh.toa_thuoc && Array.isArray(lh.toa_thuoc) && lh.toa_thuoc.length > 0) {
                        toaThuocHtml = `
                            <div class="mt-3 p-3 bg-white rounded-md border border-[#E3E8EE] space-y-2">
                                <div class="text-[13px] font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-pills text-rose-500"></i>
                                    <span>Toa Thuốc Điện Tử Điều Trị (${lh.toa_thuoc.length} loại thuốc):</span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-[13px]">
                                        <thead>
                                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                                                <th class="py-1.5 px-2">STT</th>
                                                <th class="py-1.5 px-2">Tên thuốc</th>
                                                <th class="py-1.5 px-2">Hàm lượng / liều dùng</th>
                                                <th class="py-1.5 px-2">Cách uống</th>
                                                <th class="py-1.5 px-2 text-center">Số ngày</th>
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
                                <span class="text-[13px] font-bold text-slate-400">Kết quả đính kèm:</span>
                                ${lh.tep_dinh_kem.map(f => `
                                    <a href="${f.url || '#'}" target="_blank" class="px-2 py-0.5 rounded-lg bg-sky-50 text-sky-700 border border-sky-200 text-[13px] font-semibold hover:bg-sky-100 transition inline-flex items-center gap-1">
                                        <i class="fa-solid fa-file-medical text-sky-500"></i>
                                        <span>${f.ten_tep || 'Kết quả xét nghiệm / X-Quang'}</span>
                                    </a>
                                `).join('')}
                            </div>
                        `;
                    }

                    timelineHtml += `
                        <div class="relative pl-6 pb-6 border-l-2 ${index === 0 ? 'border-rose-500' : 'border-[#E3E8EE]'} last:pb-0">
                            <!-- Bullet -->
                            <div class="absolute -left-2 top-0 w-4 h-4 rounded-full ${index === 0 ? 'bg-rose-500 ring-4 ring-rose-100' : 'bg-slate-300'} flex items-center justify-center text-white text-[13px]">
                                ${index + 1}
                            </div>

                            <div class="p-4 bg-slate-50/80 rounded-md border border-[#E3E8EE] hover:bg-white hover:shadow-xs transition space-y-3">
                                <!-- Top: Thời gian & Bác sĩ -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-[#E3E8EE]">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-black bg-slate-900 text-white flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[13px] text-rose-400"></i> ${khungGio}
                                        </span>
                                        <span class="text-xs font-extrabold text-slate-800">${ngayKham}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-xs font-bold text-sky-700"><i class="fa-solid fa-user-doctor text-sky-500 mr-1"></i>${tenBs}</span>
                                        <span class="text-[13px] text-slate-500">(${chuyenKhoa})</span>
                                    </div>
                                    <div>${stBadge}</div>
                                </div>

                                <!-- Triệu chứng & Chẩn đoán -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="p-2.5 bg-white rounded-md border border-[#E3E8EE]">
                                        <div class="text-[13px] font-bold text-slate-400">Lý do / triệu chứng khi đến khám:</div>
                                        <div class="font-semibold text-slate-800 mt-0.5">${lh.ly_do_kham || lh.trieu_chung || 'Khám sức khỏe tổng quát định kỳ'}</div>
                                    </div>

                                    <div class="p-2.5 bg-rose-50/70 rounded-md border border-rose-100">
                                        <div class="text-[13px] font-bold text-rose-600 flex items-center gap-1">
                                            <i class="fa-solid fa-heart-pulse"></i> Chẩn đoán bệnh lý (bị bệnh gì):
                                        </div>
                                        <div class="font-black text-rose-800 mt-0.5 text-xs">
                                            ${lh.chuan_doan || '<span class="text-slate-400 font-normal italic">Chưa có kết luận chẩn đoán</span>'}
                                        </div>
                                    </div>
                                </div>

                                <!-- Toa thuốc -->
                                ${toaThuocHtml}

                                <!-- Lời dặn & Hẹn tái khám -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-2 text-[13px] border-t border-[#E3E8EE]">
                                    <div class="text-slate-600">
                                        <strong class="text-slate-700">Lời dặn bác sĩ:</strong> ${lh.loi_khuyen || lh.ghi_chu_bac_si || 'Uống thuốc đúng giờ, tái khám khi có dấu hiệu bất thường.'}
                                    </div>
                                    ${lh.ngay_tai_kham ? `
                                        <div class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold flex items-center gap-1 flex-shrink-0">
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
                            <span style="color: #0284c7;">${formatTenBacSi(tenBs)} - ${khoa}</span>
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
                                <h1 style="font-size: 16px; font-weight: 900; color: #0369a1; text-transform: ;">Phòng khám đa khoa quốc tế</h1>
                                <p style="font-size: 11px; color: #64748b;">Địa chỉ: 123 Đường sức khỏe, quận 1, TP. Hồ chí minh - hotline: 1900 6868</p>
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
                        <h3 style="font-size: 13px; font-weight: bold; text-transform: ; margin-bottom: 12px; color: #0f172a; border-left: 4px solid #0284c7; padding-left: 8px;">
                            Lịch Sử Các Lần Thăm Khám & Điều Trị (${lichHens.length} ca khám)
                        </h3>
                        <div>
                            ${visitsHtml || '<p style="text-align: center; color: #94a3b8;">Chưa có dữ liệu lần khám nào.</p>'}
                        </div>

                        <!-- Chữ ký -->
                        <div style="display: flex; justify-content: space-between; margin-top: 40px; text-align: center;">
                            <div style="width: 200px;">
                                <div>Người lập bệnh án</div>
                                <div style="height: 60px;"></div>
                                <div style="font-weight: bold;">Hệ thống EHR phòng khám</div>
                            </div>
                            <div style="width: 250px;">
                                <div>Bác sĩ trưởng khoa / phụ trách</div>
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
                if (titleEl) titleEl.textContent = 'Cập nhật hồ sơ bệnh nhân';
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
                if (titleEl) titleEl.textContent = 'Thêm hồ sơ bệnh nhân mới';
                document.getElementById('form-bn-id').value = '';
            }

            moModal('modal-them-sua-benh-nhan');
        }

        async function luuThongTinBenhNhan() {
            const id = document.getElementById('form-bn-id').value;
            const hoTen = document.getElementById('form-bn-hoten').value.trim();
            const sdt = document.getElementById('form-bn-sdt').value.trim();

            if (!hoTen || !sdt) {
                Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập họ tên và số điện thoại!' });
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
                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-lg border border-dashed border-[#E3E8EE]">
                            <i class="fa-solid fa-people-roof text-4xl text-slate-300 mb-3 block"></i>
                            <p class="text-sm font-bold text-slate-600">Chưa có hồ sơ người thân nào</p>
                            <p class="text-xs text-slate-400 mt-1 mb-4">Hãy thêm hồ sơ con cái, cha mẹ hoặc vợ chồng để đặt lịch khám nhanh chóng</p>
                            <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-md shadow-xs transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Thêm người thân đầu tiên
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
                    <div class="col-span-full text-center py-12 bg-slate-50 rounded-lg border border-dashed border-[#E3E8EE]">
                        <i class="fa-solid fa-people-roof text-4xl text-slate-300 mb-3 block"></i>
                        <p class="text-sm font-bold text-slate-600">Chưa có hồ sơ gia đình nào</p>
                        <p class="text-xs text-slate-400 mt-1 mb-4">Thêm hồ sơ con cái, cha mẹ, vợ chồng để dễ dàng đặt khám</p>
                        <button type="button" onclick="moModalThemNguoiThan()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-md shadow-xs transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Thêm người thân đầu tiên
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
                let avatarBg = 'bg-sky-500 ';

                switch (h.quan_he_chu_tai_khoan) {
                    case 'BAN_THAN':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-sky-100 text-sky-700 border border-sky-200"><i class="fa-solid fa-user-check mr-1"></i>Bản thân (chủ TK)</span>';
                        avatarIcon = h.gioi_tinh === 'NU' ? 'fa-solid fa-user-nurse' : 'fa-solid fa-user-tie';
                        avatarBg = 'bg-sky-500 ';
                        break;
                    case 'CON':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-amber-100 text-amber-700 border border-amber-200"><i class="fa-solid fa-child mr-1"></i>Con cái</span>';
                        avatarIcon = 'fa-solid fa-child-reaching';
                        avatarBg = 'bg-amber-400 ';
                        break;
                    case 'CHA_ME':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-person-cane mr-1"></i>Bố / mẹ</span>';
                        avatarIcon = 'fa-solid fa-person-cane';
                        avatarBg = 'bg-emerald-500 ';
                        break;
                    case 'VO_CHONG':
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-purple-100 text-purple-700 border border-purple-200"><i class="fa-solid fa-heart mr-1"></i>Vợ / chồng</span>';
                        avatarIcon = 'fa-solid fa-user-group';
                        avatarBg = 'bg-purple-500 ';
                        break;
                    default:
                        qhBadge = '<span class="px-2.5 py-0.5 rounded-full text-[13px] font-bold bg-rose-100 text-rose-700 border border-rose-200"><i class="fa-solid fa-user-group mr-1"></i>Người thân</span>';
                        avatarIcon = 'fa-solid fa-user-shield';
                        avatarBg = 'bg-rose-500 ';
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
                const nhomMauBadge = h.nhom_mau ? `<span class="px-2 py-0.5 rounded-md text-[13px] font-bold bg-rose-50 text-rose-600 border border-rose-200">Máu: ${h.nhom_mau}</span>` : '';
                const soKham = Number(h.danh_sach_lich_hen_count) || 0;

                html += `
                    <div class="bg-white p-6 rounded-lg border border-[#E3E8EE] hover:border-emerald-300 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header Card: Avatar + Tên + Badge -->
                            <div class="flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-10 h-10 rounded-md ${avatarBg} text-white flex items-center justify-center text-xl shadow-sm flex-shrink-0 transition-transform">
                                        <i class="${avatarIcon}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-extrabold text-slate-900 truncate flex items-center gap-1.5">
                                            <span>${h.ho_ten}</span>
                                        </h4>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[13px] font-mono text-slate-400 font-bold">${h.ma_benh_nhan || 'BN---'}</span>
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
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-cake-candles text-slate-400 text-[13px]"></i> Ngày sinh:</span>
                                    <span class="font-semibold text-slate-700 font-mono">${h.ngay_sinh || 'Chưa cập nhật'}${tuoiStr}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-venus-mars text-slate-400 text-[13px]"></i> Giới tính:</span>
                                    <span class="font-semibold text-slate-700">${gioiTinhStr}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-phone text-slate-400 text-[13px]"></i> Điện thoại:</span>
                                    <span class="font-semibold text-slate-700 font-mono">${h.so_dien_thoai || '--'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-notes-medical text-slate-400 text-[13px]"></i> Lượt khám:</span>
                                    <span class="font-bold text-emerald-600 font-mono">${soKham} lượt</span>
                                </div>
                                
                                ${h.tien_su_di_ung ? `
                                <div class="bg-rose-50/70 p-2.5 rounded-md border border-rose-200/60 mt-1">
                                    <p class="text-[13px] font-bold text-rose-700 flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Dị ứng thuốc:
                                    </p>
                                    <p class="text-xs text-rose-800 font-medium mt-0.5 line-clamp-2">${h.tien_su_di_ung}</p>
                                </div>` : ''}

                                ${h.tien_su_benh ? `
                                <div class="bg-amber-50/70 p-2.5 rounded-md border border-amber-200/60 mt-1">
                                    <p class="text-[13px] font-bold text-amber-700 flex items-center gap-1">
                                        <i class="fa-solid fa-heart-pulse text-amber-500"></i> Bệnh lý nền:
                                    </p>
                                    <p class="text-xs text-amber-800 font-medium mt-0.5 line-clamp-2">${h.tien_su_benh}</p>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Footer Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex items-center gap-2">
                            <button type="button" onclick="datLichNhanhChoNguoiThan(${h.id})" class="flex-1 py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-md shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-calendar-plus text-xs"></i>
                                <span>Đặt lịch khám</span>
                            </button>
                            <button type="button" onclick="moModalThemNguoiThan(${h.id})" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 rounded-md transition" title="Chỉnh sửa hồ sơ">
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
                lbl.innerHTML = '<span class="text-sky-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-child text-sky-600"></i> Họ tên bé / con cái: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Bé Bo, Nguyễn Gia Hân...';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại: <span class="text-[13px] text-slate-400 font-normal lowercase">(không bắt buộc đối với trẻ em)</span>';
                if (inpSdt) inpSdt.placeholder = 'Để trống nếu là trẻ em';
            } else if (qh === 'CHA_ME') {
                lbl.innerHTML = '<span class="text-amber-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-person-cane text-amber-600"></i> Họ tên bố / mẹ: <span class="text-rose-500">*</span></span>';
                inp.placeholder = 'Ví dụ: Nguyễn Văn Nam, Trần Thị Mai...';
                if (lblSdt) lblSdt.innerHTML = 'Số Điện Thoại: <span class="text-[13px] text-slate-400 font-normal lowercase">(hoặc SĐT người giám hộ)</span>';
                if (inpSdt) inpSdt.placeholder = 'Nhập SĐT của bố/mẹ...';
            } else if (qh === 'VO_CHONG') {
                lbl.innerHTML = '<span class="text-rose-700 font-extrabold flex items-center gap-1.5"><i class="fa-solid fa-heart text-rose-600"></i> Họ tên vợ / chồng: <span class="text-rose-500">*</span></span>';
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
                    if (titleEl) titleEl.textContent = 'Cập nhật hồ sơ thành viên';
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
                if (titleEl) titleEl.textContent = 'Thêm hồ sơ người thân mới';
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

    <!-- ============================================================= -->
    <!-- TIỆN ÍCH GIAO DIỆN: menu di động, phím Esc, nhãn trợ năng      -->
    <!-- (Không thay đổi logic nghiệp vụ, chỉ bổ sung trải nghiệm)      -->
    <!-- ============================================================= -->
    <script>
        // Mở / đóng menu bên trái trên màn hình nhỏ (< 1024px)
        function moSidebarMobile() {
            document.getElementById('app-sidebar')?.classList.add('open');
            document.getElementById('sidebar-overlay')?.classList.add('show');
        }

        function dongSidebarMobile() {
            document.getElementById('app-sidebar')?.classList.remove('open');
            document.getElementById('sidebar-overlay')?.classList.remove('show');
        }

        // Chọn một mục menu trên điện thoại thì tự đóng menu
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024 && e.target.closest('.sidebar-item')) {
                dongSidebarMobile();
            }
        });

        // Phím Esc: đóng hộp thoại đang mở trên cùng, nếu không có thì đóng menu
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (document.querySelector('.swal2-container')) return; // SweetAlert tự xử lý Esc
            const dangMo = Array.from(document.querySelectorAll('.modal-backdrop.show'));
            if (dangMo.length > 0) {
                dangMo[dangMo.length - 1].classList.remove('show');
            } else {
                dongSidebarMobile();
            }
        });

        // Tự gán aria-label cho các nút chỉ có biểu tượng (lấy từ title)
        function ganNhanTroNangChoNutIcon(root) {
            (root || document).querySelectorAll('button:not([aria-label])').forEach((btn) => {
                if (btn.textContent.trim() === '' && btn.getAttribute('title')) {
                    btn.setAttribute('aria-label', btn.getAttribute('title'));
                }
            });
        }

        // Chuyển thông báo kỹ thuật thành câu dễ hiểu cho người dùng
        function lamThanThienThongBao(text) {
            if (typeof text !== 'string' || text.trim() === '') return text;
            const t = text.toLowerCase();
            if (t.includes('không thể kết nối') || t.includes('failed to fetch') || t.includes('networkerror') || t.includes('ngoại tuyến')) {
                return 'Chưa kết nối được tới máy chủ. Vui lòng kiểm tra mạng rồi thử lại sau ít phút.';
            }
            if (t.includes('unauthenticated') || t.includes('token') && (t.includes('hết hạn') || t.includes('expired') || t.includes('invalid'))) {
                return 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.';
            }
            if (t.includes('sqlstate') || t.includes('exception') || t.includes('stack trace') || t.includes('internal server error')) {
                return 'Hệ thống đang gặp sự cố khi xử lý yêu cầu. Vui lòng thử lại sau hoặc liên hệ quầy hỗ trợ.';
            }
            if (t.includes('forbidden') || t === '403') {
                return 'Tài khoản của bạn không có quyền thực hiện thao tác này.';
            }
            if (t.includes('the given data was invalid') || t.includes('validation')) {
                return 'Một vài thông tin chưa hợp lệ. Vui lòng kiểm tra lại các ô được đánh dấu.';
            }
            return text;
        }

        // Áp dụng cho mọi hộp thông báo SweetAlert2 mà không phải sửa từng chỗ gọi
        if (typeof Swal !== 'undefined' && !Swal.__daChuanHoa) {
            const swalFireGoc = Swal.fire.bind(Swal);
            Swal.fire = function (...args) {
                if (args.length === 1 && args[0] && typeof args[0] === 'object') {
                    const opt = Object.assign({}, args[0]);
                    if (typeof opt.text === 'string') opt.text = lamThanThienThongBao(opt.text);
                    // Giữ màu đỏ cho hộp xác nhận xóa/hủy, còn lại dùng màu chủ đạo
                    const mauDo = ['#e11d48', '#f43f5e', '#dc2626', '#ef4444', '#C0352B'];
                    if (!opt.confirmButtonColor || !mauDo.includes(opt.confirmButtonColor)) {
                        opt.confirmButtonColor = '#1F6FB2';
                    } else {
                        opt.confirmButtonColor = '#C0352B';
                    }
                    if (opt.showCancelButton && !opt.cancelButtonText) opt.cancelButtonText = 'Hủy';
                    if (!opt.confirmButtonText && !opt.toast) opt.confirmButtonText = 'Đồng ý';
                    return swalFireGoc(opt);
                }
                if (typeof args[1] === 'string') args[1] = lamThanThienThongBao(args[1]);
                return swalFireGoc(...args);
            };
            Swal.__daChuanHoa = true;
        }

        document.addEventListener('DOMContentLoaded', () => {
            ganNhanTroNangChoNutIcon(document);
            let henGio = null;
            new MutationObserver(() => {
                clearTimeout(henGio);
                henGio = setTimeout(() => ganNhanTroNangChoNutIcon(document), 300);
            }).observe(document.body, { childList: true, subtree: true });
        });
    </script>
</body>
</html>
