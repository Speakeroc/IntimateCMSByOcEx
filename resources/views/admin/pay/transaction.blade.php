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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.tags') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">{{ __('admin/pay/transaction.list') }}</h3>
                </div>
                <div class="block-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>{{ __('admin/pay/transaction.type') }}</th>
                                <th>{{ __('admin/pay/transaction.price') }}</th>
                                <th>{{ __('admin/pay/transaction.short') }}</th>
                                <th>{{ __('admin/pay/transaction.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['items'] as $item)
                                <tr>
                                    <td class="fw-semibold">{!! $item['type'] !!}</td>
                                    <td>{{ $item['price'] }}</td>
                                    <td>{{ $item['short'] }} {!! ($item['order_status']) ? '| '.$item['order_status'] : '' !!}</td>
                                    <td><a href="{{ $item['user']['user_link'] }}" target="_blank">{{ $item['user']['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></td>
                                    <td>{{ $item['date'] }}</td>
                                </tr>
                            @endforeach
                            @if(!$data['items'])
                                <tr><td class="text-center" colspan="10">{{ __('lang.list_is_empty') }}</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @if(!empty($data['data']->links('admin/common/paginate')))
                        {{ $data['data']->links('admin/common/paginate') }}
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
