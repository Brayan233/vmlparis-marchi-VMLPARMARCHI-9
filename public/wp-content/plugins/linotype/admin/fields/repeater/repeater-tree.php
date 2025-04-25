<div id="repeater-enable" class="repeater-value repeater-tree dd <?php if ( $is_collapsed ) { echo 'is-collapsed'; } ?>">

  <?php
  if ( ! function_exists('get_item_content') ) {
    function get_item_content( $item ) {

      include dirname( __FILE__ ) . '/repeater-item-content.php'; 
    
    }
  }
  ?>
  
  <ol class="dd-list">

    <?php if ( isset( $NESTABLE ) && $NESTABLE ) { foreach ( $NESTABLE as $item_key => $item) { ?>

      <?php

        $item_url = "";
        if( isset( $item['href'] ) ) $item_url = $item['href'];

        $dom_data = '';

        foreach ( $item as $data_key => $value ) {
          
          if( $data_key !== 'children' ) {
            
            if ( $value != "" ) $value = strip_tags( $value );
            $dom_data .= 'data-' . $data_key .'="' . $value . '" ';
          
          }
          
        }

      ?>

      <li class="dd-item <?php if ( isset( $item['children'] ) && $is_collapsed ) { echo 'dd-collapsed'; } ?>" <?php echo $dom_data; ?> >

        <div class="dd-handle"></div>

        <div class="dd-content">

          <?php echo get_item_content( $item ); ?>

        </div>
        <?php if ( isset( $item['children'] ) && is_array( $item['children'] ) ) { ?>

          <ol class="dd-list">

              <?php foreach ($item['children'] as $subitem_key => $subitem) { ?>

                <?php 

                  $dom_data = '';

                  foreach ( $subitem as $data_key => $value ) {
                    
                    if( $data_key !== 'children' ) { 
            
                      if ( $value != "" ) $value = strip_tags( $value );
                      $dom_data .= 'data-' . $data_key .'="' . $value . '" ';
                    
                    }

                  }

                ?>

                <li class="dd-item <?php if ( isset( $subitem['children'] ) && $is_collapsed ) { echo 'dd-collapsed'; } ?> <?php if( $disable_first_submenu && $item_url == $subitem['href'] ) { echo 'dd-disable'; } ?>" <?php echo $dom_data; ?> >

                <?php if( $disable_first_submenu == false || $item_url !== $subitem['href'] ) { ?>
                  <div class="dd-handle"></div>
                <?php } ?>

                <div class="dd-content">

                  <?php echo get_item_content( $subitem ); ?>

                </div>

                  <?php if ( isset( $subitem['children'] ) && is_array( $subitem['children'] ) ) { ?>

                    <ol class="dd-list">

                      <?php foreach ($subitem['children'] as $subsubitem_key => $subsubitem) { ?>

                        <?php 

                          $dom_data = '';

                          foreach ( $subsubitem as $data_key => $value ) {
                            
                            if( $data_key !== 'children' ) { 
            
                              if ( $value != "" ) $value = strip_tags( $value );
                              $dom_data .= 'data-' . $data_key .'="' . $value . '" ';
                            
                            }

                          }

                        ?>

                        <li class="dd-item <?php if ( $is_collapsed && isset( $item['children'] ) ) { echo 'dd-collapsed'; } ?>" <?php echo $dom_data; ?> >

                          <div class="dd-handle"></div>

                          <div class="dd-content">

                            <?php echo get_item_content( $subsubitem ); ?>

                          </div>

                          <?php if ( isset( $subsubitem['children'] ) && is_array( $subsubitem['children'] ) ) { ?>

                            <ol class="dd-list">

                              <?php foreach ($subsubitem['children'] as $subsubsubitem_key => $subsubsubitem) { ?>

                                <?php 

                                  $dom_data = '';

                                  foreach ( $subsubsubitem as $data_key => $value ) {
                                    
                                    if( $data_key !== 'children' ) { 
            
                                      if ( $value != "" ) $value = strip_tags( $value );
                                      $dom_data .= 'data-' . $data_key .'="' . $value . '" ';
                                    
                                    }

                                  }

                                ?>

                                <li class="dd-item <?php if ( $is_collapsed && isset( $item['children'] ) ) { echo 'dd-collapsed'; } ?>" <?php echo $dom_data; ?> >

                                  <div class="dd-handle"></div>

                                  <div class="dd-content">

                                    <?php echo get_item_content( $subsubsubitem ); ?>

                                  </div>

                                </li>

                              <?php } ?>

                            </ol>

                          <?php } ?>

                        </li>

                      <?php } ?>

                    </ol>

                  <?php } ?>

                </li>

              <?php } ?>

            </ol>

          <?php } ?>

        </li>

      <?php } } ?>

    </ol>

</div>


