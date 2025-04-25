(function($) {

$.fn.wp_field_file = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $VALUE = jQuery(this).find( '#' + jQuery(this).attr('wp-field-id') );

		//Add media
		$FIELD.on( 'click', '#file_button_select', wpmedia );

		function wpmedia(event){

			$this = this;

			event.preventDefault();

			var wp_field_file_frame = wp.media.frames.customHeader = wp.media({

				title: jQuery($this).data("choose"),
				library: {
					//type: [ 'svg', 'image' ],
				},
				button: {
					text: jQuery($this).data("Select")
				}

			});

			wp_field_file_frame.on( "select", function() {

				var attachment = wp_field_file_frame.state().get("selection").first();

				if ( attachment.attributes.url ) {
					
					$VALUE.val(attachment.attributes.url).change();

				} else {

					$VALUE.val('').change();

				}

			});

			wp_field_file_frame.open();

		}

		$FIELD.find('.field-content').css('visibility','visible');
		$FIELD.find('.spinner').css('display','none');

	});
}

jQuery(document).ready(function(){

	$('body').find('.wp-field-file').wp_field_file();

});

}(jQuery));
