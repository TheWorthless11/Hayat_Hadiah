@echo off
echo ========================================
echo   Rasa Shell - Test Your Chatbot
echo ========================================
echo.

REM Use Python directly from venv to avoid activation issues
set PATH=C:\Users\mahhia\hayat_rasa\venv\Scripts;%PATH%

REM Navigate to chatbot directory
cd /d c:\xampp\htdocs\Hayat_Hadi'ah\chatbot

REM Start Rasa shell
echo Starting Rasa interactive shell...
echo.
echo You can test your chatbot here!
echo.
echo Try these examples:
echo   - Hello
echo   - What are prayer times today?
echo   - Show me a Quran verse
echo   - I want to donate
echo.
echo Press Ctrl+C to exit
echo.

rasa shell
