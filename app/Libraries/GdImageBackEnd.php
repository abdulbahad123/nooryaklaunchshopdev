<?php
declare(strict_types = 1);

namespace App\Libraries;

use BaconQrCode\Exception\RuntimeException;
use BaconQrCode\Renderer\Color\Alpha;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\TransformationMatrix;
use BaconQrCode\Renderer\Path\Close;
use BaconQrCode\Renderer\Path\Curve;
use BaconQrCode\Renderer\Path\EllipticArc;
use BaconQrCode\Renderer\Path\Line;
use BaconQrCode\Renderer\Path\Move;
use BaconQrCode\Renderer\Path\Path;
use BaconQrCode\Renderer\RendererStyle\Gradient;

final class GdImageBackEnd implements ImageBackEndInterface
{
    private $image;
    private $matrices;
    private $matrixIndex;
    private $imageFormat;

    public function __construct(string $imageFormat = 'png')
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('You need to install the gd extension to use this back end');
        }
        $this->imageFormat = $imageFormat;
    }

    public function new(int $size, ColorInterface $backgroundColor): void
    {
        $this->image = imagecreatetruecolor($size, $size);
        imagealphablending($this->image, true);
        imagesavealpha($this->image, true);

        $bg = $this->getColor($backgroundColor);
        imagefill($this->image, 0, 0, $bg);

        $this->matrices = [new TransformationMatrix()];
        $this->matrixIndex = 0;
    }

    public function scale(float $size): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $this->matrices[$this->matrixIndex] = $this->matrices[$this->matrixIndex]->multiply(TransformationMatrix::scale($size));
    }

    public function translate(float $x, float $y): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $this->matrices[$this->matrixIndex] = $this->matrices[$this->matrixIndex]->multiply(TransformationMatrix::translate($x, $y));
    }

    public function rotate(int $degrees): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $this->matrices[$this->matrixIndex] = $this->matrices[$this->matrixIndex]->multiply(TransformationMatrix::rotate($degrees));
    }

    public function push(): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $this->matrices[++$this->matrixIndex] = $this->matrices[$this->matrixIndex - 1];
    }

    public function pop(): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        unset($this->matrices[$this->matrixIndex--]);
    }

    public function drawPathWithColor(Path $path, ColorInterface $color): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $gdColor = $this->getColor($color);
        $this->drawPath($path, $gdColor);
    }

    public function drawPathWithGradient(Path $path, Gradient $gradient, float $x, float $y, float $width, float $height): void
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        $gdColor = $this->getColor($gradient->getStartColor());
        $this->drawPath($path, $gdColor);
    }

    public function done(): string
    {
        if (null === $this->image) {
            throw new RuntimeException('No image has been started');
        }
        ob_start();
        imagepng($this->image);
        $blob = ob_get_clean();
        if (PHP_VERSION_ID < 80000) {
            imagedestroy($this->image);
        }
        $this->image = null;
        $this->matrices = null;
        return $blob;
    }

    private function drawPath(Path $path, int $gdColor): void
    {
        $points = [];
        foreach ($path as $op) {
            if ($op instanceof Move) {
                if (count($points) >= 6) {
                    imagefilledpolygon($this->image, $points, $gdColor);
                }
                list($tx, $ty) = $this->matrices[$this->matrixIndex]->apply($op->getX(), $op->getY());
                $points = [round($tx), round($ty)];
            } elseif ($op instanceof Line) {
                list($tx, $ty) = $this->matrices[$this->matrixIndex]->apply($op->getX(), $op->getY());
                $points[] = round($tx);
                $points[] = round($ty);
            } elseif ($op instanceof Curve) {
                $x0 = count($points) >= 2 ? $points[count($points) - 2] : 0;
                $y0 = count($points) >= 2 ? $points[count($points) - 1] : 0;
                list($tx1, $ty1) = $this->matrices[$this->matrixIndex]->apply($op->getX1(), $op->getY1());
                list($tx2, $ty2) = $this->matrices[$this->matrixIndex]->apply($op->getX2(), $op->getY2());
                list($tx3, $ty3) = $this->matrices[$this->matrixIndex]->apply($op->getX3(), $op->getY3());
                for ($i = 1; $i <= 8; $i++) {
                    $t = $i / 8.0;
                    $mt = 1.0 - $t;
                    $bx = ($mt*$mt*$mt)*$x0 + 3*($mt*$mt)*$t*$tx1 + 3*$mt*($t*$t)*$tx2 + ($t*$t*$t)*$tx3;
                    $by = ($mt*$mt*$mt)*$y0 + 3*($mt*$mt)*$t*$ty1 + 3*$mt*($t*$t)*$ty2 + ($t*$t*$t)*$ty3;
                    $points[] = round($bx);
                    $points[] = round($by);
                }
            } elseif ($op instanceof Close) {
                if (count($points) >= 6) {
                    imagefilledpolygon($this->image, $points, $gdColor);
                    $points = [];
                }
            }
        }
        if (count($points) >= 6) {
            imagefilledpolygon($this->image, $points, $gdColor);
        }
    }

    private function getColor(ColorInterface $color): int
    {
        $alpha = 100;
        if ($color instanceof Alpha) {
            $alpha = $color->getAlpha();
            $color = $color->getBaseColor();
        }
        $rgb = $color->toRgb();
        $gdAlpha = (int) round((100 - $alpha) * 127 / 100);
        if ($gdAlpha < 0) $gdAlpha = 0;
        if ($gdAlpha > 127) $gdAlpha = 127;
        return imagecolorallocatealpha($this->image, $rgb->getRed(), $rgb->getGreen(), $rgb->getBlue(), $gdAlpha);
    }
}
