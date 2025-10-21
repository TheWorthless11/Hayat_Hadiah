# Rasa Shell - Test Your Chatbot (PowerShell Version)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Rasa Shell - Test Your Chatbot" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Set execution policy for this session only
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force

# Set PATH to include venv
$env:PATH = "C:\Users\mahhia\hayat_rasa\venv\Scripts;$env:PATH"

# Navigate to chatbot directory
Set-Location -LiteralPath "c:\xampp\htdocs\Hayat_Hadi'ah\chatbot"

# Start Rasa shell
Write-Host "Starting Rasa interactive shell..." -ForegroundColor Green
Write-Host ""
Write-Host "You can test your chatbot here!" -ForegroundColor Yellow
Write-Host ""
Write-Host "Try these examples:" -ForegroundColor Cyan
Write-Host "  - Hello" -ForegroundColor White
Write-Host "  - What are prayer times today?" -ForegroundColor White
Write-Host "  - Show me a Quran verse" -ForegroundColor White
Write-Host "  - I want to donate" -ForegroundColor White
Write-Host ""
Write-Host "Press Ctrl+C to exit" -ForegroundColor Yellow
Write-Host ""

& rasa shell
