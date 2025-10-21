# Hayat Hadiah Chatbot - Training (PowerShell Version)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Hayat Hadiah Chatbot - Training" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Set execution policy for this session only
Write-Host "[0/4] Setting execution policy..." -ForegroundColor Yellow
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force

# Set PATH to include venv
Write-Host "[1/4] Setting up Python environment..." -ForegroundColor Yellow
$env:PATH = "C:\Users\mahhia\hayat_rasa\venv\Scripts;$env:PATH"

# Navigate to chatbot directory
Write-Host "[2/4] Navigating to chatbot directory..." -ForegroundColor Yellow
Set-Location -LiteralPath "c:\xampp\htdocs\Hayat_Hadi'ah\chatbot"

# Train the model
Write-Host "[3/4] Training Rasa model (this may take 5-15 minutes)..." -ForegroundColor Yellow
Write-Host ""
& rasa train

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Training Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Model saved in: models\" -ForegroundColor White
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Run: .\start-all-servers.ps1" -ForegroundColor White
Write-Host "  2. Visit: http://localhost:8000/chatbot" -ForegroundColor White
Write-Host ""
Read-Host "Press Enter to exit"
