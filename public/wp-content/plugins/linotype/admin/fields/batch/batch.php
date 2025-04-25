<?php

wp_enqueue_style( 'field-batch', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/batch.css', false, false, 'screen' );
wp_enqueue_script('field-batch', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/batch.js', array('jquery'), '1.0', true );

wp_localize_script( 'field-batch', 'LINOADMIN_AJAX', array(
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
));

$default_options = array(
	"nonce" => wp_create_nonce( $field['id'] ),
	"action" => null,
	"batch_size" => 10,
	"query" => array(
		"s" => '',
		"post_type" => array('post'),
		"post_status" => "publish",
	),
	"ids" => array(),
	"script" => null,
);

$field['options'] = handypress_helper::parse_args( $field['options'], $default_options );

if ( $field['options']['query']['s'] ) {

	global $wpdb;

	$search = strtoupper( $field['options']['query']['s'] );
	$post_type = "('" . implode("','", $field['options']['query']['post_type'] ) . "')";
	$post_status = $field['options']['query']['post_status'];

	$query_post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE UCASE(post_title) LIKE '%$search%' AND post_type IN $post_type AND post_status='$post_status' ORDER BY '$wpdb->postmeta.post_id' " );
	$query_post_titles = $wpdb->get_col("SELECT post_title FROM $wpdb->posts WHERE UCASE(post_title) LIKE '%$search%' AND post_type IN $post_type AND post_status='$post_status' ORDER BY '$wpdb->postmeta.post_id' " );

	$field['options']['ids'] = implode(',', $query_post_ids );

} else {

	global $wpdb;

	$post_type = "('" . implode("','", $field['options']['query']['post_type'] ) . "')";
	$post_status = $field['options']['query']['post_status'];

	$query_post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type IN $post_type AND post_status='$post_status' ORDER BY '$wpdb->postmeta.post_id' " );
	$query_post_titles = $wpdb->get_col("SELECT post_title FROM $wpdb->posts WHERE post_type IN $post_type AND post_status='$post_status' ORDER BY '$wpdb->postmeta.post_id' " );

	$field['options']['ids'] = implode(',', $query_post_ids );

}
//wpbatch_search_only_title
?>

<li class="wp-field wp-field-batch <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="batch-content" >

			<h3>Summary</h3><p>Ready to apply your script on <b><?php echo count( $query_post_ids ); ?></b> post(s) batched by <b><?php echo $field['options']['batch_size']; ?></b></p>

			<div class="progress<?php if ( file_exists( $field['options']['file_dir'] ) ) { echo ' progress-success'; }?>"><div class="progress-bar"><div class="progress-percent"></div></div></div>

			<!-- <div class="button button-primary edit-batch">Show Batch List</div> --> <div class="button button-success start-batch">Start Batch</div>

			<div class="button button-delete stop-batch" style="display:none">Stop Batch</div>

			<ul class="messages"></ul>

		</div>

		<div class="field-input" style="display:none;" >
			<textarea style="margin-top:10px" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo json_encode( $field['options'] ); ?></textarea>
		</div>

		<br/>

		<?php

			echo '<div class="field-title" >Batch list:</div>';

			echo '<ul class="batch-list">';

				if ( $query_post_ids ) {

					foreach ( $query_post_ids as $key => $id ) {

						$title = str_ireplace( $field['options']['query']['s'], '<span class="batch-found">' . $field['options']['query']['s'] . '</span>',  $query_post_titles[$key] );

						echo '<li id="batch-' . $id . '" data-batch-id="' . $id . '"><span class="batch-count">' . ( $key + 1 ) . '.</span><span class="batch-cb"><input type="checkbox" checked="checked" /></span><span class="batch-id">' . $id . '</span><span class="batch-title">' . $title . '</span> - <a href="/wp-admin/post.php?post='. $id .'&action=edit" target="_blank">Edit</a></li>';

					}

				} else {

					echo '<br/>No post to process';

				}

			echo '</ul>';

		?>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>
