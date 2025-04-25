(function($) {

    $.fn.linotype_field_color = function(){
    
        jQuery(this).each(function(){

            $FIELD = jQuery(this);
            $VALUE = $FIELD.find( 'input' );
            $OPTIONS = $.parseJSON( $FIELD.find( '.options' ).val() );

            showPalette = false;
            if ( $OPTIONS.palette !== "" ) showPalette = true;

            palette = false;
            if ( $OPTIONS.palette !== "" ) palette = ( $OPTIONS.default + ',' + $OPTIONS.palette ).split(',');

            storecolor = false;
            if ( $OPTIONS.storecolor ) storecolor = "spectrum.local";

            $spectrum = $FIELD.find( '.spectrum' );

            $spectrum.spectrum({
                color: "",
                showInput: true,
                allowEmpty:true,
                chooseText: "OK",
                cancelText: "x",
                showButtons: true,
              	showAlpha: $OPTIONS.alpha,
                className: "full-spectrum",
                showInitial: false,
                showPaletteOnly: $OPTIONS.palette_only,
                showPalette: showPalette,
                palette: [
                    palette
                ],
                showSelectionPalette: true,
                maxSelectionSize: 10,
                preferredFormat: "rgb",
                localStorageKey: storecolor,
                move: function (color) {
                    
                },
                show: function () {
                
                },
                beforeShow: function () {
                
                },
                hide: function () {
                
                },
                change: function() {
                    
                }
            });

        });

    }
    
    $(document).ready(function(){
    
        $('body').find('.linotype_field_color').linotype_field_color();
    
    });
    
    
}(jQuery));