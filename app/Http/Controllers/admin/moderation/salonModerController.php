<?php

namespace App\Http\Controllers\admin\moderation;

use App\Http\Controllers\Controller;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class salonModerController extends Controller
{
    private Getters $getters;
    private Salon $salon;
    private ImageConverter $imageConverter;
    private int $paginate;

    public function __construct()
    {
        $this->salon = new Salon;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/salon_moderation')) return $redirect;
        $this->getters->setSEOTitle('salon_moderation_page');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getCityData();
        $query = Salon::where('moderation_id', 0)->orderByDesc('id');

        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $image = $this->salon->getMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: 100));
            } else {
                $image = url('no_image_round.png');
            }

            $user = Users::where('id', $item['user_id'])->value('name') . ' - ID:' . $item['user_id'];

            $phone_key = ['phone', 'phone_one', 'phone_two'];
            $dd['phones'] = [];
            foreach ($phone_key as $p_key) {
                if (!empty($item[$p_key])) {
                    $dd['phones'][] = $item[$p_key];
                }
            }

            $data['items'][] = [
                'id' => $item['id'],
                'image' => $image,
                'title' => $item['title'],
                'user' => $user,
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'phones' => $dd['phones'],
                'publish' => $item['publish'],
                'publish_date' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/moderation/salon/index', ['data' => $data]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('salon_moderation.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('salon_moderation.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/salon_moderation')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_moderation')) return $redirect;
        $this->getters->setSEOTitle('salon_moderation_edit');

        $item = Salon::where('id', $id)->first();
        $item_content = SalonContent::where('salon_id', $id)->get();

        $data = [
            'id' => $id,
            'user_id' => $item['user_id'],
            'uniq_uid' => $item['uniq_uid'],
            'title' => $item['title'],
            'city_id' => $item['city_id'],
            'phone' => $item['phone'],
            'phone_one' => $item['phone_one'],
            'phone_two' => $item['phone_two'],
            'zone_id' => $item['zone_id'],
            'metro_id' => $item['metro_id'],
            'messengers' => json_decode($item['messengers'], true),
            'work_time_type' => $item['work_time_type'],
            'work_time' => json_decode($item['work_time'], true),
            'delete_code' => $item['delete_code'],
            'latitude' => $item['latitude'],
            'longitude' => $item['longitude'],
            'price_day_in_one' => $this->getters->currencyFormat($item['price_day_in_one']),
            'price_day_in_two' => $this->getters->currencyFormat($item['price_day_in_two']),
            'price_day_out_one' => $this->getters->currencyFormat($item['price_day_out_one']),
            'price_day_out_two' => $this->getters->currencyFormat($item['price_day_out_two']),
            'price_night_in_one' => $this->getters->currencyFormat($item['price_night_in_one']),
            'price_night_in_night' => $this->getters->currencyFormat($item['price_night_in_night']),
            'price_night_out_one' => $this->getters->currencyFormat($item['price_night_out_one']),
            'price_night_out_night' => $this->getters->currencyFormat($item['price_night_out_night']),
            'address' => $item['address'],
            'desc' => $item['desc'],
            'moderation_id' => $item['moderation_id'],
            'moderation_text' => $item['moderation_text'],
            'publish' => $item['publish'],
            'publish_date' => date('Y-m-d', strtotime($item['publish_date'])),
            'image_main' => null,
            'image_photos' => [],

            //Get Data
            'main_data' => $this->getters->getMainSalonData($item['city_id']),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'salon_activation_status' => $this->getters->getSetting('salon_activation_status'),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];

        $typeMap = ['main' => 'image_main', 'photo' => 'image_photos'];
        foreach ($item_content as $content) {
            $type = $content['type'];
            if (isset($typeMap[$type])) {
                if (is_array($data[$typeMap[$type]])) {
                    $data[$typeMap[$type]][] = $content['file'];
                } else {
                    $data[$typeMap[$type]] = $content['file'];
                }
            }
        }

        return view('admin/moderation/salon/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/salon_moderation')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_moderation')) return $redirect;
        $this->validate($request, [
            'user_id' => 'required|integer',
            'moderation_id' => 'required',
        ]);

        $data['salon_activation_status'] = $this->getters->getSetting('salon_activation_status');

        $data['user_id'] = (int)$request->input('user_id');
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');

        if ($data['moderation_id'] == 1) {
            $data['moderation_text'] = '';
        }

        if ($data['salon_activation_status'] && $data['moderation_id'] == 1) {
            $publish = 0;
        } elseif ($data['salon_activation_status'] && $data['moderation_id'] == 1) {
            $publish = 1;
        } else {
            $publish = 0;
        }

        Salon::where('id', '=', $id)->update([
            'user_id' => $data['user_id'],
            'moderator_id' => Auth::id(),
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'publish' => $publish,
            'publish_date' => Carbon::now(),
        ]);

        return redirect()->route('salon_moderation.index')->with('success', __('admin/posts/post.notify_moderation'));
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('salon_moderation.index');
    }
}
