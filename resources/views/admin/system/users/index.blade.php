@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.users') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">{{ __('lang.filter') }}</h3>
                </div>
                <div class="block-content pt-0">
                    <form action="{{ route('users.index') }}" method="get">
                        <div class="row">
                            <div class="col-sm-2 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/system/users.username') }}</label>
                                    <input type="text" placeholder="{{ __('admin/system/users.username') }}" name="name" value="{{ $data['name'] }}" id="input-name" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/system/users.login') }}</label>
                                    <input type="text" placeholder="{{ __('admin/system/users.login') }}" name="login" value="{{ $data['login'] }}" id="input-login" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/system/users.email') }}</label>
                                    <input type="text" placeholder="{{ __('admin/system/users.email') }}" name="email" value="{{ $data['email'] }}" id="input-email" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/system/users.group') }}</label>
                                    <select name="user_group_id" id="input-user_group_id" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['groups'] as $key)
                                            <option value="{{ $key['id'] }}" @if($data['user_group_id'] == $key['id']) selected @endif>{{ $key['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-2">{{ __('lang.filter') }}</button>
                            @if($data['filtered'])
                                <a href="{{ route('users.index') }}" class="btn btn-danger">{{ __('lang.reset') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/system/users.list') }}</h3>
                    <div class="block-options">
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
                    </div>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th style="width: 1%;">{{ __('admin/system/users.username') }}</th>
                                <th style="width: 1%;">{{ __('admin/system/users.login') }}</th>
                                <th style="width: 1%;">{{ __('admin/system/users.email') }}</th>
                                <th style="width: 1%;">{{ __('admin/system/users.group') }}</th>
                                <th style="width: 1%;">{{ __('admin/system/users.balance') }}</th>
                                <th class="text-center" style="width: 1%;"><i class="fa-solid fa-table-list" data-bs-toggle="tooltip" title="{{ __('admin/system/users.posts') }}"></i></th>
                                <th style="width: 1%;">{{ __('admin/system/users.register') }}</th>
                                <th class="text-center" style="width: 1%;"><i class="fa-solid fa-wifi" data-bs-toggle="tooltip" title="{{ __('admin/system/users.last_seen') }}"></i></th>
                                <th class="text-center" style="width: 1%;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['login'] }}</td>
                                    <td>
                                        <span class="ex_copyText" data-copy-info="{{ $item['email'] }}" data-bs-toggle="tooltip" title="{{ __('lang.copy_to_clipboard') }}">{{ $item['email'] }} <i class="ex_copyIcon fa-solid fa-copy"></i> </span>
                                    </td>
                                    <td>{{ $item['group_name'] }}</td>
                                    <td>{{ $item['balance'] }}</td>
                                    <td class="text-center">
                                        @if($item['posts'])
                                            <a href="{{ $item['posts_link'] }}" class="text-decoration-underline">{{ $item['posts'] }}</a>
                                        @else
                                            {{ $item['posts'] }}
                                        @endif
                                    </td>
                                    <td>{{ $item['register'] }}</td>
                                    <td class="text-center">{{ $item['last_seen'] }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('users.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('users.edit', $item['id']) }}" class="btn btn-alt-info">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger ex_confirmation">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if(!$data['items'])
                                <tr><td class="text-center" colspan="10">{{ __('lang.list_is_empty') }}</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($data['data']->links('admin/common/paginate')))
                        {{ $data['data']->links('admin/common/paginate') }}
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        $(document).ready(function() {
            $('.ex_copyText').on('click', function() {
                var copyText = $(this).data('copy-info');
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(copyText).catch(function() {});
                } else {
                    var $tempInput = $('<input>');
                    $('body').append($tempInput);
                    $tempInput.val(copyText).select();
                    document.execCommand('copy');
                    $tempInput.remove();
                }
                var $icon = $(this).find('.ex_copyIcon');
                $icon.fadeIn('slow', function() {
                    setTimeout(function() {
                        $icon.fadeOut('slow');
                    }, 300);
                });
            });
        });
    </script>
@endsection
