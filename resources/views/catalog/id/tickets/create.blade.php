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
            <form action="{{ route('client.auth.tickets.create') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="d-flex justify-content-end">
                    <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary main-btn-style"><i class="fas fa-save"></i> {{ __('buttons.create') }}</button>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="input-subject">{{ __('catalog/id/tickets.subject') }}</label>
                    <input type="text" class="form-control" id="input-subject" name="ticket_subject" value="{{ old('ticket_subject') }}" placeholder="{{ __('catalog/id/tickets.subject') }}">
                </div>

                <hr>

                <div class="mb-2">
                    <label class="form-label" for="input-subject">{{ __('catalog/id/tickets.content') }}</label>
                    <textarea class="form-control text-content-area" id="content" name="ticket_content" rows="4" placeholder="{{ __('catalog/id/tickets.content') }}">{{ old('ticket_content') }}</textarea>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary main-btn-style"><i class="fas fa-save"></i> {{ __('buttons.create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('.text-content-area'), {removePlugins: ['ImageUpload', 'EasyImage']}).catch(error => {console.error(error);});
        });
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
@endsection
