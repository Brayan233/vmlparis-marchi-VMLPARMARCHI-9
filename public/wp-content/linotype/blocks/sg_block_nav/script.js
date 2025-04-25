(function ($) {

  $.fn.sg_block_nav = function () {

    $(this).each(function () {

      const $nav = $(this);
      const $body = $('body');

      /* HEADER STICKY / DISAPPEAR */
      let scrollTimeOut = true,
          yPos = 0,
          setNavClasses = function() {
            scrollTimeOut = false;
            yPos = $(window).scrollTop();
            
            if (yPos > 0) {
              $nav.addClass('sticky');
            } else {
              $nav.removeClass('sticky');
            }
          };

      window.addEventListener('scroll', function(e) {
        scrollTimeOut = true;
      }, {passive: true});

      setInterval(function() {
        if (scrollTimeOut) {
          setNavClasses();
        }
      }, 250);

      
      /* NAVIGATION */
      // sub nav toggle
      $nav.find('.site-nav .sub-nav-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if ($(this).hasClass('active') ) {
          $(this).removeClass('active');
          $(this).next('.sub-nav-wrap').removeClass('show');
          if (window.matchMedia('only screen and (min-width: 992px)').matches) {
            $nav.find('.navbar').removeClass('sub-nav-open');
          }
          if (window.matchMedia('only screen and (max-width: 991px)').matches) {
            $('#menuButton').removeClass('back');
          }
        } else {
          $nav.find('.site-nav .sub-nav-toggle.active').removeClass('active');
          $nav.find('.site-nav .sub-nav-wrap.show').removeClass('show');
          $(this).addClass('active');
          $(this).next('.sub-nav-wrap').addClass('show');
          if (window.matchMedia('only screen and (min-width: 992px)').matches) {
            $nav.find('.navbar').addClass('sub-nav-open');
          }
          if (window.matchMedia('only screen and (max-width: 991px)').matches) {
            $('#menuButton').addClass('back');
          }
        }
      });

      // mobile menu buttons
      $nav.on('click', '#menuButton', function(e) {
        if ($(this).hasClass('back')) {
          $nav.find('.sub-nav-toggle.active, .dropdown-toggle.active').removeClass('active');
          $nav.find('.sub-nav-wrap.show, .dropdown-menu.show').removeClass('show');
          if (window.matchMedia('only screen and (max-width: 991px)').matches) {
            $(this).removeClass('back');
          }
        } else {
          $body.toggleClass('prevent-scroll');
          if (window.matchMedia('only screen and (max-width: 991px)').matches) {
            $body.toggleClass('menu-open');
          }
          $(this).toggleClass('close');
          $nav.find('#mainMenu').toggleClass('show');
        }
      });

      // language menu
      $nav.find('.language-switcher').on('click', '.dropdown-toggle', function() {
        $(this).toggleClass('active');
        $(this).next().toggleClass('show');
        if (window.matchMedia('only screen and (max-width: 991px)').matches) {
          $('#menuButton').toggleClass('back');
        }
      });
      // on desktop, close the lang dropdown or sub menu if user clicks outside of them
      const $languageSwitcherDesktop = $nav.find('.nav-right .language-switcher');
      const $subNav = $nav.find('.sub-nav-wrap');
      $(document).click(function(e) {
        if (window.matchMedia('only screen and (min-width: 992px)').matches) {
          const target = e.target;
          if ($languageSwitcherDesktop.find('.dropdown-menu').is(':visible') && !$languageSwitcherDesktop.is(target) && $languageSwitcherDesktop.has(target).length === 0) {
            $languageSwitcherDesktop.find('.dropdown-toggle').removeClass('active');
            $languageSwitcherDesktop.find('.dropdown-menu').removeClass('show');
          }
          if ($subNav.is(':visible') && !$subNav.is(target) && $subNav.has(target).length === 0) {
            $subNav.prev('.sub-nav-toggle').removeClass('active');
            $subNav.removeClass('show');
            $subNav.parents('.navbar').removeClass('sub-nav-open');
          }
        }
      });
      
    });

  }

  $(document).ready(function () {

    $('body').find('.sg_block_nav').sg_block_nav();

    // FOR WHOLE WEBSITE: calculate the actual vh
    // some mobile browsers (iOS Safari, Android Broswer) have a bottom navbar. the problem is that the height of this navbar is not included in CSS's vh value. so we measure the actual vh value with JS.
    // https://css-tricks.com/the-trick-to-viewport-units-on-mobile/
    var vh = window.innerHeight * 0.01;
    var vhValue = vh + 'px';
    document.documentElement.style.setProperty('--vh', vhValue);

    $(window).resize('resize', function() {
      var vh = window.innerHeight * 0.01;
      var vhValue = vh + 'px';
      document.documentElement.style.setProperty('--vh', vhValue);
    });

  });

}(jQuery));
