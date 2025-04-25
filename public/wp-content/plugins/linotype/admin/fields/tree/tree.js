
(function($) {

$.fn.wp_field_tree = function(){

	$(this).each(function(){

    var $tree = $(this);

    var $value = $tree.find('.wp-field-value');

    function update(){

      var data = {};

      $tree.find('.tree-file').each(function(index_value, item_value ) {

        if ( $( item_value ).find('input').is(':checked')) {

          var item = {};
          item[ 'file' ] = $( item_value ).attr('data-file');
          item[ 'path' ] = $( item_value ).attr('data-path');

          data[ $( item_value ).attr('data-path') ] = item;

        }


      });

      $value.val( JSON.stringify( data ) );

    }

    $tree.find('input').on('change', update );

    //init

    var $current_value = JSON.parse( '[' + $value.val() + ']' );

    $.each( $current_value[0], function(index, value ) {

      $tree.find('.tree-file[data-path="'+value.path+'"] input').prop('checked', true);

      $tree.find('.tree-file[data-path="'+value.path+'"] input').parents(".tree-folder").removeClass("close");

    });

    $tree.find('.tree-folder-title').on('click', function(){

      $(this).parent().toggleClass('close');

      return false;

    } );

    // $tree.find('.tree-file').on('click', function(e){
    //
    //   // if ( $(this).find('input:checked').length ){
    //   //   console.log('check');
    //   //   $(this).find('input').prop('checked', false);
    //   // } else {
    //   //   console.log('notcheck');
    //   //   $(this).find('input').prop('checked', true);
    //   // }
    //
    //
    //   e.preventDefault;
    //
    // } );


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-tree').wp_field_tree();

});

}(jQuery));
