(function($) {

    $.fn.linotype_field_text = function(){
    
        jQuery(this).each(function(){

            $FIELD = jQuery(this);
			$INPUT = $FIELD.find( 'input' );
			$INPUT.parsley().validate();

        });

    }
    
    $(document).ready(function(){
    
        $('body').find('.linotype_field_text').linotype_field_text();
    
    });
    
    
}(jQuery));