<?php

namespace App\Http\Controllers\admin\salon;

use App\Http\Controllers\Controller;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class salonController extends Controller
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

    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/salon')) return $redirect;
        $this->getters->setSEOTitle('salon');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getMainSalonData();
        $data['filtered'] = 0;
        $data['salon_id'] = $request->input('salon_id') ?? null;
        $data['phone'] = $request->input('phone') ?? null;
        $data['title'] = $request->input('name') ?? null;
        $data['publish'] = $request->input('publish') ?? null;
        $data['city_id'] = $request->input('city_id') ?? null;
        $data['user_id'] = $request->input('user_id') ?? null;
        $query = Salon::query();
        if ($data['salon_id'] != null) {
            $query->where('id', $data['salon_id']);
        }
        if ($data['phone'] != null) {
            $query->where('phone', 'like', '%' . $data['phone'] . '%');
            $data['filtered']++;
        }
        if ($data['phone'] != null) {
            $query->where('phone_one', 'like', '%' . $data['phone'] . '%');
            $data['filtered']++;
        }
        if ($data['phone'] != null) {
            $query->where('phone_two', 'like', '%' . $data['phone'] . '%');
            $data['filtered']++;
        }
        if ($data['title'] != null) {
            $query->where('title', 'like', '%' . $data['title'] . '%');
            $data['filtered']++;
        }
        if ($data['publish'] == 'yes') {
            $query->where('publish', 1);
            $data['filtered']++;
        }
        if ($data['publish'] == 'no') {
            $query->where('publish', 0);
            $data['filtered']++;
        }
        if ($data['city_id'] != null) {
            $query->where('city_id', $data['city_id']);
            $data['filtered']++;
        }
        if ($data['user_id'] != null) {
            $query->where('user_id', '=', $data['user_id']);
            $data['filtered']++;
        }
        $query->orderByDesc('created_at');

        $data['data'] = $query->paginate($paginate)->appends([
            'salon_id' => $data['salon_id'],
            'phone' => $data['phone'],
            'publish' => $data['publish'],
            'city_id' => $data['city_id'],
            'user_id' => $data['user_id'],
        ]);

        //Users
        $users = Salon::distinct()->orderBy('user_id', 'asc')->pluck('user_id');
        $data['users'] = [];
        foreach ($users as $user) {
            $salons = Salon::where('user_id', $user)->count();
            $login = Users::where('id', $user)->value('login');
            $salonText = trans_choice('admin/salon/salon.salon_choice', $salons, ['num' => $salons]);
            $data['users'][] = ['user_id' => $user, 'name' => $login . ' - ' . $salonText, 'count' => $login];
        }

        //Sorting by field 'count'
        usort($data['users'], function ($a, $b) {
            return $a['count'] <=> $b['count'];
        });
        //Users

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

        return view('admin/salon/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_add')) return $redirect;
        $this->getters->setSEOTitle('salon_add');

        $data = [
            //Get Data
            'this_user_id' => Auth::id(),
            'main_data' => $this->getters->getMainSalonData(),
            'currency_symbol' => $this->getters->getCurrencySymbol(),
            'salon_activation_status' => $this->getters->getSetting('salon_activation_status'),
            'elements' => $this->getters->getAdminHeaderFooter(),
        ];
        return view('admin/salon/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_add')) return $redirect;
        $this->validate($request, [
            'images.main' => 'required',
            'user_id' => 'required|integer',
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
            'publish' => 'required',
        ]);

        $data['user_id'] = (int)$request->input('user_id');
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
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');
        $data['publish'] = $request->input('publish');
        $data['publish_date'] = $request->input('publish_date') ?? null;

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
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'moderator_id' => Auth::id(),
            'publish' => $data['publish'],
            'publish_date' => $data['publish_date'] ?? Carbon::now(),
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

        return redirect()->route('salon.index')->with('success', __('admin/salon/salon.notify_created'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_edit')) return $redirect;
        $this->getters->setSEOTitle('salon_edit');

        //Info
        $item = Salon::where('id', $id)->first();
        $item_content = SalonContent::where('salon_id', $id)->get();

        $data = [
            'id' => $id,
            'user_id' => $item['user_id'],
            'uniq_uid' => $item['uniq_uid'],
            'title' => $item['title'],
            'city_id' => $item['city_id'],
            'phone' => $item['phone'] ?? '---',
            'phone_one' => $item['phone_one'] ?? '---',
            'phone_two' => $item['phone_two'] ?? '---',
            'zone_id' => $item['zone_id'],
            'metro_id' => $item['metro_id'],
            'messengers' => json_decode($item['messengers'], true),
            'work_time_type' => $item['work_time_type'],
            'work_time' => json_decode($item['work_time'], true),
            'delete_code' => $item['delete_code'],
            'latitude' => $item['latitude'],
            'longitude' => $item['longitude'],
            'price_day_in_one' => $item['price_day_in_one'],
            'price_day_in_two' => $item['price_day_in_two'],
            'price_day_out_one' => $item['price_day_out_one'],
            'price_day_out_two' => $item['price_day_out_two'],
            'price_night_in_one' => $item['price_night_in_one'],
            'price_night_in_night' => $item['price_night_in_night'],
            'price_night_out_one' => $item['price_night_out_one'],
            'price_night_out_night' => $item['price_night_out_night'],
            'address' => $item['address'],
            'desc' => $item['desc'],
            'up_date' => date('Y-m-d', strtotime($item['up_date'])),
            'moderation_id' => $item['moderation_id'],
            'moderation_text' => $item['moderation_text'],
            'moderator_id' => $item['moderator_id'],
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

        return view('admin/salon/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_edit')) return $redirect;
        $this->validate($request, [
            'images.main' => 'required',
            'user_id' => 'required|integer',
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
            'publish' => 'required',
        ]);

        $old_salon_info = Salon::where('id', $id)->first();

        $data['user_id'] = (int)$request->input('user_id');
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
        $data['up_date'] = $request->input('up_date');
        $data['moderation_id'] = $request->input('moderation_id');
        $data['moderation_text'] = $request->input('moderation_text');
        $data['publish'] = $request->input('publish');
        $data['publish_date'] = $request->input('publish_date') ?? null;

        Salon::where('id', '=', $id)->update([
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
            'up_date' => ($data['up_date']) ? Carbon::now()->format('Y-m-d\TH:i') : date('Y-m-d H:i', strtotime($old_salon_info['up_date'])),
            'moderator_id' => Auth::id(),
            'moderation_id' => $data['moderation_id'],
            'moderation_text' => $data['moderation_text'],
            'publish' => $data['publish'],
            'publish_date' => $data['publish_date'] ?? Carbon::now(),
        ]);

        $content_main = 'images/salon/' . $data['uniq_uid'] . '/main';
        $content_photo = 'images/salon/' . $data['uniq_uid'] . '/photo';

        //Delete all content
        SalonContent::where('salon_id', $id)->delete();

        //Main Photo
        $image_main = $this->getters->moveTempToFolder($data['image_main'], $content_main, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
        if ($image_main) {
            SalonContent::create(['salon_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_main, 'type' => 'main']);
        }

        //All Photo
        if (!empty($data['image_photo'])) {
            foreach ($data['image_photo'] as $photo) {
                $image_photo = $this->getters->moveTempToFolder($photo, $content_photo, rand(199, 9999) . '_' . $data['user_id'] . '_' . $data['uniq_uid'], true);
                if ($image_photo) {
                    SalonContent::create(['salon_id' => $id, 'user_id' => $data['user_id'], 'file' => $image_photo, 'type' => 'photo']);
                }
            }
        }

        //Delete old folder
        $directoryPath = public_path('images/salon/' . $old_salon_info->uniq_uid);
        if (File::exists($directoryPath)) {
            File::deleteDirectory($directoryPath);
        }
        return redirect()->route('salon.index')->with('success', __('admin/salon/salon.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/salon_delete')) return $redirect;
        $salon = Salon::where('id', $id)->first();

        //Delete content folder
        if ($salon && File::exists(public_path('/images/salon/' . $salon->uniq_uid))) {
            File::deleteDirectory(public_path('/images/salon/' . $salon->uniq_uid));
        }

        Salon::where('id', $id)->delete();
        SalonContent::where('salon_id', $id)->delete();

        return redirect()->route('salon.index')->with('success', __('admin/salon/salon.notify_deleted'));
    }

    public function massDelete(Request $request): JsonResponse
    {
        if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/post_delete')) return response()->json(['status' => 'success', 'message' => 'Доступ запрещен']);
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            foreach ($selectedIds as $selectedId) {
                $salon = Salon::where('id', $selectedId)->first();

                //Delete content folder
                if ($salon && File::exists(public_path('/images/salon/' . $salon->uniq_uid))) {
                    File::deleteDirectory(public_path('/images/salon/' . $salon->uniq_uid));
                }

                Salon::where('id', $selectedId)->delete();
                SalonContent::where('salon_id', $selectedId)->delete();
            }
            return response()->json(['status' => 'success', 'message' => __('lang.notify_selected_deleted')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Не выбраны записи для удаления.'], 400);
    }
}
