@extends('web.partials.master')

@section('title')
<title>{{ $profile->name }}</title>
@endsection

@section('content')
<main class="site-main">
    <!--================Hero Banner end =================-->

    <!--================ Start Blog Post Area =================-->
    <section class="blog-post-area section-margin mt-4">
        <div class="container">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="user-profile-box">
                                <div class="user-profile">
                                    <div class="user-img">
                                        <img id="profile-user-img" src="" alt="">
                                    </div>
                                    <div class="user-info">
                                        <a>
                                            <h2 id="profile-user-name"></h2>
                                        </a>
                                        @if($user && $user->id == $profile->id)
                                        <a href="" class="ml-3 text-dark" data-toggle="modal" data-target="#exampleModal">
                                            <i class="ti-pencil-alt"></i>
                                        </a>
                                        @endif
                                        @if($profile->role == 'admin')
                                        <span class="d-block">{{ $profile->posts->count() }} Bài viết</span>
                                        @endif


                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form class="forms-sample" id="form-update" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa thông tin</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" id="user-id" value="{{ $profile->id }}">
                                                            <input type="hidden" name="email" id="user-email" value="{{ $profile->email }}">
                                                            <input type="hidden" name="role" id="user-role" value="{{ $profile->role }}">
                                                            <div class="form-group">
                                                                <label for="menu-name">Tên người dùng</label>
                                                                <input type="text" name="name" class="form-control" id="user-name" value="{{ $profile->name }}">
                                                                <p class="text-danger"></p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="user-file">Ảnh đại diện</label>
                                                                <input type="file" name="image" class="form-control-file" id="user-file">
                                                                <p class="text-danger"></p>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="user-form-image">
                                                                    <img src="{{ $profile->image ?? '/storage/DefaultImages/avatar-default.png' }}" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="cancel-reply" data-dismiss="modal">Huỷ</button>
                                                            <button type="submit" class="button reply-submit-button">Cập Nhật</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="user-profile-posts">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @if($profile->role == 'admin')
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="posted-tab" data-toggle="tab" data-target="#posted" type="button" role="tab" aria-controls="posted" aria-selected="false">Bài viết đã đăng</button>
                                    </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $profile->role != 'admin' ? 'active' : '' }}" id="liked-tab" data-toggle="tab" data-target="#liked" type="button" role="tab" aria-controls="liked" aria-selected="false">Bài viết đã thích</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-4" id="myTabContent">
                                    <div class="tab-pane fade {{ $profile->role == 'admin' ? 'active show' : '' }}" id="posted" role="tabpanel" aria-labelledby="posted-tab">
                                        <div id="post-container">
                                            @foreach($profile->posts as $post)
                                            <div class="single-recent-blog-post">
                                                <div class="thumb">
                                                    <img class="img-fluid" src="{{ asset($post->image) }}" alt="">
                                                    <ul class="thumb-info">
                                                        <li><a><i class="ti-notepad"></i>{{ $post->created_at->format('d/m/Y') }}</a></li>
                                                        <li><a><i class="ti-heart"></i>{{ $post->likes->count() }}</a></li>
                                                        <li><a><i class="ti-comment"></i>{{ $post->comments->count() }} Bình luận</a></li>
                                                    </ul>
                                                </div>
                                                <div class="details mt-20">
                                                    <a href="/{{ $post->slug }}">
                                                        <h3>{{ $post->title }}</h3>
                                                    </a>
                                                    <p class="tag-list-inline">
                                                        <a href="/cat/{{ $post->categories[0]->slug }}">{{ $post->categories[0]->name }}</a>
                                                        <span><a href="/user/{{ $post->user->id }}">{{ $post->user->name }}</a></span>
                                                    </p>
                                                    <p>{!! Str::limit($post->title, 200) !!}</p>
                                                    <a class="button" href="/{{ $post->slug }}">Read More <i class="ti-arrow-right"></i></a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade {{ $profile->role != 'admin' ? 'active show' : '' }}" id="liked" role="tabpanel" aria-labelledby="liked-tab">
                                        @foreach($profile->likes as $like )
                                        <div class="single-recent-blog-post">
                                            <div class="thumb">
                                                <img class="img-fluid" src="{{ asset($like->post->image) }}" alt="">
                                                <ul class="thumb-info">
                                                    <li><a><i class="ti-notepad"></i>{{ $like->post->created_at->format('d/m/Y') }}</a></li>
                                                    <li><a><i class="ti-heart"></i>{{ $like->post->likes->count() }}</a></li>
                                                    <li><a><i class="ti-comment"></i>{{ $like->post->comments->count() }} Bình luận</a></li>
                                                </ul>
                                            </div>
                                            <div class="details mt-20">
                                                <a href="/{{ $like->post->slug }}">
                                                    <h3>{{ $like->post->title }}</h3>
                                                </a>
                                                <p class="tag-list-inline">
                                                    <a href="/cat/{{ $like->post->categories[0]->slug }}">{{ $like->post->categories[0]->name }}</a>
                                                    <span><a href="/user/{{ $like->post->user->id }}">{{ $like->post->user->name }}</a></span>
                                                </p>
                                                <p>{!! Str::limit($like->post->title, 200) !!}</p>
                                                <a class="button" href="/{{ $like->post->slug }}">Read More <i class="ti-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Start Blog Post Siddebar -->
                        @include('web.layouts.sidebar')
                    </div>
                </div>
                <!-- End Blog Post Siddebar -->
            </div>
        </div>
        </div>
    </section>
</main>
@endsection

@section('js')
<script src="{{ asset('/js/sidebar.js') }}"></script>
<script src="{{ asset('/js/userProfile.js') }}"></script>
<script>
    var myModal = new bootstrap.Modal(document.getElementById('myModal'));
    myModal.show();
</script>
@endsection