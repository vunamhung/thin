<?php

namespace thin;

use vnh\Allowed_HTML;
use vnh\contracts\Bootable;
use vnh\Register_Widget_Areas;
use vnh\Register_Widgets;

use const vnh\THEME_VERSION;

class Theme implements Bootable {
	use Theme_Supports;

	public function __construct() {
		$this->load();
		$this->boot();
	}

	public function load() {
		$services = Container::instance()->services;

		$services->get( Allowed_HTML::class )->boot();
		$services->get( Customizer::class )->boot();
		$services->get( Comments::class )->boot();
		$services->get( ACF::class )->boot();
		$services->get( Menu::class )->boot();
		$services->get( Social_Menu::class )->boot();
		$services->get( Register_Widgets::class )->boot();
		$services->get( Register_Widget_Areas::class )->boot();
	}

	public function boot() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'wp_head', [ $this, 'pingback_header' ] );
		add_filter( 'wp_nav_menu_objects', [ $this, 'nav_menu_objects' ], 10, 2 );
	}

	public function setup() {
		load_theme_textdomain( 'vnh_textdomain', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( [ 'menu-1' => esc_html__( 'Primary', 'vnh_textdomain' ) ] );
		add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'wp-block-styles' ); // Add support for Block Styles.
		add_theme_support( 'align-wide' ); // Add support for full and wide align images.

		add_theme_support( 'editor-styles' ); // Add support for editor styles.
		add_editor_style( 'style-editor.css' ); // Enqueue editor styles.

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support(
			'custom-logo',
			[
				'flex-width' => true,
				'flex-height' => true,
			]
		);

		// Disables the block editor
		add_filter( 'use_widgets_block_editor', '__return_false', 10 );
		add_filter( 'use_block_editor_for_post_type', '__return_false', 10, 2 );
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
	}

	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'thin_content_width', 640 );
	}

	public function scripts() {
		wp_enqueue_style( 'thin-style', get_stylesheet_uri(), [], THEME_VERSION );

		wp_enqueue_script( 'thin-main', get_template_directory_uri() . '/js/main.js', [], THEME_VERSION, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	public function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function nav_menu_objects( $items, $args ) {
		foreach ( $items as &$item ) {
			$enable_mega_menu = get_field( 'enable_mega_menu', $item );
			if ( $enable_mega_menu ) {
				$item->classes[] = 'mega-menu';
			}
		}

		return $items;
	}
}
