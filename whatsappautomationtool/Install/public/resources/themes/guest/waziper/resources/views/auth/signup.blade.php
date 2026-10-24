<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@php
    $captcha = Captcha::render();
    $languages = Language::getLanguages();
    $currentLang = app()->getLocale();
@endphp

<style>
    /* Reset & Container */
    .wb-signup-wrapper {
        min-height: 100vh;
        background-color: #edf7ea;
        background-image: radial-gradient(at 10% 20%, rgba(34, 197, 94, 0.2) 0px, transparent 50%), radial-gradient(at 90% 80%, rgba(213, 240, 198, 0.6) 0px, transparent 50%);
        padding: 48px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        width: 100%;
    }
    .wb-signup-container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        box-sizing: border-box;
    }
    .wb-signup-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #dcf2e4;
        padding: 36px 32px;
        box-shadow: 0 15px 35px rgba(31, 122, 69, 0.07);
        box-sizing: border-box;
        width: 100%;
    }
    .wb-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .wb-input-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #2e4337;
        margin-bottom: 6px;
    }
    .wb-input {
        width: 100%;
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #cfe2d6;
        background-color: #fbfdfb;
        padding: 0 14px;
        font-size: 14px;
        color: #16231b;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .wb-input:focus {
        border-color: #1f7a45;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(31, 122, 69, 0.12);
    }
    .wb-card-section {
        background: #f6fcf8;
        border: 1.5px solid #d7ece0;
        border-radius: 16px;
        padding: 16px 18px;
        margin-bottom: 18px;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .wb-otp-flex {
        display: flex !important;
        gap: 10px !important;
        align-items: center !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .wb-btn-send {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        height: 48px !important;
        min-height: 48px !important;
        padding: 0 20px !important;
        background: linear-gradient(135deg, #22c55e 0%, #15803d 100%) !important;
        color: #ffffff !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        border: none !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        box-shadow: 0 4px 12px rgba(31, 122, 69, 0.22) !important;
        transition: all 0.2s ease !important;
        flex-shrink: 0 !important;
        min-width: 120px !important;
        box-sizing: border-box !important;
    }
    .wb-btn-send:hover {
        background: linear-gradient(135deg, #16a34a 0%, #14532d 100%) !important;
        transform: translateY(-1px);
    }
    .wb-btn-send:active {
        transform: translateY(0);
    }
    .wb-btn-send:disabled {
        opacity: 0.65 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }
    .wb-badge-verified {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #dcfce7;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #86efac;
    }
    .wb-btn-submit {
        width: 100% !important;
        height: 50px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: linear-gradient(135deg, #22c55e 0%, #15803d 100%) !important;
        color: #ffffff !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border-radius: 14px !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 10px 24px rgba(31, 122, 69, 0.26) !important;
        transition: all 0.2s ease !important;
        box-sizing: border-box !important;
    }
    .wb-btn-submit:hover {
        background: linear-gradient(135deg, #16a34a 0%, #14532d 100%) !important;
        box-shadow: 0 14px 28px rgba(31, 122, 69, 0.32) !important;
    }

    /* Mobile Responsive */
    @media (max-width: 640px) {
        .wb-signup-wrapper {
            padding: 24px 12px !important;
        }
        .wb-signup-card {
            padding: 24px 16px !important;
            border-radius: 16px !important;
        }
        .wb-form-grid {
            grid-template-columns: 1fr !important;
        }
        .wb-otp-flex {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .wb-btn-send {
            width: 100% !important;
        }
    }
</style>

<div class="wb-signup-wrapper">
    <div class="wb-signup-container">
        <!-- Top branding -->
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ url('') }}" style="display: inline-flex; align-items: center; gap: 12px; text-decoration: none;">
                <span style="display: flex; height: 44px; width: 44px; align-items: center; justify-content: center; border-radius: 50%; border: 1.5px solid #cae8d3; background: #ffffff; color: #1f7a45; font-size: 22px; box-shadow: 0 6px 16px rgba(31,122,69,0.1);">
                    <i class="fa-brands fa-whatsapp"></i>
                </span>
                <div>
                    <div style="font-size: 24px; font-weight: 800; color: #16231b; line-height: 1.1; letter-spacing: -0.5px;">Whatsappbulk</div>
                    <div style="font-size: 12px; color: #759081; font-weight: 500;">{{ __("WhatsApp Marketing Tool") }}</div>
                </div>
            </a>

            @if($languages->isNotEmpty())
                <div class="dropdown dropdown-hover dropdown-end" style="position: relative;">
                    <div tabindex="0" style="display: flex; height: 38px; align-items: center; gap: 8px; border-radius: 20px; border: 1px solid #d9e9de; background: #ffffff; padding: 0 14px; color: #22352b; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <i class="fa-solid fa-globe"></i>
                        <span>{{ strtoupper($currentLang) }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                    </div>
                    <ul tabindex="0" class="dropdown-content" style="position: absolute; right: 0; z-index: 999; margin-top: 8px; min-width: 170px; border-radius: 16px; border: 1px solid #dcfce7; background: #ffffff; padding: 8px; box-shadow: 0 12px 30px rgba(0,0,0,0.1); list-style: none;">
                        @foreach($languages as $language)
                            <li>
                                <a href="{{ url('lang/' . $language->code) }}" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-size: 13px; {{ $currentLang == $language->code ? 'background:#1f7a45;color:#ffffff;font-weight:600;' : 'color:#22352b;font-weight:500;' }}">
                                    @if($language->icon)
                                        <span style="font-size: 14px;"><i class="{{ $language->icon }}"></i></span>
                                    @endif
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Main Centered Signup Card -->
        <div class="wb-signup-card">
            <div style="text-align: center; margin-bottom: 24px;">
                <span style="display: inline-block; border-radius: 20px; background: #effaf2; padding: 4px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; color: #1f7a45; margin-bottom: 10px;">
                    {{ __("Create Workspace") }}
                </span>
                <h1 style="margin: 0 0 8px 0; font-size: 26px; font-weight: 800; color: #16231b; letter-spacing: -0.5px;">{{ __("Create your account") }}</h1>
                <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #698174;">
                    {{ __("Sign up with your mobile number and email. Verification required to secure your workspace.") }}
                </p>
            </div>

            <form class="actionForm" id="signup-form" action="{{ module_url('do_signup') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Full Name & Username -->
                <div class="wb-form-grid" style="margin-bottom: 16px;">
                    <div>
                        <label for="fullname" class="wb-input-label">{{ __("Full Name") }}</label>
                        <input type="text" id="fullname" name="fullname" class="wb-input" placeholder="{{ __('Enter your full name') }}" required>
                    </div>
                    <div>
                        <label for="username" class="wb-input-label">{{ __("Username") }}</label>
                        <input type="text" id="username" name="username" class="wb-input" placeholder="{{ __('Choose a username') }}" required>
                    </div>
                </div>

                <!-- WhatsApp Mobile Number Section -->
                <div class="wb-card-section">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label for="phone" style="margin: 0; font-size: 13px; font-weight: 700; color: #1f7a45; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i>
                            <span>{{ __("WhatsApp Mobile Number") }}</span>
                        </label>
                        <span id="wa-verified-badge" class="wb-badge-verified" style="display: none;">
                            <i class="fa-solid fa-circle-check"></i> {{ __("Verified") }}
                        </span>
                    </div>

                    <div class="wb-otp-flex">
                        <div style="display: flex; flex: 1; min-width: 0; border: 1.5px solid #cfe2d6; border-radius: 12px; background: #ffffff; overflow: hidden; box-sizing: border-box;">
                            <select id="country_code" name="country_code" style="height: 48px; border: none; border-right: 1px solid #e5e7eb; background: #f9fafb; padding: 0 10px; font-size: 13px; font-weight: 600; outline: none; cursor: pointer; max-width: 135px; flex-shrink: 0;">
                                <option value="+91" selected>🇮🇳 +91 (IN)</option>
                                <option value="+1">🇺🇸 +1 (US)</option>
                                <option value="+44">🇬🇧 +44 (UK)</option>
                                <option value="+971">🇦🇪 +971 (AE)</option>
                                <option value="+966">🇸🇦 +966 (SA)</option>
                                <option value="+65">🇸🇬 +65 (SG)</option>
                                <option value="+60">🇲🇾 +60 (MY)</option>
                                <option value="+61">🇦🇺 +61 (AU)</option>
                                <option value="+49">🇩🇪 +49 (DE)</option>
                                <option value="+33">🇫🇷 +33 (FR)</option>
                                <option value="+92">🇵🇰 +92 (PK)</option>
                                <option value="+880">🇧🇩 +880 (BD)</option>
                                <option value="+977">🇳🇵 +977 (NP)</option>
                                <option value="+94">🇱🇰 +94 (LK)</option>
                                <option value="+62">🇮🇩 +62 (ID)</option>
                                <option value="+63">🇵🇭 +63 (PH)</option>
                                <option value="+86">🇨🇳 +86 (CN)</option>
                                <option value="+81">🇯🇵 +81 (JP)</option>
                                <option value="+55">🇧🇷 +55 (BR)</option>
                                <option value="+27">🇿🇦 +27 (ZA)</option>
                                <option value="+234">🇳🇬 +234 (NG)</option>
                            </select>
                            <input type="tel" id="phone" name="phone" style="height: 48px; border: none; padding: 0 14px; font-size: 14px; width: 100%; min-width: 0; outline: none; background: transparent; box-sizing: border-box; color: #16231b;" placeholder="{{ __('Mobile number (e.g. 9876543210)') }}" required>
                        </div>
                        <button type="button" id="btn-send-wa-otp" class="wb-btn-send">
                            <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i>
                            <span>{{ __("Send OTP") }}</span>
                        </button>
                    </div>

                    <div id="wa-otp-msg" style="margin-top: 6px; font-size: 12px; font-weight: 600; min-height: 16px;"></div>

                    <!-- Inline WhatsApp OTP verification box -->
                    <div id="wa-otp-box" style="display: none; margin-top: 12px; padding: 14px; background: #ffffff; border: 1.5px solid #bbf7d0; border-radius: 14px;">
                        <div style="font-size: 13px; color: #166534; font-weight: 600; margin-bottom: 8px;">
                            <i class="fa-solid fa-key" style="margin-right: 4px;"></i> {{ __("Enter the 6-digit verification code sent to your WhatsApp number:") }}
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <input type="text" id="wa-otp-input" maxlength="6" style="height: 42px; width: 140px; text-align: center; font-family: monospace; font-size: 18px; font-weight: 800; letter-spacing: 4px; border: 1.5px solid #1f7a45; border-radius: 10px; outline: none; background: #ffffff; color: #16231b;" placeholder="••••••">
                            <button type="button" id="btn-verify-wa-otp" style="height: 42px; padding: 0 18px; background-color: #1f7a45; color: #ffffff !important; border: none; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 6px rgba(31,122,69,0.2);">
                                {{ __("Verify OTP") }}
                            </button>
                            <button type="button" id="btn-resend-wa-otp" style="display: none; background: transparent; border: none; color: #1f7a45; text-decoration: underline; font-size: 12px; font-weight: 600; cursor: pointer;">
                                {{ __("Resend Code") }}
                            </button>
                            <span id="wa-timer" style="font-size: 12px; color: #6b7280; font-family: monospace; font-weight: 600; margin-left: auto;"></span>
                        </div>
                    </div>
                </div>

                <!-- Email Address Section (Standard Input without OTP) -->
                <div class="wb-card-section">
                    <label for="email" style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #1f7a45; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-envelope" style="font-size: 15px;"></i>
                        <span>{{ __("Email Address") }} <span style="color: #dc2626;">*</span></span>
                    </label>
                    <input type="email" id="email" name="email" class="wb-input" placeholder="{{ __('Enter your email address') }}" required>
                </div>

                <!-- Password & Confirm Password with Eye Toggle -->
                <div class="wb-form-grid" style="margin-bottom: 16px;">
                    <div>
                        <label for="password" class="wb-input-label">{{ __("Password") }}</label>
                        <div style="position: relative; width: 100%;">
                            <input type="password" id="password" name="password" class="wb-input" style="padding-right: 44px;" placeholder="{{ __('Enter your password') }}" required>
                            <button type="button" class="btn-toggle-password" data-target="password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #759081; font-size: 15px; padding: 6px; display: flex; align-items: center; justify-content: center; outline: none;" tabindex="-1" title="{{ __('Toggle password visibility') }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="wb-input-label">{{ __("Confirm Password") }}</label>
                        <div style="position: relative; width: 100%;">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="wb-input" style="padding-right: 44px;" placeholder="{{ __('Confirm your password') }}" required>
                            <button type="button" class="btn-toggle-password" data-target="password_confirmation" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #759081; font-size: 15px; padding: 6px; display: flex; align-items: center; justify-content: center; outline: none;" tabindex="-1" title="{{ __('Toggle password visibility') }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Timezone -->
                <div style="margin-bottom: 16px;">
                    <label for="timezone" class="wb-input-label">{{ __("Timezone") }}</label>
                    <select id="timezone" name="timezone" class="wb-input" style="cursor: pointer;" required>
                        <option value="">{{ __("Select your timezone") }}</option>
                        @foreach(timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}" {{ (old('timezone') == $tz || $tz === 'Asia/Kolkata') ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>

                @if($captcha)
                    <div style="padding-top: 4px; margin-bottom: 14px;">{!! $captcha !!}</div>
                @endif

                                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- Plan Selector & Membership Tier -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                <div class="wb-card-section" style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <label style="margin: 0; font-size: 13px; font-weight: 700; color: #1f7a45; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-crown" style="font-size: 15px;"></i>
                            <span>{{ __("Select Membership Plan") }}</span>
                        </label>
                        <span id="selected-plan-badge" style="background: #e8f5e9; color: #1b5e20; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 20px; border: 1px solid #bbf7d0;">
                            Premium Plan • ₹89 / year
                        </span>
                    </div>

                    <!-- Hidden inputs for selected plan & payment details -->
                    <input type="hidden" name="plan" id="input_selected_plan" value="premium">
                    <input type="hidden" name="plan_price" id="input_selected_price" value="89">
                    <input type="hidden" name="plan_name" id="input_selected_plan_name" value="Whatsappbulk Premium">
                    <input type="hidden" name="payment_method" id="input_payment_method" value="upi">
                    <input type="hidden" name="razorpay_payment_id" id="hidden_razorpay_payment_id" value="">

                    <!-- Plan Selection Grid -->
                    <div style="display: grid; grid-template-columns: repeat({{ (isset($plans) && count($plans) > 0) ? count($plans) : 3 }}, 1fr); gap: 10px;" id="plan-selection-grid">
                        @if(isset($plans) && count($plans) > 0)
                            @foreach($plans as $p)
                                @php
                                    $pPrice = (float)$p->price;
                                    $isFree = !empty($p->free_plan) || $pPrice == 0;
                                    $pKey = strtolower($p->name);
                                    $planIdentifier = $p->id_secure ?: ($p->id ?: $pKey);
                                    $isDefault = ($planParam && (strtolower($planParam) == $pKey || $planParam == $p->id || $planParam == $p->id_secure)) || (!$planParam && ($pKey == 'premium' || $loop->last));
                                @endphp
                                <div class="plan-option-card {{ $isDefault ? 'active' : '' }}" 
                                     data-plan="{{ $planIdentifier }}" 
                                     data-price="{{ $pPrice }}" 
                                     data-name="{{ $p->name }}" 
                                     data-free="{{ $isFree ? '1' : '0' }}" 
                                     style="position: relative; border: 2px solid {{ $isDefault ? '#1f7a45' : '#e5e7eb' }}; border-radius: 14px; padding: 14px 10px; cursor: pointer; text-align: center; background: {{ $isDefault ? '#f0fdf4' : '#ffffff' }}; transition: all 0.2s ease;">
                                    @if(str_contains($pKey, 'prem') || $loop->last)
                                        <span style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #1f7a45; color: #ffffff; font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Best Value</span>
                                    @endif
                                    <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: {{ $isFree ? '#6b7280' : (str_contains($pKey, 'stand') ? '#ff5500' : '#1f7a45') }}; margin-bottom: 4px;">{{ $p->name }}</div>
                                    <div style="font-size: 22px; font-weight: 900; color: #111827; line-height: 1.1;">₹{{ $pPrice }}</div>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">{{ $isFree ? 'Free / Year' : 'Billed Yearly' }}</div>
                                </div>
                            @endforeach
                        @else
                            <!-- Starter Plan -->
                            <div class="plan-option-card" data-plan="starter" data-price="0" data-name="Whatsappbulk Starter" data-free="1" style="position: relative; border: 2px solid #e5e7eb; border-radius: 14px; padding: 14px 10px; cursor: pointer; text-align: center; background: #ffffff; transition: all 0.2s ease;">
                                <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 4px;">Starter</div>
                                <div style="font-size: 22px; font-weight: 900; color: #111827; line-height: 1.1;">₹0</div>
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Free / Year</div>
                                <div style="font-size: 10px; color: #059669; font-weight: 600; margin-top: 6px;">10k msgs/mo</div>
                            </div>

                            <!-- Standard Plan -->
                            <div class="plan-option-card" data-plan="standard" data-price="39" data-name="Whatsappbulk Standard" data-free="0" style="position: relative; border: 2px solid #e5e7eb; border-radius: 14px; padding: 14px 10px; cursor: pointer; text-align: center; background: #ffffff; transition: all 0.2s ease;">
                                <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #ff5500; margin-bottom: 4px;">Standard</div>
                                <div style="font-size: 22px; font-weight: 900; color: #111827; line-height: 1.1;">₹39</div>
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Billed Yearly</div>
                                <div style="font-size: 10px; color: #ff5500; font-weight: 600; margin-top: 6px;">100k msgs/mo</div>
                            </div>

                            <!-- Premium Plan -->
                            <div class="plan-option-card active" data-plan="premium" data-price="89" data-name="Whatsappbulk Premium" data-free="0" style="position: relative; border: 2px solid #1f7a45; border-radius: 14px; padding: 14px 10px; cursor: pointer; text-align: center; background: #f0fdf4; transition: all 0.2s ease;">
                                <span style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #1f7a45; color: #ffffff; font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Best Value</span>
                                <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #1f7a45; margin-bottom: 4px;">Premium</div>
                                <div style="font-size: 22px; font-weight: 900; color: #111827; line-height: 1.1;">₹89</div>
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Billed Yearly</div>
                                <div style="font-size: 10px; color: #1f7a45; font-weight: 600; margin-top: 6px;">500k msgs/mo</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- Payment Gateway Section (Only shown for Paid Plans: ₹39 / ₹89) -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                <div id="payment-gateway-section" style="margin-bottom: 20px; border: 1.5px solid #bbf7d0; background: #fcfdfd; border-radius: 18px; padding: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                        <span style="font-size: 13px; font-weight: 800; color: #166534; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-credit-card me-1"></i> {{ __("Payment Method") }}
                        </span>
                        <span id="payment-amount-display" style="font-size: 14px; font-weight: 900; color: #1f7a45;">₹89.00</span>
                    </div>

                    <!-- Payment Method Tabs (UPI QR vs Razorpay) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px;">
                        <button type="button" id="tab-pay-upi" class="btn-pay-tab active" data-tab="upi" onclick="window.switchPayTab('upi')" style="padding: 10px 12px; border-radius: 12px; border: 2px solid #1f7a45; background: #1f7a45; color: #ffffff; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;">
                            <i class="fa-solid fa-qrcode"></i> <span>UPI / QR Code</span>
                        </button>
                        <button type="button" id="tab-pay-razorpay" class="btn-pay-tab" data-tab="razorpay" onclick="window.switchPayTab('razorpay')" style="padding: 10px 12px; border-radius: 12px; border: 2px solid #e5e7eb; background: #ffffff; color: #374151; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;">
                            <i class="fa-solid fa-credit-card"></i> <span>Razorpay Online</span>
                        </button>
                    </div>

                    <!-- 1. UPI Payment Box (Identical to Website Builder) -->
                    <div id="box-method-upi" style="display: block; text-align: center; background: #ffffff; border: 1px solid #d1fae5; border-radius: 14px; padding: 16px;">
                        <span style="display: inline-block; background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Scan & Pay via Any UPI App
                        </span>
                        <h6 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 800; color: #111827;">Whatsappbulk Subscription</h6>

                        <!-- Dynamic QR Code Container -->
                        <div style="margin: 8px auto 12px auto; display: inline-block; padding: 8px; background: #ffffff; border: 1.5px solid #e5e7eb; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
                            <img id="dynamic-upi-qr" src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode('upi://pay?pa=launchshop@ybl&pn=Whatsappbulk&am=89.00&cu=INR&tn=Whatsappbulk%20Premium%20Subscription') }}" alt="UPI QR Code" style="width: 190px; height: 190px; object-fit: contain; display: block;">
                            <div style="margin-top: 8px; text-align: center;">
                                <span id="upi-amount-badge" style="display: inline-block; background: #e8f5e9; color: #1b5e20; font-size: 13px; font-weight: 800; padding: 4px 14px; border-radius: 14px;">
                                    Amount to Pay: ₹89
                                </span>
                            </div>
                        </div>

                        <!-- UPI ID Display with Copy Button -->
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px;">
                            <span style="font-size: 12px; font-weight: 700; color: #6b7280;">UPI ID:</span>
                            <code style="font-family: monospace; font-size: 14px; font-weight: 800; color: #111827; background: #f3f4f6; padding: 4px 8px; border-radius: 6px; border: 1px solid #e5e7eb;" id="text-upi-vpa">launchshop@ybl</code>
                            <button type="button" id="btn-copy-upi" onclick="window.copyUpiId()" style="padding: 4px 10px; font-size: 12px; font-weight: 700; color: #1f7a45; background: #ffffff; border: 1px solid #1f7a45; border-radius: 14px; cursor: pointer;">
                                <i class="fa-regular fa-copy me-1"></i> {{ __("Copy") }}
                            </button>
                        </div>

                        <p style="margin: 0 0 14px 0; font-size: 12px; color: #6b7280; line-height: 1.4;">
                            {{ __("Scan QR with PhonePe, Google Pay, Paytm, or BHIM. The amount will display automatically. After payment, enter your 12-digit UTR below:") }}
                        </p>

                        <!-- UTR Number Input -->
                        <div style="text-align: left; margin-bottom: 12px;">
                            <label for="utr_number" style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; display: block;">
                                {{ __("UPI UTR / Transaction Reference ID") }} <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" id="utr_number" name="utr_number" class="wb-input" style="height: 44px; font-family: monospace; font-size: 14px;" placeholder="e.g. 427819028341" maxlength="30">
                            <div id="utr-error-msg" style="display: none; color: #dc2626; font-size: 12px; font-weight: 600; margin-top: 4px;">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ __("Please enter your 12-digit UPI UTR number.") }}
                            </div>
                        </div>

                        <!-- Payment Proof Upload (Screenshot / Document / Receipt) -->
                        <div style="text-align: left; margin-bottom: 12px;">
                            <label style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fa-solid fa-receipt text-success me-1"></i> {{ __("Payment Proof / Screenshot") }}</span>
                                <span style="font-size: 11px; font-weight: 600; color: #16a34a;">{{ __("Image or PDF") }}</span>
                            </label>
                            <div id="proof-dropzone" style="position: relative; border: 1.5px dashed #86efac; border-radius: 12px; padding: 12px 14px; background: #f0fdf4; text-align: center; cursor: pointer; transition: all 0.2s ease;" onclick="document.getElementById('payment_proof').click()">
                                <input type="file" id="payment_proof" name="payment_proof" accept="image/png,image/jpeg,image/jpg,image/webp,application/pdf" style="display: none;" onchange="window.handleProofSelect(this)">
                                <div id="proof-default-state">
                                    <i class="fa-solid fa-cloud-arrow-up text-success fs-20 mb-1 d-block"></i>
                                    <div style="font-size: 12px; font-weight: 700; color: #15803d;">{{ __("Click to upload payment screenshot or document") }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ __("JPG, PNG, WEBP or PDF (up to 5MB)") }}</div>
                                </div>
                                <div id="proof-preview-state" style="display: none; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fa-solid fa-file-circle-check text-success fs-18"></i>
                                    <span id="proof-preview-filename" style="font-size: 12px; font-weight: 700; color: #15803d; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                    <button type="button" class="btn btn-sm text-danger p-0 ms-1" onclick="window.clearProofSelect(event)" title="{{ __('Remove') }}"><i class="fa-solid fa-circle-xmark fs-16"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Razorpay Payment Box -->
                    <div id="box-method-razorpay" style="display: none; text-align: center; background: #ffffff; border: 1.5px solid #bfdbfe; border-radius: 14px; padding: 20px;">
                        <span style="display: inline-block; background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Online Instant Checkout
                        </span>
                        <h6 style="margin: 0 0 8px 0; font-size: 15px; font-weight: 800; color: #111827;">Pay via Razorpay Secure Gateway</h6>
                        <p style="margin: 0 0 14px 0; font-size: 12px; color: #6b7280; line-height: 1.5;">
                            {{ __("Supports Credit/Debit Cards, NetBanking, UPI, and Digital Wallets.") }}
                        </p>
                        <div style="padding: 12px; background: #eff6ff; border-radius: 10px; border: 1px dashed #93c5fd; font-size: 12px; color: #1e40af; font-weight: 600;">
                            <i class="fa-solid fa-shield-halved me-1"></i> {{ __("Submitting signup will redirect you to checkout.youverse.in to complete the Razorpay payment.") }}
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <label for="accep_terms" style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #5f7568; cursor: pointer; margin-bottom: 20px;">
                    <input id="accep_terms" name="accep_terms" type="checkbox" value="1" required style="margin-top: 3px; accent-color: #1f7a45; height: 16px; width: 16px;">
                    <span>
                        {{ __("I agree to the") }}
                        <a style="font-weight: 600; color: #1f7a45; text-decoration: underline;" href="{{ url('terms-of-service') }}" target="_blank">{{ __("Terms & Conditions") }}</a>
                    </span>
                </label>

                <!-- Submit Button -->
                <div>
                    <div class="msg-error" style="margin-bottom: 12px; min-height: 1px;"></div>
                    <button type="submit" id="btn-submit-signup" class="wb-btn-submit">
                        <span style="color: #ffffff;">{{ __('Complete Sign Up') }}</span>
                    </button>
                </div>
            </form>

            <p style="text-align: center; font-size: 13px; color: #66796d; margin: 20px 0 0 0;">
                {{ __("Already have an account?") }}
                <a href="{{ url('auth/login') }}" style="font-weight: 700; color: #1f7a45; text-decoration: none;">{{ __("Sign in") }}</a>
            </p>
        </div>

        <p style="text-align: center; font-size: 12px; color: #8ea195; margin-top: 24px;">
            {{ __("All rights reserved.") }} &copy; {{ date('Y') }} Whatsappbulk
        </p>
    </div>
</div>

<script type="text/javascript">
// ─────────────────────────────────────────────────────────────
// Global Plan & Payment State and Helper Functions
// ─────────────────────────────────────────────────────────────
window.currentPrice = 89;
window.currentPlan = 'premium';
window.currentMethod = 'upi';
window.upiVpa = "{{ $upiId ?? 'launchshop@ybl' }}";

window.updateSubmitButtonText = function() {
    const btnSubmit = document.getElementById('btn-submit-signup');
    if (!btnSubmit) return;
    if (window.currentPrice === 0) {
        btnSubmit.innerHTML = '<span style="color:#ffffff;display:inline-flex;align-items:center;gap:8px;">{{ __("Complete Free Sign Up") }} <i class="fa-solid fa-arrow-right"></i></span>';
    } else if (window.currentMethod === 'upi') {
        btnSubmit.innerHTML = '<span style="color:#ffffff;display:inline-flex;align-items:center;gap:8px;">{{ __("Complete Sign Up with UPI") }} (₹' + window.currentPrice + ') <i class="fa-solid fa-check"></i></span>';
    } else {
        btnSubmit.innerHTML = '<span style="color:#ffffff;display:inline-flex;align-items:center;gap:8px;"><i class="fa-solid fa-credit-card"></i> {{ __("Proceed to Razorpay Checkout") }} (₹' + window.currentPrice + ') <i class="fa-solid fa-arrow-right"></i></span>';
    }
};

window.selectPlan = function(planKey) {
    planKey = (planKey || 'premium').toString().toLowerCase().trim();

    const planCards = document.querySelectorAll('.plan-option-card');
    const inputPlan = document.getElementById('input_selected_plan');
    const inputPrice = document.getElementById('input_selected_price');
    const inputPlanName = document.getElementById('input_selected_plan_name');
    const inputPaymentMethod = document.getElementById('input_payment_method');
    const planBadge = document.getElementById('selected-plan-badge');
    const paymentGatewaySection = document.getElementById('payment-gateway-section');
    const paymentAmountDisplay = document.getElementById('payment-amount-display');
    const dynamicUpiQr = document.getElementById('dynamic-upi-qr');
    const upiAmountBadge = document.getElementById('upi-amount-badge');

    let matchedCard = null;
    planCards.forEach(card => {
        const cardPlan = (card.getAttribute('data-plan') || '').toLowerCase();
        const cardName = (card.getAttribute('data-name') || '').toLowerCase();
        if (cardPlan === planKey || 
            (planKey === '0' && cardPlan === 'starter') ||
            (planKey === '39' && cardPlan === 'standard') ||
            (planKey === '89' && cardPlan === 'premium') ||
            cardName.includes(planKey) || 
            (planKey.includes('prem') && cardPlan === 'premium') || 
            (planKey.includes('stand') && cardPlan === 'standard') || 
            (planKey.includes('start') && cardPlan === 'starter') || 
            (planKey.includes('free') && cardPlan === 'starter')) {
            matchedCard = card;
        }
    });

    if (!matchedCard) {
        matchedCard = document.getElementById('card-plan-' + planKey) || 
                      document.querySelector('.plan-option-card[data-plan="' + planKey + '"]') || 
                      document.querySelector('.plan-option-card[data-plan="premium"]') || 
                      planCards[0];
    }

    if (!matchedCard) return;

    // Reset styles on all cards
    planCards.forEach(c => {
        c.classList.remove('active');
        c.style.borderColor = '#e5e7eb';
        c.style.background = '#ffffff';
        c.style.boxShadow = 'none';
    });

    const plan = matchedCard.getAttribute('data-plan') || 'starter';
    const price = parseInt(matchedCard.getAttribute('data-price'), 10) || 0;
    const name = matchedCard.getAttribute('data-name') || ('Whatsappbulk ' + plan.toUpperCase());

    window.currentPrice = price;
    window.currentPlan = plan;

    // Highlight selected card
    matchedCard.classList.add('active');
    if (price === 0) {
        matchedCard.style.borderColor = '#1f7a45';
        matchedCard.style.background = '#f0fdf4';
        matchedCard.style.boxShadow = '0 4px 14px rgba(31, 122, 69, 0.15)';
    } else if (plan === 'standard') {
        matchedCard.style.borderColor = '#ff5500';
        matchedCard.style.background = '#fff7ed';
        matchedCard.style.boxShadow = '0 4px 14px rgba(255, 85, 0, 0.15)';
    } else {
        matchedCard.style.borderColor = '#1f7a45';
        matchedCard.style.background = '#f0fdf4';
        matchedCard.style.boxShadow = '0 4px 14px rgba(31, 122, 69, 0.15)';
    }

    if (inputPlan) inputPlan.value = plan;
    if (inputPrice) inputPrice.value = price;
    if (inputPlanName) inputPlanName.value = name;

    // Update Plan Badge
    if (planBadge) {
        if (price === 0) {
            planBadge.textContent = 'Starter Plan • ₹0 / Free';
            planBadge.style.background = '#f3f4f6';
            planBadge.style.color = '#374151';
            planBadge.style.border = '1px solid #d1d5db';
        } else if (plan === 'standard') {
            planBadge.textContent = 'Standard Plan • ₹39 / year';
            planBadge.style.background = '#fff0e6';
            planBadge.style.color = '#ff5500';
            planBadge.style.border = '1px solid #fed7aa';
        } else {
            planBadge.textContent = 'Premium Plan • ₹89 / year';
            planBadge.style.background = '#e8f5e9';
            planBadge.style.color = '#1b5e20';
            planBadge.style.border = '1px solid #bbf7d0';
        }
    }

    // Toggle Payment Gateway Section for Free vs Paid
    if (paymentGatewaySection) {
        if (price === 0) {
            paymentGatewaySection.style.display = 'none';
            if (inputPaymentMethod) inputPaymentMethod.value = 'free';
            const utrErr = document.getElementById('utr-error-msg');
            if (utrErr) utrErr.style.display = 'none';
        } else {
            paymentGatewaySection.style.display = 'block';
            if (inputPaymentMethod) inputPaymentMethod.value = window.currentMethod;
            if (paymentAmountDisplay) paymentAmountDisplay.textContent = '₹' + price + '.00';
            if (upiAmountBadge) upiAmountBadge.textContent = 'Amount to Pay: ₹' + price;

            if (dynamicUpiQr) {
                const upiUrl = 'upi://pay?pa=' + encodeURIComponent(window.upiVpa) + '&pn=Whatsappbulk&am=' + price.toFixed(2) + '&cu=INR&tn=' + encodeURIComponent(name + ' Subscription');
                dynamicUpiQr.src = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(upiUrl);
            }
        }
    }

    window.updateSubmitButtonText();
};

window.switchPayTab = function(method) {
    window.currentMethod = method;
    const inputPaymentMethod = document.getElementById('input_payment_method');
    if (inputPaymentMethod) inputPaymentMethod.value = method;

    const tabUpi = document.getElementById('tab-pay-upi');
    const tabRazorpay = document.getElementById('tab-pay-razorpay');
    const boxUpi = document.getElementById('box-method-upi');
    const boxRazorpay = document.getElementById('box-method-razorpay');

    if (method === 'upi') {
        if (tabUpi) {
            tabUpi.classList.add('active');
            tabUpi.style.background = '#1f7a45';
            tabUpi.style.color = '#ffffff';
            tabUpi.style.borderColor = '#1f7a45';
        }
        if (tabRazorpay) {
            tabRazorpay.classList.remove('active');
            tabRazorpay.style.background = '#ffffff';
            tabRazorpay.style.color = '#374151';
            tabRazorpay.style.borderColor = '#e5e7eb';
        }
        if (boxUpi) boxUpi.style.display = 'block';
        if (boxRazorpay) boxRazorpay.style.display = 'none';
    } else {
        if (tabRazorpay) {
            tabRazorpay.classList.add('active');
            tabRazorpay.style.background = '#1f7a45';
            tabRazorpay.style.color = '#ffffff';
            tabRazorpay.style.borderColor = '#1f7a45';
        }
        if (tabUpi) {
            tabUpi.classList.remove('active');
            tabUpi.style.background = '#ffffff';
            tabUpi.style.color = '#374151';
            tabUpi.style.borderColor = '#e5e7eb';
        }
        if (boxUpi) boxUpi.style.display = 'none';
        if (boxRazorpay) boxRazorpay.style.display = 'block';
    }

    window.updateSubmitButtonText();
};

window.copyUpiId = function() {
    const btn = document.getElementById('btn-copy-upi');
    const vpa = window.upiVpa || 'launchshop@ybl';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(vpa).then(() => {
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> {{ __("Copied!") }}';
                btn.style.background = '#1f7a45';
                btn.style.color = '#ffffff';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fa-regular fa-copy me-1"></i> {{ __("Copy") }}';
                    btn.style.background = '#ffffff';
                    btn.style.color = '#1f7a45';
                }, 2000);
            }
        });
