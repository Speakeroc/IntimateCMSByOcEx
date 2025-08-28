@if($data['new_year_mode'])
    <script src="{{ url('builder/catalog/js/particles.js') }}"></script>
    <div id="newYearModeSnow"></div>
@endif

<header class="container">
    <div class="ex_header position-relative">
        @if($data['new_year_mode'])
            <ul class="lightrope">
                <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
            </ul>
        @endif
        <div class="ex_header_top">
            <button class="ex_header_top_sidebar ex_open_sb" type="button"><svg class="ex_header_top_sidebar_svg"><use xlink:href="#icon-sidebar-btn"></use></svg></button>
            <div class="ex_header_top_logo">
                <a href="{{ route('client.index') }}">
                    <!--<img src="{{ url('logo.svg') }}" alt="Site logo" title="Site logo" class="ex_header_top_logo_img" height="40px" width="150px" style="height: auto;width: 100%;max-width:150px;">-->
                    <img src="{{ $data['logo'] }}" alt="Site logo" title="Site logo" class="ex_header_top_logo_img" height="40px" width="150px" style="height: auto;width: 100%;max-width:150px;">
                </a>
            </div>
            <div class="ex_header_top_items">
                <form action="{{ route('client.post.search') }}" method="get" class="position-relative ex_header_search ex_header_d_none">
                    <input type="search" name="name_or_desc" class="form-control ex_header_search_input" placeholder="{{ __('catalog/common/header.search') }}">
                    <button type="submit" class="ex_header_search_btn"><svg class="ex_header_search_svg"><use xlink:href="#icon-search"></use></svg></button>
                </form>
                <ul class="nav d-flex justify-content-start mb-md-0">
                    @if($data['header_display_zone'])
                        <li><a href="{{ route('client.zone.list') }}" class="nav-link px-2 text-white">
                                <svg class="ex_header_menu_svg"><use xlink:href="#icon-menu-location"></use></svg><span class="ex_header_d_none">{{ __('catalog/common/header.zone') }}</span>
                            </a>
                        </li>
                    @endif
                    @if($data['header_display_city'])
                        <li><a href="{{ route('client.city.list') }}" class="nav-link px-2 text-white">
                                <svg class="ex_header_menu_svg"><use xlink:href="#icon-menu-city"></use></svg><span class="ex_header_d_none">{{ __('catalog/common/header.city') }}</span>
                            </a>
                        </li>
                    @endif
                    @if($data['header_display_map'])
                        <li>
                            <a href="{{ route('client.post.map') }}" class="nav-link px-2 text-white">
                                <svg class="ex_header_menu_svg"><use xlink:href="#icon-menu-map"></use></svg><span class="ex_header_d_none">{{ __('catalog/common/header.map') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(!auth()->check())
                        <li class="ex_header_d_none"><a href="#" class="nav-link px-2 text-white" data-bs-toggle="modal" data-bs-target="#sign-in-modal"><svg class="ex_header_menu_svg"><use xlink:href="#icon-account"></use></svg></a></li>
                    @else
                        <li class="ex_header_d_none"><a href="#" class="nav-link px-2 text-white ex_open_sb"><svg class="ex_header_menu_svg"><use xlink:href="#icon-account"></use></svg></a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="ex_header_bottom ex_header_d_none">
            <ul class="nav col-12 d-flex justify-content-start mb-md-0">
                @foreach($data['menu'] as $item)
                    @if($item['status'])
                        <li><a href="{{ $item['link'] }}" class="nav-link px-2 text-white">{{ $item['title'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</header>

@if(!auth()->check())
    <div class="modal fade" id="sign-in-modal" tabindex="-1" aria-labelledby="sign-in-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ex-modal-content">
                <div class="modal-header ex-modal-header">
                    <div class="ex_mh_title">{{ __('catalog/common/header.modal_auth') }}</div>
                    <button type="button" class="ex_mh_btn" data-bs-dismiss="modal">
                        <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-close"></use></svg>
                    </button>
                </div>
                <div class="modal-body ex_auth_block">
                    <!-- Форма авторизации -->
                    <form id="sign-in" method="POST" action="{{ route('client.auth.popup') }}">
                        @csrf
                        <div class="col-12 mb-2">
                            <label for="input-email_or_login" class="form-label">{{ __('catalog/auth.email_or_login') }}</label>
                            <input type="text" name="email_or_login" id="input-email_or_login" value="{{ old('email_or_login') }}" class="form-control @error('email_or_login') is-invalid @enderror">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="input-password" class="form-label">{{ __('catalog/auth.password') }}</label>
                            <input type="password" name="password" id="input-password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="ex_auth_block_btn mb-2">{{ __('catalog/auth.btn_sing_in') }}</button>
                            <a href="{{ route('client.auth.forgot') }}" class="ex_auth_block_btn_reset">{{ __('catalog/auth.link_forgot') }}</a>
                            <span class="ex_auth_block_text_link">
                            {!! __('catalog/auth.go_to_signup', ['route' => route('client.auth.sign_up')]) !!}
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById('sign-in').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("{{ route('client.auth.popup') }}", {
            method: "POST",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json"},
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                $('#sign-in-modal').modal('hide');
                kbNotify('success', data.message)
                setTimeout(function () {
                    location.reload();
                }, 1500);
            } else {
                kbNotify('danger', data.message)
            }
        }).catch(error => console.error('Error:', error));
    });
</script>
@endif

<div class="ex_sidebar">
    <div class="ex_sidebar__main">
        <button class="ex_sidebar__close"><svg class="ex_sidebar__close_svg"><use xlink:href="#icon-close"></use></svg></button>
        <a href="{{ route('client.auth.post.create') }}" class="ex_sidebar__posts_add">{{ __('catalog/common/header.add_post') }}</a>
        @if(auth()->check())
            <div class="ex_sidebar__account">
                <div>
                    <a href="#" class="ex_sidebar__account_avatar">
                        <img src="{{ url('no_image.png') }}" alt="No image" title="No image">
                    </a>
                </div>
                <div class="ex_sidebar__account_info">
                    <span class="ex_sidebar__account_name">{{ $data['user']['name'] }} ({{ $data['user']['login'] }})</span>
                    <div class="ex_sidebar__account_balance">
                        <span>{{ __('catalog/common/header.you_balance') }}: <span class="ex_sidebar__account_balance_amount">{{ $data['user']['balance'] }}</span></span>
                        <a href="{{ route('client.auth.pay') }}" class="ex_sidebar__account_balance_btn">
                            <svg class="ex_sidebar__account_balance_btn_svg"><use xlink:href="#icon-wallet"></use></svg>
                            {{ __('catalog/common/header.payment') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="ex_sidebar__account p-2">
                <div class="ex_sidebar__account_links">
                    <div class="row">
                        @if(Auth::user()->can('access', 'view/admin_panel'))
                            <div class="col-12 col-md-12 col-xl-6 mb-2">
                                <a href="{{ route('admin.config') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-tahometr"></use></svg>{{ __('catalog/common/header.admin_panel') }}</a>
                            </div>
                        @endif
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.account') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-user"></use></svg>{{ __('catalog/common/header.profile') }}</a>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.posts') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-account-posts"></use></svg>{{ __('catalog/common/header.posts') }} {{ $data['user']['post_on'].'/'.$data['user']['post_all'] }}</a>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.salon') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-account-salon"></use></svg>{{ __('catalog/common/header.salon') }} {{ $data['user']['salon_on'].'/'.$data['user']['salon_all'] }}</a>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.blackList') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-post-phone"></use></svg>{{ __('catalog/common/header.black_list') }}</a>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.tickets') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-account-headset"></use></svg>{{ __('catalog/common/header.tickets') }}</a>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ route('client.auth.logout') }}" class="ex_sidebar__account_link"><svg class="ex_sidebar__account_link_svg"><use xlink:href="#icon-account-logout"></use></svg>{{ __('catalog/common/header.logout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="ex_sidebar__account">
                <div>
                    <a href="#" class="ex_sidebar__account_avatar">
                        <img src="{{ url('no_image.png') }}" alt="No image" title="No image">
                    </a>
                </div>
                <div class="ex_sidebar__account_info">
                    <div class="ex_sidebar__account_auth">
                        <span><a href="{{ route('client.auth.sign_in') }}" class="ex_side_auth_link">{{ __('catalog/common/header.auth') }}</a></span>
                    </div>
                </div>
            </div>
        @endif
        <div class="ex_sidebar__account p-2">
            <div class="ex_sidebar__account_links">
                <div class="row">
                    @foreach($data['menu'] as $item)
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ $item['link'] }}" class="ex_sidebar__account_link">{{ $item['title'] }}</a>
                        </div>
                    @endforeach
                    @foreach($data['menu_m'] as $item)
                        <div class="col-12 col-md-12 col-xl-6 mb-2">
                            <a href="{{ $item['link'] }}" class="ex_sidebar__account_link">{{ $item['title'] }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="ex_sidebar_search_block my-3">
            <h5 class="ex_block_mini_title">{{ __('catalog/common/header.search_title') }}</h5>
            <form action="{{ route('client.post.search') }}" method="get" class="position-relative ex_header_search">
                <input type="search" name="name_or_desc" class="form-control ex_header_search_input" placeholder="{{ __('catalog/common/header.search') }}">
                <button type="submit" class="ex_header_search_btn"><svg class="ex_header_search_svg"><use xlink:href="#icon-search"></use></svg></button>
            </form>
        </div>

        <!-- Services -->
        <div class="row my-3">
            @foreach($data['services'] as $service)
                <div class="col-12 col-md-6 col-xl-6">
                    <h5 class="ex_block_mini_title">{{ $service['title'] }}</h5>
                    @foreach($service['data'] as $item)
                        <a href="{{ $item['link'] }}" class="ex_all_services_item">{{ $item['title'] }}</a>
                    @endforeach
                </div>
            @endforeach
        </div>
        <!-- Services -->
    </div>
    <div class="ex_sidebar__bg"></div>
</div>

@if(isset($data['microdata']) && !empty($data['microdata']))
    <script type="application/ld+json">{!! $data['microdata'] !!}</script>
@endif

@if($data['new_year_mode'])
    <style>
        #newYearModeSnow{position:fixed;top:0;left:0;bottom:0;right:0;z-index:-10;overflow:hidden;}
    </style>
    <script>
        particlesJS("newYearModeSnow", {
            "particles": {
                "number": {
                    "value": 300,
                    "density": {
                        "enable": true,
                        "value_area": 1000
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "image",
                    "stroke": {
                        "width": 3,
                        "color": "#fff"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "{{ url('images/newYearMode/snowflake4.png') }}",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.3,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": .5,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 10,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": false,
                    "distance": 50,
                    "color": "#ffffff",
                    "opacity": 0.6,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "bottom",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": true,
                        "rotateX": 300,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": false,
                    },
                    "onclick": {
                        "enable": false,
                    },
                    "resize": false
                },
            },
            "retina_detect": true
        });
    </script>
@endif
