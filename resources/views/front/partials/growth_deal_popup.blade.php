
{{-- Year-End Growth Deal Popup v2 - Pixel Perfect Match --}}
<div class="gd-overlay" id="gdOverlay" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Year-End Growth Deal">
    <div class="gd-backdrop" id="gdBackdrop"></div>

    <div class="gd-dialog">

        {{-- 3D Gift Box (left side, overlapping modal edge) --}}
        <div class="gd-giftbox" aria-hidden="true">
            <img src="{{ asset('images/orange_percent_giftbox.png') }}?v=12" alt="Gift Box" style="width: 100%; height: auto;">
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

            {{-- Top Ribbon Badge --}}
            <div class="gd-ribbon-wrap">
                <div class="gd-ribbon">
                    <i class="fas fa-crown" style="margin-right: 6px; font-size: 11.5px; color: white;"></i>
                     LIMITED TIME OFFER
                </div>
            </div>


            {{-- Main Heading --}}
            <h2 class="gd-heading">⚡ FLASH SALE!
                <br><span>Limited Spots Available</span></h2>
                <p class="gd-timer-lbl">⏰ Offer Ending Soon</p>

            {{-- Countdown Timer --}}
            <div class="gd-timer-section">

                <span class="gd-sparkle-left">✦</span>
                <div class="gd-timer-row">
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-hours">01</span>
                        <span class="gd-tunit">HOURS</span>
                    </div>
                    <span class="gd-tsep">:</span>
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-mins">30</span>
                        <span class="gd-tunit">MINUTES</span>
                    </div>
                    <span class="gd-tsep">:</span>
                    <div class="gd-tblock">
                        <span class="gd-tnum" id="gd-secs">22</span>
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
                                <h3 class="gd-card-name"
                                >Ecom
                                Standard</h3>
                                <p class="gd-card-tagline gd-tagline-standard">Smart choice for growing brands</p>
                            </div>
                        </div>
                        <div class="gd-great-val">
                           🎉 Great Value
                        </div>
                        <div class="gd-price-row">
                            <span class="gd-original-price">₹14,283/year</span>
                            <span class="gd-save-tag">Save ₹9,284</span>
                        </div>
                        <div class="gd-price-big">₹3,999<span class="gd-per">/year</span></div>
                        <p class="gd-billed">Billed annually</p>
                        <ul class="gd-feats">
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Custom Domain </li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Unlimited Orders</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Custom Pages</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Inventory Management </li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Advanced Report</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Google Login</li>


                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Gst Billing.</li>
                            <li>
                                <a href="{{ route('front.pricing') }}?term=yearly" class="gd-more-features gd-btn-standard-trigger">
                                   More Features &nbsp;<span class="gd-plus-circle"><i class="fas fa-plus"></i></span>
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('front.pricing') }}?term=yearly" class="gd-btn-border" id="gdChooseStandard">Choose Standard &nbsp;→</a>
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
                                <h3 class="gd-card-name">
                                    Ecom Premium&nbsp;
                                    <!-- <span class="gd-most-loved"><i class="fas fa-heart"></i>&nbsp;Most Loved</span> -->
                                </h3>
                                <p class="gd-card-tagline gd-tagline-premium">
                                    Best choice for advanced features
                                </p>
                            </div>
                          
                        </div>
                        <div class="gd-great-val">
                            😍 Most Popular
                        </div>
                        <div class="gd-price-row" style="margin-top:16px;">
                            <span class="gd-original-price">₹28,569/year</span>
                            <span class="gd-save-tag">Save ₹18,570</span>
                        </div>
                        <div class="gd-price-big">₹8,999<span class="gd-per">/year</span></div>
                        <p class="gd-billed">Billed annually</p>
                        <ul class="gd-feats">
                            <li class="gd-feat-green-highlight"><span class="gd-check-circle gd-check-green"><i class="fas fa-check"></i></span>Free .in Domain</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>All Standard Features</li>

                            
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Premium Themes</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Staff Management</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Push Notification</li>
                            <li><span class="gd-check-circle"><i class="fas fa-check"></i></span>Payment Option</li>
                            <li>
                                <a href="{{ route('front.pricing') }}?term=yearly" class="gd-more-features gd-btn-premium-trigger">
                                    Advanced Features &nbsp;<span class="gd-plus-circle"><i class="fas fa-plus"></i></span>
                                </a>
                            </li>
                        </ul>
                        <div class="gd-select-premium-wrap">
                            <span class="gd-click-hand" aria-hidden="true">👆</span>
                            <a href="{{ route('front.pricing') }}?term=yearly" class="gd-btn-fill" id="gdUpgradePremium">Select Premium &nbsp;→</a>
                        </div>
                        <span class="gd-card-sparkle">✦</span>
                    </div>
                </div>

                {{-- 65% OFF Badge (outside premium card, right side) --}}
                <div class="gd-off-badge">
                    <i class="fas fa-fire"></i>
                    <span>Up to</span>
                    <strong>65%</strong>
                    <span>OFF</span>
                </div>
            </div>



            {{-- Dismiss --}}
            <div class="gd-footer">
                <a href="#" class="gd-dismiss" id="gdDismiss">
                No Thanks , Maybe Later
            </a>
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

