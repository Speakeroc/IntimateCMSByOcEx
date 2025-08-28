<?php

namespace App\Models;

use App\Models\posts\Post;
use App\Models\posts\Salon;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Users extends Model {
    use HasFactory;

    protected $table = 'ex_users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'balance',
        'login',
        'name',
        'password',
        'type',
        'user_group_id',
        'allow_post_help',
        'email',
        'email_activate',
        'email_activate_code',
        'email_activate_time',
        'new_email',
        'remember_token',
        'forgot_token',
        'forgot_time',
        'ban',
        'last_seen',
        'referer',
        'selected_language',
        'created_at',
        'updated_at'
    ];

    public function getLastSeen($last_seen): array|string|Translator|Application|null {
        $current_date = date('Y-m-d');
        $yesterday_date = date('Y-m-d', strtotime('-1 day'));

        if (empty($last_seen)) {
            return __('admin/system/users.seen_no_data');
        } else {
            $time_difference = time() - strtotime($last_seen);
            if ($time_difference <= 600) {
                return __('admin/system/users.seen_online');
            } else {
                if (date('Y-m-d', strtotime($last_seen)) === $current_date) {
                    return __('admin/system/users.seen_today_in') . date('H:i', strtotime($last_seen));
                } else if (date('Y-m-d', strtotime($last_seen)) === $yesterday_date) {
                    return __('admin/system/users.seen_yesterday_in') . date('H:i', strtotime($last_seen));
                } else {
                    return date('d.m.Y H:i', strtotime($last_seen));
                }
            }
        }
    }

    public function userData($avatar = null, $width = 100): array
    {
        $getters = new Getters;
        $user = Users::where('id', Auth::id())->first();
        $post_on = Post::where('user_id', Auth::id())->where('publish', 1)->count();
        $post_all = Post::where('user_id', Auth::id())->count();
        $salon_on = Salon::where('user_id', Auth::id())->where('publish', 1)->count();
        $salon_all = Salon::where('user_id', Auth::id())->count();

        return [
            'post_on' => ($post_on) ? $post_on : 0,
            'post_all' => ($post_all) ? $post_all : 0,
            'salon_on' => ($salon_on) ? $salon_on : 0,
            'salon_all' => ($salon_all) ? $salon_all : 0,
            'balance' => $getters->currencyFormat($user['balance']),
            'login' => $user['login'],
            'name' => $user['name'],
            'email' => $user['email'],
            'allow_post_help' => $user['allow_post_help'],
        ];
    }
}
