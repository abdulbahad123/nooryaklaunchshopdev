# Production Deployment Guide - Custom Domain Database Switching

## 🚀 Deployment to Production Server

This guide covers deploying the dynamic custom domain database switching to your production server at `cockroachjantaparty.top`.

---

## 📋 Pre-Deployment Checklist

### 1. Verify Current Production Setup:
- [x] Production site: `https://cockroachjantaparty.top/`
- [x] Main database: `bazaarwa_Launchshopdevdb`
- [x] Sass_admin database: `bazaarwa_Sass_admindb`
- [x] cPanel user: `bazaarwa`
- [x] Server path: `/home/bazaarwa/cockroachjantaparty.top/`

### 2. Verify Sass_admin Database:
```bash
# SSH into production server
mysql -u bazaarwa_sass_admindb -p'Bahad@123' bazaarwa_Sass_admindb

# Check tables exist
SHOW TABLES;

# Verify agency_products has db_name column
DESCRIBE agency_products;

# Check if any agencies have databases assigned
SELECT ap.id, a.name, a.slug, p.slug as product, ap.db_name 
FROM agency_products ap
INNER JOIN agencies a ON a.id = ap.agency_id
INNER JOIN products p ON p.id = ap.product_id
WHERE ap.db_name IS NOT NULL;
```

---

## 📁 Files to Upload to Production

### New Files (upload to production):
```
/home/bazaarwa/cockroachjantaparty.top/
├── app/
│   └── Http/
│       └── Middleware/
│           └── CustomDomainDatabaseMiddleware.php  ← NEW
└── resources/
    └── views/
        └── components/
            ├── database-status-indicator.blade.php  ← NEW
            └── database-status-badge.blade.php      ← NEW
```

### Modified Files (backup first, then upload):
```
/home/bazaarwa/cockroachjantaparty.top/
├── app/
│   ├── Http/
│   │   ├── Kernel.php                               ← MODIFIED
│   │   ├── Middleware/
│   │   │   └── TenantDatabaseMiddleware.php        ← MODIFIED
│   │   ├── Controllers/
│   │   │   └── Admin/
│   │   │       └── DashboardController.php         ← MODIFIED
│   │   └── Helpers/
│   │       └── Helper.php                          ← MODIFIED
└── config/
    └── database.php                                 ← NO CHANGES NEEDED
```

### Documentation Files (optional):
```
/home/bazaarwa/cockroachjantaparty.top/
├── DYNAMIC_DATABASE_IMPLEMENTATION.md
├── DYNAMIC_LOOKUP_FLOW.md
├── IMPLEMENTATION_SUMMARY.md
├── QUICK_REFERENCE.md
└── PRODUCTION_DEPLOYMENT.md
```

---

## 🔧 Step-by-Step Deployment

### Step 1: Backup Production Files

```bash
# SSH into production
ssh bazaarwa@cockroachjantaparty.top

# Navigate to site root
cd /home/bazaarwa/cockroachjantaparty.top

# Create backup directory
mkdir -p backups/$(date +%Y%m%d_%H%M%S)

# Backup files that will be modified
cp app/Http/Kernel.php backups/$(date +%Y%m%d_%H%M%S)/
cp app/Http/Middleware/TenantDatabaseMiddleware.php backups/$(date +%Y%m%d_%H%M%S)/
cp app/Http/Controllers/Admin/DashboardController.php backups/$(date +%Y%m%d_%H%M%S)/
cp app/Http/Helpers/Helper.php backups/$(date +%Y%m%d_%H%M%S)/
```

### Step 2: Upload New Files

Using FTP/SFTP or rsync:

```bash
# From your local development machine:
# Upload CustomDomainDatabaseMiddleware.php
scp d:\xamp\htdocs\launchshop_dev\app\Http\Middleware\CustomDomainDatabaseMiddleware.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/app/Http/Middleware/

# Upload Blade components
scp d:\xamp\htdocs\launchshop_dev\resources\views\components\database-status-*.blade.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/resources/views/components/
```

### Step 3: Upload Modified Files

