<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Http\Requests\Menu\UpdateFormRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        return response()->json([
            'data' => $menus,
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
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'status' => $request->status,
            ];
    
            $menu = Menu::create($data);
    
            return response()->json([
                'data' => $menu,
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
        $menu = Menu::with('children')->findOrFail($id);
        if(!$menu) {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }

        return response()->json([
            'data' => $menu,
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            if(!$menu) {
                return response()->json([
                    'message' => 'Menu not found'
                ], 404);
            }

            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'status' => $request->status,
            ];
    
            $menu->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'url' => $data['url'],
                'parent_id' => $data['parent_id'] ?? $menu->parent_id,
                'order' => $data['order'] ?? 0,
                'status' => $data['status'] ?? true,
            ]);
    
            return response()->json([
                'data' => $menu,
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
        $menu = Menu::findOrFail($id);
        if($menu) {
            $menu->delete();
            return response()->json([
                'message' => 'Menu deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Menu not found',
                'status' => 404,
            ]);
        }
    }
}
