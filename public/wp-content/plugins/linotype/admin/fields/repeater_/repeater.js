(function($) {

$.fn.wp_field_repeater = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);

		var $VALUE = $FIELD.find('.wp-field-value');
		
		var $OPTIONS = $.parseJSON( $VALUE.val() );
		
		console.log('wp-field-repeater');

		////////
		

		//sortable meta	
		$FIELD.find(".repeater-items").sortable({
			//axis: "y", 
			connectWith: ".repeater-items",
			//containment: "parent",
			scroll: false,
			handle: ".repeater-item-drag",
			placeholder: "placeholder",
			forceHelperSize: true,
			forcePlaceholderSize: true,
			tolerance: "pointer",
			start: function(event, ui){ 
				ui.item.addClass('drag'); 
				//if ( ui.item.find('.wp-field-ckeditor').html() ) ui.item.find('.wp-field-ckeditor').wp_field_ckeditor_destroy();
			},
			stop: function(event, ui){ 
				ui.item.removeClass('drag');
				update(); 
				//if ( ui.item.find('.wp-field-ckeditor').html() ) ui.item.find('.wp-field-ckeditor').wp_field_ckeditor();
			},
			update: function(event, ui){ 
				//update(); 
				//console.log(event,ui)
				
			}

		});


		///////

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-repeater').wp_field_repeater();

});

}(jQuery));
