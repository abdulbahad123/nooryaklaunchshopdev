@extends('front.layout')

@section('meta-keywords', !empty($seo) ? $seo->about_meta_keywords : '')
@section('meta-description', !empty($seo) ? $seo->about_meta_description : '')
@php
  $additional_section_status = json_decode($bs->about_additional_section_status, true);
@endphp
@section('pagename')
  - {{ __('About') }}
@endsection
@section('breadcrumb-title')
  {{ __('About') }}
@endsection
@section('breadcrumb-link')
  {{ __('About') }}
@endsection

@section('styles')
<style>
  /* Disable the default page breadcrumb area */
  .page-title-area {
      display: none !important;
  }

  /* Core Layout & Font */
  .about-page-wrapper {
      background: #fafbfe;
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      position: relative;
  }

  /* Decorative Floating Blobs */
  .about-decor-blob {
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      filter: blur(120px);
      opacity: 0.12;
      pointer-events: none;
      z-index: 0;
  }
  .about-blob-primary { background: #ff5a2c; top: 5%; right: -100px; }
  .about-blob-secondary { background: #3b82f6; bottom: 25%; left: -150px; width: 500px; height: 500px; }
  .about-blob-accent { background: #8b5cf6; top: 50%; right: -100px; }

  /* Custom Premium Hero Banner */
  .about-hero-section {
      background: radial-gradient(circle at 100% 0%, #fff6f3 0%, #ffffff 70%, #f4f6fa 100%);
      padding: 130px 0 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
      border-bottom: 1px solid #eef2f6;
  }
  .about-hero-section::before {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background-image: radial-gradient(rgba(255, 90, 44, 0.12) 1.5px, transparent 1.5px);
      background-size: 28px 28px;
      opacity: 0.45;
      pointer-events: none;
  }

  /* Hero floating decorative elements — pure CSS & SVG, no image files */
  .about-shape-el {
      position: absolute;
      pointer-events: none;
      z-index: 0;
      animation: floatElement 6s ease-in-out infinite;
      overflow: visible;
  }
  /* Circle — top-left */
  .about-shape-circle {
      top: 14%;
      left: 5%;
      width: 72px;
      height: 72px;
      opacity: 0.3;
      animation-delay: 0s;
  }
  /* Smaller circle — bottom-left */
  .about-shape-circle-sm {
      bottom: 18%;
      left: 8%;
      width: 40px;
      height: 40px;
      opacity: 0.35;
      animation-delay: 1.5s;
  }
  /* Triangle — top-right */
  .about-shape-triangle {
      top: 12%;
      right: 6%;
      width: 72px;
      height: 72px;
      opacity: 0.3;
      animation-delay: 0.8s;
  }
  /* Ring — bottom-right */
  .about-shape-ring {
      bottom: 15%;
      right: 7%;
      width: 70px;
      height: 70px;
      opacity: 0.35;
      animation-delay: 2.2s;
  }
  /* Square / diamond — center-right */
  .about-shape-diamond {
      top: 55%;
      right: 4%;
      width: 32px;
      height: 32px;
      opacity: 0.3;
      transform: rotate(45deg);
      animation-delay: 3s;
  }
  /* Dots cluster — top-center-left */
  .about-shape-dot-cluster {
      top: 30%;
      left: 2%;
      width: 60px;
      height: 60px;
      animation-delay: 1s;
  }
  @keyframes floatElement {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50%       { transform: translateY(-14px) rotate(6deg); }
  }

  /* Normalize section padding for direct section/store-area children of about-page-wrapper */
  .about-page-wrapper > section,
  .about-page-wrapper > .store-area {
      padding: 10px 0 !important;
      margin: 0 !important;
  }
  .about-page-wrapper > .about-hero-section {
      padding: 130px 0 70px !important;
  }

  @media (max-width: 991.98px) {
      .about-page-wrapper > section,
      .about-page-wrapper > .store-area {
          padding: 0px 0 !important;
      }
      .about-page-wrapper > .about-hero-section {
          padding: 90px 0 50px !important;
      }
  }

  @media (max-width: 575.98px) {
      .about-shape-el { display: none; }
      /* Keep the small circle and ring for rich aesthetics on mobile */
      .about-shape-circle-sm, .about-shape-ring {
          display: block;
          transform: scale(0.7);
      }
      .about-shape-circle-sm {
          bottom: 10%;
          left: 4%;
      }
      .about-shape-ring {
          bottom: 5%;
          right: 4%;
      }
  }

  .about-hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 11px;
      font-weight: 800;
      color: #ff5a2c;
      background: rgba(255, 90, 44, 0.08);
      padding: 8px 20px;
      border-radius: 50px;
      letter-spacing: 1.5px;
      margin-bottom: 24px;
      margin-top: 30px;
      text-transform: uppercase;
      border: 1px solid rgba(255, 90, 44, 0.15);
      box-shadow: 0 4px 10px rgba(255, 90, 44, 0.05);
  }
  .about-hero-badge i {
      font-size: 12px;
  }
  .about-hero-title {
      font-size: 56px;
      font-weight: 800;
      color: #0e1b3d;
      line-height: 1.15;
      margin-bottom: 24px;
      letter-spacing: -1.5px;
  }
  .about-hero-title span {
      color: #ff5a2c;
      background: linear-gradient(135deg, #ff5a2c 0%, #ff8c6b 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: inline-block;
  }
  .about-hero-desc {
      font-size: 18px;
      color: #526077;
      line-height: 1.75;
      max-width: 760px;
      margin: 0 auto 0;
  }

  /* Section Styles */
  .about-section-badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      color: #ff5a2c;
      background: rgba(255, 90, 44, 0.08);
      padding: 6px 14px;
      border-radius: 50px;
      letter-spacing: 1px;
      margin-bottom: 16px;
      text-transform: uppercase;
      border: 1px solid rgba(255, 90, 44, 0.1);
  }
  .about-section-title {
      font-size: 40px;
      font-weight: 800;
      color: #0e1b3d;
      line-height: 1.2;
      margin-bottom: 20px;
      letter-spacing: -0.5px;
  }
  .about-section-desc {
      font-size: 16px;
      color: #64748b;
      line-height: 1.7;
      margin-bottom: 35px;
  }

  /* Features Area */
  .about-features-section {
      background: #ffffff;
      padding: 60px 0;
      position: relative;
      z-index: 1;
  }
  .about-video-row {
      display: inline-flex;
      align-items: center;
      gap: 24px;
      margin-top: 15px;
      flex-wrap: wrap;
  }
  .about-btn-play {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #ff5a2c;
      color: #ffffff !important;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      box-shadow: 0 0 0 0 rgba(255, 90, 44, 0.4);
      animation: pulsePlay 1.6s infinite;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
  }
  .about-btn-play:hover {
      background: #e0451a;
      transform: scale(1.08);
  }
  @keyframes pulsePlay {
      0% {
          box-shadow: 0 0 0 0 rgba(255, 90, 44, 0.7);
      }
      70% {
          box-shadow: 0 0 0 15px rgba(255, 90, 44, 0);
      }
      100% {
          box-shadow: 0 0 0 0 rgba(255, 90, 44, 0);
      }
  }

  .about-feature-card {
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      padding: 35px 30px;
      box-shadow: 0 10px 30px rgba(14, 27, 61, 0.02);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      margin-bottom: 24px;
      height: calc(100% - 24px);
      position: relative;
  }
  .about-feature-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(255, 90, 44, 0.08);
      border-color: #ff5a2c;
  }
  .about-feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 16px;
      background: rgba(255, 90, 44, 0.07);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 24px;
      transition: all 0.3s;
      border: 1.5px solid rgba(255, 90, 44, 0.12);
  }
  .about-feature-icon i {
      font-size: 26px;
      color: #ff5a2c;
      transition: all 0.3s;
  }
  .about-feature-card:hover .about-feature-icon {
      background: #ff5a2c;
      border-color: #ff5a2c;
      transform: scale(1.05) rotate(3deg);
  }
  .about-feature-card:hover .about-feature-icon i {
      color: #ffffff;
  }
  .about-feature-title {
      font-size: 20px;
      font-weight: 700;
      color: #0e1b3d;
      margin-bottom: 14px;
  }
  .about-feature-text {
      font-size: 14px;
      color: #64748b;
      line-height: 1.65;
      margin: 0;
  }

  /* Process Section */
  .about-process-section {
      background: #fafbfe;
      padding: 60px 0;
      position: relative;
      z-index: 1;
      border-top: 1px solid #eef2f6;
      border-bottom: 1px solid #eef2f6;
  }
  .about-process-card {
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      padding: 40px 30px;
      box-shadow: 0 10px 30px rgba(14, 27, 61, 0.02);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      text-align: center;
      height: calc(100% - 30px);
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
  }
  .about-process-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(14, 27, 61, 0.06);
      border-color: #ff5a2c;
  }
  .about-process-step {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 13px;
      font-weight: 900;
      color: rgba(14, 27, 61, 0.1);
      letter-spacing: 1.5px;
  }
  .about-process-icon {
      width: 76px;
      height: 76px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 28px;
      font-size: 26px;
      color: #ffffff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
  }
  .about-process-card:hover .about-process-icon {
      transform: scale(1.08);
  }
  .about-process-title {
      font-size: 20px;
      font-weight: 700;
      color: #0e1b3d;
      margin-bottom: 14px;
  }
  .about-process-text {
      font-size: 14px;
      color: #64748b;
      line-height: 1.65;
      margin: 0;
  }

  /* Counter/Achievements Section */
  .about-counter-section {
      background: #ffffff;
      padding: 60px 0;
      position: relative;
      z-index: 1;
  }
  .about-counter-img-wrap {
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(14, 27, 61, 0.08);
      position: relative;
  }
  .about-counter-img-wrap img {
      width: 100%;
      height: auto;
      display: block;
      transition: transform 0.5s ease;
  }
  .about-counter-img-wrap:hover img {
      transform: scale(1.03);
  }
  .about-counter-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
      margin-top: 35px;
  }
  .about-counter-card {
      border-radius: 20px;
      padding: 32px 24px;
      text-align: center;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 160px;
  }
  .about-counter-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 32px rgba(14, 27, 61, 0.06);
  }
  .about-counter-number-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-bottom: 10px;
  }
  .about-counter-num {
      font-size: 38px;
      font-weight: 800;
      line-height: 1;
  }
  .about-counter-icon {
      font-size: 22px;
      line-height: 1;
  }
  .about-counter-title {
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin: 0;
  }

  /* Testimonials Section */
  .about-testimonial-section {
      background: #fafbfe;
      padding: 60px 0;
      position: relative;
      z-index: 1;
      border-top: 1px solid #eef2f6;
      border-bottom: 1px solid #eef2f6;
  }
  .about-testi-banner {
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(14, 27, 61, 0.06);
      width: 100%;
      height: 100%;
      min-height: 400px;
      position: relative;
  }
  .about-testi-banner img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      position: absolute;
      top: 0;
      left: 0;
  }
  .about-testi-slider-wrap {
      padding: 20px 0;
  }
  .about-testi-card {
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      padding: 35px;
      box-shadow: 0 10px 30px rgba(14, 27, 61, 0.02);
      margin-bottom: 24px;
      position: relative;
      transition: all 0.3s;
  }
  .about-testi-card:hover {
      border-color: #ff5a2c;
  }
  .about-testi-quote-icon {
      position: absolute;
      top: 25px;
      right: 35px;
      font-size: 28px;
      color: rgba(255, 90, 44, 0.1);
  }
  .about-testi-comment {
      font-size: 15px;
      color: #475569;
      line-height: 1.7;
      font-style: italic;
      margin-bottom: 24px;
      position: relative;
      z-index: 1;
  }
  .about-testi-client {
      display: flex;
      align-items: center;
      gap: 16px;
  }
  .about-testi-client-img {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      border: 2px solid #ff5a2c;
      overflow: hidden;
  }
  .about-testi-client-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
  }
  .about-testi-client-name {
      font-size: 16px;
      font-weight: 700;
      color: #0e1b3d;
      margin: 0 0 3px 0;
  }
  .about-testi-client-desc {
      font-size: 12px;
      color: #94a3b8;
      margin: 0;
      display: block;
      font-weight: 500;
  }

  /* Swiper Pagination Styling Override */
  .about-testimonial-section .swiper-pagination {
      position: relative;
      margin-top: 15px;
  }
  .about-testimonial-section .swiper-pagination-bullet {
      background: #cbd5e1;
      opacity: 1;
  }
  .about-testimonial-section .swiper-pagination-bullet-active {
      background: #ff5a2c !important;
      width: 24px;
      border-radius: 4px;
  }

  /* Blog Section */
  .about-blog-section {
      background: #ffffff;
      padding: 60px 0;
      position: relative;
      z-index: 1;
  }
  /* Blog slider wrapper reuses ls-slider but needs no extra side padding on desktop */
  .about-blog-section .ls-slider-wrapper {
      padding: 0 48px 52px;
  }
  @media (max-width: 767px) {
      .about-blog-section .ls-slider-wrapper {
          padding: 0 38px 52px;
      }
  }
  .about-blog-card {
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(14, 27, 61, 0.02);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      height: 100%;
      display: flex;
      flex-direction: column;
  }
  .about-blog-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(14, 27, 61, 0.06);
      border-color: #ff5a2c;
  }
  .about-blog-img-wrap {
      position: relative;
      overflow: hidden;
      aspect-ratio: 16 / 9;
      background: #f1f5f9;
  }
  .about-blog-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
  }
  .about-blog-card:hover .about-blog-img-wrap img {
      transform: scale(1.06);
  }
  .about-blog-badge {
      position: absolute;
      top: 16px;
      left: 16px;
      z-index: 2;
      font-size: 11px;
      font-weight: 700;
      color: #ffffff;
      background: #ff5a2c;
      padding: 5px 14px;
      border-radius: 20px;
      text-transform: uppercase;
      box-shadow: 0 4px 10px rgba(255, 90, 44, 0.2);
  }
  .about-blog-content {
      padding: 28px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
  }
  .about-blog-meta {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 12px;
      color: #94a3b8;
      margin-bottom: 14px;
      list-style: none;
      padding: 0;
      font-weight: 500;
  }
  .about-blog-meta li {
      display: flex;
      align-items: center;
      gap: 6px;
  }
  .about-blog-title {
      font-size: 18px;
      font-weight: 700;
      color: #0e1b3d;
      margin-bottom: 14px;
      line-height: 1.45;
  }
  .about-blog-title a {
      color: inherit;
      text-decoration: none;
      transition: color 0.2s;
  }
  .about-blog-title a:hover { color: #ff5a2c; }
  .about-blog-text {
      font-size: 14px;
      color: #64748b;
      line-height: 1.65;
      margin-bottom: 24px;
  }
  .about-blog-btn {
      font-size: 14px;
      font-weight: 700;
      color: #ff5a2c;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: auto;
      transition: gap 0.2s;
  }
  .about-blog-btn:hover { color: #e0451a; gap: 12px; }

  /* Responsive Adjustments */
  @media (max-width: 991.98px) {
      .about-hero-title    { font-size: 40px; }
      .about-section-title { font-size: 32px; }
      .about-hero-section  { padding: 80px 0 60px; }
      .about-features-section,
      .about-process-section,
      .about-counter-section,
      .about-testimonial-section,
      .about-blog-section  { padding: 60px 0; }
      .about-counter-img-wrap { margin-bottom: 30px; }
      .about-testi-banner {
          margin-bottom: 30px;
          height: 320px;
          min-height: 320px;
      }
  }
  @media (max-width: 575.98px) {
      .about-hero-title  { font-size: 32px; }
      .about-hero-desc   { font-size: 16px; }
      .about-counter-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
      .about-video-row {
          flex-direction: column;
          align-items: flex-start;
          gap: 16px;
      }
  }
</style>
@endsection

@section('content')
<div class="about-page-wrapper">

  <!-- Background Decorative Floating Blobs -->
  <div class="about-decor-blob about-blob-primary"></div>
  <div class="about-decor-blob about-blob-secondary"></div>
  <div class="about-decor-blob about-blob-accent"></div>

  <!-- Custom Premium Hero Banner -->
  <section class="about-hero-section">
    <!-- High-quality SVG floating decorative shapes -->
    <svg class="about-shape-el about-shape-circle" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="36" cy="36" r="36" fill="url(#hero-circle-grad)"/>
      <defs>
        <radialGradient id="hero-circle-grad" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(36 36) rotate(90) scale(36)">
          <stop stop-color="#ff8c6b"/>
          <stop offset="1" stop-color="#ff5a2c"/>
        </radialGradient>
      </defs>
    </svg>
    <svg class="about-shape-el about-shape-circle-sm" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="20" cy="20" r="20" fill="url(#hero-circle-sm-grad)"/>
      <defs>
        <radialGradient id="hero-circle-sm-grad" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(20 20) rotate(90) scale(20)">
          <stop stop-color="#60a5fa"/>
          <stop offset="1" stop-color="#3b82f6"/>
        </radialGradient>
      </defs>
    </svg>
    <svg class="about-shape-el about-shape-triangle" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M36.5 5.5a4 4 0 0 1 7 0l29.4 51c1.5 2.7-.4 6-3.5 6H10.6c-3 0-5-3.3-3-6l29.4-51z" fill="url(#hero-tri-grad)"/>
      <defs>
        <linearGradient id="hero-tri-grad" x1="40" y1="0" x2="40" y2="70" gradientUnits="userSpaceOnUse">
          <stop stop-color="#a78bfa"/>
          <stop offset="1" stop-color="#8b5cf6"/>
        </linearGradient>
      </defs>
    </svg>
    <svg class="about-shape-el about-shape-ring" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="35" cy="35" r="31" stroke="url(#hero-ring-grad)" stroke-width="8"/>
      <defs>
        <linearGradient id="hero-ring-grad" x1="0" y1="0" x2="70" y2="70" gradientUnits="userSpaceOnUse">
          <stop stop-color="#ff8c6b"/>
          <stop offset="1" stop-color="#ff5a2c"/>
        </linearGradient>
      </defs>
    </svg>
    <svg class="about-shape-el about-shape-diamond" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#hero-diamond-grad)"/>
      <defs>
        <linearGradient id="hero-diamond-grad" x1="2" y1="2" x2="30" y2="30" gradientUnits="userSpaceOnUse">
          <stop stop-color="#34d399"/>
          <stop offset="1" stop-color="#10b981"/>
        </linearGradient>
      </defs>
    </svg>
    <svg class="about-shape-el about-shape-dot-cluster" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="6" cy="6" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="22" cy="6" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="38" cy="6" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="54" cy="6" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="6" cy="22" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="22" cy="22" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="38" cy="22" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="54" cy="22" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="6" cy="38" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="22" cy="38" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="38" cy="38" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="54" cy="38" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="6" cy="54" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="22" cy="54" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="38" cy="54" r="2.5" fill="#ff5a2c" opacity="0.35"/>
      <circle cx="54" cy="54" r="2.5" fill="#ff5a2c" opacity="0.35"/>
    </svg>
    <div class="container" style="position:relative;z-index:1">
      <div class="about-hero-badge">
        <i class="fas fa-rocket"></i> {{ __('Our Story') }}
      </div>
      <h1 class="about-hero-title">
        {{ __('Building the Future of') }} <br><span>{{ __('eCommerce') }}</span>
      </h1>
      <p class="about-hero-desc">
        {{ __('We empower entrepreneurs worldwide with state-of-the-art tools, high-conversion storefront templates, and lightning-fast web infrastructure to launch and scale their dream stores instantly.') }}
      </p>
    </div>
  </section>

  @if ($bs->about_features_section_status == 1)
    <!-- Features Start -->
    <section class="about-features-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="mb-30" data-aos="fade-right">
              @if(!empty(@$homeSec->features_section_title))
                <span class="about-section-badge">{{ @$homeSec->features_section_title }}</span>
              @endif
              <h2 class="about-section-title">{{ $homeSec->features_section_subtitle }}</h2>
              <p class="about-section-desc">{{ $homeSec->features_section_text }}</p>
              <div class="about-video-row">
                @if(!empty($homeSec->features_section_btn_text))
                  <a href="{{ $homeSec->features_section_btn_url }}" class="btn-ls-primary">{{ $homeSec->features_section_btn_text }}</a>
                @endif
                @if(!empty($homeSec->features_section_video_url))
                  <!-- <a href="{{ $homeSec->features_section_video_url }}" class="about-btn-play youtube-popup"><i class="fas fa-play"></i></a> -->
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="row justify-content-center">
              @foreach ($features as $feature)
                @php
                  $title_lower = strtolower($feature->title);
                  if (str_contains($title_lower, 'dashboard') || str_contains($title_lower, 'easy') || str_contains($title_lower, 'panel') || str_contains($title_lower, 'board') || str_contains($title_lower, 'interface')) {
                      $fa_icon = 'fas fa-tachometer-alt';
                  } elseif (str_contains($title_lower, 'payment') || str_contains($title_lower, 'secure') || str_contains($title_lower, 'pay') || str_contains($title_lower, 'security') || str_contains($title_lower, 'safe')) {
                      $fa_icon = 'fas fa-shield-alt';
                  } elseif (str_contains($title_lower, 'growth') || str_contains($title_lower, 'scalab') || str_contains($title_lower, 'scale') || str_contains($title_lower, 'grow') || str_contains($title_lower, 'chart')) {
                      $fa_icon = 'fas fa-chart-line';
                  } elseif (str_contains($title_lower, 'support') || str_contains($title_lower, '24') || str_contains($title_lower, 'help') || str_contains($title_lower, 'customer') || str_contains($title_lower, 'chat')) {
                      $fa_icon = 'fas fa-headset';
                  } else {
                      $fa_icon = 'fas fa-star';
                  }
                @endphp
                <div class="col-lg-6 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                  <div class="about-feature-card">
                    <div class="about-feature-icon">
                      <i class="{{ $fa_icon }}"></i>
                    </div>
                    <h3 class="about-feature-title">{{ $feature->title }}</h3>
                    <p class="about-feature-text">{{ $feature->text }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Features End -->
  @endif

  @if (count($after_features) > 0)
    @foreach ($after_features as $cusFeatures)
      @if (isset($additional_section_status[$cusFeatures->id]))
        @if ($additional_section_status[$cusFeatures->id] == 1)
          @php
            $cusFeaturesContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusFeatures->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusFeaturesContent,
              'possition' => $cusFeatures->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif


  @if ($bs->about_work_process_section_status == 1)
    <!-- Store Start / Process Section -->
    <section class="about-process-section">
      <div class="container">
        <div class="text-center mb-50" data-aos="fade-up">
          <span class="about-section-badge">{{ __('Simple Workflow') }}</span>
          <h2 class="about-section-title mb-0">{{ @$homeSec->work_process_section_title }}</h2>
        </div>
        <div class="row justify-content-center">
          @foreach ($processes as $index => $process)
            <div class="col-sm-6 col-lg-6 col-xl-3" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
              <div class="about-process-card">
                <span class="about-process-step">{{ sprintf("%02d", $index + 1) }}</span>
                <div class="about-process-icon" style="background-color: #{{ $process->color }}; box-shadow: 0 8px 24px rgba({{ hexdec(substr($process->color,0,2)) ?: 0 }}, {{ hexdec(substr($process->color,2,2)) ?: 0 }}, {{ hexdec(substr($process->color,4,2)) ?: 0 }}, 0.25);">
                  <i class="{{ $process->icon }}"></i>
                </div>
                <h3 class="about-process-title">{{ $process->title }}</h3>
                <p class="about-process-text">{{ $process->text }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
    <!-- Store End -->
  @endif

  @if (count($after_work_process) > 0)
    @foreach ($after_work_process as $cusWorkProcess)
      @if (isset($additional_section_status[$cusWorkProcess->id]))
        @if ($additional_section_status[$cusWorkProcess->id] == 1)
          @php
            $cusWorkProcessContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusWorkProcess->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusWorkProcessContent,
              'possition' => $cusWorkProcess->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->about_counter_section_status == 1)
    <!-- ========= START Achievements ========= -->
    <section class="about-counter-section">
      <div class="container">
        <div class="row gx-xl-5 align-items-center">
          <div class="col-lg-6" data-aos="fade-right">
            <div class="about-counter-img-wrap">
              <img class="lazyload" src="{{ asset('assets/front/img/counter-section/' . @$counterSection->image) }}" alt="achievement">
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <div class="px-lg-0">
              <span class="about-section-badge">{{ __('Our Impact') }}</span>
              <h2 class="about-section-title">{{ @$counterSection->title }}</h2>
              <p class="about-section-desc">{{ @$counterSection->text }}</p>
            </div>
            <div class="about-counter-grid">
              @foreach ($counters as $counter)
                @php
                  $hex = str_replace('#', '', $counter->color);
                  if(strlen($hex) == 3) {
                      $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                  }
                  $r = hexdec(substr($hex, 0, 2)) ?: 0;
                  $g = hexdec(substr($hex, 2, 2)) ?: 0;
                  $b = hexdec(substr($hex, 4, 2)) ?: 0;
                  
                  // Calculate contrast ratio (YIQ)
                  $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                  
                  if ($yiq > 200) {
                      // Pastel light colors (defaults) - pair with rich deep text and matching borders
                      if (strtolower($hex) == 'f1f7ff') {
                          $numColor = '#2563eb'; // Royal Blue
                          $borderColor = 'rgba(37, 99, 235, 0.18)';
                          $cardBg = '#f5f9ff';
                      } elseif (strtolower($hex) == 'fff9f1') {
                          $numColor = '#d97706'; // Warm Amber/Orange
                          $borderColor = 'rgba(217, 119, 6, 0.18)';
                          $cardBg = '#fffaf4';
                      } elseif (strtolower($hex) == 'f4f1ff') {
                          $numColor = '#7c3aed'; // Purple
                          $borderColor = 'rgba(124, 58, 237, 0.18)';
                          $cardBg = '#faf7ff';
                      } elseif (strtolower($hex) == 'ecffff') {
                          $numColor = '#0d9488'; // Teal
                          $borderColor = 'rgba(13, 148, 136, 0.18)';
                          $cardBg = '#f2ffff';
                      } else {
                          $numColor = '#ff5a2c'; // Accent Orange
                          $borderColor = 'rgba(' . $r . ',' . $g . ',' . $b . ', 0.35)';
                          $cardBg = '#' . $hex;
                      }
                      $titleColor = '#475569';
                  } else {
                      // Dark / vibrant background - pair with high contrast white text
                      $numColor = '#ffffff';
                      $borderColor = 'rgba(255, 255, 255, 0.25)';
                      $cardBg = '#' . $hex;
                      $titleColor = 'rgba(255, 255, 255, 0.85)';
                  }
                @endphp
                <!-- achievement-item -->
                <div class="about-counter-card" style="border: 1px solid {{ $borderColor }}; background: {{ $cardBg }};">
                  <div class="about-counter-body">
                    <div class="about-counter-number-row direction-ltr">
                      <h2 class="about-counter-num mb-0 fw-bold" style="color: {{ $numColor }}" data-count="{{ $counter->amount }}">0</h2>
                      <h6 class="about-counter-icon mb-0 fw-bold" style="color: {{ $numColor }}"><i class="{{ $counter->icon }}"></i></h6>
                    </div>
                    <p class="about-counter-title" style="color: {{ $titleColor }}">{{ $counter->title }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ========= END Achievements ========= -->
  @endif

  @if (count($after_counter) > 0)
    @foreach ($after_counter as $cusCounter)
      @if (isset($additional_section_status[$cusCounter->id]))
        @if ($additional_section_status[$cusCounter->id] == 1)
          @php
            $cusCounterContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusCounter->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusCounterContent,
              'possition' => $cusCounter->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->about_testimonial_section_status == 1)
    <!-- Testimonial Start -->
    <section class="about-testimonial-section">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          <div class="col-lg-6" data-aos="fade-right">
            <div class="about-testi-banner">
              <img class="lazyload" src="{{ asset('assets/front/img/testimonials/' . $be->testimonial_img) }}" alt="Testimonials Banner">
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <div class="mb-30">
              <span class="about-section-badge">{{ __('Success Stories') }}</span>
              <h2 class="about-section-title">{{ @$homeSec->testimonial_section_title }}</h2>
            </div>
            <div class="swiper testimonial-slider about-testi-slider-wrap">
              <div class="swiper-wrapper">

                @for ($i = 0; $i <= count($testimonials); $i = $i + 2)
                  @if ($i < count($testimonials) - 1)
                    <div class="swiper-slide">

                      <div class="about-testi-card">
                        <span class="about-testi-quote-icon"><i class="fas fa-quote-right"></i></span>
                        <p class="about-testi-comment">
                          "{{ $testimonials[$i]->comment }}"
                        </p>
                        <div class="about-testi-client">
                          <div class="about-testi-client-img">
                            <img class="lazyload"
                              src="{{ $testimonials[$i]->image ? asset('assets/front/img/testimonials/' . $testimonials[$i]->image) : asset('assets/front/img/thumb-1.jpg') }}"
                              alt="{{ $testimonials[$i]->name }}">
                          </div>
                          <div>
                            <h6 class="about-testi-client-name">{{ $testimonials[$i]->name }}</h6>
                            <span class="about-testi-client-desc">{{ $testimonials[$i]->designation }}</span>
                          </div>
                        </div>
                      </div>

                      <div class="about-testi-card">
                        <span class="about-testi-quote-icon"><i class="fas fa-quote-right"></i></span>
                        <p class="about-testi-comment">
                          "{{ $testimonials[$i + 1]->comment }}"
                        </p>
                        <div class="about-testi-client">
                          <div class="about-testi-client-img">
                            <img class="lazyload"
                              src="{{ $testimonials[$i + 1]->image ? asset('assets/front/img/testimonials/' . $testimonials[$i + 1]->image) : asset('assets/front/img/thumb-1.jpg') }}"
                              alt="{{ $testimonials[$i + 1]->name }}">
                          </div>
                          <div>
                            <h6 class="about-testi-client-name">{{ $testimonials[$i + 1]->name }}</h6>
                            <span class="about-testi-client-desc">{{ $testimonials[$i + 1]->designation }}</span>
                          </div>
                        </div>
                      </div>

                    </div>
                  @endif
                @endfor

              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Testimonial End -->
  @endif

  @if (count($after_testimonial) > 0)
    @foreach ($after_testimonial as $cusTestimonial)
      @if (isset($additional_section_status[$cusTestimonial->id]))
        @if ($additional_section_status[$cusTestimonial->id] == 1)
          @php
            $cusTestimonialContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusTestimonial->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusTestimonialContent,
              'possition' => $cusTestimonial->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->about_blog_section_status == 1)
    <!-- Blog Start -->
    <section class="about-blog-section">
      <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
          <span class="about-section-badge">{{ __('Resources') }}</span>
          @if (!empty(@$homeSec->blog_section_title))
            <h2 class="about-section-title mb-0">{{ @$homeSec->blog_section_title }}</h2>
          @endif
        </div>

        <!-- Blog Slider -->
        <div class="ls-slider-wrapper" id="aboutBlogSlider">
          <div class="ls-slider-viewport">
            <div class="ls-slider-track">

              @foreach ($blogs as $blog)
                <div class="ls-slide">
                  <article class="about-blog-card">
                    <div class="about-blog-img-wrap">
                      <span class="about-blog-badge">{{ $blog->bcategory->name }}</span>
                      <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}">
                        <img class="lazyload" src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}" alt="blog thumbnail">
                      </a>
                    </div>
                    <div class="about-blog-content">
                      <ul class="about-blog-meta">
                        <li><i class="fal fa-user"></i> {{ __('Admin') }}</li>
                        <li><i class="fal fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</li>
                      </ul>
                      <h3 class="about-blog-title">
                        <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}">
                          {{ $blog->title }}
                        </a>
                      </h3>
                      <p class="about-blog-text">
                        {!! substr(strip_tags($blog->content), 0, 110) !!}...
                      </p>
                      <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                        class="about-blog-btn">{{ __('Read More') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                  </article>
                </div>
              @endforeach

            </div><!-- /.ls-slider-track -->
          </div><!-- /.ls-slider-viewport -->

          <!-- Arrows -->
          <button class="ls-arrow ls-prev" aria-label="Previous" onclick="lsSlide('aboutBlogSlider', -1)"><i class="fas fa-chevron-left"></i></button>
          <button class="ls-arrow ls-next" aria-label="Next"     onclick="lsSlide('aboutBlogSlider',  1)"><i class="fas fa-chevron-right"></i></button>
          <!-- Dots -->
          <div class="ls-dots" id="aboutBlogSlider-dots"></div>
        </div><!-- /#aboutBlogSlider -->

      </div>
    </section>
    <!-- Blog End -->
  @endif

  @if (count($after_blog) > 0)
    @foreach ($after_blog as $cusBlog)
      @if (isset($additional_section_status[$cusBlog->id]))
        @if ($additional_section_status[$cusBlog->id] == 1)
          @php
            $cusBlogContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusBlog->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusBlogContent,
              'possition' => $cusBlog->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

</div>
@endsection

@section('scripts')
<script>
  "use strict";
  document.addEventListener('DOMContentLoaded', function () {
    /* =========================================================
       Blog Slider Initialization
       ========================================================= */
    if (typeof lsInit === 'function') {
      lsInit('aboutBlogSlider', { autoMs: 3000 });
    }

    /* =========================================================
       Custom Achievements Count-Up Animation
       ========================================================= */
    initAboutCounters();

    function initAboutCounters() {
      var counters = document.querySelectorAll('.about-counter-num');
      if (!counters.length) return;

      function animateCount(el) {
        if (el.dataset.counted) return;
        el.dataset.counted = '1';
        
        var raw = el.getAttribute('data-count') || '0';
        // Extract numeric part and suffix
        var numMatch = raw.match(/^[0-9.]+/);
        var suffixMatch = raw.match(/[^0-9.]+$/);
        
        var target = numMatch ? parseFloat(numMatch[0]) : 0;
        var suffix = suffixMatch ? suffixMatch[0] : '';
        
        // If it has a slash/special format like 24/7
        if (raw.indexOf('/') !== -1) {
          var parts = raw.split('/');
          var target1 = parseFloat(parts[0]) || 0;
          var target2 = parts[1] || '';
          target = target1;
          suffix = '/' + target2;
        }
        
        var isDecimal = raw.indexOf('.') !== -1;
        var duration = 1800;
        var startTime = null;

        function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

        function step(ts) {
          if (!startTime) startTime = ts;
          var progress = Math.min((ts - startTime) / duration, 1);
          var curVal = easeOutQuart(progress) * target;
          var displayVal = isDecimal ? curVal.toFixed(1) : Math.round(curVal);
          
          el.textContent = (displayVal >= 1000 && !isDecimal ? displayVal.toLocaleString('en-US') : displayVal) + suffix;
          
          if (progress < 1) {
            requestAnimationFrame(step);
          } else {
            el.textContent = raw; // guarantee exact raw layout match at the end
          }
        }
        requestAnimationFrame(step);
      }

      if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              animateCount(entry.target);
              obs.unobserve(entry.target);
            }
          });
        }, { threshold: 0.2 });
        counters.forEach(function(el) { obs.observe(el); });
      } else {
        counters.forEach(function(el) { animateCount(el); });
      }
    }
  });
</script>
@endsection
