<?php

namespace App\Models\system;

use App\Models\posts\Post;
use App\Models\posts\PostBanner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostBannerData extends Model
{
    private Getters $getters;

    private array $post_banner_data;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->default_data = ['count' => 0, 'status' => 0, 'sort_order' => 0];
        $this->post_banner_data = $this->getters->getSetting('home_post_banner') ?? $this->default_data;
    }

    public function getPostBanner(): array
    {
        if (!$this->post_banner_data['status']) {
            return [];
        }
        $template = 'post_banner';
        $items = PostBanner::where('activation', 1)->where('activation_date', '>', Carbon::now())->where('banner', '!=', null)->orderByDesc('up_date')->limit($this->post_banner_data['count'])->get();

        $data = [];

        foreach ($items as $item) {
            $post_id = $item['post_id'];
            $post_info = Post::where('id', $post_id)->first();

            if (!$post_info) {
                continue;
            }

            $name = $post_info['name'] ?? null;
            $age = $post_info['age'] ?? null;

            if (!empty($item['link'])) {
                $link = $item['link'];
            } else {
                $link = route('client.post', ['post_id' => $post_id, 'name' => Str::slug($name)]);
            }

            $banner = [
                'id' => $item['id'],
                'banner' => url($item['banner']),
                'link' => $link,
                'name' => $name,
                'age' => trans_choice(__('choice.age'), $age, ['num' => $age]),
                'post' => $item['post_id'],
            ];

            if ($post_info['moderation_id'] == 1 && $post_info['publish'] == 1) {
                $data[] = view('catalog/posts/include/'.$template, ['data' => $banner]);
            } elseif (!empty($item['link'])) {
                $data[] = view('catalog/posts/include/'.$template, ['data' => $banner]);
            }
        }

        return $data;
    }
}
