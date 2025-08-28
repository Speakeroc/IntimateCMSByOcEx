<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\Info\Banner;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class bannerController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index(Request $request): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/information_banner')) return $redirect;
        $this->getters->setSEOTitle('banner');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $data['filtered'] = 0;
        $data['title'] = $request->input('title') ?? null;
        $data['status'] = $request->input('status') ?? null;
        $query = Banner::query();
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
        $query->orderBy('id');

        $data['data'] = $query->paginate($paginate)->appends([
            'title' => $data['title'],
            'status' => $data['status'],
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            if (!empty($item['banner']) && File::exists(public_path($item['banner']))) {
                $banner = url($this->imageConverter->toMini($item['banner'], height: 100));
            } else {
                $banner = url('no_image.png');
            }

            $data['items'][] = [
                'id' => $item['id'],
                'banner' => $banner,
                'title' => mb_strlen($item['title']) > 40 ? mb_substr($item['title'], 0, 37) . '...' : $item['title'],
                'status' => $item['status'],
                'sort_order' => $item['sort_order'],
                'date_added' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/info/banner/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_add')) return $redirect;
        $this->getters->setSEOTitle('banner_add');

        $data = [
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/info/banner/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_add')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'banner' => 'required',
            'status' => 'required|integer',
        ]);

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s__%s');
        $data['title'] = $request->input('title');
        $data['link'] = $request->input('link');
        $data['banner'] = $request->input('banner');
        $data['status'] = $request->input('status');
        $data['sort_order'] = $request->input('sort_order');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();

        $content = 'images/banner/' . $data['uniq_uid'];
        $banner = $this->getters->moveTempToFolder($data['banner'], $content, $data['uniq_uid']);

        Banner::create([
            'user_id' => Auth::id(),
            'title' => strip_tags($data['title']),
            'link' => $data['link'],
            'banner' => $banner,
            'status' => $data['status'],
            'sort_order' => $data['sort_order'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('banner.index')->with('success', __('admin/info/banner.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_edit')) return $redirect;
        $this->getters->setSEOTitle('banner_edit');

        //Info
        $item = Banner::where('id', $id)->first();

        if (!File::exists(public_path($item['banner']))) {
            $banner = null;
        } else {
            $banner = $item['banner'];
        }

        $data = [
            'id' => $id,
            'title' => $item['title'],
            'link' => $item['link'],
            'banner' => $banner,
            'status' => $item['status'],
            'sort_order' => $item['sort_order'],
            'created_at' => date('Y-m-d H:i', strtotime($item['created_at'])),

            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/info/banner/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_edit')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'banner' => 'required',
            'status' => 'required|integer',
        ]);

        $banner_info = Banner::where('id', $id)->first();

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s__%s');
        $data['title'] = $request->input('title');
        $data['link'] = $request->input('link');
        $data['banner'] = $request->input('banner');
        $data['status'] = $request->input('status');
        $data['sort_order'] = $request->input('sort_order');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();


        if ($banner_info['banner'] != $data['banner']) {
            $old_content = dirname($banner_info['banner']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
            $content = 'images/banner/' . $data['uniq_uid'];
            $banner = $this->getters->moveTempToFolder($data['banner'], $content, $data['uniq_uid']);
        } else {
            $banner = $data['banner'];
        }

        Banner::where('id', '=', $id)->update([
            'title' => strip_tags($data['title']),
            'link' => $data['link'],
            'banner' => $banner,
            'status' => $data['status'],
            'sort_order' => $data['sort_order'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('banner.index')->with('success', __('admin/info/banner.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_delete')) return $redirect;
        $banner = Banner::where('id', $id)->first();

        //Delete content
        if (!empty($banner['banner'])) {
            $old_content = dirname($banner['banner']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
        }

        Banner::where('id', $id)->delete();

        return redirect()->route('banner.index')->with('success', __('admin/info/banner.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/information_banner_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $banner = Banner::where('id', $selectedId)->first();

                //Delete content
                if (!empty($banner['banner'])) {
                    $old_content = dirname($banner['banner']);
                    if (File::exists(public_path($old_content))) {
                        File::deleteDirectory(public_path($old_content));
                    }
                }

                Banner::where('id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
