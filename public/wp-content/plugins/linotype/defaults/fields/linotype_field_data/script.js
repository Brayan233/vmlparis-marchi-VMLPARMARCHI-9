(function($) {

$.fn.linotype_field_data = function(){

	jQuery(this).each(function(){

    var $el = this;

    var $FIELD = jQuery(this);
    var $FIELD_ID = $FIELD.attr('id');
		var $VALUE = $FIELD.find( '#' + jQuery(this).attr('wp-field-id') );
		var $OUTPUT = $FIELD.attr('wp-field-output');
		var $ITEMS = $FIELD.find(".linotype_field_data-items");
    
    var el_dragula;

    function sortable() {

      el_dragula = dragula([].slice.apply($el.querySelectorAll('.linotype_field_data-items') ), {

        direction: 'vertical', 
        revertOnSpill: false,             
        removeOnSpill: false,

        moves: function (el, container, handle) {
          return handle.className === 'linotype_field_data-item-move';
        }

			} ).on('drag', function (el) {

			}).on('drop', function (el) {

			}).on('over', function (el, container) {

			}).on('dragend', function (el, container) {
        update();
      });
      
    }

    sortable();
    
    function expand() {

      var $bt = $(this);
      var $this = $(this).parent();
      
      if ( $this.hasClass('expand') ) {
      
        $this.removeClass('expand');
        $bt.html('<span>more</span>');

      } else {
      
        $this.addClass('expand');
        $bt.html('<span>less</span>');

      }
      
    }

    function add() {
    	
    	$template = $FIELD.find('.linotype_field_data-item-template').html();
      
      $ITEMS.append( $template );
      
      init_fields();

      update();
      
    }
    
    function del() {
    	
    	$(this).parent().remove();
      
      update();
      
    }

    function init_fields(){
      
      $FIELD.find('.linotype_field_data_image').linotype_field_data_image();

      $FIELD.find('.linotype_field_data-select_custom ul li').on( 'click', function(){

        $(this).closest('.linotype_field_data-select_custom').find('> input').val( $(this).attr('data-value') ).css( 'background', $(this).attr('data-value') ).change();

      } );

    }

    init_fields();
    
    function update() {
    	
      var DATA = [];
      
      $FIELD.find('.linotype_field_data-items > .linotype_field_data-item').each( function( key, value ) {
        
        var data = {};
        
        $(this).find('.linotype_field_data-item-data-value').each( function( key, value ) {
        
          if ( $(this).hasClass('linotype_field_data-json') ) {
            
            var json;

            try {
              
              json = $.parseJSON( $(this).val() );

              data[ $(this).attr('data-id') ] = json;

            } catch (e) {
              
              data[ $(this).attr('data-id') ] = "";
              alert('linotype_field_data::json::parse_error');
              
            }
            
          } else {

            data[ $(this).attr('data-id') ] = $(this).val();

          }

        });
        
        DATA.push(data);
        
      });
      
      console.log('linotype_field_data::update', DATA );
      
      $VALUE.val( JSON.stringify( DATA ) ).change();
      
    }
    
    $FIELD.on( 'click', '.linotype_field_data-item-expand', expand );

    $FIELD.on( 'change paste', '.linotype_field_data-item-data-value', update );
    
    $FIELD.on( 'click', '.linotype_field_data-item-add', add );
    
    $FIELD.on( 'click', '.linotype_field_data-item-delete', del );

    

	});
}

$.fn.linotype_field_data_image = function(){

	$(this).each(function() {

    var $FIELD = $(this);
    var $value = $FIELD.find("> .linotype_field_data-item-data-value" );
    var $button_select = $FIELD.find("> .actions > .select" );
    var $button_delete = $FIELD.find("> .actions > .delete" );
    var $preview = $FIELD.find("> .image" );
    var $link = $FIELD.find("> .link" );
    
    function field_image_select(event) {

      event.preventDefault();

      var linotype_field_data_image_frame = wp.media.frames.customHeader = wp.media({
        title: 'Select file'
      });

      linotype_field_data_image_frame.on( "select", function() {

        var attachment = linotype_field_data_image_frame.state().get("selection").first();

        $preview.find('img').remove();

        if ( attachment.attributes.type === 'image' && attachment.attributes.url ) $preview.html('<img style="max-width:200px;max-height:200px;" src="' + attachment.attributes.url + '"/>');

        $link.html( attachment.attributes.url );

        $value.val(attachment.attributes.id).change();

      });

      linotype_field_data_image_frame.open();
  
    }

    function field_image_delete(event) {
    
      $preview.find('img').remove();
      $link.html('');
      $value.val('').change();

    }

    $button_select.on( 'click', field_image_select );
    $button_delete.on( 'click', field_image_delete );

  });

}

  

jQuery(document).ready(function(){

	$('body').find('.linotype_field_data').linotype_field_data();

});

}(jQuery));
