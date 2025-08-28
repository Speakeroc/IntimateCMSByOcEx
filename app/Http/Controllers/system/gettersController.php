<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Post;
use App\Models\system\Getters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class gettersController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function clearCacheType(Request $request): JsonResponse
    {
        $typeLink = $request->input('type_link');
        if ($typeLink == 'webp') {
            return $this->getters->clearFolder(public_path('images/cache'));
        } elseif ($typeLink == 'temp') {
            return $this->getters->clearFolder(public_path('images/temp'));
        } elseif ($typeLink == 'logs') {
            return $this->getters->clearFolder(storage_path('logs'));
        } else {
            return response()->json(['message' => 'Error clear cache request']);
        }
    }

    public function getZoneMetro(Request $request): JsonResponse
    {
        $city_id = $request->input('city_id') ?? null;
        $post_id = $request->input('post_id');

        $old_zone_id = old('zone_id');
        $old_metro_id = old('metro_id');

        if ($post_id) {
            $post = Post::where('id', $post_id)->first();
        }

        $zone = [];
        $metro = [];

        if ($city_id) {
            $get_zone = Zone::where('city_id', $city_id)->where('status', 1)->get();
            foreach ($get_zone as $item) {
                $zone[] = ['id' => $item['id'], 'title' => $item['title'], 'selected' => ((isset($post['zone_id']) && !empty($post['zone_id']) && $post['zone_id'] == $item['id']) || (isset($old_zone_id) && !empty($old_zone_id) && $old_zone_id == $item['id'])) ? '1' : '0'];
            }

            $get_metro = Metro::where('city_id', $city_id)->where('status', 1)->get();
            foreach ($get_metro as $item) {
                $metro[] = ['id' => $item['id'], 'title' => $item['title'], 'selected' => ((isset($post['metro_id']) && !empty($post['metro_id']) && $post['metro_id'] == $item['id']) || (isset($old_metro_id) && !empty($old_metro_id) && $old_metro_id == $item['id'])) ? '1' : '0'];
            }
        }

        usort($zone, function($a, $b) {
            return strcasecmp($a['title'], $b['title']);
        });

        usort($metro, function($a, $b) {
            return strcasecmp($a['title'], $b['title']);
        });

        if (!empty($zone)) {
            $zone[] = ['id' => "", 'title' => __('lang.no_select')];
            $lastElement = array_pop($zone);
            array_unshift($zone, $lastElement);
        }

        if (!empty($metro)) {
            $metro[] = ['id' => "", 'title' => __('lang.no_select')];
            $lastElement = array_pop($metro);
            array_unshift($metro, $lastElement);
        }

        return response()->json(['zone' => $zone, 'metro' => $metro]);
    }
}
