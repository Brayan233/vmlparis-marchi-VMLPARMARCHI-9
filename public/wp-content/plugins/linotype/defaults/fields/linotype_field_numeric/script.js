(function($) {

    $.fn.linotype_field_numeric = function(){
    
        jQuery(this).each(function(){

            $FIELD = jQuery(this);
			$INPUT = $FIELD.find( 'input' );
			$INPUT.parsley().validate();

        });

    }
    
    $(document).ready(function(){
    
        $('body').find('.linotype_field_numeric').linotype_field_numeric();
    
    });
    
    
}(jQuery));