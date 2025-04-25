(function($) {

$.fn.wp_field_lastFM = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $OUTPUT = $FIELD.find('.wp-field-value');

		var OPTIONS = $.parseJSON( $FIELD.find('.lastFM-options').val() );

    var $PAGE =  $FIELD.find("#pageToken");
    var $NEXT =  $FIELD.find("#pageTokenNext");
    var $PREV =  $FIELD.find("#pageTokenPrev");

    var $SEARCH =  $FIELD.find("#lastFM-search-button")
    var $SEARCH_INPUT =  $FIELD.find('#lastFM-search')

    var $LIST = $FIELD.find("#lastFM-list");


    $FIELD.on("click",'.lastFM-list-item', function() {

      $OUTPUT.val( $(this).attr('data-lastFMID') );

    });


    function lastFMApiCall( track, artist ) {
			console.log( 'lastFMApiCall' );
      $.ajax({
        cache: false,
        data: {

					method:'track.getInfo',
					format:'json',
					track: track,
					artist: artist,
					api_key: 'f667a6680ce29e54221473bba0e889af'

        },
        dataType: 'json',
        type: 'GET',
        timeout: 5000,
        url: 'https://ws.audioscrobbler.com/2.0/'

      }).done(function(data) {

        console.log(data);

				$OUTPUT.val( JSON.stringify(data) );

				$SEARCH_INPUT.change();

        // if (typeof data.startIndex === "undefined") { $PREV.hide(); }else{ $PREV.show(); }
        // if (typeof data.startIndex === "undefined") { $NEXT.hide(); }else{ $NEXT.show(); }
				//
        // var items = data.results.trackmatches.track, videoList = "";
				//
        // $NEXT.val( parseInt( $PAGE.val(), 10 ) + 1 );
        // $PREV.val( parseInt( $PAGE.val(), 10 ) - 1 );
				//
        // videoList = "";
				//
        // $.each( items, function(index,e) {
				//
        //     videoList += '<li class="lastFM-list-item" data-lastFMID="' + e.mbid + '">';
				//
        //       videoList += '<span>' + e.name + ' - ' + e.artist + '</span>';
				//
        //     videoList += '</li>';
				//
        // });
				//
        // $LIST.html( videoList );

      });

    }

    $NEXT.on( "click", function( event ) {
        $PAGE.val( $NEXT.val());
        lastFMApiCall();
    });

    $PREV.on( "click", function( event ) {
        $PAGE.val($PREV.val());
        lastFMApiCall();
    });

    $SEARCH.on( "click", function( event ) {
        lastFMApiCall();
        return false;
    });


    $SEARCH_INPUT.autocomplete({

      source: function( request, response ) {

        var sqValue = [];

        jQuery.ajax({
            type: "POST",
            url: "https://ws.audioscrobbler.com/2.0/",
            dataType: 'jsonp',
            data: {
							method:'track.search',
							format:'json',
							limit: 10,
							page: $PAGE.val(),
							track: request.term,
							api_key: 'f667a6680ce29e54221473bba0e889af'
            },
            success: function(data){

                obj = data.results.trackmatches.track;

                jQuery.each( obj, function( key, value ) {
                    sqValue.push( { label : value.name + ' - ' + value.artist, value : value.name + ' - ' + value.artist, data: { track: value.name, artist: value.artist } } );
                });

                response( sqValue );

            }
        });

      },

      select: function( event, ui ) {

        setTimeout( function () { lastFMApiCall( ui.item.data.track, ui.item.data.artist ); }, 300);

      }

    });


    $(window).keydown(function(event){
      if(event.keyCode == 13 && $FIELD.find(':focus').hasClass('lastFM-search') ) {
        lastFMApiCall();
        event.preventDefault();
        return false;
      }
    });

	});

}

jQuery(document).ready(function(){

  jQuery('body').find('.wp-field-lastFM').wp_field_lastFM();

});

}(jQuery));
