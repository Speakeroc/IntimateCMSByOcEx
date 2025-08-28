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
                    {{ __('admin/page_titles.users_add') }}
                </h3>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('users.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.users_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('btns.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="username">{{ __('admin/system/users.username') }}</label>
                                            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="{{ __('admin/system/users.username') }}">
                                            @error('username')
                                            <script>
                                                kbNotify('error', '{{ $message }}');
                                            </script>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="login">{{ __('admin/system/users.login') }}</label>
                                            <input type="text" name="login" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror" id="code" placeholder="{{ __('admin/system/users.login') }}">
                                            @error('login')
                                            <script>
                                                kbNotify('error', '{{ $message }}');
                                            </script>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="email">{{ __('admin/system/users.email') }}</label>
                                            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="name" placeholder="{{ __('admin/system/users.email') }}">
                                            @error('email')
                                            <script>
                                                kbNotify('error', '{{ $message }}');
                                            </script>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-4">
                                            <select class="form-select" id="select-email-activate" name="email_activate">
                                                <option value="1" @if(old('email_activate') == 1) selected @endif>{{ __('lang.yes') }}</option>
                                                <option value="0" @if(old('email_activate') == 0) selected @endif>{{ __('lang.no') }}</option>
                                            </select>
                                            <label class="form-label" for="example-select-floating">{{ __('admin/system/users.email_active') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="balance">{{ __('admin/system/users.balance') }}</label>
                                            <div class="input-group">
                                                <input type="number" name="balance" value="{{ old('balance') }}" class="form-control @error('balance') is-invalid @enderror" id="input-balance" placeholder="{{ __('admin/system/users.balance') }}">
                                                <span class="input-group-text">{{ $data['currency_symbol'] }}</span>
                                                @error('balance')
                                                <script>
                                                    kbNotify('error', '{{ $message }}');
                                                </script>
                                                @enderror
                                            </div>
                                            <div class="btn-group btn-group-sm mt-2 w-100">
                                                <button type="button" class="btn btn-primary add_balance fs-7" data-balance="500">500{{ $data['currency_symbol'] }}</button>
                                                <button type="button" class="btn btn-primary add_balance fs-7" data-balance="1000">1000{{ $data['currency_symbol'] }}</button>
                                                <button type="button" class="btn btn-primary add_balance fs-7" data-balance="5000">5 000{{ $data['currency_symbol'] }}</button>
                                                <button type="button" class="btn btn-primary add_balance fs-7" data-balance="10000">10 000{{ $data['currency_symbol'] }}</button>
                                                <button type="button" class="btn btn-primary add_balance fs-7" data-balance="25000">25 000{{ $data['currency_symbol'] }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="password">{{ __('admin/system/users.password') }}</label>
                                            <input type="text" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="code" placeholder="{{ __('admin/system/users.password') }}">
                                            @error('password')
                                            <script>
                                                kbNotify('error', '{{ $message }}');
                                            </script>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating mb-4">
                                    <select class="form-select" id="select-user-group-id" name="user_group_id">
                                        @foreach($data['groups'] as $key)
                                            <option value="{{ $key['id'] }}" @if(old('user_group_id') == $key['id']) selected @endif>{{ $key['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label" for="example-select-floating">{{ __('admin/system/users.group') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
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
        //Balance
        const buttons = document.querySelectorAll('.add_balance');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const balance = this.getAttribute('data-balance');
                const inputBalance = document.getElementById('input-balance');
                inputBalance.value = balance;
            });
        });
    </script>
    <style>
        #upload-container {
            width: 300px;
            height: 300px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #preview-container {
            position: relative;
            width: 300px;
            height: 300px;
            border: 2px dashed #ccc;
        }

        #preview-container img.preview-img {
            width: 296px;
            height: 296px;
            object-fit: contain;
        }

        #upload-label {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 50px;
        }

        #delete-preview {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 15px;
            height: 35px;
            width: 35px;
            border-radius: 4px;
        }

        .preload-container {
            width: 300px;
            height: 300px;
        }
    </style>
@endsection
