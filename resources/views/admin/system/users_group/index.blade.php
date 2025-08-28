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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.users_group') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/system/users_group.list') }}</h3>
                    <div class="block-options">
                        <a href="{{ route('user_group.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
                    </div>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th style="width: 1%;">ID</th>
                                <th style="width: 50%;">{{ __('admin/system/users_group.title') }}</th>
                                <th class="text-center" style="width: 1%;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td>{{ $item['id'] }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('user_group.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('user_group.edit', $item['id']) }}" class="btn btn-alt-info">
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
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
