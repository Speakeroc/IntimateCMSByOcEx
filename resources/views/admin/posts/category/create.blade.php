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
                    {{ __('admin/page_titles.category_add') }}
                </h3>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('category.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.category_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/posts/category.title') }}</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="input-title-" placeholder="{{ __('admin/posts/category.title') }}">
                                    @error('title')
                                    <script>
                                        kbNotify('error', '{{ $message }}');
                                    </script>
                                    @enderror
                                </div>
                                <div class="mb-4" style="user-select:none;">
                                    <label class="form-label" for="example-text-input">{{ __('admin/posts/category.type') }}</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="0" id="only_verify_0" name="only_verify" {{ (old('only_verify') == 0) ? "checked" : "" }} onclick="toggleCheckbox('only_verify_0', 'only_verify_1')">
                                        <label class="form-check-label" for="only_verify_0">{{ __('admin/posts/category.no_only_verify') }}</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1" id="only_verify_1" name="only_verify" {{ (old('only_verify') == 1) ? "checked" : "" }} onclick="toggleCheckbox('only_verify_1', 'only_verify_0')">
                                        <label class="form-check-label" for="only_verify_1">{{ __('admin/posts/category.only_verify') }}</label>
                                    </div>
                                    <script>
                                        function toggleCheckbox(currentId, oppositeId) {
                                            var currentCheckbox = document.getElementById(currentId);
                                            var oppositeCheckbox = document.getElementById(oppositeId);
                                            if (currentCheckbox.checked) {
                                                oppositeCheckbox.checked = false;
                                            } else {
                                                if (!oppositeCheckbox.checked) {
                                                    currentCheckbox.checked = true;
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/posts/category.meta_title') }}</label>
                                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control" id="input-meta_title" placeholder="{{ __('admin/posts/category.meta_title') }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/posts/category.meta_description') }}</label>
                                    <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="form-control" id="input-meta_description" placeholder="{{ __('admin/posts/category.meta_description') }}">
                                </div>
                                <div class="form-floating mb-4">
                                    @php $status = old('status'); @endphp
                                    <select class="form-select" id="example-select-floating" name="status">
                                        <option value="1" @if(($status != null) && $status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                        <option value="0" @if(($status != null) && $status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                    </select>
                                    <label class="form-label" for="example-select-floating">{{ __('admin/posts/category.status') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="description">{!! __('admin/posts/category.description') !!}</label>
                                    <textarea name="description" class="ck_description">{{ old('description') }}</textarea>
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
    <script src="{{ url('builder/admin/js/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('.ck_description'), {removePlugins: ['ImageUpload', 'EasyImage']}).catch(error => {console.error(error);});
        });
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
@endsection
