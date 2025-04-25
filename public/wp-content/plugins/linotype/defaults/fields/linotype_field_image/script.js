(function($) {

$.fn.wp_field_image = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $VALUE = jQuery(this).find( '#' + jQuery(this).attr('wp-field-id') );
		var $OUTPUT = jQuery(this).attr('wp-field-output');

		//Add media
		$FIELD.on( 'click', '#file_button_select', wpmedia );

		//remove media
		$FIELD.on( 'click', '#file_button_remove', remove );


		function wpmedia(event){

			$this = this;

			event.preventDefault();

			var wp_field_image_frame = wp.media.frames.customHeader = wp.media({

				title: jQuery($this).data("choose"),
				library: {
					//type: [ 'svg', 'image' ],
				},
				button: {
					text: jQuery($this).data("update")
				}

			});

			wp_field_image_frame.on( "select", function() {

				var attachment = wp_field_image_frame.state().get("selection").first();

				$FIELD.find("#preview img" ).remove();

				if ( attachment.attributes.url ) $FIELD.find("#preview" ).html('<img src="' + attachment.attributes.url + '"/>');

				if ( $OUTPUT == 'url' ) {

					$VALUE.val(attachment.attributes.url).change();

				} else {

					$VALUE.val(attachment.attributes.id).change();

				}

				console.log(attachment);

				button_state();

			});

			wp_field_image_frame.open();

		}

		function remove() {

			$FIELD.find("#preview img" ).remove();
			$VALUE.val("").change();

			button_state();

			event.preventDefault();

		};

		function button_state(){

			if( $FIELD.find("#preview img").length == 0 ){

				//$FIELD.find("#file_button_remove" ).css('display','none');
				$FIELD.find(".field-image-action" ).addClass('show');
				$FIELD.find("#file_button_select" ).val('select');

			} else {

				//$FIELD.find("#file_button_remove" ).css('display','');
				$FIELD.find(".field-image-action" ).removeClass('show');
				$FIELD.find("#file_button_select" ).val('change');

			}

		}

		button_state();


		$FIELD.find('.field-content').css('visibility','visible');
		$FIELD.find('.spinner').css('display','none');

	});
}

jQuery(document).ready(function(){

	$('body').find('.wp-field-image').wp_field_image();

});

}(jQuery));
