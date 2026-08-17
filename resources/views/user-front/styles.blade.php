<!--====== Favicon Icon ======-->
<link rel="shortcut icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}"
  type="img/png" />


<link rel="stylesheet" href="{{ asset('assets/user-front/css/plugins.css') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/aos.min.css') }}">


<link rel="stylesheet" href="{{ asset('assets/user-front/fonts/fontawesome/css/all.min.css') }}">
<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/user-front/css/common/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/common/header-1.css') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/common/zoom-fix.css?v=1.1.2') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/tinymce-content.css') }}">

@if ($userBs->theme == 'vegetables')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/grocery/home-1.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/grocery/custom-styles.css') }}">
@elseif ($userBs->theme == 'furniture')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/furniture/home-2.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/furniture/custom-styles.css?v=' . time()) }}">
@elseif ($userBs->theme == 'fashion')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/fashion/home-3.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/fashion/custom-styles.css') }}">
@elseif ($userBs->theme == 'electronics')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/electronics/home-4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/electronics/custom-styles.css?v=1.0.3') }}">
@elseif ($userBs->theme == 'kids')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/kids/home-5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/kids/custom-styles.css?v=' . time()) }}">
@elseif ($userBs->theme == 'manti')
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/manti/home-6.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/manti/custom-styles.css?v=1.0.9') }}">
@elseif ($userBs->theme == 'pet')
  <style>
    :root {
      --font-family-base: "Nunito", sans-serif !important;
      --font-family-body: 'Nunito', sans-serif !important;
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/pet/home-7.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/pet/custom-styles.css') }}">
@elseif ($userBs->theme == 'skinflow')
  <style>
    :root {
      --font-family-base: "Jost", sans-serif;
      --font-family-body: "Jost", sans-serif;
    }

    /* Skinflow Mobile View Layout & Announcement Slider Fixes */
    @media (max-width: 991.98px) {
      .home-hero-9 {
        max-height: none !important;
        height: auto !important;
        overflow: hidden !important;
        margin-bottom: 20px !important;
        padding-bottom: 0 !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
        box-sizing: border-box !important;
        width: 100% !important;
      }

      .home-hero-9 .home-hero-area,
      .home-hero-9 .slider-area,
      .home-hero-9 .hero-center-slider,
      .home-hero-9 .slick-list {
        overflow: hidden !important;
        width: 100% !important;
        position: relative !important;
        padding: 0 !important;
      }

      /* Force Slick Track to stay strictly HORIZONTAL and NEVER wrap slides vertically */
      .home-hero-9 .hero-center-slider .slick-track {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: stretch !important;
        width: 100% !important;
      }

      .home-hero-9 .hero-center-slider .slick-slide {
        flex: 0 0 100% !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        height: auto !important;
        float: none !important;
        box-sizing: border-box !important;
      }

      .home-hero-9 .hero-center-slider .slick-slide > div {
        width: 100% !important;
        height: 100% !important;
      }

      /* Slide Card Inner Styling with Equal Padding */
      .home-hero-9 .slide-item {
        height: 100% !important;
        max-height: none !important;
        min-height: auto !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 24px 20px 24px 20px !important;
        margin: 0 !important;
        width: 100% !important;
        border-radius: 20px !important;
        background: linear-gradient(180deg, #fbf7f4 0%, #f5ece5 100%) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        box-sizing: border-box !important;
      }

      .home-hero-9 .slide-item .hero-content {
        width: 100% !important;
        max-width: 100% !important;
        text-align: center !important;
        margin: 0 auto 12px auto !important;
        display: block !important;
      }

      .home-hero-9 .slide-item .hero-content .title {
        font-size: 20px !important;
        line-height: 1.3 !important;
        font-weight: 700 !important;
        margin-bottom: 8px !important;
        color: #2c221e !important;
      }

      .home-hero-9 .slide-item .hero-content .description {
        font-size: 13.5px !important;
        line-height: 1.4 !important;
        margin-bottom: 14px !important;
        color: #6e5e57 !important;
      }

      .home-hero-9 .slide-item .hero-content .btn {
        display: inline-block !important;
        padding: 9px 24px !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        border-radius: 30px !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12) !important;
      }

      .home-hero-9 .slide-item .hero-image {
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin-top: 12px !important;
        margin-bottom: 0 !important;
      }

      .home-hero-9 .slide-item img {
        display: block !important;
        max-height: 175px !important;
        height: auto !important;
        width: auto !important;
        max-width: 100% !important;
        object-fit: contain !important;
        filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.08)) !important;
      }

      .home-hero-9 .slider-arrow .slider-prev {
        left: 4px !important;
        top: 50% !important;
      }

      .home-hero-9 .slider-arrow .slider-next {
        right: 4px !important;
        top: 50% !important;
      }

      /* Below Slider (Announcement Bar) Mobile Styling - DISPLAY 1 ITEM PER ROW */
      .announcement-area {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 5 !important;
        width: 100% !important;
        margin-top: 20px !important;
        margin-bottom: 25px !important;
        padding: 12px 0 !important;
        background: linear-gradient(135deg, #fcf8f5 0%, #f4eae3 100%) !important;
        border-top: 1px solid #ebdcd3 !important;
        border-bottom: 1px solid #ebdcd3 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden !important;
      }

      .announcement-slider {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      .announcement-slider .slick-list {
        width: 100% !important;
        overflow: hidden !important;
      }

      .announcement-slider .slick-track {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
      }

      .announcement-slider .slick-slide {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        box-sizing: border-box !important;
        float: none !important;
      }

      .announcement-slider .slider-item {
        width: auto !important;
        max-width: 90% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        padding: 10px 24px !important;
        margin: 0 auto !important;
        background: rgba(255, 255, 255, 0.92) !important;
        border-radius: 30px !important;
        border: 1px solid rgba(220, 195, 180, 0.5) !important;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04) !important;
        white-space: nowrap !important;
      }

      .announcement-slider .slider-item h5 {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #382c28 !important;
        margin: 0 !important;
        letter-spacing: 0.2px !important;
      }

      .announcement-slider .slider-item i {
        font-size: 16px !important;
        color: var(--color-primary, #d9826c) !important;
        margin-left: 6px !important;
      }
    }

    @media (max-width: 575.98px) {
      .home-hero-9 {
        padding-left: 12px !important;
        padding-right: 12px !important;
      }

      .home-hero-9 .slide-item {
        padding: 20px 14px 16px 14px !important;
      }

      .home-hero-9 .slide-item .hero-content .title {
        font-size: 20px !important;
      }

      .home-hero-9 .slide-item img {
        max-height: 185px !important;
      }

      .announcement-area {
        margin-top: 18px !important;
        margin-bottom: 22px !important;
      }
    }

    /* Task 3: Skinflow Product Card Redesign (Matching Reference Image 2) */
    .product-default-tab-card {
      position: relative !important;
      z-index: 2 !important;
      background: #ffffff !important;
      border-radius: 16px !important;
      border: 1px solid #f0e6e0 !important;
      overflow: hidden !important;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
      transition: all 0.3s ease !important;
      margin-bottom: 24px !important;
      display: flex !important;
      flex-direction: column !important;
      height: 100% !important;
    }

    .product-default-tab-card:hover {
      transform: translateY(-4px) !important;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
    }

    .product-default-tab-card .product-img {
      position: relative !important;
      overflow: hidden !important;
      border-radius: 16px 16px 0 0 !important;
      background: #f8f4f0 !important;
      margin-bottom: 0 !important;
      width: 100% !important;
    }

    .product-default-tab-card .product-img img {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
      transition: transform 0.4s ease !important;
    }

    .product-default-tab-card:hover .product-img img {
      transform: scale(1.05) !important;
    }

    .skinflow-discount-badge {
      position: absolute !important;
      top: 10px !important;
      left: 10px !important;
      width: 42px !important;
      height: 42px !important;
      border-radius: 50% !important;
      background: #e54848 !important;
      color: #ffffff !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      z-index: 10 !important;
      box-shadow: 0 4px 10px rgba(229, 72, 72, 0.35) !important;
      line-height: 1.1 !important;
      text-align: center !important;
    }

    .skinflow-discount-badge .percent {
      font-size: 11px !important;
      font-weight: 800 !important;
    }

    .skinflow-discount-badge .text {
      font-size: 8px !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
    }

    .skinflow-card-actions {
      position: absolute !important;
      bottom: 10px !important;
      left: 10px !important;
      display: flex !important;
      align-items: center !important;
      gap: 5px !important;
      z-index: 10 !important;
      margin: 0 !important;
    }

    .skinflow-card-actions .btn-icon {
      width: 32px !important;
      height: 32px !important;
      min-width: 32px !important;
      border-radius: 50% !important;
      background: #ffffff !important;
      border: 1px solid #e2dad2 !important;
      color: #444444 !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 12px !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
      transition: all 0.2s ease !important;
      padding: 0 !important;
    }

    .skinflow-card-actions .btn-icon i {
      font-size: 12px !important;
      margin: 0 !important;
    }

    .skinflow-card-actions .btn-icon:hover {
      background: #2c221e !important;
      color: #ffffff !important;
      border-color: #2c221e !important;
    }

    .skinflow-card-actions .btn-icon.cart-link {
      background: #e54848 !important;
      color: #ffffff !important;
      border-color: #e54848 !important;
    }

    .skinflow-card-actions .btn-icon.cart-link:hover {
      background: #c83636 !important;
      border-color: #c83636 !important;
    }

    .product-default-tab-card .product-details {
      position: relative !important;
      z-index: 2 !important;
      padding: 12px 14px 14px 14px !important;
      background: #ffffff !important;
      text-align: left !important;
      width: 100% !important;
      top: auto !important;
      left: auto !important;
      bottom: auto !important;
      margin-top: auto !important;
    }

    .product-default-tab-card .product-details::after {
      display: none !important;
    }

    .product-default-tab-card .product-title {
      font-size: 14.5px !important;
      font-weight: 600 !important;
      color: #222222 !important;
      line-height: 1.3 !important;
      margin-bottom: 4px !important;
      text-align: left !important;
    }

    .product-default-tab-card .product-title a {
      color: #222222 !important;
      text-decoration: none !important;
    }

    .product-default-tab-card .product-title a:hover {
      color: #e54848 !important;
    }

    .product-default-tab-card .product-price {
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      justify-content: flex-start !important;
      margin-top: 2px !important;
    }

    .product-default-tab-card .product-price .new-price {
      font-size: 15px !important;
      font-weight: 700 !important;
      color: #e54848 !important;
    }

    .product-default-tab-card .product-price .old-price {
      font-size: 13px !important;
      font-weight: 500 !important;
      color: #888888 !important;
      text-decoration: line-through !important;
    }

    @media (max-width: 575.98px) {
      .products-tab-8 .row > [class*="col-"] {
        padding-left: 6px !important;
        padding-right: 6px !important;
      }

      .product-default-tab-card {
        border-radius: 12px !important;
        margin-bottom: 12px !important;
      }

      .product-default-tab-card .product-img {
        border-radius: 12px 12px 0 0 !important;
      }

      .skinflow-discount-badge {
        width: 34px !important;
        height: 34px !important;
        top: 6px !important;
        left: 6px !important;
      }

      .skinflow-discount-badge .percent {
        font-size: 10px !important;
      }

      .skinflow-discount-badge .text {
        font-size: 7px !important;
      }

      .skinflow-card-actions {
        bottom: 6px !important;
        left: 6px !important;
        gap: 3px !important;
      }

      .skinflow-card-actions .btn-icon {
        width: 25px !important;
        height: 25px !important;
        min-width: 25px !important;
        font-size: 9px !important;
      }

      .skinflow-card-actions .btn-icon i {
        font-size: 9px !important;
      }

      .product-default-tab-card .product-details {
        padding: 8px 8px 10px 8px !important;
      }

      .product-default-tab-card .product-title {
        font-size: 12.5px !important;
        margin-bottom: 2px !important;
      }

      .product-default-tab-card .product-price .new-price {
        font-size: 13.5px !important;
      }

      .product-default-tab-card .product-price .old-price {
        font-size: 11px !important;
      }
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/skinflow/home-8.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/skinflow/custom-styles.css?v=' . time()) }}">
@elseif ($userBs->theme == 'jewellery')

  <style>
    :root {
      --font-family-base: "Merriweather", serif !important;
      --font-family-body: "Jost", sans-serif !important;
    }

    /* =====================================================
       JEWELLERY THEME — MOBILE HORIZONTAL SCROLL FIX
       Inline so it cannot be cached. Only loads for jewellery.
       ===================================================== */

    /* Prevent horizontal scroll at page level */
    html, body {
      overflow-x: hidden !important;
      max-width: 100% !important;
    }

    @media (max-width: 767.98px) {
      .main-panel, .wrapper, .footer-area {
        overflow-x: hidden !important;
        width: 100% !important;
        position: relative !important;
      }
      body {
        position: relative !important;
      }
    }

    /* Clip all major jewellery sections */
    .home-hero-8,
    .featured-8,
    .category-8,
    .products-section-v8,
    .flash-sale-section,
    .banner-section {
      overflow-x: hidden !important;
      max-width: 100% !important;
    }

    @media (max-width: 767.98px) {
      /* Disable all AOS animations on mobile for jewellery theme */
      [data-aos] {
        opacity: 1 !important;
        transform: translate3d(0, 0, 0) !important;
        transition: none !important;
        animation: none !important;
      }

      /* Disable hover "shine" effect on banners (mobile touch/scroll triggers hover) */
      .banner-md:hover::after, .banner-xl:hover::after {
        animation: none !important;
        display: none !important;
      }

      /* Fix hero background-attachment:fixed — collapses on iOS/Android */
      .home-hero-8 {
        background-attachment: scroll !important;
        background-size: cover !important;
        background-position: center center !important;
        padding: 60px 15px !important;
        min-height: 340px;
      }

      .home-hero-8 .hero-card-wrapper {
        max-width: 100% !important;
        width: 100%;
        padding: 16px;
      }

      /* Fix fluid-left and fluid-right negative calc causing huge overflow */
      .fluid-left, .fluid-right {
        padding-inline-start: 0 !important;
        padding-inline-end: 0 !important;
      }

      /* Force category row to wrap on mobile instead of staying nowrap */
      .category-8 .row.flex-nowrap {
        flex-wrap: wrap !important;
      }

      /* Hide the vertical rotated title (squishes layout on narrow screens) */
      .category-8 .vertical-title {
        display: none !important;
      }

      /* Make category slider take full row width */
      .category-8 .col-auto {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
      }

      /* Fix featured item huge padding causing overflow */
      .featured-8 .featured-item {
        padding: 0 16px !important;
      }

      /* Fix row negative margins inside featured wrap pushing width > 100vw */
      .featured-8 .featured-item-wrap.row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
      }
    }

    @media (max-width: 575.98px) {
      .home-hero-8 {
        padding: 40px 10px !important;
        min-height: 300px;
      }

      .featured-8 .featured-item {
        padding: 0 10px !important;
      }
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/jewellery/jewellery.css?v=2.0.3') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/jewellery/custom-styles.css?v=2.0.3') }}">
@elseif ($userBs->theme == 'clothing')

  <style>
    :root {
      --font-family-base: "Jost", sans-serif;
      --font-family-body: "Jost", sans-serif;
      --font-family-heading: "Cormorant Garamond", serif;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/clothing/clothing.css?v=1.0.5') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/clothing/custom-styles.css?v=1.0.5') }}">
