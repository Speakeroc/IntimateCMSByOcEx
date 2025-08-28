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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.news') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">{{ __('lang.filter') }}</h3>
                </div>
                <div class="block-content pt-0">
                    <form action="{{ route('news.index') }}" method="get">
                        <div class="row">
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/info/news.title') }}</label>
                                    <input type="text" placeholder="{{ __('admin/info/news.title') }}" name="title" value="{{ $data['title'] }}" id="input-name" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/info/news.pinned') }}</label>
                                    <select name="verify" id="input-verify" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['pinned'] == "yes") selected @endif>{{ __('admin/info/news.pinned_yes') }}</option>
                                        <option value="no" @if($data['pinned'] == "no") selected @endif>{{ __('admin/info/news.pinned_no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/info/news.status') }}</label>
                                    <select name="status" id="input-status" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['status'] == "yes") selected @endif>{{ __('lang.status_on') }}</option>
                                        <option value="no" @if($data['status'] == "no") selected @endif>{{ __('lang.status_off') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-2">{{ __('lang.filter') }}</button>
                            @if($data['filtered'])
                                <a href="{{ route('news.index') }}" class="btn btn-danger">{{ __('lang.reset') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/info/news.list') }}</h3>
                    <div class="block-options">
                        <button type="button" id="deleteSelected" class="btn btn-sm btn-danger" style="display: none">{{ __('buttons.delete_selected') }}</button>
                        <a href="{{ route('news.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
                    </div>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:10px;">
                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                </th>
                                <th class="text-center" style="width: 5%;"><i class="fa-solid fa-image" data-bs-toggle="tooltip" title="{{ __('admin/info/news.banner') }}"></i></th>
                                <th>{{ __('admin/info/news.title') }}</th>
                                <th style="width: 15%;">{{ __('admin/info/news.info') }}</th>
                                <th class="text-center" style="width: 5%;">{{ __('admin/info/news.status') }}</th>
                                <th class="text-center" style="width: 15%;">{{ __('admin/info/news.created_at') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected[]" value="{{ $item['id'] }}">
                                    </td>
                                    <td class="text-center">
                                        <img class="img-avatar ex_news_list_image" src="{{ $item['image'] }}" alt="{{ $item['title'] }}" style="object-fit: cover">
                                    </td>
                                    <td class="text-container">
                                        <span class="text-truncate">{{ $item['title'] }}</span>
                                        <button type="button" class="btn btn-sm btn-success toggle-btn d-none" onclick="toggleText(this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start gap-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="badge bg-primary" data-bs-toggle="tooltip" title="{{ __('admin/info/news.views') }}"><i class="fa-solid fa-eye"></i> {{ $item['views'] }}</span>
                                                    <span class="badge bg-success" data-bs-toggle="tooltip" title="{{ __('admin/info/news.like') }}"><i class="fa-solid fa-thumbs-up"></i> {{ $item['like'] }}</span>
                                                    <span class="badge bg-danger" data-bs-toggle="tooltip" title="{{ __('admin/info/news.dislike') }}"><i class="fa-solid fa-thumbs-down"></i> {{ $item['dislike'] }}</span>
                                                    <span class="badge {{ ($item['pinned']) ? 'bg-primary' : 'bg-danger' }}"><i class="fa-solid fa-thumbtack"></i> {{ $item['pinned_text'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item['status'] ? 'bg-success' : 'bg-black-50' }}">{{ $item['status'] ? __('lang.status_on') : __('lang.status_off') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-7">{{ $item['date_added'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('news.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('news.edit', $item['id']) }}" class="btn btn-alt-info">
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
    <style>
        .text-container {max-width:200px;position:relative;}
        .text-truncate {white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;vertical-align:middle;}
        .full-text {white-space:normal;}
    </style>
    <script>
        $(document).ready(function () {
            const deleteBtn = $('#deleteSelected').hide();
            $('input[name="selected[]"], input[type="checkbox"]').on('change', function () {
                const anyChecked = $('input[name="selected[]"]:checked').length > 0;
                deleteBtn.toggle(anyChecked);
                if ($(this).is(':checkbox:first')) {
                    $('input[name="selected[]"]').prop('checked', $(this).is(':checked')).trigger('change');
                }
            });

            deleteBtn.on('click', function () {
                const selectedIds = $('input[name="selected[]"]:checked').map(function () {return $(this).val();}).get();
                if (!selectedIds.length) {
                    kbNotify('danger', 'Пожалуйста, выберите записи для удаления.');
                    return;
                }
                $.ajax({
                    url: '{{ route("admin.news.massDelete") }}',
                    method: 'POST',
                    data: {_token: '{{ csrf_token() }}', selected: selectedIds},
                    success: function (response) {
                        kbNotify('success', response.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    },
                    error: function () {alert('Ошибка при удалении записей.');}
                });
            });
        });

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

        $(document).ready(function(){
            $('#input-phone').inputmask({
                mask: "+7 (999) 999-99-99",
                showMaskOnHover: false,
                showMaskOnFocus: true
            });
        });
    </script>
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
