(function($) {

  $.fn.sg_block_tabs = function(){

    $(this).each(function(){

      var $this = $(this);
      var $links = $this.find('.sg_block_tabs-links');
      
      var settings = {
        animate: false,
        animationSpeed: "normal",
        cache: true,
        collapsible: false,
        collapsedClass: "collapsed",
        collapsedByDefault: true,
        containerClass: "",
        cycle: false,
        defaultTab: '.sg_block_tabs-link:first-child',
        event: 'click',
        panelActiveClass: "active",
        panelClass: "",
        tabActiveClass: "active",
        tabs: ".sg_block_tabs-link",
        tabsClass: "",
        tabClass: "",
        transitionIn: 'fadeIn',
        transitionOut: 'fadeOut',
        transitionInEasing: 'swing',
        transitionOutEasing: 'swing',
        transitionCollapse: 'slideUp',
        transitionUncollapse: 'slideDown',
        transitionCollapseEasing: 'swing',
        transitionUncollapseEasing: 'swing',
        uiTabs: false,
        updateHash: false
      };

      if ( $links.find('.sg_block_tabs-link').length ) {

        if ( $links.find('.sg_block_tabs-link.default-active').length ) {

          settings.collapsible = false;
          settings.collapsedByDefault = false;
          settings.defaultTab = 'li.default-active';

        }
        
        $this.find('.active').removeClass('active');
        
        $this.easytabs( settings );
      
      }

			
    }); 

  }

  $(document).ready(function(){

    $('body').find('.sg_block_tabs').sg_block_tabs();

  });

}(jQuery));
