<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\system\Transaction;
use App\Models\system\WebRealtime;
use App\Models\system\WebVisor;
use App\Models\Users;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class dashboardController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/dashboard')) return $redirect;
        SEOMeta::setTitle(__('admin/page_titles.dashboard'));

        $data = [
            'count_users' => Users::count(),
            'count_posts' => Post::count(),
            'online_users' => $this->onlineUsers(),
            'chart_posts' => $this->chartsPosts(),
            'chart_users' => $this->chartsUsers(),
            'posts_content_size' => $this->getters->getFolderFilesSize(public_path('images/posts')),
            'transaction' => $this->getTransaction(),
            'last_post' => $this->getLastPosts(),
            'realtime_chart' => $this->getRealtimeChart(),
            'realtime_links' => $this->getRealtimeLinks(),
        ];

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/dashboard', ['data' => $data]);
    }

    public function chartsPosts(): array
    {
        $data = [];
        $data['today'] = Post::whereDate('created_at', Carbon::today())->count();
        $data['yesterday'] = Post::whereDate('created_at', Carbon::yesterday())->count();
        $last30Days = CarbonPeriod::create(Carbon::now()->subDays(30), Carbon::now());
        $weeklyCounts = Post::whereDate('created_at', '>=', Carbon::now()->subDays(30))->whereDate('created_at', '<=', Carbon::now())->selectRaw("DATE(created_at) as date, COUNT(*) as count")->groupBy('date')->pluck('count', 'date');
        $data['month'] = implode(', ', array_map(fn($date) => $weeklyCounts->get($date->format('Y-m-d'), 0), iterator_to_array($last30Days)));
        $data['dates'] = implode(', ', array_map(fn($date) => $date->format('d'), iterator_to_array($last30Days)));
        return $data;
    }

    public function chartsUsers(): array
    {
        $data = [];
        $data['today'] = Users::whereDate('created_at', Carbon::today())->count();
        $data['yesterday'] = Users::whereDate('created_at', Carbon::yesterday())->count();
        $last30Days = CarbonPeriod::create(Carbon::now()->subDays(30), Carbon::now());
        $weeklyCounts = Users::whereDate('created_at', '>=', Carbon::now()->subDays(30))->whereDate('created_at', '<=', Carbon::now())->selectRaw("DATE(created_at) as date, COUNT(*) as count")->groupBy('date')->pluck('count', 'date');
        $data['month'] = implode(', ', array_map(fn($date) => $weeklyCounts->get($date->format('Y-m-d'), 0), iterator_to_array($last30Days)));
        $data['dates'] = implode(', ', array_map(fn($date) => $date->format('d'), iterator_to_array($last30Days)));
        return $data;
    }

    public function onlineUsers() {
        $Minutes15 = now()->subMinutes(15);
        return WebVisor::where('created_at', '>=', $Minutes15)->distinct('unique_code')->count();
    }

    public function getLastPosts(): array
    {
        $imageConverter = new ImageConverter;
        $data['data'] = Post::orderByDesc('id')->limit(6)->get();
        $data['items'] = [];
        foreach ($data['data'] as $item) {
            $image = $this->getters->getPostMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($imageConverter->toMini($image, height: 100));
            } else {
                $image = url('no_image_round.png');
            }

            $user = Users::where('id', $item['user_id'])->value('name') . ' - ID:' . $item['user_id'];

            $data['items'][] = [
                'id' => $item['id'],
                'image' => $image,
                'name' => $item['name'],
                'age' => trans_choice(__('admin/posts/post.age_choice'), $item['age'], ['num' => $item['age']]),
                'user' => $user,
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'phone' => $item['phone'],
                'verify' => $item['verify'] ? __('admin/posts/post.verify') : __('admin/posts/post.no_verify'),
                'publish' => $item['publish'],
                'publish_date' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }

        return $data['items'];
    }

    public function getTransaction(): array
    {
        $order_status_ids = app('order_status_ids');
        $data['data'] = Transaction::orderByDesc('id')->limit(15)->get();
        $data['items'] = [];
        foreach ($data['data'] as $item) {
            if (!empty($item['order_status_id'])) {
                $status = array_filter($order_status_ids, function ($status) use ($item) {
                    return $status['id'] === $item['order_status_id'];
                });
                $order_status = !empty($status) ? '<span style="color:black;background:'.array_values($status)[0]['color'].';border-radius:5px;padding:5px 10px;">'.array_values($status)[0]['short_title'].'</span>' : null;
                $order_status_color = !empty($status) ? array_values($status)[0]['short_title'] : null;
            } else {
                $order_status = null;
                $order_status_color = null;
            }
            $data['items'][] = [
                'id' => $item['id'],
                'type_str' => $item['type'],
                'type' => __('admin/pay/transaction.type_'.$item['type']),
                'price' => $item['price'],
                'short' => $item['short'],
                'user' => ['user' => Users::where('id', $item['user_id'])->value('name') ?? 'Пользователь удален', 'user_link' => route('users.index') . '?user_id=' . $item['user_id']],
                'order_status' => $order_status,
                'order_status_color' => $order_status_color,
                'date' => date('d.m.Y H:i', strtotime($item['created_at'])),
            ];
        }
        return $data['items'];
    }

    public function getRealtimeChart(): array
    {
        $setting_minutes = 30;
        $now = Carbon::now();
        $twentyMinutesAgo = Carbon::now()->subMinutes($setting_minutes);

        $allMinutes = [];
        $currentMinute = $twentyMinutesAgo->copy();
        while ($currentMinute <= $now) {
            $allMinutes[$currentMinute->format('i')] = 0;
            $currentMinute->addMinute();
        }

        $results = WebRealtime::select(DB::raw("DATE_FORMAT(created_at, '%i') as minute"), DB::raw('COUNT(*) as count'))->whereBetween('created_at', [$twentyMinutesAgo, $now])->groupBy('minute')->orderBy('minute', 'asc')->get();

        foreach ($results as $result) {
            $allMinutes[$result->minute] = (int)$result->count;
        }

        return [
            'title' => __('admin/dashboard.realtime_chart_t').' '.trans_choice('admin/dashboard.realtime_chart', $setting_minutes, ['num' => $setting_minutes]),
            'labels' => array_map(fn($minute) => str_pad($minute, 2, '0', STR_PAD_LEFT), array_keys($allMinutes)),
            'data' => array_values($allMinutes),
        ];
    }

    public function getRealtimeLinks(): array
    {
        $setting_minutes = 60;
        $data['title'] = __('admin/dashboard.realtime_link_t').' '.trans_choice('admin/dashboard.realtime_link', $setting_minutes, ['num' => $setting_minutes]);
        $results = WebRealtime::select('url', DB::raw('COUNT(*) as count'))->orderByDesc('count')->groupBy('url')->limit(5)->get();
        $data['items'] = [];
        foreach ($results as $item) {
            $data['items'][] = [
                'url' => $item['url'],
                'count' => $item['count'],
            ];
        }

        return $data;
    }

    public function getRealtimeToAjax(): JsonResponse
    {
        $data['chart'] = $this->getRealtimeChart();
        $data['link'] = $this->getRealtimeLinks();
        return response()->json($data);
    }
}
