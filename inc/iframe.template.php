<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Almagesq - Your pattern style guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach ( $almagesq->getStyles( ) as $style ) : ?>
        <?php if ( ! empty( $style[ 'condition' ] ) ) : ?>
            <!--[if <?= $style[ 'condition' ] ?>]>
        <?php endif; ?>
            <link rel="stylesheet" href="<?= $style[ 'url' ] ?>">
        <?php if ( ! empty( $style[ 'condition' ] ) ) : ?>
            <![endif]-->
        <?php endif; ?>
    <?php endforeach; ?>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            border-top: 1px solid transparent;
            background: <?= $almagesq->getPatternBackground( ) ?>
        }
    </style>
  </head>
  <body>
    <div>
        <?= $almagesq->getCurrentPatternHtml( ) ?>
    </div>
    <?php foreach ( $almagesq->getScripts( ) as $script ) : ?>
    	<script src="<?= $script ?>"></script>
    <?php endforeach; ?>
    <script>
        var forms = document.getElementsByTagName( 'form' );
        if ( forms ) {
            for ( var i in forms ) {
                forms[ i ].onsubmit = function() {
                    return false;
                }
            }
        }
    </script>
  </body>
</html>