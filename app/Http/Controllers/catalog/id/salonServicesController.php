<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\posts\Salon;
use App\Models\system\Getters;
use App\Models\system\Transaction;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class salonServicesController extends Controller
{
    private Getters $getters;
    private Transaction $transaction;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
        $this->transaction = new Transaction;
    }

    public function getServices(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $views = $this->getViewsServices($id);
        return response()->json(['status' => 'success', 'view' => $views]);
    }

    public function getViewsServices($salon_id): string
    {
        $salon = Salon::where('id', $salon_id)->first();

        $salon_positions = Salon::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->pluck('id');

        $position = $salon_positions->search($salon['id']);
        if ($position !== false) {
            $position++;
        } else {
            $position = null;
        }

        $data['salon_activation_status'] = $this->getters->getSetting('salon_activation_status');
        $salon_publish_variable = $this->getters->getSetting('salon_publish_variable');
        $data['activation_variable'] = [];

        if ($salon) {
            if (!$salon['publish']) {
                $data['btn_activation_prefix'] = null;
            } else {
                $data['btn_activation_prefix'] = '+';
            }
            foreach ($salon_publish_variable as $item) {
                $days_choice = trans_choice('choice.days', $item['days'], ['d' => $item['days']]);
                $price = $this->getters->currencyFormat($item['price']);
                $data['activation_variable'][] = [
                    'day' => $item['days'],
                    'price' => __('catalog/id/salon.btn_activation', ['price' => $price, 'days' => $days_choice]),
                ];
            }
            $data['salon'] = [
                'id' => $salon['id'],
                'position' => $position,
                'publish' => $salon['publish'],
                'publish_date' => __('catalog/id/salon.status_activation', ['date' => date('d.m.Y', strtotime($salon['publish_date']))]),
            ];

            $salon_prices = $this->getters->getSetting('salon_prices');

            $price_up_to_top = $salon_prices['up_to_top'];
            $price_up_to_top = $this->getters->currencyFormat($price_up_to_top);
            $data['up_to_top_btn'] = ($position >= 2) ? __('catalog/id/post.btn_up_to_top', ['price' => $price_up_to_top]) : null;
        }

        return view('catalog/id/salon/modalService', ['data' => $data])->render();
    }

    public function daysActivation(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $days = $request->input('days');
        $salon_id = $request->input('salon_id');
        $salon_activation_status = $this->getters->getSetting('salon_activation_status');
        $salon_publish_variable = $this->getters->getSetting('salon_publish_variable');

        $curr_days = $curr_price = null;
        foreach ($salon_publish_variable as $var_days) {
            if ($var_days['days'] == $days) {
                $curr_days = $var_days['days'];
                $curr_price = $var_days['price'];
                break;
            }
        }

        if (!$salon_activation_status) {
            return $this->errorResponse(__('catalog/id/salon.service_act_off'));
        }

        if ($curr_price == null || $curr_days == null) {
            return $this->errorResponse(__('catalog/id/salon.service_act_search_null'));
        }

        if ($curr_price > $balance) {
            return $this->errorResponse(__('catalog/id/salon.service_act_balance'));
        }

        //Действия с анкетой
        $salon_info = Salon::where('id', $salon_id)->where('moderation_id', 1)->first();

        $current_time = Carbon::now();
        $publish_date = ($salon_info['publish_date']) ? Carbon::parse($salon_info['publish_date']) : Carbon::now()->subDays();

        Log::info($publish_date);

        //Активация
        if ($publish_date->isBefore($current_time)) {
            $this->transaction->setTransaction('service_salon_activation_act', __('transaction.service_salon_activation_act', ['id' => $salon_id, 'title' => $salon_info->title, 'days' => $curr_days]), null, $this->getters->currencyFormat($curr_price), $salon_id);
            Salon::where('id', $salon_id)->update(['publish' => 1, 'publish_date' => $current_time->addDays($curr_days)]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $curr_price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/salon.service_act_success', ['day' => trans_choice('choice.days', $curr_days, ['d' => $curr_days])])]);
        } elseif ($publish_date->isAfter($current_time)) {
            $this->transaction->setTransaction('service_salon_activation_ext', __('transaction.service_salon_activation_ext', ['id' => $salon_id, 'title' => $salon_info->title, 'days' => $curr_days]), null, $this->getters->currencyFormat($curr_price), $salon_id);
            Salon::where('id', $salon_id)->update(['publish' => 1, 'publish_date' => $publish_date->addDays($curr_days)]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $curr_price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/salon.service_ext_success', ['day' => trans_choice('choice.days', $curr_days, ['d' => $curr_days])])]);
        }

        return response()->json(['status' => 'error', 'message' => 'Что-то пошло не так.']);
    }

    public function upToTop(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $salon_id = $request->input('salon_id');
        $salon_user_id = Salon::where('id', $salon_id)->value('user_id');
        $top_salon_id = Salon::where('publish', 1)->orderByDesc('up_date')->value('id');
        $salon_prices = $this->getters->getSetting('salon_prices');

        $up_price = $salon_prices['up_to_top'] ?? null;

        if ($salon_id == $top_salon_id) {
            return $this->errorResponse(__('catalog/id/salon.service_up_exists_top'));
        }

        if ($salon_user_id != Auth::id()) {
            return $this->errorResponse(__('catalog/id/salon.service_up_is_no_mine'));
        }

        if (!$up_price) {
            return $this->errorResponse(__('catalog/id/salon.service_up_no_price'));
        }

        if ($up_price > $balance) {
            return $this->errorResponse(__('catalog/id/salon.service_up_balance'));
        }

        //Действия с анкетой
        $salon_info = Salon::where('id', $salon_id)->where('moderation_id', 1)->first();

        //Поднятие
        $this->transaction->setTransaction('service_salon_up_to_top', __('transaction.service_salon_up_to_top', ['id' => $salon_id, 'title' => $salon_info->title]), null, $this->getters->currencyFormat($up_price), $salon_id);
        Salon::where('id', $salon_id)->update(['up_date' => Carbon::now()]);
        Users::where('id', Auth::id())->update(['balance' => ($balance - $up_price)]);
        return response()->json(['status' => 'success', 'message' => __('catalog/id/salon.service_up_success')]);
    }

    private function errorResponse(string $message): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message]);
    }
}
