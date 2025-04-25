(function($) {

$.fn.wp_field_thickbox = function(){
	
	jQuery(this).each(function(){
	
		var $FIELD = jQuery(this);
		var $FIELD_ID = jQuery(this).attr('wp-field-id');
		var $VALUE = jQuery(this).find( '#' + $FIELD_ID );
		var $OUTPUT = jQuery(this).attr('wp-field-output');
		
		//Add media
		$FIELD.on( 'click', '#thickbox_button_select', open_thickbox );
		
		function open_thickbox(){

			//tb_show("", "http://www.handypress.dev/envato.net?width=100%&height=100%&TB_iframe=true");
			
			tb_show("", "#TB_inline?width=600&height=550&inlineId=thickbox_" + $FIELD_ID );

			

        	return false;

		}

		$FIELD.find('.field-content').css('visibility','visible');
		$FIELD.find('.spinner').css('display','none');

	});
}

jQuery(document).ready(function(){

	$('body').find('.wp-field-thickbox').wp_field_thickbox();
	
});

}(jQuery));
