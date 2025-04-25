<?php

require_once( dirname(dirname(dirname(dirname(dirname( __FILE__ ))))) . '/wp-admin/admin.php' );

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

wp_user_settings();
_wp_admin_html_begin();

$body_class = 'wp-core-ui js linotype linotype-files ' . get_option('linotype_editor_dark','blocks-editor-light' );

if ( wp_is_mobile() ) :
	$body_class .= ' mobile';
	?><meta name="viewport" id="viewport-meta" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=1.2" /><?php
endif;

if ( is_rtl() ) {
	$body_class .= ' rtl';
}

$body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_user_locale() ) ) );

$admin_title = 'LINOTYPE > Files';

?><title><?php echo $admin_title; ?></title>

<link rel="stylesheet" type="text/css" href="styles/default.css">

</head>

<body class="<?php echo esc_attr( $body_class ); ?>">

<div id="file-manager"></div>

<script src="js/app-es5-min.js"></script>
<script type="text/javascript">
    new engine.fileManager.Application({
        wrapper: document.getElementById('file-manager'),
        configUrl: 'server.php?action=config&',
        csrfToken: 'csrfToken'
    });
</script>

</body>
</html>
