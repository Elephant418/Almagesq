<?php

require_once( __DIR__ . '/inc/common.php' );

// Render
if ( isset( $_GET[ 'iframe' ] ) ) {
	require( __DIR__ . '/inc/iframe.template.php' );
} else {
	require( __DIR__ . '/inc/index.template.php' );
}

?>