I need you to debug and FIX the Portfolio and Blog data synchronization system in my Laravel Website Builder application.

IMPORTANT:
Do not just patch the visible Blade file.
Analyze the complete data flow across:

1. Admin Blade/forms
2. Admin controller update/store methods
3. WbAgencySetting model
4. casts/accessors/mutators
5. database columns/migrations
6. image upload/storage logic
7. routes/web.php
8. FrontendController
9. all five theme Portfolio views
10. all five theme Blog views
11. user/customer dashboard
12. demo preview routes
13. subdomain live website routes
14. custom-domain routes

The application has 5 themes:

- digital_agency
- interior
- texigo
- construction
- evently

The CRUD operation is currently working in the Portfolio/Blog admin, but the saved data is NOT reliably synchronized with the frontend/live website/user dashboard.

==================================================
CURRENT PROBLEMS
==================================================

PORTFOLIO:

A. Some themes show the Portfolio hero banner/image and some themes do not.

B. Some themes have two hero buttons while other themes have one hero button.

C. Portfolio hero description/subtitle is not reliably appearing in the admin input after saving/reloading.

D. Portfolio project description sometimes does not come back into the input.

E. Portfolio project images work inconsistently between themes.

F. CRUD appears to work in admin, but saved Portfolio HERO data does not reliably appear on the frontend/live website.

G. The user/customer dashboard and frontend are not always showing the same Portfolio hero data.

H. The five themes currently use different frontend layouts, but they must all use the same saved customer data while preserving their own visual design.

BLOG:

I. Apply the same synchronization investigation/fix to Blog data.

J. Blog CRUD works, but frontend/user-dashboard data can be stale/default/demo data.

K. Blog images, descriptions/excerpts/content and other fields must remain synchronized.

==================================================
IMPORTANT CODE OBSERVATIONS
==================================================

The current Portfolio admin form contains these fields:

- portfolio_badge
- portfolio_title
- portfolio_subtitle
- primary_btn_text
- primary_btn_url
- portfolio_hero_image
- portfolio_hero_image_file

Portfolio project records contain:

- title
- category
- desc
- description (legacy compatibility)
- btn_text
- link
- image
- image_file

Do NOT arbitrarily rename existing database fields without checking the entire application.

The current admin form uses:

portfolio_data[INDEX][title]
portfolio_data[INDEX][category]
portfolio_data[INDEX][desc]
portfolio_data[INDEX][btn_text]
portfolio_data[INDEX][link]
portfolio_data[INDEX][image]
portfolio_data[INDEX][image_file]

There is also existing compatibility logic where description can come from either:

$port['desc']
or
$port['description']

Normalize this properly in the backend so both old and new records work.

==================================================
CRITICAL SYNC BUG TO INVESTIGATE
==================================================

The current FrontendController has methods similar to:

public function agencyPortfolio()
{
    $agency = WbAgencySetting::getDemoDefaults();
    return view('website_builder.agency_template.portfolio', compact('agency'));
}

public function agencyBlogs()
{
    $agency = WbAgencySetting::getDemoDefaults();
    return view('website_builder.agency_template.blogs', compact('agency'));
}

This is WRONG for customer/live data.

Do NOT use getDemoDefaults() for an authenticated customer's real website.

The frontend must load the exact WbAgencySetting belonging to the current customer.

The same rule applies to Portfolio, Blogs and their detail pages.

Demo/default data may ONLY be used when explicitly viewing a demo theme.

==================================================
MULTI-TENANT DATA RULE
==================================================

Every live/customer frontend request must resolve:

customer -> agency settings -> template_type -> correct theme view.

Never use:

WbAgencySetting::first()

as a fallback for a live customer website.

That can display another customer's website data.

Never allow Customer A's portfolio/blog data to appear on Customer B's site.

The authoritative relationship must be:

WbCustomer.id
        ↓
WbAgencySetting.customer_id
        ↓
portfolio/blog fields

Use the same customer_id consistently everywhere.

==================================================
CREATE ONE CANONICAL DATA CONTRACT
==================================================

Do not allow each theme to invent its own Portfolio/Blog data structure.

Create one normalized internal structure.

Portfolio hero:

