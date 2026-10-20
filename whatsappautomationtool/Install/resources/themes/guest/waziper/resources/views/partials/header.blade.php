<section x-data="{ mobileNavOpen: false }" class="bg-white z-50 sticky top-0 border-b border-gray-100 shadow-sm backdrop-blur-md bg-white/95">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <!-- Brand Logo -->
            <div class="w-auto">
                <a href="{{ url('') }}" class="inline-flex items-center">
                    <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" class="h-9 md:h-10 w-auto object-contain">
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="{{ url('') }}" class="text-sm font-semibold transition {{ request()->is('/') ? 'text-[#ff5500]' : 'text-gray-700 hover:text-[#ff5500]' }}">{{ __("Home") }}</a>
                <a href="{{ url('') }}#modules" class="text-sm font-semibold text-gray-700 hover:text-[#ff5500] transition">{{ __("Features") }}</a>
                <a href="{{ url('pricing') }}" class="text-sm font-semibold {{ request()->is('pricing') ? 'text-[#ff5500]' : 'text-gray-700 hover:text-[#ff5500]' }} transition">{{ __("Pricing") }}</a>
                <a href="{{ url('faqs') }}" class="text-sm font-semibold {{ request()->is('faqs') ? 'text-[#ff5500]' : 'text-gray-700 hover:text-[#ff5500]' }} transition">{{ __("FAQs") }}</a>
                <a href="{{ url('blogs') }}" class="text-sm font-semibold {{ request()->is('blogs*') ? 'text-[#ff5500]' : 'text-gray-700 hover:text-[#ff5500]' }} transition">{{ __("Blog") }}</a>
                <a href="{{ url('contact') }}" class="text-sm font-semibold {{ request()->is('contact') ? 'text-[#ff5500]' : 'text-gray-700 hover:text-[#ff5500]' }} transition">{{ __("Contact") }}</a>
            </div>

            <!-- Actions / Auth -->
            <div class="hidden lg:flex items-center space-x-5">
                <!-- Language Selector -->
                @php
                    $languages = Language::getLanguages();
                    $currentLang = app()->getLocale();
                @endphp
                @if($languages->isNotEmpty())
                <div class="dropdown dropdown-hover dropdown-end">
                    <div tabindex="0" class="flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-gray-600 hover:text-gray-900 cursor-pointer rounded-lg hover:bg-gray-50 border border-gray-100">
                        <i class="fa-solid fa-globe text-[#ff5500]"></i>
                        <span class="uppercase">{{ $currentLang }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[999] menu p-2 shadow-lg bg-white border border-gray-100 rounded-2xl w-36 text-xs">
                        @foreach($languages as $language)
                            <li>
                                <a href="{{ url('lang/' . $language->code) }}" class="flex items-center gap-2 py-2 rounded-xl {{ $currentLang == $language->code ? 'bg-[#fff2ea] text-[#ff5500] font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    @if($language->icon)
                                        <span class="size-4 text-center"><i class="{{ $language->icon }}"></i></span>
                                    @endif
                                    <span class="truncate">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(Auth::check())
                    <a href="{{ url('app/dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-semibold text-sm shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.35);">
                        <span>{{ __('Dashboard') }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <a href="{{ url('auth/login') }}" class="text-sm font-semibold text-gray-700 hover:text-[#ff5500] transition px-3 py-2">
                        {{ __("Sign In") }}
                    </a>
                    @if(get_option("auth_signup_page_status", 1))
                        <a href="{{ url('auth/signup') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-semibold text-sm shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.35);">
                            <span>{{ __("Sign Up") }}</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    @endif
                @endif
            </div>

            <!-- Mobile Hamburger -->
            <div class="lg:hidden">
                <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-2 rounded-xl text-gray-700 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div :class="{'block': mobileNavOpen, 'hidden': !mobileNavOpen}" class="hidden fixed top-0 left-0 bottom-0 w-5/6 max-w-sm z-50">
            <div x-on:click="mobileNavOpen = !mobileNavOpen" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"></div>
            <nav class="relative z-10 p-6 bg-white h-full overflow-y-auto flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                        <a href="{{ url('') }}">
                            <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" class="h-8 w-auto">
                        </a>
                        <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-2 text-gray-400 hover:text-gray-700">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <ul class="py-6 space-y-4">
                        <li><a href="{{ url('') }}" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("Home") }}</a></li>
                        <li><a href="{{ url('') }}#modules" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("Features") }}</a></li>
                        <li><a href="{{ url('pricing') }}" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("Pricing") }}</a></li>
                        <li><a href="{{ url('faqs') }}" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("FAQs") }}</a></li>
                        <li><a href="{{ url('blogs') }}" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("Blog") }}</a></li>
                        <li><a href="{{ url('contact') }}" class="block font-semibold text-gray-800 hover:text-[#ff5500]">{{ __("Contact") }}</a></li>
                    </ul>
                </div>
                <div class="pt-6 border-t border-gray-100 space-y-3">
                    @if(Auth::check())
                        <a href="{{ url('app/dashboard') }}" class="block w-full text-center py-3 rounded-full text-white font-semibold text-sm" style="background: #ff5500;">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ url('auth/login') }}" class="block w-full text-center py-3 rounded-full border border-gray-200 font-semibold text-gray-800 hover:bg-gray-50 text-sm">
                            {{ __("Sign In") }}
                        </a>
                        @if(get_option("auth_signup_page_status", 1))
                            <a href="{{ url('auth/signup') }}" class="block w-full text-center py-3 rounded-full text-white font-semibold text-sm" style="background: #ff5500;">
                                {{ __("Sign Up") }}
                            </a>
                        @endif
                    @endif
                </div>
            </nav>
        </div>
    </div>
</section>