(function($) {

    $.fn.wp_field_code = function(){
      
      $(this).each(function(){
      
        var $this = $(this);
        
        var $EDITOR = $this.find('.linotype-editor-code');
        var $CODE = $this.find('.wp-field-code_content');
        var $LANG = $CODE.attr('data-language');
   
        var $OPTIONS = {};
        $OPTIONS['lineNumbers'] = true;
        $OPTIONS['tabSize'] = 2;
        $OPTIONS['scrollbarStyle'] = "null";
        $OPTIONS['theme'] = 'material';
  
        $OPTIONS['mode'] = "html";
        if ( $LANG == 'php' ) $OPTIONS['mode'] = "application/x-httpd-php";
        if ( $LANG == 'scss' ) $OPTIONS['mode'] = "text/x-scss";
        if ( $LANG == 'less' ) $OPTIONS['mode'] = "text/x-less";
        if ( $LANG == 'css' ) $OPTIONS['mode'] = "css";
        if ( $LANG == 'js' ) $OPTIONS['mode'] = "javascript";
        
        var editor = CodeMirror.fromTextArea( $CODE.get(0), $OPTIONS );
        editor.on('change',function(cm){ $CODE.val( cm.getValue() ); });
        
        $EDITOR.css('visibility', 'visible');

      });
    
    }
    
    $(document).ready(function(){
    
      $('body').find('.wp-field-code').wp_field_code();
    
    });
    
  }(jQuery));