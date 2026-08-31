# White-Label Agency Dynamic Landing Page — Complete Development Prompt

Build a **dynamic white-label agency website system** where every agency added by the Super Admin automatically receives its own fully branded public website/landing page.

The system must use the **first reference image as the exact visual reference** for the landing page.

## 1. Pixel-Perfect Landing Page

When a user opens an agency's public website/domain, it must open the agency's landing page.

The landing page UI must match the provided **first reference image pixel-by-pixel as closely as technically possible**.

### Requirements

* Match the reference image's:

  * Layout
  * Spacing
  * Typography
  * Font sizes
  * Font weights
  * Colors
  * Backgrounds
  * Borders
  * Border radius
  * Shadows
  * Buttons
  * Cards
  * Images
  * Icons
  * Header
  * Hero section
  * Sections
  * Footer
  * Responsive behavior
* Do not redesign the reference.
* Do not introduce unnecessary UI elements.
* Preserve the same visual hierarchy and positioning.
* Use responsive behavior while keeping the reference design as the source of truth.
* On desktop, tablet, and mobile, maintain the same design language and proportions.

The implementation should be **component-based**, but the rendered result should visually match the reference.

---

# 2. Dynamic White-Label Agency System

The landing page must NOT contain hardcoded agency information.

Every agency-specific value must come from the agency's white-label configuration/data.

For example:

* Agency name
* Agency logo
* Favicon
* Brand colors
* Primary color
* Secondary color
* Hero title
* Hero subtitle
* Description
* CTA text
* CTA URL
* Contact information
* Phone number
* Email
* Address
* Social media links
* Hero image
* Section images
* About content
* Services
* Features
* Testimonials
* Footer content
* Copyright
* Privacy Policy
* Terms & Conditions
* Cookie Policy
* Other legal content

The landing page should automatically render the correct information for the currently accessed agency.

---

# 3. Super Admin — Add White-Label Agency

Create/extend the **Super Admin** functionality so the Super Admin can create a new white-label agency.

When the Super Admin adds an agency, collect/configure:

### Agency Information

* Agency Name
* Agency Slug
* Custom Domain
* Subdomain
* Logo
* Favicon
* Contact Email
* Phone
* Address

### Branding

* Primary Brand Color
* Secondary Brand Color
* Accent Color
* Background Color
* Text Color
* Font configuration if supported

### Landing Page

* Hero title
* Hero subtitle
* Hero description
* CTA label
* CTA URL
* Hero image
* About content
* About image
* Services
* Features
* Testimonials
* FAQ
* Footer content

### Legal

* Privacy Policy
* Terms & Conditions
* Cookie Policy
* Disclaimer

The Super Admin should be able to enable/disable sections when required.

---

# 4. Automatic Landing Page Creation

When a Super Admin creates a new white-label agency:

**Do not manually create a separate website for that agency.**

Instead, the system must automatically create the agency's landing page from the existing reusable landing-page template.

Conceptually:

```text
Super Admin
     ↓
Create Agency
     ↓
Save Agency Configuration
     ↓
Generate/Activate White-Label Website
     ↓
Agency Domain/Subdomain
     ↓
Dynamic Landing Page
     ↓
Render Agency-Specific Data
```

Example:

```text
agency-a.com
      ↓
Agency A landing page

agency-b.com
      ↓
Agency B landing page

agency-c.com
      ↓
Agency C landing page
```

All agencies use the same underlying landing-page architecture, but their content, branding, images, and legal information are dynamically loaded from their own configuration.

---

# 5. White-Label Agency Dashboard

Each agency must have its own dashboard.

The agency administrator should be able to manage its public website without requiring developer changes.

Create a **Website / Landing Page** section in the agency dashboard.

The agency should be able to update:

### Branding

* Agency logo
* Favicon
* Primary color
* Secondary color
* Accent color
* Brand information

### Hero Section

* Heading
* Subheading
* Description
* CTA text
* CTA URL
* Hero image

### Content Sections

* About
* Services
* Features
* Testimonials
* FAQ
* Contact
* Other sections defined by the reference design

### Images

Allow the agency admin to upload/replace images.

When an image is changed:

```text
Agency Dashboard
      ↓
Upload New Image
      ↓
Save
      ↓
Public Landing Page Automatically Updates
```

No code deployment should be required.

---

# 6. Dynamic Content Architecture

Use a centralized agency configuration/data model.

Do NOT duplicate the landing page code for every agency.

Recommended conceptual structure:

```text
Agency
 ├── Basic Information
 ├── Branding
 ├── Domain
 ├── Landing Page Configuration
 │    ├── Hero
 │    ├── About
 │    ├── Services
 │    ├── Features
 │    ├── Testimonials
 │    ├── FAQ
 │    └── Contact
 ├── Images
 └── Legal Pages
      ├── Privacy Policy
      ├── Terms
      └── Cookie Policy
```

