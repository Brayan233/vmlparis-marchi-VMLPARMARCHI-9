
(function($) {

$.fn.wp_field_switch_ui = function(){

	$(this).each(function(){

  	var $buttons = $(this).find('.switch-ui-button');
		var $targets = $(this).find('.switch-ui-buttons').attr('data-targets');
		var $targets_hide = $(this).find('.switch-ui-buttons').attr('data-targets-hide');

		$( $targets_hide ).addClass('switch-ui-hide');

		$buttons.on('click', function(){

			$buttons.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active');

			$( $targets ).addClass('switch-ui-hide');

			$( $(this).attr('data-target') ).removeClass('switch-ui-hide');

		});

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-switch-ui').wp_field_switch_ui();

});

}(jQuery));
