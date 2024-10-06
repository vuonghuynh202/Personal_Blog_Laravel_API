<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\CreateFormRequest;
use App\Http\Requests\Tag\UpdateFormRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::with('posts')->get();
        return response()->json([
            'data' => $tags,
            'status' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFormRequest $request)
    {
        try {
            $tag = Tag::create([
                'name' => $request->name,
                'slug' => $request->slug,
            ]);
    
            return response()->json([
                'data' => $tag,
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
        $tag = Tag::with(['posts', 'posts.user', 'posts.categories'])->findOrFail($id);
        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        return response()->json([
            'data' => $tag,
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $tag = Tag::findOrFail($id);
    
            $tag->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);
    
            return response()->json([
                'data' => $tag,
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
        $tag = Tag::findOrFail($id);
        if($tag) {
            $tag->delete();
            return response()->json([
                'message' => 'Tag deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Tag not found',
                'status' => 404,
            ]);
        }
    }
}
