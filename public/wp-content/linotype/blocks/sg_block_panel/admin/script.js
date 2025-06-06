(function($) {

$.fn.sg_block_panel = function(){

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
      
      $this.find('> .composer-item-content > .container-full > .composer-items > .composer-item').each( function( index_content, content ) {

      	contents[ 'content_' + index_content ] =  $.parseJSON( $(content).find('> .composer-item-value').val() );
        
      });
			
      if( contents.length !== 0 ) {
        value['contents'] = contents;
      } else {
        value['contents'] = {};
      }
      
			value = JSON.stringify( value, null, 2 );

			$value.val( value ).change();
      
		};
    
    $this.on('element-update', element_update );

	});

}

$(document).ready(function(){

	$('body').find('.sg_block_panel').sg_block_panel();
	
  $('body').on('composer-add', function( event, element ) {
    
  	$('body').find('.sg_block_panel').sg_block_panel();
    
  });
  
  $('body').on('composer-clone', function( event, element ) {
    
  	$('body').find('.sg_block_panel').sg_block_panel();
    
  });

});

}(jQuery));
