/*!
* Nestable jQuery Plugin - Copyright (c) 2012 David Bushell - http://dbushell.com/
* Dual-licensed under the BSD or MIT licenses
*/
;(function($, window, document, undefined)
{
  var hasTouch = 'ontouchstart' in document;

  /**
  * Detect CSS pointer-events property
  * events are normally disabled on the dragging element to avoid conflicts
  * https://github.com/ausi/Feature-detection-technique-for-pointer-events/blob/master/modernizr-pointerevents.js
  */
  var hasPointerEvents = (function()
{
  var el    = document.createElement('div'),
  docEl = document.documentElement;
  if (!('pointerEvents' in el.style)) {
    return false;
  }
  el.style.pointerEvents = 'auto';
  el.style.pointerEvents = 'x';
  docEl.appendChild(el);
  var supports = window.getComputedStyle && window.getComputedStyle(el, '').pointerEvents === 'auto';
  docEl.removeChild(el);
  return !!supports;
})();

var defaults = {
  listNodeName    : 'ol',
  itemNodeName    : 'li',
  rootClass       : 'dd',
  listClass       : 'dd-list',
  itemClass       : 'dd-item',
  dragClass       : 'dd-dragel',
  handleClass     : 'dd-handle',
  collapsedClass  : 'dd-collapsed',
  placeClass      : 'dd-placeholder',
  noDragClass     : 'dd-nodrag',
  emptyClass      : 'dd-empty',
  expandBtnHTML   : '<button data-action="expand" type="button">Expand</button>',
  collapseBtnHTML : '<button data-action="collapse" type="button">Collapse</button>',
  group           : 0,
  maxDepth        : 5,
  threshold       : 20
};

function Plugin(element, options)
{
  this.w  = $(document);
  this.el = $(element);
  this.options = $.extend({}, defaults, options);
  this.init();
}

Plugin.prototype = {

  init: function()
{
  var list = this;

  list.reset();

  list.el.data('nestable-group', this.options.group);

  list.placeEl = $('<div class="' + list.options.placeClass + '"/>');

  var items = this.el.find(list.options.itemNodeName);

  $.each(items, function(k, el) {
      list.setParent($(el));
  });

  // Append the .dd-empty div if the list dont have any items on init
  if(!items.length) { this.appendEmptyElement(this.el); }

  list.el.on('click', 'button, .dd-action', function(e) {
    if (list.dragEl) {
      return;
    }
    var target = $(e.currentTarget),
    action = target.data('action'),
    item   = target.parent(list.options.itemNodeName);
    if (action === 'collapse') {
      list.collapseItem(item);
    }
    if (action === 'expand') {
      list.expandItem(item);
    }

    e.preventDefault();

  });

  var onStartEvent = function(e)
{
  var handle = $(e.target);
  if (!handle.hasClass(list.options.handleClass)) {
    if (handle.closest('.' + list.options.noDragClass).length) {
      return;
    }
    handle = handle.closest('.' + list.options.handleClass);
  }

  if (!handle.length || list.dragEl) {
    return;
  }

  list.isTouch = /^touch/.test(e.type);
  if (list.isTouch && e.touches.length !== 1) {
    return;
  }

  e.preventDefault();
  list.dragStart(e.touches ? e.touches[0] : e);
};

var onMoveEvent = function(e)
{
  if (list.dragEl) {
    e.preventDefault();
    list.dragMove(e.touches ? e.touches[0] : e);
  }
};

var onEndEvent = function(e)
{
  if (list.dragEl) {
    e.preventDefault();
    list.dragStop(e.touches ? e.touches[0] : e);
  }
};

if (hasTouch) {
  list.el[0].addEventListener('touchstart', onStartEvent, false);
  window.addEventListener('touchmove', onMoveEvent, false);
  window.addEventListener('touchend', onEndEvent, false);
  window.addEventListener('touchcancel', onEndEvent, false);
}

list.el.on('mousedown', onStartEvent);
list.w.on('mousemove', onMoveEvent);
list.w.on('mouseup', onEndEvent);

},

add: function(el)
{
  
  if( ! el.id ) {
      return;
  }

  var list = this;

  // console.log('add');
  // console.log(list);
  
  $html = '';

  $html += '<div class="dd-handle"></div>';
                
  $html += '<div class="dd-content">';

    $html += '<div class="dd-inside" style="position:relative;">';

      $html += '<div class="dd-title">';
        
        $html += '<span class="the_icon ' + el.icon + '"></span> ';
        
        var title = el.title;
        if ( ! title ) title = el.name;
        if ( ! title ) title = '';
        $html += '<span class="the_title">' + title + '</span>';
        
        $html += '<span class="is-submenu"></span>';
      $html += '</div>';

      $html += '<span class="item-controls">';
        $html += '<span class="item-type">' + el.id + '</span>';
        $html += '<a class="dd-edit item-edit" title="edit" href="#"></a>';
      $html += '</span>';

    $html += '</div>';

    $html += '<div class="menu-item-settings-holder">';

    $html += '</div>';

    // if ( el.children.length != 0 ) {
      
    //   $html += '<ol class="dd-list" style="">';

    //   $html += '</ol>';
    
    // }

  $html += '</div>';
                 
  //if(!list.find(list.options.itemNodeName).length ) {
    //the_list = $('<' + list.options.listNodeName + '/>').addClass(list.options.listClass).append(el);
    //list.html(the_list);
  //} else {
    //list.addChild(el);
  //}


      
    
  var new_el = $('<'+list.options.itemNodeName+'>');
  new_el.addClass(list.options.itemClass).append($html);
  new_el.attr('data-id', el.id);
  for (var key in el) {
      if (el.hasOwnProperty(key) ) {
          new_el.attr('data-'+key, el[key]);
      }
  }

  if( !list.el.find(list.options.itemNodeName).length ) {
    
    var new_list = $(document.createElement(list.options.listNodeName)).addClass(list.options.listClass);
    list.el.html(new_list);
    // list.init();
    
    list.el.children('.' + list.options.listClass).first().append(new_el);

    list.setParent(new_el);

    // var new_list = $('<'+list.options.listNodeName+'>').addClass(list.options.listClass);
    // new_list.append(new_el);
    // $(list.el).append(new_list);
    // list.setParent($(list.el));

  } else {

    list.el.children('.' + list.options.listClass).first().append(new_el);

  }
      
  // this.reset();
  

},

// addChild: function(child){
//     var list = this;
//     var new_item = $('<'+list.options.itemNodeName+'>');
//     new_item.addClass(list.options.itemClass).append($('<div>').html(child.text));
//     new_item.attr('data-id', child.id);
//     for (var key in child) {
//         if (child.hasOwnProperty(key) && key != 'id' && key != 'text'){
//             new_item.attr('data-'+key, child[key]);
//         }
//     }
//     $.each(list.el.find(list.options.itemNodeName), function(k, el) {
//         if($(el).attr('data-id') == child.parent){
//             if($(el).children(list.options.listNodeName).length){
//                 $(el).find(list.options.listNodeName).append(new_item);
//             }
//             else{
//                 var new_list = $('<'+list.options.listNodeName+'>').addClass(list.options.listClass);
//                 new_list.append(new_item);
//                 $(el).append(new_list);
//                 list.setParent($(el));
//             }
//             return;
//         }
//     });

// },

// remove: function(id)
// {
//     var list = this;
//     $.each(list.el.find(list.options.itemNodeName), function(k, el) {
//         if($(el).attr('data-id') == id){
//             $(el).remove();
//             return;
//         }
//     });
// },

serialize: function()
{
  var data,
  depth = 0,
  list  = this;
  getAttributes = function(dom)
{ 
  var attributes = {};
  $.each( dom.get(0).attributes, function(i, attrib){
    if (attrib.name.match("^data-")) attributes[attrib.name.slice(5)] = attrib.value;
  });
  return attributes;
}
  step  = function(level, depth)
{
  var array = [ ],
  items = level.children(list.options.itemNodeName);
  items.each(function()
{
  var li   = $(this),
  item = item = $.extend({}, li.data, getAttributes(li) ),
  sub  = li.children(list.options.listNodeName);
  if (sub.length) {
    item.children = step(sub, depth + 1);
  }
  array.push(item);
});
return array;
};
data = step(list.el.find(list.options.listNodeName).first(), depth);
return data;
},

serialise: function()
{
  return this.serialize();
},

reset: function()
{
  this.mouse = {
    offsetX   : 0,
    offsetY   : 0,
    startX    : 0,
    startY    : 0,
    lastX     : 0,
    lastY     : 0,
    nowX      : 0,
    nowY      : 0,
    distX     : 0,
    distY     : 0,
    dirAx     : 0,
    dirX      : 0,
    dirY      : 0,
    lastDirX  : 0,
    lastDirY  : 0,
    distAxX   : 0,
    distAxY   : 0
  };
  this.isTouch    = false;
  this.moving     = false;
  this.dragEl     = null;
  this.dragRootEl = null;
  this.dragDepth  = 0;
  this.hasNewRoot = false;
  this.pointEl    = null;
},

expandItem: function(li)
{
  li.removeClass(this.options.collapsedClass);
  li.children('[data-action="expand"]').hide();
  li.children('[data-action="collapse"]').show();
  li.children(this.options.listNodeName).slideDown(300);
},

collapseItem: function(li)
{
  var lists = li.children(this.options.listNodeName);
  if (lists.length) {
    li.addClass(this.options.collapsedClass);
    li.children('[data-action="collapse"]').hide();
    li.children('[data-action="expand"]').show();
    li.children(this.options.listNodeName).slideUp(300);
  }
},

expandAll: function()
{
  var list = this;
  list.el.find(list.options.itemNodeName).each(function() {
    list.expandItem($(this));
  });
},

collapseAll: function()
{
  var list = this;
  list.el.find(list.options.itemNodeName).each(function() {
    list.collapseItem($(this));
  });
},

setParent: function(li)
{
  if (li.children(this.options.listNodeName).length) {
    li.prepend($(this.options.expandBtnHTML));
    li.prepend($(this.options.collapseBtnHTML));
  }
  li.children('[data-action="expand"]').hide();
},

unsetParent: function(li)
{
  li.removeClass(this.options.collapsedClass);
  li.children('[data-action]').remove();
  li.children(this.options.listNodeName).remove();
},

dragStart: function(e)
{
  var mouse    = this.mouse,
  target   = $(e.target),
  dragItem = target.closest(this.options.itemNodeName);

  this.placeEl.css('height', dragItem.height());

  mouse.offsetX = e.offsetX !== undefined ? e.offsetX : e.pageX - target.offset().left;
  mouse.offsetY = e.offsetY !== undefined ? e.offsetY : e.pageY - target.offset().top;
  mouse.startX = mouse.lastX = e.pageX;
  mouse.startY = mouse.lastY = e.pageY;

  this.dragRootEl = this.el;

  this.dragEl = $(document.createElement(this.options.listNodeName)).addClass(this.options.listClass + ' ' + this.options.dragClass);
  this.dragEl.css('width', dragItem.width());

  dragItem.after(this.placeEl);
  dragItem[0].parentNode.removeChild(dragItem[0]);
  dragItem.appendTo(this.dragEl);

  $(document.body).append(this.dragEl);
  this.dragEl.css({
    'left' : e.pageX - mouse.offsetX,
    'top'  : e.pageY - mouse.offsetY
  });
  // total depth of dragging item
  var i, depth,
  items = this.dragEl.find(this.options.itemNodeName);
  for (i = 0; i < items.length; i++) {
    depth = $(items[i]).parents(this.options.listNodeName).length;
    if (depth > this.dragDepth) {
      this.dragDepth = depth;
    }
  }
},

dragStop: function(e)
{
  var el = this.dragEl.children(this.options.itemNodeName).first();
  el[0].parentNode.removeChild(el[0]);
  this.placeEl.replaceWith(el);

  this.dragEl.remove();
  this.el.trigger('change');
  if (this.hasNewRoot) {
    this.dragRootEl.trigger('change');
  }
  this.reset();
},

dragMove: function(e)
{
  var list, parent, prev, next, depth,
  opt   = this.options,
  mouse = this.mouse;

  this.dragEl.css({
    'left' : e.pageX - mouse.offsetX,
    'top'  : e.pageY - mouse.offsetY
  });

  // mouse position last events
  mouse.lastX = mouse.nowX;
  mouse.lastY = mouse.nowY;
  // mouse position this events
  mouse.nowX  = e.pageX;
  mouse.nowY  = e.pageY;
  // distance mouse moved between events
  mouse.distX = mouse.nowX - mouse.lastX;
  mouse.distY = mouse.nowY - mouse.lastY;
  // direction mouse was moving
  mouse.lastDirX = mouse.dirX;
  mouse.lastDirY = mouse.dirY;
  // direction mouse is now moving (on both axis)
  mouse.dirX = mouse.distX === 0 ? 0 : mouse.distX > 0 ? 1 : -1;
  mouse.dirY = mouse.distY === 0 ? 0 : mouse.distY > 0 ? 1 : -1;
  // axis mouse is now moving on
  var newAx   = Math.abs(mouse.distX) > Math.abs(mouse.distY) ? 1 : 0;

  // do nothing on first move
  if (!mouse.moving) {
    mouse.dirAx  = newAx;
    mouse.moving = true;
    return;
  }

  // calc distance moved on this axis (and direction)
  if (mouse.dirAx !== newAx) {
    mouse.distAxX = 0;
    mouse.distAxY = 0;
  } else {
    mouse.distAxX += Math.abs(mouse.distX);
    if (mouse.dirX !== 0 && mouse.dirX !== mouse.lastDirX) {
      mouse.distAxX = 0;
    }
    mouse.distAxY += Math.abs(mouse.distY);
    if (mouse.dirY !== 0 && mouse.dirY !== mouse.lastDirY) {
      mouse.distAxY = 0;
    }
  }
  mouse.dirAx = newAx;

  /**
  * move horizontal
  */
  if (mouse.dirAx && mouse.distAxX >= opt.threshold) {
    // reset move distance on x-axis for new phase
    mouse.distAxX = 0;
    prev = this.placeEl.prev(opt.itemNodeName);
    // increase horizontal level if previous sibling exists and is not collapsed
    if (mouse.distX > 0 && prev.length && !prev.hasClass(opt.collapsedClass)) {
      // cannot increase level when item above is collapsed
      list = prev.find(opt.listNodeName).last();
      // check if depth limit has reached
      depth = this.placeEl.parents(opt.listNodeName).length;
      if (depth + this.dragDepth <= opt.maxDepth) {
        // create new sub-level if one doesn't exist
        if (!list.length) {
          list = $('<' + opt.listNodeName + '/>').addClass(opt.listClass);
          list.append(this.placeEl);
          prev.append(list);
          this.setParent(prev);
        } else {
          // else append to next level up
          list = prev.children(opt.listNodeName).last();
          list.append(this.placeEl);
        }
      }
    }
    // decrease horizontal level
    if (mouse.distX < 0) {
      // we can't decrease a level if an item preceeds the current one
      next = this.placeEl.next(opt.itemNodeName);
      if (!next.length) {
        parent = this.placeEl.parent();
        this.placeEl.closest(opt.itemNodeName).after(this.placeEl);
        if (!parent.children().length) {
          this.unsetParent(parent.parent());
        }
      }
    }
  }

  var isEmpty = false;

  // find list item under cursor
  if (!hasPointerEvents) {
    this.dragEl[0].style.visibility = 'hidden';
  }
  this.pointEl = $(document.elementFromPoint(e.pageX - document.body.scrollLeft, e.pageY - (window.pageYOffset || document.documentElement.scrollTop)));
  if (!hasPointerEvents) {
    this.dragEl[0].style.visibility = 'visible';
  }
  if (this.pointEl.hasClass(opt.handleClass)) {
    this.pointEl = this.pointEl.parent(opt.itemNodeName);
  }
  if (this.pointEl.hasClass(opt.emptyClass)) {
    isEmpty = true;
  }
  else if (!this.pointEl.length || !this.pointEl.hasClass(opt.itemClass)) {
    return;
  }

  // find parent list of item under cursor
  var pointElRoot = this.pointEl.closest('.' + opt.rootClass),
  isNewRoot   = this.dragRootEl.data('nestable-id') !== pointElRoot.data('nestable-id');

  /**
  * move vertical
  */
  if (!mouse.dirAx || isNewRoot || isEmpty) {
    // check if groups match if dragging over new root
    if (isNewRoot && opt.group !== pointElRoot.data('nestable-group')) {
      return;
    }
    // check depth limit
    depth = this.dragDepth - 1 + this.pointEl.parents(opt.listNodeName).length;
    if (depth > opt.maxDepth) {
      return;
    }
    var before = e.pageY < (this.pointEl.offset().top + this.pointEl.height() / 2);
    parent = this.placeEl.parent();
    // if empty create new list to replace empty placeholder
    if (isEmpty) {
      list = $(document.createElement(opt.listNodeName)).addClass(opt.listClass);
      list.append(this.placeEl);
      this.pointEl.replaceWith(list);
    }
    else if (before) {
      this.pointEl.before(this.placeEl);
    }
    else {
      this.pointEl.after(this.placeEl);
    }
    if (!parent.children().length) {
      this.unsetParent(parent.parent());
    }
    if (!this.dragRootEl.find(opt.itemNodeName).length) {
      this.appendEmptyElement(this.dragRootEl);
    }
    // parent root list has changed
    if (isNewRoot) {
      this.dragRootEl = pointElRoot;
      this.hasNewRoot = this.el[0] !== this.dragRootEl[0];
    }
  }
},

/**
 * Append the .dd-empty div to the list so it can be populated and styled
 *
 * @param  {element} element The list to apppend the empty div
 */
appendEmptyElement: function(element) {
    element.append('<div class="' + this.options.emptyClass + '"/>');
}

};

$.fn.nestable = function(params, val )
{
  var lists  = this,
  retval = this;

  lists.each(function()
{
  var plugin = $(this).data("nestable");

  if (!plugin) {
    $(this).data("nestable", new Plugin(this, params));
    $(this).data("nestable-id", new Date().getTime());
  } else {
    if (typeof params === 'string' && typeof plugin[params] === 'function') {
      if (typeof val !== 'undefined') {
          retval = plugin[params](val);
      } else {
          retval = plugin[params]();
      }
    }
  }
});

return retval || lists;
};

})(window.jQuery || window.Zepto, window, document);
