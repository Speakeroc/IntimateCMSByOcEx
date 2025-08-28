<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ffa500"/>
    <link rel="icon" href="{{ url('favicon.png') }}" sizes="any"/>
    <link rel="icon" href="{{ url('favicon.svg') }}" type="image/svg+xml"/>
    <link rel="apple-touch-icon" href="{{ url('fav_icon.png') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
    @vite(['resources/catalog/js/main.js'])
    @vite(['resources/catalog/css/bootstrap.css', 'resources/catalog/css/all.min.css', 'resources/catalog/css/main.css', 'resources/catalog/css/main_m.css', 'resources/catalog/css/notify.css', 'resources/catalog/css/header.css'])
    <script src="{{ url('/catalog/js/jquery_3_6_3.min.js') }}"></script>
    @yield('css_js_header')
    @csrf

</head>
<body class="bg-site">
@include('catalog/common/notify')
@yield('header')
@yield('content')
@yield('footer')
@vite(['resources/catalog/js/bootstrap.bundle.js'])
<script src="{{ url('/catalog/js/popper.min.js') }}"></script>
@yield('css_js_footer')
@include('catalog/common/icons')
<div id="ex-scroll-up-progress">
    <span id="ex-scroll-up-progress-value">
        <svg class="ex-scroll-up-progress-svg"><use xlink:href="#icon-arrow-top"></use></svg>
    </span>
</div>

<script>
    let calcScrollValue = () => {
        let scrollProgress = document.getElementById("ex-scroll-up-progress");
        let pos = document.documentElement.scrollTop;
        let calcHeight =
            document.documentElement.scrollHeight -
            document.documentElement.clientHeight;
        let scrollValue = Math.round((pos * 100) / calcHeight);
        if (pos > 100) {
            scrollProgress.style.display = "grid";
        } else {
            scrollProgress.style.display = "none";
        }
        scrollProgress.addEventListener("click", () => {
            document.documentElement.scrollTop = 0;
        });
        scrollProgress.style.background = `conic-gradient(#ffa500 ${scrollValue}%, #303030 ${scrollValue}%)`;
    };
    window.onscroll = calcScrollValue;
    window.onload = calcScrollValue;
</script>
</body>
</html>
