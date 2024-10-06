@extends('admin.partials.master')

@section('title')
<title>{{ $category->name }}</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/categories/categories.css') }}">
@endsection

@section('content')
<h2 class="px-2 mb-5 py-3 px-4">Chỉnh sửa danh mục</h2>
<div class="row px-4">
    <div class="col-md-6">
        <form class="forms-sample" id="form-update" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="parent-id" value="{{ $category->parent_id }}">
            <input type="hidden" name="id" id="cat-id" value="{{ $category->id }}">
            <input type="hidden" name="slug" id="category-slug" value="{{ $category->slug }}">
            <div class="form-group">
                <label for="category-name">Tên danh mục</label>
                <input type="text" name="name" 
                class="form-control" id="category-name" 
                value="{{ old('name', $category->name) }}" autofocus>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="parent-categories">Danh mục cha</label>
                <select class="form-control" name="parent_id" id="parent-categories">
                    <option value="">Chọn danh mục cha</option>
                    {!! $htmlOptions !!}
                </select>
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="categories-file">Hình ảnh</label>
                <input type="file" name="image" class="form-control-file" id="categories-file">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <div class="category-form-image">
                    <img id="img-update-preview" src="{{ $category->image ?? asset('storage/DefaultImages/image-default.png') }}" alt="Category Image">
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
        genSlug('#category-name', '#category-slug');
        updateCategories();
        imageUploadPreview();
    });

    function updateCategories() {
        $('#form-update').on('submit', function(ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            let catId = $('#cat-id').val();

            $.ajax({
                url: `/api/categories/${catId}`,
                type: 'POST',
                data: formData,
                processData: false, // Ngăn chặn jQuery xử lý dữ liệu
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
                    $('#img-update-preview').attr('src', e.target.result)
                }
                reader.readAsDataURL(file);
            }
        })
    }
</script>
@endsection