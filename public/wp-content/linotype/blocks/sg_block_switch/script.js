(function ($) {

  $.fn.sg_block_switch = function () {

    $(this).each(function () {

      const $this = $(this);
      
      $this.on('click', '.dropdown-toggle', function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
        $(this).next('.dropdown-menu').slideToggle();
      });

      $(document).click(function(e) {
        $this.find('.dropdown-toggle').removeClass('active');
        $this.find('.dropdown-menu').slideUp();
      });

    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_switch').sg_block_switch();

  });

}(jQuery));
