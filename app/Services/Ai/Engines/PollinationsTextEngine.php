<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\AiTextEngineInterface;
use Illuminate\Support\Facades\Http;

class PollinationsTextEngine implements AiTextEngineInterface
{
  public function key(): string
  {
    return 'pollinations';
  }

  public function generate(string $prompt): string
  {
    $url = 'https://text.pollinations.ai/';
    $model = (string) config('ai.pollinations_text_model', 'openai');

    // 1. Unauthenticated JSON POST to free text.pollinations.ai endpoint
    try {
      $response = Http::timeout(30)
        ->withHeaders([
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ])
        ->post($url, [
          'messages' => [
            ['role' => 'user', 'content' => $prompt]
          ],
          'model'    => $model,
          'jsonMode' => true
        ]);

      if ($response->successful()) {
        $json = $response->json();
        $content = data_get($json, 'choices.0.message.content') ?? data_get($json, 'content');
        if (is_string($content) && trim($content) !== '') {
          return trim($content);
        }
        $body = trim((string) $response->body());
        if (!empty($body) && strpos($body, 'error') === false) {
          return $body;
        }
      }
    } catch (\Throwable $e) {
      // Continue to GET fallback
    }

    // 2. Simple GET fallback to free endpoint
    try {
      $getResp = Http::timeout(30)->get($url . urlencode($prompt));
      if ($getResp->successful()) {
        $body = trim((string) $getResp->body());
        if (!empty($body) && strpos($body, 'error') === false) {
          return $body;
        }
      }
    } catch (\Throwable $e) {
      // Ignore
    }

    throw new \RuntimeException('Pollinations free text service unavailable.');
  }
}
