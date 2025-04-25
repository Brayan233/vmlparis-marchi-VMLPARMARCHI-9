(function($) {

$.fn.wp_field_customposts = function(){

	$(this).each(function(){

		var $FIELD = $(this);

		var $OUTPUT = $FIELD.find('.wp-field-value');

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-customposts').wp_field_customposts();

});

}(jQuery));
