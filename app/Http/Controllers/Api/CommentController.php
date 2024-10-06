<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)
                          ->whereNull('parent_id')
                          ->with(['replies', 'user'])
                          ->get();

        return response()->json([
            'data' => $comments,
            'status' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'parent_id' => 'nullable|exists:comments,id',
            ]);

            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment && $parentComment->parent_id !== null) {
                $validated['parent_id'] = $parentComment->parent_id;
            }

            $comment = Comment::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
                'content' => $validated['content'],
                'parent_id' => $validated['parent_id'] ?? null,
            ]);

            return response()->json([
                'data' => $comment,
                'status' => 201,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            if ($comment->user_id !== Auth::id()) {
                return response()->json('Unauthorized', 403);
            }

            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment->update([
                'content' => $validated['content'],
            ]);

            return response()->json([
                'data' => $comment,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id == Auth::id() || Auth::user()->role == 'admin') {

            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted',
                'status' => 200,
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized',
            'status' => 403,
        ]);
    }
}