(function($) {

jQuery(document).ready(function(){

    $tabs_ids = [];

    $('.section-menu li').each( function( index, item ){
        $tabs_ids[index] = $(item).attr('data-target');
    });
    $tabs_ids = $tabs_ids.join(', ');
  
    $('.section-menu li').on( 'click', function( index, item ){

        $('.section-menu li').removeClass('active');
        $(this).addClass('active');
        
        $selected_id = $(this).attr('data-target');
        
        $($tabs_ids).removeClass('active');
        $($selected_id).addClass('active');

        window.location.hash= $(this).attr('data-hash');

        if ( $('#linotype-tab-frontend').hasClass('active') ) {
            var $frame = $('#linotype-tab-frontend').find('iframe');
            if ( $frame.attr('src') == "" ) $frame.attr("src", $frame.attr('data-src') );
        }

        if ( $('#linotype-tab-backend').hasClass('active') ) {
            var $frame = $('#linotype-tab-backend').find('iframe');
            if ( $frame.attr('src') == "" ) $frame.attr("src", $frame.attr('data-src') );
        }

    });

    $('.notice-dismiss').on( 'click', function( index, item ){
        
        $(this).parent().remove();

    });

    $(".linotype-save-file").on( 'click', function (){

        var $this = $(this);

        var textarea = $this.attr('data-textarea');

        var id = $this.attr('data-id');

        var dir = $this.attr('data-dir');

        var path = $this.attr('data-path');

        var file = $this.attr('data-file');

        var content = $( textarea ).val();

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { 
                'action' : 'linotype_editor_update_file',
                'id' : id,
                'dir' : dir,
                'path' : path,
                'file' : file,
                'content' : content
            },
            success: function( data ){
                
                console.log( data );

            }
        });

    });
    
    if ( window.location.hash ) {

        var hash = window.location.hash.substring(1);

        $('.section-menu li#linotype-tab-bt-' + hash ).click();

    }

});


}(jQuery));
  