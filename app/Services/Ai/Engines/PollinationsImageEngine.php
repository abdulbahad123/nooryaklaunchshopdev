<?php

namespace App\Services\Ai\Engines;

use App\Services\Ai\Contracts\AiImageEngineInterface;
use App\Services\Ai\Engines\Concerns\BuildsImagePrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PollinationsImageEngine implements AiImageEngineInterface
{
  use BuildsImagePrompt;

  public function generateAndStore(array $data): string
  {
    $prompt = trim((string)($data['prompt'] ?? ''));
    if ($prompt === '') {
      throw new \InvalidArgumentException('Prompt is required');
    }

    $finalPrompt = $this->buildPrompt(
      $prompt,
      (string)($data['style'] ?? ''),
      (string)($data['lighting'] ?? ''),
      (string)($data['angle'] ?? '')
    );

    [$w, $h] = $this->resolveSize((string)($data['size'] ?? 'square_1024'));

    $seed = random_int(1000, 999999);
    $url = "https://image.pollinations.ai/prompt/" . rawurlencode($finalPrompt) . "?width={$w}&height={$h}&seed={$seed}&model=flux&nologo=true";

    $resp = Http::timeout(90)->get($url);

    if (!$resp->ok()) {
      throw new \RuntimeException('Pollinations image request failed (' . $resp->status() . ')');
    }

    $contentType = (string) $resp->header('Content-Type', '');
    $ext = str_contains($contentType, 'png') ? 'png' : 'jpg';

    Storage::disk('public')->makeDirectory('ai/categories');

    $filename = 'poll_' . now()->format('Ymd_His') . '_' . \Illuminate\Support\Str::random(8) . '.' . $ext;
    $path = 'ai/categories/' . $filename;

    $saved = Storage::disk('public')->put($path, $resp->body());
    if (!$saved) {
      throw new \RuntimeException('Failed to save generated image');
    }

    $this->resizeStoredImage($path, $w, $h);

    return '/storage/' . $path;
  }
}
