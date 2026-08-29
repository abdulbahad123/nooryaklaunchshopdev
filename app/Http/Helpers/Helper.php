<?php

use App\Http\Helpers\UserPermissionHelper;
use App\Models\CustomerWishList;
use App\Models\Language;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\ProductVariation;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserShopSetting;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemReview;
use App\Models\User\UserPageContent;
use App\Models\User\UserPaymentGeteway;
use Carbon\Carbon;


if (!function_exists('truncateString')) {
    function truncateString($string, $maxLength)
    {
        return strlen($string) > $maxLength ? mb_substr($string, 0, $maxLength, 'UTF-8') . '...' : $string;
    }
}


if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue(array $values)
    {

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }
}


if (!function_exists('replaceBaseUrl')) {
    function replaceBaseUrl($html)
    {
        $startDelimiter = 'src="';
        $endDelimiter = '/assets/front/img/summernote';
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($html, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $html;
    }
}

if (!function_exists('convertUtf8')) {
    function convertUtf8($value)
    {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }
}

if (!function_exists('moveAiStorageImageToPublicAssets')) {
    function moveAiStorageImageToPublicAssets(string $aiUrlOrPath, string $destDir): string
    {
        $ai = trim($aiUrlOrPath);

        // strip domain if full url
        if (str_starts_with($ai, url('/'))) {
            $ai = str_replace(url('/'), '', $ai);
        }

        if (!str_starts_with($ai, '/')) {
            $ai = '/' . $ai;
        }

        // expected: /storage/ai/categories/xxx.png
        if (!str_starts_with($ai, '/storage/')) {
            throw new \RuntimeException('Invalid AI image path.');
        }

        // convert to disk('public') path: storage/app/public/ai/categories/xxx.png
        $relative = str_replace('/storage/', '', $ai);
        $source = storage_path('app/public/' . $relative);

        if (!file_exists($source)) {
            throw new \RuntimeException('AI image file not found on server.');
        }

        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }

        $ext = pathinfo($source, PATHINFO_EXTENSION) ?: 'png';
        $filename = time() . '_' . uniqid() . '.' . $ext;

        $dest = rtrim($destDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!@copy($source, $dest)) {
            throw new \RuntimeException('Failed to move AI image.');
        }

        return $filename;
    }
}

if (!function_exists('make_slug')) {
    function make_slug($string)
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace("/", "", $slug);
        $slug = str_replace("?", "", $slug);
        $slug = str_replace("(", "", $slug);
        $slug = str_replace(")", "", $slug);
        $slug = str_replace("%", "", $slug);
        $slug = str_replace("&", "-", $slug);
        return mb_strtolower($slug, 'UTF-8');
    }
}

if (!function_exists('make_input_name')) {
    function make_input_name($string)
    {
        return preg_replace('/\s+/u', '_', trim($string));
    }
}

