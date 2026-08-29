<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiImageEngineInterface;
use App\Services\Ai\Engines\GeminiImageEngine;
use App\Services\Ai\Engines\OpenAiImageEngine;
use App\Services\Ai\Engines\PollinationsImageEngine;

class AiImageManager
{
  public function engine(string $engine): AiImageEngineInterface
  {
    return match ($engine) {
      'openai' => app(OpenAiImageEngine::class),
      'gemini' => app(GeminiImageEngine::class),
      default  => app(PollinationsImageEngine::class),
    };
  }

  public function generateAndStore(array $data, ?string $engineKey = null): string
  {
    $primaryKey = $engineKey ?: 'gemini';
    $tryKeys = array_unique(array_filter([$primaryKey, 'gemini', 'openai', 'pollinations']));

    $lastException = null;

    foreach ($tryKeys as $key) {
      try {
        $engineInstance = match ($key) {
          'openai' => app(OpenAiImageEngine::class),
          'gemini' => app(GeminiImageEngine::class),
          default  => app(PollinationsImageEngine::class),
        };

        $url = $engineInstance->generateAndStore($data);
        if (!empty($url) && $url !== 'default.jpg') {
          return $url;
        }
      } catch (\Throwable $e) {
        $lastException = $e;
        \Illuminate\Support\Facades\Log::warning("AI Image Engine '{$key}' failed: " . $e->getMessage() . " - trying next fallback...");
      }
    }

    // Always attempt Pollinations free image generator as final fallback
    try {
      return app(PollinationsImageEngine::class)->generateAndStore($data);
    } catch (\Throwable $e) {
      // Ignore
    }

    if ($lastException) {
      throw $lastException;
    }

    throw new \RuntimeException('Failed to generate image with available engines.');
  }
}
