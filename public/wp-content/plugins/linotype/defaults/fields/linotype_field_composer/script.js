(function($) {

$.fn.linotype_field_composer = function(){

	$(this).each(function(){
		
		var $el = this;

		var $FIELD = $(this);

		var $VALUE = $FIELD.find('.wp-field-value');

		var $OPTIONS = $.parseJSON( $FIELD.find('.wp-field-options').val() );
		
		var $COMPOSER_OVERWRITE = $OPTIONS['overwrite'];

		var $COMPOSER = $FIELD.find('.composer-editor');

		var $COMPOSER_ROWS = $COMPOSER.find('.composer-layout > .composer-items');

		var $COMPOSER_ID = $COMPOSER.attr('composer-id');

		var $COMPOSER_ITEM_TYPE = $COMPOSER.attr('composer-item-type');

		var $MODAL_EDIT = $FIELD.find('> .field-content > .composer-editor > .composer-modal-edit');

		var $MODAL_ADD = $FIELD.find('> .field-content > .composer-editor > .composer-modal-add');

		var $PREVIEW = $FIELD.find('.composer-preview');

		var $HISTORY = [];

		var $HISTORY_current = 0;

		var $HISTORY_current_pos = 0;

		var $COPY = "";
    
		var LINOTYPE_composer_elements_allow = $.parseJSON( $FIELD.find('.LINOTYPE_composer_elements_allow').val() );

		LINOTYPE_composer_elements_allow.root = {'accept':'all','parent':'all'};
		
		var el_dragula;
    
		/*
		 *
		 * sortable_items
		 *
		*/
		function sortable_items() {
			
			if ( typeof el_dragula === "object" ) el_dragula.destroy();

			var $ui_item_source_parent;

			el_dragula = dragula([].slice.apply($el.querySelectorAll('.composer-items') ), {

				direction: 'mixed',
				
				moves: function (el, container, handle) {
					return handle.classList.contains('handle') || 
						handle.parentNode.classList.contains('handle');
				},

				accepts: function (el, target, source, sibling) {

					var $accept = false;
					var $parent = false;

					var $current_id = $(el).attr('composer-item-type');
					var $target_id = $(target).closest('.composer-item').attr('composer-item-type');
					
					if ( $(target).hasClass('composer-items-root') ) $target_id = 'root';

					//check if parent accept current
					if ( typeof LINOTYPE_composer_elements_allow[ $target_id ] !== 'undefined' && typeof LINOTYPE_composer_elements_allow[ $target_id ]['accept'] !== 'undefined' ) {

						if( $.inArray( $current_id, LINOTYPE_composer_elements_allow[ $target_id ]['accept'] ) !== -1 ) $accept = true;
						if( LINOTYPE_composer_elements_allow[ $target_id ]['accept'] == 'all' ) $accept = true;
					
					}

					//check if current require parent
					if ( typeof LINOTYPE_composer_elements_allow[ $current_id ] !== 'undefined' && typeof LINOTYPE_composer_elements_allow[ $current_id ]['parent'] !== 'undefined' ) {
					
						if( $.inArray( $target_id, LINOTYPE_composer_elements_allow[ $current_id ]['parent'] ) !== -1 ) $parent = true;
						if( LINOTYPE_composer_elements_allow[ $current_id ]['parent'] == 'all' ) $parent = true;
						
					}

					if ( $parent && $accept ) {

						return true; 

					} else {

						return false;
						
					}

				},

			} ).on('drag', function (el) {

				$ui_item_source_parent = $(el).parent().closest('.composer-item');

			}).on('drop', function (el) {

			}).on('over', function (el, container) {

			}).on('dragend', function (el, container) {

				$('body').trigger('composer-sort', $ui_item_source_parent );
				$('body').trigger('composer-sort', $(el).parent().closest('.composer-item') );

			});

		}

		//sortable_rows();
		sortable_items();


		/*
		 *
		 * open_modal_add
		 *
		*/
		var $current_item_target;

		function open_modal_add(e){
			
			if ( e.ctrlKey ) {

				if( $(this).hasClass('composer-item-add-root') ) {
					$target = $(this).closest('.composer-layout').find( '> .composer-items' );
				} else {
					$target = $(this).closest('.composer-items');
				}
				
				$block_copy.clone().appendTo( $target );

				// $block_copy = null;

				$('body').trigger('composer-add', $target.parent().closest('.composer-item') );
			
			} else {

				$allowed_items = [];
				//console.log(LINOTYPE_composer_elements_allow);
				$.each( LINOTYPE_composer_elements_allow, function( index, element_id ){

					$allowed_items.push( index );

				});

				if( $(this).hasClass('composer-item-add-root') ) {

					$current_item_target = $(this).closest('.composer-layout').find( '> .composer-items' );

					$.each( $allowed_items, function( index, element_id ) {

						if ( LINOTYPE_composer_elements_allow[ element_id ]['parent'] == 'all' ) {

						} else {

							$allowed_items = removeFromArray( element_id, $allowed_items );

						}

					});

				} else {

					$current_item_target = $(this).closest('.composer-items');

					$current_item_type = $(this).closest('.composer-item').attr('composer-item-type');
					$current_item_parent_type = $(this).closest('.composer-item').parent().closest('.composer-item').attr('composer-item-type');
					if ( ! $current_item_parent_type ) $current_item_parent_type = 'root';

					$.each( $allowed_items, function( index, element_id ) {

						if ( LINOTYPE_composer_elements_allow[ element_id ]['parent'] == 'all' ) {

						} else if ( $current_item_parent_type !== 'root' && ! jQuery.inArray( $current_item_parent_type, LINOTYPE_composer_elements_allow[ element_id ]['parent'] ) ) {

							$allowed_items = removeFromArray( element_id, $allowed_items );

						} else if( $current_item_parent_type == 'root' ) {

							$allowed_items = removeFromArray( element_id, $allowed_items );

						}

					});

					if ( LINOTYPE_composer_elements_allow[ $current_item_type ]['accept'] === 'all' ) {

					} else {

						$allowed_items = [];

						$.each( LINOTYPE_composer_elements_allow[ $current_item_type ]['accept'], function( index, element_id ){

							$allowed_items.push( element_id );

						});

					}

				}

				$MODAL_ADD.find('.composer-add-item').removeClass('active');

				$.each( $allowed_items, function( index, element_id ){

					$MODAL_ADD.find( '#' + element_id ).addClass('active');

				});

				if ( $MODAL_ADD.find('.composer-add-item.active').length == 1 ) {

					// $COMPOSER.removeClass('open');
					add_item_single( $MODAL_ADD.find('.composer-add-item.active') );

				} else {

					$COMPOSER.addClass('open');
					$MODAL_ADD.addClass('show');

				}

			}



		}


		/*
		 *
		 * add_item
		 *
		*/
		function add_item(e) {

			$this = $(this).closest('.composer-add-item');

			$target = $MODAL_ADD.attr('composer-target');

			$model = $( $this.find('.composer-template-item')[0].innerText );

			$clone = $model.clone();

			$item_default_type = $this.attr('composer-item-type');
			$item_default_title = $this.attr('composer-item-title');
			$item_default_icon = $this.attr('composer-item-icon');
			if ( ! $item_default_icon ) $item_default_icon = 'dashicons dashicons-admin-generic';

			var uniqueID = getUniqueID();

			//$clone.attr('id', uniqueID );

			$clone.attr('composer-item-type', $item_default_type );
			$clone.attr('composer-item-title', $item_default_title );
			$clone.attr('composer-item-icon', $item_default_icon );

			$clone.find('.composer-item-title').html( '<span class="composer-item-icon composer-bt ' + $item_default_icon + '"></span>' + $item_default_title );
			$clone.find('.composer-item-value').val( '{ "type":"' + $item_default_type + '"}' );

			$clone.appendTo( $current_item_target );

			$('body').trigger('composer-add', $($current_item_target).parent().closest('.composer-item') );

			close_modal_add();

		}


		/*
		 *
		 * add_item_single
		 *
		*/
		function add_item_single( $this ) {

			$target = $MODAL_ADD.attr('composer-target');

			$model = $( $this.find('.composer-template-item')[0].innerText );

			$clone = $model.clone();

			$item_default_type = $this.attr('composer-item-type');
			$item_default_title = $this.attr('composer-item-title');
			$item_default_icon = $this.attr('composer-item-icon');
			if ( ! $item_default_icon ) $item_default_icon = 'no-icon';

			var uniqueID = getUniqueID();
			
			$clone.attr('composer-item-type', $item_default_type );
			$clone.attr('composer-item-title', $item_default_title );
			$clone.attr('composer-item-icon', $item_default_icon );

			$clone.find('.composer-item-title').html( '<span class="composer-item-icon composer-bt ' + $item_default_icon + '"></span>' + $item_default_title );
			$clone.find('.composer-item-value').val( '{ "type":"' + $item_default_type + '"}' );

			$clone.appendTo( $current_item_target );

			$('body').trigger('composer-add', $($current_item_target).parent().closest('.composer-item') );

		}

		$COMPOSER.on( 'click', '.composer-item-add', add_item ) ;


		/*
		 *
		 * composer_item_edit
		 *
		*/
		var $current_item;

		var $current_item_deep = 0;

		function composer_item_edit(e){

			$MODAL_EDIT.find('iframe').attr('src', '' );

			$COMPOSER.addClass('open');

			$current_item_deep++;

			$current_item = {};

			$current_item['this'] = $(this).closest('.composer-item' );
			$current_item['infos'] = $current_item['this'].find('> .composer-item-toolbar > .composer-item-toolbar-left > .composer-item-infos');
			$current_item['value'] = $current_item['this'].find('> .composer-item-value');
			$current_item['settings'] = $current_item['value'].val();

			$current_item['type'] = $current_item['this'].attr('composer-item-type');
			$current_item['title'] = $current_item['this'].attr('composer-item-title');
			$current_item['icon'] = $current_item['this'].attr('composer-item-icon');
			$current_item['settings_array'] = $.parseJSON( $current_item['settings'] );

			$MODAL_EDIT.find('.composer-modal-toolbar-title').text( 'Edit ' + $current_item['title'] ) ;

			$MODAL_EDIT.addClass('show');

			$COMPOSER.find('.composer-item' ).removeClass('active');
			$current_item['this'].addClass('active');

			$.ajax({
			 	type : "post",
			 	dataType : "json",
			 	url : LINOTYPE_composer_settings.ajaxurl,
			 	data : {
			 		action: "LINOTYPE_composer_temp_settings",
					 settings : $current_item['settings'],
					 block_id : $current_item['type']
			 	},
				success: function(response) {

						 if(response.type == "success") {

							var $timestamp = new Date().getUTCMilliseconds();
               
							var iframe_url_global  = LINOTYPE_composer_settings.editurl + '?action=LINOTYPE_composer_edit&composer_id=' + $COMPOSER_ID + '&composer_type=' + $COMPOSER_ITEM_TYPE + '&composer_overwrite=' + $COMPOSER_OVERWRITE + '&id=' + response.block_id + '&device=global&timestamp=' + $timestamp;
							var iframe_url_mobile  = LINOTYPE_composer_settings.editurl + '?action=LINOTYPE_composer_edit&composer_id=' + $COMPOSER_ID + '&composer_type=' + $COMPOSER_ITEM_TYPE + '&composer_overwrite=' + $COMPOSER_OVERWRITE + '&id=' + response.block_id + '&device=mobile&timestamp=' + $timestamp;
							var iframe_url_tablet  = LINOTYPE_composer_settings.editurl + '?action=LINOTYPE_composer_edit&composer_id=' + $COMPOSER_ID + '&composer_type=' + $COMPOSER_ITEM_TYPE + '&composer_overwrite=' + $COMPOSER_OVERWRITE + '&id=' + response.block_id + '&device=tablet&timestamp=' + $timestamp;
							var iframe_url_desktop = LINOTYPE_composer_settings.editurl + '?action=LINOTYPE_composer_edit&composer_id=' + $COMPOSER_ID + '&composer_type=' + $COMPOSER_ITEM_TYPE + '&composer_overwrite=' + $COMPOSER_OVERWRITE + '&id=' + response.block_id + '&device=desktop&timestamp=' + $timestamp;
							
							$MODAL_EDIT.find('iframe#composer-iframe-edit-global').attr('src', iframe_url_global );
							$MODAL_EDIT.find('iframe#composer-iframe-edit-mobile').attr('data-src', iframe_url_mobile );
							$MODAL_EDIT.find('iframe#composer-iframe-edit-tablet').attr('data-src', iframe_url_tablet );
							$MODAL_EDIT.find('iframe#composer-iframe-edit-desktop').attr('data-src', iframe_url_desktop );
							
							$MODAL_EDIT.find('.composer-item-device > li').on('click', function(){
							
								$MODAL_EDIT.find('.composer-item-device > li').removeClass('selected');
								$(this).addClass('selected');
								
								$MODAL_EDIT.find( 'iframe.composer-iframe-edit' ).removeClass('selected');
								$MODAL_EDIT.find( '#' + $(this).attr('data-target') ).addClass('selected');
								if ( $MODAL_EDIT.find( '#' + $(this).attr('data-target') ).attr('src') == "" ) $MODAL_EDIT.find( '#' + $(this).attr('data-target') ).attr('src', $MODAL_EDIT.find( '#' + $(this).attr('data-target') ).attr('data-src') );
								
							});
              
							$MODAL_EDIT.find('iframe.composer-iframe-edit').on('load', function(){

								$(this).show();

							});

				    } else {

				       console.log("error")

				    }

			 	},

				error: function(response) {
					console.log('error');
				}

			});

		}

		$MODAL_EDIT.on( 'click', '.composer-item-save', composer_item_save_all ) ;

		/*
		 *
		 * composer_item_save_all
		 *
		*/
		function composer_item_save_all(e){
			
			$current_item['settings_array']['options'] = {};

			$MODAL_EDIT.find('iframe#composer-iframe-edit-global').contents().find(".wp-field-value").each(function(field_index, field ) {

				$field_id = $(field).attr('id');

				if ( $field_id ) {

					$field_value = $(field).val();
					if( $(field).attr('type') == 'checkbox' && ! $(field).is(':checked') ) $field_value = "";

					if ( $field_value ) {

						try {

							$current_item['settings_array']['options'][ $field_id ] = $.parseJSON( $field_value );

						} catch(e) {

							$current_item['settings_array']['options'][ $field_id ] = $field_value;

						}

					} else {

						delete $current_item['settings_array']['options'][ $field_id ];

					}

				}

			});

			var $fields_mobile = $MODAL_EDIT.find('iframe#composer-iframe-edit-mobile').contents().find(".wp-field-value");
			
			if ( $fields_mobile.length ) {

				var $options_mobile = {};

				$fields_mobile.each(function(field_index, field ) {

					$field_id = $(field).attr('id');

					if ( $field_id ) {

						$field_value = $(field).val();
						if( $(field).attr('type') == 'checkbox' && ! $(field).is(':checked') ) $field_value = "";

						if ( $field_value && String( $current_item['settings_array']['options'][ $field_id ] ) !== String( $field_value ) )  {

							try {

								$options_mobile[ $field_id ] = $.parseJSON( $field_value );

							} catch(e) {

								$options_mobile[ $field_id ] = $field_value;

							}

						}

					}

				});
				
				if ( Object.keys( $options_mobile ).length !== 0 ) $current_item['settings_array']['options_mobile'] = $options_mobile;

			}

			var $fields_tablet = $MODAL_EDIT.find('iframe#composer-iframe-edit-tablet').contents().find(".wp-field-value");
			
			if ( $fields_tablet.length ) {

				var $options_tablet = {};

				$fields_tablet.each(function(field_index, field ) {

					$field_id = $(field).attr('id');

					if ( $field_id ) {

						$field_value = $(field).val();
						if( $(field).attr('type') == 'checkbox' && ! $(field).is(':checked') ) $field_value = "";

						if ( $field_value && String( $current_item['settings_array']['options'][ $field_id ] ) !== String( $field_value ) )  {

							try {

								$options_tablet[ $field_id ] = $.parseJSON( $field_value );

							} catch(e) {

								$options_tablet[ $field_id ] = $field_value;

							}

						}

					}

				});
				
				if ( Object.keys( $options_tablet ).length !== 0 ) $current_item['settings_array']['options_tablet'] = $options_tablet;

			}


			var $fields_desktop = $MODAL_EDIT.find('iframe#composer-iframe-edit-desktop').contents().find(".wp-field-value");
			
			if ( $fields_desktop.length ) {

				var $options_desktop = {};

				$fields_desktop.each(function(field_index, field ) {

					$field_id = $(field).attr('id');

					if ( $field_id ) {

						$field_value = $(field).val();
						if( $(field).attr('type') == 'checkbox' && ! $(field).is(':checked') ) $field_value = "";

						if ( $field_value && String( $current_item['settings_array']['options'][ $field_id ] ) !== String( $field_value ) )  {

							try {

								$options_desktop[ $field_id ] = $.parseJSON( $field_value );

							} catch(e) {

								$options_desktop[ $field_id ] = $field_value;

							}

						}

					}

				});
				
				if ( Object.keys( $options_desktop ).length !== 0 ) $current_item['settings_array']['options_desktop'] = $options_desktop;

			}

			if ( $current_item['settings_array']['id'] ) delete $current_item['settings_array']['id'];

			if ( Array.isArray( $current_item['settings_array']['options'] )  || jQuery.isEmptyObject( $current_item['settings_array']['options']  ) ) delete $current_item['settings_array']['options'];
			if ( Array.isArray( $current_item['settings_array']['params'] )   || jQuery.isEmptyObject( $current_item['settings_array']['params']   ) ) delete $current_item['settings_array']['params'];
			if ( Array.isArray( $current_item['settings_array']['contents'] ) || jQuery.isEmptyObject( $current_item['settings_array']['contents'] ) ) delete $current_item['settings_array']['contents'];

			if ( $current_item['settings_array']['options'] && ! jQuery.isEmptyObject( $current_item['settings_array']['options'] ) ) {

				$settings_array_json = JSON.stringify( $current_item['settings_array'] );

				$current_item['value'].val( $settings_array_json ).change();

				 refresh_item( $current_item['this'] );

				$('body').trigger('composer-edit', $current_item['this'].parent().closest('.composer-item') );

			} else {

				console.log('composer_item_save_all::error::nodata');

			}

			$MODAL_EDIT.find('iframe').attr('src', '' );

			$COMPOSER.removeClass('open');
			$MODAL_EDIT.removeClass('show');

		}
		
		$COMPOSER.on( 'click', '.composer-item-title', composer_item_edit ) ;
		$COMPOSER.on( 'dblclick', '.composer-item-handlebar', composer_item_edit ) ;
		$COMPOSER.on( 'click', '.composer-item-edit', composer_item_edit ) ;

		/*
		 *
		 * clone_item
		 *
		*/
		function clone_item(e) {

			$model = $(this).closest('.composer-item');

			$clone = $model.clone();

			var uniqueID = getUniqueID();
			
			//$clone.attr('id', uniqueID );
			
			$field = $clone.find('> .composer-item-value');

			$value = $field.val();
			
			if ( $value ) $value = $.parseJSON( $value );

			if ( $value.id ) delete $value.id;

			$field.val( JSON.stringify( $value ) );

			$clone.insertAfter($model);

			$('body').trigger('composer-clone', $model.parent().closest('.composer-item') );

		}

		$COMPOSER.on( 'click', '.composer-item-clone', clone_item ) ;


		function source_item(e) {

			$item = $(this).closest('.composer-item');

			$field = $item.find('> .composer-item-value');
			
			if ( $item.hasClass('composer-value-show') ) {
				
				$item.removeClass('composer-value-show');

				$('body').trigger('composer-update', $item.parent().closest('.composer-item') );

			} else {
				
				$field.val( JSON.stringify( $.parseJSON( $field.val() ), null, 2 ) );

				$item.addClass('composer-value-show');

			}

			refresh_item( $item );

		}

		$COMPOSER.on( 'click', '.composer-item-source', source_item ) ;

		function refresh_item($item) {

			$item_id = $item.attr('composer-item-type');
			$preview = $item.find('> .composer-item-preview');
			$params = $item.find('> .composer-item-value').val();
			
			if ( $item_id !== "" && $params !== "" ) {

				$preview.css('opacity','0.2');

				$.ajax({
					type : "post",
					dataType : "json",
					url : LINOTYPE_composer_settings.ajaxurl,
					data : {
						action: "LINOTYPE_composer_admin_refresh",
						item_id : $item_id,
						params : $params
					},
					success: function(response) {

						if ( response.type == "success" ) {

							
							if ( response.content ) {
								console.log(response);
								$preview.html( response.content );
							
							} else {

								console.log('element-refresh::response::error');
							
							}

							$preview.css('opacity','1');

						} else {

							console.log("error")

						}

					},

				error: function(response) {
					console.log('error');
				}

			});
		   
			} else {

				console.log('element-refresh::init::error');

			}

		}

		/*
		 *
		 * clone_item
		 *
		*/
		var $block_copy = null;

		function copy_item(e) {

			$model = $(this).closest('.composer-item');

			$copy = $model.clone();

			var uniqueID = getUniqueID();
			
			//$copy.attr('id', uniqueID );
			
			$field = $copy.find('> .composer-item-value');

			$value = $field.val();
			
			if ( $value ) $value = $.parseJSON( $value );

			//$value.id = uniqueID;
			if ( $value.id ) delete $value.id;

			$field.val( JSON.stringify( $value ) );

			$block_copy = $copy;

		}

		$COMPOSER.on( 'click', '.composer-item-copy', copy_item ) ;

		
		/*
		 *
		 * overwrite_item
		 *
		*/
		function overwrite_item(e) {

			$flag = $(this);

			$item = $(this).closest('.composer-item');

			$toolbar = $item.find('> .composer-item-toolbar .composer-item-overwrite-toolbar');

			$field = $item.find('> .composer-item-value');

			$value = $field.val();
			
			if ( $value ) $value = $.parseJSON( $value );

			if ( $flag.hasClass('fa-toggle-off') ) {
				
				$value.overwrite = true;
				$flag.removeClass('fa-toggle-off').addClass('fa-toggle-on');
				$toolbar.removeClass('off');

			} else {
				
				$value.overwrite = false;
				$flag.removeClass('fa-toggle-on').addClass('fa-toggle-off');
				$toolbar.addClass('off');
			}
			
			$field.val( JSON.stringify( $value ) );

			$('body').trigger('composer-update', $item.parent().closest('.composer-item') );

		}

		$COMPOSER.on( 'click', '.composer-item-overwrite', overwrite_item ) ;

		/*
		 *
		 * overwrite_item
		 *
		*/
		// function overwrite_id(e) {

		// 	$id = $(this).val();

		// 	$item = $(this).closest('.composer-item');

		// 	$field = $item.find('> .composer-item-value');

		// 	$value = $field.val();
			
		// 	if ( $value ) $value = $.parseJSON( $value );

		// 	$value.id = $id;
			
		// 	$field.val( JSON.stringify( $value ) );

		// 	$('body').trigger('composer-update', $item.parent().closest('.composer-item') );

		// }

		// $COMPOSER.on( 'change', '.composer-item-overwrite-id', overwrite_id ) ;


		/*
		 *
		 * delete_item
		 *
		*/
		function delete_item(){

			$element = $(this).closest('.composer-item');

			$parent_before_delete = $element.parent().closest('.composer-item');

			$(this).closest('.composer-item').remove();

			$('body').trigger('composer-clone', $parent_before_delete );

		}

		$COMPOSER.on('click', '.composer-item-delete', delete_item );


		/*
		 *
		 * close_modal_add
		 *
		*/
		function close_modal_add(){

			$COMPOSER.removeClass('open');

			$MODAL_EDIT.find('iframe').hide().attr('src', '' );

			$MODAL_EDIT.removeClass('show');

			$MODAL_ADD.removeClass('show');

		}

		$COMPOSER.on( 'click keydown', '.composer-item-add-content', open_modal_add ) ;
		$COMPOSER.on( 'click', '.composer-modal-close', close_modal_add ) ;


		/*
		 *
		 * UPDATE
		 *
		*/
		$('body').on('composer-add composer-edit composer-sort composer-clone composer-delete composer-update', function( event, parent ) {

		
			if( $(parent).length !== 0 ) {
				
				$(parent).trigger('element-update', parent );

			}
			
			update_composer();
			

		});
    
    $('body').on('composer-add composer-clone', function( event, parent ) {

			sortable_items();
			
		});


		function update_composer(e) {

			//console.log('update_composer');

			var output = {};

			var composer = [];

			$COMPOSER_ROWS.find('> .composer-item').each( function( index_element, element ) {

				element_settings = $.parseJSON( $(element).find('>.composer-item-value').val() );

				composer.push(element_settings);

			});

			output = composer;

			//console.log(output);

			$data = JSON.stringify( output );
			
			if ( $data == '[]' ) $data = "";

			$VALUE.val( $data );

			item_check_collapse();

			init_empty_message();


			if ( $HISTORY_current_pos == ( $HISTORY.length - 1 ) ) {
				
				$HISTORY.push( $COMPOSER_ROWS.html() );

				$HISTORY_current_pos = $HISTORY_current_pos + 1;

				//console.log( 'update : history count ' + $HISTORY.length );

			} else {

				$HISTORY.slice(0, $HISTORY_current_pos );
				
				$HISTORY.push( $COMPOSER_ROWS.html() );

				$HISTORY_current_pos = $HISTORY_current_pos + 1;

				//console.log( 'update : history count ' + $HISTORY.length );

			}

		};

		$HISTORY.push( $COMPOSER_ROWS.html() );
		$HISTORY_current_pos = 1;


		function history_undo(){
			
			//console.log('HISTORY_current_pos = ' + $HISTORY_current_pos );

			if ( $HISTORY.length >= 1 && $HISTORY_current_pos !== 1 ) {
				
				$HISTORY_current_pos = $HISTORY_current_pos - 1;

				$COMPOSER_ROWS.html( $HISTORY[ $HISTORY_current_pos -1 ] );
				
				//console.log('HISTORY_undo : replace with history ' + $HISTORY_current_pos );
				
			}

		}
		function history_repeat(){

			//console.log('HISTORY_current_pos = ' + $HISTORY_current_pos );

			if ( $HISTORY.length >= $HISTORY_current_pos && $HISTORY_current_pos !== $HISTORY.length ) {
				
				$HISTORY_current_pos = $HISTORY_current_pos + 1;

				$COMPOSER_ROWS.html( $HISTORY[ $HISTORY_current_pos -1 ] );
				
				//console.log('HISTORY_undo : replace with history ' + $HISTORY_current_pos );

			}

		}

		function item_collapse(){

			var $item = $(this).closest('.composer-item');
			var $content = $item.find('> .composer-item-content');
			var $button = $item.find('> .composer-item-toolbar > .composer-item-toolbar-right > .composer-action-collapse');

			if ( $item.hasClass('close') ) {

				$button.removeClass('dashicons-arrow-left').addClass('dashicons-arrow-down');
				$item.removeClass('is-closed');
				$item.removeClass('close');
			
			} else {

        $button.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-left');
				$item.addClass('is-closed');
				$item.addClass('close');
        
			}

		}
		function item_check_collapse(){

			$COMPOSER.find('.composer-item').each( function( index, element_id ){

				if ( $(this).height() >= 100 || $(this).find('.composer-item-content').length ) {
				
					$(this).find('> .composer-item-toolbar > .composer-item-toolbar-right > .composer-action-collapse').addClass('show');
				
				}

			});

		}
		item_check_collapse();
		

		function view_fullscreen(){

			$COMPOSER.toggleClass('composer-fullscreen');

		}

		function view_xray(){

			$COMPOSER.toggleClass('composer-xray');

		}

		function view_source(){

			$VALUE.val( JSON.stringify( $.parseJSON( $VALUE.val() ), null, 2 ) );

			$COMPOSER.toggleClass('composer-source-show');

		}

		function view_debug(){

			$COMPOSER.find('.composer-row-value, .composer-column-value, .composer-item-value').toggle();


		}

		function view_preview(){

			$COMPOSER.toggleClass('composer-preview-show');

		}
		// $(document).keypress( function(e) {

		//   	if ( e.altKey && e.keyCode === 960) {
		//     	$COMPOSER.toggleClass('composer-preview-show');
		//     	e.preventDefault();
		// 	}

		// });

		
		$COMPOSER.on( 'click', '.composer-action-collapse', item_collapse ) ;

		$COMPOSER.on( 'click', '.composer-action-undo', history_undo ) ;
		$COMPOSER.on( 'click', '.composer-action-repeat', history_repeat ) ;

		$COMPOSER.on( 'click', '.composer-action-fullscreen', view_fullscreen ) ;
		$COMPOSER.on( 'click', '.composer-action-xray', view_xray ) ;
		$COMPOSER.on( 'click', '.composer-action-source', view_source ) ;
		$COMPOSER.on( 'click', '.composer-action-debug', view_debug ) ;
		$COMPOSER.on( 'click', '.composer-action-preview', view_preview ) ;

		/*
		 *
		 * HOVER
		 *
		*/
		$COMPOSER.on( 'mouseover mouseout', '.composer-item', function(e){

			$COMPOSER.find('.composer-item').removeClass('hover-parent');

			if ( e.type ==  'mouseout') {
				$(this).removeClass('hover');
			} else {
				$(this).addClass('hover');
				$(this).parent().closest('.composer-item').addClass('hover-parent');
			}
			
			e.stopPropagation();

		});

		/*
		 *
		 * HELPERS
		 *
		*/
		function init_empty_message() {

				if( $COMPOSER.find('.composer-layout > .composer-items').children().length == 0 ) {

					$COMPOSER.find('.composer-empty').css('display','block');

				} else {

					$COMPOSER.find('.composer-empty').css('display','none');

				}

		}
		/*
		function init_column_size(){

			$COMPOSER.find('.composer-item').each(function(index, el) {

				if ( $(this).outerWidth() < 180 ) {
					$(this).addClass('column-expend');
				} else {
					$(this).removeClass('column-expend');
				}

			});

		}

		$( window ).resize( init_column_size );

		init_column_size();
		*/

		init_empty_message();
		
		$COMPOSER.css('visibility', 'visible' );

	});

	removeFromArray = function(value, arr) {
	    return $.grep(arr, function(elem, index) {
	        return elem !== value;
	    });
	};

	getUniqueID = function() {

		
		return $.md5( '80.122.65.122' + rand( 0, Math.floor(new Date().getTime() / 1000) ) );

		// var charstoformid = '_0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');

		// if (! idlength) {
		// 	idlength = Math.floor(Math.random() * charstoformid.length);
		// }
		
		// var uniqid = '';
		
		// for (var i = 0; i < length; i++) {

		// 	uniqid += charstoformid[Math.floor(Math.random() * charstoformid.length)];
		
		// }

		// if(jQuery("#"+uniqid).length == 0) {
		
		// 	return uniqid;
		
		// } else {
		
		// 	return uniqID(20)
		// }

	}

	rand = function(min, max) {
		var min = min || 0,
			max = max || Number.MAX_SAFE_INTEGER;
		
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}


}

$(document).ready(function(){

	$('body').find('.linotype_field_composer').linotype_field_composer();

});

}(jQuery));



