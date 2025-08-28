<?php

namespace App\Http\Controllers\admin\pay;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\system\Transaction;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class transactionController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/payment_transaction')) return $redirect;
        $this->getters->setSEOTitle('transactions');
        $paginate = $this->paginate;

        $order_status_ids = app('order_status_ids');

        $data['data'] = Transaction::orderByDesc('id')->paginate($paginate);
        $data['items'] = [];
        foreach ($data['data'] as $item) {
            if (!empty($item['order_status_id'])) {
                $status = array_filter($order_status_ids, function ($status) use ($item) {
                    return $status['id'] === $item['order_status_id'];
                });
                $order_status = !empty($status) ? '<span style="color:black;background:'.array_values($status)[0]['color'].';border-radius:5px;padding:5px 10px;">'.array_values($status)[0]['short_title'].'</span>' : null;
                $order_status_color = !empty($status) ? array_values($status)[0]['short_title'] : null;
            } else {
                $order_status = null;
                $order_status_color = null;
            }
            $data['items'][] = [
                'id' => $item['id'],
                'type' => __('admin/pay/transaction.type_'.$item['type']),
                'price' => $item['price'],
                'short' => $item['short'],
                'user' => ['user' => Users::where('id', $item['user_id'])->value('name') ?? 'Пользователь удален', 'user_link' => route('users.index') . '?user_id=' . $item['user_id']],
                'order_status' => $order_status,
                'order_status_color' => $order_status_color,
                'date' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/pay/transaction', ['data' => $data]);
    }
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
