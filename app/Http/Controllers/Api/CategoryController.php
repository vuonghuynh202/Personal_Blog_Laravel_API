<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateFormRequest;
use App\Http\Requests\Category\UpdateFormRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['children', 'posts'])->whereNull('parent_id')->get();
        return response()->json([
            'data' => $categories,
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
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
            ];

            if ($request->hasFile('image')) {
                $file = $request->image;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/CategoryImages', $filename);
                $data['image'] = Storage::url($path);
            }

            $category = Category::create($data);

            return response()->json([
                'data' => $category,
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
        $category = Category::with(['children', 'posts', 'posts.user'])->findOrFail($id);
        if(!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        return response()->json([
            'data' => $category,
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
            ];
    
            if ($request->hasFile('image')) {
    
                if ($category->image && Storage::exists($category->image)) {
                    Storage::delete($category->image);
                }
    
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/CategoryImages', $filename);
                $data['image'] = Storage::url($path);
            }
    
            $category->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'parent_id' => $data['parent_id'] ?? null,
                'image' => $data['image'] ?? $category->image,
            ]);
    
            return response()->json([
                'data' => $category,
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
        $category = Category::findOrFail($id);
        if($category) {
            if($category->image) {
                $imagePath = str_replace('/storage/', 'public/', $category->image);
                Storage::delete($imagePath);
            }
            $category->delete();
            return response()->json([
                'message' => 'Category deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found',
                'status' => 404,
            ]);
        }
    }
}
