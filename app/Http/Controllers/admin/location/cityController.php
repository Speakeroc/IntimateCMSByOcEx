<?php

namespace App\Http\Controllers\admin\location;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class cityController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct() {
        $this->getters = new Getters;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/location_city')) return $redirect;
        $this->getters->setSEOTitle('city');
        $paginate = $this->paginate;
        $data['data'] = City::paginate($paginate);
        $data['items'] = [];
        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'status' => $item['status'],
            ];
        }
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/city/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_add')) return $redirect;
        $this->getters->setSEOTitle('city_add');
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/city/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_add')) return $redirect;
        $this->validate($request, [
            'title' => ['required', 'min:2', 'unique:ex_location_city'],
            'latitude' => ['required', 'regex:/^-?\d{1,2}(\.\d+)?$/',],
            'longitude' => ['required', 'regex:/^-?\d{1,3}(\.\d+)?$/',],
            'status' => 'required'
        ]);
        City::create([
            'title' => $request->input('title'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'city_code' => Str::slug($request->input('title')),
            'status' => $request->input('status')
        ]);
        return redirect()->route('city.index')->with('success', __('admin/location/city.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_edit')) return $redirect;
        $this->getters->setSEOTitle('city_edit');
        $item = City::where('id', $id)->first();
        $data['id'] = $id;
        $data['title'] = $item['title'];
        $data['latitude'] = $item['latitude'];
        $data['longitude'] = $item['longitude'];
        $data['status'] = $item['status'];
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/location/city/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_edit')) return $redirect;
        $this->validate($request, [
            'title' => 'required|min:3',
            'latitude' => ['required', 'regex:/^-?\d{1,2}(\.\d+)?$/',],
            'longitude' => ['required', 'regex:/^-?\d{1,3}(\.\d+)?$/',],
            'status' => 'required'
        ]);
        City::where('id', '=', $id)->update([
            'title' => $request->input('title'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'city_code' => Str::slug($request->input('title')),
            'status' => $request->input('status')
        ]);
        return redirect()->route('city.index')->with('success', __('admin/location/city.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_delete')) return $redirect;
        City::where('id', $id)->delete();
        Zone::where('city_id', $id)->delete();
        Metro::where('city_id', $id)->delete();
        return redirect()->route('city.index')->with('success', __('admin/location/city.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/location_city_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                City::where('id', $selectedId)->delete();
                Zone::where('city_id', $selectedId)->delete();
                Metro::where('city_id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
