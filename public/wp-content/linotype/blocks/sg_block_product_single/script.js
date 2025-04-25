(function ($) {

  $.fn.sg_block_product_single = function () {

    $(this).each(function () {

      const $this = $(this);

      $this.on('click', '.accordion-toggle', function() {
        var $toggle = $(this);
        if ($toggle.hasClass('active')) {
          $toggle.removeClass('active');
          $toggle.attr('aria-expanded', false);
          $toggle.parent().next().slideUp();
        } else {
          $this.find('.accordion-collapse:visible').slideUp();
          $this.find('.accordion-toggle.active').attr('aria-expanded', false).removeClass('active');
          $toggle.addClass('active');
          $toggle.attr('aria-expanded', true);
          $toggle.parent().next().slideDown();
        }
      });

      $this.find('.variations_form').on('woocommerce_variation_select_change', function () {
        if ($this.find('.single_variation_wrap .woocommerce-variation-price .price').find('ins')) {
          $price = $this.find('.single_variation_wrap .woocommerce-variation-price .price').find('ins .woocommerce-Price-amount');
        } else {
          $price = $this.find('.single_variation_wrap .woocommerce-variation-price .price').find('.woocommerce-Price-amount');
        }
        $this.find('.single_add_to_cart_button > *').remove();
        $this.find('.single_add_to_cart_button').append($price);
      });

      $this.find('.variations_form').on('submit', function() {
        $this.find('.single_add_to_cart_button').attr('disabled', true).addClass('loading');
      });

    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_product_single').sg_block_product_single();

  });

}(jQuery));
