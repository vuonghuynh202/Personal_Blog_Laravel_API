@extends('admin.partials.master')

@section('title')
<title>{{ $menu->name }}</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/menus/menus.css') }}">
@endsection

@section('content')
<h2 class="mb-5 py-3 px-4">Chỉnh sửa menu</h2>
<div class="row px-4">
    <div class="col-md-6">
        <form class="forms-sample" id="form-update" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="menu-slug" name="slug" value="{{ $menu->slug }}">
            <input type="hidden" name="id" id="menu-id" value="{{ $menu->id }}">
            <div class="form-group">
                <label for="menu-name">Tên menu</label>
                <input type="text" name="name" class="form-control" id="menu-name" value="{{ old('name', $menu->name) }}">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="menu-name">Url</label>
                <input type="text" name="url" class="form-control" id="menu-url" value="{{ old('name', $menu->url) }}">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="parent-menu">Menu cha</label>
                <select class="form-control" name="parent_id" id="parent-menu">
                    <option value="">Chọn menu cha</option>
                    {!! $htmlOptions !!}
                </select>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="menu-name">Thứ tự</label>
                <input type="number" name="order" class="form-control" id="menu-order" value="{{ old('name', $menu->order) }}">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="">Trạng thái</label>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" 
                               name="status" id="optionsRadios1" 
                               value="1" {{$menu->status == 1 ? 'checked' : ''}}>
                        Hiển thị
                        <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" 
                               name="status" id="optionsRadios2" 
                               value="0" {{$menu->status == 0 ? 'checked' : ''}}>
                        Ẩn
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
        genSlug('#menu-name', '#menu-slug');
        updateMenus();
    });

    function updateMenus() {
        $('#form-update').on('submit', function(ev) {
            ev.preventDefault();

            let formData = new FormData(this);
            let menuId = $('#menu-id').val();

            $.ajax({
                url: `/api/menus/${menuId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        $('.text-danger').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Chỉnh sửa danh mục thành công.')
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
</script>
@endsection