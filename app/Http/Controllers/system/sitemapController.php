<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Zone;
use App\Models\posts\Post;
use App\Models\posts\Tags;
use App\Models\system\Getters;
use App\Models\system\SitemapData;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class sitemapController extends Controller
{
    private Getters $getters;
    private SitemapData $sitemapData;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->sitemapData = new SitemapData;
    }

    public function index(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $post_count = Post::where('moderation_id', 1)->where('publish', 1)->orderBy('up_date')->count();
        $post_pages = collect(range(1, ceil($post_count / 30)))->map(fn($page) => str_replace($app_url, $sitemap_url, route('client.sitemap.posts', ['page_id' => $page])))->toArray();

        $city_with_post = Post::where('moderation_id', 1)->where('publish', 1)->whereIn('city_id', City::pluck('id'))->exists();
        $zone_with_post = Post::where('moderation_id', 1)->where('publish', 1)->whereIn('zone_id', Zone::pluck('id'))->exists();

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        $sitemap .= '    <sitemap>'.PHP_EOL;
        $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.sitemap.pages')).'</loc>'.PHP_EOL;
        $sitemap .= '    </sitemap>'.PHP_EOL;
        if ($post_count) {
            foreach ($post_pages as $post_page) {
                $sitemap .= '    <sitemap>'.PHP_EOL;
                $sitemap .= '        <loc>'.$post_page.'</loc>'.PHP_EOL;
                $sitemap .= '    </sitemap>'.PHP_EOL;
            }
        }
        if ($city_with_post) {
            $sitemap .= '    <sitemap>'.PHP_EOL;
            $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.sitemap.city')).'</loc>'.PHP_EOL;
            $sitemap .= '    </sitemap>'.PHP_EOL;
        }
        if ($zone_with_post) {
            $sitemap .= '    <sitemap>'.PHP_EOL;
            $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.sitemap.zone')).'</loc>'.PHP_EOL;
            $sitemap .= '    </sitemap>'.PHP_EOL;
        }
        $sitemap .= '    <sitemap>'.PHP_EOL;
        $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.sitemap.services')).'</loc>'.PHP_EOL;
        $sitemap .= '    </sitemap>'.PHP_EOL;
        $sitemap .= '    <sitemap>'.PHP_EOL;
        $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.sitemap.tags')).'</loc>'.PHP_EOL;
        $sitemap .= '    </sitemap>'.PHP_EOL;
        $sitemap .= '</sitemapindex>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function pages(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        $home_popular = $this->getters->getSetting('home_popular');
        $home_latest = $this->getters->getSetting('home_latest');
        $pages = [
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.index')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.search')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.all')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'all'),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.popular')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'popular'),
                'status' => $home_popular['status'] ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.latest')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'latest'),
                'status' => $home_latest['status'] ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.elite')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'elite'),
                'status' => $this->getters->getSetting('post_section_elite_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.individual')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'individual'),
                'status' => $this->getters->getSetting('post_section_individuals_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.premium')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'premium'),
                'status' => $this->getters->getSetting('post_section_premium_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.health')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'health'),
                'status' => $this->getters->getSetting('post_section_health_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.masseuse')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'masseuse'),
                'status' => $this->getters->getSetting('post_section_masseuse_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.bdsm')),
                'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'bdsm'),
                'status' => $this->getters->getSetting('post_section_bdsm_status') ?? null],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.salon.index')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.news.all')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.services.list')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.tags.list')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.city.list')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.priceServices')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.post.map')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.information.all')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.contact')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
            [
                'link' => str_replace($app_url, $sitemap_url, route('client.zone.list')),
                'lastmodify' => Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0)->toAtomString(),
                'status' => true],
        ];

        foreach ($pages as $page) {
            if ($page['status']) {
                $sitemap .= '    <url>'.PHP_EOL;
                $sitemap .= '        <loc>'.$page['link'].'</loc>'.PHP_EOL;
                $sitemap .= '        <lastmod>'.$page['lastmodify'].'</lastmod>'.PHP_EOL;
                $sitemap .= '    </url>'.PHP_EOL;
            }
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function posts($page_id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $perPage = 30;
        $currentPage = $page_id ?? 1;

        // Получаем данные для текущей страницы
        $posts = Post::where('moderation_id', 1)->where('publish', 1)->orderBy('up_date')->forPage($currentPage, $perPage)->get();

        // Генерируем карту сайта
        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        foreach ($posts as $post) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.str_replace($app_url, $sitemap_url, route('client.post', ['post_id' => $post['id'], 'name' => Str::slug($post['name'])])).'</loc>'.PHP_EOL;
            $sitemap .= '        <lastmod>'.Carbon::parse($post['updated_at'])->toAtomString().'</lastmod>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function services(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        $services = app('post_services');

        $services_all = [];

        foreach ($services as $service) {
            foreach ($service['data'] as $item) {
                //$post_count = $this->getters->getPostCountByService($item['id']);
                //if ($post_count) {
                    $service_item = [
                        'link' => str_replace($app_url, $sitemap_url, route('client.services.item', ['service_name' => Str::slug($item['title'])])),
                        'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'service', service_id: $item['id']),
                    ];
               // }
                if (isset($service_item) && !empty($service_item)) {
                    $services_all[] = $service_item;
                }
            }
        }

        foreach ($services_all as $item) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.$item['link'].'</loc>'.PHP_EOL;
            $sitemap .= '        <lastmod>'.$item['lastmodify'].'</lastmod>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function tags(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        $tags = Cache::remember('sitemapController_tags', 120, function () {
            return Tags::get() ?? [];
        });

        $tags_with_posts = [];

        foreach ($tags as $tag) {
            //$post_count = $this->getters->getPostCountByTag(tag_id: $tag['id']);
            //if ($post_count) {
                $tag_item = [
                    'link' => str_replace($app_url, $sitemap_url, route('client.tags.item', ['tag_name' => Str::slug($tag['tag'])])),
                    'lastmodify' => $this->sitemapData->getPagesLastModify(type: 'tag', tag_id: $tag['id']),
                ];
                $tags_with_posts[] = $tag_item;
            //}
        }

        foreach ($tags_with_posts as $tag) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.$tag['link'].'</loc>'.PHP_EOL;
            $sitemap .= '        <lastmod>'.$tag['lastmodify'].'</lastmod>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function city(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        $city_all = City::get();

        $city_items = [];

        foreach ($city_all as $city) {
            //$post_count = $this->getters->getPostCountByCity($city['id']);
            //if ($post_count) {
                $city_items[] = [
                    'link' => str_replace($app_url, $sitemap_url, route('client.city.item', ['city_id' => $city['id'], 'title' => Str::slug($city['title'])]))
                ];
            //}
        }

        foreach ($city_items as $item) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.$item['link'].'</loc>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }

    public function zone(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $app_url = URL::to('/');
        $sitemap_url = $this->getters->getSetting('sitemap_url') ?? $app_url;

        $sitemap = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        $zone_all = Zone::get();

        $zone_items = [];

        foreach ($zone_all as $zone) {
            //$post_count = $this->getters->getPostCountByZone($zone['id']);
            //if ($post_count) {
                $zone_items[] = [
                    'link' => str_replace($app_url, $sitemap_url, route('client.zone.item', ['zone_id' => $zone['id'], 'title' => Str::slug($zone['title'])]))
                ];
            //}
        }

        foreach ($zone_items as $item) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.$item['link'].'</loc>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
}
