jQuery('.heart-like , .heart-like-active').click(function () {
    var $this = jQuery(this);
    var id = jQuery('.post-id').val();
    var css = {
        'transition' : '1s',
        'transform' : 'scale(1.2)'
    };
    $.ajax({
        'url' : '/post/like?id='+id,
        'type' : 'POST',
        success: function (res) {
            jQuery($this).css(css);
            jQuery($this).toggleClass('heart-like heart-like-active');
            if(jQuery($this).hasClass('heart-like-active')) {
                jQuery($this).attr('src' , '/admin/images/heart_red.png');
            }
            if (jQuery($this).hasClass('heart-like')) {
                jQuery($this).attr('src' , '/admin/images/heart-outline.png');
            }
            jQuery.pjax.reload({container: '#pjaxCountLikes'});
        },
        error: function (res) {
            alert('Только авторизированным пользователям можно ставить лайк');
        }
    });
});