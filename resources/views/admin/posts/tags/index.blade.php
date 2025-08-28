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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.tags') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">{{ __('lang.filter') }}</h3>
                </div>
                <div class="block-content pt-0">
                    <form action="{{ route('tags.index') }}" method="get">
                        <div class="row">
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/tags.tag') }}</label>
                                    <input type="text" placeholder="{{ __('admin/posts/tags.tag') }}" name="tag" value="{{ $data['tag'] }}" id="input-tag" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-2">{{ __('lang.filter') }}</button>
                            @if($data['filtered'])
                                <a href="{{ route('tags.index') }}" class="btn btn-danger">{{ __('lang.reset') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/posts/tags.list') }}</h3>
                    <div class="block-options">
                        <button type="button" id="deleteSelected" class="btn btn-sm btn-danger" style="display: none">{{ __('buttons.delete_selected') }}</button>
                        <a href="{{ route('tags.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
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
                                <th>{{ __('admin/posts/tags.tag') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected[]" value="{{ $item['id'] }}">
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $item['tag'] }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('tags.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('tags.edit', $item['id']) }}" class="btn btn-alt-info">
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
                    url: '{{ route("admin.tags.massDelete") }}',
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
    </script>
@endsection
