(function($) {

$.fn.wp_field_select_theme = function(){
	
	$(this).each(function(){
	
		var $SELECT = $(this);
		var $THEMES = $SELECT.find('.themes');
		var $THEME = $SELECT.find('.theme');
		var $ACTIVATE = $SELECT.find('.action-activate');
		var $OUTPUT = $SELECT.find('.wp-field-value');

		$is_multiple = false;
		if ( $THEMES.hasClass('multiple') ) $is_multiple = true;

		$THEME.on('click', '.action-activate', function(){

			if ( ! $is_multiple ) $THEME.removeClass('active');
			$(this).parent().parent().addClass('active');

			update();

		});

		$THEME.on('click', '.action-desactivate', function(){

			$(this).parent().parent().removeClass('active');

			update();

		});

		function update() {
			
			$data = [];

			$THEME.each(function(index,item){

				if ( $(this).hasClass('active') ) $data.push( $(item).attr('data-value') );

			});

			if ( $is_multiple ) {

				$json = JSON.stringify( $data );
				if ( $json == '[]' ) $json = '';

				$OUTPUT.val( $json );

			} else {

				$OUTPUT.val( $data[0] );

			}

		}

	});
}

$(document).ready(function(){

	$('body').find('.wp-field-select-theme').wp_field_select_theme();

});

}(jQuery));
