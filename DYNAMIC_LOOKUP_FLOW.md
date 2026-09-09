# Dynamic Agency Database Lookup Flow

## 🔄 Complete Request Flow with Dynamic Database Resolution

```
┌─────────────────────────────────────────────────────────────┐
│  USER ACCESSES CUSTOM DOMAIN: https://funkiddoz.in         │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │ CustomDomainDatabaseMiddleware│
        │      handle()                │
        └──────────────┬───────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │ Is this a main host?         │
        │ (cockroachjantaparty.top?)   │
        └──────┬───────────────┬────────┘
               │               │
           YES │               │ NO
               │               │
               ▼               ▼
        ┌──────────┐    ┌──────────────────────────┐
        │Use maindb│    │ findCustomDomain()       │
        │Skip check│    │                          │
        └──────────┘    └──────────┬───────────────┘
                                   │
                        ┌──────────▼──────────────────────┐
                        │ Query: user_custom_domains      │
                        │ WHERE requested_domain =        │
                        │       'funkiddoz.in'            │
                        │   AND status = 1                │
                        └──────────┬──────────────────────┘
                                   │
                         ┌─────────▼─────────┐
                         │ Found? Status = 1?│
                         └─────┬─────┬───────┘
                               │     │
                           YES │     │ NO
                               │     │
                               │     └───────────────┐
                               │                     │
                               ▼                     ▼
                    ┌──────────────────────┐  ┌──────────┐
                    │ Get user record:     │  │Use maindb│
                    │ user_id = 5          │  └──────────┘
                    │ username = "abrsys"  │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────────────────┐
                    │ getAgencyDatabase(user)          │
                    │                                  │
                    │ METHOD 1: Query Sass_admin       │
                    └──────────┬───────────────────────┘
                               │
        ┌──────────────────────▼─────────────────────────────┐
        │ Connect to Sass_admin Database                     │
        │ DSN: mysql:host=127.0.0.1;dbname=sass_admin        │
        │ User: SASS_ADMIN_DB_USER                           │
        │ Pass: SASS_ADMIN_DB_PASS                           │
        └──────────────────────┬─────────────────────────────┘
                               │
        ┌──────────────────────▼──────────────────────────────┐
        │ SQL Query:                                          │
        │ SELECT ap.db_name, a.slug, a.name                   │
        │ FROM agency_products ap                             │
        │ INNER JOIN agencies a ON a.id = ap.agency_id        │
        │ INNER JOIN products p ON p.id = ap.product_id       │
        │ WHERE p.slug = 'launchshop'                         │
        │   AND ap.db_name IS NOT NULL                        │
        │ LIMIT 1                                             │
        └──────────────────────┬─────────────────────────────┘
                               │
                    ┌──────────▼───────────┐
                    │ Found in Sass_admin? │
                    └──────┬───────┬───────┘
                           │       │
                       YES │       │ NO
                           │       │
                           │       └──────────────────────┐
                           │                              │
                           ▼                              ▼
            ┌──────────────────────────┐  ┌────────────────────────────┐
            │ db_name =                │  │ METHOD 2: Pattern Matching │
            │ "bazaarwa_ps_abrsys      │  │ (Fallback)                 │
            │  stemss_launchsh"        │  └──────────┬─────────────────┘
            └──────────┬───────────────┘             │
                       │                             │
                       │              ┌──────────────▼────────────────┐
                       │              │ Try these patterns:           │
                       │              │ 1. bazaarwa_ps_{slug}_        │
                       │              │    launchsh                   │
                       │              │ 2. bazaarwa_ps_{slug}_        │
                       │              │    launchshop                 │
                       │              │ 3. bazaarwa_{slug}_launchshop │
                       │              └──────────┬────────────────────┘
                       │                         │
                       │              ┌──────────▼────────────────────┐
                       │              │ For each pattern:             │
                       │              │ Check if database exists      │
                       │              │ in MySQL (INFORMATION_SCHEMA) │
                       │              └──────────┬────────────────────┘
                       │                         │
                       │                         │
                       └─────────┬───────────────┘
                                 │
                      ┌──────────▼──────────────────────┐
                      │ Agency Database Name Resolved:  │
                      │ bazaarwa_ps_abrsystemss_launchsh│
                      └──────────┬──────────────────────┘
                                 │
                                 ▼
                      ┌──────────────────────────────────┐
                      │ switchToAgencyDatabase()         │
                      └──────────┬───────────────────────┘
                                 │
        ┌────────────────────────▼─────────────────────────────┐
        │ 1. Purge current DB connection                       │
        │    DB::purge('mysql')                                │
        │                                                      │
        │ 2. Update connection config:                         │
        │    Config::set('database.connections.mysql.database',│
        │                'bazaarwa_ps_abrsystemss_launchsh')   │
        │                                                      │
        │ 3. Reconnect to MySQL:                               │
        │    DB::reconnect('mysql')                            │
        │                                                      │
        │ 4. Test connection:                                  │
        │    DB::connection('mysql')->getPdo()                 │
        └────────────────────────┬─────────────────────────────┘
                                 │
                      ┌──────────▼──────────────────────┐
                      │ Set Session Variables:          │
                      │ - custom_domain_active = true   │
                      │ - custom_domain_user_id = 5     │
                      │ - custom_domain_name =          │
                      │   'funkiddoz.in'                │
                      │ - using_agency_db = true        │
                      │ - agency_db_name =              │
                      │   'bazaarwa_ps_abrsystemss_     │
                      │   launchsh'                     │
                      └──────────┬──────────────────────┘
                                 │
                                 ▼
                      ┌──────────────────────────────────┐
                      │ Log Success:                     │
                      │ "CustomDomainDB: Successfully    │
                      │  switched to agency database"    │
                      └──────────┬───────────────────────┘
                                 │
                                 ▼
                      ┌──────────────────────────────────┐
                      │ REQUEST CONTINUES                │
                      │ Using: bazaarwa_ps_abrsystemss_  │
                      │        launchsh                  │
                      │                                  │
                      │ All queries now hit this database│
                      └──────────────────────────────────┘
```

