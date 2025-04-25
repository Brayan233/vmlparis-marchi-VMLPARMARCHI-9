(function($) {

$.fn.icon_picker = function(){

	jQuery(this).each(function(){

		$FIELD = jQuery(this);
		$VALUE = $FIELD.find( 'input' );

		$MODAL_ICONS = $('body #linoadmin_modal_icons_dialog');
     	$MODAL_ICONS_ok = $('body #linoadmin_modal_icons_dialog .linoadmin_modal_icons-ok');
     	$MODAL_ICONS_close = $('body #linoadmin_modal_icons_dialog .linoadmin_modal_icons-close');
     	$MODAL_ICONS_clear = $('body #linoadmin_modal_icons_dialog .linoadmin_modal_icons-clear');
     	$MODAL_ICONS_open = $('body .linoadmin_modal_icons-open');

     	$MODAL_ICONS_nav = $('body #linoadmin_modal_icons_dialog .linoadmin_modal_icons-content .navigation-bar');
     	
     	$icons_font_awesome = $MODAL_ICONS.find('#icons-list-font-awesome a');
     	$icons_dashicons = $MODAL_ICONS.find('#icons-list-dashicons .dashicons');

     	$preview = $FIELD.find('.icon-preview');
 		$current_icon = '';

		function MODAL_ICONS_open(e) {
		    
		    e.preventDefault();

		    $MODAL_ICONS.find('*').removeClass('current-icon');

		    if ( $VALUE.val() ) {

				$current_icon = $VALUE.val();

				$MODAL_ICONS.find( '#icons-list-font-awesome a i[class="' + $current_icon + '"]' ).parent().addClass('current-icon');
				$MODAL_ICONS.find( '#icons-list-dashicons div[class="' + $current_icon + '"]' ).addClass('current-icon');

			}

			$MODAL_ICONS.show();
			$('body').css('overflow','hidden');

		}

		function MODAL_ICONS_close(e) {
		    
		    e.preventDefault();

		    $current_icon = '';

		    $MODAL_ICONS.hide();
		    $('body').css('overflow','');

		}

		function MODAL_ICONS_clear(e) {
		    
		    e.preventDefault();

		    $current_icon = '';

		    $preview.attr('class', $current_icon );

			$VALUE.val( $current_icon ).change();

		    $MODAL_ICONS.hide();
		    $('body').css('overflow','');

		}

		function MODAL_ICONS_ok(e) {
		    
		    e.preventDefault();

		    $preview.attr('class', $current_icon );

			$VALUE.val( $current_icon ).change();

			$MODAL_ICONS.hide();
		    $('body').css('overflow','');

		}

		function icons_font_awesome(e){

			e.preventDefault();
			
			$MODAL_ICONS.find('*').removeClass('current-icon');
		
			$current_icon = $(this).find('i').attr('class');

			$(this).addClass('current-icon');

			if ( e.type == "dblclick" ) MODAL_ICONS_ok(e);

		}

		function icons_dashicons(e){

			e.preventDefault();
			
			$MODAL_ICONS.find('*').removeClass('current-icon');
			
			$current_icon = $(this).attr('class');

			$(this).addClass('current-icon');

			if ( e.type == "dblclick" ) MODAL_ICONS_ok(e);

		}

		//tabs
		function switch_tabs(e){

			e.preventDefault();
			
			$selected = '#' + $(this).attr('target');

			$('.linoadmin_modal_icons-page').hide();

			$($selected).show();
			

		}

		$MODAL_ICONS_ok.on( 'click', MODAL_ICONS_ok );
		$MODAL_ICONS_close.on( 'click', MODAL_ICONS_close );
		$MODAL_ICONS_clear.on( 'click', MODAL_ICONS_clear );
		$MODAL_ICONS_open.on( 'click', MODAL_ICONS_open );

		$icons_font_awesome.on( 'click dblclick', icons_font_awesome );
		$icons_dashicons.on( 'click dblclick', icons_dashicons );

		$MODAL_ICONS_nav.on( 'click', 'li', switch_tabs );

	});

}

$(document).ready(function(){

	$('body').find('.icon-picker').icon_picker();

});


}(jQuery));
