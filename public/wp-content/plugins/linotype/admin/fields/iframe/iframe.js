(function($) {

$.fn.wp_field_iframe = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);
		var $OPTIONS = $.parseJSON( $FIELD.find('.field-options').val() );

		var $iframe = $FIELD.find('iframe');
		var $iframe_content = $FIELD.find('.iframe-content');
		var $iframe_src = $iframe.attr('data-src');
		var $bt_run = $FIELD.find('.iframe-run');
		

		$bt_run.on( 'click', function() {

			$iframe.attr( 'src', $iframe_src );
			$iframe_content.show();

		});

		setInterval( function(){ 
			
			$height = $iframe.contents().height();
			$iframe.contents().scrollTop( $height );
			
		}, 500 );

		

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-iframe').wp_field_iframe();

});

}(jQuery));
