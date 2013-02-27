<?php

require_once( __DIR__ . '/UFile.php' );

// Intialization
$menus = UFIle::folderTree( __DIR__ . '/pattern', '*.html', 2, UFile::FILE_FLAG );
$patterns =array( );
$current_menu = NULL;
$current_submenu = NULL;

// Override if a menu is requested
if ( isset( $_GET[ 'menu' ] ) && is_array( $_GET[ 'menu' ] ) ) {
	$current_menus = array_values( $_GET[ 'menu' ] );
	$patterns = $menus;
	foreach ( $current_menus as $current_menu ) {
		$patterns =& $patterns[ $current_menu ]; 
	}
	if ( isset( $current_menus[ 0 ] ) ) {
		$current_menu = $current_menus[ 0 ];
	}
	if ( isset( $current_menus[ 1 ] ) ) {
		$current_submenu = $current_menus[ 1 ];
	}
	unset( $current_menus );
}

// Render
require( __DIR__ . '/template.php' );


?>