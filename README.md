# 🖥️ TC Service Center — ERP System

> A next-generation, full-featured **Computer Service Center ERP** built with Laravel 12, featuring dual billing, GST compliance, live delivery tracking, customer portal, dealer management, and an Anti-Gravity glassmorphism UI.

---

## ✨ Feature Highlights

| Module | Description |
|--------|-------------|
| 🧾 **Dual Billing** | Estimation (Quotation) + GST Tax Invoice modes |
| 🏷️ **GST Compliance** | B2B/B2C detection, CGST/SGST/IGST auto-split |
| 🖨️ **Invoice Print System** | A4, Thermal, PDF download, QR code |
| 🚚 **Live Delivery Tracking** | GPS tracking for delivery partners, real-time map |
| 👨‍💼 **Customer Portal** | Dashboard, service tracking, shop, warranty claims |
| 🏪 **Dealer Portal** | Dealer orders, invoices, dealer dashboard |
| 🔧 **Service Management** | Job cards, technician assignment, status workflow |
| 💰 **Finance Module** | Revenue, expenses, salary management |
| 📊 **Analytics Dashboard** | Revenue graphs, KPIs, branch analytics |
| 🛒 **Spare Parts Shop** | Customer-facing product catalog & ordering |
| 📡 **CRM Module** | Customer follow-ups, notifications |
| 🔒 **Multi-role Auth** | Admin, Technician, Dealer, Customer, Delivery Partner |
| 📱 **Delivery Mobile API** | REST API for delivery partner mobile app |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12.x (PHP 8.2+) |
| **Database** | SQLite (default) / MySQL / PostgreSQL |
| **Frontend** | Blade, Tailwind CSS (CDN), Alpine.js |
| **PDF** | barryvdh/laravel-dompdf |
| **Auth** | Laravel Session Auth + Sanctum (API) |
| **Maps** | Leaflet.js (OpenStreetMap) |
| **Build Tool** | Vite + npm |

---

## ⚡ Quick Start (5 Minutes)

### Prerequisites

