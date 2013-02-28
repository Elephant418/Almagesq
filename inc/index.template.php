<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= $almagesq->getTitle( ) ?> Style Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="css/app.css">
    <style>
      .navbar-inverse .navbar-inner,
      .navbar .navbar-inverse .dropdown-menu li > a:hover,
      .navbar-inverse .dropdown-menu li > a:hover,
      .navbar-inverse .dropdown-menu .active > a,
      .navbar-inverse .dropdown-menu .active > a:hover {
        background: <?= $almagesq->getNavbarStyle( 'background' ) ?>;
        color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
      }
      .navbar.navbar-inverse .brand,
      .navbar.navbar-inverse .nav > li > a {
        color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
      }
      .navbar.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret {
          border-bottom-color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
          border-top-color: <?= $almagesq->getNavbarStyle( 'color' ) ?>;
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
            <?php if ( is_array( $almagesq->themes ) && count( $almagesq->themes ) > 1 ): ?>
              <div class="navbar-text pull-right">
                <ul class="nav">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Themes <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <?php foreach ( array_keys( $almagesq->themes ) as $theme ): ?> 
                        <li><a href="<?= $almagesq->getHttpQuery( NULL, $theme ) ?>"><?= $theme ?></a></li>
                      <?php endforeach; ?> 
                    </ul>
                  </li>
                </ul>
              </div>
            <?php endif; ?>
            <ul class="nav">
              <?php 
                foreach ( $almagesq->menus as $menu => $submenus ): 
                  $isMenuActive = ( $almagesq->currentMenus[ 0 ] == $menu );
                  if ( empty( $submenus ) || Almagesq::hasPatterns( $submenus ) ): 
              ?>
                  <li class="<?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="<?= $almagesq->getHttpQuery( array( $menu ) ) ?>"><?= ucfirst( $menu ) ?></a>
                  </li>
                <?php else: ?>
                  <li class="dropdown <?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="<?= $almagesq->getHttpQuery( array( $menu ) ) ?>" class="dropdown-toggle" data-toggle="dropdown">
                      <?= ucfirst( $menu ) ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                      <?php 
                        foreach ( array_keys( $submenus ) as $submenu ): 
                        $isSubmenuActive = ( $isMenuActive && $almagesq->currentMenus[ 1 ] == $submenu );
                      ?> 
                        <li class="<?= ( $isSubmenuActive ? 'active' : '' )?>">
                          <a href="<?= $almagesq->getHttpQuery( array( $menu, $submenu ) ) ?>"><?= ucfirst( $submenu ) ?></a>
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
          $patternName = substr( $pattern, 0, strpos( $pattern, '.' ) );
          if ( strpos( $patternName, '-' ) !== FALSE ) {
            $prefix = substr( $patternName, 0, strpos( $patternName, '-' ) );
            if ( is_numeric( $prefix ) ) {
              $patternName = substr( $patternName, strlen( $prefix ) + 1 );
            }
          }
          $patternHumanName = Almagesq::FileHumanName( $patternName )
      ?>
        <div class="pattern" id="<?= $patternName ?>">
          <div class="pattern__title">
            <a href="#<?= $patternName ?>" class="pattern__link pattern__link--anchor">#</a>
            <?= $patternHumanName ?>
            <a href="#<?= $patternName ?>_code" class="pattern__link pattern__link--code pull-right">Show the source code</a>
          </div>
          <div class="pattern__demo">
            <iframe src="iframe.php<?= $almagesq->getHttpQuery( ) ?>&amp;pattern=<?= $pattern ?>">
            </iframe>
          </div>
          <div class="pattern__code" id="<?= $patternName ?>_code">
            <pre><code><?= htmlentities( $almagesq->getPatternHtml( $pattern ) ) ?></code></pre>
          </div>
        </div>
      <?php  
        endforeach;
      ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script>
      $(function( ){
        $( '.pattern__code' ).hide( );
        $( '.pattern__link--code' ).click( function( ) {
          if ( $( this.href.substring( this.href.indexOf('#') ) ).toggle( ).is(':hidden') ) {
            $( this ).html( 'Show the source code' );
          } else {
            $( this ).html( 'Hide the source code' );
          }
          return false;
        });
        $( 'iframe' ).each( function( ) {
          this.onload = function( ) {
            this.style.height = this.contentWindow.document.body.clientHeight + 'px';
          }
        });
      });
    </script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>