<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Almagesq - Your pattern style guide</title>
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
          <a href="index.php" class="brand <?= ( $almagesq->current_menus[ 0 ] === NULL ? 'active' : '' )?>">Style Guide</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?php 
                foreach ( $almagesq->menus as $menu => $submenus ): 
                  $is_menu_active = ( $almagesq->current_menus[ 0 ] == $menu );
                  if ( empty( $submenus ) || Almagesq::has_patterns( $submenus ) ): 
              ?>
                  <li class="<?= ( $is_menu_active ? 'active' : '' )?>">
                    <a href="?menu[]=<?= $menu ?>"><?= ucfirst( $menu ) ?></a>
                  </li>
                <?php else: ?>
                  <li class="dropdown <?= ( $is_menu_active ? 'active' : '' )?>">
                    <a href="?menu[]=<?= $menu ?>" class="dropdown-toggle" data-toggle="dropdown">
                      <?= ucfirst( $menu ) ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                      <?php 
                        foreach ( array_keys( $submenus ) as $submenu ): 
                        $is_submenu_active = ( $is_menu_active && $almagesq->current_menus[ 1 ] == $submenu );
                      ?> 
                        <li class="<?= ( $is_submenu_active ? 'active' : '' )?>">
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
      ?>
        <iframe src="iframe.php?menu[]=<?= $almagesq->current_menus[ 0 ] ?>&amp;menu[]=<?= $almagesq->current_menus[ 1 ] ?>&amp;pattern=<?= $pattern ?>">
        </iframe>
      <?php  
        endforeach;
      ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>