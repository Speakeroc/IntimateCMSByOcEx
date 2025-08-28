<?php

namespace App\Http\Controllers\admin\location;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Zone;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class zoneController extends Controller
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
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/location_zone')) return $redirect;
        $this->getters->setSEOTitle('zone');
        $paginate = $this->paginate;

        $data['filtered'] = 0;
        $data['city_id'] = $request->input('city_id') ?? null;
        $query = Zone::query();
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
        return view('admin/location/zone/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_add')) return $redirect;
        $this->getters->setSEOTitle('zone_add');
        $data['city'] = City::all();
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/zone/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_add')) return $redirect;
        $this->validate($request, ['title' => ['required', 'min:3', 'unique:ex_location_zone'], 'status' => 'required']);
        Zone::create(['title' => $request->input('title'), 'city_id' => $request->input('city_id'), 'status' => $request->input('status')]);
        return redirect()->route('zone.index')->with('success', __('admin/location/zone.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_edit')) return $redirect;
        $this->getters->setSEOTitle('zone_edit');
        $item = Zone::where('id', $id)->first();
        $data['id'] = $id;
        $data['title'] = $item['title'];
        $data['city_id'] = $item['city_id'];
        $data['status'] = $item['status'];
        $data['city'] = City::all();
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/zone/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_edit')) return $redirect;
        $this->validate($request, ['title' => 'required|min:3', 'status' => 'required']);
        Zone::where('id', '=', $id)->update(['title' => $request->input('title'), 'city_id' => $request->input('city_id'), 'status' => $request->input('status')]);
        return redirect()->route('zone.index')->with('success', __('admin/location/zone.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_delete')) return $redirect;
        Zone::where('id', $id)->delete();
        return redirect()->route('zone.index')->with('success', __('admin/location/zone.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/location_zone_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            Zone::whereIn('id', $selectedIds)->delete();
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
