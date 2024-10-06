<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        $availableSettings = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
            'tiktok' => 'Tiktok',
            'threads' => 'Threads',
            'youtube' => 'Youtube',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
        ];
         // Lấy danh sách các key đã tồn tại từ cơ sở dữ liệu
        $existingKeys = Setting::pluck('key')->toArray();

        // Loại bỏ các key đã tồn tại khỏi danh sách các key có sẵn
        $filteredSettings = array_diff_key($availableSettings, array_flip($existingKeys));

    return view('admin.settings.index', compact('filteredSettings'));
    }
}
