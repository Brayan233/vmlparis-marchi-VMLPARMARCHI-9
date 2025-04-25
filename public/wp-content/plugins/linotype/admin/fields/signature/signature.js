(function($) {

$.fn.wp_field_signature = function(){
    
    $(this).each(function(){

     
        var $FIELD = $(this);

        var $OUTPUT = $FIELD.find('.wp-field-value');

        var $SIGNATURE = $FIELD.find('.signature-pad');
        var $SIGNATURE_SAVED = $FIELD.find('.signature-pad-saved');
        var $clearButton = $SIGNATURE.find(".button-clear");
        var $fullscreenButton = $SIGNATURE.find(".button-fullscreen");
        var $canvas = $SIGNATURE.find(".signature-canvas")[0];


        function resizeCanvas() {

            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            $canvas.width = $canvas.offsetWidth * ratio;
            $canvas.height = $canvas.offsetHeight * ratio;
            $canvas.getContext("2d").scale(ratio, ratio);

        }

        window.onresize = resizeCanvas;

        resizeCanvas();

        var signaturePad = new SignaturePad($canvas, {
            // minWidth: 1,
            // maxWidth: 2,
            onEnd: save_image
        });

        $clearButton.on("click", function (event) {

            signaturePad.clear();
            $SIGNATURE_SAVED.remove();
            $OUTPUT.val( '' );

        });
        
        function save_image() {

            $OUTPUT.val( signaturePad.toDataURL() );
            
        }

        $fullscreenButton.on( 'click', function (event) {

            $SIGNATURE.toggleClass('fullscreen');

        });

    });

}

$(document).ready(function(){

    $('body').find('.wp-field-signature').wp_field_signature();

});

}(jQuery));
