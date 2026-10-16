<style>
.wab-contact-wrap {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    position: relative;
    padding: 50px 0 90px 0;
    background: radial-gradient(circle at 85% 15%, rgba(255, 85, 0, 0.08) 0%, rgba(255, 250, 247, 0.6) 45%, #ffffff 80%);
    overflow: hidden;
    width: 100%;
}
.wab-contact-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
}
.wab-contact-header {
    max-width: 760px;
    margin: 0 auto 46px auto;
    text-align: center;
}
.wab-contact-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff2ea;
    color: #ff5500;
    border: 1px solid #ffd8c2;
    padding: 6px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 18px;
}
.wab-contact-title {
    font-size: 42px;
    font-weight: 800;
    line-height: 1.2;
    color: #0f172a;
    margin: 0 0 16px 0;
    letter-spacing: -0.8px;
}
.wab-contact-title span {
    color: #ff5500;
}
.wab-contact-desc {
    font-size: 17px;
    line-height: 1.65;
    color: #64748b;
    margin: 0 auto;
}

/* Two-Column Grid */
.wab-contact-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 32px;
    align-items: stretch;
}

/* Left Card (Direct Channels) */
.wab-channels-box {
    background: #ffffff;
    border: 1.5px solid #edf2f7;
    border-radius: 28px;
    padding: 36px 32px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.wab-channels-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 20px;
}
.wab-channel-tile {
    background: #fcfcfd;
    border: 1.5px solid #f1f5f9;
    border-radius: 20px;
    padding: 22px 20px;
    transition: all 0.25s ease;
}
.wab-channel-tile:hover {
    border-color: #ffd8c2;
    background: #ffffff;
    box-shadow: 0 6px 18px rgba(255, 85, 0, 0.08);
}
.wab-channel-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #ff5500;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.wab-channel-val {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.4;
    word-break: break-word;
    text-decoration: none !important;
}
.wab-channel-val:hover {
    color: #ff5500;
}
.wab-address-tile {
    background: #fcfcfd;
    border: 1.5px solid #f1f5f9;
    border-radius: 20px;
    padding: 22px 20px;
}

/* Right Card (Why Contact Us - Sleek High-Contrast) */
.wab-guidance-box {
    background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
    border: 1.5px solid #334155;
    border-radius: 28px;
    padding: 36px 32px;
    color: #ffffff;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.35);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.wab-guidance-header {
    margin-bottom: 24px;
}
.wab-guidance-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 85, 0, 0.2);
    color: #ffaa80;
    border: 1px solid rgba(255, 85, 0, 0.4);
    padding: 4px 14px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 14px;
}
.wab-guidance-title {
    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.3;
    margin: 0 0 10px 0;
}
.wab-guidance-desc {
    font-size: 14.5px;
    line-height: 1.6;
    color: #94a3b8;
    margin: 0;
}
.wab-guidance-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin: 24px 0;
}
.wab-guidance-item {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 16px 18px;
    backdrop-filter: blur(8px);
}
.wab-guidance-item h4 {
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.wab-guidance-item h4 i {
    color: #ff5500;
    font-size: 13px;
}
.wab-guidance-item p {
    font-size: 13px;
    color: #cbd5e1;
    line-height: 1.5;
    margin: 0;
}

/* Quick Chat Button */
.wab-contact-chat-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #ff5500;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 28px;
    border-radius: 9999px;
    text-decoration: none !important;
    box-shadow: 0 6px 20px rgba(255, 85, 0, 0.4);
    transition: all 0.25s ease;
    width: 100%;
}
.wab-contact-chat-btn:hover {
    background: #e64d00;
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(255, 85, 0, 0.5);
}

/* Responsive */
@media (max-width: 991px) {
    .wab-contact-grid {
        grid-template-columns: 1fr;
    }
    .wab-channels-grid {
        grid-template-columns: 1fr;
    }
    .wab-contact-title {
        font-size: 32px;
    }
}
</style>

