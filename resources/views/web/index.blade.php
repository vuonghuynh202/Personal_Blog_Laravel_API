@extends('web.partials.master')

@section('title')
<title>Trang Chủ</title>
@endsection

@section('content')
<main class="site-main">
    <div class="banner-section">
        <div class="banner-container">

        </div>
    </div>
    <!--================Hero Banner end =================-->

    <section>
        <div class="container">
            <div class="owl-carousel owl-theme">
                
            </div>
        </div>
    </section>

    <!--================ Start Blog Post Area =================-->
    <section class="blog-post-area section-margin mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div id="posts-container"></div>
                    <div id="home-pagination">
                        <a href="" id="prev-btn"><i class="ti-angle-left"></i></a>
                        <ul id="page-numbers">
                            <li>1</li>
                            <li>3</li>
                            <li>3</li>
                        </ul>
                        <a href="" id="next-btn"><i class="ti-angle-right"></i></a>
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
<script src="{{ asset('/js/home.js') }}"></script>
<script src="{{ asset('/js/sidebar.js') }}"></script>
@endsection