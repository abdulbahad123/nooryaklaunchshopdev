<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\AiTextEngineInterface;
use Illuminate\Support\Facades\Http;

class GeminiTextEngine implements AiTextEngineInterface
{
  public function key(): string
  {
    return 'gemini';
  }

  public function generate(string $prompt): string
  {
    $result = $this->generateWithMeta($prompt);
    return (string) ($result['text'] ?? '');
  }

  public function generateWithMeta(string $prompt): array
  {
    $apiKey = (string) config('ai.gemini_api_key', '');
    if ($apiKey === '') {
      $bs = \App\Models\BasicSetting::first();
      if ($bs && !empty($bs->gemini_api_key)) {
        $apiKey = (string) $bs->gemini_api_key;
      }
    }
    if ($apiKey === '') {
      throw new \RuntimeException('GEMINI_API_KEY missing. Please configure it in Super Admin > Settings > Plugins.');
    }

    $model = (string) config('ai.gemini_text_model', '');
    if ($model === '') {
      $bs = \App\Models\BasicSetting::first();
      if ($bs && !empty($bs->gemini_text_model)) {
        $model = (string) $bs->gemini_text_model;
      }
    }
    if ($model === '' || in_array($model, ['gemini-2.0-flash', 'gemini-2.0'], true)) {
      $model = 'gemini-2.5-flash';
    }

    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

    $resp = Http::timeout((int) config('ai.http_timeout', 90))
      ->withHeaders([
        'x-goog-api-key' => $apiKey,
        'Content-Type' => 'application/json',
      ])
      ->post($endpoint, [
        'contents' => [
          ['role' => 'user', 'parts' => [['text' => $prompt]]],
        ],
        'generationConfig' => ['temperature' => 0.4],
      ]);

    // Fallback if 404 model not found is returned
    if ($resp->status() === 404) {
      $fallbackModel = ($model === 'gemini-2.5-flash') ? 'gemini-1.5-flash' : 'gemini-2.5-flash';
      $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$fallbackModel}:generateContent";
      $resp = Http::timeout((int) config('ai.http_timeout', 90))
        ->withHeaders([
          'x-goog-api-key' => $apiKey,
          'Content-Type' => 'application/json',
        ])
        ->post($endpoint, [
          'contents' => [
            ['role' => 'user', 'parts' => [['text' => $prompt]]],
          ],
          'generationConfig' => ['temperature' => 0.4],
        ]);
    }

    if (!$resp->ok()) {
      $status = $resp->status();
      $errJson = $resp->json();
      $errMsg = null;

      if (is_array($errJson)) {
        $errMsg = $errJson['error']['message']
          ?? $errJson['error']['status']
          ?? $errJson['message']
          ?? null;
      }

      $body = trim((string) $resp->body());
      if ($body !== '' && $errMsg === null) {
        $errMsg = mb_substr($body, 0, 500, 'UTF-8');
      }

      $msg = $errMsg ? "Gemini request failed ({$status}): {$errMsg}" : "Gemini request failed ({$status})";
      throw new \RuntimeException($msg);
    }

    $j = $resp->json();

    $parts = $j['candidates'][0]['content']['parts'] ?? [];

    $text = '';
    foreach ($parts as $p) {
      if (isset($p['text'])) $text .= $p['text'];
    }

    $usageMeta = $j['usageMetadata'] ?? null;

    return [
      'text' => trim($text),
      'usage' => [
        'total_tokens' => is_array($usageMeta) && isset($usageMeta['totalTokenCount'])
          ? (int) $usageMeta['totalTokenCount']
          : null,
        'prompt_tokens' => is_array($usageMeta) && isset($usageMeta['promptTokenCount'])
          ? (int) $usageMeta['promptTokenCount']
          : null,
        'completion_tokens' => is_array($usageMeta) && isset($usageMeta['candidatesTokenCount'])
          ? (int) $usageMeta['candidatesTokenCount']
          : null,
      ],
    ];
  }
}
