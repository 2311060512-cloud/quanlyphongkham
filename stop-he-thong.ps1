# PowerShell Script: Dung tat ca microservices
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   DUNG TAT CA TIEN TRINH MICROSERVICES (8000, 8001-8004)" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

$ports = 8000..8004
$stoppedCount = 0

foreach ($port in $ports) {
    $conns = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
    if ($conns) {
        foreach ($c in $conns) {
            $pidToKill = $c.OwningProcess
            try {
                Stop-Process -Id $pidToKill -Force -ErrorAction Stop
                Write-Host "[OK] Da dung tien trinh (PID: $pidToKill) dang lang nghe tai Port $port" -ForegroundColor Green
                $stoppedCount++
            } catch {
                Write-Host "[WARN] Khong the dung tien trinh PID $($pidToKill): $($_.Exception.Message)" -ForegroundColor Yellow
            }
        }
    } else {
        Write-Host "[INFO] Khong co tien trinh nao tren Port $port" -ForegroundColor DarkGray
    }
}

Write-Host "`nHoan tat. Da dung $stoppedCount tien trinh." -ForegroundColor Green
