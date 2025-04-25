(function($) {

    $.fn.linotype_field_textarea = function(){
    
        jQuery(this).each(function(){

            $FIELD = jQuery(this);
			$INPUT = $FIELD.find( 'textarea' );
			$INPUT.parsley().validate();

        });

    }
    
    $(document).ready(function(){
    
        $('body').find('.linotype_field_textarea').linotype_field_textarea();
    
    });
    
    
}(jQuery));