<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {
        $query = $request->input('query');
    
        // Tìm bài viết theo tiêu đề
        $postsByPost = Post::where('title', 'like', '%' . $query . '%')
                            ->orWhere('content', 'like', '%' . $query . '%')
                            ->pluck('id');
    
        // Tìm bài viết theo danh mục
        $postsByCategory = Category::where('name', 'like', '%'. $query .'%')
                                    ->with('posts')
                                    ->get()
                                    ->flatMap(function ($category) {
                                        return $category->posts->pluck('id'); // Lấy ID của bài post trong mỗi danh mục
                                    });
    
        // Tìm bài viết theo thẻ
        $postsByTag = Tag::where('name', 'like', '%'. $query .'%')
                            ->with('posts')
                            ->get()
                            ->flatMap(function ($tag) {
                                return $tag->posts->pluck('id'); // Lấy ID của bài post trong mỗi thẻ
                            });
    
        // Tổng hợp ID của tất cả bài viết và loại bỏ trùng lặp
        $allPostIds = $postsByPost->merge($postsByCategory)->merge($postsByTag)->unique();
    
        // Truy vấn tất cả bài viết có ID thuộc các tập hợp đã tìm được
        $posts = Post::whereIn('id', $allPostIds)->get();
    
        // Trả về kết quả tìm kiếm
        return view('web.pages.search', compact('posts', 'query'));
    }
}
