<?php

namespace App\Http\Controllers\catalog\info;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class priceServicesController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $data['h1'] = __('catalog/page_titles.price_services_h1');
        $data['title'] = $title = __('catalog/page_titles.price_services_t');
        $description = __('catalog/page_titles.price_services_d');
        $this->getters->setMetaInfo(title: $title, description: $description, url: route('client.priceServices'));

        $data['post_title'] = __('catalog/info/priceServices.post_title');
        $data['post_prices'] = $this->getters->getSetting('post_prices');

        $data['post_activation_status'] = $this->getters->getSetting('post_activation_status');
        $data['post_publish_variable'] = $this->getters->getSetting('post_publish_variable');

        $data['post_variable'] = [];

        foreach ($data['post_publish_variable'] as $post_var) {
            $data['post_variable'][] = [
                'title' => __('catalog/info/priceServices.price_days_var', ['price' => $this->getters->currencyFormat($post_var['price']), 'day' => trans_choice('choice.days', $post_var['days'], ['d' => $post_var['days']])]),
            ];
        }

        //Diamond
        if ($data['post_prices']['diamond_act'] == $data['post_prices']['diamond_ext']) {
            $data['diamond_act'] = $data['post_prices']['diamond_act'] ?? 0;
            $data['diamond_act'] = __('catalog/info/priceServices.price_act_ext', ['price' => $this->getters->currencyFormat($data['diamond_act'])]);
            $data['diamond_ext'] = null;
        } else {
            $data['diamond_act'] = $data['post_prices']['diamond_act'] ?? 0;
            $data['diamond_act'] = __('catalog/info/priceServices.price_act', ['price' => $this->getters->currencyFormat($data['diamond_act'])]);
            $data['diamond_ext'] = $data['post_prices']['diamond_ext'] ?? 0;
            $data['diamond_ext'] = __('catalog/info/priceServices.price_ext', ['price' => $this->getters->currencyFormat($data['diamond_ext'])]);
        }

        //VIP
        if ($data['post_prices']['vip_act'] == $data['post_prices']['vip_ext']) {
            $data['vip_act'] = $data['post_prices']['vip_act'] ?? 0;
            $data['vip_act'] = __('catalog/info/priceServices.price_act_ext', ['price' => $this->getters->currencyFormat($data['vip_act'])]);
            $data['vip_ext'] = null;
        } else {
            $data['vip_act'] = $data['post_prices']['vip_act'] ?? 0;
            $data['vip_act'] = __('catalog/info/priceServices.price_act', ['price' => $this->getters->currencyFormat($data['vip_act'])]);
            $data['vip_ext'] = $data['post_prices']['vip_ext'] ?? 0;
            $data['vip_ext'] = __('catalog/info/priceServices.price_ext', ['price' => $this->getters->currencyFormat($data['vip_ext'])]);
        }

        //Color
        if ($data['post_prices']['color_act'] == $data['post_prices']['color_ext']) {
            $data['color_act'] = $data['post_prices']['color_act'] ?? 0;
            $data['color_act'] = __('catalog/info/priceServices.price_act_ext', ['price' => $this->getters->currencyFormat($data['color_act'])]);
            $data['color_ext'] = null;
        } else {
            $data['color_act'] = $data['post_prices']['color_act'] ?? 0;
            $data['color_act'] = __('catalog/info/priceServices.price_act', ['price' => $this->getters->currencyFormat($data['color_act'])]);
            $data['color_ext'] = $data['post_prices']['color_ext'] ?? 0;
            $data['color_ext'] = __('catalog/info/priceServices.price_ext', ['price' => $this->getters->currencyFormat($data['color_ext'])]);
        }

        //To TOP
        $data['up_to_top'] = $data['post_prices']['up_to_top'] ?? 0;
        $data['up_to_top'] = __('catalog/info/priceServices.price_up_to_top', ['price' => $this->getters->currencyFormat($data['up_to_top'])]);


        $data['salon_title'] = __('catalog/info/priceServices.salon_title');
        $data['salon_prices'] = $this->getters->getSetting('salon_prices');

        $data['salon_activation_status'] = $this->getters->getSetting('salon_activation_status');
        $data['salon_publish_variable'] = $this->getters->getSetting('salon_publish_variable');

        $data['salon_variable'] = [];

        foreach ($data['salon_publish_variable'] as $salon_var) {
            $data['salon_variable'][] = [
                'title' => __('catalog/info/priceServices.price_days_var', ['price' => $this->getters->currencyFormat($salon_var['price']), 'day' => trans_choice('choice.days', $salon_var['days'], ['d' => $salon_var['days']])]),
            ];
        }

        //To TOP
        $data['salon_up_to_top'] = $data['salon_prices']['up_to_top'] ?? 0;
        $data['salon_up_to_top'] = __('catalog/info/priceServices.price_up_to_top', ['price' => $this->getters->currencyFormat($data['salon_up_to_top'])]);

        $breadcrumbs = [
            ['link' => route('client.priceServices'), 'title' => __('catalog/page_titles.price_services_t')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/priceServices', ['data' => $data]);
    }
}
