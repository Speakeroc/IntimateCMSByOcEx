<?php

namespace App\Models\system;

use App\Models\Info\News;
use App\Models\posts\SalonContent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class NewsData extends Model
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    private array $default_data;
    private array $data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->default_data = ['status' => 0, 'count' => 0];
        $this->data = $this->getters->getSetting('home_news') ?? $this->default_data;
    }

    public function getNews(): array
    {
        if (!$this->data['status']) {
            return [];
        }
        $template = 'big';
        $pinned = News::where('status', 1)->where('created_at', '<', Carbon::now())->where('pinned', 1)->orderByDesc('created_at')->limit(1000000000);
        $no_pinned = News::where('status', 1)->where('created_at', '<', Carbon::now())->where('pinned', 0)->orderByDesc('created_at')->limit(1000000000);
        $items = $pinned->union($no_pinned)->limit($this->data['count'])->get();
        return $this->getArrayData($template, $items);
    }

    public function getArrayData($template, $data): array
    {
        $response = [];

        foreach ($data as $item) {
            if (!empty($item['image']) && File::exists(public_path($item['image']))) {
                $image = url($this->imageConverter->toMini($item['image'], width: 500));
            } else {
                $image = url('no_image_round.png');
            }

            $salon_data = [
                'id' => $item['id'],
                'image' => $image,
                'title' => $item['title'],
                'pinned' => $item['pinned'],
                'link' => route('client.news', ['news_id' => $item['id'], 'title' => Str::slug($item['title'])]),
                'desc' => Str::limit($this->getters->reverseTextData($item['desc']), 150, '...'),
                'date_added' => $this->getters->dateText($item['created_at']),
            ];

            $response[] = view('catalog/info/news/include/'.$template, ['data' => $salon_data]);
        }

        return $response;
    }

    public function getMainImage($salon_id) {
        $image = Cache::remember('getSalonImage_' . $salon_id, 60, function () use ($salon_id) {
            return SalonContent::where('salon_id', $salon_id)->where('type', 'main')->first();
        });
        return $image->file ?? null;
    }
}
