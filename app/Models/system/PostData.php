<?php

namespace App\Models\system;

use App\Models\location\City;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\posts\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostData extends Model
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    private array $default_data;
    private array $popular_data;
    private array $individual_data;
    private array $premium_data;
    private array $health_data;
    private array $latest_data;
    private array $elite_data;
    private array $bdsm_data;
    private array $masseuse_data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->default_data = ['status' => 0, 'watermark' => 0, 'count' => 0, 'template' => 'style_1'];
        $this->all_data = $this->getters->getSetting('home_all') ?? $this->default_data;
        $this->popular_data = $this->getters->getSetting('home_popular') ?? $this->default_data;
        $this->individual_data = $this->getters->getSetting('home_individual') ?? $this->default_data;
        $this->premium_data = $this->getters->getSetting('home_premium') ?? $this->default_data;
        $this->health_data = $this->getters->getSetting('home_health') ?? $this->default_data;
        $this->latest_data = $this->getters->getSetting('home_latest') ?? $this->default_data;
        $this->elite_data = $this->getters->getSetting('home_elite') ?? $this->default_data;
        $this->bdsm_data = $this->getters->getSetting('home_bdsm') ?? $this->default_data;
        $this->masseuse_data = $this->getters->getSetting('home_masseuse') ?? $this->default_data;
    }

    public function getPopularPost(): array
    {
        if (!$this->popular_data['status']) {
            return [];
        }
        $template = $this->popular_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('views_post_uniq')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('views_post_uniq')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('views_post_uniq')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('views_post_uniq')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('views_post_uniq')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('views_post_uniq')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->popular_data['count'])->get();
        return $this->getArrayData($template, $this->popular_data['watermark'], $items);
    }

    public function getAllPost(): array
    {
        if (!$this->all_data['status']) {
            return [];
        }
        $template = $this->all_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->all_data['count'])->get();
        return $this->getArrayData($template, $this->all_data['watermark'], $items);
    }

    public function getIndividualPost(): array
    {
        if (!$this->getters->getSetting('post_section_individuals_status')) {
            return [];
        }
        if (!$this->individual_data['status']) {
            return [];
        }
        $template = $this->individual_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('s_individuals', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('s_individuals', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('s_individuals', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($v_v)->union($v)->limit($this->individual_data['count'])->get();
        return $this->getArrayData($template, $this->individual_data['watermark'], $items);
    }

    public function getPremiumPost(): array
    {
        if (!$this->getters->getSetting('post_section_premium_status')) {
            return [];
        }
        if (!$this->premium_data['status']) {
            return [];
        }
        $template = $this->premium_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->premium_data['count'])->get();
        return $this->getArrayData($template, $this->premium_data['watermark'], $items);
    }

    public function getHealthPost(): array
    {
        if (!$this->getters->getSetting('post_section_health_status')) {
            return [];
        }
        if (!$this->health_data['status']) {
            return [];
        }
        $template = $this->health_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->health_data['count'])->get();
        return $this->getArrayData($template, $this->health_data['watermark'], $items);
    }

    public function getLatestPost(): array
    {
        if (!$this->latest_data['status']) {
            return [];
        }
        $template = $this->latest_data['template'];
        $items = Post::where('moderation_id', 1)->where('publish', 1)->orderByDesc('created_at')->limit($this->latest_data['count'])->get();
        return $this->getArrayData($template, $this->latest_data['watermark'], $items);
    }

    public function getElitePost(): array
    {
        if (!$this->getters->getSetting('post_section_elite_status')) {
            return [];
        }
        if (!$this->elite_data['status']) {
            return [];
        }
        $template = $this->elite_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->elite_data['count'])->get();
        return $this->getArrayData($template, $this->elite_data['watermark'], $items);
    }

    public function getBdsmPost(): array
    {
        if (!$this->getters->getSetting('post_section_bdsm_status')) {
            return [];
        }
        if (!$this->bdsm_data['status']) {
            return [];
        }
        $template = $this->bdsm_data['template'];
        $d_v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->bdsm_data['count'])->get();
        return $this->getArrayData($template, $this->bdsm_data['watermark'], $items);
    }

    public function getMasseusePost(): array
    {
        if (!$this->getters->getSetting('post_section_masseuse_status')) {
            return [];
        }
        if (!$this->masseuse_data['status']) {
            return [];
        }
        $template = $this->masseuse_data['template'];
        $d_v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $d_n_v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v_v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $v_n_v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 1)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 1)->orderByDesc('up_date')->limit(1000000000);
        $n_v = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->where('diamond', 0)->where('vip', 0)->where('verify', 0)->orderByDesc('up_date')->limit(1000000000);
        $items = $d_v->union($d_n_v)->union($v_v)->union($v_n_v)->union($v)->union($n_v)->limit($this->masseuse_data['count'])->get();
        return $this->getArrayData($template, $this->masseuse_data['watermark'], $items);
    }

    public function getArrayData($template, $watermark, $data): array
    {
        $response = [];

        $app_image_settings = app('image_settings');
        if ($template == 'style_1') {
            $image_height = $app_image_settings['posts']['style_1'];
        } elseif ($template == 'style_2') {
            $image_height = $app_image_settings['posts']['style_2'];
        } elseif ($template == 'style_3') {
            $image_height = $app_image_settings['posts']['style_3'];
        } elseif ($template == 'style_4') {
            $image_height = $app_image_settings['posts']['style_4'];
        } else {
            $image_height = 500;
        }

        foreach ($data as $item) {
            $image = $this->getters->getPostMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: $image_height,watermark: $watermark));
            } else {
                $image = url('no_image_round.png');
            }

            //City
            $city_id = $item['city_id'];
            $city_title = Cache::remember('city_title_' . $city_id, 60, function () use ($city_id) {
                return City::where('id', $city_id)->value('title');
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
                'desc' => Str::limit($item['description'], 150, '...'),
                'messengers' => json_decode($item['messengers'], true),
                'diamond' => $item['diamond'],
                'vip' => $item['vip'],
                'color' => $item['color'],
                'verify' => $item['verify'],
                'date_added' => ($this->getters->getSetting('post_block_date_status')) ? $this->getters->dateText($item['created_at']) : null,
                'microdata' => $this->getters->microDataPost($item['id']),
            ];

            $response[] = view('catalog/posts/include/'.$template, ['data' => $post_data]);
        }

        return $response;
    }

    public function getMainImage($post_id) {
        $image = Cache::remember('getMainImage_' . $post_id, 60, function () use ($post_id) {
            return PostContent::where('post_id', $post_id)->where('type', 'main')->first();
        });
        return $image->file ?? null;
    }
}
