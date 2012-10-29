/**
 * Displays a message to the user using, similar to the php array $flash.
 **/
var flash = function(){
    var show = function(string, cls){
        $content = $('#message');
        if ( $content.length == 0 ){
            $content = $('<p id="message" class="'+cls+'" style="display:none;">'+string+'<p>');
            $('#content').prepend($content);
            $content.fadeIn();
        } else {
            $content.fadeOut(400, function(){
                $content.attr('class', cls);
                $content.html(string);
                $content.fadeIn();
            });
        }
    };

    return {
        success: function(string){ show(string, 'success'); },
        error: function(string){ show(string, 'error'); },
        info: function(string){ show(string, 'info'); },
		remove: function(){ $('#message').hide(); },
        show: show,
    };
}();

function update_category_description(){
	var selected = $('#upload #category').val();
	if ( !selected ){
		return;
	}

	$('#upload #cat_description').html(category_desc[selected]);
}

$(document).ready(function(){
	update_category_description();

	$('#upload #category').change(function(){
		update_category_description();
	});

	/* validate filesize */
	$('#upload input[type=file]').change(function(){
		if ( !this.files ){
			return; /* file api not supported */
		}

		flash.remove();

		var size = this.files[0].size;
		if ( size > upload_max_filesize ){
			flash.error('Filen är för stor');
			$('#upload input[type=submit]').attr('disabled', 'disabled');
		} else {
			$('#upload input[type=submit]').removeAttr('disabled');
		}
	});

	/* validate fields */
	$('#upload input[type=submit]').click(function(){
		if ( $('#upload #title').val().length == 0 ){
			flash.error('Du måste ange en titel');
			return false;
		}
		if ( $('#upload #author').val().length == 0 ){
			flash.error('Du måste ange author');
			return false;
		}

		$(this).attr('disabled', 'disabled');
		flash.info('Ditt bidrag laddas upp, vänta...');
	});
});
