@php
  // Dynamically load active Announcement Popup from Super Admin or Custom VIP Popup
  $activePopup = null;
  try {
      if (\Illuminate\Support\Facades\Schema::hasTable('popups')) {
          $activePopup = \App\Models\Popup::where('status', 1)->orderBy('serial_number', 'ASC')->first();
      }
  } catch (\Throwable $e) {
      $activePopup = null;
  }

  $popupSec = null;
  try {
      \App\Models\Admin\ImageText::ensureColumnsExist();
      $popupSec = \App\Models\Admin\ImageText::first();
  } catch (\Throwable $e) {}

  $isPopupActive = (!isset($popupSec->popup_status) || $popupSec->popup_status == 1);
@endphp

@if ($isPopupActive && ($activePopup || $popupSec))

{{-- Year-End Growth Deal Popup v2 - Pixel Perfect Match --}}
<div class="gd-overlay" id="gdOverlay" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Year-End Growth Deal">
    <div class="gd-backdrop" id="gdBackdrop"></div>

    <div class="gd-dialog">

        {{-- 3D Gift Box (left side, overlapping modal edge) --}}
        <div class="gd-giftbox" aria-hidden="true">
            <img src="{{ !empty($popupSec->popup_giftbox_img) ? asset('assets/front/img/' . $popupSec->popup_giftbox_img) : asset('images/orange_percent_giftbox.png') }}" alt="Gift Box" style="width: 100%; height: auto;">
        </div>

        {{-- White Modal Box --}}
        <div class="gd-box" id="gdBox">

            {{-- Close Button --}}
            <button type="button" class="gd-close" id="gdClose" aria-label="Close popup">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                    <path d="M1 1L15 15M15 1L1 15" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </button>

            {{-- Celebration ornaments matching reference image exactly --}}
            <div class="gd-celebration gd-cel-left" aria-hidden="true">
                <svg viewBox="0 0 100 100" class="gd-cel-svg">
                    <path d="M10,40 Q22,32 16,18 T28,6" stroke="#FF8B5E" stroke-width="2.5" fill="none" stroke-linecap="round" />
                    <path d="M25,48 Q37,40 31,26 T43,14" stroke="#FFA37B" stroke-width="2" fill="none" stroke-linecap="round" />
                    <path d="M5,58 Q17,50 11,36 T23,24" stroke="#FFBC9F" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    <path d="M55,30 L57,35 L62,35 L58,39 L60,44 L55,41 L50,44 L52,39 L48,35 L53,35 Z" fill="#3B82F6" />
                    <path d="M35,65 L37,68 L41,68 L38,71 L39,75 L35,73 L31,75 L32,71 L29,68 L33,68 Z" fill="#FF5A2C" />
                    <path d="M75,50 L76.5,52 L79.5,52 L77,54 L78,57 L75,55.5 L72,57 L73,54 L70.5,52 L73.5,52 Z" fill="#FFB700" />
                </svg>
            </div>
            <div class="gd-celebration gd-cel-right" aria-hidden="true">
                <svg viewBox="0 0 100 100" class="gd-cel-svg">
                    <circle cx="50" cy="20" r="5" fill="none" stroke="#3B82F6" stroke-width="2" />
                    <path d="M25,40 L27,45 L32,45 L28,49 L30,54 L25,51 L20,54 L22,49 L18,45 L23,45 Z" fill="#FFB700" />
                    <path d="M70,35 L72,40 L77,40 L73,44 L75,49 L70,46 L65,49 L67,44 L63,40 L68,40 Z" fill="#FFB700" />
                    <path d="M60,60 Q70,55 65,45 T75,35" stroke="#FFA37B" stroke-width="2" fill="none" stroke-linecap="round" />
                    <path d="M85,55 L90,65" stroke="#FF5A2C" stroke-width="2" fill="none" stroke-linecap="round" />
                </svg>
            </div>

            @php
                $dispRibbon = !empty($popupSec->popup_ribbon_text) ? $popupSec->popup_ribbon_text : (!empty($activePopup->name) ? $activePopup->name : 'VIP ACCESS');
                $dispTitle  = !empty($popupSec->popup_title) ? $popupSec->popup_title : (!empty($activePopup->title) ? $activePopup->title : 'Exclusive VIP Access');
                $dispText   = !empty($popupSec->popup_text) ? $popupSec->popup_text : (!empty($activePopup->text) ? $activePopup->text : 'Unlock early access to our latest products and exclusive discounts. Become a VIP today!');

                // Sanitize any legacy Arabic database content into clean English
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $dispRibbon)) {
                    $dispRibbon = 'VIP ACCESS';
                }
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $dispTitle)) {
                    $dispTitle = 'Exclusive VIP Access';
                }
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $dispText)) {
                    $dispText = 'Unlock early access to our latest products and exclusive discounts. Become a VIP today!';
                }
            @endphp

            {{-- Top Ribbon Badge --}}
            <div class="gd-ribbon-wrap">
                <div class="gd-ribbon">
                    <i class="fas fa-crown" style="margin-right: 6px; font-size: 11.5px; color: white;"></i>
                    {{ $dispRibbon }}
                </div>
            </div>

            {{-- Main Heading --}}
            <h2 class="gd-heading">{!! $dispTitle !!}</h2>
            <p class="gd-timer-lbl">{!! $dispText !!}</p>

            {{-- Countdown Timer --}}
            <div class="gd-timer-section">

                <span class="gd-sparkle-left">✦</span>
                <div class="gd-timer-row">
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-hours">{{ !empty($popupSec->popup_timer_hours) ? $popupSec->popup_timer_hours : "00" }}</span>
                        <span class="gd-tunit">HOURS</span>
                    </div>
                    <span class="gd-tsep">:</span>
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-mins">{{ !empty($popupSec->popup_timer_mins) ? $popupSec->popup_timer_mins : "27" }}</span>
                        <span class="gd-tunit">MINUTES</span>
                    </div>
                    <span class="gd-tsep">:</span>
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-secs">{{ !empty($popupSec->popup_timer_secs) ? $popupSec->popup_timer_secs : "33" }}</span>
                        <span class="gd-tunit">SECONDS</span>
                    </div>
                </div>
                <span class="gd-sparkle-right">✦</span>
            </div>

            {{-- Pricing Cards Outer (extra right space for 65% badge) --}}
            <div class="gd-cards-outer">
                <div class="gd-cards-grid">

                    {{-- Standard Card --}}
                    <div class="gd-card gd-std">
                        <div class="gd-card-header">
                            <div class="gd-hexagon-icon">
                                <svg viewBox="0 0 100 100" class="gd-hex-svg">
                                    <polygon points="50,5 90,28 90,72 50,95 10,72 10,28" fill="#FFF5F2" stroke="#FF5A2C" stroke-width="3.5" />
                                </svg>
                                <i class="fas fa-star gd-hex-inner-icon"></i>
                            </div>
                            <div class="gd-card-meta">
                                <h3 class="gd-card-name">{{ !empty($popupSec->popup_std_name) ? $popupSec->popup_std_name : 'Ecom Standard' }}</h3>
                                <p class="gd-card-tagline gd-tagline-standard">{{ !empty($popupSec->popup_std_tagline) ? $popupSec->popup_std_tagline : 'Smart choice for growing brands' }}</p>
                            </div>
                        </div>
                        <div class="gd-great-val">
                           {{ !empty($popupSec->popup_std_pill) ? $popupSec->popup_std_pill : '🎉 Great Value' }}
                        </div>
                        <div class="gd-price-row">
                            <span class="gd-original-price">{{ !empty($popupSec->popup_std_orig_price) ? $popupSec->popup_std_orig_price : '₹14,283/year' }}</span>
                            <span class="gd-save-tag">{{ !empty($popupSec->popup_std_save_tag) ? $popupSec->popup_std_save_tag : 'Save ₹9,284' }}</span>
                        </div>
                        <div class="gd-price-big">{{ !empty($popupSec->popup_std_price) ? $popupSec->popup_std_price : '₹3,999' }}<span class="gd-per">/year</span></div>
                        <p class="gd-billed">{{ !empty($popupSec->popup_std_billed) ? $popupSec->popup_std_billed : 'Billed annually' }}</p>
                        <ul class="gd-feats">
                            @if(!empty($popupSec->popup_std_features))
                                @foreach(explode("\n", str_replace("\r", "", $popupSec->popup_std_features)) as $feat)
                                    @if(trim($feat))
                                        <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>{{ trim($feat) }}</li>
                                    @endif
                                @endforeach
                            @else
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Custom Domain </li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Unlimited Orders</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Custom Pages</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Inventory Management </li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Advanced Report</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Google Login</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Gst Billing.</li>
                            @endif
                            <li>
                                <a href="{{ !empty($popupSec->popup_std_btn_url) ? $popupSec->popup_std_btn_url : route('front.pricing') . '?term=yearly' }}" class="gd-more-features gd-btn-standard-trigger">
                                   More Features &nbsp;<span class="gd-plus-circle"><i class="fas fa-plus"></i></span>
                                </a>
                            </li>
                        </ul>
                        <a href="{{ !empty($popupSec->popup_std_btn_url) ? $popupSec->popup_std_btn_url : route('front.pricing') . '?term=yearly' }}" class="gd-btn-border" id="gdChooseStandard">{{ !empty($popupSec->popup_std_btn_text) ? $popupSec->popup_std_btn_text : 'Choose Standard  →' }}</a>
                    </div>

                    {{-- Premium Card --}}
                    <div class="gd-card gd-pre">
                        <div class="gd-card-header">
                            <div class="gd-hexagon-icon">
                                <svg viewBox="0 0 100 100" class="gd-hex-svg">
                                    <polygon points="50,5 90,28 90,72 50,95 10,72 10,28" fill="#FFFCEB" stroke="#F59E0B" stroke-width="3.5" />
                                </svg>
                                <i class="fas fa-crown gd-hex-inner-icon" style="color: #F59E0B;"></i>
                            </div>
                            <div class="gd-card-meta">
                                <h3 class="gd-card-name">{{ !empty($popupSec->popup_pre_name) ? $popupSec->popup_pre_name : 'Ecom Premium' }}</h3>
                                <p class="gd-card-tagline gd-tagline-premium">{{ !empty($popupSec->popup_pre_tagline) ? $popupSec->popup_pre_tagline : 'Best choice for advanced features' }}</p>
                            </div>
                        </div>
                        <div class="gd-great-val">
                            {{ !empty($popupSec->popup_pre_pill) ? $popupSec->popup_pre_pill : '😍 Most Popular' }}
                        </div>
                        <div class="gd-price-row" style="margin-top:16px;">
                            <span class="gd-original-price">{{ !empty($popupSec->popup_pre_orig_price) ? $popupSec->popup_pre_orig_price : '₹28,569/year' }}</span>
                            <span class="gd-save-tag">{{ !empty($popupSec->popup_pre_save_tag) ? $popupSec->popup_pre_save_tag : 'Save ₹18,570' }}</span>
                        </div>
                        <div class="gd-price-big">{{ !empty($popupSec->popup_pre_price) ? $popupSec->popup_pre_price : '₹8,999' }}<span class="gd-per">/year</span></div>
                        <p class="gd-billed">{{ !empty($popupSec->popup_pre_billed) ? $popupSec->popup_pre_billed : 'Billed annually' }}</p>
                        <ul class="gd-feats">
                            <li class="gd-feat-green-highlight"><span class="gd-check-circle gd-check-green"><i class="fas fa-check"></i></span>{{ !empty($popupSec->popup_pre_free_domain) ? $popupSec->popup_pre_free_domain : 'Free .in Domain' }}</li>
                            @if(!empty($popupSec->popup_pre_features))
                                @foreach(explode("\n", str_replace("\r", "", $popupSec->popup_pre_features)) as $feat)
                                    @if(trim($feat))
                                        <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>{{ trim($feat) }}</li>
                                    @endif
                                @endforeach
                            @else
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>All Standard Features</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Premium Themes</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Staff Management</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Push Notification</li>
                                <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Payment Option</li>
                            @endif
                            <li>
                                <a href="{{ !empty($popupSec->popup_pre_btn_url) ? $popupSec->popup_pre_btn_url : route('front.pricing') . '?term=yearly' }}" class="gd-more-features gd-btn-premium-trigger">
                                    Advanced Features &nbsp;<span class="gd-plus-circle"><i class="fas fa-plus"></i></span>
                                </a>
                            </li>
                        </ul>
                        <div class="gd-select-premium-wrap">
                            <span class="gd-click-hand" aria-hidden="true">👆</span>
                            <a href="{{ !empty($popupSec->popup_pre_btn_url) ? $popupSec->popup_pre_btn_url : route('front.pricing') . '?term=yearly' }}" class="gd-btn-fill" id="gdUpgradePremium">{{ !empty($popupSec->popup_pre_btn_text) ? $popupSec->popup_pre_btn_text : 'Select Premium  →' }}</a>
                        </div>
                        <span class="gd-card-sparkle">✦</span>
                    </div>
                </div>

                {{-- 65% OFF Badge (outside premium card, right side) --}}
                <div class="gd-off-badge">
                    <i class="fas fa-fire"></i>
                    <span>{{ !empty($popupSec->popup_pre_badge) ? $popupSec->popup_pre_badge : 'Up to 65% OFF' }}</span>
                </div>
            </div>



            {{-- Dismiss --}}
            <div class="gd-footer">
                <a href="#" class="gd-dismiss" id="gdDismiss">{{ !empty($popupSec->popup_dismiss_text) ? $popupSec->popup_dismiss_text : "No Thanks , Maybe Later" }}</a>
            </div>

        </div>{{-- .gd-box --}}
    </div>{{-- .gd-dialog --}}
