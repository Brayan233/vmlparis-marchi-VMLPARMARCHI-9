(function ($) {

  $.fn.sg_block_gallery = function () {

    $(this).each(function () {

      var $this = $(this);
      var $body = $('body');
      var $popin = $this.find('.sg_block_gallery-popin');
      var $content = $this.find('.sg_block_gallery-content');

      $this.find('.gallery').slick({
        appendDots: $this,
        dots: true,
        responsive : [
          {
            breakpoint: 992,
            settings: {
              arrows: false,
              variableWidth: true
            }
          }
        ],
        variableWidth: true
      });

      if (!$('.sg_block_gallery-backdrop').length) {
        $body.append('<div class="sg_block_gallery-backdrop"></div>');
      }

      $body.on('click', '.sg_block_gallery-item-image', function() {
        if ( $popin.hasClass('open') ) {
          $popin.removeClass('open');
          $body.find('.sg_block_gallery-backdrop').removeClass('show');
          if (!$body.hasClass('menu-open')) {
            $('html').removeClass('prevent-scroll');
          }
        } else {
          $content.html('<img src="' + $(this).find('.linotype_field_image-img').attr('data-large') +'">');
          // $this.find('.sg_block_gallery-container').animate({
          //   scrollTop: ($(this).outerHeight(true)/2) - 50,
          // }, 100);
          $popin.addClass('open');
          $body.find('.sg_block_gallery-backdrop').addClass('show');
          $('html').addClass('prevent-scroll');
        }
      });

      $body.on('click','.sg_block_gallery-close', function() {
        $popin.removeClass('open');
        $body.find('.sg_block_gallery-backdrop').removeClass('show');
        if (!$body.hasClass('menu-open')) {
          $('html').removeClass('prevent-scroll');
        }
      });
      

    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_gallery').sg_block_gallery();

  });

}(jQuery));
