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
                    jQuery('#post-upload , #portfolio-gallery , #user-upload_user_avatar , #media-media_upload').parent().append('<img class="preview_img" src="'+e.target.result+'" alt="php">');
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

$("#post-upload , #portfolio-gallery , #user-upload_user_avatar , #media-media_upload").change(function(){
    add_previw_img(this);
});
$('.upload_gallary , .upload_user_avatar').click(function() {
    $('#portfolio-gallery , #post-upload , #user-upload_user_avatar , #media-media_upload').trigger('click');
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
            alert(res);
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
                $('.static-images').after(res);
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
                $('.static-images').after(res);
            },1000);
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});
function basename(path) {
    return path.split('/').reverse()[0];
}

$('.img-delete-item').click(function(e) {
    e.preventDefault();
    var this_btn = $(this);
    var file_name = this_btn.next().attr('href');
    file_name = basename(file_name);
    $.ajax({
        url: '/admin/media/delete-item-img',
        data: { name_img : file_name},
        type: 'GET',
        success: function (res) {
            setTimeout(function() {
                $('form#w0').after(res);
                //$.pjax.reload({container: '#pjax-list-media'});
            },500);
            //window.location.reload();
            $.pjax.reload({container: '#pjax-list-media'});
        },
        error: function () {
            alert('Ошибка!');
        }
    });
});