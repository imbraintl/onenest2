# OneNest PHP Setup Instructions

## 🚨 **Fixing the "Forbidden" Error**

The "Forbidden" error occurs because:
1. Apache doesn't have proper permissions
2. The document root isn't pointing to the `public/` folder
3. Missing `.htaccess` file for URL rewriting

## 📁 **Correct Directory Structure**

Your web server should point to the `public/` folder as the document root:

```
/var/www/html/onenest/
├── composer.json
├── .env
├── config/
├── database/
├── src/
├── views/
└── public/              ← This should be your Apache DocumentRoot
    ├── index.php        ← Entry point
    ├── .htaccess        ← URL rewriting rules
    └── css/             ← Static assets
```

## ⚙️ **Apache Configuration**

### Option 1: Update Apache Virtual Host (Recommended)

Edit your Apache virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName onenest.ihostcp.com
    DocumentRoot /var/www/html/onenest/public
    
    <Directory /var/www/html/onenest/public>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/mummycare_error.log
    CustomLog ${APACHE_LOG_DIR}/mummycare_access.log combined
</VirtualHost>
```

### Option 2: Move Files to Current Web Root

If you can't change the DocumentRoot, move the contents of `public/` to your current web root:

```bash
# Move public folder contents to web root
mv public/* /var/www/html/
mv public/.htaccess /var/www/html/

# Update paths in index.php
# Change: require_once __DIR__ . '/../vendor/autoload.php';
# To:     require_once __DIR__ . '/vendor/autoload.php';
```

## 🔧 **Setup Steps**

### 1. Install Dependencies
```bash
cd /var/www/html/onenest
composer install
```

### 2. Set Permissions
```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/html/onenest

# Set proper permissions
sudo chmod -R 755 /var/www/html/onenest
sudo chmod -R 775 /var/www/html/onenest/uploads
```

### 3. Configure Database
```bash
# Copy environment file
cp .env.example .env

# Edit .env with your database credentials
nano .env
```

Update these values in `.env`:
```
DB_HOST=localhost
DB_DATABASE=onenest
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
APP_URL=https://onenest.ihostcp.com
```

### 4. Create Database and Run Migrations
```sql
-- Connect to MySQL
mysql -u root -p

-- Create database
CREATE DATABASE onenest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (optional)
CREATE USER 'mummycare_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON onenest.* TO 'mummycare_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;

-- Run migrations
mysql -u root -p onenest < database/migrations/001_create_tables.sql
```

### 5. Enable Apache Modules
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

## 🔍 **How Routing Works**

### URL Rewriting Flow:
1. **Apache** receives request (e.g., `/dashboard/user`)
2. **`.htaccess`** redirects all requests to `index.php`
3. **Router class** parses the URL and matches routes
4. **Controller** handles the request and loads appropriate view
5. **View** renders the HTML response

### Example Route Flow:
```
URL: https://onenest.ihostcp.com/dashboard/user
↓
Apache: Check if file exists → No
↓
.htaccess: Redirect to index.php
↓
Router: Match '/dashboard/user' → DashboardController::userDashboard()
↓
Controller: Load user data, render dashboard view
↓
View: Return HTML response
```

## 🐛 **Troubleshooting**

### Check Apache Error Logs:
```bash
sudo tail -f /var/log/apache2/error.log
```

### Test PHP Configuration:
Create `info.php` in your web root:
```php
<?php phpinfo(); ?>
```

### Verify File Permissions:
```bash
ls -la /var/www/html/onenest/
```

### Test Database Connection:
```php
<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=onenest', 'username', 'password');
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

## 📋 **Quick Checklist**

- [ ] Apache DocumentRoot points to `public/` folder
- [ ] `.htaccess` file exists in `public/` folder
- [ ] Composer dependencies installed (`vendor/` folder exists)
- [ ] `.env` file configured with correct database credentials
- [ ] Database created and migrations run
- [ ] Proper file permissions set (755 for folders, 644 for files)
- [ ] Apache `mod_rewrite` enabled
- [ ] PHP extensions enabled (PDO, MySQL)

Once these steps are completed, your OneNest platform should be accessible and fully functional!