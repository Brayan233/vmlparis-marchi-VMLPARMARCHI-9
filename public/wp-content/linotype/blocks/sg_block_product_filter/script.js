(function($) {

  $.fn.sg_block_product_filter = function(){
  
    $(this).each(function(){
  
      var $body = $('body');
      var $sg_block_product_filter = $(this);
      var $filterID = '#' + $sg_block_product_filter.attr('id');
      var $ID = $sg_block_product_filter.attr('data-filter-id');
      var $filter_total = '#filter-total-' + $ID;
      var $filter_search = '#filter-search-' + $ID;
      var $filter_control = '.filter-control-' + $ID;
      var $filter_items = '#filter-items-' + $ID;
      var $filter_item_template = '#filter-item-template-' + $ID;
      var $filter_pagination = '#filter-pagination-' + $ID;
      var $filter_perpage = '#filter-perpage-' + $ID;
      var activeFilterCount = 0;

      filterOPTIONS = $.parseJSON($(this).find('.sg_block_product_filter-options').val());
  
      if (!filterOPTIONS.per_page) {
        filterOPTIONS.per_page = [10,20,50,100];
      } else {
        filterOPTIONS.per_page = filterOPTIONS.per_page.split(',').map(Number);
      }
  
      if (typeof(filterOPTIONS.visible_page) != "undefined" && filterOPTIONS.visible_page !== null) filterOPTIONS.visible_page = 5;
      filterOPTIONS.visible_page = parseInt(filterOPTIONS.visible_page, 10);
  
      window.sg_block_product_filter_current_criteria = null;
  
      // Store the current filter being clicked
      $sg_block_product_filter.on('mousedown', '.sg_block_product_filter-filter', function() {
        window.sg_block_product_filter_current_criteria = $(this).attr('id');
      });

      // Handle filter triggering from UI clicks
      $sg_block_product_filter.on('filter', function(event, isCustomSort) {
        // Prevent nested filtering to avoid infinite loops
        if ($sg_block_product_filter.data('filtering')) return;
        
        $sg_block_product_filter.data('filtering', true);
        var FJS = $sg_block_product_filter.data('FJS');
        
        if (FJS) {
          try {
            FJS.filter();
          } catch (e) {
          }
        }
        
        $sg_block_product_filter.data('filtering', false);
      });

      // Handle clicks outside of inputs to trigger filtering
      $sg_block_product_filter.on('click', function(event) {
        // Only trigger filter for clicks outside of controls
        if (!$(event.target).is('input.sg_block_product_filter-field, .checkmark, .label, button, a, span, .sg_block_product_filter-filter-selected, .sg_block_product_filter-filter-dropdown, .sg_block_product_filter-filter-item')) {
          // Do nothing - don't trigger filter on whole container clicks
        }
      });

      // Handle filter changes
      $sg_block_product_filter.on('change', '.sg_block_product_filter-field', function(event) {
        // Stop event propagation to prevent clicks outside
        event.stopPropagation();
        
        var FJS = $sg_block_product_filter.data('FJS');
        
        // Special handling for sort_by changes
        if ($(this).attr('name') === 'sort_by') {
          // Trigger sort which will apply the new sort option
          if (FJS) {
            $sg_block_product_filter.trigger('sort');
          }
          return;
        }
        
        // For normal filters, update count and trigger filter
        // Count active filters (excluding sort_by)
        activeFilterCount = $sg_block_product_filter.find('.filters .sg_block_product_filter-field:checked')
          .not('[name="sort_by"]')
          .length;
        
        // Update UI based on filter count
        if (activeFilterCount > 0) {
          if (window.innerWidth < 768) {
            if ($sg_block_product_filter.find('.filters .btn-filter .filter-count').length) {
              $sg_block_product_filter.find('.filters .btn-filter .filter-count').html(activeFilterCount);
            } else {
              $sg_block_product_filter.find('.filters .btn-filter').append('<span class="filter-count">' + activeFilterCount + '</span>');
            }
            if ($sg_block_product_filter.find('.filters .filter-title .filter-count').length) {
              $sg_block_product_filter.find('.filters .filter-title .filter-count').html(activeFilterCount);
            } else {
              $sg_block_product_filter.find('.filters .filter-title').append('<span class="filter-count">' + activeFilterCount + '</span>');
            }
          } else if (window.innerWidth >= 768) {
            $sg_block_product_filter.find('.filters .btn-reset').addClass('show');
          }
        } else {
          if (window.innerWidth < 768) {
            $sg_block_product_filter.find('.filters .filter-count').remove();
          } else if (window.innerWidth >= 768) {
            $sg_block_product_filter.find('.filters .btn-reset').removeClass('show');
          }
        }
        
        // Apply filtering
        if (FJS) {
          $sg_block_product_filter.trigger('filter');
        }
      });

      // Mobile filter button
      $sg_block_product_filter.on('click', '.btn-filter', function() {
        $body.addClass('prevent-scroll');
        $body.append('<div class="filter-backdrop"></div>');
        $body.find('.filter-backdrop').addClass('show');
        if ($(this).parents('.filters')) {
          $('.filters .sg_block_product_filter-filter-selected').addClass('show');
          $('.filters .sg_block_product_filter-filter-dropdown').show();
        }
        $(this).next('.filter-list').addClass('show');
      });
      
      // Close filter dialog
      $sg_block_product_filter.on('click', '[data-action="close-filters"]', function() {
        $(this).parents('.filter-list').removeClass('show');
        $body.find('.filter-backdrop').remove();
        $body.removeClass('prevent-scroll');
      });

      // Toggle category
      $sg_block_product_filter.on('click', 'a.category-btn', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).next('ul').slideToggle();
      });

      // Filter dropdown toggle (mobile)
      $sg_block_product_filter.on('click', '.filters .sg_block_product_filter-filter-selected', function() {
        if (window.innerWidth < 768) {
          $(this).toggleClass('active');
          $(this).next('.sg_block_product_filter-filter-dropdown').slideToggle();
        }
      });
      
      // Reset filters
      $sg_block_product_filter.on('click', '[data-action*="reset-"]', function() {
        if ($(this).attr('data-action') == 'reset-categories') {
          $sg_block_product_filter.find('input[name="wc_product_cat"][value="all"]').trigger('click');
        }
        if ($(this).attr('data-action') == 'reset-filters') {
          $sg_block_product_filter.find('.filters .filter-count').remove();
          
          // Reset all non-sort filters
          $sg_block_product_filter.find('.filters .sg_block_product_filter-field:checked')
            .not('[name="sort_by"]')
            .trigger('click');
          
          // Also reset the sort option to "relevance"
          var currentSortOption = $sg_block_product_filter.find('input[name="sort_by"]:checked').val();
          if (currentSortOption !== 'relevance') {
            $sg_block_product_filter.find('input[name="sort_by"][value="relevance"]').prop('checked', true);
            // Trigger sort to apply the change
            $sg_block_product_filter.trigger('sort');
          }
          
          if (window.innerWidth >= 768) {
            $(this).removeClass('show');
          }
        }
      });

      // Search field handling
      $($filter_search).on('change keyup', function() {
        if ($(this).val() !== "") {
          $(this).parent().addClass('searching');
        } else {
          $(this).parent().removeClass('searching');
        }
      });
      
      // Clear search
      $($filter_search).parent().find('.search-clear').on('click', function() {
        $($filter_search).val('').change();
        $sg_block_product_filter.find('input[name="wc_product_cat"][value="all"]').trigger('click');
      });
  
      // Load data from sync source
      function load_sync() {
        var b64Data = sg_block_product_filter_data;
        var strData = atob(b64Data);
        var charData = strData.split('').map(function(x){return x.charCodeAt(0);});
        var binData = new Uint8Array(charData);
        var data = pako.inflateRaw(binData);
        var strData = Utf8ArrayToStr(data);
        sg_block_product_filter_init($.parseJSON(strData));
      }

      // Initialize FilterJS
      function sg_block_product_filter_init(items) {
        // Store original item order for "Relevance" sorting
        var originalItems = items.slice(0);

        var FJS = FilterJS(items, $filter_items, {
          template: $filter_item_template,
          search: { ele: $filter_search },
          pagination: {
            container: $filter_pagination,
            visiblePages: filterOPTIONS.visible_page,
            perPage: {
              values: filterOPTIONS.per_page,
              container: $filter_perpage
            },
          },
          callbacks: {
            afterFilter: function(result, jQ) {
              // Store a copy of filtered results for our own use
              this.sortedResults = result.slice(0);
              
              // Apply sorting based on selected option
              var sortOption = $sg_block_product_filter.find('input[name="sort_by"]:checked').val();
              
              if (sortOption === 'price_low_high') {
                // LOW TO HIGH: smallest price first
                // Create a copy for stable sort
                var sorted = result.slice(0);
                sorted.sort(function(a, b) {
                  // Handle missing or invalid prices
                  var priceA = parseFloat(a.sort_price || 0);
                  var priceB = parseFloat(b.sort_price || 0);
                  
                  return priceA - priceB; // Ascending
                });
                
                // IMPORTANT: Force complete re-rendering with the sorted array 
                // instead of just modifying the array in place
                this.render(sorted);
                
                // Skip the normal rendering since we're handling it ourselves
                return false;
              } 
              else if (sortOption === 'price_high_low') {
                // HIGH TO LOW: largest price first
                // Create a copy for stable sort
                var sorted = result.slice(0);
                sorted.sort(function(a, b) {
                  // Handle missing or invalid prices
                  var priceA = parseFloat(a.sort_price || 0);
                  var priceB = parseFloat(b.sort_price || 0);
                  
                  return priceB - priceA; // Descending
                });
                
                // IMPORTANT: Force complete re-rendering with the sorted array
                // instead of just modifying the array in place
                this.render(sorted);
                
                // Skip the normal rendering since we're handling it ourselves
                return false;
              }
              else if (sortOption === 'relevance') {
                // Use original order (as items were initially loaded)
                // Get IDs of current results
                var resultIds = {};
                result.forEach(function(item) {
                  resultIds[item.id] = true;
                });
                
                // Create new array preserving original order but only for current results
                var sortedByRelevance = [];
                originalItems.forEach(function(item) {
                  if (resultIds[item.id]) {
                    sortedByRelevance.push(item);
                  }
                });
                
                // IMPORTANT: Force complete re-rendering with the original order
                this.render(sortedByRelevance);
                
                // Skip the normal rendering since we're handling it ourselves
                return false;
              }

              $($filter_total).text(result.length);
              
              // Update filter options display
              $($filter_control).each(function() {
                var field_id = $(this).attr('data-field');
                if (!field_id) return; // Skip sort_by
                
                // Update select options
                $("#" + field_id + "_filter_select > select").children("option").each(function() {
                  var c = $(this), count = 0;
                  if (c.val() != 'all') {
                    if (result.length > 0) {
                      $el = {};
                      $el[field_id] = c.val();
                      count = jQ.where($el).count;
  
                      if (count) {
                        c.text(c.val() + '(' + count + ')');
                        c.prop("disabled", false);
                      } else {
                        c.text(c.val())
                        if (window.sg_block_product_filter_current_criteria !== field_id + '_filter_select') {
                          c.prop("disabled", true);
                        }
                      }
                    }
                  }
                });
  
                // Update checkbox filters
                $("#" + field_id + "_filter_checkbox").find("input").each(function() {
                  var c = $(this), count = 0;
                  if (c.val() != 'all') {
                    if (window.sg_block_product_filter_current_criteria !== field_id + '_filter_checkbox') {
                      c.parent().addClass('hide');
                    }
  
                    if (result.length > 0) {
                      $el = {};
                      $el[field_id] = c.val();
                      count = jQ.where($el).count;
                      $display_count = ' <span class="count">(' + count + ')</span>';
  
                      if (count) {
                        c.parent().children('span.label').html(c.val() + $display_count);
                        c.prop("disabled", false);
                        c.parent().removeClass('hide');
                      } else {
                        if (window.sg_block_product_filter_current_criteria !== field_id + '_filter_checkbox') { 
                          c.prop("disabled", true); 
                          c.prop("checked", false);
                        }
                      }
                    }
                  }
                });
  
                // Update radio filters
                $("#" + field_id + "_filter_radio").find("input").each(function() {
                  var c = $(this), count = 0;
                  if (c.val() != 'all') {
                    if (window.sg_block_product_filter_current_criteria !== field_id + '_filter_radio') {
                      c.parent().addClass('hide');
                    }
  
                    if (result.length > 0) {
                      $el = {};
                      $el[field_id] = c.val();
                      count = jQ.where($el).count;
                      $display_count = ' <span class="count">(' + count + ')</span>';
  
                      if (count) {
                        c.parent().children('span.label').html(c.val() + $display_count);
                        c.prop("disabled", false);
                        c.parent().removeClass('hide');
                      } else {
                        if (window.sg_block_product_filter_current_criteria !== field_id + '_filter_radio') {
                          c.prop("disabled", true);
                        }
                      }
                    }
                    }
                });
              });
            }
          },
        });
  
        // Store original items and init sortedResults
        FJS.originalItems = originalItems;
        FJS.sortedResults = [];
  
        // Add filter criteria
        $($filter_control).each(function() {
          var field = $(this).attr('data-field');
          if (field && $(this).attr('name') !== 'sort_by') {
            FJS.addCriteria({
              field: field, 
              ele: $filterID + ' #' + $(this).attr('id'), 
              all: 'all'
            });
          }
        });
  
        // Store instance for later access
        $sg_block_product_filter.data('FJS', FJS);
  
        return FJS;
      }
  
      // Load data and initialize
      load_sync();
      
      // Apply DOM-based sorting
      function applySortToDOM(sortOption) {
        // Get the container and all product items
        var $container = $($filter_items);
        var $items = $container.children('li');
        
        if ($items.length === 0) {
          return;
        }
        
        // Check if the container already has a sort attribute
        var currentSort = $container.attr('data-current-sort');
        
        // OPTIMIZATION: If we're just switching between price sorts, we can simply reverse the DOM
        if (currentSort && 
            ((currentSort === 'price_low_high' && sortOption === 'price_high_low') || 
             (currentSort === 'price_high_low' && sortOption === 'price_low_high'))) {
          
          // Simply reverse the current DOM order for optimal performance
          var $reversedItems = $items.get().reverse();
          $container.append($reversedItems);
          
          // Update sort attribute
          $container.attr('data-current-sort', sortOption);
          return;
        }
        
        // For other cases, perform normal sorting
        
        // Get items with their prices and store in array for sorting
        var itemsWithPrices = [];
        var useFallbackPricing = true;
        var currentPosition = 0;
        
        $items.each(function(index) {
          var $item = $(this);
          var title = $item.find('.product-title').text();
          var price = 0;
          
          // Try to extract price directly from the DOM
          var $discountedPrice = $item.find('.product-price ins');
          var $regularPrice = $item.find('.product-price');
          var priceText = '';
          
          // If there's a discounted price, use that
          if ($discountedPrice.length > 0) {
            priceText = $discountedPrice.text();
          } else {
            // Otherwise use the regular price
            priceText = $regularPrice.text();
          }
          
          if (priceText) {
            // First clean up the price text
            var cleanPrice = priceText.replace(/\s+/g, '').replace(/€/g, '');
            
            // Try to match the price digits
            var matches = cleanPrice.match(/(\d+)/g);
            
            if (matches && matches.length > 0) {
              // For discounted prices, use the first number
              var priceStr = matches[0];
              price = parseInt(priceStr, 10);
              
              // If price was successfully extracted, we don't need fallback
              if (price > 0) useFallbackPricing = false;
            }
          }
          
          // Add positional info to ensure stable sorting
          currentPosition++;
          
          // Store item with its price and position
          itemsWithPrices.push({
            element: $item,
            price: price,
            title: title,
            position: currentPosition
          });
        });
        
        // APPROACH 2: If DOM extraction failed, use a positional fallback
        if (useFallbackPricing) {
          if (sortOption === 'price_low_high') {
            // Reverse the array to put smaller prices (accessories) at the top
            itemsWithPrices.reverse();
          }
          // For high to low, keep default order - typically most expensive at top
        }
        // APPROACH 3: Traditional sorting based on extracted prices
        else {
          // Sort the items based on price
          if (sortOption === 'price_low_high') {
            itemsWithPrices.sort(function(a, b) {
              // If prices are equal, maintain original order using position
              if (a.price === b.price) return a.position - b.position;
              return a.price - b.price; // Low to high
            });
          } 
          else if (sortOption === 'price_high_low') {
            itemsWithPrices.sort(function(a, b) {
              // If prices are equal, maintain original order using position
              if (a.price === b.price) return a.position - b.position;
              return b.price - a.price; // High to low
            });
          }
        }
        
        // Reappend items in sorted order
        for (var i = 0; i < itemsWithPrices.length; i++) {
          $container.append(itemsWithPrices[i].element);
        }
        
        // Store the current sort type as an attribute on the container
        $container.attr('data-current-sort', sortOption);
      }

      // Update the sort event handler to use the new DOM sorting approach
      $sg_block_product_filter.on('sort', function() {
        var FJS = $sg_block_product_filter.data('FJS');
        if (FJS) {
          var sortOption = $sg_block_product_filter.find('input[name="sort_by"]:checked').val();
          var prevSortOption = $sg_block_product_filter.data('current-sort');
          
          // Store current sort for next time
          $sg_block_product_filter.data('current-sort', sortOption);
          
          // Optimize for direct sort order changes (price low→high ↔ high→low)
          var isPriceSortChange = 
            (prevSortOption === 'price_low_high' && sortOption === 'price_high_low') || 
            (prevSortOption === 'price_high_low' && sortOption === 'price_low_high');
            
          if (isPriceSortChange) {
            // For direct price sort changes, skip filtering and just reverse the current DOM
            applySortToDOM(sortOption);
          } else {
            // For other cases (e.g., changing from relevance to a price sort)
            // run the normal filter then sort
            FJS.filter();
            
            // Then apply the sorting directly to the DOM
            setTimeout(function() {
              applySortToDOM(sortOption);
            }, 50);
          }
        }
      });

      // Update the initial load to use DOM sorting as well
      setTimeout(function() {
        // Check which sort option is selected by default and apply it
        var defaultSortOption = $sg_block_product_filter.find('input[name="sort_by"]:checked').val();
        
        // Store initial sort option
        $sg_block_product_filter.data('current-sort', defaultSortOption);
        
        // Get FilterJS instance
        var FJS = $sg_block_product_filter.data('FJS');
        if (FJS) {
          // Apply initial filtering
          FJS.filter();
          
          // Then apply sorting
          setTimeout(function() {
            applySortToDOM(defaultSortOption);
          }, 100);
        }
        
        // Then trigger any initially active filters
      $sg_block_product_filter.find('.sg_block_product_filter-field.init').click();
        $sg_block_product_filter.find('.sg_block_product_filter-field.init')
          .parent().parent().parent().parent()
          .find('.category-btn').click();
      }, 300);
      
      // Handle filter item clicks
      $sg_block_product_filter.on('click', '.sg_block_product_filter-filter-item', function(e) {
        // Only handle if the click is not directly on the checkbox
        if (!$(e.target).is('input')) {
          e.preventDefault();
          var $checkbox = $(this).find('input.sg_block_product_filter-field');
          if ($checkbox.length) {
            var isChecked = $checkbox.prop('checked');
            $checkbox.prop('checked', !isChecked).trigger('change');
            $(this).toggleClass('active', !isChecked);
          } else {
            $(this).toggleClass('active');
          }
          // Toggle subcategories
          var $subcat = $(this).next('ul');
          if ($subcat.length) {
            $subcat.slideToggle();
          }
        }
      });
      
      // Handle initial active categories
      var currentActive = $sg_block_product_filter.find('.sg_block_product_filter-filter-link.active');
      if(currentActive.length) {
        // Main categories
        var nextUl = currentActive.next('ul');
        if(nextUl.length) {
          nextUl.slideDown();
        }
        
        // Subcategories
        var parentUl = currentActive.closest('ul');
        var parentLi = parentUl.parent('li');
        if(parentLi.length && !parentLi.find('> a.active').length) {
          parentUl.slideDown();
          var parentLink = parentLi.find('> a.sg_block_product_filter-filter-link');
          if(parentLink.length) {
            parentLink.addClass('active');
          }
        }
      }

      // Fix the sort_by filter selector click behavior to make it activate immediately
      $sg_block_product_filter.on('mouseup', '.sg_block_product_filter-filter-item input[name="sort_by"]', function(e) {
        // Add small delay to ensure the radio button is properly checked
        setTimeout(function() {
          $sg_block_product_filter.trigger('sort');
        }, 50);
      });
    });
  
    // UTF8 array to string (for decompressing data)
    function Utf8ArrayToStr(array) {
      var out, i, len, c;
      var char2, char3;
  
      out = "";
      len = array.length;
      i = 0;
      while(i < len) {
      c = array[i++];
        switch(c >> 4) {
        case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
          // 0xxxxxxx
          out += String.fromCharCode(c);
          break;
        case 12: case 13:
          // 110x xxxx   10xx xxxx
          char2 = array[i++];
          out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
          break;
        case 14:
          // 1110 xxxx  10xx xxxx  10xx xxxx
          char2 = array[i++];
          char3 = array[i++];
          out += String.fromCharCode(((c & 0x0F) << 12) |
                         ((char2 & 0x3F) << 6) |
                         ((char3 & 0x3F) << 0));
          break;
      }
      }
  
      return out;
    }
  }
  
  $(document).ready(function(){
    $('body').find('.sg_block_product_filter-instance').sg_block_product_filter();
  });
  
}(jQuery));
