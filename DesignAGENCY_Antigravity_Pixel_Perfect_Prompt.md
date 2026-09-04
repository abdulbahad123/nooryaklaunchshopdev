# DesignAGENCY — Pixel-Perfect Dynamic Website Implementation Prompt

## ROLE

You are a senior frontend engineer, UI/UX engineer, and design-system specialist.

Your task is to convert the provided DesignAGENCY reference screenshots into a production-ready, pixel-perfect, responsive web application.

The screenshots are the PRIMARY visual source of truth.

Do not create a generic interpretation of the design.
Do not redesign the interface.
Do not simplify the layouts.
Do not replace sections with approximate equivalents.

Reproduce the visual hierarchy, spacing, typography, colors, borders, cards, buttons, imagery, navigation, sections, responsive behavior, and overall composition as closely as technically possible.

At the same time, the application MUST be dynamic.

All business/user-specific content should ultimately come from the authenticated user's dashboard / backend / API / database instead of being hardcoded inside components.

---

# VISUAL REFERENCE — NON-NEGOTIABLE

The supplied DesignAGENCY screenshots are the visual source of truth for this implementation.

Before implementing UI, inspect both reference images carefully.

Use them to determine:
- exact layout proportions
- spacing
- typography hierarchy
- image dimensions/crops
- card sizes
- colors
- borders
- shadows
- button dimensions
- section heights
- alignment
- responsive intent

Do not substitute your own design decisions when the reference provides a clear visual answer.

After implementation, perform visual QA against the reference screenshots and iteratively correct the differences.

---

# 1. REFERENCE SCREENS

Use the two supplied screenshots as the design references.

Reference Screenshot 1:
- DesignAGENCY About Us page
- Header/navigation
- Hero section
- About/company introduction
- Mission / Vision / Values cards
- Company statistics
- Team section
- CTA banner
- Footer

Reference Screenshot 2:
- DesignAGENCY Home page
- Header/navigation
- Hero section
- Trust/client logos
- Statistics
- Services
- Portfolio / Recent Work
- Testimonials
- CTA banner
- Footer

Treat the screenshots as a visual specification.

When there is a conflict between your assumptions and the screenshots, prioritize the screenshots.

---

# 2. PRIMARY OBJECTIVE

Build the website so that:

1. The UI matches the screenshots pixel-for-pixel as closely as possible.
2. The site is fully responsive.
3. The UI is componentized and maintainable.
4. Content is data-driven.
5. Content can be changed from the user/admin dashboard without modifying frontend code.
6. Images are configurable from dashboard/API data.
7. Services, portfolio items, testimonials, team members, statistics, company information, navigation, and CTA content are dynamic.
8. The frontend supports loading, empty, error, and populated states.
9. No business content should be unnecessarily hardcoded in JSX/TSX.
10. The implementation should be production-quality rather than a static screenshot recreation.

---

# 3. IMPORTANT DATA ARCHITECTURE REQUIREMENT

The website is NOT a static landing page.

The public website should consume data from the authenticated user's/dashboard-managed data source.

Conceptually:

Dashboard/Admin
        ↓
Database / API
        ↓
Public Website
        ↓
Reusable UI Components

For example, Dashboard changes:

- Company Name
- Hero Heading
- Hero Description
- Hero Image
- Services
- Portfolio Projects
- Team Members
- Testimonials
- Statistics
- Social Links
- Contact Details

The public website should automatically display the updated information.

Do not duplicate the same content in multiple components.

Create a centralized data/API layer.

For example:

```ts
type AgencyProfile = {
  name: string;
  logo?: string;
  tagline?: string;
  description?: string;

  email?: string;
  phone?: string;
  address?: string;

  socialLinks?: {
    facebook?: string;
    twitter?: string;
    linkedin?: string;
    instagram?: string;
  };
};
```

Create appropriate models/types for:

- Agency Profile
- Hero
- Statistics
- Services
- Portfolio Projects
- Team Members
- Testimonials
- Navigation
- CTA
- Footer
- Trusted Companies / Logos
- About section
- Mission
- Vision
- Values
- Contact information

Adapt these types to the existing project's backend/API architecture if one already exists.

---

# 4. FIRST STEP — INSPECT THE EXISTING PROJECT

Before writing or replacing code:

1. Inspect the complete project structure.
2. Identify the framework.
3. Identify whether the project uses:
   - React
   - Next.js
   - TypeScript
   - Tailwind
   - CSS Modules
   - shadcn/ui
   - existing design system
   - existing API layer
   - authentication
   - dashboard
   - database
