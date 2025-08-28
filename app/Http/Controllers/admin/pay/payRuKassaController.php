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

class payRuKassaController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/payment_ruKassa')) return $redirect;
        $this->getters->setSEOTitle('payment_ruKassa');

        $data['order_status_ids'] = app('order_status_ids');

        $data_settings = [
            'ruKassa' => 'array',
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

        $data['ruKassa_success_url'] = route('client.auth.pay.ruKassa.success');
        $data['ruKassa_fail_url'] = route('client.auth.pay.ruKassa.fail');
        $data['ruKassa_api_checker'] = route('service.ruKassa.checker', ['key' => 'B4jH2G3BYOEp5NfghXXFD']);
        $ruKassa_last_check = $this->getters->getSetting('ruKassa_last_check');
        if (isset($ruKassa_last_check) && $ruKassa_last_check) {
            $data['ruKassa_last_check'] = __('admin/pay/ruKassa.last_check', ['date' => date('d.m.Y H:i:s', $ruKassa_last_check)]);
        } else {
            $data['ruKassa_last_check'] = __('admin/pay/ruKassa.last_check_no');
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/pay/ruKassa/setting', ['data' => $data]);
    }

    public function saveSetting(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_ruKassa_save')) return $redirect;
        $ruKassa = $request->input('ruKassa');

        if ($ruKassa['status']) {
            $this->validate($request, [
                'ruKassa.status' => 'required',
                'ruKassa.shop_id' => 'required',
                'ruKassa.token' => 'required',
                'ruKassa.order_wait_id' => 'required',
                'ruKassa.order_success_id' => 'required',
                'ruKassa.order_fail_id' => 'required',
                'ruKassa.min_amount' => 'required',
            ]);
        }

        Settings::updateOrCreate(['code' => 'setting', 'key' => 'ruKassa'], ['value' => json_encode($request->input('ruKassa'))]);

        return redirect()->route('admin.pay.payment_ruKassa')->with('success', __('admin/pay/ruKassa.notify_saved'));
    }
}
