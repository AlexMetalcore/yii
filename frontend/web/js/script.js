jQuery(document).ready(function(){
    jQuery('.title-separator').remove();

    $('.count_post').click(function(){
        $(this).parent().next().slideToggle();
    });

    $(document).on('click' , '.block-portfolio' ,function(){
        var item_id = $(this).children().next().next().val();
        var overlay = $('.items-overlay-portfolio');
        var loader = $('.portfolio-loader');
        loader.fadeIn();

        $.ajax({
            url: '/site/portfolio-content',
            data: { id: item_id },
            type: 'GET',
            success: function (res) {
                setTimeout(function() {
                    overlay.fadeIn();
                    loader.fadeOut();
                    overlay.html(res);
                    $('.portfolio-close').click(function () {
                        overlay.fadeOut();
                        $('.position_zoom_rooms').remove();
                        overlay.children().remove();
                    });
                },700);
            },
            error: function () {
                alert('Ошибка!');
            }
        });
    });
    $(document).on('click', '.portfolio-img' ,function() {
        var img_this = $(this);
        var get_clone_img = img_this.clone().attr('src');
        $('body').append('<div class="position_zoom_rooms"><img class="clone_img" src="'+get_clone_img+'"/></div><div class="overlay-item"></div>');
        var overlay = $('.overlay-item');
        overlay.fadeIn();
        $('.clone_img').hide().fadeIn(300);
        overlay.click(function(){
            $('.position_zoom_rooms').remove();
            overlay.remove();
        });
    });
});