<?php
namespace App\Libraries;

use SimpleSoftwareIO\QrCode\Generator;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

class QrCodeGenerator extends Generator
{
    public function getFormatter(): ImageBackEndInterface
    {
        if ($this->format === 'png') {
            if (!class_exists(\Imagick::class)) {
                return new GdImageBackEnd();
            }
            return new ImagickImageBackEnd('png');
        }

        if ($this->format === 'eps') {
            return new EpsImageBackEnd;
        }

        return new SvgImageBackEnd;
    }
}
