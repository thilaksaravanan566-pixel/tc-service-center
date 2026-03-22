# TC Service Center 

A comprehensive full-stack web application built for a modern Laptop & Desktop Service Center Business. This platform integrates a Customer Portal (e-commerce & service booking), an Advanced Admin Dashboard, a Delivery Management System, and an Inventory/CRM platform seamlessly into one ecosystem.

## 🌟 Key Features

### 1. Customer Portal 
* **Modern e-commerce UI** (Similar to Amazon) with a custom Dark/Light hybrid theme.
* **Service Booking System:** Book repairs for Laptops, Desktops, or MacBooks. Describe issues, upload device images, and schedule pickups.
* **Shop:** Buy new/refurbished devices, spare parts, and accessories directly.
* **Order Tracking:** Track repair progress and delivery status in real-time.
* **Customer Dashboard:** Manage addresses, view service history, and download PDF invoices.

### 2. Admin & Business Management Panel
* **Premium Dark Dashboard:** High-end aesthetic with dynamic charts and statistics tracking revenue, active orders, and inventory.
* **Service Workflow Management:** Transition repair states (Received -> Diagnosed -> Repairing -> Testing -> Ready -> Delivered).
* **Inventory Control:** Add, edit, and track stock for devices and spare parts. Real-time low-stock alerts.
* **CRM & User Management:** Manage Admins, Technicians, Delivery Partners, and Customers.

### 3. Logistics & Delivery System
* **Delivery Assignment:** Admins can assign verified Delivery Partners to specific orders.
* **Partner Profiles:** Track delivery partner vehicle numbers, mobile numbers, and active routes.
* **Delivery Tracking:** Status updates automatically reflect on the customer's portal.

## 🛠 Tech Stack

* **Backend:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade Templating, Tailwind CSS v4, Alpine.js
* **Database:** MySQL
* **Build Tool:** Vite

---

## 🚀 Installation & Local Development

Follow these steps to set up the application on a local development environment.

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL Database

### Step-by-Step Guide

1. **Clone the Project**
   *(If not already cloned)*

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node/Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   Copy the example environment file and generate a unique app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   Open the `.env` file and configure your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

6. **Migrate & Seed the Database**
   Run the migrations to create the tables, and seed it with dummy data/admin accounts:
   ```bash
   php artisan migrate --seed
   ```

7. **Link Storage**
   Create a symbolic link to make your image uploads publicly accessible:
   ```bash
   php artisan storage:link
   ```

8. **Start the Development Servers**
   You need to run both the Laravel backend server and the Vite frontend compiler simultaneously (in two separate terminal windows):
   
   *Terminal 1 (Backend):*
   ```bash
   php artisan serve
   ```
   
   *Terminal 2 (Frontend Assets & Tailwind):*
   ```bash
   npm run dev
   ```

---

## 💻 Usage & Access Points

Once the servers are running (usually at `http://127.0.0.1:8000`), you can access the application:

* **Customer Portal (Public Storefront):**  
  `http://127.0.0.1:8000/`  
  *Customers can register their own accounts directly from the UI.*

* **Admin Panel:**  
  `http://127.0.0.1:8000/admin/login`  
  *Log in with the system administrator credentials to manage the business.*

### Default Admin Credentials (If Seeded)
* **Email:** admin@example.com *(Replace with actual seeded email if different)*
* **Password:** password 

---

## 🚢 Production Deployment

When transitioning this application to a live production server (VPS, Forge, cPanel), follow this security and optimization checklist:

1. **Optimize Autoloader & Dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

2. **Build Production Frontend Assets:**
   ```bash
   npm run build
   ```

3. **Secure the Environment File (`.env`):**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://www.your-domain.com
   ```

4. **Cache Configurations (Critical for Speed):**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

5. **Permissions:**
   Ensure your web server (Nginx/Apache) has ownership and write access to the `storage` and `bootstrap/cache` directories.
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

6. **Run Live Migrations:**
   ```bash
   php artisan migrate --force
   ```
