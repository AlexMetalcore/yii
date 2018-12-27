function add_previw_img(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if(!jQuery('.preview_img').attr('src')){
                jQuery('#post-upload , #portfolio-gallery').parent().append('<img class="preview_img" src="'+e.target.result+'" alt="php">');
            }
            else{
                $('.preview_img').attr('src', e.target.result);
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