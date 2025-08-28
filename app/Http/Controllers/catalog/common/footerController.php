<?php

namespace App\Http\Controllers\catalog\common;

use App\Http\Controllers\Controller;
use App\Models\Info\Information;
use App\Models\system\Getters;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class footerController extends Controller
{
    private Getters $getters;
    protected ?string $logo;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->logo = $this->getters->getSetting('image_logo') ?? null;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['menu'] = [
            ['link' => route('client.post.search'), 'title' => __('catalog/page_titles.post_search')],
            ['link' => route('client.post.map'), 'title' => __('catalog/page_titles.post_map')],
            ['link' => route('client.news.all'), 'title' => __('catalog/page_titles.post_news')],
            ['link' => route('client.priceServices'), 'title' => __('catalog/page_titles.price_services_t')],
            ['link' => route('client.contact'), 'title' => __('catalog/page_titles.contact_t')],
        ];

        //Information
        $information = Information::where('status', 1)->where('created_at', '<', Carbon::now())->where('in_menu', 1)->get();
        foreach ($information as $item) {
            $data['menu'][] = ['link' => route('client.information', ['info_id' => $item['id'], 'title' => Str::slug($item['title'])]), 'title' => $item['title']];
        }

        $data['support_email'] = $this->getters->getSetting('support_email') ?? 'ocexdev@gmail.com';
        $custom_js = $this->getters->getSetting('custom_js') ?? null;
        $data['custom_js'] = ($custom_js) ? $this->getters->reverseTextData($custom_js) : null;
        $data['age_detect'] = $this->getters->getSetting('age_detect');
        $data['subscribe_status'] = $this->getters->getSetting('subscribe_status');
        $data['subscribe_title'] = $this->getters->getSetting('subscribe_title') ?? __('catalog/common/footer.subscribe_title');
        $subscribe_text = $this->getters->getSetting('subscribe_text');
        $data['subscribe_text'] = ($subscribe_text) ? $this->getters->reverseTextData($subscribe_text) : __('catalog/common/footer.subscribe_text');
        $data['subscribe_btn_title'] = $this->getters->getSetting('subscribe_btn_title') ?? __('catalog/common/footer.subscribe_btn_title');
        $data['subscribe_btn_link'] = $this->getters->getSetting('subscribe_btn_link');
        $data['subscribe_btn_color'] = $this->getters->getSetting('subscribe_btn_color');
        $data['subscribe_btn_color_t'] = $this->getters->getSetting('subscribe_btn_color_t');
        $data['social_links'] = $this->getters->getSetting('social_links') ?? null;

        $data['new_year_mode'] = $this->getters->getSetting('new_year_mode') ?? null;

        $data['logo'] = (!empty($this->logo)) ? url($this->logo) : url('logo.svg');

        return view('catalog/common/footer', ['data' => $data]);
    }
}
