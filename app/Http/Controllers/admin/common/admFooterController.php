<?php

namespace App\Http\Controllers\admin\common;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class admFooterController extends Controller
{
    public function __construct(){}

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = [];
        return view('admin/common/footer', ['data' => $data]);
    }
}
