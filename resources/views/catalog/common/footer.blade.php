<footer class="container">
    <div class="p-3 mt-3 ex_footer position-relative">
        @if($data['new_year_mode'])
            <ul class="lightrope">
                <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
            </ul>
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="{{ $data['logo'] }}" alt="" class="ex_header_logo_img" height="40px" width="150px" style="height: auto;width: 100%;max-width:150px;">
            </a>
            <ul class="nav col-12 col-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="mailto:{{ $data['support_email'] }}" class="nav-link px-2 text-white">{{ $data['support_email'] }}</a></li>
            </ul>
        </div>
        <ul class="nav col-12 col-lg-auto me-lg-auto flex-wrap mb-2 justify-content-center mb-md-0">
            @foreach($data['menu'] as $item)
                <li><a href="{{ $item['link'] }}" class="nav-link px-2 text-white">{{ $item['title'] }}</a></li>
            @endforeach
        </ul>
        @if($data['social_links'])
        <hr>
        <div class="d-flex flex-wrap align-items-center justify-content-center">
            <ul class="nav col-12 col-lg-auto mb-2 justify-content-center mb-md-0">
                @foreach($data['social_links'] as $social_link)
                    <li><a href="{{ $social_link['link'] }}" class="nav-link p-2 text-white"><svg class="ex_footer_social_icon i-{{ $social_link['social'] }}"><use xlink:href="#icon-{{ $social_link['social'] }}"></use></svg></a></li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</footer>
{!! $data['custom_js'] !!}

@if($data['age_detect'])
    <!-- Age Detector -->
    <div class="modal fade" id="age-detected" tabindex="-1" aria-labelledby="age-detected-Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header ex-modal-header">
                    <div class="ex_mh_title">{{ __('catalog/common/footer.age_detec_title') }}</div>
                    <button type="button" class="ex_mh_btn" data-bs-dismiss="modal">
                        <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-close"></use></svg>
                    </button>
                </div>
                <div class="modal-body ex_auth_block">
                    <span style="display:flex;flex-direction:row;align-items:center;justify-content:center;color:#ffa500;font-size:40px;font-weight:700;">18+</span>
                    {{ __('catalog/common/footer.age_detec_body') }}
                </div>
                <div class="modal-footer ex-modal-footer">
                    <div class="d-flex align-content-center justify-content-between w-100 gap-3">
                        <button type="button" id="age-detected-never" class="main-btn-style" data-bs-dismiss="modal">{{ __('catalog/common/footer.age_detec_yes') }}</button>
                        <a href="https://google.com/" class="main-btn-style">{{ __('catalog/common/footer.age_detec_no') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        if (!document.cookie.includes('ageDetected')) {
            setTimeout(() => {
                $('#age-detected').modal('show');
            }, 1000);
        }

        document.getElementById('age-detected-never').addEventListener('click', () => {
            document.cookie = 'ageDetected=true; max-age=3153600';
            $('#age-detected').modal('hide');
        });
    </script>
    <!-- ./Age Detector -->
@endif

@if($data['subscribe_status'])
    <!-- Subscribe -->
    <div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModal-Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header ex-modal-header">
                    <div class="ex_mh_title">{!! $data['subscribe_title'] !!}</div>
                    <button type="button" class="ex_mh_btn" data-bs-dismiss="modal">
                        <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-close"></use></svg>
                    </button>
                </div>
                <div class="modal-body ex_auth_block">
                    {!! $data['subscribe_text'] !!}
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-sm-between justify-content-center ex-modal-footer">
                    <a href="{{ $data['subscribe_btn_link'] }}" id="subscribeModal-link" target="_blank" class="main-btn-style fs-13" style="{{ ($data['subscribe_btn_color']) ? 'background:'.$data["subscribe_btn_color"].' !important;border-color:'.$data["subscribe_btn_color"].' !important;' : '' }}{{ ($data['subscribe_btn_color_t']) ? 'color:'.$data["subscribe_btn_color_t"].' !important;' : '' }}">{{ $data['subscribe_btn_title'] }}</a>
                    <div>
                        <button type="button" id="subscribeModal-later" class="main-btn-style fs-13" data-bs-dismiss="modal">{{ __('buttons.btn_later') }}</button>
                        <button type="button" id="subscribeModal-never" class="main-btn-style fs-13" data-bs-dismiss="modal">{{ __('buttons.btn_do_not_show') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const subscribeModal = document.getElementById('subscribeModal');
        document.addEventListener('DOMContentLoaded', function () {
            if (!document.cookie.includes('subscribeModal')) {
                if (document.cookie.includes('ageDetected') && !document.cookie.includes('subscribeModalTimeout')) {
                    $('#subscribeModal').modal('show');
                }
            }
            document.getElementById('subscribeModal-link').addEventListener('click', () => {
                document.cookie = 'subscribeModal=true; max-age=31536000';
                $('#subscribeModal').modal('hide');
            });

            document.getElementById('subscribeModal-later').addEventListener('click', () => {
                document.cookie = 'subscribeModalTimeout=7200';
                $('#subscribeModal').modal('hide');
            });

            document.getElementById('subscribeModal-never').addEventListener('click', () => {
                document.cookie = 'subscribeModalTimeout=604800';
                $('#subscribeModal').modal('hide');
            });
        });
    </script>
    <!-- ./Subscribe -->
@endif