if (!function_exists('hasCategory')) {
    function hasCategory($version)
    {
        if (strpos($version, "no_category") !== false) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('isDark')) {
    function isDark($version)
    {
        if (strpos($version, "dark") !== false) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('slug_create')) {
    function slug_create($val)
    {
        $slug = preg_replace('/\s+/u', '-', trim($val));
        $slug = str_replace("/", "", $slug);
        $slug = str_replace("?", "", $slug);
        return mb_strtolower($slug, 'UTF-8');
    }
}

if (!function_exists('hex2rgb')) {
    function hex2rgb($colour)
    {
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }
}

if (!function_exists('getHref')) {
    function getHref($link)
    {
        $href = "#";

        if ($link["type"] == 'home') {
            $href = route('front.index');
        } else if ($link["type"] == 'profiles') {
            $href = route('front.user.view');
        } else if ($link["type"] == 'listings') {
            $href = route('front.user.view');
        } else if ($link["type"] == 'pricing') {
            $href = route('front.pricing');
        } else if ($link["type"] == 'faq') {
            $href = route('front.faq.view');
        } else if ($link["type"] == 'blog') {
            $href = route('front.blogs');
        } else if ($link["type"] == 'contact') {
            $href = route('front.contact');
        } else if ($link["type"] == 'templates') {
            $href = route('front.templates.view');
        } else if ($link["type"] == 'about') {
            $href = route('front.about');
        } else if ($link["type"] == 'custom') {
            if (empty($link["href"])) {
                $href = "#";
            } else {
                $href = $link["href"];
            }
        } else {
            $pageid = (int) $link["type"];
            $page = Page::find($pageid);
            if (!empty($page)) {
                $href = route('front.dynamicPage', [$page->slug]);
            } else {
                $href = "#";
            }
        }

        return $href;
    }
}

if (!function_exists('create_menu')) {
    function create_menu($arr)
    {
        echo '<ul class="sub-menu">';

        foreach ($arr["children"] as $el) {

            // determine if the class is 'submenus' or not
            $class = 'class="nav-item"';
            if (array_key_exists("children", $el)) {
                $class = 'class="nav-item submenus"';
            }
            // determine the href
            $href = getHref($el);

            echo '<li ' . $class . '>';
            echo '<a  href="' . $href . '" target="' . $el["target"] . '">' . $el["text"] . '</a>';
            if (array_key_exists("children", $el)) {
                create_menu($el);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

if (!function_exists('format_price')) {

    function format_price($value): string
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()
                ->get('lang'))
                ->first();
        } else {
            $currentLang = Language::where('is_default', 1)
                ->first();
        }
        $bex = $currentLang->basic_extended;
        if ($bex->base_currency_symbol_position == 'left') {
            return $bex->base_currency_symbol . $value;
        } else {
            return  $value . $bex->base_currency_symbol;
        }
    }
}


if (!function_exists('currency_converter')) {
    function currency_converter($value): string
    {
        $userCurrentCurr = app('userCurrentCurr');
        $userDefaultCurrency = app('userDefaultCurrency');

        if ($userDefaultCurrency->id != $userCurrentCurr->id) {
            $price = $value * $userCurrentCurr->value;
        } else {
            $price = $value;
        }
        return number_format($price, 2, '.', '');
    }
}

if (!function_exists('currency_converter_shipping')) {

    function currency_converter_shipping($value, $shipping_id): string
    {
        $userCurrentCurr = app('userCurrentCurr');
        $userDefaultCurrency = app('userDefaultCurrency');
        if ($userDefaultCurrency->id != $userCurrentCurr->id) {
            $price = $value * $userCurrentCurr->value;
        } else {
            $price = $value;
        }
        return round($price, 2);
    }
}

if (!function_exists('change_curreny_value')) {
    function change_curreny_value($value, $id, $previous_currency_id)
    {
        $currency = UserCurrency::where('id', $id)->first();
        $previous_currency = UserCurrency::where('id', $previous_currency_id)->first();

        //if selected currency and current currency not equal
        if ($currency->id != $previous_currency_id) {
            if ($previous_currency->is_default == 1) {
                $price = $value * $currency->value;
            } else {
                $price = $value / $previous_currency->value;
            }
        } else {
            $price = $value;
        }
        return round($price, 2);
    }
}

if (!function_exists('currency_sign')) {

    function currency_sign(): string
    {
        $userCurrentCurr = app('userCurrentCurr');
        $curr_sign = $userCurrentCurr->symbol;
        return $curr_sign;
    }
}

if (!function_exists('currency_value')) {

    function currency_value(): string
    {
        $userCurrentCurr = app('userCurrentCurr');

        $curr_value = $userCurrentCurr->value;
        return $curr_value;
    }
}

if (!function_exists('currency_converter_user')) {

    function currency_converter_user($value, $currency_id): string
    {
        if (empty($value)) {
            $value = 0;
        }
        $data = UserCurrency::where('is_default', 1)->where('user_id',  Auth::guard('web')->user()->id)->first();
        $userCurrentCurrID = $data->id;
        $userCurrentCurrValue = $data->value;

        $order_curr  = UserCurrency::where('id', $currency_id)->first();

        if ($currency_id != $userCurrentCurrID) {
            $price = ($value / $order_curr->value) * $userCurrentCurrValue;
            $price_new = number_format($price, 2);
        } else {
            $price_new = $value;
        }

        return $price_new;
    }
}

if (!function_exists('currency_sign_user')) {

    function currency_sign_user(): string
    {
        $data = UserCurrency::where('is_default', 1)->where('user_id',  Auth::guard('web')->user()->id)->first();
        $sign = $data->symbol;
        return $sign;
    }
}
if (!function_exists('user_currency')) {

    function user_currency($id)
    {
        $user_id = getUser()->id;
        $data = UserCurrency::where('id', $id)->select('symbol', 'symbol_position', 'user_id')->first();

        if (is_null($data) || $data->user_id != $user_id) {
            $data = UserCurrency::where([['is_default', 1], ['user_id', $user_id]])->select('symbol', 'symbol_position', 'user_id')->first();
        }
        return $data;
    }
}
if (!function_exists('symbolPrice')) {
    function symbolPrice($position, $symbol, $price)
    {
        if ($symbol == '$') {
            $symbol = '₹';
        }
        if ($position == 'left') {
            $value = $symbol . $price;
        } else {
            $value = $price . $symbol;
        }

        return $value;
    }
}

if (!function_exists('textPrice')) {
    function textPrice($position, $text, $price)
    {
        if ($text == 'USD') {
            $text = 'INR';
        }
        if ($text == '$') {
            $text = '₹';
        }
        if ($position == 'left') {
            $value = $text . ' ' . $price;
        } else {
            $value = $price . ' ' . $text;
        }

        return $value;
    }
}

if (!function_exists('currencyPrice')) {

    function currencyPrice($currency_id, $price)
    {
        $currency = UserCurrency::where('id', $currency_id)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            if ($symbol == '$') {
                $symbol = '₹';
            }
            if ($currency->symbol_position == 'left') {
                $value = $symbol . $price;
            } else {
                $value = $price . $symbol;
            }
            return $value;
        }
    }
}

if (!function_exists('currencyTextPrice')) {

    function currencyTextPrice($currency_id, $price)
    {
        $currency = UserCurrency::where('id', $currency_id)->first();
        if ($currency) {
            $text = $currency->text;
            if ($text == 'USD') {
                $text = 'INR';
            }
            if ($currency->text_position == 'left') {
                $value = $text . ' ' . $price;
            } else {
                $value = $price . ' ' . $text;
            }
            return $value;
        }
    }
}

if (!function_exists('userSymbolPrice')) {

    function userSymbolPrice($price, $position, $symbol)
    {
        if (is_null($price)) {
            $price = 0;
        }
        if ($symbol == '$') {
            $symbol = '₹';
        }
        if ($position == 'left') {
            $value = $symbol . $price;
        } else {
            $value = $price . $symbol;
        }
        return $value;
    }
}



if (!function_exists('getUserHref')) {
    function getUserHref($link, $lang_id = null)
    {
        $href = "#";
        if ($link["type"] == 'home') {
            $href = route('front.user.detail.view', getParam());
        } else if ($link["type"] == 'blog') {
            $href = route('front.user.blogs', getParam());
        } else if ($link["type"] == 'contact') {
            $href = route('front.user.contact', getParam());
        } else if ($link["type"] == 'about') {
            $href = route('front.user.about', getParam());
        } else if ($link["type"] == 'faq') {
            $href = route('front.user.faq', getParam());
        } else if ($link["type"] == 'shop') {
            $href = route('front.user.shop', getParam());
        } else if ($link["type"] == 'custom') {
            if (empty($link["href"])) {
                $href = "#";
            } else {
                $href = $link["href"];
            }
        } else {
            $pageid = (int)$link["type"];
            $page = UserPageContent::where([['page_id', $pageid], ['language_id', $lang_id]])->first();
            if (!empty($page)) {
                $href = route('front.user.custom.page', [getParam(), $page->slug]);
            } else {
                $href = "#";
            }
        }
        return $href;
    }
}

if (!function_exists('currency_converter_customer')) {

    function currency_converter_customer($value, $order_currency_id): string
    {
        if (empty($value)) {
            $value = 0;
        }
        $userCurrentCurr = app('userCurrentCurr');
        $data = UserCurrency::find($userCurrentCurr->id);
        $userCurrentCurrID = $data->id;
        $userCurrentCurrValue = $data->value;

        $order_curr  = UserCurrency::where('id', $order_currency_id)->first();

        if ($order_currency_id != $userCurrentCurrID) {
            $price = ($value / $order_curr->value) * $userCurrentCurrValue;
            $price_new = round($price, 2);
        } else {
            $price_new = $value;
        }

        return $price_new;
    }
}

if (!function_exists('reviewCount')) {

    function reviewCount($id)
    {
        $data = UserItemReview::where('item_id', $id)->count();
        return $data;
    }
}


if (!function_exists('isAgencyDomain')) {
    function isAgencyDomain($host = null)
    {
        if (empty($host)) {
            $host = request()->getHost();
        }
        $cleanHost = preg_replace('/^(www|app)\./i', '', strtolower($host));

        $knownAgencies = ['cockroachjantaparty.top', 'maturednature.com', 'maturenatu'];
        foreach ($knownAgencies as $agencyHost) {
            if (str_contains($cleanHost, $agencyHost)) {
                return true;
            }
        }

        try {
            $agency = \Illuminate\Support\Facades\DB::table('agencies')
                ->where(function ($q) use ($cleanHost) {
                    $q->where('custom_domain', $cleanHost)
                      ->orWhere('custom_domain', 'www.' . $cleanHost)
                      ->orWhere('custom_domain', 'https://' . $cleanHost)
                      ->orWhere('custom_domain', 'http://' . $cleanHost);
                })
                ->first();

            if (!empty($agency)) {
                return true;
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return false;
    }
}

if (!function_exists('getParam')) {
    function getParam()
    {
        $user = getUser();
        if (!empty($user) && !empty($user->username)) {
            return strtolower($user->username);
        }

        $parsedUrl = parse_url(url()->current());
        $currentPath = $parsedUrl['path'] ?? '/';
        $pathSegments = explode('/', trim($currentPath, '/'));
        $firstSegment = $pathSegments[0] ?? null;

        $reservedKeywords = ['admin', 'user', 'front', 'api', 'login', 'register', 'checkout', 'templates', 'shops', 'pricing', 'blogs', 'contact', 'faqs', 'whitelabel-panel', 'master', 'product', 'cart', 'shop', 'page', 'about', 'privacy-policy', 'terms-and-conditions', 'terms-conditions', 'refund-policy', 'shipping-policy', 'x9_admin-portal_v7'];

        if (!empty($firstSegment) && !in_array(strtolower($firstSegment), $reservedKeywords)) {
            return strtolower(urldecode($firstSegment));
        }

        return 'default';
    }
}

// checks if 'current package has subdomain ?'

if (!function_exists('cPackageHasSubdomain')) {
    function cPackageHasSubdomain($user)
    {
        if (empty($user) || !is_object($user) || empty($user->id)) {
            return false;
        }
        $currPackageFeatures = UserPermissionHelper::packagePermission($user->id);
        $currPackageFeatures = json_decode($currPackageFeatures, true);

        // if the current package does not contain subdomain
        if (empty($currPackageFeatures) || !is_array($currPackageFeatures) || !in_array('Subdomain', $currPackageFeatures)) {
            return false;
        }
        return true;
    }
}


// checks if 'current package has customdomain ?'

if (!function_exists('cPackageHasCdomain')) {
    function cPackageHasCdomain($user)
    {
        if (empty($user) || !is_object($user) || empty($user->id)) {
            return false;
        }
        $currPackageFeatures = UserPermissionHelper::packagePermission($user->id);
        $currPackageFeatures = json_decode($currPackageFeatures, true);

        // if the current package does not contain customdomain
        if (empty($currPackageFeatures) || !is_array($currPackageFeatures) || !in_array('Custom Domain', $currPackageFeatures)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('getCdomain')) {
    function getCdomain($user)
    {
        if (empty($user) || !is_object($user) || empty($user->id)) {
            return false;
        }
        $cdomains = $user->custom_domains()->where('status', 1);
        return $cdomains->count() > 0 ? $cdomains->orderBy('id', 'DESC')->first()->requested_domain : false;
    }
}



if (!function_exists('getUser')) {

    function getUser()
    {
        // ── Resolve the real request host ────────────────────────────────────
        // Use $_SERVER['HTTP_HOST'] (real incoming host) not APP_URL.
        $requestHost = isset($_SERVER['HTTP_HOST'])
            ? strtolower(str_replace('www.', '', $_SERVER['HTTP_HOST']))
            : strtolower(str_replace('www.', '', (string) env('WEBSITE_HOST', 'localhost')));

        // ── Resolve the real request path ────────────────────────────────────
        // IMPORTANT: On cPanel, the root .htaccess rewrites /manti → public/manti.
        // $_SERVER['REQUEST_URI'] would then be /public/manti instead of /manti.
        // Laravel's request()->path() correctly resolves to just "manti" in all cases.
        try {
            $requestPath = '/' . ltrim(app('request')->path(), '/');
        } catch (\Exception $e) {
            // Fallback if request is not available (console / boot phase)
            $requestPath = $_SERVER['REQUEST_URI'] ?? '/';
            if (($q = strpos($requestPath, '?')) !== false) {
                $requestPath = substr($requestPath, 0, $q);
            }
            // Strip known prefixes
            foreach (['/public/', '/public'] as $prefix) {
                if (strpos($requestPath, $prefix) === 0) {
                    $requestPath = substr($requestPath, strlen($prefix) - 1);
                    break;
                }
            }
            $appPath = parse_url(env('APP_URL'), PHP_URL_PATH) ?? '';
            if (!empty($appPath) && strpos($requestPath, $appPath) === 0) {
                $requestPath = substr($requestPath, strlen($appPath));
            }
        }

        $pathSegments      = explode('/', trim($requestPath, '/'));
        $usernameFromPath  = $pathSegments[0] ?? null;
        $subdomainBaseHosts = array_values(array_unique(array_filter([
            strtolower((string) env('WEBSITE_HOST', '')),
            'launchshop.in',
        ])));

        $reservedKeywords = [
            'admin', 'user', 'front', 'api', 'login', 'register', 'checkout',
            'templates', 'shops', 'pricing', 'blogs', 'contact', 'faqs',
            'whitelabel-panel', 'master', 'product', 'cart', 'shop', 'page',
            'about', 'privacy-policy', 'terms-and-conditions', 'terms-conditions',
            'refund-policy', 'shipping-policy', 'assets', 'storage',
            'favicon.ico', 'sitemap.xml', 'robots.txt', 'public',
            'x9_admin-portal_v7',
        ];

        // ── CASE 1: path-based tenant ─────────────────────────────────────
        // Works for: launchshop.in/manti  OR  agency.top/manti
        // online_status is the middleware's job — NOT checked here.
        if (!empty($usernameFromPath) && !in_array(strtolower($usernameFromPath), $reservedKeywords)) {
            $rawUsername  = strtolower(urldecode($usernameFromPath));
            $cleanUsername = str_replace(' ', '', $rawUsername);

            $pathUser = User::where(function ($query) use ($rawUsername, $cleanUsername) {
                    $query->where('username', $rawUsername)
                        ->orWhere('username', $cleanUsername);
                })
                ->where(function ($q) {
                    $q->where('preview_template', 1)->orWhere('status', 1);
                })
                ->first();
            if ($pathUser) {
                return $pathUser;
            }
        }

        // ── CASE 2: subdomain of supported base host(s)  ───────────────────
        // e.g. manti.launchshop.in
        foreach ($subdomainBaseHosts as $websiteHost) {
            if (empty($websiteHost)
                || $requestHost === $websiteHost
                || !str_ends_with($requestHost, '.' . $websiteHost)
            ) {
                continue;
            }

            $sub  = explode('.', $requestHost)[0];
            $user = User::where('username', $sub)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->where('preview_template', 1)
                        ->orWhereHas('memberships', function ($q) {
                            $q->where('status', 1)
                                ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                                ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
                        });
                })
                ->first();

            if (empty($user)) {
                return null;
            }
            if ($user->online_status != 1 && $user->preview_template != 1) {
                return null;
            }
            if (!cPackageHasSubdomain($user)) {
                return null;
            }
            return $user;
        }

        // ── CASE 3: fully custom domain  ──────────────────────────────────
        $cleanCustomHost = preg_replace('/^https?:\/\//i', '', strtolower($requestHost));
        $cleanCustomHost = preg_replace('/^www\./i', '', $cleanCustomHost);

        try {
            $cDomain = \App\Models\User\UserCustomDomain::where(function ($q) use ($cleanCustomHost) {
                    $q->where('requested_domain', $cleanCustomHost)
                      ->orWhere('requested_domain', 'www.' . $cleanCustomHost)
                      ->orWhere('requested_domain', 'http://' . $cleanCustomHost)
                      ->orWhere('requested_domain', 'https://' . $cleanCustomHost)
                      ->orWhere('requested_domain', 'http://www.' . $cleanCustomHost)
                      ->orWhere('requested_domain', 'https://www.' . $cleanCustomHost);
                })
                ->where('status', 1)
                ->first();

            if ($cDomain) {
                $user = User::find($cDomain->user_id);
                if ($user) {
                    return $user;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return null;
    }
}

if (!function_exists('getUserNullCheck')) {

    function getUserNullCheck()
    {
        $user = getUser();
        if ($user && is_object($user) && isset($user->id)) {
            return $user;
        }

        try {
            $parsedUrl = parse_url(url()->current());
            $host = $parsedUrl['host'] ?? Request::getHost();
            $hostWithoutWww = str_replace('www.', '', $host);
            $hostWithWww    = 'www.' . $hostWithoutWww;

            return User::where('online_status', 1)
                ->where('status', 1)
                ->whereHas('user_custom_domains', function ($q) use ($hostWithWww, $hostWithoutWww) {
                    $q->where('status', 1)
                        ->where(function ($query) use ($hostWithWww, $hostWithoutWww) {
                            $query->where('requested_domain', $hostWithWww)
                                ->orWhere('requested_domain', $hostWithoutWww);
                        });
                })
                ->first();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('cartTotal')) {
    function cartTotal()
    {
        $user = getUser();
        if (!$user) return 0;
        $username = $user->username;
        $total = 0;
        if (session()->has('cart_' . $username) && !empty(session()->get('cart_' . $username))) {
            $cart = session()->get('cart_' . $username);
            $user_id = $user->id;
            if (!is_null($cart) && is_array($cart)) {
                $validCart = [];
                foreach ($cart as $key => $cartItem) {
                    if (isset($cartItem['user_id']) && $cartItem['user_id'] == $user_id && isset($cartItem['id'])) {
                        $exists = DB::table('user_items')->where('id', $cartItem['id'])->where('user_id', $user_id)->exists();
                        if ($exists) {
                            $itemTotal = (float)($cartItem['total'] ?? 0);
                            if ($itemTotal <= 0 && isset($cartItem['product_price']) && isset($cartItem['qty'])) {
                                $itemTotal = (float)$cartItem['product_price'] * (int)$cartItem['qty'];
                            }
                            $total += $itemTotal;
                            $validCart[$key] = $cartItem;
                        }
                    }
                }
                if (count($validCart) !== count($cart)) {
                    if (empty($validCart)) {
                        session()->forget('cart_' . $username);
                    } else {
                        session()->put('cart_' . $username, $validCart);
                    }
                }
            }
        }

        return round($total, 2);
    }
}

if (!function_exists('flashAmountStatus')) {
    function flashAmountStatus($porduct_id, $current_price)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s'); // Including seconds
        $product = DB::table('user_items')
            ->where([['user_items.flash', 1], ['id', $porduct_id]])
            ->where(function ($query) use ($now) {
                // 12-hour format handling
                $query->orWhere([
                    [DB::raw('CONCAT(user_items.start_date, " ", STR_TO_DATE(user_items.start_time, "%h:%i %p"))'), '<=', $now],
                    [DB::raw('CONCAT(user_items.end_date, " ", STR_TO_DATE(user_items.end_time, "%h:%i %p"))'), '>=', $now],
                ]);

                // 24-hour format handling
                $query->orWhere([
                    [DB::raw('CONCAT(user_items.start_date, " ", user_items.start_time)'), '<=', $now],
                    [DB::raw('CONCAT(user_items.end_date, " ", user_items.end_time)'), '>=', $now],
                ]);
            })->select('current_price', 'flash_amount')->first();

        if ($product) {
            $amount = $product->current_price - $product->current_price * ($product->flash_amount / 100);
            $data = [
                'amount' => $amount,
                'status' => true,
            ];
        } else {
            $amount = $current_price;
            $data = [
                'amount' => $amount,
                'status' => false,
            ];
        }
        return $data;
    }
}

if (!function_exists('cartSubTotal')) {
    function cartSubTotal()
    {
        $username = app('user')->username;
        $coupon = session()->has('user_coupon_' . $username) && !empty(session()->get('user_coupon_' . $username)) ? session()->get('user_coupon_' . $username) : 0;
        $cartTotal = cartTotal();
        $subTotal = $cartTotal - $coupon;

        return round($subTotal, 2);
    }
}

if (!function_exists('onlyDigitalItemsInCart')) {
    function onlyDigitalItemsInCart()
    {
        $username = app('user')->username;
        $cart = session()->get('cart_' . $username, []);
        if (!empty($cart)) {
            foreach ($cart as $key => $cartItem) {
                $item = UserItem::findorFail($cartItem["id"]);
                if ($item->type == 'digital') {
                    return true;
                }
            }
        }
        return false;
    }
}



if (!function_exists('onlyDigitalItems')) {
    function onlyDigitalItems($order)
    {

        $oitems = $order->orderitems;
        foreach ($oitems as $key => $oitem) {

            if ($oitem->item->type != 'digital') {
                return false;
            }
        }

        return true;
    }
}
if (!function_exists('tax')) {
    function tax()
    {
        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }
        
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
        if (!is_array($permissions) || !in_array('GST Billing', $permissions)) {
            return 0.00;
        }

        $bex = UserShopSetting::where('user_id', $user->id)->first();
        $tax = $bex ? $bex->tax : 0.00;
        if (session()->has('cart_' . $user->username) && !empty(session()->get('cart_' . $user->username))) {
            $tax = (cartSubTotal() * $tax) / 100;
        }

        return round($tax, 2);
    }
}
if (!function_exists('tax_percentage')) {
    function tax_percentage()
    {
        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }
        
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
        if (!is_array($permissions) || !in_array('GST Billing', $permissions)) {
            return 0.00;
        }

        $bex = UserShopSetting::where('user_id', $user->id)->first();
        return $bex ? $bex->tax : 0.00;
    }
}


if (!function_exists('coupon')) {
    function coupon()
    {
        return session()->has('coupon') && !empty(session()->get('coupon')) ? round(session()->get('coupon'), 2) : 0.00;
    }
}


if (!function_exists('detailsUrl')) {

    function detailsUrl($user)
    {
        $username = is_object($user) ? strtolower($user->username) : strtolower((string)$user);

        if (is_object($user) && method_exists($user, 'custom_domains')) {
            // Skip custom domain check for templates so they always preview on the platform subdomain/path
            if (empty($user->preview_template)) {
                $customDomain = getCdomain($user);
                if ($customDomain !== false) {
                    return '//' . ltrim($customDomain, '/');
                }
            }
        }

        $host = request()->getHost();
        $cleanHost = preg_replace('/^(www|app)\./i', '', strtolower($host));
        $mainHosts = array_filter([
            env('WEBSITE_HOST'),
            'launchshop.in',
            'nooryak.in',
            'localhost',
            '127.0.0.1'
        ]);

        $isMainSite = false;
        foreach ($mainHosts as $mHost) {
            if ($cleanHost === strtolower($mHost) || str_ends_with($cleanHost, '.' . strtolower($mHost))) {
                $isMainSite = true;
                break;
            }
        }

        // For Agency domain/subdomain (e.g. launchshop.cockroachjantaparty.top or wibro.launchshop.in):
        // Return path-based URL under the current agency host: https://{agency_host}/{username}
        if (!$isMainSite && !empty($host)) {
            $scheme = request()->getScheme() ?: 'https';
            return $scheme . '://' . $host . '/' . $username;
        }

        return '//' . $username . '.' . env('WEBSITE_HOST');
    }
}

if (!function_exists('ProductCountByCategory')) {

    function ProductCountByCategory($language_id, $category_id)
    {
        return UserItemContent::where([['language_id', $language_id], ['category_id', $category_id]])->count();
    }
}
if (!function_exists('hexToRgba')) {

    function hexToRgba($hex, $alpha = .5)
    {
        // Remove the hash at the start if it's there
        $hex = ltrim($hex, '#');

        // Parse the hex color
        if (strlen($hex) == 6) {
            list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
        } elseif (strlen($hex) == 3) {
            list($r, $g, $b) = sscanf($hex, "%1x%1x%1x");
            $r = $r * 17;
            $g = $g * 17;
            $b = $b * 17;
        } else {
            return '10, 71, 46';
        }

        // Ensure alpha is between 0 and 1
        $alpha = min(max($alpha, 0), 1);

        // Return the rgba color code
        return "$r, $g, $b";
    }
}

if (!function_exists('paytabInfo')) {
    function paytabInfo($type, $user_id = null)
    {
        if ($type == 'user') {
            $paytabs = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paytabs']])->first();
        } else {
            $paytabs = PaymentGateway::where('keyword', 'paytabs')->first();
        }
        $paytabsInfo = json_decode($paytabs->information, true);
        if ($paytabsInfo['country'] == 'global') {
            $currency = 'USD';
        } elseif ($paytabsInfo['country'] == 'sa') {
            $currency = 'SAR';
        } elseif ($paytabsInfo['country'] == 'uae') {
            $currency = 'AED';
        } elseif ($paytabsInfo['country'] == 'egypt') {
            $currency = 'EGP';
        } elseif ($paytabsInfo['country'] == 'oman') {
            $currency = 'OMR';
        } elseif ($paytabsInfo['country'] == 'jordan') {
            $currency = 'JOD';
        } elseif ($paytabsInfo['country'] == 'iraq') {
            $currency = 'IQD';
        } else {
            $currency = 'USD';
        }
        return [
            'server_key' => $paytabsInfo['server_key'],
            'profile_id' => $paytabsInfo['profile_id'],
            'url'        => $paytabsInfo['api_endpoint'],
            'currency'   => $currency,
        ];
    }


    function check_variation($item_id)
    {
        $product_variations = ProductVariation::where('item_id', $item_id)->count();
        return $product_variations;
    }
}

if (!function_exists('detectTextDirection')) {
    function detectTextDirection($text)
    {
        $length = mb_strlen($text, 'UTF-8');
        $rtlCount = 0;
        $ltrCount = 0;

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $direction = IntlChar::charDirection($char);

            if (
                $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_EMBEDDING
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_OVERRIDE
            ) {
                $rtlCount++;
            } else {
                $ltrCount++;
            }
        }

        if ($rtlCount > $ltrCount) {
            return 'rtl'; // Right-to-left
        } elseif ($ltrCount > $rtlCount) {
            return 'ltr'; // Left-to-right
        } else {
            return 'rtl'; // If both counts are equal, or if text is empty
        }
    }
}

if (!function_exists('flasSaleActive')) {
    function flasSaleActive($end_date, $end_time)
    {
        $date = Carbon::parse($end_date . ' ' . $end_time);
        if ($date->isPast()) {
            return 'deactive';
        } else {
            return 'active';
        }
    }
}
if (!function_exists('VariationStock')) {
    function VariationStock($item_id)
    {
        $product_variations = App\Models\User\ProductVariation::where([
            ['item_id', $item_id],
        ])->get();
        $varitaion_stock = [
            'has_variation' => 'no',
            'stock' => 'no'
        ];
        if (count($product_variations) > 0) {
            $varitaion_stock['has_variation'] = 'yes';
            $has_stock = false;
            foreach ($product_variations as $product_variation) {
                $product_variation_options = App\Models\User\ProductVariantOption::where(
                    'product_variation_id',
                    $product_variation->id,
                )->get();
                foreach ($product_variation_options as $product_variation_option) {
                    if ($product_variation_option->stock > 0) {
                        $has_stock = true;
                        break 2;
                    }
                }
            }
            $varitaion_stock['stock'] = $has_stock ? 'yes' : 'no';
        }
        return $varitaion_stock;
    }
}

if (!function_exists('checkWishList')) {
    function checkWishList($item_id, $customer_id)
    {
        $check = CustomerWishList::where([['customer_id', $customer_id], ['item_id', $item_id]])->first();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('canonicalUrl')) {
    function canonicalUrl()
    {
        $user = getUser();

        if ($user->subdomain_status == 1) {
            $domain = getParam() . '.' . env('WEBSITE_HOST');
        } else {
            $domain = env('WEBSITE_HOST');
        }

        // check if the user has a custom domain
        if (getCdomain($user) !== false) {
            $domain = getCdomain($user);
        }

        if (!preg_match('/^https?:\/\//', $domain)) {
            // current request's scheme (http or https) to the domain
            $scheme = request()->getScheme() . '://';
            $domain = $scheme . ltrim($domain, '/');
        }

        //current path and decode URL-encoded characters
        $path = urldecode(request()->path());

        if ($user->subdomain_status == 1 || getCdomain($user) !== false) {
            $subdomain = getParam();
            $pathSegments = explode('/', $path);
            if ($pathSegments[0] === $subdomain) {
                array_shift($pathSegments);
                $path = implode('/', $pathSegments);
            }
        }

        $path = str_replace(['–', ',', ' '], '-', $path);
        $path = preg_replace('/-+/', '-', $path);
        $path = strtolower($path);

        $canonicalUrl = rtrim($domain, '/') . '/' . ltrim($path, '/');
        return $canonicalUrl;
    }
}

if (!function_exists('hasStaffPerm')) {
    function hasStaffPerm($permissionKey) {
        if (!Session::has('staff_id')) {
            return true;
        }
        $perms = Session::get('staff_permissions', []);
        if (!is_array($perms)) return false;

        // Master / Parent permission overrides
        if (in_array('Shop Management', $perms)) return true;

        $itemsKeys = ['Products / Items', 'Items', 'Products Management', 'Product Management'];
        $productKeys = array_merge(['Products', 'Categories', 'Subcategories', 'Product Labels', 'Product Variants'], $itemsKeys);

        if ($permissionKey == 'Shop Management') {
            $shopKeys = array_merge($productKeys, ['Orders', 'Sales Report']);
            foreach ($shopKeys as $key) {
                if (in_array($key, $perms)) return true;
            }
        }

        if ($permissionKey == 'Products') {
            foreach ($productKeys as $key) {
                if (in_array($key, $perms)) return true;
            }
        }

        if (in_array('Products', $perms) && in_array($permissionKey, array_merge(['Categories', 'Subcategories', 'Product Labels', 'Product Variants'], $itemsKeys))) {
            return true;
        }

        if (in_array($permissionKey, $itemsKeys) || $permissionKey == 'Products / Items' || $permissionKey == 'Items') {
            foreach ($itemsKeys as $ik) {
                if (in_array($ik, $perms)) return true;
            }
        }

        return in_array($permissionKey, $perms);
    }
}

if (!function_exists('themeView')) {
    function themeView($view, array $data = [])
    {
        return app('theme.service')->view($view, $data);
    }
}

if (!function_exists('themeAsset')) {
    function themeAsset($path)
    {
        $theme = app('theme.service')->getActiveTheme();
        if ($theme === 'vegetables') {
            $theme = 'grocery';
        }
        return asset("assets/user-front/themes/{$theme}/" . ltrim($path, '/'));
    }
}

if (!function_exists('getMainDomainUrl')) {
    function getMainDomainUrl($path = '')
    {
        $host = request()->getHost();
        $cleanHost = preg_replace('/^(www|app)\./i', '', strtolower($host));

        $tenantBaseHosts = array_values(array_unique(array_filter([
            strtolower((string) env('WEBSITE_HOST', '')),
            'launchshop.in',
            'nooryak.in',
            'maturednature.com',
            'localhost',
            '127.0.0.1'
        ])));

        $mainHost = env('WEBSITE_HOST', '');
        if (empty($mainHost) || $mainHost === 'localhost' || $mainHost === '127.0.0.1') {
            foreach ($tenantBaseHosts as $baseHost) {
                if (!empty($baseHost) && ($cleanHost === $baseHost || str_ends_with($cleanHost, '.' . $baseHost))) {
                    $mainHost = $baseHost;
                    break;
                }
            }
        }

        if (empty($mainHost)) {
            $mainHost = $cleanHost;
        }

        $scheme = (request()->secure() || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https' : 'http';
        $port = request()->getPort();
        $portStr = ($port && !in_array($port, [80, 443])) ? (':' . $port) : '';

        return $scheme . '://' . $mainHost . $portStr . '/' . ltrim($path, '/');
    }
}