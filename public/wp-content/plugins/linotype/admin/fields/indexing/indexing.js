(function($) {

$.fn.wp_field_indexing = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);
		var $OPTIONS = $.parseJSON( $FIELD.find('.field-options').val() );

		var $bt_start = $FIELD.find('.start-indexing');
		var $bt_stop = $FIELD.find('.stop-indexing');
		var $messages = $FIELD.find('.messages');
		var $progress = $FIELD.find('.progress');
		var $progress_bar = $FIELD.find('.progress-bar');
		var $progress_percent = $FIELD.find('.progress-percent');
		
		var status = 'ready';
		var scaned = 0;
		var indexed = 0;
		var progress = 0;
		var abort = false;

		$bt_start.on( 'click', function(){

			status = 'running';
			scaned = 0;
			indexed = 0;
			progress = 0;
			abort = false;

			$progress.removeClass('progress-error');
			$progress.removeClass('progress-success');

			start(1);

		});

		$bt_stop.on( 'click', function(){ 

			if ( status == 'running' ) {
			
				message( 'Aborting...', 'error' );
			
				abort = true;
			
			}

		});

		function stream_progress( $percent ) {
			
			if ( $percent > 100 ) $percent = 100;

			$progress_percent.text( Math.floor( $percent ) + '%' );
			$progress_bar.css( 'width', $percent + '%' );
			
		}

		function message( $content, $type ) {
			
			$messages.append( '<li class="message-' + $type + '">' + $content + '</li>' );
			$messages.scrollTop( $messages[0].scrollHeight );

		}

		function start( $loop ) {

			$bt_start.hide();
			$bt_stop.show();

			if ( $loop == 1 ) {
				
				stream_progress(0);
				
				message( 'Starting...', 'success' );

			}

			$OPTIONS.loop = $loop;

			//post value
			$.post( LINOADMIN_AJAX.ajaxurl, $OPTIONS, function( response ) {
			    
			    //parce json response
			    response = $.parseJSON( response );

			    console.log( $OPTIONS, response );

			    //check status
			    if ( response.status == 'success' ) {
			    	
			    	if ( abort == true ) response.params.type = 'abort';
			  		
			  		switch( response.params.type ) {
						
						case 'continue':

							scaned = scaned + response.params.count;
							indexed = indexed + ( response.params.products_count + response.params.variations_count );

							message( 'Indexing... ' + scaned + '/' + parseInt( response.params.total ) + ' - ' + indexed + ' index' );

							progress = progress + $OPTIONS['batch_size'];
							$percent = parseFloat( ( 100/ parseInt( response.params.total ) ) * progress ).toFixed(2);

							stream_progress($percent);

							start( response.params.loop );
							
						break;
						
						case 'finish':
							
							status = 'finish';

							stream_progress(100);

							$progress.addClass('progress-success');
							
							$bt_start.show();
							$bt_stop.hide();

							message( 'Finish', 'success' );
									
						break;

						case 'abort':
							
							status = 'abort';

							abort = false;

							$progress.addClass('progress-error');

							$bt_start.show();
							$bt_stop.hide();

							message( 'Aborted', 'error' );

						break;

					}
					
			    } else {
			    	
			    	$bt_start.show();
					$bt_stop.hide();

			    	message( response.message, 'error' );

			    }

			});

		}

		//message( 'Ready for indexing' );


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-indexing').wp_field_indexing();

});

}(jQuery));
