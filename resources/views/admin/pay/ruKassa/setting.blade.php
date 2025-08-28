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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.payment_ruKassa') }}</h1>
                </div>
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    kbNotify('danger', '{{ $error }}');
                </script>
            @endforeach
        @endif

        <div class="content">
            <form action="{{ route('admin.pay.payment_ruKassa') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.payment_ruKassa') }}</h3>
                        <div class="block-options">
                            <div class="btn-group btn-group-sm pe-2">
                                <button type="submit" form="form-save" id="form-save-button"
                                        class="btn btn-primary">{{ __('buttons.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="block-content tab-content">
                        <div class="block block-rounded">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="ruKassa_status">{{ __('admin/pay/ruKassa.status') }}</label>
                                        @php $ruKassa_status = old('ruKassa.status') ?? ($data['ruKassa']['status'] ?? 0); @endphp
                                        <select class="form-select" id="select-post-publish-status" name="ruKassa[status]">
                                            <option value="1" @if($ruKassa_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                            <option value="0" @if($ruKassa_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="ruKassa_shop_id">{{ __('admin/pay/ruKassa.shop_id') }}</label>
                                        <input type="text" name="ruKassa[shop_id]" value="{{ old('ruKassa.shop_id') ?? ($data['ruKassa']['shop_id'] ?? '') }}" class="form-control" id="ruKassa_shop_id" placeholder="{{ __('admin/pay/ruKassa.shop_id') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="ruKassa_token">{{ __('admin/pay/ruKassa.token') }}</label>
                                        <input type="text" name="ruKassa[token]" value="{{ old('ruKassa.token') ?? ($data['ruKassa']['token'] ?? '') }}" class="form-control" id="ruKassa_token" placeholder="{{ __('admin/pay/ruKassa.token') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-ruKassa_order_wait_id">{{ __('admin/pay/ruKassa.order_wait_id') }}</label>
                                        @php $ruKassa_order_wait_id = old('ruKassa.order_wait_id') ?? ($data['ruKassa']['order_wait_id'] ?? null); @endphp
                                        <select class="form-select" id="select-ruKassa_order_wait_id" name="ruKassa[order_wait_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $ruKassa_order_wait_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-ruKassa_order_success_id">{{ __('admin/pay/ruKassa.order_success_id') }}</label>
                                        @php $ruKassa_order_success_id = old('ruKassa.order_success_id') ?? ($data['ruKassa']['order_success_id'] ?? null); @endphp
                                        <select class="form-select" id="select-ruKassa_order_success_id" name="ruKassa[order_success_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $ruKassa_order_success_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-ruKassa_order_fail_id">{{ __('admin/pay/ruKassa.order_fail_id') }}</label>
                                        @php $ruKassa_order_fail_id = old('ruKassa.order_fail_id') ?? ($data['ruKassa']['order_fail_id'] ?? null); @endphp
                                        <select class="form-select" id="select-ruKassa_order_fail_id" name="ruKassa[order_fail_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $ruKassa_order_fail_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="ruKassa_min_amount">{{ __('admin/pay/ruKassa.min_amount') }}</label>
                                        <input type="number" name="ruKassa[min_amount]" value="{{ old('ruKassa.min_amount') ?? ($data['ruKassa']['min_amount'] ?? '') }}" class="form-control" id="ruKassa_min_amount" placeholder="{{ __('admin/pay/ruKassa.min_amount') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="input-ruKassa-success-url">{{ __('admin/pay/ruKassa.success_url') }}</label>
                                        <input type="text" value="{{ $data['ruKassa_success_url'] }}" class="form-control" id="input-ruKassa-success-url">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="input-ruKassa-fail-url">{{ __('admin/pay/ruKassa.fail_url') }}</label>
                                        <input type="text" value="{{ $data['ruKassa_fail_url'] }}" class="form-control" id="input-ruKassa-fail-url">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">{{ __('admin/pay/ruKassa.api_check') }}<br><small>({{ $data['ruKassa_last_check'] }})</small></label>
                                        <input type="text" value="{{ $data['ruKassa_api_checker'] }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="block block-themed">
                <div class="block-content bg-primary text-white">{{ __('admin/pay/ruKassa.notify_file_create') }}</div>
            </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
