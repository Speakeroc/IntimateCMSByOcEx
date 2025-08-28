<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model {
    use HasFactory;

    protected $table = 'ex_salon';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'uniq_uid',
        'user_id',
        'title',
        'phone',
        'phone_one',
        'phone_two',
        'messengers',
        'address',
        'desc',
        'up_date',
        'price_day_in_one',
        'price_day_in_two',
        'price_day_out_one',
        'price_day_out_two',
        'price_night_in_one',
        'price_night_in_night',
        'price_night_out_one',
        'price_night_out_night',
        'city_id',
        'zone_id',
        'metro_id',
        'latitude',
        'longitude',
        'work_time_type',
        'work_time',
        'moderation_id',
        'moderator_id',
        'moderation_text',
        'delete_code',
        'publish',
        'publish_date',
        'views_salon_uniq',
        'views_salon_all',
        'views_phone_uniq',
        'views_phone_all',
    ];

    public function getMainImage($post_id) {
        $image = SalonContent::where('salon_id', $post_id)->where('type', 'main')->first();
        return $image->file ?? null;
    }

    public function viewsSalon($salon_id)
    {
        $sessionKey = "show_salon_{$salon_id}";
        $post = Salon::find($salon_id);
        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, 1);
            if ($post) {
                $post->increment('views_salon_uniq');
                $post->increment('views_salon_all');
            }
        } else {
            if ($post) {
                $post->increment('views_salon_all');
            }
        }
    }
}

