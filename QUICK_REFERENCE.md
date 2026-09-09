# Quick Reference - Dynamic Custom Domain Database Switching

## 🚀 One-Minute Overview

**What it does:** Automatically switches from maindb to agency-specific databases when custom domains are accessed.

**How it works:** Dynamically looks up the agency database from Sass_admin system - NO hardcoding needed!

---

## 📝 Configuration (.env)

```env
# SaaS Admin (for looking up agencies)
SASS_ADMIN_DB=sass_admin
SASS_ADMIN_DB_USER=root
SASS_ADMIN_DB_PASS=root

# cPanel prefix
CPANEL_USER=bazaarwa
```

**That's it!** No agency database hardcoding needed.

---

## 🔄 Flow

```
Custom Domain Access
    ↓
Check user_custom_domains (status=1?)
    ↓
Query Sass_admin for db_name
    ↓
Found: bazaarwa_ps_abrsystemss_launchsh
    ↓
Switch to agency database
    ↓
Done! ✅
```

---

## 🎯 Admin Actions

### Approve Custom Domain:
1. Admin Panel → Custom Domains
2. Set status to 1 (Connected)
3. Done! Next access uses agency database

### Reject Custom Domain:
1. Set status to 2 (Rejected)
2. Domain continues using maindb

---

## 💻 Code Examples

### Check Database:
```php
if (isUsingAgencyDb()) {
    echo "Agency Database Active";
}

echo getCurrentDatabaseName(); // Returns actual DB name
```

### Show Indicator:
```blade
{{-- Full indicator --}}
@include('components.database-status-indicator')

{{-- Minimal badge --}}
@include('components.database-status-badge')
```

---

## 🗄️ Database Tables

### user_custom_domains (maindb)
```
status: 0=Pending, 1=Connected, 2=Rejected
```

### agency_products (sass_admin)
```
db_name: bazaarwa_ps_{slug}_launchsh
```

---

## 🧪 Testing

```bash
# Check Sass_admin
SELECT * FROM sass_admin.agency_products 
WHERE db_name IS NOT NULL;

# Check custom domains
SELECT * FROM user_custom_domains WHERE status = 1;

# View logs
tail -f storage/logs/laravel.log
```

---

## 🔍 Troubleshooting

| Issue | Solution |
|-------|----------|
| Not switching | Check status=1, verify Sass_admin accessible |
| Database not found | Check agency_products.db_name column |
| Connection error | Verify SASS_ADMIN_DB_* credentials |

---

## ✅ Checklist

- [ ] Sass_admin database accessible
- [ ] Custom domain status = 1
- [ ] Agency database exists
- [ ] Cache cleared
- [ ] Logs show "Found agency database from Sass_admin"

---

## 📊 Status Reference

| Domain Type | Status | Database |
|-------------|--------|----------|
| Main (cockroachjantaparty.top) | - | maindb |
| Custom Pending | 0 | maindb |
| Custom Connected | 1 | agency DB ✅ |
| Custom Rejected | 2 | maindb |

---

## 🎨 Visual Indicators

### Show in dashboard:
```blade
@include('components.database-status-indicator')
```

### Show in layout:
```blade
@include('components.database-status-badge')
```

---

## 📚 Documentation Files

- `DYNAMIC_DATABASE_IMPLEMENTATION.md` - Complete guide
- `DYNAMIC_LOOKUP_FLOW.md` - Visual diagrams  
- `IMPLEMENTATION_SUMMARY.md` - Overview
- `QUICK_REFERENCE.md` - This file

---

## 🔑 Key Points

✅ **Dynamic Discovery** - No hardcoding needed  
✅ **Sass_admin Integration** - Uses agency_products table  
✅ **Scalable** - Works for unlimited agencies  
✅ **Auto-Discovery** - Finds new agencies automatically  
✅ **Fallback Logic** - Pattern matching if Sass_admin fails  

---

## 💡 Helper Functions

```php
// Check if using agency database
isUsingAgencyDb() // Returns: true/false

// Get custom domain info
getCustomDomainInfo() // Returns: ['user_id'=>5, 'domain'=>'...', ...]

// Get current database name
getCurrentDatabaseName() // Returns: 'bazaarwa_ps_...' or 'maindb'
```

---

**Need help? Check the detailed docs or Laravel logs!** 🚀
