@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    @vite('resources/catalog/css/map.css')
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
            <h1 class="ex_block_title">{{ $data['h1'] }}</h1>
        @else
            <h1 class="ex_block_title">{{ $data['title'] }}</h1>
        @endif
        <div id="map" style="height: 600px;"></div>
        <script>
            var defaultCityId = '1';
            var defaultZoom = 12;
            var initialCoords = [{{ $data['latitude'] }}, {{ $data['longitude'] }}];
            var map = L.map('map').setView(initialCoords, defaultZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OcEx.Dev'
            }).addTo(map);
            var posts = @json($data['posts']);
            posts.forEach(function(post) {
                var postMarker = L.marker([post.latitude, post.longitude]).addTo(map);

                var popupContent = `
                <img src="${post.image}" alt="image" class="ex_map_image">
                <a href="${post.link}" class="ex_map_name">${post.name}</a>

                <div class="ex_map_prices">
                    <div class="ex_map_prices_item">
                        <span class="ex_map_prices_item_icon"><svg class="ex_map_prices_item_icon_svg"><use xlink:href="#icon-time-hour"></use></svg> {{ __('catalog/posts/post.item_hour_one') }}</span>
                        <span class="ex_map_prices_item_price">${post.price_hour}</span>
                    </div>
                    <div class="ex_map_prices_item">
                        <span class="ex_map_prices_item_icon"><svg class="ex_map_prices_item_icon_svg"><use xlink:href="#icon-time-hour"></use></svg> {{ __('catalog/posts/post.item_hour_two') }}</span>
                        <span class="ex_map_prices_item_price">${post.price_hours}</span>
                    </div>
                </div>
                <a href="tel:${post.phone}" class="ex_map_phone">${post.phone}</a>`;
                postMarker.bindPopup(popupContent);
            });
        </script>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
