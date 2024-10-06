@extends('admin.partials.master')

@section('title')
<title>Menu</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/menus/menus.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3">Quản lý menu</h2>
<div class="row">
    <div class="col-md-4">
        <h4 class="mb-4">Thêm menu mới</h4>
        <hr>
        <form class="forms-sample" id="form-add" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="menu-slug" name="slug" value="{{ old('slug') }}">
            <div class="form-group">
                <label for="menu-name">Tên menu</label>
                <input type="text" name="name" class="form-control" id="menu-name" placeholder="Nhập tên menu">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="menu-name">Url</label>
                <input type="text" name="url" class="form-control" id="menu-url" placeholder="Nhập url">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="parent-menu">Menu cha</label>
                <select class="form-control" name="parent_id" id="parent-menu"></select>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="menu-name">Thứ tự</label>
                <input type="number" name="order" class="form-control" id="menu-order" value="0">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="">Trạng thái</label>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked="">
                        Hiển thị
                        <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0">
                        Ẩn
                        <i class="input-helper"></i></label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary border-0 text-white">Hoàn tất</button>
        </form>
    </div>

    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Url</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody id="list-menus"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        loadData();
        genSlug('#menu-name', '#menu-slug');
        createMenus();
        deleteMenus();
    });

    function loadData() {
        $.get('/api/menus', function(res) {
            if (res.status == 200) {
                let menus = (res.data).reverse();
                let _tr = '';
                let _option = '';

                function getMenuRows(menuData, level = '') {
                    let rowsHtml = '';
                    let optionHtml = '';

                    menuData.forEach(function(item, index) {
                        rowsHtml += `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.url}</td>
                            <td>${item.order}</td>
                            <td>${item.status == 1 ? 
                                '<span class="badge badge-success">Hiển thị</span>' : 
                                '<span class="badge badge-danger">Ẩn</span>'}
                            </td>
                            <td>
                                <a href="/admin/menus/edit/${item.id}" class="btn btn-primary text-white border-0 mr-1 mb-0">Sửa</a>
                                <button data-id="${item.id}" class="delete-btn btn btn-danger text-white border-0 m-0">Xoá</button>
                            </td>
                        </tr>
                        `;

                        optionHtml += `<option value="${item.id}">${level}${item.name}</option>`;

                        if (item.children) {
                            let result = getMenuRows(item.children, level + '--')
                            rowsHtml += result.rows;
                            optionHtml += result.options;
                        }
                    });
                    return {
                        rows: rowsHtml,
                        options: optionHtml
                    };
                }

                _tr = getMenuRows(menus).rows;
                $('#list-menus').html(_tr);

                _option = getMenuRows(menus).options;
                $('#parent-menu').html(_option);
                $('#parent-menu').prepend('<option selected value="">Chọn menu cha</option>');
            }
        });
    }


    function createMenus() {
        $('#form-add').on('submit', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/api/menus',
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
                        showToast('success', 'Thêm menu thành công.')
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
                            $('#menu-name').addClass('is-invalid');
                            $('#menu-name').next('.text-danger').html(errors.name[0]);
                        }
                        if (errors.slug) {
                            $('#menu-name').addClass('is-invalid');
                            $('#menu-name').next('.text-danger').html(errors.slug[0]);
                        }
                        if (errors.url) {
                            $('#menu-url').addClass('is-invalid');
                            $('#menu-url').next('.text-danger').html(errors.url[0]);
                        }
                        if (errors.parent_id) {
                            $('#parent-menu').addClass('is-invalid');
                            $('#parent-menu').next('.text-danger').html(errors.parent_id[0]);
                        }
                        if (errors.image) {
                            $('#menu-order').addClass('is-invalid');
                            $('#menu-order').next('.text-danger').html(order.image[0]);
                        }
                    }
                }
            });
        });
    }


    function deleteMenus() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let menuId = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/menus/${menuId}`,
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