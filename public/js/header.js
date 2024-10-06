$(document).ready(function() {
    $.ajax({
        url: '/api/categories',
        method: 'GET',
        success: function(response) {
            if (response.status === 200) {
                let categories = response.data;
                let menuHtml = buildMenu(categories);
                $('.menu_nav').html(menuHtml);
            }
        }
    });
});

function buildMenu(categories) {
    let menuHtml = '';

    categories.forEach(function(category) {
        menuHtml += `<li class="nav-item ${category.children.length > 0 ? 'submenu dropdown' : ''}">
                        <a href="/cat/${category.slug}" >${category.name} &nbsp;</a>
                        <span class="nav-link ${category.children.length > 0 ? 'dropdown-toggle' : ''}" 
                           ${category.children.length > 0 ? 'data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"' : ''}>
                           ${category.children.length > 0 ? '<i class="ti-angle-down"></i>' : '' }
                        </span>`;

    
        if (category.children.length > 0) {
            menuHtml += `<ul class="dropdown-menu">`;

            category.children.forEach(function(child) {
                menuHtml += `<li class="nav-item">
                                <a class="nav-link" href="/cat/${child.slug}">${child.name}</a>
                             </li>`;
            });

            menuHtml += `</ul>`;
        }

        menuHtml += `</li>`;
    });

    return menuHtml;
}

