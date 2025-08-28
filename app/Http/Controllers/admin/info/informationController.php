<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\Info\Information;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class informationController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->paginate = 20;
    }

    public function index(Request $request): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/information_information')) return $redirect;
        $this->getters->setSEOTitle('information');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $data['filtered'] = 0;
        $data['title'] = $request->input('title') ?? null;
        $data['status'] = $request->input('status') ?? null;
        $data['in_menu'] = $request->input('in_menu') ?? null;
        $query = Information::query();
        if ($data['title'] != null) {
            $query->where('title', 'like', '%' . $data['title'] . '%');
            $data['filtered']++;
        }
        if ($data['status'] == 'yes') {
            $query->where('status', 1);
            $data['filtered']++;
        }
        if ($data['status'] == 'no') {
            $query->where('status', 0);
            $data['filtered']++;
        }
        if ($data['in_menu'] == 'yes') {
            $query->where('in_menu', 1);
            $data['filtered']++;
        }
        if ($data['in_menu'] == 'no') {
            $query->where('in_menu', 0);
            $data['filtered']++;
        }
        $query->orderByDesc('created_at');

        $data['data'] = $query->paginate($paginate)->appends([
            'title' => $data['title'],
            'status' => $data['status'],
            'in_menu' => $data['in_menu'],
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'views' => $item['views'],
                'in_menu' => $item['in_menu'],
                'status' => $item['status'],
                'date_added' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/info/information/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_add')) return $redirect;
        $this->getters->setSEOTitle('information_add');

        $data = [
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];
        return view('admin/info/information/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_add')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'meta_title' => 'nullable|min:1|max:100',
            'desc' => 'required|min:1|max:10000000',
            'views' => 'nullable|integer',
            'status' => 'required|integer',
            'created_at' => 'nullable',
        ]);

        $data['title'] = $request->input('title');
        $data['desc'] = $request->input('desc');
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = Str::limit($request->input('meta_description'), 150);
        $data['seo_url'] = $request->input('seo_url') ?? Str::slug($data['title']);
        $data['views'] = $request->input('views');
        $data['status'] = $request->input('status');
        $data['in_menu'] = $request->input('in_menu');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();

        Information::create([
            'user_id' => Auth::id(),
            'title' => strip_tags($data['title']),
            'desc' => $this->getters->convertTextData($data['desc']),
            'meta_title' => strip_tags($data['meta_title']),
            'meta_description' => strip_tags($data['meta_description']),
            'seo_url' => $data['seo_url'],
            'views' => $data['views'],
            'status' => $data['status'],
            'in_menu' => $data['in_menu'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('information.index')->with('success', __('admin/info/information.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_edit')) return $redirect;
        $this->getters->setSEOTitle('information_edit');

        //Info
        $item = Information::where('id', $id)->first();

        $data = [
            'id' => $id,
            'title' => $item['title'],
            'desc' => $this->getters->reverseTextData($item['desc']),
            'meta_title' => $item['meta_title'],
            'meta_description' => $item['meta_description'],
            'seo_url' => $item['seo_url'],
            'views' => $item['views'],
            'status' => $item['status'],
            'in_menu' => $item['in_menu'],
            'created_at' => date('Y-m-d', strtotime($item['created_at'])),

            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/info/information/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_edit')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'meta_title' => 'nullable|min:1|max:100',
            'desc' => 'required|min:1|max:10000000',
            'views' => 'nullable|integer',
            'status' => 'required|integer',
            'created_at' => 'nullable',
        ]);

        $data['title'] = $request->input('title');
        $data['desc'] = $request->input('desc');
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = Str::limit($request->input('meta_description'), 150);
        $data['seo_url'] = $request->input('seo_url') ?? Str::slug($data['title']);
        $data['views'] = $request->input('views');
        $data['status'] = $request->input('status');
        $data['in_menu'] = $request->input('in_menu');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();

        Information::where('id', '=', $id)->update([
            'title' => strip_tags($data['title']),
            'desc' => $this->getters->convertTextData($data['desc']),
            'meta_title' => strip_tags($data['meta_title']),
            'meta_description' => strip_tags($data['meta_description']),
            'seo_url' => $data['seo_url'],
            'views' => $data['views'],
            'status' => $data['status'],
            'in_menu' => $data['in_menu'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('information.index')->with('success', __('admin/info/information.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_delete')) return $redirect;

        $reg_privacy = $this->getters->getSetting('reg_privacy');
        if ($reg_privacy == $id) {
            return redirect()->route('information.index')->with('danger', __('admin/info/information.notify_is_used'));
        } else {
            Information::where('id', $id)->delete();
            return redirect()->route('information.index')->with('success', __('admin/info/information.notify_deleted'));
        }
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/information_information_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $reg_privacy = $this->getters->getSetting('reg_privacy');
                if ($reg_privacy == $selectedId) {
                    return response()->json(['status' => 'error', 'message' => __('admin/info/information.notify_is_used')]);
                } else {
                    Information::where('id', $selectedId)->delete();
                    return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
                }
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