---

## 🗄️ Database Tables Involved

### 1. maindb.user_custom_domains
```
┌────┬─────────┬────────────────┬──────────────────┬────────┐
│ id │ user_id │ current_domain │ requested_domain │ status │
├────┼─────────┼────────────────┼──────────────────┼────────┤
│ 1  │ 5       │ sub.example.com│ funkiddoz.in     │ 1      │
└────┴─────────┴────────────────┴──────────────────┴────────┘
            │                                          │
            │                                          └─ 0=Pending, 1=Connected, 2=Rejected
            └─ Used to lookup user
```

### 2. maindb.users
```
┌────┬─────────────┬───────┬────────────┐
│ id │ username    │ email │ status     │
├────┼─────────────┼───────┼────────────┤
│ 5  │ abrsystems  │ ...   │ 1          │
└────┴─────────────┴───────┴────────────┘
     │
     └─ Username used for pattern matching (fallback)
```

### 3. sass_admin.agencies
```
┌────┬─────────────┬──────────────┬──────────────────────┐
│ id │ name        │ slug         │ custom_domain        │
├────┼─────────────┼──────────────┼──────────────────────┤
│ 1  │ abrsystems  │ abrsystemss  │ youroae.in           │
└────┴─────────────┴──────────────┴──────────────────────┘
     │              │
     └──────────────┴─ Used to find agency_products
```

### 4. sass_admin.products
```
┌────┬────────────┬────────────┐
│ id │ name       │ slug       │
├────┼────────────┼────────────┤
│ 2  │ Launchshop │ launchshop │
└────┴────────────┴────────────┘
     │
     └─ Used to filter for Launchshop product
```

### 5. sass_admin.agency_products (KEY TABLE!)
```
┌────┬────────────┬────────────┬────────────────────────────────────┐
│ id │ agency_id  │ product_id │ db_name                            │
├────┼────────────┼────────────┼────────────────────────────────────┤
│ 1  │ 1          │ 2          │ bazaarwa_ps_abrsystemss_launchsh   │
└────┴────────────┴────────────┴────────────────────────────────────┘
                                │
                                └─ This is the agency database name!
                                   DYNAMICALLY LOOKED UP
```

### 6. bazaarwa_ps_abrsystemss_launchsh (Agency Database)
```
The actual agency database containing:
- users
- products  
- orders
- settings
- etc.

This is where all queries go after switching!
```

---

## 🔍 Comparison: Static vs Dynamic

### ❌ OLD APPROACH (Static - Not Used):
```
.env file:
AGENCY_DB_DATABASE=agencydb  ← Hardcoded!

Problem:
- Only works for ONE agency
- Need to update .env for each agency
- Not scalable
- Doesn't integrate with Sass_admin
```

### ✅ NEW APPROACH (Dynamic - Implemented):
```
.env file:
SASS_ADMIN_DB=sass_admin  ← Points to admin system
CPANEL_USER=bazaarwa      ← For pattern matching

Process:
1. Query Sass_admin for db_name
2. Get actual database name
3. Switch connection dynamically

Benefits:
✅ Works for unlimited agencies
✅ No config updates needed
✅ Integrates with Sass_admin
✅ Auto-discovers new agencies
✅ Scalable and maintainable
```

---

## 📊 Decision Tree

```
                    Custom Domain Access
                            │
                            ▼
                ┌───────────────────────┐
                │ Is it a main host?    │
                └───────┬───────────────┘
                        │
                ┌───────┴───────┐
                │               │
            YES │               │ NO
                │               │
                ▼               ▼
         ┌──────────┐    ┌─────────────────┐
         │ maindb   │    │ Check custom    │
         │          │    │ domain status   │
         └──────────┘    └─────────┬───────┘
                                   │
                        ┌──────────┴──────────┐
                        │                     │
                    status=1              status≠1
                  (Connected)         (Pending/Rejected)
                        │                     │
                        ▼                     ▼
              ┌─────────────────┐      ┌──────────┐
              │ Query Sass_admin│      │ maindb   │
              │ for db_name     │      └──────────┘
              └─────────┬───────┘
                        │
              ┌─────────┴─────────┐
              │                   │
          Found                Not Found
              │                   │
              ▼                   ▼
    ┌──────────────────┐   ┌──────────────┐
    │ Use db_name      │   │ Try pattern  │
    │ from Sass_admin  │   │ matching     │
    └─────────┬────────┘   └──────┬───────┘
              │                   │
              └─────────┬─────────┘
                        │
                        ▼
              ┌──────────────────┐
              │ Agency Database  │
              │ (e.g., bazaarwa_ │
              │ ps_abrsystemss_  │
              │ launchsh)        │
              └──────────────────┘
```

---

## 🎯 Key Points

1. **NO Hardcoding** - Database name is NEVER hardcoded in .env or config
2. **Sass_admin Integration** - Reads from agency_products table
3. **Fallback Logic** - Pattern matching if Sass_admin query fails
4. **Dynamic Switching** - Different agency = different database, automatically
5. **Session Caching** - First request resolves, subsequent requests use cache
6. **Error Recovery** - Failed switch restores maindb connection

---

**This dynamic approach seamlessly integrates with your white-label agency onboarding workflow! 🚀**
