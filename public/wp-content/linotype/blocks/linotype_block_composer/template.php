<?php

block( 'header', $settings ); 
		
	//if ( current_user_can( 'linotype_admin' ) ) echo '<h1 style="display:block;text-align:center;padding:5%;margin:5%;border: solid 1px #ddd;">Composer</h1>';

	block( 'render', $options['content'], $settings ); 

block( 'footer', $settings ); 

?>