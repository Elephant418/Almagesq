<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= $almagesq->getTitle( ) ?> Living Style Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      <? 
        echo file_get_contents( __DIR__ . '/../css/bootstrap.min.css' );
        echo file_get_contents( __DIR__ . '/../css/bootstrap-responsive.min.css' );
        echo file_get_contents( __DIR__ . '/../css/prismjs.css' );
        echo file_get_contents( __DIR__ . '/../css/app.css' );
      ?>
      .navbar-inverse .navbar-inner,
      .navbar .navbar-inverse .dropdown-menu li > a:hover,
      .navbar-inverse .dropdown-menu li > a:hover,
      .navbar-inverse .dropdown-menu .active > a,
      .navbar-inverse .dropdown-menu .active > a:hover {
        background: <?= $almagesq->getNavbarStyle( 'background' ) ?>;
        color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
      }
      .navbar.navbar-inverse .brand,
      .navbar.navbar-inverse .nav > .resize,
      .navbar.navbar-inverse .nav > li > a {
        color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
      }
      .navbar.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret {
          border-bottom-color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
          border-top-color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
      }
      pre {
        border: 0;
        border-radius: 0;
      }
    </style>
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a href="index.php<?= $almagesq->getHttpQuery( array( ) ) ?>" class="brand <?= ( $almagesq->currentMenus[ 0 ] === NULL ? 'active' : '' )?>"><?= $almagesq->getTitle( ) ?></a>
          <div class="nav-collapse collapse">
            <div class="navbar-text pull-right">
              <ul class="nav">
                <li class="resize">
                  <a href="javascript:resizeIframe('-');">-</a> |
                  <?php
                    $breakpoints = $almagesq->getPatternBreakpoints( );
                    if ( isset( $breakpoints[ 'available'] ) && is_array( $breakpoints[ 'available'] ) ):
                      foreach( $breakpoints[ 'available'] as $breakpoint ):
                  ?>
                      <a href="javascript:resizeIframe(<?= $breakpoint ?>);"><?= $breakpoint ?></a> |
                  <?php 
                      endforeach;
                    endif;
                  ?>
                  <a href="javascript:resizeIframe('100%');">Max</a> |
                  <a href="javascript:resizeIframe('+');">+</a>
                </li>
                <?php if ( is_array( $almagesq->themes ) && count( $almagesq->themes ) > 1 ): ?>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Themes <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <?php foreach ( array_keys( $almagesq->themes ) as $theme ): ?> 
                        <li><a href="<?= $almagesq->getHttpQuery( NULL, $theme ) ?>"><?= Almagesq::FileHumanName( $theme ) ?></a></li>
                      <?php endforeach; ?> 
                    </ul>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
            <ul class="nav">
              <?php 
                foreach ( $almagesq->menus as $menu => $submenus ): 
                  $isMenuActive = ( $almagesq->currentMenus[ 0 ] == $menu );
                  if ( empty( $submenus ) || Almagesq::hasPatterns( $submenus ) ): 
              ?>
                  <li class="<?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="<?= $almagesq->getHttpQuery( array( $menu ) ) ?>"><?= Almagesq::FileHumanName( $menu ) ?></a>
                  </li>
                <?php else: ?>
                  <li class="dropdown <?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="<?= $almagesq->getHttpQuery( array( $menu ) ) ?>" class="dropdown-toggle" data-toggle="dropdown">
                      <?= Almagesq::FileHumanName( $menu ) ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                      <?php 
                        foreach ( array_keys( $submenus ) as $submenu ): 
                        $isSubmenuActive = ( $isMenuActive && $almagesq->currentMenus[ 1 ] == $submenu );
                      ?> 
                        <li class="<?= ( $isSubmenuActive ? 'active' : '' )?>">
                          <a href="<?= $almagesq->getHttpQuery( array( $menu, $submenu ) ) ?>"><?= Almagesq::FileHumanName( $submenu ) ?></a>
                        </li>
                      <?php endforeach; ?> 
                    </ul>
                  </li>
              <?php 
                  endif;
                endforeach;
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <?php
        foreach ( $almagesq->patterns as $pattern ):
          $patternName = Almagesq::FileName( $pattern );
          $patternHumanName = Almagesq::FileHumanName( $patternName );
          $iframeUrl = 'iframe.php' . $almagesq->getHttpQuery( ) . '&amp;pattern=' . $pattern;
      ?>
        <div class="pattern" id="<?= $patternName ?>">
          <div class="pattern__title">
            <a href="#<?= $patternName ?>" class="pattern__link pattern__link--anchor">#</a>
            <?= $patternHumanName ?>
            <div class="pull-right">
              <a href="#<?= $patternName ?>_copy" class="pattern__link pattern__link--copy">select code</a> |
              <a href="#<?= $patternName ?>_code" class="pattern__link pattern__link--code">view code</a> |
              <a href="<?= $iframeUrl ?>" class="pattern__link" target="_blank">open iframe</a>
            </div>
          </div>
          <div class="pattern__demo">
            <iframe src="<?= $iframeUrl ?>" frameBorder="0" marginWidth="0" marginHeight="0" scrolling="no" hspace="0" vspace="0">
            </iframe>
          </div>
          <div class="pattern__code" id="<?= $patternName ?>_code">
            <pre><code class="language-markup"><?= htmlentities( $almagesq->getPatternHtml( $pattern ) ) ?></code></pre>
          </div>
          <div class="pattern__copy" id="<?= $patternName ?>_copy">
            <textarea><?= $almagesq->getPatternHtml( $pattern ) ?></textarea>
          </div>
        </div>
      <?php  
        endforeach;
      ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script>
      <?= file_get_contents( __DIR__ . '/../js/prismjs.js' ); ?>
      <?= file_get_contents( __DIR__ . '/../js/bootstrap.js' ); ?>
      $(window).load(function( ){
        $( '.pattern__code' ).hide( );
        $( '.pattern__link--code' ).click( function( ) {
          if ( $( this.href.substring( this.href.indexOf('#') ) ).toggle( ).is(':hidden') ) {
            this.innerHTML = 'view code';
          } else {
            this.innerHTML = 'hide code';
          }
          return false;
        });
        $(".pattern__link--copy").click( function() {
          $( this.href.substring( this.href.indexOf('#') ) )
            .children()
            .get(0)
            .select();
          return false;
        });
        <?php if ( isset( $breakpoints[ 'default'] ) ) : ?>
          resizeIframe( <?= var_export( $breakpoints[ 'default'] ) ?> );
        <?php endif; ?>
        $( 'iframe' ).each( function( ) {
          this.onload = function( ) {
            adaptHeight( this );
          };
        });
      });
      function resizeIframe( width ) {
        if ( width == '-' ) {
          width = $( 'iframe' ).width() - 5;
        } else if ( width == '+' ) {
          width = $( 'iframe' ).width() + 5;
        } 
        if ( ! isNaN( width ) ) {
          width += 'px';
        }
        $( 'iframe' ).width( width );
        $( 'iframe' ).each( function( ) {
          adaptHeight( this );
        });
      }
      function adaptHeight( iframe ) {
        iframe.style.height = ( iframe.contentWindow.document.body.clientHeight + 2 ) + 'px';
      }
    </script>
  </body>
</html>