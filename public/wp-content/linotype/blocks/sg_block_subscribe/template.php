<?php block('header', $settings ); ?>

	<p class="title">Newsletter</p>

	<form action="<?php echo $options['action']; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		
		<div class="mc-field-group">
			<div class="newsletter-field">
				<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Adresse email" aria-label="Adresse email">
				<button type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn-unstyled button" aria-label="S’inscrire"></button>
			</div>
		</div>

		<div id="mce-responses">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>
		
	</form>

<?php block('footer', $settings ); ?>
