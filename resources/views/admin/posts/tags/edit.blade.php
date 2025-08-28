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
                    {{ __('admin/page_titles.tags_edit') }}
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
            <form action="{{ route('tags.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.tags_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <div class="form-group">
                                        @php $tag = old('tag') ?? $data['tag']; @endphp
                                        <label class="form-label" for="input-tag">{{ __('admin/posts/tags.tag') }}</label>
                                        <input type="text" class="form-control" id="input-tag" name="tag" value="{{ $tag }}" placeholder="{{ __('admin/posts/tags.tag') }}">
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
@endsection