@elseif ($userBs->theme == 'grocery2')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/grocery2/styles.css') }}">
@endif
<!--====== Style css ======-->

<!--====== RTL css ======-->
@if ($userCurrentLang->rtl == 1)
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
@endif
@if ($userCurrentLang->rtl == 1 & ($userBs->theme == 'pet'))
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/pet/home-7-rtl.css') }}">
@endif
@if ($userCurrentLang->rtl == 1 & ($userBs->theme == 'skinflow'))
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/skinflow/home-8-rtl.css') }}">
@endif
@if ($userCurrentLang->rtl == 1 & ($userBs->theme == 'jewellery'))
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/jewellery/jewellery-rtl.css') }}">
@endif

@if ($userBs->theme == 'manti' || $userBs->theme == 'vegetables' || $userBs->theme == 'grocery' || $userBs->theme == 'kids' || $userBs->theme == 'fashion' || $userBs->theme == 'electronics')
<style>
  .header-bottom {
    margin-top: 1rem !important;
  }
</style>
@endif

<style>
/* ==========================================================================
   MOBILE & LAYOUT ALIGNMENT FIXES (All Templates)
   ========================================================================== */

/* --- Global Horizontal (Bottom) Scroll Prevention for All Themes --- */
html {
  overflow-x: hidden !important;
}

