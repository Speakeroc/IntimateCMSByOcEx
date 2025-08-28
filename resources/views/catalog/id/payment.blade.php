@extends('catalog/layout/layout')
@section('header', $data['elements']['header'])
@section('css_js_header')
    @vite('resources/catalog/css/id/payment.css')
@endsection
@section('content')
    <div class="container">
        @if(isset($data['breadcrumb']['breadcrumb']) && !empty($data['breadcrumb']['breadcrumb']))
            <nav aria-label="ex_breadcrumb">
                <ol class="ex_breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    @foreach($data['breadcrumb']['breadcrumb'] as $breadcrumb)
                        <li class="ex_breadcrumb-item @if($loop->last) active @endif" itemscope
                            itemtype="http://schema.org/ListItem">
                            <a href="{{ $breadcrumb['link'] }}" itemprop="item">
                                <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                            </a>
                            <meta itemprop="position" content="1"/>
                        </li>
                    @endforeach
                </ol>
            </nav>
            @if(isset($data['breadcrumb']['list']) && !empty($data['breadcrumb']['list']))
                <script type="application/ld+json">{!! $data['breadcrumb']['list'] !!}</script>
            @endif
        @endif
    </div>

    <div class="container">
        <h1 class="ex_post_page_title">{{ $data['title'] }}</h1>

        <div class="ex_pay_page_block">

            @if($data['ruKassa']['status'] || $data['aaio']['status'] || $data['pin_code']['status'])
                <div class="row">
                    @if($data['ruKassa']['status'])
                        <div class="col-12 {{ $data['payments_class'] }}">
                            <div class="d-flex align-items-center justify-content-start gap-3 mb-2 ex_online_page_title">
                                <h4 class="ex_payment_page_title mb-2">{{ __('catalog/id/payment.online_payment') }} RuKassa</h4>
                            </div>
                            <div class="input-group input-group-sm ex_online_payment_input">
                                <input type="number" min="0" max="99999999" id="ruKassa_amount" class="form-control" name="amount" value="" placeholder="{{ $data['ruKassa']['min_amount_text'] }}">
                                <button type="button" id="ruKassa-submit-btn" onclick="ruKassaGetDataPage()" class="ex_pay_page_block_empty_btn btn-sm">{{ __('buttons.replenish') }}</button>
                            </div>
                        </div>
                    @endif
                    @if($data['aaio']['status'])
                        <div class="col-12 {{ $data['payments_class'] }}">
                            <div class="d-flex align-items-center justify-content-start gap-3 mb-2 ex_online_page_title">
                                <h4 class="ex_payment_page_title mb-2">{{ __('catalog/id/payment.online_payment') }} AAIO</h4>
                            </div>
                            <div class="input-group input-group-sm ex_online_payment_input">
                                <input type="number" min="0" max="99999999" id="aaio_amount" class="form-control" name="amount" value="" placeholder="{{ $data['aaio']['min_amount_text'] }}">
                                <button type="button" id="aaio-submit-btn" onclick="aaioGetDataPage()" class="ex_pay_page_block_empty_btn btn-sm">{{ __('buttons.replenish') }}</button>
                            </div>
                        </div>
                    @endif
                    @if($data['pin_code']['status'])
                        <div class="col-12 {{ $data['payments_class'] }}">
                            <h4 class="ex_payment_page_title mb-2">{{ __('catalog/id/payment.pin_payment') }}</h4>
                            <div class="input-group input-group-sm ex_online_payment_input">
                                <input type="text" id="pin_code" class="form-control" name="pin_code" placeholder="{{ __('catalog/id/payment.enter_pin_code') }}">
                                <button type="button" id="pin-code-submit" onclick="pinCodePay()" class="ex_pay_page_block_empty_btn btn-sm">{{ __('buttons.replenish') }}</button>
                            </div>
                        </div>
                    @endif
                </div>

                <hr>
            @endif

            <h4 class="ex_payment_page_title">{{ __('catalog/id/payment.pin_variable') }}</h4>
            <div class="row">
                @foreach($data['pin_code']['items'] as $item)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="ex_pin_code_item">
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <div class="ex_pin_code_item_n"><span>{{ __('catalog/id/payment.nominal') }}</span><span>{{ $item['nominal'] }}</span></div>
                                <div class="ex_pin_code_item_t">{{ __('catalog/id/payment.will_credited', ['sum' => $item['credited']]) }}</div>
                            </div>
                            @if($item['discount'])
                            <div class="ex_pin_code_item_p">+{{ $item['discount'] }}%</div>
                            @endif
                            <a href="{{ $item['pay_link'] }}" target="_blank" class="ex_pin_code_item_b">{{ __('buttons.payment') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>

        @if($data['ruKassa']['status'])
        document.addEventListener('DOMContentLoaded', (event) => {
            const amountInput = document.getElementById('ruKassa_amount');

            amountInput.addEventListener('input', () => {
                const maxValue = 99999999;
                if (parseInt(amountInput.value) > maxValue) {
                    amountInput.value = maxValue;
                }
            });
        });

        document.getElementById('ruKassa_amount').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                ruKassaGetDataPage();
            }
        });

        var loading = false;
        var ruKassa_submit_btn = document.getElementById('ruKassa-submit-btn');

        function ruKassaGetDataPage() {
            if (!loading) {
                ruKassa_submit_btn.disabled = true;
                loading = true;
                var min_amount = {{ $data['ruKassa']['min_amount'] }};
                var amount = $('#ruKassa_amount').val();
                if (amount < min_amount) {
                    kbNotify('error', '{{ $data['ruKassa']['min_amount_text'] }}');
                    ruKassa_submit_btn.disabled = false;
                    loading = false;
                    return;
                }
                $.ajax({
                    url: '{{ route('client.auth.pay.ruKassa.getData') }}' + '/' + amount,
                    type: 'get',
                    data: {amount:amount},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.status === 'success') {
                            kbNotify('success', data.message);
                            if (data.pay_url) {
                                setTimeout(function () {
                                    window.open(data.pay_url, '_blank');
                                },1000);
                            }
                            loading = false;
                            ruKassa_submit_btn.disabled = false;
                        } else {
                            kbNotify('error', data.message);
                            loading = false;
                            ruKassa_submit_btn.disabled = false;
                        }
                    }
                });
            }
        }
        @endif

        @if($data['aaio']['status'])
        document.addEventListener('DOMContentLoaded', (event) => {
            const amountInput = document.getElementById('aaio_amount');

            amountInput.addEventListener('input', () => {
                const maxValue = 99999999;
                if (parseInt(amountInput.value) > maxValue) {
                    amountInput.value = maxValue;
                }
            });
        });

        document.getElementById('aaio_amount').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                aaioGetDataPage();
            }
        });

        var loading = false;
        var aaio_submit_btn = document.getElementById('aaio-submit-btn');

        function aaioGetDataPage() {
            if (!loading) {
                aaio_submit_btn.disabled = true;
                loading = true;
                var min_amount = {{ $data['aaio']['min_amount'] }};
                var amount = $('#aaio_amount').val();
                if (amount < min_amount) {
                    kbNotify('error', '{{ $data['aaio']['min_amount_text'] }}');
                    aaio_submit_btn.disabled = false;
                    loading = false;
                    return;
                }
                $.ajax({
                    url: '{{ route('client.auth.pay.aaio.getData') }}' + '/' + amount,
                    type: 'get',
                    data: {amount:amount},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.status === 'success') {
                            kbNotify('success', data.message);
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'https://aaio.so/merchant/pay';
                            const fields = {
                                merchant_id: '{{ $data['aaio']['merchant_id'] }}',
                                amount: amount,
                                currency: '{{ $data['aaio']['currency'] }}',
                                order_id: data.order_id,
                                sign: data.sign,
                                lang: data.lang
                            };
                            for (const key in fields) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = fields[key];
                                form.appendChild(input);
                            }
                            document.body.appendChild(form);
                            form.submit();
                            loading = false;
                        } else {
                            kbNotify('error', data.message);
                            loading = false;
                            aaio_submit_btn.disabled = false;
                        }
                    }
                });
            }
        }
        @endif

        @if($data['pin_code']['status'])
        document.getElementById('pin_code').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                pinCodePay();
            }
        });

        var pin_loading = false;
        var pin_submit_btn = document.getElementById('pin-code-submit');

        function pinCodePay() {
            if (!pin_loading) {
                pin_submit_btn.disabled = true;
                pin_loading = true;
                var pin_code = $('#pin_code').val();
                if (!pin_code) {
                    kbNotify('error', '{{ __('catalog/id/payment.empty_pin_code') }}');
                    pin_submit_btn.disabled = false;
                    pin_loading = false;
                    return;
                }
                $.ajax({
                    url: '{{ route('client.auth.pay.pin.getData') }}'+'/'+pin_code,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'success') {
                            kbNotify('success', data.message);
                            document.getElementById("pin_code").value = "";
                        } else {
                            kbNotify('error', data.message);
                            document.getElementById("pin_code").value = "";
                        }
                        pin_submit_btn.disabled = false;
                        pin_loading = false;
                    }
                });
            }
        }
        @endif
    </script>
@endsection
