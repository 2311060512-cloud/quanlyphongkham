@echo off
chcp 65001 > nul
echo ============================================================
echo   CHAY MIGRATIONS VA SEEDERS CHO 4 MICROSERVICES PHONG KHAM
echo   (MySQL Laragon Port 3307)
echo ============================================================

echo.
echo [1/5] Khoi tao co so du lieu tren MySQL 3307...
php khoi-tao-database.php

echo.
echo [2/5] Migration auth-service (db_xac_thuc_bac_si)...
php auth-service\artisan migrate:fresh --seed --force

echo.
echo [3/5] Migration appointment-service (db_benh_nhan_lich_hen)...
php appointment-service\artisan migrate:fresh --seed --force

echo.
echo [4/5] Migration clinical-service (db_dich_vu_y_te)...
php clinical-service\artisan migrate:fresh --seed --force

echo.
echo [5/5] Migration billing-service (db_hoa_don_thanh_toan)...
php billing-service\artisan migrate:fresh --seed --force

echo.
echo ============================================================
echo   HOAN TAT MIGRATIONS VA SEED TOAN BO CO SO DU LIEU!
echo ============================================================
pause
