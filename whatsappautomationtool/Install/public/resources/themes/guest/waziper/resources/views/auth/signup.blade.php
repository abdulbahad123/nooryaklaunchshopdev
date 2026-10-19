@php
    $captcha = Captcha::render();
    $languages = Language::getLanguages();
    $currentLang = app()->getLocale();
@endphp

<style>
    /* Reset & Container */
    .wb-signup-wrapper {
        min-height: 100vh;
        background-color: #f7fbf8;
        background-image: radial-gradient(at 10% 20%, #e8f7ee 0px, transparent 50%), radial-gradient(at 90% 80%, #e6f3fd 0px, transparent 50%);
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
        background-color: #1f7a45 !important;
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
        background-color: #176338 !important;
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
        background-color: #1f7a45 !important;
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
        background-color: #176338 !important;
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

            <form class="actionForm" id="signup-form" action="{{ module_url('do_signup') }}" method="POST">
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

                <!-- Email Address Section -->
                <div class="wb-card-section">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label for="email" style="margin: 0; font-size: 13px; font-weight: 700; color: #1f7a45; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-envelope" style="font-size: 15px;"></i>
                            <span>{{ __("Email Address") }}</span>
                        </label>
                        <span id="email-verified-badge" class="wb-badge-verified" style="display: none;">
                            <i class="fa-solid fa-circle-check"></i> {{ __("Verified") }}
                        </span>
                    </div>

                    <div class="wb-otp-flex">
                        <input type="email" id="email" name="email" class="wb-input" style="flex: 1; min-width: 0;" placeholder="{{ __('Enter your email address') }}" required>
                        <button type="button" id="btn-send-email-otp" class="wb-btn-send">
                            <i class="fa-solid fa-paper-plane" style="font-size: 13px;"></i>
                            <span>{{ __("Send OTP") }}</span>
                        </button>
                    </div>

                    <div id="email-otp-msg" style="margin-top: 6px; font-size: 12px; font-weight: 600; min-height: 16px;"></div>

                    <!-- Inline Email OTP verification box -->
                    <div id="email-otp-box" style="display: none; margin-top: 12px; padding: 14px; background: #ffffff; border: 1.5px solid #bbf7d0; border-radius: 14px;">
                        <div style="font-size: 13px; color: #166534; font-weight: 600; margin-bottom: 8px;">
                            <i class="fa-solid fa-key" style="margin-right: 4px;"></i> {{ __("Enter the 6-digit verification code sent to your email inbox:") }}
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <input type="text" id="email-otp-input" maxlength="6" style="height: 42px; width: 140px; text-align: center; font-family: monospace; font-size: 18px; font-weight: 800; letter-spacing: 4px; border: 1.5px solid #1f7a45; border-radius: 10px; outline: none; background: #ffffff; color: #16231b;" placeholder="••••••">
                            <button type="button" id="btn-verify-email-otp" style="height: 42px; padding: 0 18px; background-color: #1f7a45; color: #ffffff !important; border: none; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 6px rgba(31,122,69,0.2);">
                                {{ __("Verify OTP") }}
                            </button>
                            <button type="button" id="btn-resend-email-otp" style="display: none; background: transparent; border: none; color: #1f7a45; text-decoration: underline; font-size: 12px; font-weight: 600; cursor: pointer;">
                                {{ __("Resend Code") }}
                            </button>
                            <span id="email-timer" style="font-size: 12px; color: #6b7280; font-family: monospace; font-weight: 600; margin-left: auto;"></span>
                        </div>
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="wb-form-grid" style="margin-bottom: 16px;">
                    <div>
                        <label for="password" class="wb-input-label">{{ __("Password") }}</label>
                        <input type="password" id="password" name="password" class="wb-input" placeholder="{{ __('Enter your password') }}" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="wb-input-label">{{ __("Confirm Password") }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="wb-input" placeholder="{{ __('Confirm your password') }}" required>
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
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    let isPhoneVerified = false;
    let isEmailVerified = false;

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

    // Elements - Email
    const btnSendEmailOtp = document.getElementById('btn-send-email-otp');
    const btnVerifyEmailOtp = document.getElementById('btn-verify-email-otp');
    const btnResendEmailOtp = document.getElementById('btn-resend-email-otp');
    const emailOtpBox = document.getElementById('email-otp-box');
    const emailOtpMsg = document.getElementById('email-otp-msg');
    const emailOtpInput = document.getElementById('email-otp-input');
    const emailVerifiedBadge = document.getElementById('email-verified-badge');
    const emailInput = document.getElementById('email');
    const emailTimerSpan = document.getElementById('email-timer');

    const signupForm = document.getElementById('signup-form');

    // Countdown Helper
    function startCountdown(durationSec, timerEl, resendBtnEl, sendBtnEl, intervalRef) {
        let left = durationSec;
        resendBtnEl.style.display = 'none';
        sendBtnEl.disabled = true;
        timerEl.textContent = '(' + left + 's)';

        if (intervalRef.val) clearInterval(intervalRef.val);
        intervalRef.val = setInterval(() => {
            left--;
            if (left <= 0) {
                clearInterval(intervalRef.val);
                timerEl.textContent = '';
                resendBtnEl.style.display = 'inline-block';
                sendBtnEl.disabled = false;
            } else {
                timerEl.textContent = '(' + left + 's)';
            }
        }, 1000);
    }

    const waIntervalRef = { val: null };
    const emailIntervalRef = { val: null };

    // ─────────────────────────────────────────────────────────────
    // 1. Send WhatsApp OTP
    // ─────────────────────────────────────────────────────────────
    function doSendWaOtp() {
        const phone = phoneInput.value.trim();
        const countryCode = countryCodeSelect.value.trim();

        if (!phone) {
            waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Please enter your mobile number.</span>';
            phoneInput.focus();
            return;
        }

        btnSendWaOtp.disabled = true;
        const originalText = btnSendWaOtp.innerHTML;
        btnSendWaOtp.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
        waOtpMsg.innerHTML = '<span style="color:#6b7280;">Connecting to WhatsApp Gateway...</span>';

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
                waOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check"></i> ' + (data.message || 'OTP sent successfully!') + '</span>';
                waOtpBox.style.display = 'block';
                waOtpInput.focus();
                startCountdown(60, waTimerSpan, btnResendWaOtp, btnSendWaOtp, waIntervalRef);
            } else {
                waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
            }
        })
        .catch(err => {
            btnSendWaOtp.disabled = false;
            btnSendWaOtp.innerHTML = originalText;
            waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.</span>';
        });
    }

    btnSendWaOtp.addEventListener('click', doSendWaOtp);
    btnResendWaOtp.addEventListener('click', doSendWaOtp);

    // ─────────────────────────────────────────────────────────────
    // 2. Verify WhatsApp OTP
    // ─────────────────────────────────────────────────────────────
    btnVerifyWaOtp.addEventListener('click', function () {
        const otp = waOtpInput.value.trim();
        const phone = phoneInput.value.trim();
        const countryCode = countryCodeSelect.value.trim();

        if (!otp || otp.length < 6) {
            waOtpMsg.innerHTML = '<span style="color:#ef4444;">Please enter the 6-digit OTP received on WhatsApp.</span>';
            waOtpInput.focus();
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
                waOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-circle-check"></i> ' + (data.message || 'WhatsApp number verified!') + '</span>';
                waOtpBox.style.display = 'none';
                btnSendWaOtp.style.display = 'none';
                waVerifiedBadge.style.display = 'inline-flex';
                phoneInput.readOnly = true;
                countryCodeSelect.disabled = true;
                phoneInput.style.backgroundColor = '#f0fdf4';
            } else {
                waOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-xmark"></i> ' + (data.message || 'Invalid code.') + '</span>';
                waOtpInput.select();
            }
        })
        .catch(err => {
            btnVerifyWaOtp.disabled = false;
            btnVerifyWaOtp.textContent = 'Verify OTP';
            waOtpMsg.innerHTML = '<span style="color:#ef4444;">Verification failed. Please try again.</span>';
        });
    });

    // ─────────────────────────────────────────────────────────────
    // 3. Send Email OTP
    // ─────────────────────────────────────────────────────────────
    function doSendEmailOtp() {
        const email = emailInput.value.trim();

        if (!email || !email.includes('@')) {
            emailOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Please enter a valid email address.</span>';
            emailInput.focus();
            return;
        }

        btnSendEmailOtp.disabled = true;
        const originalText = btnSendEmailOtp.innerHTML;
        btnSendEmailOtp.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
        emailOtpMsg.innerHTML = '<span style="color:#6b7280;">Dispatching verification code to email...</span>';

        fetch('{{ url("auth/send_email_otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email, _token: csrfToken })
        })
        .then(res => res.json())
        .then(data => {
            btnSendEmailOtp.disabled = false;
            btnSendEmailOtp.innerHTML = originalText;

            if (data.status === 1) {
                emailOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check"></i> ' + (data.message || 'Verification code sent to your email!') + '</span>';
                emailOtpBox.style.display = 'block';
                emailOtpInput.focus();
                startCountdown(60, emailTimerSpan, btnResendEmailOtp, btnSendEmailOtp, emailIntervalRef);
            } else {
                emailOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Failed to send email verification.') + '</span>';
            }
        })
        .catch(err => {
            btnSendEmailOtp.disabled = false;
            btnSendEmailOtp.innerHTML = originalText;
            emailOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.</span>';
        });
    }

    btnSendEmailOtp.addEventListener('click', doSendEmailOtp);
    btnResendEmailOtp.addEventListener('click', doSendEmailOtp);

    // ─────────────────────────────────────────────────────────────
    // 4. Verify Email OTP
    // ─────────────────────────────────────────────────────────────
    btnVerifyEmailOtp.addEventListener('click', function () {
        const otp = emailOtpInput.value.trim();
        const email = emailInput.value.trim();

        if (!otp || otp.length < 6) {
            emailOtpMsg.innerHTML = '<span style="color:#ef4444;">Please enter the 6-digit OTP sent to your email.</span>';
            emailOtpInput.focus();
            return;
        }

        btnVerifyEmailOtp.disabled = true;
        btnVerifyEmailOtp.textContent = 'Verifying...';

        fetch('{{ url("auth/verify_email_otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email, otp: otp, _token: csrfToken })
        })
        .then(res => res.json())
        .then(data => {
            btnVerifyEmailOtp.disabled = false;
            btnVerifyEmailOtp.textContent = 'Verify OTP';

            if (data.status === 1) {
                isEmailVerified = true;
                if (emailIntervalRef.val) clearInterval(emailIntervalRef.val);
                emailOtpMsg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-circle-check"></i> ' + (data.message || 'Email verified successfully!') + '</span>';
                emailOtpBox.style.display = 'none';
                btnSendEmailOtp.style.display = 'none';
                emailVerifiedBadge.style.display = 'inline-flex';
                emailInput.readOnly = true;
                emailInput.style.backgroundColor = '#f0fdf4';
            } else {
                emailOtpMsg.innerHTML = '<span style="color:#ef4444;"><i class="fa-solid fa-circle-xmark"></i> ' + (data.message || 'Invalid code.') + '</span>';
                emailOtpInput.select();
            }
        })
        .catch(err => {
            btnVerifyEmailOtp.disabled = false;
            btnVerifyEmailOtp.textContent = 'Verify OTP';
            emailOtpMsg.innerHTML = '<span style="color:#ef4444;">Verification failed. Please try again.</span>';
        });
    });

    // Auto-strip whitespace from username in real-time
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            this.value = this.value.replace(/\s+/g, '');
        });
        usernameInput.addEventListener('blur', function () {
            this.value = this.value.trim().replace(/\s+/g, '');
        });
    }

    // ─────────────────────────────────────────────────────────────
    // 5. AJAX Form Submission
    // ─────────────────────────────────────────────────────────────
    signupForm.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const errorContainer = signupForm.querySelector('.msg-error');
        const btnSubmit = document.getElementById('btn-submit-signup');

        if (errorContainer) {
            errorContainer.innerHTML = '';
        }

        if (!isPhoneVerified) {
            if (errorContainer) {
                errorContainer.innerHTML = '<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:12px;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> Please verify your WhatsApp mobile number before signing up.</div>';
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            phoneInput.focus();
            return false;
        }

        if (!isEmailVerified) {
            if (errorContainer) {
                errorContainer.innerHTML = '<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:12px;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> Please verify your email address before signing up.</div>';
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            emailInput.focus();
            return false;
        }

        // Temporarily enable select to serialize full form data
        countryCodeSelect.disabled = false;
        const formData = new FormData(signupForm);
        countryCodeSelect.disabled = true;

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
    });
});
</script>
