@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
@endsection
@section('content')
    @vite('resources/catalog/css/auth.css')

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                kbNotify('danger', '{!! $error !!}')
            </script>
        @endforeach
    @endif

    <div class="container auth-page-sign-up">
        <form action="{{ route('client.auth.sign_up') }}" method="post">
            @csrf
            <div class="ex_auth_block">
                <h1 class="ex_auth_block_title">{{ __('catalog/auth.sign_up') }}</h1>
                <div class="row">
                    <div class="col-sm-6 col-12 mb-2">
                        <label for="input-login" class="form-label">{{ __('catalog/auth.login') }}</label>
                        <input type="text" name="login" id="input-login" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror">
                    </div>
                    <div class="col-sm-6 col-12 mb-2">
                        <label for="input-name" class="form-label">{{ __('catalog/auth.name') }}</label>
                        <input type="text" name="name" id="input-name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    </div>
                    <div class="col-12 mb-2">
                        <label for="input-email" class="form-label">{{ __('catalog/auth.email') }}</label>
                        <input type="email" name="email" id="input-email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <div class="col-sm-6 col-12 mb-2">
                        <label for="input-password" class="form-label">{{ __('catalog/auth.password') }}</label>
                        <input type="password" name="password" id="input-password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                    </div>
                    <div class="col-sm-6 col-12 mb-2">
                        <label for="input-password_confirmation" class="form-label">{{ __('catalog/auth.password_confirmation') }}</label>
                        <input type="password" name="password_confirmation" id="input-password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror">
                    </div>
                    <div class="col-12 mb-2">
                        <div class="form-check d-flex">
                            <input class="form-check-input" type="checkbox" name="privacy" value="1" id="input-privacy" checked required>
                            <label class="form-check-label" for="input-privacy">{!! $data['privacy'] !!}</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="ex_auth_block_btn mb-2">{{ __('catalog/auth.btn_sing_up') }}</button>

                        <span class="ex_auth_block_text_link">
                            {!! __('catalog/auth.go_to_signin', ['route' => route('client.auth.sign_in')]) !!}
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('css_js_footer')
@endsection
@section('footer', $data['elements']['footer'])
