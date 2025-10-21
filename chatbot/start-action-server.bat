@echo off
echo ========================================
echo   Starting Rasa Action Server
echo ========================================
echo.

REM Use Python directly from venv to avoid activation issues
set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH%

REM Navigate to chatbot directory
cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot

REM Start action server
echo Starting Rasa action server on http://localhost:5055
echo.
echo Keep this window open!
echo Press Ctrl+C to stop the server
echo.

rasa run actions
