<?php

namespace App\Http\Controllers\catalog\salon;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Salon;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class salonController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->salon = new Salon;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index($salon_id, $title)
    {
        $salon = Salon::where('id', $salon_id)->first();
        $current_title = Str::slug($salon['title']);

        //Redirect checked
        if (!empty($current_title) && $title != $current_title) return redirect()->route('client.salon', ['salon_id' => $salon_id, 'title' => $current_title]);
        if (empty($current_title) || $salon['publish'] != 1) return redirect()->route('client.errors', ['code' => 404]);

        //Create View
        $this->salon->viewsSalon($salon_id);

        $data['salon_id'] = $salon_id;
        $data['phone'] = $phone = $salon['phone'];
        $data['phone_format'] = preg_replace('/[^\d+]/', '', $salon['phone']);
        $data['city'] = $city = City::where('id', $salon['city_id'])->value('title');
        $city = (!empty($city)) ? __('catalog/salon/salon.city', ['city' => $city]) : '';
        $zone_id = $salon['zone_id'];
        $metro_id = $salon['metro_id'];
        $data['zone'] = Zone::where('id', $zone_id)->value('title') ?? '---';
        $data['metro'] = Metro::where('id', $metro_id)->value('title') ?? '---';
        $zone = ($zone_id) ? Zone::where('id', $zone_id)->value('title') : null;
        $zone = ($zone) ? __('catalog/salon/salon.zone', ['zone' => $zone]) : null;
        $metro = ($metro_id) ? Metro::where('id', $metro_id)->value('title') : null;
        $metro = ($metro) ? __('catalog/salon/salon.metro', ['metro' => $metro]) : null;
        $salon_name = $salon['title'];

        $site_name = $this->getters->getSetting('micro_site_name') ?? 'IntimateCMS';
        $data['title'] = __('catalog/salon/salon.salon_title', ['title' => $salon_name, 'id' => $salon['id']]);
        $title_meta = __('catalog/salon/salon.salon_item', ['sitename' => $site_name, 'title' => $salon_name, 'phone' => $phone, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $salon_id]);
        $description = __('catalog/salon/salon.salon_desc', ['sitename' => $site_name, 'title' => $salon_name, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $salon_id]);
        $this->getters->setMetaInfo(title: $title_meta, description: $description, url: route('client.salon', ['salon_id' => $salon_id, 'title' => $current_title]));

        //Content
        $image = $this->getters->getSalonMainImage($salon['id']);
        if (!empty($image) && File::exists(public_path($image))) {
            $data['main_image'] = url($this->imageConverter->toMini($image, height: 500, watermark: true));
        } else {
            $data['main_image'] = url('no_image.png');
        }
        if (!empty($image) && File::exists(public_path($image))) {
            $data['big_main_image'] = url($this->imageConverter->toMini($image, watermark: true));
        } else {
            $data['big_main_image'] = url('no_image.png');
        }

        $content_types = $this->getters->getPluckSalon_P__count($salon_id);
        $data['photo_count'] = in_array('photo', $content_types);
        $data['content_data'] = $this->getters->getAllSalonContentData($salon_id);

        //Call Time
        $call_time_type = $salon['call_time_type'];
        $call_time = json_decode($salon['call_time'], true);
        if ($call_time_type == null || $call_time_type == 1) {
            $data['call_time'] = __('lang.call_time_hours_day');
        } elseif (isset($call_time['time_from']) && isset($call_time['time_to'])) {
            $data['call_time'] = __('lang.time_from_to', ['f' => $call_time['time_from'], 't' => $call_time['time_to']]);
        } else {
            $data['call_time'] = '---';
        }

        //Prices
        $data['price_day_in_one'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_day_in_one'])]);
        $data['price_day_in_two'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_day_in_two'])]);
        $data['price_day_out_one'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_day_out_one'])]);
        $data['price_day_out_two'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_day_out_two'])]);
        $data['price_night_in_one'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_night_in_one'])]);
        $data['price_night_in_night'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_night_in_night'])]);
        $data['price_night_out_one'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_night_out_one'])]);
        $data['price_night_out_night'] = __('lang.from_sum', ['sum' => $this->getters->currencyFormat($salon['price_night_out_night'])]);
        $data['express'] = $salon['express'];
        $data['express_price'] = $this->getters->currencyFormat($salon['express_price']);
        $data['currency_symbol'] = $this->getters->getCurrencySymbol();

        //Description
        $data['desc'] = nl2br(e($salon['desc']));

        //Location
        $latitude = $salon['latitude'];
        $longitude = $salon['longitude'];
        $data['location'] = false;
        $data['latitude'] = null;
        $data['longitude'] = null;
        if ($latitude && $longitude) {
            $data['location'] = true;
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
        }

        //Messengers
        $data['messengers'] = json_decode($salon['messengers'], true);
        $data['telegram'] = null;
        $data['whatsapp'] = null;
        $data['instagram'] = null;

        if (isset($data['messengers']['telegram']['status']) && $data['messengers']['telegram']['status']) {
            $data['telegram'] = (isset($data['messengers']['telegram']['type']) && $data['messengers']['telegram']['type'] == 'login') ? 'https://t.me/' . $data['messengers']['telegram']['content'] : $data['messengers']['telegram']['content'];
        }

        if (isset($data['messengers']['whatsapp']['status']) && $data['messengers']['whatsapp']['status'] && !empty($data['messengers']['whatsapp']['content'])) {
            $data['whatsapp'] = 'https://wa.me/' . preg_replace('/[^0-9+]/', '', $data['messengers']['whatsapp']['content']);
        }

        if (isset($data['messengers']['instagram']['status']) && $data['messengers']['instagram']['status'] && !empty($data['messengers']['instagram']['content'])) {
            $data['instagram'] = str_contains($data['messengers']['instagram']['content'], 'http') ? $data['messengers']['instagram']['content'] : 'https://www.instagram.com/' . $data['messengers']['instagram']['content'];
        }

        //Microdata Article
        $data['microdata_article'] = $this->getters->microDataSalonArticle($salon_id, $salon_name, $phone, $zone, $metro, $city, $salon['created_at'], $salon['updated_at']);

        $breadcrumbs = [
            ['link' => route('client.salon.index'), 'title' => __('catalog/page_titles.post_salon')],
            ['link' => route('client.salon', ['salon_id' => $salon_id, 'title' => $current_title]), 'title' => $salon_name]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/salon/salon', ['data' => $data]);
    }
}
