@echo off
echo ========================================
echo   Hayat Hadiah - Starting All Servers
echo ========================================
echo.

echo This will open 3 windows:
echo   1. Laravel Server (port 8000)
echo   2. Rasa Server (port 5005)
echo   3. Rasa Action Server (port 5055)
echo.
echo Press any key to continue...
pause > nul

REM Start Laravel Server
echo [1/3] Starting Laravel server...
start "Laravel Server" cmd /k "cd /d c:\xampp\htdocs\Hayat_Hadi'ah && php artisan serve"

REM Wait a bit
timeout /t 2 /nobreak > nul

REM Start Rasa Server
echo [2/3] Starting Rasa server...
start "Rasa Server" cmd /k "set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH% && cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot && rasa run --enable-api --cors "*""

REM Wait a bit
timeout /t 2 /nobreak > nul

REM Start Rasa Action Server
echo [3/3] Starting Rasa action server...
start "Rasa Action Server" cmd /k "set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH% && cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot && rasa run actions"

echo.
echo ========================================
echo   All servers starting!
echo ========================================
echo.
echo Check the 3 opened windows for status.
echo.
echo Your chatbot will be available at:
echo   http://localhost:8000/chatbot
echo.
echo To stop servers, close the 3 windows.
echo.
pause
