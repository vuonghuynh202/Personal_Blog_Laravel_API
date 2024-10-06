
displayUserInfo()
function displayUserInfo() {
    let userId = $('#user-id').val();
    $.ajax({
        url: `/api/users/${userId}`,
        type: 'GET',
        success: function(res) {
            if(res.status == 200) {
                $('#profile-user-img').attr('src', res.data.image)
                $('#profile-user-name').text(res.data.name)
            }
        }
    });
}


imageUploadPreview();
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


updateCategories()
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
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            },
            success: function(res) {
                hideLoading();
                if (res.status == 200) {
                    displayUserInfo()
                    $('#exampleModal').modal('hide');
                    showToast('success', 'Chỉnh sửa tài khoản thành công.')
                } else {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!')
                }
            },
            error: function(res) {
                hideLoading();
                showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                if (res.status == 422) {
                    let errors = res.responseJSON.errors;

                    $('.text-danger').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    if (errors.name) {
                        $('#user-name').addClass('is-invalid');
                        $('#user-name').next('.text-danger').html(errors.name[0]);
                    }
                }
            }
        });
    });
}