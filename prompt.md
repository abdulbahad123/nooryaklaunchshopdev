### Project: SaaSReselling Multi-Tenant Admin & Agency Database Architecture

Update the existing `saasreselling.com` application so that it becomes the main admin/control panel, while `nooryak.in` is no longer exposed as the main admin interface.

#### 1. Main Admin Migration

* Make `saasreselling.com` the primary/main admin application.
* When an authorized admin opens `saasreselling.com`, they should be able to access the complete Main Admin dashboard and all existing administrative functionality currently associated with the main admin.
* Remove any dependency on opening `nooryak.in` to access the Main Admin.
* Do not expose internal/debug frontend pages, development dashboards, test routes, debug panels, or developer-only UI in the production `saasreselling.com` frontend.
* Keep backend/debug functionality available only where it is required internally and properly protected from normal users.

#### 2. Agency-Based Multi-Tenant Database Architecture

When a new agency is imported/created, the system must create or connect to that agency's own databases using a consistent naming convention.

Use this exact database naming pattern:

```text
nooryak_ps_{agencyname}_launchshop
nooryak_ps_{agencyname}_webbuild
```

Example for an agency named `acme`:

```text
nooryak_ps_acme_launchshop
nooryak_ps_acme_webbuild
```

Example for an agency named `abcagency`:

```text
nooryak_ps_abcagency_launchshop
nooryak_ps_abcagency_webbuild
```

#### 3. Agency Database Isolation

Each agency must operate using **only its own databases**.

For example:

```text
Agency: acme

nooryak_ps_acme_launchshop
nooryak_ps_acme_webbuild
```

The `acme` agency must never accidentally read from, write to, or display data from:

```text
nooryak_ps_otheragency_launchshop
nooryak_ps_otheragency_webbuild
```

Implement strict tenant/database isolation at the application/backend level.

#### 4. Agency Import Flow

When a new agency is imported:

1. Validate the agency name/identifier.
2. Generate the standardized database names automatically:

   * `nooryak_ps_{agencyname}_launchshop`
   * `nooryak_ps_{agencyname}_webbuild`
3. Create/connect to the required databases.
4. Apply the correct database schema/migrations.
5. Register the agency in the Main Admin.
6. Associate the agency with its database configuration.
7. Ensure all agency requests resolve to that agency's databases only.
8. Do not hard-code a particular agency's database names.
9. Existing agencies must continue working without breaking their current data.

#### 5. Database Resolution

Create a centralized tenant/database-resolution mechanism.

The application should determine the current agency first and then resolve its database connections from the agency configuration.

Conceptually:

```text
Request
   ↓
Identify authenticated user
   ↓
Identify agency
   ↓
Load agency database configuration
   ↓
Connect to:
   nooryak_ps_{agencyname}_launchshop
   nooryak_ps_{agencyname}_webbuild
   ↓
Execute agency-specific operation
```

Do not allow frontend-provided database names to determine the database connection directly. Database selection must be controlled and validated by the backend.

#### 6. Main Admin Responsibilities

The Main Admin at `saasreselling.com` should be able to:

* View/manage agencies.
* Import new agencies.
* Create/manage agency database mappings.
* View agency status.
* Manage agency users/admins.
* Access authorized agency management functions.
* Manage LaunchShop and WebBuild configurations.
* Perform database/schema migrations where appropriate.
* Disable/remove an agency safely.
* View system-level operational information.

Do not expose sensitive database credentials in the frontend.

#### 7. Production Frontend Cleanup

Clean the production frontend of:

* Debug pages
* Development/test dashboards
* Debug API screens
* Internal developer tools
* Temporary test components
* Hard-coded agency information
* Hard-coded database names
* Development-only navigation items

The production UI should contain only the intended SaaSReselling functionality.

#### 8. Security Requirements

Implement proper authorization so that:

* Only Main Admin users can access Main Admin functionality.
* Agency admins can access only their own agency.
* Agency users cannot access another agency by changing an ID, URL parameter, hostname, request body, or API parameter.
* Database credentials are never exposed to the browser.
* Backend APIs must verify tenant/agency authorization on every relevant request.
* Do not rely only on frontend route protection.
* Prevent cross-tenant data leakage.

#### 9. Migration / Backward Compatibility

Before changing the architecture:

* Inspect the existing `nooryak.in` Main Admin implementation.
* Identify all Main Admin routes, APIs, database operations, authentication logic, and permissions.
* Move/reuse the required functionality under `saasreselling.com`.
* Preserve existing agency data.
* Do not delete or modify production databases without a migration/backup strategy.
* Ensure existing agencies continue functioning after deployment.

#### 10. Expected Final Architecture

The target architecture should look approximately like this:

```text
                    ┌─────────────────────┐
                    │   saasreselling.com │
                    │     MAIN ADMIN      │
                    └──────────┬──────────┘
                               │
                    ┌──────────▼──────────┐
                    │   Agency Registry   │
                    │  + Tenant Resolver  │
                    └──────────┬──────────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
              ▼                ▼                ▼
          Agency A          Agency B         Agency C
              │                │                │
       ┌──────┴──────┐  ┌──────┴──────┐  ┌──────┴──────┐
       ▼             ▼  ▼             ▼  ▼             ▼
    LaunchShop    WebBuild
       │             │
       ▼             ▼
nooryak_ps_acme_launchshop
nooryak_ps_acme_webbuild
```

### Important Implementation Rule

Do not simply rename URLs or hide the existing frontend.

First inspect the current architecture and determine:

* Where the Main Admin currently lives.
* How `nooryak.in` authenticates Main Admin users.
* How agency identification currently works.
* How database connections are currently selected.
* Where database names are configured.
* Which APIs are shared between agencies.
* Which debug routes/components are exposed.
* How agencies are currently imported.

Then implement the new architecture with the minimum necessary changes while preserving existing functionality and data.

### Acceptance Criteria

The implementation is complete only when all of the following are true:

* `saasreselling.com` is the primary Main Admin entry point.
* Main Admin functionality can be accessed from `saasreselling.com`.
* `nooryak.in` is no longer required for Main Admin access.
* Production debug/development frontend pages are removed or protected.
* A newly imported agency automatically follows:
  `nooryak_ps_{agencyname}_launchshop`
  and
  `nooryak_ps_{agencyname}_webbuild`.
* Each agency can access only its own data.
* Agency A cannot access Agency B's databases/data.
* Database credentials are never exposed to frontend users.
* Tenant isolation is enforced server-side.
* Existing agencies and their data continue to work.
* No hard-coded agency-specific database configuration remains in the application.
* Main Admin can manage the complete agency lifecycle from `saasreselling.com`.
