<style>
.wab-nav-bar {
    background: #d5f0c6; border-bottom: 1px solid rgba(22, 163, 74, 0.2);
    border-bottom: 1px solid rgba(22, 163, 74, 0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
.wab-nav-inner {
    max-width: 1240px;
    margin: 0 auto;
    padding: 10px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.wab-nav-logo img {
    height: 68px; max-height: 72px; object-fit: contain; transition: transform 0.2s ease;
    width: auto;
    display: block;
}
.wab-nav-menu {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.wab-menu-link {
    font-size: 14.5px;
    font-weight: 600;
    color: #475569;
    padding: 7px 16px;
    border-radius: 9999px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.wab-menu-link:hover {
    color: #15803d;
}
.wab-menu-link.active {
    background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
    color: #ffffff !important;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.35);
}
.wab-nav-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}
.wab-btn-signin {
    font-size: 14.5px;
    font-weight: 700;
    color: #334155 !important;
    padding: 8px 16px;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.wab-btn-signin:hover {
    color: #15803d !important;
}
.wab-btn-signup {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
    color: #ffffff !important;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 24px;
    border-radius: 9999px;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.35);
    transition: all 0.25s ease;
}
.wab-btn-signup:hover {
    background: linear-gradient(135deg, #16a34a 0%, #14532d 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(22, 163, 74, 0.45);
}
.wab-mobile-toggle {
    display: none;
    background: transparent;
    border: none;
    font-size: 22px;
    color: #1e293b;
    cursor: pointer;
    padding: 6px;
}
@media (max-width: 991px) {
    .wab-nav-menu,
    .wab-nav-actions {
        display: none !important;
    }
    .wab-mobile-toggle {
        display: block !important;
    }
}
</style>

<section x-data="{ mobileNavOpen: false }" class="wab-nav-bar">
    <div class="wab-nav-inner">
        <!-- Brand Logo -->
        <div class="wab-nav-logo">
            <a href="{{ url('') }}" style="display: inline-flex; align-items: center; text-decoration: none;">
                <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" onerror="this.src='/images/whatsappbulklogo.png'">
            </a>
        </div>

        <!-- Desktop Navigation Links (Centered, pill style matching Image 3) -->
        <nav class="wab-nav-menu">
            <a href="{{ url('') }}" class="wab-menu-link {{ request()->is('/') ? 'active' : '' }}">{{ __("Home") }}</a>
            <a href="{{ url('') }}#modules" class="wab-menu-link">{{ __("Features") }}</a>
            <a href="{{ url('pricing') }}" class="wab-menu-link {{ request()->is('pricing') ? 'active' : '' }}">{{ __("Pricing") }}</a>
            <a href="{{ url('faqs') }}" class="wab-menu-link {{ request()->is('faqs') ? 'active' : '' }}">{{ __("FAQs") }}</a>
            <a href="{{ url('blogs') }}" class="wab-menu-link {{ request()->is('blogs*') ? 'active' : '' }}">{{ __("Blog") }}</a>
            <a href="{{ url('contact') }}" class="wab-menu-link {{ request()->is('contact') ? 'active' : '' }}">{{ __("Contact") }}</a>
        </nav>

        <!-- Desktop Auth Actions (Matching Image 3) -->
        <div class="wab-nav-actions">
            @if(Auth::check())
                <a href="{{ url('app/dashboard') }}" class="wab-btn-signup">
                    <span>{{ __('Dashboard') }}</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ url('auth/login') }}" class="wab-btn-signin">
                    {{ __("Sign In") }}
                </a>
                @if(get_option("auth_signup_page_status", 1))
                    <a href="{{ url('auth/signup') }}" class="wab-btn-signup">
                        <span>{{ __("Sign Up") }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            @endif
        </div>

        <!-- Mobile Hamburger Toggle -->
        <button x-on:click="mobileNavOpen = !mobileNavOpen" class="wab-mobile-toggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileNavOpen" x-cloak style="position: fixed; inset: 0; z-index: 2000;">
        <div x-on:click="mobileNavOpen = false" style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);"></div>
        <nav style="position: relative; width: 85%; max-width: 320px; height: 100%; background: #d5f0c6; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 20px 40px rgba(0,0,0,0.3); z-index: 10;">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 20px; border-bottom: 1px solid rgba(22, 163, 74, 0.2);">
                    <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" style="height: 32px; width: auto;">
                    <button x-on:click="mobileNavOpen = false" style="background: transparent; border: none; font-size: 20px; color: #64748b; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px; padding: 24px 0;">
                    <a href="{{ url('') }}" style="font-size: 16px; font-weight: 700; color: #15803d; text-decoration: none;">{{ __("Home") }}</a>
                    <a href="{{ url('') }}#modules" style="font-size: 16px; font-weight: 600; color: #1e293b; text-decoration: none;">{{ __("Features") }}</a>
                    <a href="{{ url('pricing') }}" style="font-size: 16px; font-weight: 600; color: #1e293b; text-decoration: none;">{{ __("Pricing") }}</a>
                    <a href="{{ url('faqs') }}" style="font-size: 16px; font-weight: 600; color: #1e293b; text-decoration: none;">{{ __("FAQs") }}</a>
                    <a href="{{ url('blogs') }}" style="font-size: 16px; font-weight: 600; color: #1e293b; text-decoration: none;">{{ __("Blog") }}</a>
                    <a href="{{ url('contact') }}" style="font-size: 16px; font-weight: 600; color: #1e293b; text-decoration: none;">{{ __("Contact") }}</a>
                </div>
            </div>
            <div style="padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 10px;">
                @if(Auth::check())
                    <a href="{{ url('app/dashboard') }}" class="wab-btn-signup" style="justify-content: center; width: 100%;">
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                @else
                    <a href="{{ url('auth/login') }}" class="wab-btn-signin" style="text-align: center; border: 1.5px solid #e2e8f0; border-radius: 9999px; padding: 12px;">
                        {{ __("Sign In") }}
                    </a>
                    @if(get_option("auth_signup_page_status", 1))
                        <a href="{{ url('auth/signup') }}" class="wab-btn-signup" style="justify-content: center; width: 100%;">
                            <span>{{ __("Sign Up") }}</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                @endif
            </div>
        </nav>
    </div>
</section>