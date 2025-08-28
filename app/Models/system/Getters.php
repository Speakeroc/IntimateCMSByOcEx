<?php

namespace App\Models\system;

use App\Http\Controllers\admin\common\admFooterController;
use App\Http\Controllers\admin\common\admHeaderController;
use App\Http\Controllers\admin\common\admSidebarController;
use App\Http\Controllers\catalog\common\footerController;
use App\Http\Controllers\catalog\common\headerController;
use App\Models\Info\News;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\posts\Tags;
use App\Models\Users;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Getters extends Model
{
    public function getHeaderFooter(): array
    {
        $data['header'] = (new headerController)->index();
        $data['footer'] = (new footerController)->index();
        return $data;
    }

    public function getAdminHeaderFooter(): array
    {
        $data['header'] = (new admHeaderController())->index();
        $data['footer'] = (new admFooterController())->index();
        $data['sidebar'] = (new admSidebarController())->index();
        return $data;
    }

    public function setSEOTitle(string $titleKey): void
    {
        SEOMeta::setTitle(__('admin/page_titles.' . $titleKey));
    }

    public function randomString($length = 10, $only_num = false): string
    {
        if ($only_num) {
            $characters = '0123456789';
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $randomStr = '';
        for ($i = 0; $i < $length; $i++) {
            $randomStr .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomStr;
    }

    public function getFolderFilesSize($path): string
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        if ($size >= 1 << 30) {
            $formattedSize = round($size / (1 << 30), 2) . ' ГБ';
        } elseif ($size >= 1 << 20) {
            $formattedSize = round($size / (1 << 20), 2) . ' МБ';
        } elseif ($size >= 1 << 10) {
            $formattedSize = round($size / (1 << 10), 2) . ' КБ';
        } else {
            $formattedSize = $size . ' B';
        }
        return $formattedSize;
    }

    public function clearFolder($path): JsonResponse
    {
        if (File::exists($path)) {
            File::cleanDirectory($path);
            return response()->json(['message' => __('system/getters.clear_cache_success'), 'size' => $this->getFolderFilesSize($path)]);
        }
        return response()->json(['message' => __('system/getters.clear_cache_warning'), 'size' => $this->getFolderFilesSize($path)]);
    }

    public function currencyFormat($price): ?string
    {
        if (empty($price) && $price != 0) {
            return null;
        }
        $currency_symbol_right = Cache::remember('Getters_currencyFormat__symbol_right', 120, function () {
            return Settings::where('code', 'setting')->where('key', 'currency_symbol_right')->value('value');
        });
        $currency_symbol_left = Cache::remember('Getters_currencyFormat__symbol_left', 120, function () {
            return Settings::where('code', 'setting')->where('key', 'currency_symbol_left')->value('value');
        });
        return $currency_symbol_left . $this->formatPrice($price) . $currency_symbol_right;
    }

    public function getCityInfo($city_id, $status = true): array
    {
        if ($status) {
            $status = $this->getSetting('post_block_city_status') ?? null;
            if (!$status) {
                return [];
            }
        }
        $city = City::where('id', $city_id)->first();
        return [
            'title' => $city['title'],
            'latitude' => $city['latitude'],
            'longitude' => $city['longitude'],
            'city_code' => $city['city_code'],
            'link' => route('client.city.item', ['city_id' => $city_id, 'title' => Str::slug($city['title'])]),
            'status' => $city['status'],
        ];
    }

    public function getZoneInfo($zone_id): array
    {
        $status = $this->getSetting('post_block_zone_status') ?? null;
        if (!$status) {
            return [];
        }
        $zone = Zone::where('id', $zone_id)->first();
        return [
            'title' => $zone['title'],
            'city_id' => $zone['city_id'],
            'status' => $zone['status'],
            'link' => route('client.zone.item', ['zone_id' => $zone_id, 'title' => Str::slug($zone['title'])]),
        ];
    }

    public function getCurrencySymbol(): string
    {
        $currency_symbol_right = Settings::where('code', 'setting')->where('key', 'currency_symbol_right')->value('value');
        $currency_symbol_left = Settings::where('code', 'setting')->where('key', 'currency_symbol_left')->value('value');
        return $currency_symbol_left . $currency_symbol_right;
    }

    public function getCurrencyCode(): string
    {
        return Cache::remember('Getters_getCurrencyCode', 120, function () {
            return Settings::where('code', 'setting')->where('key', 'currency_symbol_code')->value('value') ?? 'NONE';
        });
    }

    public function moveTempToFolder($image, $new_path, $target, $updater = null): ?string
    {
        if (!$updater) {
            if (!str_contains(public_path($image), '/temp/')) {
                return $image;
            }
        }

        $image_path = public_path($image);
        $directory = public_path($new_path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $files = File::files($directory);
        foreach ($files as $file) {
            if (preg_match('/\b' . preg_quote(mb_strtolower($target), '/') . '\b/', mb_strtolower($file->getFilename()))) {
                File::delete($file->getRealPath());
            }
        }

        $new_image_name = mb_strtolower($target) . '.' . File::extension($image_path);
        $new_image_path = $directory . '/' . $new_image_name;

        if (File::exists($image_path)) {
            File::move($image_path, $new_image_path);
            $normalizedPath = str_replace('\\', '/', $new_image_path);
            $relativePath = strstr($normalizedPath, 'images');
            return '/' . $relativePath;
        } else {
            return null;
        }
    }

    public function getAdminAccess($type = null, $key = null, $route = 'admin'): ?RedirectResponse
    {
        if (!Auth::check()) return redirect()->route('client.index');
        //if (Auth::id() == 1) return null;
        if ($type && $key) {
            if ($route == 'admin') {
                if (!Auth::user()->can($type, $key)) return redirect()->route('admin.no_access');
            } else {
                if (!Auth::user()->can($type, $key)) return redirect()->route('client.errors', ['code' => 404]);
            }
        }
        return null;
    }

    public function getAdminAccessMenu($type = null, $key = null): bool|RedirectResponse|null
    {
        if (!Auth::check()) return redirect()->route('client.index');
        //if (Auth::id() == 1) return true;
        if ($type && $key) {
            if (!Auth::user()->can($type, $key)) {
                return false;
            } else {
                return true;
            }
        } else {
            return null;
        }
    }

    public function convertTextData($body_content): string
    {
        if (empty($body_content)) return '';
        $body_content = trim($body_content);
        $body_content = stripslashes($body_content);
        return htmlspecialchars($body_content);
    }

    public function reverseTextData($body_content): string
    {
        if (empty($body_content)) return '';
        $body_content = htmlspecialchars_decode($body_content, ENT_QUOTES);
        return stripslashes($body_content);
    }

    public function formatPrice($number): string
    {
        $number = floatval($number);
        return number_format($number, 0, '', ' ');
    }

    public function getSetting($key = null)
    {
        if (empty($key)) return null;

        $with_cache = false;

        if ($with_cache) {
            $cache_time = 60;
        } else {
            $cache_time = 1;
        }

        $value = Cache::remember("Getters_getSetting__value_{$key}", $cache_time, function () use ($key) {
            return Settings::where(['code' => 'setting', 'key' => $key])->value('value');
        });

        $decodedValue = json_decode($value, true);

        if (!empty($decodedValue) && is_array($decodedValue)) {
            return Cache::remember("Getters_getSetting__{$key}", $cache_time, function () use ($decodedValue) {
                return $decodedValue;
            });
        }

        if (!empty($value)) {
            return Cache::remember("Getters_getSetting__{$key}", $cache_time, function () use ($value) {
                return $value;
            });
        }

        return null;
    }

    public function generateUniqueId($template = '%s_%s', $length = 4): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $partsCount = substr_count($template, '%s');
        $parts = [];
        for ($i = 0; $i < $partsCount; $i++) {
            $parts[] = substr(str_shuffle($characters), 0, $length);
        }
        return vsprintf($template, $parts);
    }

    public function getMainPostData($city_id = null): array
    {
        $data = [];
        //Города
        $data['city'] = City::where('status', 1)->select('id', 'title', 'latitude', 'longitude', 'city_code')->orderBy('id')->get()->toArray();
        $first_city = City::where('status', 1)->select('id')->orderBy('id')->first();
        if ($city_id) {
            $first_city = ['id' => $city_id];
        }
        //Район
        $data['zone'] = $first_city ? Zone::where('status', 1)->where('city_id', $first_city['id'])->select('id', 'title')->orderBy('title')->get()->toArray() : [];
        //Метро
        $data['metro'] = $first_city ? Metro::where('status', 1)->where('city_id', $first_city['id'])->select('id', 'title')->orderBy('title')->get()->toArray() : [];
        //Пользователи
        $data['users'] = Users::where('email_activate', 1)->select('id', 'login', 'email')->orderBy('id')->get()->toArray();
        //Национальность
        $data['nationality'] = app('post_nationality');
        //Телосложение
        $data['body_type'] = app('post_body_type');
        //Цвет волос
        $data['hair_color'] = app('post_hair_color');
        //Интимная стрижка
        $data['hairy'] = app('post_hairy');
        //Аксессуары
        $data['body_art'] = app('post_body_art');
        //Выезд
        $data['visit_places'] = app('post_visit_places');
        //Услуги для
        $data['services_for'] = app('post_services_for');
        //Знание языков
        $data['language_skills'] = app('post_language_skills');
        //Услуги
        $data['services'] = app('post_services');
        //Moderation
        $data['moderation'] = app('moderation_status');
        //Теги
        $data['tags'] = Tags::select('id', 'tag')->orderBy('tag')->get()->toArray();

        //Text Photo format size descriptions
        $post_main_photo_setting = $this->getSetting('post_main_photo');
        $post_main_photo_format = $post_main_photo_setting['access_format'];
        $formats_array = explode(', ', $post_main_photo_format);
        $post_main_photo_format = '.' . implode(',.', $formats_array);
        $data['file_format_main_photo'] = $post_main_photo_format;

        $post_photo_setting = $this->getSetting('post_photo');
        $post_photo_width = $post_photo_setting['max_width'];
        $post_photo_height = $post_photo_setting['max_height'];
        $post_photo_format = $post_photo_setting['access_format'];
        $post_photo_size = $post_photo_setting['size'];
        $post_photo_count = $post_photo_setting['count'];
        $data['file_format_photo'] = $post_photo_format;
        $data['file_count_photo'] = $post_photo_count;
        $data['photo_text'] = __('admin/posts/post.support_text_photo', ['width' => $post_photo_width, 'height' => $post_photo_height, 'format' => strtoupper($post_photo_format), 'size' => $post_photo_size]);

        //Text Selfie format size descriptions
        $post_selfie_setting = $this->getSetting('post_selfie');
        $post_selfie_width = $post_selfie_setting['max_width'];
        $post_selfie_height = $post_selfie_setting['max_height'];
        $post_selfie_format = $post_selfie_setting['access_format'];
        $post_selfie_size = $post_selfie_setting['size'];
        $post_selfie_count = $post_selfie_setting['count'];
        $data['file_format_selfie'] = $post_selfie_format;
        $data['file_count_selfie'] = $post_selfie_count;
        $data['selfie_status'] = $post_selfie_setting['count'];
        $data['selfie_text'] = __('admin/posts/post.support_text_selfie', ['width' => $post_selfie_width, 'height' => $post_selfie_height, 'format' => strtoupper($post_selfie_format), 'size' => $post_selfie_size]);

        //Text Video format size descriptions
        $post_video_setting = $this->getSetting('post_selfie');
        $post_video_format = $post_video_setting['access_format'];
        $post_video_format_js = str_replace('quicktime', 'mov', $post_video_format);
        $post_video_size = $post_video_setting['size'];
        $post_video_count = $post_video_setting['count'];
        $data['file_format_video'] = $post_video_format;
        $data['file_count_video'] = $post_video_count;
        $data['video_text'] = __('admin/posts/post.support_text_video', ['format' => strtoupper($post_video_format_js), 'size' => $post_video_size]);

        $post_verify_setting = $this->getSetting('post_verify');
        $post_verify_width = $post_verify_setting['max_width'];
        $post_verify_height = $post_verify_setting['max_height'];
        $post_verify_format = $post_verify_setting['access_format'];
        $post_verify_size = $post_verify_setting['size'];
        $post_verify_text = $this->getSetting('post_verify_text');
        $data['file_format_verify'] = $post_verify_format;
        $data['verify_text'] = __('admin/posts/post.support_text_verify', ['width' => $post_verify_width, 'height' => $post_verify_height, 'format' => strtoupper($post_verify_format), 'size' => $post_verify_size]);
        $data['verify_description'] = $this->reverseTextData($post_verify_text);

        return $data;
    }

    public function getMainSalonData($city_id = null): array
    {
        $data = [];
        //Города
        $data['city'] = City::where('status', 1)->select('id', 'title', 'latitude', 'longitude', 'city_code')->orderBy('id')->get()->toArray();
        $first_city = City::where('status', 1)->select('id')->orderBy('id')->first();
        if ($city_id) {
            $first_city = ['id' => $city_id];
        }
        //Район
        $data['zone'] = $first_city ? Zone::where('status', 1)->where('city_id', $first_city['id'])->select('id', 'title')->orderBy('title')->get()->toArray() : [];
        //Метро
        $data['metro'] = $first_city ? Metro::where('status', 1)->where('city_id', $first_city['id'])->select('id', 'title')->orderBy('title')->get()->toArray() : [];
        //Пользователи
        $data['users'] = Users::where('email_activate', 1)->select('id', 'login', 'email')->orderBy('id')->get()->toArray();
        //Moderation
        $data['moderation'] = app('moderation_status');

        //Text Photo format size descriptions
        $salon_main_photo_setting = $this->getSetting('salon_main_photo');
        $salon_main_photo_format = $salon_main_photo_setting['access_format'];
        $formats_array = explode(', ', $salon_main_photo_format);
        $salon_main_photo_format = '.' . implode(',.', $formats_array);
        $data['file_format_main_photo'] = $salon_main_photo_format;

        $salon_photo_setting = $this->getSetting('salon_photo');
        $salon_photo_width = $salon_photo_setting['max_width'];
        $salon_photo_height = $salon_photo_setting['max_height'];
        $salon_photo_format = $salon_photo_setting['access_format'];
        $salon_photo_size = $salon_photo_setting['size'];
        $salon_photo_count = $salon_photo_setting['count'];
        $data['file_format_photo'] = $salon_photo_format;
        $data['file_count_photo'] = $salon_photo_count;
        $data['photo_text'] = __('admin/posts/post.support_text_photo', ['width' => $salon_photo_width, 'height' => $salon_photo_height, 'format' => strtoupper($salon_photo_format), 'size' => $salon_photo_size]);

        return $data;
    }

    public function getCityData(): array
    {
        $data['city'] = City::where('status', 1)->select('id', 'title', 'latitude', 'longitude', 'city_code')->orderBy('id')->get()->toArray();
        return $data;
    }

    public function dateText($date): string
    {
        Carbon::setLocale('ru');
        $date = Carbon::parse($date);
        $now = Carbon::now();

        if ($date->isToday()) {
            return __('lang.date_today') . ' ' . $date->format('H:i');
        } elseif ($date->isYesterday()) {
            return __('lang.date_yesterday') . ' ' . $date->format('H:i');
        } elseif ($date->isSameYear($now)) {
            return $date->translatedFormat('j F') . ' в ' . $date->format('H:i');
        } else {
            return $date->translatedFormat('j F Y') . ' в ' . $date->format('H:i');
        }
    }

    public function getPostTags($post_id): array
    {
        $tags = [];
        $post_tags = Cache::remember("Getters_getPostTags__post_tags_{$post_id}", 60, function () use ($post_id) {
            return Post::where('id', $post_id)->value('tags');
        });
        $post_tags = json_decode($post_tags, true);
        if (!empty($post_tags)) {
            foreach ($post_tags as $post_tag) {
                $tag = Cache::remember("Getters_getPostTags__tag_{$post_tag}", 60, function () use ($post_tag) {
                    return Tags::where('id', $post_tag)->value('tag');
                });
                if (!empty($tag)) {
                    $tags[] = [
                        'link' => route('client.tags.item', ['tag_name' => Str::slug($tag)]),
                        'tag' => $tag
                    ];
                }
            }
        }
        return $tags;
    }

    public function setMetaInfo($title, $description = null, $image = null, $url = null)
    {
        if (empty($image)) {
            $image = $this->getSetting('image_logo') ?? null;
            if (!empty($image)) $image = url($image);
        }

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);
        JsonLd::setTitle($title);

        if (!empty($description)) {
            SEOMeta::setDescription($description);
            OpenGraph::setDescription($description);
            JsonLd::setDescription($description);
        }

        OpenGraph::addImage($image);
        JsonLd::addImage($image);

        if (!empty($url)) {
            JsonLd::setUrl($url);
            SEOMeta::setCanonical($url);
        }
    }

    public function getPluck_P_S_V_count($post_id)
    {
        return Cache::remember("Getters_getPluck_P_S_V_count__{$post_id}", 120, function () use ($post_id) {
            return PostContent::where('post_id', $post_id)->whereIn('type', ['photo', 'selfie', 'video'])->pluck('type')->all();
        });
    }

    public function getPluckSalon_P__count($post_id)
    {
        return Cache::remember("Getters_getPluckSalon_P__count__{$post_id}", 120, function () use ($post_id) {
            return SalonContent::where('salon_id', $post_id)->whereIn('type', ['photo'])->pluck('type')->all();
        });
    }

    public function breadcrumbPages($send_data): array
    {
        $data = [];
        $list = [];
        $couner = 1;
        $data['breadcrumb'][] = ['link' => route('client.index'), 'title' => __('catalog/page_titles.index'), 'pos' => $couner];
        $list[] = ["@type" => "ListItem", "position" => $couner, "name" => __('catalog/page_titles.index'), "item" => route('client.index'), 'pos' => $couner];
        foreach ($send_data as $item) {
            $couner++;
            $data['breadcrumb'][] = ['link' => $item['link'], 'title' => $item['title'], 'pos' => $couner];
            $list[] = ["@type" => "ListItem", "position" => $couner, "name" => $item['title'], "item" => $item['link']];
        }

        $data['list'] = ["@context" => "https://schema.org/", "@type" => "BreadcrumbList", "itemListElement" => $list];
        $data['list'] = json_encode($data['list'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $data;
    }

    public function getSearchData($city_id = null): array
    {
        $data = [];
        //Города
        $data['city'] = City::where('status', 1)->select('id', 'title', 'latitude', 'longitude', 'city_code')->orderBy('id')->get()->toArray();
        $city = City::where('status', 1)->select('id')->orderBy('id')->first();
        if ($city_id) {
            $city = ['id' => $city_id];
        }
        //Район
        $data['zone'] = $city ? Zone::where('status', 1)->where('city_id', $city['id'])->select('id', 'title')->orderBy('id')->get()->toArray() : [];
        //Метро
        $data['metro'] = $city ? Metro::where('status', 1)->where('city_id', $city['id'])->select('id', 'title')->orderBy('id')->get()->toArray() : [];
        //Национальность
        $data['nationality'] = app('post_nationality');
        //Телосложение
        $data['body_type'] = app('post_body_type');
        //Цвет волос
        $data['hair_color'] = app('post_hair_color');
        //Интимная стрижка
        $data['hairy'] = app('post_hairy');
        //Аксессуары
        $data['body_art'] = app('post_body_art');
        //Выезд
        $data['visit_places'] = app('post_visit_places');
        //Услуги для
        $data['services_for'] = app('post_services_for');
        //Знание языков
        $data['language_skills'] = app('post_language_skills');
        //Услуги
        $data['services'] = app('post_services');
        //Теги
        $data['tags'] = Tags::select('id', 'tag')->orderBy('tag')->get()->toArray();
        //Sections
        $data['sections'] = ['individuals', 'premium', 'elite', 'bdsm', 'masseuse',];
        //Min Max Day One Hours
        $data['price_day_in_one_min'] = Post::min('price_day_in_one') ?: 3000;
        $data['price_day_in_one_max'] = Post::max('price_day_in_one') ?: 100000;
        $data['price_day_in_two_min'] = Post::min('price_day_in_two') ?: 5000;
        $data['price_day_in_two_max'] = Post::max('price_day_in_two') ?: 200000;

        $data['price_day_out_one_min'] = Post::min('price_day_out_one') ?: 3000;
        $data['price_day_out_one_max'] = Post::max('price_day_out_one') ?: 100000;
        $data['price_day_out_two_min'] = Post::min('price_day_out_two') ?: 5000;
        $data['price_day_out_two_max'] = Post::max('price_day_out_two') ?: 200000;

        $data['price_night_in_one_min'] = Post::min('price_night_in_one') ?: 5000;
        $data['price_night_in_one_max'] = Post::max('price_night_in_one') ?: 500000;
        $data['price_night_in_night_min'] = Post::min('price_night_in_night') ?: 5000;
        $data['price_night_in_night_max'] = Post::max('price_night_in_night') ?: 500000;

        $data['price_night_out_one_min'] = Post::min('price_night_out_one') ?: 5000;
        $data['price_night_out_one_max'] = Post::max('price_night_out_one') ?: 500000;
        $data['price_night_out_night_min'] = Post::min('price_night_out_night') ?: 5000;
        $data['price_night_out_night_max'] = Post::max('price_night_out_night') ?: 500000;

        return $data;
    }

    public function microDataPost($post_id): bool|string
    {
        $imageConverter = new ImageConverter;
        $site_name = $this->getSetting('micro_site_name') ?? 'IntimateCMS';
        $post = Cache::remember('Getters_microDataPost_$post_' . $post_id, (60 * 60), function () use ($post_id) {
            return Post::where('id', $post_id)->first();
        });
        $age = trans_choice(__('choice.age'), $post['age'], ['num' => $post['age']]);
        $city = Cache::remember('Getters_microDataPost_$city_' . $post['city_id'], (60 * 60), function () use ($post) {
            return City::where('id', $post['city_id'])->value('title');
        });
        $name_info = __('lang.microdata_name_info', ['name' => $post['name'], 'age' => $age, 'city' => $city]);
        $views = ($post['views_post_uniq'] <= 0) ? 1 : $post['views_post_uniq'];

        $image = $this->getPostMainImage($post['id']);
        if (!empty($image) && File::exists(public_path($image))) {
            $image = url($imageConverter->toMini($image, height: 500, watermark: false));
        } else {
            $image = url('no_image_round.png');
        }

        $price = 0;
        $currency = $this->getCurrencyCode();
        $prices = [
            1 => $post['price_day_in_one'],
            2 => $post['price_day_in_two'],
            3 => $post['price_day_out_one'],
            4 => $post['price_day_out_two'],
            5 => $post['price_night_in_one'],
            6 => $post['price_night_in_night'],
            7 => $post['price_night_out_one'],
            8 => $post['price_night_out_night'],
        ];
        $validPrices = array_filter($prices, function ($value) {
            return $value > 0;
        });

        if (!empty($validPrices)) {
            $price = min($validPrices);
        }

        $data = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "brand" => [
                "@type" => "Brand",
                "name" => $site_name
            ],
            "image" => $image,
            "name" => $name_info,
            "description" => "Описание",
            "offers" => [
                "@type" => "Offer",
                "url" => "https://link_to_post",
                "Price" => $price,
                "priceCurrency" => $currency,
                "availability" => "https://schema.org/InStock",
                "seller" => [
                    "@type" => "Organization",
                    "name" => $site_name,
                ],
            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => 5,
                "ratingCount" => $views,
            ],
        ];

        return json_encode($data);
    }

    public function microDataPostArticle($post_id, $name = null, $phone = null, $zone = null, $metro = null, $city = null, $age = null, $created_at = null, $updated_at = null): bool|string
    {
        $logo = $this->getSetting('image_logo') ?? null;
        $logo = (!empty($logo)) ? url($logo) : url('logo.svg');

        $site_name = $this->getSetting('micro_site_name') ?? 'IntimateCMS';
        $title = __('catalog/posts/post.post_item', ['sitename' => $site_name, 'type' => (!empty($post_type)) ? __('catalog/posts/post.' . $post_type) : '', 'name' => $name, 'age' => $age, 'phone' => $phone, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $post_id]);
        $description = __('catalog/posts/post.post_desc', ['sitename' => $site_name, 'name' => $name, 'age' => $age, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $post_id]);

        $data = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => url('/')
            ],
            "headline" => $title,
            "description" => $description,
            "image" => $logo,
            "author" => [
                "@type" => "Organization",
                "name" => $site_name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $site_name,
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => $logo
                ]
            ],
            "datePublished" => date('Y-m-d', strtotime($created_at)),
            "dateModified" => date('Y-m-d', strtotime($updated_at))
        ];

        return json_encode($data);
    }

    public function microDataSalonArticle($salon_id, $title = null, $phone = null, $zone = null, $metro = null, $city = null, $created_at = null, $updated_at = null): bool|string
    {
        $logo = $this->getSetting('image_logo') ?? null;
        $logo = (!empty($logo)) ? url($logo) : url('logo.svg');

        $site_name = $this->getSetting('micro_site_name') ?? 'IntimateCMS';
        $title = __('catalog/salon/salon.salon_item', ['sitename' => $site_name, 'title' => $title, 'phone' => $phone, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $salon_id]);
        $description = __('catalog/salon/salon.salon_desc', ['sitename' => $site_name, 'title' => $title, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $salon_id]);

        $data = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => url('/')
            ],
            "headline" => $title,
            "description" => $description,
            "image" => $logo,
            "author" => [
                "@type" => "Organization",
                "name" => $site_name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $site_name,
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => $logo
                ]
            ],
            "datePublished" => date('Y-m-d', strtotime($created_at)),
            "dateModified" => date('Y-m-d', strtotime($updated_at))
        ];

        return json_encode($data);
    }

    public function microDataNewsArticle($news_id): bool|string
    {
        $imageConverter = new ImageConverter;
        $news = News::where('id', $news_id)->first();
        $current_name = Str::slug($news['title']);
        $url = route('client.news', ['news_id' => $news_id, 'title' => $current_name]);
        $image = $logo = $this->getSetting('image_logo') ?? null;
        $title = $news['meta_title'] ?? $news['title'];
        $description = ($news['meta_description']) ? Str::limit($news['meta_description'], 150, '...') : Str::limit($this->reverseTextData($news['desc']), 150, '...');

        if (!empty($news['image']) && File::exists(public_path($news['image']))) {
            $image = url($imageConverter->toMini($news['image']));
        } else {
            $image = url($image);
        }

        $site_name = $this->getSetting('micro_site_name') ?? 'IntimateCMS';

        $data = [
            "@context" => "https://schema.org",
            "@type" => "NewsArticle",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $url
            ],
            "headline" => $title,
            "datePublished" => $news->created_at->format('Y-m-d\TH:i:sP'),
            "dateModified" => $news->updated_at->format('Y-m-d\TH:i:sP'),
            "description" => $description,
            "author" => [
                "@type" => "Person",
                "image" => $image,
                "url" => url('/'),
                "jobTitle" => $site_name,
                "name" => $site_name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $site_name,
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => url($logo)
                ]
            ],
            "image" => [
                "@type" => "ImageObject",
                "url" => $image
            ]
        ];

        return json_encode($data);
    }

    public function getPostCountByService($service_id): int
    {
        return Post::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(services, '$.\"$service_id\".condition')) IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->count() ?? 0;
    }

    public function getPostCountByTag($tag_id)
    {
        return Cache::remember('Getters_getPostCountByTag__' . $tag_id, 120, function () use ($tag_id) {
            return Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->count() ?? 0;
        });
    }

    public function getPostCountByCity($city_id)
    {
        return Cache::remember('Getters_getPostCountByCity__' . $city_id, 120, function () use ($city_id) {
            return Post::where("city_id", $city_id)->where('moderation_id', 1)->where('publish', 1)->count() ?? 0;
        });
    }

    public function getPostCountByZone($zone_id)
    {
        return Cache::remember('Getters_getPostCountByZone__' . $zone_id, 120, function () use ($zone_id) {
            return Post::where("zone_id", $zone_id)->where('moderation_id', 1)->where('publish', 1)->count() ?? 0;
        });
    }

    public function getPostMainImage($post_id)
    {
        $image = PostContent::where('post_id', $post_id)->where('type', 'main')->first();
        return $image->file ?? null;
    }

    public function getSalonMainImage($salon_id)
    {
        $image = SalonContent::where('salon_id', $salon_id)->where('type', 'main')->first();
        return $image->file ?? null;
    }

    public function getAllContentData($post_id): array
    {
        $imageConverter = new ImageConverter;
        $post_info = Post::where('id', $post_id)->first();
        $data['photo'] = [];
        $photos = PostContent::where('post_id', $post_id)->where('type', 'photo')->get();
        foreach ($photos as $photo) {
            if (File::exists(public_path($photo['file']))) {
                $data['photo'][] = [
                    'small' => url($imageConverter->toMini($photo['file'], width: 200, height: 200)),
                    'big' => url($imageConverter->toMini($photo['file'], watermark: true)),
                ];
            }
        }
        $data['selfie'] = [];
        $selfies = PostContent::where('post_id', $post_id)->where('type', 'selfie')->get();
        foreach ($selfies as $selfie) {
            if (File::exists(public_path($selfie['file']))) {
                $data['selfie'][] = [
                    'small' => url($imageConverter->toMini($selfie['file'], width: 200, height: 200)),
                    'big' => url($imageConverter->toMini($selfie['file'], watermark: true)),
                ];
            }
        }
        $data['video'] = [];
        $videos = PostContent::where('post_id', $post_id)->where('type', 'video')->get();
        foreach ($videos as $video) {
            $video_path = public_path($video['file']);
            $preview_path = public_path('images/posts/' . $post_info['uniq_uid'] . '/video_previev/' . pathinfo($video['file'], PATHINFO_FILENAME) . '.jpg');

            if (File::exists($video_path)) {
                $command = "ffmpeg -i {$video_path} -ss 00:00:05 -vframes 1 {$preview_path}";
                shell_exec($command);

                $data['video'][] = [
                    'small' => url($video['file']),
                    'big' => url($video['file']),
                ];

            }
        }
        return $data;
    }

    public function getAllSalonContentData($salon_id): array
    {
        $imageConverter = new ImageConverter;
        $data['photo'] = [];
        $photos = SalonContent::where('salon_id', $salon_id)->where('type', 'photo')->get();
        foreach ($photos as $photo) {
            if (File::exists(public_path($photo['file']))) {
                $data['photo'][] = [
                    'small' => url($imageConverter->toMini($photo['file'], width: 200, height: 200)),
                    'big' => url($imageConverter->toMini($photo['file'], watermark: true)),
                ];
            }
        }
        return $data;
    }

    public function postStatusChecker()
    {
        $post_activation_status = $this->getSetting('post_activation_status');
        $current_time = Carbon::now();
        $updates = ['diamond' => [], 'vip' => [], 'color' => [], 'publish' => []];
        $posts = Post::select('id', 'diamond_date', 'vip_date', 'color_date', 'publish_date', 'moderation_id')->get();

        foreach ($posts as $post) {
            if (empty($post['diamond_date']) || Carbon::parse($post['diamond_date'])->isBefore($current_time)) {
                $updates['diamond'][] = $post['id'];
            }
            if (empty($post['vip_date']) || Carbon::parse($post['vip_date'])->isBefore($current_time)) {
                $updates['vip'][] = $post['id'];
            }
            if (empty($post['color_date']) || Carbon::parse($post['color_date'])->isBefore($current_time)) {
                $updates['color'][] = $post['id'];
            }
            if ($post_activation_status && (empty($post['publish_date']) || Carbon::parse($post['publish_date'])->isBefore($current_time))) {
                $updates['publish'][] = $post['id'];
            } elseif (!$post_activation_status && $post['moderation_id'] == 1) {
                $updates['publish'][] = $post['id'];
            }
        }

        // Массовое обновление статусов
        if (!empty($updates['diamond'])) {
            Post::whereIn('id', $updates['diamond'])->update(['diamond' => 0, 'diamond_date' => $current_time->subDays(2)]);
        }
        if (!empty($updates['vip'])) {
            Post::whereIn('id', $updates['vip'])->update(['vip' => 0, 'vip_date' => $current_time->subDays(2)]);
        }
        if (!empty($updates['color'])) {
            Post::whereIn('id', $updates['color'])->update(['color' => 0, 'color_date' => $current_time->subDays(2)]);
        }
        if ($post_activation_status && !empty($updates['publish'])) {
            Post::whereIn('id', $updates['publish'])->update(['publish' => 0, 'publish_date' => $current_time->subDays(2)]);
        } elseif (!$post_activation_status && !empty($updates['publish'])) {
            Post::whereIn('id', $updates['publish'])->update(['publish' => 1]);
        }
    }

    public function salonStatusChecker()
    {
        $salon_activation_status = $this->getSetting('salon_activation_status');
        $current_time = Carbon::now();
        $updates = ['publish' => []];
        $posts = Salon::select('id', 'publish_date', 'moderation_id')->get();

        foreach ($posts as $post) {
            if ($salon_activation_status && (empty($post['publish_date']) || Carbon::parse($post['publish_date'])->isBefore($current_time))) {
                $updates['publish'][] = $post['id'];
            } elseif (!$salon_activation_status && $post['moderation_id'] == 1) {
                $updates['publish'][] = $post['id'];
            }
        }

        // Массовое обновление статусов
        if ($salon_activation_status && !empty($updates['publish'])) {
            Salon::whereIn('id', $updates['publish'])->update(['publish' => 0, 'publish_date' => $current_time->subDays(2)]);
        } elseif (!$salon_activation_status && !empty($updates['publish'])) {
            Salon::whereIn('id', $updates['publish'])->update(['publish' => 1]);
        }
    }

    public function checkAaiotransactions()
    {
        $aaio_settings = $this->getSetting('aaio') ?? null;
        if (isset($aaio_settings['status']) && $aaio_settings['status']) {
            $orders = Transaction::where('order_id', '!=', null)->where('order_status_id', '=', $aaio_settings['order_wait_id'])->where('created_at', '<', Carbon::now()->subHour())->get();
            foreach ($orders as $order) {
                Transaction::where('id', $order['id'])->update(['order_status_id' => $aaio_settings['order_fail_id']]);
            }
        }
    }

    public function webVisor(): bool
    {
        //Delete Old
        $threshold = Carbon::now()->subMonths(13);
        WebVisor::where('visited_at', '<', $threshold)->delete();

        if ($this->isCrawler(request())) {
            return false;
        }

        //Create New
        $user_agent = request()->header('User-Agent', '');
        $currentLink = url()->current();
        $uniqueCode = $this->getSign();
        $deviceType = $this->detectDeviceType();
        $os = $this->detectOS($user_agent);
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $country = $this->getCountry();
        $brovser = $this->detectBrowser($user_agent);

        if (isset($_SERVER['HTTP_REFERER'])) {
            $source = $_SERVER['HTTP_REFERER'];
        } else {
            $source = "(direct)";
        }

        $httpAcceptLanguage = request()->header('Accept-Language', 'en-gb');
        $lang = substr($httpAcceptLanguage, 0, 2);

        $today = Carbon::now()->startOfDay();
        $existingVisit = WebVisor::where('unique_code', $uniqueCode)->where('ip_address', $ipAddress)->whereDate('visited_at', $today)->first();

        if ($existingVisit) {
            $existingVisit->increment('visit_count');
        } else {
            WebVisor::create(['unique_code' => $uniqueCode, 'ip_address' => $ipAddress, 'browser' => $brovser, 'language' => $lang, 'device' => $deviceType, 'country' => $country['country'] ?? null, 'operating_system' => $os, 'source' => $source, 'visited_at' => Carbon::now(), 'latitude' => $country['latitude'] ?? null, 'longitude' => $country['longitude'] ?? null]);
        }

        $this->webRealTime($currentLink, $uniqueCode, $ipAddress);

        return false;
    }

    public function getVisitorCounter($ipAddress): bool
    {
        $blockIpsFilePath = public_path('block_ips.txt');

        if (!file_exists($blockIpsFilePath)) {
            file_put_contents($blockIpsFilePath, '');
        }

        $blockedIps = file($blockIpsFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($ipAddress, $blockedIps)) {
            return true;
        }

        $today = Carbon::now()->startOfDay();
        $existingVisit = WebVisor::where('ip_address', $ipAddress)->whereDate('visited_at', $today)->count();

        if ($existingVisit >= 2) {
            file_put_contents($blockIpsFilePath, $ipAddress . PHP_EOL, FILE_APPEND | LOCK_EX);
            return true;
        }

        return false;
    }

    public function webRealTime($currentLink, $uniqueCode, $ipAddress)
    {
        //Delete Old
        $threshold = Carbon::now()->subMinutes(60);
        WebRealtime::where('created_at', '<', $threshold)->delete();
        WebRealtime::where('url', 'like', '%error-404%')->delete();

        if ($this->isCrawler(request())) {
            return;
        }

        //Create New
        $currentMinute = Carbon::now()->format('Y-m-d H:i:00');
        $existingVisit = WebRealtime::where('unique_code', $uniqueCode)
            ->where('ip_address', $ipAddress)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:00') = ?", [$currentMinute])
            ->first();

        if ($existingVisit) {
            $existingVisit->update([
                'url' => $currentLink,
            ]);
        } else {
            WebRealtime::create([
                'unique_code' => $uniqueCode,
                'ip_address' => $ipAddress,
                'url' => $currentLink,
            ]);
        }
    }

    public function getCountry(): array
    {
        $ip = request()->ip();

        $response = Http::get("http://www.geoplugin.net/json.gp", ['ip' => $ip]);

        if ($response->successful()) {
            $json = json_decode($response, true);
            $countryCode = $json['geoplugin_countryCode'] ?? null;
            $latitude = $json['geoplugin_latitude'] ?? null;
            $longitude = $json['geoplugin_longitude'] ?? null;
            if ($countryCode || $latitude || $longitude) {
                return ['country' => $countryCode ?? 'XX', 'latitude' => $latitude, 'longitude' => $longitude];
            }
        }

        if (isset($_SERVER['HTTP_GEOIP_COUNTRY_CODE'])) {
            return ['country' => $_SERVER['HTTP_GEOIP_COUNTRY_CODE'], 'latitude' => null, 'longitude' => null];
        }

        if (function_exists('geoip_country_code_by_name')) {
            return ['country' => geoip_country_code_by_name($ip) ?? 'XX', 'latitude' => null, 'longitude' => null];
        }

        return ['country' => 'XX', 'latitude' => null, 'longitude' => null,];
    }

    private function detectDeviceType(): string
    {
        $agent = strtolower(request()->header('User-Agent', ''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $agent)) {
            return 'Tablet';
        } elseif (preg_match('/SmartTV|SMART-TV|SmartTV/i', $agent)) {
            return 'SmartTV';
        } elseif (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Silk/i', $agent)) {
            return 'Mobile';
        } else {
            return 'Desktop';
        }
    }

    public function detectBrowser($agent): string
    {
        $browsers = ['MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera', 'Edge', 'Yandex', 'YandexBrowser', 'Brave', 'Vivaldi', 'Maxthon', 'PaleMoon', 'Tor', 'UCBrowser'];
        foreach ($browsers as $browser) {
            if (str_contains($agent, $browser)) {
                return $browser;
            }
        }
        return 'Unknown';
    }

    private function detectOS($agent): string
    {
        if (preg_match('/windows phone/i', $agent)) {
            return 'WindowsPhone';
        } elseif (preg_match('/windows nt/i', $agent)) {
            return 'Windows';
        } elseif (preg_match('/smarttv/i', $agent)) {
            return 'SmartTV';
        } elseif (preg_match('/googletv/i', $agent)) {
            return 'GoogleTV';
        } elseif (preg_match('/appletv/i', $agent)) {
            return 'AppleTV';
        } elseif (preg_match('/roku/i', $agent)) {
            return 'Roku OS';
        } elseif (preg_match('/chromecast/i', $agent)) {
            return 'Chromecast';
        } elseif (preg_match('/android/i', $agent)) {
            return 'Android';
        } elseif (preg_match('/iphone/i', $agent)) {
            return 'iPhone';
        } elseif (preg_match('/ipod/i', $agent)) {
            return 'iPod';
        } elseif (preg_match('/ipad/i', $agent)) {
            return 'iPad';
        } elseif (preg_match('/apple/i', $agent)) {
            return 'Apple';
        } elseif (preg_match('/macintosh/i', $agent)) {
            return 'Mac OS';
        } elseif (preg_match('/mac os x/i', $agent)) {
            return 'Mac OSx';
        } elseif (preg_match('/linux/i', $agent)) {
            return 'Linux';
        } elseif (preg_match('/ubuntu/i', $agent)) {
            return 'Ubuntu';
        } elseif (preg_match('/blackberry/i', $agent)) {
            return 'BlackBerry';
        } elseif (preg_match('/webos/i', $agent)) {
            return 'WebOs';
        } elseif (preg_match('/bada/i', $agent)) {
            return 'Bada';
        } elseif (preg_match('/rim tablet/i', $agent)) {
            return 'BlackBerryTabletOS';
        } elseif (preg_match('/kindle/i', $agent)) {
            return 'Kindle';
        } elseif (preg_match('/symbian/i', $agent)) {
            return 'Symbian OS';
        } elseif (preg_match('/kodi/i', $agent)) {
            return 'Kodi';
        } else {
            return 'Unknown';
        }
    }

    public function isCrawler(Request $request): bool
    {
        $crawlerDetect = new CrawlerDetect();

        if ($crawlerDetect->isCrawler()) {
            return true;
        }

        return false;
    }

    public function getSign(): string
    {
        $sign_parts = [
            $_SERVER['HTTP_HOST'],
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['REMOTE_ADDR']
        ];

        if (isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM'])) {
            $sign_parts[] = $_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
        }

        return md5(implode(',', $sign_parts));
    }

    public function checkRuKassatransactions($get_key)
    {
        $key = 'B4jH2G3BYOEp5NfghXXFD';

        if ($get_key != $key) {
            return null;
        }

        $ruKassa_last_check = $this->getSetting('ruKassa_last_check') ?? 34958766;

        if ((time() - $ruKassa_last_check) < 5 * 60) {
            return null;
        }

        $ruKassa = $this->getSetting('ruKassa') ?? null;
        if (isset($ruKassa['status']) && $ruKassa['status']) {
            $orders = Transaction::where('order_id', '!=', null)->where('pay_id', '!=', null)->where('type', 'ruKassa_balance')->where('order_status_id', '=', $ruKassa['order_wait_id'])->get();
            foreach ($orders as $order) {
                $data = ['id' => $order['pay_id'], 'order_id' => $order['order_id'], 'shop_id' => $ruKassa['shop_id'], 'token' => $ruKassa['token']];
                $response = Http::asForm()->post('https://lk.rukassa.io/api/v1/getPayInfo', $data);
                $result = $response->json();
                $amount = preg_replace('/[^\d]/', '', $order['price']);
                if (isset($result['status']) && $result['status'] == 'PAID') {
                    $user = Users::find($order['user_id']);
                    if ($user) {
                        $user->increment('balance', $amount);
                    }
                    Transaction::where('order_id', $order['order_id'])->update(['short' => __('transaction.ruKassa_balance_success', ['sum' => $this->currencyFormat(($amount ?? 0))]), 'order_status_id' => $ruKassa['order_success_id']]);
                } elseif (isset($result['status']) && $result['status'] == 'CANCEL') {
                    Transaction::where('id', $order['id'])->update(['order_status_id' => $ruKassa['order_fail_id']]);
                }
            }

            $orders = Transaction::where('order_id', '!=', null)->where('pay_id', '!=', null)->where('type', 'ruKassa_balance')->where('order_status_id', '=', $ruKassa['order_wait_id'])->where('created_at', '<', Carbon::now()->subHour())->get();
            foreach ($orders as $order) {
                Transaction::where('id', $order['id'])->update(['order_status_id' => $ruKassa['order_fail_id']]);
            }
        }

        Settings::updateOrCreate(['code' => 'setting', 'key' => 'ruKassa_last_check'], ['value' => time()]);
    }
}
