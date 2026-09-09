# Quick Start Guide - Custom Domain Database Switching

## 🚀 Setup (5 minutes)

### Step 1: Configure Environment Variables
Add to your `.env` file:

```env
AGENCY_DB_CONNECTION=mysql
AGENCY_DB_HOST=127.0.0.1
AGENCY_DB_PORT=3306
AGENCY_DB_DATABASE=agencydb
AGENCY_DB_USERNAME=root
AGENCY_DB_PASSWORD=root
```

### Step 2: Create Agency Database
```sql
CREATE DATABASE IF NOT EXISTS agencydb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 4: Test the Implementation
Access your application:
- **Main domain**: http://cockroachjantaparty.top → Uses maindb ✓
- **Custom domain**: http://funkiddoz.in → Uses agencydb ✓

---

## 📊 Check Database Status

### In PHP/Controller:
```php
// Check which database is active
if (isUsingAgencyDb()) {
    echo "Using Agency Database";
} else {
    echo "Using Main Database";
}

// Get database name
echo getCurrentDatabaseName(); // 'agencydb' or 'nooryak_launchshopp'

// Get custom domain info
$info = getCustomDomainInfo();
// ['user_id' => 123, 'domain' => 'funkiddoz.in', 'using_agency_db' => true]
```

### In Blade Views:
```blade
{{-- Show database status indicator --}}
@include('components.database-status-indicator')

{{-- Or minimal floating badge --}}
@include('components.database-status-badge')
```

### In Browser Console:
```javascript
// Check session (if exposed)
console.log(sessionStorage);
```

---

## 🎯 Admin Panel Usage

### Approve Custom Domain:
1. Go to Admin Panel → Custom Domains
2. Find the domain request
3. Click "Connect" or set status to 1
4. User receives email notification
5. Next access to that domain uses agencydb

### Reject Custom Domain:
1. Find the domain request
2. Click "Reject" or set status to 2
3. User receives rejection email
4. Domain continues using maindb

---

## � Verification Checklist

### ✅ Environment Setup:
- [ ] AGENCY_DB_* variables in .env
- [ ] agencydb database created
- [ ] agencydb accessible with credentials
- [ ] Cache cleared

### ✅ Domain Configuration:
- [ ] Custom domain added in admin panel
- [ ] Domain status set to 1 (Connected)
- [ ] DNS/CNAME configured correctly

### ✅ Testing:
- [ ] Main domain uses maindb
- [ ] Custom domain uses agencydb
- [ ] Helper functions work
- [ ] Visual indicators display correctly

---

## 🐛 Quick Troubleshooting

### Issue: Custom domain not switching to agencydb

**Quick Fix:**
```sql
-- Check domain status
SELECT * FROM user_custom_domains WHERE requested_domain = 'yourdomain.com';

-- Should show status = 1 (Connected)
-- If not, update:
UPDATE user_custom_domains SET status = 1 WHERE requested_domain = 'yourdomain.com';
```

### Issue: Database connection error

**Quick Fix:**
```bash
# Test MySQL connection
mysql -u root -p agencydb

# If fails, check credentials in .env
# Then restart server
```

### Issue: Wrong database being used

**Quick Fix:**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Clear session
php artisan session:flush
```

---

## 📋 Status Reference

| Status | Value | Description | Database Used |
|--------|-------|-------------|---------------|
| Pending | 0 | Awaiting approval | maindb |
| Connected | 1 | Approved ✅ | agencydb |
| Rejected | 2 | Denied | maindb |

---

## 🎨 Visual Indicators

### Full Alert (for dashboard pages):
```blade
@include('components.database-status-indicator')
```
Shows:
- 🟢 Agency Database Active (green alert)
- 🔵 Main Database Active (blue alert)
- Database name, custom domain, user ID

### Floating Badge (for all admin pages):
```blade
@include('components.database-status-badge')
```
Shows:
- Fixed bottom-right badge
- Color-coded indicator
- Hover tooltip with details

---

## 🔗 Related Files

### Core Implementation:
- `app/Http/Middleware/CustomDomainDatabaseMiddleware.php`
- `app/Http/Kernel.php`
- `config/database.php`
- `.env`

### Helper Functions:
- `app/Http/Helpers/Helper.php`
  - `isUsingAgencyDb()`
  - `getCustomDomainInfo()`
  - `getCurrentDatabaseName()`

### Visual Components:
- `resources/views/components/database-status-indicator.blade.php`
- `resources/views/components/database-status-badge.blade.php`

### Testing:
- `tests/CustomDomainDatabaseTest.php`

### Documentation:
- `CUSTOM_DOMAIN_DATABASE_SWITCHING.md` (detailed)
- `IMPLEMENTATION_SUMMARY.md` (overview)
- `QUICK_START_GUIDE.md` (this file)

---

## 💡 Common Use Cases

### Use Case 1: Add Database Indicator to Admin Dashboard
```blade
{{-- In resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')
    {{-- Show database status --}}
    @include('components.database-status-indicator')
    
    {{-- Rest of dashboard content --}}
@endsection
```

### Use Case 2: Add Floating Badge to Admin Layout
```blade
{{-- In resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html>
<head>...</head>
<body>
    {{-- Your layout content --}}
    
    {{-- Add floating badge --}}
    @include('components.database-status-badge')
</body>
</html>
```

### Use Case 3: Check Database in Controller
```php
public function someAction(Request $request)
{
    // Check which database we're using
    if (isUsingAgencyDb()) {
        // Custom domain - agency database logic
        $data = DB::table('agency_specific_table')->get();
    } else {
        // Main infrastructure - main database logic
        $data = DB::table('main_table')->get();
    }
    
    return view('some.view', compact('data'));
}
```

---

## 📞 Need Help?

1. **Check Logs**: `storage/logs/laravel.log`
2. **Run Tests**: `php artisan test --filter CustomDomainDatabaseTest`
3. **Read Docs**: See `CUSTOM_DOMAIN_DATABASE_SWITCHING.md`
4. **Debug Mode**: Set `APP_DEBUG=true` in .env

---

## ⚡ Quick Commands

```bash
# Check PHP syntax
php -l app/Http/Middleware/CustomDomainDatabaseMiddleware.php

# Clear all caches
php artisan optimize:clear

# Run tests
php artisan test --filter CustomDomainDatabaseTest

# Check current config
php artisan config:show database

# View logs (Linux/Mac)
tail -f storage/logs/laravel.log

# View logs (Windows)
Get-Content storage\logs\laravel.log -Tail 50 -Wait
```

---

**That's it! You're ready to go! 🎉**

The system will now automatically switch between maindb and agencydb based on the accessed domain.
