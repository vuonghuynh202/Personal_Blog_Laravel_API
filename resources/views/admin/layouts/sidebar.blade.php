<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.index') }}">
                <i class="mdi mdi-chart-bar menu-icon"></i>
                <span class="menu-title">Bảng điều khiển</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categories.index') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Danh mục</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#post-collapse" aria-expanded="false" aria-controls="post-collapse">
                <i class="menu-icon mdi mdi-book-open"></i>
                <span class="menu-title">Bài viết</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="post-collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Tất cả bài viết</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.create') }}">Thêm bài viết mới</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tags.index') }}">
                <i class="mdi mdi-tag menu-icon"></i>
                <span class="menu-title">Thẻ</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('menus.index') }}">
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                <span class="menu-title">Menu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Cài đặt</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#user-collapse" aria-expanded="false" aria-controls="user-collapse">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">Người dùng</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="user-collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Tất cả người dùng</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.create') }}">Thêm người dùng mới</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>