@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @if(isset($data['microdata_article']) && !empty($data['microdata_article']))
        <script type="application/ld+json">{!! $data['microdata_article'] !!}</script>
    @endif
    @vite('resources/catalog/css/information.css')
@endsection
@section('content')
    <div class="container">
        @if(isset($data['breadcrumb']['breadcrumb']) && !empty($data['breadcrumb']['breadcrumb']))
            <nav aria-label="ex_breadcrumb">
                <ol class="ex_breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    @foreach($data['breadcrumb']['breadcrumb'] as $breadcrumb)
                        <li class="ex_breadcrumb-item @if($loop->last) active @endif" itemscope itemtype="http://schema.org/ListItem">
                            <a href="{{ $breadcrumb['link'] }}" itemprop="item">
                                <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                            </a>
                            <meta itemprop="position" content="{{ $breadcrumb['pos'] }}" />
                        </li>
                    @endforeach
                </ol>
            </nav>
            @if(isset($data['breadcrumb']['list']) && !empty($data['breadcrumb']['list']))
                <script type="application/ld+json">{!! $data['breadcrumb']['list'] !!}</script>
            @endif
        @endif
    </div>

    <div class="container">
        @if(isset($data['h1']) && !empty($data['h1']))
            <h1 class="ex_information_page_title">{{ $data['h1'] }}</h1>
        @else
            <h1 class="ex_information_page_title">{{ $data['title'] }}</h1>
        @endif

        <div class="ex_information_page_block">
            <p>{{ __('catalog/info/contact.info_text') }}</p>
            <ul>
            <li>{{ __('catalog/info/contact.var_stemp_1') }}</li>
            <li>{{ __('catalog/info/contact.var_stemp_2') }}</li>
            <li>{{ __('catalog/info/contact.var_stemp_3') }}</li>
            <li>{{ __('catalog/info/contact.var_stemp_4') }}</li>
            </ul>
            <p>{{ __('catalog/info/contact.pre_form_text') }}</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    kbNotify('danger', '{!! $error !!}')
                </script>
            @endforeach
        @endif

        <div class="ex_information_page_block ex_contact_page_main_block">
            <form action="{{ route('client.contact') }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $data['user_id'] }}" class="form-control">
                <div class="mb-2">
                    <label for="input-theme" class="form-label">{{ __('catalog/info/contact.form_theme') }}</label>
                    <input type="text" name="form_theme" id="input-theme" value="{{ old('form_theme') }}" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="input-name" class="form-label">{{ __('catalog/info/contact.form_name') }}</label>
                    <input type="text" name="form_name" id="input-name" value="{{ old('form_name') ?? $data['user_name'] }}" class="form-control {{ ($data['user_name']) ? 'form-disabled' : '' }}">
                </div>
                <div class="col-12 mb-2">
                    <label for="input-email" class="form-label">{{ __('catalog/info/contact.form_email') }}</label>
                    <input type="email" name="form_email" id="input-email" value="{{ old('form_email') }}" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="input-message" class="form-label">{{ __('catalog/info/contact.form_message') }}</label>
                    <textarea name="form_message" class="form-control" id="input-message" rows="7">{{ old('form_message') }}</textarea>
                </div>
                <button type="submit" class="ex_contant_btn_submit">{{ __('catalog/info/contact.form_submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
