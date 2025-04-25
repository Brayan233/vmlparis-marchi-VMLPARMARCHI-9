// Simple yet flexible JSON editor plugin.
// Turns any element into a stylable interactive JSON editor.

// Copyright (c) 2013 David Durman

// Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php).

// Dependencies:

// * jQuery
// * JSON (use json2 library for browsers that do not support JSON natively)

// Example:

//     var myjson = { any: { json: { value: 1 } } };
//     var opt = { change: function() { /* called on every change */ } };
//     /* opt.propertyElement = '<textarea>'; */ // element of the property field, <input> is default
//     /* opt.valueElement = '<textarea>'; */  // element of the value field, <input> is default
//     $('#mydiv').jsonEditor(myjson, opt);

(function( $ ) {

    $.fn.jsonEditor = function(json, options) {
        options = options || {};
        // Make sure functions or other non-JSON data types are stripped down.
        json = parse(stringify(json));

        var K = function() {};
        var onchange = options.change || K;
        var onpropertyclick = options.propertyclick || K;

        return this.each(function() {
            JSONEditor($(this), json, onchange, onpropertyclick, options.propertyElement, options.valueElement);
        });

    };

    function JSONEditor(target, json, onchange, onpropertyclick, propertyElement, valueElement) {
        var opt = {
            target: target,
            onchange: onchange,
            onpropertyclick: onpropertyclick,
            original: json,
            propertyElement: propertyElement,
            valueElement: valueElement
        };
        opt.json_ref = parse(stringify(opt.original));
        construct(opt, json, opt.target);
        $(opt.target).on('blur focus', '.property, .value', function() {
            $(this).toggleClass('editing');
        });
    }

    function isObject(o) { return Object.prototype.toString.call(o) == '[object Object]'; }
    function isArray(o) { return Object.prototype.toString.call(o) == '[object Array]'; }
    function isBoolean(o) { return Object.prototype.toString.call(o) == '[object Boolean]'; }
    function isNumber(o) { return Object.prototype.toString.call(o) == '[object Number]'; }
    function isString(o) { return Object.prototype.toString.call(o) == '[object String]'; }
    var types = 'object array boolean number string null';

    // Feeds object `o` with `value` at `path`. If value argument is omitted,
    // object at `path` will be deleted from `o`.
    // Example:
    //      feed({}, 'foo.bar.baz', 10);    // returns { foo: { bar: { baz: 10 } } }
    function feed(o, path, value) {
        var del = arguments.length == 2;

        if (path.indexOf('.') > -1) {
            var diver = o,
                i = 0,
                parts = path.split('.');
            for (var len = parts.length; i < len - 1; i++) {
                diver = diver[parts[i]];
            }
            if (del) delete diver[parts[len - 1]];
            else diver[parts[len - 1]] = value;
        } else {
            if (del) delete o[path];
            else o[path] = value;
        }
        return o;
    }

    // Get a property by path from object o if it exists. If not, return defaultValue.
    // Example:
    //     def({ foo: { bar: 5 } }, 'foo.bar', 100);   // returns 5
    //     def({ foo: { bar: 5 } }, 'foo.baz', 100);   // returns 100
    function def(o, path, defaultValue) {
        path = path.split('.');
        var i = 0;
        while (i < path.length) {
            if ((o = o[path[i++]]) == undefined) return defaultValue;
        }
        return o;
    }

    function error(reason) { if (window.console) { console.error(reason); } }

    function parse(str) {
        var res;
        try { res = JSON.parse(str); }
        catch (e) { res = null; error('JSON parse failed.'); }
        return res;
    }

    function stringify(obj) {
        var res;
        try { res = JSON.stringify(obj); }
        catch (e) { res = 'null'; error('JSON stringify failed.'); }
        return res;
    }

    function addExpander(item) {
        if (item.children('.expander').length == 0) {
            var expander =   $('<span>',  { 'class': 'dashicons dashicons-plus expander' });
            expander.bind('click', function() {
                var item = $(this).parent();
                item.toggleClass('expanded');
            });
            item.prepend(expander);
        }
    }

    function addListAppender(item, handler) {
        var appender = $('<div>', { 'class': 'item appender' }),
            btn      = $('<button></button>', { 'class': 'property' });

        btn.text('Add New Value');

        appender.append(btn);
        item.append(appender);

        btn.click(handler);

        return appender;
    }

    function addNewValue(json) {
        if (isArray(json)) {
            json.push(null);
            return true;
        }

        if (isObject(json)) {
            var i = 1, newName = "newKey";

            while (json.hasOwnProperty(newName)) {
                newName = "newKey" + i;
                i++;
            }

            json[newName] = null;
            return true;
        }

        return false;
    }

    function construct(opt, json, root, path) {
        path = path || '';

        root.children('.item').remove();

        for (var key in json) {
            if (!json.hasOwnProperty(key)) continue;

            var item     = $('<div>',   { 'class': 'item', 'data-path': path }),
                property =   $(opt.propertyElement || '<input>', { 'class': 'property' }),
                value    =   $(opt.valueElement || '<input>', { 'class': 'value'    });

            if (isObject(json[key]) || isArray(json[key])) {
                addExpander(item);
            }

            item.append(property).append(value);
            root.append(item);

            property.val(key).attr('title', key);
            var val = stringify(json[key]);
            value.val(val).attr('title', val);

            assignType(item, json[key]);

            property.change(propertyChanged(opt));
            value.change(valueChanged(opt));
            property.click(propertyClicked(opt));

            if (isObject(json[key]) || isArray(json[key])) {
                construct(opt, json[key], item, (path ? path + '.' : '') + key);
            }
        }

        if (isObject(json) || isArray(json)) {
            addListAppender(root, function () {
                addNewValue(json);
                construct(opt, json, root, path);
                opt.onchange(parse(stringify(opt.original)),opt.json_ref);
            })
        }
        //opt.onchange(parse(stringify(opt.original)));
    }

    function updateParents(el, opt) {
        $(el).parentsUntil(opt.target).each(function() {
            var path = $(this).data('path');
            path = (path ? path + '.' : path) + $(this).children('.property').val();
            var val = stringify(def(opt.original, path, null));
            $(this).children('.value').val(val).attr('title', val);
        });
    }

    function propertyClicked(opt) {
        return function() {
            var path = $(this).parent().data('path');
            var key = $(this).attr('title');

            var safePath = path ? path.split('.').concat([key]).join('\'][\'') : key;

            opt.onpropertyclick('[\'' + safePath + '\']');
        };
    }

    function propertyChanged(opt) {
        return function() {
            var path = $(this).parent().data('path'),
                val = parse($(this).next().val()),
                newKey = $(this).val(),
                oldKey = $(this).attr('title');

            $(this).attr('title', newKey);

            feed(opt.original, (path ? path + '.' : '') + oldKey);
            if (newKey) feed(opt.original, (path ? path + '.' : '') + newKey, val);

            updateParents(this, opt);

            if (!newKey) $(this).parent().remove();

            opt.onchange(parse(stringify(opt.original)),opt.json_ref);
        };
    }

    function valueChanged(opt) {
        return function() {
            var key = $(this).prev().val(),
                val = parse($(this).val() || 'null'),
                item = $(this).parent(),
                path = item.data('path');

            feed(opt.original, (path ? path + '.' : '') + key, val);
            if ((isObject(val) || isArray(val)) && !$.isEmptyObject(val)) {
                construct(opt, val, item, (path ? path + '.' : '') + key);
                addExpander(item);
            } else {
                item.find('.expander, .item').remove();
            }

            assignType(item, val);

            updateParents(this, opt);

            opt.onchange(parse(stringify(opt.original)),opt.json_ref);
        };
    }

    function assignType(item, val) {
        var className = 'null';

        if (isObject(val)) className = 'object';
        else if (isArray(val)) className = 'array';
        else if (isBoolean(val)) className = 'boolean';
        else if (isString(val)) className = 'string';
        else if (isNumber(val)) className = 'number';

        item.removeClass(types);
        item.addClass(className);
    }

})( jQuery );




