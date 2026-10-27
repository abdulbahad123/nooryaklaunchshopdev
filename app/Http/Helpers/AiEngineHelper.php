<?php

namespace App\Http\Helpers;

use App\Models\BasicSetting;
use App\Models\Membership;
use App\Models\User\BasicSetting as UserBasicSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AiEngineHelper
{
    /**
     * Check if an API key is non-empty, non-dummy, and valid.
     *
     * @param string|null $key
     * @return bool
     */
    public static function isValidKey(?string $key): bool
    {
        if (empty($key)) {
            return false;
        }
        $trimmed = trim((string)$key);
        if ($trimmed === '') {
            return false;
        }
        if (str_contains($trimmed, 'XXXX') || str_contains($trimmed, 'DUMMY') || str_contains(strtolower($trimmed), 'dummy')) {
            return false;
        }
        return true;
    }

    /**
     * Resolve the active AI engine, key availability, and display badge for a user.
     *
     * @param int|null $userId
     * @return array
     */
    public static function resolveActiveEngine(?int $userId = null): array
    {
        $userId = $userId ?: Auth::guard('web')->id();
        $userBs = $userId ? UserBasicSetting::where('user_id', $userId)->first() : null;

        // 1. Determine preferred engine selected by store owner (or package membership default)
        $preferred = 'gemini';
        if ($userBs && !empty($userBs->ai_engine)) {
            $preferred = strtolower(trim((string)$userBs->ai_engine));
        } else {
            $today = Carbon::now()->toDateString();
            $userMemb = $userId ? Membership::where('user_id', $userId)
                ->where('status', 1)
                ->where('start_date', '<=', $today)
                ->where('expire_date', '>=', $today)
                ->first() : null;
            if ($userMemb && !empty($userMemb->ai_engine)) {
                $preferred = strtolower(trim((string)$userMemb->ai_engine));
            }
        }

        // 2. Explicit Pollinations selection
        if ($preferred === 'pollinations') {
            return [
                'preferred'   => 'pollinations',
                'actual'      => 'pollinations',
                'has_key'     => true,
                'status'      => 'active',
                'label'       => 'POLLINATIONS',
                'display'     => 'POLLINATIONS (Free)',
                'badge_class' => 'badge-info',
                'badge_style' => 'background: #0284C7; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                'note'        => 'Free AI Engine Active (No API Key Required)',
            ];
        }

        // 3. Gemini Selection
        if ($preferred === 'gemini') {
            $hasKey = false;
            // Check Store Owner key
            if ($userBs && self::isValidKey($userBs->gemini_api_key) && ($userBs->is_gemini ?? 0) == 1) {
                $hasKey = true;
            }
            // Check Agency key
            if (!$hasKey && function_exists('getAgencyFromHost')) {
                $agency = getAgencyFromHost();
                if ($agency && self::isValidKey($agency->gemini_api_key) && ($agency->is_gemini_active ?? 0) == 1) {
                    $hasKey = true;
                }
            }
            // Check Super Admin Database (never .env)
            if (!$hasKey) {
                $bs = BasicSetting::first();
                if ($bs && self::isValidKey($bs->gemini_api_key) && ($bs->is_gemini ?? 0) == 1) {
                    $hasKey = true;
                }
            }

            return [
                'preferred'   => 'gemini',
                'actual'      => 'gemini',
                'has_key'     => $hasKey,
                'status'      => 'active',
                'label'       => 'GEMINI',
                'display'     => 'GEMINI',
                'badge_class' => 'badge-primary',
                'badge_style' => 'background: #0d6efd; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                'note'        => 'Google Gemini Engine Active',
            ];
        }

        // 4. OpenAI / ChatGPT Selection
        if ($preferred === 'openai' || $preferred === 'chatgpt') {
            $hasKey = false;
            // Check Store Owner key
            if ($userBs && self::isValidKey($userBs->openai_api_key) && ($userBs->is_openai ?? 0) == 1) {
                $hasKey = true;
            }
            // Check Agency key
            if (!$hasKey && function_exists('getAgencyFromHost')) {
                $agency = getAgencyFromHost();
                if ($agency && self::isValidKey($agency->openai_api_key) && ($agency->is_openai_active ?? 0) == 1) {
                    $hasKey = true;
                }
            }
            // Check Super Admin Database (never .env)
            if (!$hasKey) {
                $bs = BasicSetting::first();
                if ($bs && self::isValidKey($bs->openai_api_key) && ($bs->is_openai ?? 0) == 1) {
                    $hasKey = true;
                }
            }

            return [
                'preferred'   => 'openai',
                'actual'      => 'openai',
                'has_key'     => $hasKey,
                'status'      => 'active',
                'label'       => 'CHATGPT',
                'display'     => 'CHATGPT',
                'badge_class' => 'badge-success',
                'badge_style' => 'background: #10B981; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                'note'        => 'OpenAI ChatGPT Engine Active',
            ];
        }

        // 5. Default Fallback
        return [
            'preferred'   => 'pollinations',
            'actual'      => 'pollinations',
            'has_key'     => true,
            'status'      => 'active',
            'label'       => 'POLLINATIONS',
            'display'     => 'POLLINATIONS (Free)',
            'badge_class' => 'badge-info',
            'badge_style' => 'background: #0284C7; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
            'note'        => 'Pollinations Engine Active',
        ];
    }

    /**
     * Get AI Limits & Status summary for current user or given userId.
     *
     * @param int|null $userId
     * @return array
     */
    public static function getAiLimits(?int $userId = null): array
    {
        $userId = $userId ?: (Auth::guard('web')->id() ?? 0);
        $today = Carbon::now()->toDateString();
        $membership = Membership::query()->select([
            'id', 'user_id', 'ai_engine', 'ai_token_limit', 'ai_image_limit',
            'ai_used_tokens', 'ai_used_images', 'ai_token_purchased', 'ai_image_purchased'
        ])->where([
            ['user_id', $userId],
            ['start_date', '<=', $today],
            ['expire_date', '>=', $today],
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();

        $usedTokens = max(0, (int) ($membership?->ai_used_tokens ?? 0));
        $tokenLimit = max(0, (int) ($membership?->ai_token_limit ?? 0));
        $tokenPurchased = max(0, (int) ($membership?->ai_token_purchased ?? 0));
        $totalTokens = $tokenLimit + $tokenPurchased;

        $usedImages = max(0, (int) ($membership?->ai_used_images ?? 0));
        $imageLimit = max(0, (int) ($membership?->ai_image_limit ?? 0));
        $imagePurchased = max(0, (int) ($membership?->ai_image_purchased ?? 0));
        $totalImages = $imageLimit + $imagePurchased;

        $engineInfo = self::resolveActiveEngine($userId);

        return [
            'engine'       => $engineInfo,
            'used_tokens'  => $usedTokens,
            'total_tokens' => $totalTokens,
            'token_label'  => $totalTokens < 999999 ? (string)$totalTokens : __('Unlimited'),
            'used_images'  => $usedImages,
            'total_images' => $totalImages,
            'image_label'  => $totalImages < 999999 ? (string)$totalImages : __('Unlimited'),
        ];
    }
}
