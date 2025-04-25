<div class="clear"></div>

<div id="side-sortables" class="accordion-container">

  <ul class="outer-border">

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

        <?php if ( isset( $data_custom['data'] ) ) { ?>

          <li class="control-section accordion-section open" id="add-page">

            <h3 class="accordion-section-title hndle" style="display:block" tabindex="0"><?php echo $data_custom['title']; ?></h3>

            <div style="display: block;" class="accordion-section-content ">

              <div class="inside">

                <div id="posttype-page" class="posttypediv">

                  <!--
                  <ul id="posttype-page-tabs" class="posttype-tabs add-menu-item-tabs">

                    <li class="tabs">
                      <a class="nav-tab-link" data-type="" href="#">All</a>
                    </li>

                    <li class="">
                      <a class="nav-tab-link" data-type="" href="#">custom</a>
                    </li>

                  </ul>
                  -->

                  <div id="" class="tabs-panel tabs-panel-active">

                    <?php if ( isset( $data_custom['data'] ) && $data_custom['data'] ) { ?>

                      <ul id="" class="categorychecklist">

                        <?php $count_lv1 = 1; ?>

                        <?php foreach ( $data_custom['data'] as $item_key => $item) { ?>

                          <li>
                            
                            <?php
                            $the_title = ''; 
                            if ( isset( $item['title'] ) ) { 

                              $the_title = strip_tags( $item['title'] ); 
                            
                            } else if ( $the_title == '' && isset( $item['name'] ) ) { 

                              $the_title = strip_tags( $item['name'] );

                            }
                            ?>

                            <label class="menu-item-title"><input class="menu-item-checkbox" name="" data-id="<?php echo $item_key; ?>" type="checkbox"><?php echo $the_title; ?></label>
                            
                            <?php if ( isset( $item['children'] ) ) { ?>

                              <ul class="children">
                                
                                <?php $count_lv2 = 1; ?>

                                <?php foreach ( $item['children'] as $subitem_key => $subitem ) { ?>
                                  
                                  <li>
                                  
                                    <?php
                                    $the_title = ''; 
                                    if ( isset( $subitem['title'] ) ) { 

                                      $the_title = strip_tags( $subitem['title'] ); 
                                    
                                    } else if ( $the_title == '' && isset( $subitem['name'] ) ) { 

                                      $the_title = strip_tags( $subitem['name'] );

                                    }
                                    ?>

                                    <label class="menu-item-title"><input class="menu-item-checkbox" name="" data-id="<?php echo $item_key . '|' . $subitem_key; ?>" type="checkbox"><?php echo $the_title; ?></label>
                                  
                                  </li>

                                  <?php $count_lv2++; ?>
                                
                                <?php } ?>

                              </ul>

                              <?php } ?>

                          </li>

                          <?php $count_lv1++; ?>

                        <?php } ?>

                      </ul>

                      <textarea style="display:none;" class="menu-item-data"><?php echo json_encode( $data_custom['data'] ); ?></textarea>

                    <?php } ?>

                  </div>

                  <!--
                  <div class="tabs-panel tabs-panel-inactive" id="">

                    <ul id="" class="categorychecklist">

                      <li>
                        <label class="menu-item-title"><input class="menu-item-checkbox" name="" value="" type="checkbox">Dashboard</label>
                        <ul class="children">
                          <li>
                            <label class="menu-item-title"><input class="menu-item-checkbox" name="" value="" type="checkbox">Update</label>
                          </li>
                        </ul>
                      </li>

                    </ul>

                  </div>
                  -->

                  <p class="button-controls">

                    <span class="list-controls">
                      <a href="#" class="menu-item-checkbox-select-all select-all">Select All</a>
                    </span>

                    <span class="add-to-menu">
                      <div class="menu-item-checkbox-add button-secondary right" data-fields='<?php echo json_encode( $data_custom["fields"] ); ?>' ><?php echo $data_custom['button']; ?></div>
                      <span style="display: none;" class="spinner"></span>
                    </span>

                  </p>

                </div>

              </div>

           </div>

          </li>

        <?php } else { ?>

          <li class="control-section accordion-section open" id="add-custom-links">

            <h3 class="accordion-section-title hndle" style="display:block" tabindex="0"><?php echo $data_custom['title']; ?></h3>
               
            <div style="display: block;" class="accordion-section-content ">

              <div class="inside">

                <div class="customlinkdiv" id="customlinkdiv">
                    
                  <!-- 
                  <p id="menu-item-url-wrap">
                  <label class="howto" for="custom-menu-item-url">
                  <span>URL</span>
                  <input id="custom-menu-item-url" name="menu-item[-24][menu-item-url]" class="code menu-item-textbox" value="http://" type="text">
                  </label>
                  </p>
                  -->

                  <p id="menu-item-url-wrap"><?php echo $data_custom['desc']; ?></p>
                    
                  <p class="button-controls">
                    <span class="add-to-menu">
                      <div class="data-custom-add button-secondary right" data-fields='<?php echo json_encode( $data_custom["fields"] ); ?>' ><?php echo $data_custom['button']; ?></div>
                      <span style="display: none;" class="spinner"></span>
                    </span>
                  </p>
                 
                </div>

              </div>

            </div>

          </li>
            
        <?php } ?>

      <?php } ?>

    <?php } ?>

  </ul>

</div>
