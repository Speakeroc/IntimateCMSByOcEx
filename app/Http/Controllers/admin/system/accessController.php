<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class accessController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(): View|Application|Factory|Response|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        SEOMeta::setTitle(__('admin/page_titles.access_closed'));

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/system/access', ['data' => $data]);
    }
}
