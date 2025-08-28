<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    use HasFactory;

    protected $table = 'ex_post_review';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'text',
        'rating',
        'post_id',
        'user_id',
        'moderation_id',
        'moderator_id',
        'publish',
    ];
}
