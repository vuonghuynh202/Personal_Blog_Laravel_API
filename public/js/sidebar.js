$.ajax({
    url: '/api/posts',
    method: 'GET',
    success: function(res) {
        if(res.status == 200) {
            let posts = res.data.reverse();
            let featurePosts = posts.filter(function(post) {
                return post.is_featured == '1';
            });
            displayFeaturePosts(featurePosts);
        }
    }
});

function displayFeaturePosts(posts) {
    let featurePostList = '';
    posts.forEach(function(item) {
        featurePostList += `
            <div class="single-post-list">
                <div class="thumb">
                    <img class="feature-post-image" src="${item.image}" alt="">
                    <ul class="thumb-info">
                        <li><a class="thumb-info-badge" href="/cat/${item.categories[0].slug}">${item.categories.length > 0 ? item.categories[0].name : ''}</a></li>
                        <li><a href="/user/${item.user.id}">${item.user.name}</a></li>
                    </ul>
                </div>
                <div class="details mt-1">
                    <a href="/${item.slug}">
                        <h6>${item.title}</h6>
                    </a>
                </div>
            </div>
        `;
    });

    $('.popular-post-list').html(featurePostList);
}


$.ajax({
    url: '/api/categories',
    method: 'GET',
    success: function(res) {
        if(res.status == 200) {
            let cats = res.data;
            let sortedCats = cats.sort(function(a, b) {
                return (b.posts ? b.posts.length : 0) - (a.posts ? a.posts.length : 0);
            });
            let topCats = sortedCats.slice(0, 7);
            displayTopCats(topCats);
        }
    }
})

function displayTopCats(categories) {
    let catItems = '';
    categories.forEach(function(item) {
        catItems += `
            <li>
                <a href="/cat/${item.slug}" class="d-flex justify-content-between">
                    <p>${item.name}</p>
                    <p>(${item.posts.length})</p>
                </a>
            </li>
        `;
    });
    $('#sidebar-cats-list').html(catItems);
}


$.ajax({
    url: '/api/posts',
    method: 'GET',
    success: function(res) {
        if(res.status == 200) {
            let newPosts = res.data.reverse();

            displayNewPosts(newPosts.slice(0, 10));
        }
    }
});

function displayNewPosts(posts) {
    let featurePostList = '';
    posts.forEach(function(item) {
        featurePostList += `
            <div class="single-post-list">
                <div class="thumb">
                    <img class="feature-post-image" src="${item.image}" alt="">
                    <ul class="thumb-info">
                        <li><a class="thumb-info-badge" href="/cat/${item.categories[0].slug}">${item.categories.length > 0 ? item.categories[0].name : ''}</a></li>
                        <li><a href="/user/${item.user.id}">${item.user.name}</a></li>
                    </ul>
                </div>
                <div class="details mt-1">
                    <a href="/${item.slug}">
                        <h6>${item.title}</h6>
                    </a>
                </div>
            </div>
        `;
    });

    $('#sidebar-new-post-list').html(featurePostList);
}