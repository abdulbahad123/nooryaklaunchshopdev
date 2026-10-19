@php
    $captcha = Captcha::render();
    $languages = Language::getLanguages();
    $currentLang = app()->getLocale();
@endphp

<section class="relative min-h-screen overflow-hidden bg-[#f7fbf8] px-4 py-10">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute left-1/2 top-0 h-72 w-72 -translate-x-1/2 rounded-full bg-[#dff6e8] blur-3xl opacity-80"></div>
        <div class="absolute bottom-10 left-10 h-56 w-56 rounded-full bg-[#eef8f1] blur-3xl"></div>
        <div class="absolute right-10 top-24 h-56 w-56 rounded-full bg-[#edf7ff] blur-3xl"></div>
    </div>

    <div class="relative mx-auto flex min-h-[calc(100vh-5rem)] max-w-[660px] flex-col items-center justify-center">
        <div class="mb-10 flex w-full items-start justify-between gap-4">
            <a href="{{ url('') }}" class="inline-flex items-center gap-4">
                <span class="flex h-14 w-14 items-center justify-center rounded-full border border-[#cae8d3] bg-white shadow-[0_12px_30px_rgba(31,122,69,0.08)] text-[#1f7a45]">
                    <i class="fa-brands fa-whatsapp text-2xl"></i>
                </span>
                <div>
                    <div class="text-[2rem] font-bold tracking-tight text-[#16231b]">Whatsappbulk</div>
                    <div class="text-sm text-[#759081]">{{ __("WhatsApp Marketing Tool") }}</div>
                </div>
            </a>

            @if($languages->isNotEmpty())
                <div class="dropdown dropdown-hover dropdown-end">
                    <div tabindex="0" class="flex h-11 items-center gap-2 rounded-full border border-[#d9e9de] bg-white px-4 text-[#22352b] shadow-[0_10px_24px_rgba(15,23,42,0.04)] cursor-pointer">
                        <i class="fa-solid fa-globe"></i>
                        <span class="text-sm font-semibold">{{ strtoupper($currentLang) }}</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[999] mt-3 menu w-48 rounded-2xl border border-[#dcfce7] bg-white p-3 shadow-xl">
                        @foreach($languages as $language)
                            <li>
                                <a href="{{ url('lang/' . $language->code) }}" class="flex items-center gap-2 rounded-xl {{ $currentLang == $language->code ? 'text-white font-semibold' : 'font-medium text-[#22352b]' }}" style="{{ $currentLang == $language->code ? 'background:#ff5500;' : '' }}">
                                    @if($language->icon)
                                        <span class="size-4 text-center -mt-1"><i class="{{ $language->icon }}"></i></span>
                                    @endif
                                    <span class="truncate">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="w-full rounded-[2rem] border border-white/70 bg-white/95 p-8 pb-12 shadow-[0_30px_80px_rgba(27,67,45,0.10)] backdrop-blur md:p-12 md:pb-16">
            <div class="text-center" style="margin-bottom:36px;">
                <span class="inline-flex items-center rounded-full bg-[#effaf2] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#1f7a45]" style="margin-bottom:14px;">
                    {{ __("Create workspace") }}
                </span>
                <h1 class="font-bold tracking-tight text-[#16231b]" style="margin-top:0;margin-bottom:12px;font-size:32px;line-height:1.2;">{{ __("Create your account") }}</h1>
                <p class="text-sm leading-6 text-[#698174]" style="margin-top:0;">
                    {{ __("Sign up with your mobile number and email. Verification required to secure your workspace.") }}
                </p>
            </div>

            <form class="actionForm" id="signup-form" action="{{ module_url('do_signup') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <!-- Full Name & Username -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="fullname" class="mb-2 block text-sm font-semibold text-[#2e4337]">{{ __("Full Name") }}</label>
                            <input type="text" id="fullname" name="fullname" class="h-12 w-full rounded-[1rem] border border-[#dcebe0] bg-[#fbfdfb] px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45] focus:bg-white" placeholder="{{ __('Enter your full name') }}" required>
                        </div>
                        <div>
                            <label for="username" class="mb-2 block text-sm font-semibold text-[#2e4337]">{{ __("Username") }}</label>
                            <input type="text" id="username" name="username" class="h-12 w-full rounded-[1rem] border border-[#dcebe0] bg-[#fbfdfb] px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45] focus:bg-white" placeholder="{{ __('Choose a username') }}" required>
                        </div>
                    </div>

                    <!-- Mobile Number with Country Code & WhatsApp OTP Verification -->
                    <div class="rounded-2xl border border-[#dcf2e4] bg-[#f6fcf8] p-4 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <label for="phone" class="block text-sm font-semibold text-[#1f7a45]">
                                <i class="fa-brands fa-whatsapp text-base me-1"></i> {{ __("WhatsApp Mobile Number") }}
                            </label>
                            <span id="wa-verified-badge" class="hidden inline-flex items-center gap-1 rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-xs font-semibold text-[#15803d]">
                                <i class="fa-solid fa-circle-check"></i> {{ __("Verified") }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex flex-1 rounded-xl border border-[#cfe2d6] bg-white overflow-hidden focus-within:border-[#1f7a45]">
                                <select id="country_code" name="country_code" class="h-12 border-r border-[#e2ece5] bg-[#f8faf9] px-3 text-sm font-medium text-[#16231b] outline-none cursor-pointer" style="max-width: 130px;">
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
                                <input type="tel" id="phone" name="phone" class="h-12 w-full px-3 text-sm text-[#16231b] outline-none" placeholder="{{ __('Mobile number (e.g. 9876543210)') }}" required>
                            </div>
                            <button type="button" id="btn-send-wa-otp" class="h-12 whitespace-nowrap rounded-xl bg-[#1f7a45] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#186438] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-brands fa-whatsapp text-base"></i>
                                <span>{{ __("Send OTP") }}</span>
                            </button>
                        </div>
                        <div id="wa-otp-msg" class="mt-2 text-xs font-medium min-h-[16px]"></div>

                        <!-- Inline WhatsApp OTP verification box -->
                        <div id="wa-otp-box" class="hidden mt-3 pt-3 border-t border-[#e2efe7]">
                            <div class="text-xs text-[#52705e] mb-2 font-medium">
                                {{ __("Enter the 6-digit verification code sent to your WhatsApp number:") }}
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="wa-otp-input" maxlength="6" class="h-11 w-36 text-center font-mono text-lg font-bold tracking-widest rounded-xl border border-[#cfe2d6] bg-white px-3 outline-none focus:border-[#1f7a45]" placeholder="••••••">
                                <button type="button" id="btn-verify-wa-otp" class="h-11 rounded-xl bg-[#1f7a45] px-4 text-xs font-semibold text-white hover:bg-[#186438] active:scale-95 transition">
                                    {{ __("Verify OTP") }}
                                </button>
                                <button type="button" id="btn-resend-wa-otp" class="hidden text-xs text-[#1f7a45] underline font-medium hover:text-[#165a32] ml-2">
                                    {{ __("Resend Code") }}
                                </button>
                                <span id="wa-timer" class="text-xs text-gray-400 font-mono ml-auto"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Email Address & Email OTP Verification -->
                    <div class="rounded-2xl border border-[#dcf2e4] bg-[#f6fcf8] p-4 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <label for="email" class="block text-sm font-semibold text-[#1f7a45]">
                                <i class="fa-solid fa-envelope text-sm me-1"></i> {{ __("Email Address") }}
                            </label>
                            <span id="email-verified-badge" class="hidden inline-flex items-center gap-1 rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-xs font-semibold text-[#15803d]">
                                <i class="fa-solid fa-circle-check"></i> {{ __("Verified") }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="email" id="email" name="email" class="h-12 flex-1 rounded-xl border border-[#cfe2d6] bg-white px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45]" placeholder="{{ __('Enter your email address') }}" required>
                            <button type="button" id="btn-send-email-otp" class="h-12 whitespace-nowrap rounded-xl bg-[#1f7a45] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#186438] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>{{ __("Send OTP") }}</span>
                            </button>
                        </div>
                        <div id="email-otp-msg" class="mt-2 text-xs font-medium min-h-[16px]"></div>

                        <!-- Inline Email OTP verification box -->
                        <div id="email-otp-box" class="hidden mt-3 pt-3 border-t border-[#e2efe7]">
                            <div class="text-xs text-[#52705e] mb-2 font-medium">
                                {{ __("Enter the 6-digit verification code sent to your email inbox:") }}
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="email-otp-input" maxlength="6" class="h-11 w-36 text-center font-mono text-lg font-bold tracking-widest rounded-xl border border-[#cfe2d6] bg-white px-3 outline-none focus:border-[#1f7a45]" placeholder="••••••">
                                <button type="button" id="btn-verify-email-otp" class="h-11 rounded-xl bg-[#1f7a45] px-4 text-xs font-semibold text-white hover:bg-[#186438] active:scale-95 transition">
                                    {{ __("Verify OTP") }}
                                </button>
                                <button type="button" id="btn-resend-email-otp" class="hidden text-xs text-[#1f7a45] underline font-medium hover:text-[#165a32] ml-2">
                                    {{ __("Resend Code") }}
                                </button>
                                <span id="email-timer" class="text-xs text-gray-400 font-mono ml-auto"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Password & Confirm Password -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-[#2e4337]">{{ __("Password") }}</label>
                            <input type="password" id="password" name="password" class="h-12 w-full rounded-[1rem] border border-[#dcebe0] bg-[#fbfdfb] px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45] focus:bg-white" placeholder="{{ __('Enter your password') }}" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#2e4337]">{{ __("Confirm Password") }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="h-12 w-full rounded-[1rem] border border-[#dcebe0] bg-[#fbfdfb] px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45] focus:bg-white" placeholder="{{ __('Confirm your password') }}" required>
                        </div>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="mb-2 block text-sm font-semibold text-[#2e4337]">{{ __("Timezone") }}</label>
                        <select id="timezone" name="timezone" class="h-12 w-full rounded-[1rem] border border-[#dcebe0] bg-[#fbfdfb] px-4 text-sm text-[#16231b] outline-none transition focus:border-[#1f7a45] focus:bg-white" required>
                            <option value="">{{ __("Select your timezone") }}</option>
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ (old('timezone') == $tz || $tz === 'Asia/Kolkata') ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($captcha)
                        <div class="pt-1">{!! $captcha !!}</div>
                    @endif

                    <!-- Terms -->
                    <label for="accep_terms" class="flex items-start gap-3 text-sm text-[#5f7568]">
                        <input class="mt-1 h-4 w-4 rounded border-[#cfe0d4] text-[#1f7a45] focus:ring-[#1f7a45]" id="accep_terms" name="accep_terms" type="checkbox" value="1" required>
                        <span>
                            {{ __("I agree to the") }}
                            <a class="font-semibold text-[#1f7a45] hover:text-[#176338]" href="{{ url('terms-of-service') }}">{{ __("Terms & Conditions") }}</a>
                        </span>
                    </label>
                </div>

                <div style="padding-top:28px;">
                    <div class="msg-error mb-4 min-h-[1px]"></div>
                    <button type="submit" id="btn-submit-signup" style="display:flex;width:100%;height:54px;align-items:center;justify-content:center;border:none;border-radius:18px;background:#1f7a45;color:#ffffff;font-size:16px;font-weight:600;box-shadow:0 18px 40px rgba(31,122,69,0.22);cursor:pointer;transition:all 0.2s;">
                        <span style="color:#ffffff;">{{ __('Complete Sign Up') }}</span>
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-[#66796d]" style="margin-top:24px;">
                {{ __("Already have an account?") }}
                <a href="{{ url('auth/login') }}" class="font-semibold text-[#1f7a45] hover:text-[#176338]">{{ __("Sign in") }}</a>
            </p>
        </div>

        <p class="text-center text-sm text-[#8ea195]" style="margin-top:32px;">
            {{ __("All rights reserved.") }} &copy; {{ date('Y') }} Whatsappbulk
        </p>
    </div>
</section>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    let isPhoneVerified = false;
    let isEmailVerified = false;
    let waTimerInterval = null;
    let emailTimerInterval = null;

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

    // ─────────────────────────────────────────────────────────────
    // Helper: start countdown
    // ─────────────────────────────────────────────────────────────
    function startCountdown(durationSec, timerEl, resendBtnEl, sendBtnEl, intervalRef) {
        let left = durationSec;
        resendBtnEl.classList.add('hidden');
        sendBtnEl.disabled = true;
        timerEl.textContent = '(' + left + 's)';

        if (intervalRef.val) clearInterval(intervalRef.val);
        intervalRef.val = setInterval(() => {
            left--;
            if (left <= 0) {
                clearInterval(intervalRef.val);
                timerEl.textContent = '';
                resendBtnEl.classList.remove('hidden');
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
            waOtpMsg.innerHTML = '<span class="text-red-500"><i class="fa-solid fa-triangle-exclamation"></i> Please enter your mobile number.</span>';
            phoneInput.focus();
            return;
        }

        btnSendWaOtp.disabled = true;
        const originalText = btnSendWaOtp.innerHTML;
        btnSendWaOtp.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
        waOtpMsg.innerHTML = '<span class="text-gray-500">Connecting to WhatsApp Gateway...</span>';

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
                waOtpMsg.innerHTML = '<span class="text-green-600 font-semibold"><i class="fa-solid fa-check"></i> ' + (data.message || 'OTP sent successfully!') + '</span>';
                waOtpBox.classList.remove('hidden');
                waOtpInput.focus();
                startCountdown(60, waTimerSpan, btnResendWaOtp, btnSendWaOtp, waIntervalRef);
            } else {
                waOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
            }
        })
        .catch(err => {
            btnSendWaOtp.disabled = false;
            btnSendWaOtp.innerHTML = originalText;
            waOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.</span>';
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
            waOtpMsg.innerHTML = '<span class="text-red-500 font-semibold">Please enter the 6-digit OTP received on WhatsApp.</span>';
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
                waOtpMsg.innerHTML = '<span class="text-green-600 font-semibold"><i class="fa-solid fa-circle-check"></i> ' + (data.message || 'WhatsApp number verified!') + '</span>';
                waOtpBox.classList.add('hidden');
                btnSendWaOtp.classList.add('hidden');
                waVerifiedBadge.classList.remove('hidden');
                phoneInput.readOnly = true;
                countryCodeSelect.disabled = true;
                phoneInput.classList.add('bg-green-50/50');
            } else {
                waOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-xmark"></i> ' + (data.message || 'Invalid code.') + '</span>';
                waOtpInput.select();
            }
        })
        .catch(err => {
            btnVerifyWaOtp.disabled = false;
            btnVerifyWaOtp.textContent = 'Verify OTP';
            waOtpMsg.innerHTML = '<span class="text-red-500 font-semibold">Verification failed. Please try again.</span>';
        });
    });

    // ─────────────────────────────────────────────────────────────
    // 3. Send Email OTP
    // ─────────────────────────────────────────────────────────────
    function doSendEmailOtp() {
        const email = emailInput.value.trim();

        if (!email || !email.includes('@')) {
            emailOtpMsg.innerHTML = '<span class="text-red-500"><i class="fa-solid fa-triangle-exclamation"></i> Please enter a valid email address.</span>';
            emailInput.focus();
            return;
        }

        btnSendEmailOtp.disabled = true;
        const originalText = btnSendEmailOtp.innerHTML;
        btnSendEmailOtp.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
        emailOtpMsg.innerHTML = '<span class="text-gray-500">Dispatching verification code to email...</span>';

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
                emailOtpMsg.innerHTML = '<span class="text-green-600 font-semibold"><i class="fa-solid fa-check"></i> ' + (data.message || 'Verification code sent to your email!') + '</span>';
                emailOtpBox.classList.remove('hidden');
                emailOtpInput.focus();
                startCountdown(60, emailTimerSpan, btnResendEmailOtp, btnSendEmailOtp, emailIntervalRef);
            } else {
                emailOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-exclamation"></i> ' + (data.message || 'Failed to send email verification.') + '</span>';
            }
        })
        .catch(err => {
            btnSendEmailOtp.disabled = false;
            btnSendEmailOtp.innerHTML = originalText;
            emailOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.</span>';
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
            emailOtpMsg.innerHTML = '<span class="text-red-500 font-semibold">Please enter the 6-digit OTP sent to your email.</span>';
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
                emailOtpMsg.innerHTML = '<span class="text-green-600 font-semibold"><i class="fa-solid fa-circle-check"></i> ' + (data.message || 'Email verified successfully!') + '</span>';
                emailOtpBox.classList.add('hidden');
                btnSendEmailOtp.classList.add('hidden');
                emailVerifiedBadge.classList.remove('hidden');
                emailInput.readOnly = true;
                emailInput.classList.add('bg-green-50/50');
            } else {
                emailOtpMsg.innerHTML = '<span class="text-red-500 font-semibold"><i class="fa-solid fa-circle-xmark"></i> ' + (data.message || 'Invalid code.') + '</span>';
                emailOtpInput.select();
            }
        })
        .catch(err => {
            btnVerifyEmailOtp.disabled = false;
            btnVerifyEmailOtp.textContent = 'Verify OTP';
            emailOtpMsg.innerHTML = '<span class="text-red-500 font-semibold">Verification failed. Please try again.</span>';
        });
    });

    // ─────────────────────────────────────────────────────────────
    // 5. Form Submission Guard
    // ─────────────────────────────────────────────────────────────
    signupForm.addEventListener('submit', function (e) {
        const errorContainer = signupForm.querySelector('.msg-error');

        if (!isPhoneVerified) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (errorContainer) {
                errorContainer.innerHTML = '<div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-600 font-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please verify your WhatsApp mobile number before signing up.</div>';
            }
            phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        if (!isEmailVerified) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (errorContainer) {
                errorContainer.innerHTML = '<div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-600 font-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please verify your email address before signing up.</div>';
            }
            emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        // Enable country_code so its value is included in POST payload even if disabled
        countryCodeSelect.disabled = false;
    });
});
</script>
