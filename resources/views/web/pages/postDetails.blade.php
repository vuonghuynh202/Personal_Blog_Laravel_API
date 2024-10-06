@extends('web.partials.master')

@section('title')
<title>{{ $post->name }}</title>
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
                        <div class="col-lg-12">
                            <input type="hidden" id="post-id" value="{{ $post->id }}">
                            <input type="hidden" id="user-id" value="{{ $user ? $user->id : '' }}">
                            <input type="hidden" id="user-role" value="{{ $user ? $user->role : '' }}">
                            <div class="post-details-heading" style="background-image: url({{ $post->image }});">
                                <div class="post-details-heading-info">
                                    <div class="post-categries">
                                        @foreach($post->categories as $cat)
                                        <a href="/cat/{{ $cat->slug }}">{{ $cat->name }}</a>
                                        @endforeach
                                    </div>
                                    <h1>{{ $post->title }}</h1>
                                    <div class="auth-time">
                                        Bởi
                                        <a href="/user/{{$post->user->id}}">{{ $post->user->name }}</a>-
                                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="main_blog_details">
                                <div class="user_details">
                                    <div class="float-left">
                                        @foreach($post->tags as $tag)
                                        <a href="/tag/{{ $tag->slug }}">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="post-details-content">
                                    {!! $post->content !!}
                                </div>
                                <div class="news_d_footer flex-column flex-sm-row">
                                    <div>
                                        @php
                                        $userLiked = $post->likes->contains('user_id', $user ? $user->id : '');
                                        @endphp
                                        <a href="#" id="like-button" class="align-middle mr-2 like-button {{ $userLiked ? 'active' : '' }}">
                                            {!! $userLiked
                                            ? '<i class="fas fa-heart"></i>'
                                            : '<i class="far fa-heart"></i>'
                                            !!}
                                            <span>Yêu thích</span>
                                        </a>
                                        <span id="like-qty">{{ $post->likes->count() }}</span>
                                    </div>
                                    <div class="justiy-content-sm-center ml-sm-auto mt-sm-0 mt-2">
                                        <span class="align-middle mr-2">
                                            <i class="ti-comment-alt"></i>
                                        </span>
                                        <span id="comments-qty"></span>
                                    </div>
                                    <div class="news_socail ml-sm-auto mt-sm-0 mt-2">
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                        <a href="#"><i class="fab fa-dribbble"></i></a>
                                        <a href="#"><i class="fab fa-behance"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-form">
                                @if($user)
                                <h4>Bình Luận</h4>
                                <form id="form-comment" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" name="parent_id" value="">
                                        <textarea class="form-control mb-10 rounded" rows="5" id="content" name="content" placeholder="Nhập bình luận"></textarea>
                                    </div>
                                    <button type="submit" class="button submit_btn">Bình Luận</button>
                                </form>
                                @else
                                <p>Vui lòng <a href="{{ route('login') }}" style="color: #ff9907;">đăng nhập</a> để bình luận.</p>
                                @endif
                            </div>
                            <h4 id="comments-count"></h4>
                            <hr>
                            <div class="comments-area">

                            </div>

                            <div class="related-posts-container">
                                <div class="devider">
                                    <h2>Bài viết liên quan</h2>
                                </div>
                                <div class="row">
                                    @foreach($relatedPosts as $item)
                                    <div class="col-md-4 mb-4">
                                        <a href="/{{ $item->slug }}" class="related-item">
                                            <img src="{{ asset($item->image) }}" alt="">
                                            <span>{{ $item->title }}</span>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @include('web.layouts.sidebar')
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('js')
<script src="{{ asset('/js/home.js') }}"></script>
<script src="{{ asset('/js/sidebar.js') }}"></script>
<script src="{{ asset('/js/comment.js') }}"></script>
<script src="{{ asset('/js/like.js') }}"></script>
@endsection