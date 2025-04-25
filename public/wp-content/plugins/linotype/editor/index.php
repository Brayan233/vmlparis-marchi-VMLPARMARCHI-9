<?php

global $LINOTYPE_EDITOR;

define('WP_DEBUG_DISPLAY', false );

define('HANDYLOG_FORCE_PRINT', true );

require_once( dirname(dirname(dirname(dirname(dirname( __FILE__ ))))) . '/wp-admin/admin.php' );

require_once( dirname( __FILE__ ) . '/functions.php' );

add_action( 'linotype_editor_print_scripts', 'print_head_scripts', 20 );
add_action( 'linotype_editor_print_footer_scripts', '_wp_footer_scripts',  20 );
add_action( 'linotype_editor_print_styles', 'print_admin_styles', 20 );

do_action( 'linotype_editor_init' );
do_action( 'linotype_editor_save');

$registered = $wp_scripts->registered;
$wp_scripts = new WP_Scripts;
$wp_scripts->registered = $registered;

//wp_enqueue_script( 'customize-controls' );
wp_enqueue_style( 'customize-controls' );

wp_enqueue_style( 'linotype-editor-master', LINOTYPE_plugin::$plugin['url'] . 'editor/style.css', false, false, 'screen' );
wp_enqueue_script('linotype-editor-master', LINOTYPE_plugin::$plugin['url'] . 'editor/script.js', array('jquery'), '1.0', true );

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

wp_user_settings();
_wp_admin_html_begin();

$body_class = 'wp-core-ui js linotype linotype-editor ' . get_option('linotype_editor_dark','blocks-editor-light' );

if ( wp_is_mobile() ) :
	$body_class .= ' mobile';
	?><meta name="viewport" id="viewport-meta" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=1.2" /><?php
endif;

if ( is_rtl() ) {
	$body_class .= ' rtl';
}

$body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_user_locale() ) ) );

$admin_title = $LINOTYPE_EDITOR['data']['type'] . ' > ' . $LINOTYPE_EDITOR['data']['title'];

?><title><?php echo $admin_title; ?></title>

<script type="text/javascript">
var ajaxurl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php', 'relative' ) ); ?>;
</script>

<?php

do_action( 'linotype_editor_print_styles' );
do_action( 'linotype_editor_print_scripts' );

?>

</head>

<?php 
$sync_repo = 'Local';
if ( get_option('linotype_sync_github_user') && get_option('linotype_sync_github_repo') ) $sync_repo = get_option('linotype_sync_github_user') . '/' . get_option('linotype_sync_github_repo');
$sync_select = '';   
$actions = '';                     
switch ( $LINOTYPE_EDITOR['data']['sync_status'] ) {
    case 'update';
        $sync_select .= '<option value="">Save Localy</option>';
        $sync_select .= '<option value="rebase">Rebase from Github</option>';
        $sync_select .= '<option value="fetch">Fetch Github changes</option>';
        $sync_select .= '<option value="delete">Delete Localy</option>';
        $sync_status = 'Sync with Github';
        //$actions .= '<input value="action_delete" name="action_delete" id="linotype-save" class="button button" type="submit">';
        //$actions .= '<input value="action_delete" name="action_delete" id="linotype-save" class="button button" type="submit">';
        //$actions .= '<input value="action_save" name="action_save" id="linotype-save" class="button button-primary" type="submit">'; 
    break;
    case 'push';
        $sync_select .= '<option value="">Save Localy</option>';
        $sync_select .= '<option value="push">Push to Github</option>';
        $sync_select .= '<option value="rebase">Rebase from Github</option>';
        $sync_select .= '<option value="fetch">Fetch Github changes</option>';
        $sync_select .= '<option value="delete">Delete Localy</option>';
        $sync_status = 'Local Files Modified';
    break;
    case 'pull';
        $sync_select .= '<option value="">Save Localy</option>';
        $sync_select .= '<option value="pull">Update from Github</option>';
        $sync_select .= '<option value="fetch">Fetch Github changes</option>';
        $sync_select .= '<option value="delete">Delete Localy</option>';
        $sync_status = 'Update Available';
    break;
    case 'init';
        $sync_select .= '<option value="">Save Localy</option>';
        $sync_select .= '<option value="init">Create Github Sync</option>';
        $sync_select .= '<option value="fetch">Check Github version</option>';
        $sync_select .= '<option value="delete">Delete Localy</option>';
        $sync_status = 'Only Local';
    break;
    default;
        $sync_select .= '<option value="">Save Localy</option>';
        $sync_status = '';
    break;
} 
?>

