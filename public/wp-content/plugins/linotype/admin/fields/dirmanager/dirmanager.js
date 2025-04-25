
(function($) {

$.fn.wp_field_dirmanager = function(){

	$(this).each(function(){

		var $FIELD = $(this);

    var $frame = $FIELD.find('iframe');

    $frame.on('load', function(){

      $frame.css('height', ( $frame.contents().find('body').outerHeight() + 50 ) + 'px' );

    });

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-dirmanager').wp_field_dirmanager();

});

}(jQuery));
