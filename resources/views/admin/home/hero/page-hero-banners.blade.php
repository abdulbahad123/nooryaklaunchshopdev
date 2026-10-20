@extends('admin.layout')

@if (!empty(@$language) && @$language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .hero-editor-tabs .nav-link {
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 10px !important;
    color: #495057;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    margin-right: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
  }
  .hero-editor-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .hero-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #ff5a2c 0%, #d4380d 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.3);
    border-color: #d4380d;
  }
  .hero-editor-tabs .nav-link i {
    font-size: 1.1rem;
  }
  .hero-section-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .hero-section-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 24px;
    border-radius: 14px 14px 0 0;
  }
  .hero-section-card .card-body {
    padding: 24px;
  }
  .hero-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .sticky-save-bar {
    position: sticky;
    bottom: 20px;
    z-index: 99;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  }
</style>
@endsection

@section('content')
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title font-weight-bold mb-1">{{ __('All Page Hero Banners') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Hero Banners') }}</a></li>
      </ul>
    </div>
    <div style="min-width: 170px;">
      @include('admin.partials.languages')
    </div>
  </div>

  {{-- Page Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills hero-editor-tabs m-0" id="heroEditorTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab-about-btn" data-toggle="pill" href="#tab-about" role="tab" aria-controls="tab-about" aria-selected="true">
            <i class="fas fa-info-circle text-warning"></i>
            <span>{{ __('About Us Hero') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-shops-btn" data-toggle="pill" href="#tab-shops" role="tab" aria-controls="tab-shops" aria-selected="false">
            <i class="fas fa-store text-primary"></i>
            <span>{{ __('Stores Launched Hero') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-templates-btn" data-toggle="pill" href="#tab-templates" role="tab" aria-controls="tab-templates" aria-selected="false">
            <i class="fas fa-palette text-success"></i>
            <span>{{ __('Store Themes Hero') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-pricing-btn" data-toggle="pill" href="#tab-pricing" role="tab" aria-controls="tab-pricing" aria-selected="false">
            <i class="fas fa-tags text-info"></i>
            <span>{{ __('Pricing Hero') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-contact-btn" data-toggle="pill" href="#tab-contact" role="tab" aria-controls="tab-contact" aria-selected="false">
            <i class="fas fa-envelope text-danger"></i>
            <span>{{ __('Contact Us Hero') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-home-btn" data-toggle="pill" href="#tab-home" role="tab" aria-controls="tab-home" aria-selected="false">
            <i class="fas fa-desktop text-secondary"></i>
            <span>{{ __('Home Page Hero') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.page_hero_banners.update', $lang_id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="tab-content" id="heroEditorTabsContent">

      {{-- ======================================================== --}}
      {{-- TAB 1: ABOUT US HERO BANNER                              --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade show active" id="tab-about" role="tabpanel" aria-labelledby="tab-about-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-info-circle text-warning"></i>
                  {{ __('About Us Page Hero Banner (/about)') }}
                </h5>
                <a href="{{ url('/about') }}" target="_blank" class="btn btn-outline-warning btn-sm">
                  <i class="fas fa-external-link-alt mr-1"></i> {{ __('View About Page') }}
                </a>
              </div>
              <div class="card-body">
                <div class="alert alert-light border d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 10px; background: #fdfefe;">
                  <i class="fas fa-magic text-warning mr-2" style="font-size: 1.2rem;"></i>
                  <span class="text-muted small">
                    {{ __('The About Us page hero banner displays with interactive SVG floating geometric shapes on the frontend. Edit the headline, story text, and action button below.') }}
                  </span>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Floating Top Pill / Badge Text') }}</label>
                      <input name="about_hero_badge" class="form-control" value="{{ @$data->about_hero_badge ?? 'OUR STORY' }}" placeholder="OUR STORY">
                      <p id="errabout_hero_badge" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                      <input name="about_hero_title" class="form-control" value="{{ @$data->about_hero_title ?? 'Building the Future of eCommerce' }}" placeholder="Building the Future of eCommerce">
                      <p id="errabout_hero_title" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Description Text') }}</label>
                      <textarea name="about_hero_subtitle" rows="3" class="form-control" placeholder="We empower entrepreneurs worldwide with state-of-the-art tools, high-conversion storefront templates, and lightning-fast web infrastructure to launch and scale their dream stores instantly.">{{ @$data->about_hero_subtitle ?? 'We empower entrepreneurs worldwide with state-of-the-art tools, high-conversion storefront templates, and lightning-fast web infrastructure to launch and scale their dream stores instantly.' }}</textarea>
                      <p id="errabout_hero_subtitle" class="em text-danger mb-0"></p>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Action Button Text (Optional)') }}</label>
                          <input name="about_hero_btn_text" class="form-control" value="{{ @$data->about_hero_btn_text ?? 'Explore Themes' }}" placeholder="Explore Themes">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Action Button URL (Optional)') }}</label>
                          <input name="about_hero_btn_url" class="form-control" value="{{ @$data->about_hero_btn_url ?? '/templates' }}" placeholder="/templates">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 2: STORES LAUNCHED HERO BANNER                       --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-shops" role="tabpanel" aria-labelledby="tab-shops-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-store text-primary"></i>
                  {{ __('Stores Launched Page Hero Banner (/shops)') }}
                </h5>
                <a href="{{ url('/shops') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                  <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Stores Page') }}
                </a>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Badge Text') }}</label>
                      <input name="shops_hero_badge" class="form-control" value="{{ @$data->shops_hero_badge ?? 'Customer Stores' }}" placeholder="Customer Stores">
                      <p id="errshops_hero_badge" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                      <input name="shops_hero_title" class="form-control" value="{{ @$data->shops_hero_title ?? 'Discover Successful Stores Built with Ecom Builder' }}" placeholder="Discover Successful Stores Built with Ecom Builder">
                      <p id="errshops_hero_title" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Description') }}</label>
                      <textarea name="shops_hero_subtitle" rows="3" class="form-control" placeholder="Explore live, high-converting eCommerce stores launched by our merchants across fashion, electronics, groceries, and more.">{{ @$data->shops_hero_subtitle ?? 'Explore live, high-converting eCommerce stores launched by our merchants across fashion, electronics, groceries, and more.' }}</textarea>
                      <p id="errshops_hero_subtitle" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 3: STORE THEMES HERO BANNER                          --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-templates" role="tabpanel" aria-labelledby="tab-templates-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-palette text-success"></i>
                  {{ __('Store Themes Page Hero Banner (/templates)') }}
                </h5>
                <a href="{{ url('/templates') }}" target="_blank" class="btn btn-outline-success btn-sm">
                  <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Themes Catalog') }}
                </a>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Badge Text') }}</label>
                      <input name="templates_hero_badge" class="form-control" value="{{ @$data->templates_hero_badge ?? 'PREMIUM STORE THEMES' }}" placeholder="PREMIUM STORE THEMES">
                      <p id="errtemplates_hero_badge" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                      <input name="templates_hero_title" class="form-control" value="{{ @$data->templates_hero_title ?? 'Pick Your Perfect Theme & Launch Today' }}" placeholder="Pick Your Perfect Theme & Launch Today">
                      <p id="errtemplates_hero_title" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Description Text') }}</label>
                      <textarea name="templates_hero_subtitle" rows="3" class="form-control" placeholder="Explore high-converting, fully responsive templates engineered specifically for eCommerce.">{{ @$data->templates_hero_subtitle ?? 'Explore high-converting, fully responsive templates engineered specifically for eCommerce.' }}</textarea>
                      <p id="errtemplates_hero_subtitle" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 4: PRICING HERO BANNER                               --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-pricing" role="tabpanel" aria-labelledby="tab-pricing-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-tags text-info"></i>
                  {{ __('Pricing Page Hero Banner (/pricing)') }}
                </h5>
                <a href="{{ url('/pricing') }}" target="_blank" class="btn btn-outline-info btn-sm">
                  <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Pricing Page') }}
                </a>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Badge Text') }}</label>
                      <input name="pricing_hero_badge" class="form-control" value="{{ @$data->pricing_hero_badge ?? 'TRANSPARENT PLANS' }}" placeholder="TRANSPARENT PLANS">
                      <p id="errpricing_hero_badge" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                      <input name="pricing_hero_title" class="form-control" value="{{ @$data->pricing_hero_title ?? 'Choose the Perfect Plan for Your Business' }}" placeholder="Choose the Perfect Plan for Your Business">
                      <p id="errpricing_hero_title" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Description Text') }}</label>
                      <textarea name="pricing_hero_subtitle" rows="3" class="form-control" placeholder="Everything you need to launch, grow, and scale your online business. No hidden fees.">{{ @$data->pricing_hero_subtitle ?? 'Everything you need to launch, grow, and scale your online business. No hidden fees.' }}</textarea>
                      <p id="errpricing_hero_subtitle" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 5: CONTACT US HERO BANNER                            --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-contact" role="tabpanel" aria-labelledby="tab-contact-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-envelope text-danger"></i>
                  {{ __('Contact Us Page Hero Banner (/contact)') }}
                </h5>
                <a href="{{ url('/contact') }}" target="_blank" class="btn btn-outline-danger btn-sm">
                  <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Contact Page') }}
                </a>
              </div>
              <div class="card-body">
                <div class="alert alert-light border d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 10px; background: #fdfefe;">
                  <i class="fas fa-laptop-code text-primary mr-2" style="font-size: 1.2rem;"></i>
                  <span class="text-muted small">
                    {{ __('The Contact Us page features a live interactive CSS device collage (laptop + phone mockup) on the right side. Edit the headline, badge, and description below.') }}
                  </span>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Badge / Subtitle') }}</label>
                      <input name="contact_hero_badge" class="form-control" value="{{ @$data->contact_hero_badge ?? 'GET IN TOUCH' }}" placeholder="GET IN TOUCH">
                      <p id="errcontact_hero_badge" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                      <input name="contact_hero_title" class="form-control" value="{{ @$data->contact_hero_title ?? 'Get in touch with our team' }}" placeholder="Get in touch with our team">
                      <p id="errcontact_hero_title" class="em text-danger mb-0"></p>
                    </div>
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Description Text') }}</label>
                      <textarea name="contact_hero_subtitle" rows="3" class="form-control" placeholder="Have questions about our platform, themes, or pricing? Our team is here to help you get started.">{{ @$data->contact_hero_subtitle ?? 'Have questions about our platform, themes, or pricing? Our team is here to help you get started.' }}</textarea>
                      <p id="errcontact_hero_subtitle" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 6: HOME PAGE HERO BANNER LINK                        --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-home" role="tabpanel" aria-labelledby="tab-home-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card hero-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="hero-card-title">
                  <i class="fas fa-desktop text-secondary"></i>
                  {{ __('Home Page Hero Banner') }}
                </h5>
                <a href="{{ route('admin.herosection.imgtext') . '?language=' . $language->code }}#tab-hero" class="btn btn-primary btn-sm">
                  <i class="fas fa-edit mr-1"></i> {{ __('Open Home Section Visual Editor') }}
                </a>
              </div>
              <div class="card-body">
                <div class="alert alert-info border-0 rounded" style="background: #e0f2fe; color: #0369a1;">
                  <i class="fas fa-info-circle mr-1"></i>
                  {{ __('Home Page Hero has full interactive tabs for trust badges, 5 steps process, explore dashboard mockup, and badges. Click the button above to edit it.') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Sticky Bottom Save Bar --}}
    <div class="sticky-save-bar d-flex align-items-center justify-content-between">
      <span class="text-muted small">
        <i class="fas fa-info-circle text-primary mr-1"></i>
        {{ __('Changes to any tab will be saved and applied across pages.') }}
      </span>
      <button type="submit" class="btn btn-success font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save & Update Changes') }}
      </button>
    </div>

  </form>
@endsection

@section('scripts')
  <script>
    function activateHeroTabFromHash() {
      var hash = window.location.hash;
      if (hash && $('.hero-editor-tabs a[href="' + hash + '"]').length) {
        $('.hero-editor-tabs a[href="' + hash + '"]').tab('show');
        $('html, body').animate({ scrollTop: 0 }, 150);
      }
    }
    $(document).ready(function() {
      activateHeroTabFromHash();
      $(window).on('hashchange', activateHeroTabFromHash);
      $('.hero-editor-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        }
      });
    });
  </script>
@endsection