<!-- ─── PRICING V2 SECTION ─── -->
@php
  $selectedTemplate = request()->query('template');
  if (\Schema::hasTable('package_features')) {
      $allFeatures = \App\Models\PackageFeature::whereNotIn('name', ['Disqus', 'Bank Transfer Integrations'])
          ->whereNotIn('keyword', ['Disqus', 'Bank Transfer Integrations'])
          ->orderBy('serial_number', 'asc')
          ->get();
  } else {
      $allFeatures = collect();
  }
@endphp
<div class="pricing-v2-section" data-aos="fade-up">

  <!-- Toggle -->
  <div class="pricing-toggle-wrap">
    @if(in_array('yearly', array_map('strtolower', (array)$terms)) && empty($hidePill))
      <div class="pricing-save-pill">
        🏷️ Save up to 60% with yearly billing!
      </div>
    @endif
    <ul class="pricing-pill-tabs nav" id="pricing-tabs" role="tablist">
      @foreach ($terms as $term)
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                  id="{{ strtolower($term) }}-tab"
                  data-bs-toggle="pill"
                  data-bs-target="#tab-{{ strtolower($term) }}"
                  type="button"
                  role="tab"
                  aria-selected="{{ $loop->first ? 'true' : 'false' }}">
            {{ __(ucfirst(strtolower($term))) }}
          </button>
        </li>
      @endforeach
    </ul>
    @if(in_array('yearly', array_map('strtolower', (array)$terms)))
      <p class="yearly-free-domain-note mt-3">🎁 Premium & Enterprise Plans include Free Domains For Every Year.</p>
    @endif
  </div>


  <!-- Cards -->
  <div class="tab-content" id="pricing-tabs-content">
    @foreach ($terms as $term)
      @php
        $packages = \App\Models\Package::where('status', '1')->where('term', strtolower($term))->orderBy('price', 'asc')->get();
        if (strtolower($term) == 'monthly') {
            $newPackages = collect();
            
            // 1. Basic (monthly)
            $basicMonthly = $packages->first(function($p) {
                return strtolower($p->title) == 'basic';
            });
            if ($basicMonthly) {
                $newPackages->push($basicMonthly);
            } else {
                $anyBasic = \App\Models\Package::where('status', '1')->where('title', 'Basic')->first();
                if ($anyBasic) $newPackages->push($anyBasic);
            }
            
            // 2. Standard (yearly)
            $stdYearly = \App\Models\Package::where('status', '1')->where('term', 'yearly')->where('title', 'Standard')->first();
            if ($stdYearly) {
                $newPackages->push($stdYearly);
            } else {
                $stdMonthly = $packages->first(function($p) {
                    return strtolower($p->title) == 'standard';
                });
                if ($stdMonthly) $newPackages->push($stdMonthly);
            }
            
            // 3. Premium (yearly)
            $premYearly = \App\Models\Package::where('status', '1')->where('term', 'yearly')->where('title', 'Premium')->first();
            if ($premYearly) {
                $newPackages->push($premYearly);
            } else {
                $premMonthly = $packages->first(function($p) {
                    return strtolower($p->title) == 'premium';
                });
                if ($premMonthly) $newPackages->push($premMonthly);
            }
            
            $packages = $newPackages;
        }
      @endphp
      <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
           id="tab-{{ strtolower($term) }}"
           role="tabpanel">
        <div class="pricing-cards-row">

          @foreach ($packages as $index => $package)
            @php
              $titleKey    = strtolower($package->title);

              $isRecommended = ($titleKey == 'standard');
              $isBestValue   = ($titleKey == 'premium');
              $isBasic       = ($titleKey == 'basic');
              $cardClass     = $isRecommended ? 'card-recommended' : ($isBestValue ? 'card-best-value' : ($isBasic ? 'card-basic' : ''));

              // Subtitle
              $subtitles = [
                  'basic' => 'Start your own online store',
                  'standard' => 'Elevate Your E-commerce Journey',
                  'premium' => 'Best for Small Business Owners.'
              ];
              $planSubtitle = $subtitles[$titleKey] ?? ucfirst($titleKey).' plan';

              $periodLabel = strtolower($package->term) == 'lifetime' ? 'one-time' : (strtolower($package->term) == 'yearly' ? 'year' : 'month');
              // Features
              $pFeatures = json_decode($package->features, true) ?: [];
              $packageFormattedFeatures = [];
              
              if ($allFeatures->isEmpty()) {
                  $packageFormattedFeatures = [];
                  
                  $limitVal = $package->categories_limit ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Categories Limit : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  $limitVal = $package->product_limit ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Products Limit : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  $limitVal = $package->order_limit ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Orders Limit : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  $limitVal = $package->language_limit ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Additional Languages : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  $limitVal = $package->post_limit ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Posts Limit : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  $limitVal = $package->number_of_custom_page ?? 0;
                  if ($limitVal > 0 || $limitVal == 999999) {
                      $packageFormattedFeatures[] = ['text' => 'Custom Pages : '.($limitVal==999999?'Unlimited':$limitVal), 'has' => true];
                  }
                  
                  $fallbackPills = [
                      'Custom Domain' => 'Custom Domain',
                      'Subdomain' => 'Subdomain',
                      'QR Builder' => 'QR Builder',
                      'Blog' => 'Blog',
                      'Custom Page' => 'Custom Page',
                      'Google Login' => 'Google Login',
                      'Google Analytics' => 'Google Analytics',
                      'Google Recaptcha' => 'Google Recaptcha',
                      'WhatsApp Chat Button' => 'WhatsApp Chat Button',
                      'Tawk to' => 'Tawk to',
                      'AI Content & Image Generator' => 'AI Content & Image Generator'
                  ];
                  foreach ($fallbackPills as $k => $name) {
                      if ($k === 'AI Content & Image Generator') {
                          continue;
                      }
                      if ($k !== 'Blog' && $k !== 'Custom Page') {
                          if (in_array($k, $pFeatures)) {
                              $packageFormattedFeatures[] = ['text' => $name, 'has' => true];
                          }
                      }
                  }
              } else {
                  foreach ($allFeatures as $feature) {
                      if ($feature->keyword === 'AI Content & Image Generator' || $feature->name === 'AI Content & Image Generator') {
                          continue;
                      }
                      $has = false;
                      $text = $feature->name;
                      
                      if ($feature->type === 'limit') {
                          $limitVal = $package->{$feature->limit_key} ?? 0;
                          $formattedVal = ($limitVal == 999999) ? __('Unlimited') : $limitVal;
                          if ($feature->limit_key === 'order_limit' && $limitVal != 999999) {
                              $formattedVal .= '/' . ($package->term == 'monthly' ? 'm' : 'yr');
                          }
                          $text = str_replace('{limit}', $formattedVal, $text);
                          $has = ($limitVal > 0 || $limitVal == 999999);
                      } elseif ($feature->type === 'standard') {
                          $has = in_array($feature->keyword, $pFeatures);
                          if ($has && !empty($feature->limit_key)) {
                              $limitVal = $package->{$feature->limit_key} ?? 0;
                              $formattedVal = ($limitVal == 999999) ? __('Unlimited') : $limitVal;
                              $text = str_replace('{limit}', $formattedVal, $text);
                          } elseif (!empty($feature->limit_key)) {
                              $limitVal = $package->{$feature->limit_key} ?? 0;
                              $formattedVal = ($limitVal == 999999) ? __('Unlimited') : $limitVal;
                              $text = str_replace('{limit}', $formattedVal, $text);
                          }
                      } elseif ($feature->type === 'custom') {
                          $has = in_array($feature->name, $pFeatures);
                      }
                      
                      $packageFormattedFeatures[] = ['text' => $text, 'has' => $has];
                  }
              }
 
              // Post-process features to customize "Additional Languages" dynamically based on plan title key
              // Basic: strikeout, Standard: 1 Additional Language, Premium: 3 Additional Languages
              
              // Find if "Additional Languages" already exists in the formatted features
              $foundLangKey = false;
              foreach ($packageFormattedFeatures as $key => $feat) {
                  if (stripos($feat['text'], 'Additional Languages') !== false || stripos($feat['text'], 'languages') !== false) {
                      $foundLangKey = $key;
                      break;
                  }
              }
              
              $customText = 'Additional Languages';
              $customHas = false;
              
              if ($titleKey === 'standard') {
                  $customText = '1 Additional Language';
                  $customHas = true;
              } elseif ($titleKey === 'premium') {
                  $customText = '3 Additional Languages';
                  $customHas = true;
              }
              
              if ($foundLangKey !== false) {
                  // Override the existing feature
                  $packageFormattedFeatures[$foundLangKey]['text'] = $customText;
                  $packageFormattedFeatures[$foundLangKey]['has'] = $customHas;
              } else {
                  // If it doesn't exist, append it
                  $packageFormattedFeatures[] = ['text' => $customText, 'has' => $customHas];
              }
 
              $visibleCount = 7;
              $visibleFeats = array_slice($packageFormattedFeatures, 0, $visibleCount);
              $extraFeats   = array_slice($packageFormattedFeatures, $visibleCount);

              // CTA href
              if ($package->is_trial === '1' && $package->price != 0) {
                $ctaHref = $selectedTemplate
                  ? route('front.register.view', ['status'=>'regular','id'=>$package->id]).'?template='.urlencode($selectedTemplate)
                  : route('front.select.template', ['status'=>'regular','id'=>$package->id]);
              } elseif ($package->price == 0) {
                $ctaHref = $selectedTemplate
                  ? route('front.register.view', ['status'=>'regular','id'=>$package->id]).'?template='.urlencode($selectedTemplate)
                  : route('front.select.template', ['status'=>'regular','id'=>$package->id]);
              } else {
                $ctaHref = $selectedTemplate
                  ? route('front.register.view', ['status'=>'regular','id'=>$package->id]).'?template='.urlencode($selectedTemplate)
                  : route('front.select.template', ['status'=>'regular','id'=>$package->id]);
              }
            @endphp

            <div class="pricing-card-v2 {{ $cardClass }} package-{{ $package->id }} plan-{{ $titleKey }}">
              @if($titleKey == 'standard')
                <!-- Mobile-only Most Popular Callout -->
                <div class="mobile-most-popular-callout d-lg-none">
                  <div class="d-flex align-items-start gap-2">
                    <div class="callout-icon-wrap rocket" style="background:#ff5a2c; color:#fff;">
                      <i class="fas fa-rocket"></i>
                    </div>
                    <div class="callout-text">
                      <h5 class="callout-title">Most Popular</h5>
                      <p class="callout-desc">Ideal balance for growing businesses with all essential tools.</p>
                    </div>
                  </div>
                </div>
              @endif

              @if($titleKey == 'premium')
                <!-- Mobile-only Most People Choose Callout -->
                <div class="mobile-most-people-choose-callout d-lg-none">
                  <div class="d-flex align-items-start gap-2">
                    <div class="callout-icon-wrap star" style="background:#f59e0b; color:#fff;">
                      <i class="fas fa-star"></i>
                    </div>
                    <div class="callout-text">
                      <h5 class="callout-title">Most People Choose</h5>
                      <p class="callout-desc">Best value with all premium features and a free domain.</p>
                    </div>
                  </div>
                </div>
              @endif

              @if($titleKey == 'basic')
                @if(strtolower($term) == 'monthly')
                  <!-- Mobile Monthly Billing Callout Box -->
                  <div class="mobile-monthly-billing-callout d-lg-none">
                    <div class="d-flex align-items-start gap-2">
                      <div class="callout-icon-wrap">
                        <i class="far fa-calendar-alt"></i>
                      </div>
                      <div class="callout-text">
                        <h5 class="callout-title">Monthly Billing</h5>
                        <p class="callout-desc">Only Basic plan is available with <strong>monthly billing</strong>.</p>
                      </div>
                    </div>
                  </div>
                  <!-- Desktop Monthly Billing Callout Box -->
                  <div class="monthly-billing-callout d-none d-lg-block">
                    <div class="d-flex align-items-start gap-2">
                      <div class="callout-icon-wrap">
                        <i class="far fa-calendar-alt"></i>
                      </div>
                      <div class="callout-text">
                        <h5 class="callout-title">Monthly Billing</h5>
                        <p class="callout-desc">Only Basic plan is available with <strong>monthly billing</strong>.</p>
                      </div>
                    </div>
                    <div class="callout-arrow">
                      <svg width="220" height="50" viewBox="0 0 220 50" fill="none">
                        <path d="M10,0 C10,35 150,45 205,45" stroke="#ff5a2c" stroke-dasharray="4,4" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M197,39 L207,45 L197,51" stroke="#ff5a2c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                      </svg>
                    </div>
                  </div>
                @else
                  <!-- Basic Most Popular Callout Box (Top of Basic card on Yearly tab) -->
                  <div class="basic-most-popular-callout d-none d-lg-block">
                    <div class="d-flex align-items-start gap-2">
                      <div class="callout-icon-wrap rocket" style="background:#ff5a2c; color:#fff;">
                        <i class="fas fa-rocket"></i>
                      </div>
                      <div class="callout-text">
                        <h5 class="callout-title">Most Popular</h5>
                        <p class="callout-desc">Ideal balance for growing businesses with all essential tools.</p>
                      </div>
                    </div>
                    <div class="callout-arrow-to-standard">
                      <svg width="180" height="45" viewBox="0 0 180 45" fill="none">
                        <path d="M10,0 C10,30 120,40 165,40" stroke="#ff5a2c" stroke-dasharray="4,4" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M157,34 L167,40 L157,46" stroke="#ff5a2c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                      </svg>
                    </div>
                  </div>
                @endif
              @endif

              @if($titleKey == 'standard' && strtolower($term) == 'monthly')
                <!-- Standard Most Popular Callout Box (Positioned on Right side of Standard card on Monthly tab) -->
                <div class="standard-most-popular-callout d-none d-lg-block">
                  <div class="d-flex align-items-start gap-2">
                    <div class="callout-icon-wrap rocket" style="background:#ff5a2c; color:#fff;">
                      <i class="fas fa-rocket"></i>
                    </div>
                    <div class="callout-text">
                      <h5 class="callout-title">Most Popular</h5>
                      <p class="callout-desc">Ideal balance for growing businesses with all essential tools.</p>
                    </div>
                  </div>
                  <div class="callout-arrow-left">
                    <svg width="220" height="50" viewBox="0 0 220 50" fill="none">
                      <path d="M210,0 C210,35 70,45 15,45" stroke="#ff5a2c" stroke-dasharray="4,4" stroke-width="1.5" stroke-linecap="round" />
                      <path d="M23,39 L13,45 L23,51" stroke="#ff5a2c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                    </svg>
                  </div>
                </div>
              @endif

              {{-- Top badge --}}
              @if($isRecommended)
                <span class="plan-top-badge badge-recommended">RECOMMENDED</span>
              @elseif($isBestValue)
                <span class="plan-top-badge badge-best-value">BEST VALUE</span>
              @endif

              {{-- Icon --}}
              <div class="plan-v2-icon">
                @if($titleKey == 'basic') <i class="fas fa-paper-plane"></i>
                @elseif($titleKey == 'standard') <i class="fas fa-rocket"></i>
                @elseif($titleKey == 'premium') <i class="fas fa-crown"></i>
                @else <i class="fas fa-building"></i>
                @endif
              </div>

              {{-- Title --}}
              <h3 class="plan-v2-title">Ecom {{ __($package->title) }}</h3>
              <p class="plan-v2-subtitle">{{ $planSubtitle }}</p>

              {{-- Price --}}
              <div class="plan-v2-price-block">
                @if($package->price == 0)
                  <span class="plan-v2-amount">{{ __('Free') }}</span>
                @else
                  <span class="plan-v2-currency">{{ $be->base_currency_symbol }}</span><span class="plan-v2-amount">{{ number_format($package->price, 0) }}</span>
                  <span class="plan-v2-period"> / {{ $periodLabel }}</span>
                @endif
              </div>
              @if(strtolower($package->term)=='monthly' && $titleKey == 'basic')
                <div class="text-center mb-2">
                  <span class="plan-monthly-badge">Just ₹1999/Yearly</span>
                </div>
              @else
                <p class="plan-v2-billing-note">
                  @if(strtolower($package->term)=='yearly' || $titleKey == 'standard' || $titleKey == 'premium')
                    Billed yearly
                  @else
                    One-time access fee
                  @endif
                </p>
              @endif

              <hr class="plan-v2-divider">

              {{-- Visible features --}}
              <ul class="plan-v2-features">
                @foreach($visibleFeats as $feat)
                  @php
                    $isPremiumFreeDomain = ($titleKey == 'premium' && (stripos($feat['text'], 'Free .in Domain') !== false || stripos($feat['text'], 'Free Domain') !== false));
                  @endphp
                  <li class="{{ !$feat['has'] ? 'feat-disabled' : '' }} {{ $isPremiumFreeDomain ? 'feat-free-domain-premium' : '' }}">
                    @if($isPremiumFreeDomain)
                      <i class="fas fa-globe fi-globe-premium" style="color: #10b981; font-size: 15px; flex-shrink: 0; margin-top: 2px;"></i>
                    @elseif($feat['has'])
                      <i class="fas fa-check fi-check"></i>
                    @else
                      <i class="fas fa-times fi-times"></i>
                    @endif
                    <span>{{ __($feat['text']) }}</span>
                  </li>
                @endforeach
              </ul>

              {{-- Extra features (collapsed) --}}
              @php $hasExtra = (count($extraFeats) > 0); @endphp
              @if($hasExtra)
                <div class="plan-v2-extra-features" id="extra-{{ strtolower($term) }}-{{ $package->id }}">
                  <ul class="plan-v2-features" style="margin-bottom:8px;">
                    @foreach($extraFeats as $ef)
                      @php
                        $isPremiumFreeDomainExtra = ($titleKey == 'premium' && (stripos($ef['text'], 'Free .in Domain') !== false || stripos($ef['text'], 'Free Domain') !== false));
                      @endphp
                      <li class="{{ !$ef['has'] ? 'feat-disabled' : '' }} {{ $isPremiumFreeDomainExtra ? 'feat-free-domain-premium' : '' }}">
                        @if($isPremiumFreeDomainExtra)
                          <i class="fas fa-globe fi-globe-premium" style="color: #10b981; font-size: 15px; flex-shrink: 0; margin-top: 2px;"></i>
                        @elseif($ef['has'])
                          <i class="fas fa-check fi-check"></i>
                        @else
                          <i class="fas fa-times fi-times"></i>
                        @endif
                        <span>{{ __($ef['text']) }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>
                <button type="button" class="plan-v2-see-more"
                        onclick="togglePlanFeatures('{{ strtolower($term) }}-{{ $package->id }}', this)">
                  <span class="see-more-txt">See More Features</span>
                  <i class="fas fa-arrow-right see-more-icon" style="font-size:11px;"></i>
                </button>
              @endif

              {{-- CTA Button with correct name --}}
              <a href="{{ $ctaHref }}" class="plan-v2-btn {{ ($isRecommended || $isBestValue) ? '' : 'btn-v2-outline' }}">
                Select Ecom {{ __($package->title) }}
              </a>

            </div><!-- /.pricing-card-v2 -->
          @endforeach

          @if(strtolower($term) != 'monthly')
          {{-- Enterprise card always shown --}}
            <div class="pricing-card-v2 card-enterprise package-enterprise">
              <!-- Enterprise Callout Box (Most Chosen) -->
              <div class="enterprise-callout-box d-none d-lg-block">
                <div class="d-flex align-items-start gap-2">
                  <div class="callout-icon-wrap star" style="background:#f59e0b; color:#fff;">
                    <i class="fas fa-star"></i>
                  </div>
                  <div class="callout-text">
                    <h5 class="callout-title">Most People Choose</h5>
                    <p class="callout-desc">Best value with all premium features and a free domain.</p>
                  </div>
                </div>
                <div class="callout-arrow-left">
                  <svg width="120" height="40" viewBox="0 0 120 40" fill="none">
                    <path d="M110,0 C110,25 50,35 15,35" stroke="#f59e0b" stroke-dasharray="4,4" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M23,29 L13,35 L23,41" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                  </svg>
                </div>
              </div>
              <div class="plan-v2-icon" style="color:#ff5a2c;"><i class="fas fa-building"></i></div>
              <h3 class="plan-v2-title">Enterprise</h3>
              <p class="plan-v2-subtitle">Kickstart Your E-commerce Business.</p>
              <div class="plan-v2-price-block">
                <span class="plan-v2-amount" style="font-size:34px;color:#0f172a;">Custom</span>
              </div>
              <p class="plan-v2-billing-note">Tailored to your needs</p>
              <hr class="plan-v2-divider">
              <ul class="plan-v2-features">
                <li><i class="fas fa-globe fi-check" style="color: #10b981;"></i><span style="color: #10b981; font-weight: 700;">Free .com Domain Every Year</span></li>
                <li><i class="fas fa-check fi-check"></i><span>Everything in Premium</span></li>
                <li><i class="fas fa-check fi-check"></i><span>Unlimited Staff Accounts</span></li>
                <li><i class="fas fa-check fi-check"></i><span>Dedicated Account Manager</span></li>
                <li><i class="fas fa-check fi-check"></i><span>Custom Integrations</span></li>
                <li><i class="fas fa-check fi-check"></i><span>SLA &amp; Uptime Guarantee</span></li>
                <li><i class="fas fa-check fi-check"></i><span>Priority 24/7 Support</span></li>
              </ul>
              <div style="display:flex;gap:8px;margin-top:auto;width:100%;">
                <a href="{{ route('front.contact') }}" class="plan-v2-btn" style="flex:1;margin-top:0;">Talk to Sales</a>
                <a href="https://wa.me/917200770351?text=Hi%2C%20I%20want%20to%20enquire%20about%20the%20Enterprise%20Plan%20for%20Ecom Builder." target="_blank" class="plan-v2-wa-btn">
                  <i class="fab fa-whatsapp"></i>
                </a>
              </div>
            </div>
          @endif

        </div><!-- /.pricing-cards-row -->

        <!-- Trust row -->
        <div class="pricing-v2-trust ">
          <div class="pricing-v2-trust-item">
            <span class="trust-icon green"><i class="fas fa-shield-alt"></i></span>
            <span><strong>10-Days Money Back Guarantee</strong><br><small>Risk-free, no questions asked</small></span>
          </div>
          <div class="pricing-v2-trust-item">
            <span class="trust-icon orange"><i class="fas fa-times"></i></span>
            <span><strong>Cancel Anytime</strong><br><small>No lock-in contracts</small></span>
          </div>
          <div class="pricing-v2-trust-item">
            <span class="trust-icon purple"><i class="fas fa-lock"></i></span>
            <span><strong>Secure Checkout</strong><br><small>Your data is 100% safe</small></span>
          </div>
        </div>

      </div>
    @endforeach
  </div>

</div>
