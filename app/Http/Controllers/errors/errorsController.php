<?php

namespace App\Http\Controllers\errors;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Response;

class errorsController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index($code): Response
    {
        if ($code == 404) {
            $data['code'] = __('errors/errors.error_404');
            $httpStatusCode = 404;
        } elseif ($code == 500) {
            $data['code'] = __('errors/errors.error_500');
            $httpStatusCode = 500;
        } else {
            $data['code'] = __('errors/errors.error_unknown');
            $httpStatusCode = 200;
        }

        $data['elements'] = $this->getters->getHeaderFooter();

        SEOMeta::setTitle(__('errors/errors.error_code', ['code' => $data['code']]));

        return response()->view('errors/errors', ['data' => $data], $httpStatusCode);
    }
}
