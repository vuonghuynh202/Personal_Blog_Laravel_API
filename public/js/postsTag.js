$(document).ready(function() {
    let catId = $('#tag-id').val();
    $.ajax({
        url: `/api/tags/${catId}`,
        method: 'GET',
        success: function(res) {
            if(res.status == 200) {
                let cat = res.data;
                let posts = cat.posts.reverse();
                displayPost(posts);
            }
        }
    })
})

function displayPost(posts) {
    let htmlContent = '';

    posts.forEach(function(item) {
        let content = item.content.length > 450 
        ? item.content.substring(0, 450) + '...' 
        : item.content;

        let date = new Date(item.created_at);

        console.log(item.user)

        htmlContent += `
            <div class="single-recent-blog-post">
                <div class="thumb">
                    <img class="img-fluid" src="${item.image}" alt="">
                    <ul class="thumb-info">
                        <li><a href="#"><i class="ti-notepad"></i>${date.toLocaleDateString()}</a></li>
                        <li><a href="#"><i class="ti-heart"></i>${item.likes || 0}</a></li>
                        <li><a href="#"><i class="ti-comment"></i>${item.comments || 0} Bình luận</a></li>
                    </ul>
                </div>
                <div class="details mt-20">
                    <a href="/${item.slug}">
                        <h3>${item.title}</h3>
                    </a>
                    <p class="tag-list-inline">
                        <a href="/cat/${item.categories[0].slug}">${item.categories[0].name}</a>
                        <span>
                            <a href="/user/${item.user.id}">${item.user.name}</a>
                        </span>
                    </p>
                    <p>${content}</p>
                    <a class="button" href="/${item.slug}">Read More <i class="ti-arrow-right"></i></a>
                </div>
            </div>
        `;
    })
    $('#posts-container').html(htmlContent);
}
