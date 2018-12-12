jQuery(document).ready(function(){
    jQuery('.title-separator').remove();

    $('.count_post').click(function(){
        $(this).parent().next().slideToggle();
    });
});