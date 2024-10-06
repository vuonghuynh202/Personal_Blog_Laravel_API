<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function postsCategory($slug) {
        $category = Category::where('slug', $slug)->first();
        return view('web.pages.postsCategory', compact('category'));
    }
}
