Hayat Hadia – Laravel-based Islamic Lifestyle Web App

Hayat Hadia (meaning Gift of Life) is a comprehensive Islamic lifestyle web application built with Laravel. It helps users track prayer times, fasting schedules, daily Quran verses, important duas, calculate Zakat, and explore Islamic guidance, all in one interactive, personalized platform.

# Features

## 1. Prayer & Namaz Module 🕌

- **Location-based prayer times** (Fajr, Dhuhr, Asr, Maghrib, Isha, Midnight, Qiyam)
- **Multiple calculation methods** (Muslim World League, ISNA, Egyptian, Karachi, Umm Al-Qura, Tehran, Jafari, Gulf Region, Kuwait, Qatar, Singapore, France, Turkey, Russia, Moonsighting Committee)
- **Hanafi and Standard** calculation schools
- **Auto-detect location** via browser geolocation
- **City search** with 25+ worldwide locations
- **Elegant dropdown styling** with teal gradient theme
- **Timezone-aware calculations** using Carbon
- **Countdown timer** to next prayer with real-time updates
- **Dark/Light theme toggle** with smooth transitions
- **Responsive design** optimized for desktop and mobile
- **Beautiful Islamic pattern background** with gradient overlays

## 2. Qibla Compass Module 🧭

- **Real-time Qibla direction finder** using geolocation
- **Animated compass** with smooth rotation
- **Degree indication** showing exact Qibla angle
- **Distance to Kaaba** calculated from current location
- **Google Maps integration** showing Kaaba location
- **Works on mobile and desktop** devices
- **Accurate direction calculation** using geographical coordinates
- **Save favorite locations** for quick access
- **Visual feedback** with teal-themed design
- **Dark mode support** throughout

## 3. Quran & Hadith Module 📖

- **Daily random Quran verse** with Arabic and English translation
- **Hadith of the day** from authentic collections
- **Searchable Quran library** with all 114 Surahs
- **Searchable Hadith library** from multiple collections:
  - Sahih Bukhari
  - Sahih Muslim
  - Sunan Abu Dawood
  - Jami' at-Tirmidhi
  - Sunan an-Nasa'i
  - Sunan Ibn Majah
  - Muwatta Malik
  - Musnad Ahmad
  - 40 Hadith Nawawi
  - 40 Hadith Qudsi
- **Bookmark and save** favorite Ayahs and Hadiths
- **Surah-by-Surah navigation** with detailed view
- **Hadith search** by keyword across all collections
- **Arabic text** with transliteration and translation
- **Beautiful calligraphic fonts** (Diwani, Amiri)
- **Reading tracker** for daily spiritual goals
- **Dark mode optimized** for comfortable reading

## 4. Fasting / Roja Module 🌙

- **Ramadan fasting schedule** with automatic date calculation
- **Sehri and Iftar timings** with timezone support
- **Calendar-based date selector** with elegant styling
- **Location-based timing** for accurate Sehri/Iftar
- **Scaled-down fonts** for clean, readable interface
- **Styled dropdowns** with teal gradient theme
- **Hijri calendar integration**
- **Voluntary fasting tracker** (Mondays, Thursdays, White Days)
- **Fasting intention reminders**
- **Historical fasting records**
- **Countdown to next Iftar/Sehri**
- **Dark theme support**

## 5. Nearby Mosque Finder 🕌

- **100% Free** - No API keys or credit cards required
- **OpenStreetMap integration** using Overpass API
- **Browser geolocation** to detect current location
- **Search radius selector** (1-20 km) with styled dropdown
- **One-line control layout** - all buttons in single row
- **Real-time mosque search** showing:
  - Mosque name
  - Full address
  - Distance in kilometers
  - Denomination (if available)
  - Capacity information
  - Wheelchair accessibility status
- **Alternating row design** with gradient backgrounds:
  - Even rows: Soft teal gradient
  - Odd rows: Light neutral gradient
- **OpenStreetMap directions** - click to navigate
- **Fallback search** for Islamic centers if no mosques found
- **Dark mode support** with matching gradients
- **Responsive design** - works on all devices
- **Clean interface** - no ratings or clutter
- **Community-driven data** from OpenStreetMap contributors

## 6. Duas & Islamic Rules Module 🤲

- **Important and commonly used duas** (morning, evening, travel, etc.)
- **Duas organized by category** and occasion
- **Mandatory rules**, etiquettes, and regulations
- **Daily dua reminders**
- **Arabic text** with transliteration and translation
- **Audio pronunciation guide**
- **Searchable dua library**

