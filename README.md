Hayat Hadia – Laravel-based Islamic Lifestyle Web App

Hayat Hadia (meaning Gift of Life) is a comprehensive Islamic lifestyle web application built with Laravel. It helps users track prayer times, fasting schedules, daily Quran verses, important duas, calculate Zakat, and explore Islamic guidance, all in one interactive, personalized platform.

#Features

1. Prayer & Namaz Module

Location-based prayer times (Fajr, Dhuhr, Asr, Maghrib, Isha, Midnight, Qiyam)
Multiple calculation methods (Muslim World League, ISNA, Egyptian, Karachi, Umm Al-Qura)
Hanafi and Standard calculation schools
Auto-detect location via GPS
City search with 25+ worldwide locations
Notifications and reminders for prayers
Dark/Light theme toggle

2. Qibla Compass Module

Real-time Qibla direction finder using geolocation
Visual compass with degree indication
Distance to Kaaba from current location
Works on mobile and desktop devices
Accurate direction calculation using geographical coordinates
Save favorite locations for quick access

3. Quran & Hadith Module

Daily random Quran verse and Hadith of the day
Searchable Quran and Hadith library
Bookmark and save favorite Ayahs and Hadiths
Surah-by-Surah navigation
Translation support (multiple languages)
Audio recitation integration

4. Fasting / Roja Module

Ramadan schedule with Sehri and Iftar timings
Notifications for fasting reminders
Hijri calendar integration
Voluntary fasting tracker (Mondays, Thursdays, White Days)
Fasting intention reminders
Historical fasting records

5. Duas & Islamic Rules Module

Important and commonly used duas (morning, evening, travel, etc.)
Duas organized by category and occasion
Mandatory rules, etiquettes, and regulations
Daily dua reminders
Arabic text with transliteration and translation
Audio pronunciation guide

6. Islamic Finance / Zakat Module

Zakat calculator (gold, silver, cash, business assets)
Nisab threshold calculator
Track Zakat payments and records
Guidance on Islamic finance principles
Sadaqah tracker
Financial planning according to Shariah

User Personalization

Login and profile management

Dashboard with favorites, prayer reminders, and reading streaks
Session and cookie management for user preferences

Technical Stack

Backend: Laravel (MVC, Controllers, Middleware, REST API)
Frontend: Blade templates, responsive design, JavaScript integration
Database: MySQL/PostgreSQL (users, prayer schedules, Quran/Hadith, duas)

APIs & Integration: Prayer time API, geolocation for Qibla, AJAX / Fetch API for dynamic content

Security & Features: CSRF protection, authentication, sessions, cookies

Project Structure & Laravel Concepts Covered

Environment Setup: Composer, project structure

Artisan Commands: make:controller, make:model, make:view

Routing & Controllers: Basic routing, route helpers, passing data to views

Blade Templates: Syntax ({{ }}, {!! !!}), control structures, template inheritance, JavaScript integration

Middleware: Request verification, redirects, header manipulation, rate limiting

Database & Migrations: Schemas, indexes, foreign keys, seeding, rollback/refresh

Query Builder & Eloquent ORM: CRUD operations, aggregation, joins, advanced queries

REST API: Request-response handling with parameters, headers, body, file uploads, tested with Postman

Installation & Setup

Clone the repository:

git clone https://github.com/yourusername/hayat-hadia.git


Install dependencies via Composer:

composer install


Copy .env.example to .env and configure your database.

Generate app key:

php artisan key:generate


Run migrations and seeders:

php artisan migrate --seed


Start the development server:

php artisan serve


Visit http://localhost:8000 in your browser.

Screenshots / Demo (optional)



License / Credits

Built with ❤️ using Laravel

Inspired by Islamic lifestyle apps like Muslim Pro and IslamicFinder
