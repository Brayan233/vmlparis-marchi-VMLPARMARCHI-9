(function($) {

$.fn.sg_block_tabs = function(){

	$(this).each(function(){
    
    var $this = $(this);
    var $value = $this.find('> .composer-item-value');
    
		/*
		 *
		 * UPDATE
		 *
		*/
		function element_update(e) {
      
      var value = $.parseJSON( $value.val() );
      
      var contents = {};
      
      $this.find('> .composer-item-content > .container-fluid > .composer-items > .composer-item').each( function( index_content, content ) {

      	contents[ 'content_' + index_content ] =  $.parseJSON( $(content).find('> .composer-item-value').val() );
        
      });
			
      if( contents.length !== 0 ) {
        value['contents'] = contents;
      } else {
        value['contents'] = {};
      }
      
			value = JSON.stringify( value );

			$value.val( value ).change();
      
		};
    
    $this.on('element-update', element_update );

	});

}

$(document).ready(function(){

	$('body').find('.sg_block_tabs').sg_block_tabs();
	
  $('body').on('composer-add', function( event, element ) {
    
  	$('body').find('.sg_block_tabs').sg_block_tabs();
    
  });
  
  $('body').on('composer-clone', function( event, element ) {
    
  	$('body').find('.sg_block_tabs').sg_block_tabs();
    
  });

});

}(jQuery));
