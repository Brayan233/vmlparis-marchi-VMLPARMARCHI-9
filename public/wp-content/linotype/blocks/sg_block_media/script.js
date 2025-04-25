(function ($) {

  $.fn.sg_block_media = function () {

    $(this).each(function () {

      var $this = $(this);
      var video_id = $this.data('video_id');

      if( video_id ) {

        $this.find('.col-image').on( "click", function() 
        {
          var iframe;
          iframe = $('<iframe src="https://www.youtube.com/embed/' + video_id + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0&autoplay=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>');
          $this.find('.col-image iframe').remove();
          $this.find('.col-image').append( iframe );
          $this.find('.col-image').addClass('playing');
        });

      }


    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_media').sg_block_media();

  });

}(jQuery));
