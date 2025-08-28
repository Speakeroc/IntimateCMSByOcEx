<?php

namespace App\Models\system;

use App\Models\Info\Banner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BannerData extends Model
{
    private Getters $getters;

    private array $banner_data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->default_data = ['count' => 0, 'status' => 0, 'sort_order' => 0];
        $this->banner_data = $this->getters->getSetting('home_banners') ?? $this->default_data;
    }

    public function getBanner(): array
    {
        if (!$this->banner_data['status']) {
            return [];
        }
        $template = 'banner';
        $items = Banner::where('status', 1)->where('created_at', '<', Carbon::now())->orderByDesc('sort_order')->limit($this->banner_data['count'])->get();

        $data = [];

        foreach ($items as $item) {
            $banner = [
                'id' => $item['id'],
                'banner' => $item['banner'],
                'link' => $item['link'],
            ];

            $data[] = view('catalog/posts/include/'.$template, ['data' => $banner]);
        }

        return $data;
    }
}
