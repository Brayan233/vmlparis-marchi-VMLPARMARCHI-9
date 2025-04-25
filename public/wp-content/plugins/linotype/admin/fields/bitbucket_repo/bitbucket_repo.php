<?php

global $WP_Bucket;

$repos = array();

$wb_get_repos = wb_get_repos();

if ( $wb_get_repos && is_object( $wb_get_repos ) && ! is_a( $wb_get_repos, 'WP_Error' ) ) {

  usort($wb_get_repos->values , function($a, $b) {
    return strcmp($b->updated_on, $a->updated_on);
  });

  $repos = $wb_get_repos->values;

}

?>

<div id="plugin-filter" method="post">
  
  <div class="wp-list-table widefat plugin-install">
  
    <h2 class="screen-reader-text">Plugins list</h2>  

    <div id="the-list">
      
      <?php if ( $repos ) { ?>

        <?php foreach ( $repos as $key => $repo ) { ?>

          <div class="bucketpress-repo plugin-card">

            <div class="plugin-card-top">
              
              <div class="name column-name">
                <h3>
                  <a href="https://bitbucket.org/<?php echo $repo->full_name; ?>" target="_blank" class="thickbox">
                  
                  <?php if ( $repo->is_private ) { ?> 
                    <i class="fa fa-lock"></i> 
                  <?php } else { ?>
                    <i class="fa fa-unlock"></i> 
                  <?php } ?>

                  <?php echo $repo->name; ?>
                  
                  <?php 

                    if ( $repo->links->avatar->href ) { 

                      echo '<img src="' . $repo->links->avatar->href . '" basesrc="' . BUCKETPRESS_URL . 'img/bucketpress-icon-blue-500.png" class="plugin-icon" alt="">'; 
                    
                    } else { 

                      echo '<img src="' . BUCKETPRESS_URL . 'img/bucketpress-icon-blue-500.png" class="plugin-icon" alt="">'; 

                    } 

                    ?>
                  
                  </a>
                </h3>
              </div>
            
              <div class="action-links">
                <ul class="plugin-action-buttons">
                  
                  <?php 

                  $slug_part = explode( '/', $repo->full_name );
                  $repo->owner = $slug_part[0];
                  $repo->slug = $slug_part[1];

                  $plugins_dir = file_exists( WP_PLUGIN_DIR . '/' . $repo->slug );
                  $themes_dir = file_exists( WP_CONTENT_DIR . '/themes/' . $repo->slug );

                  $select_plugin = '';
                  $select_theme = '';
                  $type = '';

                  if ( $plugins_dir ) {
                    $select_plugin = 'selected="selected"';
                    $type = 'plugin';
                  } else if ( $themes_dir ) {
                    $select_theme = 'selected="selected"';
                    $type = 'theme';
                  }

                  ?>

                  <?php if ( $plugins_dir || $themes_dir ) { ?>

                    <li><a class="bucketpress-install button" data-baseurl="<?php echo admin_url('admin.php?page=bucketpress_setting' ); ?>" data-owner="<?php echo $repo->owner; ?>" data-repo="<?php echo $repo->slug; ?>" href="<?php echo admin_url('admin.php?page=bucketpress_setting&action=install&type=plugin&owner=' . $repo->owner . '&repo=' . $repo->slug . '&branch=' . 'master' ); ?>" aria-label="" data-name=""><i class="fa fa-refresh"></i> Update</a></li>
                
                    <li>
                      <select id="bucketpress-type" style="width: 79px;" disabled="disable">
                        <option value="plugin" <?php echo $select_plugin; ?>>Plugin</option>
                        <option value="theme" <?php echo $select_theme; ?>>Theme</option>
                      </select>
                    </li>

                    <?php if ( $type ) { ?>
                      <li><a href="<?php echo admin_url('admin.php?page=bucketpress_setting&action=delete&type='.$type.'&repo=' . $repo->slug ); ?>" class="thickbox color-red">Delete</a></li>
                    <?php } ?>

                  <?php } else { ?>

                    <li><a class="bucketpress-install button button-primary" data-baseurl="<?php echo admin_url('admin.php?page=bucketpress_setting' ); ?>" data-owner="<?php echo $repo->owner; ?>" data-repo="<?php echo $repo->slug; ?>" href="<?php echo admin_url('admin.php?page=bucketpress_setting&type=plugin&action=install&owner=' . $repo->owner . '&repo=' . $repo->slug . '&branch=' . 'master' ); ?>" aria-label="" data-name=""><i class="fa fa-download"></i> Install</a></li>

                    <li>
                      <select id="bucketpress-type" style="width: 72px;">
                        <option value="plugin" <?php echo $select_plugin; ?>>Plugin</option>
                        <option value="theme" <?php echo $select_theme; ?>>Theme</option>
                      </select>
                    </li>

                  <?php } ?>

                </ul>
              </div>
          
              <div class="desc column-description">

                <p><?php echo $repo->description; ?></p>
                <p class="authors"> <cite>By <a href="<?php echo admin_url('admin.php?page=bucketpress_setting&action=search&owner=' . $repo->owner . '#repositories_search' ); ?>"><?php echo $repo->owner; ?></a></cite> <?php if ( $repo->has_issues ) { ?><i class="fa fa-bug"></i> Has Issues <?php } ?></p>

              </div>
          
            </div>
          
            <div class="plugin-card-bottom">
              
              <div class="vers column-rating">
                
                <i class="fa fa-code-fork"></i> <input id="bucketpress-branch" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="master" value="" size="6" style="margin:0px;padding:0px;border:none;box-shadow:none;background-color:inherit;border-bottom:1px solid #ddd;" /> 
                
              </div>

              <div class="column-updated">
                <?php
                $date_parse = date_parse( $repo->updated_on );
                $display_date = date( get_option('date_format') . ' ' . get_option('time_format'), mktime($date_parse['hour'], $date_parse['minute'], $date_parse['second'], $date_parse['month'], $date_parse['day'], $date_parse['year']) );  
                ?>
                <strong>Last Updated:</strong> <span title=""><?php echo $display_date; ?></span>
              </div>
              
              <div class="column-downloaded">
                <?php if ( $repo->size ) { ?> 
                 <i class="fa fa-angle-double-down"></i> <?php echo number_format( $repo->size / 1048576, 2) . ' MB'; ?>
                <?php } ?>
              </div>
              
              <div class="column-compatibility">
                <?php
                $date_parse = date_parse( $repo->created_on );
                $display_date = date( get_option('date_format') . ' ' . get_option('time_format'), mktime($date_parse['hour'], $date_parse['minute'], $date_parse['second'], $date_parse['month'], $date_parse['day'], $date_parse['year']) );  
                ?>
                <strong>Created On:</strong> <span title=""><?php echo $display_date; ?></span>
              </div>
            
            </div>
        
          </div>

        <?php } ?>

      <?php } else { ?>

        <p>No repositories listed, please connect your account or <a href="#addrepositories">add public repository</a></p>

      <?php }?>

    </div>

  </div>

</div>
