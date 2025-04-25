<?php

wp_enqueue_script('html2canvas', LINOTYPE_plugin::$plugin['url'] . 'lib/html2canvas/html2canvas.js', array('jquery'), '1.0', true );

?>


<div class="linotype-tab-toolbar">
    <div class="button" id="linotype-frontend">Refresh</div>
    
</div>

<div class="linotype-tab-fullscreen">
 
    <iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="preview-iframe" class="linotype-fullscreen bg-psd" src="<?php echo get_bloginfo('url') . '?linotype-preview=block&type='.$_GET['type'].'&id=' . $_GET['id'] . '&cache=' . time(); ?>" ></iframe>

 </div>