## 7. Islamic Finance / Zakat Module 💰

- **Zakat calculator** (gold, silver, cash, business assets)
- **Nisab threshold calculator**
- **Track Zakat payments** and records
- **Guidance on Islamic finance** principles
- **Sadaqah tracker**
- **Financial planning** according to Shariah

## User Personalization

- **Login and profile management**
- **Dashboard** with favorites, prayer reminders, and reading streaks
- **Session and cookie management** for user preferences
- **Dark/Light theme toggle** persists across sessions
- **Location preferences** saved automatically
- **Custom prayer calculation** method selection

## Technical Stack

### Backend
- **Laravel 11** (MVC, Controllers, Middleware, REST API)
- **PHP 8.2+**
- **MySQL/PostgreSQL** database
- **Eloquent ORM** for database operations
- **Carbon** for timezone-aware date/time handling

### Frontend
- **Blade templates** with component inheritance
- **Responsive CSS** with mobile-first design
- **JavaScript (ES6+)** with async/await
- **Flexbox & Grid** layouts
- **Custom fonts** (Cinzel, Amiri, Diwani, Arabic Typesetting)
- **SVG patterns** and gradients
- **Dark mode** with CSS variables

### APIs & Integration
- **Islamic Network Prayer Times API** (https://aladhan.com/prayer-times-api)
- **OpenStreetMap Overpass API** (free, no authentication)
- **OpenStreetMap Directions** (OSRM routing engine)
- **Browser Geolocation API**
- **AJAX / Fetch API** for dynamic content
- **Google Maps API** (optional, for Qibla map view)

### Database Models
- Users (authentication, preferences)
- PrayerTimes (location, calculation method)
- QuranVerses (Arabic, translation, surah info)
- Hadiths (text, collection, book, chapter)
- Mosques (saved locations)
- FastingSchedule (Ramadan dates, timings)
- Duas (categories, occasions)
- ZakatRecords (calculations, payments)

### Security & Features
- **CSRF protection** on all forms
- **Laravel authentication** with middleware
- **Session management** for user state
- **Secure cookies** for preferences
- **Input validation** and sanitization
- **Rate limiting** on API endpoints

## Project Structure & Laravel Concepts Covered

### Environment Setup
- Composer dependency management
- Laravel project structure
- Environment configuration (.env)

### Artisan Commands
- `make:controller` - Creating controllers (PrayerController, MosqueController, FastingController, etc.)
- `make:model` - Creating models with migrations
- `make:migration` - Database schema management
- `make:seeder` - Populating initial data

### Routing & Controllers
- **RESTful routing** with resource controllers
- **Route groups** with middleware
- **Named routes** for clean URL generation
- **Route parameters** for dynamic content
- **API routes** for AJAX requests
- **Passing data to views** using compact() and with()

### Blade Templates
- **Blade syntax** (`{{ }}`, `{!! !!}`, `@{{ }}`)
- **Control structures** (@if, @foreach, @while)
- **Template inheritance** (@extends, @section, @yield)
- **Components** (@push, @stack)
- **Layouts** with nested templates
- **JavaScript integration** with Blade variables

### Middleware
- **Authentication middleware** (auth, guest)
- **CSRF verification** on POST requests
- **Custom middleware** for theme preferences
- **Rate limiting** on API endpoints
- **Header manipulation** for security

### Database & Migrations
- **Schema builder** for table creation
- **Foreign keys** and relationships
- **Indexes** for performance optimization
- **Migration versioning** with timestamps
- **Seeding** for test data
- **Rollback and refresh** commands

### Query Builder & Eloquent ORM
- **CRUD operations** (create, read, update, delete)
- **Relationships** (hasMany, belongsTo, hasOne)
- **Aggregation** (count, sum, avg, max, min)
- **Joins** and complex queries
- **Scopes** for reusable query logic
- **Accessors & Mutators** for data formatting
- **Casts** for type conversion

### REST API Development
- **JSON responses** with status codes
- **Request validation** with rules
- **API resource controllers**
- **Request/response handling** with headers
- **File uploads** and storage
- **Error handling** with try-catch
- **Testing with Postman** and browser console

### External API Integration
- **HTTP Client** (Laravel Http facade)
- **API rate limiting** and courtesy
- **Error handling** for API failures
- **Data transformation** from API responses
- **Caching API results** for performance

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
