(function($) {

$.fn.wp_field_codemirror = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $ID = $FIELD.attr('wp-field-id');
		var $VALUE = $FIELD.find( '#' + $ID );

		var $MODE = String( $FIELD.attr('wp-field-option-mode') );
		var $THEME = String( $FIELD.attr('wp-field-option-theme') );
		var $OVERLAY = Boolean( $FIELD.attr('wp-field-option-overlay') );
		var $CLOSETAG = Boolean( $FIELD.attr('wp-field-option-closetag') );
		var $FULLSCREEN = Boolean( $FIELD.attr('wp-field-option-fullscreen') );
		var $AUTOHEIGHT = Boolean( $FIELD.attr('wp-field-option-autoheight') );
		var $AUTOCOMPLETE = Boolean( $FIELD.attr('wp-field-option-autocomplete') );
		var $AUTOCOMPLETE_WORDS = String( $FIELD.attr('wp-field-option-autocomplete') ).split(',');
		var $READONLY = Boolean( $FIELD.attr('wp-field-option-readonly') );

		var $OPTIONS = {};

		$OPTIONS['styleActiveLine'] = true;
	    $OPTIONS['lineNumbers'] = true;
	    $OPTIONS['tabSize'] = 2;
	    $OPTIONS['extraKeys'] = {};

	    $OPTIONS['lineWrapping'] = true;
	    $OPTIONS['scrollbarStyle'] = "null";
	    $OPTIONS['fixedGutter'] = true;
	    $OPTIONS['autofocus'] = false;

		//set mode
		if ( $MODE == 'html' ) {

			$OPTIONS['mode'] = 'text/html';
			$OVERLAYMODE = 'text/html'

		}
		if ( $MODE == 'php' ) {

			$OPTIONS['mode'] = "application/x-httpd-php";
			$OVERLAYMODE = "application/x-httpd-php";

		}
		if ( $MODE == 'javascript' ) {

			$OPTIONS['mode'] = "javascript";
			$OVERLAYMODE = "javascript";

		}
		if ( $MODE == 'css' ) {

			$OPTIONS['mode'] = "css";
			$OVERLAYMODE = "css";

		}
		if ( $MODE == 'json' ) {

			$OPTIONS['mode'] = "javascript";
			$OVERLAYMODE = "application/json";

		}

		//ecrase mode if overlay
		if ( $OVERLAY ) $OPTIONS['mode'] = 'overlay';

		//set theme
		if ( $THEME ) $OPTIONS['theme'] = $THEME;

		//set auto close tag
		if ( $CLOSETAG ) $OPTIONS['autoCloseTags'] = true;

		//set autoheight
		// if ( $AUTOHEIGHT ) $OPTIONS['viewportMargin'] = Infinity;

		//set readonly
		if ( $READONLY ) $OPTIONS['readOnly'] = true;

		//set fullscreen
		if ( $FULLSCREEN ) {

			$OPTIONS['extraKeys']['F11'] = function(cm) {

				cm.setOption("fullScreen", !cm.getOption("fullScreen"));

	          	$('#wpbody').css('z-index', 9999999999 );

	        };

	        $OPTIONS['extraKeys']['Esc'] = function(cm) {

				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);

	          	$('#wpbody').css('z-index', 'inherit' );

	        };

	    }

	    //set autocomplete
	 	if ( $AUTOCOMPLETE ) {

	 		$OPTIONS['extraKeys']['Ctrl-Space'] = "autocomplete";
	 		// "'{'": function(cm) { setTimeout(function(){cm.execCommand("autocomplete");}, 100); },

			CodeMirror.commands.autocomplete = function(cm) {
	        	cm.showHint({hint: CodeMirror.hint.anyword });
	      	}

		  	var orig = CodeMirror.hint.anyword;
			CodeMirror.hint.anyword = function(cm) {

			  var inner = orig(cm) || {from: cm.getCursor(), to: cm.getCursor(), list: []};

			  inner.list = $AUTOCOMPLETE_WORDS;

			  return inner;

			};

		}

	    //overlay parse
	    if ( $OVERLAY ){

			CodeMirror.defineMode("overlay", function(config, parserConfig) {

			  var parseOverlay = {

			    token: function(stream, state) {

			      var ch;
						 var ch2;

			      if ( stream.match("{{") ) {

			        while ((ch = stream.next()) != null)

			          if (ch == "}" && stream.next() == "}") {
			            stream.eat("}");
			            return "overlay";
			          }

			      }

						if ( stream.match("[[") ) {

			        while ((ch2 = stream.next()) != null)

								if (ch2 == "]" && stream.next() == "]") {
									stream.eat("]");
									return "overlay2";
								}

			      }

						// if ( stream.match("<?php") ) {
						//
			      //   while ((ch = stream.next()) != null)
						//
			      //     if (ch == "?" && stream.next() == ">") {
			      //       stream.eat(">");
			      //       return "overlay-php";
			      //     }
						//
			      // }
						// && !stream.match( "<?php", false )

			      while (stream.next() != null && !stream.match("{{", false) && !stream.match("[[", false)  ) {}

			      return null;

			    }

			  };

			  return CodeMirror.overlayMode( CodeMirror.getMode(config, parserConfig.backdrop || $OVERLAYMODE ), parseOverlay );

			});



		}

		//execute
		var editor = CodeMirror.fromTextArea( document.getElementById( $ID ), $OPTIONS );

		function updateTextArea() {
		    editor.save();
		}
		editor.on('change', updateTextArea);

		//show codemirror
		$FIELD.find('.field-content').css('visibility','visible');
		$FIELD.find('.spinner').css('display','none');

	});
}

jQuery(document).ready(function(){

	$('body').find('.wp-field-codemirror').wp_field_codemirror();

});

}(jQuery));
