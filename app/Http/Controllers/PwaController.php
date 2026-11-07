<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User\BasicSetting;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PwaController extends Controller
{
    protected function resolveUser(?string $username): ?User
    {
        if ($username) {
            return User::where('username', $username)->first();
        }

        return getUser();
    }

    protected function resolveUserBs(?User $user): ?BasicSetting
    {
        if (!$user) {
            return null;
        }

        return BasicSetting::where('user_id', $user->id)->first();
    }

    protected function getPwaUrls(?User $user): array
    {
        if (!$user) {
            return ['start_url' => '/', 'scope' => '/'];
        }

        $host = strtolower(str_replace('www.', '', request()->getHost()));
        $isCustom = (function_exists('isTenantCustomDomainRequest') && isTenantCustomDomainRequest())
            || (function_exists('isLaunchShopCustomDomain') && isLaunchShopCustomDomain($host))
            || !empty(request()->attributes->get('is_launchshop_custom_domain'));

        if ($isCustom) {
            return ['start_url' => '/', 'scope' => '/'];
        }

        // Any custom domain (like cuckroachjanlaparly.top) is served from root
        $mainHosts = ['launchshop.in', 'launchshop.top', 'saasreselling.com', 'saasreselling.in', 'localhost', '127.0.0.1'];
        $isMainPlatformHost = false;
        foreach ($mainHosts as $mh) {
            if ($host === $mh || str_ends_with($host, '.' . $mh)) {
                $isMainPlatformHost = true;
                break;
            }
        }
        if (!$isMainPlatformHost) {
            return ['start_url' => '/', 'scope' => '/'];
        }

        // Check if tenant subdomain (e.g. mercado.saasreselling.in)
        $tenantBaseHosts = ['saasreselling.com', 'saasreselling.in', 'localhost', '127.0.0.1'];
        if (env('WEBSITE_HOST')) {
            $tenantBaseHosts[] = strtolower(str_replace('www.', '', env('WEBSITE_HOST')));
        }
        $tenantBaseHosts = array_unique(array_filter($tenantBaseHosts));

        foreach ($tenantBaseHosts as $baseHost) {
            if (str_ends_with($host, '.' . $baseHost)) {
                $sub = explode('.', $host)[0] ?? '';
                if (!in_array(strtolower($sub), ['launchshop', 'ecombuilder', 'checkout', 'app', 'www', 'admin', 'websitebuilder', 'website-builder', 'whatsappbulk', 'whatsapp'])) {
                    return ['start_url' => '/', 'scope' => '/'];
                }
            }
        }

        // Path-based tenant: e.g. ecombuilder.saasreselling.in/newshop/
        $base = '/' . $user->username . '/';
        return ['start_url' => $base, 'scope' => $base];
    }

    public function manifest(Request $request)
    {
        $user = $this->resolveUser($request->query('u'));
        $userBs = $this->resolveUserBs($user);

        $shopName = !empty($userBs->website_title)
            ? $userBs->website_title
            : ($user ? ($user->shop_name ?? $user->username) : 'LaunchShop');

        $username = $user ? $user->username : '';
        $urls = $this->getPwaUrls($user);
        $color = '#' . ltrim($userBs->base_color ?? '007bff', '#');

        $iconQuery = $username !== '' ? '?u=' . urlencode($username) : '';

        // Use relative URLs so that icons match current origin, HTTPS, and custom domain
        $icon192 = '/pwa-icon/192' . $iconQuery;
        $icon512 = '/pwa-icon/512' . $iconQuery;
        $iconMaskable = '/pwa-icon/512' . $iconQuery . ($iconQuery ? '&' : '?') . 'maskable=1';

        $manifest = [
            'name'             => $shopName,
            'short_name'       => mb_substr($shopName, 0, 12),
            'description'      => 'Install ' . $shopName . ' for a faster shopping experience',
            'id'               => $urls['start_url'],
            'start_url'        => $urls['start_url'],
            'scope'            => $urls['scope'],
            'display'          => 'standalone',
            'background_color' => '#ffffff',
            'theme_color'      => '#ffffff',
            'orientation'      => 'portrait-primary',
            'categories'       => ['shopping'],
            'icons'            => [
                [
                    'src'     => $icon192,
                    'sizes'   => '192x192',
                    'type'    => 'image/png',
                    'purpose' => 'any',
                ],
                [
                    'src'     => $icon512,
                    'sizes'   => '512x512',
                    'type'    => 'image/png',
                    'purpose' => 'any',
                ],
                [
                    'src'     => $iconMaskable,
                    'sizes'   => '512x512',
                    'type'    => 'image/png',
                    'purpose' => 'maskable',
                ],
            ],
        ];

        return response()->json($manifest, 200, [
            'Content-Type' => 'application/manifest+json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function icon(int $size, Request $request)
    {
        if (!in_array($size, [192, 512], true)) {
            abort(404);
        }

        $user = $this->resolveUser($request->query('u'));
        $userBs = $this->resolveUserBs($user);

        $shopName = !empty($userBs->website_title)
            ? $userBs->website_title
            : ($user ? ($user->shop_name ?? $user->username) : 'LaunchShop');

        $maskable = $request->boolean('maskable');
        $logoPath = $this->resolveIconPath($userBs);

        try {
            $image = $this->buildIcon($size, $logoPath, '#ffffff', $shopName, $maskable);

            return $image->response('png', 90)->header('Cache-Control', 'public, max-age=604800');
        } catch (\Throwable $e) {
            $image = Image::canvas($size, $size, '#ffffff');

            return $image->response('png', 90)->header('Cache-Control', 'public, max-age=604800');
        }
    }

    protected function resolveIconPath(?BasicSetting $userBs): ?string
    {
        if (!$userBs) {
            return null;
        }

        foreach (['web_app_image', 'logo'] as $field) {
            if (empty($userBs->{$field})) {
                continue;
            }

            $path = public_path('assets/front/img/user/' . $userBs->{$field});

            if (!is_file($path)) {
                continue;
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'ico'], true)) {
                continue;
            }

            return $path;
        }

        return null;
    }

    protected function buildIcon(int $size, ?string $logoPath, string $color, string $shopName, bool $maskable)
    {
        $canvas = Image::canvas($size, $size, '#ffffff');

        if (!$logoPath) {
            return $canvas;
        }

        $logo = Image::make($logoPath);
        $w = $logo->width();
        $h = $logo->height();

        if ($w <= 0 || $h <= 0) {
            return $canvas;
        }

        $aspectRatio = $w / $h;

        if ($aspectRatio > 1.2) {
            // Horizontal logo: scale width to 92% of canvas width
            $targetWidth = (int) round($size * 0.92);
            $targetHeight = (int) round($targetWidth / $aspectRatio);
            if ($targetHeight > (int) round($size * 0.85)) {
                $targetHeight = (int) round($size * 0.85);
                $targetWidth = (int) round($targetHeight * $aspectRatio);
            }
            $logo->resize($targetWidth, $targetHeight);
        } elseif ($aspectRatio < 0.8) {
            // Vertical logo: scale height to 92% of canvas height
            $targetHeight = (int) round($size * 0.92);
            $targetWidth = (int) round($targetHeight * $aspectRatio);
            if ($targetWidth > (int) round($size * 0.85)) {
                $targetWidth = (int) round($size * 0.85);
                $targetHeight = (int) round($targetWidth / $aspectRatio);
            }
            $logo->resize($targetWidth, $targetHeight);
        } else {
            // Square or near-square logo: scale to 88% of canvas
            $targetWidth = (int) round($size * 0.88);
            $targetHeight = (int) round($size * 0.88);
            $logo->resize($targetWidth, $targetHeight, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $x = (int) round(($size - $logo->width()) / 2);
        $y = (int) round(($size - $logo->height()) / 2);

        return $canvas->insert($logo, 'top-left', $x, $y);
    }
}
