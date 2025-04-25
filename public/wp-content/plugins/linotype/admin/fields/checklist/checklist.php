<?php

$default_options = array(
	"data" => array(),
    "title" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-html <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if ( $field['title'] ) { ?>
	<div class="field-title"><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

			<?php

			$html = '';
			
			$html .= '<table class="widefat" cellspacing="0">';

				if ( $field['options']['title'] ) {

					$html .= '<thead>';
						
						$html .= '<tr>';
						
							$html .= '<th colspan="3">' . $field['options']['title'] . '</th>';
						
						$html .= '</tr>';

					$html .= '</thead>';

				}

				$html .= '<tbody>';

					if ( $field['options']['data'] ) {
						foreach ( $field['options']['data'] as $key => $item ) {
							
							$html .= '<tr>';

								
								
									if ( isset( $item['check'] ) && $item['check'] ) {
										$html .= '<td style="background-color:#FFF">' . $item['title'] . '</td>';
										$html .= '<td style="background-color:#FFF">✔ ';
									} else {
										$html .= '<td style="background-color:#C66C75;color:#FFF;">' . $item['title'] . '</td>';
										$html .= '<td style="background-color:#C66C75;color:#FFF;"> ';
									}

									$html .= $item['message'];
								
								$html .= '</td>';
							$html .= '</tr>';

						}
					}

				$html .= '</tbody>';

			$html .= '</table>';

			echo $html;

			?>

		<?php if ( $field['desc'] ) { ?>
		<div class="field-desc"><?php echo $field['desc']; ?></div>
		<?php } ?>

	<div>

</li>
