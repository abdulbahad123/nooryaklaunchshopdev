<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\FallbackMimeTypeGuesser;
use Symfony\Component\Mime\MimeTypes;

$mimeTypes = MimeTypes::getDefault();
$mimeTypes->registerGuesser(new FallbackMimeTypeGuesser());

echo "isGuesserSupported: " . ($mimeTypes->isGuesserSupported() ? "YES" : "NO") . PHP_EOL;

// Test guessing mime type for a dummy file path
$guesser = new FallbackMimeTypeGuesser();

// Create temp image files to test
$testPng = __DIR__ . '/test.png';
file_put_contents($testPng, "\x89PNG\x0D\x0A\x1A\x0A\x00\x00\x00\x0DIHDR");
echo "PNG MIME: " . $mimeTypes->guessMimeType($testPng) . PHP_EOL;
@unlink($testPng);

$testJpg = __DIR__ . '/test.jpg';
file_put_contents($testJpg, "\xFF\xD8\xFF\xE0\x00\x10JFIF");
echo "JPG MIME: " . $mimeTypes->guessMimeType($testJpg) . PHP_EOL;
@unlink($testJpg);

$testSvg = __DIR__ . '/test.svg';
file_put_contents($testSvg, '<svg xmlns="http://www.w3.org/2000/svg"></svg>');
echo "SVG MIME: " . $mimeTypes->guessMimeType($testSvg) . PHP_EOL;
@unlink($testSvg);

echo "Test completed successfully!" . PHP_EOL;
