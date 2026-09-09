# Custom Domain Database Switching - System Flow Diagram

## 📊 Request Flow Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER REQUEST                            │
└─────────────────────┬───────────────────────────────────────────┘
                      │
                      ▼
        ┌─────────────────────────────┐
        │  What domain was accessed?  │
        └─────────────┬───────────────┘
                      │
         ─────────────┴─────────────
        │                           │
        ▼                           ▼
┌──────────────────┐      ┌──────────────────┐
│  Main Domain     │      │ Custom Domain    │
│  (Infrastructure)│      │  (User's Domain) │
└────────┬─────────┘      └────────┬─────────┘
         │                         │
         │                         │
┌────────▼─────────┐      ┌────────▼─────────┐
│ cockroachjanta   │      │  funkiddoz.in    │
│ party.top        │      │  (Example)       │
│ nooryak.in       │      │                  │
│ localhost        │      │                  │
└────────┬─────────┘      └────────┬─────────┘
         │                         │
         │                         │
         │                ┌────────▼─────────────────┐
         │                │ Check user_custom_domains│
         │                │ table in maindb          │
         │                └────────┬─────────────────┘
         │                         │
         │                ┌────────▼─────────────────┐
         │                │ Is status = 1?           │
         │                │ (Connected/Approved)     │
         │                └────────┬─────────────────┘
         │                         │
         │                    ─────┴─────
         │                   │           │
         │                   │           │
         │              ┌────▼────┐ ┌───▼────┐
         │              │  YES    │ │   NO   │
         │              │ (status │ │(status │
         │              │   = 1)  │ │ 0 or 2)│
         │              └────┬────┘ └───┬────┘
         │                   │          │
         │                   │          │
         │              ┌────▼──────────▼──────┐
         │              │                      │
         ▼              ▼                      │
┌────────────────────────────────┐            │
│   DATABASE CONNECTION          │            │
│   SWITCHING LOGIC              │            │
└────────┬───────────┬───────────┘            │
         │           │                        │
         │           │                        │
         ▼           ▼                        ▼
  ┌──────────┐  ┌──────────┐         ┌──────────┐
  │  maindb  │  │ agencydb │         │  maindb  │
  │          │  │          │         │          │
  │ (Default)│  │ (Custom  │         │ (Default)│
  │          │  │  Domain) │         │          │
  └────┬─────┘  └────┬─────┘         └────┬─────┘
       │             │                     │
       │             │                     │
       └─────────────┴─────────────────────┘
                     │
                     ▼
        ┌────────────────────────────┐
        │  APPLICATION CONTINUES     │
        │  WITH CORRECT DATABASE     │
        └────────────────────────────┘
```

---

## 🔄 Middleware Chain

```
REQUEST ENTERS APPLICATION
         │
         ▼
┌─────────────────────────────────┐
│  ResolveWbCustomDomain          │
│  (Global Middleware)            │
│  - Handles website-builder      │
│    custom domains               │
└────────────┬────────────────────┘
             ▼
┌─────────────────────────────────┐
│  StartSession                   │
│  (Web Middleware)               │
│  - Initializes session          │
└────────────┬────────────────────┘
             ▼
┌─────────────────────────────────┐
│  CustomDomainDatabaseMiddleware │  ← NEW
│  (Web Middleware)               │
│  ┌───────────────────────────┐ │
│  │ 1. Check if main host     │ │
│  │    → Skip if main         │ │
│  │                           │ │
│  │ 2. Query custom_domains   │ │
│  │    → Check status = 1     │ │
│  │                           │ │
│  │ 3. Switch to agencydb     │ │
│  │    → Update config        │ │
│  │    → Reconnect DB         │ │
│  │    → Set session flags    │ │
│  │                           │ │
│  │ 4. Mark as resolved       │ │
│  └───────────────────────────┘ │
└────────────┬────────────────────┘
             ▼
┌─────────────────────────────────┐
│  TenantDatabaseMiddleware       │
│  (Web Middleware)               │
│  - Skips if custom domain       │
│    already resolved             │
│  - Handles other tenant logic   │
└────────────┬────────────────────┘
             ▼
┌─────────────────────────────────┐
│  Other Middleware...            │
└────────────┬────────────────────┘
             ▼
┌─────────────────────────────────┐
│  ROUTE → CONTROLLER → VIEW      │
│  (Using correct database)       │
└─────────────────────────────────┘
```

---

## 🗃️ Database Decision Tree

```
                    START
                      │
                      ▼
         ┌────────────────────────┐
         │  Is domain in main     │
         │  infrastructure list?  │
         └──────┬────────┬────────┘
                │        │
            YES │        │ NO
                │        │
                ▼        ▼
         ┌──────────┐   ┌─────────────────────┐
         │ Use      │   │ Query               │
         │ maindb   │   │ user_custom_domains │
         └──────────┘   └──────┬──────────────┘
                               │
                               ▼
                    ┌──────────────────┐
                    │ Domain found?    │
                    └──────┬─────┬─────┘
                           │     │
                       YES │     │ NO
                           │     │
                           ▼     ▼
                    ┌──────────┐ ┌──────────┐
                    │ Check    │ │ Use      │
                    │ status   │ │ maindb   │
                    └────┬─────┘ └──────────┘
                         │
              ───────────┴───────────
             │           │           │
         status=0    status=1    status=2
         (Pending) (Connected) (Rejected)
             │           │           │
             ▼           ▼           ▼
        ┌────────┐  ┌──────────┐ ┌────────┐
        │ Use    │  │ Use      │ │ Use    │
        │ maindb │  │ agencydb │ │ maindb │
        └────────┘  └──────────┘ └────────┘
```

---

## 💾 Database Status Flow

```
┌─────────────────────────────────────────────────────────────┐
│                  ADMIN PANEL ACTION                         │
└─────────────────┬───────────────────────────────────────────┘
                  │
                  ▼
     ┌────────────────────────────┐
     │  Admin Updates Domain      │
     │  Status in user_custom_    │
     │  domains table             │
     └────────┬───────────────────┘
              │
    ──────────┴──────────
   │          │          │
   ▼          ▼          ▼
status=0   status=1   status=2
┌────────┐ ┌────────┐ ┌────────┐
│Pending │ │Connect-│ │Reject- │
│        │ │ed      │ │ed      │
└───┬────┘ └───┬────┘ └───┬────┘
    │          │          │
    │    ┌─────▼─────┐    │
    │    │Send Email │    │
    │    │to User    │    │
    │    └─────┬─────┘    │
    │          │          │
    └──────────┴──────────┘
              │
              ▼
    ┌──────────────────────┐
    │  User Accesses       │
    │  Custom Domain       │
    └──────────┬───────────┘
               │
               ▼
    ┌──────────────────────┐
    │  CustomDomainDB      │
    │  Middleware Checks   │
    │  Status              │
    └──────────┬───────────┘
               │
     ──────────┴──────────
    │          │          │
    ▼          ▼          ▼
status=0   status=1   status=2
┌────────┐ ┌────────┐ ┌────────┐
│Use     │ │Switch  │ │Use     │
│maindb  │ │to      │ │maindb  │
│        │ │agencydb│ │        │
└────────┘ └────────┘ └────────┘
```

---

## 🔐 Session State Management

```
┌────────────────────────────────────────────────┐
│          CUSTOM DOMAIN ACCESSED                │
│          (status = 1)                          │
└─────────────────┬──────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  Middleware Sets Session Variables:             │
│                                                 │
│  ┌───────────────────────────────────────────┐ │
│  │ custom_domain_active = true               │ │
│  │ custom_domain_user_id = <user_id>         │ │
│  │ custom_domain_name = 'funkiddoz.in'       │ │
│  │ using_agency_db = true                    │ │
│  └───────────────────────────────────────────┘ │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  Subsequent Requests in Same Session:           │
│  - Check session first                          │
│  - Skip database lookup if already resolved     │
│  - Maintain agencydb connection                 │
└─────────────────────────────────────────────────┘
```

---

## 📊 Data Flow Example

### Example 1: Main Domain Access

```
User → cockroachjantaparty.top
       │
       ▼
CustomDomainDatabaseMiddleware
       │
       ├─ Check: Is this a main host?
       │  Answer: YES
       │
       ├─ Action: Skip custom domain check
       │
       └─ Result: Continue with maindb
                  │
                  ▼
              ┌─────────┐
              │ maindb  │
              │ ┌─────┐ │
              │ │users│ │
              │ │pkgs │ │
              │ │etc. │ │
              │ └─────┘ │
              └─────────┘
```

### Example 2: Custom Domain Access (Approved)

```
User → funkiddoz.in
       │
       ▼
CustomDomainDatabaseMiddleware
       │
       ├─ Check: Is this a main host?
       │  Answer: NO
       │
       ├─ Query: SELECT * FROM user_custom_domains
       │         WHERE requested_domain = 'funkiddoz.in'
       │
       ├─ Found: status = 1 (Connected)
       │
       ├─ Action: Switch to agencydb
       │  - Purge DB connection
       │  - Update config
       │  - Reconnect to agencydb
       │  - Set session flags
       │
       └─ Result: Using agencydb
                  │
                  ▼
              ┌──────────┐
              │ agencydb │
              │ ┌──────┐ │
              │ │users │ │
              │ │data  │ │
              │ │etc.  │ │
              │ └──────┘ │
              └──────────┘
```

### Example 3: Custom Domain Access (Pending)

```
User → pendingdomain.com
       │
       ▼
CustomDomainDatabaseMiddleware
       │
       ├─ Check: Is this a main host?
       │  Answer: NO
       │
       ├─ Query: SELECT * FROM user_custom_domains
       │         WHERE requested_domain = 'pendingdomain.com'
       │
       ├─ Found: status = 0 (Pending)
       │
       ├─ Action: No database switch
       │
       └─ Result: Continue with maindb
                  │
                  ▼
              ┌─────────┐
              │ maindb  │
              │ ┌─────┐ │
              │ │users│ │
              │ │etc. │ │
              │ └─────┘ │
              └─────────┘
```

---

## 🎯 Key Decision Points

### Decision Point 1: Is this a main infrastructure host?

```
Input: request->getHost()
Check: in_array($host, $mainHosts)
Result: 
  ✓ YES → Use maindb, skip custom domain check
  ✗ NO  → Continue to Decision Point 2
```

### Decision Point 2: Is custom domain record found?

```
Input: Query user_custom_domains table
Check: Domain exists in database?
Result:
  ✓ YES → Continue to Decision Point 3
  ✗ NO  → Use maindb
```

### Decision Point 3: What is the domain status?

```
Input: status field from user_custom_domains
Check: status value
Result:
  status = 0 (Pending)   → Use maindb
  status = 1 (Connected) → Use agencydb ✅
  status = 2 (Rejected)  → Use maindb
```

---

## 📈 Performance Flow

```
┌────────────────────────────────────────┐
│  Request 1 (First time)                │
│  - Check main host: O(1)               │
│  - Query database: O(1)                │
│  - Switch connection: O(1)             │
│  - Set session: O(1)                   │
│  Total: ~4-5ms                         │
└────────────────┬───────────────────────┘
                 │
                 ▼
┌────────────────────────────────────────┐
│  Request 2+ (Same session)             │
│  - Check session: O(1)                 │
│  - Use cached connection: O(1)         │
│  Total: ~1-2ms                         │
└────────────────────────────────────────┘
```

---

**This diagram provides a visual understanding of how the custom domain database switching system works! 🎨**
