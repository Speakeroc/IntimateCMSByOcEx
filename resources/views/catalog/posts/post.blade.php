@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ url('/catalog/lightgallery/css/lightgallery-bundle.css') }}"/>
    <script src="{{ url('/catalog/lightgallery/lightgallery.umd.js') }}"></script>
    @vite('resources/catalog/css/post.css')
    @vite('resources/catalog/css/post_m.css')
    @if(isset($data['microdata_article']) && !empty($data['microdata_article']))
        <script type="application/ld+json">{!! $data['microdata_article'] !!}</script>
    @endif
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

    @php
        if ($data['vip']) {
            $status_class = 'vip';
            $status_block = '<div class="ex_post_page_el_status vip">VIP</div>';
        }
        if ($data['diamond']) {
            $status_class = 'diamond';
            $status_block = '<div class="ex_post_page_el_status diamond">Diamond</div>';
        }
        if (!$data['diamond'] && !$data['vip']) {
            $status_class = '';
            $status_block = '';
        }
    @endphp

    <div class="container">
        <h1 class="ex_post_page_title">{{ $data['title'] }}{!! $status_block !!}</h1>
        <div class="ex_post_page_block">
            <div class="row">
                <div class="col-12 col-md-6 col-xl-4 mb-2 overflow-hidden">
                    <div id="lg-post-all-images" class="swiper-container position-relative">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="{{ $data['big_main_image'] }}">
                                    <img class="lg-item ex_post_content_item_img" src="{{ $data['main_image'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}" style="width:100%;height:570px;object-fit:cover">
                                </a>
                            </div>
                            @if($data['photo_count'])
                                @foreach($data['content_data']['photo'] as $photo)
                                    <div class="swiper-slide">
                                        <a href="{{ $photo['big'] }}">
                                            <img class="lg-item ex_post_content_item_img" src="{{ $photo['big'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}" style="width:100%;height:570px;object-fit:cover">
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                            @if($data['selfie_count'])
                                @foreach($data['content_data']['selfie'] as $selfie)
                                    <div class="swiper-slide">
                                        <a href="{{ $selfie['big'] }}">
                                            <img class="lg-item ex_post_content_item_img" src="{{ $selfie['big'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}" style="width:100%;height:570px;object-fit:cover">
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <!--<div class="swiper-pagination"></div>-->
                    </div>
                    <script>
                        var swiper = new Swiper('#lg-post-all-images', {
                            slidesPerView: 1,
                            spaceBetween: 30,
                            navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                            loop: false,
                            //breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: 6}}
                        });

                        document.addEventListener("DOMContentLoaded", function() {
                            lightGallery(document.querySelector('#lg-post-all-images .swiper-wrapper'), {
                                selector: 'a',
                                speed: 500,
                                plugins: [lgZoom, lgThumbnail],
                                download: false
                            });
                        });
                    </script>
                </div>
                <div class="col-12 col-md-6 col-xl-5 mb-2">
                    <div class="ex_post_middle_info">
                        <div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/posts/post.post_answering_to') }}</span><span>{{ $data['answering_to'] }}</span></div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/posts/post.post_call_time') }}</span><span>{{ $data['call_time'] }}</span></div>
                            <div class="ex_post_info_warning">{{ __('catalog/posts/post.post_notify') }}</div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/posts/post.post_city') }}</span><span><a href="{{ $data['city_link'] }}">{{ $data['city'] }}</a></span></div>
                            @if($data['post_display_metro'])<div class="ex_post_info_item"><span>{{ __('catalog/posts/post.post_metro') }}</span><span>{{ $data['metro'] }}</span></div>@endif
                            @if($data['post_display_zone'])<div class="ex_post_info_item"><span>{{ __('catalog/posts/post.post_zone') }}</span><span>{{ $data['zone'] }}</span></div>@endif
                        </div>

                        <div class="ex_post_hourly">
                            <div class="ex_post_hourly_title">{{ __('catalog/posts/post.day') }}</div>
                            <div class="ex_post_hourly_block">
                                <div class="ex_post_hourly_double">
                                    <div class="ex_post_hourly_light">
                                        <div class="ex_post_hourly_light_header">
                                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/posts/post.day') }}" title="{{ __('catalog/posts/post.day') }}" class="ex_post_hourly_light_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/posts/post.item_hour_one_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/posts/post.to_me') }}</span><strong>{{ $data['price_day_in_one'] }}</strong></div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/posts/post.to_you') }}</span><strong>{{ $data['price_day_in_two'] }}</strong></div>
                                    </div>
                                    <div class="ex_post_hourly_light">
                                        <div class="ex_post_hourly_light_header">
                                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/posts/post.day') }}" title="{{ __('catalog/posts/post.day') }}" class="ex_post_hourly_light_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/posts/post.item_hour_two_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/posts/post.to_me') }}</span><strong>{{ $data['price_day_out_one'] }}</strong></div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/posts/post.to_you') }}</span><strong>{{ $data['price_day_out_two'] }}</strong></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ex_post_hourly_title">{{ __('catalog/posts/post.night') }}</div>
                            <div class="ex_post_hourly_block">
                                <div class="ex_post_hourly_double">
                                    <div class="ex_post_hourly_night">
                                        <div class="ex_post_hourly_night_header">
                                            <img src="{{ url('/images/icons/time/night_one.svg') }}" alt="{{ __('catalog/posts/post.item_hour_one_s') }}" title="{{ __('catalog/posts/post.item_hour_one_s') }}" class="ex_post_hourly_night_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/posts/post.item_hour_one_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/posts/post.to_me') }}</span><strong>{{ $data['price_night_in_one'] }}</strong></div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/posts/post.to_you') }}</span><strong>{{ $data['price_night_in_night'] }}</strong></div>
                                    </div>
                                    <div class="ex_post_hourly_night">
                                        <div class="ex_post_hourly_night_header">
                                            <img src="{{ url('/images/icons/time/night_two.svg') }}" alt="{{ __('catalog/posts/post.item_hour_night') }}" title="{{ __('catalog/posts/post.item_hour_night') }}" class="ex_post_hourly_night_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/posts/post.item_hour_night') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/posts/post.to_me') }}</span><strong>{{ $data['price_night_out_one'] }}</strong></div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/posts/post.to_you') }}</span><strong>{{ $data['price_night_out_night'] }}</strong></div>
                                    </div>
                                </div>
                            </div>

                            @if($data['express'])
                            <div class="ex_post_express">
                                <div class="ex_post_express_title">
                                    <img src="{{ url('/images/icons/time/express.svg') }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}" class="ex_post_express_image">
                                    <span>{{ __('catalog/posts/post.item_hour_express') }}</span>
                                </div>
                                <div class="ex_post_express_price">{{ $data['express_price'] }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="ex_post_buttons_block">
                            <a href="tel:{{ $data['phone_format'] }}" class="ex_post_btn ex_post_btn_show_phone">
                                <span class="show-phone"><svg><use xlink:href="#icon-post-phone"></use></svg> {{ __('catalog/posts/post.show_phone') }}</span>
                                <span class="phone-number d-none"><svg><use xlink:href="#icon-post-phone"></use></svg> {{ $data['phone'] }}</span>
                            </a>
                            <div class="ex_post_social">
                                @if($data['telegram'])<a href="{{ $data['telegram'] }}" target="_blank" onclick="transitionSocial('telegram');" class="ex_post_btn ex_post_btn_telg"><svg class="ex_post_btn_svg"><use xlink:href="#icon-telegram"></use></svg> Telegram</a>@endif
                                @if($data['whatsapp'])<a href="{{ $data['whatsapp'] }}" target="_blank" onclick="transitionSocial('whatsapp');" class="ex_post_btn ex_post_btn_whas"><svg class="ex_post_btn_svg"><use xlink:href="#icon-whatsapp"></use></svg> WhatsApp</a>@endif
                                @if($data['instagram'])<a href="{{ $data['instagram'] }}" target="_blank" onclick="transitionSocial('instagram');" class="ex_post_btn ex_post_btn_inst"><svg class="ex_post_btn_svg"><use xlink:href="#icon-instagram"></use></svg> Instagram</a>@endif
                                @if($data['polee'])<a href="{{ $data['polee'] }}" target="_blank" onclick="transitionSocial('polee');" class="ex_post_btn ex_post_btn_pole"><svg class="ex_post_btn_svg"><use xlink:href="#icon-polee"></use></svg> Polee</a>@endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="ex_post_end_block">
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.age') }}</span><span class="ex_post_end_value">{{ $data['age'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.height') }}</span><span class="ex_post_end_value">{{ $data['height'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.weight') }}</span><span class="ex_post_end_value">{{ $data['weight'] }}</span></div>
                        @if($data['post_display_cloth'])<div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.cloth') }}</span><span class="ex_post_end_value">{{ $data['cloth'] }}</span></div>@endif
                        @if($data['post_display_shoes'])<div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.shoes') }}</span><span class="ex_post_end_value">{{ $data['shoes'] }}</span></div>@endif
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.breast') }}</span><span class="ex_post_end_value">{{ $data['breast'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.nationality') }}</span><span class="ex_post_end_value">{{ $data['nationality'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.body_type') }}</span><span class="ex_post_end_value">{{ $data['body_type'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.hair_color') }}</span><span class="ex_post_end_value">{{ $data['hair_color'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.hairy') }}</span><span class="ex_post_end_value">{{ $data['hairy'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.post_client_age_min') }}</span><span class="ex_post_end_value">{{ $data['client_age_min'] }}</span></div>
                        <div class="ex_post_end_item"><span class="ex_post_end_title">{{ __('catalog/posts/post.post_client_age_max') }}</span><span class="ex_post_end_value">{{ $data['client_age_max'] }}</span></div>
                    </div>
                    <br>
                    <div class="ex_post_end_place_block">
                        <div class="ex_post_end_place_title mb-2">{{ __('catalog/posts/post.post_visit_place') }}</div>
                        <div class="row">
                            @foreach($data['visit_place'] as $visit_place)
                                <div class="col-6">
                                    <div class="ex_post_end_place_item"><svg class="ex_post_end_place_svg"><use xlink:href="#icon-post-{{ ($visit_place['check']) ? '' : 'no-' }}check-circle"></use></svg><span>{{ $visit_place['title'] }}</span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($data['photo_count'] || $data['selfie_count'] || $data['video_count'])
            <div class="ex_post_tabs_nav">
                @if($data['photo_count'])
                    <div class="ex_post_tabs_nav_item active" data-to-content-tab="all_photo">{{ __('catalog/posts/post.photo') }}</div>
                @endif
                @if($data['selfie_count'])
                    <div class="ex_post_tabs_nav_item {{ (!$data['photo_count']) ? 'active' : '' }}" data-to-content-tab="all_selfie">{{ __('catalog/posts/post.selfie') }}</div>
                @endif
                @if($data['video_count'])
                    <div class="ex_post_tabs_nav_item {{ (!$data['photo_count'] && !$data['selfie_count']) ? 'active' : '' }}" data-to-content-tab="all_video">{{ __('catalog/posts/post.video') }}</div>
                @endif
            </div>
        @endif

        @if($data['photo_count'])
            <div id="all_photo" class="ex_post_page_block ex_post_content_block" style="overflow: hidden">
                <div id="lg-post-photos" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($data['content_data']['photo'] as $photo)
                            <div class="swiper-slide">
                                <a href="{{ $photo['big'] }}">
                                    <img class="lg-item ex_post_content_item_img" src="{{ $photo['small'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!--<div class="swiper-pagination"></div>-->
                </div>
                <script>
                    var swiper = new Swiper('#lg-post-photos', {
                        slidesPerView: 6,
                        spaceBetween: 30,
                        navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                        loop: false,
                        breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: 6}}
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        lightGallery(document.querySelector('#lg-post-photos .swiper-wrapper'), {
                            selector: 'a',
                            speed: 500,
                            plugins: [lgZoom, lgThumbnail],
                            download: false
                        });
                    });
                </script>
            </div>
        @endif
        @if($data['selfie_count'])
            <div id="all_selfie" class="ex_post_page_block ex_post_content_block {{ (!$data['photo_count']) ? '' : 'd-none' }}" style="overflow: hidden">
                <div id="lg-post-selfies" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($data['content_data']['selfie'] as $selfie)
                            <div class="swiper-slide">
                                <a href="{{ $selfie['big'] }}">
                                    <img class="lg-item ex_post_content_item_img" src="{{ $selfie['small'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!--<div class="swiper-pagination"></div>-->
                </div>
                <script>
                    var swiper = new Swiper('#lg-post-selfies', {
                        slidesPerView: 6,
                        spaceBetween: 30,
                        navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                        loop: false,
                        breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: 6}}
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        lightGallery(document.querySelector('#lg-post-selfies .swiper-wrapper'), {
                            selector: 'a',
                            speed: 500,
                            plugins: [lgZoom, lgThumbnail],
                            download: false
                        });
                    });
                </script>
            </div>
        @endif
        @if($data['video_count'])
            <div id="all_video" class="ex_post_page_block ex_post_content_block {{ (!$data['photo_count'] && !$data['selfie_count']) ? '' : 'd-none' }}" style="overflow: hidden">
                <div id="lg-post-videos" class="swiper-container">
                    <div class="swiper-wrapper">
                        @php $video_count = 1; @endphp
                        @foreach($data['content_data']['video'] as $video)
                            <div class="swiper-slide">
                                <a data-video='{"source": [{"src":"{{ $video['big'] }}", "type":"video/mp4"}], "tracks": [{"src": "{/videos/title.txt", "kind":"captions", "srclang": "en", "label": "English", "default": "true"}], "attributes": {"preload": false, "playsinline": true, "controls": true}}'>
                                    <svg class="ex_post_video_icon"><use xlink:href="#icon-post-video-icon"></use></svg>
                                    <span class="ex_post_video_number">#{{ $video_count }}</span>
                                    <video class="ex_post_content_item_video">
                                        <source src="{{ $video['small'] }}" type="video/mp4">
                                    </video>
                                </a>
                            </div>
                            @php $video_count++; @endphp
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!--<div class="swiper-pagination"></div>-->
                </div>
                <script>
                    var swiper = new Swiper('#lg-post-videos', {
                        slidesPerView: 6,
                        spaceBetween: 30,
                        navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                        loop: false,
                        breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: 6}}
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        lightGallery(document.querySelector('#lg-post-videos .swiper-wrapper'), {
                            selector: 'a',
                            speed: 500,
                            plugins: [lgVideo],
                            videojs: true,
                            download: false
                        });
                    });
                </script>
            </div>
        @endif

        @if($data['photo_count'] || $data['selfie_count'] || $data['video_count'])
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const tabs = document.querySelectorAll(".ex_post_tabs_nav_item");
                    const contentBlocks = document.querySelectorAll(".ex_post_content_block");
                    tabs.forEach(tab => {
                        tab.addEventListener("click", function () {
                            tabs.forEach(item => item.classList.remove("active"));
                            this.classList.add("active");
                            contentBlocks.forEach(block => block.classList.add("d-none"));
                            const target = document.getElementById(this.dataset.toContentTab);
                            if (target) {
                                target.classList.remove("d-none");
                            }
                        });
                    });
                });
            </script>
        @endif

        <div class="ex_post_page_block">
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_description') }}</div>
            <div>{!! $data['desc'] !!}</div>
        </div>

        @if(!empty($data['latitude']) && !empty($data['longitude']))
            <div class="ex_post_location_block ex_open_location">
                <button type="button" class="ex_post_location__btn">{{ __('catalog/posts/post.post_show_map') }}</button>
            </div>
            <div class="ex_post_page_block ex_location_block d-none">
                <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_location') }}</div>
                <div id="map" style="height: 300px;overflow: hidden;border-radius: 15px;"></div>
                @php $latitude = $data['latitude'] ?? null; @endphp
                @php $longitude = $data['longitude'] ?? null; @endphp
                <script>
                    function showMap() {
                        var defaultCityId = '1';
                        var defaultZoom = 12;
                        var initialCoords = [{{ $latitude }}, {{ $longitude }}];
                        var map = L.map('map').setView(initialCoords, defaultZoom);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OcEx.Dev'
                        }).addTo(map);
                        var marker;
                        marker = L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
                    }
                    document.querySelector('.ex_post_location__btn').addEventListener('click', function () {
                        const openLocation = document.querySelector('.ex_open_location');
                        if (openLocation) {
                            openLocation.remove();
                        }
                        const locationBlock = document.querySelector('.ex_location_block');
                        if (locationBlock) {
                            locationBlock.classList.remove('d-none');
                        }
                        showMap();
                    });
                </script>
            </div>
        @endif

        <div class="ex_post_page_block">
            <div class="row">
                <div class="col-sm-6 col-12 mb-4">
                    <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_services_for') }}</div>
                    @foreach($data['services_for'] as $item)
                        <div class="ex_post_inline_item"><svg class="ex_post_inline_svg"><use xlink:href="#icon-post-{{ ($item['check']) ? '' : 'no-' }}check-circle"></use></svg><span>{{ $item['title'] }}</span></div>
                    @endforeach
                </div>
                <div class="col-sm-6 col-12 mb-4">
                    <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_body_art') }}</div>
                    @foreach($data['body_art'] as $item)
                        <div class="ex_post_inline_item"><svg class="ex_post_inline_svg"><use xlink:href="#icon-post-{{ ($item['check']) ? '' : 'no-' }}check-circle"></use></svg><span>{{ $item['title'] }}</span></div>
                    @endforeach
                </div>
                @if(!empty($data['language_skills']))
                    <div class="col-12 mb-4">
                        <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_language_skills') }}</div>
                        @foreach($data['language_skills'] as $item)
                            <div class="ex_post_inline_item"><svg class="ex_post_inline_svg"><use xlink:href="#icon-post-check-circle"></use></svg><span>{{ $item['title'] }}</span></div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="ex_post_page_block">
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_services') }}</div>
            <div class="ex_services_info_block">
                @for($s_info = 1; $s_info <= 4; $s_info++)
                    <div class="ex_services_info_item"><svg class="ex_service_block_svg"><use xlink:href="#icon-post-service-{{ $s_info }}"></use></svg> - {{ __('catalog/posts/post.services_type_' . $s_info) }}</div>
                @endfor
            </div>
            <div class="row">
                @foreach($data['app_new_services'] as $service)
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="ex_services_block">
                            <div class="ex_services_block_title">{{ $service['title'] }}</div>
                            <div class="ex_services_block_items">
                                @foreach($service['data'] as $item)
                                    @php $service_condition = $data['services'][$item['id']]['condition'] ?? null; @endphp
                                    @php $service_description = $data['services'][$item['id']]['description'] ?? null; @endphp
                                    @php $service_price = $data['services'][$item['id']]['price'] ?? null; @endphp
                                    <div class="ex_service_block">
                                        <div class="ex_service_block_main">
                                            <div class="ex_service_block_svg_title">
                                                <svg class="ex_service_block_svg"><use xlink:href="#icon-post-service-{{ $service_condition }}"></use></svg>
                                                <div class="ex_service_block_title"><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></div>
                                            </div>
                                            @if(!empty($service_price))
                                            <div class="ex_service_block_price">{{ $service_price.$data['currency_symbol'] }}</div>
                                            @endif
                                        </div>
                                        @if(!empty($service_description))
                                            <div class="ex_service_block_desc">{{ $service_description }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ex_post_page_block">
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_review') }}</div>
            @if(empty($data['reviews']))
                <div class="d-flex justify-content-center align-items-center my-3">{{ __('catalog/posts/post.reviews_empty') }}</div>
            @else
                @foreach($data['reviews'] as $item)
                    <div class="ex_post_review_item">
                        <div class="ex_post_review_item_header">
                            <div class="ex_post_review_item_phone">{{ $item['user'] }}</div>
                            <div class="ex_post_review_item_rating">
                                <i class="fa{{ ($item['rating'] >= 1) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                <i class="fa{{ ($item['rating'] >= 2) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                <i class="fa{{ ($item['rating'] >= 3) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                <i class="fa{{ ($item['rating'] >= 4) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                <i class="fa{{ ($item['rating'] >= 5) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                            </div>
                        </div>
                        <div class="ex_post_review_item_content">{{ $item['text'] }}</div>
                    </div>
                @endforeach
            @endif
            <form id="writeReview" class="mb-3">
                @csrf
                <input type="hidden" name="post_id" value="{{ $data['post_id'] }}">
                <div class="mb-3">
                    <label for="textarea-text" class="form-label">{{ __('catalog/posts/post.post_you_review') }}</label>
                    <textarea class="form-control" name="review" id="textarea-text" rows="5" placeholder="{{ __('catalog/posts/post.post_you_review') }}"></textarea>
                </div>
                <div class="mb-3 text-center">
                    <div class="ex_set_rating_list">
                        @for($rating = 1;$rating <= 5;$rating++)
                            <input type="radio" name="rating" id="input-rating-{{ $rating }}" value="{{ $rating }}" class="d-none">
                            <label for="input-rating-{{ $rating }}" class="cursor-pointer"><i class="far fa-star" data-rating="{{ $rating }}"></i></label>
                        @endfor
                    </div>
                </div>
                <button class="btn btn-sm btn-danger ex_post_review_btn" type="button" id="writeReviewBtn">{{ __('catalog/posts/post.post_add_review') }}</button>
            </form>

            <script>
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
                    });
                    $('#writeReview').on('submit', function (e) {
                        e.preventDefault();
                        let form = this;

                        $.ajax({
                            url: "{{ route('client.post.review.create') }}",
                            method: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                if (response.status === 'success') {
                                    kbNotify('success', response.message);
                                    form.reset();
                                }
                                if (response.status === 'error') {
                                    kbNotify('error', response.message);
                                }
                            }
                        });
                    });
                    $('#writeReviewBtn').on('click', function () {
                        $('#writeReview').submit();
                    });

                    $('input[name="rating"]').on('change', function () {
                        const selectedRating = parseInt($(this).val());
                        $('.ex_set_rating_list i.fa-star').removeClass('fas far ex_post_review_danger ex_post_review_warning ex_post_review_success');
                        $('.ex_set_rating_list i.fa-star').addClass('far');
                        $('.ex_set_rating_list i.fa-star').each(function () {
                            const starRating = parseInt($(this).data('rating'));

                            if (starRating <= selectedRating) {
                                $(this).removeClass('far').addClass('fas');

                                // Добавление класса стиля
                                if (selectedRating >= 1 && selectedRating <= 2) {
                                    $(this).addClass('ex_post_review_danger');
                                } else if (selectedRating >= 3 && selectedRating <= 4) {
                                    $(this).addClass('ex_post_review_warning');
                                } else if (selectedRating >= 5) {
                                    $(this).addClass('ex_post_review_success');
                                }
                            }
                        });
                    });
                });
            </script>
        </div>

        @if(!empty($data['tags']))
        <div class="ex_post_page_block">
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.post_tags') }}</div>
            <div class="ex_all_tags_list">
                @foreach($data['tags'] as $item)
                    <a href="{{ $item['link'] }}" target="_blank" class="ex_all_tags_item m-0">#{{ $item['tag'] }}</a>
                @endforeach
            </div>
        </div>
        @endif

        <div id="services_block">
            {!! $data['post_services_buy'] !!}
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <div class="modal fade" id="delete_post" tabindex="-1" aria-labelledby="delete_post_Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ex-modal-content">
                <div class="modal-header ex-modal-header">
                    <h2 class="modal-title fs-5" id="delete_post_Label">{{ __('catalog/posts/post.post_delete') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>{{ __('catalog/posts/post.post_delete_text') }}</span>
                    <div class="input-group mt-3">
                        <input type="text" id="input-delete-code" class="form-control" placeholder="{{ __('catalog/posts/post.post_delete_plece') }}">
                        <button type="button" id="btn-active" onclick="delete_post($('#input-delete-code').val())" class="btn btn-warning text-black fw-bold" style="border:1px solid #303030;border-left:none;">{{ __('buttons.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function delete_post(value) {
            if (value !== '') {
                $.ajax({
                    url: "{{ route('services.deletePost', ['id' => $data['post_id']]) }}",
                    method: "POST",
                    data: {deleteCode: value},
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            setTimeout(function () {
                                location.href = "/";
                            }, 3000);
                        } else if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    },
                    error: function () {
                        kbNotify('error', 'Произошла ошибка при удалении.');
                    }
                });
            }
        }
    </script>

    <script>
        function getPostServiceInfo(postId) {
            $.ajax({
                url: "{{ route('client.post.services') }}",
                method: "POST",
                data: {id: postId},
                success: function (response) {
                    if (response.status === 'success') {
                        $('#services_block').html(response.view);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postActivation(days, postId) {
            $.ajax({
                url: "{{ route('client.post.service.activationDays') }}",
                method: "POST",
                data: {days: days, post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getPostServiceInfo(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postUpToTop(postId) {
            $.ajax({
                url: "{{ route('client.post.service.upToTop') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getPostServiceInfo(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceDiamond(postId) {
            $.ajax({
                url: "{{ route('client.post.service.diamond') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getPostServiceInfo(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceVip(postId) {
            $.ajax({
                url: "{{ route('client.post.service.vip') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getPostServiceInfo(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceColor(postId) {
            $.ajax({
                url: "{{ route('client.post.service.color') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getPostServiceInfo(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }
    </script>

    <script>
        document.querySelectorAll('.ex_post_btn_show_phone').forEach(button => {
            let clicked = false;
            button.addEventListener('click', function (event) {
                if (!clicked) {
                    event.preventDefault();
                    const showPhone = this.querySelector('.show-phone');
                    const phoneNumber = this.querySelector('.phone-number');
                    showPhone.classList.add('d-none');
                    phoneNumber.classList.remove('d-none');
                    showPhoneAjax();
                    clicked = true;
                }
            });
        });

        var show_phone_url = '{{ route('service.show.phone') }}';
        var transition_social_url = '{{ route('service.transition.social') }}';
        var post_id = {{ $data['post_id'] }};
        var token = '{{ csrf_token() }}';

        function showPhoneAjax() {
            $.ajax({
                url: show_phone_url,
                type: 'POST',
                data: {post_id: post_id, _token: token},
            });
        }

        function transitionSocial(type) {
            $.ajax({
                url: transition_social_url,
                type: 'POST',
                data: {post_id: post_id, type: type, _token: token},
            });
        }
    </script>
    <script src="{{ url('/catalog/lightgallery/plugins/zoom/lg-zoom.umd.js') }}"></script>
    <script src="{{ url('/catalog/lightgallery/plugins/thumbnail/lg-thumbnail.umd.js') }}"></script>
    <script src="{{ url('/catalog/lightgallery/plugins/video/lg-video.umd.js') }}"></script>
    @vite('resources/catalog/js/posts.js')
@endsection
