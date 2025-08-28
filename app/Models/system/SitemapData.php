<?php

namespace App\Models\system;

use App\Models\posts\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SitemapData extends Model
{
    public function getPagesLastModify($type, $tag_id = null, $service_id = null): ?string
    {
        //Sections
        if ($type == 'all') {
            $item = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'popular') {
            $item = Post::where('s_masseuse', 0)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('views_post_uniq')->first();
        }
        if ($type == 'latest') {
            $item = Post::where('moderation_id', 1)->where('publish', 1)->orderByDesc('created_at')->first();
        }
        if ($type == 'elite') {
            $item = Post::where('s_masseuse', 0)->where('s_elite', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'individual') {
            $item = Post::where('s_masseuse', 0)->where('s_individuals', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'premium') {
            $item = Post::where('s_masseuse', 0)->where('s_premium', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'health') {
            $item = Post::where('s_masseuse', 0)->where('s_health', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'masseuse') {
            $item = Post::where('s_masseuse', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }
        if ($type == 'bdsm') {
            $item = Post::where('s_masseuse', 0)->where('s_bdsm', 1)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }

        //Tags
        if ($type == 'tag' && !empty($tag_id)) {
            $item = Post::whereJsonContains("tags", (string)$tag_id)->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }

        //Service
        if ($type == 'service' && !empty($service_id)) {
            $item = Post::whereRaw("JSON_EXTRACT(services, '$.\"$service_id\".condition') IN (1, 2, 3)")->where('moderation_id', 1)->where('publish', 1)->orderByDesc('up_date')->first();
        }

        if (empty($item)) {
            $item = Post::orderBy('created_at')->first();
        }

        if (isset($item) && !empty($item['updated_at'])) {
            $date = Carbon::parse($item['updated_at']);
            return $date->toAtomString();
        } else {
            $date = Carbon::now()->previous(Carbon::MONDAY)->setTime(12, 0, 0);
            return $date->toAtomString();
        }
    }
}