(function($) {

$.fn.wp_field_json = function(){

  // Is a value an array
  function isArray(val) {
      return Object.prototype.toString.call(val) === "[object Array]";
  }
  // Is a value an Object
  function isPlainObject(val) {
      return Object.prototype.toString.call(val) === "[object Object]";
  }
  // Sorting Logic
  function sortJSON(un) {
      var or = {};
      if (isPlainObject(un)) {
          or = {};
          Object.keys(un).sort(function (a, b) {
              if (a.toLowerCase() < b.toLowerCase()) return -1;
              if (a.toLowerCase() > b.toLowerCase()) return 1;
              return 0;
          }).forEach(function (key) {
              or[key] = sortJSON(un[key]);
          });
      } else {
          or = un;
      }
      return or;
  }
  // Sort the JSON
  function sort($data) {
      var input, j, r;
      input = $data;
      if (input) {
        j = input;
        r = sortJSON(j);
        return JSON.stringify(r);
      }
  }
	$(this).each(function(){

		var $FIELD = $(this);
		var $VALUE = $FIELD.find('.wp-field-value');
    var $DATA = $FIELD.find('.wp-field-data');

    var $editor = $FIELD.find('.json-editor');

    var json_data = $.parseJSON( $DATA.val() );

    var json = '';
    if ( $VALUE.val() ) json = $VALUE.val();

    json_data = sort( json_data );

    $DATA.val( json_data );

    var opt = {

        change: function(data,original) {

            $VALUE.val( sort( data ) );

            if ( $DATA.val() == $VALUE.val() ) $VALUE.val( '' );

      },
      propertyclick: function(path) {

      }

    };

    if( json ){
      $editor.jsonEditor( JSON.parse( json ), opt );
    } else {
      $editor.jsonEditor( JSON.parse( json_data ), opt );
    }

    $FIELD.find('.json-editor-reset').on('click', function(){

      $VALUE.val( '' );
      $editor.jsonEditor( JSON.parse( json_data ), opt );

    });

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-json').wp_field_json();

});

}(jQuery));
