(function($) {

  $.fn.linotype_editor_index = function(){
    
    $(this).each(function(){
    
      var $this = $(this);
      var $theme_current_title = $this.find('.theme-current-title');
      var $theme_change_bt = $this.find('.theme-change-button');
      var $install_bt = $this.find('.theme-install-button');
      var $install_select = $this.find('.theme-select');
      var $install = $this.find('.theme-install');

      function open_install() {
        $install.show();
      }

      function close_install() {
        $install.hide();
      }

      function install() {

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { 
                'action' : 'linotype_editor_theme_install',
                'theme_id' : $install_select.val()
            },
            success: function( data ){
                
                console.log( data );

                $theme_current_title.text( $install_select.find('option:selected').text() );

                close_install();

            }
        });

        
      }

      $theme_change_bt.on('click', open_install );

      $install_bt.on('click', install );

      //theme_import
      function theme_import() {

        var $item = $(this);
        var $item_type = $item.attr('data-type');
        var $item_id = $item.attr('data-id');

        $item.text('IMPORTING...');
        console.log( 'IMPORTING : ' + $item_type + ' id: ' + $item_id );

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { 
                'action' : 'linotype_editor_theme_import',
                'type' : $item_type,
                'id' : $item_id
            },
            success: function( output ){
              
              var output = JSON.parse( output );

              console.log( output );

              if ( output.success ) {

                $item.text('SYNC').attr('class', 'sync-button' );

              } else {

                $item.text('ERROR');
                console.log( 'error' );

              }
                
            }
        });
        
      }

      $this.on( 'click', '.import-button', theme_import );

    });
  
  }
  
  $(document).ready(function(){
  
    $('body').find('.linotype_editor_index').linotype_editor_index();
  
  });
  
}(jQuery));
  