<section class="wab-contact-wrap">
    <div class="wab-contact-container">
        
        <!-- Section Header (Clear of Sticky Navbar) -->
        <div class="wab-contact-header">
            <div class="wab-contact-badge">
                <i class="fas fa-headset"></i>
                {{ __("Contact us") }}
            </div>
            <h1 class="wab-contact-title">
                Talk to the team behind your <span>WhatsApp workflow</span>
            </h1>
            <p class="wab-contact-desc">
                {{ __("Reach out for onboarding help, pricing questions, custom sender volume, or support with a production WhatsApp setup.") }}
            </p>
        </div>

        <!-- Two-Column Contact Content -->
        <div class="wab-contact-grid">
            
            <!-- LEFT COLUMN: DIRECT CONTACT DETAILS -->
            <div class="wab-channels-box">
                <div>
                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 20px 0;">
                        {{ __("Direct Contact Channels") }}
                    </h3>

                    <div class="wab-channels-grid">
                        <!-- Website -->
                        <div class="wab-channel-tile">
                            <div class="wab-channel-label">
                                <i class="fas fa-globe"></i>
                                {{ __("Website") }}
                            </div>
                            <a href="{{ get_option('contact_company_website', 'https://whatsappbulk.youverse.in') }}" target="_blank" class="wab-channel-val">
                                {{ get_option('contact_company_website', 'https://whatsappbulk.youverse.in') }}
                            </a>
                        </div>

                        <!-- Email -->
                        <div class="wab-channel-tile">
                            <div class="wab-channel-label">
                                <i class="fas fa-envelope"></i>
                                {{ __("Email") }}
                            </div>
                            <a href="mailto:{{ get_option('contact_email', 'support@youverse.in') }}" class="wab-channel-val">
                                {{ get_option('contact_email', 'support@youverse.in') }}
                            </a>
                        </div>

                        <!-- Phone -->
                        <div class="wab-channel-tile">
                            <div class="wab-channel-label">
                                <i class="fas fa-phone"></i>
                                {{ __("Phone / WhatsApp") }}
                            </div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_option('contact_phone_number', '+919095111682')) }}" target="_blank" class="wab-channel-val">
                                {{ get_option('contact_phone_number', '+91 90951 11682') }}
                            </a>
                        </div>

                        <!-- Working hours -->
                        <div class="wab-channel-tile">
                            <div class="wab-channel-label">
                                <i class="fas fa-clock"></i>
                                {{ __("Working hours") }}
                            </div>
                            <div class="wab-channel-val">
                                {{ get_option('contact_working_hours', 'Mon - Sat: 09:30 AM - 06:30 PM') }}
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="wab-address-tile">
                        <div class="wab-channel-label">
                            <i class="fas fa-location-dot"></i>
                            {{ __("Office Location") }}
                        </div>
                        <div class="wab-channel-val" style="font-size: 15px;">
                            {{ get_option('contact_location', 'Youverse Technology, Bangalore, India') }}
                        </div>
                    </div>
                </div>

                <div style="margin-top: 24px;">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_option('contact_phone_number', '+919095111682')) }}?text=Hello%20Whatsappbulk%20Support" target="_blank" class="wab-contact-chat-btn">
                        <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                        <span>{{ __("Start Instant WhatsApp Chat") }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: GUIDANCE & SUPPORT SCOPE (100% VISIBLE TEXT) -->
            <div class="wab-guidance-box">
                <div class="wab-guidance-header">
                    <div class="wab-guidance-badge">
                        <i class="fas fa-shield-halved"></i>
                        {{ __("Why contact us") }}
                    </div>
                    <h2 class="wab-guidance-title">
                        {{ __("Get practical guidance, not generic support replies") }}
                    </h2>
                    <p class="wab-guidance-desc">
                        {{ __("We help businesses deploy high-speed WhatsApp marketing, auto replies, chatbots, and participant exports with zero friction.") }}
                    </p>
                </div>

                <div class="wab-guidance-list">
                    <div class="wab-guidance-item">
                        <h4><i class="fas fa-rocket"></i> {{ __("Onboarding & Setup") }}</h4>
                        <p>{{ __("Need assistance linking WhatsApp accounts, configuring sender numbers, proxies, or setting up auto-reply rules.") }}</p>
                    </div>

                    <div class="wab-guidance-item">
                        <h4><i class="fas fa-chart-line"></i> {{ __("Pricing & Custom Volume") }}</h4>
                        <p>{{ __("Planning high-volume bulk campaigns? We map the ideal plan and dedicated capacity for your team.") }}</p>
                    </div>

                    <div class="wab-guidance-item">
                        <h4><i class="fas fa-network-wired"></i> {{ __("Operational Workflows") }}</h4>
                        <p>{{ __("Lead qualification, AI smart replies, chatbot flows, and export tools tailored to your operational processes.") }}</p>
                    </div>
                </div>

                <div>
                    <a href="mailto:{{ get_option('contact_email', 'support@youverse.in') }}?subject=Inquiry:%20Whatsappbulk%20Platform" class="wab-contact-chat-btn" style="background: rgba(255, 255, 255, 0.12); border: 1.5px solid rgba(255, 255, 255, 0.3); box-shadow: none;">
                        <i class="fas fa-envelope"></i>
                        <span>{{ __("Email Our Specialist Team") }}</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>