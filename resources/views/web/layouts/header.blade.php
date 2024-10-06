<header class="header_area">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="header-container box_1620">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="/"><img src="{{ asset('webTemplate/img/logo.png') }}" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav justify-content-center"></ul>

                    <ul class="nav navbar-nav navbar-right navbar-social">
                        <div class="icon-box d-flex">
                            <div class="btn-group dropdown dropleft">
                                <a data-toggle="dropdown" aria-expanded="false">
                                    <i class="ti-search"></i>
                                </a>
                                <div class="dropdown-menu border-0 search-box">
                                    <form action="{{ route('search') }}" method="get">
                                        <input type="text" name="query" placeholder="Tìm kiếm" autofocus>
                                        <button type="submit" class="search-button">Tìm</button>
                                    </form>
                                </div>
                            </div>
                            <div class="dropdown user-header">
                                <div id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-user"></i>
                                </div>
                                <div class="dropdown-menu user-box" aria-labelledby="dropdownMenuButton">
                                    @if($user)
                                        <a class="dropdown-item" href="{{ route('user.profile', $user->id) }}">{{ $user->name }}</a>
                                        {!! $user && $user->role == 'admin' ? 
                                            '<a class="dropdown-item" href="/admin" target="_blank">Trang quản trị</a>' : ''
                                        !!}
                                        <a class="dropdown-item text-danger" id="logout-button" href="#">Đăng Xuất</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('login') }}">Đăng Nhập</a>
                                        <a class="dropdown-item" href="{{ route('register') }}">Đăng Ký</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>