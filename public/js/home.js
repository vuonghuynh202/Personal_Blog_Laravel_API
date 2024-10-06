$.ajax({
    url: '/api/posts',
    method: 'GET',
    success: function (res) {
        let posts = res.data.reverse();
        let firstPosts = posts.slice(0, 4);

        if (firstPosts.length >= 4) {
            let topPosts = `
                <a href="/${firstPosts[0].slug}" class="left-column">
                    <img class="banner-image" src="${firstPosts[0].image}" alt="">
                    <div class="banner-content">
                        <span class="item-badge">${firstPosts[0].categories.length > 0 ? firstPosts[0].categories[0].name : ''}</span>
                        <div>${firstPosts[0].title}</div>
                    </div>
                </a>
                <div class="right-column">
                    <a href="/${firstPosts[1].slug}" class="top-row">
                        <img class="banner-image" src="${firstPosts[1].image}" alt="">
                        <div class="banner-content">
                            <span class="item-badge">${firstPosts[1].categories.length > 0 ? firstPosts[1].categories[0].name : ''}</span>
                            <div>${firstPosts[1].title}</div>
                        </div>
                    </a>
                    <div class="bottom-row">
                        <a href="/${firstPosts[2].slug}" class="bottom-left">
                            <img class="banner-image" src="${firstPosts[2].image}" alt="">
                            <div class="banner-content">
                                <span class="item-badge">${firstPosts[2].categories.length > 0 ? firstPosts[2].categories[0].name : ''}</span>
                                <p>${firstPosts[2].title}</p>
                            </div>
                        </a>
                        <a href="/${firstPosts[3].slug}" class="bottom-right">
                            <img class="banner-image" src="${firstPosts[3].image}" alt="">
                            <div class="banner-content">
                                <span class="item-badge">${firstPosts[3].categories.length > 0 ? firstPosts[3].categories[0].name : ''}</span>
                                <p>${firstPosts[3].title}</p>
                            </div>
                        </a>
                    </div>
                </div>
            `;
            $('.banner-container').html(topPosts);
        }
    }
});



$(document).ready(function () {
    $.ajax({
        url: 'api/posts',
        method: 'GET',
        success: function (res) {
            if (res.status == 200) {
                let posts = res.data.reverse();
                let postsList = posts.slice(4)
                displayPost(postsList);
                pagination(postsList)
            }
        }
    })
})


function displayPost(posts) {
    let htmlContent = '';

    posts.forEach(function (item) {
        let postCategory = (item.categories && item.categories.length > 0) ? item.categories[0].name : '';

        let content = item.content.length > 450
            ? item.content.substring(0, 450) + '...'
            : item.content;

        let date = new Date(item.created_at);

        htmlContent += `
            <div class="single-recent-blog-post">
                <div class="thumb">
                    <img class="img-fluid" src="${item.image}" alt="">
                    <ul class="thumb-info">
                        <li><a><i class="ti-notepad"></i>${date.toLocaleDateString()}</a></li>
                        <li><a><i class="ti-heart"></i>${item.likes.length || 0}</a></li>
                        <li><a><i class="ti-comment"></i>${item.comments.length || 0} Bình luận</a></li>
                    </ul>
                </div>
                <div class="details mt-20">
                    <a href="/${item.slug}">
                        <h3>${item.title}</h3>
                    </a>
                    <p class="tag-list-inline">
                        <a href="/cat/${item.categories ? item.categories[0].slug : ''}">${postCategory}</a>
                        <span><a href="/user/${item.user.id}">${item.user.name}</a></span>
                    </p>
                    <p>${content}</p>
                    <a class="button" href="/${item.slug}">Read More <i class="ti-arrow-right"></i></a>
                </div>
            </div>
        `;
    })
    $('#posts-container').html(htmlContent);
}

function pagination(posts) {
    var postsPerPage = 7; // Số lượng bài viết mỗi trang
    var totalPosts = posts.length;
    var totalPages = Math.ceil(totalPosts / postsPerPage);
    var currentPage = 1;

    // Hàm hiển thị bài viết dựa trên trang hiện tại
    function showPosts(page) {
        $('.single-recent-blog-post').hide();
        var start = (page - 1) * postsPerPage;
        var end = start + postsPerPage;
        $('.single-recent-blog-post').slice(start, end).show();
    }

    // Tạo số trang
    function generatePageNumbers() {
        $('#page-numbers').empty(); // Xóa số trang trước khi thêm mới
        for (var i = 1; i <= totalPages; i++) {
            $('#page-numbers').append('<li class="page-number">' + i + '</li>');
        }
    }

    // Cập nhật phân trang
    function updatePagination() {
        $('.page-number').removeClass('active');
        $('.page-number').eq(currentPage - 1).addClass('active');

        $('#prev-btn').toggle(currentPage > 1); // Hiện nút Prev nếu không phải trang đầu
        $('#next-btn').toggle(currentPage < totalPages); // Hiện nút Next nếu không phải trang cuối
    }

    // Chuyển sang trang tiếp theo
    $('#next-btn').click(function (e) {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            showPosts(currentPage);
            updatePagination();
        }
    });

    // Quay lại trang trước
    $('#prev-btn').click(function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            showPosts(currentPage);
            updatePagination();
        }
    });

    // Nhấp vào số trang
    $(document).on('click', '.page-number', function () {
        currentPage = parseInt($(this).text());
        showPosts(currentPage);
        updatePagination();
    });

    // Khởi tạo
    showPosts(currentPage);
    generatePageNumbers();
    updatePagination();
}



$.ajax({
    url: `/api/categories`,
    type: 'GET',
    success: function (res) {
        if (res.status == 200) {
            displayOwlCarousel(res.data);
        }
    }
});

function displayOwlCarousel(data) {
    let owlItem = '';
    data.forEach(function (item) {
        owlItem += `
            <div class="slide-item">
                <div class="slide-img">
                    <img src="${item.image}" alt="">
                </div>
                <a href="/cat/${item.slug}" class="button">${item.name}</a>
            </div>
        `;
    });
    let $owlCarousel = $('.owl-carousel');
    $owlCarousel.trigger('destroy.owl.carousel');
    $owlCarousel.html(owlItem);

    $owlCarousel.owlCarousel({
        items: 3,
        loop: true,
        margin: 20,
        dots: true,
        autoplay: true,  
        autoplayTimeout: 3000, 
        autoplayHoverPause: true
    });
}


$(document).ready(function () {
    $('.owl-carousel').owlCarousel({
        margin: 20,
    });
});