</div>{{-- .gd-overlay --}}

{{-- ============================
     STYLES
     ============================ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap');

/* Apply Outfit font to headings and highlight texts to match reference image */
.gd-heading, .gd-card-name, .gd-tnum, .gd-ribbon, .gd-price-big, .gd-off-badge strong, .gd-ti strong {
    font-family: 'Outfit', sans-serif !important;
}

/* ── Reset in scope ── */
.gd-overlay *, .gd-overlay *::before, .gd-overlay *::after {
    box-sizing: border-box;
}

/* ── Overlay: fixed fullscreen container, no scroll, centered ── */
.gd-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000001;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
    overflow: hidden;
    pointer-events: none;
}
.gd-overlay.gd-show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

/* ── Backdrop ── */
.gd-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    cursor: pointer;
    z-index: 1;
}

/* ── Dialog: permanently fixed in exact center of viewport ── */
.gd-dialog {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    width: calc(100% - 24px);
    max-width: 590px;
    max-height: 94vh;
    margin: 0;
    display: flex;
    flex-direction: column;
}

/* ── 3D Gift Box ── */
.gd-giftbox {
    position: absolute;
    left: -70px;
    top: 14px;
    width: 110px;
    pointer-events: none;
    z-index: 10;
    animation: gdFloat 4s ease-in-out infinite;
    filter: drop-shadow(0 14px 20px rgba(255, 90, 30, 0.22));
}
.gd-giftbox img { width: 100%; height: auto; }

