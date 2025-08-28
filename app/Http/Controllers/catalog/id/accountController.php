<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\Transaction;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class accountController extends Controller
{
    private Users $user;
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
    }

    public function index()
    {
        $data['title'] = __('catalog/id/account.page_main');
        $this->getters->setMetaInfo(title: $data['title'], url: route('client.auth.account'));

        $data['user'] = $this->user->userData(true, 425);
        $data['user_setting'] = $this->getters->getSetting('user_setting');

        $order_status_ids = app('order_status_ids');
        $data['transactions'] = Transaction::where('user_id', Auth::id())->orderByDesc('id')->limit(10)->get();
        $data['transaction'] = [];
        foreach ($data['transactions'] as $item) {
            if (!empty($item['order_status_id'])) {
                $status = array_filter($order_status_ids, function ($status) use ($item) {
                    return $status['id'] === $item['order_status_id'];
                });
                $order_status = !empty($status) ? '<span style="color:white;background:'.array_values($status)[0]['client_color'].';border-radius:5px;padding:5px 10px;">'.array_values($status)[0]['short_title'].'</span>' : null;
                $order_status_color = !empty($status) ? array_values($status)[0]['short_title'] : null;
            } else {
                $order_status = null;
                $order_status_color = null;
            }
            $data['transaction'][] = [
                'id' => $item['id'],
                'type' => __('admin/pay/transaction.type_'.$item['type']),
                'price' => $item['price'],
                'short' => $item['short'],
                'order_status' => $order_status,
                'order_status_color' => $order_status_color,
                'date' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }

        $breadcrumbs = [
            ['link' => route('client.auth.account'), 'title' => __('catalog/id/account.page_main')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/id/account', ['data' => $data]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'curr_pass' => 'required',
            'new_pass' => 'required|min:6|confirmed',
        ], [
            'new_pass.confirmed' => __('catalog/id/account.notify_confirm_pass_invalid'),
        ]);

        $user = Auth::user();

        if (!Hash::check($request->curr_pass, $user->password)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/account.notify_current_pass_invalid')]);
        }

        $user->password = Hash::make($request->new_pass);
        $user->save();

        return response()->json(['status' => 'success', 'message' => __('catalog/id/account.notify_pass_success')]);
    }

    public function changeUser(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $old_username = $request->input('old_name');
        $login = $request->input('login');
        $old_login = $request->input('old_login');
        $allow_post_help = $request->input('allow_post_help') ?? 2;

        $changes = 0;

        // Инициализация массива с правилами валидации
        $validationRules = [];

        if ($username !== $old_username) {
            $validationRules['username'] = 'required|min:2|max:20';
        }

        if ($login !== $old_login) {
            $validationRules['login'] = 'required|min:4|max:20|unique:ex_users|regex:/^[a-zA-Z0-9]+$/';
        }

        if (!empty($validationRules)) {
            $this->validate($request, $validationRules);
        }

        if ($username !== $old_username) {
            $user = Auth::user();
            $user->name = $username;
            $user->save();
            $changes++;
        }

        $cache_files = public_path('images/cache/users');
        if (File::exists($cache_files) && File::isDirectory($cache_files)) {
            File::cleanDirectory($cache_files);
        }

        if ($login !== $old_login) {
            $user = Auth::user();
            $user->login = $login;
            $user->save();
            $changes++;
        }

        if ($allow_post_help) {
            $user = Auth::user();
            $user->allow_post_help = $allow_post_help;
            $user->save();
            $changes++;
        }

        if ($changes) {
            return response()->json(['status' => 'success', 'message' => __('catalog/id/account.notify_user_success')]);
        } else {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/account.notify_user_error')]);
        }
    }
}
