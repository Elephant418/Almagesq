<?php

class Almagesq {


	/*************************************************************************
	  CONSTANTS
	 *************************************************************************/
	const MAX_DEPTH = 2;
	const SETTINGS_PATH = '/../settings';
	const USER_SETTINGS_PATH = '/../settings/themes';


	/*************************************************************************
	  ATTRIBUTES		   
	 *************************************************************************/
	public $patternPath;
	public $menus = array( );
	public $currentMenus = array( );
	public $patterns = array( );
	public $currentPattern;
	public $themes = [ ];
	public $currentTheme;
	public $settings;


	/*************************************************************************
	  STATIC METHODS				   
	 *************************************************************************/
	public static function hasPatterns( $menu ) {
		return ( is_array( $menu ) && ! empty( $menu ) && is_numeric( current( array_keys( $menu ) ) ) );
	}
	public static function FileHumanName( $file ) {
		$fileName = static::FileName( $file );
		if ( strpos( $fileName, '-' ) !== FALSE ) {
			$prefix = substr( $fileName, 0, strpos( $fileName, '-' ) );
			if ( is_numeric( $prefix ) ) {
			  $fileName = substr( $fileName, strlen( $prefix ) + 1 );
			}
		}
		$fileName = ucfirst( str_replace( '_', ' ', $fileName ) );
  		return $fileName;
	}
	public static function FileName( $file ) {
		$fileName = basename( $file );
		if ( $pos = strpos( $file, '.' ) ) {
			$fileName = substr( $fileName, 0, $pos );
		}
		return $fileName;
	}


	/*************************************************************************
	  PUBLIC METHODS				   
	 *************************************************************************/
	public function getPatternPath( $pattern ) {
		$path = $this->patternPath . '/' . implode( $this->currentMenus, '/' ) . '/' . $pattern;
		return realpath( $path );
	}
	public function getPatternHtml( $pattern ) {
		if ( $path = $this->getPatternPath( $pattern ) ) {
			return file_get_contents( $path );
		}
	}
	public function getCurrentPatternHtml( ) {
		return $this->getPatternHtml( $this->currentPattern );
	}
	public function getTitle( ) {
		$title = 'Style Guide';
		if ( isset( $this->settings[ 'nav-bar' ][ 'title' ] ) ) {
			$title = $this->settings[ 'nav-bar' ][ 'title' ];
		}
		return $title;
	}
	public function getNavbarStyle( $property ) {
		$style = '';
		$styles = array( );
		if ( isset( $this->settings[ 'nav-bar' ][ 'style' ] ) ) {
			$styles = $this->settings[ 'nav-bar' ][ 'style' ];
		}
		if ( is_array( $styles ) && isset( $styles[ $property ] ) ) {
			$style = $styles[ $property ];
		}
		return $style;
	}
	public function getStyles( ) {
		$styles = array( );
		if ( isset( $this->settings[ 'pattern' ][ 'styles' ] ) ) {
			$styles = $this->settings[ 'pattern' ][ 'styles' ];
			if ( ! is_array( $styles ) ) {
				$styles = array( $styles );
			}
		}
		return $styles;
	}
	public function getScripts( ) {
		$scripts = array( );
		if ( isset( $this->settings[ 'pattern' ][ 'scripts' ] ) ) {
			$scripts = $this->settings[ 'pattern' ][ 'scripts' ];
			if ( ! is_array( $scripts ) ) {
				$scripts = array( $scripts );
			}
		}
		return $scripts;
	}
	public function getMenuHttpQuery( $menus = NULL ) {
		$compiledMenus = $this->currentMenus;
		if ( is_array( $menus ) ) {
			if ( isset( $menus[ 0 ] ) ) {
				$compiledMenus[ 0 ] = $menus[ 0 ];
				unset( $compiledMenus[ 1 ] );
			}
			if ( isset( $menus[ 1 ] ) ) {
				$compiledMenus[ 1 ] = $menus[ 1 ];
			}
			if ( ! isset( $menus[ 0 ] ) && ! isset( $menus[ 1 ] ) ) {
				$compiledMenus = array( );
			}
		}
		$query = '';
		if ( isset( $compiledMenus[ 0 ] ) ) {
			$query .= '&amp;menu[]=' . $compiledMenus[ 0 ];
			if ( isset( $compiledMenus[ 1 ] ) ) {
				$query .= '&amp;menu[]=' . $compiledMenus[ 1 ];
			}
		}
		return $query;
	}
	public function getThemeHttpQuery( $theme = NULL ) {
		if ( is_null( $theme ) ) {
			$theme = $this->currentTheme;
		}
		return 'theme=' . $theme;
	}
	public function getHttpQuery( $menus = NULL, $theme = NULL ) {
		return '?' . $this->getThemeHttpQuery( $theme ) . $this->getMenuHttpQuery( $menus );
	}