body {
  overflow-x: hidden !important;
  max-width: 100% !important;
  width: 100% !important;
  position: relative !important;
}

.wrapper,
.main-panel,
.content,
.page-inner,
.header-area,
.footer-area,
.main-wrapper,
.body-wrapper,
.page-wrapper,
section,
header,
footer {
  overflow-x: clip !important;
  max-width: 100% !important;
}

.shop-category-pills {
  overflow-x: auto !important;
  overflow-y: hidden !important;
  scrollbar-width: none !important;
  -ms-overflow-style: none !important;
}

.shop-category-pills::-webkit-scrollbar {
  display: none !important;
  width: 0 !important;
  height: 0 !important;
}

/* --- Task 2: Page Title Area (About/Inner Headers) Spacing Fix --- */
@if ($userBs->theme == 'fashion' || $userBs->theme == 'furniture' || $userBs->theme == 'clothing' || $userBs->theme == 'jewellery' || $userBs->theme == 'skinflow' || $userBs->theme == 'pet')
/* For templates with fixed headers, we need top padding to prevent header overlap */
.page-title-area {
  padding-top: 100px !important;
  padding-bottom: 30px !important;
}

@media only screen and (max-width: 991.98px) {
  .page-title-area {
    padding-top: 80px !important;
    padding-bottom: 25px !important;
  }
}
@else
/* For other templates, use the compact spacing */
.page-title-area {
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}

