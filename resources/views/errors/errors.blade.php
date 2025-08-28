@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
@endsection
@section('content')
    <!-- Popular Posts -->
    <div class="container text-center my-5">
        <h1 class="ex_errors_page_title">{{ $data['code'] }}</h1>
        <h2 class="ex_errors_page_text">{{ __('errors/errors.text_one') }}</h2>
        <p class="ex_errors_page_text">{{ __('errors/errors.text_two') }}</p>
        <div class="d-flex align-items-center justify-content-center">
            <a href="/" class="ex_errors_page_btn">{{ __('errors/errors.button_text') }}</a>
        </div>
    </div>
    <!-- ./Popular Posts -->
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
