function add_previw_img(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var loader = $('.portfolio-loader');
        var btn = $('.upload_gallary');
        reader.onload = function (e) {
            if(!jQuery('.preview_img').attr('src')) {
                loader.fadeIn();
                btn.prop('disabled', true);
                setTimeout(function (){
                    jQuery('#post-upload , #portfolio-gallery').parent().append('<img class="preview_img" src="'+e.target.result+'" alt="php">');
                    loader.fadeOut();
                    btn.removeAttr('disabled');
                } ,1000);
            }
            else{
                loader.fadeIn();
                btn.prop('disabled', true);
                setTimeout(function (){
                    $('.preview_img').attr('src', e.target.result);
                    loader.fadeOut();
                    btn.removeAttr('disabled');
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
        url: '/admin/settings/clear-old-imgs',
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

$('#btn-compress-img').click(function() {
    var this_btn = $(this);
    $('.alert-dismissible').hide();
    var loader = $('.loader-compression');
    loader.fadeIn();
    this_btn.prop('disabled', true);
    $.ajax({
        url: '/admin/settings/compress-img',
        type: 'POST',
        success: function (res) {
            setTimeout(function() {
                this_btn.removeAttr('disabled');
                loader.hide();
                $('.static-images').after('<div class="alert alert-success alert-dismissible" role="alert">'+res+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                $.pjax.reload({container: '#pjax-compress-img'});
            },3000);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});

$('#btn-clear-cache').click(function() {
    var this_btn = $(this);
    $('.alert-dismissible').hide();
    var loader = $('.loader-clear-cache');
    loader.fadeIn();
    this_btn.prop('disabled', true);
    $.ajax({
        url: '/admin/settings/delete-cache',
        type: 'POST',
        success: function (res) {
            setTimeout(function() {
                this_btn.removeAttr('disabled');
                loader.hide();
                $('.static-images').after('<div class="alert alert-success alert-dismissible" role="alert">'+res+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            },1000);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});