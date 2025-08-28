@extends('catalog.layout.layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/inputMask/jquery.inputmask.min.js') }}"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    @vite('resources/catalog/js/post_created.js')
    @vite('resources/catalog/css/id/post_created.css')
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
                            <meta itemprop="position" content="1"/>
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
            <form action="{{ route('client.auth.salon.create') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="random_id" id="input-random-id" value="{{ old('random_id') ?? rand(100000, 9999999) }}">
                <div class="d-flex justify-content-end">
                    <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary main-btn-style"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                </div>
                <h3 class="block-title">{{ __('admin/salon/salon.block_main') }}</h3>
                <div class="row">
                    <div class="col-sm-3 col-12">
                        <!-- Photo And Parameters -->
                        <div class="d-flex justify-content-center position-relative mb-2">
                            <div id="upload-container-main" class="upload-container" style="display: {{ old('images.main') ? 'none' : 'flex' }}">
                                <label for="image-upload-main" class="upload-placeholder"><i class="fas fa-upload"></i> {{ __('buttons.upload_image') }}</label>
                                <svg class="ex_upload_preview"><use xlink:href="#icon-preview-image"></use></svg>
                                <input type="file" id="image-upload-main" name="image_main" accept="{{ $data['main_data']['file_format_main_photo'] }}" style="display:none;" onchange="handleUploadImage(event, 'main')">
                            </div>
                            <div id="preview-container-main" class="preview-container" style="display: {{ old('images.main') ? 'flex' : 'none' }};">
                                <img id="preview-image-main" src="{{ old('images.main') }}" alt="Preview" class="preview-img"/>
                                <button type="button" class="delete-preview" onclick="deletePreviewImage('main')"><i class="fa fa-trash"></i> {{ __('buttons.delete') }}</button>
                            </div>
                            <div class="preload-container">
                                <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                </svg>
                            </div>
                            <input type="hidden" name="images[main]" id="image-path-main" value="{{ old('images.main') }}">
                        </div>
                        <!-- //Photo And Parameters -->
                    </div>
                    <div class="col-sm-9 col-12">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <!-- Title -->
                                <div class="mb-2">
                                    <label class="form-label" for="input-title">{{ __('admin/salon/salon.title') }}</label>
                                    <input type="text" class="form-control" id="input-title" name="title" value="{{ old('title') }}" placeholder="{{ __('admin/salon/salon.title_p') }}">
                                </div>
                                <!-- //Title -->

                                <!-- City -->
                                <div class="mb-2">
                                    <label class="form-label" for="select-city">{{ __('admin/salon/salon.city') }}</label>
                                    <select class="form-select" id="select-city" name="city_id">
                                        @foreach($data['main_data']['city'] as $city)
                                            <option value="{{ $city['id'] }}" {{ (old('city_id') == $city['id']) ? 'selected' : '' }}>{{ $city['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- //City -->

                                <!-- Address -->
                                <div class="mb-2">
                                    <label class="form-label" for="input-address">{{ __('admin/salon/salon.address') }}</label>
                                    <input type="text" class="form-control" id="input-address" name="address" value="{{ old('address') }}" placeholder="{{ __('admin/salon/salon.address_p') }}">
                                </div>
                                <!-- //Address -->

                                <!-- Phone -->
                                <div class="mb-2">
                                    <label class="form-label" for="input-phone">{{ __('admin/salon/salon.phone') }}</label>
                                    <input type="text" class="form-control phone-mask" id="input-phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('admin/salon/salon.phone') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label" for="input-phone-one">{{ __('admin/salon/salon.phone_one') }}</label>
                                    <input type="text" class="form-control phone-mask" id="input-phone-one" name="phone_one" value="{{ old('phone_one') }}" placeholder="{{ __('admin/salon/salon.phone_one') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label" for="input-phone-two">{{ __('admin/salon/salon.phone_two') }}</label>
                                    <input type="text" class="form-control phone-mask" id="input-phone-two" name="phone_two" value="{{ old('phone_two') }}" placeholder="{{ __('admin/salon/salon.phone_two') }}">
                                </div>
                                <!-- //Phone -->
                            </div>
                            <div class="col-sm-6 col-12">
                                <!-- Work Time -->
                                <div class="mb-2">
                                    <label class="form-label" for="input-work-time">{{ __('admin/salon/salon.work_time') }}</label>
                                    <div class="space-y-2 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="input-call-time-type-one" name="work_time_type" value="1" {{ (old('work_time_type') == 1) ? 'checked' : '' }}{{ (!old('work_time_type')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="input-call-time-type-one">{{ __('admin/salon/salon.work_time_hours') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="input-call-time-type-two" name="work_time_type" value="2" {{ (old('work_time_type') == 2) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="input-call-time-type-two">{{ __('admin/salon/salon.work_time_to_time') }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-select" id="select-call-time-from" name="work_time[time_from]">
                                                @for ($i = 0; $i <= 24; $i++)
                                                    <option value="{{ $i }}" {{ (old('work_time.time_from') == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select" id="select-call-time-to" name="work_time[time_to]">
                                                @for ($i = 0; $i <= 24; $i++)
                                                    <option value="{{ $i }}" {{ (old('work_time.time_to') == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- //Work Time -->

                                <!-- Delete code -->
                                <div class="mb-2">
                                    <label class="form-label mb-0" for="input-delete-code">{{ __('admin/salon/salon.delete_code') }}</label>
                                    <p class="mb-0 fs-14 mb-2">{{ __('admin/salon/salon.delete_info') }}</p>
                                    <input type="text" class="form-control" id="input-delete-code" name="delete_code" value="{{ old('delete_code') }}" placeholder="{{ __('admin/salon/salon.delete_place') }}">
                                </div>
                                <!-- //Delete code -->
                            </div>
                        </div>
                        <hr class="my-1">

                        <!-- Messengers -->
                        <label class="form-label" for="input-messengers">{{ __('admin/salon/salon.messengers') }}</label>
                        <div class="row">
                            <div class="col-sm-4 col-12">
                                <!-- Telegram -->
                                @php $telegram_status = old('messengers.telegram.status') ?? null; @endphp
                                @php $telegram_type = old('messengers.telegram.type') ?? null; @endphp
                                @php $telegram_content = old('messengers.telegram.content') ?? null; @endphp
                                <div class="mess_block mb_telegram">
                                    <div class="mess_block_top">
                                        <div class="mess_block_title"><img src="{{ url('images/icons/telegram.svg') }}" alt="{{ __('admin/salon/salon.telegram') }}"> {{ __('admin/salon/salon.telegram') }}</div>
                                        <div class="mess_block_radio">
                                            <div class="btn-group bg-white" role="group" aria-label="Radio button group">
                                                <input type="radio" class="btn-check" name="messengers[telegram][status]" value="1" id="radio-telegram-status-1" {{ ($telegram_status == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-primary" for="radio-telegram-status-1">Да</label>
                                                <input type="radio" class="btn-check" name="messengers[telegram][status]" value="0" id="radio-telegram-status-0" {{ ($telegram_status == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-danger" for="radio-telegram-status-0">Нет</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="telegram" class="mess_block_content" style="display: {{ $telegram_status ? 'block' : 'none' }};">
                                        <div class="space-x-2 mt-2">
                                            <div class="form-check form-check-inline ms-0 me-2">
                                                <input class="form-check-input" type="radio" id="telegram-type-link" name="messengers[telegram][type]" value="link" {{ ($telegram_type == 'link') ? 'checked' : '' }}{{ (!$telegram_type) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="telegram-type-link">{{ __('admin/salon/salon.telegram_link') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline ms-0 me-2">
                                                <input class="form-check-input" type="radio" id="telegram-type-login" name="messengers[telegram][type]" value="login" {{ ($telegram_type == 'login') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="telegram-type-login">{{ __('admin/salon/salon.telegram_login') }}</label>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control mt-2" id="input-telegram-content" name="messengers[telegram][content]" value="{{ $telegram_content }}" placeholder="">
                                    </div>
                                    <script>
                                        document.querySelectorAll('input[name="messengers[telegram][status]"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                var telegramBlock = document.getElementById('telegram');
                                                telegramBlock.style.display = (document.getElementById('radio-telegram-status-1').checked) ? 'block' : 'none';
                                            });
                                        });
                                        function updatePlaceholder() {
                                            var inputField = document.getElementById('input-telegram-content');
                                            var selectedOption = document.querySelector('input[name="messengers[telegram][type]"]:checked').value;
                                            if (selectedOption === 'link') {
                                                inputField.placeholder = 'https://t.me/intimatecms';
                                            } else if (selectedOption === 'login') {
                                                inputField.placeholder = 'intimatecms';
                                            }
                                        }
                                        document.querySelectorAll('input[name="messengers[telegram][type]"]').forEach(function(radio) {
                                            radio.addEventListener('change', updatePlaceholder);
                                        });
                                        window.addEventListener('load', updatePlaceholder);
                                    </script>
                                </div>
                                <!-- //Telegram -->
                            </div>
                            <div class="col-sm-4 col-12">
                                <!-- Whatsapp -->
                                @php $whatsapp_status = old('messengers.whatsapp.status') ?? null; @endphp
                                @php $whatsapp_content = old('messengers.whatsapp.content') ?? null; @endphp
                                <div class="mess_block mb_whatsapp">
                                    <div class="mess_block_top">
                                        <div class="mess_block_title"><img src="{{ url('images/icons/whatsapp.svg') }}" alt="{{ __('admin/salon/salon.whatsapp') }}"> {{ __('admin/salon/salon.whatsapp') }}</div>
                                        <div class="mess_block_radio">
                                            <div class="btn-group bg-white" role="group" aria-label="Radio button group">
                                                <input type="radio" class="btn-check" name="messengers[whatsapp][status]" value="1" id="radio-whatsapp-status-1" {{ ($whatsapp_status == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-primary" for="radio-whatsapp-status-1">Да</label>
                                                <input type="radio" class="btn-check" name="messengers[whatsapp][status]" value="0" id="radio-whatsapp-status-0" {{ ($whatsapp_status == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-danger" for="radio-whatsapp-status-0">Нет</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="whatsapp" class="mess_block_content" style="display: {{ $whatsapp_status ? 'block' : 'none' }};">
                                        <input type="text" class="form-control mt-2" id="input-whatsapp-content" name="messengers[whatsapp][content]" value="{{ $whatsapp_content }}" placeholder="+79999999999">
                                    </div>
                                    <script>
                                        document.querySelectorAll('input[name="messengers[whatsapp][status]"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                var whatsappBlock = document.getElementById('whatsapp');
                                                whatsappBlock.style.display = (document.getElementById('radio-whatsapp-status-1').checked) ? 'block' : 'none';
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- //Whatsapp -->
                            </div>
                            <div class="col-sm-4 col-12">
                                <!-- Instagram -->
                                @php $instagram_status = old('messengers.instagram.status') ?? null; @endphp
                                @php $instagram_content = old('messengers.instagram.content') ?? null; @endphp
                                <div class="mess_block mb_instagram">
                                    <div class="mess_block_top">
                                        <div class="mess_block_title"><img src="{{ url('images/icons/instagram.svg') }}" alt="{{ __('admin/salon/salon.instagram') }}"> {{ __('admin/salon/salon.instagram') }}</div>
                                        <div class="mess_block_radio">
                                            <div class="btn-group bg-white" role="group" aria-label="Radio button group">
                                                <input type="radio" class="btn-check" name="messengers[instagram][status]" value="1" id="radio-instagram-status-1" {{ ($instagram_status == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-primary" for="radio-instagram-status-1">Да</label>
                                                <input type="radio" class="btn-check" name="messengers[instagram][status]" value="0" id="radio-instagram-status-0" {{ ($instagram_status == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-danger" for="radio-instagram-status-0">Нет</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="instagram" class="mess_block_content" style="display: {{ $instagram_status ? 'block' : 'none' }};">
                                        <input type="text" class="form-control mt-2" id="input-instagram-content" name="messengers[instagram][content]" value="{{ $instagram_content }}" placeholder="https://www.instagram.com/intimate_cms/">
                                    </div>
                                    <script>
                                        document.querySelectorAll('input[name="messengers[instagram][status]"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                var instagramBlock = document.getElementById('instagram');
                                                instagramBlock.style.display = (document.getElementById('radio-instagram-status-1').checked) ? 'block' : 'none';
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- //Instagram -->
                            </div>
                        </div>
                        <!-- //Messengers -->
                    </div>
                </div>

                <hr>

                <h3 class="block-title">{{ __('admin/salon/salon.block_location') }}</h3>
                <div class="row">
                    <div id="block-zone" class="col-sm-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="input-zone">{{ __('admin/salon/salon.zone') }}</label>
                            <select name="zone_id" class="form-select @error('zone_id') is-invalid @enderror" id="input-zone">
                                <option value="">{{ __('lang.no_select') }}</option>
                                @foreach($data['main_data']['zone'] as $zone)
                                    <option value="{{ $zone['id'] }}">{{ $zone['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="block-metro" class="col-sm-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="input-metro">{{ __('admin/salon/salon.metro') }}</label>
                            <select name="metro_id" class="form-select @error('metro_id') is-invalid @enderror" id="input-metro">
                                <option value="">{{ __('lang.no_select') }}</option>
                                @foreach($data['main_data']['metro'] as $metro)
                                    <option value="{{ $metro['id'] }}">{{ $metro['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="alert-alert-primary" role="alert">
                    <p class="mb-0">{{ __('admin/salon/salon.location_text') }}</p>
                </div>
                <input type="hidden" id="input-latitude" name="latitude">
                <input type="hidden" id="input-longitude" name="longitude">
                <div id="map" style="height: 400px;"></div>
                @php $latitude = old('latitude') ?? null; @endphp
                @php $longitude = old('longitude') ?? null; @endphp
                <script>
                    var cities = {
                        @foreach($data['main_data']['city'] as $city)
                        '{{ $city['id'] }}': [{{ $city['latitude'] }}, {{ $city['longitude'] }}],
                        @endforeach
                    };
                    var firstKey = Object.keys(cities)[0];
                    var defaultZoom = @if(!empty($latitude) && !empty($longitude)) 15 @else 12 @endif;
                    var initialCoords = @if(!empty($latitude) && !empty($longitude)) [{{ $latitude }}, {{ $longitude }}] @else cities[firstKey] @endif;
                    var map = L.map('map').setView(initialCoords, defaultZoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OcEx.Dev'
                    }).addTo(map);

                    var marker;

                    @if(!empty($latitude) && !empty($longitude))
                        marker = L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
                    document.getElementById('input-latitude').value = {{ $latitude }};
                    document.getElementById('input-longitude').value = {{ $longitude }};
                    marker.on('click', function() {
                        map.removeLayer(marker);
                        marker = null;
                        document.getElementById('input-latitude').value = '';
                        document.getElementById('input-longitude').value = '';
                    });
                    @else
                    var selectedCity = document.getElementById('select-city').value;
                    if (cities[selectedCity]) {
                        var initialCoords = cities[selectedCity];
                        map.setView(initialCoords, defaultZoom);
                    }
                    @endif

                    function onMapClick(e) {
                        if (!marker) {
                            marker = L.marker(e.latlng).addTo(map);
                            marker.on('click', function() {
                                map.removeLayer(marker);
                                marker = null;
                                document.getElementById('input-latitude').value = '';
                                document.getElementById('input-longitude').value = '';
                            });
                        } else {
                            marker.setLatLng(e.latlng);
                        }
                        document.getElementById('input-latitude').value = e.latlng.lat.toFixed(4);
                        document.getElementById('input-longitude').value = e.latlng.lng.toFixed(4);
                    }
                    map.on('click', onMapClick);
                    document.getElementById('select-city').addEventListener('change', function() {
                        var selectedCity = this.value;
                        var coords = cities[selectedCity];
                        if (coords) {
                            map.setView(coords, 12);
                        }
                    });
                </script>

                <hr>

                <h3 class="block-title mt-3">{{ __('admin/salon/salon.block_prices') }}</h3>
                <div class="row mb-2">
                    <div class="col-sm-4 col-12">
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.day') }}</div>
                            <div class="col-sm-4 col-4 text-center">{{ __('admin/salon/salon.apartments') }}</div>
                            <div class="col-sm-4 col-4 text-center">{{ __('admin/salon/salon.outside') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.one_hour') }}</div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_day_in_one" name="price_day_in_one" value="{{ old('price_day_in_one') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_day_in_two" name="price_day_in_two" value="{{ old('price_day_in_two') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.two_hours') }}</div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_day_out_one" name="price_day_out_one" value="{{ old('price_day_out_one') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_day_out_two" name="price_day_out_two" value="{{ old('price_day_out_two') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.night') }}</div>
                            <div class="col-sm-4 col-4 text-center">{{ __('admin/salon/salon.apartments') }}</div>
                            <div class="col-sm-4 col-4 text-center">{{ __('admin/salon/salon.outside') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.one_hour') }}</div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_night_in_one" name="price_night_in_one" value="{{ old('price_night_in_one') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_night_in_night" name="price_night_in_night" value="{{ old('price_night_in_night') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 col-4">{{ __('admin/salon/salon.night') }}</div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_night_out_one" name="price_night_out_one" value="{{ old('price_night_out_one') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                            <div class="col-sm-4 col-4">
                                <input type="text" class="form-control" id="input-price_night_out_night" name="price_night_out_night" value="{{ old('price_night_out_night') }}" placeholder="{{ __('lang.from') . ' ' . $data['currency_symbol'] }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h3 class="block-title mt-3">{{ __('admin/salon/salon.block_desc') }}</h3>
                <div class="mb-4">
                    <textarea class="form-control" id="textarea-desc" name="desc" rows="4" placeholder="{{ __('admin/salon/salon.desc') }}">{{ old('desc') }}</textarea>
                    <p class="text-gray-dark mb-0">{{ trans_choice('admin/salon/salon.min_symbol', 100, ['num' => 100]) }}</p>
                </div>

                <hr>

                <nav class="nav nav-pills flex-column flex-sm-row mt-4 mb-3">
                    <a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="tab" href="#block-image" role="tab" aria-controls="block-image" aria-selected="true"><i class="fas fa-camera"></i> {{ __('admin/posts/post.image_photo') }} <span id="counter-photos"></span></a>
                </nav>
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="block-image" role="tabpanel">
                        <p>{{ $data['main_data']['photo_text'] }}</p>
                        <div class="ex-post-photo-all-media row">
                            @php $old_photos = old('images.photos'); @endphp
                            @if(isset($old_photos) && !empty($old_photos) && is_array($old_photos))
                                @foreach($old_photos as $photos)
                                    <div class="col-sm-2 col-4">
                                        <div class="ex_images_item_block" style="background-image: url('{{ $photos }}')">
                                            <input type="hidden" name="images[photos][]" value="{{ $photos }}">
                                            <button type="button" data-types="photo" data-file-path="{{ $photos }}" class="ex_images_item_block_button post_btn_del"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ex-post-btn">
                            <button type="button" id="upload-salon-photos" data-types="photos" data-in-block="ex-post-photo-all-media" class="ex-post-btn-button"><i class="fas fa-upload"></i> {{ __('buttons.upload_photo') }}</button>
                        </div>
                    </div>
                    <script>
                        function filesLimits(fileType) {
                            var limit_count = 0;
                            var mediaContainerId = '';
                            if (fileType === 'photos') {
                                limit_count = {{ $data['main_data']['file_count_photo'] }};
                                mediaContainerId = '.ex-post-photo-all-media';
                            }
                            var mediaContainer = document.querySelector(mediaContainerId);
                            if (mediaContainer) {
                                var mediaBlocks = mediaContainer.querySelectorAll('div.col-sm-2.col-4');
                                var excessCount = mediaBlocks.length - limit_count;

                                if (excessCount > 0) {
                                    for (let i = 0; i < excessCount; i++) {
                                        mediaBlocks[mediaBlocks.length - 1 - i].remove();
                                    }
                                    if (fileType === 'photos') {
                                        kbNotify('danger', '{{ trans_choice('admin/posts/post.max_files_count', $data['main_data']['file_count_photo'], ['num' => $data['main_data']['file_count_photo']]) }}');
                                    }
                                }
                            } else {
                                console.log(`Контейнер ${mediaContainerId} для ${fileType} не найден.`);
                            }
                            showCountFiles();
                        }

                        function showCountFiles() {
                            var photosLimitCount = {{ $data['main_data']['file_count_photo'] }};
                            var photosCounterBlock = '#counter-photos';
                            var photosMediaContainerId = '.ex-post-photo-all-media';
                            var photosBtnUpload = '#upload-salon-photos';

                            function updateCounter(mediaContainerId, counterBlock, limitCount, btnUpload) {
                                var currentCount = $(mediaContainerId).find('div.col-sm-2.col-4').length;
                                $(counterBlock).text(currentCount + '/' + limitCount);
                                if (currentCount >= limitCount) {
                                    $(btnUpload).prop('disabled', true);
                                } else {
                                    $(btnUpload).prop('disabled', false);
                                }
                            }

                            updateCounter(photosMediaContainerId, photosCounterBlock, photosLimitCount, photosBtnUpload);
                        }

                        showCountFiles();

                        document.querySelectorAll('#upload-salon-photos').forEach(button => {
                            button.addEventListener('click', function() {
                                var random_id = $('#input-random-id').val();
                                var dataTypes = this.dataset.types;
                                var format = '';
                                if (dataTypes == 'photos') {
                                    format = '{{ $data['main_data']['file_format_photo'] }}';
                                }
                                var dataInBlock = this.dataset.inBlock;
                                let input = document.createElement('input');
                                input.type = 'file';
                                input.multiple = true;
                                input.accept = format.split(', ').map(ext => {
                                    if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                        return `image/${ext}`;
                                    } else {
                                        return `video/${ext}`;
                                    }
                                }).join(', ');
                                input.onchange = async function(event) {
                                    let files = event.target.files;
                                    let formData = new FormData();
                                    formData.append('photo_type', dataTypes);
                                    formData.append('random_id', random_id);
                                    for (let file of files) {
                                        formData.append('images[]', file);
                                    }
                                    try {
                                        let response = await fetch("{{ route('multiUploadSalonImages') }}", {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: formData
                                        });
                                        let result = await response.json();
                                        if (result.files) {
                                            result.files.forEach(filePath => {
                                                let fileBlock;
                                                fileBlock = createImageBlock(filePath, dataTypes);
                                                document.querySelector(`.${dataInBlock}`).appendChild(fileBlock);
                                            });
                                            filesLimits(dataTypes);
                                        } else if (result.error) {
                                            if (Array.isArray(result.error)) {
                                                result.error.forEach(function (error) {
                                                    kbNotify('danger', error);
                                                });
                                            } else {
                                                kbNotify('danger', result.error);
                                            }
                                        }
                                    } catch (error) {
                                        kbNotify('danger', error);
                                    }
                                };
                                input.click();
                            });
                        });

                        document.addEventListener('click', async function(event) {
                            if (event.target.closest('.post_btn_del')) {
                                const button = event.target.closest('.post_btn_del');
                                const dataTypes = button.dataset.types;
                                const dataFilePath = button.dataset.filePath;
                                let formData = new FormData();
                                formData.append('image_path', dataFilePath);
                                button.closest('.col-sm-2').remove();
                                showCountFiles();
                                try {
                                    let response = await fetch("{{ route('multiDeletePostImages') }}", {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        body: formData
                                    });
                                    let result = await response.json();
                                    if (result.success) {
                                        kbNotify('success', result.success);
                                    } else if (result.error) {
                                        kbNotify('danger', result.error);
                                    }
                                } catch (error) {
                                    kbNotify('danger', error);
                                    console.error("Delete failed", error);
                                }
                            }
                        });

                        function createImageBlock(imagePath, typeImage) {
                            const block = `<div class="col-sm-2 col-4">
                                                <div class="ex_images_item_block" style="background-image: url('/${imagePath}')">
                                                   <input type="hidden" name="images[${typeImage}][]" value="/${imagePath}">
                                                   <button type="button" data-types="${typeImage}" data-file-path="${imagePath}" class="ex_images_item_block_button post_btn_del"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </div>`;
                            const container = document.createElement('div');
                            container.innerHTML = block.trim();
                            return container.firstChild;
                        }
                    </script>
                </div>

                <hr>

                <div class="d-flex justify-content-center">
                    <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary main-btn-style"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        function handleUploadImage(event, type) {
            var random_id = $('#input-random-id').val();
            const file = event.target.files[0];
            if (!file) return;

            $('.preload-container').addClass('active');

            const formData = new FormData();
            const path = `/images/temp/salon/${random_id}/${type}`;
            formData.append('image', file);
            formData.append('path', path);
            fetch('/services/upload-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            }).then(response => response.json()).then(data => {
                    if (data.success) {
                        document.getElementById(`preview-image-${type}`).src = `${path}/${data.filename}`;
                        document.getElementById(`image-path-${type}`).value = `${path}/${data.filename}`;
                        document.getElementById(`preview-container-${type}`).style.display = 'flex';
                        document.getElementById(`upload-container-${type}`).style.display = 'none';

                        setTimeout(function () {
                            $('.preload-container').removeClass('active');
                        }, 1000);
                    }
                });
        }

        function deletePreviewImage(type) {
            document.getElementById(`preview-container-${type}`).style.display = 'none';
            document.getElementById(`upload-container-${type}`).style.display = 'flex';
            document.getElementById(`image-path-${type}`).value = '';
            document.getElementById(`image-upload-${type}`).value = '';
        }
    </script>
    <script>
        $(document).ready(function(){
            setPhoneMask();
        });

        function setPhoneMask() {
            $('.phone-mask').inputmask({
                mask: "+7 (999) 999-99-99",
                showMaskOnHover: true,
                showMaskOnFocus: true
            });
        }
    </script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                kbNotify('danger', '{!! $error !!}')
            </script>
        @endforeach
    @endif
@endsection
