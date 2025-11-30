# 🚀 DEPLOYMENT GUIDE - TodoList Application

**Last Updated:** November 2, 2025  
**Version:** 1.0.0

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### 1. Code Preparation
- [x] All features implemented
- [x] Code reviewed
- [ ] Tests written and passing
- [ ] Documentation complete
- [ ] Dependencies updated

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database
DB_CONNECTION=mysql
DB_HOST=your_host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Set app environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 3. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed database (optional for demo data)
php artisan db:seed --force

# Or specific seeders
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=TaskSeeder
```

---

## 🔧 DEPLOYMENT STEPS

### OPTION 1: Shared Hosting (cPanel)

#### Step 1: Prepare Files
```bash
# Local machine
composer install --optimize-autoloader --no-dev
npm run build
zip -r todolist.zip . -x "node_modules/*" -x ".git/*"
```

#### Step 2: Upload
1. Upload `todolist.zip` via cPanel File Manager
2. Extract in `/home/username/`
3. Move `public` folder contents to `public_html`

#### Step 3: Configure
```bash
# In cPanel Terminal
cd /home/username/todolist
composer install --no-dev
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 4: Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
```

---

### OPTION 2: VPS/Cloud Server (Ubuntu + Nginx)

#### Step 1: Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install dependencies
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-curl composer git -y

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

#### Step 2: Clone & Install
```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/todolist.git
cd todolist

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/todolist
sudo chmod -R 755 storage bootstrap/cache
```

#### Step 3: Configure Nginx
```nginx
# /etc/nginx/sites-available/todolist
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/todolist/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \\.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/todolist /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Step 4: Setup Database
```bash
# Login to MySQL
sudo mysql

# Create database and user
CREATE DATABASE todolist_db;
CREATE USER 'todolist_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON todolist_db.* TO 'todolist_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 5: Configure Application
```bash
cd /var/www/todolist

# Copy and edit .env
cp .env.example .env
nano .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 6: SSL with Let's Encrypt
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
sudo systemctl reload nginx
```

---

### OPTION 3: Docker Deployment

#### Docker Compose Configuration
```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    depends_on:
      - db
      - redis

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: todolist_db
      MYSQL_USER: todolist_user
      MYSQL_PASSWORD: password
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - dbdata:/var/lib/mysql

  redis:
    image: redis:alpine
    ports:
      - "6379:6379"

volumes:
  dbdata:
```

```bash
# Deploy with Docker
docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
```

---

## ⚙️ POST-DEPLOYMENT

### 1. Verify Installation
```bash
# Check routes
php artisan route:list

# Check migrations
php artisan migrate:status

# Test application
curl https://yourdomain.com
```

### 2. Setup Cron Jobs
```bash
# Edit crontab
crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/todolist && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Setup Queue Workers
```bash
# Install Supervisor
sudo apt install supervisor -y

# Create configuration
sudo nano /etc/supervisor/conf.d/todolist-worker.conf
```

```ini
[program:todolist-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/todolist/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/todolist/storage/logs/worker.log
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start todolist-worker:*
```

### 4. Setup Backups
```bash
# Create backup script
nano /usr/local/bin/backup-todolist.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backups/todolist"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u todolist_user -p'password' todolist_db > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/todolist/storage

# Delete old backups (keep last 7 days)
find $BACKUP_DIR -type f -mtime +7 -delete
```

```bash
# Make executable
chmod +x /usr/local/bin/backup-todolist.sh

# Add to crontab (daily at 2 AM)
0 2 * * * /usr/local/bin/backup-todolist.sh
```

---

## 🔒 SECURITY HARDENING

### 1. Application Security
```bash
# Force HTTPS in .env
APP_URL=https://yourdomain.com

# Disable debug mode
APP_DEBUG=false

# Secure session
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### 2. Server Security
```bash
# Firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable

# Fail2ban
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
```

### 3. File Permissions
```bash
sudo chown -R www-data:www-data /var/www/todolist
sudo find /var/www/todolist -type f -exec chmod 644 {} \\;
sudo find /var/www/todolist -type d -exec chmod 755 {} \\;
sudo chmod -R 775 storage bootstrap/cache
```

---

## 📊 MONITORING

### 1. Laravel Telescope (Development/Staging)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### 2. Application Monitoring
```bash
# Install Laravel Horizon (for queues)
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

### 3. Server Monitoring
- Setup Uptime monitoring (UptimeRobot, Pingdom)
- Configure error tracking (Sentry, Bugsnag)
- Setup performance monitoring (New Relic, DataDog)

---

## 🔄 UPDATE PROCEDURE

### Rolling Update
```bash
# 1. Backup current version
cp -r /var/www/todolist /var/www/todolist_backup_$(date +%Y%m%d)

# 2. Pull latest code
cd /var/www/todolist
git pull origin main

# 3. Update dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx
sudo supervisorctl restart todolist-worker:*
```

---

## 🆘 TROUBLESHOOTING

### Common Issues

**1. 500 Internal Server Error**
```bash
# Check permissions
sudo chown -R www-data:www-data storage bootstrap/cache

# Check logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

**2. Database Connection Failed**
```bash
# Verify credentials in .env
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

**3. Assets Not Loading**
```bash
# Rebuild assets
npm run build

# Clear browser cache
# Check nginx configuration
```

**4. Queue Jobs Not Processing**
```bash
# Check worker status
sudo supervisorctl status todolist-worker:*

# Restart workers
sudo supervisorctl restart todolist-worker:*
```

---

## 📞 SUPPORT

**Documentation:**
- Laravel: https://laravel.com/docs/10.x
- Nginx: https://nginx.org/en/docs/
- MySQL: https://dev.mysql.com/doc/

**Emergency Rollback:**
```bash
# Restore from backup
sudo systemctl stop nginx
mv /var/www/todolist /var/www/todolist_failed
mv /var/www/todolist_backup_YYYYMMDD /var/www/todolist
mysql -u root -p todolist_db < /backups/todolist/db_YYYYMMDD.sql
sudo systemctl start nginx
```

---

**Deployment Complete!** 🎉

Your TodoList application should now be live and accessible.
