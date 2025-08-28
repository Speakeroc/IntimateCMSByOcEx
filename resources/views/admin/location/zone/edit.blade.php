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
                    {{ __('admin/page_titles.zone_edit') }}
                </h3>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('zone.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.zone_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/location/zone.title') }}</label>
                                    <input type="text" name="title" value="{{ old('title') ?? $data['title'] }}" class="form-control @error('title') is-invalid @enderror" id="input-title" placeholder="{{ __('admin/location/zone.title') }}">
                                    @error('title')
                                    <script>
                                        kbNotify('error', '{{ $message }}');
                                    </script>
                                    @enderror
                                </div>
                                <div class="form-floating mb-4">
                                    <select class="form-select" id="input-city_id" name="city_id">
                                        @foreach($data['city'] as $city)
                                            <option value="{{ $city['id'] }}" @if((old('city_id') ?? $data['city_id']) == $city['id']) selected @endif>{{ $city['title'] }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label" for="input-status">{{ __('admin/location/zone.city') }}</label>
                                </div>
                                <div class="form-floating mb-4">
                                    @php $status = old('status') ?? $data['status']; @endphp
                                    <select class="form-select" id="example-select-floating" name="status">
                                        <option value="1" @if($status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                        <option value="0" @if($status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                    </select>
                                    <label class="form-label" for="example-select-floating">{{ __('admin/posts/category.status') }}</label>
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
