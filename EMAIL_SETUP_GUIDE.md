# Email Configuration Guide for Donation Receipts

## Overview
The donation feature now sends automated receipt emails to donors after successful payments.

---

## Quick Setup Options

### Option 1: Gmail (Recommended for Testing)

1. **Open `.env` file and update these settings:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Hayat Hadiah"
```

2. **Generate Gmail App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Sign in to your Gmail account
   - Click "Select app" → Choose "Mail"
   - Click "Select device" → Choose "Other" → Type "Laravel"
   - Click "Generate"
   - Copy the 16-character password (no spaces)
   - Use this as `MAIL_PASSWORD` in `.env`

3. **Test the email:**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($message) {
       $message->to('test@example.com')->subject('Test');
   });
   ```

---

### Option 2: Mailtrap (Best for Development)

1. **Sign up at:** https://mailtrap.io (Free account)

2. **Get credentials from Mailtrap dashboard**

3. **Update `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hayathadiah.com
MAIL_FROM_NAME="Hayat Hadiah"
```

**Benefits:**
- All emails go to Mailtrap inbox (not real recipients)
- Perfect for testing without spamming real emails
- View HTML rendering, check for spam score

---

### Option 3: SendGrid (Production Ready)

1. **Sign up:** https://sendgrid.com (Free tier: 100 emails/day)

2. **Create API Key:**
   - Go to Settings → API Keys
   - Create new API key with "Mail Send" permission
   - Copy the API key

3. **Update `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hayathadiah.com
MAIL_FROM_NAME="Hayat Hadiah"
```

---

### Option 4: Mailgun (Alternative Production)

1. **Sign up:** https://mailgun.com (Free tier: 5,000 emails/month)

2. **Get credentials from Mailgun dashboard**

3. **Update `.env`:**

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS=noreply@hayathadiah.com
MAIL_FROM_NAME="Hayat Hadiah"
```

---

## Testing the Email Feature

After configuring `.env`, test with a real donation:

1. Visit: `http://127.0.0.1:8000/donations`
2. Fill out the form with a **real email address you can check**
3. Submit the donation
4. Check your email inbox for the receipt

---

## Email Template Features

The receipt email includes:

✅ Professional header with gradient design
✅ Transaction reference number
✅ Donation amount, date, category
✅ Payment method
✅ Donor's custom message (if provided)
✅ Islamic dua in English & Arabic
✅ Quranic verse about charity
✅ "Make Another Donation" button
✅ Mobile responsive design
✅ Matches your teal/green theme

---

## Troubleshooting

### Email not sending?

1. **Check Laravel logs:**
   ```
   storage/logs/laravel.log
   ```

2. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Test mail settings:**
   ```bash
   php artisan tinker
   Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));
   ```

### Common Issues:

**"Connection refused"**
- Check MAIL_HOST and MAIL_PORT
- Verify firewall isn't blocking port 587/465

**"Authentication failed"**
- Double-check MAIL_USERNAME and MAIL_PASSWORD
- For Gmail, make sure you're using App Password, not regular password
- Enable "Less secure apps" if using regular Gmail password (not recommended)

**"Failed to connect to mailserver"**
- Check if XAMPP is blocking outbound SMTP
- Try changing MAIL_PORT from 587 to 465 (or vice versa)
- Try MAIL_ENCRYPTION=ssl instead of tls

---

## Recommended Setup by Environment

**Local Development:**
→ Use **Mailtrap** (safest, won't send real emails)

**Testing/Staging:**
→ Use **Gmail** (quick setup, real emails)

**Production:**
→ Use **SendGrid** or **Mailgun** (reliable, scalable)

---

## For Now (Quick Testing)

If you want to test immediately without email setup:

The system will still work! If email sending fails, it logs the error but doesn't stop the donation process. The user still sees the thank you page.

To test with real emails, I recommend **Mailtrap** first → it's free and shows exactly how your emails will look without sending them to real users.

---

## Need Help?

If you run into issues:
1. Check `storage/logs/laravel.log`
2. Make sure `.env` changes are saved
3. Run `php artisan config:clear`
4. Try the tinker test command above

---

**Current Status:** 
✅ Email feature is fully implemented
✅ Receipt template is styled and ready
⏳ Just needs `.env` configuration to send real emails
