<?php

namespace App\Http\Controllers\catalog\Info\information;

use App\Http\Controllers\Controller;
use App\Models\Info\Information;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class informationController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->config = ['count_per_page' => 20, 'watermark' => 0];
    }

    public function index()
    {
        $data['h1'] = __('catalog/page_titles.post_information_h1');
        $data['title'] = __('catalog/page_titles.post_information_t');
        $data['description'] = __('catalog/page_titles.post_information_d');
        $this->getters->setMetaInfo(title: $data['title'], description: $data['description'], url: route('client.information.all'));

        $template = 'big';
        $data['data'] = $items = Information::where('status', 1)->where('created_at', '<', Carbon::now())->orderByDesc('created_at')->paginate($this->config['count_per_page']);

        $data['items'] = [];

        foreach ($items as $item) {
            $post_data = [
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => route('client.information', ['info_id' => $item['id'], 'title' => Str::slug($item['title'])]),
                'desc' => Str::limit($this->getters->reverseTextData($item['desc']), 150),
                'date_added' => $this->getters->dateText($item['created_at']),
            ];

            $data['items'][] = view('catalog/info/information/include/'.$template, ['data' => $post_data]);
        }

        $breadcrumbs = [
            ['link' => route('client.information.all'), 'title' => __('catalog/page_titles.post_information')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/information/page', ['data' => $data]);
    }
}
