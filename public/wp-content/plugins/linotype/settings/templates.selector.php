<?php

global $pagenow;

$template_title = 'Not defined';
$template_placeholder = 'Select Template';
$template_autoloded = '';

if ( LINOADMIN::$post_type && isset( LINOTYPE::$THEME['map'][ LINOADMIN::$post_type ]['types']['single']['template'] ) ) {

    $template_placeholder = 'Autoload';

    if ( get_post_meta( LINOADMIN::$post_id, '_linotype_template', true ) ) {
    
        $template_autoloded = get_post_meta( LINOADMIN::$post_id, '_linotype_template', true );
    
    } else {
        
        $template_autoloded = LINOTYPE::$THEME['map'][ LINOADMIN::$post_type ]['types']['single']['template'];
    
    }

}

if ( LINOTYPE::$SETTINGS['linotype_content_post_types'] ) {

    foreach ( LINOTYPE::$SETTINGS['linotype_content_post_types'] as $post_type ) {

        $remove_supports = array();
        if ( isset( LINOTYPE::$TEMPLATES->get( $template_autoloded )['supports'] ) ) $remove_supports = LINOTYPE::$TEMPLATES->get( $template_autoloded )['supports'];
       
        self::$admin->addLocation( $post_type, array(
            "type" => 'post',
            "capability" => 'linotype_edit',
            "remove_supports" => $remove_supports,
        ));

        if ( isset( LINOTYPE::$TEMPLATES->get( $template_autoloded )['title'] ) ) $template_title = LINOTYPE::$TEMPLATES->get( $template_autoloded )['title'];

        self::$admin->addMetabox( 'linotype_metabox_settings', array(
        "name"=>'Template: <span style="color: #2c74aa;">' . $template_title . '</span>',
        "context"=>'side',
        "priority"=>'over',
        "force_state" => "close",
        "hide_box_style" => false,
        "hide_handle" => false,
        "disable_switch" => false,
        "disable_sortable" => false,
        "remove_padding" => true,
        //"bt_save" => "Load",
        ));

       

        if ( LINOTYPE::$SETTINGS['has_templates'] ) {

            $edit_template_link = '';
            if ( $template_autoloded && current_user_can( 'linotype_admin' ) ) $edit_template_link = '<a target="_blank" style="width: 100%;text-align: center;" class="button" href="' . LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=template&id=' . $template_autoloded . '">Edit template</a>';

            if ( $edit_template_link ) {
                    
                self::$admin->addMeta('_linotype_template_edit', array(
                    "title"=>'',
                    "type"=> 'html',
                    "options" => array(
                        "data" => $edit_template_link,
                    ),
                    "help" => false,
                    "padding" => "20px 20px 20px 20px;border-bottom:1px solid #f5f5f5;",
                    "fullwidth" => true,
                ));
            
            }

        }

        if ( LINOTYPE::$SETTINGS['has_templates'] ) {

            self::$admin->addMeta('_linotype_template', array(
                "title"=>'Define a template:',
                "type"=> 'select',
                "options" => array(
                    "data" => LINOTYPE::$TEMPLATES->get_select_data(),
                    "maxItems" => 1,
                    "empty" => true,
                    "plugins" => array('clear_button'),
                    "placeholder" => $template_placeholder
                ),
                // "default" => "1",
                // 'desc' => ,
                "help" => false,
                "padding" => "20px 20px 20px 20px;",
                "fullwidth" => true,
            ));

        }

    
    }

}

?>
