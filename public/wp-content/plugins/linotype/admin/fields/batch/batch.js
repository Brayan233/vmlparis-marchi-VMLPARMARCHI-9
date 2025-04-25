(function($) {

$.fn.wp_field_batch = function(){
	
	$(this).each(function(){
	
		var $FIELD = $(this);

		var $VALUE = $FIELD.find('.wp-field-value');
		
		var $OPTIONS = $.parseJSON( $VALUE.val() );

		var $batch_list = $FIELD.find('.batch-list');
		var $bt_start = $FIELD.find('.start-batch');
		var $bt_stop = $FIELD.find('.stop-batch');
		var $messages = $FIELD.find('.messages');
		var $progress = $FIELD.find('.progress');
		var $progress_bar = $FIELD.find('.progress-bar');
		var $progress_percent = $FIELD.find('.progress-percent');
		
		var status = 'ready';
		var scaned = 0;
		var indexed = 0;
		var progress = 0;
		var abort = false;

		$batch_list.find( 'input[type=checkbox]' ).on( 'change', update_ids );

	    function update_ids() {

	    	$ids = "";

	    	$batch_list.find('li').each(function() {

	    		if ( $(this).find( "input[type=checkbox]:checked" ).length ) {
		    		if ( $ids == "" ) {
		    			$ids += $(this).attr('data-batch-id');
		    		} else {
		    			$ids += "," + $(this).attr('data-batch-id');
		    		}
		    	}

	    	});

	    	$OPTIONS.ids = $ids;
	    	$VALUE.val( JSON.stringify( $OPTIONS ) );

	    }

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

		function batch_update_status_item( $ids ) {
			
			$.each( $ids, function( index, value ) {

				$batch_list.find('li#batch-' + value ).addClass('success');
			
			});

		}

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

							message( 'Processing... ' + scaned + '/' + parseInt( response.params.total ) );

							progress = progress + $OPTIONS['batch_size'];
							$percent = parseFloat( ( 100/ parseInt( response.params.total ) ) * progress ).toFixed(2);

							batch_update_status_item( response.params.output );

							stream_progress($percent);

							start( response.params.loop );
							
						break;
						
						case 'finish':
							
							status = 'finish';

							batch_update_status_item( response.params.output );

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

		//message( 'Ready for batch' );


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-batch').wp_field_batch();

});

}(jQuery));