```bash
# Upload modified files
scp d:\xamp\htdocs\launchshop_dev\app\Http\Kernel.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/app/Http/

scp d:\xamp\htdocs\launchshop_dev\app\Http\Middleware\TenantDatabaseMiddleware.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/app/Http/Middleware/

scp d:\xamp\htdocs\launchshop_dev\app\Http\Controllers\Admin\DashboardController.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/app/Http/Controllers/Admin/

scp d:\xamp\htdocs\launchshop_dev\app\Http\Helpers\Helper.php \
    bazaarwa@cockroachjantaparty.top:/home/bazaarwa/cockroachjantaparty.top/app/Http/Helpers/
```

### Step 4: Verify .env Configuration

```bash
# SSH into production
cd /home/bazaarwa/cockroachjantaparty.top

# Check if .env has required variables
grep SASS_ADMIN_DB .env
grep CPANEL_USER .env

# Should show:
# SASS_ADMIN_DB=bazaarwa_Sass_admindb
# DB_DATABASE_admin=bazaarwa_Sass_admindb
# DB_USERNAME_admin=bazaarwa_sass_admindb
# DB_PASSWORD_admin=Bahad@123
# CPANEL_USER=bazaarwa
```

Your production `.env` already has these variables, so no changes needed! ✅

### Step 5: Set File Permissions

```bash
# Set correct ownership
chown -R bazaarwa:bazaarwa /home/bazaarwa/cockroachjantaparty.top/app/Http/Middleware/CustomDomainDatabaseMiddleware.php
chown -R bazaarwa:bazaarwa /home/bazaarwa/cockroachjantaparty.top/resources/views/components/database-status-*.blade.php

# Set permissions
chmod 644 /home/bazaarwa/cockroachjantaparty.top/app/Http/Middleware/CustomDomainDatabaseMiddleware.php
chmod 644 /home/bazaarwa/cockroachjantaparty.top/resources/views/components/database-status-*.blade.php
```

### Step 6: Clear Caches

```bash
cd /home/bazaarwa/cockroachjantaparty.top

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Test Syntax

```bash
# Check PHP syntax
php -l app/Http/Middleware/CustomDomainDatabaseMiddleware.php
php -l app/Http/Kernel.php
php -l app/Http/Middleware/TenantDatabaseMiddleware.php
```

---

## 🧪 Testing in Production

### Test 1: Verify Sass_admin Connection

```bash
# Create test script
cat > test_sass_admin.php << 'EOF'
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $dsn = "mysql:host=localhost;dbname=bazaarwa_Sass_admindb;charset=utf8mb4";
    $pdo = new PDO($dsn, 'bazaarwa_sass_admindb', 'Bahad@123');
    echo "✅ Sass_admin connection successful!\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM agencies");
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    echo "✅ Found {$result->count} agencies\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM agency_products WHERE db_name IS NOT NULL");
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    echo "✅ Found {$result->count} agency products with databases\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
EOF

php test_sass_admin.php
rm test_sass_admin.php
```

### Test 2: Check Custom Domains

```bash
# Query custom domains
mysql -u bazaarwa_launchshopdevuser -p'Bahad@123' bazaarwa_Launchshopdevdb \
  -e "SELECT id, user_id, requested_domain, status FROM user_custom_domains;"
```

### Test 3: Access Main Domain

```bash
# Should use main database (bazaarwa_Launchshopdevdb)
curl -I https://cockroachjantaparty.top/
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

Should see:
```
[INFO] CustomDomainDB: Main host detected 'cockroachjantaparty.top' - using maindb
```

### Test 4: Access Custom Domain (if any approved)

```bash
# If you have a custom domain with status=1, test it:
curl -I https://funkiddoz.in/  # (example)
```

Should see in logs:
```
[INFO] CustomDomainDB: Found agency database from Sass_admin
       agency_name: ...
       db_name: bazaarwa_ps_...
[INFO] CustomDomainDB: Successfully switched to agency database
```

---

## 🔍 Monitoring & Verification

### Monitor Logs:

```bash
# Watch logs in real-time
tail -f /home/bazaarwa/cockroachjantaparty.top/storage/logs/laravel.log | grep CustomDomainDB
```

### Check Database Connections:

```bash
# Show active MySQL connections
mysql -u root -p -e "SHOW PROCESSLIST;"
```

### Verify Helper Functions Work:

```bash
# Use Laravel Tinker
cd /home/bazaarwa/cockroachjantaparty.top
php artisan tinker

# Test helper functions
>>> getCurrentDatabaseName();
=> "bazaarwa_Launchshopdevdb"

>>> isUsingAgencyDb();
=> false

>>> exit
```

