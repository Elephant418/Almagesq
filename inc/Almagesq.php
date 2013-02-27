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
	public $patternPath;
	public $menus = array( );
	public $currentMenus = array( );
	public $patterns = array( );
	public $currentPattern;
	public $resources;


	/*************************************************************************
	  STATIC METHODS				   
	 *************************************************************************/
	public static function hasPatterns( $menu ) {
		return ( is_array( $menu ) && ! empty( $menu ) && is_numeric( current( array_keys( $menu ) ) ) );
	}


	/*************************************************************************
	  PUBLIC METHODS				   
	 *************************************************************************/
	public function getCurrentPatternPath( ) {
		$path = $this->patternPath . '/' . implode( $this->currentMenus, '/' ) . '/' . $this->currentPattern;
		return realpath( $path );
	}
	public function getCurrentPatternHtml( ) {
		if ( $path = $this->getCurrentPatternPath( ) ) {
			return file_get_contents( $path );
		}
	}
	public function getTitle( ) {
		$title = 'Style Guide';
		if ( isset( $this->resources[ 'title' ] ) ) {
			$title = $this->resources[ 'title' ];
		}
		return $title;
	}
	public function getStyles( ) {
		$styles = array( );
		if ( isset( $this->resources[ 'styles' ] ) ) {
			$styles = $this->resources[ 'styles' ];
			if ( ! is_array( $styles ) ) {
				$styles = array( $styles );
			}
		}
		return $styles;
	}
	public function getScripts( ) {
		$scripts = array( );
		if ( isset( $this->resources[ 'scripts' ] ) ) {
			$scripts = $this->resources[ 'scripts' ];
			if ( ! is_array( $scripts ) ) {
				$scripts = array( $scripts );
			}
		}
		return $scripts;
	}


	/*************************************************************************
	  CONSTRUCTOR METHODS				   
	 *************************************************************************/
	public function __construct( ) {
		$this->patternPath = __DIR__ . '/../' . static::PATTERN_FOLDER;
		$this->menus = UFIle::folderTree( $this->patternPath, '*.html', static::MAX_DEPTH, UFile::FILE_FLAG );
		$this->currentMenus = $this->getCurrentMenus( );
		$this->patterns = $this->getPatterns( );
		$this->currentPattern = $this->getCurrentPattern( );
		$this->resources = parse_ini_file( __DIR__ . '/../conf/resource.ini' );
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function getCurrentMenus( ) {
		$currentMenus = array( );
		if ( isset( $_GET[ 'menu' ] ) && is_array( $_GET[ 'menu' ] ) ) {
			$currentMenus = array_values( $_GET[ 'menu' ] );
		}
		$menus = $this->menus;
		foreach ( $currentMenus as $key => $currentMenu ) {
			if ( empty( $currentMenu ) || ! in_array( $currentMenu, array_keys( $menus ) ) ) {
				unset( $currentMenus[ $key ] );
				$menus = array( );
			} else {
				$menus =& $menus[ $currentMenu ];
			}
		}
		$currentMenus = array_pad( $currentMenus, static::MAX_DEPTH, NULL );
		return $currentMenus;
	}

	protected function getSubmenu( $keys ) {
		$submenus = $this->menus;
		foreach ( $keys as $menu ) {
			if ( is_null( $menu ) ) {
				break;
			}
			$submenus =& $submenus[ $menu ]; 
		}
		return $submenus;
	}

	protected function getPatterns( ) {
		$patterns = $this->getSubmenu( $this->currentMenus );
		if ( ! static::hasPatterns( $patterns ) ) {
			$patterns = array( );
		}
		return $patterns;
	}

	protected function getCurrentPattern( ) {
		if ( ! empty( $this->patterns ) && isset( $_GET[ 'pattern' ] ) && in_array( $_GET[ 'pattern' ], $this->patterns ) ) {
			return $_GET[ 'pattern' ];
		}
	}
}

?>