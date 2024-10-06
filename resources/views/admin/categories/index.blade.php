@extends('admin.partials.master')

@section('title')
<title>Danh mục</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/categories/categories.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3">Quản lý danh mục</h2>
<div class="row">
    <div class="col-md-4">
        <h4 class="mb-4">Thêm danh mục mới</h4>
        <hr>
        <form class="forms-sample" id="form-add" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="slug" id="category-slug" value="{{ old('slug') }}">
            <div class="form-group">
                <label for="category-name">Tên danh mục</label>
                <input type="text"
                    name="name"
                    class="form-control"
                    id="category-name"
                    placeholder="Nhập tên danh mục">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="parent-categories">Danh mục cha</label>
                <select class="form-control" name="parent_id" id="parent-categories"></select>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="categories-file">Hình ảnh</label>
                <input type="file" name="image" class="form-control-file" id="categories-file">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <div class="category-form-image"></div>
            </div>
            <button type="submit" class="btn btn-primary border-0 text-white">Hoàn tất</button>
        </form>
    </div>

    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hình ảnh</th>
                        <th>Danh mục</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody id="list-cats"></tbody>
            </table>
            <div id="pagination" class="pagination-container">
                <!-- Pagination buttons will be inserted here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        loadData();
        genSlug('#category-name', '#category-slug');
        createCategories();
        imageUploadPreview();
        deleteCategories();
    });

    function loadData(page = 1) {
        $.get('/api/categories', function(res) {
            if (res.status == 200) {
                let cats = (res.data).reverse();
                let _tr = '';
                let _option = '';

                function getCategoryRows(categories, level = '') {
                    let rowsHtml = '';
                    let optionHtml = '';

                    categories.forEach(function(item, index) {
                        rowsHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><img class="categories-list-image" src="${item.image ?? '/storage/DefaultImages/image-default.png'}" alt=""></td>
                            <td>${item.name}</td>
                            <td>
                                <a href="/admin/categories/edit/${item.id}" class="btn btn-primary text-white border-0 mr-1 mb-0">Sửa</a>
                                <button data-id="${item.id}" class="delete-btn btn btn-danger text-white border-0 m-0">Xoá</button>
                            </td>
                        </tr>
                        `;

                        optionHtml += `<option value="${item.id}">${level}${item.name}</option>`;

                        if (item.children) {
                            let result = getCategoryRows(item.children, level + '--')
                            rowsHtml += result.rows;
                            optionHtml += result.options;
                        }
                    });
                    return {
                        rows: rowsHtml,
                        options: optionHtml
                    };
                }

                _tr = getCategoryRows(cats).rows;
                $('#list-cats').html(_tr);

                _option = getCategoryRows(cats).options;
                $('#parent-categories').html(_option);
                $('#parent-categories').prepend('<option selected value="">Chọn danh mục cha</option>');


                let paginationHtml = '';
                let paginationData = res.data; // Contains pagination info
                if (paginationData.last_page > 1) {
                    for (let i = 1; i <= paginationData.last_page; i++) {
                        paginationHtml += `<button onclick="loadData(${i})" class="pagination-btn">${i}</button>`;
                    }
                }
                $('#pagination').html(paginationHtml);
            }
        });
    }


    function createCategories() {
        $('#form-add').on('submit', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/api/categories',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 201) {
                        loadData();
                        $('#form-add')[0].reset();
                        $('.category-form-image').empty();
                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Thêm danh mục thành công.')
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
                            $('#category-name').addClass('is-invalid');
                            $('#category-name').next('.text-danger').html(errors.name[0]);
                        }
                        if (errors.slug) {
                            $('#category-name').addClass('is-invalid');
                            $('#category-name').next('.text-danger').html(errors.slug[0]);
                        }
                        if (errors.parent_id) {
                            $('#parent-categories').addClass('is-invalid');
                            $('#parent-categories').next('.text-danger').html(errors.parent_id[0]);
                        }
                        if (errors.image) {
                            $('#categories-file').addClass('is-invalid');
                            $('#categories-file').next('.text-danger').html(errors.image[0]);
                        }
                    }
                }
            });
        });
    }


    function imageUploadPreview() {
        $('#categories-file').on('change', function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('.category-form-image').empty();

                    let image = `<img src="${e.target.result}">`
                    $('.category-form-image').append(image);
                }
                reader.readAsDataURL(file);
            }
        })
    }


    function deleteCategories() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let catID = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/categories/${catID}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        success: function(res) {
                            if (res.status == 200) {
                                loadData();
                                showToast('success', 'Xoá danh mục thành công.')
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