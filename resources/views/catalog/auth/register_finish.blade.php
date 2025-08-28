@extends('catalog/layout/layout')
@section('header', $data['header'])
@section('css_js_header')
@endsection
@section('content')
    <div id="page-register" class="content d-flex align-items-center justify-content-center position-relative">
        <div class="card m-4 bg-gray">
            <div class="card-body p-0">
                <h3 class="text-center fw-light">{{ __('catalog/auth/auth.page_register_finish__title') }}</h3>
                <hr>
                <p class="d-block text-center">{!! __('catalog/auth/auth.page_register_finish__text') !!}</p>
                <br>
                <p class="d-block text-center">{!! __('catalog/auth/auth.page_register_finish__contact', ['route' => route('c.info.contact')]) !!}</p>
            </div>
        </div>
    </div>
@endsection
@section('css_js_footer')
    <style>
        body {
            background: #000 url(/images/catalog/auth/auth_bg.jpg);background-position: center center;background-size: cover;background-repeat: no-repeat;
        }

        body:after, body:before {
            background-repeat: no-repeat;background-size: contain;content: "";height: 100%;position: fixed;top: 0;
            z-index: -1;
        }

        body:before {
            background-image: url(/images/system/auth_bg_1.png);left: 0;max-height: inherit;width: 29.6%;
        }

        body:after {
            background-image: url(/images/system/auth_bg_2.png);background-position-x: right;right: 0;width: 25.7%;
        }

        @media (max-width: 900px) {
            body:before {
                display: none;
            }

            body:after {
                display: none;
            }
        }
    </style>
@endsection
@section('footer', $data['footer'])
