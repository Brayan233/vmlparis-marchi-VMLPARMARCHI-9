<?php

function get_sg_block_categories( $options ) {
    
  $cat_term_1_data = get_term( $options['cat_term_1'] );
  $cat_term_2_data = get_term( $options['cat_term_2'] );
  $cat_term_3_data = get_term( $options['cat_term_3'] );

  $data = array();
  if ( isset( $cat_term_1_data->name ) ) {
    $data[] = array(
      "name" => $cat_term_1_data->name,
      "count" => $cat_term_1_data->count,
      "url" => get_term_link( $cat_term_1_data->term_id ),
      "img" => get_term_meta( $cat_term_1_data->term_id, 'thumbnail_id', true ),
    );
  }
  if ( isset( $cat_term_2_data->name ) ) {
    $data[] = array(
      "name" => $cat_term_2_data->name,
      "count" => $cat_term_2_data->count,
      "url" => get_term_link( $cat_term_2_data->term_id ),
      "img" => get_term_meta( $cat_term_2_data->term_id, 'thumbnail_id', true ),
    );
  }
  if ( isset( $cat_term_3_data->name ) ) {
    $data[] = array(
      "name" => $cat_term_3_data->name,
      "count" => $cat_term_3_data->count,
      "url" => get_term_link( $cat_term_3_data->term_id ),
      "img" => get_term_meta( $cat_term_3_data->term_id, 'thumbnail_id', true ),
    );
  }

  return $data;

}