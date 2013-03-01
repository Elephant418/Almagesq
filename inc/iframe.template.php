<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Almagesq - Your pattern style guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    	body {
    		margin: 0;
            overflow: hidden;
            border-top: 1px solid transparent;
    	}
    </style>
    <?php foreach ( $almagesq->getStyles( ) as $style ) : ?>
    	<link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach; ?>
  </head>
  <body>
    <?= $almagesq->getCurrentPatternHtml( ) ?>
    <?php foreach ( $almagesq->getScripts( ) as $script ) : ?>
    	<script src="<?= $script ?>"></script>
    <?php endforeach; ?>
  </body>
</html>