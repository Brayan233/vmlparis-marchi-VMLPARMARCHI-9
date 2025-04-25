(function($) {

$.fn.wp_field_googlefonts = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $SELECT = $FIELD.find('.wp-field-value');

    $SELECT.fontselect();

	});

}

$(document).ready(function(){

	//$('body').find('.wp-field-googlefonts').wp_field_googlefonts();

});

}(jQuery));
