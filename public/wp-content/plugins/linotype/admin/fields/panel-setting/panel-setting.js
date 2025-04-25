(function($) {

$.fn.wp_field_panel_setting = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);
		var $VALUE = $FIELD.find( '.wp-field-value' );
		var $PREVIEW = $FIELD.find( '.panel-setting-preview' );

		//create custom trigger to listen changes
		//$background_color.wpColorPicker({ change: function(event, ui){ console.log(event,ui); $(this).trigger( "wpColorPicker" ); } });
		//$FIELD.find( '.wp-picker-clear' ).on('click', function(){ console.log('wp-picker-clear'); $(this).parent().find('.background-color').trigger( "wpColorPicker" ); });

		//listen changes
		$FIELD.on( 'wpColorPicker change click keyup input paste', '.panel-setting-params', update );

		//add setter acction
		$FIELD.on( 'click', '.panel-setting-setter', bt_setter );

		//add setter acction
		$FIELD.on( 'focusout', 'input.panel-setting-params', bt_setter_check );

		function bt_setter_check(){

			$input = $(this);
			$data = $input.val();
			$setter = $input.parent();
			$bt = $setter.find('.button-custom-data');
			$dummy = $setter.find('.panel-setting-setter-dummy');


			if ( $data == "" ) {

				$setter.find('.panel-setting-setter').removeClass('button-current');
				$input.removeClass('visible').hide();
				$dummy.removeClass('visible');

			}

			$input.val($data).change();
		
		}

		function bt_setter(){

			$bt = $(this);
			$data = $bt.attr('data-set');
			$setter = $bt.parent().parent();
			$input = $setter.find('input.panel-setting-params')
			$dummy = $setter.find('.panel-setting-setter-dummy');

			if ( $bt.hasClass('button-current') ) {

				$bt.removeClass('button-current');
				$input.removeClass('visible').focus();
				$dummy.removeClass('visible');
				$data = '';
			
			} else {
				
				if ( $bt.hasClass('panel-setting-setter-image') ) {

					wpmedia( $input, $dummy );

				} else if ( $data == "" && $bt.hasClass('button-custom-data') ) {

					$setter.find('.panel-setting-setter').removeClass('button-current');
					$bt.addClass('button-current');
					$input.addClass('visible').focus();
					$dummy.removeClass('visible');

				} else {

					$setter.find('.panel-setting-setter').removeClass('button-current');
					$bt.addClass('button-current');
					$input.removeClass('visible').focus();
					$dummy.removeClass('visible');

				}

			}

			// if ( $data == "" ) {

			// 	$setter.find('.panel-setting-setter').removeClass('button-current');
			// 	$input.removeClass('visible').hide();
			// 	$dummy.removeClass('visible');

			// }
			
			$input.val($data).change();

		}

		//wpmedia
		function wpmedia( $input, $dummy ){
				
			var wpmedia_frame = wp.media.frames.customHeader = wp.media( { "title": "Select Image", "library": { "type": 'image' }, "button": { "text": "Select" } });
		
			wpmedia_frame.on( "select", function() {
						
				var attachment = wpmedia_frame.state().get("selection").first();
				
				$input.val( attachment.attributes.url ).change();
				$dummy.addClass('button-current').addClass('visible').text( attachment.attributes.filename + '  x' );

			});
		
			wpmedia_frame.open();
					
		}
		
		//create css
		function update(){

			$output = [];

			$json = {};

			$('.panel-setting-params').each(function(){

				var $data_id = $(this).attr('data-value-id');
				var $data = $(this).val();

				if( $data != "" ) {
					$json[ $data_id ] = $data;
				}
			
			});

			$output.push( $json );

			if ( $output.length ){

				$VALUE.val( JSON.stringify( $output ) ).change();
			
			} else {

				$VALUE.val('').change();

			}

		}

	});
}

$(document).ready(function(){

	$('body').find('.wp-field-panel-setting').wp_field_panel_setting();
	
});

}(jQuery));
