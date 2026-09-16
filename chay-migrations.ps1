# PowerShell Script: Chay migrations va seeders cho 4 microservices
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   CHAY MIGRATIONS VA SEEDERS CHO 4 MICROSERVICES PHONG KHAM" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

Write-Host "`n[1/5] Khoi tao co so du lieu tren MySQL 3307..." -ForegroundColor Yellow
php khoi-tao-database.php

$services = @(
    @{ name = "01_dich_vu_xac_thuc_bac_si"; db = "db_xac_thuc_bac_si" },
    @{ name = "02_dich_vu_benh_nhan_lich_hen"; db = "db_benh_nhan_lich_hen" },
    @{ name = "03_dich_vu_y_te_can_lam_sang"; db = "db_dich_vu_y_te" },
    @{ name = "04_dich_vu_hoa_don_thanh_toan"; db = "db_hoa_don_thanh_toan" }
)

$step = 2
foreach ($s in $services) {
    Write-Host "`n[$step/5] Migration & Seed cho $($s.name) ($($s.db))..." -ForegroundColor Yellow
    php "$($s.name)\artisan" migrate:fresh --seed --force
    $step++
}

Write-Host "`n============================================================" -ForegroundColor Green
Write-Host "   HOAN TAT MIGRATIONS VA SEED TOAN BO 4 DICH VU!" -ForegroundColor Green
Write-Host "============================================================" -ForegroundColor Green
