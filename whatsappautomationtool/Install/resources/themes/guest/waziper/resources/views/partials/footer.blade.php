<style>
.wab-footer {
    background: #fafafa;
    border-top: 1px solid #f1f5f9;
    padding: 60px 0 36px 0;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
.wab-footer-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
}
.wab-footer-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 36px;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 24px;
}
.wab-footer-brand {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.wab-footer-brand img {
    height: 50px; max-height: 52px; object-fit: contain;
    width: auto;
    display: block;
}
.wab-footer-brand span {
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
}
.wab-footer-links {
    display: flex;
    align-items: center;
    gap: 24px;
    list-style: none;
    margin: 0;
    padding: 0;
    flex-wrap: wrap;
}
.wab-footer-links a {
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: color 0.2s ease;
}
.wab-footer-links a:hover {
    color: #ff5500;
}
.wab-footer-socials {
    display: flex;
    align-items: center;
    gap: 10px;
}
.wab-footer-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.wab-footer-social-btn:hover {
    color: #ff5500;
    border-color: #ffd8c2;
    transform: translateY(-2px);
}
.wab-footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 28px;
    font-size: 13px;
    color: #94a3b8;
    flex-wrap: wrap;
    gap: 14px;
}
.wab-footer-bottom a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s ease;
}
.wab-footer-bottom a:hover {
    color: #ff5500;
}
@media (max-width: 768px) {
    .wab-footer-top {
        flex-direction: column;
        align-items: flex-start;
    }
    .wab-footer-links {
        gap: 16px;
    }
    .wab-footer-bottom {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<footer class="wab-footer">
    <div class="wab-footer-container">
        <div class="wab-footer-top">
            <!-- Brand -->
            <div class="wab-footer-brand">
                <a href="{{ url('/') }}">
                    <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" onerror="this.src='/images/whatsappbulklogo.png'">
                </a>
                <span>{{ __('WhatsApp Marketing & Automation Tool') }}</span>
            </div>

            <!-- Links -->
            <ul class="wab-footer-links">
                <li><a href="{{ url('') }}">{{ __("Home") }}</a></li>
                <li><a href="{{ url('') }}#modules">{{ __("Features") }}</a></li>
                <li><a href="{{ url('pricing') }}">{{ __("Pricing") }}</a></li>
                <li><a href="{{ url('faqs') }}">{{ __("FAQs") }}</a></li>
                <li><a href="{{ url('blogs') }}">{{ __("Blog") }}</a></li>
                <li><a href="{{ url('contact') }}">{{ __("Contact") }}</a></li>
            </ul>

            <!-- Socials -->
            <div class="wab-footer-socials">
                <a class="wab-footer-social-btn" href="{{ get_option('social_page_facebook', 'https://facebook.com') }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                <a class="wab-footer-social-btn" href="{{ get_option('social_page_x', 'https://twitter.com') }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>
                <a class="wab-footer-social-btn" href="https://linkedin.com" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                <a class="wab-footer-social-btn" href="https://youtube.com" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="wab-footer-bottom">
            <div>&copy; {{ date('Y') }} Whatsappbulk. {{ __("All Rights Reserved.") }}</div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <a href="{{ url('privacy-policy') }}">{{ __("Privacy Policy") }}</a>
                <span>&bull;</span>
                <a href="{{ url('terms-of-service') }}">{{ __("Terms & Conditions") }}</a>
            </div>
        </div>
    </div>
</footer>