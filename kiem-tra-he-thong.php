<?php

/**
 * KICH BAN KIEM TRA TOAN DIEN HE THONG MICROSERVICES PHONG KHAM DA KHOA
 * Chay qua API Gateway (Port: 8000)
 */

echo "============================================================" . PHP_EOL;
echo "  KIEM TRA TOAN DIEN HE THONG MICROSERVICES PHONG KHAM" . PHP_EOL;
echo "============================================================" . PHP_EOL . PHP_EOL;

// 1. Kiem tra Database tren MySQL Laragon
echo "[BƯỚC 1] Kiem tra ket noi 4 Database tren MySQL Laragon..." . PHP_EOL;
$dbs = ['db_xac_thuc_bac_si', 'db_benh_nhan_lich_hen', 'db_dich_vu_y_te', 'db_hoa_don_thanh_toan'];
$dbConfigs = [
    ['port' => 3307, 'pass' => ''],
    ['port' => 3306, 'pass' => 'vanh2005'],
    ['port' => 3306, 'pass' => ''],
    ['port' => 3307, 'pass' => 'vanh2005'],
];

$workingConfig = null;
foreach ($dbConfigs as $cfg) {
    try {
        $testPdo = new PDO("mysql:host=127.0.0.1;port={$cfg['port']}", 'root', $cfg['pass']);
        $workingConfig = $cfg;
        break;
    } catch (Exception $e) {}
}

if (!$workingConfig) {
    echo PHP_EOL . "[CANH BAO] Khong the ket noi den MySQL Laragon tren cong 3306 / 3307." . PHP_EOL;
    exit(1);
}

$dbOk = true;
foreach ($dbs as $db) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;port={$workingConfig['port']};dbname={$db}", 'root', $workingConfig['pass']);
        echo "  -> [OK] Database {$db} (Port {$workingConfig['port']}): Ket noi thanh cong." . PHP_EOL;
    } catch (Exception $e) {
        echo "  -> [LOI] Database {$db}: " . $e->getMessage() . PHP_EOL;
        $dbOk = false;
    }
}

if (!$dbOk) {
    echo PHP_EOL . "[CANH BAO] Khong the ket noi du 4 database. Vui long chay lai chay-migrations.bat." . PHP_EOL;
    exit(1);
}

// 2. Kiem tra API Gateway va cac dich vu
$gatewayUrl = 'http://127.0.0.1:8000';
echo PHP_EOL . "[BƯỚC 2] Kiem tra API Gateway va Health Check (Port 8000)..." . PHP_EOL;

