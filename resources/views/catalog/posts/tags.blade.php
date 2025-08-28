@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
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

    <div class="container">
        <div class="ex_indiv_page_block">
            <div class="ex_all_tags_list">
                @foreach($data['tags'] as $tag)
                    <a href="{{ $tag['link'] }}" class="ex_all_tags_item">#{{ $tag['title'] }} <small>({{ $tag['posts'] }})</small></a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