[
    'badge' => ...,
    'title' => ...,
    'subtitle' => ...,
    'primary_button' => [
        'text' => ...,
        'url' => ...
    ],
    'secondary_button' => [
        'enabled' => true/false,
        'text' => ...,
        'url' => ...
    ],
    'image' => ...
]

However, if the existing database stores these values as individual columns, KEEP the existing database columns and normalize them at the model/service/view-model level rather than unnecessarily changing the schema.

Portfolio project:

[
    'id' => ...,
    'title' => ...,
    'category' => ...,
    'description' => ...,
    'image' => ...,
    'button' => [
        'text' => ...,
        'url' => ...
    ]
]

Support legacy data:

desc
description

and normalize both to:

description

Do not lose existing customer data.

==================================================
BUTTON REQUIREMENT
==================================================

Do NOT force all five themes to have the same visual number of buttons.

Some themes visually have:

- one button

and some have:

- two buttons

Preserve the original theme design.

The data layer should support:

primary button
secondary button

If a theme only supports one button, render only the primary button.

If a theme supports two buttons and secondary button data exists, render both.

Do not invent a second button where the theme does not have one.

Do not remove an existing theme's second CTA just because the admin form currently exposes only one button.

If secondary button fields do not currently exist in the database, inspect the existing theme/default data first and implement backward-compatible storage.

==================================================
PORTFOLIO HERO IMAGE REQUIREMENT
==================================================

The hero image must work consistently across all five themes.

When a customer uploads:

portfolio_hero_image_file

the backend must:

1. validate the image
2. store it correctly
3. save the final relative/public path into portfolio_hero_image
4. preserve the old image if no new image was uploaded
5. not overwrite the saved image with an empty hidden input
6. return the saved image to the admin
7. return the exact same image to the live frontend
8. work for both subdomain and custom domain
9. work after cache clearing/reload
10. work on all five theme views

Use one reliable image URL resolver.

It should correctly support:

- absolute http/https URLs
- /storage/... paths
- storage-relative paths
- public assets paths
- uploaded customer images

Do not blindly prepend asset() to an already absolute URL.

Do not use a demo placeholder if a valid customer image exists.

Only use the theme default image when the customer has no image.

==================================================
PROJECT IMAGE REQUIREMENT
==================================================

Apply the same rules to:

portfolio_data[index][image_file]

When an existing project image exists and the customer edits only the title/description:

DO NOT delete the existing image.

When a new image is uploaded:

replace the image path.

When no new image is uploaded:

preserve the existing image path.

When a project is deleted:

remove it from portfolio_data.

Do not accidentally re-index records in a way that changes IDs/links.

==================================================
DESCRIPTION REQUIREMENT
==================================================

Fix both Portfolio and Blog description synchronization.

Portfolio:

desc
description

must be normalized.

Blog may contain fields such as:

title
category
date
author
image
excerpt
content
description

Inspect the actual existing data structures before changing them.

The admin input must display the exact saved value after:

SAVE → REDIRECT → RELOAD

The frontend must display the exact same value.

Test with special characters, apostrophes, HTML-safe text and multiline text.

Do not silently replace an empty saved description with a demo description.

==================================================
ADMIN SAVE REQUIREMENT
==================================================

Inspect the actual Portfolio update controller.

Verify that every submitted field is explicitly persisted:

portfolio_badge
portfolio_title
portfolio_subtitle
primary_btn_text
primary_btn_url
portfolio_hero_image
portfolio_data

Also inspect Blog update persistence.

Do not rely on mass assignment unless the model fillable/casts are confirmed.

If WbAgencySetting uses JSON casts, verify:

portfolio_data
blogs_data

are correctly cast as arrays.

Verify that save/update actually executes:

$agency->save()

or equivalent persistence.

Add logging temporarily if necessary to identify:

customer_id
agency_id
template_type
portfolio_data
portfolio_hero_image

Then remove excessive debug logging after the fix.

==================================================
VERY IMPORTANT: DO NOT OVERWRITE CUSTOMER DATA WITH DEFAULTS
==================================================

Inspect methods such as:

applyTemplateDefaults()
createDefaultInstance()
createInteriorDefaultInstance()
createTexigoDefaultInstance()
createConstructionDefaultInstance()
createEventlyDefaultInstance()
getDemoDefaults()

Make sure they do NOT overwrite customer-customized:

