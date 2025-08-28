<?php

namespace App\Http\Controllers\catalog;

use App\Http\Controllers\Controller;
use App\Models\system\BannerData;
use App\Models\system\Getters;
use App\Models\system\NewsData;
use App\Models\system\PostBannerData;
use App\Models\system\PostData;
use App\Models\system\SalonData;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class mainController extends Controller
{
    private Getters $getters;
    private PostData $postData;
    private SalonData $salonData;
    private PostBannerData $post_bannerData;
    protected ?string $logo;

    private array $default_data;
    private array $post_banner_data;
    private array $popular_data;
    private array $individual_data;
    private array $latest_data;
    private array $elite_data;
    private array $bdsm_data;
    private array $masseuse_data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->postData = new PostData;
        $this->salonData = new SalonData;
        $this->post_bannerData = new PostBannerData;
        $this->bannerData = new BannerData;
        $this->newsData = new NewsData;
        $this->default_data = ['status' => 0, 'sort_order' => 0];
        $this->news_data = $this->getters->getSetting('home_news') ?? 0;
        $this->banner_data = $this->getters->getSetting('home_banners') ?? $this->default_data;
        $this->post_banner_data = $this->getters->getSetting('home_post_banner') ?? $this->default_data;
        $this->home_all = $this->getters->getSetting('home_all') ?? $this->default_data;
        $this->popular_data = $this->getters->getSetting('home_popular') ?? $this->default_data;
        $this->individual_data = $this->getters->getSetting('home_individual') ?? $this->default_data;
        $this->premium_data = $this->getters->getSetting('home_premium') ?? $this->default_data;
        $this->health_data = $this->getters->getSetting('home_health') ?? $this->default_data;
        $this->latest_data = $this->getters->getSetting('home_latest') ?? $this->default_data;
        $this->elite_data = $this->getters->getSetting('home_elite') ?? $this->default_data;
        $this->bdsm_data = $this->getters->getSetting('home_bdsm') ?? $this->default_data;
        $this->masseuse_data = $this->getters->getSetting('home_masseuse') ?? $this->default_data;
        $this->salon_data = $this->getters->getSetting('home_salon') ?? $this->default_data;
        $this->logo = $this->getters->getSetting('image_logo') ?? null;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $data['logo'] = (!empty($this->logo)) ? url($this->logo) : url('logo.svg');
        $data['title'] = $this->getters->getSetting('meta_h1') ?? __('catalog/page_titles.index');
        $meta_title = $this->getters->getSetting('meta_title') ?? __('catalog/page_titles.index');
        $data['description'] = $this->getters->getSetting('meta_description') ?? null;
        $this->getters->setMetaInfo(title: $meta_title, description: $data['description'], url: route('client.index'));

        //Types
        $data['types'] = [
            [
                'key' => 'news', 'type' => 'news', 'data' => $this->newsData->getNews(), 'status' => (int)$this->news_data['status'],
                'link' => null, 'sort_order' => (int)$this->news_data['sort_order']
            ],
            [
                'key' => 'banner', 'type' => 'banner', 'data' => $this->bannerData->getBanner(), 'status' => (int)$this->banner_data['status'],
                'link' => null, 'sort_order' => (int)$this->banner_data['sort_order']
            ],
            [
                'key' => 'post_banner', 'type' => 'post_banner', 'data' => $this->post_bannerData->getPostBanner(), 'status' => (int)$this->post_banner_data['status'],
                'link' => null, 'sort_order' => (int)$this->post_banner_data['sort_order']
            ],
            [
                'key' => 'all', 'type' => 'post', 'data' => $this->postData->getAllPost(), 'status' => $this->home_all['status'],
                'link' => route('client.post.all'), 'sort_order' => $this->home_all['sort_order']
            ],
            [
                'key' => 'popular', 'type' => 'post', 'data' => $this->postData->getPopularPost(), 'status' => $this->popular_data['status'],
                'link' => route('client.post.popular'), 'sort_order' => $this->popular_data['sort_order']
            ],
            [
                'key' => 'individual', 'type' => 'post', 'data' => $this->postData->getIndividualPost(), 'status' => $this->individual_data['status'],
                'link' => route('client.post.individual'), 'sort_order' => $this->individual_data['sort_order']
            ],
            [
                'key' => 'premium', 'type' => 'post', 'data' => $this->postData->getPremiumPost(), 'status' => $this->premium_data['status'],
                'link' => route('client.post.premium'), 'sort_order' => $this->premium_data['sort_order']
            ],
            [
                'key' => 'health', 'type' => 'post', 'data' => $this->postData->getHealthPost(), 'status' => $this->health_data['status'],
                'link' => route('client.post.health'), 'sort_order' => $this->health_data['sort_order']
            ],
            [
                'key' => 'latest', 'type' => 'post', 'data' => $this->postData->getLatestPost(), 'status' => $this->latest_data['status'],
                'link' => route('client.post.latest'), 'sort_order' => $this->latest_data['sort_order']
            ],
            [
                'key' => 'elite', 'type' => 'post', 'data' => $this->postData->getElitePost(), 'status' => $this->elite_data['status'],
                'link' => route('client.post.elite'), 'sort_order' => $this->elite_data['sort_order']
            ],
            [
                'key' => 'bdsm', 'type' => 'post', 'data' => $this->postData->getBdsmPost(), 'status' => $this->bdsm_data['status'],
                'link' => route('client.post.bdsm'), 'sort_order' => $this->bdsm_data['sort_order']
            ],
            [
                'key' => 'masseuse', 'type' => 'post', 'data' => $this->postData->getMasseusePost(), 'status' => $this->masseuse_data['status'],
                'link' => route('client.post.masseuse'), 'sort_order' => $this->masseuse_data['sort_order']
            ],
            [
                'key' => 'salon', 'type' => 'salon', 'data' => $this->salonData->getSalon(), 'status' => $this->salon_data['status'],
                'link' => route('client.salon.index'), 'sort_order' => $this->salon_data['sort_order']
            ],
        ];

        usort($data['types'], function ($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        $footer_text = $this->getters->getSetting('footer_text') ?? null;
        $data['footer_text'] = ($footer_text) ? $this->getters->reverseTextData($footer_text) : null;

        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/main', ['data' => $data]);
    }
}