window.handleProofSelect = function(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const defaultState = document.getElementById('proof-default-state');
        const previewState = document.getElementById('proof-preview-state');
        const filenameEl = document.getElementById('proof-preview-filename');
        const dropzone = document.getElementById('proof-dropzone');
        if (defaultState) defaultState.style.display = 'none';
        if (previewState) previewState.style.display = 'flex';
        if (filenameEl) filenameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        if (dropzone) {
            dropzone.style.background = '#dcfce7';
            dropzone.style.borderColor = '#16a34a';
        }
    }
};

window.clearProofSelect = function(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const input = document.getElementById('payment_proof');
    if (input) input.value = '';
    const defaultState = document.getElementById('proof-default-state');
    const previewState = document.getElementById('proof-preview-state');
    const dropzone = document.getElementById('proof-dropzone');
    if (defaultState) defaultState.style.display = 'block';
    if (previewState) previewState.style.display = 'none';
    if (dropzone) {
        dropzone.style.background = '#f0fdf4';
        dropzone.style.borderColor = '#86efac';
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    let isPhoneVerified = false;

    // Elements - WhatsApp
    const btnSendWaOtp = document.getElementById('btn-send-wa-otp');
    const btnVerifyWaOtp = document.getElementById('btn-verify-wa-otp');
    const btnResendWaOtp = document.getElementById('btn-resend-wa-otp');
    const waOtpBox = document.getElementById('wa-otp-box');
    const waOtpMsg = document.getElementById('wa-otp-msg');
    const waOtpInput = document.getElementById('wa-otp-input');
    const waVerifiedBadge = document.getElementById('wa-verified-badge');
    const phoneInput = document.getElementById('phone');
    const countryCodeSelect = document.getElementById('country_code');
    const waTimerSpan = document.getElementById('wa-timer');

    const signupForm = document.getElementById('signup-form');
    const utrInput = document.getElementById('utr_number');
    const utrErrorMsg = document.getElementById('utr-error-msg');
    const razorpayPaymentIdInput = document.getElementById('hidden_razorpay_payment_id');
    const inputPlanName = document.getElementById('input_selected_plan_name');
    const btnSubmit = document.getElementById('btn-submit-signup');

    // Countdown Helper
    function startCountdown(durationSec, timerEl, resendBtnEl, sendBtnEl, intervalRef) {
        let left = durationSec;
        if (resendBtnEl) resendBtnEl.style.display = 'none';
        if (sendBtnEl) sendBtnEl.disabled = true;
        if (timerEl) timerEl.textContent = '(' + left + 's)';

        if (intervalRef.val) clearInterval(intervalRef.val);
        intervalRef.val = setInterval(() => {
            left--;
            if (left <= 0) {
                clearInterval(intervalRef.val);
                if (timerEl) timerEl.textContent = '';
                if (resendBtnEl) resendBtnEl.style.display = 'inline-block';
                if (sendBtnEl) sendBtnEl.disabled = false;
            } else {
                if (timerEl) timerEl.textContent = '(' + left + 's)';
            }
        }, 1000);
    }

    const waIntervalRef = { val: null };

    // ─────────────────────────────────────────────────────────────
    // 1. Send WhatsApp OTP
    // ─────────────────────────────────────────────────────────────
    function doSendWaOtp() {
        const phone = phoneInput ? phoneInput.value.trim() : '';
        const countryCode = countryCodeSelect ? countryCodeSelect.value.trim() : '+91';

        if (!phone) {
            if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Please enter your mobile number.</span>';
            if (phoneInput) phoneInput.focus();
            return;
        }

        if (btnSendWaOtp) {
            btnSendWaOtp.disabled = true;
            const originalText = btnSendWaOtp.innerHTML;
            btnSendWaOtp.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#6b7280;">Connecting to WhatsApp Gateway...</span>';

            fetch('{{ url("auth/send_whatsapp_otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, country_code: countryCode, _token: csrfToken })
            })
            .then(res => res.json())
            .then(data => {
                btnSendWaOtp.disabled = false;
                btnSendWaOtp.innerHTML = originalText;

                if (data.status === 1) {
                    if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check"></i> ' + (data.message || 'OTP sent successfully!') + '</span>';
                    if (waOtpBox) waOtpBox.style.display = 'block';
                    if (waOtpInput) waOtpInput.focus();
                    startCountdown(60, waTimerSpan, btnResendWaOtp, btnSendWaOtp, waIntervalRef);
                } else {
                    if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
                }
            })
            .catch(err => {
                btnSendWaOtp.disabled = false;
                btnSendWaOtp.innerHTML = originalText;
                if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.</span>';
            });
        }
    }

    if (btnSendWaOtp) btnSendWaOtp.addEventListener('click', doSendWaOtp);
    if (btnResendWaOtp) btnResendWaOtp.addEventListener('click', doSendWaOtp);

    // ─────────────────────────────────────────────────────────────
    // 2. Verify WhatsApp OTP
    // ─────────────────────────────────────────────────────────────
    if (btnVerifyWaOtp) {
        btnVerifyWaOtp.addEventListener('click', function () {
            const otp = waOtpInput ? waOtpInput.value.trim() : '';
            const phone = phoneInput ? phoneInput.value.trim() : '';
            const countryCode = countryCodeSelect ? countryCodeSelect.value.trim() : '+91';

            if (!otp || otp.length < 6) {
                if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;">Please enter the 6-digit OTP received on WhatsApp.</span>';
                if (waOtpInput) waOtpInput.focus();
                return;
            }

            btnVerifyWaOtp.disabled = true;
            btnVerifyWaOtp.textContent = 'Verifying...';

            fetch('{{ url("auth/verify_whatsapp_otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, country_code: countryCode, otp: otp, _token: csrfToken })
            })
            .then(res => res.json())
            .then(data => {
                btnVerifyWaOtp.disabled = false;
                btnVerifyWaOtp.textContent = 'Verify OTP';

                if (data.status === 1) {
                    isPhoneVerified = true;
                    if (waIntervalRef.val) clearInterval(waIntervalRef.val);
                    if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-circle-check"></i> ' + (data.message || 'WhatsApp number verified!') + '</span>';
                    if (waOtpBox) waOtpBox.style.display = 'none';
                    if (btnSendWaOtp) btnSendWaOtp.style.display = 'none';
                    if (waVerifiedBadge) waVerifiedBadge.style.display = 'inline-flex';
                    if (phoneInput) {
                        phoneInput.readOnly = true;
                        phoneInput.style.backgroundColor = '#f0fdf4';
                    }
                    if (countryCodeSelect) countryCodeSelect.disabled = true;
                } else {
                    if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-xmark"></i> ' + (data.message || 'Invalid code.') + '</span>';
                    if (waOtpInput) waOtpInput.select();
                }
            })
            .catch(err => {
                btnVerifyWaOtp.disabled = false;
                btnVerifyWaOtp.textContent = 'Verify OTP';
                if (waOtpMsg) waOtpMsg.innerHTML = '<span style="color:#ef4444;">Verification failed. Please try again.</span>';
            });
        });
    }

    // Password visibility toggle
    document.querySelectorAll('.btn-toggle-password').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (!targetInput) return;

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                this.style.color = '#1f7a45';
            } else {
                targetInput.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
                this.style.color = '#759081';
            }
        });
    });

    // Strip whitespace from username
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            this.value = this.value.replace(/\s+/g, '');
        });
        usernameInput.addEventListener('blur', function () {
            this.value = this.value.trim().replace(/\s+/g, '');
        });
    }

    // Secondary Event Listeners on Plan Cards
    document.querySelectorAll('.plan-option-card').forEach(card => {
        card.addEventListener('click', function () {
            window.selectPlan(this.getAttribute('data-plan'));
        });
    });

    // Secondary Event Listeners on Pay Tabs
    document.querySelectorAll('.btn-pay-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            window.switchPayTab(this.getAttribute('data-tab'));
        });
    });

    // Auto-detect URL parameter ?plan=
    const urlParams = new URLSearchParams(window.location.search);
    const initialPlan = urlParams.get('plan') || '{{ $planParam ?? "premium" }}';
    window.selectPlan(initialPlan);

    // Razorpay Popup Trigger
    function launchRazorpayModal() {
        const fullname = document.getElementById('fullname')?.value.trim() || 'Customer';
        const email = document.getElementById('email')?.value.trim() || '';
        const phone = document.getElementById('phone')?.value.trim() || '';

        const options = {
            key: "{{ $razorpayKey ?? 'rzp_test_T9UaATIMf1qeO8' }}",
            amount: Math.round(window.currentPrice * 100),
            currency: "INR",
            name: "Whatsappbulk",
            description: (inputPlanName?.value || "Whatsappbulk") + " Subscription",
            handler: function (response) {
                if (razorpayPaymentIdInput) {
                    razorpayPaymentIdInput.value = response.razorpay_payment_id;
                }
                executeFormAjaxSubmit();
            },
            prefill: {
                name: fullname,
                email: email,
                contact: phone
            },
            theme: {
                color: "#1f7a45"
            }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (response) {
            alert("Payment failed: " + response.error.description);
            if (btnSubmit) {
                btnSubmit.disabled = false;
                window.updateSubmitButtonText();
            }
        });
        rzp.open();
    }

    // ─────────────────────────────────────────────────────────────
    // Form Submission
    // ─────────────────────────────────────────────────────────────
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            const errorContainer = signupForm.querySelector('.msg-error');

            if (errorContainer) {
                errorContainer.innerHTML = '';
            }

            if (!isPhoneVerified) {
                if (errorContainer) {
                    errorContainer.innerHTML = '<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:12px;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> Please verify your WhatsApp mobile number before signing up.</div>';
                    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                if (phoneInput) phoneInput.focus();
                return false;
            }

            // Payment Validation for Paid Plans (₹39 / ₹89)
            if (window.currentPrice > 0) {
                if (window.currentMethod === 'upi') {
                    const utrVal = utrInput ? utrInput.value.trim().replace(/\s+/g, '') : '';
                    if (!utrVal || utrVal.length < 6) {
                        if (utrErrorMsg) utrErrorMsg.style.display = 'block';
                        if (utrInput) {
                            utrInput.focus();
                            utrInput.style.borderColor = '#dc2626';
                        }
                        return false;
                    } else {
                        if (utrErrorMsg) utrErrorMsg.style.display = 'none';
                        if (utrInput) utrInput.style.borderColor = '#1f7a45';
                    }
                } else if (window.currentMethod === 'razorpay') {
                    // Ready to proceed to checkout.youverse.in after signup
                }
            }

            executeFormAjaxSubmit();
        });
    }

    function executeFormAjaxSubmit() {
        const errorContainer = signupForm.querySelector('.msg-error');
        if (countryCodeSelect) countryCodeSelect.disabled = false;
        const formData = new FormData(signupForm);
        if (countryCodeSelect) countryCodeSelect.disabled = true;

        if (btnSubmit) {
            btnSubmit.disabled = true;
            const originalBtnHtml = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<span style="color:#ffffff;display:inline-flex;align-items:center;gap:8px;"><i class="fa-solid fa-circle-notch fa-spin"></i> {{ __("Creating Account...") }}</span>';

            fetch(signupForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 1) {
                    btnSubmit.innerHTML = '<span style="color:#ffffff;"><i class="fa-solid fa-check"></i> {{ __("Account Created!") }}</span>';
                    if (errorContainer) {
                        errorContainer.innerHTML = '<div style="background:#f0fdf4;border:1.5px solid #86efac;color:#15803d;padding:14px;border-radius:12px;font-size:14px;font-weight:700;margin-bottom:12px;"><i class="fa-solid fa-circle-check" style="margin-right:6px;"></i> ' + (data.message || 'Account created successfully! Redirecting...') + '</div>';
                        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ url("auth/login") }}';
                    }, 1500);
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalBtnHtml;

                    let errMsg = data.message || 'Signup failed. Please check the form errors.';
                    if (data.errors) {
                        if (typeof data.errors === 'object') {
                            errMsg = Object.values(data.errors).flat().join('<br>');
                        } else {
                            errMsg = data.errors;
                        }
                    }

                    if (errorContainer) {
                        errorContainer.innerHTML = '<div style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:12px;line-height:1.5;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> ' + errMsg + '</div>';
                        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalBtnHtml;
                if (errorContainer) {
                    errorContainer.innerHTML = '<div style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:12px;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> An unexpected network error occurred. Please try again.</div>';
                }
            });
        }
    }
});
</script>
