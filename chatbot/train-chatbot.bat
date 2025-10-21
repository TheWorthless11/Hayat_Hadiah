@echo off
echo ========================================
echo   Hayat Hadiah Chatbot - Training
echo ========================================
echo.

REM Activate virtual environment
echo [1/3] Activating virtual environment...
REM Use Python directly from venv to avoid activation issues
set PYTHON_EXE=C:\Users\mahhia\hayat_rasa\venv\Scripts\python.exe
set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH%

REM Navigate to chatbot directory
echo [2/3] Navigating to chatbot directory...
cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot

REM Train the model
echo [3/3] Training Rasa model (this may take 5-15 minutes)...
echo.
rasa train

echo.
echo ========================================
echo   Training Complete!
echo ========================================
echo.
echo Model saved in: models\
echo.
echo Next steps:
echo   1. Run: start-chatbot-servers.bat
echo   2. Visit: http://localhost:8000/chatbot
echo.
pause
