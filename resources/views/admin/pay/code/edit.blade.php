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
                    {{ __('admin/page_titles.payment_code_edit') }}
                </h3>
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    kbNotify('danger', '{{ $error }}')
                </script>
            @endforeach
        @endif

        <div class="content">
            <form action="{{ route('payment_code.update', $data['id']) }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">{{ __('admin/page_titles.payment_code_edit') }}</h3>
                        <div class="block-options">
                            <button type="submit" form="form-save" id="form-save-button" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> {{ __('buttons.save') }}</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-nominal">{{ __('admin/pay/code.nominal') }} ({{ $data['currency_symbol'] }})</label>
                                    <input type="number" class="form-control" id="input-nominal" name="nominal" value="{{ old('nominal') ?? $data['nominal'] }}" placeholder="{{ __('admin/pay/code.nominal') }} ({{ $data['currency_symbol'] }})">
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-bonus">{{ __('admin/pay/code.bonus_form') }} ({{ $data['currency_symbol'] }})</label>
                                    <input type="number" class="form-control" id="input-bonus" name="bonus" value="{{ old('bonus') ?? $data['bonus'] }}" placeholder="{{ __('admin/pay/code.bonus_form') }} ({{ $data['currency_symbol'] }})">
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="select-status">{{ __('admin/pay/code.status') }}</label>
                                    @php $status = old('status') ?? $data['status']; @endphp
                                    <select class="form-select" id="input-status" name="status">
                                        <option value="1" @if($status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                        <option value="0" @if($status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="input-pay-link">{{ __('admin/pay/code.pay_link') }}</label>
                                    <input type="text" class="form-control" id="input-pay-link" name="pay_link" value="{{ old('pay_link') ?? $data['pay_link'] }}" placeholder="{{ __('admin/pay/code.pay_link') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="alert alert-primary d-flex align-items-center">
                                    <p class="m-0 fs-14">{{ __('admin/pay/code.pin_code_rules') }}</p>
                                </div>
                                <div class="mb-3 position-relative">
                                    <label for="input-codes">{!! __('admin/pay/code.pin_codes') !!}</label>
                                    <textarea name="pin_codes" class="form-control" id="input-pin-codes" rows="10">{{ old('pin_codes') ?? $data['pin_codes'] }}</textarea>
                                    <div class="btn-group btn-group-sm mt-2" role="group" aria-label="Small Horizontal Primary">
                                        <button type="button" class="btn btn-primary">{{ __('admin/pay/code.generate') }}</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(2);">2-2</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(3);">3-3</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(4);">4-4</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(5);">5-5</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(6);">6-6</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(7);">7-7</button>
                                        <button type="button" class="btn btn-primary" onclick="generateCodes(8);">8-8</button>
                                    </div>
                                    <div id="count-pin-codes"></div>
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
    <script>
        var textarea = document.getElementById("input-pin-codes");
        var counter = document.getElementById("count-pin-codes");
        function countLines() {
            var value = textarea.value;
            var lines = value.split("\n").filter(function(line) {
                return line.trim() !== "";
            });
            var counterValue = lines.length;
            counter.innerHTML = "{{ __('admin/pay/code.pin_code_count') }}: " + counterValue + 'шт.';
        }
        countLines();
        textarea.addEventListener("input", countLines);

        function generateRandomCode(length) {
            const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            let code = '';
            for (let i = 0; i < length; i++) {
                code += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            code += '-';
            for (let i = 0; i < length; i++) {
                code += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return code;
        }

        function generateCodes(length) {
            const codes = [];
            const numCodes = Math.floor(Math.random() * 41) + 10;
            for (let i = 0; i < numCodes; i++) {
                codes.push(generateRandomCode(length));
            }
            document.getElementById("input-pin-codes").value = codes.join("\n");
            countLines();
        }
    </script>
@endsection
