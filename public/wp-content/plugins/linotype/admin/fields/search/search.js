(function($) {

$.fn.wp_field_search = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $OUTPUT = $FIELD.find('.wp-field-value');

		var OPTIONS = $.parseJSON( $FIELD.find('.search-options').val() );

    var $PAGE =  $FIELD.find("#pageToken");
    var $NEXT =  $FIELD.find("#pageTokenNext");
    var $PREV =  $FIELD.find("#pageTokenPrev");

    var $SEARCH =  $FIELD.find("#search-search-button")
    var $SEARCH_INPUT =  $FIELD.find('#search-search')

    var $LIST = $FIELD.find("#search-list");


    $FIELD.on("click",'.search-list-item', function() {

			$FIELD.find('#search-select img').attr('src', $(this).attr('data-searchID') );
      $OUTPUT.val( $(this).attr('data-searchID') );

    });


    function searchApiCall() {

      $.ajax({
        cache: false,
        data: {

          q: $SEARCH_INPUT.val(),
          alt: "json",
          searchType: "image",
          cx: "001714412054070837329:a-4s4gmzhl4",
          key: "AIzaSyCiq9kKJvF5ora3N5qHGCInfGoXmTdCJQk",
          // rights: "cc_publicdomain",
          // filter: "1",
          // fileType: "jpg",
          // imgType: "photo"
          num: "10",
          start:$PAGE.val()

        },
        dataType: 'json',
        type: 'GET',
        timeout: 5000,
        url: 'https://www.googleapis.com/customsearch/v1'

      }).done(function(data) {

        console.log(data.queries.nextPage);
        if (typeof data.queries.prevPage === "undefined") { $PREV.hide(); }else{ $PREV.show(); }
        if (typeof data.queries.nextPage === "undefined") { $NEXT.hide(); }else{ $NEXT.show(); }

        var items = data.items, videoList = "";

        $NEXT.val( parseInt( $PAGE.val(), 10 ) + 1 );
        $PREV.val( parseInt( $PAGE.val(), 10 ) - 1 );

        videoList = "";

        $.each( items, function(index,e) {

            videoList += '<li class="search-list-item" data-searchID="'+e.link+'">';

              videoList += '<img alt="'+e.snippet.title+'" src="'+e.link+'" >';

              //videoList += '<span>' + e.snippet.title + '</span>';

            videoList += '</li>';

        });

        $LIST.html( videoList );

				$FIELD.find('#search-select img').attr('src', $LIST.children(":first").attr('data-searchID') );
	      $OUTPUT.val( $LIST.children(":first").attr('data-searchID') );

				$SEARCH_INPUT.change();

      });

    }

    $NEXT.on( "click", function( event ) {
        $PAGE.val( $NEXT.val());
        searchApiCall();
    });

    $PREV.on( "click", function( event ) {
        $PAGE.val($PREV.val());
        searchApiCall();
    });

    $SEARCH.on( "click", function( event ) {
        searchApiCall();
        return false;
    });

    $SEARCH_INPUT.autocomplete({

      source: function( request, response ) {

        var sqValue = [];

        jQuery.ajax({
            type: "POST",
            url: "https://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=search&hjson=t&cp=1",
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

        setTimeout( function () { searchApiCall(); }, 300);

      }

    });

    $(window).keydown(function(event){
      if(event.keyCode == 13 && $FIELD.find(':focus').hasClass('search-search') ) {
        searchApiCall();
        event.preventDefault();
        return false;
      }
    });

	});

}

jQuery(document).ready(function(){

  jQuery('body').find('.wp-field-search').wp_field_search();

});

}(jQuery));
