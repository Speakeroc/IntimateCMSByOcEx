<?php

namespace App\Http\Controllers\catalog\Info\information;

use App\Http\Controllers\Controller;
use App\Models\Info\Information;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class informationPageController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index($info_id, $title): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $information = Information::where('id', $info_id)->first();
        $current_name = Str::slug($information['title']);

        //Redirect checked
        if (!empty($current_name) && $title != $current_name) return redirect()->route('client.information', ['info_id' => $info_id, 'title' => $current_name]);
        if (empty($current_name)) return redirect()->route('client.errors', ['code' => 404]);

        $data['information_id'] = $info_id;
        $data['h1'] = $information['title'];
        $data['title'] = __('catalog/page_titles.post_information_tt', ['info' => $information['title']]);

        $title = $information['meta_title'] ?? $information['title'];
        $description = ($information['meta_description']) ? Str::limit($information['meta_description'], 150) : Str::limit($this->getters->reverseTextData($information['desc']), 150);
        $this->getters->setMetaInfo(title: $title, description: $description, url: route('client.information', ['info_id' => $info_id, 'title' => $current_name]));

        //Content
        if (!empty($information['image']) && File::exists(public_path($information['image']))) {
            $data['image'] = url($this->imageConverter->toMini($information['image']));
        } else {
            $data['image'] = null;
        }

        $supportEmail = $this->getters->getSetting('support_email') ?? 'support@example.com';
        $microSiteName = $this->getters->getSetting('micro_site_name') ?? 'Default Site';

        $keys = ['{support_email}', '{micro_site_name}'];
        $replacer = [$supportEmail, $microSiteName];

        $description = str_replace($keys, $replacer, $information['desc']);

        //Description
        $data['id'] = $information['id'];
        $data['desc'] = $this->getters->reverseTextData($description);
        $data['views'] = $information['views'];
        $data['like'] = $information['like'];
        $data['dislike'] = $information['dislike'];
        $data['created_at'] = $this->getters->dateText($information['created_at']);

        $breadcrumbs = [
            ['link' => route('client.information.all'), 'title' => __('catalog/page_titles.post_information')],
            ['link' => route('client.information', ['info_id' => $info_id, 'title' => $current_name]), 'title' => $title],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/information/information', ['data' => $data]);
    }
}
