<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($postId) {
        try {
            $userId = Auth::id();
            $existingLike = Like::where('user_id', $userId)
                                ->where('post_id', $postId)
                                ->first();
    
            if(!$existingLike) {
                Like::create([
                    'user_id' => $userId,
                    'post_id' => $postId,
                ]);
    
                return response()->json([
                    'message' => 'liked',
                    'status' => 201,
                ]);
            }

            return response()->json([
                'message' => 'Already liked',
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unlike($postId) {
        $userId = Auth::id();

        $like = Like::where('user_id', $userId)
                    ->where('post_id', $postId)
                    ->first();

        if($like) {
            $like->delete();

            return response()->json([
                'message' => 'Unliked',
                'status' => 200,
            ]);
        }

        return response()->json([
            'message' => 'Not liked yet',
            'status' => 404,
        ]);
    }
    
}
