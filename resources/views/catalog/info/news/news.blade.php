@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @if(isset($data['microdata_article']) && !empty($data['microdata_article']))
        <script type="application/ld+json">{!! $data['microdata_article'] !!}</script>
    @endif
    @vite('resources/catalog/css/news.css')
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
        <h1 class="ex_news_page_title">{{ $data['title'] }}</h1>
        @if($data['image'])
        <div class="ex_news_page_block">
            <img src="{{ $data['image'] }}" alt="" class="ex_news_page_main_photo">
        </div>
        @endif

        <div class="ex_news_page_block">
            <div>{!! $data['desc'] !!}</div>
        </div>

        <div class="ex_news_page_block">
            <div class="ex_news_page_info">
                <div class="ex_news_page_info_item"><svg><use xlink:href="#icon-views"></use></svg> {{ $data['views'] }}</div>
                <div class="ex_news_page_info_item">
                    <button class="ex_news_page_info_item_btn sp_product_reviews_button_positive" data-type="like" data-news-id="{{ $data['id'] }}">
                        <svg><use xlink:href="#icon-like"></use></svg>
                        <span>{{ $data['like'] }}</span>
                    </button>
                </div>
                <div class="ex_news_page_info_item">
                    <button class="ex_news_page_info_item_btn sp_product_reviews_button_negative" data-type="dislike" data-news-id="{{ $data['id'] }}">
                        <svg><use xlink:href="#icon-dislike"></use></svg>
                        <span>{{ $data['dislike'] }}</span>
                    </button>
                </div>
                <div class="ex_news_page_info_item"><svg><use xlink:href="#icon-calendar"></use></svg> {{ $data['created_at'] }}</div>
            </div>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
            });
        });
        $(document).on('click', '.ex_news_page_info_item_btn', function(e) {
            var rate_button = $(this);
            var news_id = rate_button.attr('data-news-id');
            var rate_type = rate_button.attr('data-type');
            rate_button.prop('disabled', true);
            e.preventDefault();
            $.ajax({
                url: "{{ route('client.news.rate') }}",
                method: "POST",
                data: {news_id: news_id,type:rate_type},
                success: function (response) {
                    if (response.status === 'success') {
                        $('.sp_product_reviews_button_positive span').text(response.like);
                        $('.sp_product_reviews_button_negative span').text(response.dislike);
                    }
                    rate_button.prop('disabled', false);
                },
                error: function (xhr) {}
            });
        });
    </script>
@endsection
