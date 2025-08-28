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
            <div class="ex_block_mini_title">{{ $data['post_title'] }}</div>
            @if($data['post_activation_status'] && !empty($data['post_variable']))
            <div class="ex_price_service_item activation">{{ __('catalog/info/priceServices.activation_t') }}</div>
            <p>{{ __('catalog/info/priceServices.activation_d') }}</p>
            <p class="m-0">{{ __('catalog/info/priceServices.price_days') }}</p>
            <div class="ex_price_service_ul">
                @foreach($data['post_variable'] as $post_var)
                    <div>- {!! $post_var['title'] !!}</div>
                @endforeach
            </div>
            <br>
            @endif

            <div class="ex_price_service_item diamond">{{ __('catalog/info/priceServices.diamind_t') }}</div>
            <p>{{ __('catalog/info/priceServices.diamind_d') }}</p>
            @if(!empty($data['diamond_act']))<p class="ex_price_services">{!! $data['diamond_act'] !!}</p>@endif
            @if(!empty($data['diamond_ext']))<p class="ex_price_services">{!! $data['diamond_ext'] !!}</p>@endif
            <br>
            <div class="ex_price_service_item vip">{{ __('catalog/info/priceServices.vip_t') }}</div>
            <p>{{ __('catalog/info/priceServices.vip_d') }}</p>
            @if(!empty($data['vip_act']))<p class="ex_price_services">{!! $data['vip_act'] !!}</p>@endif
            @if(!empty($data['vip_ext']))<p class="ex_price_services">{!! $data['vip_ext'] !!}</p>@endif
            <br>
            <div class="ex_price_service_item color">{{ __('catalog/info/priceServices.color_t') }}</div>
            <p>{{ __('catalog/info/priceServices.color_d') }}</p>
            @if(!empty($data['color_act']))<p class="ex_price_services">{!! $data['color_act'] !!}</p>@endif
            @if(!empty($data['color_ext']))<p class="ex_price_services">{!! $data['color_ext'] !!}</p>@endif
            <br>
            <div class="ex_price_service_item top">{{ __('catalog/info/priceServices.top_t') }}</div>
            <p>{{ __('catalog/info/priceServices.top_d') }}</p>
            @if(!empty($data['up_to_top']))<p class="ex_price_services">{!! $data['up_to_top'] !!}</p>@endif
        </div>

        <div class="ex_information_page_block">
            <div class="ex_block_mini_title">{{ $data['salon_title'] }}</div>
            @if($data['salon_activation_status'] && !empty($data['salon_variable']))
            <div class="ex_price_service_item activation">{{ __('catalog/info/priceServices.s_activation_t') }}</div>
            <p>{{ __('catalog/info/priceServices.s_activation_d') }}</p>
            <p class="m-0">{{ __('catalog/info/priceServices.price_days') }}</p>
            <div class="ex_price_service_ul">
                @foreach($data['salon_variable'] as $salon_var)
                    <div>- {!! $salon_var['title'] !!}</div>
                @endforeach
            </div>
            <br>
            @endif
            <div class="ex_price_service_item top">{{ __('catalog/info/priceServices.top_t') }}</div>
            <p>{{ __('catalog/info/priceServices.s_top_d') }}</p>
            @if(!empty($data['salon_up_to_top']))<p class="ex_price_services">{!! $data['salon_up_to_top'] !!}</p>@endif
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
