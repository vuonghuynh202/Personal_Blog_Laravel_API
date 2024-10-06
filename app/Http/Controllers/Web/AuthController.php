<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view('web.pages.login');
    }

    public function register() {
        return view('web.pages.register');
    }

    public function userProfile($id) {
        $profile = User::findOrFail($id);
        return view('web.pages.userProfile', compact('profile'));
    }
}