<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all();
        return response()->json([
            'data' => $settings,
            'status' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
            ]);
            
            $setting = Setting::create($validated);

            return response()->json([
                'data' => $setting,
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
            $setting = Setting::findOrFail($id);
    
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
            ]);
    
            $setting->update([
                'key' => $validated['key'],
                'value' => $validated['value'],
            ]);
    
            return response()->json([
                'data' => $setting,
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
        $setting = Setting::findOrFail($id);
        if($setting) {
            $setting->delete();
            return response()->json([
                'message' => 'Setting deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Setting not found',
                'status' => 404
            ]);
        }
    }
}
