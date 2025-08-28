<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\system\Getters;
use App\Models\system\Transaction;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class servicesController extends Controller
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

    public function getViewsServices($post_id): string
    {
        $post = Post::where('id', $post_id)->first();

        $post_positions = Post::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->pluck('id');

        $position = $post_positions->search($post['id']);
        if ($position !== false) {
            $position++;
        } else {
            $position = null;
        }

        $data['post_activation_status'] = $this->getters->getSetting('post_activation_status');
        $post_publish_variable = $this->getters->getSetting('post_publish_variable');
        $data['activation_variable'] = [];

        if ($post) {
            if (!$post['publish']) {
                $data['btn_activation_prefix'] = null;
            } else {
                $data['btn_activation_prefix'] = '+';
            }
            foreach ($post_publish_variable as $item) {
                $days_choice = trans_choice('choice.days', $item['days'], ['d' => $item['days']]);
                $price = $this->getters->currencyFormat($item['price']);
                $data['activation_variable'][] = [
                    'day' => $item['days'],
                    'price' => __('catalog/id/post.btn_activation', ['price' => $price, 'days' => $days_choice]),
                ];
            }
            $data['post'] = [
                'id' => $post['id'],
                'diamond' => $post['diamond'],
                'diamond_date' => __('catalog/id/post.status_to', ['date' => date('d.m.y', strtotime($post['diamond_date']))]),
                'vip' => $post['vip'],
                'vip_date' => __('catalog/id/post.status_to', ['date' => date('d.m.y', strtotime($post['vip_date']))]),
                'color' => $post['color'],
                'color_date' => __('catalog/id/post.status_to', ['date' => date('d.m.y', strtotime($post['color_date']))]),
                'position' => $position,
                'publish' => $post['publish'],
                'publish_date' => __('catalog/id/post.status_activation', ['date' => date('d.m.Y', strtotime($post['publish_date']))]),
            ];

            $post_prices = $this->getters->getSetting('post_prices');

            $price_up_to_top = $post_prices['up_to_top'];
            $price_up_to_top = $this->getters->currencyFormat($price_up_to_top);
            $data['up_to_top_btn'] = ($position >= 2) ? __('catalog/id/post.btn_up_to_top', ['price' => $price_up_to_top]) : null;

            $price_diamond = (!$post['diamond']) ? $post_prices['diamond_act'] : $post_prices['diamond_ext'];
            $price_diamond = $this->getters->currencyFormat($price_diamond);
            $data['diamond_btn'] = (!$post['diamond']) ? __('catalog/id/post.btn_act_diamond', ['price' => $price_diamond]) : __('catalog/id/post.btn_ext_diamond', ['price' => $price_diamond]);

            $price_vip = (!$post['vip']) ? $post_prices['vip_act'] : $post_prices['vip_ext'];
            $price_vip = $this->getters->currencyFormat($price_vip);
            $data['vip_btn'] = (!$post['vip']) ? __('catalog/id/post.btn_act_vip', ['price' => $price_vip]) : __('catalog/id/post.btn_ext_vip', ['price' => $price_vip]);

            $price_color = (!$post['color']) ? $post_prices['color_act'] : $post_prices['color_ext'];
            $price_color = $this->getters->currencyFormat($price_color);
            $data['color_btn'] = (!$post['color']) ? __('catalog/id/post.btn_act_color', ['price' => $price_color]) : __('catalog/id/post.btn_ext_color', ['price' => $price_color]);
        }

        return view('catalog/id/post/modalService', ['data' => $data])->render();
    }

    public function daysActivation(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $days = $request->input('days');
        $post_id = $request->input('post_id');
        $post_activation_status = $this->getters->getSetting('post_activation_status');
        $post_publish_variable = $this->getters->getSetting('post_publish_variable');

        $curr_days = $curr_price = null;
        foreach ($post_publish_variable as $var_days) {
            if ($var_days['days'] == $days) {
                $curr_days = $var_days['days'];
                $curr_price = $var_days['price'];
                break;
            }
        }

        if (!$post_activation_status) {
            return $this->errorResponse(__('catalog/id/post.service_act_off'));
        }

        if ($curr_price == null || $curr_days == null) {
            return $this->errorResponse(__('catalog/id/post.service_act_search_null'));
        }

        if ($curr_price > $balance) {
            return $this->errorResponse(__('catalog/id/post.service_act_balance'));
        }

        //Действия с анкетой
        $post_info = Post::where('id', $post_id)->where('moderation_id', 1)->first();

        $current_time = Carbon::now();
        $publish_date = ($post_info['publish_date']) ? Carbon::parse($post_info['publish_date']) : Carbon::now()->subDays();

        Log::info($publish_date);

        //Активация
        if ($publish_date->isBefore($current_time)) {
            $this->transaction->setTransaction('service_activation_act', __('transaction.service_activation_act', ['id' => $post_id, 'name' => $post_info->name, 'days' => $curr_days]), null, $this->getters->currencyFormat($curr_price), $post_id);
            Post::where('id', $post_id)->update(['publish' => 1, 'publish_date' => $current_time->addDays($curr_days)]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $curr_price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_act_success', ['day' => trans_choice('choice.days', $curr_days, ['d' => $curr_days])])]);
        } elseif ($publish_date->isAfter($current_time)) {
            $this->transaction->setTransaction('service_activation_ext', __('transaction.service_activation_ext', ['id' => $post_id, 'name' => $post_info->name, 'days' => $curr_days]), null, $this->getters->currencyFormat($curr_price), $post_id);
            Post::where('id', $post_id)->update(['publish' => 1, 'publish_date' => $publish_date->addDays($curr_days)]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $curr_price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_ext_success', ['day' => trans_choice('choice.days', $curr_days, ['d' => $curr_days])])]);
        }

        return response()->json(['status' => 'error', 'message' => 'Что-то пошло не так.']);
    }

    public function diamondActivation(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $post_id = $request->input('post_id');
        $post_prices = $this->getters->getSetting('post_prices');
        $post_status = Post::where('id', $post_id)->value('diamond');
        $post_status = ($post_status) ? 'diamond_ext' : 'diamond_act';

        $price = $post_prices[$post_status];

        if (!$price) {
            return $this->errorResponse(__('catalog/id/post.service_diamond_no_price'));
        }

        if ($price > $balance) {
            return $this->errorResponse(__('catalog/id/post.service_diamond_balance'));
        }

        //Действия с анкетой
        $post_info = Post::where('id', $post_id)->where('moderation_id', 1)->first();

        $current_time = Carbon::now();
        $diamond_date = Carbon::parse($post_info['diamond_date']);

        //Активация
        if ($diamond_date->isBefore($current_time)) {
            $this->transaction->setTransaction('service_diamond_act', __('transaction.service_diamond_act', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['diamond' => 1, 'diamond_date' => $current_time->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_diamond_act_success')]);
        } elseif ($diamond_date->isAfter($current_time)) {
            $this->transaction->setTransaction('service_diamond_ext', __('transaction.service_diamond_ext', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['diamond' => 1, 'diamond_date' => $diamond_date->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_diamond_ext_success')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Что-то пошло не так.']);
    }

    public function vipActivation(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $post_id = $request->input('post_id');
        $post_prices = $this->getters->getSetting('post_prices');
        $post_status = Post::where('id', $post_id)->value('vip');
        $post_status = ($post_status) ? 'vip_ext' : 'vip_act';

        $price = $post_prices[$post_status];

        if (!$price) {
            return $this->errorResponse(__('catalog/id/post.service_vip_no_price'));
        }

        if ($price > $balance) {
            return $this->errorResponse(__('catalog/id/post.service_vip_balance'));
        }

        //Действия с анкетой
        $post_info = Post::where('id', $post_id)->where('moderation_id', 1)->first();

        $current_time = Carbon::now();
        $vip_date = Carbon::parse($post_info['vip_date']);

        //Активация
        if ($vip_date->isBefore($current_time)) {
            $this->transaction->setTransaction('service_vip_act', __('transaction.service_vip_act', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['vip' => 1, 'vip_date' => $current_time->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_vip_act_success')]);
        } elseif ($vip_date->isAfter($current_time)) {
            $this->transaction->setTransaction('service_vip_ext', __('transaction.service_vip_ext', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['vip' => 1, 'vip_date' => $vip_date->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_vip_ext_success')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Что-то пошло не так.']);
    }

    public function colorActivation(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $post_id = $request->input('post_id');
        $post_prices = $this->getters->getSetting('post_prices');
        $post_status = Post::where('id', $post_id)->value('color');
        $post_status = ($post_status) ? 'color_ext' : 'color_act';

        $price = $post_prices[$post_status];

        if (!$price) {
            return $this->errorResponse(__('catalog/id/post.service_color_no_price'));
        }

        if ($price > $balance) {
            return $this->errorResponse(__('catalog/id/post.service_color_balance'));
        }

        //Действия с анкетой
        $post_info = Post::where('id', $post_id)->where('moderation_id', 1)->first();

        $current_time = Carbon::now();
        $color_date = Carbon::parse($post_info['color_date']);

        //Активация
        if ($color_date->isBefore($current_time)) {
            $this->transaction->setTransaction('service_color_act', __('transaction.service_color_act', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['color' => 1, 'color_date' => $current_time->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_color_act_success')]);
        } elseif ($color_date->isAfter($current_time)) {
            $this->transaction->setTransaction('service_color_ext', __('transaction.service_color_ext', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($price), $post_id);
            Post::where('id', $post_id)->update(['color' => 1, 'color_date' => $color_date->addDays()]);
            Users::where('id', Auth::id())->update(['balance' => ($balance - $price)]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_color_ext_success')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Что-то пошло не так.']);
    }

    public function upToTop(Request $request): JsonResponse
    {
        $balance = Auth::user()->balance;
        $post_id = $request->input('post_id');
        $top_post_id = Post::where('publish', 1)->orderByDesc('up_date')->value('id');
        $post_prices = $this->getters->getSetting('post_prices');

        $up_price = $post_prices['up_to_top'] ?? null;

        if ($post_id == $top_post_id) {
            return $this->errorResponse(__('catalog/id/post.service_up_exists_top'));
        }

        if (!$up_price) {
            return $this->errorResponse(__('catalog/id/post.service_up_no_price'));
        }

        if ($up_price > $balance) {
            return $this->errorResponse(__('catalog/id/post.service_up_balance'));
        }

        //Действия с анкетой
        $post_info = Post::where('id', $post_id)->where('moderation_id', 1)->first();

        //Поднятие
        $this->transaction->setTransaction('service_up_to_top', __('transaction.service_up_to_top', ['id' => $post_id, 'name' => $post_info->name]), null, $this->getters->currencyFormat($up_price), $post_id);
        Post::where('id', $post_id)->update(['up_date' => Carbon::now()]);
        Users::where('id', Auth::id())->update(['balance' => ($balance - $up_price)]);
        return response()->json(['status' => 'success', 'message' => __('catalog/id/post.service_up_success')]);
    }

    private function errorResponse(string $message): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message]);
    }
}
