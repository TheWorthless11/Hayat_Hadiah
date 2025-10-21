@echo off
echo ========================================
echo   Starting Rasa Server
echo ========================================
echo.

REM Use Python directly from venv to avoid activation issues
set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH%

REM Navigate to chatbot directory
cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot

REM Start Rasa server
echo Starting Rasa server on http://localhost:5005
echo.
echo Keep this window open!
echo Press Ctrl+C to stop the server
echo.

rasa run --enable-api --cors "*"
