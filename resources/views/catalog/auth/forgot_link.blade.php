@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/auth.css')
@endsection
@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                kbNotify('danger', '{!! $error !!}')
            </script>
        @endforeach
    @endif
    <div class="container">
        <div class="ex_auth_page_block">
            @if (session('accept'))
                <h1 class="ex_auth_page_title">{{ __('catalog/auth.forgot_success_title') }}</h1>
                <div>{!! session('accept') !!}</div>
            @else
                <h1 class="ex_auth_page_title">{{ $data['title'] }}</h1>
                <div>{!! $data['text'] !!}</div>
                @if($data['forgot'])
                    <br>
                    <form action="{{ route('client.auth.forgot_link', ['id' => $data['id'], 'token' => $data['token']]) }}" method="post" style="text-align: left;max-width: 500px;margin: 0 auto;">
                        @csrf
                        <div class="mb-3">
                            <label for="input-password" class="form-label">{{ __('catalog/auth.password') }}</label>
                            <input type="password" name="password" id="input-password" class="form-control" readonly onfocus="this.removeAttribute('readonly')" placeholder="{{ __('catalog/auth.password') }}">
                        </div>
                        <div class="mb-3">
                            <label for="input-password_confirmation" class="form-label">{{ __('catalog/auth.password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" id="input-password_confirmation" class="form-control" placeholder="{{ __('catalog/auth.password_confirmation') }}">
                        </div>
                        <button type="submit" class="ex_contant_btn_submit">{{ __('catalog/auth.forgot_btn') }}</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
