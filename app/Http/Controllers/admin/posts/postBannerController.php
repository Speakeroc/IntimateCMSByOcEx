<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\PostBanner;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class postBannerController extends Controller
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

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post_banner')) return $redirect;
        $this->getters->setSEOTitle('post_banner');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $data['filtered'] = 0;
        $data['status'] = $request->input('status') ?? null;
        $query = PostBanner::query();
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
            'status' => $data['status'],
        ]);

        $data['items'] = [];

        $sortedBanners = PostBanner::orderByDesc('up_date')->pluck('id')->toArray();

        foreach ($data['data'] as $item) {
            if (!empty($item['banner']) && File::exists(public_path($item['banner']))) {
                $banner = url($item['banner']);
            } else {
                $banner = url('no_image.png');
            }

            $position = array_search($item['id'], $sortedBanners) + 1;

            $post_user_id = Post::where('id', $item['post_id'])->value('user_id');
            $post_name = Post::where('id', $item['post_id'])->value('name');
            $post_age = Post::where('id', $item['post_id'])->value('age');
            $post_phone = Post::where('id', $item['post_id'])->value('phone');
            $post_image = $this->getters->getPostMainImage($item['post_id']);
            if (!empty($post_image) && File::exists(public_path($post_image))) {
                $post_image = url($this->imageConverter->toMini($post_image, height: 100));
            } else {
                $post_image = url('no_image.png');
            }

            $post = [
                'link' => route('post.index') . '?post_id=' . $item['post_id'],
                'name' => $post_name,
                'age' => trans_choice(__('admin/posts/post.age_choice'), $post_age, ['num' => $post_age]),
                'phone' => $post_phone,
                'user' => Users::where('id', $post_user_id)->value('name') . ' - ID:' . Users::where('id', $post_user_id)->value('id'),
                'user_link' => route('users.index') . '?user_id=' . $post_user_id,
            ];

            if ((time() < strtotime($item['activation_date'])) && $item['activation']) {
                $activation_text = __('admin/posts/banner.activation_on', ['date' => date('d.m.Y H:i', strtotime($item['activation_date']))]);
            } else {
                $activation_text = __('admin/posts/banner.activation_off');
            }

            $data['items'][] = [
                'id' => $item['id'],
                'banner' => $banner,
                'link' => $item['link'],

                'post_link' => $post['link'],
                'post_image' => $post_image,
                'post_name' => $post['name'],
                'post_age' => $post['age'],
                'post_phone' => $post['phone'],
                'post_user' => $post['user'],
                'post_user_link' => $post['user_link'],

                'title' => mb_strlen($item['title']) > 40 ? mb_substr($item['title'], 0, 37) . '...' : $item['title'],
                'status' => $item['status'],
                'position' => $position,
                'activation' => $item['activation'],
                'activation_text' => $activation_text,
                'date_added' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/banner/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_add')) return $redirect;
        $this->getters->setSEOTitle('post_banner_add');

        $posts = Post::orderBy('name')->get();

        $data['posts'] = [];

        foreach ($posts as $post) {
            $data['posts'][] = [
                'id' => $post['id'],
                'title' => $post['name'].' ('.trans_choice(__('admin/posts/post.age_choice'), $post['age'], ['num' => $post['age']]).') - '.$post['phone'],
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/banner/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_add')) return $redirect;
        $this->validate($request, [
            'status' => 'required|integer',
            'banner' => 'required',
        ]);

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s%s');
        $data['post_id'] = $request->input('post_id');
        $data['link'] = $request->input('link');
        $data['banner'] = $request->input('banner');
        $data['activation'] = $request->input('activation');
        $data['activation_date'] = $request->input('activation_date');
        $data['status'] = $request->input('status');

        $content = 'images/post_banner/' . $data['uniq_uid'];
        $banner = $this->getters->moveTempToFolder($data['banner'], $content, $data['uniq_uid']);

        PostBanner::create([
            'user_id' => Auth::id(),
            'post_id' => $data['post_id'],
            'link' => $data['link'],
            'banner' => $banner,
            'activation' => $data['activation'],
            'activation_date' => $data['activation_date'],
            'up_date' => Carbon::now(),
            'status' => $data['status'],
        ]);

        return redirect()->route('banner_post.index')->with('success', __('admin/posts/banner.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_edit')) return $redirect;
        $this->getters->setSEOTitle('post_banner_edit');

        $posts = Post::orderBy('name')->get();

        $data['posts'] = [];

        foreach ($posts as $post) {
            $data['posts'][] = [
                'id' => $post['id'],
                'title' => $post['name'].' ('.trans_choice(__('admin/posts/post.age_choice'), $post['age'], ['num' => $post['age']]).') - '.$post['phone'],
            ];
        }

        $item = PostBanner::where('id', $id)->first();

        if (!File::exists(public_path($item['banner']))) {
            $banner = null;
        } else {
            $banner = $item['banner'];
        }

        $data['id'] = $id;
        $data['banner'] = $banner;
        $data['post_id'] = $item['post_id'];
        $data['link'] = $item['link'];
        $data['activation'] = $item['activation'];
        $data['activation_date'] = $item['activation_date'];
        $data['status'] = $item['status'];
        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/banner/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_edit')) return $redirect;
        $this->validate($request, [
            'status' => 'required|integer',
            'banner' => 'required',
        ]);

        $banner_info = PostBanner::where('id', $id)->first();

        $data['uniq_uid'] = $this->getters->generateUniqueId('%s%s');
        $data['post_id'] = $request->input('post_id');
        $data['link'] = $request->input('link');
        $data['banner'] = $request->input('banner');
        $data['activation'] = $request->input('activation');
        $data['activation_date'] = $request->input('activation_date');
        $data['up_date'] = $request->input('up_date');
        $data['status'] = $request->input('status');


        if ($banner_info['banner'] != $data['banner']) {
            $old_content = dirname($banner_info['banner']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
            $content = 'images/post_banner/' . $data['uniq_uid'];
            $banner = $this->getters->moveTempToFolder($data['banner'], $content, $data['uniq_uid']);
        } else {
            $banner = $data['banner'];
        }

        PostBanner::where('id', '=', $id)->update([
            'post_id' => $data['post_id'],
            'link' => $data['link'],
            'banner' => $banner,
            'activation' => $data['activation'],
            'activation_date' => $data['activation_date'],
            'up_date' => ($data['up_date']) ? Carbon::now()->format('Y-m-d\TH:i') : date('Y-m-d H:i', strtotime($banner_info['up_date'])),
            'status' => $data['status'],
        ]);

        return redirect()->route('banner_post.index')->with('success', __('admin/posts/banner.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_delete')) return $redirect;
        $item = PostBanner::where('id', $id)->first();

        //Delete content
        if (!empty($item['banner'])) {
            $old_content = dirname($item['banner']);
            if (File::exists(public_path($old_content))) {
                File::deleteDirectory(public_path($old_content));
            }
        }

        PostBanner::where('id', $id)->delete();

        return redirect()->route('banner_post.index')->with('success', __('admin/posts/banner.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/post_banner_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $item = PostBanner::where('id', $selectedId)->first();

                //Delete content
                if (!empty($item['banner'])) {
                    $old_content = dirname($item['banner']);
                    if (File::exists(public_path($old_content))) {
                        File::deleteDirectory(public_path($old_content));
                    }
                }

                PostBanner::where('id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
