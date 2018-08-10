
jQuery('.icon_search').click(function() {
   jQuery('#form_search , .overlay').fadeIn();
});
jQuery('.overlay').click(function(){
    jQuery('#form_search , .overlay').fadeOut();
});

jQuery('form#form_search').submit(function(e) {
    e.preventDefault();
    var text = jQuery('.input_search').val().trim();
    if(text.length >= 3) {
        $.ajax({
            url: '/search?search_query=' + text,
            type: 'GET',
            success: function (res) {
                jQuery('.site-about , .site-contact , .site-index ' +
                    ', .site-post , .site-signup , .site-login , .site-category').replaceWith(res);
                if(jQuery(window).width() <= 780){
                    jQuery('#w0-collapse').removeClass('in');
                }
                jQuery('#form_search , .overlay').fadeOut();
            },
            error: function () {
                alert('Ошибка!');
            }
        });
        jQuery('.input_search').attr('style' , '');
    }
    else {
        jQuery('.input_search').css('border' , '1px solid red');
    }
});