portfolio_badge
portfolio_title
portfolio_subtitle
primary_btn_text
primary_btn_url
portfolio_hero_image
portfolio_data
blogs_data

when loading a website.

Defaults should only be used when creating a new agency/customer or when a field is genuinely empty.

Do not call template-default methods during normal frontend rendering.

==================================================
FIVE-THEME FRONTEND REQUIREMENT
==================================================

Inspect these five Portfolio views:

website_builder.agency_template.portfolio
website_builder.interior_template.portfolio
website_builder.texigo_theme.portfolio
website_builder.construction_theme.portfolio
website_builder.evently_theme.portfolio

And these Blog views:

website_builder.agency_template.blogs
website_builder.interior_template.blogs
website_builder.texigo_theme.blogs
website_builder.construction_theme.blogs
website_builder.evently_theme.blogs

Every view must receive the same customer-specific $agency object.

Only the presentation/layout should differ.

Do NOT duplicate database logic in every Blade file.

The frontend should consume the normalized agency data.

==================================================
FRONTEND ROUTING REQUIREMENT
==================================================

Inspect:

agencyPortfolio()
agencyBlogs()
agencyBlogDetail()
viewSubdomainPortfolio()
viewSubdomainBlogs()
viewSubdomainBlog()
custom-domain portfolio routes
custom-domain blog routes

Separate:

DEMO ROUTES
from
CUSTOMER LIVE ROUTES

Demo route:
    getDemoDefaults($template)

Customer route:
    resolve customer
    load WbAgencySetting where customer_id = customer.id

Never mix these two.

If the admin's "Preview Portfolio Page" button currently points to a demo route, fix it so that for an authenticated customer it previews that customer's actual saved website.

The preview must show the same data as the live customer URL.

==================================================
USER DASHBOARD REQUIREMENT
==================================================

Inspect the customer/user dashboard.

If the dashboard loads:

WbAgencySetting::getDemoDefaults()
or
WbAgencySetting::first()

replace that behavior with the logged-in customer's agency record.

Verify:

Auth::guard('wb_customer')->id()

matches:

WbAgencySetting.customer_id

The admin editor, user dashboard preview and live website must all read/write the same agency record.

==================================================
CACHE REQUIREMENT
==================================================

Check:

Laravel cache
view cache
config cache
browser caching
CDN/static caching if applicable

Do not use cache as a workaround for incorrect database queries.

After fixing the data flow, verify:

SAVE
→ DB updated
→ admin reload
→ dashboard reload
→ live website reload

all show identical data.

==================================================
IMAGE URL REQUIREMENT
==================================================

Create/use a single helper such as:

resolveWebsiteBuilderImage($path)

or an equivalent existing helper if one already exists.

Use it consistently for:

portfolio hero image
portfolio project image
blog image
blog hero image

Handle:

http://
https://
/storage/
storage/
assets/
uploads/

without creating malformed URLs such as:

https://domain.com/https://...
https://domain.com/storage/storage/...
https://domain.com/assets/assets/...

==================================================
BACKWARD COMPATIBILITY
==================================================

There may already be existing customer records using older keys.

Do NOT destroy or reset existing JSON data.

Support legacy:

desc
description

and any existing button/image key variations found in the repository.

Migrate/normalize only when necessary.

If a migration is required, make it backward compatible.

==================================================
CRUD REQUIREMENT
==================================================

Keep existing CRUD functionality working:

Create project
Read project
Update project
Delete project
Upload project image
Upload hero image

Also verify Blog CRUD.

Deleting one project must not delete other projects.

Adding a project must not reset the hero fields.

Updating hero fields must not reset portfolio_data.

Updating portfolio_data must not reset hero fields.

Updating Blog data must not reset Portfolio data.

Portfolio and Blog saves must be independent unless the existing architecture intentionally combines them.

==================================================
ADMIN FORM REQUIREMENT
==================================================

Fix the Portfolio admin form so after saving:

1. Hero badge appears correctly.
2. Hero title appears correctly.
3. Hero subtitle appears correctly.
4. Primary button text appears correctly.
5. Primary button URL appears correctly.
6. Secondary button values, if supported, appear correctly.
7. Hero image preview appears correctly.
8. Project title appears correctly.
9. Category appears correctly.
10. Description appears correctly.
11. Button text appears correctly.
12. Project URL appears correctly.
13. Project image preview appears correctly.

