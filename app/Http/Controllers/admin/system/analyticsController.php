<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\Transaction;
use App\Models\system\WebVisor;
use App\Models\Users;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class analyticsController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index()
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/analytics')) return $redirect;
        SEOMeta::setTitle(__('admin/page_titles.analytics'));

        $periods = ['day' => Carbon::now()->subDay(), 'week' => Carbon::now()->subWeek(), 'month' => Carbon::now()->subMonth(), 'year' => Carbon::now()->subYear()];

        //Aaio Transactions
        $aaio_settings = $this->getters->getSetting('aaio');

        $data['aaio'] = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
        foreach ($periods as $key => $date) {
            $transactions = Transaction::where('order_id', '!=', null)->where('order_status_id', $aaio_settings['order_success_id'] ?? 0)->where('created_at', '>=', $date)->get();
            foreach ($transactions as $transaction) {
                $price = preg_replace('/[^\d]/', '', $transaction['price']);
                if (!empty($price)) {
                    $data['aaio'][$key] += (int)$price;
                }
            }
            $data['aaio'][$key] = $this->getters->currencyFormat($data['aaio'][$key]);
        }

        $data['services'] = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
        foreach ($periods as $key => $date) {
            $services = Transaction::where('order_id', '=', null)->where('created_at', '>=', $date)->get();
            foreach ($services as $service) {
                $price = preg_replace('/[^\d]/', '', $service['price']);
                if (!empty($price)) {
                    $data['services'][$key] += (int)$price;
                }
            }
            $data['services'][$key] = $this->getters->currencyFormat($data['services'][$key]);
        }

        $data['visitors'] = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
        foreach ($periods as $key => $date) {
            $visitors = WebVisor::where('created_at', '>=', $date)->count();
            $data['visitors'][$key] = $visitors;
        }

        $data['registers'] = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
        foreach ($periods as $key => $date) {
            $users = Users::where('created_at', '>=', $date)->count();
            $data['registers'][$key] = $users;
        }

        foreach ($periods as $key => $date) {
            $data['visitors_device'][$key] = WebVisor::where('device', '!=', null)->where('created_at', '>=', $date)->select('device', DB::raw('count(*) as count'))->groupBy('device')->get()->map(function ($item) {return ['title' => $item->device, 'count' => $item->count];})->sortByDesc('count')->toArray();
        }

        foreach ($periods as $key => $date) {
            $data['visitors_operating_system'][$key] = WebVisor::where('operating_system', '!=', null)->where('created_at', '>=', $date)->select('operating_system', DB::raw('count(*) as count'))->groupBy('operating_system')->get()->map(function ($item) {return ['title' => $item->operating_system, 'count' => $item->count];})->sortByDesc('count')->toArray();
        }

        foreach ($periods as $key => $date) {
            $data['visitors_country'][$key] = WebVisor::where('country', '!=', null)->where('created_at', '>=', $date)->select('country', DB::raw('count(*) as count'))->groupBy('country')->get()->map(function ($item) {return ['title' => __('admin/system/analytics.country_'.strtolower($item->country)), 'count' => $item->count];})->sortByDesc('count')->toArray();
        }

        foreach ($periods as $key => $date) {
            $data['visitors_browser'][$key] = WebVisor::where('browser', '!=', null)->where('created_at', '>=', $date)->select('browser', DB::raw('count(*) as count'))->groupBy('browser')->get()->map(function ($item) {return ['title' => $item->browser, 'count' => $item->count];})->sortByDesc('count')->toArray();
        }

        foreach ($periods as $key => $date) {
            $data['visitors_language'][$key] = WebVisor::where('language', '!=', null)->where('created_at', '>=', $date)->select('language', DB::raw('count(*) as count'))->groupBy('language')->get()->map(function ($item) {return ['title' => __('admin/system/analytics.lang_'.$item->language), 'count' => $item->count];})->sortByDesc('count')->toArray();
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/system/analytics', ['data' => $data]);
    }
}
