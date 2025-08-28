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
                    {{ __('admin/page_titles.banner_edit') }}
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
            <form action="{{ route('banner.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.banner_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="input-title">{{ __('admin/info/banner.title') }}</label>
                                            <input type="text" name="title" value="{{ (old('title') ?? $data['title']) }}" class="form-control" id="input-title" placeholder="{{ __('admin/info/banner.title') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="input-link">{{ __('admin/info/banner.link') }}</label>
                                            <input type="text" name="link" value="{{ (old('link') ?? $data['link']) }}" class="form-control" id="input-link" placeholder="{{ __('admin/info/banner.link') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="input-sort_order">{{ __('admin/info/banner.sort_order') }}</label>
                                            <input type="text" name="sort_order" value="{{ (old('sort_order') ?? $data['sort_order']) }}" class="form-control" id="input-sort_order" placeholder="{{ __('admin/info/banner.sort_order') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <label class="form-label">{{ __('admin/info/banner.status') }}</label>
                                        <div class="space-x-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-status-status-1" name="status" value="1" {{ ((old('status') ?? $data['status']) == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-status-status-1">{{ __('lang.status_on') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="radios-status-status-2" name="status" value="0" {{ ((old('status') ?? $data['status']) == 0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radios-status-status-2">{{ __('lang.status_off') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <div class="form-group">
                                                @php $created_at = old('created_at') ?? ($data['created_at'] ? \Carbon\Carbon::parse($data['created_at'])->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')); @endphp
                                                <label class="form-label" for="input-created-at">{{ __('admin/info/banner.created_at') }}</label>
                                                <input type="datetime-local" class="form-control" id="input-created-at" name="created_at" value="{{ $created_at }}" placeholder="{{ __('admin/info/banner.created_at') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <label class="form-label" for="example-text-input">{{ __('admin/info/banner.banner') }}</label>
                                <div class="mb-4 position-relative">
                                    <div id="upload-container" style="display: {{ (old('banner') ?? $data['banner']) ? 'none' : 'flex' }}">
                                        <label for="image-upload" id="upload-label">
                                            <i class="fa fa-plus"></i>
                                        </label>
                                        <input type="file" id="image-upload" name="image" accept=".png,.jpeg,.jpg" style="display:none;" onchange="handleFileUpload(event)">
                                    </div>
                                    <div id="preview-container" style="display: {{ (old('banner') ?? $data['banner']) ? 'flex' : 'none' }};">
                                        <img id="preview-image" src="{{ old('banner') ?? $data['banner'] }}" alt="Preview" class="preview-img"/>
                                        <button id="delete-preview" type="button" onclick="deletePreview()"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <div class="preload-container">
                                        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                            <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                        </svg>
                                    </div>
                                    <input type="hidden" name="banner" id="image-path" value="{{ old('banner') ?? $data['banner'] }}">
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
            width: 600px;
            height: 340px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #preview-container {
            position: relative;
            width: 600px;
            height: 340px;
            border: 0 dashed #ccc;
        }

        #preview-container img.preview-img {
            width: 600px;
            height: 340px;
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
            width: 600px;
            height: 340px;
        }
    </style>
@endsection
