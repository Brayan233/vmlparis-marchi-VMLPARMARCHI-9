(function($) {

$.fn.wp_field_gallery = function(){

	jQuery(this).each(function(){

		var $FIELD = jQuery(this);
		var $VALUE = jQuery(this).find( '#' + jQuery(this).attr('wp-field-id') );
		var $OUTPUT = jQuery(this).attr('wp-field-output');

		//Add media
		$FIELD.on( 'click', '#file_button_select', wpmedia );

		//remove media
		$FIELD.on( 'click', '#file_button_remove', remove );


		function wpmedia(event){

			$this = this;

			event.preventDefault();

			var wp_field_gallery_frame = wp.media.frames.customHeader = wp.media({

				title: jQuery($this).data("choose"),
				library: {
					type: 'image'
				},
				button: {
					text: jQuery($this).data("update")
				},
        multiple: true

			});
		
      wp_field_gallery_frame.on('open',function() {
        var selection = wp_field_gallery_frame.state().get('selection');
        ids = $VALUE.val().split(',');
          ids.forEach(function(id) {
          attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add( attachment ? [ attachment ] : [] );
        });
      });

			wp_field_gallery_frame.on( "select", function() {
				
        var attachments = wp_field_gallery_frame.state().get('selection').map( function( attachment ) {

            attachment.toJSON();
            return attachment;

        });
				
        $FIELD.find("#preview ul" ).html('');
        
        var i;
        var ids = [];

        for (i = 0; i < attachments.length; ++i) {
					
          $FIELD.find("#preview ul" ).append('<li data-id="' + attachments[i].attributes.id + '"><img style="width:150px;" src="' + attachments[i].attributes.url + '"/></li>');
          
          ids.push( attachments[i].attributes.id );
				
        }
				
        var str_ids = ids.join(',');
        
				if ( str_ids ) {

					$VALUE.val( str_ids ).change();

				} else {

					$VALUE.val('').change();

				}
				
        sort();
        
				button_state();

			});

			wp_field_gallery_frame.open();

		}

		function remove() {

			$FIELD.find("#preview ul li" ).remove();
			$VALUE.val("").change();

			button_state();

			event.preventDefault();

		};
		
    function sort(){
      
      $(".field-gallery-preview > ul").sortable({

          //connectWith: '.composer-items.composer-items-sortable',
          items: "li",
          //opacity: 0.5,
            //placeholder: 'composer-item-placeholder',
          //helper: "clone",
            //revert: false,
            //revertDuration: 0,
            //handle: ".composer-item-move, .composer-item-title",
          //forcePlaceholderSize:true,
            //forceHelperSize: true,
          //dropOnEmpty: true,
            //tolerance: "pointer",

          //appendTo: 'body',
          // containment: "window",
          // cursorAt: {top: 0, left: 0},
          //axis: "x",

          start: function(e, ui){

            
          },
          sort: function (e, ui) {

          

          },
          change: function(event, ui) {

          

          },
          stop: function(e, ui) {
						
            var ids = [];
            
            $FIELD.find("#preview ul li" ).each(function(){
            
            	ids.push( $(this).attr('data-id') );
              
            });
            
            var str_ids = ids.join(',');
        
            if ( str_ids ) {

              $VALUE.val( str_ids ).change();

            } else {

              $VALUE.val('').change();

            }

          }
        });

      }
    

     sort();
  
		function button_state(){

			if( $FIELD.find("#preview ul li").length == 0 ){

				//$FIELD.find("#file_button_remove" ).css('display','none');
				$FIELD.find(".field-gallery-action" ).addClass('show');
				$FIELD.find("#file_button_select" ).val('select');

			} else {

				//$FIELD.find("#file_button_remove" ).css('display','');
				$FIELD.find(".field-gallery-action" ).removeClass('show');
				$FIELD.find("#file_button_select" ).val('change');

			}

		}

		button_state();


		$FIELD.find('.field-content').css('visibility','visible');
		$FIELD.find('.spinner').css('display','none');

	});
}

jQuery(document).ready(function(){

	$('body').find('.wp-field-gallery').wp_field_gallery();

});

}(jQuery));


