<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CategoryRecursive;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        return view('admin.categories.index');
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->get();
        $catsOption = new CategoryRecursive();
        $htmlOptions = $catsOption->displayCategoryOptions($categories, [$category->parent_id]);
        
        return view('admin.categories.edit', compact('category', 'htmlOptions'));
    }
}
