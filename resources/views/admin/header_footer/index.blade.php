@extends('admin.layout')

@if (!empty(@$language) && @$language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .hf-tabs .nav-link {
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
  .hf-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .hf-tabs .nav-link.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    border-color: #1d4ed8;
  }
  .hf-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .hf-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 24px;
    border-radius: 14px 14px 0 0;
  }
  .hf-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .preview-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
  }
  .preview-box img {
    max-height: 80px;
    width: auto;
    object-fit: contain;
    border-radius: 8px;
    background: #ffffff;
    padding: 8px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .upload-btn {
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }
  .upload-btn input[type="file"] {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    opacity: 0;
    cursor: pointer;
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
      <h4 class="page-title font-weight-bold mb-1">{{ __('Header & Footer Settings') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Header & Footer') }}</a></li>
      </ul>
    </div>
    <div style="min-width: 170px;">
      @include('admin.partials.languages')
    </div>
  </div>

  {{-- Nav Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills hf-tabs m-0" id="hfTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab-header-btn" data-toggle="pill" href="#tab-header" role="tab">
            <i class="fas fa-heading"></i>
            <span>{{ __('Header Logo & Buttons') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-footer-btn" data-toggle="pill" href="#tab-footer" role="tab">
            <i class="fas fa-shoe-prints"></i>
            <span>{{ __('Footer Logo & Branding') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-navlinks-btn" data-toggle="pill" href="#tab-navlinks" role="tab">
            <i class="fas fa-bars"></i>
            <span>{{ __('Header Navigation Links') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-contact-btn" data-toggle="pill" href="#tab-contact" role="tab">
            <i class="fas fa-address-book"></i>
            <span>{{ __('Footer Contact & Socials') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.header_footer.update', $lang_id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="tab-content" id="hfTabsContent">
      
      <!-- ==========================================
           TAB 1: HEADER LOGO & BUTTONS
           ========================================== -->
      <div class="tab-pane fade show active" id="tab-header" role="tabpanel">
        <div class="card hf-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="hf-card-title">
              <i class="fas fa-heading text-primary"></i>
              {{ __('Header Logo & Action Buttons') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Displayed at the top of every public frontend page') }}</span>
          </div>
          <div class="card-body">
            <div class="row">

              {{-- Header Logo Upload --}}
              <div class="col-lg-5 mb-4">
                <div class="preview-box h-100 d-flex flex-column align-items-center justify-content-center">
                  <label class="font-weight-bold text-dark mb-2">{{ __('Main Header Logo') }}</label>
                  @php
                    $hasHdrLogo = !empty($abs->logo) && file_exists(public_path('assets/front/img/' . $abs->logo));
                    $hdrLogoSrc = $hasHdrLogo ? asset('assets/front/img/' . $abs->logo) : asset('images/launchshop_icon.png');
                  @endphp
                  <div class="showImage mb-3">
                    <img src="{{ $hdrLogoSrc }}" alt="Header Logo Preview" class="img-fluid">
                  </div>
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <label class="btn btn-primary btn-sm upload-btn mb-0 px-3">
                      <i class="fas fa-upload mr-1"></i> {{ __('Upload New Logo') }}
                      <input type="file" name="logo" class="img-input">
                    </label>
                    @if ($hasHdrLogo)
                      <a href="{{ route('admin.header_footer.remove_logo', 'header') }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Revert to default logo?') }}');">
                        <i class="fas fa-trash"></i>
                      </a>
                    @endif
                  </div>
                  <small class="text-muted mt-2">{{ __('Recommended: Transparent PNG or SVG, ~220x50px') }}</small>
                </div>
              </div>

              {{-- Header Action Buttons --}}
              <div class="col-lg-7 mb-4">
                <div class="card border p-3 h-100" style="border-radius: 12px; background: #fafbfc;">
                  <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-external-link-alt text-primary mr-1"></i> {{ __('Header Right Buttons') }}</h6>
                  
                  {{-- Button 1: Book Demo --}}
                  <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                      <label class="font-weight-bold text-dark small">{{ __('Button 1 Text (Outline)') }}</label>
                      <input type="text" name="header_demo_text" class="form-control form-control-sm" 
                        value="{{ $abs->header_demo_text ?? 'Book Demo' }}" placeholder="Book Demo">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="font-weight-bold text-dark small">{{ __('Button 1 Target Link') }}</label>
                      <input type="text" name="header_demo_url" class="form-control form-control-sm" 
                        value="{{ $abs->header_demo_url ?? '/contact' }}" placeholder="/contact">
                    </div>
                  </div>

                  {{-- Button 2: Login --}}
                  <div class="row">
                    <div class="col-md-6 mb-2">
                      <label class="font-weight-bold text-dark small">{{ __('Button 2 Text (Solid Primary)') }}</label>
                      <input type="text" name="header_login_text" class="form-control form-control-sm" 
                        value="{{ $abs->header_login_text ?? 'Login' }}" placeholder="Login">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="font-weight-bold text-dark small">{{ __('Button 2 Target Link') }}</label>
                      <input type="text" name="header_login_url" class="form-control form-control-sm" 
                        value="{{ $abs->header_login_url ?? '/login' }}" placeholder="/login">
                    </div>
                  </div>

                  <div class="alert alert-info py-2 px-3 small mt-3 mb-0" style="border-radius: 8px;">
                    <i class="fas fa-info-circle mr-1"></i> {{ __('Logged in users will automatically see the Dashboard button instead.') }}
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 2: FOOTER LOGO & BRANDING
           ========================================== -->
      <div class="tab-pane fade" id="tab-footer" role="tabpanel">
        <div class="card hf-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="hf-card-title">
              <i class="fas fa-shoe-prints text-success"></i>
              {{ __('Footer Logo & Branding Content') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Displayed at the footer of all public pages') }}</span>
          </div>
          <div class="card-body">
            <div class="row">

              {{-- Footer Logo Upload --}}
              <div class="col-lg-5 mb-4">
                <div class="preview-box h-100 d-flex flex-column align-items-center justify-content-center" style="background: #0f172a; color: #fff;">
                  <label class="font-weight-bold text-white mb-2">{{ __('Footer Logo') }}</label>
                  @php
                    $hasFtrLogo = !empty($abs->footer_logo) && file_exists(public_path('assets/front/img/' . $abs->footer_logo));
                    $ftrLogoSrc = $hasFtrLogo 
                                ? asset('assets/front/img/' . $abs->footer_logo) 
                                : (!empty($abs->logo) && file_exists(public_path('assets/front/img/' . $abs->logo)) ? asset('assets/front/img/' . $abs->logo) : asset('images/launchshop_icon.png'));
                  @endphp
                  <div class="showImage mb-3">
                    <img src="{{ $ftrLogoSrc }}" alt="Footer Logo Preview" class="img-fluid" style="background: transparent;">
                  </div>
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <label class="btn btn-light btn-sm upload-btn mb-0 px-3 font-weight-bold text-dark">
                      <i class="fas fa-upload mr-1"></i> {{ __('Upload Footer Logo') }}
                      <input type="file" name="footer_logo" class="img-input">
                    </label>
                    @if ($hasFtrLogo)
                      <a href="{{ route('admin.header_footer.remove_logo', 'footer') }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Revert to main logo?') }}');">
                        <i class="fas fa-trash"></i>
                      </a>
                    @endif
                  </div>
                  <small class="text-muted mt-2" style="color: #94a3b8 !important;">{{ __('If not uploaded, the header logo is used automatically.') }}</small>
                </div>
              </div>

              {{-- Footer Texts --}}
              <div class="col-lg-7 mb-4">
                <div class="form-group p-0 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Footer Tagline / About Text') }}</label>
                  <textarea name="footer_text" rows="3" class="form-control" 
                    placeholder="Launch Your Ecommerce Store In Minutes With Professional Themes">{{ $abs->footer_text ?? 'Launch Your Ecommerce Store In Minutes With Professional Themes' }}</textarea>
                </div>

                <div class="form-group p-0 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Copyright Line Text') }}</label>
                  <input type="text" name="copyright_text" class="form-control" 
                    value="{{ $abs->copyright_text ?? 'launchshop.in © 2026 All rights reserved | Nooryak Tech\'s Digital Product' }}" 
                    placeholder="launchshop.in © 2026 All rights reserved">
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="font-weight-bold text-dark small">{{ __('Useful Links Column Title') }}</label>
                    <input type="text" name="useful_links_title" class="form-control form-control-sm" 
                      value="{{ $abs->useful_links_title ?? 'Useful Links' }}" placeholder="Useful Links">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="font-weight-bold text-dark small">{{ __('Contact Info Column Title') }}</label>
                    <input type="text" name="contact_info_title" class="form-control form-control-sm" 
                      value="{{ $abs->contact_info_title ?? 'CONTACT INFO' }}" placeholder="CONTACT INFO">
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 3: HEADER NAVIGATION LINKS
           ========================================== -->
      <div class="tab-pane fade" id="tab-navlinks" role="tabpanel">
        <div class="card hf-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="hf-card-title">
              <i class="fas fa-bars text-warning"></i>
              {{ __('Header Navigation Links (Menu Builder)') }}
            </h5>
            <a href="{{ route('admin.menu_builder.index') . '?language=' . $language->code }}" class="btn btn-primary btn-sm font-weight-bold">
              <i class="fas fa-edit mr-1"></i> {{ __('Open Advanced Menu Builder') }}
            </a>
          </div>
          <div class="card-body">
            <p class="text-muted mb-3">
              {{ __('These are the active primary navigation links rendered on the desktop and mobile header navbar. You can reorder, rename, or add custom submenus anytime.') }}
            </p>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>{{ __('Nav Label / Name') }}</th>
                    <th>{{ __('Target Route / Link') }}</th>
                    <th>{{ __('Page Location') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td><strong class="text-primary">{{ __('Home') }}</strong></td>
                    <td><code>/</code> (front.index)</td>
                    <td><span class="badge badge-success">{{ __('Homepage') }}</span></td>
                    <td><a href="{{ route('admin.herosection.imgtext') }}" class="btn btn-xs btn-outline-primary">{{ __('Edit Home') }}</a></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td><strong class="text-primary">{{ __('Stores Launched') }}</strong></td>
                    <td><code>/shops</code> (front.user.view)</td>
                    <td><span class="badge badge-info">{{ __('Merchant Showcase') }}</span></td>
                    <td><a href="{{ route('admin.herosection.imgtext') . '#tab-shops' }}" class="btn btn-xs btn-outline-primary">{{ __('Edit Banner') }}</a></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td><strong class="text-primary">{{ __('About Us') }}</strong></td>
                    <td><code>/about</code> (front.about)</td>
                    <td><span class="badge badge-secondary">{{ __('Company Story') }}</span></td>
                    <td><a href="{{ route('admin.herosection.imgtext') . '#tab-about' }}" class="btn btn-xs btn-outline-primary">{{ __('Edit Banner') }}</a></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td><strong class="text-primary">{{ __('Pricing') }}</strong></td>
                    <td><code>/pricing</code> (front.pricing)</td>
                    <td><span class="badge badge-warning">{{ __('Subscription Plans') }}</span></td>
                    <td><a href="{{ route('admin.package.index') }}" class="btn btn-xs btn-outline-primary">{{ __('Manage Plans') }}</a></td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td><strong class="text-primary">{{ __('Store Themes') }}</strong></td>
                    <td><code>/templates</code> (front.templates.view)</td>
                    <td><span class="badge badge-dark">{{ __('Ecommerce Templates') }}</span></td>
                    <td><a href="{{ route('admin.herosection.imgtext') . '#tab-templates' }}" class="btn btn-xs btn-outline-primary">{{ __('Edit Banner') }}</a></td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td><strong class="text-primary">{{ __('Contact Us') }}</strong></td>
                    <td><code>/contact</code> (front.contact)</td>
                    <td><span class="badge badge-success">{{ __('Lead Capture & Support') }}</span></td>
                    <td><a href="{{ route('admin.contact.index') }}" class="btn btn-xs btn-outline-primary">{{ __('Manage Contact') }}</a></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('admin.menu_builder.index') . '?language=' . $language->code }}" class="btn btn-outline-primary px-4">
                <i class="fas fa-sitemap mr-1"></i> {{ __('Customize Menu Order & Hierarchy in Menu Builder') }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 4: FOOTER CONTACT & SOCIALS
           ========================================== -->
      <div class="tab-pane fade" id="tab-contact" role="tabpanel">
        <div class="card hf-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="hf-card-title">
              <i class="fas fa-address-book text-info"></i>
              {{ __('Footer Contact Info & Social Media Links') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Displayed in Column 3 and bottom row of the footer') }}</span>
          </div>
          <div class="card-body">
            
            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-map-pin text-danger mr-1"></i> {{ __('Contact Information Details') }}</h6>
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Office Address') }}</label>
                <input type="text" name="contact_addresses" class="form-control" 
                  value="{{ $abe->contact_addresses ?? 'triplicane , chennai' }}" placeholder="triplicane , chennai">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Phone Numbers (comma separated)') }}</label>
                <input type="text" name="contact_numbers" class="form-control" 
                  value="{{ $abe->contact_numbers ?? '9360157880' }}" placeholder="9360157880">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Support Email Address') }}</label>
                <input type="email" name="contact_mails" class="form-control" 
                  value="{{ $abe->contact_mails ?? 'suppot@launchshop.in' }}" placeholder="suppot@launchshop.in">
              </div>
            </div>

            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-share-alt text-primary mr-1"></i> {{ __('Social Media Profile Links') }}</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small"><i class="fab fa-instagram text-danger mr-1"></i> {{ __('Instagram Profile URL') }}</label>
                <input type="text" name="social_instagram" class="form-control form-control-sm" 
                  value="{{ $abs->social_instagram ?? 'https://www.instagram.com/ecombuilder.in/' }}" placeholder="https://instagram.com/...">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small"><i class="fab fa-facebook-f text-primary mr-1"></i> {{ __('Facebook Page URL') }}</label>
                <input type="text" name="social_facebook" class="form-control form-control-sm" 
                  value="{{ $abs->social_facebook ?? 'https://www.facebook.com/' }}" placeholder="https://facebook.com/...">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small"><i class="fab fa-linkedin-in text-info mr-1"></i> {{ __('LinkedIn Profile URL') }}</label>
                <input type="text" name="social_linkedin" class="form-control form-control-sm" 
                  value="{{ $abs->social_linkedin ?? 'https://www.linkedin.com/' }}" placeholder="https://linkedin.com/...">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small"><i class="fab fa-youtube text-danger mr-1"></i> {{ __('YouTube Channel URL') }}</label>
                <input type="text" name="social_youtube" class="form-control form-control-sm" 
                  value="{{ $abs->social_youtube ?? 'https://www.youtube.com/' }}" placeholder="https://youtube.com/...">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small"><i class="fab fa-twitter text-info mr-1"></i> {{ __('Twitter / X Profile URL') }}</label>
                <input type="text" name="social_twitter" class="form-control form-control-sm" 
                  value="{{ $abs->social_twitter ?? 'https://twitter.com/' }}" placeholder="https://twitter.com/...">
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
        {{ __('Changes will be immediately applied across the frontend header and footer.') }}
      </span>
      <button type="submit" class="btn btn-primary font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save Header & Footer Changes') }}
      </button>
    </div>

  </form>
@endsection

@section('scripts')
  <script>
    $(document).on('change', '.img-input', function(e) {
      var file = this.files && this.files[0];
      if (file) {
        var $box = $(this).closest('.preview-box');
        var $img = $box.find('.showImage img');
        var reader = new FileReader();
        reader.onload = function(evt) {
          if ($img.length) {
            $img.attr('src', evt.target.result);
          }
        };
        reader.readAsDataURL(file);
      }
    });

    function activateTabFromHash() {
      var hash = window.location.hash;
      if (hash && $('#hfTabs a[href="' + hash + '"]').length) {
        $('#hfTabs a[href="' + hash + '"]').tab('show');
      }
    }
    $(document).ready(function() {
      activateTabFromHash();
      $(window).on('hashchange', activateTabFromHash);
      $('#hfTabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        }
      });
    });
  </script>
@endsection