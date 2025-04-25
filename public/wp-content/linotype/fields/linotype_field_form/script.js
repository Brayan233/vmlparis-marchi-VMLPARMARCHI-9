(function($) {

$.fn.linotype_field_form = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $VALUE = $FIELD.find('.wp-field-value');
        var SCHEMA = $FIELD.find('.linotype_field_form-schema').val();

        var $EDITOR = $FIELD.find('.linotype_field_form-editor');

        var editor = new JSONEditor( $EDITOR[0], {
            schema: $.parseJSON( SCHEMA ),
            compact:true,
            disable_edit_json:true,
            disable_properties:true,
            array_controls_top:true,
            enable_array_copy:true,
            iconlib:'fontawesome4',
            prompt_before_delete:false,
            startval: $.parseJSON( $VALUE.val() )
        });

        editor.on('change',function() {
            
            var value = editor.getValue();

            $VALUE.val( JSON.stringify( value ) );

        });

	});

}

$(document).ready(function(){

	$('body').find('.linotype_field_form').linotype_field_form();

});

}(jQuery));
