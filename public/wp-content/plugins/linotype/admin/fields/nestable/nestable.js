(function($) {

$.fn.wp_field_nestable = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $VALUE = $FIELD.find('.wp-field-value');
		var $NESTABLE_TREE = $FIELD.find('.nestable-tree');
		var $NESTABLE_VALUE = $FIELD.find('.nestable-value');
		var $NESTABLE_ITEMS = $FIELD.find('.menu-item-settings');

		var $NESTABLE_ITEMS_DATA = JSON.parse( $FIELD.find('.menu-item-data').val() );

		/*
		* Init nestable
		*/
		$NESTABLE_TREE.nestable({
			'maxDepth' : 4,
			'expandBtnHTML':'<div class="dd-action" data-action="expand"><span class="dashicons dashicons-before dd-dashicons dashicons-arrow-right"></span></div>',
			'collapseBtnHTML':'<div class="dd-action" data-action="collapse"><span class="dashicons dashicons-before dd-dashicons dashicons-arrow-down"></span></div>',
		});

		function addItem(e){

			e.preventDefault();

			$FIELD.find('.menu-item-checkbox:checked').each(function(){

				$item_id = $(this).attr('data-id');

				$item_keys = $item_id.split('|');

				if ( $item_keys.length == 1 ) {
					$data = $NESTABLE_ITEMS_DATA[ $item_keys[0] ];
				} else if ( $item_keys.length == 2 ) {
					$data = $NESTABLE_ITEMS_DATA[ $item_keys[0] ]['children'][ $item_keys[1] ];
				}

				$NESTABLE_VALUE.nestable( 'add', $data );

				$(this).prop( 'checked', false );
			
			});

			$NESTABLE_TREE.change();

		}

		$FIELD.on( 'click', '.menu-item-checkbox-add', addItem ) ;

		function selectAllItem(e){

			e.preventDefault();

			$FIELD.find('.menu-item-checkbox').each(function(){

				$(this).prop( 'checked', true );
			
			});

			$NESTABLE_TREE.change();

		}

		$FIELD.on( 'click', '.menu-item-checkbox-select-all', selectAllItem ) ;

		function addCustomItem(e){

			e.preventDefault();

			$new_item = $(this).attr('data-fields');

			$new_item = JSON.parse($new_item);

			$NESTABLE_VALUE.nestable( 'add', $new_item );
			
			$NESTABLE_TREE.change();

		}

		$FIELD.on( 'click', '.data-custom-add', addCustomItem ) ;
		
		if ( $NESTABLE_TREE.hasClass('is-collapsed') ) $NESTABLE_TREE.nestable('collapseAll');
		
		/*
		* On order change
		*/
		$NESTABLE_TREE.on('change', updateOutput );
		
		/*
		* On remove click
		*/
		$NESTABLE_TREE.on('click', '.dd-remove', function(){

			$(this).closest('.dd-item').remove();

			$NESTABLE_TREE.change();

			return false;

		})

		/*
		* On edit click
		*/
		$NESTABLE_TREE.on('click', '.dd-edit', function(){

			$item = $(this).closest('.dd-item');
			$content = $(this).closest('.dd-content');
			 

			if ( ! $content.find('.menu-item-settings').length ) {
			
				$data = getDataAttributes($item);

				$NESTABLE_ITEM_SETTINGS = '<div class="menu-item-settings" style="display:none;">';
				
						$.each( $data, function( index, attr ) {

							if ( index !== 'id' && index !== 'children' ) {

								if ( index == 'icon' ) {

									$NESTABLE_ITEM_SETTINGS += '<div class="description-wide"><label>' + index + '</label><br/>';
										
										$NESTABLE_ITEM_SETTINGS += '<div class="icon-picker inline">';

											$NESTABLE_ITEM_SETTINGS += '<div class="linoadmin_modal_icons-open button" style="min-width: 30px;">';
												
												$NESTABLE_ITEM_SETTINGS += '<i class="icon-preview ' + attr + '" style="height: 26px; line-height: 26px; font-size: 20px;"></i>';

											$NESTABLE_ITEM_SETTINGS += '</div>';

											$NESTABLE_ITEM_SETTINGS += '<input class="dd-option widefat" data-target="data-' + index + '" type="text" value="' + attr + '" autocomplete="off">';
											
										$NESTABLE_ITEM_SETTINGS += '</div>';

									$NESTABLE_ITEM_SETTINGS += '</div>';

								} else {

									$NESTABLE_ITEM_SETTINGS += '<div class="description-wide"><label>' + index + '</label><br/><input class="dd-option widefat" data-target="data-' + index + '" type="text" value="' + attr + '" autocomplete="off"></div>';
								
								}

							}

						});

					$NESTABLE_ITEM_SETTINGS += '<div class="menu-item-actions description-wide submitbox"><a style="float:none;" class="item-delete submitdelete deletion dd-remove" href="#">Remove</a></div>';
				$NESTABLE_ITEM_SETTINGS += '</div>';

				$content.find('.menu-item-settings-holder').append( $NESTABLE_ITEM_SETTINGS );

			}

			$content.find('.menu-item-settings').slideToggle(300);

			$content.find('.menu-item-settings').find('.icon-picker').icon_picker();

			return false;

		});

		/*
		* On option change
		*/
		$NESTABLE_TREE.on('change paste', '.dd-option', function( event ){

			$item = $(this).closest('.dd-item');
			$id = $(this).attr('data-target');
			$value = $(this).val();

			if ( $id == 'data-title' || $id == 'data-name' ) {

				$item.find('.the_title').first().text( $value );

			}

			if ( $id == 'data-icon' ) {

				$item.find('.the_icon').first().attr( 'class', 'the_icon ' + $value );

			}

			$item.attr( $id, $value );

			$NESTABLE_TREE.change();

		});

		/*
		* On option change
		*/
		
		$('#nestable-lib li').on('click', function(){
		
			//$('#nestable-enable > .dd-list').append( $(this)[0].outerHTML );
		
			//$NESTABLE_TREE.change();
		
			//return false;
		
		})
		

		/*
		* Update field
		*/
		function updateOutput() {
			
			// console.log( $NESTABLE_VALUE.nestable('serialize') );

			$val = $NESTABLE_VALUE.nestable('serialize');

			if ( $val ) $VALUE.val( JSON.stringify( $val ) );

		};


		function getDataAttributes(node) {
		    var d = {}, 
		        re_dataAttr = /^data\-(.+)$/;

		    $.each(node.get(0).attributes, function(index, attr) {
		        if (re_dataAttr.test(attr.nodeName)) {
		            var key = attr.nodeName.match(re_dataAttr)[1];
		            d[key] = attr.nodeValue;
		        }
		    });

		    return d;
		}


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-nestable').wp_field_nestable();

});

}(jQuery));
