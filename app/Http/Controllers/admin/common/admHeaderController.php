<?php

namespace App\Http\Controllers\admin\common;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class admHeaderController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = [];

        $this->getters->checkAaiotransactions();

        return view('admin/common/header', ['data' => $data]);
    }
}
