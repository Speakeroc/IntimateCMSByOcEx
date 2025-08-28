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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.feedback_page') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/page_titles.feedback_page') }}</h3>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th style="width: 10%;">{{ __('admin/info/feedback.information') }}</th>
                                <th class="text-center">{{ __('admin/info/feedback.message') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/info/feedback.date') }}</th>
                                <th class="text-center" style="width: 10%;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr id="review-block-{{ $item['id'] }}">
                                    <td>
                                        <div>{{ $item['theme'] }}</div>
                                        <div>{{ $item['name'] }}</div>
                                        <div><a href="mailto:{{ $item['email'] }}">{{ $item['email'] }}</a></div>
                                    </td>
                                    <td class="text-container">
                                        <span class="text-truncate">{{ $item['message'] }}</span>
                                        <button type="button" class="btn btn-sm btn-success toggle-btn d-none" onclick="toggleText(this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $item['date_added'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('feedback.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ex_confirmation">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
    </script>
@endsection
