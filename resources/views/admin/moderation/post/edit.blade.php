@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/inputMask/jquery.inputmask.min.js') }}"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    @vite('resources/admin/js/post_created.js')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ __('admin/page_titles.post_moderation_edit') }}
                </h3>
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    kbNotify('danger', '{{ $error }}')
                </script>
            @endforeach
        @endif

        <div class="content">
            <form action="{{ route('post_moderation.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="random_id" id="input-random-id" value="{{ old('random_id') ?? rand(100000, 9999999) }}">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.post_moderation_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                </div>
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/posts/post.block_main') }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-3 col-12">
                                <!-- Photo And Parameters -->
                                <div class="d-flex justify-content-center position-relative mb-2">
                                    <div id="upload-container-main" class="upload-container" style="display: {{ old('images.main') ?? $data['image_main'] ? 'none' : 'flex' }}">
                                    </div>
                                    <div id="preview-container-main" class="preview-container" style="display: {{ old('images.main') ?? $data['image_main'] ? 'flex' : 'none' }};">
                                        <img id="preview-image-main" src="{{ old('images.main') ?? $data['image_main'] }}" alt="Preview" class="preview-img"/>
                                    </div>
                                    <div class="preload-container">
                                        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                            <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                        </svg>
                                    </div>
                                    <input type="hidden" name="images[main]" id="image-path-main" value="{{ old('images.main') ?? $data['image_main'] }}">
                                </div>
                                <!-- //Photo And Parameters -->
                            </div>
                            <div class="col-sm-9 col-12">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <!-- Users -->
                                        <div class="mb-2">
                                            <label class="form-label" for="select-user">{{ __('admin/posts/post.user') }}</label>
                                            @foreach($data['main_data']['users'] as $user)
                                                @if($data['user_id'] == $user['id'])
                                                    <div>{{ __('admin/posts/post.user_r', ['login' => $user['login'], 'email' => $user['email']]) }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <!-- //Users -->

                                        <!-- Name -->
                                        <div class="mb-2">
                                            <label class="form-label" for="input-name">{{ __('admin/posts/post.name') }}</label>
                                            <div>{{ $data['name'] }}</div>
                                        </div>
                                        <!-- //Name -->

                                        <!-- City -->
                                        <div class="mb-2">
                                            <label class="form-label" for="select-city">{{ __('admin/posts/post.city') }}</label>
                                            @foreach($data['main_data']['city'] as $city)
                                                @if($data['city_id'] == $city['id'])
                                                    <div>{{ $city['title'] }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <!-- //City -->

                                        <!-- Phone -->
                                        <div class="mb-2">
                                            <label class="form-label" for="input-phone">{{ __('admin/posts/post.phone') }}</label>
                                            <div>{{ $data['phone'] }}</div>
                                        </div>
                                        <!-- //Phone -->
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <!-- Call Time -->
                                        <div class="mb-2">
                                            <label class="form-label" for="input-phone">{{ __('admin/posts/post.call_time') }}</label>
                                            <div class="space-y-2 mb-2">
                                                @php $call_time_type = old('call_time_type') ?? $data['call_time_type']; @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="input-call-time-type-one" name="call_time_type" value="1" {{ ($call_time_type == 1) ? 'checked' : '' }}{{ (!$call_time_type) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="input-call-time-type-one">{{ __('admin/posts/post.call_time_hours') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="input-call-time-type-two" name="call_time_type" value="2" {{ ($call_time_type == 2) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="input-call-time-type-two">{{ __('admin/posts/post.call_time_to_time') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-select" id="select-call-time-from" name="call_time[time_from]">
                                                        @for ($i = 0; $i <= 24; $i++)
                                                            <option value="{{ $i }}" {{ (old('call_time.time_from') ?? ($data['call_time']['time_from'] ?? null) == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select class="form-select" id="select-call-time-to" name="call_time[time_to]">
                                                        @for ($i = 0; $i <= 24; $i++)
                                                            <option value="{{ $i }}" {{ (old('call_time.time_to') ?? ($data['call_time']['time_to'] ?? null) == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- //Call Time -->

                                        <!-- Client age -->
                                        <div class="mb-2">
                                            <label class="form-label" for="input-phone">{{ __('admin/posts/post.client_age') }}</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-select" id="select-client-age-min" name="client_age[min]">
                                                        @for ($i = 18; $i <= 99; $i++)
                                                            <option value="{{ $i }}" {{ ((old('client_age.min') ?? ($data['client_age']['min'] ?? null)) == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select class="form-select" id="select-client-age-max" name="client_age[max]">
                                                        @for ($i = 18; $i <= 99; $i++)
                                                            <option value="{{ $i }}" {{ ((old('client_age.max') ?? ($data['client_age']['max'] ?? null)) == $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- //Client age -->

                                        <!-- Answering to -->
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.answering_title') }}</label>
                                            <div class="space-x-2">
                                                @php $call_time_answering_to = old('call_time.answering_to') ?? ($data['call_time']['answering_to'] ?? null); @endphp
                                                <div class="ex_checkbox_item_adm badge bg-{{ ($call_time_answering_to && in_array(1, $call_time_answering_to)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ ($call_time_answering_to && in_array(1, $call_time_answering_to)) ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.answering_call') }}</div>
                                                <div class="ex_checkbox_item_adm badge bg-{{ ($call_time_answering_to && in_array(2, $call_time_answering_to)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ ($call_time_answering_to && in_array(2, $call_time_answering_to)) ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.answering_sms') }}</div>
                                                <div class="ex_checkbox_item_adm badge bg-{{ ($call_time_answering_to && in_array(3, $call_time_answering_to)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ ($call_time_answering_to && in_array(3, $call_time_answering_to)) ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.answering_messages') }}</div>
                                            </div>
                                        </div>
                                        <!-- //Answering to -->

                                        <!-- Delete code -->
                                        <div class="mb-2">
                                            <label class="form-label mb-0" for="input-delete-code">{{ __('admin/posts/post.delete_code') }}</label>
                                            <p class="mb-0 fs-7">{{ __('admin/posts/post.delete_info') }}</p>
                                            <div>{{ $data['delete_code'] }}</div>
                                        </div>
                                        <!-- //Delete code -->
                                    </div>
                                </div>
                                <hr class="my-1">
                                <!-- Messengers -->
                                <label class="form-label" for="input-phone">{{ __('admin/posts/post.messengers') }}</label>
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <!-- Telegram -->
                                        @php $telegram_status = old('messengers.telegram.status') ?? ($data['messengers']['telegram']['status'] ?? null); @endphp
                                        @php $telegram_type = old('messengers.telegram.type') ?? ($data['messengers']['telegram']['type'] ?? null); @endphp
                                        @php $telegram_content = old('messengers.telegram.content') ?? ($data['messengers']['telegram']['content'] ?? null); @endphp
                                        <div class="mess_block mb_telegram">
                                            <div class="mess_block_top">
                                                <div class="mess_block_title"><img src="{{ url('images/icons/telegram.svg') }}" alt="{{ __('admin/posts/post.telegram') }}"> {{ __('admin/posts/post.telegram') }}</div>
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
                                                        <label class="form-check-label" for="telegram-type-link">{{ __('admin/posts/post.telegram_link') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline ms-0 me-2">
                                                        <input class="form-check-input" type="radio" id="telegram-type-login" name="messengers[telegram][type]" value="login" {{ ($telegram_type == 'login') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="telegram-type-login">{{ __('admin/posts/post.telegram_login') }}</label>
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
                                    <div class="col-sm-6 col-12">
                                        <!-- Whatsapp -->
                                        @php $whatsapp_status = old('messengers.whatsapp.status') ?? ($data['messengers']['whatsapp']['status'] ?? null); @endphp
                                        @php $whatsapp_content = old('messengers.whatsapp.content') ?? ($data['messengers']['whatsapp']['content'] ?? null); @endphp
                                        <div class="mess_block mb_whatsapp">
                                            <div class="mess_block_top">
                                                <div class="mess_block_title"><img src="{{ url('images/icons/whatsapp.svg') }}" alt="{{ __('admin/posts/post.whatsapp') }}"> {{ __('admin/posts/post.whatsapp') }}</div>
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
                                    <div class="col-sm-6 col-12">
                                        <!-- Instagram -->
                                        @php $instagram_status = old('messengers.instagram.status') ?? ($data['messengers']['instagram']['status'] ?? null); @endphp
                                        @php $instagram_content = old('messengers.instagram.content') ?? ($data['messengers']['instagram']['content'] ?? null); @endphp
                                        <div class="mess_block mb_instagram">
                                            <div class="mess_block_top">
                                                <div class="mess_block_title"><img src="{{ url('images/icons/instagram.svg') }}" alt="{{ __('admin/posts/post.instagram') }}"> {{ __('admin/posts/post.instagram') }}</div>
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
                                    <div class="col-sm-6 col-12">
                                        <!-- Polee -->
                                        @php $polee_status = old('messengers.polee.status') ?? ($data['messengers']['polee']['status'] ?? null); @endphp
                                        @php $polee_content = old('messengers.polee.content') ?? ($data['messengers']['polee']['content'] ?? null); @endphp
                                        <div class="mess_block mb_polee">
                                            <div class="mess_block_top">
                                                <div class="mess_block_title"><img src="{{ url('images/icons/polee.svg') }}" alt="{{ __('admin/posts/post.polee') }}"> {{ __('admin/posts/post.polee') }}</div>
                                                <div class="mess_block_radio">
                                                    <div class="btn-group bg-white" role="group" aria-label="Radio button group">
                                                        <input type="radio" class="btn-check" name="messengers[polee][status]" value="1" id="radio-polee-status-1" {{ ($polee_status == 1) ? 'checked' : '' }}>
                                                        <label class="btn btn-sm btn-outline-primary" for="radio-polee-status-1">Да</label>
                                                        <input type="radio" class="btn-check" name="messengers[polee][status]" value="0" id="radio-polee-status-0" {{ ($polee_status == 0) ? 'checked' : '' }}>
                                                        <label class="btn btn-sm btn-outline-danger" for="radio-polee-status-0">Нет</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="polee" class="mess_block_content" style="display: {{ $polee_status ? 'block' : 'none' }};">
                                                <input type="text" class="form-control mt-2" id="input-polee-content" name="messengers[polee][content]" value="{{ $polee_content }}" placeholder="https://polee.me/intimate_cms">
                                            </div>
                                            <script>
                                                document.querySelectorAll('input[name="messengers[polee][status]"]').forEach(function(radio) {
                                                    radio.addEventListener('change', function() {
                                                        var poleeBlock = document.getElementById('polee');
                                                        poleeBlock.style.display = (document.getElementById('radio-polee-status-1').checked) ? 'block' : 'none';
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <!-- //Polee -->
                                    </div>
                                </div>
                                <!-- //Messengers -->

                                <hr class="my-1">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-age">{{ __('admin/posts/post.age') }}</label>
                                                <div>{{ $data['age'] }}</div>
                                            </div>
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-weight">{{ __('admin/posts/post.weight') }}</label>
                                                <div>{{ $data['weight'] }}</div>
                                            </div>
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-cloth">{{ __('admin/posts/post.cloth') }}</label>
                                                <div>{{ $data['cloth'] }}</div>
                                            </div>
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-height">{{ __('admin/posts/post.height') }}</label>
                                                <div>{{ $data['height'] }}</div>
                                            </div>
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-breast">{{ __('admin/posts/post.breast') }}</label>
                                                <div>{{ $data['breast'] }}</div>
                                            </div>
                                            <div class="col-sm-4 col-6 mb-2">
                                                <label class="form-label" for="input-shoes">{{ __('admin/posts/post.shoes') }}</label>
                                                <div>{{ $data['shoes'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-sm-6 col-12 mb-2">
                                                <label class="form-label" for="select-nationality">{{ __('admin/posts/post.nationality') }}</label>
                                                @foreach($data['main_data']['nationality'] as $item)
                                                    @if($data['nationality'] == $item['id'])
                                                        <div>{{ $item['title'] }}</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="col-sm-6 col-12 mb-2">
                                                <label class="form-label" for="select-body-type">{{ __('admin/posts/post.body_type') }}</label>
                                                @foreach($data['main_data']['body_type'] as $item)
                                                    @if($data['body_type'] == $item['id'])
                                                        <div>{{ $item['title'] }}</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="col-sm-6 col-12 mb-2">
                                                <label class="form-label" for="select-hair-color">{{ __('admin/posts/post.hair_color') }}</label>
                                                @foreach($data['main_data']['hair_color'] as $item)
                                                    @if($data['hair_color'] == $item['id'])
                                                        <div>{{ $item['title'] }}</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="col-sm-6 col-12 mb-2">
                                                <label class="form-label" for="select-hairy">{{ __('admin/posts/post.hairy') }}</label>
                                                @foreach($data['main_data']['hairy'] as $item)
                                                    @if($data['hairy'] == $item['id'])
                                                        <div>{{ $item['title'] }}</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-1">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.desired_sections') }}</label>
                                            <div class="space-x-2">
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_individuals'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_individuals'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_individuals') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_individuals_help') }}</p>
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_premium'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_premium'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_premium') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_premium_help') }}</p>
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_health'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_health'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_health') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_health_help') }}</p>
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_masseuse'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_masseuse'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_masseuse') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_masseuse_help') }}</p>
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_elite'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_elite'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_elite') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_elite_help') }}</p>
                                                <div class="ex_checkbox_item_adm badge bg-{{ $data['s_bdsm'] ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ $data['s_bdsm'] ? 'check' : 'xmark' }}"></i> {{ __('admin/posts/post.s_bdsm') }}</div>
                                                <p class="m-0 text-gray-dark fs-14">{{ __('admin/posts/post.s_bdsm_help') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.services_for') }}</label>
                                            <div class="space-x-2">
                                                @php $services_for = old('services_for') ?? ($data['services_for'] ?? null); @endphp
                                                @foreach($data['main_data']['services_for'] as $service)
                                                    <div class="ex_checkbox_item_adm badge bg-{{ ($services_for && in_array($service['id'], $services_for)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ ($services_for && in_array($service['id'], $services_for)) ? 'check' : 'xmark' }}"></i> {{ $service['title'] }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.body_art') }}</label>
                                            <div class="space-x-2">
                                                @php $body_art = old('body_art') ?? ($data['body_art'] ?? []); @endphp
                                                @foreach($data['main_data']['body_art'] as $body_art_item)
                                                    <div class="ex_checkbox_item_adm badge bg-{{ (!empty($body_art) && in_array($body_art_item['id'], $body_art)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ (!empty($body_art) && in_array($body_art_item['id'], $body_art)) ? 'check' : 'xmark' }}"></i> {{ $body_art_item['title'] }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.language_skills') }}</label>
                                            <div class="space-x-2">
                                                @php $language_skills = old('language_skills') ?? ($data['language_skills'] ?? null); @endphp
                                                @foreach($data['main_data']['language_skills'] as $language_skill)
                                                    <div class="ex_checkbox_item_adm badge bg-{{ (is_array($language_skills) && in_array($language_skill['id'], $language_skills)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ (is_array($language_skills) && in_array($language_skill['id'], $language_skills)) ? 'check' : 'xmark' }}"></i> {{ $language_skill['title'] }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="form-label">{{ __('admin/posts/post.tags') }}</label>
                                            <div class="space-x-2">
                                                @php $tags = old('tags') ?? ($data['tags'] ?? null); @endphp
                                                @foreach($data['main_data']['tags'] as $tag)
                                                    <div class="ex_checkbox_item_adm badge bg-{{ (is_array($tags) && in_array($tag['id'], $tags)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ (is_array($tags) && in_array($tag['id'], $tags)) ? 'check' : 'xmark' }}"></i> {{ $tag['tag'] }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/posts/post.block_location') }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div id="block-zone" class="col-sm-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="input-zone">{{ __('admin/posts/post.zone') }}</label>
                                    <select name="zone_id" class="form-control @error('zone_id') is-invalid @enderror" id="input-zone">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['main_data']['zone'] as $zone)
                                            <option value="{{ $zone['id'] }}" @if(($data['zone_id'] ?? null) == $zone['id']) selected @endif>{{ $zone['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="block-metro" class="col-sm-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="input-metro">{{ __('admin/posts/post.metro') }}</label>
                                    <select name="metro_id" class="form-control @error('metro_id') is-invalid @enderror" id="input-metro">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['main_data']['metro'] as $metro)
                                            <option value="{{ $metro['id'] }}" @if(($data['metro_id'] ?? null) == $metro['id']) selected @endif>{{ $metro['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-primary" role="alert">
                            <p class="mb-0">{{ __('admin/posts/post.location_text') }}</p>
                        </div>
                        <input type="hidden" id="input-latitude" name="latitude">
                        <input type="hidden" id="input-longitude" name="longitude">
                        <div id="map" style="height: 400px;"></div>
                        @php $latitude = $data['latitude'] ?? null; @endphp
                        @php $longitude = $data['longitude'] ?? null; @endphp
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
                            // Устанавливаем начальную метку, если координаты не пустые
                            marker = L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
                            document.getElementById('input-latitude').value = {{ $latitude }};
                            document.getElementById('input-longitude').value = {{ $longitude }};

                            // Добавляем обработчик клика для удаления начальной метки
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
                                    // Создаем маркер, если его нет
                                    marker = L.marker(e.latlng).addTo(map);

                                    // Добавляем обработчик клика для удаления маркера
                                    marker.on('click', function() {
                                        map.removeLayer(marker);
                                        marker = null;
                                        document.getElementById('input-latitude').value = '';
                                        document.getElementById('input-longitude').value = '';
                                    });
                                } else {
                                    marker.setLatLng(e.latlng); // Перемещаем маркер, если он уже есть
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
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/posts/post.block_prices') }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="row mb-2">
                            <div class="col-sm-4 col-12">
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.day') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ __('admin/posts/post.apartments') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ __('admin/posts/post.outside') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.one_hour') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_day_in_one'] }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_day_out_one'] }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.two_hours') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_day_in_two'] }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_day_out_two'] }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.night') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ __('admin/posts/post.apartments') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ __('admin/posts/post.outside') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.one_hour') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_night_in_one'] }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_night_out_one'] }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-2 col-4">{{ __('admin/posts/post.night') }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_night_in_night'] }}</div>
                                    <div class="col-sm-4 col-4 text-center">{{ $data['price_night_out_night'] }}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12">
                                <div class="form-check form-check-inline ms-0 me-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="checkbox-express-status" name="express" {{ old('express') ?? $data['express'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkbox-express-status">{{ __('admin/posts/post.express') }}</label>
                                </div>
                                <br>
                                {{ old('express_price') ?? $data['express_price'] }}{{ $data['currency_symbol'] }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ __('admin/posts/post.visit_places') }}</label>
                            <div class="space-x-2">
                                @php $visit_places = old('visit_places') ?? ($data['visit_places'] ?? null); @endphp
                                @foreach($data['main_data']['visit_places'] as $visit_place)
                                    <div class="ex_checkbox_item_adm badge bg-{{ ($visit_places && in_array($visit_place['id'], $visit_places)) ? 'success' : 'black-25' }}"><i class="fa-regular fa-{{ ($visit_places && in_array($visit_place['id'], $visit_places)) ? 'check' : 'xmark' }}"></i> {{ $visit_place['title'] }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/posts/post.block_description') }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            {{ $data['description'] }}
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/posts/post.block_services') }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="ex_services_block_description mb-2">
                            <div class="ex_services_block_description_item">
                                <i class="fas fa-check-double"></i> - {{ __('admin/posts/post.services_type_1') }}
                            </div>
                            <div class="ex_services_block_description_item">
                                <i class="fas fa-heart"></i> - {{ __('admin/posts/post.services_type_2') }}
                            </div>
                            <div class="ex_services_block_description_item">
                                <i class="fas fa-plus"></i> - {{ __('admin/posts/post.services_type_3') }}
                            </div>
                            <div class="ex_services_block_description_item">
                                <i class="fas fa-xmark"></i> - {{ __('admin/posts/post.services_type_4') }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @foreach($data['main_data']['services'] as $service)
                                <div class="col-sm-4 col-12">
                                    <div class="ex_services_block">
                                        <div class="ex_services_block_title">{{ $service['title'] }}</div>
                                        <div class="ex_services_block_items">
                                            @foreach($service['data'] as $item)
                                                @php $service_condition = old('services.'.$item['id'].'.condition') ?? ($data['services'][$item['id']]['condition'] ?? null); @endphp
                                                @php $service_description = old('services.'.$item['id'].'.description') ?? ($data['services'][$item['id']]['description'] ?? null); @endphp
                                                @php $service_price = old('services.'.$item['id'].'.price') ?? ($data['services'][$item['id']]['price'] ?? null); @endphp
                                                <div class="ex_services_block_item" style="background: transparent">
                                                    <div class="ex_services_block_item_main">
                                                        <div class="ex_services_block_item_radio_title">
                                                            <div class="ex_services_block_item_radio">
                                                                <input type="radio" id="services_{{ $item['id'] }}_1" class="d-none" name="services[{{ $item['id'] }}][condition]" value="1" {{ ($service_condition == 1) ? 'checked' : '' }}>
                                                                <label class="ex_services_block_item_radio_item checks" for="services_{{ $item['id'] }}_1" onclick="clearField('services_{{ $item['id'] }}_price')" title="{{ __('admin/posts/post.title_services_type_1') }}"><i class="fas fa-check-double"></i></label>
                                                                <input type="radio" id="services_{{ $item['id'] }}_2" class="d-none" name="services[{{ $item['id'] }}][condition]" value="2" {{ ($service_condition == 2) ? 'checked' : '' }}>
                                                                <label class="ex_services_block_item_radio_item simpatico" for="services_{{ $item['id'] }}_2" onclick="clearField('services_{{ $item['id'] }}_price')" title="{{ __('admin/posts/post.title_services_type_2') }}"><i class="fas fa-heart"></i></label>
                                                                <input type="radio" id="services_{{ $item['id'] }}_3" class="d-none" name="services[{{ $item['id'] }}][condition]" value="3" {{ ($service_condition == 3) ? 'checked' : '' }}>
                                                                <label class="ex_services_block_item_radio_item sum" for="services_{{ $item['id'] }}_3" onclick="activateField('services_{{ $item['id'] }}_price')" title="{{ __('admin/posts/post.title_services_type_3') }}"><i class="fas fa-plus"></i></label>
                                                                <input type="radio" id="services_{{ $item['id'] }}_4" class="d-none" name="services[{{ $item['id'] }}][condition]" value="4" {{ ($service_condition == 4) ? 'checked' : '' }} {{ ($service_condition == null) ? 'checked' : '' }}>
                                                                <label class="ex_services_block_item_radio_item ignoring" for="services_{{ $item['id'] }}_4" onclick="clearField('services_{{ $item['id'] }}_price')" title="{{ __('admin/posts/post.title_services_type_4') }}"><i class="fas fa-xmark"></i></label>
                                                            </div>
                                                            <div class="ex_services_block_item_title">{{ $item['title'] }}</div>
                                                        </div>
                                                        <div class="ex_services_block_item_price">
                                                            <input type="text" class="form-control form-control-sm" value="{{ $service_price }}" id="services_{{ $item['id'] }}_price" name="services[{{ $item['id'] }}][price]" onclick="activateRadio('services_{{ $item['id'] }}_3')" placeholder="{{ $data['currency_symbol'] }}" @if(empty($service_condition) || $service_condition == 1 || $service_condition == 2 || $service_condition == 4) disabled @endif>
                                                        </div>
                                                    </div>
                                                    <div class="ex_services_block_item_description" style="display:@if(!empty($service_condition) && $service_condition != 4) block @else none @endif">
                                                        <input type="text" class="form-control form-control-sm" id="textarea-services-{{ $item['id'] }}-description" name="services[{{ $item['id'] }}][description]" value="{{ $service_description }}" placeholder="{{ __('admin/posts/post.description_service') }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#block-image" role="tab" aria-controls="block-image" aria-selected="true"><i class="fas fa-camera"></i> {{ __('admin/posts/post.image_photo') }} <span id="counter-photos"></span></a>
                        </li>
                        @if($data['main_data']['selfie_status'])
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#block-selfie" role="tab" aria-controls="block-selfie" aria-selected="false"><i class="fas fa-image-portrait"></i> {{ __('admin/posts/post.image_selfie') }} <span id="counter-selfies"></span></a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#block-video" role="tab" aria-controls="block-video" aria-selected="false"><i class="fas fa-video"></i> {{ __('admin/posts/post.image_video') }} <span id="counter-videos"></span></a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="block-image" role="tabpanel">
                            <p>{{ $data['main_data']['photo_text'] }}</p>
                            <div class="ex-post-photo-all-media row">
                                @php $old_photos = old('images.photos') ?? $data['image_photos']; @endphp
                                @if(isset($old_photos) && !empty($old_photos) && is_array($old_photos))
                                    @foreach($old_photos as $photos)
                                        <div class="col-sm-2 col-4">
                                            <div class="ex_images_item_block" style="background-image: url('{{ $photos }}')"></div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if($data['main_data']['selfie_status'])
                            <div class="tab-pane" id="block-selfie" role="tabpanel">
                                <p>{{ $data['main_data']['selfie_text'] }}</p>
                                <div class="ex-post-selfie-all-media row">
                                    @php $old_selfies = old('images.selfies') ?? $data['image_selfies']; @endphp
                                    @if(isset($old_selfies) && !empty($old_selfies) && is_array($old_selfies))
                                        @foreach($old_selfies as $selfies)
                                            <div class="col-sm-2 col-4">
                                                <div class="ex_images_item_block" style="background-image: url('{{ $selfies }}')"></div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane" id="block-video" role="tabpanel">
                            <p>{{ $data['main_data']['video_text'] }}</p>
                            <div class="ex-post-video-all-media row">
                                @php $old_videos = old('videos') ?? $data['image_videos']; @endphp
                                @if(isset($old_videos) && !empty($old_videos) && is_array($old_videos))
                                    @foreach($old_videos as $videos)
                                        <div class="col-sm-2 col-4">
                                            <div class="ex_video_item_block">
                                                <video src="{{ $videos }}" controls style="width: 100%; height: 100%;"></video>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <script>
                            function showCountFiles() {
                                var photosLimitCount = {{ $data['main_data']['file_count_photo'] }};
                                var photosCounterBlock = '#counter-photos';
                                var photosMediaContainerId = '.ex-post-photo-all-media';
                                var photosBtnUpload = '#upload-post-photos';

                                @if($data['main_data']['selfie_status'])
                                var selfiesLimitCount = {{ $data['main_data']['file_count_selfie'] }};
                                var selfiesCounterBlock = '#counter-selfies';
                                var selfiesMediaContainerId = '.ex-post-selfie-all-media';
                                var selfiesBtnUpload = '#upload-post-selfies';
                                @endif

                                var videosLimitCount = {{ $data['main_data']['file_count_video'] }};
                                var videosCounterBlock = '#counter-videos';
                                var videosMediaContainerId = '.ex-post-video-all-media';
                                var videosBtnUpload = '#upload-post-videos';

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
                                @if($data['main_data']['selfie_status'])
                                updateCounter(selfiesMediaContainerId, selfiesCounterBlock, selfiesLimitCount, selfiesBtnUpload);
                                @endif
                                updateCounter(videosMediaContainerId, videosCounterBlock, videosLimitCount, videosBtnUpload);
                            }

                            showCountFiles();
                        </script>
                    </div>
                    <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#block-verify" role="tab" aria-controls="block-verify" aria-selected="false"><i class="fas fa-square-check"></i> {{ __('admin/posts/post.image_verify') }}</a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="block-verify" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="d-flex justify-content-center mb-2">
                                        <div id="upload-container-verify" class="upload-container" style="display: {{ $data['image_verify'] ? 'none' : 'flex' }}">
                                            <label for="image-upload-verify" class="upload-placeholder"><i class="fas fa-upload"></i> {{ __('buttons.upload_image') }}</label>
                                            <svg class="ex_upload_preview"><use xlink:href="#icon-preview"></use></svg>
                                            <input type="file" id="image-upload-verify" name="image_verify" accept=".png,.jpeg,.jpg" style="display:none;" onchange="handleUploadImage(event, 'verify')">
                                        </div>
                                        <div id="preview-container-verify" class="preview-container" style="display: {{ $data['image_verify'] ? 'flex' : 'none' }};">
                                            <img id="preview-image-verify" src="{{ $data['image_verify'] }}" alt="Preview" class="preview-img"/>
                                        </div>
                                        <input type="hidden" name="images[verify]" id="image-path-verify" value="{{ $data['image_verify'] }}">
                                    </div>
                                </div>
                                <div class="col-sm-9 col-12">
                                    {!! $data['main_data']['verify_description'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-3 col-12 mb-2">
                                <label class="form-label">{{ __('admin/posts/post.verify_status') }}</label>
                                @php $verify = old('verify') ?? ($data['verify'] ?? null); @endphp
                                <div class="space-x-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-verify-status-1" name="verify" value="0" {{ ($verify == 0) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-verify-status-1">{{ __('lang.status_off') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-verify-status-2" name="verify" value="1" {{ ($verify == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-verify-status-2">{{ __('lang.status_on') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-3 col-12 mb-2">
                                <label class="form-label">{{ __('admin/posts/post.moderation') }}</label>
                                @php $moderation_id = old('moderation_id') ?? ($data['moderation_id'] ?? null); @endphp
                                @php $moderation_text = old('moderation_text') ?? ($data['moderation_text'] ?? null); @endphp
                                <select class="form-select" name="moderation_id">
                                    @foreach($data['main_data']['moderation'] as $moder)
                                        <option value="{{ $moder['id'] }}" {{ ($moderation_id == $moder['id']) ? 'selected' : '' }}>{{ $moder['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">{{ __('admin/posts/post.moderation_txt') }}</label>
                                <textarea class="form-control" id="textarea-moderation-text" name="moderation_text" rows="4" placeholder="{{ __('admin/posts/post.moderation_txt') }}">{{ $moderation_text }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header d-flex justify-content-center">
                        <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
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
            const path = `/images/temp/posts/${random_id}/${type}`;
            formData.append('image', file);
            formData.append('path', path);
            fetch('/services/upload-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
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
            $('#input-phone').inputmask({
                mask: "+7 (999) 999-99-99",
                showMaskOnHover: true,
                showMaskOnFocus: true
            });
        });
    </script>
    <script>
        //Services
        function clearField(fieldId) {
            document.getElementById(fieldId).value = '';
            document.getElementById(fieldId).disabled = true;
        }
        function activateField(fieldId) {
            document.getElementById(fieldId).disabled = false;
        }
        function activateRadio(radioId) {
            document.getElementById(radioId).checked = true;
            activateField(radioId.replace('_price', '_price'));
        }
        document.querySelectorAll('input[name^="services["][name$="[condition]"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const itemId = this.name.match(/\[(\d+)\]/)[1];
                const descriptionInput = document.getElementById(`textarea-services-${itemId}-description`);
                if (this.value === '4') {
                    descriptionInput.value = '';
                    descriptionInput.parentElement.style.display = 'none';
                } else {
                    descriptionInput.parentElement.style.display = 'block';
                }
            });
        });
        //Services
    </script>
@endsection
