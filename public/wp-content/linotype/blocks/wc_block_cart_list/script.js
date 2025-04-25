(function($) {

  $.fn.wc_block_cart_list = function(){

    $(this).each(function(){

      var $this = $(this);

      $('body').on('click', '.btn-quantity', function () {

        var $button = $(this);
        var $qtyInput = $button.closest('.product-quantity').find('.qty');
        var oldVal = $qtyInput.val();

        if ($button.hasClass('btn-plus')) {
          var newVal = parseFloat(oldVal) + 1;
        } else {
          if (oldVal > 0) {
            var newVal = parseFloat(oldVal) - 1;
          } else {
            newVal = 0;
          }
        }

        $qtyInput.val(newVal).trigger('change');

      });

    }); 

  }

  $(document).ready(function(){

    $('body').find('.wc_block_cart_list').wc_block_cart_list();

  });

}(jQuery));
