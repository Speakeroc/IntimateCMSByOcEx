@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ __('admin/page_titles.post_banner_add') }}
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
            <form action="{{ route('banner_post.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.post_banner_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="form-label" for="select-post-id">{{ __('admin/posts/banner.post') }}</label>
                                            <select class="form-select" id="select-post-id" name="post_id">
                                                <option value="">{{ __('lang.no_select') }}</option>
                                                @foreach($data['posts'] as $post)
                                                    <option value="{{ $post['id'] }}" {{ (old('post_id') == $post['id']) ? 'selected' : '' }}>{{ $post['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-group">
                                                @php $link = old('link'); @endphp
                                                <label class="form-label" for="input-link">{{ __('admin/posts/banner.link') }}</label>
                                                <input type="text" class="form-control" id="input-link" name="link" value="{{ $link }}" placeholder="{{ __('admin/posts/banner.link_p') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <label class="form-label">{{ __('admin/posts/banner.activation') }}</label>
                                        <div class="space-x-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-activation-1" name="activation" value="1" {{ (old('activation') == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-activation-1">{{ __('lang.status_on') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-activation-2" name="activation" value="0" {{ (old('activation') == 0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-activation-2">{{ __('lang.status_off') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <div class="form-group">
                                                @php $activation_date = (old('activation_date') ? \Carbon\Carbon::parse(old('activation_date'))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')); @endphp
                                                <label class="form-label" for="input-activation-date">{{ __('admin/posts/banner.activation_date') }}</label>
                                                <input type="datetime-local" class="form-control" id="input-activation-date" name="activation_date" value="{{ $activation_date }}" placeholder="{{ __('admin/posts/banner.activation_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <label class="form-label">{{ __('admin/posts/banner.up_date_status') }}</label>
                                        <div class="space-x-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-up-date-status-1" name="up_date" value="0" {{ (old('up_date') == 0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-up-date-status-1">{{ __('admin/posts/banner.up_date_one') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-up-date-status-2" name="up_date" value="1" {{ (old('up_date') == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-up-date-status-2">{{ __('admin/posts/banner.up_date_two') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <label class="form-label">{{ __('admin/posts/banner.status') }}</label>
                                        <div class="space-x-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-status-status-1" name="status" value="1" {{ (old('status') == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-status-status-1">{{ __('lang.status_on') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-status-status-2" name="status" value="0" {{ (old('status') == 0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-status-status-2">{{ __('lang.status_off') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <label class="form-label" for="label-banner">{{ __('admin/posts/banner.banner') }}</label>
                                <div class="mb-4 position-relative">
                                    <div id="upload-container" style="display: {{ old('banner') ? 'none' : 'flex' }}">
                                        <label for="image-upload" id="upload-label">
                                            <i class="fa fa-plus"></i>
                                        </label>
                                        <input type="file" id="image-upload" name="image" accept=".svg,.png,.jpeg,.jpg,.gif" style="display:none;" onchange="handleFileUpload(event)">
                                    </div>
                                    <div id="preview-container" style="display: {{ old('banner') ? 'flex' : 'none' }};">
                                        <img id="preview-image" src="{{ old('banner') }}" alt="Preview" class="preview-img"/>
                                        <button id="delete-preview" type="button" onclick="deletePreview()"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <div class="preload-container">
                                        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                            <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                        </svg>
                                    </div>
                                    <input type="hidden" name="banner" id="image-path" value="{{ old('banner') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            $('.preload-container').addClass('active');

            const formData = new FormData();
            const path = 'images/temp/banner';
            formData.append('image', file);
            formData.append('path', path);
            fetch('/services/upload-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('preview-image').src = '/images/temp/banner/'+data.filename;
                        document.getElementById('image-path').value = '/images/temp/banner/'+data.filename;
                        document.getElementById('preview-container').style.display = 'flex';
                        document.getElementById('upload-container').style.display = 'none';

                        setTimeout(function () {
                            $('.preload-container').removeClass('active');
                        }, 1000);
                    }
                });
        }

        function deletePreview() {
            document.getElementById('preview-container').style.display = 'none';
            document.getElementById('upload-container').style.display = 'flex';
            document.getElementById('image-path').value = '';
            document.getElementById('image-upload').value = '';
        }
    </script>
    <style>
        #upload-container {
            width: 540px;
            height: 380px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #preview-container {
            position: relative;
            width: 540px;
            height: 380px;
            border: 0 dashed #ccc;
        }

        #preview-container img.preview-img {
            width: 540px;
            height: 380px;
            object-fit: cover;
        }

        #upload-label {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 50px;
        }

        #delete-preview {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 15px;
            height: 35px;
            width: 35px;
            border-radius: 4px;
        }

        .preload-container {
            width: 540px;
            height: 380px;
        }
    </style>
@endsection
