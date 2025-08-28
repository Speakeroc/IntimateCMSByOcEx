<?php

namespace App\Http\Controllers\admin\errors;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class errorsController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function not_found($data_type = null, $data_id = null): View|Application|Factory|Response|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $this->getters->setSEOTitle('errors_not_found');

        $data['heading'] = __('admin/errors/errors.not_found_title');
        $data['text'] = __('admin/errors/errors.not_found_text');

        if (!empty(__('admin/errors/errors.'.$data_type.'_id'))) {
            $data['text'] = __('admin/errors/errors.'.$data_type.'_id', ['id' => $data_id]);
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/errors/not_found', ['data' => $data]);
    }
}
