

<div class="repeater-toolbar-<?php echo $field['options']['toolbar_pos']; ?>">

  <div class="repeater-add-items">

    <ul>

      <?php if ( isset( $field['options']['data_custom'] ) ) { ?>
        <?php foreach ( $field['options']['data_custom'] as $data_custom_key => $data_custom ) { ?> 

          <?php

          $default_data_custom = array(
            'title' => 'Item',
            'desc' => '',
            'button' => 'Add',
          );

          $data_custom = wp_parse_args( $data_custom, $default_data_custom );
          
          ?>

          <li class="repeater-add-item">

            <?php if ( $data_custom['title'] ) { ?><h3><?php echo $data_custom['title']; ?></h3><?php } ?>
               
            <?php if ( $data_custom['desc'] ) { ?><p><?php echo $data_custom['desc']; ?></p><?php } ?>
                    
            <div class="data-custom-add button-secondary" data-fields='<?php echo json_encode( $data_custom["fields"] ); ?>' ><?php echo $data_custom['button']; ?></div>

          </li>
              
        <?php } ?>

      <?php } ?>

    </ul>

  </div>

  <div class="repeater-source-view"><span class="dashicons dashicons-editor-code"></span></div>

</div>
