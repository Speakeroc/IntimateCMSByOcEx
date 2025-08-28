<?php

namespace App\Http\Controllers\admin\pay;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\Settings;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class payAaioController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/payment_aaio')) return $redirect;
        $this->getters->setSEOTitle('payment_aaio');

        $data['order_status_ids'] = app('order_status_ids');

        $data_settings = [
            'aaio' => 'array',
        ];

        foreach ($data_settings as $key => $value) {
            if ($value == 'array') {
                $value = Settings::where('code', 'setting')->where('key', $key)->value('value');
                if (is_string($value) && !empty($value)) {
                    $data[$key] = json_decode($value, true) ?? [];
                } else {
                    $data[$key] = [];
                }
            }
        }

        $data['aaio_status_url'] = route('client.auth.pay.aaio.status');
        $data['aaio_success_url'] = route('client.auth.pay.aaio.success');
        $data['aaio_fail_url'] = route('client.auth.pay.aaio.fail');

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/pay/aaio/setting', ['data' => $data]);
    }

    public function saveSetting(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_aaio_save')) return $redirect;
        $aaio = $request->input('aaio');
        $filePath = public_path('aaio_verification.html');

        if ($aaio['status']) {
            $this->validate($request, [
                'aaio.status' => 'required',
                'aaio.merchant_id' => 'required',
                'aaio.secret_key_1' => 'required',
                'aaio.secret_key_2' => 'required',
                'aaio.order_wait_id' => 'required',
                'aaio.order_success_id' => 'required',
                'aaio.order_fail_id' => 'required',
                'aaio.min_amount' => 'required',
            ]);
        } else {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        Settings::updateOrCreate(['code' => 'setting', 'key' => 'aaio'], ['value' => json_encode($request->input('aaio'))]);

        if ($aaio['status']) {
            $merchantId = $aaio['merchant_id'];
            if (File::exists($filePath)) {
                $currentContent = File::get($filePath);
                if ($currentContent !== $merchantId) {
                    File::put($filePath, $merchantId);
                }
            } else {
                File::put($filePath, $merchantId);
            }
        }

        return redirect()->route('admin.pay.payment_aaio')->with('success', __('admin/pay/aaio.notify_saved'));
    }
}
