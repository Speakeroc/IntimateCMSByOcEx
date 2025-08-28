<?php

namespace Database\Seeders;

use App\Models\info\Banner;
use App\Models\system\Getters;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function run() {
        $this->getters->clearFolder(public_path('images/banner'));

        $banner_id = 1;

        for ($i = 1; $i <= 40; $i++) {
            $data['uniq_uid'] = $this->getters->generateUniqueId('%s__%s');
            $content = 'images/banner/' . $data['uniq_uid'];
            $image = $this->moveTempToFolder('/images/demo/banners/'.rand(1,5).'.png', $content, $data['uniq_uid']);
            Banner::create([ 'user_id' => 1, 'title' => 'Баннер '.$banner_id, 'banner' => $image, 'link' => '/', 'sort_order' => $banner_id, 'status' => 1]);
            $banner_id++;
        }
    }

    public function moveTempToFolder($image, $new_path, $target): ?string
    {
        $image_path = public_path($image);
        $directory = public_path($new_path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $files = File::files($directory);
        foreach ($files as $file) {
            if (preg_match('/\b' . preg_quote(mb_strtolower($target), '/') . '\b/', mb_strtolower($file->getFilename()))) {
                File::delete($file->getRealPath());
            }
        }

        $new_image_name = mb_strtolower($target) . '.' . File::extension($image_path);
        $new_image_path = $directory . '/' . $new_image_name;

        if (File::exists($image_path)) {
            File::copy($image_path, $new_image_path);
            $normalizedPath = str_replace('\\', '/', $new_image_path);
            $relativePath = strstr($normalizedPath, 'images');
            return '/' . $relativePath;
        } else {
            return null;
        }
    }

    public function generateUniqueId($template = '%s_%s', $length = 4): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $partsCount = substr_count($template, '%s');
        $parts = [];
        for ($i = 0; $i < $partsCount; $i++) {
            $parts[] = substr(str_shuffle($characters), 0, $length);
        }
        return vsprintf($template, $parts);
    }
}
