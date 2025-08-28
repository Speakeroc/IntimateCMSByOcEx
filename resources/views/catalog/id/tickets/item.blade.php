@extends('catalog.layout.layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/ckeditor5-classic/build/ckeditor.js') }}"></script>
    @vite('resources/catalog/css/id/tickets.css')
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

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                kbNotify('danger', '{!! $error !!}')
            </script>
        @endforeach
    @endif

    <div class="container">
        <h1 class="ex_post_page_title">{{ $data['title'] }}</h1>

        <div class="ex_indiv_page_block">
            <div class="ex_info_title">{{ $data['subject'] }}</div>
            <div class="ex_info_user_item_content">{!! $data['first_content'] !!}</div>
            <hr>
            <div class="ex_info_box_info">
                <div class="ex_info_box_info_answers_count">
                    <div class="ex_info_box_info_answers_count_t">{{ __('catalog/id/tickets.answers') }}</div>
                    <div class="ex_info_box_info_answers_count_c"><i class="fa fa-comment"></i> <span>{{ $data['message_count'] }}</span></div>
                </div>
                <div class="ex_info_box_info_dates">
                    <span class="ex_info_box_info_dates_item">{{ __('catalog/id/tickets.created') }}: <span>{{ $data['created'] }}</span></span>
                    <span class="ex_info_box_info_dates_item">{{ __('catalog/id/tickets.answering') }}: <span>{{ $data['answer'] }}</span></span>
                </div>
                @if($data['status_id'] == 1)
                    <button type="button" id="closeTicketBtn" class="btn btn-sm main-btn-style"><i class="fas fa-trash"></i> {{ __('buttons.ticket_close') }}</button>
                @endif
            </div>
            <hr>
            <div id="tickets_messages" class="ex_ticket_messages">
                @foreach($data['all_messages'] as $message)
                    <div class="ex_ticket_item">
                        <div class="ex_ticket_item_header">
                            <div class="ex_ticket_item_name">{{ $message['name'] }}</div>
                            <div class="ex_ticket_item_date">
                                {{ $message['created_at'] }}
                            </div>
                        </div>
                        <div class="ex_ticket_item_content">{!! $message['content'] !!}</div>
                    </div>
                @endforeach
            </div>
            <hr>
            @if($data['status_id'] == 1)
                <form id="writeMessage" class="mb-3">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $data['id'] }}">
                    <div class="mb-3">
                        <textarea class="form-control text-content-area" name="ticket_content" id="ticket_content" rows="4" placeholder="{{ __('catalog/id/tickets.content_message') }}"></textarea>
                    </div>
                    <button class="btn btn-sm btn-danger main-btn-style" type="button" id="writeMessageBtn">{{ __('catalog/id/tickets.write_message') }}</button>
                </form>
            @else
                <div class="d-flex justify-content-center">{{ __('catalog/id/tickets.ticket_closed') }}</div>
            @endif
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    @if($data['status_id'] == 1)
    <script>
        let editorInstance;
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('.text-content-area'), {
                removePlugins: ['ImageUpload', 'EasyImage', 'Indent', 'MediaEmbed', 'Table', 'Heading']
            }).then(editor => {
                editorInstance = editor;
            }).catch(error => {
                console.error(error);
            });
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
            });
            $('#writeMessage').on('submit', function (e) {
                e.preventDefault();

                if (editorInstance) {
                    $('#ticket_content').val(editorInstance.getData());
                }

                let formData = $(this).serializeArray();

                $.ajax({
                    url: "{{ route('client.auth.tickets.ajax') }}",
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            $('#writeMessage')[0].reset();
                            if (editorInstance) {
                                editorInstance.setData('');
                            }
                            if (response.view) {
                                $('#tickets_messages').append(response.view);
                            }
                        }
                        if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    }
                });
            });
            $('#writeMessageBtn').on('click', function () {
                $('#writeMessage').submit();
            });

            $('#closeTicketBtn').on('click', function (e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('ticket_id', '{{ $data['id'] }}');

                $.ajax({
                    url: "{{ route('client.auth.tickets.close') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status === 'success') {
                            kbNotify('success', response.message);
                            location.reload();
                        }
                        if (response.status === 'error') {
                            kbNotify('error', response.message);
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
    @endif
@endsection
