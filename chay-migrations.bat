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
echo [2/5] Migration Service 01 (db_xac_thuc_bac_si)...
php 01_dich_vu_xac_thuc_bac_si\artisan migrate:fresh --seed --force

echo.
echo [3/5] Migration Service 02 (db_benh_nhan_lich_hen)...
php 02_dich_vu_benh_nhan_lich_hen\artisan migrate:fresh --seed --force

echo.
echo [4/5] Migration Service 03 (db_dich_vu_y_te)...
php 03_dich_vu_y_te_can_lam_sang\artisan migrate:fresh --seed --force

echo.
echo [5/5] Migration Service 04 (db_hoa_don_thanh_toan)...
php 04_dich_vu_hoa_don_thanh_toan\artisan migrate:fresh --seed --force

echo.
echo ============================================================
echo   HOAN TAT MIGRATIONS VA SEED TOAN BO CO SO DU LIEU!
echo ============================================================
pause
