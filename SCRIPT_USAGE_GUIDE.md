# 🔧 Script Usage Guide - CMD vs PowerShell

## 📋 Quick Answer

**Use the scripts that match your terminal!**

- 🟦 **Using CMD (Command Prompt)?** → Use `.bat` files
- 🔵 **Using PowerShell?** → Use `.ps1` files

---

## 🎯 Available Scripts

### **For CMD (Command Prompt)** - `.bat` files

| Script | Location | Purpose |
|--------|----------|---------|
| `train-chatbot.bat` | `chatbot\` | Train the AI model |
| `start-rasa-server.bat` | `chatbot\` | Start Rasa server only |
| `start-action-server.bat` | `chatbot\` | Start action server only |
| `test-chatbot.bat` | `chatbot\` | Test in terminal |
| `start-all-servers.bat` | Root | **Start all 3 servers at once** ⭐ |

### **For PowerShell** - `.ps1` files

| Script | Location | Purpose |
|--------|----------|---------|
| `train-chatbot.ps1` | `chatbot\` | Train the AI model |
| `start-rasa-server.ps1` | `chatbot\` | Start Rasa server only |
| `start-action-server.ps1` | `chatbot\` | Start action server only |
| `test-chatbot.ps1` | `chatbot\` | Test in terminal |
| `start-all-servers.ps1` | Root | **Start all 3 servers at once** ⭐ |

---

## 🚀 How to Use

### **Method 1: Double-Click (Easiest)**

**For CMD (.bat files):**
- Just double-click the `.bat` file in Windows Explorer
- Works immediately, no setup needed! ✅

**For PowerShell (.ps1 files):**
- Right-click the `.ps1` file
- Select **"Run with PowerShell"**
- The script automatically handles execution policy! ✅

### **Method 2: From Terminal**

**In CMD:**
```cmd
cd c:\xampp\htdocs\Hayat_Hadi'ah
start-all-servers.bat
```

**In PowerShell:**
```powershell
cd c:\xampp\htdocs\Hayat_Hadi'ah
.\start-all-servers.ps1
```

---

## ⚡ What Changed?

### **The PowerShell Execution Policy Issue - SOLVED!** ✅

**Problem:** PowerShell blocks `.ps1` scripts by default for security

**Old Solution (Manual):**
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
```

**New Solution (Automatic):** 🎉
All `.ps1` scripts now include this line at the start:
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
```

**This means:**
- ✅ Scripts work immediately
- ✅ No manual commands needed
- ✅ Only affects the current PowerShell window (safe!)
- ✅ Doesn't change your system settings

### **The venv Activation Issue - SOLVED!** ✅

**Both CMD and PowerShell scripts now:**
- ✅ Set the PATH correctly
- ✅ Activate virtual environment automatically
- ✅ Handle Python environment properly
- ✅ No manual activation needed!

---

## 🎯 Recommended Workflow

### **Which to Use?**

**Use CMD (.bat) if:**
- ✅ You're familiar with Command Prompt
- ✅ You want the simplest experience
- ✅ You're double-clicking from Windows Explorer

**Use PowerShell (.ps1) if:**
- ✅ You prefer PowerShell terminal
- ✅ You want colored output (prettier!)
- ✅ You're already working in PowerShell

**Both work equally well!** Pick your favorite! 😊

---

## 📝 Step-by-Step Examples

### **Example 1: First-Time Setup (Training)**

**Using CMD:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\
2. Double-click: train-chatbot.bat
3. Wait ~10 minutes ☕
```

**Using PowerShell:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\
2. Right-click: train-chatbot.ps1
3. Select: "Run with PowerShell"
4. Wait ~10 minutes ☕
```

### **Example 2: Daily Use (Start Servers)**

**Using CMD:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\
2. Double-click: start-all-servers.bat
3. 3 windows open! ✅
```

**Using PowerShell:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\
2. Right-click: start-all-servers.ps1
3. Select: "Run with PowerShell"
4. 3 windows open! ✅
```

### **Example 3: Testing Before Web**

**Using CMD:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\
2. Double-click: test-chatbot.bat
3. Type: Hello
4. Chat in terminal!
```

**Using PowerShell:**
```cmd
1. Navigate to: c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\
2. Right-click: test-chatbot.ps1
3. Select: "Run with PowerShell"
4. Type: Hello
5. Chat in terminal!
```

---

## ⚠️ Troubleshooting

### **Issue: PowerShell says "cannot be loaded because running scripts is disabled"**

**Solution:**
- The `.ps1` scripts now handle this automatically!
- Just right-click → "Run with PowerShell"
- OR run from PowerShell terminal (script sets policy internally)

### **Issue: "venv is not recognized"**

**Solution:**
- ✅ **Fixed!** Scripts no longer use activation
- They directly set the PATH to Python in venv
- Works in both CMD and PowerShell!

### **Issue: Which Python is being used?**

**Check in CMD:**
```cmd
where python
```

**Check in PowerShell:**
```powershell
Get-Command python | Select-Object Source
```

**Should show:** `C:\Users\mahhia\hayat_rasa\venv\Scripts\python.exe`

---

## 💡 Pro Tips

### **Tip 1: Create Desktop Shortcuts**

**For easy access:**
1. Right-click `start-all-servers.bat` or `.ps1`
2. Select "Create shortcut"
3. Drag shortcut to Desktop
4. Double-click from Desktop anytime! 🎉

### **Tip 2: Pin to Taskbar**

**Windows 10/11:**
1. Right-click the `.bat` file
2. Select "Pin to taskbar"
3. One-click access! ⚡

### **Tip 3: Use Windows Terminal (Optional)**

**Modern experience:**
1. Install "Windows Terminal" from Microsoft Store
2. Supports both CMD and PowerShell in tabs
3. Beautiful colored output!

---

## 📊 Feature Comparison

| Feature | CMD (.bat) | PowerShell (.ps1) |
|---------|------------|-------------------|
| **Ease of Use** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Colored Output** | ⚪ Plain | 🌈 Colored |
| **Double-Click** | ✅ Works | ✅ Works (right-click) |
| **Virtual Env** | ✅ Auto | ✅ Auto |
| **Execution Policy** | N/A | ✅ Auto-handled |
| **Speed** | Fast | Fast |
| **Compatibility** | Windows XP+ | Windows 7+ |

---

## 🎉 Summary

**You now have TWO options:**

1. **`.bat` files** - Simple, classic, works everywhere
2. **`.ps1` files** - Modern, colorful, PowerShell-friendly

**Both:**
- ✅ Handle virtual environment automatically
- ✅ No manual commands needed
- ✅ Just double-click (or right-click for `.ps1`)
- ✅ Work perfectly!

**Pick your favorite and go!** 🚀

---

## 📚 Next Steps

1. Choose your preferred script type (`.bat` or `.ps1`)
2. Read `QUICK_START.md` for usage instructions
3. Train your model with `train-chatbot.bat` or `.ps1`
4. Start servers with `start-all-servers.bat` or `.ps1`
5. Visit `http://localhost:8000/chatbot` and enjoy! 🎉

**JazakAllah Khair!** 🤲
