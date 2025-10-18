# Nearby Mosque Feature - Setup Guide

## 🕌 Overview
This feature allows users to find nearby mosques using their browser's geolocation and **OpenStreetMap** (OSM) - completely free, no API key or credit card required!

## ✨ Why OpenStreetMap?
- ✅ **100% Free** - No API key needed
- ✅ **No Credit Card** - No payment information required
- ✅ **No Limits** - Unlimited requests (with fair use)
- ✅ **Open Data** - Community-driven, constantly updated
- ✅ **Privacy-Friendly** - No tracking or data collection

## ✅ Installation Complete

The following files have been created:

### Backend Files:
- ✅ `database/migrations/2025_10_18_000001_create_mosques_table.php` - Database migration
- ✅ `app/Models/Mosque.php` - Mosque model with distance calculation
- ✅ `app/Http/Controllers/MosqueController.php` - Controller with **OpenStreetMap Overpass API** integration
- ✅ Routes added to `routes/web.php`

### Frontend Files:
- ✅ `resources/views/mosque/index.blade.php` - Mosque finder with geolocation & OSM directions
- ✅ `public/css/mosque-styles.css` - Beautiful styles with alternating row colors
- ✅ Navigation link added to `resources/views/layouts/app.blade.php`

## 🔧 Required Setup Steps

### 1. Run Database Migration

Open terminal and run:

```bash
php artisan migrate
```

This will create the `saved_mosques` table in your database.

### 2. Clear Configuration Cache (optional)

```bash
php artisan config:cache
```

## ✅ That's It!

**No API keys needed!** The feature is ready to use immediately.

## 🎨 Design Features

### Layout:
- ✅ Clean, modern design matching your theme
- ✅ Alternating row colors (gradient backgrounds)
  - Even rows: Soft teal gradient
  - Odd rows: Light neutral gradient
- ✅ Full-width mosque listing boxes
- ✅ Responsive design (desktop & mobile)

### Typography:
- ✅ Medium-sized, readable fonts (0.8rem - 1rem)
- ✅ Proper hierarchy with font weights

### Buttons:
- ✅ Small, aesthetic buttons (0.45rem padding)
- ✅ Consistent with dark theme
- ✅ Smooth hover effects
- ✅ Icons for better UX (🧭 Directions)

### Features Per Mosque:
- ✅ Mosque name with 🕌 icon
- ✅ Full address
- ✅ Distance in kilometers (calculated accurately)
- ✅ Denomination (e.g., Sunni, Shia, etc.) if available
- ✅ Capacity information if available
- ✅ Wheelchair accessibility indicator
- ✅ "Directions" button → Opens OpenStreetMap navigation

### Dark Mode:
- ✅ Full dark mode support
- ✅ Alternating colors work in both modes
- ✅ Proper contrast for readability

## 🚀 Usage

1. Visit: `http://your-domain/mosques`
2. Click "Find Nearby Mosques"
3. Allow browser location access when prompted
4. Select search radius (1-20 km)
5. View nearby mosques with:
   - Name, address, distance
   - Additional info (denomination, capacity, accessibility)
   - Click "Directions" to navigate via OpenStreetMap

## 🗺️ How It Works

### Data Source: OpenStreetMap
- Uses **Overpass API** to query OSM database
- Searches for places tagged as:
  - `amenity=place_of_worship` + `religion=muslim`
  - `building=mosque`
- Returns detailed information from OSM community data

### Navigation: OpenStreetMap Directions
- Opens OSM with routing from your location to the mosque
- Uses OSRM (Open Source Routing Machine) for directions
- Works in any browser, no account needed

## 🔒 Browser Permissions

The feature requires:
- ✅ Browser Geolocation API access
- ✅ User must grant location permission
- ✅ HTTPS recommended for production (geolocation works on HTTP localhost)

## 🎯 Features Included

### User Experience:
- ✅ Auto-detect user location
- ✅ Adjustable search radius (1-20 km)
- ✅ Loading spinner during search
- ✅ Error handling with friendly messages
- ✅ Empty state when no results
- ✅ Location coordinates display
- ✅ Fallback search for Islamic centers if no mosques found

### Mosque Information:
- ✅ Name (multiple language support)
- ✅ Address (street, city, state, postcode)
- ✅ Distance from user (Haversine formula)
- ✅ Latitude/Longitude coordinates
- ✅ Denomination (if tagged in OSM)
- ✅ Capacity (if available)
- ✅ Wheelchair accessibility
- ✅ Website & phone (if available)

### Actions:
- ✅ "Directions" button → Opens OpenStreetMap with:
  - User's current location as origin
  - Selected mosque as destination
  - Driving directions enabled

## 📱 Responsive Design

- Desktop: Full layout with side-by-side info and actions
- Mobile: Stacked layout, full-width buttons
- Tablet: Adaptive grid layout

## 🎨 Color Scheme

### Light Mode:
- Even rows: `linear-gradient(135deg, #f0fdfa, #e6fffa)`
- Odd rows: `linear-gradient(135deg, #ffffff, #f9fafb)`
- Accent: Teal (#0f766e, #14b8a6)

### Dark Mode:
- Even rows: `linear-gradient(135deg, #0d4d47, #064e3b)`
- Odd rows: `linear-gradient(135deg, #042f24, #053b2f)`
- Accent: Light teal (#5eead4, #14b8a6)

## ⚠️ Troubleshooting

### "Location permission denied":
- User must allow location access in browser
- HTTPS is required for geolocation in production
- Check browser location settings

### No mosques found:
- Increase search radius
- Verify location coordinates are correct
- Check if OpenStreetMap has data for your area
- Try contributing to OSM if data is missing!

### "Failed to fetch nearby mosques":
- Overpass API might be temporarily unavailable
- Check your internet connection
- Try again in a few moments

## 💰 Cost

**Completely FREE!**
- ✅ No API key required
- ✅ No credit card needed
- ✅ No usage limits (fair use policy applies)
- ✅ No hidden costs

## 🌍 Data Accuracy

OpenStreetMap data is:
- Community-maintained
- Constantly updated by volunteers
- Very accurate in most regions
- You can contribute to improve it!

To add missing mosques:
1. Visit [OpenStreetMap.org](https://www.openstreetmap.org/)
2. Create a free account
3. Click "Edit" and add missing mosques
4. Tag them properly (amenity=place_of_worship, religion=muslim)

## 🔐 Privacy & Fair Use

### Fair Use Policy:
- Don't make excessive requests (thousands per minute)
- Use reasonable timeouts (25 seconds default)
- Include User-Agent header (already configured)
- Cache results when possible

### Privacy:
- OpenStreetMap doesn't track users
- No personal data collected
- Browser geolocation stays on device
- Directions open in new tab

## 📞 Support

If you encounter any issues:
1. Check browser console for errors
2. Verify you granted location permission
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure migration ran successfully
5. Try increasing search radius

---

**✨ Your Nearby Mosque feature is now ready to use - completely free!**

Visit: `http://localhost/mosques` (or your domain)

**No API keys, no credit cards, no limits!** 🕌
