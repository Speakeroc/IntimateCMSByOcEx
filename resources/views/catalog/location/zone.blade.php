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
            @php $item_num = 0; @endphp
            @foreach($data['items'] as $item)
                @if($item_num > 0)
                    <hr>
                @endif
                <h5 class="ex_block_mini_title {{ ($item_num > 0) ? 'mt-4' : '' }}">{{ $item['title'] }}</h5>
                <div class="row">
                    @foreach($item['data'] as $i)
                        <div class="col-12 col-md-6 col-xl-3">
                            <a href="{{ $i['link'] }}" class="ex_all_services_item {{ ($i['count'] >= 1) ? 'ex_bb_color' : 'ex_bb_color_none' }}">{{ $i['title'] }} <small>({{ $i['posts'] }})</small></a>
                        </div>
                    @endforeach
                </div>
                @php $item_num++; @endphp
            @endforeach
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
