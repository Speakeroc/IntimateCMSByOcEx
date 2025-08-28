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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.review_moderation_page') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/page_titles.review_moderation_page') }}</h3>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>{{ __('admin/posts/review.review') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/posts/review.rating') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/posts/review.user') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/posts/review.post') }}</th>
                                <th class="text-center" style="width: 20%;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr id="review-block-{{ $item['id'] }}">
                                    <td class="text-container">
                                        <span class="text-truncate">{{ $item['text'] }}</span>
                                        <button type="button" class="btn btn-sm btn-success toggle-btn d-none" onclick="toggleText(this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        @php
                                            switch (true) {
                                                case ($item['rating'] >= 1 && $item['rating'] <= 2):
                                                    $rating_class = 'text-danger';
                                                    break;
                                                case ($item['rating'] >= 3 && $item['rating'] <= 4):
                                                    $rating_class = 'text-warning';
                                                    break;
                                                case ($item['rating'] >= 5):
                                                    $rating_class = 'text-success';
                                                    break;
                                                default:
                                                    $rating_class = '';
                                            }
                                        @endphp
                                        <i class="fa{{ ($item['rating'] >= 1) ? 's' : 'r' }} fa-star {{ $rating_class }}"></i>
                                        <i class="fa{{ ($item['rating'] >= 2) ? 's' : 'r' }} fa-star {{ $rating_class }}"></i>
                                        <i class="fa{{ ($item['rating'] >= 3) ? 's' : 'r' }} fa-star {{ $rating_class }}"></i>
                                        <i class="fa{{ ($item['rating'] >= 4) ? 's' : 'r' }} fa-star {{ $rating_class }}"></i>
                                        <i class="fa{{ ($item['rating'] >= 5) ? 's' : 'r' }} fa-star {{ $rating_class }}"></i>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        <div><small><a href="{{ $item['user_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/posts/review.user') }}">{{ $item['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></div>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        <div><small><a href="{{ $item['post_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/posts/review.post') }}">{{ $item['name'] }} {{ $item['age'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-lg">
                                            <button type="button" class="btn btn-alt-info" onclick="setReviewStatus({{ $item['id'] }}, 1)">{{ __('buttons.access') }}</button>
                                            <button type="button" class="btn btn-danger" onclick="setReviewStatus({{ $item['id'] }}, 2)">{{ __('buttons.noaccess') }}</button>
                                        </div>
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
    <style>
        .text-container {max-width:200px;position:relative;}
        .text-truncate {white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;vertical-align:middle;}
        .full-text {white-space:normal;}
    </style>
    <script>
        function toggleText(button) {
            var textElement = button.previousElementSibling;
            if (textElement.classList.contains('text-truncate')) {
                textElement.classList.remove('text-truncate');
                textElement.classList.add('full-text');
                button.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
            } else {
                textElement.classList.remove('full-text');
                textElement.classList.add('text-truncate');
                button.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
            }
        }
        function checkTextOverflow() {
            const containers = document.querySelectorAll('.text-container');
            containers.forEach(container => {
                const textElement = container.querySelector('.text-truncate');
                const button = container.querySelector('.toggle-btn');
                if (textElement.scrollWidth > textElement.clientWidth) {
                    button.classList.remove('d-none');
                } else {
                    button.classList.add('d-none');
                }
            });
        }
        window.onload = checkTextOverflow;

        function setReviewStatus(review_id, status) {
            $.ajax({
                url: '{{ route('admin.moderation.review.access') }}',
                type: 'POST',
                data: {review_id: review_id, status: status, _token: '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.status === 'success') {
                        kbNotify('success', response.message);
                        $('#review-block-'+review_id).remove();
                    }
                    if (response.status === 'error') {
                        kbNotify('error', response.message);
                        $('#review-block-'+review_id).remove();
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
