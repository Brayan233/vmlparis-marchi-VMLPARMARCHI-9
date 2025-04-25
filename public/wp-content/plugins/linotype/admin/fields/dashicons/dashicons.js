(function($) {

$.fn.wp_field_dashicons = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $VALUE = $FIELD.find( '#' + $FIELD.attr('wp-field-id') );

		var $toogle = $FIELD.find( '.dashicons-toogle' );
		var $list = $FIELD.find( '.dashicons-list' );
		var $icons = $FIELD.find('.dashicons-list li');
		var $preview = $FIELD.find('.dashicons-preview');
		
		
		if ( $VALUE.val() ) {
			
			var $current = $FIELD.find('.dashicons-list li .' + $VALUE.val() );

			$current.parent('li').addClass('current');

		}

		$toogle.bind('click', function(){

			if ( $list.hasClass('open') ){

				$list.removeClass('open');

			} else {

				$list.addClass('open');

			}

		});

		$icons.bind('click', function(){

			$FIELD.find('.dashicons-list li').removeClass('current');
			jQuery(this).addClass('current');

			$list.removeClass('open');

			jQuery(this).find('span').attr('class')

			$preview.attr('class', 'dashicons-preview ' + jQuery(this).find('span').attr('class') )

			$VALUE.val( 'dashicons ' + jQuery(this).find('span').attr('class') ).change();

		});

	});

}

jQuery(document).ready(function(){

	$('body').find('.wp-field-dashicons').wp_field_dashicons();

});


}(jQuery));
