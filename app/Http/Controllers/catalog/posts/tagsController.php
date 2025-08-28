<?php

namespace App\Http\Controllers\catalog\posts;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\posts\Post;
use App\Models\posts\Review;
use App\Models\posts\Tags;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class tagsController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_tags') ?? ['count_per_page' => 20, 'watermark' => 0, 'template' => 'style_1'];
    }

    public function index()
    {
        $data['h1'] = __('catalog/page_titles.post_tags_list_h1');
        $data['title'] = __('catalog/page_titles.post_tags_list_t');
        $data['description'] = __('catalog/page_titles.post_tags_list_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.tags.list'));

        $tags = Cache::remember('tagsController_index', 120, function () {
            return Tags::get() ?? [];
        });

        $data['tags'] = [];

        foreach ($tags as $tag) {
            $post_count = $this->getters->getPostCountByTag($tag['id']);
            $tag_item = [
                'id' => $tag['id'],
                'title' => $tag['tag'],
                'posts' => trans_choice('choice.post', $post_count, ['num' => $post_count]),
                'link' => route('client.tags.item', ['tag_name' => Str::slug($tag['tag'])]),
            ];
            $data['tags'][] = $tag_item;
        }

        $breadcrumbs = [
            ['link' => route('client.tags.list'), 'title' => __('catalog/page_titles.post_tags_list')]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/tags', ['data' => $data]);
    }

    public function item($tag_name)
    {
        $tag_id = 0;
        $tag_title = '';
        $tags = Tags::get();
        foreach ($tags as $tag) {
            if (Str::slug($tag['tag']) == $tag_name) {
                $tag_id = $tag['id'];
                $tag_title = $tag['tag'];
            }
        }

        if (!$tag_id) return redirect()->route('client.errors', ['code' => 404]);

        $data['h1'] = __('catalog/page_titles.post_tags_item_h1', ['tag' => $tag_title]);
        $data['title'] = __('catalog/page_titles.post_tags_item_t', ['tag' => $tag_title]);
        $data['description'] = __('catalog/page_titles.post_tags_item_d', ['tag' => $tag_title]);
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.tags.item', ['tag_name' => Str::slug($tag_title)]));

        $template = $this->config['template'];
        $d_v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
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
            ['link' => route('client.tags.list'), 'title' => __('catalog/page_titles.post_tags_list')],
            ['link' => route('client.tags.item', ['tag_name' => $tag_name]), 'title' => $tag_title]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/page', ['data' => $data]);
    }
}
