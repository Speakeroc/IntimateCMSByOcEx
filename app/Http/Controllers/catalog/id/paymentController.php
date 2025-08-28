<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\pay\PayCodes;
use App\Models\pay\PayCodesPinCode;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\system\Transaction;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class paymentController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->transaction = new Transaction;
        $this->aaio = $this->getters->getSetting('aaio');
        $this->ruKassa = $this->getters->getSetting('ruKassa');
    }

    public function index()
    {
        $data['title'] = __('catalog/id/payment.page_main');
        $this->getters->setMetaInfo(title: $data['title'], url: route('client.auth.pay'));

        $data['payments_count'] = 0;

        $data['aaio']['status'] = $this->aaio['status'] ?? null;
        $data['aaio']['min_amount'] = $this->aaio['min_amount'] ?? 200;
        $data['aaio']['min_amount_text'] = __('transaction.aaio_min_amount', ['sum' => $this->getters->currencyFormat($data['aaio']['min_amount'])]);
        $data['aaio']['merchant_id'] = $this->aaio['merchant_id'] ?? null;
        $data['aaio']['currency'] = $this->getters->getSetting('currency_symbol_code') ?? 'RUB';

        if ($data['aaio']['status']) $data['payments_count']++;

        $data['ruKassa']['status'] = $this->ruKassa['status'] ?? null;
        $data['ruKassa']['min_amount'] = $this->ruKassa['min_amount'] ?? 200;
        $data['ruKassa']['min_amount_text'] = __('transaction.ruKassa_min_amount', ['sum' => $this->getters->currencyFormat($data['ruKassa']['min_amount'])]);
        $data['ruKassa']['merchant_id'] = $this->ruKassa['merchant_id'] ?? null;
        $data['ruKassa']['currency'] = $this->getters->getSetting('currency_symbol_code') ?? 'RUB';

        if ($data['ruKassa']['status']) $data['payments_count']++;

        //Pin codes
        $data['pin_code']['status'] = PayCodes::count();

        if ($data['pin_code']['status']) $data['payments_count']++;

        if ($data['payments_count'] == 1 || $data['payments_count'] == 2) {
            $data['payments_class'] = 'col-md-6 col-xl-6';
        } elseif ($data['payments_count'] == 3) {
            $data['payments_class'] = 'col-md-4 col-xl-4';
        } else {
            $data['payments_class'] = 'col-md-6 col-xl-6';
        }

        $data['pin_code']['items'] = [];

        $pay_codes = PayCodes::where('status', 1)->get();

        foreach ($pay_codes as $pay_code) {
            $nominal = $pay_code['nominal'];
            $bonus = $pay_code['bonus'];
            $nominal_bonus = $pay_code['nominal'];
            if ($bonus) {
                $nominal_bonus = $pay_code['nominal'] + $pay_code['bonus'];
                $percent = (int)round(($bonus / $nominal) * 100);
            } else {
                $percent = 0;
            }
            $data['pin_code']['items'][] = [
                'nominal' => $this->getters->currencyFormat($pay_code['nominal']),
                'credited' => $this->getters->currencyFormat($nominal_bonus),
                'discount' => $percent,
                'pay_link' => $pay_code['pay_link']
            ];
        }

        $breadcrumbs = [
            ['link' => route('client.auth.pay'), 'title' => __('catalog/id/payment.page_main')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/id/payment', ['data' => $data]);
    }

    public function pinCodePayment($pin_code = null): JsonResponse
    {
        if (empty($pin_code)) return response()->json(['response' => 'error', 'message' => __('catalog/id/payment.empty_pin_code')]);

        $check = PayCodesPinCode::where('pin_code', $pin_code)->first();
        if ($check) {
            $pincode_info = PayCodes::where('id', $check['pay_id'])->first();
            if ($pincode_info) {
                if ($pincode_info['bonus']) {
                    $nominal = $pincode_info['nominal'] + $pincode_info['bonus'];
                } else {
                    $nominal = $pincode_info['nominal'];
                }
                $user = Auth::user();
                $user->increment('balance', $nominal);
                PayCodesPinCode::where('pin_code', $pin_code)->delete();
                $this->transaction->setTransaction(type: 'pin_code_balance', short: __('transaction.pin_code_balance_success', ['sum' => $this->getters->currencyFormat($nominal), 'pin_code' => $pin_code]), price: $this->getters->currencyFormat($nominal));

                $sum = $this->getters->currencyFormat($pincode_info['nominal']);
                $bonus = $this->getters->currencyFormat($pincode_info['bonus']);

                if ($pincode_info['bonus']) {
                    return response()->json(['status' => 'success', 'message' => __('catalog/id/payment.success_pin_n_w_b', ['sum' => $sum, 'bonus' => $bonus])]);
                } else {
                    return response()->json(['status' => 'success', 'message' => __('catalog/id/payment.success_pin_n', ['sum' => $sum])]);
                }
            }
        }

        return response()->json(['response' => 'error', 'message' => __('catalog/id/payment.invalid_pin_code')]);
    }

    public function aaioPayment($amount): JsonResponse
    {
        if (!empty($this->aaio['min_amount']) && $amount < $this->aaio['min_amount']) {
            return response()->json(['status' => 'success', 'message' => __('transiction.aaio_min_amount', ['sum' => $this->aaio['min_amount']])]);
        }

        $lang = 'ru';
        $order_id = $this->generateUniqueOrderId();
        $this->transaction->setTransaction(type: 'aaio_balance', short: __('transaction.aaio_balance_create', ['sum' => $this->getters->currencyFormat($amount)]), price: $this->getters->currencyFormat($amount), order_id: $order_id, order_status_id: $this->aaio['order_wait_id']);

        $data['merchant_id'] = $merchant_id = $this->aaio['merchant_id'];
        $data['currency'] = $this->getters->getSetting('currency_symbol_code') ?? 'RUB';
        $data['sign'] = hash('sha256', implode(':', [$merchant_id, $amount, $data['currency'], html_entity_decode($this->aaio['secret_key_1']), $order_id]));

        return response()->json(['status' => 'success', 'message' => __('transaction.aaio_redirect'), 'order_id' => $order_id, 'lang' => $lang, 'sign' => $data['sign']]);
    }

    public function aaioStatus(Request $request)
    {
        Log::info($request);
        $method = $request->method();

        if ($method === 'POST') {
            die("wrong request method");
        }

        // Проверка на IP адрес сервиса (по желанию)
        $ctx = stream_context_create(['http'=> ['timeout' => 10]]);
        $ips = json_decode(file_get_contents('https://aaio.so/api/public/ips', false, $ctx));
        if (isset($ips->list) && !in_array($this->getIP(request()), $ips->list)) {
            die("hacking attempt");
        }
        // Конец проверки на IP адрес сервиса

        $sign = hash('sha256', implode(':', [$this->aaio['merchant_id'], $request->input('amount'), $request->input('currency'), html_entity_decode($this->aaio['secret_key_2']), $request->input('order_id')]));
        if (!hash_equals($request->input('sign'), $sign)) {
            die("wrong sign");
        }

        $order = Transaction::where('order_id', '=', $request->input('order_id'))->first();

        if(empty($order)) {
            die("order_id not found");
        }

        if($request->input('amount') < $order['amount']) {
            die("wrong amount");
        }

        if($order['order_status_id'] !== $this->aaio['order_success_id']) {
            Transaction::where('order_id', $request->input('order_id'))->update(['short' => __('transaction.aaio_balance_success', ['sum' => $this->getters->currencyFormat(($request->input('amount') ?? 0))]), 'order_status_id' => $this->aaio['order_success_id']]);
        } else {
            die("order has complete");
        }

        echo "OK";
    }

    public function aaioSuccess(Request $request): RedirectResponse
    {
        $order_id = (int)$request->input('order_id');
        $amount = (int)$request->input('amount');
        $exists_order = Transaction::where('order_id', $order_id)->first();
        if (!$order_id || !$amount) {
            return redirect()->route('client.errors', ['code' => 404]);
        }
        if (empty($exists_order)) {
            return redirect()->route('client.auth.pay')->with('warning', __('transaction.aaio_fail'));
        }
        $transaction = Transaction::where('order_id', $order_id)->where('order_status_id', '!=', $this->aaio['order_success_id'])->count();
        if ($transaction) {
            $user = Auth::user();
            $user->increment('balance', $amount);
            Transaction::where('order_id', $order_id)->update(['short' => __('transaction.aaio_balance_success', ['sum' => $this->getters->currencyFormat(($amount ?? 0))]), 'order_status_id' => $this->aaio['order_success_id']]);
            return redirect()->route('client.auth.pay')->with('success', __('transaction.aaio_success', ['sum' => $this->getters->currencyFormat($amount)]));
        } else {
            return redirect()->route('client.auth.pay')->with('warning', __('transaction.aaio_fail'));
        }
    }

    public function aaioFail(): RedirectResponse
    {
        return redirect()->route('client.auth.pay')->with('success', __('modal.aaio_fail'));
    }

    public function ruKassaPayment($amount): JsonResponse
    {
        if (!empty($this->ruKassa['min_amount']) && $amount < $this->ruKassa['min_amount']) {
            return response()->json(['status' => 'success', 'message' => __('transaction.ruKassa_min_amount', ['sum' => $this->ruKassa['min_amount']])]);
        }

        $order_id = $this->generateUniqueOrderId();

        $data['currency'] = $this->getters->getSetting('currency_symbol_code') ?? 'RUB';

        $data = [
            'shop_id'	=> $this->ruKassa['shop_id'],
            'token'		=> $this->ruKassa['token'],
            'order_id' 	=> $order_id,
            'amount' 	=> $amount,
            'user_code'	=> Auth::id() + rand(99,999),
            'list'		=> ['card', 'card_kzt', 'card_uzs', 'card_azn', 'card_kgs', 'skinpay', 'yandexmoney', 'payeer', 'crypta', 'sbp', 'clever', 'sber'],
            'currency'	=> $data['currency'],
            'json'		=> false,
        ];

        $response = Http::asForm()->post('https://lk.rukassa.io/api/v1/create', $data);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => __('transaction.ruKassa_api_error')], 500);
        }

        $result = $response->json();
        $pay_url = $result['url'] ?? null;
        $pay_id = $result['id'] ?? null;

        $this->transaction->setTransaction(type: 'ruKassa_balance', short: __('transaction.ruKassa_balance_create', ['sum' => $this->getters->currencyFormat($amount)]), price: $this->getters->currencyFormat($amount), order_id: $order_id, order_status_id: $this->ruKassa['order_wait_id'], pay_id: $pay_id);

        if (!$pay_url) {
            return response()->json(['status' => 'error', 'message' => __('transaction.ruKassa_no_url')]);
        }

        return response()->json(['status' => 'success', 'message' => __('transaction.ruKassa_redirect'), 'pay_url' => $pay_url]);
    }

    public function ruKassaSuccess(Request $request): RedirectResponse
    {
        $this->getters->checkRuKassatransactions($request);
        return redirect()->route('client.auth.account')->with('warning', __('transaction.ruKassa_success'));
    }

    public function ruKassaFail(Request $request): RedirectResponse
    {
        $this->getters->checkRuKassatransactions($request);
        return redirect()->route('client.auth.account')->with('success', __('modal.ruKassa_fail'));
    }

    function generateUniqueOrderId(): string
    {
        do {
            $order_id = $this->getters->randomString(only_num: true);
        } while (Transaction::where('order_id', $order_id)->exists());

        return $order_id;
    }

    public function getIP(Request $request): array|string|null
    {
        $ip = $request->ip();
        if ($request->server('HTTP_X_FORWARDED_FOR')) {
            $ip = $request->server('HTTP_X_FORWARDED_FOR');
        }
        if ($request->server('HTTP_X_REAL_IP')) {
            $ip = $request->server('HTTP_X_REAL_IP');
        }
        if ($request->server('HTTP_CF_CONNECTING_IP')) {
            $ip = $request->server('HTTP_CF_CONNECTING_IP');
        }
        $explode = explode(',', $ip);
        if (count($explode) > 1) {
            $ip = trim($explode[0]);
        }
        return $ip;
    }

    public function ruKassaChecker(Request $request) {
        $this->getters->checkRuKassatransactions($request);
    }
}
