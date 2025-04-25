<?php

function sg_block_nav_lang(){

    $lang_selector = array(
        'current' => array(),
        'items' => array(),
    );

    if ( function_exists( 'pll_the_languages' ) ) {

        $translate_ready = true;

        $lang_selector_array = pll_the_languages( array( "raw" => 1 ) ); 

        if ( $lang_selector_array ) {
            
            foreach( $lang_selector_array as $lang ) {

                if ( $lang['no_translation'] == true ) $translate_ready = false;

                if ( $lang['slug'] == 'zh' ) $lang['slug'] = '中文';

                if ( $lang['current_lang'] ) {
                        
                    $lang_selector['current'] = array(
                        'name' => $lang['name'],
                        'flag' => $lang['flag'],
                        'code' => $lang['slug'],
                        'link' => $lang['url'],
                        'class' => 'current',
                        'translation' => $translate_ready
                    );

                } else {
                    
                    array_push( $lang_selector['items'],  array(
                        'name' => $lang['name'],
                        'flag' => $lang['flag'],
                        'code' => $lang['slug'],
                        'link' => $lang['url'],
                        'class' => '',
                        'translation' => $translate_ready
                    ) );

                }
            
            }

        }

    }

    return $lang_selector;

}
