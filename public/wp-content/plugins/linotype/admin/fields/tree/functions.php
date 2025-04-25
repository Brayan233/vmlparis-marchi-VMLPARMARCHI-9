<?php

class linoadmin_tree {

  static function get( $dir, $type = '', $list = '' ){

    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    $list .= '<ul class="tree-list">';

    $files = '';

    foreach($ffs as $ff){

      if( ! is_dir($dir.'/'.$ff) ) {

        $file_parts = pathinfo($dir.'/'.$ff);

        if( isset( $file_parts['extension'] ) && $file_parts['extension'] == $type ) {

          $files .= '<li class="tree-file" data-file="' . $ff . '" data-path="' . $dir.'/'.$ff . '" ><label><input type="checkbox"/> <i class="fa fa-file-code-o"></i> ' . $ff . '</label>';

            //$files .= '<input type="text" style="" class="tree-file-id" value="' . $ff . '">';

          $files .= '</li>';

        }

      } else {

        $list_next = self::get($dir.'/'.$ff, $type );

        if( $list_next != '<ul class="tree-list"></ul>' ) {

          $list .= '<li class="tree-folder close">';

            $list .= '<div class="tree-folder-title"><i class="fa fa-plus"></i> <i class="fa fa-folder icon-folder icon-folder"></i> '.$ff . '</div>';

            $list .= $list_next;

          $list .= '</li>';

        }

      }

    }

    $list .= $files;

    $list .= '</ul>';

    return $list;

  }

}

?>
