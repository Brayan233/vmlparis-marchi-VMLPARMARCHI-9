<?php

global $LINOTYPE_EDITOR;

wp_enqueue_script('html2canvas', LINOTYPE_plugin::$plugin['url'] . 'lib/html2canvas/html2canvas.js', array('jquery'), '1.0', true );

?>

<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="preview-iframe" class="linotype-fullscreen bg-psd" src="<?php echo LINOTYPE_plugin::$plugin['url'] . 'editor/template/preview-iframe.php?type='.$_GET['type'].'&id=' . $_GET['id'] . '&cache=' . time(); ?>" ></iframe>

