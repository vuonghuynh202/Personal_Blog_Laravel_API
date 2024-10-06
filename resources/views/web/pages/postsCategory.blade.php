@extends('web.partials.master')

@section('title')
<title>{{ $category->name }}</title>
@endsection

@section('content')
<main class="site-main">
    <section class="blog-post-area section-margin mt-4">
        <div class="container">
            <h1 class="mb-4 category-name">{{ $category->name }}</h1>
            <input type="hidden" id="category-id" value="{{ $category->id }}">
            <div class="row">
                <div class="col-lg-8" id="posts-container">

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
<script src="{{ asset('/js/postsCategory.js') }}"></script>
<script src="{{ asset('/js/sidebar.js') }}"></script>
@endsection