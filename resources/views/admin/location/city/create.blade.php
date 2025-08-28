@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ __('admin/page_titles.city_add') }}
                </h3>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('city.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.city_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/location/city.title') }}</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="input-title" placeholder="{{ __('admin/location/city.title') }}">
                                    @error('title')
                                    <script>
                                        kbNotify('error', '{{ $message }}');
                                    </script>
                                    @enderror
                                </div>
                                <div class="form-floating mb-4">
                                    @php $status = old('status'); @endphp
                                    <select class="form-select" id="input-status" name="status">
                                        <option value="1" @if(($status != null) && $status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                        <option value="0" @if(($status != null) && $status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                    </select>
                                    <label class="form-label" for="input-status">{{ __('admin/location/city.status') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="input-latitude">{{ __('admin/location/city.latitude') }}</label>
                                    <input type="text" name="latitude" value="{{ old('latitude') }}" class="form-control @error('latitude') is-invalid @enderror" id="input-latitude" placeholder="{{ __('admin/location/city.latitude') }}">
                                    @error('latitude')
                                    <script>
                                        kbNotify('error', '{{ $message }}');
                                    </script>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="input-longitude">{{ __('admin/location/city.longitude') }}</label>
                                    <input type="text" name="longitude" value="{{ old('longitude') }}" class="form-control @error('longitude') is-invalid @enderror" id="input-longitude" placeholder="{{ __('admin/location/city.longitude') }}">
                                    @error('longitude')
                                    <script>
                                        kbNotify('error', '{{ $message }}');
                                    </script>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="mb-2">{{ __('admin/location/city.select_on_the_map') }}</p>
                                <div class="mb-4">
                                    <label class="form-label" for="citySelect">{{ __('admin/location/city.regional_city') }}</label>
                                    <select class="form-select" id="citySelect">
                                        <option value="moscow">Москва</option>
                                        <option value="saint-petersburg">Санкт-Петербург</option>
                                        <option value="novosibirsk">Новосибирск</option>
                                        <option value="yekaterinburg">Екатеринбург</option>
                                        <option value="nizhny-novgorod">Нижний Новгород</option>
                                        <option value="kazan">Казань</option>
                                        <option value="chelyabinsk">Челябинск</option>
                                        <option value="omsk">Омск</option>
                                        <option value="samara">Самара</option>
                                        <option value="rostov-on-don">Ростов-на-Дону</option>
                                        <option value="ufa">Уфа</option>
                                        <option value="krasnoyarsk">Красноярск</option>
                                        <option value="voronezh">Воронеж</option>
                                        <option value="perm">Пермь</option>
                                        <option value="volgograd">Волгоград</option>
                                        <option value="krasnodar">Краснодар</option>
                                        <option value="saratov">Саратов</option>
                                        <option value="tyumen">Тюмень</option>
                                        <option value="tolyatti">Тольятти</option>
                                        <option value="izhevsk">Ижевск</option>
                                    </select>
                                </div>
                                <div id="map" style="height: 400px;"></div>
                                <script>
                                    var cities = {
                                        'moscow': [55.751244, 37.618423],
                                        'saint-petersburg': [59.9311, 30.3609],
                                        'novosibirsk': [55.0084, 82.9357],
                                        'yekaterinburg': [56.8389, 60.6057],
                                        'nizhny-novgorod': [56.2965, 43.9361],
                                        'kazan': [55.7963, 49.1088],
                                        'chelyabinsk': [55.1644, 61.4368],
                                        'omsk': [54.9893, 73.3682],
                                        'samara': [53.2415, 50.2212],
                                        'rostov-on-don': [47.2357, 39.7015],
                                        'ufa': [54.7388, 55.9721],
                                        'krasnoyarsk': [56.0086, 92.8705],
                                        'voronezh': [51.6615, 39.2005],
                                        'perm': [58.0105, 56.2502],
                                        'volgograd': [48.708, 44.5133],
                                        'krasnodar': [45.0393, 38.9872],
                                        'saratov': [51.5336, 46.0343],
                                        'tyumen': [57.1613, 65.525],
                                        'tolyatti': [53.5206, 49.3894],
                                        'izhevsk': [56.8526, 53.2061]
                                    };
                                    var map = L.map('map').setView(cities['moscow'], 10);
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OcEx.Dev'
                                    }).addTo(map);
                                    var marker;
                                    function onMapClick(e) {
                                        if (marker) {
                                            map.removeLayer(marker);
                                        }
                                        marker = L.marker(e.latlng).addTo(map);
                                        document.getElementById('input-latitude').value = e.latlng.lat.toFixed(5);
                                        document.getElementById('input-longitude').value = e.latlng.lng.toFixed(5);
                                    }
                                    map.on('click', onMapClick);
                                    document.getElementById('citySelect').addEventListener('change', function() {
                                        var selectedCity = this.value;
                                        var coords = cities[selectedCity];
                                        map.setView(coords, 10);
                                        document.getElementById('input-latitude').value = cities[selectedCity][0];
                                        document.getElementById('input-longitude').value = cities[selectedCity][1];
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
