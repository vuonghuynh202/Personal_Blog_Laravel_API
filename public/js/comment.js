$(document).ready(function () {
    let postId = $('#post-id').val();
    loadComments(postId);
    CreateComments(postId);
    replyComments(postId);
    deleteComments(postId);
});

function loadComments(postId) {
    $.ajax({
        url: `/api/comments/${postId}`,
        type: 'GET',
        success: function (res) {
            if (res.status == 200) {
                renderComments(res.data);
                
                let qty = 0;
                qty += res.data.length;
                res.data.forEach(function(item) {
                    qty += item.replies.length;
                });
                $('#comments-count').text(qty + ' Bình Luận');
                $('#comments-qty').text(qty);
            }
        }
    });
}


function createCommentHtml(item, isReply = false) {
    let userId = $('#user-id').val();
    let userRole = $('#user-role').val();

    return `
        <div class="comment-list${isReply ? ' left-padding' : ''}" id="comment-${item.id}">
            <div class="single-comment d-flex justify-content-between">
                <div class="user d-flex">
                    <div class="thumb">
                        <img src="${item.user.image ?? '/storage/DefaultImages/avatar-default.png'}" alt="">
                    </div>
                    <div class="desc">
                        <h5><a>${item.user.name}</a></h5>
                        <p class="comment mb-1">${item.content}</p>
                        <div class="commet-bottom d-flex">
                            <p class="date mr-4">${getTimeAgo(item.created_at)}</p>
                            <a class="reply-button" data-toggle="collapse" href="#reply-${item.id}" aria-expanded="false" aria-controls="reply-${item.id}">
                                ${userId ? 'Phản hồi' : ''}
                            </a>
                        </div>
                        <div class="collapse" id="reply-${item.id}">
                            <form action="" class="form-reply" id="form-reply-${item.id}" method="post">
                                <input type="hidden" name="parent_id" id="parent_id" value="${item.id}">
                                <div class="form-group">
                                    <textarea class="form-control" id="reply-content"></textarea>
                                </div>
                                <div class="form-group float-right">
                                    <button type="button" class="cancel-reply" data-toggle="collapse" data-target="#reply-${item.id}" aria-expanded="false" aria-controls="reply-${item.id}">HUỶ</button>
                                    <button type="submit" class="button reply-submit-button">Phản Hồi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                ${userId == item.user.id || userRole == 'admin' ? `
                    <div class="comment-menu dropdown dropleft">
                        <a id="comment-menu-${item.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="comment-menu-${item.id}">
                            <a class="dropdown-item delete-comment-button" data-id="${item.id}" href="">Xoá bình luận</a>
                        </div>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function renderComments(comments) {
    let commentsHtml = '';
    
    comments.forEach(function (item) {
        commentsHtml += createCommentHtml(item);

        item.replies.forEach(function(reply) {
            commentsHtml += createCommentHtml(reply, true);
        });
    });

    $('.comments-area').html(commentsHtml);
}

//tính thời gian đã bình luận
function getTimeAgo(time) {
    const date = new Date(time);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000); 
    
    let years = Math.floor(diffInSeconds / 31536000);
    if (years > 0) return `${years} năm trước`;
    
    let months = Math.floor(diffInSeconds / 2592000);
    if (months > 0) return `${months} tháng trước`;

    let days = Math.floor(diffInSeconds / 86400);
    if (days > 0) return `${days} ngày trước`;

    let hours = Math.floor(diffInSeconds / 3600);
    if (hours > 0) return `${hours} giờ trước`;
    
    let minutes = Math.floor(diffInSeconds / 60);
    if (minutes > 0) return `${minutes} phút trước`;

    return `${diffInSeconds} giây trước`;
}


function CreateComments(postId) {
    $('#form-comment').on('submit', function (ev) {
        ev.preventDefault();
        let token = localStorage.getItem('token');

        let data = {
            content: $('#content').val(),
            parent_id: $('input[name="parent_id"]').val(),
        };

        $.ajax({
            url: `/api/comments/${postId}`,
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            },
            success: function (res) {
                if (res.status == 201) {
                    loadComments(postId);
                    showToast('success', 'Đã thêm bình luận!');
                }
            }
        });
    })
}

function replyComments(postId) {
    $('.comments-area').on('submit', '.form-reply', function(ev) {
        ev.preventDefault();

        let data = {
            content: $(this).find('#reply-content').val(),
            parent_id: $(this).find('#parent_id').val()
        }

        $.ajax({
            url: `/api/comments/${postId}`,
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                if (res.status == 201) {
                    loadComments(postId);
                    showToast('success', 'Đã phản hồi!');
                }
            }
        });
    })
}

function deleteComments(postId) {
    $('.comments-area').on('click', '.delete-comment-button', function(ev) {
        ev.preventDefault();

        let commentId = $(this).data('id');

        $.ajax({
            url: `/api/comments/${commentId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(res) {
                if(res.status == 200) {
                    loadComments(postId);
                    showToast('success', 'Đã xoá bình luận!');
                }
            }
        })
    });
}