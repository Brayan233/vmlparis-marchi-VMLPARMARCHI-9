<?php 

block( 'header', $settings ); ?>

    <div class="container">

        <?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

    </div>

<?php block( 'footer', $settings );
