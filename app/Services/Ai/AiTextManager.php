<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiTextEngineInterface;
use App\Services\Ai\Engines\GeminiTextEngine;
use App\Services\Ai\Engines\OpenAiTextEngine;
use App\Services\Ai\Engines\PollinationsTextEngine;

class AiTextManager
{
  /** @var array<string, AiTextEngineInterface> */
  private array $engines;

  public function __construct(
    PollinationsTextEngine $pollinations,
    OpenAiTextEngine $openai,
    GeminiTextEngine $gemini,
  ) {
    $this->engines = [
      $pollinations->key() => $pollinations,
      $openai->key() => $openai,
      $gemini->key() => $gemini,
    ];
  }

  public function engine(?string $key = null): AiTextEngineInterface
  {
    $key = $key ?: config('ai.default_text_engine', 'openai');

    if (!isset($this->engines[$key])) {
      // fallback to default
      $fallback = config('ai.default_text_engine', 'openai');
      return $this->engines[$fallback];
    }

    return $this->engines[$key];
  }

  public function generate(string $prompt, ?string $engineKey = null): string
  {
    return $this->engine($engineKey)->generate($prompt);
  }

  public function generateWithMeta(string $prompt, ?string $engineKey = null): array
  {
    $primaryKey = $engineKey ?: config('ai.default_text_engine', 'gemini');

    // Engine fallback sequence
    $tryKeys = array_unique(array_filter([
      $primaryKey,
      'gemini',
      'openai',
      'pollinations'
    ]));

    $lastException = null;

    foreach ($tryKeys as $key) {
      if (!isset($this->engines[$key])) {
        continue;
      }

      try {
        $engine = $this->engines[$key];
        if (method_exists($engine, 'generateWithMeta')) {
          $res = $engine->generateWithMeta($prompt);
          if (!empty($res['text'])) {
            return $res;
          }
        } else {
          $text = $engine->generate($prompt);
          if (!empty($text)) {
            return [
              'text' => $text,
              'usage' => null,
            ];
          }
        }
      } catch (\Throwable $e) {
        $lastException = $e;
        \Illuminate\Support\Facades\Log::warning("AI Engine '{$key}' failed: " . $e->getMessage() . " - Trying next fallback engine...");
      }
    }

    if ($lastException) {
      throw $lastException;
    }

    throw new \RuntimeException('All AI engines failed to generate content.');
  }
}