4. Identify existing reusable components.
5. Identify existing API services/hooks.
6. Identify existing image/file upload mechanisms.
7. Identify existing routing.
8. Identify existing dashboard data models.
9. Identify existing environment variables.
10. Reuse the existing architecture wherever possible.

DO NOT unnecessarily rewrite the entire project.

If an existing dashboard/API already provides agency/user data, integrate with it.

Do not create a second competing data architecture.

---

# 5. DESIGN SYSTEM

Create a consistent design system based on the screenshots.

## Overall visual style

The design should feel:

- modern
- premium
- clean
- professional
- technology-focused
- creative agency
- spacious
- minimal
- trustworthy

Primary brand colors are approximately:

- Green: #20B15A / #22B45F range
- Dark text: near #111111
- Secondary text: gray
- Background: white / very subtle off-white
- Light green backgrounds for badges and icons
- CTA green gradients where visually appropriate

Do not blindly use these exact values if the screenshot indicates a more accurate value.

Use visual inspection to tune the values.

---

# 6. TYPOGRAPHY

The typography should closely resemble the screenshots.

Use a modern geometric/sans-serif font.

Recommended fallback stack:

```css
font-family:
  Inter,
  "Plus Jakarta Sans",
  "Poppins",
  system-ui,
  sans-serif;
```

If the existing project already has a configured font, use the project's font unless it visually differs significantly from the screenshots.

Typography must have carefully tuned:

- font size
- font weight
- line height
- letter spacing
- text width
- heading wrapping

Do NOT allow headings to wrap differently from the reference screenshots unless required by responsive behavior.

---

# 7. GLOBAL PAGE STRUCTURE

The public website should use:

```text
Global Header
    ↓
Page Content
    ↓
CTA Section
    ↓
Global Footer
```

Use reusable:

```text
Header
Footer
CTASection
SectionHeading
Button
Badge
Container
IconBox
StatCard
```

architecture.

Avoid duplicated header/footer implementations.

---

# 8. CONTAINER AND GRID

The desktop layout should use a centered max-width container similar to the screenshots.

Target approximately:

```css
max-width: 1200px;
margin-inline: auto;
padding-inline: 24px;
```

Adjust based on actual screenshot proportions.

Maintain consistent horizontal alignment between:

- header
- hero
- statistics
- services
- portfolio
- testimonials
- CTA
- footer

Sections should visually line up on the same grid.

---

# 9. HEADER

Reproduce the screenshot header accurately.

Desktop structure:

```text
Top Information Bar
---------------------------------------------
tagline                         email phone social icons

Main Navigation
---------------------------------------------
Logo | Home | Services ↓ | About Us | Portfolio | Blog | Contact Us | Login | Register
```

## Top bar

Include dynamic:

- company tagline
- email
- phone
- social links

These values must come from dashboard/API data.

## Main navbar

Navigation items should be data-driven.

Example:

```ts
const navigation = [
  { label: "Home", href: "/" },
  { label: "Services", href: "/services", hasDropdown: true },
  { label: "About Us", href: "/about" },
  { label: "Portfolio", href: "/portfolio" },
  { label: "Blog", href: "/blog" },
  { label: "Contact Us", href: "/contact" }
];
```

Do not hardcode navigation directly into JSX.

## Active navigation

The active page should visually match the screenshot:

- green text
- subtle green underline
- correct spacing

## Authentication buttons

Include:

```text
Login
Register
```

with the same visual treatment as the screenshot.

---

# 10. MOBILE HEADER

The desktop navigation MUST NOT simply shrink on mobile.

Implement a proper mobile navigation.

Expected behavior:

```text
Logo                         Menu
                              ↓
                       Mobile Navigation
```

Mobile menu should include:

- navigation items
- Services submenu if applicable
- Login
- Register

Use a smooth but subtle animation.

Ensure the menu does not overflow the viewport.

---

# 11. HOME PAGE

Implement the home page shown in the second reference screenshot.

Structure:

```text
Header
Hero
Trusted Companies
Statistics
Services
Recent Work / Portfolio
Testimonials
CTA
Footer
```

---

# 12. HOME HERO

The hero is one of the most important areas.

Reproduce:

- large left-side heading
- highlighted green text
- description
- primary CTA
- secondary CTA
- trusted logos
- large right-side hero image/person
- decorative floating icons
- subtle background shapes
- statistics card overlapping the lower hero area

Hero content must be dynamic.

Example data:

```ts
type HeroSection = {
  badge?: string;
  title: string;
  highlightedText?: string;
  description: string;
  primaryButton?: {
    label: string;
    href: string;
  };
  secondaryButton?: {
    label: string;
    href: string;
  };
  image?: string;
};
```