The frontend should identify the current agency from the domain/subdomain and load the corresponding agency configuration.

---

# 7. Agency Website Navigation

The white-label agency dashboard must contain a navigation item for managing the public landing page.

For example:

```text
Dashboard
Analytics
Users
...
Website
```

When the admin clicks **Website**, open a dropdown/sub-navigation containing the website pages.

Example:

```text
Website
   ├── Landing Page
   ├── About
   ├── Services
   ├── Testimonials
   ├── FAQ
   ├── Contact
   ├── Privacy Policy
   ├── Terms & Conditions
   └── Cookie Policy
```

The exact pages should follow the supplied reference design and business requirements.

---

# 8. Dropdown Navigation Behavior

When the user clicks the **Website** navigation item:

* Open a dropdown/sub-navigation.
* Display all available website management pages.
* Use the same visual style as the existing dashboard.
* Clearly show the active page.
* Support keyboard navigation where applicable.
* Close the dropdown appropriately when clicking outside.
* Maintain responsive/mobile behavior.

Example:

```text
Website ▼

    Landing Page
    About
    Services
    Testimonials
    FAQ
    Contact
    Privacy Policy
    Terms & Conditions
    Cookie Policy
```

---

# 9. Dynamic Privacy Policy

The Privacy Policy must also be white-label and agency-specific.

Do NOT hardcode one company's privacy policy.

The Privacy Policy page should dynamically use the current agency's:

* Agency name
* Legal/business name
* Website/domain
* Contact email
* Address
* Data-controller information
* Other configurable legal information

Example:

```text
https://agency-a.com/privacy-policy
```

should show Agency A's information.

While:

```text
https://agency-b.com/privacy-policy
```

should show Agency B's information.

The same reusable Privacy Policy template should be populated with each agency's data.

---

# 10. Terms & Conditions

Implement the same dynamic architecture for:

```text
/terms-and-conditions
```

The page must automatically use the current agency's information.

---

# 11. Cookie Policy

Implement:

```text
/cookie-policy
```

The content must dynamically use the current agency's configuration.

---

# 12. Automatic Updates

Any changes made from the agency dashboard must automatically appear on the public website.

For example:

```text
Agency Dashboard
      ↓
Change Agency Name
      ↓
Save
      ↓
Public Website
      ↓
Updated Agency Name
```

And:

```text
Agency Dashboard
      ↓
Change Hero Image
      ↓
Save
      ↓
Public Website
      ↓
New Hero Image
```

And:

```text
Agency Dashboard
      ↓
Update Privacy Policy
      ↓
Save
      ↓
Public Privacy Policy
      ↓
Updated Content
```

There should be no need to modify frontend source code for normal agency content changes.

---

# 13. Domain-Based Agency Resolution

The application must determine which agency is being accessed based on the current hostname/domain.

Conceptually:

```text
Request:
agency-a.com

        ↓

Resolve domain

        ↓

Find Agency A

        ↓

Load Agency A configuration

        ↓

Render landing page
```

For:

```text
agency-b.com
```

the system should load Agency B instead.

Do not expose another agency's content if the domain does not belong to that agency.

---

# 14. SEO & Metadata

Every agency website must dynamically generate:

* Page title
* Meta description
* Favicon
* Open Graph title
* Open Graph description
* Open Graph image
* Canonical URL
* Robots configuration where applicable

Example:

```text
Agency A

Title:
Agency A — [Dynamic Tagline]

Description:
[Agency A dynamic description]

OG Image:
[Agency A configured image]
```

---

# 15. Dynamic Footer

The footer must automatically use agency-specific data.

Include configurable:

* Logo
* Agency name
* Description
* Contact information
* Social links
* Navigation links
* Privacy Policy
* Terms & Conditions
* Cookie Policy
* Copyright

Example:

```text
© 2026 {{agency.name}}. All rights reserved.
```

Do not hardcode the agency name.

---

# 16. Image Management

All landing-page images should be configurable from the agency dashboard.

Support:

* Upload image
* Replace image
* Delete image
* Preview image
* Image URL/storage reference
* Alt text

Use the configured image dynamically on the public website.

Images should not be hardcoded into the frontend.

---

# 17. Preview Functionality

Add a **Preview Website** option in the agency dashboard.

When clicked, the agency admin should be able to preview the current landing page before publishing.

Example:

```text
Website Settings

[ Save Changes ]   [ Preview Website ]
```

Preview should display the current agency branding and content.

---

# 18. Publish / Draft State

If appropriate for the existing architecture, support:

```text
Draft
Published
```

Agency admins can edit content without immediately publishing it.

Then:

