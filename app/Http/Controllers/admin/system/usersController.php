<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use App\Models\UsersGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class usersController extends Controller
{
    private Users $users;
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct() {
        $this->users = new Users;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/users')) return $redirect;
        $this->getters->setSEOTitle('users');

        $data['filtered'] = 0;
        $data['name'] = $request->input('name') ?? null;
        $data['login'] = $request->input('login') ?? null;
        $data['email'] = $request->input('email') ?? null;
        $data['user_id'] = $request->input('user_id') ?? null;
        $data['user_group_id'] = $request->input('user_group_id') ?? null;
        $query = Users::query();
        if ($data['name'] != null) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
            $data['filtered']++;
        }
        if ($data['login'] != null) {
            $query->where('login', 'like', '%' . $data['login'] . '%');
            $data['filtered']++;
        }
        if ($data['email'] != null) {
            $query->where('email', 'like', '%' . $data['email'] . '%');
            $data['filtered']++;
        }
        if ($data['user_group_id'] != null) {
            $query->where('user_group_id', $data['user_group_id']);
            $data['filtered']++;
        }
        if ($data['user_id'] != null) {
            $query->where('id', $data['user_id']);
        }
        $query->orderBy('id');

        $data['groups'] = UsersGroup::pluck('name', 'id')->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        })->values()->toArray();

        $paginate = 20;
        $data['data'] = $query->paginate($paginate)->appends([
            'name' => $data['name'],
            'login' => $data['login'],
            'email' => $data['email'],
            'user_group_id' => $data['user_group_id'],
        ]);

        $data['items'] = [];

        foreach ($data['data'] as $user) {

            $last_seen = $this->users->getLastSeen($user['last_seen']);

            $image = url('no_image.png');

            $userGroup = UsersGroup::select('name')->where('id', '=', $user['user_group_id'])->first();

            $data['items'][] = [
                'id'            => $user['id'],
                'login'         => $user['login'],
                'name'          => $user['name'],
                'balance'       => $this->getters->currencyFormat(($user['balance'] ?? 0)),
                'group_name'    => $userGroup['name'] ?? 'NULL',
                'email'         => $user['email'],
                'posts'         => Post::where('user_id', $user['id'])->count(),
                'posts_link'    => route('post.index').'?user_id='.$user['id'],
                'register'      => date('d.m.Y', strtotime($user['created_at'])),
                'image'         => $image,
                'last_seen'     => $last_seen,
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/system/users/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/users_add')) return $redirect;
        $this->getters->setSEOTitle('users_add');

        $data['groups'] = UsersGroup::pluck('name', 'id')->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        })->values()->toArray();

        $data['currency_symbol'] = trim($this->getters->getCurrencySymbol());

        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/system/users/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/users_add')) return $redirect;
        $this->validate($request, [
            'username' => 'required|min:2|max:20',
            'login' => 'required|min:4|max:20|unique:ex_users|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|unique:ex_users|email|max:50',
            'balance' => 'required|min:0|max:255',
            'password' => ['required', 'min:8', 'max:30'],
            'user_group_id' => 'required',
        ]);

        $username = $request->input('username');
        $login = $request->input('login');
        $email = $request->input('email');
        $email_activate = $request->input('email_activate') ?? 0;
        $balance = $request->input('balance') ?? 0;
        $password = $request->input('password');
        $user_group_id = $request->input('user_group_id');

        $image_path = null;

        Users::create([
            'name' => $username,
            'login' => $login,
            'email' => $email,
            'email_activate' => $email_activate,
            'balance' => $balance,
            'password' => bcrypt($password),
            'user_group_id' => $user_group_id,
        ]);

        $this->getters->clearFolder(public_path('images/cache/users'));
        return redirect()->route('users.index')->with('success', __('admin/system/users.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/users_edit')) return $redirect;
        $this->getters->setSEOTitle('users_edit');

        $data['groups'] = UsersGroup::pluck('name', 'id')->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        })->values()->toArray();

        $data['currency_symbol'] = trim($this->getters->getCurrencySymbol());

        $item = Users::where('id', $id)->first();
        $data['id'] = $id;
        $data['username'] = $item['name'] ?? null;
        $data['login'] = $item['login'] ?? null;
        $data['email'] = $item['email'] ?? null;
        $data['email_activate'] = $item['email_activate'] ?? null;
        $data['balance'] = $item['balance'] ?? null;
        $data['user_group_id'] = $item['user_group_id'] ?? null;

        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/system/users/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/users_edit')) return $redirect;
        $username = $request->input('username');
        $old_username = $request->input('old_username');
        $login = $request->input('login');
        $old_login = $request->input('old_login');
        $email = $request->input('email');
        $old_email = $request->input('old_email');
        $email_activate = $request->input('email_activate') ?? 0;
        $balance = $request->input('balance') ?? 0;
        $password = $request->input('password');
        $user_group_id = $request->input('user_group_id');

        if ($login !== $old_login) {
            $this->validate($request, ['login' => 'required|min:4|max:20|unique:ex_users|regex:/^[a-zA-Z0-9]+$/']);
        }

        if ($username !== $old_username) {
            $this->validate($request, ['username' => 'required|min:2|max:20']);
        }

        if ($email !== $old_email) {
            $this->validate($request, ['email' => 'required|unique:ex_users|email|max:50']);
        }

        $this->validate($request, ['user_group_id' => 'required']);

        Users::where('id', '=', $id)->update([
            'name' => $username,
            'login' => $login,
            'email' => $email,
            'email_activate' => $email_activate,
            'balance' => $balance,
            'user_group_id' => $user_group_id,
        ]);

        if (!empty($password)) {
            $this->validate($request, ['password' => ['required', 'min:8', 'max:30']]);
        }

        if (!empty($password)) {
            Users::where('id', '=', $id)->update([
                'password' => bcrypt($password)
            ]);
        }

        $this->getters->clearFolder(public_path('images/cache/users'));
        return redirect()->route('users.index')->with('success', __('admin/system/users.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/users_delete')) return $redirect;
        $countUsers = Users::count();

        if ($id == 1) return redirect()->back()->with('warning', __('admin/system/users.notify_delete_admin'));

        if ($countUsers <= 1) return redirect()->back()->with('warning', __('admin/system/users.notify_delete_last_user'));

        if (Auth::user()->id == $id) return redirect()->back()->with('warning', __('admin/system/users.notify_delete_this_user'));

        Users::where('id', '=', $id)->delete();
        return redirect()->route('users.index')->with('success', __('admin/system/users.notify_deleted'));
    }
}
