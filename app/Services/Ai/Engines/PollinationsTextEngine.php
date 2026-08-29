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
    $apiKey = (string) config('ai.pollinations_secret_key', '');
    $model = (string) config('ai.pollinations_text_model', 'openai');

    $payload = [
      'messages' => [
        [
          'role' => 'user',
          'content' => $prompt
        ],
      ],
      'model' => $model,
      'jsonMode' => true
    ];

    $headers = [
      'Content-Type' => 'application/json',
    ];
    if ($apiKey !== '') {
      $headers['Authorization'] = 'Bearer ' . $apiKey;
    }

    $response = Http::timeout((int) config('ai.http_timeout', 90))
      ->connectTimeout(20)
      ->withHeaders($headers)
      ->post($url, $payload);

    if (!$response->successful()) {
      // Fallback simple GET request to free endpoint
      $response = Http::timeout((int) config('ai.http_timeout', 90))
        ->get('https://text.pollinations.ai/' . urlencode($prompt));
    }

    if (!$response->successful()) {
      throw new \RuntimeException('Pollinations failed (' . $response->status() . '): ' . mb_substr($response->body(), 0, 200));
    }

    $body = trim((string) $response->body());
    $json = $response->json();
    $content = data_get($json, 'choices.0.message.content') ?? data_get($json, 'content');

    if (is_string($content) && trim($content) !== '') {
      return trim($content);
    }

    return $body;
  }
}
