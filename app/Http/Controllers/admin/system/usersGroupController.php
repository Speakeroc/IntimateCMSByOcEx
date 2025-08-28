<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\Users;
use App\Models\UsersGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class usersGroupController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
        $this->permissions = [
            //Access
            ['type' => 'access', 'path' => 'view', 'name' => 'admin', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'admin_panel'],
            ['type' => 'access', 'path' => 'view', 'name' => 'dashboard'],
            ['type' => 'access', 'path' => 'view', 'name' => 'feedback'],
            ['type' => 'access', 'path' => 'view', 'name' => 'analytics'],
            ['type' => 'access', 'path' => 'view', 'name' => 'ticket_system'],
            ['type' => 'access', 'path' => 'view', 'name' => 'moderation', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_moderation'],
            ['type' => 'access', 'path' => 'view', 'name' => 'review_moderation'],
            ['type' => 'access', 'path' => 'view', 'name' => 'salon_moderation'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_salon', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'post'],
            ['type' => 'access', 'path' => 'view', 'name' => 'salon'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_banner'],
            ['type' => 'access', 'path' => 'view', 'name' => 'tags'],
            ['type' => 'access', 'path' => 'view', 'name' => 'blacklist'],
            ['type' => 'access', 'path' => 'view', 'name' => 'review'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content_main'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content_photo'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content_selfie'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content_verify'],
            ['type' => 'access', 'path' => 'view', 'name' => 'post_content_video'],
            ['type' => 'access', 'path' => 'view', 'name' => 'location', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'location_city'],
            ['type' => 'access', 'path' => 'view', 'name' => 'location_zone'],
            ['type' => 'access', 'path' => 'view', 'name' => 'location_metro'],
            ['type' => 'access', 'path' => 'view', 'name' => 'information', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'information_news'],
            ['type' => 'access', 'path' => 'view', 'name' => 'information_information'],
            ['type' => 'access', 'path' => 'view', 'name' => 'information_banner'],
            ['type' => 'access', 'path' => 'view', 'name' => 'payment', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'payment_code'],
            ['type' => 'access', 'path' => 'view', 'name' => 'payment_aaio'],
            ['type' => 'access', 'path' => 'view', 'name' => 'payment_ruKassa'],
            ['type' => 'access', 'path' => 'view', 'name' => 'payment_transaction'],
            ['type' => 'access', 'path' => 'view', 'name' => 'users', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'users'],
            ['type' => 'access', 'path' => 'view', 'name' => 'user_group'],
            ['type' => 'access', 'path' => 'view', 'name' => 'app', 'title' => true],
            ['type' => 'access', 'path' => 'view', 'name' => 'app_settings'],

            //Modify
            ['type' => 'modify', 'path' => 'edit', 'name' => 'feedback', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'feedback_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'feedback_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'ticket_system', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'ticket_system_write'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'ticket_system_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'moderation', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_moderation'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'review_moderation'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'salon_moderation'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'salon', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'salon_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'salon_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'salon_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_banner', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_banner_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_banner_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_banner_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'tags', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'tags_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'tags_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'tags_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'blacklist', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'blacklist_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'blacklist_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'blacklist_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'review', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'review_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'review_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'review_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_main', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_main_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_photo', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_photo_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_selfie', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_selfie_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_verify', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_verify_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_video', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'post_content_video_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_city', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_city_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_city_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_city_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_zone', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_zone_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_zone_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_zone_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_metro', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_metro_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_metro_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'location_metro_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_news', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_news_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_news_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_news_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_information', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_information_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_information_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_information_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_banner', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_banner_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_banner_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'information_banner_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_code', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_code_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_code_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_code_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_aaio', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_aaio_save'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_ruKassa', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'payment_ruKassa_save'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'users', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'users_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'users_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'users_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'user_group', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'user_group_add'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'user_group_edit'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'user_group_delete'],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'app_settings', 'title' => true],
            ['type' => 'modify', 'path' => 'edit', 'name' => 'app_settings_save'],
        ];
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/user_group')) return $redirect;
        $this->getters->setSEOTitle('users_group');

        $paginate = 20;
        $data['data'] = UsersGroup::select('id', 'name')->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/system/users_group/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/user_group_add')) return $redirect;
        $this->getters->setSEOTitle('users_group_add');

        foreach ($this->permissions as $permission) {
            $permissionType = $permission['type'];
            $permissionPath = $permission['path'];
            $permissionName = $permission['name'];
            $fullPermissionName = __('permissions.' . $permissionType . '_' . $permissionPath . '_' . $permissionName);

            if ($permissionType === 'access') {
                $name = $fullPermissionName;
            } else {
                $actionNames = ['add', 'edit', 'delete', 'save'];
                $actionKey = array_filter($actionNames, fn($action) => str_contains($permissionName, $action));
                $name = !empty($actionKey) ? __('permissions.' . reset($actionKey)) : $fullPermissionName;
            }

            $data['permissions'][$permissionType][] = [
                'type' => $permissionType,
                'permission' => "$permissionPath/$permissionName",
                'name' => $name,
                'number' => crc32($permissionPath . $permissionName . $name),
                'separate' => $permission['title'] ?? null,
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/system/users_group/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/user_group_add')) return $redirect;
        $this->validate($request, [
            'name' => 'required|min:3'
        ]);

        $permissions = $request->input('permission', []);

        UsersGroup::create([
            'name' => $request->input('name'),
            'permission' => json_encode($permissions)
        ]);

        return redirect()->route('user_group.index')->with('success', __('admin/system/users_group.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/user_group_edit')) return $redirect;
        $this->getters->setSEOTitle('users_group_edit');

        $userGroup = UsersGroup::where('id', '=', $id)->first();

        $data['id'] = $id;
        $data['name'] = $userGroup['name'];
        $data['groupPermission'] = json_decode($userGroup['permission'], true);

        foreach ($this->permissions as $permission) {
            $checked = false;
            if (isset($data['groupPermission'][$permission['type']]) && in_array($permission['path'] . '/' . $permission['name'], $data['groupPermission'][$permission['type']])) {
                $checked = true;
            }
            $permissionType = $permission['type'];
            $permissionPath = $permission['path'];
            $permissionName = $permission['name'];
            $fullPermissionName = __('permissions.' . $permissionType . '_' . $permissionPath . '_' . $permissionName);

            if ($permissionType === 'access') {
                $name = $fullPermissionName;
            } else {
                $actionNames = ['add', 'edit', 'delete', 'save'];
                $actionKey = array_filter($actionNames, fn($action) => str_contains($permissionName, $action));
                $name = !empty($actionKey) ? __('permissions.' . reset($actionKey)) : $fullPermissionName;
            }

            $data['permissions'][$permissionType][] = [
                'type' => $permissionType,
                'permission' => "$permissionPath/$permissionName",
                'name' => $name,
                'number' => crc32($permissionPath . $permissionName . $name),
                'separate' => $permission['title'] ?? null,
                'checked' => $checked,
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/system/users_group/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/user_group_edit')) return $redirect;
        $this->validate($request, [
            'name' => 'required|min:3'
        ]);

        $permissions = $request->input('permission', []);

        UsersGroup::where('id', '=', $id)->update([
            'name' => $request->input('name'),
            'permission' => json_encode($permissions)
        ]);

        return redirect()->route('user_group.index')->with('success', __('admin/system/users_group.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/user_group_delete')) return $redirect;
        $countUsers = Users::where('user_group_id', '=', $id)->count();
        $countUsersGroup = UsersGroup::count();

        if ($id == 1) return redirect()->back()->with('warning', __('admin/system/users_group.notify_delete_main'));

        if ($countUsers) return redirect()->back()->with('warning', __('admin/system/users_group.notify_user_in_group'));

        if ($countUsersGroup <= 1) return redirect()->back()->with('warning', __('admin/system/users_group.notify_last'));

        UsersGroup::where('id', '=', $id)->delete();
        return redirect()->route('user_group.index')->with('success', __('admin/system/users_group.notify_deleted'));
    }
}
