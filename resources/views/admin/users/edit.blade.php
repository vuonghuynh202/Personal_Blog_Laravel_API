@extends('admin.partials.master')

@section('title')
<title>{{ $user->name }}</title>
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/users/users.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3 px-4">Chỉnh người dùng</h2>
<div class="row px-4">
    <div class="col-md-6">
        <form class="forms-sample" id="form-update" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="user-id" value="{{ $user->id }}">
            <div class="form-group">
                <label for="menu-name">Tên người dùng</label>
                <input type="text" name="name" class="form-control" id="user-name" value="{{ $user->name }}">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="user-name">Email</label>
                <input type="email" name="email" class="form-control" id="user-email" value="{{ $user->email }}">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="user-password">Mật khẩu</label>
                <input type="password" name="password" class="form-control" id="user-password">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="user-password">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" id="password-confirmation">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="user-file">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control-file" id="user-file">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <div class="user-form-image">
                    <img src="{{ $user->image ?? '/storage/DefaultImages/avatar-default.png' }}" alt="">
                </div>
            </div>
            <div class="form-group">
                <label for="">Vai trò</label>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" 
                                name="role" id="optionsRadios1" 
                                value="user" {{ $user->role == 'user' ? 'checked' : '' }} checked="">
                        Độc giả
                        <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" 
                        name="role" id="optionsRadios2" 
                        value="admin" {{ $user->role == 'admin' ? 'checked' : '' }}>
                        Quản trị viên
                        <i class="input-helper"></i></label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary border-0 text-white">Hoàn tất</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        imageUploadPreview();
        updateCategories();
    });


    function updateCategories() {
        $('#form-update').on('submit', function(ev) {
            ev.preventDefault();

            let formData = new FormData(this);
            let userId = $('#user-id').val();
            showLoading();

            $.ajax({
                url: `/api/users/${userId}`,
                type: 'POST',
                data: formData,
                processData: false, // Ngăn chặn jQuery xử lý dữ liệu
                contentType: false,
                success: function(res) {
                    hideLoading();
                    if (res.status == 200) {
                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Chỉnh sửa tài khoản thành công.')
                    } else {
                        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!')
                    }
                },
                error: function(res) {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                    if (res.status == 422) {
                        let errors = res.responseJSON.errors;

                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');

                        if (errors.name) {
                            $('#user-name').addClass('is-invalid');
                            $('#user-name').next('.text-danger').html(errors.name[0]);
                        }
                        if (errors.email) {
                            $('#user-email').addClass('is-invalid');
                            $('#user-email').next('.text-danger').html(errors.email[0]);
                        }
                        if (errors.password) {
                            $('#user-password').addClass('is-invalid');
                            $('#user-password').next('.text-danger').html(errors.password[0]);
                        }
                    }
                }
            });
        });
    }

    function imageUploadPreview() {
        $('#user-file').on('change', function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('.user-form-image').empty();

                    let image = `<img src="${e.target.result}">`
                    $('.user-form-image').append(image);
                }
                reader.readAsDataURL(file);
            }
        })
    }
</script>
@endsection