Do not hardcode:

```text
Increase Your Customers Loyalty and Satisfaction
```

as frontend-only content.

That text should come from API/dashboard data.

---

# 13. HERO TEXT HIGHLIGHT

The screenshot uses green emphasis inside the heading.

Support dynamic highlighted text.

Example:

```text
Increase Your
Customers [Loyalty]
and Satisfaction
```

The highlighted portion should have:

- brand green
- optional underline/accent
- correct font weight

The content model should support this.

---

# 14. HERO IMAGE

Hero images must be configurable.

Do not hardcode local image paths into components.

Use:

```ts
hero.image
```

or the project's existing media/file model.

Images should use:

```css
object-fit: cover;
```

or the appropriate behavior required by the screenshot.

Maintain the same aspect ratio and positioning.

If the API image is missing:

- show a graceful fallback
- preserve the layout
- never create broken-image UI

---

# 15. HERO DECORATIONS

Recreate the visual decorative elements:

- small green diamonds
- orange shapes
- floating icon cards
- subtle decorative lines
- dotted/curved decorative elements where visible

These should be positioned relative to the hero container.

They should not cause horizontal scrolling.

Use CSS/SVG where appropriate.

Do not use unnecessary JavaScript for simple decorative elements.

---

# 16. TRUSTED COMPANIES

Reproduce the trusted-by row below the hero.

It should support dynamic logos:

```ts
trustedCompanies: [
  {
    name: string;
    logo: string;
    href?: string;
  }
]
```

Do not hardcode company logos into the component.

Maintain consistent logo dimensions and grayscale/color treatment based on the reference.

---

# 17. STATISTICS

Create a reusable dynamic statistics component.

Example:

```ts
type Statistic = {
  value: string;
  label: string;
  icon?: string;
};
```

Examples from the design:

```text
8+
Years of Experience

120+
Projects Completed

98%
Client Satisfaction

24/7
Support Available
```

These values must come from dashboard/API data.

Do not hardcode them in the component.

The component should gracefully support:

- 2 statistics
- 3 statistics
- 4 statistics
- more statistics

while maintaining visual consistency.

---

# 18. SERVICES SECTION

Create the Services section matching the screenshot.

Structure:

```text
Small section label
Heading
Description

Service cards
```

Each card should contain:

- icon
- service title
- description
- Learn More link
- hover state

Dynamic model:

```ts
type Service = {
  id: string;
  title: string;
  description: string;
  icon?: string;
  image?: string;
  slug?: string;
  href?: string;
  featured?: boolean;
  sortOrder?: number;
  isPublished?: boolean;
};
```

Services should be fetched from the dashboard/API.

Do not hardcode service cards.

---

# 19. SERVICE CARD DESIGN

Match the reference closely:

- white background
- subtle border
- rounded corners
- light icon background
- green icon
- centered content
- compact description
- green Learn More link

Hover behavior should be subtle:

- slight elevation
- border/accent transition
- icon transition

Do not introduce excessive animations.

---

# 20. PORTFOLIO / RECENT WORK

Implement the portfolio section exactly according to the screenshot.

Structure:

```text
OUR WORK
Our Recent Work

Category filters
--------------------------------
All
Web Design
UI/UX Design
Branding
App Design
Marketing

Portfolio grid
```

Portfolio data must come from the dashboard/API.

Example:

```ts
type PortfolioProject = {
  id: string;
  title: string;
  category: string;
  image: string;
  slug?: string;
  href?: string;
  description?: string;
  isPublished?: boolean;
  sortOrder?: number;
};
```

---

# 21. PORTFOLIO FILTERING

The category filter must be functional.

Do not create fake buttons.

When the user selects:

```text
All
Web Design
UI/UX Design
Branding
App Design
Marketing
```

the displayed projects should update based on project category.

Support API-driven categories if the backend already provides them.

If projects are paginated by API, integrate pagination correctly.

---

# 22. PORTFOLIO GRID

Desktop:

```text
4 columns
```

Tablet:

```text
2 columns
```

Mobile:

```text
1 column
```

Cards should maintain consistent image aspect ratio.

Each item:

```text
Image
Project Title
Category
```

Match spacing and typography to the screenshot.

---

# 23. TESTIMONIALS

Implement the testimonial section shown in the screenshot.

Dynamic model:

```ts
type Testimonial = {
  id: string;
  quote: string;
  name: string;
  role?: string;
  company?: string;
  avatar?: string;
  rating?: number;
  isPublished?: boolean;
};
```

Support:

- quote
- rating
- avatar
- name
- designation
- company

The testimonial carousel should be functional.

Do not create a fake carousel where the arrows do nothing.

---

# 24. TESTIMONIAL CAROUSEL

Desktop should show multiple testimonial cards similar to the screenshot.

Mobile should show one card at a time.

Include:

- previous button
- next button
- pagination indicators

Use the existing project's carousel library if available.

Otherwise implement a lightweight accessible carousel.

Do not add excessive autoplay unless the existing design requires it.

---

# 25. ABOUT PAGE

Implement the About Us page shown in the first reference screenshot.

Structure:

```text
Header
Hero / About Introduction
Our Story
Mission / Vision / Values
Company Statistics
Team
CTA
Footer
```

---

# 26. ABOUT HERO

Reproduce:

```text
About Us badge

We Are A Creative
Digital Solutions Agency

description

Our Portfolio
Contact Us

8+
Years of Experience

large image on right
```

All content must be dashboard/API driven.

Example:

```ts
type AboutHero = {
  badge?: string;
  title: string;
  highlightedText?: string;
  description: string;
  image?: string;
  experienceValue?: string;
  experienceLabel?: string;
};
```

---

# 27. OUR STORY

Create a two-column layout.

Left:

```text
OUR STORY

Our Journey Started With
A Simple Idea

paragraph
paragraph

signature
founder name
designation
```

Right:

```text
Our Mission
Our Vision
Our Values
```

All content must be dynamic.

---

# 28. MISSION / VISION / VALUES

Dynamic models:

```ts
type CompanyPrinciple = {
  title: string;
  description: string;
  icon?: string;
};

type CompanyValue = {
  title: string;
  description?: string;
};
```

Values should support a list.

Example:

```text
Client Success First
Innovation & Creativity
Integrity & Transparency
Quality & Excellence
```

Do not hardcode these into the UI.

---

# 29. SIGNATURE

If the dashboard provides a founder signature image, display it.

If no signature exists:

- hide the image gracefully
- preserve appropriate spacing

Do not create broken image placeholders.

---

# 30. ABOUT STATISTICS

The About page has a horizontal statistics bar.

Reproduce the screenshot:

```text
8+             250+             98%             50+
Years          Projects         Satisfaction    Team Members
```

Use the same reusable statistics component as the Home page wherever possible.

---

# 31. TEAM SECTION

Implement the team carousel/grid.

Each member:

```ts
type TeamMember = {
  id: string;
  name: string;
  designation: string;
  image: string;
  socialLinks?: {
    facebook?: string;
    twitter?: string;
    linkedin?: string;
    instagram?: string;
  };
  sortOrder?: number;
  isPublished?: boolean;
};
```

Do not hardcode team members.

The dashboard must be able to:

- add member
- edit member
- remove member
- reorder member
- change image
- change designation
- update social links
- publish/unpublish member

The website should reflect those changes.

---

# 32. TEAM CARD

Match screenshot styling:

- portrait image
- rounded top corners
- name
- designation
- social icons
- clean white card
- subtle border
- consistent image ratio

Do not distort portrait images.

---

# 33. CTA SECTION

The CTA banner appears immediately before the footer.

Reproduce the green CTA block.

Support dynamic:

```ts
type CTASection = {
  title: string;
  description?: string;
  buttonLabel: string;
  buttonHref: string;
  icon?: string;
  backgroundImage?: string;
};
```

Examples from screenshots include:

```text
Let's Build Something Amazing Together!
```

and

```text
Ready to Grow Your Business?
```

Do not hardcode either phrase.

The dashboard should control this content.

---

# 34. FOOTER

Create one reusable global footer.

Structure:

```text
Logo / Company description
Social icons

Quick Links
Services
Resources
Contact Us
```

Footer data should be dynamic.

Contact section should pull from:

```text
address
phone
email
business hours
```

Social links must come from API/dashboard.

---

# 35. FOOTER NAVIGATION

Do not hardcode duplicated navigation.

Reuse the navigation configuration/API data where appropriate.

Footer link groups should support dynamic configuration.

---

# 36. ICONS

Use a consistent icon library already installed in the project.

If none exists, use a lightweight modern icon library.

Do NOT use random emoji icons for production UI.

Icons should visually match the screenshot:

- outline style
- green brand accents
- appropriate stroke weight
- consistent size

---

# 37. IMAGES

Do not invent random images if the project already contains assets or the dashboard provides media.

Create a proper media abstraction:

```ts
getImageUrl(image)
```

or use the project's existing media helper.

Support:

- uploaded images
- remote image URLs
- CDN URLs
- dashboard-managed media

Always provide:

- alt
- loading behavior
- object-fit
- responsive sizing

---

# 38. RESPONSIVE DESIGN

The screenshots primarily represent desktop layouts, but the implementation MUST be fully responsive.

Required breakpoints:

```text
Desktop
Tablet
Mobile
```

Suggested:

```css
>= 1200px
768px - 1199px
< 768px
```

Do not merely scale the desktop UI down.

Reflow the layouts intelligently.

---

# 39. MOBILE BEHAVIOR

On mobile:

Hero:

```text
Badge
Heading
Description
Buttons
Image
Statistics
```

Services:

```text
1 card per row
```

Portfolio:

```text
1 card per row
```

Testimonials:

```text
1 card
```

Team:

```text
1 card
```

Footer:

```text
stacked columns
```

CTA:

```text
stacked content/button
```

Avoid horizontal scrolling.

---

# 40. TABLET BEHAVIOR

Tablet should generally use:

- two-column hero where possible
- two-column portfolio
- two-column services
- two-column team
- stacked footer groups where necessary

Ensure no awkward empty areas.

---

# 41. PIXEL-PERFECT REQUIREMENT

After implementation, compare the rendered website against the screenshots.

Pay special attention to:

### Header
- height
- logo position
- navigation spacing
- button sizes
- top bar height

### Hero
- heading width
- font size
- line height
- image dimensions
- image position
- button spacing
- decorative elements
- statistics overlap

### Sections
- vertical spacing
- container width
- card dimensions
- card gaps
- icon sizes
- heading alignment

### Portfolio
- image ratio
- grid gap
- typography
- category spacing

### Testimonials
- card dimensions
- quote positioning
- avatar size
- pagination

### CTA
- height
- radius
- button placement
- decorative graphics

### Footer
- column alignment
- spacing
- typography
- bottom copyright bar

Do not stop at "looks similar."

Iteratively tune CSS until the visual output closely matches the reference.

---

# 42. SPACING SYSTEM

Use a consistent spacing system.

Approximate section rhythm:

```text
Small spacing: 8px
Medium: 16px
Large: 24px
XL: 32px
2XL: 48px
3XL: 64px
4XL: 80px
```

Tune based on screenshot measurements.

Avoid arbitrary spacing values unless required for pixel accuracy.

---

# 43. BORDER RADIUS

Use rounded cards/buttons consistent with the screenshots.

Approximate:

```text
Small cards: 10–14px
Large cards: 14–18px
Buttons: 6–10px
Pills/badges: 999px
```

Tune visually.

---

# 44. BUTTONS

Create reusable button variants.

Example:

```ts
<Button variant="primary" />
<Button variant="outline" />
<Button variant="secondary" />
```

Primary:

- green background
- white text
- subtle hover

Outline:

- white background
- green border
- green text

Buttons should support:

- icon
- loading state
- disabled state
- href/navigation

---

# 45. ACCESSIBILITY

Do not sacrifice accessibility for visual similarity.

Implement:

- semantic HTML
- accessible buttons
- accessible navigation
- keyboard navigation
- visible focus states
- alt text
- proper heading hierarchy
- aria labels where necessary
- accessible carousel controls
- sufficient color contrast

Do not use clickable `<div>` elements where buttons/links are appropriate.

---

# 46. LOADING STATES

Because content comes from API/dashboard data, implement loading states.

For example:

```text
Header skeleton
Hero skeleton
Service skeleton
Portfolio skeleton
Team skeleton
Testimonial skeleton
```

Do not show a completely blank page while fetching.

Skeletons should visually fit the design.

---

# 47. EMPTY STATES

If dashboard data is empty:

Services:
- show a clean empty state or hide the section according to business requirements.

Portfolio:
- show an appropriate "No projects available" state.

Testimonials:
- hide the carousel if there are no testimonials.

Team:
- hide the section if no team members exist.

Do not render broken cards.

---

# 48. ERROR HANDLING

If an API request fails:

- do not crash the entire page
- show graceful fallback
- preserve the page structure
- allow retry where appropriate

Example:

```text
Unable to load projects.
Try again
```

Keep the UI consistent with the design.

---

# 49. DATA FETCHING

Use the project's existing data-fetching strategy.

If the project already uses:

- React Query
- SWR
- server components
- server actions
- REST
- GraphQL

reuse it.

Do not introduce another data-fetching library unnecessarily.

Create reusable hooks/services such as:

```ts
useAgencyProfile()
useHero()
useServices()
usePortfolio()
useTestimonials()
useTeamMembers()
useStatistics()
useNavigation()
```

