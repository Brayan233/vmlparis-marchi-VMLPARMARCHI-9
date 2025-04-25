(function ($) {

  $.fn.sg_block_panel = function () {

    $(this).each(function () {

      var $this = $(this);
      var $close = $this.find('.sg_block_panel-close');
      var trigger = $this.data('trigger');
      var $body = $('body');

      $(trigger).addClass('sg_block_panel-trigger');

      if (window.matchMedia('only screen and (min-width: 992px)').matches) {
        if (!$('.panel-backdrop').length) {
          $body.append('<div class="panel-backdrop"></div>');
        }
      }

      $body.on('click', trigger, function() {

        if ( $this.hasClass('open') ) {

          $this.removeClass('open');
          $(this).removeClass('active');

          if (window.matchMedia('only screen and (min-width: 992px)').matches) {
            $body.find('.panel-backdrop').removeClass('show');
          }
          $body.removeClass('prevent-scroll');

        } else {

          if (window.matchMedia('only screen and (min-width: 992px)').matches) {
            $body.find('.panel-backdrop').addClass('show');
          }
          $body.addClass('prevent-scroll');

          $body.find('.sg_block_panel').removeClass('open');
          $body.find('.sg_block_panel-trigger').removeClass('active');

          $this.addClass('open');
          $(this).addClass('active');

        }

      });

      $close.on('click', function() {

        $body.find('.sg_block_panel').removeClass('open');
        $body.find('.sg_block_panel-trigger').removeClass('active');
        
        if (window.matchMedia('only screen and (min-width: 992px)').matches) {
          $body.find('.panel-backdrop').removeClass('show');
        }
        
        if (!$body.hasClass('menu-open')) {
          $body.removeClass('prevent-scroll');
        }

      });

      $this.find('.btn-login-panel').on('click', function() {
        $this.find('.panel-register').slideUp();
        $this.find('.panel-login').slideDown();
      });

      $this.find('.btn-register-panel').on('click', function() {
        $this.find('.panel-login').slideUp();
        $this.find('.panel-register').slideDown();
      });
      
    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_panel').sg_block_panel();

  });

}(jQuery));
