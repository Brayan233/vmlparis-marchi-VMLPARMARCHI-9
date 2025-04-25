<?php

$MENUS = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

?>

<li class="wp-field wp-field-select <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input" >

			<select style="width:100%;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field">
				
      		<option autocorrect="off" autocomplete="off" spellcheck="false" value="">- select menu -</option>

			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[general]" <?php if ( $field['value'] && $field['value'] == '[general]' ) echo 'selected="selected"'; ?> >LOCATION: General</option>

			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[header]" <?php if ( $field['value'] && $field['value'] == '[header]' ) echo 'selected="selected"'; ?> >LOCATION: Header</option>
			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[footer]" <?php if ( $field['value'] && $field['value'] == '[footer]' ) echo 'selected="selected"'; ?> >LOCATION: Footer</option>

			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[primary]" <?php if ( $field['value'] && $field['value'] == '[primary]' ) echo 'selected="selected"'; ?> >LOCATION: Primary</option>
			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[secondary]" <?php if ( $field['value'] && $field['value'] == '[secondary]' ) echo 'selected="selected"'; ?> >LOCATION: Secondary</option>

			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[extra_01]" <?php if ( $field['value'] && $field['value'] == '[extra_01]' ) echo 'selected="selected"'; ?> >LOCATION: Extra 1</option>
			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[extra_03]" <?php if ( $field['value'] && $field['value'] == '[extra_03]' ) echo 'selected="selected"'; ?> >LOCATION: Extra 2</option>
			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[extra_04]" <?php if ( $field['value'] && $field['value'] == '[extra_04]' ) echo 'selected="selected"'; ?> >LOCATION: Extra 3</option>
			<option autocorrect="off" autocomplete="off" spellcheck="false" value="[extra_05]" <?php if ( $field['value'] && $field['value'] == '[extra_05]' ) echo 'selected="selected"'; ?> >LOCATION: Extra 4</option>

			<?php if ( isset( $MENUS ) && $MENUS ) { ?>
				<?php foreach ( $MENUS as $MENU_key => $MENU ) { ?>

					<?php
					$selected = '';
					if ( $field['value'] && $field['value'] == $MENU->term_id ) $selected = 'selected="selected"';
					?>
	
					<option autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $MENU->term_id; ?>" <?php echo $selected; ?> >ID: <?php echo $MENU->name; ?></option>

				<?php } ?>
			<?php } ?>

			</select>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>
