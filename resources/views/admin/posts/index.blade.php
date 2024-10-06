@extends('admin.partials.master')

@section('title')
<title>Bài viết</title>
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('adminAssets/posts/posts.css') }}">
@endsection

@section('content')
<h2 class="mb-4 py-3">Quản lý bài viết</h2>
<div class="row">
    <div class="col-md-12">
        <a href="{{ route('posts.create') }}" class="btn btn-primary text-white border-0 mb-4">Thêm mới</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Danh mục</th>
                        <th>Thẻ</th>
                        <th>Nổi bật</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody id="list-posts"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        loadData();
        deletePosts();
    });

    function loadData() {
        $.get('/api/posts', function(res) {
            if (res.status == 200) {
                let posts = (res.data).reverse();
                let _tr = '';

                posts.forEach(function(item) {
                    let date = new Date(item.created_at);
                    _tr += `
                    <tr>
                        <td><img class="post-img-list" src="${item.image ?? '/storage/DefaultImages/image-default.png'}" alt=""></td>
                        <td>${item.title}</td>
                        <td>${item.user ? item.user.name : 'Unknown'}</td>
                        <td>${item.categories ? item.categories.map(cat => cat.name).join(', ') : ''}</td>
                        <td>${item.tags ? item.tags.map(tag => tag.name).join(', ') : ''}</td>
                        <td>${item.is_featured ? '<i class="featured-icon mdi mdi-check"></i>' : ''}</td>
                        <td>${date.toLocaleDateString()}</td>
                        <td>
                            ${{
                                published: 'Công khai',
                                private: 'Riêng tư',
                                draft: 'Nháp'
                            }[item.status] || ''}
                        </td>
                        <td class="d-flex flex-column">
                            <a href="/${item.slug}" class="btn btn-primary text-white border-0 my-1" target="_blank">Xem</a>
                            <a href="/admin/posts/edit/${item.id}" class="btn btn-primary text-white border-0 my-1">Sửa</a>
                            <button data-id="${item.id}" class="delete-btn btn btn-danger text-white border-0 my-1">Xoá</button>
                        </td>
                    </tr>
                    `;
                });

                $('#list-posts').html(_tr);
            }
        });
    }


    function deletePosts() {
        $(document).on('click', '.delete-btn', function(ev) {
            ev.preventDefault();

            let postId = $(this).data('id');

            showConfirm().then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/posts/${postId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        success: function(res) {
                            if (res.status == 200) {
                                loadData();
                                showToast('success', 'Xoá bài viết thành công.')
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