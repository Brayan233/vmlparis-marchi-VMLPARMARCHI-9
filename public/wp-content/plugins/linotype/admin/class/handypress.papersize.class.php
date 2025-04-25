<?php

/**
*
* handypress_papersize
*
* //Source-helper:
* http://en.wikipedia.org/wiki/Photo_print_sizes
* http://www.endmemo.com/sconvert/millimeterpixel.php
*
**/
if ( ! class_exists('handypress_papersize') ) {
  
class handypress_papersize {

  static $paper_size = array( 

    "ISO" => array(

      'A0'                  => array( 841 , 1189 ),
      'A1'                  => array( 594 , 841 ),
      'A2'                  => array( 420 , 594 ),
      'A3'                  => array( 297 , 420 ),
      'A4'                  => array( 210 , 297 ),
      'A5'                  => array( 148 , 210 ),
      'A6'                  => array( 105 , 148 ),
      'A7'                  => array( 74  , 105 ),
      'A8'                  => array( 52  , 74 ),
      'A9'                  => array( 37  , 52 ),
      'A10'                 => array( 26  , 37 ),

      'B0'                  => array( 1000 , 1414 ),
      'B1'                  => array( 707  , 1000 ),
      'B2'                  => array( 500  , 707 ),
      'B3'                  => array( 353  , 500 ),
      'B4'                  => array( 250  , 353 ),
      'B5'                  => array( 176  , 250 ),
      'B6'                  => array( 125  , 176 ),
      'B7'                  => array( 88   , 125 ),
      'B8'                  => array( 62   , 88 ),
      'B9'                  => array( 44   , 62 ),
      'B10'                 => array( 31   , 44 ),

      'C0'                  => array( 917 , 1297 ),
      'C1'                  => array( 648 , 917 ),
      'C2'                  => array( 458 , 648 ),
      'C3'                  => array( 324 , 458 ),
      'C4'                  => array( 229 , 324 ),
      'C5'                  => array( 162 , 229 ),
      'C6'                  => array( 114 , 162 ),
      'C7'                  => array( 81  , 114 ),
      'C8'                  => array( 57  , 81 ),
      'C9'                  => array( 40  , 57 ),
      'C10'                 => array( 28  , 40 ),

    ),
    
    "DIN" => array(

      'DL'                  => array( 99  , 210 ),
      'DLE'                 => array( 110 , 220 ),
      'F4'                  => array( 210 , 330 ),

    ),

    "US" => array(

      'Letter'              => array( 8.5 , 11 ),
      'Government-Letter'   => array( 8.0 , 10.5 ),
      'Legal'               => array( 8.5 , 14 ),
      'JuniorLegal'         => array( 8.0 , 5.0 ),
      'Ledger'              => array( 17  , 11 ),
      'Tabloid'             => array( 11  , 17 ),

      'ANSI A'              => array( 8.5 , 11 ),
      'ANSI B'              => array( 11  , 17 ),
      'ANSI C'              => array( 17  , 22 ),
      'ANSI D'              => array( 22  , 34 ),
      'ANSI E'              => array( 34  , 44 ),

      'Arch A'              => array( 9  , 12 ),
      'Arch B'              => array( 12 , 18 ),
      'Arch C'              => array( 18 , 24 ),
      'Arch D'              => array( 24 , 36 ),
      'Arch E'              => array( 36 , 48 ),
      'Arch E1'             => array( 30 , 42 ),
      'Arch E2'             => array( 26 , 38 ),
      'Arch E3'             => array( 27 , 39 ),

    ),

    "UK" => array(

      'Quarto'              => array( 10 , 8 ),
      'Foolscap'            => array( 13 , 8 ),
      'Imperial'            => array( 9  , 7 ),
      'Kings'               => array( 8  , 6.5 ),
      'Dukes'               => array( 7  , 5.5 ),
    
    ),

  );

  /**
   * get
   *
   * - get all paper size 
   *
  */
  static function get( $array_merge = false ) {

    if ( $array_merge ) {
    
      //merge paper_size array
      $all_paper_size = array_merge( self::$paper_size['ISO'], self::$paper_size['DIN'], self::$paper_size['US'], self::$paper_size['UK'] );
    
    } else {

      //get all paper_size
      $all_paper_size = self::$paper_size;
    
    }

    return $all_paper_size;

  }

  /**
   * info
   *
   * - get all info of one paper size 
   *
  */
  static function infos( $format = "A4", $unit = "mm", $borderless = 0, $dpi = 300, $orientation = "portrait" ){

    $paper_data = array(
      "size" => array(
        "width" => array(
          "px" => null,
          "px_96" => null,
          "px_72" => null,
          "in" => null,
          "mm" => null,
        ),
        "height" => array(
          "px" => null,
          "px_96" => null,
          "px_72" => null,
          "in" => null,
          "mm" => null,
        ),
      ),
      "borderless" => array(
        "px" => null,
        "px_96" => null,
        "px_72" => null,
        "in" => null,
        "mm" => null,
      ),
      "size_with_borderless" => array(
        "width" => array(
          "px" => null,
          "px_96" => null,
          "px_72" => null,
          "in" => null,
          "mm" => null,
        ),
        "height" => array(
          "px" => null,
          "px_96" => null,
          "px_72" => null,
          "in" => null,
          "mm" => null,
        ),
      ),
      "dpi" => $dpi,
      "orientation" => $orientation,
      "unit" => $unit,
    );

    //get all papersize merged
    $all_paper_size = self::get(true);

    //if format exist
    if ( isset( $all_paper_size[ $format ] ) ) {

      //convert mm format in inch        
      $format_in_mm = array(
        'A0','A1','A2','A3','A4','A5','A6','A7','A8','A9','A10',
        'B0','B1','B2','B3','B4','B5','B6','B7','B8','B9','B10',
        'C0','C1','C2','C3','C4','C5','C6','C7','C8','C9','C10',
        'DL','DLE','F4',
      );

      //convert to inch
      if ( in_array( $format, $format_in_mm ) ) {

        //mm
        $paper_data['size']['width']['mm']  = $all_paper_size[ $format ][0]; 
        $paper_data['size']['height']['mm'] = $all_paper_size[ $format ][1]; 

        //in
        $paper_data['size']['width']['in']  = self::mm_to_inch( $all_paper_size[ $format ][0] );
        $paper_data['size']['height']['in'] = self::mm_to_inch( $all_paper_size[ $format ][1] );

        //get px
        $px_width_96dpi  = self::mm_to_px( $paper_data['size']['width']['mm'] );
        $px_height_96dpi = self::mm_to_px( $paper_data['size']['height']['mm'] );
        
        //calculate px with 96 dpi
        $paper_data['size']['width']['px_96']  = $px_width_96dpi;
        $paper_data['size']['height']['px_96'] = $px_height_96dpi;

        //calculate px with 72 dpi
        $paper_data['size']['width']['px_72']  = 72 * ( $px_width_96dpi / 96 );
        $paper_data['size']['height']['px_72'] = 72 * ( $px_height_96dpi / 96 );

        //calculate px with dpi
        $paper_data['size']['width']['px']  = $dpi * ( $px_width_96dpi / 96 );
        $paper_data['size']['height']['px'] = $dpi * ( $px_height_96dpi / 96 );

        //calculate borderless
        $paper_data['borderless']['mm'] = intval( $borderless, 0 );
        $paper_data['borderless']['in'] = self::mm_to_inch( intval( $borderless, 0 ) );
        $borderless_96dpi = self::mm_to_px( intval( $borderless, 0 ) );
        $paper_data['borderless']['px_96'] = $borderless_96dpi;
        $paper_data['borderless']['px_72'] = 72 * ( $borderless_96dpi / 96 );
        $paper_data['borderless']['px'] = $dpi * ( $borderless_96dpi / 96 );

      } else {

        //mm
        $paper_data['size']['width']['in']  = $all_paper_size[ $format ][0]; 
        $paper_data['size']['height']['in'] = $all_paper_size[ $format ][1]; 

        //in
        $paper_data['size']['width']['mm']  = self::inch_to_mm( $all_paper_size[ $format ][0] );
        $paper_data['size']['height']['mm'] = self::inch_to_mm( $all_paper_size[ $format ][1] );

        //get px
        $px_width_96dpi  = self::mm_to_px( $paper_data['size']['width']['mm'] );
        $px_height_96dpi = self::mm_to_px( $paper_data['size']['height']['mm'] );
        
        //calculate px with 96 dpi
        $paper_data['size']['width']['px_96']  = $px_width_96dpi;
        $paper_data['size']['height']['px_96'] = $px_height_96dpi;

        //calculate px with 72 dpi
        $paper_data['size']['width']['px_72']  = 72 * ( $px_width_96dpi / 96 );
        $paper_data['size']['height']['px_72'] = 72 * ( $px_height_96dpi / 96 );

        //calculate px with dpi
        $paper_data['size']['width']['px']  = $dpi * ( $px_width_96dpi / 96 );
        $paper_data['size']['height']['px'] = $dpi * ( $px_height_96dpi / 96 );

        //calculate borderless
        $paper_data['borderless']['in'] = intval( $borderless, 0 );
        $paper_data['borderless']['mm'] = self::inch_to_mm( intval( $borderless, 0 ) );
        $borderless_96dpi = self::mm_to_px( intval( $paper_data['borderless']['mm'], 0 ) );
        $paper_data['borderless']['px_96'] = $borderless_96dpi;
        $paper_data['borderless']['px_72'] = 72 * ( $borderless_96dpi / 96 );
        $paper_data['borderless']['px'] = $dpi * ( $borderless_96dpi / 96 );

      }

    } else {

      
      //get from custom format
      $custom_geometry = explode( 'x', $format );

      //check if custom exist
      if ( ! empty( $custom_geometry ) ) {

        switch ( $unit ) {

          case 'mm':

            //get mm
            $paper_data['size']['width']['mm']  = intval( $custom_geometry[0], 0 );
            $paper_data['size']['height']['mm'] = intval( $custom_geometry[1], 0 );

            //get in from mm
            $paper_data['size']['width']['in']  = self::mm_to_inch( intval( $custom_geometry[0], 0 ) );
            $paper_data['size']['height']['in'] = self::mm_to_inch( intval( $custom_geometry[1], 0 ) );

            //get px
            $px_width_96dpi  = self::mm_to_px( $paper_data['size']['width']['mm'] );
            $px_height_96dpi = self::mm_to_px( $paper_data['size']['height']['mm'] );
            
            //calculate px with 96 dpi
            $paper_data['size']['width']['px_96']  = $px_width_96dpi;
            $paper_data['size']['height']['px_96'] = $px_height_96dpi;

            //calculate px with 72 dpi
            $paper_data['size']['width']['px_72']  = 72 * ( $px_width_96dpi / 96 );
            $paper_data['size']['height']['px_72'] = 72 * ( $px_height_96dpi / 96 );

            //calculate px with dpi
            $paper_data['size']['width']['px']  = $dpi * ( $px_width_96dpi / 96 );
            $paper_data['size']['height']['px'] = $dpi * ( $px_height_96dpi / 96 );

            //calculate borderless
            $paper_data['borderless']['mm'] = intval( $borderless, 0 );
            $paper_data['borderless']['in'] = self::mm_to_inch( intval( $borderless, 0 ) );
            $borderless_96dpi = self::mm_to_px( intval( $borderless, 0 ) );
            $paper_data['borderless']['px_96'] = $borderless_96dpi;
            $paper_data['borderless']['px_72'] = 72 * ( $borderless_96dpi / 96 );
            $paper_data['borderless']['px'] = $dpi * ( $borderless_96dpi / 96 );

          break;

          case 'in':

            //get in
            $paper_data['size']['width']['in']  = intval( $custom_geometry[0], 0 );
            $paper_data['size']['height']['in'] = intval( $custom_geometry[1], 0 );

            //get in from mm
            $paper_data['size']['width']['mm']  = self::inch_to_mm( intval( $custom_geometry[0], 0 ) );
            $paper_data['size']['height']['mm'] = self::inch_to_mm( intval( $custom_geometry[1], 0 ) );

            //get px
            $px_width_96dpi  = self::mm_to_px( $paper_data['size']['width']['mm'] );
            $px_height_96dpi = self::mm_to_px( $paper_data['size']['height']['mm'] );
            
            //calculate px with 96 dpi
            $paper_data['size']['width']['px_96']  = $px_width_96dpi;
            $paper_data['size']['height']['px_96'] = $px_height_96dpi;

            //calculate px with 72 dpi
            $paper_data['size']['width']['px_72']  = 72 * ( $px_width_96dpi / 96 );
            $paper_data['size']['height']['px_72'] = 72 * ( $px_height_96dpi / 96 );

            //calculate px with dpi
            $paper_data['size']['width']['px']  = $dpi * ( $px_width_96dpi / 96 );
            $paper_data['size']['height']['px'] = $dpi * ( $px_height_96dpi / 96 );

            //calculate borderless
            $paper_data['borderless']['in'] = intval( $borderless, 0 );
            $paper_data['borderless']['mm'] = self::inch_to_mm( intval( $borderless, 0 ) );
            $borderless_96dpi = self::mm_to_px( intval( $paper_data['borderless']['mm'], 0 ) );
            $paper_data['borderless']['px_96'] = $borderless_96dpi;
            $paper_data['borderless']['px_72'] = 72 * ( $borderless_96dpi / 96 );
            $paper_data['borderless']['px'] = $dpi * ( $borderless_96dpi / 96 );

          break;
          
          case 'px':
          default:
            
            $paper_data['size']['width']['px']  = intval( $custom_geometry[0], 0 );
            $paper_data['size']['height']['px'] = intval( $custom_geometry[1], 0 );

            //calculate px with 72 dpi
            $paper_data['size']['width']['px_96']  = 96 * ( $paper_data['size']['width']['px'] / 300 );
            $paper_data['size']['height']['px_96'] = 96 * ( $paper_data['size']['height']['px'] / 300 );

            //calculate px with 72 dpi
            $paper_data['size']['width']['px_72']  = 72 * ( $paper_data['size']['width']['px'] / 300 );
            $paper_data['size']['height']['px_72'] = 72 * ( $paper_data['size']['height']['px'] / 300 );

            //get mm
            $paper_data['size']['width']['mm']  = self::px_to_mm( $paper_data['size']['width']['px_96'] );
            $paper_data['size']['height']['mm'] = self::px_to_mm( $paper_data['size']['height']['px_96'] );

            //get in from mm
            $paper_data['size']['width']['in']  = self::mm_to_inch( $paper_data['size']['width']['mm'] );
            $paper_data['size']['height']['in'] = self::mm_to_inch( $paper_data['size']['height']['mm'] );

            //calculate borderless
            $paper_data['borderless']['px'] = intval( $borderless, 0 );
            $paper_data['borderless']['px_96'] = 96 * ( $paper_data['borderless']['px'] / 300 );
            $paper_data['borderless']['px_72'] = 72 * ( $paper_data['borderless']['px'] / 300 );
            $paper_data['borderless']['mm'] = self::px_to_mm( $paper_data['borderless']['px_96'] );
            $paper_data['borderless']['in'] = self::mm_to_inch( $paper_data['borderless']['mm'] );

          break;

        }

      } else {

        //no size info found
        $paper_data = false;

      }
      
          
    }

    
    //calculate size with borderless
    $paper_data['size_with_borderless']['width']['in'] = round( $paper_data['size']['width']['in'] + ( $paper_data['borderless']['in'] * 2 ), 2 );
    $paper_data['size_with_borderless']['height']['in'] = round( $paper_data['size']['height']['in'] + ( $paper_data['borderless']['in'] * 2 ), 2 );
    $paper_data['size_with_borderless']['width']['mm'] = round( $paper_data['size']['width']['mm'] + ( $paper_data['borderless']['mm'] * 2 ), 0, PHP_ROUND_HALF_UP );
    $paper_data['size_with_borderless']['height']['mm'] = round( $paper_data['size']['height']['mm'] + ( $paper_data['borderless']['mm'] * 2 ), 0, PHP_ROUND_HALF_UP );
    $paper_data['size_with_borderless']['width']['px_96'] = round( $paper_data['size']['width']['px_96'] + ( $paper_data['borderless']['px_96'] * 2 ) );
    $paper_data['size_with_borderless']['height']['px_96'] = round( $paper_data['size']['height']['px_96'] + ( $paper_data['borderless']['px_96'] * 2 ) );
    $paper_data['size_with_borderless']['width']['px_72'] = round( $paper_data['size']['width']['px_72'] + ( $paper_data['borderless']['px_72'] * 2 ) );
    $paper_data['size_with_borderless']['height']['px_72'] = round( $paper_data['size']['height']['px_72'] + ( $paper_data['borderless']['px_72'] * 2 ) );
    $paper_data['size_with_borderless']['width']['px'] = round( $paper_data['size']['width']['px'] + ( $paper_data['borderless']['px'] * 2 ) );
    $paper_data['size_with_borderless']['height']['px'] = round( $paper_data['size']['height']['px'] + ( $paper_data['borderless']['px'] * 2 ) );

    //up values if px to unit
    $paper_data['size']['width']['in'] = round( $paper_data['size']['width']['in'], 2 );
    $paper_data['size']['height']['in'] = round( $paper_data['size']['height']['in'], 2 );
    $paper_data['size']['width']['mm'] = round( $paper_data['size']['width']['mm'], 0, PHP_ROUND_HALF_UP );
    $paper_data['size']['height']['mm'] = round( $paper_data['size']['height']['mm'], 0, PHP_ROUND_HALF_UP );
    $paper_data['size']['width']['px_96'] = floor( $paper_data['size']['width']['px_96'] );
    $paper_data['size']['height']['px_96'] = floor( $paper_data['size']['height']['px_96'] );
    $paper_data['size']['width']['px_72'] = floor( $paper_data['size']['width']['px_72'] );
    $paper_data['size']['height']['px_72'] = floor( $paper_data['size']['height']['px_72'] );
    $paper_data['size']['width']['px'] = floor( $paper_data['size']['width']['px'] );
    $paper_data['size']['height']['px'] = floor( $paper_data['size']['height']['px'] );

    //reverce if landscape
    if ( $orientation == "landscape" ) {
      
      $paper_data_width  = $paper_data['size']['width'];
      $paper_data_height = $paper_data['size']['height'];

      $paper_data['size']['height'] = $paper_data_width;
      $paper_data['size']['width']  = $paper_data_height;

    }

    //return paper data
    return $paper_data;

  }
  
  static function mm_to_inch( $value ){

    return $value * 0.03937007874016;

  }

  static function inch_to_mm( $value ){

    return $value / 0.03937007874016;

  }

  static function mm_to_px( $value ){

    return $value * 3.7795275593333;

  }

  static function px_to_mm( $value ){

    return $value / 3.7795275593333;

  }


}
}
