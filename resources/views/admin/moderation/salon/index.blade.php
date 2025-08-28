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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.salon_moderation_page') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/page_titles.salon_moderation_page') }}</h3>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;"><i class="fa-solid fa-image" data-bs-toggle="tooltip" title="{{ __('admin/salon/salon.col_image') }}"></i></th>
                                <th>{{ __('admin/salon/salon.title') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('admin/salon/salon.col_publish') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('admin/salon/salon.col_publish_date') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">
                                        <img class="img-avatar ex_post_list_image" src="{{ $item['image'] }}" alt="{{ $item['title'] }}" style="object-fit: cover">
                                    </td>
                                    <td class="fw-semibold">
                                        <div>{{ $item['title'] }}</div>
                                        <div>
                                            @foreach($item['phones'] as $phone)
                                                <small><span class="ex_copyText" data-copy-info="{{ $phone }}" data-bs-toggle="tooltip" title="{{ __('lang.copy_to_clipboard') }}">{{ $phone }} <i class="ex_copyIcon fa-solid fa-copy"></i>  </span>@if(!$loop->last)| @endif</small>
                                            @endforeach
                                        </div>
                                        <div><small><a href="{{ $item['user_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/salon/salon.user') }}">{{ $item['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item['publish'] ? 'bg-success' : 'bg-black-50' }}">{{ $item['publish'] ? __('lang.status_on') : __('lang.status_off') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-7">{{ $item['publish_date'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('salon_moderation.edit', $item['id']) }}" class="btn btn-alt-info">{{ __('buttons.checks') }}</a>
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
