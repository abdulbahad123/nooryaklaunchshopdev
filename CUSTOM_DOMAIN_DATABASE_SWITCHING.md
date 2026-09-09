# Custom Domain Database Switching Implementation

## Overview

This implementation enables automatic database switching from `maindb` to **dynamically resolved agency databases** when a custom domain (approved by admin) is accessed. The agency databases are automatically created when agencies are onboarded through the Sass_admin system.

## How It Works

### 1. Dynamic Database Resolution

Agency databases are **NOT** statically configured. Instead, they are:

- **Created automatically** when a white-label agency is onboarded via Sass_admin (`D:\xamp\htdocs\Sass_admin`)
- **Named dynamically** following the pattern: `bazaarwa_ps_{agency_slug}_launchsh`
- **Looked up** from the `agency_products` table in the Sass_admin database

Example agency database names:
- `bazaarwa_ps_abrsystemss_launchsh` (for abrsystems agency)
- `bazaarwa_ps_abrsystemss_website_` (for website builder product)

### 2. Environment Variables

Add these variables to your `.env` file:

```env
# Main Database (already exists)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nooryak_launchshopp
DB_USERNAME=root
DB_PASSWORD=root

# SaaS Admin DB - used to lookup agency databases
SASS_ADMIN_DB=sass_admin
SASS_ADMIN_DB_HOST=127.0.0.1
SASS_ADMIN_DB_PORT=3306
SASS_ADMIN_DB_USER=root
SASS_ADMIN_DB_PASS=root

# cPanel user prefix for database naming
CPANEL_USER=bazaarwa
```

**Note:** NO static `AGENCY_DB_*` variables needed! The system dynamically discovers the agency database.

### 3. Middleware Flow

The request flows through the following middleware in order:

1. **ResolveWbCustomDomain** (Global middleware)
   - Handles website-builder custom domains
   - Runs before routing

2. **StartSession** (Web middleware)
   - Initializes the session

3. **CustomDomainDatabaseMiddleware** (Web middleware) - **NEW**
   - Checks if the current domain is an approved custom domain
   - Switches to `agencydb` if domain is found with status=1
   - Sets session flags for subsequent requests
   - Priority: Runs BEFORE TenantDatabaseMiddleware

4. **TenantDatabaseMiddleware** (Web middleware)
   - Handles multi-tenant database switching
   - Skips if CustomDomainDatabaseMiddleware already resolved

## Custom Domain Status Values

In the `user_custom_domains` table:

- **0** = Pending (not yet approved)
- **1** = Connected/Approved (will trigger database switch)
- **2** = Rejected

## Database Switching Logic

### When Custom Domain is Accessed:

```
1. User accesses: https://funkiddoz.in
2. CustomDomainDatabaseMiddleware detects custom domain
3. Queries user_custom_domains table in maindb
4. Finds record with status=1 (Connected)
5. Gets user_id from the custom domain record
6. Queries Sass_admin database (agency_products table)
7. Looks up the agency database name (e.g., bazaarwa_ps_abrsystemss_launchsh)
8. Switches database connection from maindb to agency database
9. Sets session variables:
   - custom_domain_active = true
   - custom_domain_user_id = <user_id>
   - custom_domain_name = funkiddoz.in
   - using_agency_db = true
   - agency_db_name = bazaarwa_ps_abrsystemss_launchsh
10. All subsequent queries use the agency database
```

### When Main Domain is Accessed:

```
1. User accesses: https://cockroachjantaparty.top
2. CustomDomainDatabaseMiddleware detects main infrastructure host
3. Skips custom domain check
4. Continues using maindb (default)
5. No database switching occurs
```

## Main Infrastructure Hosts

These hosts are considered main infrastructure and will ALWAYS use maindb:

- nooryak.in
- localhost
- 127.0.0.1
- launchshop.in
- cockroachjantaparty.top
- Any host defined in `WEBSITE_HOST` or `APP_URL` env variables

## Helper Functions

Three new helper functions are available:

### 1. `isUsingAgencyDb()`

Check if the application is currently using the agency database:

```php
if (isUsingAgencyDb()) {
    // We're on a custom domain using agencydb
    echo "Using Agency Database";
} else {
    // We're on main infrastructure using maindb
    echo "Using Main Database";
}
```

### 2. `getCustomDomainInfo()`

Get information about the active custom domain:

```php
$info = getCustomDomainInfo();
if ($info) {
    echo "User ID: " . $info['user_id'];
    echo "Domain: " . $info['domain'];
    echo "Using Agency DB: " . ($info['using_agency_db'] ? 'Yes' : 'No');
}
```

### 3. `getCurrentDatabaseName()`

Get the name of the currently active database:

```php
$dbName = getCurrentDatabaseName();
echo "Current Database: " . $dbName; // e.g., "agencydb" or "nooryak_launchshopp"
```

## Admin Panel Integration

### Approving Custom Domains

When an admin approves a custom domain (sets status to 1):

1. The domain becomes "Connected"
2. An email is sent to the user
3. The next time the domain is accessed, it will use agencydb

### Rejecting Custom Domains

When an admin rejects a custom domain (sets status to 2):

1. The domain becomes "Rejected"
2. An email is sent to the user
3. The domain will NOT trigger database switching

## Testing

### Test Case 1: Main Domain

```bash
# Access main domain
curl https://cockroachjantaparty.top

# Expected: Uses maindb
# Check logs: "CustomDomainDB: Main host detected"
```

### Test Case 2: Custom Domain (Approved)

```bash
# Access approved custom domain
curl https://funkiddoz.in

# Expected: Uses agencydb
# Check logs: "CustomDomainDB: Switched to agencydb for custom domain"
```

### Test Case 3: Custom Domain (Pending)

```bash
# Access pending custom domain (status=0)
curl https://pendingdomain.com

# Expected: Uses maindb (not approved yet)
# Check logs: "CustomDomainDB: No custom domain found"
```

## Logging

All database switching actions are logged:

```php
// Success log
Log::info("CustomDomainDB: Switched to agencydb for custom domain", [
    'user_id' => 123,
    'requested_domain' => 'funkiddoz.in',
]);

// Main host log
Log::info("CustomDomainDB: Main host detected - using maindb");

// Error log
Log::error("CustomDomainDB: Failed to switch to agencydb: " . $error);
```

## Troubleshooting

### Issue: Custom domain not switching to agencydb

**Check:**
1. Is the domain status = 1 in `user_custom_domains` table?
2. Are AGENCY_DB_* environment variables set correctly?
3. Does the agencydb database exist and is it accessible?
4. Check Laravel logs for errors

### Issue: Main domain using agencydb

**Check:**
1. Is the domain listed in the main hosts array?
2. Check session variables - clear session if stale
3. Review middleware order in Kernel.php

### Issue: Database connection errors

**Check:**
1. Verify agencydb credentials in .env
2. Check if MySQL user has access to agencydb
3. Test connection manually: `mysql -u root -p agencydb`
4. Review error logs for specific connection issues

## Security Considerations

1. **Database Credentials**: Store agency database credentials securely in .env
2. **Session Security**: Session data includes user_id and domain info
3. **Connection Pooling**: Database connections are purged and reconnected on switches
4. **Error Handling**: Connection failures restore the original maindb connection
5. **Access Control**: Only approved domains (status=1) trigger database switches

## Performance Considerations

1. **Caching**: Session stores database switch state to avoid repeated queries
2. **Connection Reuse**: Database connection is maintained for the entire request lifecycle
3. **Early Exit**: Main infrastructure hosts skip custom domain checks entirely
4. **Middleware Order**: CustomDomainDatabaseMiddleware runs before other tenant logic

## File Changes Summary

### New Files:
- `app/Http/Middleware/CustomDomainDatabaseMiddleware.php` - Main middleware logic

### Modified Files:
- `config/database.php` - Added agencydb connection configuration
- `.env` - Added AGENCY_DB_* environment variables
- `app/Http/Kernel.php` - Registered CustomDomainDatabaseMiddleware
- `app/Http/Middleware/TenantDatabaseMiddleware.php` - Added skip logic for custom domains
- `app/Http/Helpers/Helper.php` - Added helper functions

### Documentation:
- `CUSTOM_DOMAIN_DATABASE_SWITCHING.md` - This file

## Maintenance

### Adding New Main Infrastructure Hosts

Edit the `getMainHosts()` method in `CustomDomainDatabaseMiddleware.php`:

```php
protected function getMainHosts(): array
{
    return array_filter([
        'nooryak.in',
        'newdomain.com', // Add new infrastructure host here
        'localhost',
        // ... rest of hosts
    ]);
}
```

### Updating Database Credentials

Update the `.env` file and restart the application:

```env
AGENCY_DB_DATABASE=new_agencydb
AGENCY_DB_USERNAME=new_user
AGENCY_DB_PASSWORD=new_password
```

## Future Enhancements

1. **Multiple Agency Databases**: Support different databases per agency/user
2. **Database Pool Management**: Implement connection pooling for better performance
3. **Read/Write Splitting**: Separate read and write operations to different databases
4. **Automatic Failover**: Implement fallback logic if agencydb is unavailable
5. **Admin Dashboard**: Add visual indicators showing which database is active
6. **Audit Logging**: Track all database switches for security auditing

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review this documentation
3. Contact the development team
