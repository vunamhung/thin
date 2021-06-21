<?php

namespace thin;

/**
 * Get theme info
 *
 * @param string $header
 *
 * @return array|false|string
 */
function get_theme_info( $header ) {
	return wp_get_theme( get_option( 'template' ) )->get( $header );
}

define( __NAMESPACE__ . '\THEME_SLUG', get_option( 'template' ) );
define( __NAMESPACE__ . '\THEME_NAME', get_theme_info( 'Name' ) );
define( __NAMESPACE__ . '\THEME_VERSION', get_theme_info( 'Version' ) );
define( __NAMESPACE__ . '\THEME_TEXTDOMAIN', get_theme_info( 'TextDomain' ) );
define( __NAMESPACE__ . '\THEME_DESCRIPTION', get_theme_info( 'Description' ) );
define( __NAMESPACE__ . '\THEME_AUTHOR', get_theme_info( 'Author' ) );
define( __NAMESPACE__ . '\THEME_AUTHOR_URI', get_theme_info( 'AuthorURI' ) );
define( __NAMESPACE__ . '\THEME_DOCUMENT_URI', get_file_data( get_theme_file_path( 'style.css' ), [ 'Document URI' ] )[0] );
