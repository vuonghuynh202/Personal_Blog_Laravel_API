@extends('admin.partials.master')

@section('title')
<title>Thẻ</title>
@endsection


@section('content')
<h2 class="mb-5 py-3">Quản lý thẻ</h2>
<div class="row">
    <div class="col-md-4">
        <h4 class="mb-4">Thêm thẻ mới</h4>
        <hr>
        <form class="forms-sample" id="form-add" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="tag-slug" name="slug" value="{{ old('slug') }}">
            <div class="form-group">
                <label for="tag-name">Tên thẻ</label>
                <input type="text" name="name" class="form-control" id="tag-name" placeholder="Nhập tên thẻ">
                <p class="text-danger"></p>
            </div>

            <button type="submit" class="btn btn-primary border-0 text-white">Hoàn tất</button>
        </form>
    </div>

    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody id="list-tags"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        loadData();
        genSlug('#tag-name', '#tag-slug');
        createTags();
        updateTags();
        deleteTags();
    });

    function loadData() {
        $.get('/api/tags', function(res) {
            if (res.status == 200) {
                let tags = (res.data).reverse();
                let _tr = '';

                tags.forEach(function(item) {
                    _tr += `
                    <tr>
                        <td>${item.name}</td>
                        <td style="width: 400px;">
                            <button class="btn btn-primary text-white border-0 mr-1 mb-0" type="button" data-toggle="collapse" data-target="#collapse-${item.id}" aria-expanded="false" aria-controls="collapse-${item.id}">
                                Sửa
                            </button>
                            <button data-id="${item.id}" class="delete-btn btn btn-danger text-white border-0 m-0">Xoá</button>
                            <div class="collapse" id="collapse-${item.id}">
                                <div class="d-block">
                                    <form class="forms-sample form-update" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" class="tag-slug" name="slug" value="${item.slug}">
                                        <input type="hidden" class="tag-id" name="id" value="${item.id}">
                                        <div class="form-group mb-3 mt-4">
                                            <input type="text" name="name" class="form-control tag-name" value="${item.name}" autofocus>
                                            <p class="text-danger"></p>
                                        </div>

                                        <button type="submit" class="btn btn-primary border-primary text-white mr-1 mb-0">Cập nhật</button>
                                        <button type="button" class="btn btn-outline-primary border-primary m-0" data-toggle="collapse" data-target="#collapse-${item.id}" aria-expanded="false" aria-controls="collapse-${item.id}">Huỷ</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `;
                });

                $('#list-tags').html(_tr);
            }
        });
    }


    function createTags() {
        $('#form-add').on('submit', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/api/tags',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 201) {
                        loadData();
                        $('#form-add')[0].reset();
                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Thêm thẻ thành công.')
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
                            $('#tag-name').addClass('is-invalid');
                            $('#tag-name').next('.text-danger').html(errors.name[0]);
                        }
                        if (errors.slug) {
                            $('#tag-name').addClass('is-invalid');
                            $('#tag-name').next('.text-danger').html(errors.slug[0]);
                        }
                    }
                }
            });
        });
    }

    function updateTags() {
        $(document).on('submit', '.form-update', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            let tagId = $(this).find('.tag-id').val();

            $.ajax({
                url: `/api/tags/${tagId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        loadData();
                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Sửa thẻ thành công.')
                    } else {
                        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!')
                    }
                },
                error: function(res) {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            });
        });
    }


    function deleteTags() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let tagId = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/tags/${tagId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        success: function(res) {
                            if (res.status == 200) {
                                loadData();
                                showToast('success', 'Xoá menu thành công.')
                            } else {
                                showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!')
                            }
                        },
                        error: function() {
                            showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                        }
                    });
                }
            });
        })
    }
</script>
@endsection