@media only screen and (max-width: 991.98px) {
  .page-title-area {
    padding-top: 15px !important;
    padding-bottom: 23px !important;
  }
}
@endif

/* Reduce page title font size on desktop only */
@media only screen and (min-width: 992px) {
  @if ($userBs->theme == 'fashion' || $userBs->theme == 'furniture' || $userBs->theme == 'clothing' || $userBs->theme == 'jewellery' || $userBs->theme == 'skinflow' || $userBs->theme == 'pet')
  /* Keep compact padding for fixed header templates on desktop */
  .page-title-area {
    padding-top: 100px !important;
    padding-bottom: 30px !important;
  }
  @else
  .page-title-area {
    padding-top: 30px !important;
    padding-bottom: 30px !important;
  }
  @endif

  .page-title-area h1,
  .page-title-area h2,
  .page-title-area h3,
  .page-title-area .content h2 {
    font-size: 28px !important;
  }

  .product-single {
    padding-top: 40px !important;
  }
}

/* --- Task 4: Hero Slider Product Thumb Overlap Fix --- */
@media only screen and (max-width: 767.98px) {
  .home-slider .product-thumb {
    position: relative !important;
    left: 0 !important;
    margin-top: 30px !important;
    width: 100% !important;
    max-width: 100% !important;
    bottom: auto !important;
    padding: 0 15px !important;
    display: block !important;
  }
}

