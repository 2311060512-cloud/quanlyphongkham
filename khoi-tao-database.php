<?php

$host = '127.0.0.1';
$user = 'root';
$databases = [
    'db_xac_thuc_bac_si',
    'db_benh_nhan_lich_hen',
    'db_dich_vu_y_te',
    'db_hoa_don_thanh_toan',
];

$configs = [
    ['port' => 3307, 'pass' => ''],
    ['port' => 3306, 'pass' => 'vanh2005'],
    ['port' => 3306, 'pass' => ''],
    ['port' => 3307, 'pass' => 'vanh2005'],
];

$pdo = null;
$connectedPort = null;
$connectedPass = null;

foreach ($configs as $cfg) {
    try {
        $pdo = new PDO("mysql:host={$host};port={$cfg['port']}", $user, $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $connectedPort = $cfg['port'];
        $connectedPass = $cfg['pass'];
        break;
    } catch (Exception $e) {
        // try next
    }
}

if (!$pdo) {
    echo "[ERROR] Khong the ket noi den MySQL tren cac cong 3306 / 3307. Vui long kiem tra Laragon MySQL." . PHP_EOL;
    exit(1);
}

echo "=== KHOI TAO CO SO DU LIEU PHONG KHAM DA KHOA (PORT {$connectedPort}) ===" . PHP_EOL;

foreach ($databases as $db) {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "[OK] Co so du lieu: {$db} da duoc tao / san sang." . PHP_EOL;
}

echo "=== HOAN TAT KHOI TAO 4 DATABASES THANH CONG ===" . PHP_EOL;
