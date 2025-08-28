<?php

namespace App\Http\Controllers\admin\moderation;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\Review;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class reviewModerController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/review_moderation')) return $redirect;
        $this->getters->setSEOTitle('review_moderation_page');
        $paginate = $this->paginate;

        $query = Review::where('moderation_id', 0)->orderByDesc('created_at');
        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $user = Users::where('id', $item['user_id'])->value('name') . ' - ID:' . $item['user_id'];
            $name = Post::where('id', $item['post_id'])->value('name');
            $age = Post::where('id', $item['post_id'])->value('age');
            $name = $name ?? 'Анкета удалена';

            if (!empty($age)) {
                $age = trans_choice(__('admin/posts/post.age_choice'), $age, ['num' => $age]);
            }

            if (!$item['user_id']) {
                $user = 'Аноним';
            }

            $data['items'][] = [
                'id' => $item['id'],
                'text' => $item['text'],
                'name' => $name,
                'age' => $age,
                'post_link' => route('post.index') . '?post_id=' . $item['post_id'],
                'user' => $user,
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'rating' => $item['rating'],
                'date_added' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/moderation/review/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_moderation')) return $redirect;
        $this->getters->setSEOTitle('information_add');

        $data = [
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];
        return view('admin/info/information/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('review_moderation.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        return redirect()->route('review_moderation.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        return redirect()->route('review_moderation.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('review_moderation.index');
    }

    public function access(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/review_moderation')) return response()->json(['status' => 'error', 'message' => 'Доступ запрещен']);
        $review_id = $request->input('review_id');
        $status = $request->input('status');

        if (!$review_id) {
            return response()->json(['status' => 'error', 'message' => __('admin/posts/review.notify_review_empty')]);
        }

        if ($status == 1) {
            Review::where('id', $review_id)->update([
                'moderation_id' => 1,
                'moderator_id' => Auth::id(),
            ]);
            return response()->json(['status' => 'success', 'message' => __('admin/posts/review.notify_moder_success_1')]);
        }

        if ($status == 2) {
            Review::where('id', $review_id)->delete();
            return response()->json(['status' => 'success', 'message' => __('admin/posts/review.notify_moder_success_2')]);
        }

        return response()->json(['status' => 'error', 'message' => '']);
    }
}
