<?php

class Almagesq {


	/*************************************************************************
	  CONSTANTS
	 *************************************************************************/
	const MAX_DEPTH = 2;
	const PATTERN_FOLDER = 'pattern';


	/*************************************************************************
	  ATTRIBUTES		   
	 *************************************************************************/
	public $pattern_path;
	public $menus = array( );
	public $current_menus = array( );
	public $patterns = array( );
	public $current_pattern;


	/*************************************************************************
	  STATIC METHODS				   
	 *************************************************************************/
	public static function has_patterns( $menu ) {
		return ( is_array( $menu ) && ! empty( $menu ) && is_numeric( current( array_keys( $menu ) ) ) );
	}


	/*************************************************************************
	  PUBLIC METHODS				   
	 *************************************************************************/
	public function get_current_pattern_path( ) {
		$path = $this->pattern_path . '/' . implode( $this->current_menus, '/' ) . '/' . $this->current_pattern;
		return realpath( $path );
	}
	public function get_current_pattern_html( ) {
		if ( $path = $this->get_current_pattern_path( ) ) {
			return file_get_contents( $path );
		}
	}


	/*************************************************************************
	  CONSTRUCTOR METHODS				   
	 *************************************************************************/
	public function __construct( ) {
		$this->pattern_path = __DIR__ . '/' . static::PATTERN_FOLDER;
		$this->menus = UFIle::folderTree( $this->pattern_path, '*.html', static::MAX_DEPTH, UFile::FILE_FLAG );
		$this->current_menus = $this->get_current_menus( );
		$this->patterns = $this->get_patterns( );
		$this->current_pattern = $this->get_current_pattern( );
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function get_current_menus( ) {
		$current_menus = array( );
		if ( isset( $_GET[ 'menu' ] ) && is_array( $_GET[ 'menu' ] ) ) {
			$current_menus = array_values( $_GET[ 'menu' ] );
		}
		$menus = $this->menus;
		foreach ( $current_menus as $key => $current_menu ) {
			if ( empty( $current_menu ) || ! in_array( $current_menu, array_keys( $menus ) ) ) {
				unset( $current_menus[ $key ] );
				$menus = array( );
			} else {
				$menus =& $menus[ $current_menu ];
			}
		}
		$current_menus = array_pad( $current_menus, static::MAX_DEPTH, NULL );
		return $current_menus;
	}

	protected function get_submenu( $keys ) {
		$submenus = $this->menus;
		foreach ( $keys as $menu ) {
			if ( is_null( $menu ) ) {
				break;
			}
			$submenus =& $submenus[ $menu ]; 
		}
		return $submenus;
	}

	protected function get_patterns( ) {
		$patterns = $this->get_submenu( $this->current_menus );
		if ( ! static::has_patterns( $patterns ) ) {
			$patterns = array( );
		}
		return $patterns;
	}

	protected function get_current_pattern( ) {
		if ( ! empty( $this->patterns ) && isset( $_GET[ 'pattern' ] ) && in_array( $_GET[ 'pattern' ], $this->patterns ) ) {
			return $_GET[ 'pattern' ];
		}
	}
}

?>