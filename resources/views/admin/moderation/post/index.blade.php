@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/inputMask/jquery.inputmask.min.js') }}"></script>
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.post_moderation_page') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/page_titles.post_moderation_page') }}</h3>
                </div>
                <div class="block-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;"><i class="fa-solid fa-image" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.col_image') }}"></i></th>
                                <th style="width: 15%;">{{ __('admin/posts/post.name') }}</th>
                                <th>{{ __('admin/posts/post.col_info') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('admin/posts/post.col_publish') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('admin/posts/post.col_publish_date') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">
                                        <img class="img-avatar ex_post_list_image" src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="object-fit: cover">
                                    </td>
                                    <td class="fw-semibold">
                                        <div>{{ $item['name'] }} ({{ $item['age'] }})</div>
                                        <div><small><span class="ex_copyText" data-copy-info="{{ $item['phone'] }}" data-bs-toggle="tooltip" title="{{ __('lang.copy_to_clipboard') }}">{{ $item['phone'] }} <i class="ex_copyIcon fa-solid fa-copy"></i> </span></small></div>
                                        <div><small><a href="{{ $item['user_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.user') }}">{{ $item['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start gap-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="badge {{ ($item['photo_count']) ? 'bg-primary' : 'bg-danger' }}" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.image_photo') }}"><i class="fa-solid fa-image"></i> {{ __('admin/posts/post.image_photo') }} - {{ $item['photo_count'] }}</span>
                                                    <span class="badge {{ ($item['selfie_count']) ? 'bg-primary' : 'bg-danger' }}" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.image_selfie') }}"><i class="fa-solid fa-image-portrait"></i> {{ __('admin/posts/post.image_selfie') }} - {{ $item['selfie_count'] }}</span>
                                                    <span class="badge {{ ($item['video_count']) ? 'bg-primary' : 'bg-danger' }}" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.image_video') }}"><i class="fa-solid fa-video"></i> {{ __('admin/posts/post.image_video') }} - {{ $item['video_count'] }}</span>
                                                </div>
                                                <div class="col-12">
                                                    <span class="badge {{ ($item['diamond_s']) ? 'bg-primary' : 'bg-black-50' }}" data-bs-toggle="tooltip" title="{{ $item['diamond_status'] }}"><i class="fa-regular fa-gem"></i> Diamond</span>
                                                    <span class="badge {{ ($item['vip_s']) ? 'bg-warning' : 'bg-black-50' }}" data-bs-toggle="tooltip" title="{{ $item['vip_status'] }}"><i class="fa-solid fa-crown"></i> VIP</span>
                                                    <span class="badge {{ ($item['color_s']) ? 'bg-success' : 'bg-black-50' }}" data-bs-toggle="tooltip" title="{{ $item['color_status'] }}"><i class="fa-solid fa-brush"></i> Color</span>
                                                    <span class="badge {{ ($item['verify_s']) ? 'bg-success' : 'bg-black-50' }}">{{ $item['verify_status'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item['publish'] ? 'bg-success' : 'bg-black-50' }}">{{ $item['publish'] ? __('lang.status_on') : __('lang.status_off') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-7">{{ $item['publish_date'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('post_moderation.edit', $item['id']) }}" class="btn btn-alt-info">{{ __('buttons.checks') }}</a>
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
