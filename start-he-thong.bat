@echo off
chcp 65001 > nul
echo ============================================================
echo   KHOI DONG 5 MICROSERVICES PHONG KHAM DA KHOA
echo ============================================================

echo.
echo [1/5] Khoi dong 00_api_gateway (Port: 8000)...
start "00_API_GATEWAY_8000" cmd /k "title 00_API_GATEWAY:8000 && cd /d %~dp000_api_gateway && php -S 127.0.0.1:8000 server.php"

echo [2/5] Khoi dong 01_dich_vu_xac_thuc_bac_si (Port: 8001)...
start "01_XAC_THUC_BAC_SI_8001" cmd /k "title 01_XAC_THUC:8001 && cd /d %~dp001_dich_vu_xac_thuc_bac_si && php -S 127.0.0.1:8001 server.php"

echo [3/5] Khoi dong 02_dich_vu_benh_nhan_lich_hen (Port: 8002)...
start "02_BENH_NHAN_LICH_HEN_8002" cmd /k "title 02_LICH_HEN:8002 && cd /d %~dp002_dich_vu_benh_nhan_lich_hen && php -S 127.0.0.1:8002 server.php"

echo [4/5] Khoi dong 03_dich_vu_y_te_can_lam_sang (Port: 8003)...
start "03_Y_TE_CAN_LAM_SANG_8003" cmd /k "title 03_Y_TE:8003 && cd /d %~dp003_dich_vu_y_te_can_lam_sang && php -S 127.0.0.1:8003 server.php"

echo [5/5] Khoi dong 04_dich_vu_hoa_don_thanh_toan (Port: 8004)...
start "04_HOA_DON_THANH_TOAN_8004" cmd /k "title 04_HOA_DON:8004 && cd /d %~dp004_dich_vu_hoa_don_thanh_toan && php -S 127.0.0.1:8004 server.php"

echo.
echo ============================================================
echo   DA KHOI DONG 5 DICH VU THANH CONG!
echo   -> API Gateway Dashboard: http://127.0.0.1:8000
echo   -> De dung tat ca: Chay file stop-he-thong.bat
echo ============================================================
timeout /t 5
