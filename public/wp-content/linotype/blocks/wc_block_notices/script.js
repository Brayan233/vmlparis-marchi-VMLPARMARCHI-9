(function($) {

  $.fn.wc_block_notices = function(){

    $(this).each(function(){

      var $this = $(this);

      $this.find('.btn-close').on('click', function() {
        $this.find('.woocommerce-message').fadeOut();
      });
			
    }); 

  }

  $(document).ready(function(){

    $('body').find('.wc_block_notices').wc_block_notices();

  });

}(jQuery));
