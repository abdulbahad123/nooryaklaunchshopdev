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
            if ($userBs && !empty($userBs->gemini_api_key) && ($userBs->is_gemini ?? 1) == 1) {
                $hasKey = true;
            }
            // Check Agency key
            if (!$hasKey && function_exists('getAgencyFromHost')) {
                $agency = getAgencyFromHost();
                if ($agency && !empty($agency->gemini_api_key) && ($agency->is_gemini_active ?? 1) == 1) {
                    $hasKey = true;
                }
            }
            // Check Super Admin Database (never .env)
            if (!$hasKey) {
                $bs = BasicSetting::first();
                if ($bs && !empty($bs->gemini_api_key)) {
                    $hasKey = true;
                }
            }

            if ($hasKey) {
                return [
                    'preferred'   => 'gemini',
                    'actual'      => 'gemini',
                    'has_key'     => true,
                    'status'      => 'active',
                    'label'       => 'GEMINI',
                    'display'     => 'GEMINI',
                    'badge_class' => 'badge-success',
                    'badge_style' => 'background: #10B981; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                    'note'        => 'Google Gemini Engine Active',
                ];
            } else {
                return [
                    'preferred'   => 'gemini',
                    'actual'      => 'pollinations',
                    'has_key'     => false,
                    'status'      => 'fallback',
                    'label'       => 'POLLINATIONS',
                    'display'     => 'POLLINATIONS (Gemini Key Missing)',
                    'badge_class' => 'badge-warning',
                    'badge_style' => 'background: #F59E0B; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                    'note'        => 'Gemini API key not configured; using Pollinations free fallback.',
                ];
            }
        }

        // 4. OpenAI / ChatGPT Selection
        if ($preferred === 'openai' || $preferred === 'chatgpt') {
            $hasKey = false;
            // Check Store Owner key
            if ($userBs && !empty($userBs->openai_api_key) && ($userBs->is_openai ?? 1) == 1) {
                $hasKey = true;
            }
            // Check Agency key
            if (!$hasKey && function_exists('getAgencyFromHost')) {
                $agency = getAgencyFromHost();
                if ($agency && !empty($agency->openai_api_key) && ($agency->is_openai_active ?? 1) == 1) {
                    $hasKey = true;
                }
            }
            // Check Super Admin Database (never .env)
            if (!$hasKey) {
                $bs = BasicSetting::first();
                if ($bs && !empty($bs->openai_api_key)) {
                    $hasKey = true;
                }
            }

            if ($hasKey) {
                return [
                    'preferred'   => 'openai',
                    'actual'      => 'openai',
                    'has_key'     => true,
                    'status'      => 'active',
                    'label'       => 'CHATGPT',
                    'display'     => 'CHATGPT',
                    'badge_class' => 'badge-success',
                    'badge_style' => 'background: #10B981; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                    'note'        => 'OpenAI ChatGPT Engine Active',
                ];
            } else {
                return [
                    'preferred'   => 'openai',
                    'actual'      => 'pollinations',
                    'has_key'     => false,
                    'status'      => 'fallback',
                    'label'       => 'POLLINATIONS',
                    'display'     => 'POLLINATIONS (OpenAI Key Missing)',
                    'badge_class' => 'badge-warning',
                    'badge_style' => 'background: #F59E0B; color: #fff; font-size: 11px; padding: 5px 12px; border-radius: 20px;',
                    'note'        => 'OpenAI API key not configured; using Pollinations free fallback.',
                ];
            }
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
}
