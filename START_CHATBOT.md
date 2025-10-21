# 🚀 Quick Start Guide - Hayat Hadiah Chatbot

## Daily Startup Commands

### PowerShell (Recommended)

**Copy and paste these TWO commands:**

```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
```

Then:

```powershell
& 'c:\xampp\htdocs\Hayat_Hadi''ah\start-all-servers.ps1'
```

---

### CMD (Alternative)

**Just run this ONE command:**

```cmd
c:\xampp\htdocs\Hayat_Hadi'ah\start-all-servers.bat
```

---

## What This Does

Opens 3 terminal windows:
1. ✅ **Laravel Server** (http://localhost:8000)
2. ✅ **Rasa Server** (http://localhost:5005)
3. ✅ **Action Server** (http://localhost:5055)

**Keep all 3 windows open while working!**

---

## Access Your Chatbot

After starting servers, open browser:

- **Full Chatbot Page:** http://localhost:8000/chatbot
- **Main Website:** http://localhost:8000 (chat widget in bottom-right)

---

## To Stop

Press `Ctrl+C` in each terminal window, or just close them.

---

## Troubleshooting

### If PowerShell gives execution policy error:
Always run this first:
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
```

### If servers don't start:
1. Make sure XAMPP MySQL is running
2. Close any stuck terminal windows
3. Try again

### If chatbot doesn't respond:
Make sure all 3 servers are running (check terminal windows)

---

## Training the Chatbot (After Making Changes)

**CMD:**
```cmd
c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\train-chatbot.bat
```

**PowerShell:**
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
& 'c:\xampp\htdocs\Hayat_Hadi''ah\chatbot\train-chatbot.ps1'
```

---

## Individual Server Commands

### Start Laravel Only:
```cmd
cd /d c:\xampp\htdocs\Hayat_Hadi'ah
php artisan serve
```

### Start Rasa Server Only:
```cmd
c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\start-rasa-server.bat
```

### Start Action Server Only:
```cmd
c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\start-action-server.bat
```

---

## Testing in Terminal (Optional)

**CMD:**
```cmd
c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\test-chatbot.bat
```

**PowerShell:**
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
& 'c:\xampp\htdocs\Hayat_Hadi''ah\chatbot\test-chatbot.ps1'
```

---

## 💡 Pro Tip: Create Desktop Shortcut

1. Right-click Desktop → New → Shortcut
2. Location: `c:\xampp\htdocs\Hayat_Hadi'ah\start-all-servers.bat`
3. Name: "Start Hayat Hadiah Chatbot"
4. Click Finish
5. **Double-click to start everything instantly!**

---

## Quick Reference

| Action | Command |
|--------|---------|
| Start Everything | `start-all-servers.bat` or `.ps1` |
| Train Chatbot | `chatbot\train-chatbot.bat` |
| Test in Terminal | `chatbot\test-chatbot.bat` |
| Web Interface | http://localhost:8000/chatbot |

---

**🌙 JazakAllah Khair for using Hayat Hadiah!**