	/*************************************************************************
	  CONSTRUCTOR METHODS				   
	 *************************************************************************/
	public function __construct( ) {
		$this->themes = $this->initThemes( );
		$this->settings = $this->initSettings( );
		$this->patternPath = $this->initPatternPath( );
		$this->menus = UFIle::folderTree( $this->patternPath, '*.html', static::MAX_DEPTH, UFile::FILE_FLAG );
		$this->currentMenus = $this->initCurrentMenus( );
		$this->patterns = $this->initPatterns( );
		$this->currentPattern = $this->initCurrentPattern( );
	}
	protected function initThemes( ) {
		$themes = \UFile::fileList( __DIR__ . static::USER_SETTINGS_PATH, '*.ini' );
		if ( empty( $themes ) ) {
			if ( ! $themes = realpath( __DIR__ . static::SETTINGS_PATH . '/default.ini' ) ) {
				echo 'Settings file not found :\'(';
				die;
			}
		} else {
			foreach( $themes as $key => $theme ) {
				unset( $themes[ $key ] );
				$themes[ static::FileName( $theme ) ] = $theme;
			}
		}
		return $themes;
	}
	protected function initSettings( ) {
		if ( is_array( $this->themes ) ) {
			if ( isset( $_GET[ 'theme' ] ) && $this->isThemeExist( $_GET[ 'theme' ] ) ) {
				$this->setCurrentTheme( $_GET[ 'theme' ] );
			}
			if ( ! $this->issetCurrentTheme( ) ) {
				$this->setDefaultCurrentTheme( );
			}
			$settings = parse_ini_file( $this->themes[ $this->currentTheme ], TRUE );
		} else {
			$settings = parse_ini_file( $this->themes );
		}
		return $settings;
	}
	protected function initPatternPath( ) {
		$basePath = __DIR__ . '/..';
		$patternPath = $basePath . '/pattern';
		if ( isset( $this->settings[ 'pattern' ][ 'path' ] ) ) {
			$patternPath = $basePath . '/' . $this->settings[ 'pattern' ][ 'path' ];
		}
		if ( ! $patternPath = realpath( $patternPath ) ) {
			echo 'Pattern folder not found :\'(';
			die;
		}
		return $patternPath;
	}
	protected function initCurrentMenus( ) {
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
	protected function initPatterns( ) {
		$patterns = $this->getSubmenu( $this->currentMenus );
		if ( ! static::hasPatterns( $patterns ) ) {
			$patterns = array( );
		}
		return $patterns;
	}
	protected function initCurrentPattern( ) {
		if ( ! empty( $this->patterns ) && isset( $_GET[ 'pattern' ] ) && in_array( $_GET[ 'pattern' ], $this->patterns ) ) {
			return $_GET[ 'pattern' ];
		}
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function isThemeExist( $theme ) {
		return in_array( $theme, array_keys( $this->themes ) );
	}
	protected function setCurrentTheme( $theme ) {
		$this->currentTheme = $theme;
	}
	protected function issetCurrentTheme( ) {
		return ( isset( $this->currentTheme ) );
	}
	protected function setDefaultCurrentTheme( ) {
		$this->setCurrentTheme( key( $this->themes ) );
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

}

?>