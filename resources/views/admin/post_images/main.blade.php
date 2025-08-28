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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $data['heading_title'] }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            @if($data['items'])
                <div class="block block-themed">
                    <div class="block-content bg-danger text-white">
                        {{ __('admin/posts/content.info') }}
                    </div>
                </div>
            @endif

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ $data['heading_title'] }}</h3>
                </div>
                <div class="block-content">
                    <div class="row">
                        @foreach($data['items'] as $item)
                            <div class="col-sm-2 col-6" id="image-block-{{ $item['id'] }}">
                                <div class="ex_gallery_block_item">
                                    <img class="img-avatar ex_gallery_block_item_image" src="{{ $item['file'] }}" alt="Image" style="object-fit: cover">
                                    <button type="submit" class="btn btn-danger delete-image-btn" data-id="{{ $item['id'] }}"  data-url="{{ $item['link_delete'] }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(!$data['items'])
                        <div class="d-flex justify-content-center">{{ __('lang.list_is_empty') }}</div>
                    @endif
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
        $(document).ready(function () {
            $('.delete-image-btn').on('click', function () {
                const imageId = $(this).data('id');
                const url = $(this).data('url');
                const imageBlock = $(`#image-block-${imageId}`);
                imageBlock.remove();
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function (response) {
                        kbNotify('success', response.message);
                        if (response.success) {
                            const remainingItems = $('.block-content .ex_gallery_block_item').length;
                            if (remainingItems === 0) {
                                location.reload();
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Ошибка при удалении изображения:', error);
                    }
                });
            });
        });
    </script>
@endsection
