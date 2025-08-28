@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/auth.css')
@endsection
@section('content')
    <div class="container">
        <div class="ex_auth_page_block">
            <h1 class="ex_auth_page_title">{{ $data['title'] }}</h1>
            <div>{!! $data['text'] !!}</div>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
