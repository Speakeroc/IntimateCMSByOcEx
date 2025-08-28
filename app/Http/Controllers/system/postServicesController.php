<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\posts\Post;
use App\Models\posts\Salon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class postServicesController extends Controller
{
    public function showPhone(Request $request): JsonResponse
    {
        $post_id = $request->input('post_id');
        $sessionKey = "show_phone_post_".$post_id;
        $response = ['uniq' => false, 'all' => false];
        $post = Post::find($post_id);
        if (!$request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, 1);
            if ($post) {
                $post->increment('views_phone_uniq');
                $post->increment('views_phone_all');
                $response['uniq'] = true;
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        } else {
            if ($post) {
                $post->increment('views_phone_all');
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        }
        return response()->json(['response' => $response]);
    }

    public function showSalonPhone(Request $request): JsonResponse
    {
        $salon_id = $request->input('salon_id');
        $sessionKey = "show_phone_salon_".$salon_id;
        $response = ['uniq' => false, 'all' => false];
        $post = Salon::find($salon_id);
        if (!$request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, 1);
            if ($post) {
                $post->increment('views_phone_uniq');
                $post->increment('views_phone_all');
                $response['uniq'] = true;
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        } else {
            if ($post) {
                $post->increment('views_phone_all');
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        }
        return response()->json(['response' => $response]);
    }

    public function transitionSocial(Request $request): JsonResponse
    {
        $post_id = $request->input('post_id');
        $type = $request->input('type');

        if (!in_array($type, ['telegram', 'whatsapp', 'instagram', 'polee'])) {
            return response()->json(['message' => 'Post not found']);
        }

        $sessionKey = "transition_post_".$type."_".$post_id;
        $response = ['uniq' => false, 'all' => false];
        $post = Post::find($post_id);
        if (!$request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, 1);
            if ($post) {
                $post->increment('transition_'.$type.'_uniq');
                $post->increment('transition_'.$type.'_all');
                $response['uniq'] = true;
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        } else {
            if ($post) {
                $post->increment('transition_'.$type.'_all');
                $response['all'] = true;
            } else {
                return response()->json(['message' => 'Post not found']);
            }
        }
        return response()->json(['response' => $response]);
    }
}
