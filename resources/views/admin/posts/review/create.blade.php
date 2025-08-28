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
                    {{ __('admin/page_titles.review_add') }}
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
            <form action="{{ route('review.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.review_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="select-user">{{ __('admin/posts/review.user') }}</label>
                                    <select class="form-select" id="select-user" name="user_id">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['main_data']['users'] as $user)
                                            <option value="{{ $user['id'] }}" @if((old('user_id')) == $user['id']) selected @endif>{{ __('admin/posts/review.user_r', ['login' => $user['login'], 'email' => $user['email']]) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label" for="select-post-id">{{ __('admin/posts/review.post') }}</label>
                                    <select class="form-select" id="select-post-id" name="post_id">
                                        <option value="">{{ __('lang.no_select') }}</option>
                                        @foreach($data['posts'] as $post)
                                            <option value="{{ $post['id'] }}" {{ ((old('post_id')) == $post['id']) ? 'selected' : '' }}>{{ $post['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-textarea-input">{{ __('admin/posts/review.review') }}</label>
                                    <textarea class="form-control" id="input-text" name="text" rows="4" placeholder="{{ __('admin/posts/review.review') }}">{{ old('text') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label>{{ __('admin/posts/review.rating') }}</label>
                                        <select name="rating" id="input-rating" class="form-select">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" @if((old('rating')) == $i) selected @endif>{{ trans_choice('admin/posts/review.rating_choice', $i, ['num' => $i]) }}</option>
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
@endsection
