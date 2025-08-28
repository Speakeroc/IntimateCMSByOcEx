<?php

namespace App\Http\Controllers\catalog\posts;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\posts\Post;
use App\Models\posts\Review;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class servicesController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_services') ?? ['count_per_page' => 20, 'watermark' => 0, 'template' => 'style_1'];
    }

    public function index()
    {
        $data['h1'] = __('catalog/page_titles.post_services_list_h1');
        $data['title'] = __('catalog/page_titles.post_services_list_t');
        $data['description'] = __('catalog/page_titles.post_services_list_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.services.list'));

        $services = app('post_services');

        $data['services'] = [];

        foreach ($services as $service) {
            $service_item = [];
            $service_item['title'] = $service['title'];
            foreach ($service['data'] as $item) {
                $post_count = $this->getters->getPostCountByService($item['id']);
                $service_item['data'][] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'posts' => trans_choice('choice.post', $post_count, ['num' => $post_count]),
                    'link' => route('client.services.item', ['service_name' => Str::slug($item['title'])]),
                ];
            }
            $data['services'][] = $service_item;
        }

        $breadcrumbs = [
            ['link' => route('client.services.list'), 'title' => __('catalog/page_titles.post_services_list')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/services', ['data' => $data]);
    }

    public function item($service_name): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $service_id = 0;
        $service_title = '';
        $services = app('post_services');
        foreach ($services as $service) {
            foreach ($service['data'] as $item) {
                if (Str::slug($item['title']) == $service_name) {
                    $service_id = $item['id'];
                    $service_title = $item['title'];
                }
            }
        }

        if (!$service_id) return redirect()->route('client.errors', ['code' => 404]);

        $data['title'] = __('catalog/page_titles.post_services_item_t', ['service' => $service_title]);
        $data['description'] = __('catalog/page_titles.post_services_item_d', ['service' => $service_title]);
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.services.item', ['service_name' => Str::slug($service_title)]));

        $template = $this->config['template'];
        $d_v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $data['data'] = $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->paginate($this->config['count_per_page']);

        $data['items'] = [];

        foreach ($items as $item) {
            $image = $this->getters->getPostMainImage($item['id']);
            $image_height = app('image_settings')['posts'][$template];
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: $image_height, watermark: $this->config['watermark']));
            } else {
                $image = url('no_image_round.png');
            }

            //Count by types
            $item_id = $item['id'];
            $content_types = $this->getters->getPluck_P_S_V_count($item_id);

            $post_data = [
                'id' => $item['id'],
                'image' => $image,
                'photo' => in_array('photo', $content_types),
                'selfie' => in_array('selfie', $content_types),
                'video' => in_array('video', $content_types),
                'name' => $item['name'],
                'age' => trans_choice(__('choice.age'), $item['age'], ['num' => $item['age']]),
                'link' => route('client.post', ['post_id' => $item['id'], 'name' => Str::slug($item['name'])]),
                'city' => ($item['city_id']) ? $this->getters->getCityInfo($item['city_id']) : null,
                'zone' => ($item['zone_id']) ? $this->getters->getZoneInfo($item['zone_id']) : null,
                'reviews' => Review::where('post_id', $item['id'])->where('moderation_id', 1)->count(),
                'breast' => $item['breast'],
                'weight' => __('lang.kilogram', ['num' => $item['weight']]),
                'height' => __('lang.centimeter', ['num' => $item['height']]),
                'tags' => $this->getters->getPostTags($item['id']),
                'price_hour' => $this->getters->currencyFormat($item['price_day_in_one']),
                'price_hours' => $this->getters->currencyFormat($item['price_day_in_two']),
                'desc' => Str::limit($item['description'], 150),
                'messengers' => json_decode($item['messengers'], true),
                'diamond' => $item['diamond'],
                'vip' => $item['vip'],
                'color' => $item['color'],
                'verify' => $item['verify'],
                'date_added' => ($this->getters->getSetting('post_block_date_status')) ? $this->getters->dateText($item['created_at']) : null,
                'microdata' => $this->getters->microDataPost($item['id']),
            ];

            $data['items'][] = view('catalog/posts/include/'.$template, ['data' => $post_data]);
        }

        $breadcrumbs = [
            ['link' => route('client.services.list'), 'title' => __('catalog/page_titles.post_services_list')],
            ['link' => route('client.services.item', ['service_name' => $service_name]), 'title' => $service_title]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/page', ['data' => $data]);
    }
}
