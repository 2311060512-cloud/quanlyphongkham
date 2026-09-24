@echo off
chcp 65001 > nul
echo ============================================================
echo   KHOI DONG 5 MICROSERVICES PHONG KHAM DA KHOA
echo ============================================================

echo.
echo [1/5] Khoi dong api-gateway (Port: 8000)...
start "API_GATEWAY_8000" cmd /k "title API_GATEWAY:8000 && cd /d %~dp0api-gateway && php -S 127.0.0.1:8000 server.php"

echo [2/5] Khoi dong auth-service (Port: 8001)...
start "AUTH_SERVICE_8001" cmd /k "title AUTH_SERVICE:8001 && cd /d %~dp0auth-service && php -S 127.0.0.1:8001 server.php"

echo [3/5] Khoi dong appointment-service (Port: 8002)...
start "APPOINTMENT_SERVICE_8002" cmd /k "title APPOINTMENT_SERVICE:8002 && cd /d %~dp0appointment-service && php -S 127.0.0.1:8002 server.php"

echo [4/5] Khoi dong clinical-service (Port: 8003)...
start "CLINICAL_SERVICE_8003" cmd /k "title CLINICAL_SERVICE:8003 && cd /d %~dp0clinical-service && php -S 127.0.0.1:8003 server.php"

echo [5/5] Khoi dong billing-service (Port: 8004)...
start "BILLING_SERVICE_8004" cmd /k "title BILLING_SERVICE:8004 && cd /d %~dp0billing-service && php -S 127.0.0.1:8004 server.php"

echo.
echo ============================================================
echo   DA KHOI DONG 5 DICH VU THANH CONG!
echo   -- API Gateway Dashboard: http://127.0.0.1:8000
echo   -- De dung tat ca: Chay file stop-he-thong.bat
echo ============================================================
timeout /t 5
