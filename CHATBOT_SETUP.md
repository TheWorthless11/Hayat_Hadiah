# 🤖 AI Islamic Chatbot Setup Guide

## Overview
This guide will help you set up and deploy the AI Islamic chatbot powered by Rasa, integrated with the Hayat Hadiah Laravel application.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Training the Model](#training-the-model)
4. [Running the Chatbot](#running-the-chatbot)
5. [Testing](#testing)
6. [Deployment](#deployment)
7. [Troubleshooting](#troubleshooting)
8. [API Endpoints](#api-endpoints)

---

## Prerequisites

Before you begin, ensure you have the following installed:

- **Python 3.8+** (for Rasa)
- **pip** (Python package manager)
- **virtualenv** or **venv** (Python virtual environment)
- **PHP 8.2+** (for Laravel)
- **Composer** (PHP package manager)
- **MySQL** (Database)
- **XAMPP** (Local development server)

### Check Installations
```bash
python --version
pip --version
php --version
composer --version
```

---

## Installation

### Step 1: Set Up Rasa Environment

1. **Navigate to your Rasa project directory:**
   ```bash
   cd C:\Users\mahhia\hayat_rasa
   ```

2. **Activate your virtual environment:**
   ```bash
   # On Windows
   venv\Scripts\activate
   
   # On macOS/Linux
   source venv/bin/activate
   ```

3. **Install required Python packages:**
   ```bash
   pip install rasa
   pip install rasa-sdk
   pip install requests
   ```

### Step 2: Copy Chatbot Files

1. **Copy the chatbot directory to your Laravel project:**
   ```bash
   # The chatbot folder should be at:
   # c:\xampp\htdocs\Hayat_Hadi'ah\chatbot\
   ```

2. **Verify the chatbot directory structure:**
   ```
   chatbot/
   ├── actions/
   │   ├── __init__.py
   │   └── actions.py
   ├── data/
   │   ├── nlu.yml
   │   ├── stories.yml
   │   └── rules.yml
   ├── config.yml
   ├── domain.yml
   └── endpoints.yml
   ```

### Step 3: Install Rasa Dependencies

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah\chatbot
pip install -r requirements.txt
```

If `requirements.txt` doesn't exist, create it with:
```txt
rasa==3.6.13
rasa-sdk==3.6.2
requests==2.31.0
```

---

## Training the Model

### Step 1: Navigate to Chatbot Directory

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah\chatbot
```

### Step 2: Train the Rasa Model

```bash
rasa train
```

**Expected output:**
- Model training will take 5-15 minutes
- A new model will be created in the `models/` directory
- File format: `models/YYYYMMDD-HHMMSS.tar.gz`

### Step 3: Verify Training

Check if the model was created:
```bash
dir models
# or on macOS/Linux:
ls -la models/
```

You should see a `.tar.gz` file with a timestamp.

---

## Running the Chatbot

You need to run **THREE** separate servers for the chatbot to work:

### Server 1: Laravel Development Server

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah
php artisan serve
```

**Server runs at:** `http://localhost:8000`

### Server 2: Rasa Server

Open a **new terminal/command prompt:**

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah\chatbot
rasa run --enable-api --cors "*"
```

**Server runs at:** `http://localhost:5005`

**Important flags:**
- `--enable-api`: Enables REST API endpoints
- `--cors "*"`: Allows cross-origin requests from Laravel frontend

### Server 3: Rasa Action Server

Open a **third terminal/command prompt:**

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah\chatbot
rasa run actions
```

**Server runs at:** `http://localhost:5055`

---

## Testing

### Test 1: Check Rasa Server Status

```bash
curl http://localhost:5005/status
```

**Expected response:**
```json
{
  "model_file": "models/20250120-143022.tar.gz",
  "fingerprint": {...},
  "num_active_training_jobs": 0
}
```

### Test 2: Test with Rasa Shell

```bash
cd c:\xampp\htdocs\Hayat_Hadi'ah\chatbot
rasa shell
```

**Test these sample conversations:**

```
You: Hello
Bot: السلام عليكم ورحمة الله وبركاته! Welcome to Hayat Hadiah...

You: What are prayer times today?
Bot: 🕌 Prayer Times for Today...

You: Show me a Quran verse
Bot: 📖 [Surah Name (2:255)]...

You: I want to donate
Bot: To make a donation, please visit...
```

Press `Ctrl+C` to exit.

### Test 3: Test Web Interface

1. **Start all three servers** (Laravel, Rasa, Actions)
2. **Open browser:** `http://localhost:8000/chatbot`
3. **Test the chat interface**
4. **Test the floating widget** on any page

### Test 4: Test API Endpoints

Test Laravel API endpoints:

```bash
# Prayer Times
curl http://localhost:8000/api/chatbot/prayer-times

# Random Quran Verse
curl http://localhost:8000/api/chatbot/quran-verse/random

# Donation Info
curl http://localhost:8000/api/chatbot/donations/info
```

---

## Deployment

### Production Setup

#### 1. Update Rasa Server URL

In `resources/views/chatbot/index.blade.php` and `public/js/chatbot-widget.js`, update:

```javascript
// Change from:
const RASA_SERVER_URL = 'http://localhost:5005';

// To:
const RASA_SERVER_URL = 'https://your-domain.com/rasa';
```

#### 2. Update Laravel API URL

In `chatbot/actions/actions.py`, update:

```python
# Change from:
LARAVEL_API_BASE = "http://localhost/Hayat_Hadi'ah/public/api"

# To:
LARAVEL_API_BASE = "https://your-domain.com/api"
```

#### 3. Deploy Rasa on Production Server

**Option A: Docker Deployment**

Create `Dockerfile`:
```dockerfile
FROM rasa/rasa:3.6.13-full

WORKDIR /app
COPY . /app

RUN rasa train

CMD ["rasa", "run", "--enable-api", "--cors", "*", "--port", "5005"]
```

Build and run:
```bash
docker build -t hayat-chatbot .
docker run -d -p 5005:5005 hayat-chatbot
```

**Option B: Direct Server Deployment**

```bash
# Install Rasa on server
pip install rasa

# Copy chatbot folder to server
scp -r chatbot/ user@server:/var/www/hayat_hadiah/

# Train model on server
cd /var/www/hayat_hadiah/chatbot
rasa train

# Run with systemd service (recommended)
# Create /etc/systemd/system/rasa-server.service
# Create /etc/systemd/system/rasa-actions.service
```

#### 4. Configure Nginx/Apache

**Nginx configuration:**

```nginx
# Rasa server proxy
location /rasa/ {
    proxy_pass http://localhost:5005/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
}

# Laravel API
location /api/ {
    try_files $uri $uri/ /index.php?$query_string;
}
```

#### 5. Set Up SSL

```bash
# Using Let's Encrypt
sudo certbot --nginx -d your-domain.com
```

---

## Troubleshooting

### Issue 1: Rasa Server Not Starting

**Error:** `Port 5005 is already in use`

**Solution:**
```bash
# Find process using port 5005
netstat -ano | findstr :5005

# Kill process (Windows)
taskkill /PID <process_id> /F

# Kill process (macOS/Linux)
kill -9 <process_id>
```

### Issue 2: Action Server Connection Error

**Error:** `Failed to connect to action server`

**Solution:**
1. Verify action server is running on port 5055
2. Check `endpoints.yml` has correct URL:
   ```yaml
   action_endpoint:
     url: "http://localhost:5055/webhook"
   ```
3. Restart action server

### Issue 3: Model Training Fails

**Error:** `No training data found`

**Solution:**
1. Verify all YAML files are in `data/` directory
2. Check YAML syntax (use YAML validator)
3. Ensure `domain.yml` includes all intents from `nlu.yml`

### Issue 4: Chatbot Not Responding

**Symptoms:** User sends message, no response

**Debugging steps:**
1. Check browser console for errors (F12)
2. Verify Rasa server status: `http://localhost:5005/status`
3. Test with `rasa shell` to isolate issue
4. Check action server logs for errors

### Issue 5: Laravel API Returns 404

**Error:** `404 Not Found` for `/api/chatbot/*`

**Solution:**
1. Run `php artisan route:list | findstr chatbot`
2. Clear Laravel cache:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan cache:clear
   ```
3. Verify `bootstrap/app.php` includes API routes

### Issue 6: CORS Errors

**Error:** `Access-Control-Allow-Origin`

**Solution:**
1. Ensure Rasa runs with `--cors "*"` flag
2. Add CORS middleware to Laravel API routes
3. Check browser network tab for preflight OPTIONS requests

---

## API Endpoints

### Laravel Chatbot API

All endpoints are prefixed with `/api/chatbot/`

#### Prayer Times
- `GET /api/chatbot/prayer-times` - Get all prayer times for today
- `GET /api/chatbot/prayer-times/{prayerName}` - Get specific prayer time

**Parameters:**
- `location` (optional) - User location

**Example:**
```bash
curl "http://localhost:8000/api/chatbot/prayer-times?location=Dhaka"
```

#### Qibla Direction
- `GET /api/chatbot/qibla-direction` - Get Qibla direction and distance

**Parameters:**
- `location` (optional) - User location

#### Quran
- `GET /api/chatbot/quran-verse` - Get verse by topic
- `GET /api/chatbot/quran-verse/random` - Get random verse

**Parameters:**
- `topic` (optional) - Search topic (e.g., "patience", "charity")

#### Hadith
- `GET /api/chatbot/hadith` - Get hadith by topic
- `GET /api/chatbot/hadith/random` - Get random hadith

**Parameters:**
- `topic` (optional) - Search topic

#### Duas
- `GET /api/chatbot/duas` - Get all duas
- `GET /api/chatbot/duas/{category}` - Get specific dua category

**Example:**
```bash
curl http://localhost:8000/api/chatbot/duas/morning
```

#### Zakat
- `GET /api/chatbot/zakat-info` - Get Zakat information

#### Donations
- `GET /api/chatbot/donations/info` - Get donation categories and progress

#### Mosques
- `GET /api/chatbot/mosques/nearby` - Find nearby mosques

**Parameters:**
- `location` (optional) - User location

#### Fasting
- `GET /api/chatbot/fasting-times` - Get today's fasting times
- `GET /api/chatbot/ramadan-info` - Get Ramadan information

---

## Features

### Implemented Features

1. **Prayer Times** ✅
   - Get all prayer times for today
   - Get specific prayer time (Fajr, Dhuhr, Asr, Maghrib, Isha)
   - Location-based prayer times

2. **Qibla Direction** ✅
   - Calculate Qibla direction from user location
   - Distance to Kaaba

3. **Quran** ✅
   - Get random verse
   - Search verse by topic
   - Arabic text + English translation

4. **Hadith** ✅
   - Get random hadith
   - Search by topic
   - Multiple collections (Sahih Bukhari, Sahih Muslim, etc.)

5. **Duas** ✅
   - Daily duas
   - Category-based duas (Morning, Evening, Travel, etc.)
   - Arabic, transliteration, and translation

6. **Zakat** ✅
   - Zakat information
   - Calculator link
   - Nisab and rates

7. **Donations** ✅
   - Donation categories
   - Goal progress tracking
   - Direct donation link

8. **Mosques** ✅
   - Find nearby mosques
   - Mosque details (name, address, distance)

9. **Fasting** ✅
   - Suhoor and Iftar times
   - Ramadan calendar
   - Fasting duration

### Chatbot Capabilities

- **Natural Language Understanding**: Understands variations of user queries
- **Multi-turn Conversations**: Maintains context across messages
- **Quick Replies**: Provides quick action buttons
- **Rich Responses**: Formatted text with emojis and markdown
- **Fallback Handling**: Graceful error responses
- **Authentication Awareness**: Adapts responses based on user login status

---

## Usage Examples

### Example Conversations

**Prayer Times:**
```
User: When is Fajr today?
Bot: 🕌 Fajr prayer time is at 05:30 AM

User: Show me all prayer times
Bot: 🕌 Prayer Times for Today
     • Fajr: 05:30 AM
     • Dhuhr: 12:15 PM
     • Asr: 03:45 PM
     • Maghrib: 06:10 PM
     • Isha: 07:30 PM
```

**Quran:**
```
User: Show me a verse about patience
Bot: 📖 Al-Baqarah (2:153)
     يَا أَيُّهَا الَّذِينَ آمَنُوا اسْتَعِينُوا بِالصَّبْرِ وَالصَّلَاةِ
     
     O you who have believed, seek help through patience and prayer...
```

**Donations:**
```
User: I want to donate
Bot: To make a donation, please visit our donation page:
     
     Visit: http://localhost:8000/donations
     
     You can:
     • Choose a donation category
     • Enter your donation amount
     • Pay via bKash or Nagad
     • Receive email confirmation
     
     JazakAllah Khair for your generosity! 🤲
```

---

## Next Steps

1. **Train the Model**: Run `rasa train` in the chatbot directory
2. **Start Servers**: Start Laravel, Rasa, and Action servers
3. **Test**: Visit `http://localhost:8000/chatbot` and test the chatbot
4. **Customize**: Modify training data in `data/nlu.yml` to add more examples
5. **Deploy**: Follow the deployment guide for production setup

---

## Support

For issues or questions:
- Check the [Troubleshooting](#troubleshooting) section
- Review Rasa logs: `chatbot/rasa.log`
- Check Laravel logs: `storage/logs/laravel.log`
- Review action server output in the terminal

---

## Credits

- **Rasa**: Open-source conversational AI framework
- **Laravel**: PHP web application framework
- **Hayat Hadiah**: Islamic lifestyle web application

---

## License

This chatbot is part of the Hayat Hadiah project and follows the same license.

---

**Last Updated:** January 2025