/*
 * jQuery MD5 Plugin 1.2.1
 * https://github.com/blueimp/jQuery-MD5
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 * 
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/*jslint bitwise: true */
/*global unescape, jQuery */

(function ($) {
    'use strict';

    /*
    * Add integers, wrapping at 2^32. This uses 16-bit operations internally
    * to work around bugs in some JS interpreters.
    */
    function safe_add(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }

    /*
    * Bitwise rotate a 32-bit number to the left.
    */
    function bit_rol(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    /*
    * These functions implement the four basic operations the algorithm uses.
    */
    function md5_cmn(q, a, b, x, s, t) {
        return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
    }
    function md5_ff(a, b, c, d, x, s, t) {
        return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }
    function md5_gg(a, b, c, d, x, s, t) {
        return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }
    function md5_hh(a, b, c, d, x, s, t) {
        return md5_cmn(b ^ c ^ d, a, b, x, s, t);
    }
    function md5_ii(a, b, c, d, x, s, t) {
        return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
    }

    /*
    * Calculate the MD5 of an array of little-endian words, and a bit length.
    */
    function binl_md5(x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << ((len) % 32);
        x[(((len + 64) >>> 9) << 4) + 14] = len;

        var i, olda, oldb, oldc, oldd,
            a =  1732584193,
            b = -271733879,
            c = -1732584194,
            d =  271733878;

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = md5_ff(a, b, c, d, x[i],       7, -680876936);
            d = md5_ff(d, a, b, c, x[i +  1], 12, -389564586);
            c = md5_ff(c, d, a, b, x[i +  2], 17,  606105819);
            b = md5_ff(b, c, d, a, x[i +  3], 22, -1044525330);
            a = md5_ff(a, b, c, d, x[i +  4],  7, -176418897);
            d = md5_ff(d, a, b, c, x[i +  5], 12,  1200080426);
            c = md5_ff(c, d, a, b, x[i +  6], 17, -1473231341);
            b = md5_ff(b, c, d, a, x[i +  7], 22, -45705983);
            a = md5_ff(a, b, c, d, x[i +  8],  7,  1770035416);
            d = md5_ff(d, a, b, c, x[i +  9], 12, -1958414417);
            c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
            b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
            a = md5_ff(a, b, c, d, x[i + 12],  7,  1804603682);
            d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
            c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
            b = md5_ff(b, c, d, a, x[i + 15], 22,  1236535329);

            a = md5_gg(a, b, c, d, x[i +  1],  5, -165796510);
            d = md5_gg(d, a, b, c, x[i +  6],  9, -1069501632);
            c = md5_gg(c, d, a, b, x[i + 11], 14,  643717713);
            b = md5_gg(b, c, d, a, x[i],      20, -373897302);
            a = md5_gg(a, b, c, d, x[i +  5],  5, -701558691);
            d = md5_gg(d, a, b, c, x[i + 10],  9,  38016083);
            c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
            b = md5_gg(b, c, d, a, x[i +  4], 20, -405537848);
            a = md5_gg(a, b, c, d, x[i +  9],  5,  568446438);
            d = md5_gg(d, a, b, c, x[i + 14],  9, -1019803690);
            c = md5_gg(c, d, a, b, x[i +  3], 14, -187363961);
            b = md5_gg(b, c, d, a, x[i +  8], 20,  1163531501);
            a = md5_gg(a, b, c, d, x[i + 13],  5, -1444681467);
            d = md5_gg(d, a, b, c, x[i +  2],  9, -51403784);
            c = md5_gg(c, d, a, b, x[i +  7], 14,  1735328473);
            b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

            a = md5_hh(a, b, c, d, x[i +  5],  4, -378558);
            d = md5_hh(d, a, b, c, x[i +  8], 11, -2022574463);
            c = md5_hh(c, d, a, b, x[i + 11], 16,  1839030562);
            b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
            a = md5_hh(a, b, c, d, x[i +  1],  4, -1530992060);
            d = md5_hh(d, a, b, c, x[i +  4], 11,  1272893353);
            c = md5_hh(c, d, a, b, x[i +  7], 16, -155497632);
            b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
            a = md5_hh(a, b, c, d, x[i + 13],  4,  681279174);
            d = md5_hh(d, a, b, c, x[i],      11, -358537222);
            c = md5_hh(c, d, a, b, x[i +  3], 16, -722521979);
            b = md5_hh(b, c, d, a, x[i +  6], 23,  76029189);
            a = md5_hh(a, b, c, d, x[i +  9],  4, -640364487);
            d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
            c = md5_hh(c, d, a, b, x[i + 15], 16,  530742520);
            b = md5_hh(b, c, d, a, x[i +  2], 23, -995338651);

            a = md5_ii(a, b, c, d, x[i],       6, -198630844);
            d = md5_ii(d, a, b, c, x[i +  7], 10,  1126891415);
            c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
            b = md5_ii(b, c, d, a, x[i +  5], 21, -57434055);
            a = md5_ii(a, b, c, d, x[i + 12],  6,  1700485571);
            d = md5_ii(d, a, b, c, x[i +  3], 10, -1894986606);
            c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
            b = md5_ii(b, c, d, a, x[i +  1], 21, -2054922799);
            a = md5_ii(a, b, c, d, x[i +  8],  6,  1873313359);
            d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
            c = md5_ii(c, d, a, b, x[i +  6], 15, -1560198380);
            b = md5_ii(b, c, d, a, x[i + 13], 21,  1309151649);
            a = md5_ii(a, b, c, d, x[i +  4],  6, -145523070);
            d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
            c = md5_ii(c, d, a, b, x[i +  2], 15,  718787259);
            b = md5_ii(b, c, d, a, x[i +  9], 21, -343485551);

            a = safe_add(a, olda);
            b = safe_add(b, oldb);
            c = safe_add(c, oldc);
            d = safe_add(d, oldd);
        }
        return [a, b, c, d];
    }

    /*
    * Convert an array of little-endian words to a string
    */
    function binl2rstr(input) {
        var i,
            output = '';
        for (i = 0; i < input.length * 32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
        }
        return output;
    }

    /*
    * Convert a raw string to an array of little-endian words
    * Characters >255 have their high-byte silently ignored.
    */
    function rstr2binl(input) {
        var i,
            output = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0;
        }
        for (i = 0; i < input.length * 8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
        }
        return output;
    }

    /*
    * Calculate the MD5 of a raw string
    */
    function rstr_md5(s) {
        return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
    }

    /*
    * Calculate the HMAC-MD5, of a key and some data (raw strings)
    */
    function rstr_hmac_md5(key, data) {
        var i,
            bkey = rstr2binl(key),
            ipad = [],
            opad = [],
            hash;
        ipad[15] = opad[15] = undefined;                        
        if (bkey.length > 16) {
            bkey = binl_md5(bkey, key.length * 8);
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5C5C5C5C;
        }
        hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
        return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
    }

    /*
    * Convert a raw string to a hex string
    */
    function rstr2hex(input) {
        var hex_tab = '0123456789abcdef',
            output = '',
            x,
            i;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hex_tab.charAt((x >>> 4) & 0x0F) +
                hex_tab.charAt(x & 0x0F);
        }
        return output;
    }

    /*
    * Encode a string as utf-8
    */
    function str2rstr_utf8(input) {
        return unescape(encodeURIComponent(input));
    }

    /*
    * Take string arguments and return either raw or hex encoded strings
    */
    function raw_md5(s) {
        return rstr_md5(str2rstr_utf8(s));
    }
    function hex_md5(s) {
        return rstr2hex(raw_md5(s));
    }
    function raw_hmac_md5(k, d) {
        return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
    }
    function hex_hmac_md5(k, d) {
        return rstr2hex(raw_hmac_md5(k, d));
    }
    
    $.md5 = function (string, key, raw) {
        if (!key) {
            if (!raw) {
                return hex_md5(string);
            } else {
                return raw_md5(string);
            }
        }
        if (!raw) {
            return hex_hmac_md5(key, string);
        } else {
            return raw_hmac_md5(key, string);
        }
    };
    
}(typeof jQuery === 'function' ? jQuery : this));


(function($) {

$.fn.textWidth = function(text, font) {
    
    if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
    
    $.fn.textWidth.fakeEl.text(text || this.val() || this.text() || this.attr('placeholder')).css('font', font || this.css('font'));
    
    return $.fn.textWidth.fakeEl.width();
};

$('.width-dynamic').on('input', function() {
    var inputWidth = $(this).textWidth();
    $(this).css({
        width: inputWidth
    })
}).trigger('input');


function inputWidth(elem, minW, maxW) {
    elem = $(this);

}

var targetElem = $('.width-dynamic');

inputWidth(targetElem);

}(jQuery));