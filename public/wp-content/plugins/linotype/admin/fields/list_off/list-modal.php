

<div class="list-modal" list-target="">

  <div class="list-modal-container">

    <div class="list-modal-toolbar">

      <h1>Add Elements</h1>

      <span class="list-modal-close list-bt fa fa-close"></span>

    </div>

    <div class="list-modal-content">

      <div class="list-add-items">

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

              <li class="list-add-item">

                <?php if ( $data_custom['title'] ) { ?><h3><?php echo $data_custom['title']; ?></h3><?php } ?>

                <?php if ( $data_custom['desc'] ) { ?><p><?php echo $data_custom['desc']; ?></p><?php } ?>

                <div class="list-add-item-button button-secondary" data-fields='<?php echo json_encode( $data_custom["fields"] ); ?>' ><?php echo $data_custom['button']; ?></div>

              </li>

            <?php } ?>

          <?php } ?>

        </ul>

      </div>

    </div>

  </div>

</div>
