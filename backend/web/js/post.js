function add_previw_img(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var loader = $('.portfolio-loader');
        reader.onload = function (e) {
            if(!jQuery('.preview_img').attr('src')){
                loader.fadeIn();
                setTimeout(function (){
                    jQuery('#post-upload , #portfolio-gallery').parent().append('<img class="preview_img" src="'+e.target.result+'" alt="php">');
                    loader.fadeOut();
                } ,1000);
            }
            else{
                loader.fadeIn();
                setTimeout(function (){
                    $('.preview_img').attr('src', e.target.result);
                    loader.fadeOut();
                },1000);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#post-upload , #portfolio-gallery").change(function(){
    add_previw_img(this);
});
$('.upload_gallary').click(function() {
    $('#portfolio-gallery , #post-upload').trigger('click');
});

$('#btn-delete-img').click(function() {
   var this_btn = $(this);
   $('.alert-dismissible').hide();
   var loader = $('.loader-delete');
   loader.fadeIn();
   this_btn.prop('disabled', true);
    $.ajax({
        url: '/admin/post/clear-old-imgs',
        type: 'POST',
        success: function (res) {
            setTimeout(function() {
                this_btn.removeAttr('disabled');
                loader.hide();
                $('.breadcrumb').after(res);
                $.pjax.reload({container: '#pjax-delete-trash-img'});
            },2000);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});