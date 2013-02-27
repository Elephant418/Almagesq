<?php

class Almagesq {


	/*************************************************************************
	  CONSTANTS
	 *************************************************************************/
	const MAX_DEPTH = 2;


	/*************************************************************************
	  ATTRIBUTES		   
	 *************************************************************************/
	public $patternPath;
	public $menus = array( );
	public $currentMenus = array( );
	public $patterns = array( );
	public $currentPattern;
	public $settings;


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
		if ( isset( $this->settings[ 'title' ] ) ) {
			$title = $this->settings[ 'title' ];
		}
		return $title;
	}
	public function getStyles( ) {
		$styles = array( );
		if ( isset( $this->settings[ 'styles' ] ) ) {
			$styles = $this->settings[ 'styles' ];
			if ( ! is_array( $styles ) ) {
				$styles = array( $styles );
			}
		}
		return $styles;
	}
	public function getScripts( ) {
		$scripts = array( );
		if ( isset( $this->settings[ 'scripts' ] ) ) {
			$scripts = $this->settings[ 'scripts' ];
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
		$this->settings = $this->getSettings( );
		$this->patternPath = $this->getPatternPath( );
		$this->menus = UFIle::folderTree( $this->patternPath, '*.html', static::MAX_DEPTH, UFile::FILE_FLAG );
		$this->currentMenus = $this->getCurrentMenus( );
		$this->patterns = $this->getPatterns( );
		$this->currentPattern = $this->getCurrentPattern( );
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function getSettings( ) {
		$confDir = __DIR__ . '/../settings';
		if ( ! $settingsFile = realpath( $confDir . '/user.ini' ) ) {
			if ( ! $settingsFile = realpath( $confDir . '/default.ini' ) ) {
				echo 'Settings file not found :\'(';
				die;
			}
		}
		$settings = parse_ini_file( $settingsFile );
		return $settings;
	}

	protected function getPatternPath( ) {
		$basePath = __DIR__ . '/..';
		$patternPath = $basePath . '/pattern';
		if ( isset( $this->settings[ 'pattern_path' ] ) ) {
			$patternPath = $basePath . '/' . $this->settings[ 'pattern_path' ];
		}
		if ( ! $patternPath = realpath( $patternPath ) ) {
			echo 'Pattern folder not found :\'(';
			die;
		}
		return $patternPath;
	}

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