only if appropriate for the existing architecture.

---

# 50. CACHING

Public website data should not unnecessarily refetch on every small UI interaction.

Use the existing application's caching/revalidation strategy.

Dashboard updates should eventually propagate to the public site without requiring frontend code changes.

If the application uses Next.js:

- use appropriate server-side fetching/revalidation
- use cache tags or equivalent where appropriate
- avoid exposing secrets to the browser

---

# 51. SECURITY

Never expose:

- database credentials
- secret API keys
- admin tokens
- private environment variables

to the client.

Only expose public website data.

Validate dashboard-managed content before rendering where appropriate.

---

# 52. ROUTING

Create/use routes such as:

```text
/
 /about
 /services
 /portfolio
 /blog
 /contact
```

Use the existing router architecture.

Navigation must work.

Do not create fake links that point nowhere unless explicitly required.

---

# 53. SEO

Implement proper:

- page title
- meta description
- Open Graph metadata
- canonical URL where applicable
- semantic HTML
- image alt text

SEO values should preferably be dashboard configurable.

Example:

```ts
type SEOConfig = {
  title?: string;
  description?: string;
  keywords?: string[];
  ogImage?: string;
};
```

---

# 54. PERFORMANCE

Optimize:

- images
- lazy loading
- fonts
- unnecessary JavaScript
- component rendering
- API requests

Use framework-native image optimization if available.

Do not sacrifice pixel accuracy unnecessarily.

---

# 55. ANIMATIONS

Animations should be subtle and premium.

Use:

- fade
- slight translate
- hover elevation
- smooth button transitions
- carousel transitions

Avoid:

- excessive bouncing
- huge parallax effects
- distracting animations
- animation on every element

The reference is clean and professional.

---

# 56. COMPONENT ARCHITECTURE

Use reusable components.

Suggested structure:

```text
components/
  layout/
    Header
    TopBar
    Footer
    MobileMenu

  common/
    Container
    Button
    Badge
    SectionHeading
    IconBox
    StatCard

  home/
    Hero
    TrustedCompanies
    Statistics
    Services
    Portfolio
    Testimonials
    CTA

  about/
    AboutHero
    Story
    MissionVisionValues
    CompanyStats
    Team

  portfolio/
    PortfolioFilters
    PortfolioCard
    PortfolioGrid

  testimonials/
    TestimonialCard
    TestimonialCarousel

  team/
    TeamCard
    TeamCarousel
```

Adapt this structure to the existing codebase.

Do not create excessive micro-components that make the project harder to maintain.

---

# 57. DATA SEPARATION

Never do this:

```tsx
<h1>Increase Your Customers Loyalty</h1>
```

inside a reusable Hero component.

Instead:

```tsx
<Hero data={heroData} />
```

And:

```tsx
<Hero
  data={data.hero}
/>
```

The component should render the data.

This principle applies to:

- Header
- Hero
- Services
- Portfolio
- Testimonials
- Team
- Statistics
- CTA
- Footer
- About
- Contact

---

# 58. DASHBOARD COMPATIBILITY

The public website should be designed around the assumption that an authenticated dashboard exists.

The dashboard may control:

### Company

```text
company name
logo
tagline
description
email
phone
address
business hours
social links
```

### Home

```text
hero badge
hero title
hero highlighted text
hero description
hero image
hero buttons
trusted logos
statistics
```

### Services

```text
title
description
icon
image
link
status
order
```

### Portfolio

```text
title
category
image
description
link
status
order
```

### Team

```text
name
designation
photo
social links
status
order
```

### Testimonials

```text
quote
name
designation
company
avatar
rating
status
order
```

### About

```text
story
mission
vision
values
founder
signature
experience
```

### CTA

```text
title
description
button
link
image
```

---

# 59. USER-SPECIFIC DATA

If the platform supports multiple users/agencies, the website MUST load data based on the currently selected/authenticated agency/user.

Do NOT use global hardcoded agency data.

Conceptually:

```text
authenticated user
        ↓
agency/user ID
        ↓
API query
        ↓
agency-specific content
        ↓
public website
```

For example:

```ts
getAgencyWebsiteData(agencyId)
```

should return all relevant public content.

If the existing project already has a tenant/user ID mechanism, use that.

---

# 60. NO HARDCODED BUSINESS CONTENT

Hardcoded UI labels are acceptable where they are structural.

For example:

```text
Learn More
Next
Previous
```

can remain static if appropriate.

But business-specific content must be dynamic.

Do NOT hardcode:

