@extends('catalog.layout.layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/id/tickets.css')
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

        <div class="ex_indiv_page_block ticket-list-container">
            @if(empty($data['items']))
                <div class="ex_tickets_page_block_empty">
                    {{ __('catalog/id/tickets.my_list_empty') }}
                    <a href="{{ route('client.auth.tickets.create') }}" class="main-btn-style">{{ __('buttons.ticket_create') }}</a>
                </div>

            @else
                <div class="ex_tickets_page_block_add">
                    <a href="{{ route('client.auth.tickets.create') }}" class="main-btn-style">{{ __('buttons.ticket_create') }}</a>
                </div>
                <div class="row">
                    <div class="ex_tickets_list my-4">
                        @foreach($data['items'] as $item)
                        <div class="ex_tickets_item">
                            <div class="ex_tickets_item-info">
                                <div class="ex_tickets_item-info-top">
                                    <h3><a href="{{ $item['link'] }}">{{ $item['subject'] }} #{{ $item['id'] }}</a></h3>
                                </div>
                                <div class="ex_tickets_item-info-bottom">
                                    <span>{{ $item['user'] }}</span>
                                    <span>{{ __('catalog/id/tickets.created') }}: {{ $item['created'] }}</span>
                                    <span>{{ __('catalog/id/tickets.answering') }}: {{ $item['answer'] }}</span>
                                </div>
                            </div>
                            <div class="ex_tickets_item-status">
                                <span class="ex_tickets_item-status status_{{ $item['status_id'] }}">{{ $item['status'] }}</span>
                            </div>
                            <div class="ex_tickets_item-count">
                                <i class="fa fa-comment outline icon"></i> <span>{{ $item['messages'] }}</span>
                            </div>
                            <div class="ex_tickets_item-action">
                                <div class="ticket-starter-info-block ui top right pointing dropdown" tabindex="0">
                                    <a href="{{ $item['link'] }}" class="main-btn-style" style="height: 34px;"><i class="fa fa-eye"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
                    <div class="ex_mh_title">{{ __('catalog/id/tickets.action_services') }}</div>
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
                const isConfirmed = confirm("{{ __('catalog/id/tickets.notify_delete_confirm') }}");
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
