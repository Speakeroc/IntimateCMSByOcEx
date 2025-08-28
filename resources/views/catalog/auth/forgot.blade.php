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
            <h1 class="ex_auth_page_title">{{ $data['title'] }}</h1>
            <form action="{{ route('client.auth.forgot') }}" method="post" style="text-align: left;max-width: 500px;margin: 0 auto;">
                @csrf
                <div class="col-12 mb-2">
                    <label for="input-email" class="form-label">{{ __('catalog/info/contact.form_email') }}</label>
                    <input type="email" name="form_email" id="input-email" value="{{ old('form_email') }}" class="form-control" placeholder="{{ __('catalog/info/contact.form_email') }}">
                </div>
                <button type="submit" class="ex_contant_btn_submit">{{ __('catalog/info/contact.form_submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
