<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\BlackList;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class blackListController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->paginate = 20;
    }

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/blacklist')) return $redirect;
        $this->getters->setSEOTitle('blacklist');
        $paginate = $this->paginate;

        $data['filtered'] = 0;
        $data['phone'] = $request->input('phone') ?? null;
        $data['rating'] = $request->input('rating') ?? null;
        $data['user_id'] = $request->input('user_id') ?? null;
        $query = BlackList::query();
        if ($data['phone'] != null) {
            $query->where('phone', 'like', '%' . $data['phone'] . '%');
            $data['filtered']++;
        }
        if ($data['rating'] != null) {
            $query->where('rating', '=', $data['rating']);
            $data['filtered']++;
        }
        if ($data['user_id'] != null) {
            $query->where('user_id', '=', $data['user_id']);
            $data['filtered']++;
        }
        $query->select('phone', DB::raw('MAX(id) as id'), DB::raw('MAX(created_at) as created_at'), DB::raw('MAX(views) as views'), DB::raw('MAX(text) as text'))
            ->groupBy('phone')
            ->orderByDesc('id');

        $data['data'] = $query->paginate($paginate)->appends([
            'phone' => $data['phone'],
            'rating' => $data['rating'],
            'user_id' => $data['user_id'],
        ]);

        $data['items'] = [];
        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'phone' => $item['phone'],
                'text' => $item['text'],
                'duplicate' => BlackList::where('phone', $item['phone'])->count(),
                'middle_rating' => round(BlackList::where('phone', $item['phone'])->avg('rating')),
                'date_added' => date('d.m.Y H:i', strtotime($item['created_at'])),
                'views' => trans_choice('admin/posts/blacklist.views_choice', $item['views'], ['num' => $item['views']])
            ];
        }

        //Users
        $users = BlackList::distinct()->orderBy('user_id', 'asc')->pluck('user_id');

        $data['users'] = [];

        foreach ($users as $user) {
            $reviews = BlackList::where('user_id', $user)->count();
            $name = Users::where('id', $user)->value('name');
            $reviewText = trans_choice('admin/posts/blacklist.reviews_choice', $reviews, ['num' => $reviews]);

            $data['users'][] = [
                'user_id' => $user,
                'name' => 'ID:' . $user . ' - ' . $name . ' - ' . $reviewText
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/posts/blacklist/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_add')) return $redirect;
        $this->getters->setSEOTitle('blacklist_add');
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/posts/blacklist/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_add')) return $redirect;
        $this->validate($request, [
            'phone' => 'required',
            'text' => 'required',
            'rating' => 'required'
        ]);
        BlackList::create([
            'phone' => $request->input('phone'),
            'text' => $request->input('text'),
            'rating' => $request->input('rating'),
            'user_id' => Auth::user()->id
        ]);
        return redirect()->route('blacklist.index')->with('success', __('admin/posts/blacklist.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_edit')) return $redirect;
        $this->getters->setSEOTitle('blacklist_edit');
        $item = BlackList::where('id', $id)->first();
        $data['id'] = $id;
        $data['phone'] = $item['phone'];
        $data['text'] = $item['text'];
        $data['rating'] = $item['rating'];
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/posts/blacklist/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_edit')) return $redirect;
        $this->validate($request, [
            'phone' => 'required',
            'text' => 'required',
            'rating' => 'required'
        ]);
        BlackList::where('id', '=', $id)->update([
            'phone' => $request->input('phone'),
            'text' => $request->input('text'),
            'rating' => $request->input('rating')
        ]);
        return redirect()->route('blacklist.index')->with('success', __('admin/posts/blacklist.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_delete')) return $redirect;
        BlackList::where('id', $id)->delete();
        return redirect()->route('blacklist.index')->with('success', __('admin/posts/blacklist.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/blacklist_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            BlackList::whereIn('id', $selectedIds)->delete();
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }

    public function getReviews(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
        $phone = $request->input('phone');
        $data = BlackList::where('phone', $phone)->get();
        $items = [];
        foreach ($data as $item) {
            $items[] = [
                'phone' => $item['phone'],
                'user' => Users::where('id', $item['user_id'])->value('name') . ' - ID:' . $item['user_id'],
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'text' => $item['text'],
                'rating' => $item['rating'],
                'link' => route('blacklist.edit', $item['id'])
            ];
        }
        return response()->json($items);
    }
}
