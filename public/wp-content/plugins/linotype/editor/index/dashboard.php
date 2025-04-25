<div class="linotype_editor_index row" style="margin:40px 50px">

    <?php

    wp_enqueue_style( 'linotype-index', LINOTYPE_plugin::$plugin['url'] . 'editor/index/index.css', false, false, 'screen' );
    wp_enqueue_script('linotype-index', LINOTYPE_plugin::$plugin['url'] . 'editor/index/index.js', array('jquery'), '1.0', true );

    $THEMES = LINOTYPE::$THEMES->get();
    $THEME = LINOTYPE::$THEMES->get( LINOTYPE::$SETTINGS['theme']['id'] );

    $wp_theme = wp_get_theme();

    $select = '';

    $DATA = LINOTYPE::$CURRENT->get();
   
    if ( $THEMES ) {

        $select .=  '<select class="theme-select">';

            $select .=  '<option value=" ">- Select theme -</option>';

            foreach ( $THEMES as $theme_id => $theme ) {

                $selected = '';
                //if ( $theme_id == LINOTYPE::$SETTINGS['theme']['id'] ) $selected = ' selected="selected"';
                $select .=  '<option value="' . $theme_id . '"' . $selected . '>' . $theme['title'] . '</option>';
                
            }

        $select .=  '</select>';

    }

    ?>

    <div class="theme-current">
        
        <h4>Current Theme</h4>
        
        <h2><span class="theme-current-title"><?php echo $wp_theme->get( 'Name' ); ?></span> <span class="theme-change-button button button-small">change</span></h2>

        <div class="theme-install" style="display:none">

            <?php echo $select; ?> <span class="theme-install-button button">Install</span>

        </div>

    </div>

    <div class="theme-stats">

        <h3>Active Templates</h3>

        <?php 

        if ( $DATA['templates'] ) {

            echo '<ul class="theme-active-template">';

                foreach ( $DATA['templates'] as $template_id ) {

                    $template = LINOTYPE::$TEMPLATES->get( $template_id );
                    
                    if ( isset( $template['title'] ) && $template['title'] !== "" ) {
                        echo '<li><a class="sync-button" data-type="template" data-id="' . $template_id . '" href="#">SYNC</a> ' . $template['title'] . '</li>';
                    } else {
                        echo '<li><a class="import-button" data-type="template" data-id="' . $template_id . '" href="#">IMPORT</a> <i class="error">' . $template_id . '</i></li>';
                    }
                    
                }

            echo '</ul>';

        }

        ?>

        <h3>Active Modules</h3>

        <?php 

        if ( $DATA['modules'] ) {

            echo '<ul class="theme-active-modules">';

                foreach ( $DATA['modules'] as $module_id ) {

                    $module = LINOTYPE::$MODULES->get( $module_id );

                    if ( isset( $module['title'] ) && $module['title'] !== "" ) {
                        echo '<li><a class="sync-button" data-type="module" data-id="' . $module_id . '" href="#">SYNC</a>  ' . $module['title'] . '</li>';
                    } else {
                        echo '<li><a class="import-button" data-type="module" data-id="' . $module_id . '" href="#">IMPORT</a> <i class="error">' . $module_id . '</i></li>';
                    }
                    
                }

            echo '</ul>';

        }
        ?>

        <h3>Active Blocks</h3>

        <?php 

        if ( $DATA['blocks'] ) {

            echo '<ul class="theme-active-blocks">';

                foreach ( $DATA['blocks'] as $block_id ) {

                    $block = LINOTYPE::$BLOCKS->get( $block_id );

                    if ( isset( $block['title'] ) && $block['title'] !== "" ) {
                        echo '<li><a class="sync-button" data-type="block" data-id="' . $block_id . '" href="#">SYNC</a>  ' . $block['title'] . '</li>';
                    } else {
                        echo '<li><a class="import-button" data-type="block" data-id="' . $block_id . '" href="#">IMPORT</a> <i class="error">' . $block_id . '</i></li>';
                    }
                    
                }

            echo '</ul>';

        }
        ?>

        <h3>Active Fields</h3>

        <?php 

        if ( $DATA['fields'] ) {

            echo '<ul class="theme-active-fields">';

                foreach ( $DATA['fields'] as $field_id ) {

                    $field = LINOTYPE::$FIELDS->get( $field_id );

                    if ( isset( $field['title'] ) && $field['title'] !== "" ) {
                        echo '<li><a class="sync-button" data-type="field" data-id="' . $field_id . '" href="#">SYNC</a>  ' . $field['title'] . '</li>';
                    } else {
                        echo '<li><a class="import-button" data-type="field" data-id="' . $field_id . '" href="#">IMPORT</a> <i class="error">' . $field_id . '</i></li>';
                    }
                    
                }

            echo '</ul>';

        }
        ?>

        <h3>Active Libraries</h3>

        <?php 

        if ( $DATA['libraries'] ) {

            echo '<ul class="theme-active-libraries">';

                foreach ( $DATA['libraries'] as $library_id ) {

                    $library = LINOTYPE::$LIBRARIES->get( $library_id );

                    if ( isset( $library['title'] ) && $library['title'] !== "" ) {
                        echo '<li><a class="sync-button" data-type="library" data-id="' . $library_id . '" href="#">SYNC</a>  ' . $library['title'] . '</li>';
                    } else {
                        echo '<li><a class="import-button" data-type="library" data-id="' . $library_id . '" href="#">IMPORT</a> <i class="error">' . $library_id . '</i></li>';
                    }
                    
                }

            echo '</ul>';

        }
        ?>

    </div>

</div>