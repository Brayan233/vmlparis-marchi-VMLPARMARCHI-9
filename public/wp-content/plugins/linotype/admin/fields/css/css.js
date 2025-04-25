(function($) {

$.fn.wp_field_css = function(){
	
	jQuery(this).each(function(i){
	
		var $FIELD = jQuery(this);
		var $VALUE = jQuery(this).find( '#' + jQuery(this).attr('wp-field-id') );
		
		var $OUTPUT_TYPE = jQuery(this).attr('wp-field-output');

		if ( $OUTPUT_TYPE == 'json' ) {

			$FIELD.on( 'change', '.value-css', get_style_as_json );
		
		} else {

			$FIELD.on( 'change', '.value-css', get_style_as_css );

		}
		

		function get_style_as_css(){

			$css = '';
			
			$FIELD.find('.value-css').each(function(){
				
				if ( jQuery(this).val() ){

					$css += jQuery(this).attr('css') + ':' + jQuery(this).val() + ';';

				}

			});

			//firefox debug
			$bgPosX = 'left';
			$bgPosY = 'top';
			if ( $FIELD.find('.value-css[css="background-position-x"]').val() ) $bgPosX = $FIELD.find('.value-css[css="background-position-x"]').val();
			if ( $FIELD.find('.value-css[css="background-position-y"]').val() ) $bgPosY = $FIELD.find('.value-css[css="background-position-y"]').val();
			
			if ( $FIELD.find('.value-css[css="background-position-x"]').val() || $FIELD.find('.value-css[css="background-position-y"]').val() ){
				$css += 'background-position:' + $bgPosX + ' ' + $bgPosY + ';';
			}
			//firefox debug end
						
			if( $css ){
				
				$VALUE.val( $css );
		
			}else{
				
				$VALUE.val('');
			
			}

			$VALUE.change();

		}

		function get_style_as_json(){

			var $output = [];
			
			$css = {};
			

			$FIELD.find('.value-css').each(function(){
				
				if ( jQuery(this).val() ) $css[jQuery(this).attr('css')] = jQuery(this).val();

			});
			
			$output.push($css);
						
			if($output.length){
				
				$VALUE.val( JSON.stringify($output) );
		
			}else{
				
				$VALUE.val('');
			
			}

			$VALUE.change();

		}

		
		var $TAB = $FIELD.find('.css-tab > ul > li');
		var $TABCONTENT = $FIELD.find('.css-tab-content > ul');

		$FIELD.on('click', '.css-tab > ul > li', function(){
			
			if ( jQuery(this).hasClass('active') ) {

				$TAB.removeClass('active');
				$TABCONTENT.addClass('hidden');

			} else {

				var $activeTab = jQuery(this).attr("target");
			
				//hide all
				$TAB.removeClass('active');
				$TABCONTENT.addClass('hidden');
					
				//show clicked
				jQuery(this).addClass('active');
				$FIELD.find( $activeTab ).removeClass('hidden');

			}

			
			
		});

		//$FIELD.find('.css-tab > ul > li').eq(0).click();

		$FIELD.on('click','.wp-field-css-value-bt', function(){

			if ( $FIELD.find('.wp-field-css-value').hasClass('open') ){

				$FIELD.find('.wp-field-css-value').removeClass('open');

			} else {

				$FIELD.find('.wp-field-css-value').addClass('open');

			}
			
		});

		$FIELD.css('display','block');

	});
};

jQuery(document).ready(function(){

	$('body').find('.wp-field-css').wp_field_css();
	
});

}(jQuery));


