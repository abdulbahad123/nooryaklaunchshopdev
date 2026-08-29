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

    $userModel = (string) config('ai.gemini_text_model', '');
    if ($userModel === '') {
      $bs = \App\Models\BasicSetting::first();
      if ($bs && !empty($bs->gemini_text_model)) {
        $userModel = (string) $bs->gemini_text_model;
      }
    }

    // List of models and API versions to attempt
    $modelsToTry = [];
    if (!empty($userModel) && !in_array($userModel, ['gemini-2.0-flash', 'gemini-2.0', 'gemini-1.0-pro'], true)) {
      $modelsToTry[] = $userModel;
    }
    $modelsToTry = array_unique(array_merge($modelsToTry, [
      'gemini-1.5-flash',
      'gemini-1.5-pro',
      'gemini-2.5-flash',
    ]));

    $versions = ['v1beta', 'v1'];
    $lastResp = null;

    foreach ($modelsToTry as $model) {
      foreach ($versions as $version) {
        $endpoint = "https://generativelanguage.googleapis.com/{$version}/models/{$model}:generateContent?key=" . urlencode($apiKey);

        $resp = Http::timeout((int) config('ai.http_timeout', 90))
          ->withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type'   => 'application/json',
          ])
          ->post($endpoint, [
            'contents' => [
              ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => ['temperature' => 0.4],
          ]);

        $lastResp = $resp;
        if ($resp->ok()) {
          break 2;
        }

        // If authentication failed (403), throw immediately so manager can fail over to next engine
        if ($resp->status() === 403) {
          $errJson = $resp->json();
          $errMsg = $errJson['error']['message'] ?? 'Gemini API Key is invalid or unauthorized (403).';
          throw new \RuntimeException($errMsg);
        }
      }
    }

    $resp = $lastResp;

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
