<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User\BasicSetting;
use Illuminate\Http\Request;

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

        $headers = [
            'Content-Type' => 'application/manifest+json; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        ];
        $origin = $request->header('Origin');
        if (!empty($origin)) {
            $headers['Access-Control-Allow-Origin'] = $origin;
            $headers['Access-Control-Allow-Credentials'] = 'true';
        } else {
            $headers['Access-Control-Allow-Origin'] = '*';
        }

        return response()->json($manifest, 200, $headers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function icon(int $size, Request $request)
    {
        if (!in_array($size, [192, 512], true)) {
            abort(404);
        }

        $user = $this->resolveUser($request->query('u'));
        $userBs = $this->resolveUserBs($user);

        $maskable = $request->boolean('maskable');
        $logoPath = $this->resolveIconPath($userBs);

        $pngData = $this->renderPngIcon($size, $logoPath, $maskable);

        return response($pngData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=604800',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    protected function renderPngIcon(int $size, ?string $logoPath, bool $maskable): string
    {
        // Try PHP GD first if available (zero dependency on fileinfo or Intervention Image)
        if (function_exists('imagecreatetruecolor') && function_exists('imagepng')) {
            try {
                $canvas = imagecreatetruecolor($size, $size);
                if ($canvas) {
                    $white = imagecolorallocate($canvas, 255, 255, 255);
                    imagefilledrectangle($canvas, 0, 0, $size, $size, $white);

                    if ($logoPath && is_file($logoPath)) {
                        $rawLogo = @file_get_contents($logoPath);
                        if (!empty($rawLogo)) {
                            $src = @imagecreatefromstring($rawLogo);
                            if ($src) {
                                $srcW = imagesx($src);
                                $srcH = imagesy($src);

                                if ($srcW > 0 && $srcH > 0) {
                                    $scaleFactor = $maskable ? 0.70 : 0.88;
                                    $aspect = $srcW / $srcH;

                                    if ($aspect >= 1.0) {
                                        $dstW = (int) round($size * $scaleFactor);
                                        $dstH = (int) round($dstW / $aspect);
                                        if ($dstH > (int) round($size * $scaleFactor)) {
                                            $dstH = (int) round($size * $scaleFactor);
                                            $dstW = (int) round($dstH * $aspect);
                                        }
                                    } else {
                                        $dstH = (int) round($size * $scaleFactor);
                                        $dstW = (int) round($dstH * $aspect);
                                        if ($dstW > (int) round($size * $scaleFactor)) {
                                            $dstW = (int) round($size * $scaleFactor);
                                            $dstH = (int) round($dstW / $aspect);
                                        }
                                    }

                                    $dstX = (int) round(($size - $dstW) / 2);
                                    $dstY = (int) round(($size - $dstH) / 2);

                                    imagecopyresampled($canvas, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
                                }
                                imagedestroy($src);
                            }
                        }
                    }

                    ob_start();
                    imagepng($canvas, null, 6);
                    $data = ob_get_clean();
                    imagedestroy($canvas);

                    if (!empty($data)) {
                        return $data;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore and fall through to static file
            }
        }

        // Static file fallback
        $fallbackFiles = array_filter([
            $logoPath,
            public_path('assets/front/img/logo.png'),
            public_path('assets/front/img/pushnotification_icon.png'),
            public_path('assets/front/img/673095353bc62.png'),
        ]);

        foreach ($fallbackFiles as $file) {
            if (is_file($file)) {
                $content = @file_get_contents($file);
                if (!empty($content)) {
                    return $content;
                }
            }
        }

        // Ultimate failsafe: 1x1 transparent PNG
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
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

        $defaultLogo = public_path('assets/front/img/logo.png');
        if (is_file($defaultLogo)) {
            return $defaultLogo;
        }

        $pushIcon = public_path('assets/front/img/pushnotification_icon.png');
        if (is_file($pushIcon)) {
            return $pushIcon;
        }

        return null;
    }
}
