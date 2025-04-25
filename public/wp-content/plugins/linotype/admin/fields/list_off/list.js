(function($) {

$.fn.wp_field_list = function(){

	$(this).each(function(){

		var $FIELD = $(this);

		var $VALUE = $FIELD.find('.wp-field-value');

		var $OPTIONS = JSON.parse( $FIELD.find('.wp-field-options').val() );

		var $LIST = $FIELD.find('.list-editor');

		var $LIST_ID = $LIST.attr('list-id');

		var $MODAL_EDIT = $FIELD.find('.list-modal-edit');

		var $MODAL_ADD = $FIELD.find('.list-modal-add');

		function sortable_items() {

			$(".list-items").sortable({
				// appendTo: 'body',
				// containment: "window",
				connectWith: '.list-items',
				items: "> .list-item",
				opacity: 0.5,
				// scroll: true,
			    placeholder: 'list-item-placeholder',
			    // cursorAt: {top: 0, left: 0},
			    // axis: "x",
			    revert: 150,
			    // cursor: "move",
			    handle: ".list-item-move",
			    forcePlaceholderSize:true,
			    tolerance: "pointer",
			    start: function(e, ui){

					ui.placeholder.height( ui.item.outerHeight() -1 );

			    },
			    change: function(event, ui) {

			        ui.placeholder.height( ui.item.outerHeight() -1 );

			    },
			    stop: function(e, ui) {

			        $(".list-item").css('z-index', 0 );

			        update_list();

			    }
			});

		}

		sortable_items();


		/*
		 *
		 * MODAL_ADD ADD
		 *
		*/
		var $current_item_target;

		function open_modal_add(){

			console.log('open_modal_add');

			$current_item_target = $(this).closest('.list-column').find('.list-items');

			if ( $MODAL_ADD.find('.list-add-item').length == 1 ) {
				add_single_item( $MODAL_ADD.find('.list-add-item') );
			} else {
				$MODAL_ADD.addClass('show');
			}

		}
		$LIST.on( 'click', '.list-modal-add-bt', open_modal_add ) ;


		function add_single_item( $this ) {

			console.log('add_single_item');

			$target = $MODAL_ADD.attr('list-target');

			$model = $( $LIST.find('.list-template-item')[0].innerText );

			$clone = $model.clone();

			$item_default = $this.find('.list-item-value').val();

			$item_default_settings = JSON.parse( $item_default );

			$clone.attr('list-item-type', $item_default_settings.type );
			$clone.find('.list-item-title').text( $item_default_settings.title );
			if ( ! $item_default_settings.icon ) $item_default_settings.icon = 'no-icon';
			$clone.find('.list-item-icon').attr('class', 'list-item-icon list-bt ' + $item_default_settings.icon );
			$clone.find('.list-item-value').val( $item_default );

			// $current_item_target.append( $clone )
			$clone.appendTo( $current_item_target ).hide().fadeIn(500);

			sortable_items();

			update_list();

			close_modal_add();

		}

		function add_item(e) {

			console.log('add_item');

			$target = $MODAL_ADD.attr('list-target');

			$model = $( $LIST.find('.list-template-item')[0].innerText );

			$clone = $model.clone();

			$item_default = $(this).closest('.list-add-item').find('.list-item-value').val();

			$item_default_settings = JSON.parse( $item_default );

			$clone.attr('list-item-type', $item_default_settings.type );
			$clone.find('.list-item-title').text( $item_default_settings.title );
			if ( ! $item_default_settings.icon ) $item_default_settings.icon = 'no-icon';
			$clone.find('.list-item-icon').attr('class', 'list-item-icon list-bt ' + $item_default_settings.icon );
			$clone.find('.list-item-value').val( $item_default );

			// $current_item_target.append( $clone )
			$clone.appendTo( $current_item_target ).hide().fadeIn(500);

			sortable_items();

			update_list();

			close_modal_add();

		}

		$LIST.on( 'click', '.list-item-add', add_item ) ;


		function clone_item(e) {

			$model = $(this).closest('.list-item');

			$clone = $model.clone();

			$clone.insertAfter($model).hide().fadeIn(500);

			sortable_items();

			update_list();

		}

		$LIST.on( 'click', '.list-item-clone', clone_item ) ;

		/*
		 *
		 * DELETE ACTIONS
		 *
		*/
		function delete_item(){

			$(this).closest('.list-item').remove();

			update_list();

		}

		$LIST.on('click', '.list-item-delete', delete_item );


		/*
		 *
		 * EDIT ACTIONS
		 *
		*/

		var $current_item;

		function list_item_edit(){

			$current_item = {};

			$current_item['this'] = $(this).closest('.list-item');

			$current_item['type'] = $current_item['this'].attr('list-item-type');

			$current_item['title'] = $current_item['this'].find('.list-item-title');

			$current_item['icon'] = $current_item['this'].find('.list-item-icon');

			$current_item['value'] = $current_item['this'].find('.list-item-value');

			$current_item['settings'] = $current_item['value'].val();

			$current_item['settings_array'] = JSON.parse( $current_item['settings'] );

			$MODAL_EDIT.addClass('show');

			$.ajax({
			 	type : "post",
			 	dataType : "json",
			 	url : handypress_list_settings.ajaxurl,
			 	data : {
			 		action: "handypress_list_temp_settings",
			 		settings : $current_item['settings']
			 	},
			 	success: function(response) {

				    if(response.type == "success") {

				    	$MODAL_EDIT.find('iframe').hide().attr('src', handypress_list_settings.ajaxurl + '?action=handypress_list_edit&list_id=' + $LIST_ID );

							$MODAL_EDIT.find('iframe').on('load', function(){

								$MODAL_EDIT.find('iframe').contents().on( 'click', '.list-modal-close', close_modal_add ) ;

								$MODAL_EDIT.find('iframe').contents().on( 'click', '.list-item-save', list_item_save ) ;

								$MODAL_EDIT.find('iframe').contents().find('.list-modal-toolbar-title').text( 'Edit ' + $current_item['type'] + ' ( ' + $current_item['settings_array']['title'] + ' )' );

								$(this).show();

							});

				    } else {

				       alert("error")

				    }

			 	}
			});

		}

		function list_item_save(){

			$item_value = {};

			$item_value[ 'type' ] = $current_item['type'];

			$MODAL_EDIT.find('iframe').contents().find(".wp-field-value, .wp-editor-area").each(function(index_value, item_value ) {

				$field_id = $(item_value).attr('id');

				if ( $field_id ) {

					$field_value =  $(this).val();

					$field_value['type'] = $current_item['value']['type'];

					$item_value[ $field_id ] = $field_value;

				}

			});

			$current_item['title'].text( $item_value['title'] );

			if ( ! $item_value['icon'] ) $item_value['icon'] = 'no-icon';

			$current_item['icon'].attr('class', 'list-item-icon list-bt ' + $item_value['icon'] );

			$current_item['value'].val( JSON.stringify( $item_value )  );

			$MODAL_EDIT.find('iframe').attr('src', '' );

			$MODAL_EDIT.removeClass('show');

			update_list();

		}

		$LIST.on( 'dblclick', '.list-item', list_item_edit ) ;
		$LIST.on( 'click', '.list-item-edit', list_item_edit ) ;

		// $LIST.on( 'click', '.list-item-save', list_item_save ) ;

		/*
		 *
		 * MODAL_EDIT CLOSE
		 *
		*/
		function close_modal_add(){

			$MODAL_EDIT.find('iframe').hide().attr('src', '' );

			$MODAL_EDIT.removeClass('show');

			$MODAL_ADD.removeClass('show');

		}

		$LIST.on( 'click', '.list-modal-close', close_modal_add ) ;


		/*
		 *
		 * GLOBAL ACTIONS
		 *
		*/

		function view_fullscreen(){

			$LIST.toggleClass('list-fullscreen');

		}

		function view_source(){

			$LIST.toggleClass('list-source-show');

		}

		function view_debug(){

			$LIST.find('.list-item-value').toggle();

		}

		$LIST.on( 'click', '.list-action-fullscreen', view_fullscreen ) ;
		$LIST.on( 'click', '.list-action-source', view_source ) ;
		$LIST.on( 'click', '.list-action-debug', view_debug ) ;


		/*
		 *
		 * UPDATE TRIGGER
		 *
		*/
		$LIST.on('change paste', '.list-value', function( event ){

			update_list();

		});


		/*
		 *
		 * UPDATE
		 *
		*/
		function update_list(e) {

			var items = {};

			$LIST.find('.list-item').each(function(index_item, item ) {

				items[ 'item_' + index_item ] =  JSON.parse( $(item).find('.list-item-value').val() );

			});

			$data = JSON.stringify( items );

			$VALUE.val( $data );

		};


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-list').wp_field_list();

});

}(jQuery));
