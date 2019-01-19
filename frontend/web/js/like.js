jQuery('.heart-like , .heart-like-active').click(function () {
    var $this = jQuery(this);
    var id = jQuery('.post-id').val();
    $.ajax({
        url : '/post/like',
        type : 'GET',
        data: {id : id},
        success: function (res) {
            jQuery($this).toggleClass('heart-like heart-like-active');
            if(jQuery($this).hasClass('heart-like-active')) {
                jQuery($this).attr('src' , '/admin/images/staticimg/heart_red.png');
            }
            if (jQuery($this).hasClass('heart-like')) {
                jQuery($this).attr('src' , '/admin/images/staticimg/heart-outline.png');
            }
            jQuery.pjax.reload({container: '#pjaxCountLikes'});
            setTimeout(function(){
                Objectresize();
            },100);
        },
        error: function (res) {
            alert('Только авторизированным пользователям можно ставить лайк');
        }
    });
});