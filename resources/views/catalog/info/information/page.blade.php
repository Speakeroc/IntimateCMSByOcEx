@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/blocks/information.css')
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
        @if(!empty($data['items']))
            <div class="row">
                @foreach($data['items'] as $item)
                    {!! $item !!}
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center text-white ex_empty_content_height">{{ __('lang.empty_news_list') }}</div>
        @endif
        @if(isset($data['data']))
            <div class="d-block mt-4">
                {{ $data['data']->onEachSide(1)->links('catalog/common/pagination') }}
            </div>
        @endif
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
