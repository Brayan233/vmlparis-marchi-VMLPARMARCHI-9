(function($) {

$.fn.wc_block_cart = function(){

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
      
      $this.find('> .composer-item-content > .composer-items > .composer-item').each( function( index_content, content ) {

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

	$('body').find('.wc_block_cart').wc_block_cart();
	
  $('body').on('composer-add', function( event, element ) {
    
  	$('body').find('.wc_block_cart').wc_block_cart();
    
  });
  
  $('body').on('composer-clone', function( event, element ) {
    
  	$('body').find('.wc_block_cart').wc_block_cart();
    
  });

});

}(jQuery));
