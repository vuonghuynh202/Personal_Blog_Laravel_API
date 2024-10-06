<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CategoryRecursive;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return view('admin.posts.index');
    }

    public function create() {
        $categories = Category::all();
        $catsOption = new CategoryRecursive();
        $htmlOptions = $catsOption->displayCategoryOptions($categories, []);
        $tags = Tag::all();
        return view('admin.posts.create', compact('htmlOptions', 'tags'));
    }

    public function edit($id) {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $catsOption = new CategoryRecursive();
        $htmlOptions = $catsOption->displayCategoryOptions($categories, $post->categories->pluck('id')->toArray());
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'htmlOptions', 'tags'));
    }
}
