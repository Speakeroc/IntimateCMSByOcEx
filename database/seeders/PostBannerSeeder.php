<?php

namespace Database\Seeders;

use App\Models\posts\Post;
use App\Models\posts\PostBanner;
use App\Models\system\Getters;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostBannerSeeder extends Seeder
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function run() {
        $this->getters->clearFolder(public_path('images/post_banner'));

        $banner_id = 1;

        for ($i = 1; $i <= 8; $i++) {
            $post_id = Post::inRandomOrder()->value('id');
            $activation = 1;
            $activation_date = Carbon::now()->addDays(rand(1, 15))->addHours(rand(1, 59))->addMinutes(rand(1, 59))->format('Y-m-d\TH:i');

            $data['uniq_uid'] = $this->getters->generateUniqueId('%s%s');
            $content = 'images/post_banner/' . $data['uniq_uid'];
            $image = $this->moveTempToFolder('/images/demo/post_banner/'.$banner_id.'.gif', $content, $data['uniq_uid']);
            PostBanner::create([
                'user_id' => 1,
                'post_id' => $post_id,
                'banner' => $image,
                'activation' => $activation,
                'activation_date' => $activation_date,
                'up_date' => Carbon::now(),
                'status' => 1
            ]);
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
