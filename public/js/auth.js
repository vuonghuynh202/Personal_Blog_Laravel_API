// Lưu URL hiện tại vào localStorage nếu không phải trang đăng nhập
if (window.location.pathname !== '/user/login') {
    localStorage.setItem('previousUrl', window.location.href);
}

//Đăng nhập
$('#form-login').on('submit', function (ev) {
    ev.preventDefault();

    let email = $('#email').val();
    let password = $('#password').val();

    showLoading();

    $.ajax({
        url: '/api/login',
        type: 'POST',
        data: JSON.stringify({
            email: email,
            password: password
        }),
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            'Content-Type': 'application/json',
        },
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            hideLoading();
            localStorage.setItem('token', response.token);
            var previousUrl = localStorage.getItem('previousUrl');

            if (previousUrl) {
                window.location.href = previousUrl;
            } else {
                window.location.href = '/';
            }
        },
        error: function (res) {
            hideLoading();
            showToast('error', 'Có lỗi xảy ra! Vui lòng thử lại.')

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

//Đăng xuất
$('#logout-button').on('click', function (ev) {
    ev.preventDefault();

    let token = localStorage.getItem('token');

    showLoading();

    $.ajax({
        url: '/api/logout',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            hideLoading();
            localStorage.removeItem('token');
            console.log(response.message);
            window.location.href = localStorage.getItem('previousUrl');
        },
        error: function (xhr, status, error) {
            hideLoading();
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra! Vui lòng thử lại.')
        }
    })
})

//Đăng ký
$('#form-register').on('submit', function (ev) {
    ev.preventDefault();

    let email = $('#email').val();
    let password = $('#password').val();
    let password_confirmation = $('#confirm-password').val();
    let name = $('#name').val();

    $.ajax({
        url: '/api/register',
        type: 'POST',
        data: JSON.stringify({
            name: name,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
        }),
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            'Content-Type': 'application/json',
        },
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            window.location.href = '/user/login';
            showToast('success', 'Đăng ký tài khoản thành công.')
        },
        error: function (res) {
            showToast('error', 'Có lỗi xảy ra! Vui lòng thử lại.')

            if (res.status == 422) {
                let errors = res.responseJSON.errors;

                $('.text-danger').html('');
                $('.is-invalid').removeClass('is-invalid');

                if (errors.name) {
                    $('#name').addClass('is-invalid');
                    $('#name').next('.text-danger').html(errors.name[0]);
                }
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
})