@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
@endsection
@section('content')
    <h1 style="display:none">{{ $data['title'] }}</h1>

    {{-- All Post Types and Other Blocks --}}
    @foreach($data['types'] as $type)
        @if($type['status'] && !empty($type['data']))
            @if($type['type'] == 'post')
                <div class="container">
                    <h2 class="ex_block_title">{{ __('catalog/page_titles.post_'.$type['key']) }}</h2>
                    <div class="row">
                        @foreach($type['data'] as $item)
                            {!! $item !!}
                        @endforeach
                    </div>
                    <div class="ex_block_center_button">
                        <a href="{{ $type['link'] }}" class="ex_block_center_btn">{{ __('catalog/posts/post.btn_show_all') }}</a>
                    </div>
                </div>
            @elseif($type['type'] == 'salon')
                <div class="container">
                    <h2 class="ex_block_title">{{ __('catalog/page_titles.post_'.$type['key']) }}</h2>
                    <div class="row">
                        @foreach($type['data'] as $item)
                            {!! $item !!}
                        @endforeach
                    </div>
                    <div class="ex_block_center_button">
                        <a href="{{ $type['link'] }}" class="ex_block_center_btn">{{ __('catalog/posts/post.btn_show_all') }}</a>
                    </div>
                </div>
            @elseif($type['type'] == 'news')
                <div class="container">
                    @vite('resources/catalog/css/blocks/news.css')
                    <h2 class="ex_block_title">{{ __('catalog/page_titles.post_'.$type['key']) }}</h2>
                    <div class="row">
                        @foreach($type['data'] as $item)
                            {!! $item !!}
                        @endforeach
                    </div>
                </div>
            @elseif($type['type'] == 'post_banner' || $type['type'] == 'banner')
                @if($type['type'] == 'post_banner')
                    @php
                        $banner_class = 'post_banner';
                    @endphp
                    @vite('resources/catalog/css/blocks/post_banner.css')
                    @vite('resources/catalog/css/blocks/post_banner_m.css')
                @elseif($type['type'] == 'banner')
                    @php
                        $banner_class = 'banner';
                    @endphp
                    @vite('resources/catalog/css/blocks/banner.css')
                @endif
                <div class="container ex_{{ $banner_class }}_block position-relative px-3">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
                    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
                    <div id="{{ ($type['type'] == 'post_banner') ? 'post_banner' : 'banner' }}" class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($type['data'] as $banner)
                                <div class="swiper-slide">{!! $banner !!}</div>
                            @endforeach
                        </div>
                        <!--<div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>-->
                        <div class="swiper-pagination"></div>
                    </div>
                    <script>
                        var swiper = new Swiper('#{{ ($type['type'] == 'post_banner') ? 'post_banner' : 'banner' }}', {
                            slidesPerView: {{ ($type['type'] == 'post_banner') ? 4 : 2 }},
                            spaceBetween: 30,
                            pagination: {el: '.swiper-pagination', clickable: true, dynamicBullets: true,},
                            //navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev',},
                            navigation: false,
                            loop: true,
                            //autoplay: {delay: 5000, disableOnInteraction: false,},
                            breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: {{ ($type['type'] == 'post_banner') ? 4 : 2 }}}}
                        });
                    </script>
                </div>
            @endif
        @endif
    @endforeach
    {{-- All Post Types and Other Blocks --}}

    @if(!empty($data['footer_text']))
        <div class="container">
            <div class="ex_indiv_page_block mt-3">
                <div class="ex_show_more_footer_text">
                    <div class="ex_show_more_footer_text_content">
                        {!! $data['footer_text'] !!}
                    </div>
                    <button class="btn main-btn-style ex_show_more_footer_toggle" id="ex_show_more_footer_toggle">{{ __('buttons.show_more') }}</button>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('ex_show_more_footer_toggle').addEventListener('click', function () {
                const textBlock = document.querySelector('.ex_show_more_footer_text');
                const isExpanded = textBlock.classList.toggle('expanded');

                if (isExpanded) {
                    this.textContent = '{{ __('buttons.hide') }}';
                } else {
                    this.textContent = '{{ __('buttons.show_more') }}';
                }
            });
        </script>
    @endif
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
