@extends('catalog.layout.layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/id/post.css')
    @vite('resources/catalog/css/id/post_m.css')
@endsection
@section('content')
    <div class="container">
        @if(isset($data['breadcrumb']['breadcrumb']) && !empty($data['breadcrumb']['breadcrumb']))
            <nav aria-label="ex_breadcrumb">
                <ol class="ex_breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    @foreach($data['breadcrumb']['breadcrumb'] as $breadcrumb)
                        <li class="ex_breadcrumb-item @if($loop->last) active @endif" itemscope
                            itemtype="http://schema.org/ListItem">
                            <a href="{{ $breadcrumb['link'] }}" itemprop="item">
                                <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                            </a>
                            <meta itemprop="position" content="1"/>
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

        <div class="ex_post_page_block">
            @if(empty($data['items']))
                <div class="ex_post_page_block_empty">
                    {{ __('catalog/id/post.my_list_empty') }}
                    <a href="{{ route('client.auth.post.create') }}" class="ex_post_page_block_empty_btn">{{ __('catalog/id/post.action_add_post') }}</a>
                </div>
            @else
                <div class="ex_post_page_block_add">
                    <a href="{{ route('client.auth.post.create') }}" class="ex_post_page_block_empty_btn">{{ __('catalog/id/post.action_add_post') }}</a>
                </div>
                <div class="row">
                    @foreach($data['items'] as $item)
                        <div id="item-block-{{ $item['id'] }}" class="col-12 col-md-12 col-xl-4">
                            <div class="ex_post_item">
                                <div class="ex_post_item_block_image {{ ($item['color']) ? 'color' : '' }}">
                                    <div class="ex_post_item_block_image_info">
                                        @if($item['photo'])
                                            <div class="ex_post_item_block_image_info_item"><i class="fas fa-images"></i>{{ $item['photo_count'] }}</div>
                                        @endif
                                        @if($item['selfie'])
                                            <div class="ex_post_item_block_image_info_item"><i class="fas fa-image-portrait"></i>{{ $item['selfie_count'] }}</div>
                                        @endif
                                        @if($item['video'])
                                            <div class="ex_post_item_block_image_info_item"><i class="fas fa-video"></i>{{ $item['video_count'] }}</div>
                                        @endif
                                    </div>
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }} {{ $item['age'] }}" class="ex_post_item_image">
                                    <div class="ex_post_item_block_statuses">
                                        @if($item['verify'])
                                            <div
                                                class="ex_post_item_block_status verify">{{ __('catalog/id/post.verify') }}</div>
                                        @endif
                                        @if($item['position'])
                                            <div class="ex_post_item_block_status top">TOP #{{ $item['position'] }}</div>
                                        @endif
                                    </div>
                                    <div class="ex_post_item_block_statuses_bottom"
                                         id="item-status-block-{{ $item['id'] }}">
                                        @if($item['publish_date'])
                                            <div class="ex_post_item_block_status activation">{{ $item['publish_date'] }}</div>
                                        @endif
                                        @if($item['diamond'])
                                            <div class="ex_post_item_block_status diamond">Diamond: {{ $item['diamond_date'] }}</div>
                                        @endif
                                        @if($item['vip'])
                                            <div class="ex_post_item_block_status vip">VIP: {{ $item['vip_date'] }}</div>
                                        @endif
                                        @if($item['color'])
                                            <div class="ex_post_item_block_status color">Color: {{ $item['color_date'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ex_post_item_info_block">
                                    <div class="ex_post_item_info_block_top">
                                        <div class="ex_post_item_block_publish {{ $item['publish_class'] }}">{{ $item['publish_text'] }}</div>
                                        @if($item['publish_text_two'])
                                            <div style="color:red;line-height:14px;">{{ $item['publish_text_two'] }}</div>
                                        @endif
                                        @if(!empty($item['moderation_text']))
                                            <div class="ex_post_item_block_moder_text">{{ $item['moderation_text'] }}</div>
                                        @endif
                                        <div class="ex_post_item_post_name_age">
                                            <div class="ex_post_item_post_name">{{ $item['name'] }} </div>
                                            <div class="ex_post_item_post_age">{{ $item['age'] }}</div>
                                            @if(!empty($item['city']) || !empty($item['zone']))
                                                <div class="d-flex flex-row flex-wrap align-items-center justify-content-start gap-1">
                                                    @if(!empty($item['city']))
                                                        <div class="ex_post_item_post_age">
                                                            <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-menu-city"></use></svg>
                                                            <span>{{ $item['city']['title'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($item['zone']))
                                                        <div class="ex_post_item_post_age">
                                                            <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-menu-location"></use></svg>
                                                            <span>{{ $item['zone']['title'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="item-buttons-block-{{ $item['id'] }}" class="ex_post_item_info_block_buttons">
                                        @if($item['post_action'])
                                            <button type="button" class="ex_post_item_info_block_link btn_services" onclick="getModalServiceInfo({{ $item['id'] }});"><i class="far fa-money-bill-wave"></i>{{ __('catalog/id/post.action_services') }}</button>
                                        @endif
                                        <a href="{{ $item['link'] }}" class="ex_post_item_info_block_link btn_edit"><i class="far fa-pen"></i>{{ __('catalog/id/post.action_edit') }}</a>
                                        <button type="button" class="ex_post_item_info_block_link btn_delete" data-list-id="{{ $item['id'] }}"><i class="far fa-trash"></i>{{ __('catalog/id/post.action_delete') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(isset($data['data']))
                    <div class="d-block mt-4">
                        {{ $data['data']->onEachSide(1)->links('catalog/common/pagination') }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    <div class="modal fade" id="services-in-modal" tabindex="-1" aria-labelledby="services-in-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ex-modal-content">
                <div class="modal-header ex-modal-header">
                    <div class="ex_mh_title">{{ __('catalog/id/post.action_services') }}</div>
                    <button type="button" class="ex_mh_btn" data-bs-dismiss="modal">
                        <svg class="ex_post_item_post_icon_location"><use xlink:href="#icon-close"></use></svg>
                    </button>
                </div>
                <div id="service-modal-info" class="modal-body"></div>
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

            //Delete
            $(document).on('click', '.btn_delete', function (e) {
                e.preventDefault();
                const id = $(this).data('list-id');
                const isConfirmed = confirm("{{ __('catalog/id/post.notify_delete_confirm') }}");
                if (isConfirmed) {
                    $.ajax({
                        url: "{{ route('client.auth.post.delete') }}",
                        method: "POST",
                        data: {id: id},
                        success: function (response) {
                            if (response.status === 'success') {
                                kbNotify('success', response.message);
                                $('#item-block-'+id).remove();
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

        var loading = false;
        function getModalServiceInfo(postId) {
            if (!loading) {
                loading = true;
                getModalserviceInfoByPost(postId);
                $('#services-in-modal').modal('show');
                setTimeout(function () {
                    loading = false
                }, 500);
            }
        }

        function getModalserviceInfoByPost(postId) {
            $.ajax({
                url: "{{ route('client.auth.post.services') }}",
                method: "POST",
                data: {id: postId},
                success: function (response) {
                    if (response.status === 'success') {
                        $('#service-modal-info').html(response.view);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postActivation(days, postId) {
            $.ajax({
                url: "{{ route('client.auth.post.service.activationDays') }}",
                method: "POST",
                data: {days: days, post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getModalserviceInfoByPost(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postUpToTop(postId) {
            $.ajax({
                url: "{{ route('client.auth.post.service.upToTop') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getModalserviceInfoByPost(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceDiamond(postId) {
            $.ajax({
                url: "{{ route('client.auth.post.service.diamond') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getModalserviceInfoByPost(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceVip(postId) {
            $.ajax({
                url: "{{ route('client.auth.post.service.vip') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getModalserviceInfoByPost(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }

        function postServiceColor(postId) {
            $.ajax({
                url: "{{ route('client.auth.post.service.color') }}",
                method: "POST",
                data: {post_id: postId},
                success: function (response) {
                    console.log(response);
                    if (response.status === 'success') {
                        kbNotify(response.status, response.message);
                        getModalserviceInfoByPost(postId);
                    } else {
                        kbNotify(response.status, response.message);
                    }
                },
                error: function () {
                    kbNotify('error', 'Произошла ошибка.');
                }
            });
        }
    </script>
@endsection
