<?php

namespace App\Http\Controllers\catalog\id;

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

class postEditController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $title = __('catalog/id/post_update.page_main');
        $this->getters->setMetaInfo(title: $title, url: route('client.auth.post.create'));

        $breadcrumbs = [
            ['link' => route('client.auth.posts'), 'title' => __('catalog/id/post.page_main')],
            ['link' => route('client.auth.post.update', ['id' => $id]), 'title' => __('catalog/id/post_update.page_main')],
        ];

        $item = Post::where('id', $id)->first();
        $item_content = PostContent::where('post_id', $id)->get();

        if (Auth::id() != $item['user_id']) return redirect()->route('client.errors', ['code' => 404]);

        $data = [
            'title' => $title,
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
            'image_main' => null,
            'image_photos' => [],
            'image_selfies' => [],
            'image_videos' => [],
            'image_verify' => null,

            //Get Data
            'main_data' => $this->getters->getMainPostData($item['city_id']),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'post_activation_status' => $this->getters->getSetting('post_activation_status'),
            'breadcrumb' => $this->getters->breadcrumbPages($breadcrumbs),
            'elements' => $this->getters->getHeaderFooter(),
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

        return view('catalog/id/post/update', ['data' => $data]);
    }

    public function updatePost(Request $request, string $id): RedirectResponse
    {
        $this->validate($request, [
            'images.main' => 'required',
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
        ]);

        $old_post_info = Post::where('id', $id)->first();

        $data['user_id'] = (int)Auth::id();
        $data['uniq_uid'] = $this->getters->generateUniqueId($data['user_id'] . '_%s_%s');
        $data['image_main'] = $request->input('images.main');
        $data['image_photo'] = $request->input('images.photos') ?? null;
        $data['image_selfie'] = $request->input('images.selfies') ?? null;
        $data['image_video'] = $request->input('videos') ?? null;
        $data['image_verify'] = $request->input('images.verify') ?? null;
        $data['name'] = $request->input('name');
        $data['city_id'] = (int)$request->input('city_id');
        $data['s_individuals'] = (int)$request->input('s_individuals');
        $data['s_masseuse'] = (int)$request->input('s_masseuse');
        $data['s_elite'] = (int)$request->input('s_elite');
        $data['s_bdsm'] = (int)$request->input('s_bdsm');
        $data['s_premium'] = (int)$request->input('s_premium');
        $data['s_health'] = (int)$request->input('s_health');
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
            'moderation_id' => 0,
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

        return redirect()->route('client.auth.posts')->with('success', __('admin/posts/post.notify_updated'));
    }
}
