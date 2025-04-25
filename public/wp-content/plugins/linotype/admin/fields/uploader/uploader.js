(function($) {

$.fn.wp_field_uploader = function(){

	$(this).each(function(){

		var $UPLOADER_PARAMS = field_uploader.plupload_init;

		var $UPLOADER = $(this);
		var $UPLOADER_ID = $UPLOADER.find('.field-uploader').attr('data-id');
		var $UPLOADER_POST_ID = $UPLOADER.find('.field-uploader').attr('data-post-id');
		var $UPLOADER_FORM = $UPLOADER.find('.field-uploader-form');
		var $UPLOADER_FILES = $UPLOADER.find('.field-uploader-files');

		var $UPLOADER_CONTAINER = $UPLOADER.find('#plupload-upload-ui');
		var $UPLOADER_AREA = $UPLOADER.find('#drag-drop-area');

		var $UPLOADER_ERROR = $UPLOADER.find('#media-upload-error');

		var $UPLOADER_MIME_TYPE = $UPLOADER.find('.field-uploader').attr('data-mime-types');
		var $UPLOADER_MAX_FILE_SIZE = $UPLOADER.find('.field-uploader').attr('data-max-file-size');
		var $UPLOADER_MAX_CHUNK_SIZE = $UPLOADER.find('.field-uploader').attr('data-max-chunk-size');

		/*
		*
		* uploader_init
		*
		*/
		function uploader_init() {

			//replace default params with current field
			if ( $UPLOADER_ID ) $UPLOADER_PARAMS.multipart_params.action = 'field_uploader_' + $UPLOADER_ID + '_upload';
			if ( $UPLOADER_POST_ID ) $UPLOADER_PARAMS.multipart_params.post_id = $UPLOADER_POST_ID;
			if ( $UPLOADER_MAX_FILE_SIZE ) $UPLOADER_PARAMS.filters.max_file_size = $UPLOADER_MAX_FILE_SIZE + 'b';
			if ( $UPLOADER_MIME_TYPE ) $UPLOADER_PARAMS.filters.mime_types[0].extensions = $UPLOADER_MIME_TYPE;
			if ( $UPLOADER_MAX_CHUNK_SIZE ) $UPLOADER_PARAMS.chunk_size = $UPLOADER_MAX_CHUNK_SIZE + 'b';

			// Make sure flash sends cookies (seems in IE it does whitout switching to urlstream mode)
			var isIE = navigator.userAgent.indexOf('Trident/') != -1 || navigator.userAgent.indexOf('MSIE ') != -1;
			if ( ! isIE && 'flash' === plupload.predictRuntime( $UPLOADER_PARAMS ) && ( ! $UPLOADER_PARAMS.required_features || ! $UPLOADER_PARAMS.required_features.hasOwnProperty( 'send_binary_string' ) ) ) {
				$UPLOADER_PARAMS.required_features = $UPLOADER_PARAMS.required_features || {};
				$UPLOADER_PARAMS.required_features.send_binary_string = true;
			}

			//new uploder
			uploader = new plupload.Uploader( $UPLOADER_PARAMS );

			//Init
			uploader.bind('Init', function(up) {

				// console.log('Init:');
				// console.log(up);

				if ( up.features.dragdrop && ! $(document.body).hasClass('mobile') ) {

					$UPLOADER_CONTAINER.addClass('drag-drop');

					$UPLOADER_AREA.bind('dragover.wp-uploader', function(){

						$UPLOADER_CONTAINER.addClass('drag-over');

					}).bind('dragleave.wp-uploader, drop.wp-uploader', function(){

						$UPLOADER_CONTAINER.removeClass('drag-over');

					});

				} else {

					$UPLOADER_CONTAINER.removeClass('drag-drop');
					$UPLOADER_AREA.unbind('.wp-uploader');

				}

			});

			//FilesAdded
			uploader.bind('FilesAdded', function( up, files ) {

				// console.log('FilesAdded:');
				// console.log(up, files);

				uploader_state("close");

				$UPLOADER_ERROR.empty();

				plupload.each( files, function( file ) {

					fileQueued( file );

				});

				up.refresh();
				up.start();

			});

			//UploadFile
			uploader.bind('UploadFile', function(up, file) {

				// console.log('UploadFile:');
				// console.log(up, file);

				fileUploading(up, file);

			});

			//UploadProgress
			uploader.bind('UploadProgress', function(up, file) {

				// console.log('UploadProgress:');
				// console.log(up, file);

				uploadProgress(up, file);

			});

			//FileUploaded
			uploader.bind('FileUploaded', function(up, file, response) {

				console.log('FileUploaded:');
				console.log(up, file, response);

				uploadSuccess(file, response.response);

			});

			//UploadComplete
			uploader.bind('UploadComplete', function() {

				// console.log('UploadComplete');

				uploadComplete();

			});

			//Error
			uploader.bind('Error', function(up, err) {

				console.log('Error:');
				console.log(up, err);

				uploadError(err.file, err.code, err.message, up);
				up.refresh();

			});

			//Init
			uploader.init();

		};

		/*
		*
		* fileQueued
		*
		*/
		function fileQueued( fileObj ) {

			$item = $('.field-uploader-file-clone .media-item').clone().attr( 'id', 'media-item-' + fileObj.id ).attr( 'data-filename', fileObj.name );
			$item.find('.filename').text( fileObj.name );
			$( $item ).appendTo( $UPLOADER_FILES );

		}

		/*
		*
		* fileUploading
		*
		*/
		function fileUploading( up, file ) {

			// check to see if a large file failed to upload
			var hundredmb = 100 * 1024 * 1024,
				max = parseInt( up.settings.max_file_size, 10 );

			if ( max > hundredmb && file.size > hundredmb ) {
				setTimeout( function() {
					if ( file.status < 3 && file.loaded === 0 ) { // not uploading
						wpFileError( file, pluploadL10n.big_upload_failed.replace( '%1$s', '<a class="uploader-html" href="#">' ).replace( '%2$s', '</a>' ) );
						up.stop(); // stops the whole queue
						up.removeFile( file );
						up.start(); // restart the queue
					}
				}, 10000 ); // wait for 10 sec. for the file to start uploading
			}

		}

		/*
		*
		* uploadProgress
		*
		*/
		function uploadProgress(up, file) {

			var item = $('#media-item-' + file.id);

			$('.bar', item ).width( ( 200 * file.loaded ) / file.size );
			$('.percent', item).html( file.percent + '%' );

		}

		/*
		*
		* uploadSuccess
		*
		*/
		function uploadSuccess( fileObj, response ) {

			response = JSON.parse(response);

			var item = $('#media-item-' + fileObj.id );

			if ( response.type == "success" ) {

				item.find('.filename').text( fileObj.name );

				$('.progress', item ).hide();
				$('.field-uploader-bt-delete', item ).show();

			} else {

				item.find('.filename').text( fileObj.name + ': ' + response.message );

				$('.progress', item ).hide();
				$('.field-uploader-bt-delete', item ).hide();

			}

			console.log( response.type + ': ' + response.message );

		}

		/*
		*
		* uploadComplete
		*
		*/
		function uploadComplete() {

		}

		/*
		*
		* uploadError
		*
		*/
		function uploadError(fileObj, errorCode, message, uploader) {

			var hundredmb = 100 * 1024 * 1024, max;

			switch (errorCode) {

				case plupload.FAILED:
					wpFileError(fileObj, pluploadL10n.upload_failed);
				break;

				case plupload.FILE_EXTENSION_ERROR:
					wpFileError(fileObj, pluploadL10n.invalid_filetype);
				break;

				case plupload.FILE_SIZE_ERROR:
					uploadSizeError(uploader, fileObj);
				break;

				case plupload.IMAGE_FORMAT_ERROR:
					wpFileError(fileObj, pluploadL10n.not_an_image);
				break;

				case plupload.IMAGE_MEMORY_ERROR:
					wpFileError(fileObj, pluploadL10n.image_memory_exceeded);
				break;

				case plupload.IMAGE_DIMENSIONS_ERROR:
					wpFileError(fileObj, pluploadL10n.image_dimensions_exceeded);
				break;

				case plupload.GENERIC_ERROR:
					wpQueueError(pluploadL10n.upload_failed);
				break;

				case plupload.IO_ERROR:
					max = parseInt( uploader.settings.filters.max_file_size, 10 );

					if ( max > hundredmb && fileObj.size > hundredmb )
						wpFileError( fileObj, pluploadL10n.big_upload_failed.replace('%1$s', '<a class="uploader-html" href="#">').replace('%2$s', '</a>') );
					else
						wpQueueError(pluploadL10n.io_error);
				break;

				case plupload.HTTP_ERROR:
					wpQueueError(pluploadL10n.http_error);
				break;

				case plupload.INIT_ERROR:
					$('.media-upload-form').addClass('html-uploader');
				break;

				case plupload.SECURITY_ERROR:
					wpQueueError(pluploadL10n.security_error);
				break;

				default:
					wpFileError(fileObj, pluploadL10n.default_error);
				break;

			}

		}

		/*
		*
		* wpQueueError
		*
		*/
		function wpQueueError(message) {

			$UPLOADER_ERROR.show().html( '<div class="error"><p>' + message + '</p></div>' );

		}

		/*
		*
		* wpFileError
		*
		*/
		function wpFileError(fileObj, message) {

			itemAjaxError(fileObj.id, message);

		}

		/*
		*
		* itemAjaxError
		*
		*/
		function itemAjaxError(id, message) {

			var item = $('#media-item-' + id), filename = item.find('.filename').text(), last_err = item.data('last-err');

			if ( last_err == id ) return;

			item.html('<div class="error-div">' +
						'<a class="dismiss" href="#">' + pluploadL10n.dismiss + '</a>' +
						'<strong>' + pluploadL10n.error_uploading.replace('%s', $.trim(filename)) + '</strong> ' +
						message +
						'</div>').data('last-err', id);

		}

		/*
		*
		* uploadSizeError
		*
		*/
		function uploadSizeError( up, file, over100mb ) {

			var message;

			if ( over100mb ) {
				message = pluploadL10n.big_upload_queued.replace('%s', file.name) + ' ' + pluploadL10n.big_upload_failed.replace('%1$s', '<a class="uploader-html" href="#">').replace('%2$s', '</a>');
			} else {
				message = pluploadL10n.file_exceeds_size_limit.replace('%s', file.name);
			}

			$('.field-uploader-files').append('<div id="media-item-' + file.id + '" class="media-item error"><p>' + message + '</p></div>');

			up.removeFile(file);

		}

		$UPLOADER.on( 'click', '.field-uploader-bt-add', function(){

			uploader_state("open", true);

		});
		$UPLOADER.on( 'click', '.field-uploader-bt-close', function(){

			uploader_state("close", true);

		});

		function uploader_state( $state, $anim = false ) {

			if ( $state == "open" ) {

				if ( $anim ){
					$UPLOADER_FORM.slideDown( 'fast' );
				} else {
					$UPLOADER_FORM.show();
				}
				$('.field-uploader-bt-add').hide();

			} else {

				if ( $anim ){
					$UPLOADER_FORM.slideUp( 'fast' );
				} else {
					$UPLOADER_FORM.hide();
				}
				$('.field-uploader-bt-add').show();

			}

		}

		/*
		* DELETE
		*/
		function delete_file( $item, $dir, $filename ){

			$item.find('.filename').text( 'deletting' );
			$('.progress', $item ).hide();
			$('.field-uploader-bt-delete', $item ).hide();

			$.ajax({
				url: field_uploader.ajaxurl,
				type : "post",
         		dataType : "json",
				data : {
					action : 'field_uploader_' + $UPLOADER_ID + '_delete',
					post_id: $UPLOADER_POST_ID,
					filename : $filename
				},
				success : function(response){

					if ( response.type == "success" ) {

						$item.remove();

					} else {

					}

					console.log( response.type + ': ' + response.message );

				}

			});

		}

		//action
		$UPLOADER.on( 'click', '.field-uploader-bt-delete', function(){

			$item = $(this).closest('.media-item');

			$dir = $item.attr('data-dir');
			$filename = $item.attr('data-filename');

			delete_file( $item, $dir, $filename );

			if ( $UPLOADER_FILES.children().length == 0 ) {
				uploader_state("open");
			}

		});

		/*
		*
		* uploader_init
		*
		*/
		uploader_init();

	});
}

$(document).ready(function(){

	$('body').find('.wp-field-uploader').wp_field_uploader();

} );


}(jQuery));
