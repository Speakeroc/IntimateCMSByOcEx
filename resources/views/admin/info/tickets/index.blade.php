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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.ticket_system') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/page_titles.ticket_system') }}</h3>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>{{ __('admin/info/tickets.subject') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/info/tickets.user') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/info/tickets.messages_count') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/info/tickets.status') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/info/tickets.created') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr id="review-block-{{ $item['id'] }}">
                                    <td class="text-container">
                                        <span class="text-truncate">{{ $item['subject'] }}</span>
                                        <button type="button" class="btn btn-sm btn-success toggle-btn d-none" onclick="toggleText(this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        <small><a href="{{ $item['user_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/info/tickets.user') }}">{{ $item['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        {{ $item['messages'] }}
                                    </td>
                                    <td class="fw-semibold text-center">
                                        <span class="badge {{ ($item['status_id'] == 1) ? 'bg-success' : 'bg-black-50' }}">{{ $item['status'] }}</span>
                                        <span class="badge {{ ($item['view_admin_id'] == 1) ? 'bg-success' : 'bg-black-50' }}">{{ $item['view_admin'] }}</span>
                                    </td>
                                    <td class="fw-semibold text-center">
                                        <small>{{ $item['created'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('ticket_system.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('ticket_system.show', $item['id']) }}" class="btn btn-alt-info">
                                                    <i class="fa fa-eye"></i>
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
