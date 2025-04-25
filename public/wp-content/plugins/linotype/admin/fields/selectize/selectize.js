
(function($) {

Selectize.define('clear_button', function(options) {
    /**
     * Escapes a string for use within HTML.
     *
     * @param {string} str
     * @returns {string}
     */
    var escape_html = function(str) {
        return (str + '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    };

    options = $.extend({
        label     : '&times;',
        title     : 'Remove',
        className : 'clearAll',
        append    : true
    }, options);

    var self = this;

    this.setup = (function() {
        console.log("Called setup")
        var original = self.setup;
        return function() {
            // override the item rendering method to add the button to each
            original.apply(this, arguments);

            var sel_input = this.$wrapper.find('.selectize-input');

            if ( sel_input.length > 0 ) {
                var disabled = sel_input.hasClass('disabled');

                if ( disabled ) {
                    options.className = options.className + " disabled";
                }
            }

            var html = '<span class="' + options.className + '" tabindex="-1" title="' + escape_html(options.title) + '">' + options.label + '</span>';

            this.$wrapper.append(html);

            // add event listener
            this.$wrapper.on('click', '.' + options.className, function(e) {
                e.preventDefault();
                if (self.isLocked) return;
                self.clear();
            });

        };
    })();

});

$.fn.wp_field_selectize = function(){

	$(this).each(function(){

		var $FIELD = $(this);
		var $SELECT = $FIELD.find('.wp-field-value');

		var OPTIONS = $.parseJSON( $FIELD.find('.selectize-options').val() );

		var datagroups = {};
		var optgroups = [];

    if( $.inArray( 'optgroup_columns', OPTIONS['plugins'] ) !='-1' ) {
  		$.each( OPTIONS.data, function( index, item ) {
  			datagroups[ item.optgroup ] = { value: item.optgroup, label: item.optgroup };
  		});
  		$.each( datagroups, function( index, item ) {
  			optgroups.push( item );
  		});
    }

		var arg = {

			options: OPTIONS.data,
			optgroups: optgroups,

			plugins: OPTIONS.plugins,
			delimiter: OPTIONS.delimiter,
			splitOn: null, // regexp or string for splitting up values from a paste command
			persist: OPTIONS.persist,
			diacritics: true,
			createOnBlur: false,
			createFilter: null,
			highlight: true,
			openOnFocus: true,
			maxOptions: OPTIONS.maxOptions,
			maxItems: OPTIONS.maxItems,
			hideSelected: OPTIONS.hideSelected,
			addPrecedence: false,
			selectOnTab: false,
			preload: false,
			allowEmptyOption: OPTIONS.allowEmptyOption,
			closeAfterSelect: OPTIONS.closeAfterSelect,

			scrollDuration: 60,
			loadThrottle: 300,
			loadingClass: 'loading',

			dataAttr: 'data-data',
			optgroupField: 'optgroup',
			valueField: OPTIONS.valueField,
			labelField: OPTIONS.labelField,
			optgroupLabelField: 'label',
			optgroupValueField: 'value',
			lockOptgroupOrder: false,

			sortField: '$order',
			searchField: [OPTIONS.labelField,OPTIONS.valueField],
			searchConjunction: 'and',

			mode: null,
			wrapperClass: 'selectize-control',
			inputClass: 'selectize-input',
			dropdownClass: 'selectize-dropdown',
			dropdownContentClass: 'selectize-dropdown-content',

			dropdownParent: null,

			copyClassesToDropdown: true,

			/*
			load                 : null, // function(query, callback) { ... }
			score                : null, // function(search) { ... }
			onInitialize         : null, // function() { ... }
			onChange             : null, // function(value) { ... }
			onItemAdd            : null, // function(value, $item) { ... }
			onItemRemove         : null, // function(value) { ... }
			onClear              : null, // function() { ... }
			onOptionAdd          : null, // function(value, data) { ... }
			onOptionRemove       : null, // function(value) { ... }
			onOptionClear        : null, // function() { ... }
			onOptionGroupAdd     : null, // function(id, data) { ... }
			onOptionGroupRemove  : null, // function(id) { ... }
			onOptionGroupClear   : null, // function() { ... }
			onDropdownOpen       : null, // function($dropdown) { ... }
			onDropdownClose      : null, // function($dropdown) { ... }
			onType               : null, // function(str) { ... }
			onDelete             : null, // function(values) { ... }
			*/

			render: {
				/*
				item: null,
				optgroup: null,
				optgroup_header: null,
				option: null,
				option_create: null
				*/
			}
		}

		var arg_custom = {

			create: function(input) {
		        return {
		        	title: input,
		            value: input
		        }
		    },

		}

		if ( OPTIONS.custom ) $.extend( arg, arg_custom );

		$SELECT.selectize(arg);

		if ( OPTIONS.default && ! $SELECT[0].selectize.getValue() ) $SELECT[0].selectize.setValue( OPTIONS.default );

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-selectize').wp_field_selectize();

});

}(jQuery));
