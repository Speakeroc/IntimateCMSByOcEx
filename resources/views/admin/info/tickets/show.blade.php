@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/ckeditor5-classic/build/ckeditor.js') }}"></script>
    @vite('resources/admin/css/tickets.css')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ $data['title'] }}
                </h3>
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    kbNotify('danger', '{{ $error }}')
                </script>
            @endforeach
        @endif

        <div class="content">
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ $data['title'] }}</h3>
                        @if($data['status_id'] == 1)
                            <div class="block-options">
                                <button type="button" id="closeTicketBtn" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> {{ __('buttons.ticket_close') }}</button>
                            </div>
                        @endif
                    </div>
                    <div class="block-content">
                        <h5>{{ $data['subject'] }}</h5>
                        <hr>
                        <div class="ex_info_user_item_content">{!! $data['first_content'] !!}</div>
                        <hr>
                        <div class="ex_info_box_info">
                            <div class="ex_info_box_info_answers_count">
                                <div class="ex_info_box_info_answers_count_t">{{ __('admin/info/tickets.answers') }}</div>
                                <div class="ex_info_box_info_answers_count_c"><i class="fa fa-comment"></i> <span>{{ $data['message_count'] }}</span></div>
                            </div>
                            <div class="ex_info_box_info_dates">
                                <span class="ex_info_box_info_dates_item">{{ __('admin/info/tickets.created') }}: <span>{{ $data['created'] }}</span></span>
                                <span class="ex_info_box_info_dates_item">{{ __('admin/info/tickets.answering') }}: <span>{{ $data['answer'] }}</span></span>
                            </div>
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
                                    <textarea class="form-control text-content-area" name="ticket_content" id="ticket_content" rows="4" placeholder="{{ __('admin/info/tickets.content_message') }}"></textarea>
                                </div>
                                <button class="btn btn-sm btn-primary main-btn-style" type="button" id="writeMessageBtn">{{ __('admin/info/tickets.write_message') }}</button>
                            </form>
                        @else
                            <div class="block block-rounded block-themed bg-image">
                                <div class="block-header bg-primary-dark-op">
                                    <h3 class="block-title text-center">{{ __('admin/info/tickets.ticket_closed') }}</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
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
                formData.push({name: 'is_admin', value: '1'});

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
                formData.append('is_admin', '1');

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
@endsection
