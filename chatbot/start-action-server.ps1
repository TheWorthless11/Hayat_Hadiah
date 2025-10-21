# Starting Rasa Action Server (PowerShell Version)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Starting Rasa Action Server" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Set execution policy for this session only
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force

# Set PATH to include venv
$env:PATH = "C:\Users\mahhia\hayat_rasa\venv\Scripts;$env:PATH"

# Navigate to chatbot directory (using Set-Location with -LiteralPath to handle apostrophe)
Set-Location -LiteralPath "c:\xampp\htdocs\Hayat_Hadi'ah\chatbot"

# Start action server
Write-Host "Starting Rasa action server on http://localhost:5055" -ForegroundColor Green
Write-Host ""
Write-Host "Keep this window open!" -ForegroundColor Yellow
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

& rasa run actions
