<?php

function get_linotype_field_link( $options = array() ) {

	if ( ! is_array( $options ) ) $options = array();

	$link = array_merge( array(
		'url' => '',
		'target' => '',
		'title' => '',
		'title' => '',
		'rel' => ''
	), $options );

	if ( is_numeric( $link['url'] ) ) {
		$link['url'] = get_the_permalink( $link['url'] );
	}

 	if ( $link['target'] == '_blank' ) {
		 if ( $link['rel'] ) $link['rel'] = $link['rel'] . ' ';
		 $link['rel'] = $link['rel'] . 'noopener';
	}

	return $link;

}