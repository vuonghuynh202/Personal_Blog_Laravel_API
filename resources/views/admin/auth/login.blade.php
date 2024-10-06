@include('admin.partials.head')
@section('title')
<title>Đăng Nhập</title>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6 mt-5">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" id="form-login" class="p-4">
                        @csrf
                        <h2 class="card-title text-center mb-4">Đăng nhập</h2>
                        <div class="form-group">
                            <label for="email">Địa chỉ email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Nhập địa chỉ email" autofocus>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu">
                            <small class="error-text text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Đăng nhập</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jquery -->
<script src="{{ asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>
<script>
    $('#form-login').submit(function(ev) {
        ev.preventDefault();

        let email = $('#email').val();
        let password = $('#password').val();

        $.ajax({
            url: '/api/login',
            type: 'POST',
            data: JSON.stringify({
                email: email,
                password: password
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                localStorage.setItem('token', response.token); // Lưu token
                window.location.href = '/admin';
            },
            error: function(res) {
                //lỗi nhập sai thông tin đăng nhập
                if (res.status == 401) {
                    $('#email').addClass('is-invalid');
                    $('#email').next('.text-danger').html(res.responseJSON.error);
                    $('#password').addClass('is-invalid');
                }

                //lỗi validate
                if (res.status == 422) {
                    let errors = res.responseJSON.errors;

                    $('.text-danger').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    if (errors.email) {
                        $('#email').addClass('is-invalid');
                        $('#email').next('.text-danger').html(errors.email[0]);
                    }
                    if (errors.password) {
                        $('#password').addClass('is-invalid');
                        $('#password').next('.text-danger').html(errors.password[0]);
                    }
                }
            }
        });
    });
</script>