(function($) {

$.fn.linotype_field_hotspots = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $VALUE = jQuery(this).find( '#' + jQuery(this).attr('wp-field-id') );
		var $OUTPUT = jQuery(this).attr('wp-field-output');
		
    if ( $FIELD.hasClass('full') ) $( window ).resize(function() { $FIELD.height( $('.ADMINBLOCKS-tab-content').height() ) }).trigger( "resize" );
    
    var DATA = { "image": "", "hotspots": {}, "labels": {}, "settings": {} };
		
    $.extend( DATA, $.parseJSON( $VALUE.val() ) );
    
    var dot_count = $FIELD.find( ".linotype_field_hotspots-item" ).length;
    
    function init_image() {
      
      init_image_select();
      
      draggable();
    
    }

    function click_image(e) {
				
      dot_count++;

      $FIELD.find('.linotype_field_hotspots-item-empty').remove();
      
      $FIELD.find( '.linotype_field_hotspots-action-contents .linotype_field_hotspots-item-content' ).hide();
      $FIELD.find( '.linotype_field_hotspots-show-items' ).show();

      var top_offset = $(this).offset().top - $(window).scrollTop();
      var left_offset = $(this).offset().left - $(window).scrollLeft();

      var top_px = Math.round( (e.clientY - top_offset - 12) );
      var left_px = Math.round( (e.clientX - left_offset - 12) );

      var top_perc = top_px / $(this).height() * 100;
      var left_perc = left_px / $(this).width() * 100;
      
      var dot = '<div class="linotype_field_hotspots-item hotspot-id-' + dot_count + '" hotspot-id="' + dot_count + '" style="top: ' + top_perc + '%; left: ' + left_perc + '%;">' + dot_count + '</div>';
      $(dot).hide().appendTo($(this)).fadeIn(350);
      
      var $content = $( $FIELD.find('#template-item')[0].innerText ).clone();

      $content.attr('hotspot-id', dot_count );
      $content.attr('hotspot-top', top_perc );
      $content.attr('hotspot-left', left_perc );
      $content.find('.linotype_field_hotspots-item-header').html('Hotspot: ' + dot_count );

      $content.hide().appendTo( $FIELD.find( '.linotype_field_hotspots-action-contents' ) ).fadeIn(350);
      
      init_image_select();
      
      draggable();

      update();

      e.preventDefault();

    }
    
    function draggable() {
    
    	$FIELD.find( ".linotype_field_hotspots-item" ).draggable({
          containment: $FIELD.find(".field-image-preview"),
          stop: function( event, ui ) {

            var new_left_perc = parseInt($(this).css("left")) / ( $FIELD.find(".linotype_field_hotspots-image > img").width() / 100);
            var new_top_perc = parseInt($(this).css("top")) / ( $FIELD.find(".linotype_field_hotspots-image > img").height() / 100);

            $(this).css("left", new_left_perc + "%");
            $(this).css("top", new_top_perc + "%");
						
            $FIELD.find('.linotype_field_hotspots-item-content[hotspot-id=' + $(this).attr('hotspot-id') + ']').attr('hotspot-left', new_left_perc ).attr('hotspot-top', new_top_perc );
            
            update();

          }
        });
    
    }
    
    function init_image_select() {
    	
      jQuery('.linotype_field_hotspots-image-select').each(function(){
      
        var $this = $(this);
        var $value = $this.attr('data-value');
        var $width = $this.attr('data-width');
        var $img_target = $this.attr('data-img-target');
        
        $this.html('<input style="vertical-align: middle;" type="button" value="Select" class="button-primary image-select"> <input style="vertical-align: middle;" type="button" value="Remove" class="button image-remove button-small"><img style="vertical-align: middle;width:' + $width + ';" src="' + $value + '"/>');
        $this.on( 'click', '.image-select', wpmedia );
        $this.on( 'click', '.image-remove', remove );
				
        if ( $img_target ) {
        	var $current_media = $FIELD.find( $img_target );
        } else { 
          var $current_media = $(this).parent().find('img'); 
        }
        
        function wpmedia(event){
					
          var media = wp.media.frames.customHeader = wp.media();

          media.on( "select", function() {

            var attachment = media.state().get("selection").first();

            $current_media.attr('data-id', attachment.attributes.id ).attr('src', attachment.attributes.url );

            //init_image();

            update();

          });

          media.open();

        }
				
        function remove() {
					
          $current_media.attr('data-id', '' ).attr('src', '' );

        };
        
      });
      
    }

    function open_item(e){

      var id = $(this).attr('hotspot-id');
      
      $FIELD.find( '.linotype_field_hotspots-action-contents .linotype_field_hotspots-item-content' ).hide();
      $FIELD.find( '.linotype_field_hotspots-action-contents .linotype_field_hotspots-item-content[hotspot-id="' + id + '"]' ).show();
      $FIELD.find( '.linotype_field_hotspots-show-items' ).show();

      e.stopImmediatePropagation();

    }

    function show_items(e){

      $FIELD.find( '.linotype_field_hotspots-action-contents .linotype_field_hotspots-item-content' ).show();
      $FIELD.find( '.linotype_field_hotspots-show-items' ).hide();
      
      e.stopImmediatePropagation();

    }

    function delete_item() {
    	
      var $id = $(this).parent().attr('hotspot-id');
      
      $FIELD.find('.linotype_field_hotspots-item.hotspot-id-' + $id ).remove();
      $(this).parent().remove();
      
      update();
      
    }

    function labels_add() {
    	
      var $content = $( $FIELD.find('#template-label')[0].innerText ).clone();

      $content.hide().appendTo( $FIELD.find( '.linotype_field_hotspots-labels-contents' ) ).fadeIn(350);
      
      init_labels();
      
    }
    
    function delete_label() {
    	
      $(this).parent().remove();
      
      update();
      
    }

    function init_labels(){
      
      $FIELD.find('.linotype_select_custom ul li').on( 'click', function(){

        $(this).closest('.linotype_select_custom').find('> input').val( $(this).attr('data-value') ).css( 'background', $(this).attr('data-value') ).change();

      } );

    }

    function update_ui() {
      
      $FIELD.find('.linotype_field_hotspots-action-contents > li .linotype_field_hotspots-item-label').each( function( key, value ) {
        
        var $html_labels = "";
        
        $current_val = $(this).val();
        
        $current = '';
        if ( $current_val == "" ) $current = ' selected="selected"';

        $html_labels += '<option value=""' + $current + '>-</option>';

        $.each( DATA.labels, function( key, value ) {
          
          if ( value.color ) {  
            $current = '';
            if ( $current_val == value.color ) $current = ' selected="selected"';
            $html_labels += '<option value="' + value.color + '"' + $current + '>' + value.title + '</option>';
          }

        });

        $(this).html( $html_labels );

      });

    }

    function update() {
      
      DATA = { "image": "", "hotspots": [], "labels": [], "settings": {} };
      
      DATA['image'] = $FIELD.find('.linotype_field_hotspots-image > img').attr('src');
      
      $FIELD.find('.linotype_field_hotspots-action-contents > li').each( function( key, value ) {
        
        DATA.hotspots.push( { 
          'left': $(this).attr('hotspot-left'), 
          'top': $(this).attr('hotspot-top'),
          'image': $(this).find('img').attr('src'), 
          'title': $(this).find('.linotype_field_hotspots-item-title').val(),
          'label': $(this).find('.linotype_field_hotspots-item-label').val(), 
          'size': $(this).find('.linotype_field_hotspots-item-size').val(), 
          'content': $(this).find('.linotype_field_hotspots-item-content-value').val() 
      	} );
        
      });

      $FIELD.find('.linotype_field_hotspots-labels-contents > li').each( function( key, value ) {
        
        DATA.labels.push( { 
          'color': $(this).find('.linotype_field_hotspots-label-color').val(), 
          'title': $(this).find('.linotype_field_hotspots-label-title').val(), 
      	} );
        
      });
      
      console.log(DATA);
      
      $VALUE.val( JSON.stringify( DATA ) ).change();
      
      update_ui();

    }

    $FIELD.on( 'click', '.linotype_field_hotspots-image', click_image );

    $FIELD.on( 'click', '.linotype_field_hotspots-item-delete', delete_item );  
    $FIELD.on( 'click', '.linotype_field_hotspots-label-delete', delete_label );       
    $FIELD.on( 'click', '.linotype_field_hotspots-label-add', labels_add );
    $FIELD.on( 'click', '.linotype_field_hotspots-item', open_item );
    $FIELD.on( 'click', '.linotype_field_hotspots-show-items', show_items );

    $FIELD.on( 'change paste', '.linotype_field_hotspots-item-value', update );
		$FIELD.find('.linotype_field_hotspots-content').css('visibility','visible');
    
    init_image();
    init_labels();

	});
}

jQuery(document).ready(function(){

	$('body').find('.linotype_field_hotspots').linotype_field_hotspots();

});

}(jQuery));
