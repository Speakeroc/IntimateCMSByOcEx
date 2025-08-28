@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/id/blacklist.css')
    <script src="{{ url('builder/catalog/js/inputMask/jquery.inputmask.min.js') }}"></script>
@endsection
@section('content')
    <div class="container">
        @if(isset($data['breadcrumb']['breadcrumb']) && !empty($data['breadcrumb']['breadcrumb']))
            <nav aria-label="ex_breadcrumb">
                <ol class="ex_breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    @foreach($data['breadcrumb']['breadcrumb'] as $breadcrumb)
                        <li class="ex_breadcrumb-item @if($loop->last) active @endif" itemscope itemtype="http://schema.org/ListItem">
                            <a href="{{ $breadcrumb['link'] }}" itemprop="item">
                                <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                            </a>
                            <meta itemprop="position" content="{{ $breadcrumb['pos'] }}" />
                        </li>
                    @endforeach
                </ol>
            </nav>
            @if(isset($data['breadcrumb']['list']) && !empty($data['breadcrumb']['list']))
                <script type="application/ld+json">{!! $data['breadcrumb']['list'] !!}</script>
            @endif
        @endif
    </div>

    <div class="container">
        <h1 class="ex_post_page_title">{{ $data['title'] }}</h1>

        <div class="ex_blacklist_page_block">
            <div class="ex_blacklist_text_border">{{ __('catalog/id/blacklist.black_list') }}</div>
            <div class="row">
                <div class="col-12 col-md-12 col-xl-4 mb-2">
                    <h2 class="ex_blacklist_mini_title">{{ __('catalog/id/blacklist.search') }}</h2>
                    <form id="getPhoneData">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" id="input-search-phone" name="search_phone" class="form-control" placeholder="+7 (___) ___-__-__">
                            <button class="btn btn-warning ex_blacklist_btn" type="button" id="getPhoneDataBtn">{{ __('catalog/id/blacklist.btn_search') }}</button>
                        </div>
                    </form>
                    <div class="ex_blacklist_result_block d-none">
                        <div class="ex_blacklist_result_block_header">
                            <div class="ex_blacklist_result_block_title"></div>
                            <div class="ex_blacklist_result_block_btn">
                                <button class="btn btn-sm btn-danger ex_blacklist_btn clearResult" type="button"><svg class="ex_sidebar__account_balance_btn_svg"><use xlink:href="#icon-blacklist-clean"></use></svg></button>
                            </div>
                        </div>
                        <div id="resultBlock" class="ex_blacklist_my_list"></div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-xl-8 mb-2">
                    <h2 class="ex_blacklist_mini_title">{{ __('catalog/id/blacklist.add_client') }}</h2>
                    <form id="createPhoneData" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="input-set-phone" class="form-label">{{ __('catalog/id/blacklist.phone_review') }}</label>
                            <input type="text" id="input-set-phone" name="phone" class="form-control" placeholder="+7 (___) ___-__-__">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">{{ __('catalog/id/blacklist.rating_review') }}</label>
                            <div class="ex_set_rating_list">
                                @for($rating = 1;$rating <= 5;$rating++)
                                    <input type="radio" name="rating" id="input-rating-{{ $rating }}" value="{{ $rating }}" class="d-none">
                                    <label for="input-rating-{{ $rating }}"><i class="far fa-star" data-rating="{{ $rating }}"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="textarea-text" class="form-label">{{ __('catalog/id/blacklist.text_review') }}</label>
                            <input type="text" id="textarea-text" name="text" class="form-control" maxlength="250" placeholder="{{ __('catalog/id/blacklist.text_review') }}">
                        </div>
                        <button class="btn btn-sm btn-danger ex_blacklist_btn" type="button" id="createPhoneDataBtn">{{ __('catalog/id/blacklist.btn_add') }}</button>
                    </form>
                    <h2 class="ex_blacklist_mini_title">{{ __('catalog/id/blacklist.my_list') }}</h2>
                    <div class="ex_blacklist_my_list">
                        @if(empty($data['black_list']))
                            {{ __('catalog/id/blacklist.my_list_empty') }}
                        @else
                            @foreach($data['black_list'] as $item)
                                <div id="list-item-{{ $item['id'] }}" class="ex_blacklist_my_list_item">
                                    <div class="ex_blacklist_my_list_item_header">
                                        <div class="ex_blacklist_my_list_item_phone">{{ $item['phone'] }}</div>
                                        <div class="ex_blacklist_my_list_item_rating">
                                            <i class="fa{{ ($item['rating'] >= 1) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                            <i class="fa{{ ($item['rating'] >= 2) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                            <i class="fa{{ ($item['rating'] >= 3) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                            <i class="fa{{ ($item['rating'] >= 4) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                            <i class="fa{{ ($item['rating'] >= 5) ? 's' : 'r' }} fa-star {{ $item['rating_class'] }}"></i>
                                            <button class="btn btn-sm btn-danger ex_blacklist_btn deleteItem" data-list-id="{{ $item['id'] }}" type="button"><svg class="ex_sidebar__account_balance_btn_svg"><use xlink:href="#icon-trash"></use></svg></button>
                                        </div>
                                    </div>
                                    <div class="ex_blacklist_my_list_item_content">{{ $item['text'] }}</div>
                                    <div class="ex_blacklist_my_list_item_footer">{{ $item['views'] }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
            });

            //Get
            $('#getPhoneData').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('client.auth.blackList.get') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            let html = '';
                            $('.ex_blacklist_result_block_title').text(response.message);
                            response.result.forEach(item => {
                                html += `
                                <div class="ex_blacklist_my_list_item">
                                    <div class="ex_blacklist_my_list_item_header">
                                        <div class="ex_blacklist_my_list_item_phone">${item.phone}</div>
                                        <div class="ex_blacklist_my_list_item_rating">
                                            ${[1, 2, 3, 4, 5].map(rating => `
                                                <i class="fa${item.rating >= rating ? 's' : 'r'} fa-star ${item.rating_class}"></i>
                                            `).join('')}
                                        </div>
                                    </div>
                                    <div class="ex_blacklist_my_list_item_content">${item.text}</div>
                                    <div class="ex_blacklist_my_list_item_footer">${item.views}</div>
                                </div>`;
                            });
                            $('#resultBlock').html(html);
                            $('.ex_blacklist_result_block').removeClass('d-none');
                        }
                        if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    }
                });
            });
            $('#getPhoneDataBtn').on('click', function () {
                $('#getPhoneData').submit();
            });

            //Create
            $('#createPhoneData').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('client.auth.blackList.create') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }
                        if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    }
                });
            });
            $('#createPhoneDataBtn').on('click', function () {
                $('#createPhoneData').submit();
            });

            //Delete
            $(document).on('click', '.deleteItem', function (e) {
                e.preventDefault();
                const id = $(this).data('list-id');
                const isConfirmed = confirm("{{ __('catalog/id/blacklist.notify_delete_confirm') }}");
                if (isConfirmed) {
                    $.ajax({
                        url: "{{ route('client.auth.blackList.delete') }}",
                        method: "POST",
                        data: {id: id},
                        success: function (response) {
                            if (response.status === 'success') {
                                kbNotify('success', response.message);
                                $('#list-item-'+id).remove();
                                if ($('.ex_blacklist_my_list').children().length === 0) {
                                    location.reload();
                                }
                            } else if (response.status === 'error') {
                                kbNotify('error', response.message);
                            }
                        },
                        error: function () {
                            kbNotify('error', 'Произошла ошибка при удалении.');
                        }
                    });
                }
            });
        });

        $('.clearResult').on('click', function () {
            var resultBlock = document.querySelector('.ex_blacklist_result_block');
            var resultBlockItems = document.getElementById('resultBlock');
            resultBlock.classList.add('d-none');
            resultBlockItems.innerHTML = '';
        });

        $(document).ready(function(){
            $('#input-search-phone, #input-set-phone').inputmask({
                mask: "+7 (999) 999-99-99",
                showMaskOnHover: false,
                showMaskOnFocus: true
            });
        });

        function checkInputCompletion() {
            const inputValue = $('#input-search-phone').val();
            const isComplete = inputValue && inputValue.indexOf('_') === -1;
            $('#getPhoneDataBtn').prop('disabled', !isComplete);
        }

        $('#input-search-phone').on('input', function () {
            checkInputCompletion();
        });

        checkInputCompletion();

        function checkInputCompletionSet() {
            const inputValue = $('#input-set-phone').val();
            const isComplete = inputValue && inputValue.indexOf('_') === -1;
            $('#createPhoneDataBtn').prop('disabled', !isComplete);
        }

        $('#input-set-phone').on('input', function () {
            checkInputCompletionSet();
        });

        checkInputCompletionSet();
    </script>

    <script>
        $(document).ready(function () {
            $('input[name="rating"]').on('change', function () {
                const selectedRating = parseInt($(this).val());
                $('.ex_set_rating_list i.fa-star').removeClass('fas far ex_blacllist_danger ex_blacllist_warning ex_blacllist_success');
                $('.ex_set_rating_list i.fa-star').addClass('far');
                $('.ex_set_rating_list i.fa-star').each(function () {
                    const starRating = parseInt($(this).data('rating'));

                    if (starRating <= selectedRating) {
                        $(this).removeClass('far').addClass('fas');

                        // Добавление класса стиля
                        if (selectedRating >= 1 && selectedRating <= 2) {
                            $(this).addClass('ex_blacllist_danger');
                        } else if (selectedRating >= 3 && selectedRating <= 4) {
                            $(this).addClass('ex_blacllist_warning');
                        } else if (selectedRating >= 5) {
                            $(this).addClass('ex_blacllist_success');
                        }
                    }
                });
            });
        });
    </script>
@endsection
