<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

class ImageConverter extends Model
{

    private string $watermark;
    private int $watermark_status;

    public function __construct() {
        $this->getters = new Getters;
        $this->watermark = $this->getters->getSetting('image_watermark') ?? null;
        $this->watermark_status = $this->getters->getSetting('watermark_status') ?? 0;
    }

    public function toMini($imgPath, $width = null, $height = null, $prefix = null, $watermark = false): string
    {
        if (empty($imgPath)) {
            $imgPath = 'no_image.png';
        }

        if (strtolower(pathinfo($imgPath, PATHINFO_EXTENSION)) === 'svg') {
            return $imgPath;
        }

        if (!File::exists(public_path($imgPath))) {
            $imgPath = 'no_image.png';
        }

        $pathResult = str_replace('images', 'images/cache', dirname($imgPath));

        if (!File::exists(public_path($pathResult))) {
            File::makeDirectory(public_path($pathResult), 0755, true);
        }

        $imgName = pathinfo($imgPath, PATHINFO_FILENAME);
        $imgResultWebp = $pathResult . '/' . $imgName . ($prefix ? "_$prefix" : '') . ($width ? "_$width" : '') . ($height ? "_$height" : '') . ($watermark ? "_wm" : '') . '.webp';

        // Проверяем, если изображение уже существует, возвращаем его
        if (File::exists(public_path($imgResultWebp))) {
            return $imgResultWebp;
        }

        // Загружаем изображение
        $manager = new ImageManager();
        $image = $manager->make(public_path($imgPath));

        // Проверка изменения размера
        if ($width && $height) {
            $image->fit($width, $height);
        } elseif ($width || $height) {
            $image->resize($width ?? null, $height ?? null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $watermark_position = (int)$this->getters->getSetting('watermark_position') ?? 5;

        // Проверка применения водяного знака
        if ($watermark && $this->watermark_status && !empty($this->watermark)) {
            $cachedWatermark = $this->getCachedWatermark($image->width());
            if ($cachedWatermark) {
                $image->insert($cachedWatermark, 'center', 15, 15);
            } else {
                throw new \Exception("Файл водяного знака не найден: " . $this->watermark);
            }
        }

        $image->encode('webp', 100);
        $image->save(public_path($imgResultWebp));

        return $imgResultWebp;
    }

    private function getCachedWatermark($imageWidth): ?\Intervention\Image\Image
    {
        static $cachedWatermark = null;
        if ($cachedWatermark === null) {
            $watermarkPath = public_path($this->watermark);
            if (File::exists($watermarkPath)) {
                $watermarkImg = Image::make($watermarkPath);
                $newWidth = $imageWidth * 0.4;
                $watermarkImg->resize($newWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $cachedWatermark = $watermarkImg;
            }
        }
        return $cachedWatermark;
    }
}
