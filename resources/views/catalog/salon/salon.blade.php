@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    @if($data['photo_count'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    @endif
    <link rel="stylesheet" href="{{ url('/catalog/lightgallery/css/lightgallery-bundle.css') }}"/>
    <script src="{{ url('/catalog/lightgallery/lightgallery.umd.js') }}"></script>
    @vite('resources/catalog/css/post.css')
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

    <div class="container">
        <h1 class="ex_post_page_title">{{ $data['title'] }}</h1>
        <div class="ex_post_page_block">
            <div class="row">
                <div id="lg-main-image" class="col-12 col-md-6 col-xl-4 mb-2">
                    <a href="{{ $data['big_main_image'] }}">
                        <img src="{{ $data['main_image'] }}" alt="{{ $data['title'] }}" class="ex_post_page_main_photo">
                    </a>
                </div>
                <div class="col-12 col-md-6 col-xl-8 mb-2">
                    <div class="ex_post_middle_info">
                        <div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/salon/salon.salon_call_time') }}</span><span>{{ $data['call_time'] }}</span></div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/salon/salon.salon_city') }}</span><span>{{ $data['city'] }}</span></div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/salon/salon.salon_metro') }}</span><span>{{ $data['metro'] }}</span></div>
                            <div class="ex_post_info_item"><span>{{ __('catalog/salon/salon.salon_zone') }}</span><span>{{ $data['zone'] }}</span></div>
                        </div>

                        <div>
                            <div class="ex_post_hourly_title">{{ __('catalog/salon/salon.day') }}</div>
                            <div class="ex_post_hourly_block">
                                <div class="ex_post_hourly_double">
                                    <div class="ex_post_hourly_light">
                                        <div class="ex_post_hourly_light_header">
                                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/salon/salon.day') }}" title="{{ __('catalog/salon/salon.day') }}" class="ex_post_hourly_light_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/salon/salon.item_hour_one_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/salon/salon.to_me') }}</span><strong>{{ $data['price_day_in_one'] }}</strong></div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/salon/salon.to_you') }}</span><strong>{{ $data['price_day_in_two'] }}</strong></div>
                                    </div>
                                    <div class="ex_post_hourly_light">
                                        <div class="ex_post_hourly_light_header">
                                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/salon/salon.day') }}" title="{{ __('catalog/salon/salon.day') }}" class="ex_post_hourly_light_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/salon/salon.item_hour_two_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/salon/salon.to_me') }}</span><strong>{{ $data['price_day_out_one'] }}</strong></div>
                                        <div class="ex_post_hourly_light_bottom"><span>{{ __('catalog/salon/salon.to_you') }}</span><strong>{{ $data['price_day_out_two'] }}</strong></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ex_post_hourly_title">{{ __('catalog/salon/salon.night') }}</div>
                            <div class="ex_post_hourly_block">
                                <div class="ex_post_hourly_double">
                                    <div class="ex_post_hourly_night">
                                        <div class="ex_post_hourly_night_header">
                                            <img src="{{ url('/images/icons/time/night_one.svg') }}" alt="{{ __('catalog/salon/salon.night') }}" title="{{ __('catalog/salon/salon.night') }}" class="ex_post_hourly_night_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/salon/salon.item_hour_one_s') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/salon/salon.to_me') }}</span><strong>{{ $data['price_night_in_one'] }}</strong></div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/salon/salon.to_you') }}</span><strong>{{ $data['price_night_in_night'] }}</strong></div>
                                    </div>
                                    <div class="ex_post_hourly_night">
                                        <div class="ex_post_hourly_night_header">
                                            <img src="{{ url('/images/icons/time/night_two.svg') }}" alt="{{ __('catalog/salon/salon.night') }}" title="{{ __('catalog/salon/salon.night') }}" class="ex_post_hourly_night_image">
                                            <div class="ex_post_hourly_hour">{{ __('catalog/salon/salon.item_hour_night') }}</div>
                                        </div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/salon/salon.to_me') }}</span><strong>{{ $data['price_night_out_one'] }}</strong></div>
                                        <div class="ex_post_hourly_night_bottom"><span>{{ __('catalog/salon/salon.to_you') }}</span><strong>{{ $data['price_night_out_night'] }}</strong></div>
                                    </div>
                                </div>
                            </div>

                            @if($data['express'])
                            <div class="ex_post_express">
                                <div class="ex_post_express_title">
                                    <img src="{{ url('/images/icons/time/express.svg') }}" alt="{{ __('catalog/salon/salon.item_hour_express') }}" title="{{ __('catalog/salon/salon.item_hour_express') }}" class="ex_post_express_image">
                                    <span>{{ __('catalog/salon/salon.item_hour_express') }}</span>
                                </div>
                                <div class="ex_post_express_price">{{ $data['express_price'] }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="ex_post_buttons_block">
                            <a href="tel:{{ $data['phone_format'] }}" class="ex_post_btn ex_post_btn_show_phone">
                                <span class="show-phone"><svg><use xlink:href="#icon-post-phone"></use></svg> {{ __('catalog/salon/salon.show_phone') }}</span>
                                <span class="phone-number d-none"><svg><use xlink:href="#icon-post-phone"></use></svg> {{ $data['phone'] }}</span>
                            </a>
                            <div class="ex_post_social">
                                @if($data['telegram'])<a href="{{ $data['telegram'] }}" target="_blank" onclick="transitionSocial('telegram');" class="ex_post_btn ex_post_btn_telg"><svg class="ex_post_btn_svg"><use xlink:href="#icon-telegram"></use></svg> Telegram</a>@endif
                                @if($data['whatsapp'])<a href="{{ $data['whatsapp'] }}" target="_blank" onclick="transitionSocial('whatsapp');" class="ex_post_btn ex_post_btn_whas"><svg class="ex_post_btn_svg"><use xlink:href="#icon-whatsapp"></use></svg> WhatsApp</a>@endif
                                @if($data['instagram'])<a href="{{ $data['instagram'] }}" target="_blank" onclick="transitionSocial('instagram');" class="ex_post_btn ex_post_btn_inst"><svg class="ex_post_btn_svg"><use xlink:href="#icon-instagram"></use></svg> Instagram</a>@endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @if($data['photo_count'])
            <div id="all_photo" class="ex_post_page_block" style="overflow: hidden">
                <div id="lg-salon-photos" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($data['content_data']['photo'] as $photo)
                            <div class="swiper-slide">
                                <a href="{{ $photo['big'] }}">
                                    <img class="lg-item ex_post_content_item_img" src="{{ $photo['small'] }}" alt="{{ $data['title'] }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <!--<div class="swiper-pagination"></div>-->
                </div>
                <script>
                    var swiper = new Swiper('#lg-salon-photos', {
                        slidesPerView: 6,
                        spaceBetween: 30,
                        navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                        loop: false,
                        breakpoints: {320: {slidesPerView: 2}, 640: {slidesPerView: 2}, 768: {slidesPerView: 2}, 1024: {slidesPerView: 6}}
                    });
                </script>
            </div>
        @endif

        <div class="ex_post_page_block">
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/salon/salon.salon_description') }}</div>
            <div>{!! $data['desc'] !!}</div>
        </div>

        @if(!empty($data['latitude']) && !empty($data['longitude']))
            <div class="ex_post_location_block ex_open_location">
                <button type="button" class="ex_post_location__btn">{{ __('catalog/salon/salon.salon_show_map') }}</button>
            </div>
            <div class="ex_post_page_block ex_location_block d-none">
                <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/salon/salon.salon_location') }}</div>
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
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
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

        var show_phone_url = '{{ route('service.salon.show.phone') }}';
        var salon_id = {{ $data['salon_id'] }};
        var token = '{{ csrf_token() }}';

        function showPhoneAjax() {
            $.ajax({
                url: show_phone_url,
                type: 'POST',
                data: {salon_id: salon_id, _token: token},
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lightGallery(document.getElementById('lg-main-image'), {
                speed: 500,
                plugins: [lgZoom, lgThumbnail],
                download: false
            });
            lightGallery(document.querySelector('#lg-salon-photos .swiper-wrapper'), {
                selector: 'a',
                speed: 500,
                plugins: [lgZoom, lgThumbnail],
                download: false
            });
        });
    </script>
    <script src="{{ url('/catalog/lightgallery/plugins/zoom/lg-zoom.umd.js') }}"></script>
    <script src="{{ url('/catalog/lightgallery/plugins/thumbnail/lg-thumbnail.umd.js') }}"></script>
@endsection