/* --- Task 1: Product Card Button Action Icons Group Mobile Fit --- */
@media only screen and (max-width: 575.98px) {
  /* Prevent action buttons from wrapping and keep on a single line */
  .btn-icon-group, 
  .btn-icon-group-area,
  .product-default .btn-icon-group,
  .product-default-2 .btn-icon-group,
  .product-default-3 .btn-icon-group,
  .product-default-7 .btn-icon-group,
  .product-default-8 .btn-icon-group,
  .product-default-9 .btn-icon-group,
  .product-default-tab-card .btn-icon-group {
          display: flex !important;
        flex-direction: row !important;
        /* flex-wrap: wrap !important; */
        white-space: nowrap !important;
        gap: 4px !important;
        align-items: center !important;
        width: 100% !important;
        justify-content: center;
        margin-bottom: 2px;
  }

  .btn-icon-group.text-center,
  .btn-icon-group.justify-content-center,
  .product-center .btn-icon-group {
    justify-content: center !important;
  }

  .btn-icon-group.text-start,
  .btn-icon-group.justify-content-start {
    justify-content: flex-start !important;
  }

  .btn-icon-group.text-end,
  .btn-icon-group.justify-content-end {
    justify-content: flex-end !important;
  }

  /* Shrink button sizes inside the action group to fit card width */
  .btn-icon-group .btn-icon,
  .btn-icon-group a.btn,
  .btn-icon-group button.btn,
  .btn-icon-group a,
  .btn-icon-group button {
    --size: 26px !important;
    width: 26px !important;
    height: 26px !important;
    min-width: 26px !important;
    max-width: 26px !important;
    line-height: 24px !important;
    font-size: 10px !important;
    padding: 0 !important;
    margin: 0 1px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
  }

  .btn-icon-group .btn-icon i,
  .btn-icon-group a.btn i,
  .btn-icon-group button.btn i,
  .btn-icon-group a i,
  .btn-icon-group button i {
    font-size: 10px !important;
    margin: 0 !important;
    padding: 0 !important;
  }
}

/* --- Task 3: Single Product Gallery Alignment & Thumbnails --- */
@media only screen and (max-width: 575.98px) {
  .product-single-default .product-single-gallery {
    flex-direction: column !important;
    align-items: center !important;
    gap: 15px !important;
    width: 100% !important;
  }

  .product-single-default .slider-thumbnails,
  .product-single-default .slider-thumbnails2 {
    width: 100% !important;
    max-width: 100% !important;
    order: 2 !important;
    margin-top: 10px !important;
    display: block !important;
    height: auto !important;
  }

  .product-single-default .slider-thumbnails .slick-list,
  .product-single-default .slider-thumbnails2 .slick-list {
    height: auto !important;
  }

  .product-single-default .slider-thumbnails2 .slick-track,
  .product-single-default .slider-thumbnails .slick-track {
    height: auto !important;
  }

  .product-single-default .product-single-slider,
  .product-single-default .product-single-slider2 {
    width: 100% !important;
    max-width: 100% !important;
    order: 1 !important;
    margin-bottom: 0 !important;
  }

  /* Make thumbnail items nice squares on mobile */
  .product-single-default .slider-thumbnails2 .thumbnail-img,
  .product-single-default .slider-thumbnails .thumbnail-img {
    width: 65px !important;
    height: 65px !important;
    max-width: 65px !important;
    max-height: 65px !important;
    /* margin: 0 auto !important; */
    border: 1px solid var(--border) !important;
    border-radius: 6px !important;
  }

  .product-single-default .slider-thumbnails2 .thumbnail-img img,
  .product-single-default .slider-thumbnails .thumbnail-img img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
  }
}

/* --- Task 4: Reduce font size for the third slider image/slide --- */
.third-slide-content .title, 
.third-slide-content h1 {
  font-size: clamp(1.5rem, 0.5rem + 2vw, 3rem) !important;
}
.third-slide-content .sub-title, 
.third-slide-content span {
  font-size: 14px !important;
}
.third-slide-content .text-lg, 
.third-slide-content p {
  font-size: 14px !important;
}

/* Custom class for product details page related products spacing */
.product-details-related-products {
  padding-top: 20px !important;
}

/* --- Task 3: Cart Popup Padding & Styling Fix (All Templates) --- */
.cart-dropdown,
#cart-dropdown-header,
#cart-dropdown-mobile {
  padding: 20px 24px 24px 24px !important;
  box-sizing: border-box !important;
  border-radius: 8px !important;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
  border: 1px solid rgba(0, 0, 0, 0.08) !important;
}