/* ── Overlay ── */
.gd-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000001;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 16px; /* 60px space top and bottom for laptop views */
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.32s ease, visibility 0.32s ease;
    overflow-y: auto;
}
.gd-overlay.gd-show {
    opacity: 1;
    visibility: visible;
}

/* ── Backdrop ── */
.gd-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.52);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    cursor: pointer;
}

/* ── Dialog Wrapper (positions gift box + white box together) ── */
.gd-dialog {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 760px;
    /* On desktop, shift right slightly so gift box doesn't overflow viewport */
    margin-left: 60px;
    display: flex;
    flex-direction: column;
        /* height: 665px; */
}

/* ── 3D Gift Box ── */
.gd-giftbox {
    position: absolute;
    left: -90px;
    top: 24px;
    width: 148px;
    pointer-events: none;
    z-index: 10;
    animation: gdFloat 4s ease-in-out infinite;
    filter: drop-shadow(0 16px 24px rgba(255, 90, 30, 0.22));
}
.gd-giftbox img { width: 100%; height: auto; }

@keyframes gdFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-9px); }
}

/* ── White Box ── */
.gd-box {
    background: linear-gradient(180deg, #FFFFFF 0%, #FFFDFB 100%);
    border: 1.5px solid rgba(255, 90, 44, 0.12);
    border-radius: 22px;
    padding: 11px 36px 22px;
    box-shadow: 0 30px 80px rgba(255, 90, 44, 0.08), 0 10px 30px rgba(0, 0, 0, 0.04);
    position: relative;
    width: 100%;
    overflow-y: auto;
    overflow-x: visible;
    scrollbar-width: thin;
    scrollbar-color: #f0ece9 transparent;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.gd-box::-webkit-scrollbar { width: 3px; }
.gd-box::-webkit-scrollbar-thumb { background: #e2ded9; border-radius: 3px; }


/* ── Close Button ── */
.gd-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 34px;
    height: 34px;
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
    width: 90px;
    height: 90px;
    pointer-events: none;
    z-index: 1;
}
.gd-cel-left {
    top: 3%;
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
.gd-ribbon-wrap { text-align: center; margin-bottom: 14px; }
.gd-ribbon {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(90deg, #FF6B3D 0%, #FF3D1F 100%);
    color: #fff;
    padding: 8px 22px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.3px;
    text-transform: uppercase;
    box-shadow: 0 5px 16px rgba(255, 90, 44, 0.38);
    animation: gdRibbonBlink 2.5s ease-in-out infinite;
}

/* ── Heading ── */
.gd-heading {
    text-align: center;
    font-size: 34px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.18;
    margin: 0 0 10px;
    letter-spacing: -0.5px;
}
.gd-heading span { color: #FF5A2C; }

.gd-subtext {
    text-align: center;
    font-size: 14px;
    color: #64748B;
    line-height: 1.6;
    margin: 0 0 20px;
}

/* ── Timer ── */
.gd-timer-section {
    position: relative;
    max-width: 350px;
    margin: 0 auto 22px;
    background: linear-gradient(180deg, #ffffff 0%, #FFFDFB 100%);
    border: 2px dashed rgba(255, 90, 44, 0.16);
    border-radius: 20px;
    padding: 12px 20px 10px;
    box-shadow: 0 8px 30px rgba(255, 90, 44, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
    text-align: center;
    animation: gdTimerBlink 2.5s ease-in-out infinite;
}
.gd-timer-lbl {
    font-size: 15.5px;
    font-weight: 600;
    color: #475569;
    margin: 0 0 -6px;
    letter-spacing: 0.5px;
    text-align: center;
    margin-bottom: 10px;
}
.gd-timer-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
}
.gd-tblock {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 58px;
}
.gd-tnum {
    font-size: 35px;
    font-weight: 800;
    color: #FF5A2C;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    letter-spacing: -1.2px;
}
.gd-tunit {
    font-size: 8.5px;
    font-weight: 700;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 6px;
}
.gd-tsep {
    font-size: 34px;
    font-weight: 700;
    color: #FF5A2C;
    line-height: 1;
    padding-bottom: 12px;
    align-self: center;
}
.gd-sparkle-left, .gd-sparkle-right, .gd-card-sparkle {
    position: absolute;
    font-size: 16px;
    color: #FFB700;
    animation: gdTwinkle 2s ease-in-out infinite;
    pointer-events: none;
    line-height: 1;
}
.gd-sparkle-left {
    left: -12px;
    top: 55%;
    transform: translateY(-50%);
    animation-delay: 0.3s;
}
.gd-sparkle-right {
    right: -12px;
    top: 45%;
    transform: translateY(-50%);
    animation-delay: 0.7s;
}
.gd-card-sparkle {
    right: 12px;
    bottom: 12px;
    animation-delay: 1.1s;
    font-size: 18px;
}

@keyframes gdTwinkle {
    0%, 100% { opacity: 0.3; transform: scale(0.85); }
    50%       { opacity: 1;   transform: scale(1.15); }
}

@keyframes gdRibbonBlink {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 5px 16px rgba(255, 90, 44, 0.38);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 8px 24px rgba(255, 90, 44, 0.6);
    }
}

@keyframes gdTimerBlink {
    0%, 100% {
        box-shadow: 0 8px 30px rgba(255, 90, 44, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 90, 44, 0.16);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 8px 30px rgba(255, 90, 44, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 90, 44, 0.4);
        transform: scale(1.015);
    }
}

/* ── Cards Outer (holds grid + 65% badge) ── */
.gd-cards-outer {
    position: relative;
    padding-right: 0 !important;
    margin-bottom: 18px;
}

/* ── Cards Grid ── */
.gd-cards-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* ── Card Base ── */
.gd-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 22px 20px 20px;
    border: 1.5px solid #E5E7EB;
    position: relative;
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
}
.gd-pre {
    background: #FFFCEB !important;
    border: 2.5px solid #F59E0B !important;
    box-shadow: 0 6px 28px rgba(245, 158, 11, 0.15);
}

/* ── Card Header ── */
.gd-card-header {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 0;
}
.gd-hexagon-icon {
    position: relative;
    width: 48px;
    height: 48px;
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
    font-size: 18px;
    color: #FF5A2C;
}
.gd-card-name {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 3px;
    line-height: 1.2;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}
.gd-card-tagline {
    font-size: 15px;
    margin: 0;
    font-weight: 500;
}
.gd-tagline-standard {
    color: #C25E3D;
}
.gd-tagline-premium {
    color: #D97706;
}

/* ── Great Value Badge ── */
.gd-great-val {
        margin: 12px 0;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14.5px;
    font-weight: 700;
    color: #FF5A2C;
    background: #FFF5F2;
    border: 1px solid rgba(255, 90, 44, 0.16);
    padding: 4px 12px;
    border-radius: 20px;
    width: 178px;
    /* text-align: center; */
    margin: 7px auto;
}
.gd-great-val i { font-size: 11px; }

/* ── Most Loved Badge ── */
.gd-most-loved {
    background: #F59E0B !important;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 9px;
    border-radius: 20px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    vertical-align: middle;
}
.gd-most-loved i { font-size: 9px; }

/* ── 65% OFF Badge ── */
.gd-off-badge {
    position: absolute;
    left: 50%;
    top: -26px; /* Centered below clock, overlapping cards top border */
    transform: translateX(-50%);
    width: 86px;
    height: 86px;
    background: #ffffff;
    border: 2.5px solid #FF5A2C;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 10px 25px rgba(255, 90, 44, 0.14), 0 0 15px rgba(255, 90, 44, 0.15);
    z-index: 5;
    line-height: 1.15;
}
.gd-off-badge i    { font-size: 14px; color: #FF5A2C; margin-bottom: 2px; }
.gd-off-badge span { font-size: 9px; font-weight: 700; color: #C25E3D; text-transform: uppercase; }
.gd-off-badge strong { font-size: 24px; font-weight: 900; color: #FF5A2C; display: block; }

/* ── Pricing ── */
.gd-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 14px 0 4px;
}
.gd-original-price {
    font-size: 15.5px;
    color: #94A3B8;
    text-decoration: line-through;
    font-weight: 500;
}
.gd-save-tag {
    background: #DCFCE7;
    color: #16A34A;
    font-size: 12.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
    white-space: nowrap;
}
.gd-price-big {
    font-size: 36px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 3px;
}
.gd-per {
    font-size: 14px;
    font-weight: 500;
    color: #64748B;
}
.gd-billed {
    font-size: 12px;
    color: #64748B;
    margin: 0 0 14px;
}

/* ── Feature List ── */
.gd-feats {
    list-style: none;
    padding: 0;
    margin: 0 0 auto;
    flex: 1;
}
.gd-feats li {
    font-size: 15.5px;
    color: #475569;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.gd-check-circle {
    width: 17px;
    height: 17px;
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
    font-size: 8px;
    color: #FF5A2C;
    line-height: 1;
}
.gd-feat-green-highlight {
    background: #E8F5E9 !important;
    border-radius: 8px;
    padding: 6px 10px !important;
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
@media (max-width: 767px) {
    .gd-feat-green-highlight {
        padding: 4px 8px !important;
        border-radius: 6px;
    }
}
@media (max-width: 480px) {
    .gd-feat-green-highlight {
        padding: 3px 6px !important;
        border-radius: 4px;
    }
    /* ── Card Header ── */
.gd-card-header {
    gap: 6px;
}
}
.gd-more-features {
    text-decoration: none !important;
    color: #16A34A !important;
    background: transparent !important;
    padding: 0 !important;
    border-radius: 0 !important;
    font-weight: 700;
    font-size: 13.5px !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap !important;
    transition: color 0.2s ease, transform 0.2s ease;
}
.gd-more-features:hover {
    color: #117835 !important;
    transform: translateX(2px);
    background: transparent !important;
}
.gd-plus-circle {
    width: 17px;
    height: 17px;
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
    font-size: 8px;
    color: #ffffff;
    line-height: 1;
}

/* ── Buttons ── */
.gd-btn-border, .gd-btn-fill {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 12px 16px;
    margin-top: 16px;
    border-radius: 10px;
    font-size: 14px;
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
    margin-top: 16px;
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
    right: -10px;
    bottom: -18px;
    font-size: 26px;
    z-index: 10;
    pointer-events: none;
    animation: gdHandBounce 1.2s ease-in-out infinite;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.18));
    transform-origin: bottom center;
}
@keyframes gdHandBounce {
    0%, 100% { transform: translateY(0) rotate(-10deg); opacity: 1; }
    30%       { transform: translateY(-8px) rotate(0deg); opacity: 1; }
    60%       { transform: translateY(-2px) rotate(-5deg); opacity: 0.7; }
}
@keyframes gdPremiumPulse {
    0%, 100% {
        background: #FF5A2C;
        box-shadow: 0 4px 18px rgba(255, 90, 44, 0.32);
        transform: scale(1);
    }
    50% {
        background: #FF7340;
        box-shadow: 0 6px 28px rgba(255, 90, 44, 0.65), 0 0 0 5px rgba(255, 90, 44, 0.18);
        transform: scale(1.025);
    }
}
.gd-btn-fill {
    background: #FF5A2C;
    color: #fff;
    border: none;
    box-shadow: 0 4px 18px rgba(255, 90, 44, 0.32);
}
.gd-btn-fill:hover { background: #E04E22; color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 7px 24px rgba(255,90,44,0.42); }

/* ── Trust Row ── */
.gd-trust {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    background: #FFFBF9;
    border: 1px solid rgba(255, 90, 44, 0.1);
    border-radius: 12px;
    padding: 13px 8px;
    margin-bottom: 14px;
}
.gd-ti {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 8px;
    border-right: 1px solid #E2E8F0;
}
.gd-ti:last-child { border-right: none; }
.gd-ti-ico {
    width: 30px;
    height: 30px;
    background: #FFF0EB;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FF5A2C;
    font-size: 13px;
    flex-shrink: 0;
}
.gd-ti strong {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 2px;
}
.gd-ti span {
    display: block;
    font-size: 10px;
    color: #64748B;
    line-height: 1.3;
}

/* ── Footer / Dismiss ── */
.gd-footer { text-align: center; }
.gd-dismiss {
    font-size: 13.5px;
    color: #64748B;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: color 0.18s;
}
.gd-dismiss:hover { color: #1E293B; text-decoration: none; }

/* ===========================================
   RESPONSIVE
   =========================================== */

/* Hide gift box on screens too narrow to accommodate it */
@media (max-width: 960px) {
    .gd-dialog { margin: auto 0; }
    .gd-giftbox { display: none; }
}

/* Tablet adjustments */
@media (max-width: 767px) {

    .gd-dialog {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 760px;
    /* On desktop, shift right slightly so gift box doesn't overflow viewport */
    margin-left: 60px;
    display: flex;
    flex-direction: column;
        height: 665px;
}
    .gd-overlay { padding: 40px 12px; align-items: flex-start; }
    .gd-dialog { margin: 0 auto 60px; }
    .gd-box {
        padding: 20px 24px 14px;
        border-radius: 18px;
    }
    .gd-heading { font-size: 24px; }
    .gd-subtext { font-size: 12.5px; }
    .gd-timer-section { max-width: 325px; padding: 10px 14px; }
    .gd-timer-lbl { font-size: 13.5px; }
    .gd-tnum { font-size: 40px; }
    .gd-tsep { font-size: 24px; padding-bottom: 6px; }
    .gd-tblock { min-width: 42px; }
    .gd-timer-row { padding: 8px 12px 6px; gap: 8px; }
    .gd-sparkle-left, .gd-sparkle-right { display: none; }

    /* Show cards side-by-side (2 columns) */
    .gd-cards-outer { padding-right: 0 !important; margin-bottom: 12px; }
    .gd-cards-grid { grid-template-columns: 1fr 1fr; gap: 10px; }

    /* Adjust Card elements for tablet side-by-side */
    .gd-card { padding: 14px 12px 12px; }
    .gd-hexagon-icon { width: 38px; height: 38px; }
    .gd-hex-inner-icon { font-size: 14px; }
    .gd-card-name { font-size: 16px; }
    .gd-card-tagline { font-size: 11px; }
    .gd-great-val { font-size: 10px; padding: 2px 8px; margin: 8px 0; }
    .gd-price-row { margin: 8px 0 2px; gap: 6px; }
    .gd-original-price { font-size: 13.5px; }
    .gd-save-tag { font-size: 11px; padding: 1px 6px; }
    .gd-price-big { font-size: 24px; }
    .gd-billed { font-size: 11px; margin-bottom: 8px; }
    .gd-feats li { font-size: 13px; margin-bottom: 6px; gap: 6px; }
    .gd-check-circle { width: 15px; height: 15px; }
    .gd-check-circle i { font-size: 7px; }
    .gd-more-features { font-size: 13px; }
    .gd-plus-circle { width: 15px; height: 15px; }
    .gd-plus-circle i { font-size: 7px; }
    .gd-btn-border, .gd-btn-fill { padding: 8px 10px; font-size: 12px; margin-top: 8px; }

    /* Move 65% badge inside premium card area but styled for side-by-side */
    .gd-off-badge {
        position: absolute;
        left: 50%;
        top: -18px;
        right: auto;
        transform: translateX(-50%);
        width: 58px;
        height: 58px;
        border-width: 2px;
    }
    .gd-off-badge strong { font-size: 16px; }
    .gd-off-badge i { font-size: 10px; }
    .gd-off-badge span { font-size: 7.5px; }
    .gd-pre { margin-top: 0; } /* Reset top margin since it is side-by-side */

    /* Trust 2 columns */
    .gd-trust { grid-template-columns: 1fr 1fr; gap: 10px; padding: 10px; }
    .gd-ti { border-right: none !important; border-bottom: 1px solid #E2E8F0; padding: 6px 4px; }
    .gd-ti:nth-child(odd)  { border-right: 1px solid #E2E8F0 !important; }
    .gd-ti:nth-child(3),
    .gd-ti:nth-child(4)    { border-bottom: none; }
}

/* Small mobile */
@media (max-width: 480px) {
    .gd-overlay { padding: 19px 10px; }
    .gd-box {
        padding: 14px 14px 10px;
        border-radius: 14px;
    }
    .gd-heading { font-size: 22px !important;}
    .gd-ribbon { font-size: 10px; padding: 5px 12px; letter-spacing: 0.8px; }
    .gd-timer-section { max-width: 328px; padding: 8px 10px; }
    .gd-timer-lbl { font-size: 17.5px; font-weight: 700;         text-align: center;
        margin-bottom: 4px;}
    .gd-tnum { font-size: 30px; }
    .gd-tsep { font-size: 18px; }
    .gd-tblock { min-width: 32px; }
    .gd-tunit { font-size: 7px; }
    .gd-timer-row { gap: 4px; padding: 6px 8px 4px; }

    .gd-cards-outer { padding-right: 10px; }
    .gd-cards-grid { gap: 6px; }
    .gd-card { padding: 10px 8px 8px; }
    .gd-hexagon-icon { width: 38px; height: 38px; }
    .gd-hex-inner-icon { font-size: 11px; }
    .gd-card-name { font-size: 13px !important;}
    .gd-card-tagline { font-size: 11px; }
    .gd-great-val { font-size: 9px;
        padding: 1px 6px;
        margin: 4px 0;
        width: 64%;
        margin: 10px auto;
    }
    .gd-original-price { font-size: 11.5px; }
    .gd-save-tag { font-size: 10px; padding: 1px 4px; }
    .gd-price-big { font-size: 18px; }
    .gd-billed { font-size: 10px; margin-bottom: 6px; }
    .gd-feats li { font-size: 11.5px; margin-bottom: 4px; gap: 4px; }
    .gd-check-circle { width: 12px; height: 12px; }
    .gd-check-circle i { font-size: 5.5px; }
    .gd-more-features { font-size: 11.5px; }
    .gd-plus-circle { width: 12px; height: 12px; }
    .gd-plus-circle i { font-size: 5.5px; }
    .gd-btn-border, .gd-btn-fill { padding: 6px 8px; font-size: 10.5px; margin-top: 6px; }

    .gd-off-badge {
        left: 50%;
        top: -21px;
        right: auto;
        transform: translateX(-50%);
        width: 44px;
        height: 44px;
        border-width: 1.5px;
    }
    .gd-off-badge strong { font-size: 12px; }
    .gd-off-badge i { font-size: 8px; }
    .gd-off-badge span { font-size: 6px; }

    .gd-trust { grid-template-columns: 1fr 1fr; font-size: 8.5px; }
}

/* Viewport height-based layout scaling to prevent vertical scrollbars on desktop */
@media (min-width: 768px) and (max-height: 900px) {
    .gd-dialog {
        transform: scale(0.88);
        transform-origin: center center;
    }
}
@media (min-width: 768px) and (max-height: 820px) {
    .gd-dialog {
        transform: scale(0.80);
        transform-origin: center center;
    }
}
@media (min-width: 768px) and (max-height: 750px) {
    .gd-dialog {
        transform: scale(0.72);
        transform-origin: center center;
    }
}
@media (min-width: 768px) and (max-height: 680px) {
    .gd-dialog {
        transform: scale(0.64);
        transform-origin: center center;
    }
}
@media (min-width: 768px) and (max-height: 600px) {
    .gd-dialog {
        transform: scale(0.56);
        transform-origin: center center;
    }
}
@media (min-width: 768px) and (max-height: 520px) {
    .gd-dialog {
        transform: scale(0.48);
        transform-origin: center center;
    }
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
    }
    function closePopup() {
        if (!overlay) return;
        overlay.classList.remove('gd-show');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    /* Auto-open after 30 s */
    if (overlay) setTimeout(openPopup, 5000);

    /* Close handlers */
    if (closeBtn)  closeBtn.addEventListener('click', closePopup);
    if (backdrop)  backdrop.addEventListener('click', closePopup);
    if (dismissEl) dismissEl.addEventListener('click', function(e){ e.preventDefault(); closePopup(); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closePopup(); });

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
    var SK = 'gdTimerEnd_v3';
    var endTs = parseInt(sessionStorage.getItem(SK), 10);
    if (!endTs || isNaN(endTs) || endTs < Date.now()) {
        /* 1 hour 30m 22s */
        endTs = Date.now() + (1*3600 + 30*60 + 22) * 1000;
        sessionStorage.setItem(SK, endTs);
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
