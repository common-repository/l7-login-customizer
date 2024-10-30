jQuery(document).ready(function($) {
    
    // Leaving before saving changes?  
    $(function () {
        $("input, textarea, select").on("input change", function() {
            window.onbeforeunload = window.onbeforeunload || function (e) {
                return "You have unsaved changes.  Do you want to save your changes?";
            };
        });
        $("form").on("submit", function() {
            window.onbeforeunload = null;
        });
    })

    // Preview
    $('#jsm_preview_button').toggle(function( event ) {
        event.preventDefault();
        $( '#jsm_iframe_preview' ).css( 'display', 'block' );
    }, function( event ){
        event.preventDefault();
        $( '#jsm_iframe_preview' ).css( 'display', 'none' );
    });

    // Upload Image Click
    var whichMedia = 0;
    $('#upload_logo_button').click(function() {
        whichMedia = 0;
        tb_show('Upload a Logo', 'media-upload.php?referer=wptuts-settings&type=image&TB_iframe=true&post_id=0', false);
        return false;
    });

    $('#upload_bk_image_button').click(function() {
        whichMedia = 1;
        tb_show('Upload a Background Image', 'media-upload.php?referer=wptuts-settings&type=image&TB_iframe=true&post_id=0', false);
        return false;
    });

    // After Image Upload
    window.send_to_editor = function(html) {
        if (whichMedia == 0){
    	    var image_url = $('img',html).attr('src');
    	    $('#text_string').val(image_url);
    	    tb_remove();
    	    $('#upload_logo_preview img').attr('src',image_url);
        	$('input[type="submit"]').trigger('click');
        }
        if (whichMedia == 1){
            var image_url = $('img',html).attr('src');
            $('#bk_image').val(image_url);
            tb_remove();
            $('#upload_bk_image_preview img').attr('src',image_url);
            $('input[type="submit"]').trigger('click');
        }
	}
});