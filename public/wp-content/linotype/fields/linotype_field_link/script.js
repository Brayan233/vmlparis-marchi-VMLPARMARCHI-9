
(function($) {

$.fn.linotype_field_link = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		
		var $OUTPUT = $FIELD.find('.wp-field-value');

		var $INPUT = $FIELD.find('.linotype_field_link-input');

		var $TITLE = $FIELD.find('.linotype_field_link-title');
		var $URL = $FIELD.find('.linotype_field_link-url');
		var $TARGET = $FIELD.find('.linotype_field_link-target');

		var OPTIONS = $.parseJSON( $FIELD.find('.selectize-options').val() );

		var datagroups = {};
		var optgroups = [];

		// if( $.inArray( 'optgroup_columns', OPTIONS['plugins'] ) !='-1' ) {
		// 	$.each( OPTIONS.data, function( index, item ) {
		// 		datagroups[ item.optgroup ] = { value: item.optgroup, label: item.optgroup };
		// 	});
		// 	$.each( datagroups, function( index, item ) {
		// 		optgroups.push( item );
		// 	});
		// }

		// var arg = {

		// 	options: OPTIONS.data,
		// 	optgroups: optgroups,

		// 	plugins: ["restore_on_backspace","remove_button","optgroup_columns"],
		// 	delimiter: ",",
		// 	clear_button : true,
		// 	splitOn: null,
		// 	persist: true,
		// 	diacritics: true,
		// 	createOnBlur: false,
		// 	createFilter: null,
		// 	highlight: true,
		// 	openOnFocus: true,
		// 	maxOptions: OPTIONS.maxOptions,
		// 	maxItems: 1,
		// 	hideSelected: false,
		// 	addPrecedence: false,
		// 	selectOnTab: false,
		// 	preload: false,
		// 	allowEmptyOption: true,
		// 	closeAfterSelect: true,

		// 	scrollDuration: 60,
		// 	loadThrottle: 300,
		// 	loadingClass: 'loading',

		// 	dataAttr: 'data-data',
		// 	optgroupField: 'optgroup',
		// 	valueField: OPTIONS.valueField,
		// 	labelField: OPTIONS.labelField,
		// 	optgroupLabelField: 'label',
		// 	optgroupValueField: 'value',
		// 	lockOptgroupOrder: false,

		// 	sortField: '$order',
		// 	searchField: [OPTIONS.labelField,OPTIONS.valueField],
		// 	searchConjunction: 'and',

		// 	mode: null,
		// 	wrapperClass: 'selectize-control',
		// 	inputClass: 'selectize-input',
		// 	dropdownClass: 'selectize-dropdown',
		// 	dropdownContentClass: 'selectize-dropdown-content',

		// 	dropdownParent: null,

		// 	copyClassesToDropdown: true,
			
		// 	fullwidthitem: true,

		// 	/*
		// 	load                 : null, // function(query, callback) { ... }
		// 	score                : null, // function(search) { ... }
		// 	onInitialize         : null, // function() { ... }
		// 	onChange             : null, // function(value) { ... }
		// 	onItemAdd            : null, // function(value, $item) { ... }
		// 	onItemRemove         : null, // function(value) { ... }
		// 	onClear              : null, // function() { ... }
		// 	onOptionAdd          : null, // function(value, data) { ... }
		// 	onOptionRemove       : null, // function(value) { ... }
		// 	onOptionClear        : null, // function() { ... }
		// 	onOptionGroupAdd     : null, // function(id, data) { ... }
		// 	onOptionGroupRemove  : null, // function(id) { ... }
		// 	onOptionGroupClear   : null, // function() { ... }
		// 	onDropdownOpen       : null, // function($dropdown) { ... }
		// 	onDropdownClose      : null, // function($dropdown) { ... }
		// 	onType               : null, // function(str) { ... }
		// 	onDelete             : null, // function(values) { ... }
		// 	*/

		// 	render: {
		// 		/*
		// 		item: null,
		// 		optgroup: null,
		// 		optgroup_header: null,
		// 		option: null,
		// 		option_create: null
		// 		*/
		// 	}
		// }

		// var arg_custom = {

		// 	create: function(input) {
		//         return {
		//         	title: input,
		//             value: input
		//         }
		//     },

		// }

		// if ( OPTIONS.custom ) $.extend( arg, arg_custom );

		// $URL.selectize(arg);

		// if ( OPTIONS.default && ! $URL[0].selectize.getValue() ) $URL[0].selectize.setValue( OPTIONS.default );


		$INPUT.on('change paste', function(){
			
			update();
		
		});

		function update() {

			var link = {};

			link.title = $TITLE.val();
			
			link.url = $URL.val(); //$URL[0].selectize.getValue();

			link.target = '';
			if ( $TARGET.is(":checked") ) link.target = '_blank';

			$OUTPUT.val( JSON.stringify( link ) );

		}


	});

}

$(document).ready(function(){

	$('body').find('.linotype_field_link').linotype_field_link();

});

}(jQuery));
