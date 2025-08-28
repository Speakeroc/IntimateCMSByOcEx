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
                    {{ __('admin/page_titles.users_group_add') }}
                </h3>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('user_group.store') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.users_group_add') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="name">{{ __('admin/system/users_group.title') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="{{ __('admin/system/users_group.title') }}">
                            @error('name')
                            <script>
                                kbNotify('error', '{{ $message }}');
                            </script>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="user-login">{{ __('admin/system/users_group.permission_access') }}</label>
                                    <div class="py-2 pb-2 ex_bordered_group">
                                        @foreach($data['permissions']['access'] as $permission)
                                            @if($permission['separate'])
                                                <h5 class="my-2">{{ $permission['name'] }}</h5>
                                            @else
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="switch-default{!! $permission['number'] !!}" name="permission[{{ $permission['type'] }}][]" value="{{ $permission['permission'] }}">
                                                    <label class="form-check-label" for="switch-default{!! $permission['number'] !!}">{!! $permission['name'] !!}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="btn-group mt-4">
                                        <button type="button" onclick="$(this).parent().parent().find(':checkbox').prop('checked', true);"  class="btn btn-primary">
                                            {{ __('admin/system/users_group.select_all') }}
                                        </button>
                                        <button type="button" onclick="$(this).parent().parent().find(':checkbox').prop('checked', false);"  class="btn btn-danger">
                                            {{ __('admin/system/users_group.unselect_all') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="user-login">{{ __('admin/system/users_group.permission_modify') }}</label>
                                    <div class="py-2 ex_bordered_group">
                                        @foreach($data['permissions']['modify'] as $permission)
                                            @if($permission['separate'])
                                                <h5 class="my-2">{{ $permission['name'] }}</h5>
                                            @else
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="switch-default{!! $permission['number'] !!}" name="permission[{{ $permission['type'] }}][]" value="{{ $permission['permission'] }}">
                                                    <label class="form-check-label" for="switch-default{!! $permission['number'] !!}">{!! $permission['name'] !!}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="btn-group mt-4">
                                        <button type="button" onclick="$(this).parent().parent().find(':checkbox').prop('checked', true);"  class="btn btn-primary">
                                            {{ __('admin/system/users_group.select_all') }}
                                        </button>
                                        <button type="button" onclick="$(this).parent().parent().find(':checkbox').prop('checked', false);"  class="btn btn-danger">
                                            {{ __('admin/system/users_group.unselect_all') }}
                                        </button>
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
