(function ($) {

  $.fn.sg_block_product_slider = function () {

    $(this).each(function () {

      const $this = $(this);
      
      const productSlider = $this.find('.product-slider').slick({
        dots: true,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              arrows: false,
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ],
        slidesToShow: 4,
        slidesToScroll: 1
      });
      productSlider.on('init setPosition', function() {
        const imgHeight = $(this).find('.linotype_field_image-img').height();
        $(this).find('.slick-arrow').css('top', imgHeight/2);
      });

    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_product_slider').sg_block_product_slider();

  });

}(jQuery));
