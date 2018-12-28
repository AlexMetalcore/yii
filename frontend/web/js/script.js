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

    $(document).on('click' , '.block-portfolio' ,function(){
       var item_id = $(this).children().next().next().val();
        var overlay = $('.items-overlay-portfolio');
        var loader = $('.portfolio-loader');
        loader.fadeIn();

        $.ajax({
            url: '/site/portfolio-content',
            data: {id: item_id},
            type: 'GET',
            success: function (res) {
                setTimeout(function() {
                    overlay.fadeIn();
                    loader.fadeOut();
                    overlay.html(res);
                    $('.portfolio-close').click(function () {
                        overlay.fadeOut();
                    });
                },700);
            },
            error: function () {
                alert('Ошибка!');
            }
        });
    });
});