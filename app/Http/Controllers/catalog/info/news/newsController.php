<?php

namespace App\Http\Controllers\catalog\Info\news;

use App\Http\Controllers\Controller;
use App\Models\Info\News;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class newsController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_news') ?? ['count_per_page' => 20, 'watermark' => 0];
    }

    public function index()
    {
        $data['h1'] = __('catalog/page_titles.post_news_h1');
        $data['title'] = __('catalog/page_titles.post_news_t');
        $data['description'] = __('catalog/page_titles.post_news_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.news.all'));

        $template = 'big';
        $pinned = News::where('status', 1)->where('created_at', '<', Carbon::now())->where('pinned', 1)->orderByDesc('created_at')->limit(1000000000);
        $no_pinned = News::where('status', 1)->where('created_at', '<', Carbon::now())->where('pinned', 0)->orderByDesc('created_at')->limit(1000000000);
        $data['data'] = $items = $pinned->union($no_pinned)->paginate($this->config['count_per_page']);

        $data['items'] = [];

        foreach ($items as $item) {
            if (!empty($item['image']) && File::exists(public_path($item['image']))) {
                $image = url($this->imageConverter->toMini($item['image'], width: 500));
            } else {
                $image = url('no_image_round.png');
            }

            $post_data = [
                'id' => $item['id'],
                'image' => $image,
                'title' => $item['title'],
                'pinned' => $item['pinned'],
                'link' => route('client.news', ['news_id' => $item['id'], 'title' => Str::slug($item['title'])]),
                'desc' => Str::limit($this->getters->reverseTextData($item['desc']), 150),
                'date_added' => $this->getters->dateText($item['created_at']),
            ];

            $data['items'][] = view('catalog/info/news/include/'.$template, ['data' => $post_data]);
        }

        $breadcrumbs = [
            ['link' => route('client.news.all'), 'title' => __('catalog/page_titles.post_news')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/news/page', ['data' => $data]);
    }
}
