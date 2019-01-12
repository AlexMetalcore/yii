
$('.icon_search').click(function() {
   $('#form_search , .overlay').fadeIn();
});

$('.overlay').click(function(){
    $('.input_search').attr('style' , '');
    $('#form_search , .overlay').fadeOut();
});
function setLocation(curLoc){
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}
$('form#form_search').submit(function(e) {
    e.preventDefault();
    var input = $('.input_search');
    var text = input.val().trim();
    if(text.length >= 2) {
        $.ajax({
            url: '/search',
            data: { search_query : text},
            type: 'GET',
            success: function (res) {
                jQuery('.site-about , .site-contact , .site-index ' +
                    ', .site-post , .site-signup , .site-login , .site-category , .portfolio-index').html(res);
                setLocation('/search?search_query=' + text);
                if(jQuery(window).width() <= 780){
                    jQuery('#w0-collapse').removeClass('in');
                }
                jQuery('#form_search , .overlay').fadeOut();
            },
            error: function () {
                alert('Ошибка!');
            }
        });
        input.attr('style' , '');
    }
    else {
        input.css('border' , '2px solid red');
    }
});
