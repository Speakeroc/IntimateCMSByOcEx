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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.payment_aaio') }}</h1>
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
            <form action="{{ route('admin.pay.payment_aaio') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.payment_aaio') }}</h3>
                        <div class="block-options">
                            <div class="btn-group btn-group-sm pe-2">
                                <button type="submit" form="form-save" id="form-save-button" class="btn btn-primary">{{ __('buttons.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="block-content tab-content">
                        <div class="block block-rounded">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="aaio_status">{{ __('admin/pay/aaio.status') }}</label>
                                        @php $aaio_status = old('aaio.status') ?? ($data['aaio']['status'] ?? 0); @endphp
                                        <select class="form-select" id="select-post-publish-status" name="aaio[status]">
                                            <option value="1" @if($aaio_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                            <option value="0" @if($aaio_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="aaio_merchant_id">{{ __('admin/pay/aaio.merchant_id') }}</label>
                                        <input type="text" name="aaio[merchant_id]" value="{{ old('aaio.merchant_id') ?? ($data['aaio']['merchant_id'] ?? '') }}" class="form-control" id="aaio_merchant_id" placeholder="{{ __('admin/pay/aaio.merchant_id') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="aaio_secret_key_1">{{ __('admin/pay/aaio.secret_key_1') }}</label>
                                        <input type="text" name="aaio[secret_key_1]" value="{{ old('aaio.secret_key_1') ?? ($data['aaio']['secret_key_1'] ?? '') }}" class="form-control" id="aaio_secret_key_1" placeholder="{{ __('admin/pay/aaio.secret_key_1') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="aaio_secret_key_2">{{ __('admin/pay/aaio.secret_key_2') }}</label>
                                        <input type="text" name="aaio[secret_key_2]" value="{{ old('aaio.secret_key_2') ?? ($data['aaio']['secret_key_2'] ?? '') }}" class="form-control" id="aaio_secret_key_2" placeholder="{{ __('admin/pay/aaio.secret_key_2') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-aaio_order_wait_id">{{ __('admin/pay/aaio.order_wait_id') }}</label>
                                        @php $aaio_order_wait_id = old('aaio.order_wait_id') ?? ($data['aaio']['order_wait_id'] ?? null); @endphp
                                        <select class="form-select" id="select-aaio_order_wait_id" name="aaio[order_wait_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $aaio_order_wait_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-aaio_order_success_id">{{ __('admin/pay/aaio.order_success_id') }}</label>
                                        @php $aaio_order_success_id = old('aaio.order_success_id') ?? ($data['aaio']['order_success_id'] ?? null); @endphp
                                        <select class="form-select" id="select-aaio_order_success_id" name="aaio[order_success_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $aaio_order_success_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-aaio_order_fail_id">{{ __('admin/pay/aaio.order_fail_id') }}</label>
                                        @php $aaio_order_fail_id = old('aaio.order_fail_id') ?? ($data['aaio']['order_fail_id'] ?? null); @endphp
                                        <select class="form-select" id="select-aaio_order_fail_id" name="aaio[order_fail_id]">
                                            @foreach($data['order_status_ids'] as $order_status_id)
                                                <option value="{{ $order_status_id['id'] }}" @if($order_status_id['id'] == $aaio_order_fail_id) selected @endif>{{ $order_status_id['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="aaio_min_amount">{{ __('admin/pay/aaio.min_amount') }}</label>
                                        <input type="number" name="aaio[min_amount]" value="{{ old('aaio.min_amount') ?? ($data['aaio']['min_amount'] ?? '') }}" class="form-control" id="aaio_min_amount" placeholder="{{ __('admin/pay/aaio.min_amount') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="input-aaio-status-url">{{ __('admin/pay/aaio.aaio_status_url') }}</label>
                                        <input type="text" value="{{ $data['aaio_status_url'] }}" class="form-control" id="input-aaio-status-url">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="input-aaio-success-url">{{ __('admin/pay/aaio.aaio_success_url') }}</label>
                                        <input type="text" value="{{ $data['aaio_success_url'] }}" class="form-control" id="input-aaio-success-url">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="input-aaio-fail-url">{{ __('admin/pay/aaio.aaio_fail_url') }}</label>
                                        <input type="text" value="{{ $data['aaio_fail_url'] }}" class="form-control" id="input-aaio-fail-url">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="block block-themed">
                <div class="block-content bg-primary text-white">{{ __('admin/pay/aaio.notify_file_create') }}</div>
            </div>
        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
