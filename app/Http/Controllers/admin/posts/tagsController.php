<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Tags;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class tagsController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 50;
    }

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/tags')) return $redirect;
        $this->getters->setSEOTitle('tags');
        $paginate = $this->paginate;

        $data['filtered'] = 0;
        $data['tag'] = $request->input('tag') ?? null;
        $query = Tags::query();
        if ($data['tag'] != null) {
            $query->where('tag', 'like', '%' . $data['tag'] . '%');
            $data['filtered']++;
        }
        $query->orderBy('id');

        $data['data'] = $query->paginate($paginate)->appends([
            'tag' => $data['tag']
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'tag' => $item['tag'],
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/tags/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_add')) return $redirect;
        $this->getters->setSEOTitle('tags_add');

        $data = [
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];
        return view('admin/posts/tags/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_add')) return $redirect;
        $this->validate($request, [
            'tag' => 'required|unique:ex_post_tags'
        ]);

        $data['tag'] = $request->input('tag');

        Tags::create(['tag' => $data['tag'],]);

        return redirect()->route('tags.index')->with('success', __('admin/posts/tags.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_edit')) return $redirect;
        $this->getters->setSEOTitle('tags_edit');

        $item = Tags::where('id', $id)->first();

        $data = [
            'id' => $id,
            'tag' => $item['tag'],

            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/posts/tags/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_edit')) return $redirect;
        $this->validate($request, [
            'tag' => 'required|unique:ex_post_tags,tag,' . $id,
        ]);


        $data['tag'] = $request->input('tag');

        Tags::where('id', '=', $id)->update([
            'tag' => $data['tag'],
        ]);

        return redirect()->route('tags.index')->with('success', __('admin/posts/tags.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_delete')) return $redirect;
        Tags::where('id', $id)->delete();

        return redirect()->route('tags.index')->with('success', __('admin/posts/tags.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/tags_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            Tags::whereIn('id', $selectedIds)->delete();
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
