jQuery(function($) {

$(document).ready(function() {

	$(".wpb_vc_row").on( 'mouseover', 'a,span', function() {

		//remove link title
		$('.wpb_vc_row a,.wpb_vc_row span').attr("title", "");

	});

});

$(document).on('keydown', function ( e ) {

    if ((e.metaKey || ( e.ctrlKey && e.altKey ) ) && ( String.fromCharCode(e.which).toLowerCase() === 'b') ) {
			if( $('body').hasClass('wp-backend-sidebar-hide') ) {
				$('body').removeClass('wp-backend-sidebar-hide');
			} else {
				$('body').addClass('wp-backend-sidebar-hide');
			}
    }
});



});