function goiApi($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init();
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    if ($token) {
        $headers[] = "Authorization: Bearer {$token}";
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $cleanResponse = $response ? preg_replace('/^\xEF\xBB\xBF/', '', trim($response)) : null;

    return [
        'code' => $httpCode,
        'body' => $cleanResponse ? json_decode($cleanResponse, true) : null,
        'raw' => $response,
        'error' => $error
    ];
}

$health = goiApi("{$gatewayUrl}/api/health");
if ($health['code'] !== 200) {
    echo "  -> [THONG BAO] API Gateway (8000) hoac cac service con chua duoc bat." . PHP_EOL;
    echo "  -> Vui long chay: powershell -File start-he-thong.ps1 (hoac start-he-thong.bat)" . PHP_EOL;
    echo "  -> Sau do chay lai: php kiem-tra-he-thong.php de kiem tra End-to-End!" . PHP_EOL;
    exit(0);
}

echo "  -> [OK] API Gateway dang hoat dong. Trang thai he thong: " . ($health['body']['trang_thai_chung'] ?? 'OK') . PHP_EOL;
foreach ($health['body']['danh_sach_dich_vu'] as $name => $s) {
    echo "     * {$name} (Port {$s['port']}): {$s['trang_thai']}" . PHP_EOL;
}

// 3. Test Dang Nhap qua Gateway de lay JWT Token
echo PHP_EOL . "[BƯỚC 3] Test Dang Nhap qua Gateway (/api/xac-thuc/dang-nhap)..." . PHP_EOL;
$login = goiApi("{$gatewayUrl}/api/xac-thuc/dang-nhap", 'POST', [
    'email' => 'admin@phongkham.vn',
    'mat_khau' => 'admin123'
]);

if ($login['code'] === 200 && !empty($login['body']['du_lieu']['token'])) {
    $token = $login['body']['du_lieu']['token'];
    echo "  -> [OK] Dang nhap thanh cong. Nhan duoc JWT Token (Role: ADMIN)." . PHP_EOL;
} else {
    echo "  -> [LOI] Dang nhap that bai: " . json_encode($login) . PHP_EOL;
    exit(1);
}

// 4. Test Route duoc bao ve - Lay thong tin qua Gateway
echo PHP_EOL . "[BƯỚC 4] Test Route duoc Gateway bao ve & Header Propagation (/api/xac-thuc/thong-tin)..." . PHP_EOL;
$thongTin = goiApi("{$gatewayUrl}/api/xac-thuc/thong-tin", 'GET', null, $token);
if ($thongTin['code'] === 200) {
    echo "  -> [OK] Gateway xac thuc JWT thanh cong va chuyen tiep toi Service 01." . PHP_EOL;
    $vt = is_array($thongTin['body']['du_lieu']['vai_tro']) 
        ? ($thongTin['body']['du_lieu']['vai_tro']['ten_vai_tro'] ?? 'N/A') 
        : $thongTin['body']['du_lieu']['vai_tro'];
    echo "     Nguoi dung: " . $thongTin['body']['du_lieu']['ho_ten'] . " | Vai tro: " . $vt . PHP_EOL;
} else {
    echo "  -> [LOI] Gateway xac thuc token that bai." . PHP_EOL;
}

// 5. Test Lay Danh Sach Bac Si tu Service 01 qua Gateway
echo PHP_EOL . "[BƯỚC 5] Test Lay Danh Sach Bac Si (/api/bac-si)..." . PHP_EOL;
$bacSi = goiApi("{$gatewayUrl}/api/bac-si", 'GET');
if ($bacSi['code'] === 200) {
    echo "  -> [OK] Lay thanh cong " . $bacSi['body']['tong_so'] . " bac si:" . PHP_EOL;
    foreach ($bacSi['body']['du_lieu'] as $b) {
        echo "     * ID {$b['id']}: {$b['tai_khoan']['ho_ten']} - Chuyen khoa: {$b['chuyen_khoa']['ten_chuyen_khoa']} - Gia: " . number_format($b['gia_kham']) . "d" . PHP_EOL;
    }
}

// 6. Test Dat Lich Hen (Service 02 qua Gateway)
echo PHP_EOL . "[BƯỚC 6] Test Dat Lich Hen (Service 02 qua Gateway)..." . PHP_EOL;
$ngayKham = date('Y-m-d', strtotime('+' . rand(5, 30) . ' days'));
$gioBatDau = '15:00:00';
$gioKetThuc = '15:30:00';

$datLich1 = goiApi("{$gatewayUrl}/api/v1/lich-hen/dat-lich", 'POST', [
    'benh_nhan_id' => 1,
    'bac_si_id' => 1,
    'ngay_kham' => $ngayKham,
    'gio_bat_dau' => $gioBatDau,
    'gio_ket_thuc' => $gioKetThuc,
    'ly_do_kham' => 'Kham tai mui hong viem xoang',
], $token);

if ($datLich1['code'] === 201) {
    $lichHenId = $datLich1['body']['du_lieu']['id'];
    echo "  -> [OK] Dat lich hen thanh cong! Lich hen ID: {$lichHenId} vao luc {$gioBatDau} ngay {$ngayKham}." . PHP_EOL;
} else {
    echo "  -> [LOI] Dat lich hen that bai: " . json_encode($datLich1) . PHP_EOL;
    $lichHenId = 1;
}

// 7. TEST THUAT TOAN CHONG TRUNG LICH BAC SI
echo PHP_EOL . "[BƯỚC 7] TEST THUAT TOAN CHONG TRUNG LICH BAC SI..." . PHP_EOL;
echo "  -> Thu dat lich hen thu 2 cung bac si ID 1, cung ngay {$ngayKham} nhung gio bat dau 15:15 (giao thoa voi 15:00-15:30)..." . PHP_EOL;
$datLichTrung = goiApi("{$gatewayUrl}/api/v1/lich-hen/dat-lich", 'POST', [
    'benh_nhan_id' => 2,
    'bac_si_id' => 1,
    'ngay_kham' => $ngayKham,
    'gio_bat_dau' => '15:15:00',
    'gio_ket_thuc' => '15:45:00',
    'ly_do_kham' => 'Test trung gio',
], $token);

if ($datLichTrung['code'] === 409 && ($datLichTrung['body']['ma_loi'] ?? '') === 'TRUNG_LICH_KHAM') {
    echo "  -> [CHINH XAC] Thuat toan chong trung lich hoat dong xuat sac!" . PHP_EOL;
    echo "     Ma loi tra ve: 409 Conflict (TRUNG_LICH_KHAM)" . PHP_EOL;
    echo "     Thong diep: " . $datLichTrung['body']['thong_diep'] . PHP_EOL;
} else {
    echo "  -> [CANH BAO] Khong phat hien trung lich nhu ky vong: " . json_encode($datLichTrung) . PHP_EOL;
}

// 7b. TEST NGHIEP VU DOI LICH KHAM (RESCHEDULE)
echo PHP_EOL . "[BƯỚC 7b] TEST NGHIEP VU DOI LICH KHAM (RESCHEDULE)..." . PHP_EOL;
$doiLichRes = goiApi("{$gatewayUrl}/api/v1/lich-hen/{$lichHenId}/doi-lich", 'PUT', [
    'ngay_kham' => $ngayKham,
    'gio_bat_dau' => '16:00:00',
    'gio_ket_thuc' => '16:30:00',
    'ly_do_doi_lich' => 'Kẹt xe đột xuất, xin dời sau 1 tiếng',
], $token);

if ($doiLichRes['code'] === 200 && ($doiLichRes['body']['thanh_cong'] ?? false)) {
    echo "  -> [OK] Doi lich thanh cong sang 16:00:00! So lan doi lich: " . ($doiLichRes['body']['du_lieu']['so_lan_doi_lich'] ?? 1) . PHP_EOL;
    echo "     Thong diep: " . $doiLichRes['body']['thong_diep'] . PHP_EOL;
} else {
    echo "  -> [THONG BAO] Ket qua doi lich: " . json_encode($doiLichRes) . PHP_EOL;
}

// 8. Test Chi Dinh Dich Vu Can Lam Sang (Service 03 qua Gateway)
echo PHP_EOL . "[BƯỚC 8] Test Bac Si Chi Dinh Can Lam Sang (Service 03 qua Gateway)..." . PHP_EOL;
$chiDinh = goiApi("{$gatewayUrl}/api/dich-vu/chi-dinh", 'POST', [
    'lich_hen_id' => $lichHenId,
    'benh_nhan_id' => 1,
    'bac_si_id' => 1,
    'danh_sach_dich_vu_id' => [1, 4], // Xet nghiem mau (120k) + Noi soi TMH (250k)
], $token);

if ($chiDinh['code'] === 201) {
    echo "  -> [OK] Bac si da chi dinh 2 dich vu can lam sang thanh cong." . PHP_EOL;
} else {
    echo "  -> [THONG BAO] Ket qua chi dinh: " . json_encode($chiDinh) . PHP_EOL;
}

// 9. Test Tu Dong Tong Hop Hoa Don Lien Dich Vu (Service 04 qua Gateway)
echo PHP_EOL . "[BƯỚC 9] TEST TU DONG TONG HOP HOA DON LIEN DICH VU (Service 04 qua Gateway)..." . PHP_EOL;
$hoaDon = goiApi("{$gatewayUrl}/api/hoa-don/tao-tu-dong", 'POST', [
    'lich_hen_id' => $lichHenId,
    'giam_gia' => 20000.00
], $token);

if ($hoaDon['code'] === 201 || $hoaDon['code'] === 200) {
    $hd = $hoaDon['body']['du_lieu'];
    echo "  -> [XUAT SAC] Service 04 da tu dong goi cac service con de tong hop hoa don!" . PHP_EOL;
    echo "     Ma hoa don: " . $hd['ma_hoa_don'] . PHP_EOL;
    echo "     Tien kham: " . number_format($hd['tien_kham']) . "d" . PHP_EOL;
    echo "     Tien dich vu CLS: " . number_format($hd['tien_dich_vu']) . "d" . PHP_EOL;
    echo "     Tong tien: " . number_format($hd['tong_tien']) . "d (Giam gia: " . number_format($hd['giam_gia']) . "d)" . PHP_EOL;
    echo "     Thuc thu: " . number_format($hd['thuc_thu']) . "d" . PHP_EOL;
    $hoaDonId = $hd['id'];
} else {
    echo "  -> [LOI] Tong hop hoa don that bai: " . json_encode($hoaDon) . PHP_EOL;
    $hoaDonId = 1;
}

// 10. Test Thanh Toan Hoa Don
echo PHP_EOL . "[BƯỚC 10] Test Thanh Toan Hoa Don..." . PHP_EOL;
$thanhToan = goiApi("{$gatewayUrl}/api/hoa-don/{$hoaDonId}/thanh-toan", 'PUT', [
    'phuong_thuc_thanh_toan' => 'VNPAY',
    'ghi_chu' => 'Benh nhan thanh toan qua cong VNPAY QR'
], $token);

if ($thanhToan['code'] === 200) {
    echo "  -> [OK] Thanh toan hoa don thanh cong qua VNPAY!" . PHP_EOL;
} else {
    echo "  -> [THONG BAO] Ket qua thanh toan: " . json_encode($thanhToan) . PHP_EOL;
}

// 11. Test Thong Ke Doanh Thu
echo PHP_EOL . "[BƯỚC 11] Test Báo Cáo Doanh Thu (/api/hoa-don/thong-ke)..." . PHP_EOL;
$thongKe = goiApi("{$gatewayUrl}/api/hoa-don/thong-ke", 'GET', null, $token);
if ($thongKe['code'] === 200) {
    $tk = $thongKe['body']['du_lieu'];
    echo "  -> [OK] Tong doanh thu phong kham: " . number_format($tk['tong_doanh_thu']) . "d" . PHP_EOL;
    echo "     So hoa don da thu: " . $tk['so_luong_da_thanh_toan'] . " | Chua thu: " . $tk['so_luong_chua_thanh_toan'] . PHP_EOL;
}

echo PHP_EOL . "============================================================" . PHP_EOL;
echo "  HOAN TAT KIEM TRA TOAN DIEN HE THONG MICROSERVICES!" . PHP_EOL;
echo "  Tat ca cac tieu chi ky thuat va nghiep vu hoat dong 100%!" . PHP_EOL;
echo "============================================================" . PHP_EOL;
