<?php

if ( ! function_exists('LINOADMIN_modal_icons') ) {

function LINOADMIN_modal_icons(){

      ?>
<style>

.linoadmin_modal_icons {
      position: fixed;
      top: 30px;
      left: 30px;
      right: 30px;
      bottom: 30px;
      z-index: 160000;
      font-family: sans-serif;
      font-size: 14px;
}
.linoadmin_modal_icons .close-modal {
      position: absolute;
      text-decoration: none;
      top: 10px;
      right: 10px;
      width: 30px;
      height: 30px;
      z-index: 1000;
      -webkit-transition: color .1s ease-in-out, background .1s ease-in-out;
      transition: color .1s ease-in-out, background .1s ease-in-out;
      color: #444 ;
}
.linoadmin_modal_icons .close-modal:hover ,
.linoadmin_modal_icons .close-modal:focus {
      color: #2ea2cc ;
}

.linoadmin_modal_icons .linoadmin_modal_icons-content {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      overflow: hidden;
      min-height: 300px;
      background: #fff;
}
.linoadmin_modal_icons-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      min-height: 360px;
      background: #000;
      opacity: .7;
      z-index: 159900;
}
.linoadmin_modal_icons-content .navigation-bar {
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      width: 199px;
      z-index: 150;
}
.linoadmin_modal_icons-content .navigation-bar nav {
      display:block;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: 0;
      padding: 16px 0;
      border-right: 1px solid #d9d9d9;
      box-shadow: inset -6px 0 6px -6px rgba(0,0,0,0.2);
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
}
.linoadmin_modal_icons-content .navigation-bar nav ul {
      display:block;
      padding: 16px;
      margin: 0;
      list-style: none;
}
.linoadmin_modal_icons-content .navigation-bar nav ul li{
      padding: 0;
      margin: 0;
      list-style: none;
}
.linoadmin_modal_icons-content .navigation-bar nav ul li.separator {
      height: 0;
      margin: 12px 20px;
      padding: 0;
      border-top: 1px solid #dfdfdf;
      border-bottom: 1px solid #fff;
}
.linoadmin_modal_icons-content .navigation-bar nav a {
      display: block;
      position: relative;
      padding: 4px 20px;
      margin: 0;
      line-height: 18px;
      font-size: 14px;
      color: #21759b;
      text-shadow: 0 1px 0 #fff;
      text-decoration: none;
}
.linoadmin_modal_icons-content .navigation-bar nav a:hover ,
.linoadmin_modal_icons-content .navigation-bar nav a:focus {
      background-color: rgba(0, 0, 0, 0.04);
}
.linoadmin_modal_icons-main {
      position: absolute;
      top: 0;
      left: 200px;
      right: 0;
      bottom: 0 ;
      z-index: 200;
}
.linoadmin_modal_icons-main .linoadmin_modal_icons-page {
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 50px;
    z-index: 200;
    overflow-y: auto;
    overflow-x: hidden;
        padding-left: 20px;
        padding-bottom: 20px;
    padding-right: 20px;
}
.linoadmin_modal_icons-main article, .linoadmin_modal_icons-main header {
      display: block;
      position: relative;
      padding: 4px 16px;
}
.linoadmin_modal_icons-main h1 {
      font-size: 22px;
      font-weight: 200;
      line-height: 45px;
      margin: 0;
}
.linoadmin_modal_icons-main footer {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 30px;
      z-index: 100;
      padding: 10px 0px;
      border: 0 solid #dfdfdf;
      border-width: 1px 0 0 0;
      box-shadow: 0 -4px 4px -4px rgba(0,0,0,0.1);
}
.linoadmin_modal_icons-main footer .inner {
      padding: 0 16px;
}
.text-right {
      text-align: right;
}
.ir {
      background-color: transparent;
      border: 0;
      overflow: hidden;
      /* IE 6/7 fallback */
      *text-indent: -9999px;
}

.ir:before {
      content: "";
      display: block;
      width: 0;
      height: 150%;
}

/*
#linoadmin_modal_icons_dialog.no-sidebar .navigation-bar {
display: none;
}
#linoadmin_modal_icons_dialog.no-sidebar .linoadmin_modal_icons-main{
left: 0px;
}
*/
</style>

<div id="linoadmin_modal_icons_dialog" class="no-sidebar" tabindex="0" style="display:none;">

      <div class="linoadmin_modal_icons">

            <a class="linoadmin_modal_icons-close close-modal dashicons dashicons-no" href="#" title="Close"><span class="screen-reader-text">Close</span></a>

            <div class="linoadmin_modal_icons-content">

                  <div class="navigation-bar">
                        <nav>
                              <ul>
                                  <li class="nav-item" target="linoadmin_modal_icons-tab-content__dashicons"><a href="#one">dashicons</a></li>
                                  <li class="nav-item" target="linoadmin_modal_icons-tab-content__font-awesome"><a href="#one">Font Awsome</a></li>
                                    <!-- <li class="separator">&nbsp;</li> -->
                              </ul>
                        </nav>
                  </div>

                  <section class="linoadmin_modal_icons-main" role="main">

                        <!-- <header><h1>Select Icon</h1></header> -->

                        <section class="linoadmin_modal_icons-page" id="linoadmin_modal_icons-tab-content__dashicons">
                              <?php include 'icons-list-dashicons.php'; ?>
                        </section>

                        <section class="linoadmin_modal_icons-page" id="linoadmin_modal_icons-tab-content__font-awesome" style="display:none">
                              <?php include 'icons-list-font-awesome.php'; ?>
                        </section>

                        <footer>
                              <div class="inner text-right">
                                    <button id="btn-cancel" class="linoadmin_modal_icons-clear button button-large">Remove</button>
                                    <button id="btn-ok" class="linoadmin_modal_icons-ok button button-primary button-large">OK</button>
                              </div>
                        </footer>
                  </section>

            </div>

      </div>

      <div class="linoadmin_modal_icons-backdrop">&nbsp;</div>

</div>

      <?php

}

add_action( 'admin_footer', 'LINOADMIN_modal_icons' );

}
