<?php

namespace App\Http\Controllers\catalog\common;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class headerController extends Controller
{
    protected Getters $getters;
    protected Users $user;
    protected ?string $logo;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->user = new Users;
        $this->logo = $this->getters->getSetting('image_logo') ?? null;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $this->getters->postStatusChecker();
        $this->getters->salonStatusChecker();

        $data['logo'] = (!empty($this->logo)) ? url($this->logo) : url('logo.svg');

        $data['menu'] = [
            ['link' => route('client.post.search'), 'title' => __('catalog/page_titles.post_search'), 'status' => true],
            ['link' => route('client.post.all'), 'title' => __('catalog/page_titles.post_all'), 'status' => true],
            ['link' => route('client.post.individual'), 'title' => __('catalog/page_titles.post_individual'), 'status' => $this->getters->getSetting('post_section_individuals_status') ?? null],
            ['link' => route('client.post.premium'), 'title' => __('catalog/page_titles.post_premium'), 'status' => $this->getters->getSetting('post_section_premium_status') ?? null],
            ['link' => route('client.post.health'), 'title' => __('catalog/page_titles.post_health'), 'status' => $this->getters->getSetting('post_section_health_status') ?? null],
            ['link' => route('client.post.elite'), 'title' => __('catalog/page_titles.post_elite'), 'status' => $this->getters->getSetting('post_section_elite_status') ?? null],
            ['link' => route('client.post.bdsm'), 'title' => __('catalog/page_titles.post_bdsm'), 'status' => $this->getters->getSetting('post_section_bdsm_status') ?? null],
            ['link' => route('client.post.masseuse'), 'title' => __('catalog/page_titles.post_masseuse'), 'status' => $this->getters->getSetting('post_section_masseuse_status') ?? null],
            ['link' => route('client.salon.index'), 'title' => __('catalog/page_titles.post_salon'), 'status' => true],
        ];

        $data['menu_m'] = [
            ['link' => route('client.city.list'), 'title' => __('catalog/common/header.city')],
            ['link' => route('client.post.map'), 'title' => __('catalog/page_titles.post_map')],
            ['link' => route('client.news.all'), 'title' => __('catalog/page_titles.post_news')],
            ['link' => route('client.services.list'), 'title' => __('catalog/page_titles.post_services_list')],
            ['link' => route('client.tags.list'), 'title' => __('catalog/page_titles.post_tags_list')],
            ['link' => route('client.city.list'), 'title' => __('catalog/page_titles.post_city_list')],
            ['link' => route('client.priceServices'), 'title' => __('catalog/page_titles.price_services_t')],
        ];

        //Services
        $services = app('post_services');
        $data['services'] = [];
        foreach ($services as $service) {
            $service_item = [];
            $service_item['title'] = $service['title'];
            foreach ($service['data'] as $item) {
                $service_item['data'][] = [
                    'title' => $item['title'],
                    'link' => route('client.services.item', ['service_name' => Str::slug($item['title'])]),
                ];
            }
            $data['services'][] = $service_item;
        }

        $data['microdata'] = $this->microData();

        if (Auth::check()) {
            $data['user'] = $this->user->userData();
        } else {
            $data['user'] = null;
        }

        $data['header_display_zone'] = $this->getters->getSetting('header_display_zone');
        $data['header_display_city'] = $this->getters->getSetting('header_display_city');
        $data['header_display_map'] = $this->getters->getSetting('header_display_map');

        $data['new_year_mode'] = $this->getters->getSetting('new_year_mode') ?? null;

        return view('catalog/common/header', ['data' => $data]);
    }

    public function microData(): bool|string
    {
        $site_name = $this->getters->getSetting('micro_site_name') ?? 'IntimateCMS';
        $microdata = [
            "@context" => "https://schema.org/",
            "@type" => "Organization",
            "name" => __('microdata.name', ['sitename' => $site_name]),
            "alternateName" => __('microdata.altername', ['sitename' => $site_name]),
            "url" => url('/'),
            "logo" => url('/logo.png')
        ];
        return json_encode($microdata, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
