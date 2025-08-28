@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.category') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/posts/category.list') }}</h3>
                    <div class="block-options">
                        <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary">{{ __('buttons.add') }}</a>
                    </div>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 4%;">#</th>
                                <th>{{ __('admin/posts/category.title') }}</th>
                                <th style="width: 15%;">{{ __('admin/posts/category.type') }}</th>
                                <th style="width: 15%;">{{ __('admin/posts/category.status') }}</th>
                                <th class="text-center" style="width: 100px;"><i class="fa-solid fa-solar-panel"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="text-center">{{ $item['id'] }}</td>
                                    <td class="fw-semibold">
                                        {{ $item['title'] }}
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $item['only_verify'] }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $item['status'] ? 'bg-primary' : 'bg-danger' }}">{{ $item['status'] ? __('lang.status_on') : __('lang.status_off') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('category.destroy', $item['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">
                                                <a href="{{ route('category.edit', $item['id']) }}" class="btn btn-alt-info">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger ex_confirmation">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if(!$data['items'])
                                <tr><td class="text-center" colspan="10">{{ __('lang.list_is_empty') }}</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
