<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class salonAddController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $title = __('catalog/id/salon_create.page_main');
        $this->getters->setMetaInfo(title: $title, url: route('client.auth.salon.create'));

        $breadcrumbs = [
            ['link' => route('client.auth.salon'), 'title' => __('catalog/id/salon.page_main')],
            ['link' => route('client.auth.salon.create'), 'title' => __('catalog/id/salon_create.page_main')],
        ];

        $data = [
            'title' => $title,
            'this_user_id' => Auth::id(),
            'main_data' => $this->getters->getMainSalonData(),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'salon_activation_status' => $this->getters->getSetting('salon_activation_status'),
            'breadcrumb' => $this->getters->breadcrumbPages($breadcrumbs),
            'elements' => $this->getters->getHeaderFooter(),
        ];

        return view('catalog/id/salon/create', ['data' => $data]);
    }

    public function createPost(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'images.main' => 'required',
            'title' => 'required|min:2|max:100',
            'city_id' => 'required|integer',
            'phone' => 'required',
            'work_time_type' => 'required',
            'delete_code' => 'required|min:4|max:8',
            'price_day_in_one' => 'required|integer',
            'price_day_in_two' => 'required|integer',
            'price_day_out_one' => 'nullable|integer',
            'price_day_out_two' => 'nullable|integer',
            'price_night_in_one' => 'nullable|integer',
            'price_night_in_night' => 'nullable|integer',
            'price_night_out_one' => 'nullable|integer',
            'price_night_out_night' => 'nullable|integer',
            'address' => 'nullable|max:100',
            'desc' => 'required|min:100|max:3000',
        ]);

        $data['user_id'] = (int)Auth::id();
        $data['uniq_uid'] = $this->getters->generateUniqueId($data['user_id'] . '_%s_%s');
        $data['image_main'] = $request->input('images.main');
        $data['image_photo'] = $request->input('images.photos') ?? null;
        $data['title'] = $request->input('title');
        $data['city_id'] = (int)$request->input('city_id');
        $data['phone'] = $request->input('phone');
        $data['phone_one'] = $request->input('phone_one');
        $data['phone_two'] = $request->input('phone_two');
        $data['zone_id'] = (int)$request->input('zone_id');
        $data['metro_id'] = (int)$request->input('metro_id');
        $data['messengers'] = json_encode($request->input('messengers') ?? []);
        $data['work_time_type'] = (int)$request->input('work_time_type');
        $data['work_time'] = json_encode($request->input('work_time') ?? []);
        $data['delete_code'] = $request->input('delete_code');
        $data['latitude'] = $request->input('latitude');
        $data['longitude'] = $request->input('longitude');
        $data['price_day_in_one'] = $request->input('price_day_in_one');
        $data['price_day_in_two'] = $request->input('price_day_in_two');
        $data['price_day_out_one'] = $request->input('price_day_out_one');
        $data['price_day_out_two'] = $request->input('price_day_out_two');
        $data['price_night_in_one'] = $request->input('price_night_in_one');
        $data['price_night_in_night'] = $request->input('price_night_in_night');
        $data['price_night_out_one'] = $request->input('price_night_out_one');
        $data['price_night_out_night'] = $request->input('price_night_out_night');
        $data['address'] = $request->input('address');
        $data['desc'] = $request->input('desc');

        $salon = Salon::create([
            'user_id' => $data['user_id'],
            'uniq_uid' => $data['uniq_uid'],
            'title' => $data['title'],
            'city_id' => $data['city_id'],
            'phone' => $data['phone'],
            'phone_one' => $data['phone_one'],
            'phone_two' => $data['phone_two'],
            'zone_id' => $data['zone_id'],
            'metro_id' => $data['metro_id'],
            'messengers' => $data['messengers'],
            'work_time_type' => $data['work_time_type'],
            'work_time' => $data['work_time'],
            'delete_code' => $data['delete_code'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'price_day_in_one' => $data['price_day_in_one'],
            'price_day_in_two' => $data['price_day_in_two'],
            'price_day_out_one' => $data['price_day_out_one'],
            'price_day_out_two' => $data['price_day_out_two'],
            'price_night_in_one' => $data['price_night_in_one'],
            'price_night_in_night' => $data['price_night_in_night'],
            'price_night_out_one' => $data['price_night_out_one'],
            'price_night_out_night' => $data['price_night_out_night'],
            'address' => $data['address'],
            'desc' => $data['desc'],
            'up_date' => Carbon::now(),
            'moderation_id' => 0,
            'moderator_id' => 0,
            'publish' => 0,
            'publish_date' => null,
        ]);

        $salon_id = $salon->id;

        $content_main = 'images/salon/' . $data['uniq_uid'] . '/main';
        $content_photo = 'images/salon/' . $data['uniq_uid'] . '/photo';

        //Main Photo
        $image_main = $this->getters->moveTempToFolder($data['image_main'], $content_main, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
        if ($image_main) {
            SalonContent::create(['salon_id' => $salon_id, 'user_id' => $data['user_id'], 'file' => $image_main, 'type' => 'main']);
        }

        //All Photo
        if (!empty($data['image_photo'])) {
            foreach ($data['image_photo'] as $photo) {
                $image_photo = $this->getters->moveTempToFolder($photo, $content_photo, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid']);
                if ($image_photo) {
                    SalonContent::create(['salon_id' => $salon_id, 'user_id' => $data['user_id'], 'file' => $image_photo, 'type' => 'photo']);
                }
            }
        }

        return redirect()->route('client.auth.salon')->with('success', __('admin/salon/salon.notify_created'));
    }
}
