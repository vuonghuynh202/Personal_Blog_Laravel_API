@extends('web.partials.master')

@section('title')
<title>Đăng ký</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendors/bootstrap/bootstrap.min.css') }}">
@endsection

@section('content')
<main class="site-main">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="login-form mt-5">
                    <form action="#/" method="post" id="form-register" novalidate="novalidate">
                        @csrf
                        <h3 class="text-center mb-5">ĐĂNG KÝ</h3>
                        <div class="form-group mb-4">
                            <label for="">Tên hiển thị</label>
                            <input type="text" class="login-input form-control" name="name" id="name" placeholder="Nhập tên hiển thị" autofocus>
                            <span class="error-text text-danger"></span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Email</label>
                            <input type="email" class="login-input form-control" name="email" id="email"placeholder="Nhập địa chỉ email">
                            <span class="error-text text-danger"></span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Mật Khẩu</label>
                            <input type="password" class="login-input form-control" name="password" id="password" placeholder="Nhập mật khẩu">
                            <span class="error-text text-danger"></span>
                        </div>
                        <div class="form-group mb-5">
                            <label for="">Xác Nhận Mật Khẩu</label>
                            <input type="password" class="login-input" name="password_confirmation" id="confirm-password" placeholder="Nhập lại mật khẩu">
                        </div>
                        <div class="form-group text-center mb-4">
                            <input type="submit" class="login-input" value="Đăng Ký" class="button">
                        </div>
                    </form>
                    <div class="text-center bottom-text">Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay.</a></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script src="{{asset('js/auth.js')}}"></script>
@endsection