<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\location\City;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class salonController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->user = new Users;
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index()
    {
        $data['title'] = __('catalog/id/salon.page_main');
        $this->getters->setMetaInfo(title: $data['title'], url: route('client.auth.salon'));

        $app_image_settings = app('image_settings');

        $data['data'] = Salon::where('user_id', Auth::id())->orderByDesc('id')->paginate(24);
        $post_positions = Salon::where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->pluck('id');

        $data['items'] = [];
        $data['salon_activation_status'] = $this->getters->getSetting('salon_activation_status');

        foreach ($data['data'] as $item) {
            $image = $this->getters->getSalonMainImage($item['id']);
            if (!empty($image) && File::exists(public_path($image))) {
                $image = url($this->imageConverter->toMini($image, height: $app_image_settings['posts']['in_account']));
            } else {
                $image = url('no_image_round.png');
            }

            $position = $post_positions->search($item['id']);
            if ($position !== false) {
                $position++;
            } else {
                $position = null;
            }

            $content_types = $this->getters->getPluckSalon_P__count($item['id']);
            $content_values = array_count_values($content_types);
            $moderation_status = app('moderation_status');
            $salon_activation_status = $this->getters->getSetting('salon_activation_status');

            $publish_class = 'publish_off';
            if ($item['moderation_id'] === 0) {
                $publish_action = false;
                $publish_text = collect($moderation_status)->firstWhere('id', $item['moderation_id'])['title'] ?? '';
            } elseif ($item['moderation_id'] === 1) {
                $publish_action = true;
                if ($item['publish'] === 1) {
                    $publish_text = __('catalog/id/post.publish_on');
                } else {
                    $publish_text = collect($moderation_status)->firstWhere('id', $item['moderation_id'])['title'] ?? '';
                }
                $publish_class = 'publish_on';
            } elseif ($item['moderation_id'] === 2) {
                $publish_action = false;
                $publish_text = collect($moderation_status)->firstWhere('id', $item['moderation_id'])['title'] ?? '';
            } elseif ($item['moderation_id'] === 3) {
                $publish_action = false;
                $publish_text = collect($moderation_status)->firstWhere('id', $item['moderation_id'])['title'] ?? '';
            } else {
                $publish_action = false;
                $publish_text = 'Неизвестный статус';
            }

            $data['items'][] = [
                'id' => $item['id'],
                'link' => route('client.auth.salon.update', ['id' => $item['id']]),
                'image' => $image,
                'photo' => in_array('photo', $content_types),
                'photo_count' => $content_values['photo'] ?? 0,
                'title' => $item['title'],
                'city' => ($item['city_id']) ? $this->getters->getCityInfo($item['city_id'],false) : null,
                'zone' => ($item['zone_id']) ? $this->getters->getZoneInfo($item['zone_id']) : null,
                'publish_date' => ($salon_activation_status && $item['publish'] && $item['moderation_id'] == 1) ? __('catalog/id/post.status_activation', ['date' => date('d.m.Y', strtotime($item['publish_date']))]) : null,
                'post_action' => $publish_action,
                'publish_class' => $publish_class,
                'publish_text' => $publish_text,
                'position' => $position,
                'moderation_id' => $item['moderation_id'],
                'moderation_text' => $item['moderation_text'],
                'moderation_status' => $moderation_status,
                'publish' => $item['publish'],
                'created' => $this->getters->dateText($item['created_at']),
                'updated' => $this->getters->dateText($item['updated_at']),
            ];
        }

        $breadcrumbs = [
            ['link' => route('client.auth.salon'), 'title' => __('catalog/id/salon.page_main')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/id/salon/list', ['data' => $data]);
    }

    public function delSalonById(Request $request): JsonResponse
    {

        $id = $request->input('id');

        if (empty($id)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/post.notify_delete_empty')]);
        } else {
            $is_my = Salon::where('id', $id)->where('user_id', Auth::id())->count();
            if (!$is_my) {
                return response()->json(['status' => 'error', 'message' => __('catalog/id/post.notify_delete_other')]);
            }
        }

        $post = Salon::where('id', $id)->first();

        //Delete content folder
        if ($post && File::exists(public_path('/images/posts/' . $post->uniq_uid))) {
            File::deleteDirectory(public_path('/images/posts/' . $post->uniq_uid));
        }
        Salon::where('id', $id)->where('user_id', Auth::id())->delete();
        SalonContent::where('salon_id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'success', 'message' => __('admin/salon/salon.notify_deleted')]);
    }
}
