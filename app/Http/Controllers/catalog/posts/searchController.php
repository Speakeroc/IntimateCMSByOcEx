<?php

namespace App\Http\Controllers\catalog\posts;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\posts\Review;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class searchController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_search') ?? ['count_per_page' => 20, 'watermark' => 0, 'template' => 'style_1'];
    }

    public function index(Request $request)
    {
        $data['h1'] = __('catalog/page_titles.post_search_h1');
        $data['title'] = __('catalog/page_titles.post_search_t');
        $data['description'] = __('catalog/page_titles.post_search_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.post.search'));

        //Searching
        $filter['filtered'] = 0;
        $filter['section'] = (array)$request->input('section') ?? [];
        $filter['zone'] = (array)$request->input('zone') ?? [];
        $filter['metro'] = (array)$request->input('metro') ?? [];
        $filter['tags'] = (array)$request->input('tags') ?? [];
        $filter['language_skills'] = (array)$request->input('language_skills') ?? [];
        $filter['visit_places'] = (array)$request->input('visit_places') ?? [];
        $filter['express'] = (int)$request->input('express') ?? null;
        $filter['health'] = (int)$request->input('health') ?? null;
        $filter['apartment'] = (int)$request->input('apartment') ?? null;
        $filter['arrival'] = (int)$request->input('arrival') ?? null;
        $filter['age_min'] = (int)$request->input('age_min') ?? 18;
        $filter['age_max'] = (int)$request->input('age_max') ?? 99;
        $filter['height_min'] = (int)$request->input('height_min') ?? 120;
        $filter['height_max'] = (int)$request->input('height_max') ?? 220;
        $filter['breast_min'] = (int)$request->input('breast_min') ?? 1;
        $filter['breast_max'] = (int)$request->input('breast_max') ?? 12;
        $filter['shoes_min'] = (int)$request->input('shoes_min') ?? 30;
        $filter['shoes_max'] = (int)$request->input('shoes_max') ?? 46;
        $filter['cloth_min'] = (int)$request->input('cloth_min') ?? 25;
        $filter['cloth_max'] = (int)$request->input('cloth_max') ?? 60;
        $filter['nationality'] = (array)$request->input('nationality') ?? [];
        $filter['body_type'] = (array)$request->input('body_type') ?? [];
        $filter['hair_color'] = (array)$request->input('hair_color') ?? [];
        $filter['hairy'] = (array)$request->input('hairy') ?? [];
        $filter['body_art'] = (array)$request->input('body_art') ?? [];
        $filter['name_or_desc'] = (string)$request->input('name_or_desc') ?? null;

        //Prices
        $filter['price_day_in_one_min'] = (int)$request->input('price_day_in_one_min') ?? 3000;
        $filter['price_day_in_one_max'] = (int)$request->input('price_day_in_one_max') ?? 100000;
        $filter['price_day_in_two_min'] = (int)$request->input('price_day_in_two_min') ?? 5000;
        $filter['price_day_in_two_max'] = (int)$request->input('price_day_in_two_max') ?? 200000;
        $filter['price_day_out_one_min'] = (int)$request->input('price_day_out_one_min') ?? 3000;
        $filter['price_day_out_one_max'] = (int)$request->input('price_day_out_one_max') ?? 100000;
        $filter['price_day_out_two_min'] = (int)$request->input('price_day_out_two_min') ?? 5000;
        $filter['price_day_out_two_max'] = (int)$request->input('price_day_out_two_max') ?? 200000;
        $filter['price_night_in_one_min'] = (int)$request->input('price_night_in_one_min') ?? 5000;
        $filter['price_night_in_one_max'] = (int)$request->input('price_night_in_one_max') ?? 500000;
        $filter['price_night_in_night_min'] = (int)$request->input('price_night_in_night_min') ?? 5000;
        $filter['price_night_in_night_max'] = (int)$request->input('price_night_in_night_max') ?? 500000;
        $filter['price_night_out_one_min'] = (int)$request->input('price_night_out_one_min') ?? 5000;
        $filter['price_night_out_one_max'] = (int)$request->input('price_night_out_one_max') ?? 500000;
        $filter['price_night_out_night_min'] = (int)$request->input('price_night_out_night_min') ?? 5000;
        $filter['price_night_out_night_max'] = (int)$request->input('price_night_out_night_max') ?? 500000;

        //Services
        $filter['services'] = (array)$request->input('services') ?? [];

        $data['posts'] = $this->filtered($filter);

        //Searching

        $data['search_data'] = $this->getters->getSearchData();

        $breadcrumbs = [
            ['link' => route('client.post.search'), 'title' => __('catalog/page_titles.post_search')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['currency_symbol'] = $this->getters->getCurrencySymbol();
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/search', ['data' => $data]);
    }

    public function filtered($filter_data): array
    {
        $query = Post::query();

        $data['filtered'] = 0;

        $query->where('publish', 1);

        //Sections
        if (!empty($filter_data['section'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['section'] as $section) {
                    $q->orWhere('s_' . $section, 1);
                }
            });
            $data['filtered']++;
        }

        //Zone
        if (!empty($filter_data['zone'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['zone'] as $zone) {
                    $q->orWhere('zone_id', $zone);
                }
            });
            $data['filtered']++;
        }

        //Metro
        if (!empty($filter_data['metro'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['metro'] as $metro) {
                    $q->orWhere('metro_id', $metro);
                }
            });
            $data['filtered']++;
        }

        //Tags
        if (!empty($filter_data['tags'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['tags'] as $tag) {
                    $q->orWhereJsonContains('tags', (string)$tag);
                }
            });
            $data['filtered']++;
        }

        //Language Skills
        if (!empty($filter_data['language_skills'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['language_skills'] as $language_skill) {
                    $q->orWhereJsonContains('language_skills', (string)$language_skill);
                }
            });
            $data['filtered']++;
        }

        //Visit places
        if (!empty($filter_data['visit_places'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['visit_places'] as $visit_place) {
                    $q->orWhereJsonContains('visit_places', (string)$visit_place);
                }
            });
            $data['filtered']++;
        }

        //Express
        if (!empty($filter_data['express'])) {
            $query->where('express', $filter_data['express']);
            $data['filtered']++;
        }

        //Section Health
        if (!empty($filter_data['health'])) {
            $query->where('s_health', $filter_data['health']);
            $data['filtered']++;
        }

        //Apartment
        if (!empty($filter_data['apartment'])) {
            $apartments = ['price_day_in_one','price_day_in_two','price_night_in_one','price_night_in_night'];
            $query->where(function ($q) use ($apartments) {
                foreach ($apartments as $apartment) {
                    $q->orWhere($apartment, '>', 0);
                }
            });
            $data['filtered']++;
        }

        //Arrival
        if (!empty($filter_data['arrival'])) {
            $arrivals = ['price_day_out_one','price_day_out_two','price_night_out_one','price_night_out_night'];
            $query->where(function ($q) use ($arrivals) {
                foreach ($arrivals as $arrival) {
                    $q->orWhere($arrival, '>', 0);
                }
            });
            $data['filtered']++;
        }

        //Age
        if (!empty($filter_data['age_min']) && !empty($filter_data['age_max'])) {
            $query->where('age', '>=', $filter_data['age_min']);
            $query->where('age', '<=', $filter_data['age_max']);
            $data['filtered']++;
        }

        //Height
        if (!empty($filter_data['height_min']) && !empty($filter_data['height_max'])) {
            $query->where('height', '>=', $filter_data['height_min']);
            $query->where('height', '<=', $filter_data['height_max']);
            $data['filtered']++;
        }

        //Breast
        if (!empty($filter_data['breast_min']) && !empty($filter_data['breast_max'])) {
            $query->where('breast', '>=', $filter_data['breast_min']);
            $query->where('breast', '<=', $filter_data['breast_max']);
            $data['filtered']++;
        }

        //Shoes
        if (!empty($filter_data['shoes_min']) && !empty($filter_data['shoes_max'])) {
            $query->where('shoes', '>=', $filter_data['shoes_min']);
            $query->where('shoes', '<=', $filter_data['shoes_max']);
            $data['filtered']++;
        }

        //Cloth
        if (!empty($filter_data['cloth_min']) && !empty($filter_data['cloth_max'])) {
            $query->where('cloth', '>=', $filter_data['cloth_min']);
            $query->where('cloth', '<=', $filter_data['cloth_max']);
            $data['filtered']++;
        }

        //Nationality
        if (!empty($filter_data['nationality'])) {
            $query->whereIn('nationality', $filter_data['nationality']);
            $data['filtered']++;
        }

        //Body type
        if (!empty($filter_data['body_type'])) {
            $query->whereIn('body_type', $filter_data['body_type']);
            $data['filtered']++;
        }

        //Hair color
        if (!empty($filter_data['hair_color'])) {
            $query->whereIn('hair_color', $filter_data['hair_color']);
            $data['filtered']++;
        }

        //Hairy
        if (!empty($filter_data['hairy'])) {
            $query->whereIn('hairy', $filter_data['hairy']);
            $data['filtered']++;
        }

        //Body art
        if (!empty($filter_data['body_art'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['body_art'] as $body_art) {
                    $q->orWhereJsonContains('body_art', (string)$body_art);
                }
            });
            $data['filtered']++;
        }

        //Prices
        if (!empty($filter_data['price_day_in_one_min']) && !empty($filter_data['price_day_in_one_max'])) {
            $query->where('price_day_in_one', '>=', $filter_data['price_day_in_one_min']);
            $query->where('price_day_in_one', '<=', $filter_data['price_day_in_one_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_day_in_two_min']) && !empty($filter_data['price_day_in_two_max'])) {
            $query->where('price_day_in_two', '>=', $filter_data['price_day_in_two_min']);
            $query->where('price_day_in_two', '<=', $filter_data['price_day_in_two_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_day_out_one_min']) && !empty($filter_data['price_day_out_one_max'])) {
            $query->where('price_day_out_one', '>=', $filter_data['price_day_out_one_min']);
            $query->where('price_day_out_one', '<=', $filter_data['price_day_out_one_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_day_out_two_min']) && !empty($filter_data['price_day_out_two_max'])) {
            $query->where('price_day_out_two', '>=', $filter_data['price_day_out_two_min']);
            $query->where('price_day_out_two', '<=', $filter_data['price_day_out_two_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_night_in_one_min']) && !empty($filter_data['price_night_in_one_max'])) {
            $query->where('price_night_in_one', '>=', $filter_data['price_night_in_one_min']);
            $query->where('price_night_in_one', '<=', $filter_data['price_night_in_one_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_night_in_night_min']) && !empty($filter_data['price_night_in_night_max'])) {
            $query->where('price_night_in_night', '>=', $filter_data['price_night_in_night_min']);
            $query->where('price_night_in_night', '<=', $filter_data['price_night_in_night_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_night_out_one_min']) && !empty($filter_data['price_night_out_one_max'])) {
            $query->where('price_night_out_one', '>=', $filter_data['price_night_out_one_min']);
            $query->where('price_night_out_one', '<=', $filter_data['price_night_out_one_max']);
            $data['filtered']++;
        }

        if (!empty($filter_data['price_night_out_night_min']) && !empty($filter_data['price_night_out_night_max'])) {
            $query->where('price_night_out_night', '>=', $filter_data['price_night_out_night_min']);
            $query->where('price_night_out_night', '<=', $filter_data['price_night_out_night_max']);
            $data['filtered']++;
        }

        //Services
        if (!empty($filter_data['services'])) {
            $query->where(function ($q) use ($filter_data) {
                foreach ($filter_data['services'] as $service_id) {
                    $q->orWhere(function ($q) use ($service_id) {
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)");
                    });
                }
            });
            $data['filtered']++;
        }

        //Name or Desc
        if (!empty($filter_data['name_or_desc'])) {
            $name_or_desc = $filter_data['name_or_desc'];
            $query->where(function ($q) use ($name_or_desc) {
                $q->orWhere('name', 'like', '%' . $name_or_desc . '%')->orWhere('description', 'like', '%' . $name_or_desc . '%');
            });
            $data['filtered']++;
        }

        //If not a filtered data
        if (!$data['filtered']) {
            $query->where('publish', 15);
        }

        $filter_data['post_count'] = $query->count();

        $data['data'] = $query->paginate($this->config['count_per_page'])->appends([
            'section' => $filter_data['section'],
            'zone' => $filter_data['zone'],
            'metro' => $filter_data['metro'],
            'tags' => $filter_data['tags'],
            'language_skills' => $filter_data['language_skills'],
            'visit_places' => $filter_data['visit_places'],
            'express' => $filter_data['express'],
            'health' => $filter_data['health'],
            'apartment' => $filter_data['apartment'],
            'arrival' => $filter_data['arrival'],
            'age_min' => $filter_data['age_min'],
            'age_max' => $filter_data['age_max'],
            'height_min' => $filter_data['height_min'],
            'height_max' => $filter_data['height_max'],
            'breast_min' => $filter_data['breast_min'],
            'breast_max' => $filter_data['breast_max'],
            'shoes_min' => $filter_data['shoes_min'],
            'shoes_max' => $filter_data['shoes_max'],
            'cloth_min' => $filter_data['cloth_min'],
            'cloth_max' => $filter_data['cloth_max'],
            'nationality' => $filter_data['nationality'],
            'body_type' => $filter_data['body_type'],
            'hair_color' => $filter_data['hair_color'],
            'hairy' => $filter_data['hairy'],
            'body_art' => $filter_data['body_art'],
            'price_day_in_one_min' => $filter_data['price_day_in_one_min'],
            'price_day_in_one_max' => $filter_data['price_day_in_one_max'],
            'price_day_in_two_min' => $filter_data['price_day_in_two_min'],
            'price_day_in_two_max' => $filter_data['price_day_in_two_max'],
            'price_day_out_one_min' => $filter_data['price_day_out_one_min'],
            'price_day_out_one_max' => $filter_data['price_day_out_one_max'],
            'price_day_out_two_min' => $filter_data['price_day_out_two_min'],
            'price_day_out_two_max' => $filter_data['price_day_out_two_max'],
            'price_night_in_one_min' => $filter_data['price_night_in_one_min'],
            'price_night_in_one_max' => $filter_data['price_night_in_one_max'],
            'price_night_in_night_min' => $filter_data['price_night_in_night_min'],
            'price_night_in_night_max' => $filter_data['price_night_in_night_max'],
            'price_night_out_one_min' => $filter_data['price_night_out_one_min'],
            'price_night_out_one_max' => $filter_data['price_night_out_one_max'],
            'price_night_out_night_min' => $filter_data['price_night_out_night_min'],
            'price_night_out_night_max' => $filter_data['price_night_out_night_max'],
            'services' => $filter_data['services'],
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $item) {

            $image = $this->getters->getPostMainImage($item['id']);
            $image_height = app('image_settings')['posts'][$this->config['template']];
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

            $data['items'][] = view('catalog/posts/include/'.$this->config['template'], ['data' => $post_data]);
        }

        return $data;
    }

    public function getMainImage($post_id) {
        $image = Cache::remember('getMainImage_' . $post_id, 60, function () use ($post_id) {
            return PostContent::where('post_id', $post_id)->where('type', 'main')->first();
        });
        return $image->file ?? null;
    }
}
