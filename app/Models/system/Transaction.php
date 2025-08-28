<?php

namespace App\Models\system;

use App\Models\posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    protected $table = 'ex_transaction';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'type',
        'short',
        'pincode',
        'price',
        'post_id',
        'post_name',
        'user_id',
        'order_id',
        'order_status_id',
        'pay_id',
    ];

    public function setTransaction($type = null, $short = null, $pincode = null, $price = null, $post_id = null, $order_id = null, $order_status_id = null, $pay_id = null) {
        if ($post_id) {
            $post_name = Post::where('id', $post_id)->value('name');
        } else {
            $post_name = null;
        }
        Transaction::create([
            'type' => $type,
            'short' => $short,
            'pincode' => $pincode,
            'price' => $price,
            'post_id' => $post_id,
            'post_name' => $post_name,
            'user_id' => Auth::id() ?? null,
            'order_id' => $order_id,
            'order_status_id' => $order_status_id,
            'pay_id' => $pay_id,
        ]);
    }
}