Make sure you have:
- **PHP** >= 8.2 — [download](https://www.php.net/downloads)
- **Composer** >= 2.x — [download](https://getcomposer.org)
- **Node.js** >= 18 + **npm** — [download](https://nodejs.org)

> No MySQL required — uses SQLite by default. Zero database setup needed.

---

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/tc-service-center.git
cd tc-service-center
```

---

### 2. Install PHP Dependencies

```bash
composer install
```

---

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

The app uses **SQLite by default** — no database configuration needed.
The SQLite file is auto-created at `database/database.sqlite`.

---

### 4. Run Migrations & Seed Demo Data

```bash
php artisan migrate --force
php artisan db:seed --class=SuperAppSeeder
php artisan db:seed --class=CustomizationSeeder
php artisan db:seed --class=NotificationTemplateSeeder
```

---

### 5. Install Node Dependencies & Build Assets

```bash
npm install
npm run build
```

> For **development** with hot-reload, use `npm run dev` alongside `php artisan serve`.

---

### 6. Start the Server

```bash
php artisan serve
```

Open your browser: **http://127.0.0.1:8000**

---

## 🔑 Default Login Credentials

> All seeded users use these credentials after fresh installation.

| Role | Email | Password | Portal URL |
|------|-------|----------|------------|
| **Admin** | `admin@tcservice.com` | `admin123` | `/login` |
| **Technician** | `tech@tcservice.com` | `admin123` | `/login` |
| **Delivery Partner** | `delivery@tcservice.com` | `admin123` | `/login` |
| **Customer** | `customer@example.com` | `password123` | `/customer/login` |

> **Warning:** Change all passwords immediately before deploying to production.

---

## 🗂️ Portal URLs

| Portal | URL | Who |
|--------|-----|-----|
| Admin Dashboard | `/admin/dashboard` | Admin / Technician |
| Delivery Partner Dashboard | `/delivery-partner` | Delivery Partners |
| Customer Dashboard | `/customer/dashboard` | Customers |
| Dealer Dashboard | `/dealer/dashboard` | Dealers |
| Public Shop | `/shop` | Anyone |
| Device Tracking | `/track/{job_id}` | Anyone |
| Live Delivery Map (Admin) | `/admin/delivery/live-map` | Admin |
| Invoice Settings | `/admin/invoice-settings` | Admin |
| Delivery Partner Fleet | `/admin/delivery-partners` | Admin |

---

## 📋 Full Installation Guide

### Using MySQL Instead of SQLite

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tc_service_center
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database in MySQL:
```sql
CREATE DATABASE tc_service_center CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then run migrations:
```bash
php artisan migrate --force
php artisan db:seed --class=SuperAppSeeder
```

---

### Storage & File Uploads

Link the storage directory for logo and file uploads:
```bash
php artisan storage:link
```

---

### Queue Worker (for background jobs)

The app uses queued jobs for notifications. Run the queue worker:
```bash
php artisan queue:work
```

For production, use a process manager like **Supervisor**.

---

### One-Command Dev Start

The `composer.json` includes a `dev` script that starts everything together:

```bash
composer run dev
```

This concurrently runs:
- `php artisan serve` — web server
- `npm run dev` — Vite hot-reload
- `php artisan queue:listen` — background jobs
- `php artisan pail` — log tailing

---

### Password Reset Utility

If login credentials are lost, run the included reset script:

```bash
php reset_passwords.php
```

This sets all staff accounts to `admin123`.

---

## 🏗️ Project Structure

```
tc-service-center/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Auth/           # Login / Auth controllers
│   │   ├── Customer/       # Customer portal controllers
│   │   ├── Dealer/         # Dealer portal controllers
│   │   └── Api/Mobile/     # Mobile API for delivery app
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # All DB schema migrations
│   └── seeders/            # Demo data seeders
├── resources/
│   └── views/
│       ├── admin/          # Admin panel views
│       ├── auth/           # Login pages
│       ├── customer/       # Customer portal views
│       ├── dealer/         # Dealer portal views
│       └── delivery/       # Delivery partner dashboard
├── routes/
│   ├── web.php             # Web routes
│   ├── auth.php            # Authentication routes
│   └── api.php             # API routes (Sanctum)
└── public/                 # Public assets
```

---

## 🧾 Billing System

### Two Modes

| Mode | Prefix | Features |
|------|--------|----------|
| **Estimation** | `EST-001` | No GST, validity date, watermark, editable |
| **GST Tax Invoice** | `INV-001` | Full GST, CGST/SGST/IGST, legal format, locked |

### GST Logic

- **B2C** (no customer GST): CGST + SGST applied
- **B2B same state**: CGST + SGST split
- **B2B different state**: IGST applied

### Customer GSTIN Format
```
33ABCDE1234F1Z5
|└──────────────  State code (33 = Tamil Nadu)
 └─────────────── PAN embedded + checksum
```

---

## 🚚 Delivery System

### How It Works
1. Admin assigns a delivery partner to a service/product order
2. Delivery partner logs in → sees assigned orders on live map
3. GPS auto-pushes location every few seconds
4. Customer can track delivery in real-time via their portal
5. Partner marks order as **Picked Up** then **Delivered**

### Mobile API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/delivery/login` | Partner login (returns Sanctum token) |
| `GET` | `/api/v1/delivery/tasks` | Get assigned tasks |
| `POST` | `/api/v1/delivery/tasks/{id}/status` | Update delivery status |
| `POST` | `/api/delivery/location/update` | Push GPS location |
| `POST` | `/api/delivery/location/offline` | Set partner offline |
| `GET` | `/api/tracking/{orderId}/status` | Customer polls delivery status |

---

## 🔧 Configuration

### Company Settings
Go to **Admin -> Invoice Settings** to configure:
- Company name, logo, GST number, address
- Invoice header/footer text
- Terms & Conditions
- Show/hide fields (HSN, discount, signature)
- Theme color & font size

### Key Environment Variables

```env
APP_NAME="TC Service Center"
APP_URL=http://127.0.0.1:8000

# Database (SQLite default — no config needed)
DB_CONNECTION=sqlite

# Mail (log driver for development)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@tcservice.com"
MAIL_FROM_NAME="TC Service Center"
```

---

## 🚀 Production Deployment

```bash
# 1. Install without dev dependencies
composer install --no-dev --optimize-autoloader

# 2. Build frontend
npm run build

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run migrations
php artisan migrate --force

# 5. Link storage (for uploaded files)
php artisan storage:link

# 6. Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|---------|
| **Login not working** | Run `php reset_passwords.php` then try `admin123` |
| **500 Server Error** | Check `storage/logs/laravel.log` for the full error |
| **Blank white page** | Run `php artisan view:clear && php artisan cache:clear` |
| **Assets not loading** | Run `npm run build` to rebuild Vite assets |
| **Migrations failing** | Run `php artisan migrate:fresh --force --seed` (WARNING: deletes all data) |
| **Invoice PDF blank** | Ensure `php-gd` or `php-imagick` PHP extension is installed |
| **Session / CSRF errors** | Run `php artisan session:table && php artisan migrate` |
| **Storage files missing** | Run `php artisan storage:link` |

---

## 📄 License

MIT License — free to use, modify, and distribute for personal and commercial projects.

---

## 🙌 Credits

Built with love using:
- [Laravel](https://laravel.com) — The PHP Framework for Web Artisans
- [Tailwind CSS](https://tailwindcss.com) — Utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) — Lightweight reactive JS
- [Leaflet.js](https://leafletjs.com) — Open-source interactive maps
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) — PDF generation

---

*TC Service Center ERP © 2026 — Thambu Computers, Tamil Nadu, India*
