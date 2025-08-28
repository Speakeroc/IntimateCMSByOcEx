<?php

namespace App\Http\Controllers\catalog\location;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Zone;
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

class zoneController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_zone') ?? ['count_per_page' => 20, 'watermark' => 0, 'template' => 'style_1'];
    }

    public function index()
    {
        $data['title'] = __('catalog/page_titles.post_zone_list');
        $title = __('catalog/page_titles.post_zone_list_t');
        $data['description'] = __('catalog/page_titles.post_zone_list_d');
        $this->getters->setMetaInfo(title: $title, description: $data['description'], url: route('client.zone.list'));

        $zones = Zone::get();

        $data['items'] = [];

        foreach ($zones as $zone) {
            $post_count = $this->getters->getPostCountByZone($zone['id']);
            $title = $zone['title'];
            $zone_item = [
                'id' => $zone['id'],
                'title' => $title,
                'count' => $post_count,
                'posts' => trans_choice('choice.post', $post_count, ['num' => $post_count]),
                'link' => route('client.zone.item', ['zone_id' => $zone['id'], 'title' => Str::slug($zone['title'])]),
            ];
            $data['items'][$zone['city_id']]['title'] = City::where('id', $zone['city_id'])->value('title');
            $data['items'][$zone['city_id']]['data'][] = $zone_item;
        }

        foreach ($data['items'] as &$item) {
            if (isset($item['data'])) {
                $item['data'] = collect($item['data'])->sortBy('title')->values()->toArray();
            }
        }

        $breadcrumbs = [
            ['link' => route('client.zone.list'), 'title' => __('catalog/page_titles.post_zone_list')]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/location/zone', ['data' => $data]);
    }

    public function item($zone_id, $title): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $zone = Zone::where('id', $zone_id)->first();
        $current_name = Str::slug($zone['title']);

        //Redirect checked
        if (!empty($current_name) && $title != $current_name) return redirect()->route('client.zone.item', ['zone_id' => $zone_id, 'title' => $current_name]);
        if (empty($current_name)) return redirect()->route('client.errors', ['code' => 404]);

        $data['h1'] = __('catalog/page_titles.post_zone_item_h1', ['zone' => $zone['title']]);
        $data['title'] = __('catalog/page_titles.post_zone_item_t', ['zone' => $zone['title']]);
        $data['description'] = __('catalog/page_titles.post_zone_item_d', ['zone' => $zone['title']]);
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.zone.item', ['zone_id' => $zone_id, 'title' => Str::slug($current_name)]));

        $template = $this->config['template'];
        $d_v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
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

            //City
            $item_city_id = $item['city_id'];
            $city_title = Cache::remember('city_title_' . $item_city_id, 60, function () use ($item_city_id) {
                return City::where('id', $item_city_id)->value('title');
            });

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
                'date_added' => $this->getters->dateText($item['created_at']),
                'microdata' => $this->getters->microDataPost($item['id']),
            ];

            $data['items'][] = view('catalog/posts/include/'.$template, ['data' => $post_data]);
        }

        $breadcrumbs = [
            ['link' => route('client.zone.list'), 'title' => __('catalog/page_titles.post_zone_list')],
            ['link' => route('client.zone.item', ['zone_id' => $zone_id, 'title' => $current_name]), 'title' => $zone['title']]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/page', ['data' => $data]);
    }
}