<body class="<?php echo esc_attr( $body_class ); ?>">

<form method="post" enctype="multipart/form-data">

    <div class="linotype-toolbar">
        
        <?php

        echo '<span class="linotype-load" style="display:none"><span class="spinner"></span></span>';
        
        if( in_array('close', $LINOTYPE_EDITOR['toolbar'] ) ) echo '<a class="linotype-close" href="' . $LINOTYPE_EDITOR['close_url'] . '"></a>';
        
        if( in_array('back', $LINOTYPE_EDITOR['toolbar'] ) ) echo '<a class="linotype-close back" href="' . $LINOTYPE_EDITOR['return_url'] . '"></a>';
        
        echo '<div class="linotype-toolbar-left">';
            
            echo '<div class="linotype-toolbar-blogname"><a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a></div>';
        
            if ( $LINOTYPE_EDITOR['type'] !== 'index' ) {
                
                echo '<div class="linotype-toolbar-pageinfos"><span style="color: #999;padding: 0px 5px 0px 0px;">Edit</span><span style="color:#333;">' . $LINOTYPE_EDITOR['data']['type'] . '</span></div>';
                
                /*
                echo '<select>';
                    foreach ( LINOTYPE::$TEMPLATES->get() as $TEMPLATE_ID => $TEMPLATE ) {

                        $selected = '';
                        if ( $TEMPLATE['id'] == $LINOTYPE_EDITOR['data']['id'] ) $selected = ' selected="selected"';

                        echo '<option val="' . $TEMPLATE['id'] . '"' . $selected . '>' . $TEMPLATE['title'] . '</option>';
                        
                    }
                echo '</select>';
                */

                echo '<h3 class="linotype-toolbar-title"><span style="color:#333">' . $LINOTYPE_EDITOR['data']['title'] . '</span></h3>';
                
            }

            if ( isset ( $LINOTYPE_EDITOR['tabs'] ) && $LINOTYPE_EDITOR['tabs'] ) { 

                echo '<ul class="linotype-toolbar-section section-menu">';
                    
                    foreach ( $LINOTYPE_EDITOR['tabs'] as $tab_id => $tab ) {
                        
                        $active = '';
                        if ( isset( $tab['active'] ) && $tab['active'] ) $active = 'active';
                        
                        $icon = '';
                        if( isset( $tab['icon'] ) ) $icon = '<div class="' . $tab['icon'] . '"></div>';

                        $count = '';
                        if ( isset( $tab['count'] ) && $tab['count'] !== 0 ) $count = '<span class="count">' . $tab['count'] . '</span>';

                        echo '<li id="linotype-tab-bt-' . $tab_id . '" data-hash="' . $tab_id . '" data-target="#linotype-tab-' . $tab_id . '" class="' . $active . '">' . $icon . '<span>' . $tab['title'] . '</span>' . $count . '</li>';

                    } 
                
                echo '</ul>';

            }

        echo '</div>';
        
        ?>
            
            
        

        <div class="linotype-toolbar-right">
            
            
            <?php if ( $LINOTYPE_EDITOR['type'] !== 'index' ) { ?>
                
                <div class="linotype-toolbar-control">

                    <div class="linotype-toolbar-actions">
                    
                        <?php

                        if ( $sync_select ) {

                        echo '<div class="linotype-sync-status linotype-sync-' . $LINOTYPE_EDITOR['data']['sync_status'] . '">';

                            echo  $sync_repo . ' [' . $sync_status . ']';

                            echo '<div class="linotype-sync-status-infos"><div>';
                                
                                echo '<select name="sync" id="linotype-sync">' . $sync_select . '</select>';

                                echo '<p>local update :' . $LINOTYPE_EDITOR['data']['update'] . '</p>';

                                $COMMITS = LINOTYPE::$SYNC->get_commits( $LINOTYPE_EDITOR['data']['path'] );
                                if ( isset( $COMMITS[0]['date'] ) ) echo '<p>last commit: ' . $COMMITS[0]['date'] . '</p>';
                                
                                echo '<p>repo :' . get_option('linotype_sync_github_user') . '/' . get_option('linotype_sync_github_repo') . '</p>';

                            echo '</div></div>';

                        echo '</div>';

                        echo '';

                        }

                        ?>

                        
                        <input value="save" name="action" id="linotype-save" class="button button-primary" type="submit">
                        
                    </div>

                    <input type="hidden" name="type" value="<?php echo $LINOTYPE_EDITOR['data']['type']; ?>">
                    
                    <input type="hidden" name="id"   value="<?php echo $LINOTYPE_EDITOR['data']['id']; ?>">
                
                </div>
            
            <?php } //else { ?>
                <ul class="linotype-toolbar-section section-menu">

                <?php 
                
                if ( isset ( $LINOTYPE_EDITOR['tabs_right'] ) && $LINOTYPE_EDITOR['tabs_right'] ) { 

                    foreach ( $LINOTYPE_EDITOR['tabs_right'] as $tab_id => $tab ) { 
                        
                        $active = '';
                        if ( isset( $tab['active'] ) && $tab['active'] ) $active = 'active';

                        echo '<li id="linotype-tab-bt-' . $tab_id . '" data-hash="' . $tab_id . '" data-target="#linotype-tab-' . $tab_id . '" class="' . $active . '"><div class="' . $tab['icon'] . '"></div>' . $tab['title'] . '</li>';

                    }

                } 
                
                //if ( ! isset( $LINOTYPE_EDITOR['tabs']['backend'] ) ) echo '<li id="linotype-tab-bt-backend" data-hash="backend" data-target="#linotype-tab-backend" class=""><div style="margin: 0px 5px 0px 0px;" class="dashicons dashicons-wordpress-alt"></div></li>';
                //if ( ! isset( $LINOTYPE_EDITOR['tabs']['frontend'] ) ) echo '<li id="linotype-tab-bt-frontend" data-hash="frontend" data-target="#linotype-tab-frontend" class=""><div style="margin: 0px 5px 0px 0px;" class="dashicons dashicons-admin-site"></div></li>';
                
                ?>
                   
                    
                </ul>
            <?php //} ?>

        </div>

        

    </div>

    <div class="linotype-page">
        
        <?php 
        
        if ( $LINOTYPE_EDITOR['message'] ) {

            ?>

            <div id="message" class="updated-- notice notice-info is-dismissible--">
            
                <p><?php echo $LINOTYPE_EDITOR['message']; ?></p>

                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Ne pas tenir compte de ce message.</span></button>

            </div>

            <?php
            //echo '<div class="linotype-message">' . $LINOTYPE_EDITOR['message'] . '<div class="linotype-message-close dashicons dashicons-no"></div></div>';

        }
        ?>

        <div class="linotype-header">
            <?php do_action( 'linotype_editor_header' ); ?>
        </div>

        <div class="linotype-main">

            <?php 
            
                if ( isset ( $LINOTYPE_EDITOR['tabs'] ) && $LINOTYPE_EDITOR['tabs'] ) { 
                
                    if ( isset ( $LINOTYPE_EDITOR['tabs_right'] ) && $LINOTYPE_EDITOR['tabs_right'] ) $LINOTYPE_EDITOR['tabs'] = array_merge( $LINOTYPE_EDITOR['tabs'], $LINOTYPE_EDITOR['tabs_right']  );

                    foreach ( $LINOTYPE_EDITOR['tabs'] as $tab_id => $tab ) { 
                        
                        if ( file_exists( LINOTYPE_plugin::$plugin['dir'] . 'editor/' . $LINOTYPE_EDITOR['data']['type'] . '/' . $tab_id . '.php' ) ){

                    ?>
                
                    <div id="<?php echo 'linotype-tab-' . $tab_id; ?>" class="linotype-tab <?php if ( isset( $tab['active'] ) && $tab['active'] ) echo 'active'; ?>">
                        
                        <?php include LINOTYPE_plugin::$plugin['dir'] . 'editor/' . $LINOTYPE_EDITOR['data']['type'] . '/' . $tab_id . '.php'; ?>
                        
                    </div>

                    <?php } ?>

                    <div id="linotype-tab-frontend" class="linotype-tab">
                        
                    <?php include LINOTYPE_plugin::$plugin['dir'] . 'editor/frontend.php'; ?>
                                                
                    </div>

                    <div id="linotype-tab-backend" class="linotype-tab">
                        
                        <?php include LINOTYPE_plugin::$plugin['dir'] . 'editor/backend.php'; ?>
                                                    
                    </div>
                
                <?php } ?>
            <?php } ?>

            <?php do_action( 'linotype_editor_main' ); ?>

        </div>

        <div class="linotype-footer">

            <?php do_action( 'linotype_editor_footer' ); ?>

        </div>

    </div>

</form>

<?php do_action( 'linotype_editor_print_footer_scripts' ); ?>

</body>
</html>
