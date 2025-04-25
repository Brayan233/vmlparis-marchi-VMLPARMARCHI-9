<?php


function find_overwrite( $datas = array(), $ids = array() ) {
  
    foreach ( $datas as $data_key => $data ) {

        if ( isset( $data['overwrite'] ) && $data['overwrite'] == true ) {
        
            $ids[ $data['id'] ] = array( 'id' => $data['id'], 'desc' => $data['desc'], 'type' => $data['type'], 'options' => LINOTYPE::$BLOCKS->get( $data['type'] )['options'] );
           
        }

        if ( isset( $data['contents'] ) ) {
        
        $ids = find_overwrite( $data['contents'], $ids );
        
        }

    }

    return $ids;

}

$overwrites = find_overwrite( $LINOTYPE_EDITOR['data']['template'] );

$options_select = array();

 if ( isset( $overwrites ) && $overwrites ) {
    foreach ( $overwrites as $overwrite_id => $overwrite ) {
       
        
        if ( isset( $overwrite['options'] ) && $overwrite['options'] ) {
            foreach ( $overwrite['options'] as $option_id => $option ) {
                if ( $option['title'] == '' ) $option['title'] = $option_id;
                $options_select[] =  array( 'value' => $overwrite_id . '_' . $option_id, 'title' =>  $overwrite['desc'] . ' > ' . $option['title'] );
            }
        } 

    }
}

$field = array(
    'id' => 'overwrite',
    "title"=> '',
    "info"=>'',
    "type"=> 'linotype_field_overwrite',
    "options" => array(
    'data'=> array(
        

        array(
            'id' => 'id',
            'title' => 'Overide option: ',
            'desc' => '',
            'type' => 'select',
            'options' => $options_select,
            'width' => '24%',
        ),
        array(
            'id' => 'overwrite_target',
            'title' => 'Overide by: ',
            'desc' => '',
            'type' => 'select',
            'options' => array(
                    array('value' => 'meta', 'title' => 'Meta' ),
                    array('value' => 'option', 'title' => 'Option' ),
                    array('value' => 'both', 'title' => 'Both' ),
            ),
            'width' => '24%',
        ),
        array(
            'id' => 'meta_id_strict',
            'title' => 'Strict id',
            'options' => array(
                array('value' => '', 'title' => '_overide_' ),
                array('value' => 'yes', 'title' => 'yes' ),
            ),
            'desc' => '',
            'type' => 'select',
            'width' => '24%',
        ),
        array(
        'id' => 'meta_id',
        'title' => 'Meta ID: ',
        'default' => '',
        'desc' => '',
        'type' => 'text',
        'width' => '24%',
        ),
        array(
        'id' => 'type',
        'title' => 'Meta Type: ',
        'desc' => '',
        'type' => 'select',
        'options' => array(
            array('value' => 'text', 'title' => 'Text' ),
            array('value' => 'textarea', 'title' => 'Textarea' ),
            array('value' => 'editor', 'title' => 'Editor' ),
            array('value' => 'color', 'title' => 'Color' ),
            array('value' => 'image', 'title' => 'Image' ),
            array('value' => 'checkbox', 'title' => 'checkbox' ),
            array('value' => 'radio', 'title' => 'radio' ),
        )
        ),
        array(
        'id' => 'title',
        'title' => 'Meta Title: ',
        'desc' => '',
        'type' => 'text',
        ),
        array(
        'id' => 'desc',
        'title' => 'Meta Desc',
        'desc' => '',
        'type' => 'text',
        ),
        array(
        'id' => 'col',
        'title' => 'Column',
        'desc' => '',
        'type' => 'select',
        'options' => array(
            array('value' => 'col-12', 'title' => 'Col 12' ),
            array('value' => 'col-11', 'title' => 'Col 11' ),
            array('value' => 'col-10', 'title' => 'Col 10' ),
            array('value' => 'col-9', 'title' => 'Col 9' ),
            array('value' => 'col-8', 'title' => 'Col 8' ),
            array('value' => 'col-7', 'title' => 'Col 7' ),
            array('value' => 'col-6', 'title' => 'Col 6' ),
            array('value' => 'col-5', 'title' => 'Col 5' ),
            array('value' => 'col-4', 'title' => 'Col 4' ),
            array('value' => 'col-3', 'title' => 'Col 3' ),
            array('value' => 'col-2', 'title' => 'Col 2' ),
            array('value' => 'col-1', 'title' => 'Col 1' ),
        )
        ),
        array(
        'id' => 'tab',
        'title' => 'Tab',
        'desc' => '',
        'type' => 'text',
        ),
        // array(
        //   'id' => 'pos',
        //   'title' => 'Position',
        //   'desc' => '',
        //   'type' => 'text',
        // )
    ),
    'height'=>'500px'
    ),
    "default"=>"",
    "col" => "",
    "disabled" => "",
    "help" => false,
    'value' => $LINOTYPE_EDITOR['data']['overwrite'],
    'padding' => '0px;margin-top:-1px',
    'fullwidth' => true,
);

include LINOTYPE::$SETTINGS['dir'] . '/fields/linotype_field_overwrite/template.php';

?>