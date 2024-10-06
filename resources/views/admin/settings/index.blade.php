@extends('admin.partials.master')

@section('title')
<title>Cài đặt</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/menus/menus.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3">Quản lý cài đặt</h2>
<div class="row">
    <div class="col-md-4">
        <h4 class="mb-4">Thêm cài đặt mới</h4>
        <hr>
        <form class="forms-sample" id="form-add" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="setting-key">Cài đặt</label>
                <select class="form-control" 
                        name="key" id="setting-value" 
                        style="background-color: transparent; 
                               appearance: auto;
                               padding-bottom: 3px;
                               padding-top: 2px;" 
                        required></select>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="setting-value">Giá trị</label>
                <input type="text" name="value" class="form-control" id="setting-value" placeholder="Nhập giá trị" required>
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
                        <th>Giá trị</th>
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
        createSettings();
        updateSettings();
        deleteSettings();
    });

    function loadData() {
        $.get('/api/settings', function(res) {
            if (res.status == 200) {
                let settings = (res.data).reverse();
                let _tr = '';

                settings.forEach(function(item) {
                    _tr += `
                    <tr>
                        <td>${item.key}</td>
                        <td>${item.value}</td>
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
                                        <input type="hidden" class="setting-id" name="id" value="${item.id}">
                                        <input type="hidden" name="key" value="${item.key}">
                                        <div class="form-group mb-3 mt-4">
                                            <input type="text" name="value" class="form-control tag-name" value="${item.value}" required>
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


                const availableOptions = [
                    { key: 'facebook', label: 'Facebook' },
                    { key: 'instagram', label: 'Instagram' },
                    { key: 'twitter', label: 'Twitter' },
                    { key: 'tiktok', label: 'Tiktok' },
                    { key: 'youtube', label: 'Youtube' },
                    { key: 'threads', label: 'Threads' },
                    { key: 'email', label: 'Email' },
                    { key: 'phone', label: 'Phone' },
                    { key: 'address', label: 'Address' }
                ];

                // Lấy danh sách các key đã tồn tại từ API
                const existingKeys = settings.map(item => item.key);

                // Lọc các tùy chọn có sẵn để loại bỏ các key đã tồn tại
                const filteredOptions = availableOptions.filter(option => !existingKeys.includes(option.key));
                let selectOptions = '<option value="">Chọn loại cài đặt</option>';
                filteredOptions.forEach(option => {
                    selectOptions += `
                        <option value="${option.key}">${option.label}</option>
                    `;
                });
                $('#setting-value').html(selectOptions);
            }
        });
    }


    function createSettings() {
        $('#form-add').on('submit', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/api/settings',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 201) {
                        loadData();
                        $('#form-add')[0].reset();
                        showToast('success', 'Thêm cài đặt thành công.')
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

    function updateSettings() {
        $(document).on('submit', '.form-update', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            let settingId = $(this).find('.setting-id').val();

            $.ajax({
                url: `/api/settings/${settingId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        loadData();
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


    function deleteSettings() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let settingId = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/settings/${settingId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        success: function(res) {
                            if (res.status == 200) {
                                loadData();
                                showToast('success', 'Xoá cài đặt thành công.')
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