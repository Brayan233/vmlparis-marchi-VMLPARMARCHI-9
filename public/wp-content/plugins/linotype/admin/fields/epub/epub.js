(function($) {

$.fn.wp_field_epub = function(){
	
	$(this).each(function(){
	
		var $SELECT = $(this);
		
		var Book = ePub("http://wp-opengraph.com/wp-content/plugins/wp-opengraph/documentation.epub");
        Book.renderTo("area");

	});
}

$(document).ready(function(){

	$('body').find('.wp-field-epub').wp_field_epub();

});

}(jQuery));
