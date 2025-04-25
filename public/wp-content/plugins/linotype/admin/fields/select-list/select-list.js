(function($) {

$.fn.wp_field_select_list = function(){
	
	$(this).each(function(){
	
		var $SELECT = $(this);
		var $LIST = $SELECT.find('.select-list');
		var $ITEM = $SELECT.find('.select-item');
		var $ACTIVATE = $SELECT.find('.action-activate');
		var $OUTPUT = $SELECT.find('.wp-field-value');

		$is_multiple = false;
		if ( $LIST.hasClass('multiple') ) $is_multiple = true;

		$ITEM.on('click', '.select-item-title', function(){

			$(this).parent().toggleClass( 'open' );

		});

		$ITEM.on('click', '.action-activate', function(){

			if ( ! $is_multiple ) $ITEM.removeClass('active');
			$(this).parent().parent().addClass('active');

			update();

		});

		$ITEM.on('click', '.action-desactivate', function(){

			$(this).parent().parent().removeClass('active');

			update();

		});

		function update() {
			
			$data = [];

			$ITEM.each(function(index,item){

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

	$('body').find('.wp-field-select-list').wp_field_select_list();

});

}(jQuery));
