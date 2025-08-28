<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\Info\Banner;
use App\Models\Info\Information;
use App\Models\Info\News;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\pay\PayCodes;
use App\Models\pay\PayCodesPinCode;
use App\Models\posts\BlackList;
use App\Models\posts\Category;
use App\Models\system\Getters;
use App\Models\system\Settings;
use App\Models\UsersGroup;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class seederController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function seeder(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        SEOMeta::setTitle('Seeder');

        $data['items'] = [];

        //Settings
        $items = Settings::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("Settings::updateOrCreate(['code' => '".$item['code']."', 'key' => '".$item['key']."'],['value' => '".$item['value']."']);")];
        }
        $data['items'][] = ['name' => 'Настройки', 'data' => $data_lines];

        //Category
        $items = Category::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("Category::create(['image' => '".$item['image']."', 'title' => '".$item['title']."', 'description' => '".$item['description']."', 'meta_title' => '".$item['meta_title']."', 'meta_description' => '".$item['meta_description']."', 'slug' => '".$item['slug']."', 'only_verify' => ".$item['only_verify'].", 'status' => ".$item['status']."]);")];
        }
        //$data['items'][] = ['name' => 'Категории', 'data' => $data_lines];

        //City
        $items = City::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("City::create([
            'title' => '".$item['title']."',
            'latitude' => '".$item['latitude']."',
            'longitude' => '".$item['longitude']."',
            'city_code' => '".$item['city_code']."',
            'status' => ".$item['status']."]);")];
        }
        //$data['items'][] = ['name' => 'Города', 'data' => $data_lines];

        //Zone
        $items = Zone::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("Zone::create(['title' => '".$item['title']."', 'city_id' => ".$item['city_id'].", 'status' => ".$item['status']."]);")];
        }
        //$data['items'][] = ['name' => 'Районы', 'data' => $data_lines];

        //Metro
        $items = Metro::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("Metro::create(['title' => '".$item['title']."', 'city_id' => ".$item['city_id'].", 'status' => ".$item['status']."]);")];
        }
        //$data['items'][] = ['name' => 'Метро', 'data' => $data_lines];

        //BlackList
        $items = BlackList::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("BlackList::create(['phone' => '".$item['phone']."', 'text' => '".$item['text']."', 'rating' => ".$item['rating'].", 'user_id' => ".$item['user_id'].", 'views' => ".$item['views']."]);")];
        }
        //$data['items'][] = ['name' => 'Черный список', 'data' => $data_lines];

        //Pin Codes
        $items = PayCodes::all();
        $items_two = PayCodesPinCode::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("PayCodes::create(['id' => ".$item['id'].", 'pay_link' => '".$item['pay_link']."', 'nominal' => ".$item['nominal'].", 'bonus' => ".$item['bonus'].", 'status' => ".$item['status']."]);")];
        }
        foreach ($items_two as $item_two) {
            $data_lines[] = ['line' => trim("PayCodesPinCode::create(['pay_id' => ".$item_two['pay_id'].", 'pin_code' => '".$item_two['pin_code']."']);")];
        }
        //$data['items'][] = ['name' => 'Пин коды', 'data' => $data_lines];

        //News
        $items = News::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("News::create(['user_id' => ".$item['user_id'].", 'title' => '".$item['title']."', 'desc' => '".$item['desc']."', 'meta_title' => '".$item['meta_title']."', 'meta_description' => '".$item['meta_description']."', 'image' => '".$item['image']."', 'seo_url' => '".$item['seo_url']."', 'views' => ".$item['views'].", 'pinned' => ".$item['pinned'].", 'like' => ".$item['like'].", 'dislike' => ".$item['dislike'].", 'status' => ".$item['status'].", 'created_at' => '".$item['created_at']."']); ")];
        }
        //$data['items'][] = ['name' => 'Новости', 'data' => $data_lines];

        //Information
        $items = Information::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("Information::create(['user_id' => ".$item['user_id'].", 'title' => '".$item['title']."', 'desc' => '".$item['desc']."', 'meta_title' => '".$item['meta_title']."', 'meta_description' => '".$item['meta_description']."', 'seo_url' => '".$item['seo_url']."', 'views' => ".$item['views'].", 'status' => ".$item['status'].", 'in_menu' => ".$item['in_menu'].", 'created_at' => '".$item['created_at']."']); ")];
        }
        //$data['items'][] = ['name' => 'Информация', 'data' => $data_lines];

        //Banner
        $items = Banner::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("
            Banner::create([
            'user_id' => ".$item['user_id'].",
            'title' => '".$item['title']."',
            'banner' => '".$item['banner']."',
            'link' => '".$item['link']."',
            'sort_order' => ".$item['sort_order'].",
            'status' => ".$item['status']."]); ")];
        }
        //$data['items'][] = ['name' => 'Баннер', 'data' => $data_lines];

        //User Group
        $items = UsersGroup::all();
        $data_lines = [];
        foreach ($items as $item) {
            $data_lines[] = ['line' => trim("UsersGroup::create(['name' => '".$item['name']."', 'color' => '".$item['color']."', 'permission' => '".$item['permission']."']); ")];
        }
        //$data['items'][] = ['name' => 'Группы пользователей', 'data' => $data_lines];


        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/seeders/seeder', ['data' => $data]);
    }
}
