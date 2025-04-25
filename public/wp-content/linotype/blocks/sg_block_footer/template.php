<?php block('header', $settings ); ?>

<div class="main-footer">
  <div class="container">
    <div class="grid">
      <div class="col-subscribe">
        <?php echo LINOTYPE_composer::render( $options['content_1'], $elements, false, false ); ?>
      </div>
      <div class="col-nav">
        <?php echo LINOTYPE_composer::render( $options['content_2'], $elements, false, false ); ?>
      </div>
    </div>
  </div>
</div>
<div class="bottom-footer">
  <div class="grid">
    <div class="payment">
      <div class="container">
        <img class="flex-img" data-src="<?php echo block('get_url', $settings); ?>/assets/img/payment-methods.png" data-srcset="<?php echo block('get_url', $settings); ?>/assets/img/payment-methods.png 1x, <?php echo block('get_url', $settings); ?>/assets/img/payment-methods@2x.png 2x" alt="<?php echo linotrad('The following means of payment are accepted: PayPal, Stripe, Apple Pay, Credit Card'); ?>">
      </div>
    </div>
    <div class="address">
      <div class="container">
        <p><?php echo $options['address']; ?></p>
      </div>
    </div>
  </div>
</div>

<?php

 block('footer', $settings );
