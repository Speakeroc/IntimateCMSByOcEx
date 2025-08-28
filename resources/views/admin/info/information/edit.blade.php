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
                    {{ __('admin/page_titles.information_edit') }}
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
            <form action="{{ route('information.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.information_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="input-title">{{ __('admin/info/information.title') }}</label>
                            <input type="text" name="title" value="{{ old('title') ?? $data['title'] }}" class="form-control" id="input-title" placeholder="{{ __('admin/info/information.title') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-desc">{{ __('admin/info/information.desc') }}</label>
                            <textarea class="form-control desc" id="input-desc" name="desc" rows="4" placeholder="{{ __('admin/info/information.desc') }}">{{ old('desc') ?? $data['desc'] }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-meta_title">{{ __('admin/info/information.meta_title') }}</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') ?? $data['meta_title'] }}" class="form-control" id="input-meta_title" placeholder="{{ __('admin/info/information.meta_title') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="input-meta-description">{{ __('admin/info/information.meta_description') }}</label>
                            <textarea class="form-control" id="input-meta-description" name="meta_description" rows="4" placeholder="{{ __('admin/info/information.meta_description') }}">{{ old('meta_description') ?? $data['meta_description'] }}</textarea>
                        </div>
                        <label class="form-label" for="example-text-input">{{ __('admin/info/information.banner') }}</label>
                        <div class="row">
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-seo_url">{{ __('admin/info/information.seo_url') }}</label>
                                    <input type="text" name="seo_url" value="{{ old('seo_url') ?? $data['seo_url'] }}" class="form-control" id="input-seo_url" placeholder="{{ __('admin/info/information.seo_url') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-views">{{ __('admin/info/information.views') }}</label>
                                    <input type="number" name="views" value="{{ old('views') ?? $data['views'] }}" class="form-control" id="input-views" placeholder="{{ __('admin/info/information.views') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <label class="form-label">{{ __('admin/info/information.status') }}</label>
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
                                <label class="form-label">{{ __('admin/info/information.in_menu') }}</label>
                                <div class="space-x-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-in_menu-in_menu-1" name="in_menu" value="1" {{ ((old('in_menu') ?? $data['in_menu']) == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-in_menu-in_menu-1">{{ __('lang.status_on') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radios-in_menu-in_menu-2" name="in_menu" value="0" {{ ((old('in_menu') ?? $data['in_menu']) == 0) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radios-in_menu-in_menu-2">{{ __('lang.status_off') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="mb-4">
                                    <div class="form-group">
                                        @php $created_at = ((old('created_at') ?? $data['created_at']) ? \Carbon\Carbon::parse((old('created_at') ?? $data['created_at']))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')); @endphp
                                        <label class="form-label" for="input-created-at">{{ __('admin/info/information.created_at') }}</label>
                                        <input type="datetime-local" class="form-control" id="input-created-at" name="created_at" value="{{ $created_at }}" placeholder="{{ __('admin/info/information.created_at') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header d-flex justify-content-center">
                        <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
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
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
@endsection
