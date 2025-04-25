(function($) {

$.fn.wp_field_list = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		
		var $FIELD_ID = $FIELD.attr('id');

		var $OUTPUT = $FIELD.find('.wp-field-value');
	
		var $DATA = window[ $OUTPUT.attr('id') + '_data' ];

		$FIELD.find( '#category_criteria .checkbox_input').prop('checked', false);
		$FIELD.find('#category_criteria .checkbox_input').on('click', function(){
		  if( $(this).hasClass('checkbox_all') ) {
				$FIELD.find('#category_criteria .checkbox_input').prop('checked', false );
				$FIELD.find('#category_criteria .checkbox_input').removeClass('all');
				$(this).addClass('all');
		  } else {
				$FIELD.find('#category_criteria .checkbox_input').removeClass('all');
				$FIELD.find('#category_criteria .checkbox_input').prop('checked', false );
				$(this).prop('checked', true );
		  }
		});

		$FIELD.find('#tags_criteria .checkbox_input').prop('checked', false);
		$FIELD.find('#tags_criteria .checkbox_input').on('click', function(){
		  if( $(this).hasClass('checkbox_all') ) {
			$FIELD.find('#tags_criteria .checkbox_input').prop('checked', false );
			$FIELD.find('#tags_criteria .checkbox_input').removeClass('all');
			$(this).addClass('all');
		  } else {
			$FIELD.find('#tags_criteria .checkbox_input').removeClass('all');
		  }
		});

		$FIELD.find('#target_criteria .checkbox_input').prop('checked', false);
		$FIELD.find('#target_criteria .checkbox_input').on('click', function(){
		  if( $(this).hasClass('checkbox_all') ) {
			$FIELD.find('#target_criteria .checkbox_input').prop('checked', false );
			$FIELD.find('#target_criteria .checkbox_input').removeClass('all');
			$(this).addClass('all');
		  } else {
			$FIELD.find('#target_criteria .checkbox_input').removeClass('all');
		  }
		});
	  
		$FIELD.find('#libraries_criteria .checkbox_input').prop('checked', false);
		$FIELD.find('#libraries_criteria .checkbox_input').on('click', function(){
		  if( $(this).hasClass('checkbox_all') ) {
			$FIELD.find('#libraries_criteria .checkbox_input').prop('checked', false );
			$FIELD.find('#libraries_criteria .checkbox_input').removeClass('all');
			$(this).addClass('all');
		  } else {
			$FIELD.find('#libraries_criteria .checkbox_input').removeClass('all');
		  }
		});
	  
		$FIELD.find('#author_criteria .checkbox_input').prop('checked', false);
		$FIELD.find('#author_criteria .checkbox_input').on('click', function(){
		  if( $(this).hasClass('checkbox_all') ) {
			$FIELD.find('#author_criteria .checkbox_input').prop('checked', false );
			$FIELD.find('#author_criteria .checkbox_input').removeClass('all');
			$(this).addClass('all');
		  } else {
			$FIELD.find('#author_criteria .checkbox_input').removeClass('all');
		  }
		});
		
		$FIELD.find('.wp-field-list-filters-toggle').on('click', function(){
		  if( $FIELD.find('.wp-field-list-content').hasClass('open') ) {
			$FIELD.find('.wp-field-list-content').removeClass('open');
		  } else {
			$FIELD.find('.wp-field-list-content').addClass('open');
			$FIELD.find('.wp-field-list-filters-search').focus();
		  }
		});
	  
		$FIELD.on('click', '.listcard-toggle', function(){

		  var $this = $(this).closest('.listcard-footer');
		  if( $this.hasClass('open') ) {
			$this.removeClass('open');
		  } else {
			$this.addClass('open');
			}
			
		});
		
		var beforeRender = function(result, jQ){

		}

		var afterFilter = function(result, jQ){
	  
		  var checkboxes  = $FIELD.find("#category_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			count = jQ.where({ 'category': c.val() }).count;

			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
				c.parent().css('opacity',1);
				c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>')
			}
	  
			});
		
			var checkboxes  = $FIELD.find("#type_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			if(result.length > 0){
			  count = jQ.where({ 'type': c.val() }).count;
			}
			c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>');
			
			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
			  c.parent().css('opacity',1);
			}
	  
			});
			
			var checkboxes  = $FIELD.find("#target_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			if(result.length > 0){
			  count = jQ.where({ 'target': c.val() }).count;
			}
			c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>')
	  
			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
			  c.parent().css('opacity',1);
			}
	  
			});
			
		  var checkboxes  = $FIELD.find("#tags_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			if(result.length > 0){
			  count = jQ.where({ 'tags': c.val() }).count;
			}
			c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>');
			
			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
			  c.parent().css('opacity',1);
			}
	  
		  });
	  
		  var checkboxes  = $FIELD.find("#libraries_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			if(result.length > 0){
			  count = jQ.where({ 'libraries': c.val() }).count;
			}
			c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>')
	  
			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
			  c.parent().css('opacity',1);
			}
	  
		  });
	  
		  var checkboxes  = $FIELD.find("#author_criteria :input:gt(0)");
		  checkboxes.each(function(){
			var c = $(this), count = 0
	  
			if(result.length > 0){
			  count = jQ.where({ 'author': c.val() }).count;
			}
			c.next().html(c.attr('data-title') + '<span class="checkbox_input_counter">' + count + '<span>')
	  
			if( count == 0 ) {
			  c.parent().css('opacity',.5);
			} else {
			  c.parent().css('opacity',1);
			}
	  
		  });
	  
		}
	  
		var FJS = FilterJS( $DATA, '#' + $FIELD_ID + ' #wp-field-list-items-content', {
		  template: '#' + $FIELD_ID + ' #movie-template',
		  //search: { ele: '#searchbox' },
		  search: {ele: '#' + $FIELD_ID + ' #searchbox', fields: ['title']}, // With specific fields
		  callbacks: {
				beforeRender: beforeRender,
			afterFilter: afterFilter 
		  },
		  pagination: {
			container: '#' + $FIELD_ID + ' #pagination',
			visiblePages: 5,
			perPage: {
			  values: [200, 400, 600, 1000 ],
			  container: '#' + $FIELD_ID + ' #per_page'
			},
		  }
		});
	  
	  
		FJS.addCriteria({field: 'target', ele: '#' + $FIELD_ID + ' #target_criteria input:checkbox'});
		FJS.addCriteria({field: 'category', ele: '#' + $FIELD_ID + ' #category_criteria input:checkbox'});
		FJS.addCriteria({field: 'tags', ele: '#' + $FIELD_ID + ' #tags_criteria input:checkbox'});
		FJS.addCriteria({field: 'libraries', ele: '#' + $FIELD_ID + ' #libraries_criteria input:checkbox'});
		FJS.addCriteria({field: 'author', ele: '#' + $FIELD_ID + ' #author_criteria input:checkbox'});

		window.FJS = FJS;

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-list').wp_field_list();

});

}(jQuery));