```text
DesignAGENCY
Michael Roberts
Sarah Johnson
8+
250+
98%
Our Mission
Our Vision
Our Values
```

unless they are fallback/demo seed data.

The actual rendered content must come from the data layer.

---

# 61. FALLBACK / DEMO DATA

If the backend is not currently available, create a temporary typed mock-data adapter.

Example:

```ts
const mockAgencyData = {
  ...
};
```

The architecture should make it easy to replace:

```text
mockAgencyData
```

with:

```text
apiAgencyData
```

without rewriting the UI.

Clearly isolate mock data from production components.

---

# 62. IMAGE FALLBACK

If an API image does not exist, do not allow layout collapse.

Create a reusable image fallback strategy.

Example:

```tsx
<SmartImage
  src={project.image}
  alt={project.title}
  fallback="/images/placeholder.jpg"
/>
```

Use the project's actual asset strategy if one exists.

---

# 63. URL / LINK HANDLING

Dashboard-managed links must be safely handled.

Support:

```text
internal route
external URL
```

External links should use appropriate behavior.

Do not blindly construct unsafe URLs.

---

# 64. RESPONSIVE IMAGE HANDLING

Every major image should have appropriate responsive behavior.

Avoid:

```css
width: 100vw;
```

when it causes overflow.

Use:

```css
max-width: 100%;
height: auto;
```

where appropriate.

Hero artwork may use controlled positioning.

---

# 65. VISUAL QA PROCESS

After implementing each major section:

1. Run the application.
2. Render the page.
3. Compare it to the supplied screenshot.
4. Identify visual differences.
5. Correct:
   - spacing
   - dimensions
   - typography
   - colors
   - alignment
   - image crop
   - borders
   - radius
   - shadows
6. Repeat.

Do not assume the first implementation is correct.

---

# 66. DESKTOP QA

At the screenshot's approximate desktop viewport, verify:

- page width
- header height
- hero height
- content alignment
- section spacing
- card sizes
- footer height
- CTA position

The visual hierarchy should closely match the screenshot.

---

# 67. MOBILE QA

Test at minimum:

```text
375px
390px
414px
768px
1024px
1280px
1440px
```

Make sure:

- no horizontal scrolling
- no overlapping content
- buttons remain usable
- headings wrap naturally
- images remain correctly positioned
- cards don't become too narrow
- footer remains readable

---

# 68. DO NOT OVERENGINEER

Do not introduce:

- unnecessary libraries
- unnecessary state management
- unnecessary abstractions
- complicated animation systems
- duplicate APIs
- duplicate components

Prefer the simplest production-quality solution.

---

# 69. DO NOT DESTROY EXISTING FUNCTIONALITY

If the existing project already has:

- authentication
- dashboard
- API
- database
- file uploads
- routing
- components

DO NOT replace them just to implement this UI.

Integrate with them.

Preserve existing functionality.

---

# 70. IF AN API IS ALREADY AVAILABLE

Inspect the API response structure first.

Map it into frontend view models where necessary.

Example:

```ts
const agency = await getAgency();

const viewModel = {
  hero: mapHero(agency),
  services: mapServices(agency.services),
  portfolio: mapPortfolio(agency.projects),
  team: mapTeam(agency.team),
  testimonials: mapTestimonials(agency.testimonials),
};
```

Do not force the backend database schema directly into every UI component.

---

# 71. IF DATABASE SCHEMA DOES NOT EXIST

If the project does not yet have the required models, create a clean schema compatible with the existing database technology.

The schema should support:

```text
Agency
AgencySettings
NavigationItem
HeroSection
Statistic
Service
PortfolioProject
PortfolioCategory
TeamMember
Testimonial
TrustedCompany
AboutSection
CompanyValue
CTASection
SEO
```

All records should be associated with the correct agency/user/tenant where multi-tenancy exists.

---

# 72. ADMIN/DASHBOARD EDITABILITY

The public site implementation should be compatible with dashboard CRUD operations.

For example:

```text
Dashboard
  ├── Website Settings
  ├── Home Page
  ├── About Page
  ├── Services
  ├── Portfolio
  ├── Team
  ├── Testimonials
  ├── Navigation
  ├── CTA
  └── SEO
```

A dashboard user should be able to modify content without touching code.

---

# 73. PUBLISHING STATE

Content entities should support publishing where appropriate:

```ts
isPublished: boolean
```

The public website should only display published content.

Draft dashboard content should not accidentally appear publicly.

---

# 74. SORT ORDER

For repeatable content such as:

- services
- projects
- team members
- testimonials
- navigation

support:

```ts
sortOrder
```

The public site should render according to that order.

---

