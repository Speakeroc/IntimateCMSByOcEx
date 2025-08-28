@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/ckeditor5-classic/build/ckeditor.js') }}"></script>
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ __('admin/page_titles.news_edit') }}
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
            <form action="{{ route('news.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.news_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="input-title">{{ __('admin/info/news.title') }}</label>
                            <input type="text" name="title" value="{{ old('title') ?? $data['title'] }}" class="form-control" id="input-title" placeholder="{{ __('admin/info/news.title') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-desc">{{ __('admin/info/news.desc') }}</label>
                            <textarea class="form-control desc" id="input-desc" name="desc" rows="4" placeholder="{{ __('admin/info/news.desc') }}">{{ old('desc') ?? $data['desc'] }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-meta_title">{{ __('admin/info/news.meta_title') }}</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') ?? $data['meta_title'] }}" class="form-control" id="input-meta_title" placeholder="{{ __('admin/info/news.meta_title') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-meta-description">{{ __('admin/info/news.meta_description') }}</label>
                            <textarea class="form-control" id="input-meta-description" name="meta_description" rows="4" placeholder="{{ __('admin/info/news.meta_description') }}">{{ old('meta_description') ?? $data['meta_description'] }}</textarea>
                        </div>
                        <label class="form-label" for="example-text-input">{{ __('admin/info/news.banner') }}</label>
                        <div class="mb-4 position-relative">
                            <div id="upload-container" style="display: {{ (old('image') ?? $data['image']) ? 'none' : 'flex' }}">
                                <label for="image-upload" id="upload-label">
                                    <i class="fa fa-plus"></i>
                                </label>
                                <input type="file" id="image-upload" name="image" accept=".png,.jpeg,.jpg" style="display:none;" onchange="handleFileUpload(event)">
                            </div>
                            <div id="preview-container" style="display: {{ (old('image') ?? $data['image']) ? 'flex' : 'none' }};">
                                <img id="preview-image" src="{{ (old('image') ?? $data['image']) }}" alt="Preview" class="preview-img"/>
                                <button id="delete-preview" type="button" onclick="deletePreview()"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="preload-container">
                                <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                </svg>
                            </div>
                            <input type="hidden" name="image" id="image-path" value="{{ (old('image') ?? $data['image']) }}">
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-seo_url">{{ __('admin/info/news.seo_url') }}</label>
                                    <input type="text" name="seo_url" value="{{ old('seo_url') ?? $data['seo_url'] }}" class="form-control" id="input-seo_url" placeholder="{{ __('admin/info/news.seo_url') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-views">{{ __('admin/info/news.views') }}</label>
                                    <input type="number" name="views" value="{{ old('views') ?? $data['views'] }}" class="form-control" id="input-views" placeholder="{{ __('admin/info/news.views') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-like">{{ __('admin/info/news.like') }}</label>
                                    <input type="number" name="like" value="{{ old('like') ?? $data['like'] }}" class="form-control" id="input-like" placeholder="{{ __('admin/info/news.like') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-dislike">{{ __('admin/info/news.dislike') }}</label>
                                    <input type="number" name="dislike" value="{{ old('dislike') ?? $data['dislike'] }}" class="form-control" id="input-dislike" placeholder="{{ __('admin/info/news.dislike') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label class="form-label">{{ __('admin/info/news.pinned') }}</label>
                                <div class="space-x-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-pinned-status-1" name="pinned" value="1" {{ ((old('pinned') ?? $data['pinned']) == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-pinned-status-1">{{ __('admin/info/news.pinned_yes') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-pinned-status-2" name="pinned" value="0" {{ ((old('pinned') ?? $data['pinned']) == 0) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-pinned-status-2">{{ __('admin/info/news.pinned_no') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label class="form-label">{{ __('admin/info/news.status') }}</label>
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
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <div class="form-group">
                                        @php $created_at = ((old('created_at') ?? $data['created_at']) ? \Carbon\Carbon::parse((old('created_at') ?? $data['created_at']))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')); @endphp
                                        <label class="form-label" for="input-created-at">{{ __('admin/info/news.created_at') }}</label>
                                        <input type="datetime-local" class="form-control" id="input-created-at" name="created_at" value="{{ $created_at }}" placeholder="{{ __('admin/info/news.created_at') }}">
                                    </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('.desc'), {removePlugins: ['ImageUpload', 'EasyImage']}).catch(error => {console.error(error);});
        });

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            $('.preload-container').addClass('active');

            const formData = new FormData();
            const path = 'images/temp/news';
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
                        document.getElementById('preview-image').src = '/images/temp/news/'+data.filename;
                        document.getElementById('image-path').value = '/images/temp/news/'+data.filename;
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

        .ck-editor__editable_inline {
            min-height: 400px;
        }

        .preload-container {
            width: 600px;
            height: 340px;
        }
    </style>
@endsection
