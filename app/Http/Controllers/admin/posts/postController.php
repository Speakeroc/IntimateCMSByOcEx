<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class postController extends Controller
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

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post')) return $redirect;
        $this->getters->setSEOTitle('post');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $data['filtered'] = 0;
        $data['post_id'] = $request->input('post_id') ?? null;
        $data['phone'] = $request->input('phone') ?? null;
        $data['name'] = $request->input('name') ?? null;
        $data['age'] = $request->input('age') ?? null;
        $data['verify'] = $request->input('verify') ?? null;
        $data['publish'] = $request->input('publish') ?? null;
        $data['diamond'] = $request->input('diamond') ?? null;
        $data['vip'] = $request->input('vip') ?? null;
        $data['color'] = $request->input('color') ?? null;
        $data['city_id'] = $request->input('city_id') ?? null;
        $data['user_id'] = $request->input('user_id') ?? null;
        $query = Post::query();
        if ($data['post_id'] != null) {
            $query->where('id', $data['post_id']);
        }
        if ($data['phone'] != null) {
            $query->where('phone', 'like', '%' . $data['phone'] . '%');
            $data['filtered']++;
        }
        if ($data['name'] != null) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
            $data['filtered']++;
        }
        if ($data['age'] != null) {
            $query->where('age', $data['age']);
            $data['filtered']++;
        }
        if ($data['verify'] == 'yes') {
            $query->where('verify', 1);
            $data['filtered']++;
        }
        if ($data['verify'] == 'no') {
            $query->where('verify', 0);
            $data['filtered']++;
        }
        if ($data['publish'] == 'yes') {
            $query->where('publish', 1);
            $data['filtered']++;
        }
        if ($data['publish'] == 'no') {
            $query->where('publish', 0);
            $data['filtered']++;
        }
        if ($data['diamond'] == 'yes') {
            $query->where('diamond', 1);
            $data['filtered']++;
        }
        if ($data['diamond'] == 'no') {
            $query->where('diamond', 0);
            $data['filtered']++;
        }
        if ($data['color'] == 'yes') {
            $query->where('color', 1);
            $data['filtered']++;
        }
        if ($data['color'] == 'no') {
            $query->where('color', 0);
            $data['filtered']++;
        }
        if ($data['vip'] == 'yes') {
            $query->where('vip', 1);
            $data['filtered']++;
        }
        if ($data['vip'] == 'no') {
            $query->where('vip', 0);
            $data['filtered']++;
        }
        if ($data['city_id'] != null) {
            $query->where('city_id', $data['city_id']);
            $data['filtered']++;
        }
        if ($data['user_id'] != null) {
            $query->where('user_id', '=', $data['user_id']);
            $data['filtered']++;
        }
        $query->orderByDesc('id');

        $data['data'] = $query->paginate($paginate)->appends([
            'post_id' => $data['post_id'],
            'phone' => $data['phone'],
            'verify' => $data['verify'],
            'publish' => $data['publish'],
            'diamond' => $data['diamond'],
            'vip' => $data['vip'],
            'color' => $data['color'],
            'city_id' => $data['city_id'],
            'user_id' => $data['user_id'],
        ]);

        //Users
        $users = Post::distinct()->orderBy('user_id', 'asc')->pluck('user_id');
        $data['users'] = [];
        foreach ($users as $user) {
            $posts = Post::where('user_id', $user)->count();
            $login = Users::where('id', $user)->value('login');
            $postText = trans_choice('admin/posts/post.posts_choice', $posts, ['num' => $posts]);
            $data['users'][] = ['user_id' => $user, 'name' => $login . ' - ' . $postText, 'count' => $login];
        }

        //Sorting by field 'count'
        usort($data['users'], function ($a, $b) {
            return $a['count'] <=> $b['count'];
        });
        //Users

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

        return view('admin/posts/post/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_add')) return $redirect;
        $this->getters->setSEOTitle('post_add');
        $data = [
            'this_user_id' => Auth::id(),
            'main_data' => $this->getters->getMainPostData(),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'post_activation_status' => $this->getters->getSetting('post_activation_status'),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        $data['post_section_individuals_status'] = $this->getters->getSetting('post_section_individuals_status');
        $data['post_section_premium_status'] = $this->getters->getSetting('post_section_premium_status');
        $data['post_section_health_status'] = $this->getters->getSetting('post_section_health_status');
        $data['post_section_elite_status'] = $this->getters->getSetting('post_section_elite_status');
        $data['post_section_bdsm_status'] = $this->getters->getSetting('post_section_bdsm_status');
        $data['post_section_masseuse_status'] = $this->getters->getSetting('post_section_masseuse_status');

        $data['post_display_cloth'] = $this->getters->getSetting('post_display_cloth');
        $data['post_display_shoes'] = $this->getters->getSetting('post_display_shoes');
        $data['post_display_zone'] = $this->getters->getSetting('post_display_zone');
        $data['post_display_metro'] = $this->getters->getSetting('post_display_metro');

        return view('admin/posts/post/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_add')) return $redirect;
        $this->validate($request, [
            'images.main' => 'required',
            'user_id' => 'required|integer',
            'name' => 'required|min:2|max:15',
            'city_id' => 'required|integer',
            'phone' => 'required',
            'age' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'breast' => 'required',
            'call_time_type' => 'required',
            'delete_code' => 'required|min:4|max:8',
            'nationality' => 'required',
            'body_type' => 'required',
            'hair_color' => 'required',
            'hairy' => 'required',
            'accessories' => 'array',
            'language_skills' => 'array',
            'price_day_in_one' => 'required|integer',
            'price_day_in_two' => 'required|integer',
            'price_day_out_one' => 'nullable|integer',
            'price_day_out_two' => 'nullable|integer',
            'price_night_in_one' => 'nullable|integer',
            'price_night_in_night' => 'nullable|integer',
            'price_night_out_one' => 'nullable|integer',
            'price_night_out_night' => 'nullable|integer',
            'express_price' => 'nullable|integer',
            'description' => 'required|min:100|max:3000',
            'services' => 'required|array',
            'services.*.price' => 'nullable|integer',
            'images.photos' => 'nullable|array',
            'images.selfies' => 'nullable|array',
            'images.verify' => 'nullable',
            'videos' => 'nullable|array',
            'publish' => 'required',
        ]);

        $data['user_id'] = (int)$request->input('user_id');
        $data['uniq_uid'] = $this->getters->generateUniqueId($data['user_id'] . '_%s_%s');
        $data['image_main'] = $request->input('images.main');
        $data['image_photo'] = $request->input('images.photos') ?? null;
        $data['image_selfie'] = $request->input('images.selfies') ?? null;
        $data['image_video'] = $request->input('videos') ?? null;
        $data['image_verify'] = $request->input('images.verify') ?? null;
        $data['name'] = $request->input('name');
        $data['city_id'] = (int)$request->input('city_id');
        $data['s_individuals'] = (int)$request->input('s_individuals') ?? 0;
        $data['s_masseuse'] = (int)$request->input('s_masseuse') ?? 0;
        $data['s_elite'] = (int)$request->input('s_elite') ?? 0;
        $data['s_bdsm'] = (int)$request->input('s_bdsm') ?? 0;
        $data['s_premium'] = (int)$request->input('s_premium') ?? 0;
        $data['s_health'] = (int)$request->input('s_health') ?? 0;
        $data['phone'] = $request->input('phone');
        $data['zone_id'] = (int)$request->input('zone_id');
        $data['metro_id'] = (int)$request->input('metro_id');
        $data['messengers'] = json_encode($request->input('messengers') ?? []);
        $data['tags'] = json_encode($request->input('tags') ?? []);
        $data['call_time_type'] = (int)$request->input('call_time_type');
        $data['call_time'] = json_encode($request->input('call_time') ?? []);
        $data['client_age'] = json_encode($request->input('client_age') ?? []);
        $data['delete_code'] = $request->input('delete_code');
        $data['age'] = (int)$request->input('age');
        $data['weight'] = (int)$request->input('weight');
        $data['cloth'] = (int)$request->input('cloth');
        $data['height'] = (int)$request->input('height');
        $data['breast'] = (int)$request->input('breast');
        $data['shoes'] = (int)$request->input('shoes');
        $data['nationality'] = (int)$request->input('nationality');
        $data['body_type'] = (int)$request->input('body_type');
        $data['hair_color'] = (int)$request->input('hair_color');
        $data['hairy'] = (int)$request->input('hairy');
        $data['body_art'] = json_encode($request->input('body_art') ?? []);
        $data['services_for'] = json_encode($request->input('services_for') ?? []);
        $data['language_skills'] = json_encode($request->input('language_skills') ?? []);
        $data['visit_places'] = json_encode($request->input('visit_places') ?? []);
        $data['services'] = json_encode($request->input('services') ?? []);
        $data['latitude'] = $request->input('latitude');
        $data['longitude'] = $request->input('longitude');
        $data['price_day_in_one'] = $request->input('price_day_in_one');
        $data['price_day_in_two'] = $request->input('price_day_in_two');
        $data['price_day_out_one'] = $request->input('price_day_out_one');
        $data['price_day_out_two'] = $request->input('price_day_out_two');
        $data['price_night_in_one'] = $request->input('price_night_in_one');
        $data['price_night_in_night'] = $request->input('price_night_in_night');
        $data['price_night_out_one'] = $request->input('price_night_out_one');
        $data['price_night_out_night'] = $request->input('price_night_out_night');
        $data['express'] = $request->input('express');
        $data['express_price'] = ($data['express'] == 1) ? $request->input('express_price') : null;
        $data['description'] = $request->input('description');
        $data['diamond'] = $request->input('diamond');
        $data['diamond_date'] = $request->input('diamond_date');
        $data['vip'] = $request->input('vip');
        $data['vip_date'] = $request->input('vip_date');
        $data['color'] = $request->input('color');
        $data['color_date'] = $request->input('color_date');
        $data['verify'] = $request->input('verify');
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');
        $data['publish'] = $request->input('publish');
        $data['publish_date'] = $request->input('publish_date') ?? null;

        $post = Post::create([
            'user_id' => $data['user_id'],
            'uniq_uid' => $data['uniq_uid'],
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            's_individuals' => $data['s_individuals'],
            's_masseuse' => $data['s_masseuse'],
            's_elite' => $data['s_elite'],
            's_bdsm' => $data['s_bdsm'],
            's_premium' => $data['s_premium'],
            's_health' => $data['s_health'],
            'phone' => $data['phone'],
            'zone_id' => $data['zone_id'],
            'metro_id' => $data['metro_id'],
            'messengers' => $data['messengers'],
            'tags' => $data['tags'],
            'call_time_type' => $data['call_time_type'],
            'call_time' => $data['call_time'],
            'client_age' => $data['client_age'],
            'delete_code' => $data['delete_code'],
            'age' => $data['age'],
            'weight' => $data['weight'],
            'cloth' => $data['cloth'],
            'height' => $data['height'],
            'breast' => $data['breast'],
            'shoes' => $data['shoes'],
            'nationality' => $data['nationality'],
            'body_type' => $data['body_type'],
            'hair_color' => $data['hair_color'],
            'hairy' => $data['hairy'],
            'body_art' => $data['body_art'],
            'services_for' => $data['services_for'],
            'language_skills' => $data['language_skills'],
            'visit_places' => $data['visit_places'],
            'services' => $data['services'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'price_day_in_one' => $data['price_day_in_one'],
            'price_day_in_two' => $data['price_day_in_two'],
            'price_day_out_one' => $data['price_day_out_one'],
            'price_day_out_two' => $data['price_day_out_two'],
            'price_night_in_one' => $data['price_night_in_one'],
            'price_night_in_night' => $data['price_night_in_night'],
            'price_night_out_one' => $data['price_night_out_one'],
            'price_night_out_night' => $data['price_night_out_night'],
            'express' => $data['express'],
            'express_price' => ($data['express']) ? $data['express_price'] : null,
            'description' => $data['description'],
            'diamond' => $data['diamond'],
            'diamond_date' => $data['diamond_date'],
            'vip' => $data['vip'],
            'vip_date' => $data['vip_date'],
            'color' => $data['color'],
            'color_date' => $data['color_date'],
            'verify' => $data['verify'],
            'up_date' => Carbon::now(),
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'moderator_id' => Auth::id(),
            'publish' => $data['publish'],
            'publish_date' => $data['publish_date'] ?? Carbon::now(),
        ]);

        $post_id = $post->id;

        $content_main = 'images/posts/' . $data['uniq_uid'] . '/main';
        $content_verify = 'images/posts/' . $data['uniq_uid'] . '/verify';
        $content_photo = 'images/posts/' . $data['uniq_uid'] . '/photo';
        $content_selfie = 'images/posts/' . $data['uniq_uid'] . '/selfie';
        $content_video = 'images/posts/' . $data['uniq_uid'] . '/video';

        //Main Photo
        $image_main = $this->getters->moveTempToFolder($data['image_main'], $content_main, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
        if ($image_main) {
            PostContent::create(['post_id' => $post_id, 'user_id' => $data['user_id'], 'file' => $image_main, 'type' => 'main']);
        }

        //Verify Photo
        if (!empty($data['image_verify'])) {
            $image_verify = $this->getters->moveTempToFolder($data['image_verify'], $content_verify, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
            if ($image_verify) {
                PostContent::create(['post_id' => $post_id, 'user_id' => $data['user_id'], 'file' => $image_verify, 'type' => 'verify']);
            }
        }

        //All Photo
        if (!empty($data['image_photo'])) {
            foreach ($data['image_photo'] as $photo) {
                $image_photo = $this->getters->moveTempToFolder($photo, $content_photo, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
                if ($image_photo) {
                    PostContent::create(['post_id' => $post_id, 'user_id' => $data['user_id'], 'file' => $image_photo, 'type' => 'photo']);
                }
            }
        }

        //All Selfie
        if (!empty($data['image_selfie'])) {
            foreach ($data['image_selfie'] as $selfie) {
                $image_selfie = $this->getters->moveTempToFolder($selfie, $content_selfie, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
                if ($image_selfie) {
                    PostContent::create(['post_id' => $post_id, 'user_id' => $data['user_id'], 'file' => $image_selfie, 'type' => 'selfie']);
                }
            }
        }

        //All Video
        if (!empty($data['image_video'])) {
            foreach ($data['image_video'] as $video) {
                $image_video = $this->getters->moveTempToFolder($video, $content_video, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
                if ($image_video) {
                    PostContent::create(['post_id' => $post_id, 'user_id' => $data['user_id'], 'file' => $image_video, 'type' => 'video']);
                }
            }
        }

        return redirect()->route('post.index')->with('success', __('admin/posts/post.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_edit')) return $redirect;
        $this->getters->setSEOTitle('post_edit');

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
            'age' => $item['age'],
            'weight' => $item['weight'],
            'cloth' => $item['cloth'],
            'height' => $item['height'],
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
            'price_day_in_one' => $item['price_day_in_one'],
            'price_day_in_two' => $item['price_day_in_two'],
            'price_day_out_one' => $item['price_day_out_one'],
            'price_day_out_two' => $item['price_day_out_two'],
            'price_night_in_one' => $item['price_night_in_one'],
            'price_night_in_night' => $item['price_night_in_night'],
            'price_night_out_one' => $item['price_night_out_one'],
            'price_night_out_night' => $item['price_night_out_night'],
            'express' => $item['express'],
            'express_price' => $item['express_price'],
            'description' => $item['description'],
            'diamond' => $item['diamond'],
            'diamond_date' => date('Y-m-d', strtotime($item['diamond_date'])),
            'vip' => $item['vip'],
            'vip_date' => date('Y-m-d', strtotime($item['vip_date'])),
            'color' => $item['color'],
            'color_date' => date('Y-m-d', strtotime($item['color_date'])),
            'verify' => $item['verify'],
            'up_date' => date('Y-m-d', strtotime($item['up_date'])),
            'moderation_id' => $item['moderation_id'],
            'moderation_text' => $item['moderation_text'],
            'moderator_id' => $item['moderator_id'],
            'publish' => $item['publish'],
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

        $data['post_section_individuals_status'] = $this->getters->getSetting('post_section_individuals_status');
        $data['post_section_premium_status'] = $this->getters->getSetting('post_section_premium_status');
        $data['post_section_health_status'] = $this->getters->getSetting('post_section_health_status');
        $data['post_section_elite_status'] = $this->getters->getSetting('post_section_elite_status');
        $data['post_section_bdsm_status'] = $this->getters->getSetting('post_section_bdsm_status');
        $data['post_section_masseuse_status'] = $this->getters->getSetting('post_section_masseuse_status');

        $data['post_display_cloth'] = $this->getters->getSetting('post_display_cloth');
        $data['post_display_shoes'] = $this->getters->getSetting('post_display_shoes');
        $data['post_display_zone'] = $this->getters->getSetting('post_display_zone');
        $data['post_display_metro'] = $this->getters->getSetting('post_display_metro');

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

        return view('admin/posts/post/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_edit')) return $redirect;
        $this->validate($request, [
            'images.main' => 'required',
            'user_id' => 'required|integer',
            'name' => 'required|min:2|max:15',
            'city_id' => 'required|integer',
            'phone' => 'required',
            'age' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'breast' => 'required',
            'call_time_type' => 'required',
            'delete_code' => 'required|min:4|max:8',
            'nationality' => 'required',
            'body_type' => 'required',
            'hair_color' => 'required',
            'hairy' => 'required',
            'accessories' => 'array',
            'language_skills' => 'array',
            'price_day_in_one' => 'required|integer',
            'price_day_in_two' => 'required|integer',
            'price_day_out_one' => 'nullable|integer',
            'price_day_out_two' => 'nullable|integer',
            'price_night_in_one' => 'nullable|integer',
            'price_night_in_night' => 'nullable|integer',
            'price_night_out_one' => 'nullable|integer',
            'price_night_out_night' => 'nullable|integer',
            'express_price' => 'nullable|integer',
            'description' => 'required|min:100|max:3000',
            'services' => 'required|array',
            'services.*.price' => 'nullable|integer',
            'images.photos' => 'nullable|array',
            'images.selfies' => 'nullable|array',
            'images.verify' => 'nullable',
            'videos' => 'nullable|array',
            'publish' => 'required',
        ]);

        $old_post_info = Post::where('id', $id)->first();

        $data['user_id'] = (int)$request->input('user_id');
        $data['uniq_uid'] = $this->getters->generateUniqueId($data['user_id'] . '_%s_%s');
        $data['image_main'] = $request->input('images.main');
        $data['image_photo'] = $request->input('images.photos') ?? null;
        $data['image_selfie'] = $request->input('images.selfies') ?? null;
        $data['image_video'] = $request->input('videos') ?? null;
        $data['image_verify'] = $request->input('images.verify') ?? null;
        $data['name'] = $request->input('name');
        $data['city_id'] = (int)$request->input('city_id');
        $data['s_individuals'] = (int)$request->input('s_individuals') ?? 0;
        $data['s_masseuse'] = (int)$request->input('s_masseuse') ?? 0;
        $data['s_elite'] = (int)$request->input('s_elite') ?? 0;
        $data['s_bdsm'] = (int)$request->input('s_bdsm') ?? 0;
        $data['s_premium'] = (int)$request->input('s_premium') ?? 0;
        $data['s_health'] = (int)$request->input('s_health') ?? 0;
        $data['phone'] = $request->input('phone');
        $data['zone_id'] = (int)$request->input('zone_id');
        $data['metro_id'] = (int)$request->input('metro_id');
        $data['messengers'] = json_encode($request->input('messengers') ?? []);
        $data['tags'] = json_encode($request->input('tags') ?? []);
        $data['call_time_type'] = (int)$request->input('call_time_type');
        $data['call_time'] = json_encode($request->input('call_time') ?? []);
        $data['client_age'] = json_encode($request->input('client_age') ?? []);
        $data['delete_code'] = $request->input('delete_code');
        $data['age'] = (int)$request->input('age');
        $data['weight'] = (int)$request->input('weight');
        $data['cloth'] = (int)$request->input('cloth');
        $data['height'] = (int)$request->input('height');
        $data['breast'] = (int)$request->input('breast');
        $data['shoes'] = (int)$request->input('shoes');
        $data['nationality'] = (int)$request->input('nationality');
        $data['body_type'] = (int)$request->input('body_type');
        $data['hair_color'] = (int)$request->input('hair_color');
        $data['hairy'] = (int)$request->input('hairy');
        $data['body_art'] = json_encode($request->input('body_art') ?? []);
        $data['services_for'] = json_encode($request->input('services_for') ?? []);
        $data['language_skills'] = json_encode($request->input('language_skills') ?? []);
        $data['visit_places'] = json_encode($request->input('visit_places') ?? []);
        $data['services'] = json_encode($request->input('services') ?? []);
        $data['latitude'] = $request->input('latitude');
        $data['longitude'] = $request->input('longitude');
        $data['price_day_in_one'] = $request->input('price_day_in_one');
        $data['price_day_in_two'] = $request->input('price_day_in_two');
        $data['price_day_out_one'] = $request->input('price_day_out_one');
        $data['price_day_out_two'] = $request->input('price_day_out_two');
        $data['price_night_in_one'] = $request->input('price_night_in_one');
        $data['price_night_in_night'] = $request->input('price_night_in_night');
        $data['price_night_out_one'] = $request->input('price_night_out_one');
        $data['price_night_out_night'] = $request->input('price_night_out_night');
        $data['express'] = $request->input('express');
        $data['express_price'] = ($data['express'] == 1) ? $request->input('express_price') : null;
        $data['description'] = $request->input('description');
        $data['diamond'] = $request->input('diamond');
        $data['diamond_date'] = $request->input('diamond_date');
        $data['vip'] = $request->input('vip');
        $data['vip_date'] = $request->input('vip_date');
        $data['color'] = $request->input('color');
        $data['color_date'] = $request->input('color_date');
        $data['verify'] = $request->input('verify');
        $data['up_date'] = $request->input('up_date');
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');
        $data['publish'] = $request->input('publish');
        $data['publish_date'] = $request->input('publish_date') ?? null;

        if ($data['moderation_id'] == 1) {
            $data['moderation_text'] = '';
        }

        Post::where('id', '=', $id)->update([
            'user_id' => $data['user_id'],
            'uniq_uid' => $data['uniq_uid'],
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            's_individuals' => $data['s_individuals'],
            's_masseuse' => $data['s_masseuse'],
            's_elite' => $data['s_elite'],
            's_bdsm' => $data['s_bdsm'],
            's_premium' => $data['s_premium'],
            's_health' => $data['s_health'],
            'phone' => $data['phone'],
            'zone_id' => $data['zone_id'],
            'metro_id' => $data['metro_id'],
            'messengers' => $data['messengers'],
            'tags' => $data['tags'],
            'call_time_type' => $data['call_time_type'],
            'call_time' => $data['call_time'],
            'client_age' => $data['client_age'],
            'delete_code' => $data['delete_code'],
            'age' => $data['age'],
            'weight' => $data['weight'],
            'cloth' => $data['cloth'],
            'height' => $data['height'],
            'breast' => $data['breast'],
            'shoes' => $data['shoes'],
            'nationality' => $data['nationality'],
            'body_type' => $data['body_type'],
            'hair_color' => $data['hair_color'],
            'hairy' => $data['hairy'],
            'body_art' => $data['body_art'],
            'services_for' => $data['services_for'],
            'language_skills' => $data['language_skills'],
            'visit_places' => $data['visit_places'],
            'services' => $data['services'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'price_day_in_one' => $data['price_day_in_one'],
            'price_day_in_two' => $data['price_day_in_two'],
            'price_day_out_one' => $data['price_day_out_one'],
            'price_day_out_two' => $data['price_day_out_two'],
            'price_night_in_one' => $data['price_night_in_one'],
            'price_night_in_night' => $data['price_night_in_night'],
            'price_night_out_one' => $data['price_night_out_one'],
            'price_night_out_night' => $data['price_night_out_night'],
            'express' => $data['express'],
            'express_price' => ($data['express']) ? $data['express_price'] : null,
            'description' => $data['description'],
            'diamond' => $data['diamond'],
            'diamond_date' => $data['diamond_date'],
            'vip' => $data['vip'],
            'vip_date' => $data['vip_date'],
            'color' => $data['color'],
            'color_date' => $data['color_date'],
            'verify' => $data['verify'],
            'up_date' => ($data['up_date']) ? Carbon::now()->format('Y-m-d\TH:i') : date('Y-m-d H:i', strtotime($old_post_info['up_date'])),
            'moderator_id' => Auth::id(),
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'publish' => $data['publish'],
            'publish_date' => $data['publish_date'] ?? Carbon::now(),
        ]);

        $content_main = 'images/posts/' . $data['uniq_uid'] . '/main';
        $content_verify = 'images/posts/' . $data['uniq_uid'] . '/verify';
        $content_photo = 'images/posts/' . $data['uniq_uid'] . '/photo';
        $content_selfie = 'images/posts/' . $data['uniq_uid'] . '/selfie';
        $content_video = 'images/posts/' . $data['uniq_uid'] . '/video';

        //Delete all content
        PostContent::where('post_id', $id)->delete();

        //Main Photo
        $image_main = $this->getters->moveTempToFolder($data['image_main'], $content_main, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
        if ($image_main) {
            PostContent::create(['post_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_main, 'type' => 'main']);
        }

        //Verify Photo
        if (!empty($data['image_verify'])) {
            $image_verify = $this->getters->moveTempToFolder($data['image_verify'], $content_verify, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
            if ($image_verify) {
                PostContent::create(['post_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_verify, 'type' => 'verify']);
            }
        }

        //All Photo
        if (!empty($data['image_photo'])) {
            foreach ($data['image_photo'] as $photo) {
                $image_photo = $this->getters->moveTempToFolder($photo, $content_photo, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
                if ($image_photo) {
                    PostContent::create(['post_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_photo, 'type' => 'photo']);
                }
            }
        }

        //All Selfie
        if (!empty($data['image_selfie'])) {
            foreach ($data['image_selfie'] as $selfie) {
                $image_selfie = $this->getters->moveTempToFolder($selfie, $content_selfie, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
                if ($image_selfie) {
                    PostContent::create(['post_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_selfie, 'type' => 'selfie']);
                }
            }
        }

        //All Video
        if (!empty($data['image_video'])) {
            foreach ($data['image_video'] as $video) {
                $image_video = $this->getters->moveTempToFolder($video, $content_video, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
                if ($image_video) {
                    PostContent::create(['post_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_video, 'type' => 'video']);
                }
            }
        }

        //Delete old folder
        $directoryPath = public_path('images/posts/' . $old_post_info->uniq_uid);
        if (File::exists($directoryPath)) {
            File::deleteDirectory($directoryPath);
        }
        return redirect()->route('post.index')->with('success', __('admin/posts/post.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_delete')) return $redirect;
        $post = Post::where('id', $id)->first();

        //Delete content folder
        if ($post && File::exists(public_path('/images/posts/' . $post->uniq_uid))) {
            File::deleteDirectory(public_path('/images/posts/' . $post->uniq_uid));
        }

        Post::where('id', $id)->delete();
        PostContent::where('post_id', $id)->delete();

        return redirect()->route('post.index')->with('success', __('admin/posts/post.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/post_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $post = Post::where('id', $selectedId)->first();

                //Delete content folder
                if ($post && File::exists(public_path('/images/posts/' . $post->uniq_uid))) {
                    File::deleteDirectory(public_path('/images/posts/' . $post->uniq_uid));
                }

                Post::where('id', $selectedId)->delete();
                PostContent::where('post_id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
