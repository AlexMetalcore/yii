jQuery(document).ready(function(){
    jQuery('.title-separator').css('display' , 'none');

    $('.count_post').click(function(){
        $(this).parent().next().slideToggle();
    });
});