---

## 🚨 Rollback Plan (If Needed)

If something goes wrong, you can quickly rollback:

```bash
# Restore from backup
cd /home/bazaarwa/cockroachjantaparty.top
BACKUP_DIR=backups/$(ls -t backups/ | head -1)

# Restore modified files
cp $BACKUP_DIR/Kernel.php app/Http/
cp $BACKUP_DIR/TenantDatabaseMiddleware.php app/Http/Middleware/
cp $BACKUP_DIR/DashboardController.php app/Http/Controllers/Admin/
cp $BACKUP_DIR/Helper.php app/Http/Helpers/

# Remove new middleware (if causing issues)
rm app/Http/Middleware/CustomDomainDatabaseMiddleware.php

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "✅ Rollback complete!"
```

---

## 📊 Production Environment Details

### Current Setup:
```
Domain: https://cockroachjantaparty.top/
Server: cPanel hosting
Path: /home/bazaarwa/cockroachjantaparty.top/
PHP: PHP 8.x

Databases:
- Main: bazaarwa_Launchshopdevdb
- Sass_admin: bazaarwa_Sass_admindb
- Agency DBs: bazaarwa_ps_*_launchsh (pattern)

Credentials:
- Main DB User: bazaarwa_launchshopdevuser
- Sass_admin User: bazaarwa_sass_admindb
- Password: Bahad@123
```

### .env Variables (Already Configured):
```env
SASS_ADMIN_DB=bazaarwa_Sass_admindb
DB_DATABASE_admin=bazaarwa_Sass_admindb
DB_USERNAME_admin=bazaarwa_sass_admindb
DB_PASSWORD_admin=Bahad@123
CPANEL_USER=bazaarwa
```

---

## ✅ Post-Deployment Checklist

- [ ] All files uploaded successfully
- [ ] File permissions set correctly
- [ ] Caches cleared
- [ ] Syntax check passed
- [ ] Sass_admin connection works
- [ ] Main domain loads correctly
- [ ] Custom domain routing works (if applicable)
- [ ] Logs show correct messages
- [ ] Helper functions work in Tinker
- [ ] No PHP errors in logs
- [ ] Backup created and verified

---

## 🎯 Deployment Summary

**Files Added:**
1. `app/Http/Middleware/CustomDomainDatabaseMiddleware.php`
2. `resources/views/components/database-status-indicator.blade.php`
3. `resources/views/components/database-status-badge.blade.php`

**Files Modified:**
1. `app/Http/Kernel.php`
2. `app/Http/Middleware/TenantDatabaseMiddleware.php`
3. `app/Http/Controllers/Admin/DashboardController.php`
4. `app/Http/Helpers/Helper.php`

**Configuration:**
- ✅ .env already has required variables
- ✅ No config/database.php changes needed
- ✅ Dynamic database resolution from Sass_admin

**Impact:**
- Main domain: NO change (continues using main database)
- Custom domains (approved): Automatically switches to agency database
- Custom domains (pending/rejected): Uses main database

---

## 📞 Support & Troubleshooting

### Common Issues:

**Issue: "Sass_admin connection failed"**
```bash
# Verify credentials
mysql -u bazaarwa_sass_admindb -p'Bahad@123' bazaarwa_Sass_admindb
# Should connect without errors
```

**Issue: "Custom domain not switching"**
```bash
# Check custom domain status
mysql -u bazaarwa_launchshopdevuser -p'Bahad@123' bazaarwa_Launchshopdevdb \
  -e "SELECT * FROM user_custom_domains WHERE status = 1;"
```

**Issue: "Agency database not found"**
```bash
# List agency databases
mysql -u bazaarwa_sass_admindb -p'Bahad@123' bazaarwa_Sass_admindb \
  -e "SELECT ap.db_name FROM agency_products ap WHERE ap.db_name IS NOT NULL;"
```

---

## 🎉 Success Criteria

✅ Deployment completes without errors  
✅ Main site loads normally  
✅ Logs show "Main host detected" for main domain  
✅ Custom domains switch to agency databases (when approved)  
✅ No PHP errors in logs  
✅ Performance remains normal  
✅ All features work as expected  

---

**Ready to deploy! Follow the steps carefully and monitor the logs.** 🚀

**Deployment Time Estimate: 15-30 minutes**
