# 🚀 Quick Start Guide - AI Chatbot

## 📋 Super Easy Setup (Just 3 Steps!)

> **Note:** Scripts available in both CMD (`.bat`) and PowerShell (`.ps1`) versions!  
> See `SCRIPT_USAGE_GUIDE.md` for details. Use whichever you prefer! 😊

### **Step 1: Train the Model (First Time Only)** ⏱️ ~10 minutes

**CMD Users:** Double-click `chatbot\train-chatbot.bat`  
**PowerShell Users:** Right-click `chatbot\train-chatbot.ps1` → "Run with PowerShell"

This will:
- Activate Python environment automatically
- Train the Rasa model
- Save it in `chatbot\models\` folder

### **Step 2: Start All Servers** 

**CMD Users:** Double-click `start-all-servers.bat`  
**PowerShell Users:** Right-click `start-all-servers.ps1` → "Run with PowerShell"

This will open 3 windows:
- ✅ Laravel Server (http://localhost:8000)
- ✅ Rasa Server (http://localhost:5005)
- ✅ Rasa Action Server (http://localhost:5055)

**Keep all 3 windows open!**

### **Step 3: Test Your Chatbot** 🎉

**Option A: Web Interface (Recommended)**
- Open browser: http://localhost:8000
- Click the 🤖 bubble in bottom-right corner
- Start chatting!

**Option B: Full Page**
- Visit: http://localhost:8000/chatbot
- Full-page chat interface

**Option C: Command Line Test**
- Double-click: `chatbot\test-chatbot.bat`
- Test in terminal before web testing

---

## 📁 Script Files Reference

| Script | Purpose | When to Use |
|--------|---------|-------------|
| `chatbot\train-chatbot.bat` | Train the AI model | First time & after changing training data |
| `start-all-servers.bat` | Start Laravel + Rasa + Actions | Every time you want to use chatbot |
| `chatbot\start-rasa-server.bat` | Start only Rasa server | Manual control |
| `chatbot\start-action-server.bat` | Start only action server | Manual control |
| `chatbot\test-chatbot.bat` | Test in terminal | Before web testing |

---

## 💬 Try These Queries

Once your chatbot is running, try:

```
✅ Hello
✅ What are prayer times today?
✅ When is Fajr?
✅ Show me Qibla direction
✅ Show me a Quran verse about patience
✅ Tell me a hadith
✅ Show me morning duas
✅ I want to donate
✅ Find mosque near me
✅ When is iftar?
✅ What is Zakat?
```

---

## 🛑 Stopping Servers

**Easy Way:**
- Close all 3 command prompt windows

**Or press:** `Ctrl+C` in each window

---

## 🔄 Retraining the Model

**When to retrain:**
- After modifying `chatbot\data\nlu.yml`
- After adding new intents to `chatbot\domain.yml`
- After changing conversation flows

**How to retrain:**
1. Stop all servers
2. Double-click `chatbot\train-chatbot.bat`
3. Wait for training to complete
4. Restart servers with `start-all-servers.bat`

---

## ⚠️ Troubleshooting

### Problem: "Port already in use"

**Solution:**
1. Close all command prompts
2. Wait 10 seconds
3. Restart `start-all-servers.bat`

### Problem: Chatbot not responding

**Check:**
1. All 3 windows are open and running
2. No errors in the windows
3. Visit http://localhost:5005/status (should show JSON)
4. Open browser console (F12) for errors

### Problem: Training fails

**Solution:**
1. Check `chatbot\data\nlu.yml` for YAML syntax errors
2. Ensure all intents in `nlu.yml` are listed in `domain.yml`
3. Try: `pip install --upgrade rasa`

---

## 📚 Documentation

For detailed documentation, see:
- **Full Setup Guide:** `CHATBOT_SETUP.md`
- **Implementation Summary:** `CHATBOT_IMPLEMENTATION_SUMMARY.md`

---

## 🎯 Daily Workflow

```bash
1. Double-click: start-all-servers.bat
2. Open browser: http://localhost:8000
3. Click 🤖 bubble
4. Chat away!
5. When done: Close the 3 windows
```

**That's it!** 🎉

---

## 💡 Pro Tips

- **First Time:** Always run `train-chatbot.bat` before starting servers
- **Keep Windows Open:** Don't close the 3 server windows while using chatbot
- **Browser Cache:** If widget doesn't appear, press `Ctrl+F5` to hard refresh
- **Test First:** Use `test-chatbot.bat` to verify model works before web testing
- **Logs:** Check the server windows for error messages if something goes wrong

---

## 🆘 Need Help?

1. **Check logs** in the 3 server windows
2. **Read:** `CHATBOT_SETUP.md` Troubleshooting section
3. **Test with:** `chatbot\test-chatbot.bat` to isolate issues

---

**JazakAllah Khair!** 🤲

Your AI Islamic assistant is ready to help users with prayer times, Quran verses, Hadith, donations, and much more!
