/**
 * LINOADMIN
 *
 * handy fields builder
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */
(function($) {

$.fn.LINOADMIN_postbox_disable_switch = function(){

	//for each
	jQuery(this).each(function(){

		var $postbox = jQuery(this);

		//disable click event
		if ( $postbox.hasClass('disable-switch') ) {

			$postbox.find('.hndle').off('click');

		}

		//move the postbox over others
		if ( $postbox.hasClass('move-over') ) {

			$postbox.prependTo( $postbox.parent().parent() );

		}

	});

	//disable sortable on specific postbox
	//if ( $(".meta-box-sortables").length ) $(".meta-box-sortables").sortable('option', 'cancel', '.disable-sortable .hndle, :input, button').sortable('refresh');

};


$.fn.LINOADMIN_tab = function(){

	jQuery(this).each(function(){

		var $this = jQuery(this);
		var $TABS = $this.find('.LINOADMIN-tab-nav');
		var $CONTENTS = $this.find('.LINOADMIN-tab-content');

		$TABS.find('.LINOADMIN-tab-nav-item').on('click', function(){

			$TABS.find('.LINOADMIN-tab-nav-item').removeClass( 'active' );
			$CONTENTS.find('.LINOADMIN-tab-content-item').removeClass('content-tab-active');

			$(this).addClass( 'active' );
			$CONTENTS.find( $(this).attr('data-target') ).addClass('content-tab-active');

		});


	});

};

$.fn.LINOADMIN_clipboard = function(){

	//for each
	jQuery(this).each(function(){

		var $bt = jQuery(this);
		$bt.on('click', function(e){
			copyToClipboard( $( $(this).attr('data-target') )[0] );
			e.preventDefaults;
		})

	});

	function copyToClipboard(elem) {
		  // create hidden text element, if it doesn't already exist
	    var targetId = "_hiddenCopyText_";
	    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
	    var origSelectionStart, origSelectionEnd;
	    if (isInput) {
	        // can just use the original source element for the selection and copy
	        target = elem;
	        origSelectionStart = elem.selectionStart;
	        origSelectionEnd = elem.selectionEnd;
	    } else {
	        // must use a temporary form element for the selection and copy
	        target = document.getElementById(targetId);
	        if (!target) {
	            var target = document.createElement("textarea");
	            target.style.position = "absolute";
	            target.style.left = "-9999px";
	            target.style.top = "0";
	            target.id = targetId;
	            document.body.appendChild(target);
	        }
	        target.textContent = elem.textContent;
	    }
	    // select the content
	    var currentFocus = document.activeElement;
	    target.focus();
	    target.setSelectionRange(0, target.value.length);

	    // copy the selection
	    var succeed;
	    try {
	    	  succeed = document.execCommand("copy");
	    } catch(e) {
	        succeed = false;
	    }
	    // restore original focus
	    if (currentFocus && typeof currentFocus.focus === "function") {
	        currentFocus.focus();
	    }

	    if (isInput) {
	        // restore prior selection
	        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
	    } else {
	        // clear temporary content
	        target.textContent = "";
	    }
	    return succeed;
	}

};

jQuery(document).ready(function(){

	$('body').find('.LINOADMIN-tab').LINOADMIN_tab();

	$('body').find('.postbox.disable-switch').LINOADMIN_postbox_disable_switch();

	$('body').find('.clipboard').LINOADMIN_clipboard();

});


}(jQuery));
