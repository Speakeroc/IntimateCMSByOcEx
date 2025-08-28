<?php

namespace App\Http\Controllers\admin\location;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class metroController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct() {
        $this->getters = new Getters;
        $this->paginate = 20;
    }

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/location_metro')) return $redirect;
        $this->getters->setSEOTitle('metro');
        $paginate = $this->paginate;

        $data['filtered'] = 0;
        $data['city_id'] = $request->input('city_id') ?? null;
        $query = Metro::query();
        if ($data['city_id'] != null) {
            $query->where('city_id', '=', $data['city_id']);
            $data['filtered']++;
        }
        $query->orderBy('id');

        $data['data'] = $query->paginate($paginate)->appends([
            'city_id' => $data['city_id'],
        ]);

        $data['items'] = [];
        foreach ($data['data'] as $item) {
            $data['items'][] = ['id' => $item['id'], 'title' => $item['title'], 'city' => City::where('id', $item['city_id'])->value('title'), 'status' => $item['status'],];
        }
        $data['city'] = City::all();
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/metro/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_add')) return $redirect;
        $this->getters->setSEOTitle('metro_add');
        $data['city'] = City::all();
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/metro/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_add')) return $redirect;
        $this->validate($request, ['title' => ['required', 'min:3', 'unique:ex_location_metro'], 'status' => 'required']);
        Metro::create(['title' => $request->input('title'), 'city_id' => $request->input('city_id'), 'status' => $request->input('status')]);
        return redirect()->route('metro.index')->with('success', __('admin/location/metro.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_edit')) return $redirect;
        $this->getters->setSEOTitle('metro_edit');
        $item = Metro::where('id', $id)->first();
        $data['id'] = $id;
        $data['title'] = $item['title'];
        $data['city_id'] = $item['city_id'];
        $data['status'] = $item['status'];
        $data['city'] = City::all();
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/metro/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_edit')) return $redirect;
        $this->validate($request, ['title' => 'required|min:3', 'status' => 'required']);
        Metro::where('id', '=', $id)->update(['title' => $request->input('title'), 'city_id' => $request->input('city_id'), 'status' => $request->input('status')]);
        return redirect()->route('metro.index')->with('success', __('admin/location/metro.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_delete')) return $redirect;
        Metro::where('id', $id)->delete();
        return redirect()->route('metro.index')->with('success', __('admin/location/metro.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/location_metro_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            Metro::whereIn('id', $selectedIds)->delete();
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
