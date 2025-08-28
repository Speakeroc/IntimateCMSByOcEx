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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.post') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">{{ __('lang.filter') }}</h3>
                </div>
                <div class="block-content pt-0">
                    <form action="{{ route('post.index') }}" method="get">
                        <div class="row">
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.phone') }}</label>
                                    <input type="text" placeholder="{{ __('admin/posts/post.phone') }}" name="phone" value="{{ $data['phone'] }}" id="input-phone" class="form-control">
                                </div>
                                <script>
                                    document.getElementById('input-phone').addEventListener('input', function(e) {
                                        this.value = this.value.replace(/[^0-9]/g, '');
                                        if (this.value.length > 16) {
                                            this.value = this.value.slice(0, 16);
                                        }
                                    });
                                    document.getElementById('input-phone').addEventListener('paste', function(e) {
                                        e.preventDefault();
                                        const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                                        this.value = pasteData.replace(/[^0-9]/g, '').slice(0, 16);
                                    });
                                </script>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.name') }}</label>
                                    <input type="text" placeholder="{{ __('admin/posts/post.name') }}" name="name" value="{{ $data['name'] }}" id="input-name" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.age') }}</label>
                                    <input type="text" placeholder="{{ __('admin/posts/post.age') }}" name="age" value="{{ $data['age'] }}" id="input-age" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.verify') }}</label>
                                    <select name="verify" id="input-verify" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['verify'] == "yes") selected @endif>{{ __('admin/posts/post.verify_yes') }}</option>
                                        <option value="no" @if($data['verify'] == "no") selected @endif>{{ __('admin/posts/post.verify_no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.publish') }}</label>
                                    <select name="publish" id="input-publish" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['publish'] == "yes") selected @endif>{{ __('lang.yes') }}</option>
                                        <option value="no" @if($data['publish'] == "no") selected @endif>{{ __('lang.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>Diamond</label>
                                    <select name="diamond" id="input-diamond" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['diamond'] == "yes") selected @endif>{{ __('lang.yes') }}</option>
                                        <option value="no" @if($data['diamond'] == "no") selected @endif>{{ __('lang.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>VIP</label>
                                    <select name="vip" id="input-vip" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['vip'] == "yes") selected @endif>{{ __('lang.yes') }}</option>
                                        <option value="no" @if($data['vip'] == "no") selected @endif>{{ __('lang.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>Color</label>
                                    <select name="color" id="input-color" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        <option value="yes" @if($data['color'] == "yes") selected @endif>{{ __('lang.yes') }}</option>
                                        <option value="no" @if($data['color'] == "no") selected @endif>{{ __('lang.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.city') }}</label>
                                    <select name="city_id" id="input-city-id" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['main_data']['city'] as $city)
                                            <option value="{{ $city['id'] }}" {{ ($data['city_id'] == $city['id']) ? 'selected' : '' }}>{{ $city['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/post.user') }}</label>
                                    <select name="user_id" id="input-user_id" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['users'] as $user)
                                            <option value="{{ $user['user_id'] }}" @if($data['user_id'] == $user['user_id']) selected @endif>{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-2">{{ __('lang.filter') }}</button>
                            @if($data['filtered'])
                                <a href="{{ route('post.index') }}" class="btn btn-danger">{{ __('lang.reset') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/posts/post.list') }}</h3>
                    <div class="block-options">
                        <button type="button" id="deleteSelected" class="btn btn-sm btn-danger" style="display: none">{{ __('buttons.delete_selected') }}</button>
                        <a href="{{ route('post.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
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
                                        <input type="checkbox" name="selected[]" value="{{ $item['id'] }}">
                                    </td>
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
                                        <form action="{{ route('post.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('post.edit', $item['id']) }}" class="btn btn-alt-info">
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
                    url: '{{ route("admin.post.massDelete") }}',
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
