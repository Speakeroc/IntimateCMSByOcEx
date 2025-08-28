<?php

namespace App\Http\Controllers\catalog\salon;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class salonListController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = $this->getters->getSetting('page_salon') ?? ['count_per_page' => 20, 'watermark' => 0, 'template' => 'big_salon'];
    }

    public function index()
    {
        $data['h1'] = __('catalog/page_titles.post_salon_h1');
        $data['title'] = __('catalog/page_titles.post_salon_t');
        $data['description'] = __('catalog/page_titles.post_salon_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.salon.index'));

        $template = $this->config['template'];
        $data['data'] = $items = Salon::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->paginate($this->config['count_per_page']);

        $data['items'] = [];

        foreach ($items as $item) {
            $image = $this->getters->getSalonMainImage($item['id']);
            $image_height = app('image_settings')['salon']['main'];
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, width: $image_height));
            } else {
                $image = url('no_image_round.png');
            }

            //City
            $city_id = $item['city_id'];
            $city_title = Cache::remember('city_title_' . $city_id, 60, function () use ($city_id) {
                return City::where('id', $city_id)->value('title');
            });

            $salon_data = [
                'id' => $item['id'],
                'image' => $image,
                'title' => $item['title'],
                'city' => $city_title,
                'city_link' => route('client.city.item', ['city_id' => $city_id, 'title' => Str::slug($city_title)]),
                'price_day_in_one' => $this->getters->currencyFormat($item['price_day_in_one']),
                'price_day_in_two' => $this->getters->currencyFormat($item['price_day_in_two']),
                'price_day_out_one' => $this->getters->currencyFormat($item['price_day_out_one']),
                'price_day_out_two' => $this->getters->currencyFormat($item['price_day_out_two']),
                'price_night_in_one' => $this->getters->currencyFormat($item['price_night_in_one']),
                'price_night_in_night' => $this->getters->currencyFormat($item['price_night_in_night']),
                'price_night_out_one' => $this->getters->currencyFormat($item['price_night_out_one']),
                'price_night_out_night' => $this->getters->currencyFormat($item['price_night_out_night']),
                'address' => $item['address'],
                'link' => route('client.salon', ['salon_id' => $item['id'], 'title' => Str::slug($item['title'])]),
                'desc' => Str::limit($item['desc'], 150),
                'messengers' => json_decode($item['messengers'], true),
                'date_added' => $this->getters->dateText($item['created_at']),
            ];

            $data['items'][] = view('catalog/salon/include/'.$template, ['data' => $salon_data]);
        }

        $breadcrumbs = [
            ['link' => route('client.salon.index'), 'title' => __('catalog/page_titles.post_salon')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/salon/page', ['data' => $data]);
    }

    public function getMainImage($salon_id) {
        $image = Cache::remember('getSalonImage_' . $salon_id, 60, function () use ($salon_id) {
            return SalonContent::where('salon_id', $salon_id)->where('type', 'main')->first();
        });
        return $image->file ?? null;
    }
}
