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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.blacklist') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">{{ __('lang.filter') }}</h3>
                </div>
                <div class="block-content pt-0">
                    <form action="{{ route('blacklist.index') }}" method="get">
                        <div class="row">
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/blacklist.phone') }}</label>
                                    <input type="text" placeholder="{{ __('admin/posts/blacklist.phone') }}" name="phone" value="{{ $data['phone'] }}" id="input-phone" class="form-control">
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
                                    <label>{{ __('admin/posts/blacklist.rating') }}</label>
                                    <select name="rating" id="input-rating" class="form-select">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" @if($data['rating'] == $i) selected @endif>{{ trans_choice('admin/posts/blacklist.rating_choice', $i, ['num' => $i]) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 my-2">
                                <div class="form-group">
                                    <label>{{ __('admin/posts/blacklist.user') }}</label>
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
                                <a href="{{ route('blacklist.index') }}" class="btn btn-danger">{{ __('lang.reset') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/posts/blacklist.list') }}</h3>
                    <div class="block-options">
                        <button type="button" id="deleteSelected" class="btn btn-sm btn-danger" style="display: none">{{ __('buttons.delete_selected') }}</button>
                        <a href="{{ route('blacklist.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
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
                                <th style="width: 15%;">{{ __('admin/posts/blacklist.phone') }}</th>
                                <th>{{ __('admin/posts/blacklist.text') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/posts/blacklist.views') }}</th>
                                <th class="text-center" style="width: 20%;">{{ __('admin/posts/blacklist.avg_rating') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('admin/posts/blacklist.date_added') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected[]" value="{{ $item['id'] }}">
                                    </td>
                                    <td class="fw-semibold">{{ $item['phone'] }}</td>
                                    <td class="text-container">
                                        <span class="text-truncate">{{ $item['text'] }}</span>
                                        <button type="button" class="btn btn-sm btn-success toggle-btn d-none" onclick="toggleText(this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td class="text-center"><span class="fs-6">{{ $item['views'] }}</span></td>
                                    <td class="text-center">
                                        @php
                                            switch (true) {
                                                case ($item['middle_rating'] >= 1 && $item['middle_rating'] <= 2):
                                                    $middle_rating_class = 'text-danger';
                                                    break;
                                                case ($item['middle_rating'] >= 3 && $item['middle_rating'] <= 4):
                                                    $middle_rating_class = 'text-warning';
                                                    break;
                                                case ($item['middle_rating'] >= 5):
                                                    $middle_rating_class = 'text-success';
                                                    break;
                                                default:
                                                    $middle_rating_class = '';
                                            }
                                        @endphp
                                        <i class="fa{{ ($item['middle_rating'] >= 1) ? 's' : 'r' }} fa-star {{ $middle_rating_class }}"></i>
                                        <i class="fa{{ ($item['middle_rating'] >= 2) ? 's' : 'r' }} fa-star {{ $middle_rating_class }}"></i>
                                        <i class="fa{{ ($item['middle_rating'] >= 3) ? 's' : 'r' }} fa-star {{ $middle_rating_class }}"></i>
                                        <i class="fa{{ ($item['middle_rating'] >= 4) ? 's' : 'r' }} fa-star {{ $middle_rating_class }}"></i>
                                        <i class="fa{{ ($item['middle_rating'] >= 5) ? 's' : 'r' }} fa-star {{ $middle_rating_class }}"></i>
                                        @if($item['duplicate'] > 1)
                                            <div>{{ trans_choice('admin/posts/blacklist.reviews_choice', $item['duplicate'], ['num' => $item['duplicate']]) }}
                                                <button type="button" class="btn btn-sm btn-primary" onclick="getPhoneData('{{ $item['phone'] }}')" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-7">{{ $item['date_added'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('blacklist.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('blacklist.edit', $item['id']) }}" class="btn btn-alt-info">
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('admin/posts/blacklist.all_reviews') }} <span></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="blacklist_modal_body" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('lang.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <style>
        .blacklist_modal_item {font-size:14px;border:1px solid gray;padding:10px;border-radius:10px;margin-bottom:10px;}
        .blacklist_modal_item_text {}
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
                    url: '{{ route("admin.blacklist.massDelete") }}',
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

        function getPhoneData(phone) {
            $('#blacklist_modal_body').html('');
            $.ajax({
                url: '{{ route('services.blacklistPhoneData') }}',
                type: 'POST',
                data: {phone: phone, _token: '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.length > 0) {
                        response.forEach(function(review) {
                            var reviewBlock = `
                                <div class="blacklist_modal_item">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div><b>${review.phone}</b></div>
                                        <div><a href="${review.user_link}" target="_blank">${review.user} <i class="fas fa-arrow-up-right-from-square"></i></a></div>
                                    </div>
                                    <div class="blacklist_modal_item_text">${review.text}</div>
                                    <div class="blacklist_modal_item_text" style="display:flex;justify-content:space-between;margin:5px 0;align-items:center;">
                                        <div>${getStarRating(review.rating)}</div>
                                        <a href="${review.link}" class="btn btn-sm btn-alt-info w-auto"><i class="fa fa-pencil-alt"></i></a>
                                    </div>
                                    <div class="blacklist_modal_item_text">
                                    </div>
                                </div>
                            `;
                            $('#blacklist_modal_body').append(reviewBlock);
                        });
                    }
                    console.log(response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function getStarRating(rating) {
            let stars = '';
            for (let i = 0; i < 5; i++) {
                if (i < rating) {
                    stars += '<i class="fas fa-star '+getRatingClass(rating)+'"></i>';
                } else {
                    stars += '<i class="far fa-star '+getRatingClass(rating)+'"></i>';
                }
            }
            return stars;
        }


        function getRatingClass(rating) {
            if (rating >= 1 && rating <= 2) {
                return 'text-danger';
            } else if (rating >= 3 && rating <= 4) {
                return 'text-warning';
            } else if (rating >= 5) {
                return 'text-success';
            }
            return '';
        }
    </script>
@endsection
