<?php

if ( empty( $GLOBALS['wp_filesystem'] ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'wp_create_nonce' ) ) {
	require_once ABSPATH . 'wp-includes/pluggable.php';
}
