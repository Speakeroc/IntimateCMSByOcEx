<?php

namespace App\Http\Controllers\admin\common;

use App\Http\Controllers\Controller;
use App\Models\Info\Banner;
use App\Models\Info\Information;
use App\Models\Info\News;
use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\BlackList;
use App\Models\posts\Post;
use App\Models\posts\PostBanner;
use App\Models\posts\PostContent;
use App\Models\posts\Review;
use App\Models\posts\Salon;
use App\Models\posts\Tags;
use App\Models\system\Feedback;
use App\Models\system\Getters;
use App\Models\Users;
use App\Models\UsersGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class admSidebarController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = [];

        $data['name'] = Auth::user()->name ?? 'User';
        $user_group_id = Auth::user()->user_group_id;
        $data['group'] = Cache::rememberForever("admSidebar_user_group_" . $user_group_id, fn() => UsersGroup::where('id', $user_group_id)->value('name') ?? 'User Group');

        //Cache
        $data['cache_image_size'] = $this->getters->getFolderFilesSize(public_path('images/cache'));
        $data['cache_temp_image_size'] = $this->getters->getFolderFilesSize(public_path('images/temp'));
        $data['cache_log_files_size'] = $this->getters->getFolderFilesSize(storage_path('logs'));

        //Counters
        $counters = [
            'count_feedback' => Feedback::count(),
            'count_moder_post' => Post::where('moderation_id', 0)->count(),
            'count_moder_salon' => Salon::where('moderation_id', 0)->count(),
            'count_moder_review' => Review::where('moderation_id', 0)->count(),
            'count_post' => Post::count(),
            'count_salon' => Salon::count(),
            'count_post_banner' => PostBanner::count(),
            'count_post_tags' => Tags::count(),
            'count_black_list' => BlackList::count(),
            'count_review' => Review::where('moderation_id', 1)->count(),
            'count_image_main' => PostContent::where('type', 'main')->count(),
            'count_image_photo' => PostContent::where('type', 'photo')->count(),
            'count_image_selfie' => PostContent::where('type', 'selfie')->count(),
            'count_image_verify' => PostContent::where('type', 'verify')->count(),
            'count_image_video' => PostContent::where('type', 'video')->count(),
            'count_city' => City::count(),
            'count_zone' => Zone::count(),
            'count_metro' => Metro::count(),
            'count_news' => News::count(),
            'count_information' => Information::count(),
            'count_banner' => Banner::count(),
            'count_users' => Users::count(),
        ];

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/dashboard') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/analytics') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/ticket_system')) {
            $dashboard_group = true;
        } else {
            $dashboard_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_moderation') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/salon_moderation') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/review_moderation')) {
            $moderation_group = true;
        } else {
            $moderation_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/post') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/salon') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_banner') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/tags') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/blacklist')) {
            $post_salon_group = true;
        } else {
            $post_salon_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_main') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_photo') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_selfie') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_verify') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_video')) {
            $images_group = true;
        } else {
            $images_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_city') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_zone') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_metro')) {
            $location_group = true;
        } else {
            $location_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_information') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_news') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_banner')) {
            $info_group = true;
        } else {
            $info_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_code') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_aaio') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_ruKassa') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_transaction')) {
            $payment_group = true;
        } else {
            $payment_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/users') || $this->getters->getAdminAccessMenu(type: 'access', key: 'view/user_group')) {
            $users_group = true;
        } else {
            $users_group = false;
        }

        if ($this->getters->getAdminAccessMenu(type: 'access', key: 'view/app_settings')) {
            $app_group = true;
        } else {
            $app_group = false;
        }

        $data['menu'] = [
            [
                'block_title' => '',
                'type' => 'section', 'display' => $dashboard_group,
                'menu' => [
                    [
                        'link' => route('admin.config'), 'title' => __('admin/page_titles.dashboard'),
                        'path' => 'config/dashboard', 'icon' => 'far fa-gauge',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/dashboard')
                    ],
                    [
                        'link' => route('feedback.index'), 'title' => __('admin/page_titles.feedback'),
                        'path' => 'config/feedback*', 'icon' => 'far fa-envelope', 'counter' => $counters['count_feedback'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/feedback')
                    ],
                    [
                        'link' => route('admin.analytics'), 'title' => __('admin/page_titles.analytics'),
                        'path' => 'config/analytics', 'icon' => 'far fa-chart-line',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/analytics')
                    ],
                    [
                        'link' => route('ticket_system.index'), 'title' => __('admin/page_titles.ticket_system'),
                        'path' => 'config/ticket_system', 'icon' => 'far fa-headset',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/ticket_system')
                    ],
                ],
            ],
            [
                'block_title' => __('admin/common/sidebar.title_moderation'),
                'type' => 'section', 'display' => $moderation_group,
                'menu' => [
                    [
                        'link' => route('post_moderation.index'), 'title' => __('admin/page_titles.post_moderation'),
                        'path' => 'config/moderation/post_moderation*', 'icon' => 'far fa-address-card', 'counter' => $counters['count_moder_post'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_moderation')],
                    [
                        'link' => route('salon_moderation.index'), 'title' => __('admin/page_titles.salon_moderation'),
                        'path' => 'config/moderation/salon_moderation*', 'icon' => 'far fa-house-chimney-user', 'counter' => $counters['count_moder_salon'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/salon_moderation')],
                    [
                        'link' => route('review_moderation.index'), 'title' => __('admin/page_titles.review_moderation'),
                        'path' => 'config/moderation/review_moderation*', 'icon' => 'far fa-comments', 'counter' => $counters['count_moder_review'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/review_moderation')],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_post'),
                'type' => 'section', 'display' => $post_salon_group,
                'menu' => [
                    [
                        'link' => route('post.index'), 'title' => __('admin/page_titles.post'),
                        'path' => 'config/posts/post*', 'icon' => 'far fa-address-card', 'counter' => $counters['count_post'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post')
                    ],
                    [
                        'link' => route('salon.index'), 'title' => __('admin/page_titles.salon'),
                        'path' => 'config/salon*', 'icon' => 'far fa-house-chimney-user', 'counter' => $counters['count_salon'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/salon')
                    ],
                    [
                        'link' => route('banner_post.index'), 'title' => __('admin/page_titles.post_banner'),
                        'path' => 'config/posts/banner_post*', 'icon' => 'far fa-images', 'counter' => $counters['count_post_banner'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_banner')
                    ],
                    [
                        'link' => route('tags.index'), 'title' => __('admin/page_titles.tags'),
                        'path' => 'config/posts/tags*', 'icon' => 'far fa-tags', 'counter' => $counters['count_post_tags'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/tags')
                    ],
                    [
                        'link' => route('blacklist.index'), 'title' => __('admin/page_titles.blacklist'),
                        'path' => 'config/posts/blacklist*', 'icon' => 'far fa-address-book', 'counter' => $counters['count_black_list'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/blacklist')
                    ],
                    [
                        'link' => route('review.index'), 'title' => __('admin/page_titles.review'),
                        'path' => 'config/posts/review*', 'icon' => 'far fa-comments', 'counter' => $counters['count_review'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/review')
                    ],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_images'),
                'type' => 'section', 'display' => $images_group,
                'menu' => [
                    [
                        'link' => route('content_main.index'), 'title' => __('admin/page_titles.post_images_main'),
                        'path' => 'config/post_images/content_main*', 'icon' => 'far fa-image', 'counter' => $counters['count_image_main'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_main')
                    ],
                    [
                        'link' => route('content_photo.index'), 'title' => __('admin/page_titles.post_images_photo'),
                        'path' => 'config/post_images/content_photo*', 'icon' => 'far fa-camera', 'counter' => $counters['count_image_photo'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_photo')
                    ],
                    [
                        'link' => route('content_selfie.index'), 'title' => __('admin/page_titles.post_images_selfie'),
                        'path' => 'config/post_images/content_selfie*', 'icon' => 'far fa-image-portrait', 'counter' => $counters['count_image_selfie'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_selfie')
                    ],
                    [
                        'link' => route('content_verify.index'), 'title' => __('admin/page_titles.post_images_verify'),
                        'path' => 'config/post_images/content_verify*', 'icon' => 'far fa-circle-check', 'counter' => $counters['count_image_verify'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_verify')
                    ],
                    [
                        'link' => route('content_video.index'), 'title' => __('admin/page_titles.post_images_video'),
                        'path' => 'config/post_images/content_video*', 'icon' => 'far fa-video', 'counter' => $counters['count_image_video'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/post_content_video')
                    ],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_location'),
                'type' => 'section', 'display' => $location_group,
                'menu' => [
                    [
                        'link' => route('city.index'), 'title' => __('admin/page_titles.city'),
                        'path' => 'config/location/city*', 'icon' => 'far fa-city', 'counter' => $counters['count_city'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_city')
                    ],
                    [
                        'link' => route('zone.index'), 'title' => __('admin/page_titles.zone'),
                        'path' => 'config/location/zone*', 'icon' => 'far fa-location-dot', 'counter' => $counters['count_zone'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_zone')
                    ],
                    [
                        'link' => route('metro.index'), 'title' => __('admin/page_titles.metro'),
                        'path' => 'config/location/metro*', 'icon' => 'far fa-train-tram', 'counter' => $counters['count_metro'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/location_metro')
                    ],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_info'),
                'type' => 'section', 'display' => $info_group,
                'menu' => [
                    [
                        'link' => route('news.index'), 'title' => __('admin/page_titles.news'),
                        'path' => 'config/information/news*', 'icon' => 'far fa-newspaper', 'counter' => $counters['count_news'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_news')
                    ],
                    [
                        'link' => route('information.index'), 'title' => __('admin/page_titles.information'),
                        'path' => 'config/information/information*', 'icon' => 'far fa-list', 'counter' => $counters['count_information'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_information')
                    ],
                    [
                        'link' => route('banner.index'), 'title' => __('admin/page_titles.banner'),
                        'path' => 'config/information/banner*', 'icon' => 'far fa-images', 'counter' => $counters['count_banner'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/information_banner')
                    ],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_payment'),
                'type' => 'section', 'display' => $payment_group,
                'menu' => [
                    [
                        'link' => route('payment_code.index'), 'title' => __('admin/page_titles.payment_code'),
                        'path' => 'config/pay/payment_code*', 'icon' => 'far fa-barcode',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_code')],
                    [
                        'link' => route('admin.pay.payment_aaio'), 'title' => __('admin/page_titles.payment_aaio'),
                        'path' => 'config/pay/payment_aaio*', 'icon' => 'far fa-credit-card',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_aaio')],
                    [
                        'link' => route('admin.pay.payment_ruKassa'), 'title' => __('admin/page_titles.payment_ruKassa'),
                        'path' => 'config/pay/payment_ruKassa*', 'icon' => 'far fa-credit-card',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_aaio')],
                    [
                        'link' => route('transaction.index'), 'title' => __('admin/page_titles.transactions'),
                        'path' => 'config/pay/transactions*', 'icon' => 'far fa-list-check',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/payment_transaction')],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_users'),
                'type' => 'section', 'display' => $users_group,
                'menu' => [
                    [
                        'link' => route('users.index'), 'title' => __('admin/page_titles.users'),
                        'path' => 'config/users/users*', 'icon' => 'far fa-user', 'counter' => $counters['count_users'],
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/users')
                    ],
                    [
                        'link' => route('user_group.index'), 'title' => __('admin/page_titles.users_group'),
                        'path' => 'config/users/user_group*', 'icon' => 'far fa-user-group',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/user_group')],
                ]
            ],
            [
                'block_title' => __('admin/common/sidebar.title_system'),
                'type' => 'section', 'display' => $app_group,
                'menu' => [
                    [
                        'link' => route('admin.settings'), 'title' => __('admin/page_titles.settings'),
                        'path' => 'config/app/settings', 'icon' => 'far fa-gear',
                        'permission' => $this->getters->getAdminAccessMenu(type: 'access', key: 'view/app_settings')],
                ]
            ],
        ];

        return view('admin/common/sidebar', ['data' => $data]);
    }
}
