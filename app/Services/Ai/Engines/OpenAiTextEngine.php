<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\AiTextEngineInterface;
use Illuminate\Support\Facades\Http;

class OpenAiTextEngine implements AiTextEngineInterface
{
  public function key(): string
  {
    return 'openai';
  }

  public function generate(string $prompt): string
  {
    $result = $this->generateWithMeta($prompt);
    return (string) ($result['text'] ?? '');
  }

  public function generateWithMeta(string $prompt): array
  {
    $apiKey = '';
    if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
      $userBs = \App\Models\User\BasicSetting::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->first();
      if ($userBs && isset($userBs->is_openai) && (int)$userBs->is_openai === 0) {
        throw new \RuntimeException('OpenAI engine is deactivated in Plugins settings.');
      }
      if ($userBs && !empty($userBs->openai_api_key)) {
        $apiKey = (string) $userBs->openai_api_key;
      }
    }
    if ($apiKey === '' && function_exists('getAgencyFromHost')) {
      $agency = getAgencyFromHost();
      if ($agency && !empty($agency->openai_api_key)) {
        $apiKey = (string) $agency->openai_api_key;
      }
    }
    if ($apiKey === '') {
      $apiKey = (string) config('ai.openai_api_key', '');
    }
    if ($apiKey === '') {
      $bs = \App\Models\BasicSetting::first();
      if ($bs && !empty($bs->openai_api_key)) {
        $apiKey = (string) $bs->openai_api_key;
      }
    }
    if ($apiKey === '') {
      throw new \RuntimeException('OPENAI_API_KEY missing');
    }

    $resp = Http::timeout((int) config('ai.http_timeout', 90))
      ->withToken($apiKey)
      ->acceptJson()
      ->post('https://api.openai.com/v1/chat/completions', [
        'model' => config('ai.openai_text_model', 'gpt-4o-mini'),
        'temperature' => 0.4,
        'messages' => [
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

    if (!$resp->ok()) {
      throw new \RuntimeException('OpenAI request failed');
    }

    $j = $resp->json();

    return [
      'text' => (string)($j['choices'][0]['message']['content'] ?? ''),
      'usage' => $j['usage'] ?? null,
    ];
  }
}
