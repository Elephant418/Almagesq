<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= $almagesq->getTitle( ) ?> Style Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="css/app.css">
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
          <a href="index.php" class="brand <?= ( $almagesq->currentMenus[ 0 ] === NULL ? 'active' : '' )?>"><?= $almagesq->getTitle( ) ?></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?php 
                foreach ( $almagesq->menus as $menu => $submenus ): 
                  $isMenuActive = ( $almagesq->currentMenus[ 0 ] == $menu );
                  if ( empty( $submenus ) || Almagesq::hasPatterns( $submenus ) ): 
              ?>
                  <li class="<?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="?menu[]=<?= $menu ?>"><?= ucfirst( $menu ) ?></a>
                  </li>
                <?php else: ?>
                  <li class="dropdown <?= ( $isMenuActive ? 'active' : '' )?>">
                    <a href="?menu[]=<?= $menu ?>" class="dropdown-toggle" data-toggle="dropdown">
                      <?= ucfirst( $menu ) ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                      <?php 
                        foreach ( array_keys( $submenus ) as $submenu ): 
                        $isSubmenuActive = ( $isMenuActive && $almagesq->currentMenus[ 1 ] == $submenu );
                      ?> 
                        <li class="<?= ( $isSubmenuActive ? 'active' : '' )?>">
                          <a href="?menu[]=<?= $menu ?>&amp;menu[]=<?= $submenu ?>"><?= ucfirst( $submenu ) ?></a>
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
          $patternHumanName = ucfirst( str_replace( '_', ' ', $patternName ) );
      ?>
        <div class="pattern" id="<?= $patternName ?>">
          <div class="pattern__title">
            <a href="#<?= $patternName ?>" class="pattern__link">#</a>
            <?= $patternHumanName ?>
          </div>
          <div class="pattern__demo">
            <iframe src="iframe.php?menu[]=<?= $almagesq->currentMenus[ 0 ] ?>&amp;menu[]=<?= $almagesq->currentMenus[ 1 ] ?>&amp;pattern=<?= $pattern ?>">
            </iframe>
          </div>
          <div class="pattern_code"></div>
        </div>
      <?php  
        endforeach;
      ?>
    </div>
    <script>
      var iframes = document.getElementsByTagName( 'iframe' );
      for ( var i in iframes ) {
        iframes[ i ].onload = function( ) {
          console.dir( this.contentWindow );
          this.style.height = this.contentWindow.document.body.clientHeight + 'px';
        }
      }
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>