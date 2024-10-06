<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function postsTag($slug) {
        $tag = Tag::where('slug', $slug)->first();
        return view('web.pages.postsTag', compact('tag'));
    }
}