.cart-dropdown-list {
  padding: 0 !important;
  margin: 0 0 15px 0 !important;
  list-style: none !important;
}

.cart-dropdown-list-item {
  position: relative !important;
  padding: 15px 0 !important;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
  display: flex !important;
  align-items: flex-start !important;
  width: 100% !important;
  box-sizing: border-box !important;
}

.cart-dropdown-list-item:last-child {
  border-bottom: none !important;
}

.cart-dropdown-list-item .cart-img {
  width: 70px !important;
  height: 70px !important;
  margin-right: 15px !important;
  flex-shrink: 0 !important;
  border-radius: 6px !important;
  border: 1px solid rgba(0, 0, 0, 0.08) !important;
  overflow: hidden !important;
}

.cart-dropdown-list-item .cart-img img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
}

.cart-dropdown-list-item .cart-title {
  flex-grow: 1 !important;
  min-width: 0 !important;
  padding-right: 10px !important;
}

.cart-dropdown-list-item .cart-title .product-title {
  margin-top: 0 !important;
  margin-bottom: 6px !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  line-height: 1.3 !important;
}

.cart-dropdown-list-item .cart-title .product-title a {
  color: #111 !important;
  text-decoration: none !important;
}

.cart-dropdown-list-item .cart-delete {
  position: static !important;
  margin-left: auto !important;
  flex-shrink: 0 !important;
  align-self: flex-start !important;
  right: auto !important;
  top: auto !important;
  transform: none !important;
}

.cart-dropdown-list-item .cart-delete .btn-remove {
  width: 24px !important;
  height: 24px !important;
  line-height: 24px !important;
  font-size: 12px !important;
  border-radius: 50% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  background: rgba(0, 0, 0, 0.05) !important;
  color: #666 !important;
  transition: all 0.2s ease !important;
}

.cart-dropdown-list-item .cart-delete .btn-remove:hover {
  background: #ff3f5c !important;
  color: #fff !important;
}

/* Variation table overrides inside cart items */
.cart-dropdown-list-item .variation-table {
  width: 100% !important;
  margin: 4px 0 !important;
  border-collapse: collapse !important;
  border: none !important;
}

.cart-dropdown-list-item .variation-table tr {
  display: flex !important;
  justify-content: space-between !important;
  align-items: flex-start !important;
  width: 100% !important;
  margin-bottom: 2px !important;
}

.cart-dropdown-list-item .variation-table td {
  padding: 0 !important;
  border: none !important;
  font-size: 12px !important;
  background: transparent !important;
}

.cart-dropdown-list-item .variation-table td:first-child {
  font-weight: 500 !important;
  color: #666 !important;
  padding-right: 10px !important;
}

.cart-dropdown-list-item .variation-table td:last-child {
  text-align: right !important;
  font-weight: 600 !important;
  color: #111 !important;
}

.cart-footer {
  padding-top: 15px !important;
  border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
}

/* Empty cart spacing */
.cart-dropdown h4.text-center,
#cart-dropdown-header h4.text-center,
#cart-dropdown-mobile h4.text-center {
  padding: 30px 10px !important;
  margin: 0 !important;
  font-size: 16px !important;
  font-weight: 500 !important;
  color: #6c757d !important;
}

/* --- Global Cart Sidebar Drawer & Overlay Backdrop --- */
.cart-sidebar-overlay {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  width: 100vw !important;
  height: 100vh !important;
  background: rgba(0, 0, 0, 0.4) !important;
  z-index: 999999 !important;
  opacity: 0 !important;
  visibility: hidden !important;
  transition: all 0.3s ease !important;
}

.cart-sidebar-overlay.active {
  opacity: 1 !important;
  visibility: visible !important;
}

#cart-dropdown-mobile {
  position: fixed !important;
  top: 0 !important;
  right: -420px !important;
  left: auto !important;
  width: 420px !important;
  max-width: 100% !important;
  height: 100vh !important;
  background: #ffffff !important;
  border-left: 1px solid #e5e1db !important;
  border-right: none !important;
  box-shadow: -10px 0 40px rgba(0, 0, 0, 0.15) !important;
  margin-top: 0 !important;
  z-index: 1000000 !important;
  border-radius: 0 !important;
  padding: 30px 24px !important;
  opacity: 1 !important;
  visibility: visible !important;
  transform: none !important;
  transition: right 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
  display: flex !important;
  flex-direction: column !important;
}

#cart-dropdown-mobile.active {
  right: 0 !important;
  left: auto !important;
}

/* Close button and list item styling overrides for sidebar drawer */
.cart-dropdown .cart-header {
  display: flex !important;
}

#cart-dropdown-mobile .cart-dropdown-list {
  flex: 1 !important;
  overflow-y: auto !important;
  margin-bottom: 20px !important;
}