# 75. SEO-FRIENDLY DYNAMIC CONTENT

Dynamic content should still produce crawlable HTML where the framework supports server-side rendering.

Do not make the entire website client-only just because some components are interactive.

Use server rendering/static generation where appropriate.

---

# 76. ACCESSIBILITY FOR DYNAMIC CONTENT

When content comes from the dashboard:

- require meaningful alt text for uploaded images
- use semantic headings
- ensure empty values don't create broken UI
- escape/sanitize rich text where necessary
- ensure dynamic links have meaningful labels

---

# 77. RICH TEXT

If dashboard content supports rich text:

Do not blindly render unsafe HTML.

Use the project's existing sanitization/Markdown/rich-text strategy.

Ensure typography remains visually consistent with the design.

---

# 78. ERROR BOUNDARIES

Use appropriate error boundaries or framework-level error handling.

A broken portfolio API should not destroy the entire Home page.

A missing testimonial should not break the CTA/footer.

Each major dynamic section should fail gracefully.

---

# 79. FINAL IMPLEMENTATION REQUIREMENTS

Before considering the task complete, verify:

### Visual
- [ ] Home page matches screenshot
- [ ] About page matches screenshot
- [ ] Header matches
- [ ] Hero matches
- [ ] Statistics match
- [ ] Services match
- [ ] Portfolio matches
- [ ] Testimonials match
- [ ] Team matches
- [ ] CTA matches
- [ ] Footer matches

### Dynamic
- [ ] Agency information comes from API/dashboard
- [ ] Hero comes from API/dashboard
- [ ] Statistics come from API/dashboard
- [ ] Services come from API/dashboard
- [ ] Portfolio comes from API/dashboard
- [ ] Team comes from API/dashboard
- [ ] Testimonials come from API/dashboard
- [ ] CTA comes from API/dashboard
- [ ] Footer/contact data comes from API/dashboard
- [ ] Navigation is configurable

### Functional
- [ ] Navigation works
- [ ] Mobile menu works
- [ ] Portfolio filters work
- [ ] Testimonial carousel works
- [ ] Team carousel works if applicable
- [ ] Buttons work
- [ ] External links work
- [ ] Loading states work
- [ ] Empty states work
- [ ] Error states work

### Responsive
- [ ] 375px
- [ ] 390px
- [ ] 414px
- [ ] 768px
- [ ] 1024px
- [ ] 1280px
- [ ] 1440px

### Code quality
- [ ] TypeScript types are correct
- [ ] No unnecessary `any`
- [ ] No duplicated business data
- [ ] No unnecessary libraries
- [ ] Components are reusable
- [ ] API/data layer is separated
- [ ] Existing application functionality is preserved
- [ ] No secrets exposed
- [ ] No console errors
- [ ] No broken images
- [ ] No broken links

---

# 80. IMPORTANT EXECUTION RULE

Do not immediately start coding after reading this prompt.

First:

1. Inspect the existing repository.
2. Inspect the existing dashboard.
3. Inspect the API/data layer.
4. Inspect the database/schema if available.
5. Inspect existing components.
6. Identify what can be reused.
7. Identify missing data models/endpoints.
8. Create an implementation plan.
9. Then implement the UI.

Do not ask unnecessary questions if the repository already contains the answer.

Make reasonable engineering decisions based on the existing architecture.

---

# 81. PRIORITY ORDER

When making implementation decisions, use this priority:

1. Existing project architecture
2. Reference screenshots
3. Dynamic dashboard/API integration
4. Responsive behavior
5. Accessibility
6. Performance
7. Maintainability

The final result must feel like the same website shown in the screenshots, but it must function as a real production application rather than a static mockup.

---

# 82. FINAL QUALITY BAR

The final implementation should NOT look like:

"an AI-generated website inspired by the screenshot."

It should look like:

"the actual DesignAGENCY website represented by the screenshots, implemented in production code, with all business content controlled dynamically through the user's dashboard."

Pixel accuracy is important.

Data architecture is equally important.

Do not sacrifice one for the other.

---

# 83. START NOW

Begin by inspecting the existing project and determining:

1. Current framework
2. Current frontend architecture
3. Current dashboard architecture
4. Current API architecture
5. Current database schema
6. Current authentication/tenant model
7. Existing reusable UI components
8. Existing media/image handling
9. Existing styling/design system

Then provide a concise implementation plan and proceed with the implementation.

Do not replace working architecture unnecessarily.

Build the complete responsive Home and About pages based on the supplied reference screenshots and connect every business-specific element to the appropriate dashboard/API data source.

Same for the contactus page
