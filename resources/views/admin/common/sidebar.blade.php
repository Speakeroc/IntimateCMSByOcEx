<aside id="side-overlay">
    <div class="bg-image" style="background-image: url('{{ url('images/admin/bg_side_overlay.jpg') }}');">
        <div class="bg-primary-op">
            <div class="content-header">
                <div class="ms-2">
                    <a class="text-white fw-semibold" href="#">{{ $data['name'] }}</a>
                    <div class="text-white-75 fs-sm">{{ $data['group'] }}</div>
                </div>
                <a class="ms-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="content-side">
        <div class="block block-transparent pull-x pull-t mb-0">
            <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="so-settings-tab" data-bs-toggle="tab" data-bs-target="#so-settings" role="tab" aria-controls="so-settings" aria-selected="true">
                        <i class="fa fa-fw fa-cog"></i>
                    </button>
                </li>
            </ul>
            <div class="block-content tab-content overflow-hidden">
                <div class="tab-pane pull-x fade fade-up show active" id="so-settings" role="tabpanel" aria-labelledby="so-settings-tab">
                    <div class="block mb-0">
                        <div class="block-content block-content-sm block-content-full bg-body p-3 d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fs-sm fw-bold">{{ __('admin/common/sidebar.site_cache') }}</span>
                            <span id="cacheTextInfo" class="badge rounded-pill bg-primary" style="display:none;"></span>
                        </div>
                        <div class="block-content block-content-full p-3">
                            <div class="block block-rounded">
                                <div class="block-header p-0">
                                    <h3 class="block-title d-flex align-items-center fs-7">{{ __('admin/common/sidebar.cache_image') }}</h3>
                                    <div class="block-options"><button type="button" id="btn_clear_cache_webp" class="btn btn-sm btn-primary" style="font-size:12px;" onclick="ex_clearCache('webp')"><i class="fa-solid fa-broom"></i> <span>{{ $data['cache_image_size'] }}</span></button></div>
                                </div>
                                <div class="block-header p-0 pt-3">
                                    <h3 class="block-title d-flex align-items-center fs-7">{{ __('admin/common/sidebar.cache_temp_image') }}</h3>
                                    <div class="block-options"><button type="button" id="btn_clear_cache_temp" class="btn btn-sm btn-primary" style="font-size:12px;" onclick="ex_clearCache('temp')"><i class="fa-solid fa-broom"></i> <span>{{ $data['cache_temp_image_size'] }}</span></button></div>
                                </div>
                                <div class="block-header p-0 pt-3">
                                    <h3 class="block-title d-flex align-items-center fs-7">{{ __('admin/common/sidebar.cache_log') }}</h3>
                                    <div class="block-options"><button type="button" id="btn_clear_cache_logs" class="btn btn-sm btn-primary" style="font-size:12px;" onclick="ex_clearCache('logs')"><i class="fa-solid fa-broom"></i> <span>{{ $data['cache_log_files_size'] }}</span></button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<script>
    function ex_clearCache(type) {
        $.ajax({
            url: '/services/clear-cache',
            type: 'DELETE',
            data: { type_link: type },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.message);
                $('#cacheTextInfo').show();
                $('#cacheTextInfo').html(response.message);
                $('#btn_clear_cache_'+type+' span').html(response.size);
                setTimeout(function () {
                    $('#cacheTextInfo').hide();
                },3000)
            },
            error: function(xhr) {
                console.log(xhr.responseJSON.message);
            }
        });
    }
</script>

<script>
    //Dark Mode
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('dark-mode-toggle');
        const pageContainer = document.getElementById('page-container');
        const togglerIcon = document.getElementById('dark-mode-toggler');
        function toggleDarkMode() {
            const darkModeEnabled = pageContainer.classList.toggle('dark-mode');
            const darkModeSideBar = pageContainer.classList.toggle('sidebar-dark');
            if (darkModeEnabled && darkModeSideBar) {
                togglerIcon.classList.remove('far', 'fa-moon');
                togglerIcon.classList.add('fas', 'fa-moon');
            } else {
                togglerIcon.classList.remove('fas', 'fa-moon');
                togglerIcon.classList.add('far', 'fa-moon');
            }
            fetch('/toggle-dark-mode', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ darkMode: darkModeEnabled ? 'enabled' : 'disabled' })
            });
        }
        if (pageContainer.classList.contains('dark-mode')) {
            togglerIcon.classList.remove('far', 'fa-moon');
            togglerIcon.classList.add('fas', 'fa-moon');
        }
        toggleButton.addEventListener('click', toggleDarkMode);
    });
    //Dark Mode
</script>

<nav id="sidebar">
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
            <a class="fw-semibold text-white tracking-wide" href="{{ route('admin.config') }}">
                <span class="smini-visible">IC</span>
                <span class="smini-hidden">Intimate<span class="opacity-75">CMS</span></span>
            </a>
            <div>
                <button type="button" class="btn btn-sm btn-alt-secondary" id="dark-mode-toggle" data-toggle="class-toggle" data-target="#dark-mode-toggler" data-class="far fa">
                    <i class="far fa-moon" id="dark-mode-toggler"></i>
                </button>
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">
                @foreach($data['menu'] as $menu)

                    @if($menu['type'] == 'section')
                        @if(!empty($menu['display']))
                            @if(!empty($menu['block_title']))
                                <li class="nav-main-heading">{{ $menu['block_title'] }}</li>
                            @endif
                            @if(isset($menu['menu']))
                                @foreach($menu['menu'] as $menus)
                                    @if(isset($menus['permission']) && $menus['permission'])
                                        <li class="nav-main-item">
                                            <a class="nav-main-link{{ (request()->is($menus['path'].'*')) ? ' active' : '' }}" href="{{ $menus['link'] }}">
                                                <i class="nav-main-link-icon {{ $menus['icon'] }}"></i>
                                                <span class="nav-main-link-name">{{ $menus['title'] }}</span>
                                                @if(isset($menus['counter']) && $menus['counter'])
                                                    <span class="nav-main-link-badge badge rounded-pill bg-primary" style="margin:0;">{{ $menus['counter'] ?? null }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
