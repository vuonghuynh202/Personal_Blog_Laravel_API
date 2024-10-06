@extends('admin.partials.master')

@section('title')
<title>Thêm mới bài viết</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/posts/posts.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
@endsection

@section('content')
<h2 class="mb-4 py-3">Tạo bài viết mới</h2>
<div class="row">
    <div class="col-md-12">
        <form class="forms-sample" id="form-add" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="post-slug" name="slug" value="{{ old('slug') }}">
            <div class="form-group">
                <label for="post-title">Tiêu đề</label>
                <input type="text" name="title" class="form-control" id="post-title" placeholder="Nhập tiêu đề bài viết">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <label for="post-content">Nội dung</label>
                <textarea class="form-control" name="content" id="post-content" rows="5"></textarea>
                <p class="text-danger content-error"></p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post-categories">Danh mục</label>
                        <select class="form-control post-category-select" name="category_ids[]" id="post-categories" multiple>
                            {!! $htmlOptions !!}
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post-tags">Thẻ</label>
                        <select class="form-control post-tag-select" name="tags[]" id="post-tags" multiple="multiple">
                            @foreach($tags as $tag)
                            <option>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="post-file">Hình ảnh</label>
                <input type="file" name="image" class="form-control-file" id="post-file">
                <p class="text-danger"></p>
            </div>
            <div class="form-group">
                <div class="post-form-image"></div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                    <input type="checkbox" class="custom-control-input" name="is_featured" value="1" id="post-featured">
                    <label class="custom-control-label" for="post-featured">Bài viết nổi bật</label>
                </div>
            </div>
            <div class="form-group">
                <label for="">Trạng thái</label>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="published" checked="">
                        Công khai
                        <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="private">
                        Riêng tư
                        <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="draft">
                        Nháp
                        <i class="input-helper"></i></label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary border-0 text-white">Hoàn tất</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
<script src="{{ asset('vendors/ckeditor/ckeditor.js') }}"></script>
<script>
    $(".post-category-select").select2();
    $(".post-tag-select").select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: "Mỗi thẻ cách nhau bằng dấu phẩy",
    })
</script>
<script>
    CKEDITOR.replace('post-content')
    $('#form-add').on('submit', function(ev) {
        // Đồng bộ dữ liệu từ CKEditor về textarea
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });
</script>

<script>
    $(document).ready(function() {
        genSlug('#post-title', '#post-slug');
        imageUploadPreview();
        createPosts();
    });

    function imageUploadPreview() {
        $('#post-file').on('change', function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('.post-form-image').empty();

                    let image = `<img src="${e.target.result}">`
                    $('.post-form-image').append(image);
                }
                reader.readAsDataURL(file);
            }
        })
    }

    function createPosts() {
        $('#form-add').on('submit', function(ev) {
            ev.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '/api/posts',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 201) {
                        $('.text-danger').html('');
                        $('.content-error').html('');
                        $('.is-invalid').removeClass('is-invalid');
                        showToast('success', 'Tạo bài viết thành công.');
                    } else {
                        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!')
                    }
                },
                error: function(res) {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                    if (res.status == 422) {
                        let errors = res.responseJSON.errors;

                        $('.text-danger').html('');
                        $('.content-error').html('');
                        $('.is-invalid').removeClass('is-invalid');

                        if (errors.title) {
                            $('#post-title').addClass('is-invalid');
                            $('#post-title').next('.text-danger').html(errors.title[0]);
                        }
                        if (errors.slug) {
                            $('#post-title').addClass('is-invalid');
                            $('#post-title').next('.text-danger').html(errors.slug[0]);
                        }
                        if (errors.content) {
                            $('.content-error').html(errors.content[0]);
                        }
                        if (errors.image) {
                            $('#post-file').next('.text-danger').html(errors.image[0]);
                        }
                    }
                }
            });
        })
    }
</script>

@endsection