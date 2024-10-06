@extends('web.partials.master')

@section('title')
<title>{{ $query }}</title>
@endsection

@section('content')
<main class="site-main">
    <section class="blog-post-area section-margin mt-4">
        <div class="container">
            <h2 class="mb-4 category-name">Kết quả tìm kiếm cho <i>'{{ $query }}'</i></h2>
            <div class="row">
                <div class="col-lg-8">
                    <div id="posts-container">
                        @foreach($posts as $post)
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
                                    <span><a href="/${item.user.name}-${item.user.id}">{{ $post->user->name }}</a></span>
                                </p>
                                <p>{!! Str::limit($post->title, 200) !!}</p>
                                <a class="button" href="/{{ $post->slug }}">Read More <i class="ti-arrow-right"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Start Blog Post Siddebar -->
                @include('web.layouts.sidebar')
            </div>
            <!-- End Blog Post Siddebar -->
        </div>
    </section>
</main>
@endsection

@section('js')
<script src="{{ asset('/js/sidebar.js') }}"></script>
@endsection