<footer class="pt-16 pb-12 border-t border-gray-100 bg-[#fafafa]">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between pb-10 border-b border-gray-200 gap-6">
            <!-- Brand Column -->
            <div class="flex flex-col items-center md:items-start">
                <a href="{{ url('/') }}" class="inline-flex items-center mb-2">
                    <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" class="h-9 w-auto object-contain">
                </a>
                <span class="text-xs font-medium text-gray-400">WhatsApp Marketing Tool</span>
            </div>

            <!-- Footer Links -->
            <ul class="flex flex-wrap items-center justify-center gap-6 md:gap-8 text-sm font-semibold text-gray-700">
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('') }}">{{ __("Home") }}</a></li>
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('') }}#modules">{{ __("Features") }}</a></li>
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('pricing') }}">{{ __("Pricing") }}</a></li>
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('faqs') }}">{{ __("FAQs") }}</a></li>
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('blogs') }}">{{ __("Blog") }}</a></li>
                <li><a class="hover:text-[#ff5500] transition" href="{{ url('contact') }}">{{ __("Contact") }}</a></li>
            </ul>

            <!-- Social Links -->
            <div class="flex items-center space-x-4">
                <a class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:text-[#ff5500] hover:border-[#ff5500] transition shadow-xs" href="{{ get_option('social_page_facebook', 'https://facebook.com') }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f text-sm"></i></a>
                <a class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:text-[#ff5500] hover:border-[#ff5500] transition shadow-xs" href="{{ get_option('social_page_x', 'https://twitter.com') }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter text-sm"></i></a>
                <a class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:text-[#ff5500] hover:border-[#ff5500] transition shadow-xs" href="https://linkedin.com" target="_blank" rel="noopener"><i class="fab fa-linkedin-in text-sm"></i></a>
                <a class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:text-[#ff5500] hover:border-[#ff5500] transition shadow-xs" href="https://youtube.com" target="_blank" rel="noopener"><i class="fab fa-youtube text-sm"></i></a>
            </div>
        </div>

        <!-- Copyright & Legal -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
            <p>© {{ date('Y') }}. {{ __("All Rights Reserved.") }}</p>
            <div class="flex items-center space-x-6">
                <a class="hover:text-[#ff5500] transition" href="{{ url('privacy-policy') }}">{{ __("Privacy Policy") }}</a>
                <span class="text-gray-300">|</span>
                <a class="hover:text-[#ff5500] transition" href="{{ url('terms-of-service') }}">{{ __("Terms & Conditions") }}</a>
            </div>
        </div>
    </div>
</footer>