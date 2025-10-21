# Hayat Hadiah - Starting All Servers (PowerShell Version)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Hayat Hadiah - Starting All Servers" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "This will open 3 windows:" -ForegroundColor Yellow
Write-Host "  1. Laravel Server (port 8000)" -ForegroundColor White
Write-Host "  2. Rasa Server (port 5005)" -ForegroundColor White
Write-Host "  3. Rasa Action Server (port 5055)" -ForegroundColor White
Write-Host ""

# Set execution policy for this session
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force

# Start Laravel Server
Write-Host "[1/3] Starting Laravel server..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'c:\xampp\htdocs\Hayat_Hadi''ah'; php artisan serve"

# Wait a bit
Start-Sleep -Seconds 2

# Start Rasa Server
Write-Host "[2/3] Starting Rasa server..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass; & 'C:\Users\mahhia\hayat_rasa\venv\Scripts\Activate.ps1'; cd 'c:\xampp\htdocs\Hayat_Hadi''ah\chatbot'; rasa run --enable-api --cors '*'"

# Wait a bit
Start-Sleep -Seconds 2

# Start Rasa Action Server
Write-Host "[3/3] Starting Rasa action server..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass; & 'C:\Users\mahhia\hayat_rasa\venv\Scripts\Activate.ps1'; cd 'c:\xampp\htdocs\Hayat_Hadi''ah\chatbot'; rasa run actions"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  All servers starting!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Check the 3 opened windows for status." -ForegroundColor White
Write-Host ""
Write-Host "Your chatbot will be available at:" -ForegroundColor Yellow
Write-Host "  http://localhost:8000/chatbot" -ForegroundColor Cyan
Write-Host ""
Write-Host "To stop servers, close the 3 windows." -ForegroundColor White
Write-Host ""
Read-Host "Press Enter to exit"
