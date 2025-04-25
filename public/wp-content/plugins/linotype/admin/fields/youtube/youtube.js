(function($) {

$.fn.wp_field_youtube = function(player){

  var yplayer = player;

	$(this).each(function(){

		var $FIELD = $(this);
		var $OUTPUT = $FIELD.find('.wp-field-value');

		var OPTIONS = $.parseJSON( $FIELD.find('.youtube-options').val() );

    var $PAGE =  $FIELD.find("#pageToken");
    var $NEXT =  $FIELD.find("#pageTokenNext");
    var $PREV =  $FIELD.find("#pageTokenPrev");

    var $SEARCH =  $FIELD.find("#youtube-search-button")
    var $SEARCH_INPUT =  $FIELD.find('#youtube-search')

    var $LIST = $FIELD.find("#youtube-video-list");

    $FIELD.on("click",'.youtube-video-list-item', function() {

      play( $(this).attr('data-youtubeID') );

    });

    function play( youtubeID ) {

      yplayer.loadVideoById({
        videoId : youtubeID
      })

      $OUTPUT.val( youtubeID );

      $(".youtube-player").show();

    }

    function youtubeApiCall() {

      $.ajax({
        cache: false,
        data: {
          key: 'AIzaSyBr12gtFFgVo-MloZTzBFDNVBpS2FYSjmc',
          q: $SEARCH_INPUT.val(),
          part: 'snippet',
          maxResults:10,
          pageToken:$PAGE.val()
        },
        dataType: 'json',
        type: 'GET',
        timeout: 5000,
        url: 'https://www.googleapis.com/youtube/v3/search'

      }).done(function(data) {

        if (typeof data.prevPageToken === "undefined") { $PREV.hide(); }else{ $PREV.show(); }
        if (typeof data.nextPageToken === "undefined") { $NEXT.hide(); }else{ $NEXT.show(); }

        var items = data.items, videoList = "";

        $NEXT.val( data.nextPageToken );
        $PREV.val( data.prevPageToken );

        videoList = "";

        $.each( items, function(index,e) {

            videoList += '<li class="youtube-video-list-item" data-youtubeID="'+e.id.videoId+'">';

              videoList += '<img alt="'+e.snippet.title+'" src="'+e.snippet.thumbnails.default.url+'" >';

              videoList += '<span>' + e.snippet.title + '</span>';

            videoList += '</li>';

        });

        $LIST.html( videoList );

        play( $LIST.children(":first").attr('data-youtubeID') );

        $SEARCH_INPUT.change();

      });

    }

    $NEXT.on( "click", function( event ) {
        $PAGE.val( $NEXT.val());
        youtubeApiCall();
    });

    $PREV.on( "click", function( event ) {
        $PAGE.val($PREV.val());
        youtubeApiCall();
    });

    $SEARCH.on( "click", function( event ) {
        youtubeApiCall();
        return false;
    });

    $SEARCH_INPUT.autocomplete({

      source: function( request, response ) {

        var sqValue = [];

        jQuery.ajax({
            type: "POST",
            url: "https://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=youtube&hjson=t&cp=1",
            dataType: 'jsonp',
            data: {
              q: request.term
            },
            success: function(data){

                obj = data[1];

                jQuery.each( obj, function( key, value ) {
                    sqValue.push(value[0]);
                });

                response( sqValue);

            }
        });

      },

      select: function( event, ui ) {

        setTimeout( function () { youtubeApiCall(); }, 300);

      }

    });

    $(window).keydown(function(event){
      if(event.keyCode == 13 && $FIELD.find(':focus').hasClass('youtube-search') ) {
        youtubeApiCall();
        event.preventDefault();
        return false;
      }
    });

	});

}





}(jQuery));

var player;
var currentsong;

function onYouTubePlayerAPIReady() {

  player = new YT.Player('youtube-video', {
    playerVars: { 'autoplay': 0, 'controls': 0 },
    events: {
      'onReady': onPlayerReady,
    }
  });

}

function onPlayerReady(){
  console.log('onPlayerReady');
  jQuery('body').find('.wp-field-youtube').wp_field_youtube(player);

}


// jQuery(document).ready(function(){
// });
