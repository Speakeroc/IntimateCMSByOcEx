<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @csrf
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    {!! SEOMeta::generate() !!}
    <meta name="description" content="Admin Panel">
    <meta name="author" content="OcEx">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="{{ url('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="{{ url('builder/admin/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ url('builder/admin/js/popper.min.js') }}"></script>
    @vite(['resources/admin/css/dashmix.css', 'resources/admin/css/all.min.css', 'resources/admin/css/main.css'])
    @vite('resources/admin/js/main.js')
    @yield('css_js_header')
</head>
<body>
<div id="page-container" class="sidebar-o {{ session('darkMode') == 'enabled' ? 'sidebar-dark' : '' }} enable-page-overlay side-scroll page-header-fixed side-trans-enabled page-header-dark {{ session('darkMode') == 'enabled' ? 'dark-mode' : '' }}">
    @include('admin/common/notify')
    @yield('header')
    @yield('sidebar')
    @yield('content')
    @yield('footer')
    @include('admin/common/icons')
</div>
@vite('resources/admin/js/dashmix.app.min.js')
@yield('css_js_footer')
<script>
    $('.ex_confirmation').click(function () {
        var res = confirm('{{ __('lang.confirmation') }}');
        if (!res) {
            return false;
        }
    });

    window.addEventListener('load', () => {
        const timing = performance.timing;
        const backendTime = timing.responseEnd - timing.requestStart;
        console.log(`Время ожидания ответа от сервера: ${backendTime} мс`);
    });

    const form_save_button = document.getElementById('form-save-button');
    if (form_save_button) {
        form_save_button.addEventListener('click', function () {
            const button = this;
            button.classList.add('disabled');
            setTimeout(() => {
                button.classList.remove('disabled');
            }, 2000);
        });
    }
</script>
</body>
</html>
