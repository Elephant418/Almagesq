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
          <a href="index.php" class="brand <?= ( $current_menu === NULL ? 'active' : '' )?>">Style Guide</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?php 
                foreach ( $menus as $menu => $submenus ): 
                  if ( empty( $submenus ) ): 
              ?>
                  <li class="<?= ( $current_menu == $menu ? 'active' : '' )?>">
                    <a href="?menu[]=menu"><?= ucfirst( $menu ) ?></a>
                  </li>
                <?php else: ?>
                  <li class="dropdown <?= ( $current_menu == $menu ? 'active' : '' )?>">
                    <a href="?menu[]=<?= $menu ?>" class="dropdown-toggle" data-toggle="dropdown">
                      <?= ucfirst( $menu ) ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                      <?php foreach ( array_keys( $submenus ) as $submenu ): ?> 
                        <li class="<?= ( $current_submenu == $submenu ? 'active' : '' )?>">
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
        <h1>Almagesq</h1>
        <?= var_dump( $patterns ); ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>