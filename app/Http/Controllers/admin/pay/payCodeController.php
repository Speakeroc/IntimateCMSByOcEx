<?php

namespace App\Http\Controllers\admin\pay;

use App\Http\Controllers\Controller;
use App\Models\pay\PayCodes;
use App\Models\pay\PayCodesPinCode;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class payCodeController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/payment_code')) return $redirect;
        $this->getters->setSEOTitle('payment_code');
        $paginate = $this->paginate;

        $query = PayCodes::query();
        $query->orderBy('id');

        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        $data['currency_symbol'] = $this->getters->getCurrencySymbol();

        foreach ($data['data'] as $item) {
            $pin_codes = PayCodesPinCode::where('pay_id', $item['id'])->count();
            $data['items'][] = [
                'id' => $item['id'],
                'nominal' => $item['nominal'].$data['currency_symbol'],
                'bonus' => ($item['bonus']) ? $item['bonus'].$data['currency_symbol'] : '-',
                'percent' => round(($item['bonus'] / $item['nominal']) * 100),
                'status' => $item['status'],
                'pin_codes' => trans_choice(__('admin/pay/code.pin_code_choice'), $pin_codes, ['num' => $pin_codes]),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/pay/code/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_code_add')) return $redirect;
        $this->getters->setSEOTitle('payment_code');

        $data = [
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/pay/code/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_code_add')) return $redirect;
        $this->validate($request, [
            'nominal' => 'required|integer|min:1',
            'bonus' => 'nullable|integer',
            'pay_link' => 'required',
            'status' => 'required',
            'pin_codes' => 'required'
        ]);

        $nominal = $request->input('nominal');
        $bonus = $request->input('bonus');
        $status = $request->input('status');
        $pay_link = $request->input('pay_link');
        $pin_codes = $request->input('pin_codes');

        foreach (explode("\n", $pin_codes) as $item) {
            $check = PayCodesPinCode::where('pin_code', trim($item))->first();
            if (!empty($check)) {
                $nominal = PayCodes::where('id', $check['pay_id'])->value('nominal');
                $pin_code = trim($item);
                $request->validate([
                    'pin_codes' => [
                        function ($attribute, $value, $fail) use ($pin_code, $nominal) {
                            $fail(__('admin/pay/code.pin_code_exists', ['pin_code' => $pin_code, 'nominal' => $nominal]));
                        }
                    ],
                ]);
            }
        }

        $pay = PayCodes::create([
            'pay_link' => $pay_link,
            'nominal' => $nominal,
            'bonus' => $bonus ?? 0,
            'status' => $status,
        ]);

        $pay_id = $pay->id;

        foreach (explode("\n", $pin_codes) as $item) {
            PayCodesPinCode::create(['pay_id' => $pay_id, 'pin_code' => trim($item)]);
        }

        return redirect()->route('payment_code.index')->with('success', __('admin/pay/code.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_code_edit')) return $redirect;
        $this->getters->setSEOTitle('post_edit');

        $pay = PayCodes::where('id', $id)->first();
        $pin_codes = PayCodesPinCode::where('pay_id', $id)->get();


        $data = [
            'id' => $id,
            'nominal' => $pay['nominal'],
            'bonus' => $pay['bonus'],
            'pay_link' => $pay['pay_link'],
            'status' => $pay['status'],
            'pin_codes' => "",
            'currency_symbol' => $this->getters->getCurrencySymbol(),
        ];

        foreach ($pin_codes as $pin_code) {
            $data['pin_codes'] .= $pin_code['pin_code'] . "\n";
        }


        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/pay/code/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_code_edit')) return $redirect;
        $this->validate($request, [
            'nominal' => 'required|integer|min:1',
            'bonus' => 'nullable|integer',
            'pay_link' => 'required',
            'status' => 'required',
            'pin_codes' => 'required'
        ]);

        $nominal = $request->input('nominal');
        $bonus = $request->input('bonus');
        $status = $request->input('status');
        $pay_link = $request->input('pay_link');
        $pin_codes = $request->input('pin_codes');

        foreach (explode("\n", $pin_codes) as $item) {
            $check = PayCodesPinCode::where('pay_id', '!=', $id)->where('pin_code', trim($item))->first();
            if (!empty($check)) {
                $nominal = PayCodes::where('id', $check['pay_id'])->value('nominal');
                $pin_code = trim($item);
                $request->validate([
                    'pin_codes' => [
                        function ($attribute, $value, $fail) use ($pin_code, $nominal) {
                            $fail(__('admin/pay/code.pin_code_exists', ['pin_code' => $pin_code, 'nominal' => $nominal]));
                        }
                    ],
                ]);
            }
        }

        PayCodes::where('id', '=', $id)->update([
            'pay_link' => $pay_link,
            'nominal' => $nominal,
            'bonus' => $bonus ?? 0,
            'status' => $status,
        ]);

        PayCodesPinCode::where('pay_id', $id)->delete();
        foreach (explode("\n", $pin_codes) as $item) {
            PayCodesPinCode::create(['pay_id' => $id, 'pin_code' => trim($item)]);
        }

        return redirect()->route('payment_code.index')->with('success', __('admin/pay/code.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/payment_code_delete')) return $redirect;
        PayCodes::where('id', $id)->delete();
        PayCodesPinCode::where('pay_id', $id)->delete();

        return redirect()->route('payment_code.index')->with('success', __('admin/pay/code.notify_deleted'));
    }
}
