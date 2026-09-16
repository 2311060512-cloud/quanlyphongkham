# PowerShell Script: Khoi dong 5 microservices
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   KHOI DONG 5 MICROSERVICES PHONG KHAM DA KHOA" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

$services = @(
    @{ name = "00_api_gateway"; port = 8000; title = "00_API_GATEWAY" },
    @{ name = "01_dich_vu_xac_thuc_bac_si"; port = 8001; title = "01_XAC_THUC_BAC_SI" },
    @{ name = "02_dich_vu_benh_nhan_lich_hen"; port = 8002; title = "02_BENH_NHAN_LICH_HEN" },
    @{ name = "03_dich_vu_y_te_can_lam_sang"; port = 8003; title = "03_Y_TE_CAN_LAM_SANG" },
    @{ name = "04_dich_vu_hoa_don_thanh_toan"; port = 8004; title = "04_HOA_DON_THANH_TOAN" }
)

foreach ($s in $services) {
    Write-Host "Dang bat $($s.name) tren port $($s.port)..." -ForegroundColor Yellow
    $dir = Join-Path $PSScriptRoot $s.name
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "`$host.ui.RawUI.WindowTitle='$($s.title):$($s.port)'; Set-Location '$dir'; php -S 127.0.0.1:$($s.port) server.php"
}

Write-Host "`n============================================================" -ForegroundColor Green
Write-Host "   DA KHOI DONG 5 DICH VU TREN 5 CUA SO RIENG BIET!" -ForegroundColor Green
Write-Host "   -> API Gateway Dashboard: http://127.0.0.1:8000" -ForegroundColor Green
Write-Host "============================================================" -ForegroundColor Green
