<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreateFormRequest;
use App\Http\Requests\Post\UpdateFormRequest;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'tags', 'categories', 'likes', 'comments'])->get();
        return response()->json([
            'data' => $posts,
            'status' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFormRequest $request)
    {
        try {
            $data = [
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'image' => $request->image,
                'is_featured' => $request->is_featured,
                'status' => $request->status ?? 'draft',
                'user_id' => Auth::id(),                
            ];

            if ($request->hasFile('image')) {
                $file = $request->image;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/PostImages', $filename);
                $data['image'] = Storage::url($path);
            }

            $post = Post::create($data);

            if (isset($request->category_ids)) {
                $post->categories()->attach($request->category_ids);
            }

            if(isset($request->tags)) {
                $tagIds = [];
                foreach($request->tags as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)],
                    );

                    $tagIds[] = $tag->id;
                }
                $post->tags()->attach($tagIds);
            }

            return response()->json([
                'data' => $post,
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
    public function show(Post $post)
    {
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
                'status' => 404,
            ]);
        }

        return response()->json([
            'data' => $post,
            'likes_count' => $post->likesCount(),
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);

            $data = [];
            if ($request->hasFile('image')) {

                if ($post->image && Storage::exists($post->image)) {
                    Storage::delete($post->image);
                }
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/PostImages', $filename);
                $data['image'] = Storage::url($path);
            }

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'slug' => $request->slug,
                'image' => $data['image'] ?? $post->image,
                'status' => $request->status,
                'is_featured' => $request->is_featured,
            ]);

            if (isset($request->category_ids)) {
                $post->categories()->sync($request->category_ids);
            }

            if (isset($request->tags)) {
                $tagIds = [];
                foreach ($request->tags as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
                    
                    $tagIds[] = $tag->id;
                }
                $post->tags()->sync($tagIds);
            }

            return response()->json([
                'data' => $post,
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
        $post = Post::find($id);
        if($post) {
            if($post->image) {
                $imagePath = str_replace('/storage/', 'public/', $post->image);
                Storage::delete($imagePath);
            }
            $post->delete();
            return response()->json([
                'message' => 'Post deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Post not found',
                'status' => 404,
            ]);
        }
    }
}
