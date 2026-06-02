# 🎨 FLATTY CMS

**Flatty CMS** adalah sistem manajemen konten yang ringan, minimalis, dan sederhana untuk website wisata/informasi. Dibangun dengan PHP, MySQL, dan JavaScript vanilla.

Fitur utama fokus pada manajemen konten wisata Bandung dengan integrasi peta interaktif, trip planning, dan komunitas pengguna.

---

## 📋 DAFTAR ISI

- [Admin Features](#-admin-features)
- [User Features](#-user-features)
- [Teknologi](#-teknologi)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)

---

## 👨‍💼 ADMIN FEATURES

Dashboard admin panel untuk mengelola semua konten dan pengaturan website.

### 1. 📊 Dashboard

**Lokasi:** `/admin`

**Fitur:**
- 📈 Statistik pengunjung harian
- 📝 Jumlah posts, pages, subscribers
- 📊 Chart analytics (Chart.js)
- 🔝 Top pages report
- 🌐 Cloudflare analytics integration
- 📅 Daily/weekly/monthly metrics
- 👥 Unique visitor tracking

---

### 2. 📝 Blog Manager

**Lokasi:** `/admin/blog-manager`

**Fitur:**
- ✅ Create, edit, delete blog posts
- ✅ Rich text editor (Quill.js) dengan image upload
- ✅ Category management
- ✅ Status post (active, inactive, pending)
- ✅ Featured image upload
- ✅ Excerpt auto-generation
- ✅ SEO-friendly URL slugs
- ✅ View counter
- ✅ Search & filter posts
- ✅ Bulk actions
- ✅ Publication date scheduling

**Upload Gambar:**
```
Max size: Unlimited (recommended < 5MB)
Format: JPEG, PNG, GIF, WebP
Destination: /public/uploads/
```

---

### 3. 📄 Pages Builder

**Lokasi:** `/admin/pages-builder`

**Fitur:**
- ✅ WYSIWYG HTML/CSS editor (CodeMirror)
- ✅ Live preview real-time
- ✅ Auto-generate page slug dari title
- ✅ Event date field (opsional)
- ✅ Code highlighting & syntax
- ✅ Keyboard shortcuts (Ctrl+S / Cmd+S)
- ✅ HTML sanitization (remove PHP, scripts, event handlers)
- ✅ Generate static PHP files otomatis
- ✅ Page versioning (update at tracking)

**Struktur:**
```
/pages/{slug}/index.php      (Generated page)
/pages/{slug}/.htaccess      (Apache config)
```

**Security:**
- ✅ Removes `<?php ?>` tags
- ✅ Removes `<script>` tags
- ✅ Removes event handlers (onclick, onload, etc)
- ✅ CSRF token validation

---

### 4. 🎨 CMPT Manager (Components)

**Lokasi:** `/admin/cmpt-manager`

**Tipe Komponen:**
- **Cards** - Display dalam format card
- **Modals** - Pop-up dialog
- **Toasts** - Notification alerts
- **Popups** - Alert messages

**Fitur:**
- ✅ Create/edit/delete components
- ✅ Image upload dengan preview
- ✅ Category classification
- ✅ Button links & CTA
- ✅ Drag-to-reorder
- ✅ Bulk status management
- ✅ Component search

**Kategori Tersedia:**
```
- Wisata Alam, Kuliner, Fashion, Budaya
- Wisata Family
- Event, Blog, Artikel
- Layanan, Maps, Penginapan
- Lokasi: Pusat Kota, Bandung Utara, Riau, Dago, Pasteur, Cihampelas
- Custom categories
```

---

### 5. 📧 Newsletter

**Lokasi:** `/admin/newsletter`

**Fitur:**
- ✅ Subscriber management
- ✅ Send bulk newsletters via SMTP
- ✅ Email templates (HTML/CSS)
- ✅ Unsubscribe tokens (5-year expiry)
- ✅ Newsletter draft & sent tracking
- ✅ Subscriber count analytics
- ✅ Sent history

**Email Config:**
```
SMTP Server: smtp.gmail.com (configurable)
Port: 587
Auth: STARTTLS
```

**Template Features:**
- Auto signature dengan logo
- Unsubscribe link otomatis
- Date/time stamp
- Company branding

---

### 6. 🗺️ POI Manager (Points of Interest)

**Lokasi:** `/admin/poi-manager`

**Fitur:**
- ✅ Create/edit/delete locations
- ✅ Category assignment
- ✅ Latitude/longitude mapping
- ✅ Location image upload
- ✅ Description text
- ✅ Website URL
- ✅ Status toggle (active/inactive)
- ✅ Search & filter
- ✅ Bulk status change
- ✅ Category filtering

**Data Per Lokasi:**
- Name
- Address
- Coordinates (GPS)
- Description
- Image
- Website URL
- Category
- Status

---

### 7. 💬 Feedback Management

**Lokasi:** `/admin/feedback`

**Fitur:**
- ✅ View user feedback
- ✅ Filter by category/rating
- ✅ Mark as reviewed
- ✅ Bulk delete
- ✅ Export feedback
- ✅ Response tracking

**Feedback Includes:**
- Rating (1-10)
- Category
- Message content
- User info (anonymous option)
- Timestamp

---

### 8. ⚙️ Settings

**Lokasi:** `/admin/setting`

**Fitur:**
- ✅ Site name & branding
- ✅ Maintenance mode toggle
- ✅ Maintenance bypass key
- ✅ Email configuration
- ✅ API keys storage
- ✅ Custom settings (key-value)

**Maintenance Mode:**
```
Dapat mengaktifkan mode maintenance
Bypass dengan key khusus
Assets tetap accessible (CSS, JS, images)
Admin tetap bisa akses
```

---

### 9. 👤 User Management

**Fitur Admin:**
- ✅ View all users
- ✅ User status (active, banned)
- ✅ Delete user account
- ✅ View user contributions
- ✅ Admin role assignment

---

## 👥 USER FEATURES

Frontend website untuk pengunjung & community members.

### 1. 🏠 Home Page

**Lokasi:** `/`

**Fitur:**
- 📍 Hero section dengan rotating slides
- 🎯 Call-to-action buttons
- 📰 Latest blog posts
- 🗺️ Featured locations (POIs)
- ⭐ Top ratings & reviews
- 🌤️ Weather widget (Bandung)
- 📧 Newsletter subscription
- 🎨 Responsive design

---

### 2. 🗺️ Trip Planner

**Lokasi:** `/trip`

**Fitur:**

#### Explore Tab
- 🔍 Search locations by name
- 🏷️ Filter by category
- 📋 Browse all POIs
- 📸 View location images
- ⭐ See ratings/reviews
- 👤 Login to contribute

#### Map Tab
- 🗺️ Interactive map (Leaflet.js)
- 📍 Click pins untuk detail lokasi
- ✍️ Select start point
- ➕ Add multiple stops
- 🛣️ Auto-generate optimal route
- 📏 Distance & duration estimation
- 💾 Save trip to profile
- 📱 Share trip link

#### Tripku Tab (My Trips)
- 📋 View saved trips
- 🔄 Load & modify trips
- 🗑️ Delete trips
- 📊 Trip statistics

**Trip Features:**
- Save up to unlimited trips
- Add custom notes per stop
- View total distance & time
- Share via WhatsApp, Facebook
- Copy trip link

---

### 3. 🖼️ Gallery & Reviews

**Lokasi:** `/gallery`

#### Gallery Section
- 🖼️ Browse community photos
- 📤 Upload your photos
- 🏷️ Tag location
- 💬 Add photo caption/credit
- 🔍 Search by location
- 📄 Pagination (12 per page)

**Upload Requirements:**
```
Formats: JPG, PNG, WebP
Max size: 10MB
Must be tagged with location
Optional: Credit/attribution
```

#### Reviews Section
- ⭐ Rate locations (1-5 stars)
- ✍️ Write detailed reviews
- 🏷️ Category tagging
- 👤 Uploader profile
- 🕐 Timestamp
- 📊 Helpful votes

**Review Requirements:**
```
Min length: 10 characters
Max length: 5000 characters
Required: Rating + title
Optional: Photo attachment
```

---

### 4. 👤 User Account

**Lokasi:** `/profile`, `/login`, `/register`

#### Authentication
- 📧 Email/password registration
- 🔐 Secure password hashing
- 🚀 Google Sign-In (GSI)
- 🔄 Password reset flow
- 📱 Session management (30 days)

#### Profile Page
- 👤 Edit profile info
- 🖼️ Avatar upload
- 📸 View my photos
- 🗺️ View my trips
- ❤️ View my reactions (likes)
- 🔐 Change password
- 🚪 Logout

**Profile Stats:**
- Total photos uploaded
- Total trips created
- Total reactions given
- Member since date

---

### 5. 📝 Blogs

**Lokasi:** `/blogs`

**Fitur:**
- 📰 List semua blog posts
- 🏷️ Filter by category
- 🔍 Search posts
- 📄 Pagination
- 👁️ View counter
- ❤️ Like/reaction system
- 💬 Comment (future)
- 📱 Share buttons
- 📌 Breadcrumbs

**Post Display:**
- Judul & excerpt
- Featured image
- Author info
- Publication date
- Read time estimate
- Category badge
- View count

---

### 6. 📄 Custom Pages

**Lokasi:** `/pages/{slug}/`

**Built-in Pages:**
- `/pages/sejarah/` - Sejarah Bandung
- `/pages/tentang/` - Tentang Website
- `/pages/layanan/` - Layanan
- `/pages/privacy-policy/` - Privacy Policy
- `/pages/kritik-dan-saran/` - Feedback Form

**Features:**
- 💬 Reaction/like system
- 📱 Share buttons (WA, FB, Instagram)
- 🔗 Copy page link
- 📱 Responsive layout

---

### 7. 🌐 Event Pages

**Lokasi:** `/upcoming-events`

**Fitur:**
- 📅 Event calendar
- 🎯 Upcoming events list
- 🔍 Filter by date/category
- 📝 Event details
- 🗺️ Location on map
- 🎟️ Registration link
- ❤️ Save event

---

### 8. 🔍 Live Search

**Global Search**

**Features:**
- ⚡ Real-time search
- 📝 Search across:
  - Blog posts
  - Pages
  - Locations (POIs)
  - Components
- 🎯 Keyboard navigation (Ctrl+K / Cmd+K)
- 🏃 Debounced (300ms)
- 🔝 Smart ranking (exact match first)

---

### 9. 🌙 Dark Mode

**Global Toggle**

- 🌙 Dark/Light theme
- 💾 Remember preference (localStorage)
- 🎨 Smooth transition
- 📱 Mobile friendly

---

### 10. 🌤️ Weather Widget

**Real-time Weather for Bandung**

**Info:**
- 🌡️ Current temperature
- ☁️ Weather condition
- 💨 Wind speed
- 💧 Humidity
- 🔄 Auto-refresh (30 min)

---

### 11. 📧 Newsletter Subscription

**Homepage Footer**

**Features:**
- ✉️ Email subscription
- 🤖 Spam protection
- 📧 Confirmation email
- 🔗 Unsubscribe link
- ⏱️ 5-year unsubscribe token

---

### 12. 💬 Feedback Form

**Lokasi:** `/pages/kritik-dan-saran/`

**Features:**
- ⭐ Rating (1-10 stars)
- 🏷️ Category selection
- 💬 Message textarea
- 👤 Anonymous option
- ⏱️ Rate limiting (5 sec)
- ✅ Validation

---

### 13. 📍 Things to Do

**Lokasi:** `/things-to-do`

**Fitur:**
- 🍽️ Kuliner recommendations
- 🏛️ Budaya & sejarah
- 🎨 Arts & crafts
- 🏊 Olahraga & rekreasi
- 👨‍👩‍👧‍👦Family activities
- 🗺️ Interactive map
- 🔍 Filter by type

---

## 🛠️ TEKNOLOGI

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 7.4+, MySQL 5.7+ |
| **Frontend** | Vanilla JavaScript, Bootstrap 5 |
| **Database** | MySQL (InnoDB) |
| **Mapping** | Leaflet.js, OpenStreetMap |
| **Editor** | CodeMirror, Quill.js |
| **Email** | PHPMailer (SMTP) |
| **Styling** | CSS3, OKLCH colors |
| **API** | REST (JSON) |
| **Security** | CSRF tokens, SSL/TLS, Prepared statements |

---

## 🔐 SECURITY FEATURES

✅ CSRF token validation
✅ Session management (HTTPOnly cookies)
✅ SQL injection prevention (prepared statements)
✅ XSS prevention (sanitization, htmlspecialchars)
✅ File upload validation (MIME type, size)
✅ Password hashing (bcrypt)
✅ Rate limiting (feedback, login)
✅ Admin authentication
✅ HTTPS support
✅ SameSite cookie policy

---

## 📊 DATABASE TABLES

**Core Tables:**
- `users` - User accounts
- `allcontent_posts` - Blog posts
- `allcontent_categories` - Blog categories
- `pages` - Custom pages
- `admin_items` - UI components (CMPT)
- `admin_setting` - Site settings

**Location Management:**
- `poi` - Points of Interest
- `poi_categories` - POI categories
- `poi_photos` - Community photos
- `poi_reviews` - Location reviews

**User Features:**
- `trips` - Saved trip plans
- `trip_items` - Trip stops
- `subscribers` - Newsletter subscribers
- `newsletters` - Newsletter records
- `reactions` - Page/post likes
- `feedback` - User feedback

**Analytics:**
- `analytics` - Page view tracking

---

## 📁 STRUKTUR FOLDER

```
flatty-cms/
├── config/              # Configuration files
│   ├── app.php         # App constants
│   ├── db.php          # Database connection
│   └── key.php         # Security keys
│
├── lib/                # Backend logic
│   ├── blog-actions.php
│   ├── pages-builder-actions.php
│   ├── cmpt-actions.php
│   ├── poi-actions.php
│   ├── analytics.php
│   ├── newsletter-actions.php
│   ├── mailer.php
│   ├── helper.php
│   └── [utility files]
│
├── public/             # Public files
│   ├── admin/
│   │   ├── index.php   # Admin router
│   │   ├── router.php
│   │   ├── login.php
│   │   └── logout.php
│   │
│   ├── api/            # REST APIs
│   │   ├── api-search.php
│   │   ├── api-feedback.php
│   │   ├── map/
│   │   │   ├── api-trips.php
│   │   │   ├── api-gallery.php
│   │   │   ├── api-review.php
│   │   │   └── api-route.php
│   │   └── [+10 API files]
│   │
│   ├── assets/         # Static assets
│   │   ├── js/
│   │   │   ├── trip.js
│   │   │   ├── gallery.js
│   │   │   ├── live-search.js
│   │   │   └── [+10 files]
│   │   ├── css/
│   │   │   ├── flattypurple.css
│   │   │   ├── etc.css
│   │   │   └── [+5 files]
│   │   └── images/
│   │
│   ├── pages/          # Generated pages
│   ├── uploads/        # User uploads
│   └── errors/         # Error pages
│
├── src/                # Source files
│   └── views/
│       ├── admin/      # Admin templates
│       ├── apps/       # Frontend apps
│       ├── user/       # User pages
│       ├── pages/      # Content pages
│       ├── header.php
│       ├── footer.php
│       └── partials/
│
├── bootstrap.php       # Error handling
├── maintenance.php     # Maintenance mode
├── index.php          # Entry point
└── composer.json      # Dependencies
```

---

## ⚙️ INSTALASI

### 1. Requirements
```
PHP 7.4+
MySQL 5.7+
Apache 2.4+ (with mod_rewrite)
Composer
```

### 2. Setup Database
```bash
# Import database
mysql -u username -p database_name < flatty.sql
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Configure Files
```php
// config/app.php
// config/db.php (database credentials)
// config/key.php (security keys)
```

### 5. Set Permissions
```bash
chmod 755 public/uploads/
chmod 755 public/pages/
chmod 755 cache/
chmod 755 errors/
```

### 6. Admin Login
```
URL: /admin/login
Default: Check database seeding
```

---

## 📝 KONFIGURASI

### Environment Variables

**config/app.php:**
```php
define("SITE_NAME", "AYOKEBANDUNG.ID");
define("APP_NAME", "FLATTY-CMS");
define("APP_TIMEZONE", "Asia/Jakarta");
define("DEBUG_MODE", in_array($_SERVER["SERVER_NAME"], ["localhost"]));
```

**config/db.php:**
```php
define("DB_HOST", "localhost");
define("DB_NAME", "flattycms");
define("DB_USER", "root");
define("DB_PASS", "password");
```

### Email Configuration

**lib/mailer.php:**
```php
SMTP_USER = "your-email@gmail.com"
SMTP_PASS = "app-password"
SMTP_HOST = "smtp.gmail.com"
SMTP_PORT = 587
```

### Maintenance Mode

**public/admin/setting.php:**
```
Toggle: Aktifkan/nonaktifkan
Bypass Key: Diset di config/key.php
```

---

## 🚀 DEPLOYMENT

### Production Checklist

- ✅ Set DEBUG_MODE = false
- ✅ Configure HTTPS/SSL
- ✅ Set strong JWT keys
- ✅ Configure email SMTP
- ✅ Set file permissions (755 for dirs, 644 for files)
- ✅ Database backups
- ✅ Update security headers
- ✅ Enable caching
- ✅ Monitor error logs

---

## 📞 SUPPORT

For issues & features:
- 📧 Email: support@ayokebandung.id
- 🐛 GitHub Issues
- 💬 Community Forum

---

## 📄 LICENSE

Flatty CMS © 2025 - Personal Project

---

**Happy Building! 🎉**
