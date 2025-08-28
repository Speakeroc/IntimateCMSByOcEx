<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\posts\BlackList;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class blackListController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
    }

    public function index()
    {
        $data['title'] = __('catalog/id/blacklist.page_main');
        $this->getters->setMetaInfo(title: $data['title'], url: route('client.auth.blackList'));
        $user_id = Auth::id();
        $black_list = BlackList::where('user_id', $user_id)->orderByDesc('id')->get();

        $data['black_list'] = [];

        foreach ($black_list as $item) {
            if ($item['rating'] >= 1 && $item['rating'] <= 2) {
                $rating_class = 'ex_blacllist_danger';
            } else if ($item['rating'] >= 3 && $item['rating'] <= 4) {
                $rating_class = 'ex_blacllist_warning';
            } else if ($item['rating'] >= 5) {
                $rating_class = 'ex_blacllist_success';
            } else {
                $rating_class = '';
            }

            $data['black_list'][] = [
                'id' => $item['id'],
                'phone' => $item['phone'],
                'rating' => $item['rating'],
                'rating_class' => $rating_class,
                'text' => $item['text'],
                'views' => __('catalog/id/blacklist.looking').trans_choice('choice.count', $item['views'], ['num' => $item['views']]),
            ];
        }

        $breadcrumbs = [
            ['link' => route('client.auth.blackList'), 'title' => __('catalog/id/blacklist.page_main')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/id/blackList', ['data' => $data]);
    }

    public function getPhoneData(Request $request): JsonResponse
    {
        $phone = $request->input('search_phone');

        if (empty($phone)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_empty'), 'result' => []]);
        }

        $items_count = BlackList::where('phone', 'like', '%' . $phone . '%')->count();
        $items = BlackList::where('phone', 'like', '%' . $phone . '%')->get();

        $items_data = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $viewedIds = session()->get('viewed_blacklist_ids', []);
                if (!in_array($item['id'], $viewedIds)) {
                    BlackList::where('id', $item['id'])->where('user_id', '!=', Auth::id())->increment('views');
                    $viewedIds[] = $item['id'];
                    session()->put('viewed_blacklist_ids', $viewedIds);
                }

                $views = BlackList::where('id', $item['id'])->value('views');

                if ($item['rating'] >= 1 && $item['rating'] <= 2) {
                    $rating_class = 'ex_blacllist_danger';
                } else if ($item['rating'] >= 3 && $item['rating'] <= 4) {
                    $rating_class = 'ex_blacllist_warning';
                } else if ($item['rating'] >= 5) {
                    $rating_class = 'ex_blacllist_success';
                } else {
                    $rating_class = '';
                }

                $items_data[] = [
                    'phone' => $item['phone'],
                    'rating' => $item['rating'],
                    'rating_class' => $rating_class,
                    'text' => $item['text'],
                    'views' => __('catalog/id/blacklist.looking').trans_choice('choice.count', $views, ['num' => $views]),
                ];
            }
            return response()->json(['status' => 'success', 'message' => __('catalog/id/blacklist.notify_result', ['num' => trans_choice('choice.coincidence', $items_count, ['num' => $items_count])]), 'result' => $items_data]);
        }

        return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_not_found'), 'result' => []]);
    }

    public function setPhoneData(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $rating = $request->input('rating');
        $text = $request->input('text');

        if (empty($phone)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_empty_phone')]);
        }

        if (empty($rating)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_empty_rating')]);
        }

        if (empty($text)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_empty_text')]);
        }

        $exists_phone = BlackList::where('phone', 'like', '%' . $phone . '%')->where('user_id', Auth::id())->count();
        if ($exists_phone) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_exists')]);
        } else {
            BlackList::create(['phone' => $phone, 'text' => $text, 'rating' => $rating, 'user_id' => Auth::user()->id]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/blacklist.notify_success')]);
        }
    }

    public function delPhoneData(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (empty($id)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_delete_empty')]);
        } else {
            $is_my = BlackList::where('id', $id)->where('user_id', Auth::id())->count();
            if (!$is_my) {
                return response()->json(['status' => 'error', 'message' => __('catalog/id/blacklist.notify_delete_other')]);
            }
        }

        BlackList::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'success', 'message' => __('catalog/id/blacklist.notify_delete_success')]);
    }
}
