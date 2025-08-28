@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="{{ url('builder/admin/js/inputMask/jquery.inputmask.min.js') }}"></script>
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-image" style="background-image: url('{{ url('images/admin/photo16@2x.jpg') }}');">
            <div class="content py-6">
                <h3 class="text-center text-white mb-0">
                    {{ __('admin/page_titles.blacklist_add') }}
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
            <form action="{{ route('blacklist.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.blacklist_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">{{ __('admin/posts/blacklist.phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" id="input-phone" placeholder="{{ __('admin/posts/blacklist.phone') }}">
                                    <script>
                                        document.getElementById('input-phone').addEventListener('input', function(e) {
                                            this.value = this.value.replace(/[^0-9]/g, '');
                                            if (this.value.length > 16) {
                                                this.value = this.value.slice(0, 16);
                                            }
                                        });
                                        document.getElementById('input-phone').addEventListener('paste', function(e) {
                                            e.preventDefault();
                                            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                                            this.value = pasteData.replace(/[^0-9]/g, '').slice(0, 16);
                                        });
                                    </script>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-textarea-input">{{ __('admin/posts/blacklist.text') }}</label>
                                    <textarea class="form-control" id="input-text" name="text" rows="4" placeholder="{{ __('admin/posts/blacklist.text') }}">{{ old('text') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label>{{ __('admin/posts/blacklist.rating') }}</label>
                                        <select name="rating" id="input-rating" class="form-select">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" @if(old('rating') == $i) selected @endif>{{ trans_choice('admin/posts/blacklist.rating_choice', $i, ['num' => $i]) }}</option>
                                            @endfor
                                        </select>
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
        $(document).ready(function(){
            $('#input-phone').inputmask({
                mask: "+7 (999) 999-99-99",
                showMaskOnHover: false,
                showMaskOnFocus: true
            });
        });
    </script>
@endsection
