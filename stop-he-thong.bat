@echo off
chcp 65001 > nul
echo ============================================================
echo   DUNG TAT CA TIEN TRINH MICROSERVICES (8000, 8001-8004)
echo ============================================================

powershell -Command "foreach ($port in 8000..8004) { $conns = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue; foreach ($c in $conns) { Stop-Process -Id $c.OwningProcess -Force -ErrorAction SilentlyContinue; Write-Host ('[OK] Da dung tien trinh tren port ' + $port) -ForegroundColor Green } }"

echo.
echo Da dung toan bo cac service.
pause
