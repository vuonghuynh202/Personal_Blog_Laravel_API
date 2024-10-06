<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function details($slug) {
        $post = Post::where('slug', $slug)->first();

        $relatedPosts = collect();
        $categories = $post->categories;
        foreach($categories as $category) {
            $relatedPosts = $relatedPosts->merge($category->posts->where('id', '!=', $post->id));
        }
        $relatedPosts = $relatedPosts->unique('id');
        
        return view('web.pages.postDetails', compact('post', 'relatedPosts'));
    }
}
