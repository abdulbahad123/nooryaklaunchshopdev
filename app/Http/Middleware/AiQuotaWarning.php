<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AiQuotaWarning
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return $next($request);
        }

        $user = Auth::guard('web')->user();

        $membership = Membership::query()
            ->select([
                'id',
                'user_id',
                'ai_engine',
                'ai_token_limit',
                'ai_image_limit',
                'ai_used_tokens',
                'ai_used_images',
                'ai_token_purchased',
                'ai_image_purchased',
            ])
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->where('start_date', '<=', Carbon::now()->toDateString())
            ->where('expire_date', '>=', Carbon::now()->toDateString())
            ->first();

        if (!$membership) {
            return $this->warning(__('No active membership found. Please purchase a plan.'));
        }

        if (empty($membership->ai_engine)) {
            return $this->warning(__('AI engine is not configured for your plan.'));
        }

        if (strtolower((string) $membership->ai_engine) === 'pollinations') {
            return $next($request);
        }

        $checks = $this->resolveChecks($request);

        if ($checks['token']) {
            $tokenLimit = (int) $membership->ai_token_limit + (int) $membership->ai_token_purchased;
            $tokenUsed = (int) $membership->ai_used_tokens;

            if ($tokenLimit <= 0 || $tokenUsed >= $tokenLimit) {
                return $this->warning(__('Your AI token limit is finished. Please upgrade or purchase more tokens.'));
            }
        }

        if ($checks['image']) {
            $imageLimit = (int) $membership->ai_image_limit + (int) $membership->ai_image_purchased;
            $imageUsed = (int) $membership->ai_used_images;

            if ($imageLimit <= 0 || $imageUsed >= $imageLimit) {
                return $this->warning(__('Your AI image limit is finished. Please upgrade or purchase more images.'));
            }
        }

        return $next($request);
    }

    private function resolveChecks(Request $request): array
    {
        $routeName = $request->route() ? $request->route()->getName() : '';

        return [
            'token' => in_array($routeName, [
                'user.ai.generate.content',
            ], true),
            'image' => in_array($routeName, [
                'user.ai.generate.category.image',
                'user.ai.generate.slider.images',
            ], true),
        ];
    }

    private function warning(string $message)
    {
        return response()->json([
            'status' => false,
            'warning' => true,
            'message' => $message,
        ]);
    }
}
