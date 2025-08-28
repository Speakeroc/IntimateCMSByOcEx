<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\Review;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class reviewsController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index()
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/review')) return $redirect;
        $this->getters->setSEOTitle('review');
        $paginate = $this->paginate;

        $query = Review::where('moderation_id', 1)->orderByDesc('created_at');
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

            if ($item['moderation_id'] == 0) {
                $status = __('admin/posts/review.status_0');
            } elseif ($item['moderation_id'] == 1) {
                $status = __('admin/posts/review.status_1');
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
                'status' => $status,
                'status_int' => $item['moderation_id'],
                'date_added' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/review/index', ['data' => $data]);
    }

    public function create()
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_add')) return $redirect;
        $this->getters->setSEOTitle('review_add');

        $data = [
            'main_data' => $this->getters->getMainPostData(),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        $posts = Post::orderBy('name')->get();
        $data['posts'] = [];
        foreach ($posts as $post) {
            $data['posts'][] = [
                'id' => $post['id'],
                'title' => $post['name'].' ('.trans_choice(__('admin/posts/post.age_choice'), $post['age'], ['num' => $post['age']]).') - '.$post['phone'],
            ];
        }

        return view('admin/posts/review/create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_add')) return $redirect;
        $this->validate($request, [
            'post_id' => 'required|integer',
            'text' => 'required|min:10|max:3000',
            'rating' => 'required',
        ]);

        $data['user_id'] = (int)$request->input('user_id');
        $data['post_id'] = $request->input('post_id');
        $data['text'] = $request->input('text');
        $data['rating'] = $request->input('rating');

        Review::create([
            'user_id' => $data['user_id'],
            'post_id' => $data['post_id'],
            'text' => $data['text'],
            'rating' => $data['rating'],
            'moderation_id' => 1,
            'moderator_id' => Auth::id(),
            'publish' => 1,
        ]);

        return redirect()->route('review.index')->with('success', __('admin/posts/review.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_edit')) return $redirect;
        $this->getters->setSEOTitle('review_edit');

        //Info
        $item = Review::where('id', $id)->first();

        $data = [
            'id' => $id,
            'text' => $item['text'],
            'rating' => $item['rating'],
            'post_id' => $item['post_id'],
            'user_id' => $item['user_id'],
            'moderation_id' => $item['moderation_id'],

            //Get Data
            'main_data' => $this->getters->getMainPostData(),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        $posts = Post::orderBy('name')->get();
        $data['posts'] = [];
        foreach ($posts as $post) {
            $data['posts'][] = [
                'id' => $post['id'],
                'title' => $post['name'].' ('.trans_choice(__('admin/posts/post.age_choice'), $post['age'], ['num' => $post['age']]).') - '.$post['phone'],
            ];
        }

        return view('admin/posts/review/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id)
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_edit')) return $redirect;
        $this->validate($request, [
            'post_id' => 'required|integer',
            'text' => 'required|min:10|max:3000',
            'rating' => 'required',
        ]);

        $data['user_id'] = (int)$request->input('user_id');
        $data['post_id'] = $request->input('post_id');
        $data['text'] = $request->input('text');
        $data['rating'] = $request->input('rating');

        Review::where('id', '=', $id)->update([
            'user_id' => $data['user_id'],
            'post_id' => $data['post_id'],
            'text' => $data['text'],
            'rating' => $data['rating'],
            'moderation_id' => 1,
            'moderator_id' => Auth::id(),
            'publish' => 1,
        ]);

        return redirect()->route('review.index')->with('success', __('admin/posts/review.notify_updated'));
    }

    public function destroy(string $id)
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_delete')) return $redirect;
        Review::where('id', $id)->delete();
        return redirect()->route('review.index')->with('success', __('admin/posts/review.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/review_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            Review::whereIn('id', $selectedIds)->delete();
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
