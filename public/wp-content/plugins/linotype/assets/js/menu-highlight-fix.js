jQuery(function($) {
	
	//(function($) {
	// parseUri 1.2.2
	// (c) Steven Levithan <stevenlevithan.com>
	// MIT License
	function parseUri (str) {
		var	o   = parseUri.options,
			m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
			uri = {},
			i   = 14;

		while (i--) uri[o.key[i]] = m[i] || "";

		uri[o.q.name] = {};
		uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
			if ($1) uri[o.q.name][$1] = $2;
		});

		return uri;
	}

	parseUri.options = {
		strictMode: false,
		key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
		q:   {
			name:   "queryKey",
			parser: /(?:^|&)([^&=]*)=?([^&]*)/g
		},
		parser: {
			strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
			loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
		}
	};

	//console.log(js_wp_backend);

	var currentUri = parseUri(location.href);
	var currentUriFile = currentUri.file;

	//console.log(currentUri);

	$('#adminmenu *[class*=current]').removeClass('current');
	$('#adminmenu *[class*=wp-has-current-submenu]').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu');

	$best_match = currentUriFile;

	if ( currentUri.query ) {
		
		$split_query = currentUri.query.split('&');

		$query_map = '';
		
		$split_query = $.map( $split_query, function( query, index ) {
		  
		  if(index) $query_map += '&';
		  $query_map += query;

		  return currentUriFile + '?' + $query_map;

		});

		$split_query.unshift(currentUriFile);

		$.each($split_query, function(index, href) {
			
			$item = $('#adminmenu a[href="' + href + '"]');

			if ( $item.length ) {
				$best_match = href;
			}

		});

	}

	

	if ( $('#adminmenu a[href="' + $best_match + '"]').length == 0 ) {

		switch( $best_match ) {
			
		    case 'post-new.php':
		    
		        $best_match = $best_match.replace('post-new.php','edit.php');
		    
		    break;

		    case 'post.php':
		    	
		    	if( js_wp_backend.post_type && js_wp_backend.post_type != 'post' ) $best_match = 'edit.php?post_type=' + js_wp_backend.post_type;

		    	if( js_wp_backend.post_type && js_wp_backend.post_type == 'attachment' ) $best_match = 'upload.php';
		    	
		    break;
		   	
		   	case 'plugin-install.php':
		    
		        $best_match = $best_match.replace('plugin-install.php','plugins.php');
		    
		    break;

		    case 'edit-tags.php':
		    
		        $best_match = 'edit-tags.php?taxonomy=' + currentUri.queryKey['taxonomy'];
		    
		    break;    
		
		}

	}

	if ( $('#adminmenu a[href="' + $best_match + '"]').length ){

		//add li "current" if menu
		$('#adminmenu a[href="' + $best_match + '"]').closest('.menu-top').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu').addClass('wp-menu-open').addClass('menu-top-last');

		//add a "current" if menu
		$('#adminmenu a[href="' + $best_match + '"]').closest('.menu-top').children( "a" ).removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu').addClass('wp-menu-open').addClass('menu-top-last');

		//add li "current" if submenu
		$('#adminmenu a[href="' + $best_match + '"]').closest('li').addClass('current');
		$('#adminmenu a[href="' + $best_match + '"]').closest('li').children( "a" ).addClass('current');

		//submenu overflow fix
		$('#adminmenu a[href="' + $best_match + '"]').closest('.menu-top').off();

	}	

	//Menu height fix
	$(document).trigger( 'resize.wp-fire-once' );

});
