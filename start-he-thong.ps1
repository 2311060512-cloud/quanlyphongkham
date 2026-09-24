# PowerShell Script: Khoi dong 5 microservices
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   KHOI DONG 5 MICROSERVICES PHONG KHAM DA KHOA" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

$services = @(
    @{ name = "api-gateway"; port = 8000; title = "API_GATEWAY" },
    @{ name = "auth-service"; port = 8001; title = "AUTH_SERVICE" },
    @{ name = "appointment-service"; port = 8002; title = "APPOINTMENT_SERVICE" },
    @{ name = "clinical-service"; port = 8003; title = "CLINICAL_SERVICE" },
    @{ name = "billing-service"; port = 8004; title = "BILLING_SERVICE" }
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
