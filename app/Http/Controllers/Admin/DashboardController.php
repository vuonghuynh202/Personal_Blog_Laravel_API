<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $quantity = [];
        $quantity['posts'] = Post::count();
        $quantity['cats'] = Category::count();
        $quantity['tags'] = Tag::count();
        $quantity['users'] = User::count();
        $quantity['comments'] = Comment::count();
        $quantity['likes'] = Like::count();


        // Lấy số lượng truy cập cho 7 ngày gần nhất
        $visits = Visit::selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->where('visited_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chuyển đổi dữ liệu thành định dạng để hiển thị trên biểu đồ
        $labels = [];
        $data = [];

        // Điền dữ liệu vào labels và data
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels[] = $date;

            // Tìm số lượng truy cập cho ngày hiện tại
            $visitCount = $visits->where('date', $date)->first();
            $data[] = $visitCount ? $visitCount->count : 0;
        }

        return view('admin.index', compact('labels', 'data', 'quantity'));
    }
}