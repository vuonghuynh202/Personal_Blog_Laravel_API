<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateFormRequest;
use App\Http\Requests\User\UpdateFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'data' => $users,
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
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_confirmation' => $request->password_confirmation,
                'image' => $request->image,
                'role' => $request->role,
            ];
            $data['image'] = $data['image'] ?? null;
            $data['role'] = $data['role'] ?? 'user';

            if ($request->hasFile('image')) {
                $file = $request->image;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/UserImages', $filename);
                $data['image'] = Storage::url($path);
            }

            $user = User::create($data);

            return response()->json([
                'data' => $user,
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
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 404,
            ]);
        }

        return response()->json([
            'data' => $user,
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if (!empty($request->password)) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                $file = $request->image;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/UserImages', $filename);
                $data['image'] = Storage::url($path);
            }
            
            $user->update($data);

            return response()->json([
                'data' => $user,
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
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'message' => 'User deleted',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'User not found',
                'status' => 404,
            ]);
        }
    }
}