/* ── Ensure header-top is a single line on desktop screens (min-width: 1600px) ── */
@media (min-width: 1600px) {
  .header-top .row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
  }
  /* Hide the empty middle column */
  .header-top .row > div:nth-child(2) {
    display: none !important;
  }
  /* Expand left and right columns to fill available space */
  .header-top .row > div:first-child {
    flex: 1 1 auto !important;
    width: auto !important;
  }
  .header-top .row > div:last-child {
    flex: 1 1 auto !important;
    max-width: 50% !important;
    width: auto !important;
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
  }
  /* Force right menu items to stay inline */
  .header-top .header-right ul.menu {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    margin: 0 !important;
    padding: 0 !important;
    gap: 15px !important;
  }
  .header-top .header-right ul.menu > li {
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center !important;
  }
}

/* Ensure all modals and backdrops are rendered above any headers */
.modal {
  z-index: 999999 !important;
}
.modal-backdrop {
  z-index: 999998 !important;
}

/* Prevent gallery slider and thumbnail overlap on laptop/desktop views */
@media (min-width: 992px) {
  .product-single-default .product-single-gallery {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: start !important;
    gap: 0 !important;
  }
  .product-single-default .slider-thumbnails,
  .product-single-default .slider-thumbnails2 {
    width: 80px !important;
    flex: 0 0 80px !important;
    max-width: 80px !important;
    margin-right: 20px !important;
  }
  .product-single-default .product-single-slider,
  .product-single-default .product-single-slider2 {
    width: calc(100% - 100px) !important;
    flex: 0 0 calc(100% - 100px) !important;
    max-width: calc(100% - 100px) !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }
}

/* --- Global Header Logo Size Enhancements (All Themes) --- */
.mobile-navbar .logo img,
.mobile-navbar-inner .logo img,
.header-area .logo img,
.header-navigation .logo img {
  max-height: 55px !important;
  max-width: 230px !important;
  width: auto !important;
  height: auto !important;
  object-fit: contain !important;
}

@media (min-width: 992px) {
  .brand-logo img,
  .header-middle .brand-logo img,
  .header-area .brand-logo img,
  .navbar-brand img,
  .site-logo img {
    max-height: 75px !important;
    max-width: 280px !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
  }
}

/* --- Global Mobile Copyright Display & Bottom Clearance Fix (All Themes) --- */
@media only screen and (max-width: 991.98px) {
  footer, .footer-area, .copy-right-area, .copy-right-content, .footer-bottom {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
  }
  footer, .footer-area {
    padding-bottom: 5px !important;
  }
  .copy-right-area, .footer-bottom {
    padding-top: 15px !important;
    /* padding-bottom: 85px !important; */
    margin-bottom: 0 !important;
    text-align: center !important;
    display: block !important;
    position: relative !important;
    z-index: 10 !important;
  }
  .copy-right-content span, .copy-right-area p, .copy-right-area span, .footer-bottom span, .footer-bottom p {
    display: inline-block !important;
    color: inherit !important;
    font-size: 13px !important;
    line-height: 1.5 !important;
  }
}

/* =========================================================
   UNIVERSAL PIXEL-PERFECT QUICK VIEW MODAL (ALL THEMES)
   ========================================================= */

.quick-view-modal .modal-dialog,
#quickViewModal .modal-dialog {
    max-width: 900px !important;
    margin: 30px auto !important;
}

.quick-view-modal .modal-content,
#quickViewModal .modal-content {
    border-radius: 16px !important;
    padding: 28px !important;
    border: none !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
    background: #ffffff !important;
    position: relative !important;
}

/* Close Button (Top Right Red X) */
.quick-view-modal .close_modal_btn,
.quick-view-modal .btn-close,
#quickViewModal .close_modal_btn,
#quickViewModal .btn-close {
    position: absolute !important;
    top: 14px !important;
    right: 14px !important;
    width: 32px !important;
    height: 32px !important;
    background-color: #ff3f5c !important;
    color: #ffffff !important;
    border-radius: 6px !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 16px !important;
    opacity: 1 !important;
    cursor: pointer !important;
    z-index: 100 !important;
    transition: all 0.2s ease !important;
}

.quick-view-modal .close_modal_btn:hover,
.quick-view-modal .btn-close:hover,
#quickViewModal .close_modal_btn:hover,
#quickViewModal .btn-close:hover {
    background-color: #e02847 !important;
}

/* Gallery Container Layout */
.quick-view-modal .product-single-gallery,
#quickViewModal .product-single-gallery {
    display: flex !important;
    flex-direction: row !important;
    align-items: flex-start !important;
    gap: 14px !important;
    width: 100% !important;
    height: 372px !important;
}

/* Left Vertical Thumbnails Column */
.quick-view-modal .slider-thumbnails,
#quickViewModal .slider-thumbnails {
    width: 68px !important;
    flex: 0 0 68px !important;
    max-width: 68px !important;
    height: 372px !important;
    margin: 0 !important;
    padding: 0 !important;
}

