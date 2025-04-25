<?php

wp_enqueue_script('filterjs', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/filterjs/filter.js', array('jquery'), '1.0', true );
//wp_enqueue_script('filterjs-pagination', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/filterjs/pagination.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'handypress-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/list.css', false, false, 'screen' );
wp_enqueue_script('handypress-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/list.js', array('jquery'), '1.0', true );

$default_options = array(
	'col' => 'col-md-4',
	'height' => '',
	'preview' => true,
	'as_list' => false,
	'toolbar' => function(){},
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div id="field_<?php echo $field['id']; ?>" class="wp-field wp-field-list <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>
		
		<?php 
		
		$list_categories = array();
		$list_libraries = array();
		$list_tags = array();
		$list_author = array();
		$list_target = array();
		
		$has_no_category = false;
		$has_no_librariy = false;
		$has_no_tag = false;
		$has_no_author = false;
		$has_no_target = false;
		
		//_HANDYLOG( $field['options']['items'] );
		// usort( $field['options']['items'], function( $a, $b ) {
		// 	//if ( isset( $a['update'] ) && $a['update'] && isset( $b['update'] ) && $b['update'] ) {
		// 		//return  strtotime($b["update"]) - strtotime($a["update"]);
		// 	//}
		// 	return  strcmp($a["title"], $b["title"]);
		// });
		//_HANDYLOG( $field['options']['items'] );
		
		$field['options']['items'] = $field['options']['items'];

		if( $field['options']['items'] ) { 

			foreach ( $field['options']['items'] as $item_key => $item ) {
				
				$field['options']['items'][$item_key]['id'] = $item_key;

				if ( $field['options']['items'][$item_key]['source'] === "default" ){

					$field['options']['items'][$item_key]['sync_status'] = "core";
					$field['options']['items'][$item_key]['sync_title'] = "Core";

				} else {

					switch ( $field['options']['items'][$item_key]['sync_status'] ) {
						case 'update':
							$field['options']['items'][$item_key]['sync_title'] = "Sync";
						break;
						case 'push':
							$field['options']['items'][$item_key]['sync_title'] = "Modified";
						break;
						case 'pull':
							$field['options']['items'][$item_key]['sync_title'] = "Update";
						break;
						case 'init':
						default:
							$field['options']['items'][$item_key]['sync_title'] = "Local";
						break;
					} 
				
				}

				if ( isset( $item['target'] ) && is_array( $item['target'] ) ) {
					
					foreach( $item['target'] as $target ){
						
						if ( $target ) {
							//$target_data = LINOTYPE::$TARGET->get($target);
							$list_target[$target] = $target;
						} else {
							$has_no_target = true;
						}
					}
					
				} else {
					$field['options']['items'][$item_key]['target'] = array("Any");
					$has_no_target = true;
				}

				if ( isset( $item['libraries'] ) && $item['libraries'] ) {
					
					foreach( $item['libraries'] as $librariy ){
						
						if ( $librariy ) {

							$librariy_data = LINOTYPE::$LIBRARIES->get($librariy);
							
							$list_libraries[$librariy] = $librariy_data['title'];
							
							if ( ! $list_libraries[$librariy] ) $list_libraries[$librariy] = $librariy;
						
						} else {
							$has_no_librariy = true;
						}
					}
					
				} else {
					$field['options']['items'][$item_key]['libraries'] = array("No dependencies");
					$has_no_librariy = true;
				}

				if ( isset( $item['category'] ) && $item['category'] ) {
					$list_categories[$item['category']] = $item['category'];
				} else {
					$field['options']['items'][$item_key]['category'] = array("Other");
					$has_no_category = true;
				}

				if ( isset( $item['author'] ) && $item['author'] ) {
					$list_author[$item['author']] = $item['author'];
				} else {
					$field['options']['items'][$item_key]['author'] = array("Unknow");
					$has_no_author = true;
				}
				

				if ( isset( $item['tags'] ) && $item['tags'] ) {
					
					foreach( $item['tags'] as $tag ){
						
						if ( $tag ) {
							$list_tags[$tag] = $tag;
						} else {
							$has_no_tag = true;
						}

					}
					
				} else {
					$field['options']['items'][$item_key]['tags'] = array("Other");
					$has_no_tag = true;
				}
		
			}

			if ( $has_no_category ) $list_categories['Other'] = 'Other';
			if ( $has_no_librariy ) $list_libraries['No dependencies'] = 'No dependencies';
			if ( $has_no_tag ) $list_tags['Other'] = 'Other';
			if ( $has_no_author ) $list_author['Unknow'] = 'Unknow';
			if ( $has_no_target ) $list_target['Any'] = 'Any';
			
		}

		$field['options']['items'] = array_values( $field['options']['items'] );

		?>
		
		

		<div class="wp-field-list-content">

			<div class="list-toolbar">

				<div class="list-toolbar-left">

				<span class="wp-field-list-filters-toggle dashicons dashicons-search"></span>
				
				<input type="text" class="wp-field-list-filters-search" id="searchbox" placeholder="Search" autocomplete="off">
					
				<fieldset id="category_criteria" class="wp-field-list-filter-checkbox inline">
				
					<div class="checkbox">
						<label>
							<input class="checkbox_input checkbox_all all" type="checkbox" value="All" id="all_category">
							<span>All</span>
						</label>
					</div>

					<?php

					if( $list_categories ) { 

						foreach ( $list_categories as $category ) {
							
							?>
							<div class="checkbox">
								<label>
									<input class="checkbox_input" type="checkbox" data-title="<?php echo $category; ?>" value="<?php echo $category; ?>">
									<span><?php echo $category; ?><span>
								</label>
							</div>
							<?php

						}
					}

					?>
					
				
				</fieldset>

				</div>

				<div class="list-toolbar-right">
					
					<?php echo $field['options']['toolbar'](); ?>
					
				</div>

			</div>

			<div class="wp-field-list-filters">

				
				
				<fieldset id="target_criteria" class="wp-field-list-filter-checkbox">
					
					<h4 class="checkbox_input_title">Filter by Type</h4>
						
						<div class="checkbox">
							<label>
								<input class="checkbox_input checkbox_all all" type="checkbox" value="All" id="all_target">
								<span>All</span>
							</label>
						</div>

						<?php

						if( $list_target ) { 

							foreach ( $list_target as $target => $target_title ) {
								
								?>
								<div class="checkbox">
									<label>
										<input class="checkbox_input" type="checkbox" data-title="<?php echo $target_title; ?>" value="<?php echo $target; ?>">
										<span><?php echo $target_title; ?><span>
									</label>
								</div>
								<?php

							}
						}

						?>
						
						
				</fieldset>

				
				<fieldset id="libraries_criteria" class="wp-field-list-filter-checkbox">
					
					<h4 class="checkbox_input_title">Filter by Libraries</h4>
						
						<div class="checkbox">
							<label>
								<input class="checkbox_input checkbox_all all" type="checkbox" value="All" id="all_libraries">
								<span>All</span>
							</label>
						</div>

						<?php

						if( $list_libraries ) { 

							foreach ( $list_libraries as $library => $library_title ) {
								
								?>
								<div class="checkbox">
									<label>
										<input class="checkbox_input" type="checkbox" data-title="<?php echo $library_title; ?>" value="<?php echo $library; ?>">
										<span><?php echo $library_title; ?><span>
									</label>
								</div>
								<?php

							}
						}

						?>
						
						
				</fieldset>

				<fieldset id="tags_criteria" class="wp-field-list-filter-checkbox">
					
					<h4 class="checkbox_input_title">Filter by Tags</h4>
						
						<div class="checkbox">
							<label>
								<input class="checkbox_input checkbox_all all" type="checkbox" value="All" id="all_tags">
								<span>All</span>
							</label>
						</div>

						<?php

						if( $list_tags ) { 

							foreach ( $list_tags as $tag ) {
								
								?>
								<div class="checkbox">
									<label>
										<input class="checkbox_input" type="checkbox" data-title="<?php echo $tag; ?>" value="<?php echo $tag; ?>">
										<span><?php echo $tag; ?><span>
									</label>
								</div>
								<?php

							}
						}

						?>
						
						
				</fieldset>

				<fieldset id="author_criteria" class="wp-field-list-filter-checkbox">
					
					<h4 class="checkbox_input_title">Filter by Author</h4>
						
						<div class="checkbox">
							<label>
								<input class="checkbox_input checkbox_all all" type="checkbox" value="All" id="all_author">
								<span>All</span>
							</label>
						</div>

						<?php

						if( $list_author ) { 

							foreach ( $list_author as $author ) {
								
								?>
								<div class="checkbox">
									<label>
										<input class="checkbox_input" type="checkbox" data-title="<?php echo $author; ?>" value="<?php echo $author; ?>">
										<span><?php echo $author; ?><span>
									</label>
								</div>
								<?php

							}
						}

						?>
						
						
				</fieldset>
				
	
			</div>
		
			<div class="wp-field-list-items">
				
				<div class="wp-field-list-items-content listcard-items <?php if ( $field['options']['as_list'] ) echo 'as-list'; ?> linoadmin-row linoadmin-no-gutters" id="wp-field-list-items-content"> </div>
				
				<div class="wp-field-list-bottom">
					<div id="pagination" class="wp-field-list-pagination movies-pagination"></div>
					<span id="per_page" class="wp-field-list-perpage content"></span>
				</div>

			</div>

		</div>

		<script id="movie-template" type="text/html">
			
			<div class="movie listcard-item linoadmin-<?php echo $field['options']['col']; ?>">

				<div class="listcard-item-content">
							
					<div class="listcard-header" style="min-height:<?php echo $field['options']['height']; ?>">
						
						<a href="<%= editor_link %>" class="listcard-title"><div class="listcard-icon <%= icon %>"></div><%= title %></a>
						<div class="listcard-author">by <%= author %></div>
						<div class="listcard-desc"><%= desc %></div>
						
						
						<div class="listcard-header-right">
							<div class="listcard-sync listcard-sync-<%= sync_status %>"><%= sync_title %></div>
							<!-- <div class="dashicons dashicons-marker" style="color: #e5e5e5;line-height: 22px;font-size: 13px;"></div> -->
							<!-- <a href="<%= editor_link %>" class="button button-primary button-small">Edit</a> -->
						</div>

					</div>
					
					<?php if( $field['options']['preview'] ) { ?>
					<div class="listcard-item-preview" style="background-image:url(<%= preview %>?<?php echo time(); ?>)"></div>
					<?php } ?>

					<div class="listcard-footer">
						<div class="listcard-footer-left">
							
							<div class="listcard-infos">
								
								<div class="listcard-author">Category: <%= category %></div>

								<div class="listcard-type">Type: <%= target %></div>
							
								<div class="listcard-version">Version: <b><%= version %></b></div>
								
								<div class="listcard-date">Updated: <b><%= update %></b></div>
								
								<div class="listcard-author">Author: <a href="#"><%= author %></a></div>
							
							</div>

						</div>

						<div class="listcard-footer-right">
						<div class="listcard-id"><%= id %></div> <div class="listcard-toggle dashicons dashicons-info"></div>
						</div>
						
					</div>
				
				</div>
			
			</div>
		
		</script>
	  
		<script id="tags_template" type="text/html">
			
			<div class="checkbox">
				<label>
				<input type="checkbox" value="<%= tags %>"> <%= tags %>
				</label>
			</div>

		</script>
						
		<script id="tags_template" type="text/javascript">
			var <?php echo $field['id'] . '_data'; ?> = <?php echo json_encode( $field['options']['items'], JSON_HEX_TAG | JSON_UNESCAPED_SLASHES ); ?>
		</script>

		<div class="field-input" style="display:none">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php //echo $field['value']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>
