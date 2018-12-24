jQuery(document).ready(function(){
    jQuery('.title-separator').remove();

    $('.count_post').click(function(){
        $(this).parent().next().slideToggle();
    });
    function positionSlide() {
        jQuery(window).scroll(function() {
            var scrollposition = $('.category_widget');
            var css = {
                'position': 'fixed',
                'width': '26%',
                'top': '93px'
            };
            if ($(window).scrollTop() > 93 && $(window).width() > 768) {
                scrollposition.css(css)
            } else {
                scrollposition.attr('style', '');
            }
        });
    }
    positionSlide();
});