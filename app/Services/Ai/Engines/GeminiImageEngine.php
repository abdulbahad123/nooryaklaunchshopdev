<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\AiImageEngineInterface;
use App\Services\Ai\Engines\Concerns\BuildsImagePrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeminiImageEngine implements AiImageEngineInterface
{
  use BuildsImagePrompt;

  public function generateAndStore(array $data): string
  {
    $apiKey = '';
    if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
      $userBs = \App\Models\User\BasicSetting::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->first();
      if ($userBs && isset($userBs->is_gemini) && (int)$userBs->is_gemini === 0) {
        throw new \RuntimeException('Gemini AI engine is deactivated in Plugins settings.');
      }
      if ($userBs && !empty($userBs->gemini_api_key) && \App\Http\Helpers\AiEngineHelper::isValidKey($userBs->gemini_api_key)) {
        $apiKey = (string) $userBs->gemini_api_key;
      }
    }
    if ($apiKey === '' && function_exists('getAgencyFromHost')) {
      $agency = getAgencyFromHost();
      if ($agency && !empty($agency->gemini_api_key) && ($agency->is_gemini_active ?? 1) == 1 && \App\Http\Helpers\AiEngineHelper::isValidKey($agency->gemini_api_key)) {
        $apiKey = (string) $agency->gemini_api_key;
      }
    }
    if ($apiKey === '') {
      $bs = \App\Models\BasicSetting::first();
      if ($bs && !empty($bs->gemini_api_key) && ($bs->is_gemini ?? 0) == 1 && \App\Http\Helpers\AiEngineHelper::isValidKey($bs->gemini_api_key)) {
        $apiKey = (string) $bs->gemini_api_key;
      }
    }
    if ($apiKey === '') {
      return app(\App\Services\Ai\Engines\PollinationsImageEngine::class)->generateAndStore($data);
    }

    $model = (string) config('ai.gemini_image_model', 'imagen-4.0-generate-001');

    $prompt = trim((string)($data['prompt'] ?? ''));
    if ($prompt === '') {
      throw new \InvalidArgumentException('Prompt is required');
    }

    $hasRef = !empty($data['reference_image']) || !empty($data['reference_url']);

    $finalPrompt = $this->buildPrompt(
      $prompt,
      (string)($data['style'] ?? ''),
      (string)($data['lighting'] ?? ''),
      (string)($data['angle'] ?? ''),
      (int)($data['variant_index'] ?? 0),
      (int)($data['variant_total'] ?? 1),
      $hasRef
    );

    [$w, $h] = $this->resolveSize((string)($data['size'] ?? 'square_1024'));
    $aspectRatio = $this->aspectRatioFromSize($w, $h); 

    try {
      $imageBinary = null;

    // 1. Try Imagen 3.0 via :predict
    try {
      $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$apiKey}";
      $response = Http::timeout(30)
        ->withoutVerifying()
        ->acceptJson()
        ->post($endpoint, [
          "instances" => [
            ["prompt" => $finalPrompt] 
          ],
          "parameters" => [
            "sampleCount" => 1,
            "aspectRatio" => $aspectRatio,
          ]
        ]);

      if ($response->successful()) {
        $json = $response->json();
        $base64 = $json['predictions'][0]['bytesBase64Encoded'] ?? null;
        if ($base64) {
          $decoded = base64_decode($base64, true);
          if ($decoded !== false) {
            $imageBinary = $decoded;
          }
        }
      }
    } catch (\Throwable $e) {
      \Log::info('Gemini Imagen predict attempt failed: ' . $e->getMessage());
    }

    // 2. Try multimodal Gemini Image via :generateContent if Imagen was not available
    if (!$imageBinary) {
      try {
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent?key={$apiKey}";
        $response = Http::timeout(30)
          ->withoutVerifying()
          ->acceptJson()
          ->post($endpoint, [
            'contents' => [
              ['parts' => [['text' => $finalPrompt]]]
            ],
            'generationConfig' => [
              'responseModalities' => ['IMAGE']
            ]
          ]);

        if ($response->successful()) {
          $json = $response->json();
          $parts = $json['candidates'][0]['content']['parts'] ?? [];
          foreach ($parts as $p) {
            $base64 = $p['inlineData']['data'] ?? $p['inline_data']['data'] ?? null;
            if ($base64) {
              $decoded = base64_decode($base64, true);
              if ($decoded !== false) {
                $imageBinary = $decoded;
                break;
              }
            }
          }
        } else {
          \Log::warning('Gemini image generation returned status ' . $response->status(), [
            'body' => substr($response->body(), 0, 300)
          ]);
        }
      } catch (\Throwable $e) {
        \Log::info('Gemini flash-image attempt failed: ' . $e->getMessage());
      }
    }

    if (!$imageBinary) {
      return 'default.jpg';
    }

      Storage::disk('public')->makeDirectory('ai/categories');

      $filename = 'gemini_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.png';
      $path = 'ai/categories/' . $filename;

      $saved = Storage::disk('public')->put($path, $imageBinary); 
      if (!$saved) {
        throw new \RuntimeException('Failed to save generated image');
      }

      $this->resizeStoredImage($path, $w, $h);

      return '/storage/' . $path;
    } catch (\Throwable $e) {
      \Log::error('Gemini image engine exception', [
        'error' => $e->getMessage(),
      ]);
      return 'default.jpg';
    }
  }


  //aspectratio generate for gemini
  private function aspectRatioFromSize($width, $height)
  {
    if (!$width || !$height) return '1:1';

    $ratio = $width / $height;

    if (abs($ratio - 1) < 0.1) return '1:1';
    if ($ratio > 1.7) return '16:9';
    if ($ratio > 1.2) return '4:3';
    if ($ratio < 0.6) return '9:16';

    return '3:4';
  }
}
