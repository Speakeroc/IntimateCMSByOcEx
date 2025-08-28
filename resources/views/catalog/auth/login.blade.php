@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
@endsection
@section('content')
    @vite('resources/catalog/css/auth.css')

    <div class="container auth-page-sign-in">
        <form action="{{ route('client.auth.sign_in') }}" method="post">
            @csrf
            <div class="ex_auth_block">
                <h1 class="ex_auth_block_title">{{ __('catalog/auth.sign_in') }}</h1>
                <div class="row">
                    <div class="col-12 mb-2">
                        <label for="input-email_or_login" class="form-label">{{ __('catalog/auth.email_or_login') }}</label>
                        <input type="text" name="email_or_login" id="input-email_or_login" value="{{ old('email_or_login') }}" class="form-control @error('email_or_login') is-invalid @enderror">
                        @error('email_or_login')
                        <div id="input-login-invalid" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label for="input-password" class="form-label">{{ __('catalog/auth.password') }}</label>
                        <input type="password" name="password" id="input-password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <div id="input-password-invalid" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="ex_auth_block_btn mb-2">{{ __('catalog/auth.btn_sing_in') }}</button>
                        <a href="{{ route('client.auth.forgot') }}" class="ex_auth_block_btn_reset">{{ __('catalog/auth.link_forgot') }}</a>
                        <span class="ex_auth_block_text_link">
                            {!! __('catalog/auth.go_to_signup', ['route' => route('client.auth.sign_up')]) !!}
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
