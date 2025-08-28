<?php

namespace App\Http\Controllers\catalog\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class mapController extends Controller
{
    private Getters $getters;
    protected ?string $logo;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->logo = $this->getters->getSetting('image_logo') ?? null;
    }

    public function index()
    {
        $data['h1'] = __('catalog/posts/map.h1');
        $data['title'] = __('catalog/posts/map.title');
        $data['description'] = __('catalog/posts/map.description');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.post.map'));

        $posts = Post::where('moderation_id', 1)->where('publish', 1)->where('latitude', '!=', null)->where('longitude', '!=', null)->get();

        $data['posts'] = [];

        foreach ($posts as $post) {
            $image = $this->getters->getPostMainImage($post['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: 275));
            } else {
                $image = url('no_image_round.png');
            }
            $data['posts'][] = [
                'id' => $post['id'],
                'link' => route('client.post', ['post_id' => $post['id'], 'name' => Str::slug($post['name'])]),
                'image' => $image,
                'name' => $post['name'],
                'latitude' => $post['latitude'],
                'longitude' => $post['longitude'],
                'phone' => $post['phone'],
                'price_hour' => $this->getters->currencyFormat($post['price_day_in_one']) ?? '---',
                'price_hours' => $this->getters->currencyFormat($post['price_day_in_two']) ?? '---',
                'date_created' => $post['created_at']
            ];
        }

        $data['latitude'] = '55.7512';
        $data['longitude'] = '37.6184';
        $city = $this->getters->getCityData();
        $default_city_id = $this->getters->getSetting('default_city_id');

        foreach ($city['city'] as $item) {
            if ($item['id'] == $default_city_id && !empty($item['latitude']) && !empty($item['longitude'])) {
                $data['latitude'] = $item['latitude'];
                $data['longitude'] = $item['longitude'];
                break;
            }
        }

        $breadcrumbs = [
            ['link' => route('client.post.map'), 'title' => __('catalog/posts/map.title')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/map', ['data' => $data]);
    }

    public function getMainImage($post_id) {
        $image = PostContent::where('post_id', $post_id)->where('type', 'main')->first();
        return $image->file ?? null;
    }
}
