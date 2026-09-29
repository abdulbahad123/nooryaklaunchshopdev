<?php

namespace App\Services;

use Symfony\Component\Mime\MimeTypeGuesserInterface;

class FallbackMimeTypeGuesser implements MimeTypeGuesserInterface
{
    private static array $mimeMap = [
        // Images
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'png'   => 'image/png',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'bmp'   => 'image/bmp',
        'tif'   => 'image/tiff',
        'tiff'  => 'image/tiff',
        'avif'  => 'image/avif',
        'heic'  => 'image/heic',
        
        // Documents
        'pdf'   => 'application/pdf',
        'doc'   => 'application/msword',
        'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'   => 'application/vnd.ms-excel',
        'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'csv'   => 'text/csv',
        'txt'   => 'text/plain',
        'zip'   => 'application/zip',
        'rar'   => 'application/x-rar-compressed',
        'json'  => 'application/json',
        'xml'   => 'application/xml',
        
        // Audio/Video
        'mp4'   => 'video/mp4',
        'mp3'   => 'audio/mpeg',
        'webm'  => 'video/webm',
        'ogg'   => 'audio/ogg',
        'wav'   => 'audio/wav',
    ];

    /**
     * Always return true so Symfony MimeTypes registers at least one supported guesser
     * even when php_fileinfo extension is disabled on host server.
     */
    public function isGuesserSupported(): bool
    {
        return true;
    }

    /**
     * Guesses the MIME type of a file by inspection of magic bytes or file extension.
     */
    public function guessMimeType(string $path): ?string
    {
        if (!file_exists($path) || !is_readable($path)) {
            return null;
        }

        // Try reading magic bytes
        $handle = @fopen($path, 'rb');
        if ($handle) {
            $bytes = fread($handle, 12);
            fclose($handle);

            if ($bytes !== false && strlen($bytes) >= 3) {
                // JPEG magic bytes: FF D8 FF
                if (substr($bytes, 0, 3) === "\xFF\xD8\xFF") {
                    return 'image/jpeg';
                }
                // PNG magic bytes: \x89PNG\x0D\x0A\x1A\x0A
                if (strlen($bytes) >= 8 && substr($bytes, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") {
                    return 'image/png';
                }
                // GIF magic bytes: GIF87a or GIF89a
                if (strlen($bytes) >= 6 && (substr($bytes, 0, 6) === 'GIF87a' || substr($bytes, 0, 6) === 'GIF89a')) {
                    return 'image/gif';
                }
                // WEBP magic bytes: RIFF....WEBP
                if (strlen($bytes) >= 12 && substr($bytes, 0, 4) === 'RIFF' && substr($bytes, 8, 4) === 'WEBP') {
                    return 'image/webp';
                }
                // PDF magic bytes: %PDF-
                if (strlen($bytes) >= 4 && substr($bytes, 0, 4) === '%PDF') {
                    return 'application/pdf';
                }
                // SVG check
                if (str_contains(strtolower($bytes), '<svg')) {
                    return 'image/svg+xml';
                }
            }
        }

        // Check full file header for SVG if xml/svg wrapper
        $fullHeader = @file_get_contents($path, false, null, 0, 512);
        if ($fullHeader && str_contains(strtolower($fullHeader), '<svg')) {
            return 'image/svg+xml';
        }

        // Fallback to extension check
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext && isset(self::$mimeMap[$ext])) {
            return self::$mimeMap[$ext];
        }

        return 'application/octet-stream';
    }
}
