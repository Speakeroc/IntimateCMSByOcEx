<?php

namespace App\Http\Controllers\catalog\posts;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\posts\Review;
use App\Models\posts\Tags;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class postController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->post = new Post;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index($post_id, $name)
    {
        $post = Post::where('id', $post_id)->first();
        if (empty($post)) return redirect()->route('client.errors', ['code' => 404]);
        $current_name = Str::slug($post['name']);

        //Redirect checked
        if (!empty($current_name) && $name != $current_name) return redirect()->route('client.post', ['post_id' => $post_id, 'name' => $current_name]);
        if (empty($current_name) || $post['publish'] != 1) return redirect()->route('client.errors', ['code' => 404]);

        //Create View
        $this->post->viewsPost($post_id);

        $data['post_display_cloth'] = $this->getters->getSetting('post_display_cloth');
        $data['post_display_shoes'] = $this->getters->getSetting('post_display_shoes');
        $data['post_display_zone'] = $this->getters->getSetting('post_display_zone');
        $data['post_display_metro'] = $this->getters->getSetting('post_display_metro');

        $data['post_id'] = $post_id;
        $data['post_services_buy'] = $this->getViewsServices($post_id);
        $data['name'] = $name = $post['name'];
        $data['phone'] = $phone = $post['phone'];
        $data['phone_format'] = preg_replace('/[^\d+]/', '', $post['phone']);
        $data['age'] = $age = trans_choice(__('choice.age'), $post['age'], ['num' => $post['age']]);
        $data['city'] = $city = City::where('id', $post['city_id'])->value('title');
        $data['city_link'] = route('client.city.item', ['city_id' => $post['city_id'], 'title' => Str::slug($city)]);
        $city = (!empty($city)) ? __('catalog/posts/post.city', ['city' => $city]) : '';
        $zone_id = $post['zone_id'];
        $metro_id = $post['metro_id'];
        $data['zone'] = Zone::where('id', $zone_id)->value('title') ?? '---';
        $data['metro'] = Metro::where('id', $metro_id)->value('title') ?? '---';
        $zone = ($zone_id) ? Zone::where('id', $zone_id)->value('title') : null;
        $zone = ($zone) ? __('catalog/posts/post.zone', ['zone' => $zone]) : null;
        $metro = ($metro_id) ? Metro::where('id', $metro_id)->value('title') : null;
        $metro = ($metro) ? __('catalog/posts/post.metro', ['metro' => $metro]) : null;

        $post_type = '';
        if ($post['s_premium']) {
            $post_type = 'premium';
        }
        if ($post['verify'] && $post['s_individuals']) {
            $post_type = 'individual';
        }
        if ($post['s_masseuse']) {
            $post_type = 'masseuse';
        }

        $site_name = $this->getters->getSetting('micro_site_name') ?? 'IntimateCMS';
        $data['title'] = __('catalog/posts/post.post_title', ['type' => (!empty($post_type)) ? __('catalog/posts/post.' . $post_type) : '', 'name' => $name, 'age' => $age, 'id' => $post['id']]);
        $title = __('catalog/posts/post.post_item', ['sitename' => $site_name, 'type' => (!empty($post_type)) ? __('catalog/posts/post.' . $post_type) : '', 'name' => $name, 'age' => $age, 'phone' => $phone, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $post_id]);
        $description = __('catalog/posts/post.post_desc', ['sitename' => $site_name, 'name' => $name, 'age' => $age, 'city' => $city, 'zone' => $zone, 'metro' => $metro, 'id' => $post_id]);
        $this->getters->setMetaInfo(title: $title, description: $description, url: route('client.post', ['post_id' => $post_id, 'name' => $current_name]));

        //Post Data
        $data['vip'] = $post['vip'];
        $data['diamond'] = $post['diamond'];
        $data['app_services'] = app('post_services');
        $data['services'] = json_decode($post['services'], true);

        $data['app_new_services'] = [];

        foreach ($data['app_services'] as $service) {
            $service_item = [];
            $service_item['title'] = $service['title'];
            foreach ($service['data'] as $item) {
                $service_item['data'][] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'link' => route('client.services.item', ['service_name' => Str::slug($item['title'])]),
                ];
            }
            $data['app_new_services'][] = $service_item;
        }

        //Content
        $image = $this->getters->getPostMainImage($post['id']);
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

        $content_types = $this->getters->getPluck_P_S_V_count($post_id);
        $data['photo_count'] = in_array('photo', $content_types);
        $data['selfie_count'] = in_array('selfie', $content_types);
        $data['video_count'] = in_array('video', $content_types);
        $data['content_data'] = $this->getters->getAllContentData($post_id);

        //Informations
        $data['height'] = __('lang.centimeter', ['num' => $post['height']]) ?? '---';
        $data['weight'] = __('lang.kilogram', ['num' => $post['weight']]) ?? '---';
        $data['cloth'] = $post['cloth'] ?? '---';
        $data['shoes'] = $post['shoes'] ?? '---';
        $data['breast'] = $post['breast'] ?? '---';
        $data['app_nationality'] = app('post_nationality');
        $data['nationality'] = array_column($data['app_nationality'], 'title', 'id')[$post['nationality']] ?? '---';
        $data['app_body_type'] = app('post_body_type');
        $data['body_type'] = array_column($data['app_body_type'], 'title', 'id')[$post['body_type']] ?? '---';
        $data['app_hair_color'] = app('post_hair_color');
        $data['hair_color'] = array_column($data['app_hair_color'], 'title', 'id')[$post['hair_color']] ?? '---';
        $data['app_hairy'] = app('post_hairy');
        $data['hairy'] = array_column($data['app_hairy'], 'title', 'id')[$post['hairy']] ?? '---';

        //Call Time
        $call_time_type = $post['call_time_type'];
        $call_time = json_decode($post['call_time'], true);
        if (!empty($call_time['answering_to'])) {
            $answering_to = '';
            foreach ($call_time['answering_to'] as $answering_to_item) {
                $answering_to .= __('catalog/posts/post.post_answering_' . $answering_to_item) . ', ';
            }
            if (empty($answering_to)) {
                $data['answering_to'] = '---';
            } else {
                $data['answering_to'] = rtrim($answering_to, ', ');
            }
        } else {
            $data['answering_to'] = '---';
        }
        if ($call_time_type == 1) {
            $data['call_time'] = __('lang.call_time_hours_day');
        } elseif (isset($call_time['time_from']) && isset($call_time['time_to'])) {
            $data['call_time'] = __('lang.time_from_to', ['f' => $call_time['time_from'], 't' => $call_time['time_to']]);
        } else {
            $data['call_time'] = '---';
        }

        //Client Age
        $client_age = json_decode($post['client_age'], true);
        if (!empty($client_age)) {
            $data['client_age_min'] = trans_choice('choice.age', $client_age['min'], ['num' => $client_age['min']]);
            $data['client_age_max'] = trans_choice('choice.age', $client_age['max'], ['num' => $client_age['max']]);
        } else {
            $data['client_age_min'] = '---';
            $data['client_age_max'] = '---';
        }

        //Prices
        $data['price_day_in_one'] = $this->getters->currencyFormat($post['price_day_in_one']);
        $data['price_day_in_two'] = $this->getters->currencyFormat($post['price_day_in_two']);
        $data['price_day_out_one'] = $this->getters->currencyFormat($post['price_day_out_one']);
        $data['price_day_out_two'] = $this->getters->currencyFormat($post['price_day_out_two']);
        $data['price_night_in_one'] = $this->getters->currencyFormat($post['price_night_in_one']);
        $data['price_night_in_night'] = $this->getters->currencyFormat($post['price_night_in_night']);
        $data['price_night_out_one'] = $this->getters->currencyFormat($post['price_night_out_one']);
        $data['price_night_out_night'] = $this->getters->currencyFormat($post['price_night_out_night']);
        $data['express'] = $post['express'];
        $data['express_price'] = $this->getters->currencyFormat($post['express_price']);
        $data['currency_symbol'] = $this->getters->getCurrencySymbol();

        //Description
        $data['desc'] = nl2br(e($post['description']));

        //Visit place
        $visit_place = app('post_visit_places');
        $post_visit_place = json_decode($post['visit_places'], true);
        $data['visit_place'] = array_map(function ($item) use ($post_visit_place) {
            return ['title' => $item['title'], 'check' => in_array($item['id'], $post_visit_place)];
        }, $visit_place);

        //Services for
        $services_for = app('post_services_for');
        $post_services_for = json_decode($post['services_for'], true);
        $data['services_for'] = array_map(function ($item) use ($post_services_for) {
            return ['title' => $item['title'], 'check' => in_array($item['id'], $post_services_for)];
        }, $services_for);

        //Body art
        $body_art = app('post_body_art');
        $post_body_art = json_decode($post['body_art'], true);
        $data['body_art'] = array_map(function ($item) use ($post_body_art) {
            return ['title' => $item['title'], 'check' => in_array($item['id'], $post_body_art)];
        }, $body_art);

        //Language skills
        $language_skills = app('post_language_skills');
        $post_language_skills = json_decode($post['language_skills'], true);
        $data['language_skills'] = array_values(array_filter($language_skills, function ($item) use ($post_language_skills) {
            return in_array($item['id'], $post_language_skills);
        }));

        //Tags
        $db_tags = Tags::all()->map(function ($db_tag) {
            $db_tag['link'] = route('client.tags.item', ['tag_name' => Str::slug($db_tag['tag'])]);
            return $db_tag;
        })->toArray();

        $post_tags = json_decode($post['tags'], true);

        $data['tags'] = array_values(array_filter($db_tags, function ($item) use ($post_tags) {
            return in_array($item['id'], $post_tags);
        }));

        //Location
        $latitude = $post['latitude'];
        $longitude = $post['longitude'];
        $data['location'] = false;
        $data['latitude'] = null;
        $data['longitude'] = null;
        if ($latitude && $longitude) {
            $data['location'] = true;
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
        }

        //Messengers
        $data['messengers'] = json_decode($post['messengers'], true);
        $data['telegram'] = null;
        $data['whatsapp'] = null;
        $data['instagram'] = null;
        $data['polee'] = null;

        if (isset($data['messengers']['telegram']['status']) && $data['messengers']['telegram']['status']) {
            $data['telegram'] = (isset($data['messengers']['telegram']['type']) && $data['messengers']['telegram']['type'] == 'login') ? 'https://t.me/' . $data['messengers']['telegram']['content'] : $data['messengers']['telegram']['content'];
        }

        if (isset($data['messengers']['whatsapp']['status']) && $data['messengers']['whatsapp']['status'] && !empty($data['messengers']['whatsapp']['content'])) {
            $data['whatsapp'] = 'https://wa.me/' . preg_replace('/[^0-9+]/', '', $data['messengers']['whatsapp']['content']);
        }

        if (isset($data['messengers']['instagram']['status']) && $data['messengers']['instagram']['status'] && !empty($data['messengers']['instagram']['content'])) {
            $data['instagram'] = str_contains($data['messengers']['instagram']['content'], 'http') ? $data['messengers']['instagram']['content'] : 'https://www.instagram.com/' . $data['messengers']['instagram']['content'];
        }

        if (isset($data['messengers']['polee']['status']) && $data['messengers']['polee']['status'] && !empty($data['messengers']['polee']['content'])) {
            $data['polee'] = $data['messengers']['polee']['content'];
        }

        //Review
        $reviews = Review::where('post_id', $post_id)->where('moderation_id', 1)->get();

        $data['reviews'] = [];
        foreach ($reviews as $review) {

            if ($review['rating'] >= 1 && $review['rating'] <= 2) {
                $rating_class = 'ex_review_danger';
            } else if ($review['rating'] >= 3 && $review['rating'] <= 4) {
                $rating_class = 'ex_review_warning';
            } else if ($review['rating'] >= 5) {
                $rating_class = 'ex_review_success';
            } else {
                $rating_class = '';
            }

            $data['reviews'][] = [
                'user' => ($review['user_id']) ? Users::where('id', $review['user_id'])->value('login') ?? 'Аноним' : 'Аноним',
                'text' => $review['text'],
                'rating' => $review['rating'],
                'rating_class' => $rating_class,
                'date' => $this->getters->dateText($review['created_at']),
            ];
        }

        //Microdata Article
        $data['microdata_article'] = $this->getters->microDataPostArticle($post_id, $name, $phone, $zone, $metro, $city, $age, $post['created_at'], $post['updated_at']);

        $breadcrumbs = [
            ['link' => route('client.post', ['post_id' => $post_id, 'name' => $current_name]), 'title' => $name]
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/posts/post', ['data' => $data]);
    }

    public function writeReview(Request $request): JsonResponse
    {
        $post_id = $request->input('post_id');
        $review = $request->input('review');
        $rating = $request->input('rating');

        if (empty($review)) {
            return response()->json(['status' => 'error', 'message' => 'Нужно заполнить поле Отзыв']);
        }

        if (empty($rating)) {
            return response()->json(['status' => 'error', 'message' => 'Не выбран рейтинг']);
        }

        if ($post_id) {
            Review::create(['text' => $review, 'rating' => $rating, 'post_id' => $post_id, 'user_id' => Auth::id() ?? 0, 'moderation_id' => 0, 'publish' => 0]);
            return response()->json(['status' => 'success', 'message' => 'Отзыв успешно отправлен на модерацию']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Создание отзыва из вне']);
        }
    }

    public function getServices(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $views = $this->getViewsServices($id);
        return response()->json(['status' => 'success', 'view' => $views]);
    }

    public function getViewsServices($post_id): string
    {
        $post = Post::where('id', $post_id)->first();

        $allow_post_help = Users::where('id', $post['user_id'])->value('allow_post_help');
        $user_check = Auth::check();

        if ($allow_post_help == 1 && $user_check) {
            $data['allow_post_help'] = true;
        } elseif ($allow_post_help == 2) {
            $data['allow_post_help'] = false;
        } else {
            $data['allow_post_help'] = false;
        }

        if ($data['allow_post_help']) {
            $post_positions = Post::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->pluck('id');

            $position = $post_positions->search($post['id']);
            if ($position !== false) {
                $position++;
            } else {
                $position = null;
            }

            $data['post_activation_status'] = $this->getters->getSetting('post_activation_status');
            $post_publish_variable = $this->getters->getSetting('post_publish_variable');
            $data['activation_variable'] = [];

            if ($post) {
                if (!$post['publish']) {
                    $data['btn_activation_prefix'] = null;
                } else {
                    $data['btn_activation_prefix'] = '+';
                }
                foreach ($post_publish_variable as $item) {
                    $days_choice = trans_choice('choice.days', $item['days'], ['d' => $item['days']]);
                    $price = $this->getters->currencyFormat($item['price']);
                    $data['activation_variable'][] = [
                        'day' => $item['days'],
                        'price' => __('catalog/id/post.btn_activation', ['price' => $price, 'days' => $days_choice]),
                    ];
                }
                $data['post'] = [
                    'id' => $post['id'],
                    'position' => $position,
                ];

                $post_prices = $this->getters->getSetting('post_prices');

                $price_up_to_top = $post_prices['up_to_top'];
                $price_up_to_top = $this->getters->currencyFormat($price_up_to_top);
                $data['up_to_top_btn'] = ($position >= 2) ? __('catalog/id/post.btn_up_to_top', ['price' => $price_up_to_top]) : null;

                $price_diamond = (!$post['diamond']) ? $post_prices['diamond_act'] : $post_prices['diamond_ext'];
                $price_diamond = $this->getters->currencyFormat($price_diamond);
                $data['diamond_btn'] = (!$post['diamond']) ? __('catalog/id/post.btn_act_diamond', ['price' => $price_diamond]) : __('catalog/id/post.btn_ext_diamond', ['price' => $price_diamond]);

                $price_vip = (!$post['vip']) ? $post_prices['vip_act'] : $post_prices['vip_ext'];
                $price_vip = $this->getters->currencyFormat($price_vip);
                $data['vip_btn'] = (!$post['vip']) ? __('catalog/id/post.btn_act_vip', ['price' => $price_vip]) : __('catalog/id/post.btn_ext_vip', ['price' => $price_vip]);

                $price_color = (!$post['color']) ? $post_prices['color_act'] : $post_prices['color_ext'];
                $price_color = $this->getters->currencyFormat($price_color);
                $data['color_btn'] = (!$post['color']) ? __('catalog/id/post.btn_act_color', ['price' => $price_color]) : __('catalog/id/post.btn_ext_color', ['price' => $price_color]);
            }
        }

        return view('catalog/posts/include/postService', ['data' => $data])->render();
    }

    public function deleteByCode(Request $request, $id): JsonResponse
    {
        $failedAttempts = Session::get('delete_codes', 0);
        $lastFailedAttemptTime = Session::get('delete_code_time');
        $currentTime = now();
        $shouldBlockAttempts = $lastFailedAttemptTime !== null && $currentTime->diffInMinutes($lastFailedAttemptTime) < 10;
        if ($failedAttempts >= 10 && $shouldBlockAttempts) {
            $unlockTime = Carbon::parse($lastFailedAttemptTime)->addMinutes(10);
            $remainingTime = now()->diffInMinutes($unlockTime);
            return response()->json(['status' => 'error', 'message' => __('catalog/posts/post.notify_del_post_limit', ['time' => $this->func->minutes($remainingTime)])]);
        }
        if ($shouldBlockAttempts === false) $failedAttempts = 0;

        $deleteCode = $request->input('deleteCode');

        $post = Post::where('id', $id)->first();

        if (empty($post)) {
            Session::put('delete_codes', $failedAttempts + 1);
            Session::put('delete_code_time', $currentTime);
            return response()->json(['status' => 'error', 'message' => __('catalog/posts/post.notify_del_post_error')]);
        } elseif ($post['delete_code'] == $deleteCode) {
            $post = Post::where('id', $id)->first();

            //Delete content folder
            if ($post && File::exists(public_path('/images/posts/' . $post->uniq_uid))) {
                File::deleteDirectory(public_path('/images/posts/' . $post->uniq_uid));
            }

            Post::where('id', $id)->delete();
            PostContent::where('post_id', $id)->delete();

            Session::put('delete_code_time', $currentTime);
            Session::forget('delete_codes');

            return response()->json(['status' => 'success', 'message' => __('catalog/posts/post.notify_del_post_success')]);
        } else {
            Session::put('delete_codes', $failedAttempts + 1);
            Session::put('delete_code_time', $currentTime);
            return response()->json(['status' => 'error', 'message' => __('catalog/posts/post.notify_del_post_error')]);
        }
    }
}
