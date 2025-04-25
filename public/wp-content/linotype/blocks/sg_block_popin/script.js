(function ($) {

  $.fn.sg_block_popin = function () {

    $(this).each(function () {

      var $this = $(this);
      var uid = $this.data('uid');
      var trigger = $this.data('trigger');
      var trigger_by_delay = $this.data('trigger_by_delay');
      var $force_date_start = $this.data('force_date_start');
      var $force_date_end = $this.data('force_date_end');
      var $body = $('body');

      var oneYearFromNow = new Date();
      oneYearFromNow.setFullYear( oneYearFromNow.getFullYear() + 1 );

      if (!$('.popin-backdrop').length) {
        $body.append('<div class="popin-backdrop"></div>');
      }

      if ( trigger ) {
        $(trigger).addClass('sg_block_popin-trigger');
        $body.on('click', trigger, function() {
          if ( $this.hasClass('open') ) {
            $this.removeClass('open');
            $body.find('.popin-backdrop').removeClass('show');
            if (!$body.hasClass('menu-open')) {
              $('html').removeClass('prevent-scroll');
            }
          } else {
            $this.addClass('open');
            $body.find('.popin-backdrop').addClass('show');
            $('html').addClass('prevent-scroll');
          }
        });
      }

      $body.on('click','.sg_block_popin-close', function() {
        $this.removeClass('open');
        $body.find('.popin-backdrop').removeClass('show');
        if (!$body.hasClass('menu-open')) {
          $('html').removeClass('prevent-scroll');
        }
        $body.find('.sg_block_popin-content').remove();
        document.cookie = 'sg_block_popin_' + uid + '=closed; expires=' + oneYearFromNow;
      });

      var getCookie = function (name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
      };
      
      if ( getCookie( 'sg_block_popin_' + uid ) !== 'closed' ) {
        setTimeout( function(){
          $this.addClass('open');
          $body.find('.popin-backdrop').addClass('show');
          $('html').addClass('prevent-scroll');
        }, parseInt( trigger_by_delay, 10) *1000 );
      }

      if ( $force_date_start && $force_date_end ) {
        $date_start = new Date($force_date_start).getTime();
        $date_end = new Date($force_date_end).getTime();
        $date_current = new Date().getTime();
        if ( $date_current > $date_start && $date_current < $date_end ) {
          setTimeout( function(){
            $this.addClass('open');
            $body.find('.popin-backdrop').addClass('show');
            $('html').addClass('prevent-scroll');
          }, parseInt( trigger_by_delay, 10) *1000 );
        }
      }
      
    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_popin').sg_block_popin();

  });

}(jQuery));
