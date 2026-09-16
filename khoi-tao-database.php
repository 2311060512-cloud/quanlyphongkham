<?php

$host = '127.0.0.1';
$port = 3307;
$user = 'root';
$pass = '';

$databases = [
    'db_xac_thuc_bac_si',
    'db_benh_nhan_lich_hen',
    'db_dich_vu_y_te',
    'db_hoa_don_thanh_toan',
];

try {
    $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "=== KHOI TAO CO SO DU LIEU PHONG KHAM DA KHOA (PORT 3307) ===" . PHP_EOL;

    foreach ($databases as $db) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "[OK] Co so du lieu: {$db} da duoc tao / san sang." . PHP_EOL;
    }

    echo "=== HOAN TAT KHOI TAO 4 DATABASES THANH CONG ===" . PHP_EOL;
} catch (Exception $e) {
    echo "[ERROR] Loi khoi tao database: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
