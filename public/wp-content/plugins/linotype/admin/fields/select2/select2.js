(function($) {

$.fn.wp_field_select2 = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);
		var $SELECT = $FIELD.find('.linoadmin-select2-select');

		var $SELECT_OPTIONS = jQuery.parseJSON( $FIELD.find('.select2-options').val() );

		var $arg = {};
		
		if ( $SELECT_OPTIONS.placeholder != "" ) $arg['placeholder'] = $SELECT_OPTIONS.placeholder;
		
		if ( $SELECT_OPTIONS.clear ) $arg['allowClear'] = true;

		//if ( $SELECT_OPTIONS.search ) 
		$arg['minimumResultsForSearch'] = 10;
		
		if ( $SELECT_OPTIONS.tags ) $arg['tags'] = true;

		if ( $SELECT_OPTIONS.custom ) {
		
			$arg['tags'] = true;
			$arg['createTag'] = function (params) {
				return {
				  id: params.term,
				  text: params.term,
				  newOption: true
				}
			};
			
		}

		$arg['templateSelection'] = function (data) {

			var $result = '';

			if (data.newOption) {
				
				$info = 'custom';
				$result = '<span>' + data.text + '</span>';
				$result += '<span class="select2-item-info">' + $info + '</span>';

			} else {

				$info = $( data.element ).attr('data-info');
				$result = '<span>' + data.text + '</span>';
				if ( $info ) $result += '<span class="select2-item-info">' + $info + '</span>';

			}

			return $($result);

		};
		$arg['templateResult'] = function (data) {

			var $result = '';

			if ( data.text ) {
				if ( data.newOption ) {
					
					$result = '<span class="select2-results__option_content select2-item-custom">';
						$result += '<span>' + data.text + '</span>';
						$result += '<span class="select2-item-info">custom</span>';
					$result += '</span>';

				} else {

					$info = $( data.element ).attr('data-info');
					
					$result = '<span class="select2-results__option_content">';
						$result += '<span>' + data.text + '</span>';
						if ( $info ) $result += '<span class="select2-item-info">' + $info + '</span>';
					$result += '</span>';

				}
			}

			return $($result);

		};

		if ( $SELECT_OPTIONS.multiple !== true && $SELECT_OPTIONS.placeholder ) {

			$SELECT.on("select2:open", function() {
			   $(".select2-search--dropdown .select2-search__field").attr("placeholder", $SELECT_OPTIONS.placeholder );
			});
			$SELECT.on("select2:close", function() {
			    $(".select2-search--dropdown .select2-search__field").attr("placeholder", null);
			});
		}

		// init sortable
		// $("ul.select2-selection__rendered").sortable({
		//     containment: 'parent',
		//     stop: function( evt, ui ) {
		    	
		//  		var myVal = [];
		      	
		//       	$('li.select2-selection__choice').each(function(index) {
		        
		//         	var item = $( this ).attr('title');
		//         	myVal.push( item );
		      	
		//       	});

		//       	//error on reload
		//       	$SELECT.val(myVal);
		      	
		//}
		//});
	    
		$SELECT.select2( $arg );

		//$SELECT.show();
	
	});

}

$(document).ready(function(){

	$('body').find('.wp-field-select2').wp_field_select2();

});

}(jQuery));
