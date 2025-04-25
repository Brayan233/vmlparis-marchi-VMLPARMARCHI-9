(function($) {

$.fn.wp_field_background = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);
		var $VALUE = $FIELD.find( '.wp-field-value' );
		var $PREVIEW = $FIELD.find( '.field-background-preview' );

		var $background_color = $FIELD.find( '.background-color' );
		var $background_image = $FIELD.find( '.background-image' );
		var $background_posx = $FIELD.find( '.background-posx' );
		var $background_posy = $FIELD.find( '.background-posy' );
		var $background_repeat = $FIELD.find( '.background-repeat' );
		var $background_size = $FIELD.find( '.background-size' );
		var $background_attachment = $FIELD.find( '.background-attachment' );
		var $background_origin = $FIELD.find( '.background-origin' );

		//create custom trigger to listen changes
		$background_color.wpColorPicker({ change: function(event, ui){ console.log(event,ui); $(this).trigger( "wpColorPicker" ); } });
		$FIELD.find( '.wp-picker-clear' ).on('click', function(){ console.log('wp-picker-clear'); $(this).parent().find('.background-color').trigger( "wpColorPicker" ); });

		//listen changes
		$FIELD.on( 'wpColorPicker change click keyup input paste', '.background-color,.background-image,.background-posx,.background-posy,.background-repeat,.background-size,.background-attachment,.background-origin', update );

		//add setter acction
		$FIELD.on( 'click', '.field-background-setter', bt_setter );
		
		function bt_setter(){

			$bt = $(this);
			$data = $bt.attr('data-set');
			$setter = $bt.parent().parent();
			$input = $setter.find('input.background-params')
			$dummy = $setter.find('.field-background-setter-dummy');

			if ( $bt.hasClass('button-primary') ) {

				$bt.removeClass('button-primary');
				$input.addClass('hide');
				$dummy.addClass('hide');
				$data = '';
			
			} else {
				
				if ( $bt.hasClass('field-background-setter-image') ) {

					wpmedia( $input, $dummy );

				} else if ( $data == "" ) {

					$setter.find('.field-background-setter').removeClass('button-primary');
					$bt.addClass('button-primary');
					$input.removeClass('hide');
					$dummy.addClass('hide');

				} else {

					$setter.find('.field-background-setter').removeClass('button-primary');
					$bt.addClass('button-primary');
					$input.addClass('hide');
					$dummy.addClass('hide');

				}

			}

			$input.val($data).change();

		}

		//wpmedia
		function wpmedia( $input, $dummy ){
				
			var wp_field_background_frame = wp.media.frames.customHeader = wp.media( { "title": "Select Background Image", "library": { "type": 'image' }, "button": { "text": "Select" } });
		
			wp_field_background_frame.on( "select", function() {
						
				var attachment = wp_field_background_frame.state().get("selection").first();
				
				$input.val( attachment.attributes.url ).change();
				$dummy.addClass('button-primary').removeClass('hide').text( attachment.attributes.filename + '  x' );

			});
		
			wp_field_background_frame.open();
					
		}
		
		//create css
		function update(){
			
			$css = '';

			$output = [];

			$json = {};

			var $bg_color = $background_color.val();
			if( $bg_color != "" ) {
				$css += 'background-color:' + $bg_color + '; ';
				$json['background-color'] = $bg_color;
			}

			var $bg_image = $background_image.val();
			if( $bg_image != "" ) {
				$css += 'background-image:url(' + $bg_image + '); ';
				$json['background-image'] = $bg_image;
			}

			var $bg_posx = $background_posx.val();
			var $bg_posy = $background_posy.val();
			if( $bg_posx != "" || $bg_posy != "" ) {
				if( ! $bg_posx ) $bg_posx = 'left';
				if( ! $bg_posy ) $bg_posy = 'top';
				$css += 'background-position:' + $bg_posx + ' ' + $bg_posy + '; ';
				$json['background-posx'] = $bg_posx;
				$json['background-posy'] = $bg_posy;
			}

			var $bg_repeat = $background_repeat.val();
			if( $bg_repeat != "" ) {
				$css += 'background-repeat:' + $bg_repeat + '; ';
				$json['background-repeat'] = $bg_repeat;
			}

			var $bg_size = $background_size.val();
			if( $bg_size != "" ) {
				$css += 'background-size:' + $bg_size + '; ';
				$json['background-size'] = $bg_size;
			}

			var $bg_attachment = $background_attachment.val();
			if( $bg_attachment != "" ) {
				$css += 'background-attachment:' + $bg_attachment + '; ';
				$json['background-attachment'] = $bg_attachment;
			}

			var $bg_origin = $background_origin.val();
			if( $bg_origin != "" ) {
				$css += 'background-origin:' + $bg_origin + '; ';
				$json['background-origin'] = $bg_origin;
			}

			$PREVIEW.attr('style',$css);

			$json['css'] = $css;

			$output.push($json);

			if ( $css ){

				$VALUE.val( JSON.stringify( $output ) ).change();
			
			} else {

				$VALUE.val('').change();

			}

		}

	});
}

$(document).ready(function(){

	$('body').find('.wp-field-background').wp_field_background();
	
});

}(jQuery));
