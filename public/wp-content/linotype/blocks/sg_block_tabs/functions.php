<?php 

class sg_block_tabs {

  public $options;

  public $contents;

  public $uniq;

  public $default_tab = 0;

  function __construct( $options, $contents ) {

    $this->uniq++;
    
    $this->options = $options;
    $this->contents = $contents;
    $this->init();

  }

  private function init() {

    if ( isset( $this->options['default_tab'] ) && $this->options['default_tab'] ) $this->default_tab = intval( $this->options['default_tab'] );

    $this->contents = array_values( $this->contents );

  }

  public function get_links() {

    $links = array();

    $count = 0;

    foreach ( $this->contents as $i => $content ) {
      
      $content['options'] = array_merge( array(
        'icon' => '',
        'title' => '',
        'desc' => '',
      ), isset( $content['options'] ) ? $content['options'] : [] );

      $id = sanitize_key( $content['options']['title'] );
      if ( ! $id ) $id = 'tab-' . $this->uniq . '-' . $count++;

      linolog($id);

      if ( $id ) {
          
        $active = '';
        if( $this->default_tab === $i +1 ) $active = ' default-active active';

        $links[ $id ] = array(
          'id' => $id,
          'class' => 'sg_block_tabs-link' . $active,
          'href' => '#' . $id,
          'icon' => $content['options']['icon'],
          'title' => $content['options']['title'],
          'desc' => $content['options']['desc'],
          'active' => $active,
        );
        
      }
      
    }

    return $links;

  }

  public function get_contents() {

    $contents = array();

    $count = 0;

    foreach ( $this->contents as $i => $content ) {
      
      $id = sanitize_key( isset( $content['options']['title'] ) ? $content['options']['title'] : '' );
      if ( ! $id ) $id = 'tab-' . $this->uniq . '-' . $count++;

      if ( $id ) {
          
        $active = '';
        if( $this->default_tab === $i +1 ) $active = ' default-active active';

        $contents[ $id ] = array(
          'id' => $id,
          'class' => 'sg_block_tabs-content' . $active,
          'active' => $active,
          'data' => array( $content )
        );
        
      }
      
    }

    return $contents;

  }

  public function get_icon( $media_id = null ) {
   
    if ( $media_id ) {

      $src = wp_get_attachment_image_src( $media_id, 'full', false );
      
      if ( $src ) { 

        return '<img src="' . $src[0] . '" height="24" alt="">';

      } else {

        return '';
      
      }

    }

  }

  public function get_icon_svg( $media_id = null ) {
   
    if ( $media_id ) {

      $svg = "";

      $src = wp_get_attachment_image_src( $media_id, 'full', false );

      if ( $src ) { 
        
        $svg_file = $src[0];

        $svg_data = linotype_file_get_contents( $svg_file );
        
        $svg_data = substr( $svg_data, strpos( $svg_data, '<svg' ) );

        $dom = new DomDocument();
        $dom->loadXML($svg_data);
        $res = $dom->getElementsByTagName('svg');

        $svg_width = $res->item(0)->getAttribute('width');
        $svg_height = $res->item(0)->getAttribute('height');

        if( $res->item(0)->hasAttribute('viewBox') == false ) $res->item(0)->setAttribute('viewBox', '0 0 ' . $svg_width . ' ' . $svg_height );

        $res->item(0)->removeAttribute('height');
        $res->item(0)->removeAttribute('width');
        $res->item(0)->removeAttribute('style');

        $svg_data = $dom->saveXML();

        if ( $svg_data ) $svg = $svg_data;
        
      }

      return $svg;

    } else {

      return '';

    }

  }

}
