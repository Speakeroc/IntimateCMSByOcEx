<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\Info\News;
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
use Illuminate\Support\Str;

class newsController extends Controller
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
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/information_news')) return $redirect;
        $this->getters->setSEOTitle('news');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $data['filtered'] = 0;
        $data['title'] = $request->input('title') ?? null;
        $data['pinned'] = $request->input('pinned') ?? null;
        $data['status'] = $request->input('status') ?? null;
        $query = News::query();
        if ($data['title'] != null) {
            $query->where('title', 'like', '%' . $data['title'] . '%');
            $data['filtered']++;
        }
        if ($data['pinned'] == 'yes') {
            $query->where('pinned', 1);
            $data['filtered']++;
        }
        if ($data['pinned'] == 'no') {
            $query->where('pinned', 0);
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
        $query->orderByDesc('created_at');

        $data['data'] = $query->paginate($paginate)->appends([
            'title' => $data['title'],
            'pinned' => $data['pinned'],
            'status' => $data['status'],
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            if (!empty($item['image']) && File::exists(public_path($item['image']))) {
                $image = url($this->imageConverter->toMini($item['image'], height: 100));
            } else {
                $image = url('no_image_round.png');
            }

            $data['items'][] = [
                'id' => $item['id'],
                'image' => $image,
                'title' => $item['title'],
                'views' => $item['views'],
                'like' => $item['like'],
                'dislike' => $item['dislike'],
                'pinned' => $item['pinned'],
                'pinned_text' => ($item['pinned']) ? __('admin/info/news.pinned_yes') : __('admin/info/news.pinned_no'),
                'status' => $item['status'],
                'date_added' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/info/news/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_add')) return $redirect;
        $this->getters->setSEOTitle('news_add');

        $data = [
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];
        return view('admin/info/news/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_add')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'meta_title' => 'nullable|min:1|max:100',
            'desc' => 'required|min:1|max:10000',
            'views' => 'nullable|integer',
            'like' => 'nullable|integer',
            'dislike' => 'nullable|integer',
            'pinned' => 'required|integer',
            'status' => 'required|integer',
            'created_at' => 'nullable',
        ]);

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s__%s');
        $data['title'] = $request->input('title');
        $data['desc'] = $request->input('desc');
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = Str::limit($request->input('meta_description'), 150);
        $data['image'] = $request->input('image');
        $data['seo_url'] = $request->input('seo_url') ?? Str::slug($data['title']);
        $data['views'] = $request->input('views');
        $data['like'] = $request->input('like');
        $data['dislike'] = $request->input('dislike');
        $data['pinned'] = $request->input('pinned');
        $data['status'] = $request->input('status');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();

        $content = 'images/news/' . $data['uniq_uid'];
        $image = null;
        if (!empty($data['image'])) {
            $image = $this->getters->moveTempToFolder($data['image'], $content, $data['uniq_uid']);
        }

        News::create([
            'user_id' => Auth::id(),
            'title' => strip_tags($data['title']),
            'desc' => $this->getters->convertTextData($data['desc']),
            'meta_title' => strip_tags($data['meta_title']),
            'meta_description' => strip_tags($data['meta_description']),
            'image' => $image,
            'seo_url' => $data['seo_url'],
            'views' => $data['views'],
            'like' => $data['like'],
            'dislike' => $data['dislike'],
            'pinned' => $data['pinned'],
            'status' => $data['status'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('news.index')->with('success', __('admin/info/news.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_edit')) return $redirect;
        $this->getters->setSEOTitle('news_edit');

        //Info
        $item = News::where('id', $id)->first();

        if (!File::exists(public_path($item['image']))) {
            $image = null;
        } else {
            $image = $item['image'];
        }

        $data = [
            'id' => $id,
            'title' => $item['title'],
            'desc' => $this->getters->reverseTextData($item['desc']),
            'meta_title' => $item['meta_title'],
            'meta_description' => $item['meta_description'],
            'image' => $image,
            'seo_url' => $item['seo_url'],
            'views' => $item['views'],
            'like' => $item['like'],
            'dislike' => $item['dislike'],
            'pinned' => $item['pinned'],
            'status' => $item['status'],
            'created_at' => date('Y-m-d', strtotime($item['created_at'])),

            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        return view('admin/info/news/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_edit')) return $redirect;
        $this->validate($request, [
            'title' => 'required',
            'meta_title' => 'nullable|min:1|max:100',
            'desc' => 'required|min:1|max:10000',
            'views' => 'nullable|integer',
            'like' => 'nullable|integer',
            'dislike' => 'nullable|integer',
            'pinned' => 'required|integer',
            'status' => 'required|integer',
            'created_at' => 'nullable',
        ]);

        $news_info = News::where('id', $id)->first();

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s__%s');
        $data['title'] = $request->input('title');
        $data['desc'] = $request->input('desc');
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = Str::limit($request->input('meta_description'), 150);
        $data['image'] = $request->input('image');
        $data['seo_url'] = $request->input('seo_url') ?? Str::slug($data['title']);
        $data['views'] = $request->input('views');
        $data['like'] = $request->input('like');
        $data['dislike'] = $request->input('dislike');
        $data['pinned'] = $request->input('pinned');
        $data['status'] = $request->input('status');
        $data['created_at'] = $request->input('created_at') ?? Carbon::now();

        if ($news_info['image'] != $data['image']) {
            $old_content = dirname($news_info['image']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
            $content = 'images/news/' . $data['uniq_uid'];
            $image = null;
            if (!empty($data['image'])) {
                $image = $this->getters->moveTempToFolder($data['image'], $content, $data['uniq_uid']);
            }
        } else {
            $image = $data['image'];
        }

        News::where('id', '=', $id)->update([
            'title' => strip_tags($data['title']),
            'desc' => $this->getters->convertTextData($data['desc']),
            'meta_title' => strip_tags($data['meta_title']),
            'meta_description' => strip_tags($data['meta_description']),
            'image' => $image,
            'seo_url' => $data['seo_url'],
            'views' => $data['views'],
            'like' => $data['like'],
            'dislike' => $data['dislike'],
            'pinned' => $data['pinned'],
            'status' => $data['status'],
            'created_at' => $data['created_at'],
        ]);

        return redirect()->route('news.index')->with('success', __('admin/info/news.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_delete')) return $redirect;
        $news = News::where('id', $id)->first();

        //Delete content
        if (!empty($news['image'])) {
            $old_content = dirname($news['image']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
        }
        News::where('id', $id)->delete();

        return redirect()->route('news.index')->with('success', __('admin/info/news.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/information_news_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $news = News::where('id', $selectedId)->first();
                if (!empty($news['image'])) {
                    $old_content = dirname($news['image']);
                    if (File::exists(public_path($old_content))) {
                        File::deleteDirectory(public_path($old_content));
                    }
                }
                News::where('id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
