@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/ex_select_checkbox.css')
    @vite('resources/catalog/js/ex_select_checkbox.js')
    @vite('resources/catalog/css/jquery-ui.css')
    <script type="text/javascript" src="{{ url('/catalog/js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script>
        var doesnt_matter = '{{ __('catalog/posts/search.doesnt_matter') }}';
    </script>
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
    </div>

    @if(isset($data['posts']['filtered']) && $data['posts']['filtered'] == 0)
    <div class="container">
        <form action="{{ route('client.post.search') }}" method="get">
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-xl-3">
                    <h4 class="ex_block_middle_title">{{ __('catalog/posts/search.section_main') }}</h4>
                    <div class="mb-2">
                        <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.section') }}</label>
                        <div id="select-sections" class="ex_select_checkbox_block">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.section') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $section_count = 0; @endphp
                                @foreach($data['search_data']['sections'] as $section)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="section[]" value="{{ $section }}" id="section-{{ $section_count }}">
                                            <label class="form-check-label" for="section-{{ $section_count }}">{{ __('catalog/posts/search.section_' . $section) }}</label>
                                        </div>
                                    </div>
                                    @php $section_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1 @if(empty($data['search_data']['zone'])) d-none @endif">{{ __('catalog/posts/search.zone') }}</label>
                    <div class="mb-2">
                        <div id="select-zone" class="ex_select_checkbox_block @if(empty($data['search_data']['zone'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.zone') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $zone_count = 0; @endphp
                                @foreach($data['search_data']['zone'] as $zone)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="zone[]" value="{{ $zone['id'] }}" id="zones-{{ $zone_count }}">
                                            <label class="form-check-label" for="zones-{{ $zone_count }}">{{ $zone['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $zone_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1 @if(empty($data['search_data']['metro'])) d-none @endif">{{ __('catalog/posts/search.metro') }}</label>
                    <div class="mb-2">
                        <div id="select-metro" class="ex_select_checkbox_block @if(empty($data['search_data']['metro'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.metro') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $metro_count = 0; @endphp
                                @foreach($data['search_data']['metro'] as $metro)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="metro[]" value="{{ $metro['id'] }}" id="metro-{{ $metro_count }}">
                                            <label class="form-check-label" for="metro-{{ $metro_count }}">{{ $metro['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $metro_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1 @if(empty($data['search_data']['tags'])) d-none @endif">{{ __('catalog/posts/search.tags') }}</label>
                    <div class="mb-2">
                        <div id="select-tags" class="ex_select_checkbox_block @if(empty($data['search_data']['tags'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.tags') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $tag_count = 0; @endphp
                                @foreach($data['search_data']['tags'] as $tag)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag['id'] }}" id="tags-{{ $tag_count }}">
                                            <label class="form-check-label" for="tags-{{ $tag_count }}">{{ $tag['tag'] }}</label>
                                        </div>
                                    </div>
                                    @php $tag_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.language_skills') }}</label>
                    <div class="mb-2">
                        <div id="select-language-skills" class="ex_select_checkbox_block @if(empty($data['search_data']['language_skills'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.language_skills') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $language_count = 0; @endphp
                                @foreach($data['search_data']['language_skills'] as $language)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="language_skills[]" value="{{ $language['id'] }}" id="language-skills-{{ $language_count }}">
                                            <label class="form-check-label" for="language-skills-{{ $language_count }}">{{ $language['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $language_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.visit_places') }}</label>
                    <div class="mb-2">
                        <div id="select-visit-places" class="ex_select_checkbox_block @if(empty($data['search_data']['visit_places'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.visit_places') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $visit_count = 0; @endphp
                                @foreach($data['search_data']['visit_places'] as $visit)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="visit_places[]" value="{{ $visit['id'] }}" id="visit-place-{{ $visit_count }}">
                                            <label class="form-check-label" for="visit-place-{{ $visit_count }}">{{ $visit['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $visit_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-check cursor-pointer mb-2">
                        <input class="form-check-input" type="checkbox" name="express" value="1" id="express">
                        <label class="form-check-label cursor-pointer w-100 text-white" for="express">{{ __('catalog/posts/search.express') }}</label>
                    </div>

                    <div class="form-check cursor-pointer mb-2">
                        <input class="form-check-input" type="checkbox" name="health" value="1" id="health">
                        <label class="form-check-label cursor-pointer w-100 text-white" for="health">{{ __('catalog/posts/search.health') }}</label>
                    </div>

                    <div class="form-check cursor-pointer mb-2">
                        <input class="form-check-input" type="checkbox" name="apartment" value="1" id="apartment">
                        <label class="form-check-label cursor-pointer w-100 text-white" for="apartment">{{ __('catalog/posts/search.apartment') }}</label>
                    </div>

                    <div class="form-check cursor-pointer mb-2">
                        <input class="form-check-input" type="checkbox" name="arrival" value="1" id="arrival">
                        <label class="form-check-label cursor-pointer w-100 text-white" for="arrival">{{ __('catalog/posts/search.arrival') }}</label>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <h4 class="ex_block_middle_title">{{ __('catalog/posts/search.section_params') }}</h4>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.age') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="age_min" value="18" id="age_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="age_max" value="99" id="age_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="age-slider" class="mx-2"></div></div>
                    </div>

                    <label class="form-check-label text-white mb-1 mt-2">{{ __('catalog/posts/search.height') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="height_min" value="120" id="height_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="height_max" value="220" id="height_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="height-slider" class="mx-2"></div></div>
                    </div>

                    <label class="form-check-label text-white mb-1 mt-2">{{ __('catalog/posts/search.weight') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="weight_min" value="30" id="weight_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="weight_max" value="100" id="weight_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="weight-slider" class="mx-2"></div></div>
                    </div>

                    <label class="form-check-label text-white mb-1 mt-2">{{ __('catalog/posts/search.breast') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="breast_min" value="1" id="breast_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="breast_max" value="12" id="breast_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="breast-slider" class="mx-2"></div></div>
                    </div>

                    <label class="form-check-label text-white mb-1 mt-2">{{ __('catalog/posts/search.shoes') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="shoes_min" value="30" id="shoes_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="shoes_max" value="46" id="shoes_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="shoes-slider" class="mx-2"></div></div>
                    </div>

                    <label class="form-check-label text-white mb-1 mt-2">{{ __('catalog/posts/search.cloth') }}</label>
                    <div class="row">
                        <div class="col-5"><input type="text" name="cloth_min" value="25" id="cloth_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="cloth_max" value="60" id="cloth_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="cloth-slider" class="mx-2"></div></div>
                    </div>

                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <h4 class="ex_block_middle_title">{{ __('catalog/posts/search.section_national') }}</h4>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.nationality') }}</label>
                    <div class="mb-2">
                        <div id="select-nationality" class="ex_select_checkbox_block @if(empty($data['search_data']['nationality'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.nationality') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $nationality_count = 0; @endphp
                                @foreach($data['search_data']['nationality'] as $nationality)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nationality[]" value="{{ $nationality['id'] }}" id="nationality-{{ $nationality_count }}">
                                            <label class="form-check-label" for="nationality-{{ $nationality_count }}">{{ $nationality['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $nationality_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.body_type') }}</label>
                    <div class="mb-2">
                        <div id="select-body-type" class="ex_select_checkbox_block @if(empty($data['search_data']['body_type'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.body_type') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $body_type_count = 0; @endphp
                                @foreach($data['search_data']['body_type'] as $body_type)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="body_type[]" value="{{ $body_type['id'] }}" id="body-type-{{ $body_type_count }}">
                                            <label class="form-check-label" for="body-type-{{ $body_type_count }}">{{ $body_type['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $body_type_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.hair_color') }}</label>
                    <div class="mb-2">
                        <div id="select-hair-color" class="ex_select_checkbox_block @if(empty($data['search_data']['hair_color'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.hair_color') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $hair_color_count = 0; @endphp
                                @foreach($data['search_data']['hair_color'] as $hair_color)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hair_color[]" value="{{ $hair_color['id'] }}" id="hair-color-{{ $hair_color_count }}">
                                            <label class="form-check-label" for="hair-color-{{ $hair_color_count }}">{{ $hair_color['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $hair_color_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.hairy') }}</label>
                    <div class="mb-2">
                        <div id="select-hairy" class="ex_select_checkbox_block @if(empty($data['search_data']['hairy'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.hairy') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $hairy_count = 0; @endphp
                                @foreach($data['search_data']['hairy'] as $hairy)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hairy[]" value="{{ $hairy['id'] }}" id="hairy-{{ $hairy_count }}">
                                            <label class="form-check-label" for="hairy-{{ $hairy_count }}">{{ $hairy['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $hairy_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.body_art') }}</label>
                    <div class="mb-2">
                        <div id="select-body-art" class="ex_select_checkbox_block @if(empty($data['search_data']['body_art'])) d-none @endif">
                            <div class="ex_select_checkbox_title">{{ __('catalog/posts/search.body_art') }}: <span>{{ __('catalog/posts/search.doesnt_matter') }}</span></div>
                            <div class="ex_select_checkbox_list">
                                @php $body_art_count = 0; @endphp
                                @foreach($data['search_data']['body_art'] as $body_art)
                                    <div class="ex_select_checkbox_item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="body_art[]" value="{{ $body_art['id'] }}" id="body-art-{{ $body_art_count }}">
                                            <label class="form-check-label" for="body-art-{{ $body_art_count }}">{{ $body_art['title'] }}</label>
                                        </div>
                                    </div>
                                    @php $body_art_count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <h4 class="ex_block_middle_title">{{ __('catalog/posts/search.section_prices') }}</h4>

                    <h5 class="ex_block_mini_title">{{ __('catalog/posts/search.day') }}</h5>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_in', ['currency' => $data['currency_symbol'], 'hour' => 1]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_day_in_one_min" value="{{ $data['search_data']['price_day_in_one_min'] }}" id="price_day_in_one_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_day_in_one_max" value="{{ $data['search_data']['price_day_in_one_max'] }}" id="price_day_in_one_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_day_in_one-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_in', ['currency' => $data['currency_symbol'], 'hour' => 2]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_day_in_two_min" value="{{ $data['search_data']['price_day_in_two_min'] }}" id="price_day_in_two_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_day_in_two_max" value="{{ $data['search_data']['price_day_in_two_max'] }}" id="price_day_in_two_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_day_in_two-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_out', ['currency' => $data['currency_symbol'], 'hour' => 1]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_day_out_one_min" value="{{ $data['search_data']['price_day_out_one_min'] }}" id="price_day_out_one_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_day_out_one_max" value="{{ $data['search_data']['price_day_out_one_max'] }}" id="price_day_out_one_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_day_out_one-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_out', ['currency' => $data['currency_symbol'], 'hour' => 2]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_day_out_two_min" value="{{ $data['search_data']['price_day_out_two_min'] }}" id="price_day_out_two_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_day_out_two_max" value="{{ $data['search_data']['price_day_out_two_max'] }}" id="price_day_out_two_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_day_out_two-slider" class="mx-2"></div></div>
                    </div>
                    <hr>
                    <h5 class="ex_block_mini_title">{{ __('catalog/posts/search.night') }}</h5>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_in', ['currency' => $data['currency_symbol'], 'hour' => 1]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_night_in_one_min" value="{{ $data['search_data']['price_night_in_one_min'] }}" id="price_night_in_one_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_night_in_one_max" value="{{ $data['search_data']['price_night_in_one_max'] }}" id="price_night_in_one_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_night_in_one-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_night', ['currency' => $data['currency_symbol'], 'hour' => 2]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_night_in_night_min" value="{{ $data['search_data']['price_night_in_night_min'] }}" id="price_night_in_night_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_night_in_night_max" value="{{ $data['search_data']['price_night_in_night_max'] }}" id="price_night_in_night_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_night_in_night-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_time_out', ['currency' => $data['currency_symbol'], 'hour' => 1]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_night_out_one_min" value="{{ $data['search_data']['price_night_out_one_min'] }}" id="price_night_out_one_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_night_out_one_max" value="{{ $data['search_data']['price_night_out_one_max'] }}" id="price_night_out_one_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_night_out_one-slider" class="mx-2"></div></div>
                    </div>
                    <label class="form-check-label text-white mb-1">{{ __('catalog/posts/search.price_night', ['currency' => $data['currency_symbol'], 'hour' => 2]) }}</label>
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="price_night_out_night_min" value="{{ $data['search_data']['price_night_out_night_min'] }}" id="price_night_out_night_min" class="form-control" placeholder="{{ __('lang.min') }}"></div>
                        <div class="col-2 text-center text-white">-</div>
                        <div class="col-5"><input type="text" name="price_night_out_night_max" value="{{ $data['search_data']['price_night_out_night_max'] }}" id="price_night_out_night_max" class="form-control" placeholder="{{ __('lang.max') }}"></div>
                        <div class="col-12"><div id="price_night_out_night-slider" class="mx-2"></div></div>
                    </div>

                </div>
            </div>

            <h4 class="ex_block_middle_title">{{ __('catalog/posts/search.section_services') }}</h4>
            <div class="row">
                @foreach($data['search_data']['services'] as $service)
                    <div class="col-12 col-md-6 col-xl-3">
                        <h5 class="ex_block_mini_title">{{ $service['title'] }}</h5>
                        @foreach($service['data'] as $item)
                            <div class="form-check cursor-pointer mb-2">
                                <input class="form-check-input" type="checkbox" name="services[]" value="{{ $item['id'] }}" id="service-{{ $item['id'] }}">
                                <label class="form-check-label cursor-pointer w-100 text-white" for="service-{{ $item['id'] }}">{{ $item['title'] }}</label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="ex_search_buttons">
                <button type="submit" class="ex_search_button">Найти</button>
                <a href="{{ route('client.post.search') }}" class="ex_search_button">Очистить</a>
            </div>
        </form>
    </div>
    @endif


    @if(isset($data['posts']['filtered']) && $data['posts']['filtered'] >= 1)
    <div class="container">
        @if(!empty($data['posts']['items']))
            <div class="row">
                @foreach($data['posts']['items'] as $item)
                    {!! $item !!}
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center text-white ex_empty_content_height">{{ __('lang.empty_post_list') }}</div>
        @endif
        @if(isset($data['posts']['data']))
            <div class="d-block mt-4">
                {{ $data['posts']['data']->onEachSide(1)->links('catalog/common/pagination') }}
            </div>
        @endif
    </div>
    @endif
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        function initialSliders(slider_block_id, min, max, val_min, val_max, input_min, input_max) {
            var slider = $("#" + slider_block_id);
            slider.slider({
                range: true,
                min: min,
                max: max,
                values: [val_min, val_max],
                slide: function (event, ui) {
                    $("#" + input_min).val(ui.values[0]);
                    $("#" + input_max).val(ui.values[1]);
                },
                touchmove: function (event) {
                    var touches = event.originalEvent.touches;
                    if (touches && touches.length) {
                        var percent = ((touches[0].clientX - slider.offset().left) / slider.width()) * 100;
                        var value = (max - min) * percent / 100 + min;
                        var index = ui.handleIndex;
                        ui.values[index] = Math.round(value);
                        $("#" + input_min).val(ui.values[0]);
                        $("#" + input_max).val(ui.values[1]);
                        var $handles = $(this).find('.ui-slider-handle');
                        var handle1 = $handles.first();
                        var handle2 = $handles.last();
                        handle1.css('left', ui.values[0] + '%');
                        handle2.css('left', ui.values[1] + '%');
                    }
                }
            });
        }
        initialSliders('age-slider', 18, 99, 18, 99, 'age_min', 'age_max')
        initialSliders('height-slider', 120, 220, 120, 220, 'height_min', 'height_max')
        initialSliders('weight-slider', 30, 100, 30, 100, 'weight_min', 'weight_max')
        initialSliders('breast-slider', 1, 12, 1, 12, 'breast_min', 'breast_max')
        initialSliders('shoes-slider', 30, 46, 30, 46, 'shoes_min', 'shoes_max')
        initialSliders('cloth-slider', 25, 60, 25, 60, 'cloth_min', 'cloth_max')

        //Prices
        initialSliders('price_day_in_one-slider', {{ $data['search_data']['price_day_in_one_min'] }}, {{ $data['search_data']['price_day_in_one_max'] }},'{{ $data['price_day_in_one_min'] ?? $data['search_data']['price_day_in_one_min'] }}','{{ $data['price_day_in_one_max'] ?? $data['search_data']['price_day_in_one_max'] }}','price_day_in_one_min','price_day_in_one_max')
        initialSliders('price_day_in_two-slider', {{ $data['search_data']['price_day_in_two_min'] }}, {{ $data['search_data']['price_day_in_two_max'] }},'{{ $data['price_day_in_two_min'] ?? $data['search_data']['price_day_in_two_min'] }}','{{ $data['price_day_in_two_max'] ?? $data['search_data']['price_day_in_two_max'] }}','price_day_in_two_min','price_day_in_two_max')
        initialSliders('price_day_out_one-slider', {{ $data['search_data']['price_day_out_one_min'] }}, {{ $data['search_data']['price_day_out_one_max'] }},'{{ $data['price_day_out_one_min'] ?? $data['search_data']['price_day_out_one_min'] }}','{{ $data['price_day_out_one_max'] ?? $data['search_data']['price_day_out_one_max'] }}','price_day_out_one_min','price_day_out_one_max')
        initialSliders('price_day_out_two-slider', {{ $data['search_data']['price_day_out_two_min'] }}, {{ $data['search_data']['price_day_out_two_max'] }},'{{ $data['price_day_out_two_min'] ?? $data['search_data']['price_day_out_two_min'] }}','{{ $data['price_day_out_two_max'] ?? $data['search_data']['price_day_out_two_max'] }}','price_day_out_two_min','price_day_out_two_max')
        initialSliders('price_night_in_one-slider', {{ $data['search_data']['price_night_in_one_min'] }}, {{ $data['search_data']['price_night_in_one_max'] }},'{{ $data['price_night_in_one_min'] ?? $data['search_data']['price_night_in_one_min'] }}','{{ $data['price_night_in_one_max'] ?? $data['search_data']['price_night_in_one_max'] }}','price_night_in_one_min','price_night_in_one_max')
        initialSliders('price_night_in_night-slider', {{ $data['search_data']['price_night_in_night_min'] }}, {{ $data['search_data']['price_night_in_night_max'] }},'{{ $data['price_night_in_night_min'] ?? $data['search_data']['price_night_in_night_min'] }}','{{ $data['price_night_in_night_max'] ?? $data['search_data']['price_night_in_night_max'] }}','price_night_in_night_min','price_night_in_night_max')
        initialSliders('price_night_out_one-slider', {{ $data['search_data']['price_night_out_one_min'] }}, {{ $data['search_data']['price_night_out_one_max'] }},'{{ $data['price_night_out_one_min'] ?? $data['search_data']['price_night_out_one_min'] }}','{{ $data['price_night_out_one_max'] ?? $data['search_data']['price_night_out_one_max'] }}','price_night_out_one_min','price_night_out_one_max')
        initialSliders('price_night_out_night-slider', {{ $data['search_data']['price_night_out_night_min'] }}, {{ $data['search_data']['price_night_out_night_max'] }},'{{ $data['price_night_out_night_min'] ?? $data['search_data']['price_night_out_night_min'] }}','{{ $data['price_night_out_night_max'] ?? $data['search_data']['price_night_out_night_max'] }}','price_night_out_night_min','price_night_out_night_max')
    </script>
@endsection
