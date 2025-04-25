(function($) {

    $.fn.linotype_field_alphanum = function(){
    
        jQuery(this).each(function(){

            $FIELD = jQuery(this);
			$INPUT = $FIELD.find( 'input' );
			$INPUT.parsley().validate();

        });

    }
    
    $(document).ready(function(){
    
        $('body').find('.linotype_field_alphanum').linotype_field_alphanum();
    
    });
    
    
}(jQuery));