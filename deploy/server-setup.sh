#!/bin/bash
##############################################################
# Thambu Computers — Full Server Deployment Script
# Step 5: SSL Setup + Complete Production Bootstrap
#
# RUN AS: sudo bash deploy/server-setup.sh
# TESTED ON: Ubuntu 22.04 LTS / Debian 12
##############################################################

set -e  # Exit on any error

# ─── Config ─────────────────────────────────────────────────
APP_DIR="/var/www/thambucomputers"
DOMAIN="thambucomputers.com"
SUBDOMAINS="admin.${DOMAIN},dealer.${DOMAIN},customer.${DOMAIN},www.${DOMAIN}"
PHP_VERSION="8.2"
DB_NAME="thambu_erp"
DB_USER="thambu_user"
# DB_PASS is read from user input below

echo ""
echo "======================================================"
echo " Thambu Computers — Production Server Setup"
echo "======================================================"
echo ""

# ─── Step 5.1: System Dependencies ─────────────────────────
echo "[1/8] Installing system dependencies..."
apt-get update -qq
apt-get install -y -qq \
    software-properties-common \
    curl zip unzip git supervisor cron \
    nginx certbot python3-certbot-nginx \
    mysql-server redis-server

# ─── Step 5.2: PHP 8.2 ──────────────────────────────────────
echo "[2/8] Installing PHP ${PHP_VERSION}..."
add-apt-repository -y ppa:ondrej/php
apt-get update -qq
apt-get install -y -qq \
    php${PHP_VERSION} \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-redis \
    php${PHP_VERSION}-intl

# ─── Step 5.3: Composer ─────────────────────────────────────
echo "[3/8] Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# ─── Step 5.4: MySQL Database ───────────────────────────────
echo "[4/8] Configuring MySQL..."
read -s -p "Enter a strong DB password for '${DB_USER}': " DB_PASS
echo ""

mysql -u root <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
echo "  ✓ Database '${DB_NAME}' and user '${DB_USER}' created."

# ─── Step 5.5: Deploy Application ───────────────────────────
echo "[5/8] Deploying Laravel application..."
if [ ! -d "$APP_DIR" ]; then
    echo "  → Creating ${APP_DIR}..."
    mkdir -p $APP_DIR
    echo "  ⚠  Upload your project to ${APP_DIR} via git clone or SFTP"
    echo "     Then re-run this script, or continue manually from Step 5.6"
    exit 0
fi

cd $APP_DIR
composer install --no-dev --optimize-autoloader --quiet
npm install --silent
npm run build

# Copy production .env
if [ ! -f ".env" ]; then
    cp deploy/.env.production .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env
    php artisan key:generate
    echo "  ✓ .env configured. Please update remaining values."
fi

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# File permissions
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# ─── Step 5.6: Nginx ────────────────────────────────────────
echo "[6/8] Configuring Nginx..."
# First, get SSL cert on HTTP (before HTTPS redirect kicks in)
# Use temporary HTTP-only config
cat > /etc/nginx/sites-available/${DOMAIN}.conf << 'NGINX'
server {
    listen 80;
    server_name thambucomputers.com www.thambucomputers.com admin.thambucomputers.com dealer.thambucomputers.com customer.thambucomputers.com;
    root /var/www/thambucomputers/public;
    index index.php;
    location /.well-known/acme-challenge/ { root /var/www/html; }
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/${DOMAIN}.conf /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
echo "  ✓ Nginx running on HTTP temporarily for SSL issuance."

# ─── Step 5.7: SSL Certificate ──────────────────────────────
echo "[7/8] Obtaining SSL certificate via Let's Encrypt..."
certbot --nginx \
    -d ${DOMAIN} \
    -d www.${DOMAIN} \
    -d admin.${DOMAIN} \
    -d dealer.${DOMAIN} \
    -d customer.${DOMAIN} \
    --non-interactive \
    --agree-tos \
    --email admin@${DOMAIN} \
    --redirect

echo "  ✓ SSL certificates installed. HTTPS is now active."

# Deploy full production Nginx config
cp $APP_DIR/deploy/nginx/thambucomputers.conf /etc/nginx/sites-available/${DOMAIN}.conf
nginx -t && systemctl reload nginx
echo "  ✓ Production Nginx config deployed."

# Auto-renew cron
(crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet") | crontab -
echo "  ✓ SSL auto-renew cron added."

# ─── Step 5.8: Supervisor (Queue Workers) ───────────────────
echo "[8/8] Configuring queue worker with Supervisor..."
cat > /etc/supervisor/conf.d/thambu-worker.conf << SUPERVISOR
[program:thambu-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ${APP_DIR}/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=${APP_DIR}/storage/logs/worker.log
stdout_logfile_maxbytes=50MB
stdout_logfile_backups=5
SUPERVISOR

# Laravel scheduler cron
(crontab -l -u www-data 2>/dev/null; echo "* * * * * php ${APP_DIR}/artisan schedule:run >> /dev/null 2>&1") | crontab -u www-data -

supervisorctl reread
supervisorctl update
supervisorctl start thambu-worker:*

echo ""
echo "======================================================"
echo " ✅ Deployment Complete!"
echo "======================================================"
echo ""
echo " 🌐 Main site:     https://${DOMAIN}"
echo " 🔒 Admin panel:   https://admin.${DOMAIN}"
echo " 🏪 Dealer portal: https://dealer.${DOMAIN}"
echo " 👤 Customer:      https://customer.${DOMAIN}"
echo ""
echo " ⚠  Important next steps:"
echo "    1. Update .env with your WHATSAPP_API_TOKEN"
echo "    2. Update .env with your MAIL credentials"
echo "    3. Run: php artisan db:seed --class=AdminSeeder"
echo "    4. Visit https://admin.${DOMAIN} to verify"
echo ""
