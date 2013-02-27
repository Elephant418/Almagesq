<?php

class UFile {

	const INFINITE_DEPTH = -1;
	const FILE_FLAG = 0;

	public static function fileTree( $path, $pattern = '*', $depth = 1, $flags = 0, $level = 0 ) {
		$tree  = static::folderTree( $pattern, $path, $depth, $flags, $level );
		$files = static::fileList( $pattern, $path, $flags );

		return array_merge( $tree, $files );
	}

	public static function folderTree( $path, $pattern = '*', $depth = 1, $flags = GLOB_ONLYDIR, $level = 0 ) {
		$tree = array( );
		$folders = static::folderList( $path );
		if ( ! empty( $folders ) && ( $depth == static::INFINITE_DEPTH || $level < $depth ) ) {
			$level++;
			foreach ( $folders as $folder ) {
				$tree[ basename( $folder ) ] = static::fileTree( $pattern, $folder, $depth, $flags, $level );
			}	
		}

		return $tree;
	}

	public static function folderList( $path ) {
		return glob( $path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR|GLOB_NOSORT );
	}

	public static function fileList( $path, $pattern = '*', $flags = 0 ) {
		return glob( $path . DIRECTORY_SEPARATOR . $pattern, $flags );
	}
}

?>