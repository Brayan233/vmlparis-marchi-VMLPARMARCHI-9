(function($) {

    $.fn.linotype_field_selector = function(){
    
        $(this).each(function(){
            
            var $field = $(this);
            var $value = $field.find( 'input' );
            var $select = $field.find('.selector');
            var $selects = $('body').find('.linotype_field_selector .selector');

            $value.on( 'focus', function() {
                $selects.removeClass('active');  
                $select.addClass('active');
            });

            $select.on( 'click', 'li', function(event) {

                var $selected_value = $(this).attr('data-value');
                var $selected_color = $(this).attr('data-color');
                $value.val( $selected_value ).css( 'background', $selected_color ).change();
                $selects.removeClass('active');    
                
            });

        });

    }
    
    $(document).ready(function() {
    
        $('body').find('.linotype_field_selector').linotype_field_selector();
    
    });
    
}(jQuery));