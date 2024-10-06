<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CategoryRecursive;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() {
        return view('admin.menus.index');
    }

    public function edit($id) {
        $menu = Menu::findOrFail($id);
        $menus = Menu::where('id', '!=', $id)->get();
        $menusOption = new CategoryRecursive();
        $htmlOptions = $menusOption->displayCategoryOptions($menus, [$menu->parent_id]);
        return view('admin.menus.edit', compact('menu', 'htmlOptions'));
    }
}
