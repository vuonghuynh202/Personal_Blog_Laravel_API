//tạo slug dựa theo tên
function genSlug(inputSelector, outputSelector) {
    $(inputSelector).on('input', function() {
        let name = $(this).val();
        
        let slug = name
            .toLowerCase()                      
            .normalize("NFD")                  
            .replace(/[\u0300-\u036f]/g, '')   
            .replace(/[^a-z0-9\s-]/g, '')      
            .trim()                            
            .replace(/\s+/g, '-')               
            .replace(/-+/g, '-');              

        slug = slug.replace(/^-+/, '').replace(/-+$/, '');

        $(outputSelector).val(slug);
    });
}