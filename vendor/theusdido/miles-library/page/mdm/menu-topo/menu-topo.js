$(document).ready(function(){
    $('#brand-mdm').attr('src',session.url_favicon);
    $('#project-id').html(session.project_id);
    $('#project-name').html(session.project_name);
    $('#_environment').html(session._environment);
});

$('.navbar-nav .dropdown-menu li a').click(function(){
    let href = $(this).data('href');    
    loadMDMContent(href);
});