.quick-view-modal .slider-thumbnails .thumbnail-img,
#quickViewModal .slider-thumbnails .thumbnail-img {
    width: 68px !important;
    height: 68px !important;
    max-width: 68px !important;
    max-height: 68px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    margin-bottom: 8px !important;
    cursor: pointer !important;
    background-color: #f8fafc !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
    padding: 2px !important;
}

.quick-view-modal .slider-thumbnails .slick-slide.slick-current .thumbnail-img,
.quick-view-modal .slider-thumbnails .slick-slide.slick-active.slick-current .thumbnail-img,
.quick-view-modal .slider-thumbnails .thumbnail-img.active,
.quick-view-modal .slider-thumbnails .thumbnail-img:hover,
#quickViewModal .slider-thumbnails .slick-slide.slick-current .thumbnail-img,
#quickViewModal .slider-thumbnails .thumbnail-img.active {
    border-color: #0f5b3f !important;
    border-width: 2px !important;
}

.quick-view-modal .slider-thumbnails .thumbnail-img img,
#quickViewModal .slider-thumbnails .thumbnail-img img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    border-radius: 6px !important;
    opacity: 1 !important;
    filter: none !important;
    -webkit-filter: none !important;
    display: block !important;
}

/* Center Main Product Image Slider */
.quick-view-modal .product-single-slider,
#quickViewModal .product-single-slider {
    flex: 1 !important;
    width: calc(100% - 82px) !important;
    max-width: calc(100% - 82px) !important;
    height: 372px !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    background-color: #f8fafc !important;
    margin: 0 !important;
    padding: 0 !important;
}

.quick-view-modal .product-single-slider figure,
#quickViewModal .product-single-slider figure {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: 372px !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    background-color: #f8fafc !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.quick-view-modal .product-single-slider img,
.quick-view-modal .product-single-slider figure img,
#quickViewModal .product-single-slider img,
#quickViewModal .product-single-slider figure img,
.quick-view-modal .product-single-slider .slick-slide img,
#quickViewModal .product-single-slider .slick-slide img {
    width: 100% !important;
    height: 372px !important;
    max-height: 372px !important;
    object-fit: contain !important;
    border-radius: 12px !important;
    opacity: 1 !important;
    visibility: visible !important;
    filter: none !important;
    -webkit-filter: none !important;
    background: transparent !important;
    display: block !important;
    pointer-events: auto !important;
}

.quick-view-modal .lazy-container,
#quickViewModal .lazy-container {
    background-color: transparent !important;
}

.quick-view-modal .lazy-container::after,
#quickViewModal .lazy-container::after {
    display: none !important;
}

#quickViewModal .zoomContainer,
#quickViewModal .zoomWindowContainer,
.quick-view-modal .zoomContainer,
.quick-view-modal .zoomWindowContainer {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

#quickViewModal .product-single-slider .slick-slide a,
.quick-view-modal .product-single-slider .slick-slide a {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 100% !important;
}

/* Right Details Column Alignments */
.quick-view-modal .product-single-details,
#quickViewModal .product-single-details {
    padding-left: 10px !important;
}

.quick-view-modal .product-title,
#quickViewModal .product-title {
    font-size: 21px !important;
    font-weight: 700 !important;
    line-height: 1.35 !important;
    color: #1e293b !important;
    margin-bottom: 10px !important;
}

.quick-view-modal .rating-wrapper,
#quickViewModal .rating-wrapper {
    font-size: 13px !important;
    color: #64748b !important;
}

.quick-view-modal .stock-status .badge.bg-success,
#quickViewModal .stock-status .badge.bg-success {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    padding: 4px 10px !important;
    border-radius: 6px !important;
}

.quick-view-modal .product-price,
#quickViewModal .product-price {
    display: flex !important;
    align-items: baseline !important;
    gap: 10px !important;
    margin-top: 12px !important;
    margin-bottom: 12px !important;
}

.quick-view-modal .new-price-area .new-price,
#quickViewModal .new-price-area .new-price {
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #0f5b3f !important;
}

.quick-view-modal .old-price-area .old-price,
#quickViewModal .old-price-area .old-price {
    font-size: 16px !important;
    color: #94a3b8 !important;
    text-decoration: line-through !important;
}

.quick-view-modal .discountoff,
#quickViewModal .discountoff {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #f97316 !important;
}

.quick-view-modal .btn-primary,
#quickViewModal .btn-primary {
    background-color: #0f5b3f !important;
    border-color: #0f5b3f !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border-radius: 8px !important;
    padding: 10px 24px !important;
}

.quick-view-modal .social-link a,
#quickViewModal .social-link a {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background-color: #0f5b3f !important;
    color: #ffffff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
</style>

@yield('styles')
