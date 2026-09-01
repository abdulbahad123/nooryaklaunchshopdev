<?php

namespace App\Services\Ai\Engines\Concerns;

use Illuminate\Support\Str;

trait BuildsImagePrompt
{
  protected function resolveSize(string $size): array
  {
    // Allow custom sizes from UI: custom_{width}_{height}
    if (preg_match('/^custom_(\d{2,4})_(\d{2,4})$/', $size, $m)) {
      return [(int) $m[1], (int) $m[2]];
    }

    return match ($size) {
      'portrait_1024_1536'  => [1024, 1536],
      'landscape_1536_1024' => [1536, 1024],
      default               => [1024, 1024],
    };
  }

  protected function buildPrompt(string $prompt, string $style, string $lighting, string $angle, int $variantIndex = 0, int $variantTotal = 1, bool $hasReference = false): string
  {
    $chunks = [$prompt];

    if ($hasReference) {
      $chunks[] = 'product variation based on reference image design, matching exact product shape and item structure';
    }

    if ($variantTotal > 1) {
      $variantModifiers = [
        0 => 'variant 1: front studio perspective, pristine crisp lighting, official product render',
        1 => 'variant 2: 45-degree angle perspective, alternate color scheme, modern lifestyle studio setup',
        2 => 'variant 3: side profile view, luxury material finish, high-contrast accent lighting',
        3 => 'variant 4: top-down elevated view, vibrant color tone, soft shadow detail',
        4 => 'variant 5: close-up detail view, premium aesthetic background, warm ambient lighting',
        5 => 'variant 6: isometric view, sleek minimal background, soft studio glow',
      ];

      $mod = $variantModifiers[$variantIndex % count($variantModifiers)];
      $chunks[] = $mod;
    }

    $styleMap = [
      'photorealistic'    => 'photorealistic',
      '3d_render'         => '3d render',
      'flat_illustration' => 'flat illustration',
      'minimal'           => 'minimal',
    ];
    $lightingMap = [
      'natural'  => 'natural light',
      'studio'   => 'studio lighting',
      'soft'     => 'soft light',
      'dramatic' => 'dramatic lighting',
    ];
    $angleMap = [
      'eye_level' => 'eye-level',
      'top_down'  => 'top-down',
      'close_up'  => 'close-up',
      'wide'      => 'wide shot',
    ];

    if (isset($styleMap[$style])) $chunks[] = $styleMap[$style];
    if (isset($lightingMap[$lighting])) $chunks[] = $lightingMap[$lighting];
    if (isset($angleMap[$angle])) $chunks[] = $angleMap[$angle];

    $chunks[] = 'high quality, clean background, product thumbnail';

    return implode(', ', $chunks);
  }

  protected function resizeStoredImage(string $storagePath, int $width, int $height): void
  {
    if ($width <= 0 || $height <= 0) {
      return;
    }

    $relative = ltrim($storagePath, '/');
    $absPath = storage_path('app/public/' . $relative);

    if (!file_exists($absPath)) {
      return;
    }

    try {
      $img = \Intervention\Image\Facades\Image::make($absPath);
      if ((int) $img->width() === $width && (int) $img->height() === $height) {
        return;
      }

      $img->resize($width, $height, function ($constraint) {
        $constraint->upsize();
      })->save($absPath);
    } catch (\Throwable $e) {
      // If resize fails, keep the original image.
    }
  }

  protected function storageBase(string $prefix): string
  {
    return 'ai/categories/' . $prefix . now()->format('Ymd_His') . '_' . Str::random(8);
  }
}
