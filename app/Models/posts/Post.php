<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model {
    use HasFactory;

    protected $table = 'ex_post';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'uniq_uid',
        'user_id',
        'salon_id',
        'category_ids',
        's_individuals',
        's_premium',
        's_health',
        's_elite',
        's_bdsm',
        's_masseuse',
        'section_8',
        'section_9',
        'section_10',
        'name',
        'age',
        'phone',
        'messengers',
        'tags',
        'description',
        'verify',
        'diamond',
        'diamond_date',
        'vip',
        'vip_date',
        'color',
        'color_date',
        'up_date',
        'price_day_in_one',
        'price_day_in_two',
        'price_day_out_one',
        'price_day_out_two',
        'price_night_in_one',
        'price_night_in_night',
        'price_night_out_one',
        'price_night_out_night',
        'express',
        'express_price',
        'city_id',
        'zone_id',
        'metro_id',
        'latitude',
        'longitude',
        'language_skills',
        'client_age',
        'hair_color',
        'nationality',
        'body_type',
        'hairy',
        'cloth',
        'shoes',
        'height',
        'weight',
        'breast',
        'call_time_type',
        'call_time',
        'body_art',
        'visit_places',
        'services',
        'services_for',
        'moderation_id',
        'moderator_id',
        'moderation_text',
        'delete_code',
        'user_publish',
        'publish',
        'publish_date',
        'views_post_uniq',
        'views_post_all',
        'views_phone_uniq',
        'views_phone_all',
        'transition_telegram_uniq',
        'transition_telegram_all',
        'transition_whatsapp_uniq',
        'transition_whatsapp_all',
        'transition_instagram_uniq',
        'transition_instagram_all',
        'transition_polee_uniq',
        'transition_polee_all',
    ];

    public function main_image(): HasMany
    {
        return $this->hasMany(PostContent::class, 'post_id')->where('type', 'main');
    }

    public function photo(): HasMany
    {
        return $this->hasMany(PostContent::class, 'post_id')->where('type', 'photo');
    }

    public function selfie(): HasMany
    {
        return $this->hasMany(PostContent::class, 'post_id')->where('type', 'selfie');
    }

    public function video(): HasMany
    {
        return $this->hasMany(PostContent::class, 'post_id')->where('type', 'video');
    }

    public function verify(): HasMany
    {
        return $this->hasMany(PostContent::class, 'post_id')->where('type', 'verify');
    }

    public function viewsPost($post_id)
    {
        $sessionKey = "show_post_{$post_id}";
        $post = Post::find($post_id);
        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, 1);
            if ($post) {
                $post->increment('views_post_uniq');
                $post->increment('views_post_all');
            }
        } else {
            if ($post) {
                $post->increment('views_post_all');
            }
        }
    }
}

