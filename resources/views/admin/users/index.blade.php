@extends('admin.partials.master')

@section('title')
<title>Người dùng</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/users/users.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3">Quản lý Người dùng</h2>
<div class="row">
    <div class="col-md-12">
    <a href="{{ route('users.create') }}" class="btn btn-primary text-white border-0 mb-4">Thêm mới</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody id="list-users"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        loadData();
        deleteUsers();
    });

    function loadData() {
        $.get('/api/users', function(res) {
            if (res.status == 200) {
                let users = (res.data).reverse();
                let _tr = '';

                users.forEach(function(item) {
                    _tr += `
                    <tr>
                        <td><img class="user-list-image" src="${item.image ?? '/storage/DefaultImages/avatar-default.png'}" alt=""></td>
                        <td>${item.name}</td>
                        <td>${item.email}</td>
                        <td>${item.role}</td>
                        <td>
                            <a href="/admin/users/edit/${item.id}" class="btn btn-primary text-white border-0 mr-1 mb-0">Sửa</a>
                            <button data-id="${item.id}" class="delete-btn btn btn-danger text-white border-0 m-0">Xoá</button>
                        </td>
                    </tr>
                    `;
                });
                $('#list-users').html(_tr);
            }
        });
    }


    function deleteUsers() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let userId = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/users/${userId}`,
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