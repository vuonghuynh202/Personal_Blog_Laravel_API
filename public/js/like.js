$(document).ready(function () {
    let postId = $('#post-id').val();
    
    toggleLike(postId);
});

function toggleLike(postId) {
    $('#like-button').on('click', function(ev) {
        ev.preventDefault();
        let userId = $('#user-id').val();
        if(!userId) {
            window.location.href = '/user/login';
        } else {

            let $this = $(this);
            let isLiked = $this.hasClass('active'); // Kiểm tra xem đã thích hay chưa
            let url = isLiked ? `/api/unlike/${postId}` : `/api/like/${postId}`;
    
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(res) {
                    if(res.status == 201) {
                        $this.addClass('active');
                        $this.find('i').removeClass('far').addClass('fas');
    
                        showToast('success', 'Đã thích bài viết.');
                        $('#like-qty').text(parseInt($('#like-qty').text()) + (res.status === 201 ? 1 : 0));
                    }else if (res.status == 200) {
                        $this.removeClass('active');
                        $this.find('i').removeClass('fas').addClass('far');
    
                        showToast('success', 'Đã bỏ thích bài viết.');
                        $('#like-qty').text(parseInt($('#like-qty').text()) - 1);
                    }
                },
                error: function(err) {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            })
        }
    })
}
