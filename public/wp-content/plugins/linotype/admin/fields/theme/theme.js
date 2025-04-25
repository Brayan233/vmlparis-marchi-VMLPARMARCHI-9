(function($) {

$.fn.wp_field_theme = function(){

	$(this).each(function(){

		var $FIELD = $(this);

		var $OUTPUT = $FIELD.find('.wp-field-value');

		var $MAP = $.parseJSON( $FIELD.find('.wp-field-map').val() );

		var $SELECT_FIELD = $FIELD.find('.template-field');
		var $TEMPLATE_DEFAULT = $FIELD.find('.template-default');
		var $TEMPLATE_RULES = $FIELD.find('.template-rules');

		var $THEME_TABS = $FIELD.find('.theme-tabs li');
		var $THEME_CONTENTS = $FIELD.find('.theme-contents li');
		var $TYPE_TABS = $FIELD.find('.type-tabs li');
		var $TYPE_CONTENTS = $FIELD.find('.type-contents li');

		$THEME_TABS.on('click', function(){

			$THEME_TABS.removeClass('select');
			$(this).addClass('select');

			$THEME_CONTENTS.removeClass('show');
			$( $(this).attr('data-target') ).addClass('show');


			$TYPE_TABS.removeClass('select');
			$( $(this).attr('data-target') ).find('.type-tabs li:first-child').addClass('select');

			$TYPE_CONTENTS.removeClass('show');
			$( $(this).attr('data-target') ).find('.type-contents li:first-child').addClass('show');

		});

		$TYPE_TABS.on('click', function(){

			$TYPE_TABS.removeClass('select');
			$(this).addClass('select');

			$TYPE_CONTENTS.removeClass('show');
			$( $(this).attr('data-target') ).addClass('show');

		} );

		$TEMPLATE_RULES.find('ul').sortable({
			// appendTo: 'body',
			// containment: "window",
			// connectWith: '.composer-row-content',
			// items: "> .composer-column",
			opacity: 0.5,
			// scroll: true,
				//placeholder: 'composer-column-placeholder',
				axis: "y",
				distance: 5,
				revert: 150,
				// cursor: "move",
				handle: ".order-rule",
				forcePlaceholderSize:true,
				forceHelperSize: true,
				// tolerance: "pointer",

				stop: function(event, ui) {

						update();

				}
		});

		$TEMPLATE_RULES.on('click', '.add-rule', function() {

			$model = $( $(this).parent().parent().find('.template-rule-item')[0].innerText );

			$clone = $model.clone();

			$clone.find('select').val('');
			$clone.find('input').val('');
			$clone.find('.rule-is option').hide();

			$(this).parent().parent().find('.template-rules ul').append( $clone );

		});

		$TEMPLATE_RULES.on('click', '.delete-rule', function() {

			$(this).parent().remove();

			update();

		});


		$FIELD.on( 'change', '.rule-if', function(){

			switch ( $(this).val() ) {
				case 'post':
				case 'taxonomy':
				case 'archive':
					$(this).parent().find('.rule-is').hide();
					$(this).parent().find('.rule-is-select').show();
					$(this).parent().find('.rule-is').val( $(this).parent().find('.rule-is-select').val() );
				break;
				default:
					$(this).parent().find('.rule-is').show();
					$(this).parent().find('.rule-is-select').hide();
					$(this).parent().find('.rule-is').val( '' );
				break;
			}

		});

		$FIELD.on( 'change', '.rule-if', function(){

			$(this).parent().find('.rule-is-select option').hide();

			switch ( $(this).val() ) {
				case 'post':
					$(this).parent().find('.rule-is-select option.post').show();
					$(this).parent().find('.rule-is-select').val('');
				break;
				case 'taxonomy':
				case 'archive':
					$(this).parent().find('.rule-is-select option.taxonomy').show();
					$(this).parent().find('.rule-is-select').val('');
				break;
			}

		});

		$FIELD.on( 'change', '.rule-is-select', function(){

			$(this).parent().find('.rule-is').val( $(this).val() );

		});


		function update(){

			$TEMPLATE_DEFAULT.each( function(){

				$target_map = $(this).attr('data-map');
				$target_type = $(this).attr('data-type');
				$target_value = $(this).val();

				$MAP[ $target_map ]['types'][ $target_type ]['template'] = $target_value;

			});

			$TEMPLATE_RULES.each( function(){

				var rules = [];

				var $target_map = $(this).attr('data-map');
				var $target_type = $(this).attr('data-type');

				$(this).find('li').each( function(){

					var $target_value_if = $(this).find('.rule-if').val();
					var $target_value_is = $(this).find('.rule-is').val();
					var $target_value_template = $(this).find('.rule-template').val();

					if ( $target_value_if && $target_value_is && $target_value_template ) {

						rule = { if:$target_value_if, is:$target_value_is, template:$target_value_template };

						rules.push(rule);

					}

				});

				$MAP[ $target_map ]['types'][ $target_type ]['rules'] = rules;

			});

			//template-rule
			console.log( $MAP[ 'post' ]['types'][ 'single' ]['rules'] );

			$OUTPUT.val( JSON.stringify( $MAP ) );

		}


		$FIELD.on( 'change', '.template-field', update );


	});

}

$(document).ready(function(){

	$('body').find('.wp-field-theme').wp_field_theme();

});

}(jQuery));
