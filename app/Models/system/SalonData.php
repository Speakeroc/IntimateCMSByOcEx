<?php

namespace App\Models\system;

use App\Models\location\City;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SalonData extends Model
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    private array $default_data;
    private array $data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->default_data = ['status' => 0, 'count' => 0, 'template' => 'big_salon'];
        $this->data = $this->getters->getSetting('home_salon') ?? $this->default_data;
    }

    public function getSalon(): array
    {
        if (!$this->data['status']) {
            return [];
        }
        $template = $this->data['template'];
        $items = Salon::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->limit($this->data['count'])->get();
        return $this->getArrayData($template, $items);
    }

    public function getArrayData($template, $data): array
    {
        $response = [];

        foreach ($data as $item) {
            $image = $this->getters->getSalonMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, width: 500));
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
                'desc' => Str::limit($item['desc'], 150, '...'),
                'messengers' => json_decode($item['messengers'], true),
                'date_added' => $this->getters->dateText($item['created_at']),
            ];

            $response[] = view('catalog/salon/include/'.$template, ['data' => $salon_data]);
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