@keyframes gdFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-8px); }
}

/* ── White Box: compact padding, fits laptop viewports without scrolling ── */
.gd-box {
    background: linear-gradient(180deg, #FFFFFF 0%, #FFFDFB 100%);
    border: 1.5px solid rgba(255, 90, 44, 0.14);
    border-radius: 18px;
    padding: 12px 18px 12px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.22), 0 10px 30px rgba(255, 90, 44, 0.12);
    position: relative;
    width: 100%;
    max-height: 94vh;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #f0ece9 transparent;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    overscroll-behavior: contain;
}
.gd-box::-webkit-scrollbar { width: 4px; }
.gd-box::-webkit-scrollbar-thumb { background: #e2ded9; border-radius: 4px; }

/* ── Close Button ── */
.gd-close {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #FF5A2C;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s, transform 0.18s;
    z-index: 10;
    padding: 0;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.25);
}
.gd-close:hover { background: #E04E22; transform: scale(1.08); }

/* ── Celebration Ornaments ── */
.gd-celebration {
    position: absolute;
    width: 75px;
    height: 75px;
    pointer-events: none;
    z-index: 1;
}
.gd-cel-left {
    top: 2%;
    left: 2%;
    transform: rotate(-10deg);
}
.gd-cel-right {
    top: 2%;
    right: 2%;
    transform: rotate(10deg);
}
.gd-cel-svg {
    width: 100%;
    height: 100%;
    overflow: visible;
}

/* ── Ribbon Badge ── */
.gd-ribbon-wrap { text-align: center; margin-bottom: 6px; }
.gd-ribbon {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(90deg, #FF6B3D 0%, #FF3D1F 100%);
    color: #fff;
    padding: 4px 14px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    box-shadow: 0 4px 14px rgba(255, 90, 44, 0.35);
    animation: gdRibbonBlink 2.5s ease-in-out infinite;
}

/* ── Heading ── */
.gd-heading {
    text-align: center;
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.15;
    margin: 0 0 3px;
    letter-spacing: -0.5px;
}
.gd-heading span { color: #FF5A2C; }

/* ── Subtitle / Timer Label ── */
.gd-timer-lbl {
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    margin: 0 0 5px;
    letter-spacing: 0.3px;
    text-align: center;
}

/* ── Timer ── */
.gd-timer-section {
    position: relative;
    max-width: 300px;
    margin: 0 auto 10px;
    background: linear-gradient(180deg, #ffffff 0%, #FFFDFB 100%);
    border: 1.5px dashed rgba(255, 90, 44, 0.2);
    border-radius: 14px;
    padding: 6px 14px;
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
    text-align: center;
    animation: gdTimerBlink 2.5s ease-in-out infinite;
}
.gd-timer-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.gd-tblock {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 46px;
}
.gd-tnum {
    font-size: 26px;
    font-weight: 800;
    color: #FF5A2C;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.8px;
}
.gd-tunit {
    font-size: 7.5px;
    font-weight: 700;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-top: 2px;
}
.gd-tsep {
    font-size: 22px;
    font-weight: 700;
    color: #FF5A2C;
    line-height: 1;
    padding-bottom: 4px;
    align-self: center;
}
.gd-sparkle-left, .gd-sparkle-right, .gd-card-sparkle {
    position: absolute;
    font-size: 15px;
    color: #FFB700;
    animation: gdTwinkle 2s ease-in-out infinite;
    pointer-events: none;
    line-height: 1;
}
.gd-sparkle-left {
    left: -10px;
    top: 55%;
    transform: translateY(-50%);
    animation-delay: 0.3s;
}
.gd-sparkle-right {
    right: -10px;
    top: 45%;
    transform: translateY(-50%);
    animation-delay: 0.7s;
}
.gd-card-sparkle {
    right: 10px;
    bottom: 10px;
    animation-delay: 1.1s;
    font-size: 16px;
}

@keyframes gdTwinkle {
    0%, 100% { opacity: 0.3; transform: scale(0.85); }
    50%       { opacity: 1;   transform: scale(1.15); }
}

@keyframes gdRibbonBlink {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 14px rgba(255, 90, 44, 0.35);
    }
    50% {
        transform: scale(1.04);
        box-shadow: 0 6px 20px rgba(255, 90, 44, 0.55);
    }
}

@keyframes gdTimerBlink {
    0%, 100% {
        box-shadow: 0 6px 20px rgba(255, 90, 44, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 90, 44, 0.2);
    }
    50% {
        box-shadow: 0 6px 20px rgba(255, 90, 44, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 90, 44, 0.45);
    }
}

/* ── Cards Outer ── */
.gd-cards-outer {
    position: relative;
    padding-right: 0 !important;
    margin-bottom: 8px;
}

/* ── Cards Grid ── */
.gd-cards-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

/* ── Card Base ── */
.gd-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 10px 10px 8px;
    border: 1.5px solid #E5E7EB;
    position: relative;
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
}
.gd-pre {
    background: #FFFCEB !important;
    border: 2px solid #F59E0B !important;
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15);
}

/* ── Card Header ── */
.gd-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 0;
}
.gd-hexagon-icon {
    position: relative;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.gd-hex-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
.gd-hex-inner-icon {
    position: relative;
    z-index: 2;
    font-size: 13px;
    color: #FF5A2C;
}
.gd-card-name {
    font-size: 14px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 2px;
    line-height: 1.2;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
}
.gd-card-tagline {
    font-size: 10.5px;
    margin: 0;
    font-weight: 500;
}
.gd-tagline-standard { color: #C25E3D; }
.gd-tagline-premium { color: #D97706; }

/* ── Great Value Badge ── */
.gd-great-val {
    margin: 4px auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 700;
    color: #FF5A2C;
    background: #FFF5F2;
    border: 1px solid rgba(255, 90, 44, 0.16);
    padding: 2px 8px;
    border-radius: 14px;
}
.gd-great-val i { font-size: 9px; }

/* ── 65% OFF Badge ── */
.gd-off-badge {
    position: absolute;
    left: 50%;
    top: -14px;
    transform: translateX(-50%);
    width: 54px;
    height: 54px;
    background: #ffffff;
    border: 2px solid #FF5A2C;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 6px 18px rgba(255, 90, 44, 0.16);
    z-index: 5;
    line-height: 1.1;
}
.gd-off-badge i    { font-size: 8px; color: #FF5A2C; margin-bottom: 1px; }
.gd-off-badge span { font-size: 6px; font-weight: 700; color: #C25E3D; text-transform: uppercase; }
.gd-off-badge strong { font-size: 14px; font-weight: 900; color: #FF5A2C; display: block; }

/* ── Pricing ── */
.gd-price-row {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 4px 0 2px;
}
.gd-original-price {
    font-size: 12px;
    color: #94A3B8;
    text-decoration: line-through;
    font-weight: 500;
}
.gd-save-tag {
    background: #DCFCE7;
    color: #16A34A;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 5px;
    border-radius: 6px;
    white-space: nowrap;
}
.gd-price-big {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 2px;
}
.gd-per {
    font-size: 11px;
    font-weight: 500;
    color: #64748B;
}
.gd-billed {
    font-size: 10px;
    color: #64748B;
    margin: 0 0 5px;
}

/* ── Feature List ── */
.gd-feats {
    list-style: none;
    padding: 0;
    margin: 0 0 auto;
    flex: 1;
}
.gd-feats li {
    font-size: 11.5px;
    color: #475569;
    margin-bottom: 3.5px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.gd-check-circle {
    width: 13px;
    height: 13px;
    background: #FFF0EB;
    border: 1px solid rgba(255, 90, 44, 0.15);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 2px;
    flex-shrink: 0;
}
.gd-check-circle i {
    font-size: 6px;
    color: #FF5A2C;
    line-height: 1;
}
.gd-feat-green-highlight {
    background: #E8F5E9 !important;
    border-radius: 6px;
    padding: 3px 6px !important;
    color: #1B5E20 !important;
    font-weight: 700;
}
.gd-check-green {
    background: #C8E6C9 !important;
    border: 1px solid rgba(76, 175, 80, 0.4) !important;
}
.gd-check-green i {
    color: #2E7D32 !important;
}
.gd-more-features {
    text-decoration: none !important;
    color: #16A34A !important;
    background: transparent !important;
    padding: 0 !important;
    border-radius: 0 !important;
    font-weight: 700;
    font-size: 11px !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap !important;
    transition: color 0.2s ease, transform 0.2s ease;
}
.gd-more-features:hover {
    color: #117835 !important;
    transform: translateX(2px);
    background: transparent !important;
}
.gd-plus-circle {
    width: 13px;
    height: 13px;
    background: #16A34A;
    border: none;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-left: 2px;
    flex-shrink: 0;
}
.gd-plus-circle i {
    font-size: 6px;
    color: #ffffff;
    line-height: 1;
}

/* ── Buttons ── */
.gd-btn-border, .gd-btn-fill {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 7px 10px;
    margin-top: 6px;
    border-radius: 7px;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.22s ease;
    cursor: pointer;
}
.gd-btn-border {
    border: 1.5px solid #FF5A2C;
    color: #FF5A2C;
    background: transparent;
}
.gd-btn-border:hover { background: #FFF5F2; color: #FF5A2C; text-decoration: none; }

.gd-select-premium-wrap {
    position: relative;
    margin-top: 8px;
}
.gd-select-premium-wrap .gd-btn-fill {
    margin-top: 0;
    animation: gdPremiumPulse 1.6s ease-in-out infinite;
    cursor: pointer !important;
}
.gd-select-premium-wrap .gd-btn-fill:hover {
    animation-play-state: paused;
}
.gd-click-hand {
    position: absolute;
    right: -6px;
    bottom: -12px;
    font-size: 20px;
    z-index: 10;
    pointer-events: none;
    animation: gdHandBounce 1.2s ease-in-out infinite;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.18));
    transform-origin: bottom center;
}
@keyframes gdHandBounce {
    0%, 100% { transform: translateY(0) rotate(-10deg); opacity: 1; }
    30%       { transform: translateY(-6px) rotate(0deg); opacity: 1; }
    60%       { transform: translateY(-2px) rotate(-5deg); opacity: 0.7; }
}
@keyframes gdPremiumPulse {
    0%, 100% {
        background: #FF5A2C;
        box-shadow: 0 4px 14px rgba(255, 90, 44, 0.32);
        transform: scale(1);
    }
    50% {
        background: #FF7340;
        box-shadow: 0 6px 20px rgba(255, 90, 44, 0.55), 0 0 0 4px rgba(255, 90, 44, 0.16);
        transform: scale(1.02);
    }
}
.gd-btn-fill {
    background: #FF5A2C;
    color: #fff;
    border: none;
    box-shadow: 0 4px 14px rgba(255, 90, 44, 0.32);
}
.gd-btn-fill:hover { background: #E04E22; color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(255,90,44,0.42); }

/* ── Footer / Dismiss ── */
.gd-footer { text-align: center; margin-top: 6px; }
.gd-dismiss {
    font-size: 11.5px;
    color: #64748B;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.18s;
}
.gd-dismiss:hover { color: #1E293B; text-decoration: underline; }

/* ===========================================
   RESPONSIVE
   =========================================== */
@media (max-width: 960px) {
    .gd-giftbox { display: none !important; }
}

@media (max-width: 767px) {
    .gd-dialog {
        width: calc(100% - 20px);
        max-height: 92vh;
    }
    .gd-box {
        padding: 10px 12px 10px;
        border-radius: 14px;
        max-height: 92vh;
    }
    .gd-heading { font-size: 20px !important; }
    .gd-timer-section { max-width: 280px; padding: 6px 10px; }
    .gd-tnum { font-size: 22px; }
    .gd-tsep { font-size: 18px; }
    .gd-cards-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    .gd-card { padding: 10px 8px 8px; }
    .gd-card-name { font-size: 13px !important; }
    .gd-price-big { font-size: 19px; }
    .gd-feats li { font-size: 11px; margin-bottom: 3px; }
    .gd-btn-border, .gd-btn-fill { padding: 6px 8px; font-size: 11px; margin-top: 6px; }
    .gd-off-badge {
        width: 48px;
        height: 48px;
        top: -14px;
    }
    .gd-off-badge strong { font-size: 13px; }
    .gd-off-badge i { font-size: 8px; }
    .gd-off-badge span { font-size: 5.5px; }
}

@media (max-height: 600px) {
    .gd-dialog { max-height: 96vh; }
    .gd-box { max-height: 96vh; padding: 8px 16px; }
    .gd-heading { font-size: 18px !important; margin-bottom: 2px; }
    .gd-ribbon { padding: 3px 10px; font-size: 9.5px; }
    .gd-timer-section { margin-bottom: 6px; padding: 4px 10px; }
    .gd-tnum { font-size: 20px; }
    .gd-feats li { font-size: 10.5px; margin-bottom: 2px; }
}
</style>

{{-- ============================
     SCRIPT
     ============================ --}}
<script>
(function() {
    'use strict';

    var overlay   = document.getElementById('gdOverlay');
    var backdrop  = document.getElementById('gdBackdrop');
    var closeBtn  = document.getElementById('gdClose');
    var dismissEl = document.getElementById('gdDismiss');

    /* ── Show / Hide ── */
    function openPopup() {
        if (!overlay) return;
        overlay.classList.add('gd-show');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }
    function closePopup() {
        if (!overlay) return;
        overlay.classList.remove('gd-show');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

    /* Auto-open after 30 s */
    var popupDelay = {{ (!empty($activePopup->delay) ? (int)$activePopup->delay : 5) * 1000 }};
    if (overlay) setTimeout(openPopup, popupDelay);

    /* Close handlers */
    if (closeBtn)  closeBtn.addEventListener('click', closePopup);
    if (backdrop)  backdrop.addEventListener('click', closePopup);
    if (dismissEl) dismissEl.addEventListener('click', function(e){ e.preventDefault(); closePopup(); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closePopup(); });

    // Lock background scroll when popup is open
    if (overlay) {
        overlay.addEventListener('wheel', function(e) {
            var box = document.getElementById('gdBox');
            if (box && box.contains(e.target)) return;
            e.preventDefault();
        }, { passive: false });
        overlay.addEventListener('touchmove', function(e) {
            var box = document.getElementById('gdBox');
            if (box && box.contains(e.target)) return;
            e.preventDefault();
        }, { passive: false });
    }

    /* Mobile-only scroll to plans */
    var standardBtn = document.getElementById('gdChooseStandard');
    var premiumBtn  = document.getElementById('gdUpgradePremium');

    function handlePlanClick(e, cardSelector) {
        if (window.innerWidth <= 991) {
            e.preventDefault();
            closePopup();

            // Switch to yearly tab if yearly tab exists
            var yearlyTab = document.getElementById('yearly-tab');
            if (yearlyTab) {
                yearlyTab.click();
            }

            // Scroll to the standard/premium card
            setTimeout(function() {
                // Target the card inside the activated yearly tab container to avoid matching the hidden monthly tab card
                var targetCard = document.querySelector('#tab-yearly ' + cardSelector);
                if (targetCard) {
                   var headerHeight = 100;

const mobileHeader = document.querySelector('.main-responsive-nav');
const desktopHeader = document.querySelector('.header-area');

if (mobileHeader && mobileHeader.getBoundingClientRect().height) {
    headerHeight = mobileHeader.getBoundingClientRect().height;
}

if (desktopHeader && desktopHeader.getBoundingClientRect().height) {
    headerHeight = Math.max(
        headerHeight,
        desktopHeader.getBoundingClientRect().height
    );
}
                    var scrollY = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                    var elementPosition = targetCard.getBoundingClientRect().top + scrollY;
                    var offsetPosition = elementPosition - headerHeight - 140; // 20px extra spacing to show the top badge/border clearly
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                } else {
                    var pricingSection = document.getElementById('home-pricing-section');
                    if (pricingSection) {
                        pricingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }, 300);
        }
    }

    var standardTriggers = document.querySelectorAll('#gdChooseStandard, .gd-btn-standard-trigger');
    var premiumTriggers  = document.querySelectorAll('#gdUpgradePremium, .gd-btn-premium-trigger');

    standardTriggers.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            handlePlanClick(e, '.pricing-card-v2.card-recommended');
        });
    });

    premiumTriggers.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            handlePlanClick(e, '.pricing-card-v2.card-best-value');
        });
    });

    /* ── Persistent Countdown ── */
    var SK = 'gdTimerEnd_v4_' + '{{ $activePopup->id ?? 1 }}';
    var dbEndDate = '{{ !empty($activePopup->end_date) ? $activePopup->end_date . " " . ($activePopup->end_time ?? "23:59:59") : "" }}';
    var endTs = null;
    if (dbEndDate) {
        var parsed = new Date(dbEndDate.replace(/-/g, '/')).getTime();
        if (!isNaN(parsed) && parsed > Date.now()) {
            endTs = parsed;
        }
    }
    if (!endTs) {
        endTs = parseInt(sessionStorage.getItem(SK), 10);
        if (!endTs || isNaN(endTs) || endTs < Date.now()) {
            endTs = Date.now() + (1*3600 + 30*60 + 22) * 1000;
            sessionStorage.setItem(SK, endTs);
        }
    }

    var elH = document.getElementById('gd-hours');
    var elM = document.getElementById('gd-mins');
    var elS = document.getElementById('gd-secs');

    function pad(n){ return n < 10 ? '0'+n : ''+n; }
    function tick(){
        var diff = Math.max(0, endTs - Date.now());
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000)  / 60000);
        var s = Math.floor((diff % 60000)    / 1000);
        if(elH) elH.textContent = pad(h);
        if(elM) elM.textContent = pad(m);
        if(elS) elS.textContent = pad(s);
    }
    tick();
    setInterval(tick, 1000);
})();
</script>

@endif