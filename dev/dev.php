<?php
/**
 * Plugin Name: DEV
 * Description: enable dev mode
 * Version: 1.0
 * Author: Vu Nam Hung
 */

define( 'DEV_MODE', WP_DEBUG ? true : false );
define( 'BS_PORT', 3000 );
define( 'BASELINE_GRID', false );

require_once __DIR__ . '/export.php';

class Dev {
	public function __construct() {
		add_filter( 'core/register/scripts/args', [ $this, 'disable_have_min' ] );

		add_filter( 'body_class', [ $this, 'body_class' ] );

		add_filter( 'auth_cookie_expiration', [ $this, 'wp_never_log_out' ] );

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_bs_script' ], 999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_bs_script' ], 999 );
	}

	public function disable_have_min( $args ) {
		if ( DEV_MODE ) {
			$args['have_min'] = false;
		}

		return $args;
	}

	public function body_class( $classes ) {
		if ( BASELINE_GRID ) {
			$classes[] = 'baseline';
		}

		return $classes;
	}

	public function wp_never_log_out( $expirein ) {
		return 1421150815; // 40+ years in seconds
	}

	public function enqueue_bs_script() {
		if ( ! DEV_MODE ) {
			return;
		}

		//phpcs:disable
		if ( isset( $_GET['port'] ) ) {
			$port = (int) sanitize_text_field( $_GET['port'] );
		} elseif ( defined( 'BS_PORT' ) ) {
			$port = BS_PORT;
		} else {
			$port = 3000;
		}

		$host          = wp_parse_url( get_stylesheet_directory_uri() )['host'];
		$url           = sprintf( 'http://%s:%s/browser-sync/browser-sync-client.js', $host, $port );
		$response_code = wp_remote_retrieve_response_code( wp_remote_head( $url ) );

		if ( $response_code === 200 ) {
			wp_enqueue_script( '__bs_script__', $url, [], false, true );
		}
	}
}

new Dev();
