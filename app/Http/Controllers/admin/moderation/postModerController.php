<?php

namespace App\Http\Controllers\admin\moderation;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class postModerController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index()
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post_moderation')) return $redirect;
        $this->getters->setSEOTitle('post_moderation_page');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $query = Post::where('moderation_id', 0)->orderByDesc('id');

        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $image = $this->getters->getPostMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: 100));
            } else {
                $image = url('no_image_round.png');
            }

            $postId = $item['id'];
            $photo_count = PostContent::where('post_id', $postId)->where('type', 'photo')->count();
            $selfie_count = PostContent::where('post_id', $postId)->where('type', 'selfie')->count();
            $video_count = PostContent::where('post_id', $postId)->where('type', 'video')->count();
            $user = Users::where('id', $item['user_id'])->value('name') . ' - ID:' . $item['user_id'];

            $data['items'][] = [
                'id' => $item['id'],
                'image' => $image,
                'name' => $item['name'],
                'age' => trans_choice(__('admin/posts/post.age_choice'), $item['age'], ['num' => $item['age']]),
                'user' => $user,
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'photo_count' => $photo_count,
                'selfie_count' => $selfie_count,
                'video_count' => $video_count,
                'diamond_status' => ($item['diamond'] == 1) ? __('admin/posts/post.status_diamond_on', ['date' => date('d.m.Y', strtotime($item['diamond_date']))]) : __('admin/posts/post.status_diamond_off'),
                'diamond_s' => $item['diamond'],
                'vip_status' => ($item['vip'] == 1) ? __('admin/posts/post.status_vip_on', ['date' => date('d.m.Y', strtotime($item['vip_date']))]) : __('admin/posts/post.status_vip_off'),
                'vip_s' => $item['vip'],
                'color_status' => ($item['color'] == 1) ? __('admin/posts/post.status_color_on', ['date' => date('d.m.Y', strtotime($item['color_date']))]) : __('admin/posts/post.status_color_off'),
                'color_s' => $item['color'],
                'verify_status' => $item['verify'] ? __('admin/posts/post.status_verify_on') : __('admin/posts/post.status_verify_off'),
                'verify_s' => $item['verify'],
                'phone' => $item['phone'],
                'verify' => $item['verify'] ? __('admin/posts/post.verify') : __('admin/posts/post.no_verify'),
                'publish' => $item['publish'],
                'publish_date' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/moderation/post/index', ['data' => $data]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('post_moderation.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('post_moderation.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post_moderation')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_moderation')) return $redirect;
        $this->getters->setSEOTitle('post_moderation_edit');

        $item = Post::where('id', $id)->first();
        $item_content = PostContent::where('post_id', $id)->get();

        $data = [
            'id' => $id,
            'user_id' => $item['user_id'],
            'uniq_uid' => $item['uniq_uid'],
            'name' => $item['name'],
            'city_id' => $item['city_id'],
            's_individuals' => $item['s_individuals'],
            's_masseuse' => $item['s_masseuse'],
            's_elite' => $item['s_elite'],
            's_bdsm' => $item['s_bdsm'],
            's_premium' => $item['s_premium'],
            's_health' => $item['s_health'],
            'phone' => $item['phone'],
            'zone_id' => $item['zone_id'],
            'metro_id' => $item['metro_id'],
            'messengers' => json_decode($item['messengers'], true),
            'tags' => json_decode($item['tags'], true),
            'call_time_type' => $item['call_time_type'],
            'call_time' => json_decode($item['call_time'], true),
            'client_age' => json_decode($item['client_age'], true),
            'delete_code' => $item['delete_code'],
            'age' => trans_choice(__('choice.age'), $item['age'], ['num' => $item['age']]),
            'weight' => __('lang.kilogram', ['num' => $item['weight']]),
            'cloth' => $item['cloth'],
            'height' => __('lang.centimeter', ['num' => $item['height']]),
            'breast' => $item['breast'],
            'shoes' => $item['shoes'],
            'nationality' => $item['nationality'],
            'body_type' => $item['body_type'],
            'hair_color' => $item['hair_color'],
            'hairy' => $item['hairy'],
            'body_art' => json_decode($item['body_art'], true),
            'services_for' => json_decode($item['services_for'], true),
            'language_skills' => json_decode($item['language_skills'], true),
            'visit_places' => json_decode($item['visit_places'], true),
            'services' => json_decode($item['services'], true),
            'latitude' => $item['latitude'],
            'longitude' => $item['longitude'],
            'price_day_in_one' => $this->getters->currencyFormat($item['price_day_in_one']),
            'price_day_in_two' => $this->getters->currencyFormat($item['price_day_in_two']),
            'price_day_out_one' => $this->getters->currencyFormat($item['price_day_out_one']),
            'price_day_out_two' => $this->getters->currencyFormat($item['price_day_out_two']),
            'price_night_in_one' => $this->getters->currencyFormat($item['price_night_in_one']),
            'price_night_in_night' => $this->getters->currencyFormat($item['price_night_in_night']),
            'price_night_out_one' => $this->getters->currencyFormat($item['price_night_out_one']),
            'price_night_out_night' => $this->getters->currencyFormat($item['price_night_out_night']),
            'express' => $item['express'],
            'express_price' => $item['express_price'],
            'description' => $item['description'],
            'verify' => $item['verify'],
            'moderation_id' => $item['moderation_id'],
            'moderation_text' => $item['moderation_text'],
            'moderator_id' => $item['moderator_id'],
            'publish_date' => date('Y-m-d', strtotime($item['publish_date'])),
            'image_main' => null,
            'image_photos' => [],
            'image_selfies' => [],
            'image_videos' => [],
            'image_verify' => null,

            //Get Data
            'main_data' => $this->getters->getMainPostData($item['city_id']),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'post_activation_status' => $this->getters->getSetting('post_activation_status'),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        $typeMap = ['main' => 'image_main', 'photo' => 'image_photos', 'selfie' => 'image_selfies', 'video' => 'image_videos', 'verify' => 'image_verify'];
        foreach ($item_content as $content) {
            $type = $content['type'];
            if (isset($typeMap[$type])) {
                if (is_array($data[$typeMap[$type]])) {
                    $data[$typeMap[$type]][] = $content['file'];
                } else {
                    $data[$typeMap[$type]] = $content['file'];
                }
            }
        }

        return view('admin/moderation/post/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post_moderation')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_moderation')) return $redirect;
        $this->validate($request, [
            'moderation_id' => 'required',
        ]);

        $data['post_activation_status'] = $this->getters->getSetting('post_activation_status');

        $data['verify'] = $request->input('verify');
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');

        if ($data['moderation_id'] == 1) {
            $data['moderation_text'] = '';
        }

        if ($data['post_activation_status'] && $data['moderation_id'] == 1) {
            $publish = 0;
        } elseif ($data['post_activation_status'] && $data['moderation_id'] == 1) {
            $publish = 1;
        } else {
            $publish = 0;
        }

        Post::where('id', '=', $id)->update([
            'verify' => $data['verify'],
            'moderator_id' => Auth::id(),
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'publish' => $publish,
            'publish_date' => Carbon::now(),
        ]);

        return redirect()->route('post_moderation.index')->with('success', __('admin/posts/post.notify_moderation'));
    }

    public function destroy(): RedirectResponse
    {
        return redirect()->route('post_moderation.index');
    }
}
