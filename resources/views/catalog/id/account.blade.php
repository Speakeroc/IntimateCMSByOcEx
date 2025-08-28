@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/id/account.css')
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
        <h1 class="ex_post_page_title">{{ $data['title'] }}</h1>

        <div class="ex_account_page_block">
            <div class="row">
                <div class="col-12 col-md-6 col-xl-9 mb-2">
                    <form id="changeUserForm">
                        @csrf
                        <div class="mb-3">
                            <label for="account-username" class="form-label">{{ __('catalog/id/account.form_name') }}</label>
                            <input type="text" name="username" class="form-control" id="account-username" value="{{ $data['user']['name'] }}" placeholder="{{ __('catalog/id/account.form_name') }}">
                            <input type="hidden" name="old_name" value="{{ $data['user']['name'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="account-login" class="form-label">{{ __('catalog/id/account.form_login') }}</label>
                            <input type="text" name="login" class="form-control" id="account-login" value="{{ $data['user']['login'] }}" placeholder="{{ __('catalog/id/account.form_login') }}">
                            <input type="hidden" name="old_login" value="{{ $data['user']['login'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="account-email" class="form-label">{{ __('catalog/id/account.form_email') }}</label>
                            <input type="text" class="form-control" id="account-email" value="{{ $data['user']['email'] }}" placeholder="{{ __('catalog/id/account.form_email') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_post_help" value="1" id="account-allow-post-help" {{ ($data['user']['allow_post_help'] == 1) ? 'checked' : '' }}>
                                <label class="form-check-label fs-14" for="account-allow-post-help">{{ __('catalog/id/account.form_allow_post_help') }}</label>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-end">
                            <button type="button" class="btn btn-warning ex_account_btn" id="saveUserBtn">{{ __('buttons.save') }}</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-6 col-xl-3 mb-2">
                    <form id="changePasswordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="account-curr-pass" class="form-label">{{ __('catalog/id/account.form_curr_pass') }}</label>
                            <input type="password" name="curr_pass" class="form-control" id="account-curr-pass" placeholder="{{ __('catalog/id/account.form_curr_pass') }}">
                            <div id="error-curr-pass" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="account-new-pass" class="form-label">{{ __('catalog/id/account.form_new_pass') }}</label>
                            <input type="password" name="new_pass" class="form-control" id="account-new-pass" placeholder="{{ __('catalog/id/account.form_new_pass') }}">
                            <div id="error-new-pass" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="account-new-pass-confirm" class="form-label">{{ __('catalog/id/account.form_new_pass_conf') }}</label>
                            <input type="password" name="new_pass_confirmation" class="form-control" id="account-new-pass-confirm" placeholder="{{ __('catalog/id/account.form_new_pass_conf') }}">
                            <div id="error-new-pass-confirm" class="text-danger"></div>
                        </div>
                        <div class="mb-3 d-flex justify-content-end">
                            <button type="button" class="btn btn-warning ex_account_btn" id="savePasswordBtn">{{ __('buttons.save') }}</button>
                        </div>
                        <div id="success-message" class="text-success"></div>
                    </form>
                </div>
            </div>

            @if(isset($data['transaction']) && !empty($data['transaction']))
                <h2 style="font-size: 20px;margin-bottom: 15px;">{{ __('catalog/id/account.transactions') }}</h2>

                <div class="table-responsive ex_account_table">
                    <table class="table table-bordered table-striped table-vcenter table-dark">
                        <thead>
                        <tr>
                            <th>{{ __('catalog/id/account.trans_type') }}</th>
                            <th>{{ __('catalog/id/account.trans_price') }}</th>
                            <th>{{ __('catalog/id/account.trans_short') }}</th>
                            <th>{{ __('catalog/id/account.trans_date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['transaction'] as $item)
                            <tr>
                                <td class="fw-semibold">{!! $item['type'] !!}</td>
                                <td>{{ $item['price'] }}</td>
                                <td>{{ $item['short'] }} {!! ($item['order_status']) ? '| '.$item['order_status'] : '' !!}</td>
                                <td>{{ $item['date'] }}</td>
                            </tr>
                        @endforeach
                        @if(!$data['transactions'])
                            <tr><td class="text-center" colspan="10">{{ __('lang.list_is_empty') }}</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            @endif

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
            $('#changeUserForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('client.auth.change.user') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                        if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.username) {
                                kbNotify('error', errors.username[0]);
                            }
                            if (errors.login) {
                                kbNotify('error', errors.login[0]);
                            }
                        }
                    }
                });
            });
            $('#saveUserBtn').on('click', function () {
                $('#changeUserForm').submit();
            });
        });
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
            });
            $('#changePasswordForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('client.auth.change.password') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            $('#changePasswordForm')[0].reset();
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.curr_pass) {
                                kbNotify('danger', errors.curr_pass[0]);
                            }
                            if (errors.new_pass) {
                                kbNotify('danger', errors.new_pass[0]);
                            }
                            if (errors.new_pass_confirmation) {
                                kbNotify('danger', errors.new_pass_confirmation[0]);
                            }
                        }
                    }
                });
            });
            $('#savePasswordBtn').on('click', function () {
                $('#changePasswordForm').submit();
            });
        });
    </script>
    <script>
        document.getElementById('saveUserBtn').addEventListener('click', function () {
            const button = this;
            button.classList.add('disabled');
            setTimeout(() => {
                button.classList.remove('disabled');
            }, 2000);
        });

        document.getElementById('savePasswordBtn').addEventListener('click', function () {
            const button = this;
            button.classList.add('disabled');
            setTimeout(() => {
                button.classList.remove('disabled');
            }, 2000);
        });
    </script>
@endsection