Do not use demo defaults to hide missing database values.

If a database value is empty, show empty/default only when that is intentional.

==================================================
JAVASCRIPT CLEANUP
==================================================

Inspect the current addPortfolio() JavaScript.

There appears to be duplicated/malformed template markup after the function body.

Remove the duplicated/broken fragment.

Ensure:

addPortfolio()
removePortfolio()

work correctly.

Ensure generated input names are valid:

portfolio_data[index][title]
portfolio_data[index][category]
portfolio_data[index][desc]
portfolio_data[index][btn_text]
portfolio_data[index][link]
portfolio_data[index][image]
portfolio_data[index][image_file]

Do not create duplicate indexes.

==================================================
BLOG FIX
==================================================

Perform the same complete audit for Blog.

Verify:

Blog admin fields
↓
controller
↓
WbAgencySetting
↓
blogs_data
↓
frontend controller
↓
theme-specific Blog view
↓
Blog detail view

All must use the same customer-specific data.

Do not allow:

getDemoDefaults()

to override customer Blog data on live/customer routes.

Blog images must also use the same image path resolver.

==================================================
TEST MATRIX
==================================================

After implementation, test ALL FIVE THEMES:

1. digital_agency
2. interior
3. texigo
4. construction
5. evently

For each theme perform:

TEST 1:
Change hero badge.

Expected:
Admin reload = new value
Dashboard = new value
Live frontend = new value

TEST 2:
Change hero title.

Expected:
all three locations match.

TEST 3:
Change hero description.

Expected:
all three locations match.

TEST 4:
Change primary button text + URL.

Expected:
all three locations match.

TEST 5:
If theme supports secondary CTA:
change secondary button text + URL.

Expected:
correctly rendered.

TEST 6:
Upload a new hero image.

Expected:
image appears in:
- admin preview
- dashboard preview
- live frontend

TEST 7:
Edit a portfolio project's description.

Expected:
description survives reload and appears frontend.

TEST 8:
Upload a project image.

Expected:
correct image appears frontend.

TEST 9:
Delete a project.

Expected:
only that project disappears.

TEST 10:
Create a new project.

Expected:
existing hero data remains untouched.

TEST 11:
Edit Blog title/excerpt/content/image.

Expected:
admin + dashboard + live frontend/detail page all match.

TEST 12:
Test customer A and customer B.

Expected:
customer A can NEVER see customer B's Portfolio/Blog data.

TEST 13:
Open demo theme routes.

Expected:
demo routes continue showing demo data.

TEST 14:
Open authenticated customer preview.

Expected:
customer data, NOT demo data.

==================================================
IMPORTANT SECURITY / DATA ISOLATION
==================================================

Do not use:

WbAgencySetting::first()

for customer website rendering.

Do not trust arbitrary customer_id from request input.

Always derive the customer from the authenticated customer/session/subdomain/domain resolution.

Do not allow one customer to update another customer's agency settings.

Use authorization/ownership checks before updating.

==================================================
DELIVERABLE
==================================================

Do not simply explain the problem.

Actually inspect the repository and implement the fix.

Before changing code:

1. Identify the exact current data flow.
2. Identify why CRUD succeeds but frontend synchronization fails.
3. Identify all routes involved.
4. Identify all five Portfolio views.
5. Identify all five Blog views.
6. Identify WbAgencySetting casts/accessors/default methods.
7. Identify the Portfolio and Blog update controllers.
8. Identify image upload logic.

Then implement the smallest clean architecture that fixes the root cause.

After implementation provide:

A. Files changed
B. Exact root causes
C. What was changed in each file
D. How customer-specific data is now resolved
E. How hero image synchronization works
F. How Portfolio/Blog JSON normalization works
G. How one-button/two-button themes are preserved
H. Test results for all 5 themes
I. Any migration required
J. Any cache commands required

Do NOT redesign the five themes.
Do NOT remove existing functionality.
Do NOT replace customer data with defaults.
Do NOT hard-code demo data into customer views.

The final result must have this single source of truth:

Customer
   ↓
WbAgencySetting
   ↓
Portfolio Hero + Portfolio Data + Blog Data
   ↓
Admin Editor
   ↓
User Dashboard Preview
   ↓
Live Subdomain
   ↓
Live Custom Domain

All must display the same saved customer data.