```text
Save Draft
      ↓
Preview
      ↓
Publish
      ↓
Public Website Updated
```

If the existing system does not require draft functionality, keep the implementation simpler and update the public website immediately after saving.

---

# 19. Reusable Component Architecture

Build the landing page using reusable components.

For example:

```text
AgencyLandingPage
 ├── Header
 ├── HeroSection
 ├── AboutSection
 ├── ServicesSection
 ├── FeaturesSection
 ├── TestimonialsSection
 ├── FAQSection
 ├── ContactSection
 └── Footer
```

Each component receives dynamic agency configuration.

Example:

```text
<HeroSection
    title={agency.landingPage.hero.title}
    subtitle={agency.landingPage.hero.subtitle}
    image={agency.landingPage.hero.image}
    cta={agency.landingPage.hero.cta}
/>
```

Do not create duplicated components for each agency.

---

# 20. Important Data Isolation Requirement

Agency data must be strictly isolated.

Agency A must never see:

* Agency B's logo
* Agency B's content
* Agency B's images
* Agency B's users
* Agency B's legal information
* Agency B's configuration

The domain/tenant resolution must always determine the correct agency context.

---

# 21. Reference Image Requirement

The **first reference image is the primary source of truth for the landing-page UI**.

Before implementing:

1. Analyze the reference image.
2. Identify every visible section.
3. Identify spacing and alignment.
4. Identify typography.
5. Identify colors.
6. Identify buttons.
7. Identify cards.
8. Identify images.
9. Identify navigation behavior.
10. Identify footer structure.
11. Recreate the design as accurately as possible.

Do not replace the reference design with a generic SaaS landing page.

The final rendered page should look like the reference image while all content is dynamically powered by the agency configuration.

---

# 22. Responsive Requirements

The landing page must work on:

* Desktop
* Laptop
* Tablet
* Mobile

On smaller screens:

* Navigation should become responsive.
* Sections should stack appropriately.
* Images should resize/crop correctly.
* Typography should scale appropriately.
* Buttons should remain usable.
* No horizontal overflow.
* Maintain the visual identity of the reference image.

---

# 23. Final User Flow

The complete system should work like this:

```text
SUPER ADMIN
    ↓
Create White-Label Agency
    ↓
Configure Domain
    ↓
Configure Initial Branding
    ↓
Agency Created
    ↓
Dynamic Website Automatically Available
    ↓
AGENCY ADMIN
    ↓
Login to Agency Dashboard
    ↓
Website ▼
    ├── Landing Page
    ├── About
    ├── Services
    ├── Testimonials
    ├── FAQ
    ├── Contact
    ├── Privacy Policy
    ├── Terms & Conditions
    └── Cookie Policy
    ↓
Edit Content / Images / Branding
    ↓
Save / Publish
    ↓
PUBLIC AGENCY WEBSITE
    ↓
Automatically Displays Updated Data
```

---

# 24. Acceptance Criteria

The implementation is complete only when all of the following are true:

* [ ] Opening an agency domain displays the correct agency landing page.
* [ ] Landing page visually matches the first reference image.
* [ ] Agency information is dynamically loaded.
* [ ] Agency logo is dynamically loaded.
* [ ] Agency colors are dynamically loaded.
* [ ] Hero content is dynamically loaded.
* [ ] Images are dynamically loaded.
* [ ] Content sections are dynamically loaded.
* [ ] Footer is dynamically generated.
* [ ] Privacy Policy is dynamically generated.
* [ ] Terms & Conditions are dynamically generated.
* [ ] Cookie Policy is dynamically generated.
* [ ] Super Admin can create a new white-label agency.
* [ ] Creating an agency automatically enables its website.
* [ ] Each agency gets its own isolated configuration.
* [ ] Agency Admin can edit website content.
* [ ] Agency Admin can replace images.
* [ ] Agency Admin can update branding.
* [ ] Agency Admin can update legal content.
* [ ] Changes automatically reflect on the public website.
* [ ] Website navigation contains the required dropdown.
* [ ] Landing Page and other pages are accessible through the dropdown.
* [ ] Preview functionality works.
* [ ] Responsive behavior works.
* [ ] SEO metadata is agency-specific.
* [ ] No agency data is hardcoded into the public landing-page components.
* [ ] No duplicated landing-page implementation is created for individual agencies.

## Core Principle

**One reusable landing-page system + multiple dynamically configured white-label agencies.**

The UI/template should be reusable, while the following are tenant-specific:

```text
Agency
Brand
Content
Images
Domain
Navigation
Legal Pages
SEO
Contact Information
Social Links
```

The result should behave like a true **multi-tenant white-label website builder**, where adding an agency in Super Admin automatically provisions its public website and the agency can independently manage its website content from its own dashboard.
